<?php
require_once 'lib/Autoloader.php';
$config = require_once 'config/config.php';
$filePath = explode('@', $config['business_path']);
$worker = new EasyExport();
$worker->config = $config;
$worker->businessClass = new $filePath[1];
$worker->runAll();