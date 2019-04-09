<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/26 下午4:36
 * Description:
 */
class File
{

    /**
     * User: yuzhao
     * CreateTime: 2019/3/8 下午4:48
     * @var array
     * Description: 本类常用配置
     */
    private $config = array(
        'file_path' => 'runtime/data/'
    );

    /**
     * File constructor.
     */
    public function __construct()
    {
        $config = require 'config/config.php';
        if (isset($config['data_path'])) {
            $this->config['file_path'] = $config['data_path'];
        }
        $this->mkFolder($this->config['file_path']);
    }

    public static function instance()
    {
        return new File();
    }

    public function wFile($fileName, $data)
    {
        $file = fopen($this->config['file_path'] . $fileName, 'a+');
        if (flock($file, LOCK_EX)) {
            fwrite($file, $data);
        }
        flock($file, LOCK_UN);
        fclose($file);
    }

    private function mkFolder($path)
    {
        if (!is_readable($path)) {
            is_file($path) or mkdir($path, 0700);
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/4/10 上午12:08
     * @param $fileName
     * @return array
     * Description: 获取文件所有内容
     */
    public function getAllFileContent($fileName) {
        $file = fopen($this->config['file_path'] . $fileName, 'a+');
        $returnData = array();
        while (!feof($file)) {
            $returnData[] = trim(fgets($file));
        }
        return $returnData;
    }

}