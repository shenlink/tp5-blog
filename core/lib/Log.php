<?php

namespace core\lib;

use core\lib\Config;

class Log
{
    public static $file;
    // 1.确定日志的存储方式
    // 2.写日志
    public static function init()
    {
        //确定存储方式
        $driver = Config::get('DRIVER', 'log');
        $class = '\core\lib\driver\log\\' . $driver;
        self::$file = new $class();
    }

    public static function log($message, $file = 'log')
    {
        //调用core\lib\driver下的File类的log方法
        self::$file->log($message, $file);
    }
}
