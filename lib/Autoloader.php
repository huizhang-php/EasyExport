<?php
if(!defined('ROOTDIR'))
{
    define('ROOTDIR',dirname(dirname(__FILE__)));   //定义更目录
}
class Autoloader {

    private static $allPath = array();

    public static function myAutoload( $name )
    {
        // 作为缓存
        if (empty(self::$allPath)) {
            self::$allPath = self::recurDir(ROOTDIR);
        }
        foreach (self::$allPath as $key => $value) {
            $file = $value .'/'.$name.'.php';
            if( file_exists( $file ) )
            {
                require_once( $file );
                if( class_exists($name, false) )
                {
                    return true;
                }
            }
        }
        return false;
    }

    private static function recurDir($pathName)
    {
        //将结果保存在result变量中
        $result = array();
        $temp = array();
        //判断传入的变量是否是目录
        if(!is_dir($pathName) || !is_readable($pathName)) {
            return null;
        }
        $allFiles = scandir($pathName);
        foreach($allFiles as $fileName) {
            if(in_array($fileName, array('.', '..'))) {
                continue;
            }
            if (substr($fileName,0,1) == '.') {
                continue;
            }
            if ($fileName == 'runtime') {
                continue;
            }
            $fullName = $pathName.'/'.$fileName;
            if(is_dir($fullName)) {
                $res = self::recurDir($fullName);
                if (!empty($res)) {
                    foreach ($res as $key => $value) {
                        $result[] = $value;
                    }
                }
                $temp[] = $fullName;
            }
        }
        if($temp) {
            foreach($temp as $f) {
                $result[] = $f;
            }
        }
        return $result;
    }
}
spl_autoload_register('Autoloader::myAutoload');