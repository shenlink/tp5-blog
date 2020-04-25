<?php

namespace core\common;

use core\lib\Controller;
use core\lib\Log;

class Start
{
    // 启动框架
    static public function run()
    {
        Log::init();
        Controller::check();
    }
}