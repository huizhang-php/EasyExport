<?php
/**
 * @CreateTime:   2019/5/18 上午9:44
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  文件test
 */

class FileTest implements CallBackInter {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/25 下午4:12
     * @return mixed
     * Description: 开始回调
     */
    public function onStart()
    {
        // TODO: Implement onStart() method.
        // 获取文件所有内容
        FileTool::instance()->getAllFileContent('content.txt', $returnData);
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
        $fileName = $data['id'].'.txt';
        $addData = array();
        for ($i=0;$i<rand(1,10000);$i++) {
            $addData[] = $i;
        }
        FileTool::instance()->wFile($fileName, $addData);
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
        FileTool::instance()->getAllFileContent($data['id'].'.txt', $returnData);
        var_dump(count($returnData));
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