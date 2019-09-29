<?php
if (!@$argv) {
	die("CLI only");
}
if (count($argv) < 2) {
	fwrite(STDERR, "Usage: php /path/to/update-site.php path/to/site\n");
	exit(1);
}

$sitePath = $argv[1];
if (!file_exists($sitePath)) {
	fwrite(STDERR, "Site not found at $sitePath\n");
	exit(1);
}

$thisSwete = dirname(dirname(__FILE__));
$thisSweteAdmin = $thisSwete . DIRECTORY_SEPARATOR . 'swete-admin';
$thisBin = $thisSwete . DIRECTORY_SEPARATOR . 'bin';

$sweteAdmin = $sitePath . DIRECTORY_SEPARATOR . 'swete-admin';
$docRoot = $sitePath;
$sweteData = $docRoot . DIRECTORY_SEPARATOR . 'swete-data';
if (!file_exists($sweteAdmin)) {
	$docRoot = $sitePath . DIRECTORY_SEPARATOR . 'www';
	$sweteAdmin = $docRoot . DIRECTORY_SEPARATOR . 'swete-admin';
	$sweteData = $docRoot . DIRECTORY_SEPARATOR . 'swete-data';
}
if (!file_exists($sweteAdmin)) {
	fwrite(STDERR, "Cannot find swete-admin directory.\n");
	exit(1);
}

if (realpath($sweteAdmin) === realpath($thisSweteAdmin)) {
	fwrite(STDERR, wordwrap("update-site cannot update itself.  It can only update other swete sites.\n"));
	exit(1);
}

// Compare versions
$thisVersion = file_get_contents($thisSweteAdmin . DIRECTORY_SEPARATOR . 'version.txt');
$version = file_get_contents($sweteAdmin . DIRECTORY_SEPARATOR . 'version.txt');
list($thisVersionString, $thisVersionNumber) = explode(' ', trim($thisVersion));
list($versionString, $versionNumber) = explode(' ', trim($version));
if (intval($versionNumber) >= intval($thisVersionNumber)) {
	fwrite(STDERR, wordwrap("The SWeTE instance at $docRoot is the same version or newer than this version of SWeTE.  Cancelling update.\n"));
	exit(1);
}

$bin = $docRoot . DIRECTORY_SEPARATOR .'bin';
$update = $bin . DIRECTORY_SEPARATOR . 'update.php';

echo "Updating bin directory in site...\n";

passthru("rm -rf ".escapeshellarg($bin), $res);
if ($res !== 0) {
	fwrite(STDERR, "Failed to delete old bin directory.\n");
	exit(1);
}

passthru("cp -r ".escapeshellarg($thisBin)." ".escapeshellarg($bin), $res);
if ($res !== 0) {
	fwrite(STDERR, "Failed to copy bin directory to $bin\n");
	exit(1);
}

// Now we run update.php on the site
echo "Running update.php script in site...\n";
passthru("php ".escapeshellarg($update), $res);
if ($res !== 0) {
	fwrite(STDERR, "Failed to update the site.\n");
	exit($res);
		
}

// Smoke test:  Make sure there is no site data still in the swete-admin directory.
if (file_exists($sweteAdmin . DIRECTORY_SEPARATOR . 'livecache')) {
	fwrite(STDERR, wordwrap("Assertion error: livecache directory still exists inside swete-admin.  It should be gone.\n"));
	fwrite(STDERR, "Cancelling update.\n");
	exit(1);
}
if (file_exists($sweteAdmin . DIRECTORY_SEPARATOR . 'snapshots')) {
	fwrite(STDERR, wordwrap("Assertion error: snapshots directory still exists inside swete-admin.  It should be gone.\n"));
	fwrite(STDERR, "Cancelling update.\n");
	exit(1);
}
if (file_exists($sweteAdmin . DIRECTORY_SEPARATOR . 'sites')) {
	fwrite(STDERR, wordwrap("Assertion error: sites directory still exists inside swete-admin.  It should be gone.\n"));
	fwrite(STDERR, "Cancelling update.\n");
	exit(1);
}

// Now we copy the conf.db.ini file into the swete-data directory.
$dataConfIni = $sweteData . DIRECTORY_SEPARATOR . 'conf.ini';
if (!file_exists($dataConfIni)) {
	echo wordwrap("$dataConfIni not found.  Trying to migrate old conf.db.ini file to it...\n");
	$confDbIni = $sweteAdmin . DIRECTORY_SEPARATOR . 'conf.db.ini';
	if (!file_exists($confDbIni)) {
		$confDbIni .= '.php';
	}
	if (!file_exists($confDbIni)) {
		fwrite(STDERR, "Cannot find conf.db.ini to copy into swete-data\n");
		fwrite(STDERR, 'Update cancelled'."\n");
		exit(1);
	}
	if (!copy($confDbIni, $dataConfIni)) {
		fwrite(STDERR, "Failed to copy $confDbIni to $dataConfIni\n");
		exit(1);
	} else {
		echo "Successfully copied db config to $dataConfIni\n";
	}
}

// Now we can copy the swete-admin directory
if (is_link($sweteAdmin)) {
	if (!unlink($sweteAdmin)) {
		fwrite(STDERR, "ERROR: Failed to remove link to old swete-admin directory.\n");
		exit(1);
	}
} else {
	passthru("rm -rf ".escapeshellarg($sweteAdmin), $res);
	if ($res !== 0) {
		fwrite(STDERR, "ERROR: Failed to delete $sweteAdmin\n");
		exit($res);
	}
}

if (!symlink($thisSweteAdmin, $sweteAdmin)) {
	fwrite(STDERR, "ERROR: Failed to make symlink to swete-admin directory.");
}

echo wordwrap("This script deleted the old swete-admin directory and replaced it with a symlink to $thisSweteAdmin
	The bin directory was also updated with the latest binary scripts.
The swete-data directory was left unchanged, as was everything else in the document root of the site.
You may want to compare the .htaccess rules of your site with the .htaccess file in this version of SWeTE to see if you need to make any changes, as the site's .htaccess file was left untouched.

UPDATE COMPLETE!
");





?>