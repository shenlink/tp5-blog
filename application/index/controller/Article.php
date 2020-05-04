<?php

namespace app\index\controller;

use think\Db;
use think\Request;
use app\index\controller\Base;
use app\index\model\Category;
use app\index\model\Collect;
use app\index\model\Comment;
use app\index\model\Follow;
use app\index\model\Praise;
use app\index\model\Share;
use app\index\model\User;
use app\index\model\Article as ArticleModel;

class Article extends Base
{
    // 搜索相关操作的方法
    public function search(Request $request)
    {
        // 标志位，会在页头显示
        $type = '文章查询结果';
        // 获取查询条件
        $condition = $request->param('condition');
        // 使用模糊查询
        $condition = '%' . $condition . '%';
        // 获取符合搜索结果的文章并分页
        $articles = ArticleModel::where('title', 'like', $condition)->whereOr('content', 'like', $condition)->paginate(5);
        // 获取10片推荐文章
        $recommends = ArticleModel::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('recommends', $recommends);
        $this->view->assign('type', $type);
        $this->view->assign('articles', $articles);
        return $this->view->fetch('public/search');
    }

    // 显示write写文章页面
    public function write()
    {
        // 判断是否登录，如果没有，定位到错误页面
        $this->isLogin();
        return $this->view->fetch('write');
    }

    // 处理写文章页面提交的数据
    public function checkWrite(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '发表失败';
            $data = $request->post();
            $data['author'] = $this->username;
            $category = $data['category'];
            // 开启事务
            Db::startTrans();
            try {
                // 写入文章数据进文章表
                $articleResult = ArticleModel::create($data);
                // user表的article_count自增1
                $userResult = User::where('username', $this->username)->setInc('article_count');
                // category表的article_count自增1
                $categoryResult = Category::where('category', $category)->setInc('article_count');
                // 如果发生错误，抛出异常
                if (!($articleResult && $userResult && $categoryResult)) {
                    throw new \Exception('发生错误');
                }
                // 提交事务
                Db::commit();
                $status = 1;
                $message = '发表成功';
                return ['status' => $status, 'message' => $message];
            } catch (\Exception $e) {
                // 回滚
                Db::rollback();
                return ['status' => $status, 'message' => $message];
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 显示编辑文章页面
    public function editArticle(Request $request)
    {
        // 获取文章id
        $id = $request->param('id');
        // 获取文章的作者
        $author = ArticleModel::get(['id' => $id, 'status' => 1])->value('author');
        if ($author != $this->username) {
            $this->error('文章被拉黑或id不准确', '/');
        }
        // 获取文章内容
        $articles = ArticleModel::get(['id', $id]);
        // 模板赋值
        $this->view->assign('articles', $articles);
        return $this->view->fetch('edit');
    }

    // 处理文章编辑页面提交的数据
    public function checkEdit(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '修改失败';
            $data = $request->post();
            $condition = ['id' => $data['id']];
            // 确认更新文章内容
            $result = ArticleModel::update($data, $condition);
            if ($result == true) {
                $status = 1;
                $message = '修改成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 拉黑文章
    public function defriendArticle(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '拉黑失败';
            $id = $request->post('id');
            // 确认拉黑文章
            $result = ArticleModel::update(['status' => 0], ['id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '拉黑成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 恢复文章到正常状态
    public function normalArticle(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '恢复失败';
            $id = $request->post('id');
            // 恢复文章到正常状态
            $result = ArticleModel::update(['status' => 1], ['id' => $id]);
            if ($result == true) {
                $status = 1;
                $message = '恢复成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    //删除文章
    public function delArticle(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $id = $request->post('id');
            $category = $request->post('category');
            // 管理员页面的会传过来author，而manage页面不会
            $author = $request->post('author') ? $request->post('author') : $this->username;
            // 开启事务
            Db::startTrans();
            try {
                // 删除指定id的文章
                $articleResult = ArticleModel::destroy($id);
                // user表的article_count自减1
                $userResult = User::where('username', $author)->setDec('article_count');
                // category表的article_count自减1
                $categoryResult = Category::where('category', $category)->setDec('article_count');
                // 如果发生错误，则抛出异常
                if (!($articleResult && $userResult && $categoryResult)) {
                    throw new \Exception('发生错误');
                }
                // 提交事务
                Db::commit();
                $status = 1;
                $message = '删除成功';
                return ['status' => $status, 'message' => $message];
            } catch (\Exception $e) {
                // 回滚
                Db::rollback();
                return ['status' => $status, 'message' => $message];
            }
        } else {
            return $this->error('非法访问');
        }
    }

    // 获取当前用户新写的文章的分类
    public function getUserCategoryArticleCount(Request $request)
    {
        if ($request->isAjax()) {
            $time = $request->post('time');
            // 获取当前用户新写的文章所在的分类
            $result = ArticleModel::whereTime('create_time', $time)->where('author', $this->username)->column('category');

            // 新增多少个
            $result = array_count_values($result);
            // 获取$result的key作为数组
            $category = array_keys($result);
            // 获取$result的value作为数组
            $number = array_values($result);
            $data = ['category' => $category, 'number' => $number];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }

    // 获取所有用户新写的文章的分类
    public function getCategoryArticleCount(Request $request)
    {
        if ($request->isAjax()) {
            $time = $request->post('time');
            // 获取用户新写的文章所在的分类
            $result = ArticleModel::whereTime('create_time', $time)->column('category');

            // 新增多少个
            $result = array_count_values($result);
            // 获取$result的key作为数组
            $category = array_keys($result);
            // 获取$result的value作为数组
            $number = array_values($result);
            $data = ['category' => $category, 'number' => $number];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }

    // 新增的文章
    public function getNewArticleCount(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            // 获取时间范围，有today，month，year
            $time = $data['time'];
            $format = $data['format'];

            // 新增多少个
            $result = ArticleModel::whereTime('create_time', $time)->column("id,FROM_UNIXTIME(create_time, $format)");
            // 计算$result的不重复的值的总数，比如14点的值是2
            $result = array_count_values($result);
            $newPerTime = [];
            $type = '';
            if ($format == '"%k"') {
                // 获取一个一维数组，表示一天
                for ($i = 0; $i < 24; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(0, 23);
                $type = 'hour';
            } else if ($format == '"%e"') {
                $days = date("t") + 1;
                // 获取一个一维数组，表示一月
                for ($i = 1; $i < $days; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, $days - 1);
                $type = 'day';
            } else {
                // 获取一个一维数组，表示一年
                for ($i = 1; $i < 13; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 12);
                $type = 'month';
            }
            // 循环遍历$newPerTime
            foreach ($newPerTime as $key => $value) {
                foreach ($result as $k => $v) {
                    // 当$newPerTime的key与$result的key相同，比如14==14,14点就有2篇文章
                    if ($key == $k) {
                        $newPerTime[$key] = $v;
                    }
                }
            }
            // type是时间范围
            $data = ['type' => $type, 'rangeTime' => $rangeTime, 'newPerTime' => $newPerTime];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }

    // 新增的用户的文章
    public function getNewUserArticleCount(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->post();
            $time = $data['time'];
            $format = $data['format'];

            // 新增多少个
            $result = ArticleModel::whereTime('create_time', $time)->where('author', $this->username)->column("id,FROM_UNIXTIME(create_time, $format)");
            // 计算$result的不重复的值的总数，比如14点的值是2
            $result = array_count_values($result);
            $newPerTime = [];
            $type = '';
            if ($format == '"%k"') {
                // 获取一个一维数组，表示一天
                for ($i = 0; $i < 24; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(0, 23);
                $type = 'hour';
            } else if ($format == '"%e"') {
                $days = date("t") + 1;
                // 获取一个一维数组，表示一月
                for ($i = 1; $i < $days; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, $days - 1);
                $type = 'day';
            } else {
                // 获取一个一维数组，表示一年
                for ($i = 1; $i < 13; $i++) {
                    $newPerTime[$i] = 0;
                }
                $rangeTime = range(1, 12);
                $type = 'month';
            }
            // 循环遍历$newPerTime
            foreach ($newPerTime as $key => $value) {
                foreach ($result as $k => $v) {
                    // 当$newPerTime的key与$result的key相同，比如14==14,14点就有2篇文章，
                    if ($key == $k) {
                        $newPerTime[$key] = $v;
                    }
                }
            }
            // type是时间范围
            $data = ['type' => $type, 'rangeTime' => $rangeTime, 'newPerTime' => $newPerTime];
            return json_encode($data);
        } else {
            return $this->error('非法访问');
        }
    }

    // 当用户输入/article/+数字后，会访问这个方法
    public function post($id)
    {
        // 确认文章的状态
        $result = ArticleModel::get(['id' => $id, 'status' => 1]);
        // 如果文章的状态为正常
        if ($result) {
            // 获取指定id的文章的内容
            $articles = ArticleModel::get(['id', $id]);
            // 获取指定id的文章的评论并分页
            $comments = Comment::where(['article_id' => $id])->paginate(10);
            // 获取指定id的文章的作者的用户名
            $author = ArticleModel::where('id', $id)->value('author');
            // 查找非主键字段得用关联数组
            $users = User::get(['username' => $author]);
            // 确认当前用户是否关注当前文章的作者
            $followed = Follow::get(['username' => $this->username, 'author' => $author]);
            // 确认当前用户是否点赞了当前文章
            $praised =  Praise::get(['article_id' => $id, 'username' => $this->username]);
            // 确认当前用户是否收藏了当前文章
            $collected =  Collect::get(['article_id' => $id, 'username' => $this->username]);
            // 确认当前用户是否分享了当前文章
            $shared =  Share::get(['article_id' => $id, 'username' => $this->username]);
            // 获取推荐的10篇文章
            $recents = ArticleModel::where('author', $this->username)->field(['id', 'title'])->limit(5)->order('update_time', 'desc')->select();
            // 获取当前文章的作者获取的总点赞数
            $praise_count = Praise::where('author', $author)->count();
            // 获取当前文章的作者获取的总评论数
            $comment_count = Comment::where('author', $author)->count();
            // 模板赋值
            $this->view->assign('articles', $articles);
            $this->view->assign('collected', $collected);
            $this->view->assign('comments', $comments);
            $this->view->assign('followed', $followed);
            $this->view->assign('praised', $praised);
            $this->view->assign('praise_count', $praise_count);
            $this->view->assign('comment_count', $comment_count);
            $this->view->assign('recents', $recents);
            $this->view->assign('shared', $shared);
            $this->view->assign('users', $users);
            return $this->view->fetch('article');
        } else {
            $this->error('文章不存在或已被删除', '/');
        }
    }
}
