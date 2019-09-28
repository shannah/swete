
<?php
if (!@$argv) {
	fwrite(STDERR, "CLI only");
	exit(1);
}
if (count($argv) < 2) {
	fwrite(STDERR, "Usage: php create-site.php /path/to/site");
	exit(1);
}
$dest = $argv[1];
$self = dirname(__FILE__);
$swete = $self . DIRECTORY_SEPARATOR . '..';
$sweteAdmin = $swete . DIRECTORY_SEPARATOR . 'swete-admin';
$xataface = $sweteAdmin . DIRECTORY_SEPARATOR . 'xataface';
$xfTools = $xataface . DIRECTORY_SEPARATOR . 'tools';
$xatafaceCreate = $xfTools . DIRECTORY_SEPARATOR . 'create.php';
passthru("php ".escapeshellarg($xatafaceCreate)." ".escapeshellarg($dest), $res);
if ($res !== 0) {
	fwrite(STDERR, "Failed to create site.\n");
	exit($res);
}
$www = $dest . DIRECTORY_SEPARATOR . 'www';
$confDb = $www . DIRECTORY_SEPARATOR . 'conf.db.ini.php';
$confDbContents = file_get_contents($confDb);
passthru("rm -rf ".escapeshellarg($www), $res);
if ($res !== 0) {
	fwrite(STDERR, "Failed to remove temporary www directory after creating generic Xataface application.\n");
	exit($res);
}
passthru("cp -r ".escapeshellarg(realpath($swete))." ".escapeshellarg($www), $res);
if ($res !== 0) {
	fwrite("Failed to copy swete into target directory\n");
	exit($res);
}
passthru("rm -rf ".escapeshellarg($www . DIRECTORY_SEPARATOR . 'swete-admin'), $res);
if ($res !== 0) {
	fwrite("Install failed.  There was a problem deleting the swete-admin directory.\n");
	exit($res);
}

if (!symlink(realpath($swete . DIRECTORY_SEPARATOR . 'swete-admin'), $www .DIRECTORY_SEPARATOR . 'swete-admin')) {
	fwrite(STDERR, "Failed to create link from swete to swete-admin directory");
	exit(1);
}


$siteData = $www . DIRECTORY_SEPARATOR . 'swete-data';
mkdir($siteData);

$newConfDb = $siteData . DIRECTORY_SEPARATOR . 'conf.ini';
file_put_contents($newConfDb, $confDbContents);



$liveCache = $siteData . DIRECTORY_SEPARATOR . 'livecache';
mkdir($liveCache);
if (!copy(
	$sweteAdmin .DIRECTORY_SEPARATOR . 'livecache' . DIRECTORY_SEPARATOR . '.htaccess',
	$liveCache . DIRECTORY_SEPARATOR . '.htaccess'
)) {
	fwrite(STDERR, "Failed to setup livecache directory.\n");
	exit(1);
}
$snapshots = $siteData . DIRECTORY_SEPARATOR . 'snapshots';
mkdir($snapshots);
if (!copy(
	$sweteAdmin .DIRECTORY_SEPARATOR . 'livecache' . DIRECTORY_SEPARATOR . '.htaccess',
	$snapshots . DIRECTORY_SEPARATOR . '.htaccess'
)) {
	fwrite(STDERR, "Failed to setup snapshots directory.\n");
	exit(1);
}

$stringImports = $liveCache . DIRECTORY_SEPARATOR . 'string_imports';
mkdir($stringImports);

$app = $dest . DIRECTORY_SEPARATOR . 'app';
unlink($app);
$cwd = realpath(getcwd());
chdir($dest);
if (!symlink('www'. DIRECTORY_SEPARATOR . 'swete-admin', 'app')) {
	fwrite(STDERR, "Failed to create new symlink to app\n");
	exit(1);
}
chdir($cwd);

$cliConfig = <<<END
XATAFACE_CONFIG_PATHS=../www/swete-data
END
;
file_put_contents($dest . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'cli-conf.ini', $cliConfig);






