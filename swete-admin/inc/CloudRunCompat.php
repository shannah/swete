<?php
/**
 * Cloud Run compatibility utilities.
 *
 * Provides file write and mutex operations that work on both local
 * filesystems and GCS FUSE volume mounts (which do not support flock/LOCK_EX).
 *
 * GCS FUSE uses atomic object replacement, so write-to-temp-then-rename
 * is the safe pattern. rename() on GCS FUSE is atomic at the object level.
 */
class CloudRunCompat {

    /**
     * Whether we are running on Cloud Run with GCS FUSE.
     * Auto-detected from SWETE_DATA_DIR env var, or can be set manually.
     */
    private static $gcsFuseMode = null;

    public static function isGcsFuse() {
        if (self::$gcsFuseMode === null) {
            self::$gcsFuseMode = !empty(getenv('SWETE_DATA_DIR'));
        }
        return self::$gcsFuseMode;
    }

    /**
     * Safe file_put_contents that works on GCS FUSE.
     * On local filesystem, uses LOCK_EX as before.
     * On GCS FUSE, uses write-to-temp-then-rename for atomicity.
     *
     * @param string $path Target file path
     * @param string $data Data to write
     * @return bool|int Number of bytes written, or false on failure
     */
    public static function filePutContents($path, $data) {
        if (self::isGcsFuse()) {
            $dir = dirname($path);
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            $tmpFile = $path . '.tmp.' . getmypid() . '.' . mt_rand();
            $bytes = file_put_contents($tmpFile, $data);
            if ($bytes === false) {
                @unlink($tmpFile);
                return false;
            }
            if (!rename($tmpFile, $path)) {
                @unlink($tmpFile);
                return false;
            }
            return $bytes;
        } else {
            return file_put_contents($path, $data, LOCK_EX);
        }
    }

    /**
     * Database-backed mutex for Cloud Run (GCS FUSE has no flock support).
     * Falls back to directory-based mutex on local filesystem.
     *
     * @param string $name Mutex name
     * @param int $timeoutSeconds Max wait time
     * @return bool|resource Returns mutex handle on success, false on timeout
     */
    public static function acquireMutex($name, $timeoutSeconds = 15) {
        if (self::isGcsFuse()) {
            // On Cloud Run, use MySQL GET_LOCK for distributed mutex
            return self::acquireDbMutex($name, $timeoutSeconds);
        } else {
            // On local, use mkdir-based mutex (existing behavior)
            return self::acquireDirMutex($name, $timeoutSeconds);
        }
    }

    /**
     * Release a previously acquired mutex.
     *
     * @param string $name Mutex name
     * @param mixed $handle Mutex handle returned by acquireMutex
     */
    public static function releaseMutex($name, $handle = null) {
        if (self::isGcsFuse()) {
            self::releaseDbMutex($name);
        } else {
            self::releaseDirMutex($handle);
        }
    }

    private static function acquireDbMutex($name, $timeoutSeconds) {
        $confDbIni = 'conf.db.ini';
        if (!is_readable($confDbIni)) {
            $confDbIni = 'conf.db.ini.php';
        }
        $info = parse_ini_file($confDbIni, true);
        $db = new mysqli(
            $info['_database']['host'],
            $info['_database']['user'],
            $info['_database']['password'],
            $info['_database']['name']
        );
        if ($db->connect_error) {
            error_log("CloudRunCompat: Failed to connect for mutex: " . $db->connect_error);
            return false;
        }
        $safeName = $db->real_escape_string('swete_mutex_' . $name);
        $result = $db->query("SELECT GET_LOCK('{$safeName}', {$timeoutSeconds})");
        if ($result) {
            $row = $result->fetch_row();
            if ($row[0] == 1) {
                // Store the connection so we can release later
                self::$dbMutexConnections[$name] = $db;
                return true;
            }
        }
        $db->close();
        return false;
    }

    private static $dbMutexConnections = array();

    private static function releaseDbMutex($name) {
        if (isset(self::$dbMutexConnections[$name])) {
            $db = self::$dbMutexConnections[$name];
            $safeName = $db->real_escape_string('swete_mutex_' . $name);
            $db->query("SELECT RELEASE_LOCK('{$safeName}')");
            $db->close();
            unset(self::$dbMutexConnections[$name]);
        }
    }

    private static function acquireDirMutex($name, $timeoutSeconds) {
        $dir = is_writable(sys_get_temp_dir()) ? sys_get_temp_dir() : 'templates_c';
        $mutexPath = $dir . '/' . basename($name) . '.mutex';
        $elapsed = 0;
        while (!@mkdir($mutexPath, 0777)) {
            if ($elapsed >= $timeoutSeconds) {
                return false;
            }
            sleep(2);
            $elapsed += 2;
        }
        return $mutexPath;
    }

    private static function releaseDirMutex($handle) {
        if ($handle && is_string($handle)) {
            @rmdir($handle);
        }
    }
}
