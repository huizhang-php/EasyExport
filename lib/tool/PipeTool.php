<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/15 下午5:25
 * Description: 管道
 */

class PipeTool {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:16
     * @var array
     * Description: 管道们
     */
    private static $pipes = array();

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午5:47
     * @param string $name
     * @return bool
     * Description: 初始化管道
     */
    public static function iniPipe($name='pepe') {
        $fifoPath = "runtime/pipe/$name";
        if (!file_exists($fifoPath)) {
            if (!posix_mkfifo($fifoPath, 0666)) {
                return false;
            }
        } else {
            return false;
        }
        self::$pipes[$name] = $fifoPath;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:11
     * @param $pipeName
     * @param $data
     * @return bool
     * Description: 向管道中写数据
     */
    public static function wPipe($pipeName, $data) {
        $pipe = fopen(self::$pipes[$pipeName], 'w');
        if ($pipe == NULL) {
            return false;
        }
        fwrite($pipe, $data);
        fclose($pipe);
        return true;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:14
     * @param $pipeName
     * @param int $size
     * @return bool|string
     * Description: 读管道
     */
    public static function rPipe($pipeName, $size=1024) {
        $pipe = fopen(self::$pipes[$pipeName], 'r');
        if ($pipe == NULL) {
            return false;
        }
        $data = fread($pipe, $size);
        fclose($pipe);
        return $data;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:14
     * @param $pipeName
     * @param int $size
     * @return string
     * Description: 读取管道所有数据
     */
    public static function rAllData($pipeName, $size=1024) {
        $pipe = fopen(self::$pipes[$pipeName], 'r');
        $data = '';
        while (!feof($pipe)) {
            $data .= fread($pipe, $size);
        }
        fclose($pipe);
        return $data;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:18
     * @param $pipeName
     * Description: 删除管道
     */
    public function delPipe($pipeName) {
        unlink(self::$pipes[$pipeName]);
    }
}
