<?php
/**
 * @CreateTime:   2019/5/19 下午3:39
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  导出2018年数据，管道接收、去重
 */
date_default_timezone_set("PRC");
class Export2018DataOnlyTest implements CallBackInter {

    /**
     * 管道名称
     *
     * @var string
     * CreateTime: 2019/5/19 下午3:46
     */
    private static $pipeName = 'only';

    /**
     * 开始回调
     *
     * @return mixed
     * CreateTime: 2019/5/18 上午9:56
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
        // 初始化管道
        PipeTool::instance()->iniPipe(self::$pipeName);
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
        // TODO: Implement onForkBefore() method.
        // 这里的return可以将数据返回到相应进程中
        return array(
            'table_name' => 'order-2018'.($data['id']+1)
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
        // 用于查询数据
        if ($data['id'] < 12) {
            // 拼装sql
            $sql = "select * from {$data['table_name']}";
            // 查询(假逻辑)
            $res = $sql;
            // 模拟消耗的时间
            sleep(rand(1,3));
            PipeTool::instance()->wPipe(self::$pipeName, $res);
        } else if ($data['id'] === 12) { // 负责接收所有数据、去重
            $allData = array();
            while (true) {
                // 阻塞方式从管道中获取数据
                $res = PipeTool::instance()->gPipe(self::$pipeName);
                $res=str_replace("\n","",$res);
                $allData[] = $res;
                // 全部进程导出完毕退出
                if (count($allData) === 12) {
                    break;
                }
            }
            // 去重
            $allData = array_unique($allData);
            // 写入文件
            FileTool::instance()->wFile("data.txt", $allData);
        }

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
    }
}