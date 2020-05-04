<?php

namespace app\admin\controller;

use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Category;
use app\index\model\Comment;
use app\index\model\User;

class Index extends Base
{
    // 展示管理员页面的首页
    public function index()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        // 获取所有的用户的数量，包含被软删除的
        $userCount = User::withTrashed()->count();
        // 获取所有的文章的数量
        $articleCount = Article::count();
        // 获取所有的评论的数量
        $commentCount = Comment::count();
        // 获取所有的分类的数量
        $categoryCount = Category::count();
        // 获取所有的文章表中的分类数量
        $allCategory = Article::group('category')->column('category');
        // 获取今天注册的用户的数量
        $newUserCount = User::whereTime('create_time', 'today')->count();
        // 获取今天发表的文章的数量
        $newArticleCount = Article::whereTime('create_time', 'today')->count();
        // 给模板赋值
        $this->view->assign('userCount', $userCount);
        $this->view->assign('articleCount', $articleCount);
        $this->view->assign('commentCount', $commentCount);
        $this->view->assign('newUserCount', $newUserCount);
        $this->view->assign('categoryCount', $categoryCount);
        $this->view->assign('allCategory', $allCategory);
        $this->view->assign('newArticleCount', $newArticleCount);
        return $this->view->fetch('index');
    }

    // 展示管理员页面的用户页面
    public function user()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('user');
    }

    // 获取管理员页面的用户页面的所有数据
    public function getUserData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // User模型类
            $class = 'app\index\model\User';
            // 用于搜索
            $searchName = 'username';
            // sql里的field，用于指定select的范围
            $field = 'id,username,role,article_count,follow_count,fans_count,create_time,status';
            // 调用application目录下的common.php的getAdminData函数
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示管理员页面的文章页面
    public function article()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('article');
    }

    // 获取管理员页面的文章页面的所有数据
    public function getArticleData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Article模型类
            $class = 'app\index\model\Article';
            // 用于搜索
            $searchName = 'title';
            // sql里的field，用于指定select的范围
            $field = 'id,author,title,status,update_time,category,comment_count,praise_count,collect_count,share_count';
            // 调用application目录下的common.php的getAdminData函数
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示管理员页面的评论页面
    public function comment()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('comment');
    }

    // 获取管理员页面的评论页面的所有数据
    public function getCommentData()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Comment模型类
            $class = 'app\index\model\Comment';
            // 用于搜索
            $searchName = 'content';
            // sql里的field，用于指定select的范围
            $field = 'id,content,comment_time,article_id,title,username';
            // 调用application目录下的common.php的getAdminData函数
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示管理员页面的分类页面
    public function category()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('category');
    }

    // 获取管理员页面的分类页面的所有数据
    public function getCategoryData()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Category模型类
            $class = 'app\index\model\Category';
            // 用于搜索
            $searchName = 'category';
            // sql里的field，用于指定select的范围
            $field = 'id,category,article_count,create_time';
            // 调用application目录下的common.php的getAdminData函数
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示管理员页面的公告页面
    public function announcement()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('announcement');
    }

    // 获取管理员页面的公告页面的所有数据
    public function getAnnouncementData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Announcement模型类
            $class = 'app\index\model\Announcement';
            // 用于搜索
            $searchName = 'content';
            // sql里的field，用于指定select的范围
            $field = 'id,content,create_time';
            // 调用application目录下的common.php的getAdminData函数
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示管理员页面的私信页面
    public function message()
    {
        // 判断是否是管理员，如果不是，定位到错误页面
        if ($this->isNotAdmin()) {
            return $this->error('你不是管理员');
        }
        return $this->view->fetch('message');
    }

    // 获取管理员页面的私信页面的所有数据
    public function getMessageData()
    {
        if (request()->isAjax()) {
            $datatables = request()->post();
            // Message模型类
            $class = 'app\index\model\Message';
            // 用于搜索
            $searchName = 'username';
            // sql里的field，用于指定select的范围
            $field = 'id,username,content,message_time';
            // 调用application目录下的common.php的getAdminData函数
            $data = getAdminData($class, $datatables, $searchName, $field);
            return $data;
        } else {
            return $this->error('非法访问');
        }
    }
}
