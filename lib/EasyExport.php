<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/25 下午12:05
 * Description: 导数据
 */
class EasyExport {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午12:16
     * @var int
     * Description: 任务数量
     */
    public $workerNum = 1;

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 上午11:40
     * @var array
     * Description: 子进程
     */
    private $childsPid = array();

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午1:20
     * @var array
     * Description: 永久保存的
     */
    private $saveChildsPid = array();

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午2:40
     * @var array
     * Description: 配置
     */
    public $config = array();

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午2:43
     * @var $businessClass
     * Description: 业务class
     */
    public $businessClass;

    /**
     * EasyExport constructor.
     * @param $input
     */
    public function __construct()
    {
        $config= $GLOBALS['config'];
        // 检查是否为cli启动
        if (substr(php_sapi_name(), 0, 3) !== 'cli') {
            exit("cli mode only");
        }

        // 进程数量
        if (isset($config['worker_num'])) {
            if ($config['worker_num'] >= 20 ) {
                exit('进程数量最大限制为20');
            }
            $this->workerNum = $config['worker_num'];
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午2:15
     * Description: 执行
     */
    public function runAll() {
        // 检查必要信息
        $this->checkMustInfo();
        // 解析命令
        $this->parseCommand();
        // 安装信号处理器
        $this->installiSignal();
        // fork前置回调
        $this->businessClass->onStart();
        // fork子进程
        $this->forkProcess();
        // 循环检测信号队列里是否有信号发生
        $this->isSignal();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午1:13
     * Description: 展示ui
     */
    private function displayUi() {
        static $lastLine = 0;
        $ui = "\n                           EasyExport\n";
        $ui .= "-----------------------------------------------------------------\n";
        $ui .= "进程ID[space10]开始时间[space15]结束时间[space11]状态\n";
        foreach ($this->saveChildsPid as $key => $value) {
            if (in_array($key, $this->childsPid)) {
                $ui .= $key."[space5]".$value['stime']."[space5]".$value['etime']."[space5]running\n";
            } else {
                $ui .= $key."[space5]".$value['stime']."[space5]".$value['etime']."[space5]stop\n";
            }
        }
        $count = 0;
        for ($i=0;$i<20;$i++) {
            $ui = str_replace('[space'.$i.']', str_pad('', $i,' '), $ui);
        }
        foreach(explode("\n", $ui) as $line)
        {
            $count += count(str_split($line, 1024));
        }
        for($i = 0; $i < $lastLine-1; $i++)
        {
            // 光标移到行首
            echo "\r";
            // 清除这一行的内容
            echo "\033[K";
            // 上移一行
            echo "\033[1A";
        }
        echo $ui;
        $lastLine = $count;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午1:08
     * Description: 检查必要信息
     */
    private function checkMustInfo() {
        if ($this->workerNum > 1 && !function_exists('pcntl_fork'))
        {
            exit('Multitasking needs pcntl, the pcntl extension was not found');
        }
        $this->workerNum = $this->config['worker_num'];
    }

    private function parseCommand() {
        global $argv;
        $startFile = $argv[0];
        if (isset($argv[1])) {
            switch ($argv[1]) {
                case 'stop': // 停止进程
                    $this->killEasyExport($startFile);
                    exit;
                    break;
                case  'reload': // 重新启动
                    $this->killEasyExport($startFile);
                    break;
                case 'start': // 普通方式启动
                    $this->killEasyExport($startFile);
                    if (isset($argv[2])) {
                        switch ($argv[2]) {
                            case '-d': // 守护进程方式启动
                                $this->daemonStart();
                                break;
                            default:
                                exit('无效参数');
                        }
                    }
                    break;
                case 'status': // 查看运行状态
                    break;
                default:
                    exit("Usage: php yourfile.php {start|stop|status|reload}\n");
            }
        } else {
            exit("Usage: php yourfile.php {start|stop|status|reload}\n");
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午12:16
     * @param $startFile
     * Description: kill easyexport
     */
    private function killEasyExport($startFile) {
        exec("ps aux | grep $startFile | grep -v grep | awk '{print $2}'", $info);
        if (count($info) <= 1) {
//            echo "not run\n";
        } else {
            echo "[$startFile] stop success";
            exec("ps aux | grep $startFile | grep -v grep | awk '{print $2}' |xargs kill -SIGINT", $info);
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 上午11:59
     * Description: 安装信号处理器
     */
    private function installiSignal() {
        // 非常消耗性能，每执行1行PHP代码就回调pcntl_signal函数，为了兼容<php5.3
        declare(ticks = 1);
        pcntl_signal( SIGCHLD, array($this, 'mySignal'));
    }

    public function mySignal ($signal){
        $childsPidNum = count( $this->childsPid );
        if( $childsPidNum > 0 ){
            foreach( $this->childsPid as $pidKey => $pid ){
                $waitResult = pcntl_waitpid( $pid, $status, WNOHANG );
                if( $waitResult == $pid || -1 == $waitResult ){
                    $this->saveChildsPid[$pid]['etime'] = date('Y-m-d H:i:s', time());
                    unset( $this->childsPid[$pidKey] );
//                    $this->displayUi();
                    $childsPidNum = count( $this->childsPid );
                    if ($childsPidNum == 0) {
                        // fork后置回调
                        $this->businessClass->onEnd();
                        LogTool::instance()->course('easyexport end!');
                        die('进程退出');
                    }
                }
            }
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 上午11:57
     * Description: 检测信号队列里是否有信号发生，如果有，则执行进程绑定的信号处理回调函数。
     */
    private function isSignal() {
        LogTool::instance()->course('easyexport start!');
        while( true ){
//            pcntl_signal_dispatch();
//            $this->displayUi();
            sleep(1);
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 上午11:56
     * Description: fork后置函数
     */
    private function onEndCallBack($data) {
        if ($this->onEnd)
        {
            call_user_func($this->onEnd, $data);
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 上午11:55
     * @param $startResult
     * Description: fork 子进程
     */
    private function forkProcess() {
        for( $i = 0; $i < $this->workerNum; $i++ ){
            $forckBeforeReturn = $this->businessClass->onForkBefore($i);
            $pid = pcntl_fork();
            if( $pid < 0 ){
                exit();
            } else if( 0 == $pid ) {
                $this->businessClass->onChildProcess($forckBeforeReturn);
                exit();
            } else if( $pid > 0 ) {
                $this->childsPid[] = $pid;
                $this->saveChildsPid[$pid] = array(
                    'stime' => date('Y-m-d H:i:s', time()),
                    'etime' => '[space19]'
                );
            }
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 上午11:51
     * Description: 将启动damon化
     */
    private function daemonStart() {
        // 守护进程需要pcntl扩展支持
        if (!function_exists('pcntl_fork'))
        {
            exit('Daemonize needs pcntl, the pcntl extension was not found');
        }
        umask( 0 );
        $pid = pcntl_fork();
        if( $pid < 0 ){
            exit('fork error.');
        } else if( $pid > 0 ) {
            exit();
        }
        if( !posix_setsid() ){
            exit('setsid error.');
        }
        $pid = pcntl_fork();
        if( $pid  < 0 ){
            exit('fork error');
        } else if( $pid > 0 ) {
            // 主进程退出
            exit;
        }
        // 子进程继续，实现daemon化
    }
}