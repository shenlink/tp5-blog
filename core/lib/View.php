<?php

namespace core\lib;

include_once SHEN . '/core/common/smarty/Smarty.class.php';
class View
{
    private static $view;
    public $assign = array();
    private function __construct()
    {

    }
    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (self::$view) {
            return self::$view;
        } else {
            self::$view = new self();
            return self::$view;
        }
    }
    public function assign($name, $value)
    {
        $this->assign[$name] = $value;
    }
    public function display($file)
    {
        $file = APP . '/view/' . $file;
        if (is_file($file)) {
            $smarty = new \Smarty();
            $smarty->caching = false;
            $smarty->template_dir = APP.'/view';
            $smarty->compile_dir = SHEN.'/runtime/smarty/templates_c';
            $smarty->cache_dir = SHEN."/runtime/smarty/cache";
            $smarty->cache_lifetime = 60;
            $smarty->left_delimiter = "{";
            $smarty->right_delimiter = "}";
            $smarty->assign($this->assign);
            $smarty->display($file);
        }
    }
}
