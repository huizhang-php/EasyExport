<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/25 下午3:13
 * Description:
 */

class test implements CallBackInter{

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午4:12
     * @return mixed
     * Description: 开始回调
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
        MsgQueue::createQueue('test');
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
        return $data['first_n'];
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
        if ($data == 3) {
            while (true) {
                var_dump(MsgQueue::get('test'));
            }
        } else {
            for ($i=0;$i<=1000;$i++) {
                MsgQueue::add('test', $i);
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