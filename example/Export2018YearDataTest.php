<?php
/**
 * @CreateTime:   2019/5/19 上午11:12
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  导出2018年数据(过程写的可能有点复杂，目的是为了介绍更复杂的业务怎么使用)
 */

class Export2018YearDataTest implements CallBackInter {

    /**
     * 开始回调
     *
     * @return mixed
     * CreateTime: 2019/5/18 上午9:56
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
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
        // 拼装sql
        $sql = "select * from {$data['table_name']}";
        // 查询(假逻辑)
        $res = $sql;
        FileTool::instance()->wFile("data.txt", $res);
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
        var_dump('发邮件');
    }
}