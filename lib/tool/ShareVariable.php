<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/11 下午5:11
 * Description: 共享变量,同一时刻只能有一个进程访问此类,尽量不要使用此类处理大数据，只用来赋值即可。
 */
class ShareVariable {

    /**
     * User: yuzhao
     * CreateTime: 2019/3/11 下午5:15
     * @var
     * Description: 锁id
     */
    private static $semId=null;

    /**
     * ShareVariable constructor.
     */
    public function __construct()
    {
        if (self::$semId === null) {
            $semKey = ftok( __FILE__, 'b' );
            self::$semId = sem_get($semKey);
        }
        sem_acquire(self::$semId);
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/11 下午6:40
     * @return ShareVariable
     * Description: 返回当前对象
     */
    public static function instance() {
        return new ShareVariable();
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/11 下午5:11
     * Description: 初始化共享变量
     */
    public function iniVar($vars) {
        foreach ($vars as $key => $value) {
            $GLOBALS[$key] = $value;
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/12 下午7:32
     * @param $var
     * @param $data
     * Description: 设置数据
     * @param null $callBack 当callback 不为null时, 可以拿共享变量进行处理，但尽量要快，因为会独占资源
     */
    public function setData($var, $data, $callBack=null) {
        if ($callBack  == null) {
            $GLOBALS[$var] = $data;
        } else {
            call_user_func($callBack, $GLOBALS[$var], $data);
        }
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/12 下午7:32
     * Description: push数据
     * @param $var
     * @param null $data
     */
    public function pushData($var, $data=null) {
        if (!is_array($GLOBALS[$var])) {
            $GLOBALS[$var] = array();
        }
        array_push($GLOBALS[$var], $data);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        sem_release(self::$semId);
    }
}