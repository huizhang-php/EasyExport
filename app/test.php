<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/25 下午3:13
 * Description:
 */

class test implements CallBackInter{

    private static $pipePrefix;

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午4:12
     * @return mixed
     * Description: 开始回调
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
        self::$pipePrefix = md5(__FILE__);
        $worerNum = $GLOBALS['CONFIG']['worker_num'];
        for ($i=0;$i<$worerNum;$i++) {
            PipeTool::iniPipe(self::$pipePrefix);
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午3:26
     * @param $data
     * @return mixed
     * Description: 每个fock之前的回调
     */
    public function onForkBefore($data)
    {
        // 将数据写入管道
        return $data;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午3:27
     * @param $data
     * @return mixed
     * Description: 子进程处理回调
     */
    public function onChildProcess($data)
    {
        // TODO: Implement onChildProcess() method.
        if ($data != 1) {
            $i = 0;
            while (true) {
                PipeTool::wPipe(self::$pipePrefix,++$i."\n");
                sleep(1);
            }
        } else {
            while (true) {
                $res = PipeTool::gPipe(self::$pipePrefix);
                var_dump($res);
            }
        }

    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午3:27
     * @return mixed
     * Description: 结束回调
     */
    public function onEnd()
    {
        // TODO: Implement onEnd() method.1

    }
}