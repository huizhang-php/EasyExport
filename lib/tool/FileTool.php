<?php
/**
 * @CreateTime:   2019/5/18 上午9:44
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  文件操作工具
 */
class FileTool extends BaseTool
{
    /**
     * 文件基础路径
     *
     * @var string
     * CreateTime: 2019/5/18 上午10:35
     */
    private static $filePath='runtime/data/';

    /**
     * FileTool constructor.
     */
    public function __construct()
    {
        $business = ConfigTool::instance()->getConfig('business_path');
        $businessArr = explode('@', $business);
        $this->mkFolder(self::$filePath.end($businessArr));
        self::$filePath = self::$filePath.end($businessArr).'/';
    }

    /**
     * 写文件(独占锁方式写入)
     *
     * @param string $fileName 文件名称
     * @param $data
     * CreateTime: 2019/5/18 上午9:48
     * @param string $separator 分隔符
     */
    public function wFile($fileName, $data, $separator="\t")
    {
        if (is_string($data)) {
            $data = array($data);
        }
        $file = fopen(self::$filePath . $fileName, 'a+');
        if (flock($file, LOCK_EX)) {
            foreach ($data as $key => $val) {
                $val .= "\n";
                if (is_array($val)) {
                    fwrite($file, implode($separator, $val));
                } else {
                    fwrite($file, $val);
                }
            }
        }
        flock($file, LOCK_UN);
        fclose($file);
    }

    /**
     * 创建文件夹
     *
     * @param string $path
     * CreateTime: 2019/5/18 上午9:51
     */
    public function mkFolder($path='')
    {
        if (!is_readable($path)) {
            is_file($path) or mkdir($path, 0700);
        }
    }

    /**
     * 获取文件所有内容
     *
     * @param $fileName string 文件名称
     * @param $returnData &array 传址方式传递目的是防止文件内容过大
     * @param string $separator 数据以什么方式分割
     * @return void
     * CreateTime: 2019/5/18 上午9:51
     */
    public function getAllFileContent($fileName, &$returnData, $separator="\t") {
        $file = fopen(self::$filePath . $fileName, 'a+');
        $returnData = array();
        while (!feof($file)) {
            $returnData[] = explode($separator, trim(fgets($file)));
        }
        fclose($file);
    }

}