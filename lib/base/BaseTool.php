<?php
/**
 * @CreateTime:   2019/5/18 上午10:05
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  工具基类
 */

class BaseTool {

    /**
     * 工具对象池
     *
     * @var array
     * CreateTime: 2019/5/18 上午10:14
     */
    protected static $toolObjs = array();

    /**
     * 返回当前对象
     *
     * @return FileTool | ConfigTool
     * CreateTime: 2019/5/18 上午10:15
     */
    public static function instance() {
        $objName = get_called_class();
        if (!isset(self::$toolObjs[$objName])) {
            self::$toolObjs[$objName] = new $objName;
        }
        return self::$toolObjs[$objName];
    }

}