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
     * CreateTime: 2019/3/18 下午3:50
     * @var null
     * Description:
     */
    private static $rSemId = null;

    /**
     * User: yuzhao
     * CreateTime: 2019/3/18 下午3:53
     * @var null
     * Description: 写锁id
     */
    private static $wSemId = null;

    /**
     * User: yuzhao
     * CreateTime: 2019/3/18 下午3:51
     * @var int
     * Description: 读锁数量
     */
    private static $rSemIdNum=0;

    /**
     * User: yuzhao
     * CreateTime: 2019/3/18 下午3:52
     * @var int
     * Description: 写锁数量
     */
    private static $wSemIdNum=0;

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午5:47
     * @param string $name
     * @return bool
     * Description: 初始化管道
     */
    public static function iniPipe($name='pepe') {
        $semKey = ftok( __FILE__, 'b' );
        self::$rSemIdNum ++;
        self::$wSemIdNum = (self::$wSemIdNum) * 100;
        self::$rSemId = sem_get($semKey.self::$rSemIdNum);
        self::$wSemId = sem_get($semKey.self::$wSemIdNum);
        $fifoPath = "runtime/cache/pipe/$name";
        self::$pipes[$name] = array(
            'pipe_path' => $fifoPath,
            'r_sem_id' => self::$rSemId,
            'w_sem_id' => self::$wSemId
        );
        if (!file_exists($fifoPath)) {
            if (!posix_mkfifo($fifoPath, 0666)) {
                return false;
            }
        } else {
            return false;
        }
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
        sem_acquire( self::$pipes[$pipeName]['w_sem_id']);
        $pipe = fopen(self::$pipes[$pipeName]['pipe_path'], 'w');
        if ($pipe == NULL) {
            sem_release(self::$pipes[$pipeName]['w_sem_id']);
            return false;
        }
        fwrite($pipe, $data);
        fclose($pipe);
        sem_release(self::$pipes[$pipeName]['w_sem_id']);
        return true;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/15 下午6:14
     * @param $pipeName
     * @param int $size
     * @return bool|string
     * Description: 每次固定读取多少长度
     */
    public static function rPipe($pipeName, $size=1024,$isSyn=false) {
        sem_acquire( self::$pipes[$pipeName]['r_sem_id'] );
        $pipe = fopen(self::$pipes[$pipeName]['pipe_path'], 'r');
        if ($isSyn) {
            stream_set_blocking($pipe, $isSyn);
        }
        if ($pipe == NULL) {
            sem_release( self::$pipes[$pipeName]['r_sem_id'] );
            return false;
        }
        $data = fread($pipe, $size);
        fclose($pipe);
        sem_release( self::$pipes[$pipeName]['r_sem_id'] );
        return $data;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/18 下午3:37
     * @param $pipeName
     * @param int $size
     * @param bool $isSyn
     * @return bool|string
     * Description: 每次读取一行\n
     */
    public static function gPipe($pipeName, $size=1024, $isSyn=false) {
        sem_acquire( self::$pipes[$pipeName]['r_sem_id'] );
        $pipe = fopen(self::$pipes[$pipeName]['pipe_path'], 'r');
        if ($isSyn) {
            stream_set_blocking($pipe, $isSyn);
        }
        if ($pipe == NULL) {
            sem_release( self::$pipes[$pipeName]['r_sem_id'] );
            return false;
        }
        $data = fgets($pipe, $size);
        fclose($pipe);
        sem_release( self::$pipes[$pipeName]['r_sem_id'] );
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
        sem_acquire( self::$pipes[$pipeName]['r_sem_id'] );
        $pipe = fopen(self::$pipes[$pipeName]['pipe_path'], 'r');
        stream_set_blocking($pipe, false);
        $data = '';
        while (!feof($pipe)) {
            $data .= fread($pipe, $size);
        }
        fclose($pipe);
        sem_release( self::$pipes[$pipeName]['r_sem_id'] );
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
