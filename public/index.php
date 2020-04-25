<?php


//定义根目录
define('SHEN',str_replace('\\','/',dirname(dirname(realpath(__FILE__)))));
define('CORE',SHEN.'/core');
define('APP',SHEN.'/app');
define('DEBUG', true);

if (DEBUG) {
    ini_set('display_error', 'On');
} else {
    ini_set('display_error', 'Off');
}
// 引入自动加载类
include CORE.'/common/Loader.php';


spl_autoload_register('\core\common\Loader::autoload');

\core\common\Start::run();
