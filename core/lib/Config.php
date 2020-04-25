<?php

namespace core\lib;

/**
 * 配置类
 */
class Config
{

    public static $config = array();

    public static function get($name, $file)
    {

        //  1. 判断配置文件是否存在
        //  2. 判断对应配置是否存在
        //  3. 缓存配置
        if (isset(self::$config[$file])) {
            return self::$config[$file][$name];
        } else {
            $path = SHEN . '/config/' . $file . '.php';
            if (is_file($path)) {
                $config = include $path;
                if (isset($config[$name])) {
                    self::$config[$file] = $config;
                    return $config[$name];
                } else {
                    throw new \Exception('没有这个配置项' . $config[$name]);
                }
            } else {
                throw new \Exception('找不到配置文件' . $file);
            }
        }
    }

    static public function all($file)
    {
        if (isset(self::$config[$file])) {
            return self::$config[$file];
        } else {
            $path = SHEN . '/config/' . $file . '.php';
            if (is_file($path)) {
                $config = include $path;
                self::$config[$file] = $config;
                return $config;
            } else {
                throw new \Exception('找不到配置文件' . $file);
            }
        }
    }
}
