<?php

namespace core\lib;

use core\lib\Config;
use core\lib\Db;
use core\lib\Factory;

class Route extends Db
{

    public $controller;
    public $action;
    // 1. 隐藏index.php
    // 2. 获取URL参数部分
    // 3. 返回对应的控制器和方法
    public function __construct()
    {
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $path = $_SERVER['REQUEST_URI'];
            $prefix = substr($path, -5);
            if($prefix == '.html'){
                $path = trim($path,'.html');
            }
            $pathArray = explode('/', trim($path, '/'));
            if (count($pathArray) > 4) {
                $this->displayNone();
            }
            if (isset($pathArray[0])) {
                $this->controller = $pathArray[0];
            }
            if (isset($pathArray[1])) {
                $this->action = $pathArray[1];
            }
            $this->rule($pathArray);
        } else {
            $this->controller = Config::get('DEFAULT_CONTROLLER', 'route');
            $this->action = Config::get('DEFAULT_ACTION', 'route');
        }
    }

    public function rule($pathArray)
    {
        if (isset($pathArray[2]) && !isset($pathArray[3])) {
            $this->displayNone();
        }
        if (isset($pathArray[3])) {
            $controllerArray = ['admin', 'category', 'index'];
            if (in_array($pathArray[0], $controllerArray)) {
                if (!preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                    $this->displayNone();
                }
            }
        }

        if ($pathArray[0] == 'announcement' && $pathArray[1] == 'changeAnnouncement') {
            if ($pathArray[2] == 'id') {
                if (!preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                    $this->displayNone();
                }
                $this->type = $pathArray[2];
                $this->pagination = $pathArray[3];
            }else{
                $this->displayNone();
            }
        }

        if ($pathArray[0] == 'admin' && $pathArray[1] == 'manage') {
            $typeArray = ['user', 'article', 'category', 'comment', 'announcement', 'message'];
            if (in_array($pathArray[2], $typeArray)) {
                if (!preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                    $this->displayNone();
                }
                $this->type = $pathArray[2];
                $this->pagination = $pathArray[3];
            }
        }

        if ($pathArray[0] == 'article' && $pathArray[1] == 'search') {
            if (!preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                $this->displayNone();
            }
            $this->type = $pathArray[2];
            $this->pagination = $pathArray[3];
        }

        if ($pathArray[0] == 'article' && $pathArray[1] == 'editArticle') {
            if (!preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                $this->displayNone();
            }
            if ($pathArray[2] == 'article') {
                $this->type = $pathArray[2];
                $this->pagination = $pathArray[3];
            } else {
                $this->displayNone();
            }
        }

        if ($pathArray[0] == 'category') {
            $category = $this->table('category')->field('category')->where(['category' => "{$pathArray[1]}"])->select();
            if ($category) {
                $this->type = 'pagination';
                $this->pagination = $pathArray[3];
            }
            if (isset($pathArray[2]) && $pathArray[2] != 'pagination') {
                $this->displayNone();
            }
        }

        if ($pathArray[0] == 'index' && $pathArray[1] == 'index' && $pathArray[2] == 'pagination') {
            $this->type = 'pagination';
            $this->pagination = $pathArray[3];
        }
        if ($pathArray[0] == 'index' && $pathArray[1] == 'index') {
            if(isset($pathArray[2]) && $pathArray[2] != 'pagination'){
                $this->displayNone();
            }
        }

        if ($pathArray[0] == 'user') {
            $manageType = ['article', 'comment', 'follow', 'fans'];
            $userType = ['article', 'comment', 'praise', 'collect', 'share'];
            $username = $this->table('user')->where(['username' => "{$pathArray[1]}", 'status' => 1])->select();
            if ($pathArray[1] == 'manage') {
                if (isset($pathArray[3]) && !preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                    $this->displayNone();
                }
                if (in_array($pathArray[2], $manageType)) {
                    $this->type = $pathArray[2];
                    $this->pagination = $pathArray[3];
                }
                if (isset($pathArray[2]) && !in_array($pathArray[2], $manageType)) {
                    $this->displayNone();
                }
            }
            if ($pathArray[1] == 'search') {
                if (mb_strlen($pathArray[2]) <= 16) {
                    if (!preg_match('/^([1-9][0-9]*){1,10}$/', $pathArray[3])) {
                        $this->displayNone();
                    }
                    $this->type = $pathArray[2];
                    $this->pagination = $pathArray[3];
                } else {
                    $this->displayNone();
                }
            }
            if ($username) {
                if (in_array($pathArray[2], $userType)) {
                    $this->type = $pathArray[2];
                    $this->pagination = $pathArray[3];
                }
                if (isset($pathArray[2]) && !in_array($pathArray[2], $userType)) {
                    $this->displayNone();
                }
            }
        }

        if ($pathArray[0] == 'message' && $pathArray[1] == 'addMessage') {
            $username = $this->table('user')->field('username')->where(['username' => "{$pathArray[3]}", 'status' => 1])->select();
            if (!$username) {
                $this->displayNone();
            }
            if ($pathArray[2] == 'username') {
                $this->type = $pathArray[2];
                $this->pagination = $pathArray[3];
            } else {
                $this->displayNone();
            }
        }
    }

    public function displayNone()
    {
        session_start();
        $view = Factory::createView();
        $category = Factory::createCategory();
        $categorys = $category->getCategory();
        $username = $_SESSION['username'] ?? null;
        $view->assign('categorys', $categorys);
        $view->assign('username', $username);
        $view->assign('error', 'error');
        $view->display('error.html');
        exit();
    }
}
