<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Comment;
use app\index\model\Follow;

class Manage extends Base
{
    // 显示用户管理页面首页
    public function index()
    {
        // 判断是否登录，如果没有，定位到登录页面
        $this->isLogin();
        // 获取当前用户的文章的总数
        $articleCount = Article::where('author', $this->username)->count();
        // 获取当前用户的评论的总数
        $commentCount = Comment::where('author', $this->username)->count();
        // 获取当前用户的粉丝总数
        $fansCount = Follow::where('author', $this->username)->count();
        // 获取当前用户的关注的用户的总数
        $followCount = Follow::where('author', $this->username)->count();
        $allCategory = Article::group('category')->column('category');
        // 获取当前用户当天写的文章的总数
        $newArticleCount = Article::where('author', $this->username)->whereTime('create_time', 'today')->count();
        // 获取当前用户当天新增的粉丝的总数
        $newFansCount = Article::where('author', $this->username)->whereTime('create_time', 'today')->count();
        // 模板赋值
        $this->view->assign('articleCount', $articleCount);
        $this->view->assign('commentCount', $commentCount);
        $this->view->assign('fansCount', $fansCount);
        $this->view->assign('followCount', $followCount);
        $this->view->assign('allCategory', $allCategory);
        $this->view->assign('newArticleCount', $newArticleCount);
        $this->view->assign('newFansCount', $newFansCount);
        return $this->view->fetch('index');
    }

    // 显示文章页面
    public function article()
    {
        // 判断是否登录，如果没有，定位到登录页面
        $this->isLogin();
        return $this->view->fetch('article');
    }

    // 获取用户管理页面的文章页面的所有数据
    public function getArticleData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Article模型类
            $class = 'app\index\model\Article';
            // 用于搜索
            $searchName = 'title';
            // 用于搜索
            $where = 'author';
            // sql里的field，用于指定select的范围
            $field = 'id,title,status,update_time,category,comment_count,praise_count,collect_count,share_count';
            // 调用application目录下的common.php的getManageData函数
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示用户管理页面的评论页面
    public function comment()
    {
        // 判断是否登录，如果没有，定位到登录页面
        $this->isLogin();
        return $this->view->fetch('comment');
    }

    // 获取用户管理页面的评论页面的所有数据
    public function getCommentData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Comment模型类
            $class = 'app\index\model\Comment';
            // 用于搜索
            $searchName = 'content';
            // 用于搜索
            $where = 'author';
            // sql里的field，用于指定select的范围
            $field = 'id,username,content,comment_time,title,article_id';
            // 调用application目录下的common.php的getManageData函数
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示用户管理页面的关注页面、
    public function follow()
    {
        // 判断是否登录，如果没有，定位到登录页面
        $this->isLogin();
        return $this->view->fetch('follow');
    }

    // 获取用户管理页面的关注页面的所有数据
    public function getFollowData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Follow模型类
            $class = 'app\index\model\Follow';
            // 用于搜索
            $searchName = 'author';
            // 用于搜索
            $where = 'username';
            // sql里的field，用于指定select的范围
            $field = 'id,author,follow_time';
            // 调用application目录下的common.php的getManageData函数
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示用户管理页面的粉丝页面
    public function fans()
    {
        // 判断是否登录，如果没有，定位到登录页面
        $this->isLogin();
        return $this->view->fetch('fans');
    }

    // 获取用户管理页面的粉丝页面的所有数据
    public function getFansData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Follow模型类
            $class = 'app\index\model\Follow';
            // 用于搜索
            $searchName = 'username';
            // 用于搜索
            $where = 'author';
            // sql里的field，用于指定select的范围
            $field = 'id,username,follow_time';
            // 调用application目录下的common.php的getManageData函数
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示用户管理页面的私信页面
    public function receive()
    {
        // 判断是否登录，如果没有，定位到登录页面
        $this->isLogin();
        // 如果当前用户不是管理员，则显示receive页面
        if ($this->username !== $this->admin) {
            return $this->view->fetch('receive');
        } else {
            return $this->error('非法请求');
        }
    }

    // 获取用户管理页面的私信页面的所有数据
    public function getReceiveData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Receive模型类
            $class = 'app\index\model\Receive';
            // 用于搜索
            $searchName = 'content';
            // 用于搜索
            $where = 'username';
            // sql里的field，用于指定select的范围
            $field = 'id,content,receive_time';
            // 调用application目录下的common.php的getManageData函数
            $data = getManageData($class, $datatables, $searchName, $where, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }
}
