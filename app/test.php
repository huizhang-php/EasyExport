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
        $worerNum = $GLOBALS['CONFIG']['worker_num'];
        PipeTool::instance()->iniPipe(self::$pipePrefix);
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
        if ($data < 9) {
            $i = 0;
            while (true) {
                $res = PipeTool::instance()->wPipe(self::$pipePrefix, '
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                    解放路圣诞节疯狂送砥砺奋进速度快乐福建省多亏了附近附近的说服力的卡时间发了多少开发机就发大水来访接待室客服连接单身快乐房价多少,
                '."\n");
                var_dump($res);
//                sleep(1);
            }
        } else {
            while (true) {
                $res = PipeTool::instance()->gPipe(self::$pipePrefix,1024);
                var_dump($res);
//                sleep(3);
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