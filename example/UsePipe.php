<?php

/**
 * Class UsePipe
 */
class UsePipe implements CallBackInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午4:12
     * @return mixed
     * Description: 开始回调
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
        // 初始化管道
        $res = PipeTool::instance()->iniPipe(
            'pipe1');
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
        // TODO: Implement onForkBefore() method.
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
        if ($data['id'] < 9) {
            $i = 0;
            while (true) {
                $res = PipeTool::instance()->wPipe('pipe1', '积分鲁大师房间开始懂了附近');
            }
        } else {
            while (true) {
                $res = PipeTool::instance()->gPipe('pipe1');
                var_dump($res);
                sleep(1);
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
        // TODO: Implement onEnd() method.
    }
}