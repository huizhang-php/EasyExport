<?php
/**
 * @CreateTime:   2019/5/18 上午9:44
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  回调base接口
 */

interface CallBackInter
{

    /**
     * 开始回调
     *
     * @return mixed
     * CreateTime: 2019/5/18 上午9:56
     */
    public function onStart();

    /**
     * 每个fock之前的回调
     *
     * @param $data
     * @return mixed
     * CreateTime: 2019/5/18 上午9:57
     */
    public function onForkBefore($data);

    /**
     * 子进程处理回调
     *
     * @param $data
     * @return mixed
     * CreateTime: 2019/5/18 上午9:57
     */
    public function onChildProcess($data);

    /**
     * 结束回调
     *
     * @return mixed
     * CreateTime: 2019/5/18 上午9:57
     */
    public function onEnd();

}