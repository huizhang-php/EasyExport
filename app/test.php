<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/25 下午3:13
 * Description:
 */

class test implements CallBackInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午4:12
     * @return mixed
     * Description: 开始回调
     */
    public static function onStart()
    {
        // TODO: Implement onStart() method.
        $queue = MsgQueue::createQueue();
        var_dump($queue);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午3:26
     * @param $data
     * @return mixed
     * Description: 每个fock之前的回调
     */
    public static function onForkBefore($data)
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
    public static function onChildProcess($data)
    {
        // TODO: Implement onChildProcess() method.

    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午3:27
     * @return mixed
     * Description: 结束回调
     */
    public static function onEnd()
    {
        // TODO: Implement onEnd() method.

    }
}