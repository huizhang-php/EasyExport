<?php
/**
 * @CreateTime:   2019/5/18 上午11:11
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  框架callback简单介绍
 */

class CallBackTest implements CallBackInter {

    /**
     * 开始回调
     *
     * @return mixed
     * CreateTime: 2019/5/18 上午9:56
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
        return array(
            'onStart'
        ); // 将此方法的数据传递给onForkBefore方法
    }

    /**
     * 每个fock之前的回调
     *
     * @param $data
     * @return mixed
     * CreateTime: 2019/5/18 上午9:57
     */
    public function onForkBefore($data)
    {
        var_dump($data);
        // TODO: Implement onForkBefore() method.
        return array(
            'onForkBefore'
        );
    }

    /**
     * 子进程处理回调
     *
     * @param $data
     * @return mixed
     * CreateTime: 2019/5/18 上午9:57
     */
    public function onChildProcess($data)
    {
        // TODO: Implement onChildProcess() method.
        var_dump($data);
    }

    /**
     * 结束回调
     *
     * @return mixed
     * CreateTime: 2019/5/18 上午9:57
     */
    public function onEnd()
    {
        // TODO: Implement onEnd() method.
        // 这里可以做完成后的一些操作，比如邮件提醒
    }
}
