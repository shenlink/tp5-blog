<?php

namespace core\lib;

use core\lib\Config;
use core\lib\Log;
use core\lib\Route;
use core\lib\Factory;


// 控制器基类
class Controller
{

    public function __construct()
    {
        session_start();
        $this->announcement = Factory::createAnnouncement();
        $this->article = Factory::createArticle();
        $this->category = Factory::createCategory();
        $this->collect = Factory::createCollect();
        $this->comment = Factory::createComment();
        $this->follow = Factory::createFollow();
        $this->message = Factory::createMessage();
        $this->praise = Factory::createPraise();
        $this->receive = Factory::createReceive();
        $this->share = Factory::createShare();
        $this->user = Factory::createUser();
        $this->view = Factory::createView();
        $this->categorys = $this->category->getCategory();
        $this->admin = Config::get('admin','access');
        $this->username = $_SESSION['username'] ?? null;
        $this->view->assign('categorys', $this->categorys);
        $this->view->assign('username', $this->username);
        date_default_timezone_set('PRC');
        $this->time = date('Y-m-d H:i:s', time());
        $this->view->assign('admin',$this->admin);
    }

    // 执行前调用
    public function beforeAction($action)
    {
        session_start();
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            Log::log("用户{$username}:" . 'beforeAction:' .  $action);
        } else {
            Log::log('beforeAction:' . $action);
        }
    }

    // 执行后调用
    public function afterAction($action)
    {
        session_start();
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            Log::log("用户{$username}:" . 'afterAction:' . $action);
        } else {
            Log::log('afterAction:' . $action);
        }
    }

    public static function check()
    {
        $route = new Route();
        $controller = $route->controller;
        $action = $route->action;
        $type = $route->type;
        $pagination = $route->pagination;
        $controllerFile = APP . '/controller/' . $controller . '.php';
        $controllerClass = '\\app' . '\\controller\\' . $controller;
        if (is_file($controllerFile)) {
            include $controllerFile;
            $controller = new $controllerClass();
            $controller->beforeAction($action);
            if($type && $pagination){
                $controller->$action($type, $pagination);
            }else{
                $controller->$action();
            }
            $controller->afterAction($action);
            session_start();
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                Log::log("用户{$username}:" . 'controller:' . $controllerClass . ' action:' . $action . "\r\n");
            } else {
                Log::log('controller:' . $controllerClass . ' action:' . $action . "\r\n");
            }
        } else {
            session_start();
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                Log::log("用户{$username}:" . '找不到控制器' . $controllerClass . "\r\n");
            } else {
                Log::log('找不到控制器' . $controllerClass . "\r\n");
            }
            $view = Factory::createView();
            $view->assign('error', 'error');
            $view->display('error.html');
        }
    }
}
