<?php
if (!@$argv) {
	die("CLI only");
}
if (count($argv) < 3) {
	fwrite(STDERR, "Usage: php /path/to/tarball-site.php path/to/site path/to/tarbarll.tar\n");
	exit(1);
}

$sitePath = $argv[1];
$tarballPath = $argv[2];
if (file_exists($tarballPath)) {
	fwrite(STDERR, "$tarballPath already exists\n");
	exit(1);
}
if (!file_exists($sitePath)) {
	fwrite(STDERR, "$sitePath doesn't exist\n");
	exit(1);
}

if (!file_exists(dirname($tarballPath))) {
	fwrite(STDERR, dirname($tarballPath)." does not exist\n");
	exit(1);
}

chdir(dirname($sitePath));
$command = "tar cf ".escapeshellarg($tarballPath)
	." --exclude=swete-admin/livecache --exclude=swete-data/livecache --exclude=www/swete-data/livecache --exclude=www/swete-admin/livecache --exclude=.git --exclude=phpmyadmin "
.escapeshellarg(basename($sitePath));
echo "Running $command\n";
passthru($command, $res);
if ($res !== 0) {
	fwrite(STDERR, "ERROR: Failed to create tarball\n");
	exit(1);
}
