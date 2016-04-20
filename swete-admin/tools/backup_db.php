<?php
if ( !@$argv ){
    die("No access");
}

$confPath = dirname(__FILE__).'/../conf.db.ini';
$conf = parse_ini_file($confPath, true);
$conf = $conf['_database'];
$outfile = dirname(__FILE__).'/../backups/'.$conf['name'].'.'.date('Y.m.d.H.i.s').'sql';

`mysqldump -h "{$conf['host']}" -u "{$conf['user']}" -p"{$conf['password']}" "{$conf['name']}" > "$outfile"`;
`gzip $outfile`;

