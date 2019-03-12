<?php
include_once 'lib/Export.php';
include_once 'lib/base/CallBackInter.php';
include_once 'lib/tool/MsgQueue.php';
require_once 'lib/tool/Log.php';
require_once 'lib/tool/File.php';
require_once 'lib/tool/ShareVariable.php';
$config = require_once 'config/config.php';
$filePath = explode('@', $config['business_path']);
require_once $filePath[0];
$worker = new EasyExport();
$worker->config = $config;
$worker->businessClass = new $filePath[1];
$worker->runAll();