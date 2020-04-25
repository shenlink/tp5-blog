<?php


namespace core\lib;

use app\model\Announcement;
use app\model\Article;
use app\model\Category;
use app\model\Collect;
use app\model\Comment;
use app\model\Follow;
use app\model\Message;
use app\model\Praise;
use app\model\Share;
use app\model\Receive;
use app\model\User;
use core\lib\Db;
use core\lib\View;
use core\lib\RegisterTree;

//工厂模式
class Factory
{
    public static function createArticle()
    {
        $key = 'article';
        $article = RegisterTree::get($key);
        if (!$article) {
            $article = Article::getInstance();
            RegisterTree::set('article', $article);
        }
        return $article;
    }

    public static function createAnnouncement()
    {
        $key = 'announcement';
        $announcement = RegisterTree::get($key);
        if (!$announcement) {
            $announcement = Announcement::getInstance();
            RegisterTree::set('announcement', $announcement);
        }
        return $announcement;
    }

    public static function createCategory()
    {
        $key = 'category';
        $category = RegisterTree::get($key);
        if (!$category) {
            $category = Category::getInstance();
            RegisterTree::set('category', $category);
        }
        return $category;
    }

    public static function createCollect()
    {
        $key = 'collect';
        $collect = RegisterTree::get($key);
        if (!$collect) {
            $collect = Collect::getInstance();
            RegisterTree::set('collect', $collect);
        }
        return $collect;
    }

    public static function createComment()
    {
        $key = 'comment';
        $comment = RegisterTree::get($key);
        if (!$comment) {
            $comment = Comment::getInstance();
            RegisterTree::set('comment', $comment);
        }
        return $comment;
    }

    public static function createFollow()
    {
        $key = 'follow';
        $follow = RegisterTree::get($key);
        if (!$follow) {
            $follow = Follow::getInstance();
            RegisterTree::set('follow', $follow);
        }
        return $follow;
    }

    public static function createMessage()
    {
        $key = 'message';
        $message = RegisterTree::get($key);
        if (!$message) {
            $message = Message::getInstance();
            RegisterTree::set('message', $message);
        }
        return $message;
    }

    public static function createPraise()
    {
        $key = 'praise';
        $praise = RegisterTree::get($key);
        if (!$praise) {
            $praise = Praise::getInstance();
            RegisterTree::set('praise', $praise);
        }
        return $praise;
    }

    public static function createShare()
    {
        $key = 'share';
        $share = RegisterTree::get($key);
        if (!$share) {
            $share = Share::getInstance();
            RegisterTree::set('share', $share);
        }
        return $share;
    }

    public static function createReceive()
    {
        $key = 'receive';
        $receive = RegisterTree::get($key);
        if (!$receive) {
            $receive = Receive::getInstance();
            RegisterTree::set('receive', $receive);
        }
        return $receive;
    }

    public static function createUser()
    {
        $key = 'user';
        $user = RegisterTree::get($key);
        if (!$user) {
            $user = User::getInstance();
            RegisterTree::set('user', $user);
        }
        return $user;
    }

    public static function createDatabase()
    {
        $key = 'shen';
        $db = RegisterTree::get($key);
        if (!$db) {
            $db = Db::getInstance();
            RegisterTree::set('shen', $db);
        }
        return $db;
    }

    public static function createView()
    {
        $key = 'view';
        $view = RegisterTree::get($key);
        if (!$view) {
            $view = View::getInstance();
            RegisterTree::set('view', $view);
        }
        return $view;
    }
}
