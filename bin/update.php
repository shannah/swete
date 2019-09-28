<?php
$basedir = dirname(dirname(__FILE__));
define(SWETE_ADMIN, $basedir . DIRECTORY_SEPARATOR . 'swete-admin' . DIRECTORY_SEPARATOR);
define(SWETE_DATA_ROOT, $basedir . DIRECTORY_SEPARATOR . 'swete-data' . DIRECTORY_SEPARATOR);
if (!file_exists(SWETE_DATA_ROOT)) {
	if (!mkdir(SWETE_DATA_ROOT)) {
		fwrite(STDERR, "Update failed.  Could not create directory ".SWETE_DATA_ROOT);
		exit(1);
	}
}

if (file_exists(SWETE_ADMIN.'livecache') and !file_exists(SWETE_DATA_ROOT . 'livecache')) {
	if (!rename(SWETE_ADMIN.'livecache', SWETE_DATA_ROOT.'livecache')) {
		fwrite(STDERR, "Update failed.  Could not move livecache directory to ".SWETE_DATA_ROOT."livecache");
		exit(1);
	} else {
		echo "Moved livecache directory to " . SWETE_DATA_ROOT . "livecache\n";
	}
}


if (file_exists(SWETE_ADMIN.'sites') and !file_exists(SWETE_DATA_ROOT . DIRECTORY_SEPARATOR . 'sites')) {
	if (!rename(SWETE_ADMIN.'sites', SWETE_DATA_ROOT.'sites')) {
		fwrite(STDERR, "Update failed.  Could not move sites directory to ".SWETE_DATA_ROOT."sites");
		exit(1);
	} else {
		echo "Moved sites directory to " . SWETE_DATA_ROOT . "sites\n";
	}
}
echo "Update Complete\n";
?>