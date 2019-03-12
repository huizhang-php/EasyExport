<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/26 下午6:34
 * Description:
 */

class SignalMemory {

    private static $semId = null;
    private static $shmId = null;
    const SHM_VAR = 1;

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午6:47
     * Description: 创建共享内存
     */
    public static function createSM($size=1024) {
        $sem_key = ftok( __FILE__, 'b' );
        self::$semId = sem_get( $sem_key );
        $shm_key = ftok( __FILE__, 'm' );
        self::$shmId = shm_attach( $shm_key, $size, 0666 );
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午7:08
     * Description: 释放共享内存
     */
    public static function closeSM() {
        shm_remove( self::$semId );
        shm_detach( self::$shmId );
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午7:07
     * Description: 获得锁
     */
    public static function getLock() {
        // 获取锁
        sem_acquire( self::$semId );
        if( shm_has_var( self::$shmId, SignalMemory::SHM_VAR ) ){
            $counter = shm_get_var( self::$shmId, SignalMemory::SHM_VAR );
            $counter += 1;
            shm_put_var( self::$shmId, SignalMemory::SHM_VAR, $counter );
        } else {
            $counter = 1;
            shm_put_var( self::$shmId, SignalMemory::SHM_VAR, $counter );
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午7:07
     * Description: 释放锁
     */
    public static function closeLock() {
        // 释放锁
        sem_release( self::$semId );
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午7:09
     * @param $func
     * @param $params
     * @return mixed
     * Description: 操作共享数据
     */
    public static function opShareData($func, $params) {
        self::getLock();
        $res = $func($params);
        self::closeLock();
        return $res;
    }
}