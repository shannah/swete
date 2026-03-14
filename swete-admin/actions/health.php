<?php
/**
 * Health check endpoint for Cloud Run.
 * Returns 200 if the application is healthy, 503 otherwise.
 * Accessed via: /swete-admin/index.php?-action=health
 */
class actions_health {
    function handle($params = array()) {
        header('Content-Type: application/json');

        $status = array(
            'status' => 'ok',
            'timestamp' => date('c')
        );

        // Check database connectivity
        try {
            $confDbIni = dirname(__FILE__) . '/../conf.db.ini';
            if (!is_readable($confDbIni)) {
                $confDbIni = dirname(__FILE__) . '/../conf.db.ini.php';
            }
            if (is_readable($confDbIni)) {
                $info = parse_ini_file($confDbIni, true);
                $db = @new mysqli(
                    $info['_database']['host'],
                    $info['_database']['user'],
                    $info['_database']['password'],
                    $info['_database']['name']
                );
                if ($db->connect_error) {
                    $status['status'] = 'degraded';
                    $status['database'] = 'unreachable';
                } else {
                    $status['database'] = 'connected';
                    $db->close();
                }
            } else {
                $status['database'] = 'not_configured';
            }
        } catch (Exception $e) {
            $status['status'] = 'degraded';
            $status['database'] = 'error';
        }

        // Check writable directories
        $dirs = array('templates_c', 'livecache', 'snapshots');
        $dirStatus = array();
        foreach ($dirs as $dir) {
            $path = dirname(__FILE__) . '/../' . $dir;
            $dirStatus[$dir] = is_writable($path) ? 'writable' : 'not_writable';
        }
        $status['directories'] = $dirStatus;

        $httpCode = ($status['status'] === 'ok') ? 200 : 503;
        http_response_code($httpCode);
        echo json_encode($status, JSON_PRETTY_PRINT);
        exit;
    }
}
