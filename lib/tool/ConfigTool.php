<?php
/**
 * @CreateTime:   2019/5/18 上午10:55
 * @Author:       yuzhao  <tuzisir@163.com>
 * @Copyright:    copyright(2019) yuzhao all rights reserved
 * @Description:  配置工具
 */

class ConfigTool extends BaseTool {

    /**
     * 配置信息
     *
     * @var array
     * CreateTime: 2019/5/18 上午10:56
     */
    private static $config=array();

    /**
     * ConfigTool constructor.
     */
    public function __construct()
    {
        self::$config = $GLOBALS['CONFIG'];
    }

    /**
     * 获取所有配置
     *
     * @return array
     * CreateTime: 2019/5/18 上午10:57
     */
    public function getAllConfig() {
        return self::$config;
    }

    /**
     * 根据配置key获取配置信息
     *
     * @param $configName
     * CreateTime: 2019/5/18 上午10:57
     * @return mixed
     */
    public function getConfig($configName) {
        return self::$config[$configName];
    }
}