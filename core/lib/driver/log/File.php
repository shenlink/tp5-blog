<?php

namespace core\lib\driver\log;

use core\lib\Config;


// 日志文件存储方式
class File
{
    public $path;
    public function __construct()
    {
        $config = Config::get('OPTION', 'log');
        $this->path = $config['PATH'];
    }

    public function log($message, $file = 'log')
    {
        // 1. 确定文件存储位置是否存在
        //    新建目录
        // 2. 写入日志
        if (!is_dir($this->path . date('Ymd'))) {
            mkdir($this->path . date('Ymd'), 0777, true);
        }
        date_default_timezone_set('PRC');
        $message = date('Y-m-d H:i:s', time()) . ' ' . $message . "\r\n";
        return file_put_contents($this->path . date('Ymd') . '/' . $file . '.log', $message, FILE_APPEND);
    }
}
