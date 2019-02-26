<?php
include_once 'lib/Export.php';
include_once 'lib/base/CallBackInter.php';
$config = require_once 'config/config.php';

$worker = new EasyExport($config);

require_once $config['business_path'];

$worker->onForkBefore = function ($params) {
    return test::onForkBefore($params);
};

$worker->onChildProcess = function ($params) {
    test::onChildProcess($params);
};

$worker->onEnd = function () {
    test::onEnd();
};

$worker->onStart = function () {
    return test::onStart();
};

$worker->runAll();