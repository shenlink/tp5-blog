<?php

namespace core\lib;

use core\lib\Db;

// 模型类
class Model extends Db
{
    public function __construct()
    {
        // 工厂模式
        return Factory::createDatabase();
    }
}
