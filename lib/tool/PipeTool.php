<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/15 下午5:25
 * Description: 有名管道
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
     * 当前对象
     *
     * @var null
     * CreateTime: 2019/4/29 下午6:06
     */
    private static $myself = null;

    /**
     * 返回当前实例
     *
     * @return null|PipeTool
     * CreateTime: 2019/4/29 下午6:07
     */
    public static function instance() {
        if (is_null(self::$myself)) {
            self::$myself = new PipeTool();
        }
        return self::$myself;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午5:47
     * @param string $name
     * @return bool
     * Description: 初始化管道
     */
    public function iniPipe($names) {
        if (is_array($names)) {
            $names = array_unique($names);
        } else {
            $names = array($names);
        }
        $res = $this->createPipe($names);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 创建管道
     *
     * CreateTime: 2019/4/30 上午11:02
     */
    private function createPipe($names) {
        foreach ($names as $key => $name) {
            $pipeName = $this->getPipeName($name);
            $fifoPath = "runtime/cache/pipe/$pipeName";
            if (!file_exists($fifoPath)) {
                if (!posix_mkfifo($fifoPath, 0666)) {
                    return false;
                }
            }
            if (isset(self::$pipes[$pipeName])) {
                $this->delPipe($name);
            }
            self::$pipes[$pipeName] = array(
                'pipe_path' => $fifoPath,
                'pipe' => fopen($fifoPath, 'a+'),
            );
        }
        return true;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:11
     * @param $name
     * @param $data
     * @return bool
     * Description: 向管道中写数据
     */
    public function wPipe($name, $data) {
        $pipeName = $this->getPipeName($name);
        $pipe = self::$pipes[$pipeName]['pipe'];
        stream_set_blocking($pipe, false);
        $res = fwrite($pipe, $data);
        if ($res === false) {
            sleep(1);
        }
        return $res;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:14
     * @param $name
     * @param int $size
     * @param bool $isSyn
     * @return bool|string
     * Description: 每次固定读取多少长度
     */
    public function rPipe($name, $size=1024,$isSyn=false) {
        $pipeName = $this->getPipeName($name);
        $pipe = self::$pipes[$pipeName]['pipe'];
        if ($isSyn) {
            stream_set_blocking($pipe, false);
        }
        $data = fread($pipe, $size);
        return trim($data);
    }

    /**
     * 读取一行数据
     *
     * @param $name
     * @param int $size 读取大小
     * @param bool $isSyn 是否阻塞
     * @return bool|string
     * CreateTime: 2019/4/29 下午7:27
     */
    public function gPipe($name, $size=1024, $isSyn=false) {
        $pipeName = $this->getPipeName($name);
        $pipe = self::$pipes[$pipeName]['pipe'];
        if ($isSyn) {
            stream_set_blocking($pipe, false);
        }
        $data = fgets($pipe, $size);
        return $data;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:18
     * @param $name
     */
    public function delPipe($name) {
        $pipeName = $this->getPipeName($name);
        unlink(self::$pipes[$pipeName]);
    }

    /**
     * 获取管道名称
     *
     * @param $name
     * @return string
     */
    private function getPipeName($name) {
        $pipeName = md5(__FILE__.$name);
        return $pipeName;
    }
}
