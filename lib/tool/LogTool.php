<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/26 下午4:36
 * Description:
 */

class LogTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午5:42
     * @var array
     * Description: 本类常用配置
     */
    private $config = array(
        'log_path' => 'runtime/log/',
        'debug_prefix' => 'debug_',
        'error_prefix' => 'error_',
        'course_prefix' => 'course_',
    );

    /**
     * LogTool constructor.
     */
    public function __construct()
    {
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午5:43
     * @return LogTool
     * Description: 返回当前实例
     */
    public static function instance() {
        return new LogTool();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午5:39
     * Description: debug 记录
     */
    public function debug($data) {
        $this->baseWLog($data, 'debug_prefix');
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午5:40
     * Description: 错误日志
     */
    public function error($data) {
        $this->baseWLog($data, 'error_prefix');
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午5:42
     * Description: 过程日志
     */
    public function course($data) {
        $this->baseWLog($data, 'course_prefix');
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/7 下午5:57
     * @param $data
     * @param $type
     * Description: 基础日志
     */
    private function baseWLog($data, $type) {
        $logData  = date('Y-m-d H:i:s', time())."==============================================\n";
        $logData .= var_export($data, true)."\n";
        $logData  .= "end==============================================================\n";
        $this->config[$type] .= date('Ymd', time()). '.txt';
        $this->config[$type] = fopen($this->config['log_path'].$this->config[$type], 'a+');
        if(flock($this->config[$type], LOCK_EX)) {
            fwrite($this->config[$type], $logData);
        }
        flock($this->config[$type],LOCK_UN);
        fclose($this->config[$type]);
    }
}