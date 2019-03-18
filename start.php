<?php
$loadFiels = get_included_files();
define('MYROOT',dirname($loadFiels[0]));
require_once 'lib/Autoloader.php';
$config = require_once 'config/config.php';
$GLOBALS['CONFIG'] = $config;
$filePath = explode('@', $config['business_path']);
$worker = new EasyExport();
$worker->config = $config;
$worker->businessClass = new $filePath[1];
$worker->runAll();