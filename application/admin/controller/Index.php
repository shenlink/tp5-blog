<?php

namespace app\admin\controller;

use app\index\controller\Base;
use app\index\model\Announcement;
use app\index\model\Article;
use app\index\model\Category;
use app\index\model\Comment;
use app\index\model\Message;
use app\index\model\User;

class Index extends Base
{
    public function index()
    {
        $this->isLogin();
        if ($this->username == $this->admin) {
            return $this->view->fetch('index');
        } else {
            $this->error('你不是管理员', '/');
        }
    }

    public function user()
    {
        $users = User::order('create_time desc')->paginate(5);
        $this->view->assign('users', $users);
        return $this->view->fetch('user');
    }


    public function getUserData()
    {
        if (request()->isAjax()) {
            $user = new User();
            //接受请求
            $datatables = request()->post();
            //得到排序的方式
            $order = $datatables['order'][0]['dir'];
            //得到排序字段的下标
            $order_column = $datatables['order'][0]['column'];
            //根据排序字段的下标得到排序字段
            $order_field = $datatables['columns'][$order_column]['data'];
            //得到limit参数
            $limit_start = $datatables['start'];
            $limit_length = $datatables['length'];
            //得到搜索的关键词
            $search = $datatables['search']['value'];

            //如有搜索行为，则按照姓名进行模糊查询
            if ($search) {
                $data = $user
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('username', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $user
                    ->where('username', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $user
                    ->where('is_delete', 1)
                    ->field('id,username,role,article_count,follow_count,fans_count,create_time,status')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $user->count();
            }

            if ($data) {
                $data = collection($data)->toArray();
            }
            $draw = request()->post('draw');
            $AllData = [
                // ajax的请求次数，创建唯一标识
                'draw' => $draw,
                // 结果数
                'recordsTotal' => count($data),
                // 总数据量
                'recordsFiltered' => $total,
                // 总数据
                'data' => $data,
            ];
            return json($AllData);
        }
    }

    public function article()
    {
        $articles = Article::order('create_time desc')->paginate(5);
        $this->view->assign('articles', $articles);
        return $this->view->fetch('article');
    }

    public function getArticleData()
    {
        if (request()->isAjax()) {
            $article = new Article();
            //接受请求
            $datatables = request()->post();
            //得到排序的方式
            $order = $datatables['order'][0]['dir'];
            //得到排序字段的下标
            $order_column = $datatables['order'][0]['column'];
            //根据排序字段的下标得到排序字段
            $order_field = $datatables['columns'][$order_column]['data'];
            //得到limit参数
            $limit_start = $datatables['start'];
            $limit_length = $datatables['length'];
            //得到搜索的关键词
            $search = $datatables['search']['value'];

            //如有搜索行为，则按照姓名进行模糊查询
            if ($search) {
                $data = $article
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('title', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $article
                    ->where('title', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $article
                    ->where('is_delete', 1)
                    ->field('id,author,title,status,update_time,fans_count,category,comment_count,praise_count,collect_count')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $article->count();
            }

            if ($data) {
                $data = collection($data)->toArray();
            }
            $draw = request()->post('draw');
            $AllData = [
                // ajax的请求次数，创建唯一标识
                'draw' => $draw,
                // 结果数
                'recordsTotal' => count($data),
                // 总数据量
                'recordsFiltered' => $total,
                // 总数据
                'data' => $data,
            ];
            return json($AllData);
        }
    }

    public function comment()
    {
        $comments = Comment::order('comment_time desc')->paginate(5);
        $this->view->assign('comments', $comments);
        return $this->view->fetch('comment');
    }

    public function getCommentData()
    {
        if (request()->isAjax()) {
            $comment = new Comment();
            //接受请求
            $datatables = request()->post();
            //得到排序的方式
            $order = $datatables['order'][0]['dir'];
            //得到排序字段的下标
            $order_column = $datatables['order'][0]['column'];
            //根据排序字段的下标得到排序字段
            $order_field = $datatables['columns'][$order_column]['data'];
            //得到limit参数
            $limit_start = $datatables['start'];
            $limit_length = $datatables['length'];
            //得到搜索的关键词
            $search = $datatables['search']['value'];

            //如有搜索行为，则按照姓名进行模糊查询
            if ($search) {
                $data = $comment
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('content', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $comment
                    ->where('content', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $comment
                    ->where('is_delete', 1)
                    ->field('id,content,comment_time,username,article_id')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $comment->count();
            }

            if ($data) {
                $data = collection($data)->toArray();
            }
            $draw = request()->post('draw');
            $AllData = [
                // ajax的请求次数，创建唯一标识
                'draw' => $draw,
                // 结果数
                'recordsTotal' => count($data),
                // 总数据量
                'recordsFiltered' => $total,
                // 总数据
                'data' => $data,
            ];
            return json($AllData);
        }
    }

    public function category()
    {
        $AllCategorys = Category::order('create_time desc')->paginate(5);
        $this->view->assign('AllCategorys', $AllCategorys);
        return $this->view->fetch('category');
    }

    public function getCategoryData()
    {
        if (request()->isAjax()) {
            $category = new Category();
            //接受请求
            $datatables = request()->post();
            //得到排序的方式
            $order = $datatables['order'][0]['dir'];
            //得到排序字段的下标
            $order_column = $datatables['order'][0]['column'];
            //根据排序字段的下标得到排序字段
            $order_field = $datatables['columns'][$order_column]['data'];
            //得到limit参数
            $limit_start = $datatables['start'];
            $limit_length = $datatables['length'];
            //得到搜索的关键词
            $search = $datatables['search']['value'];

            //如有搜索行为，则按照姓名进行模糊查询
            if ($search) {
                $data = $category
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('category', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $category
                    ->where('category', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $category
                    ->where('is_delete', 1)
                    ->field('id,category,article_count')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $category->count();
            }

            if ($data) {
                $data = collection($data)->toArray();
            }
            $draw = request()->post('draw');
            $AllData = [
                // ajax的请求次数，创建唯一标识
                'draw' => $draw,
                // 结果数
                'recordsTotal' => count($data),
                // 总数据量
                'recordsFiltered' => $total,
                // 总数据
                'data' => $data,
            ];
            return json($AllData);
        }
    }

    public function announcement()
    {
        $announcements = Announcement::order('create_time desc')->paginate(5);
        $this->view->assign('announcements', $announcements);
        return $this->view->fetch('announcement');
    }

    public function getAnnouncementData()
    {
        if (request()->isAjax()) {
            $announcement = new Announcement();
            //接受请求
            $datatables = request()->post();
            //得到排序的方式
            $order = $datatables['order'][0]['dir'];
            //得到排序字段的下标
            $order_column = $datatables['order'][0]['column'];
            //根据排序字段的下标得到排序字段
            $order_field = $datatables['columns'][$order_column]['data'];
            //得到limit参数
            $limit_start = $datatables['start'];
            $limit_length = $datatables['length'];
            //得到搜索的关键词
            $search = $datatables['search']['value'];

            //如有搜索行为，则按照姓名进行模糊查询
            if ($search) {
                $data = $announcement
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('content', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $announcement
                    ->where('content', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $announcement
                    ->where('is_delete', 1)
                    ->field('id,content,create_time')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $announcement->count();
            }

            if ($data) {
                $data = collection($data)->toArray();
            }
            $draw = request()->post('draw');
            $AllData = [
                // ajax的请求次数，创建唯一标识
                'draw' => $draw,
                // 结果数
                'recordsTotal' => count($data),
                // 总数据量
                'recordsFiltered' => $total,
                // 总数据
                'data' => $data,
            ];
            return json($AllData);
        }
    }

    public function message()
    {
        $messages = Message::order('message_time desc')->paginate(5);
        $this->view->assign('messages', $messages);
        return $this->view->fetch('message');
    }

    public function getMessageData()
    {
        if (request()->isAjax()) {
            $message = new Message();
            //接受请求
            $datatables = request()->post();
            //得到排序的方式
            $order = $datatables['order'][0]['dir'];
            //得到排序字段的下标
            $order_column = $datatables['order'][0]['column'];
            //根据排序字段的下标得到排序字段
            $order_field = $datatables['columns'][$order_column]['data'];
            //得到limit参数
            $limit_start = $datatables['start'];
            $limit_length = $datatables['length'];
            //得到搜索的关键词
            $search = $datatables['search']['value'];

            //如有搜索行为，则按照姓名进行模糊查询
            if ($search) {
                $data = $message
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('username', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $message
                    ->where('username', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $message
                    ->where('is_delete', 1)
                    ->field('id,username,content,message_time')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $message->count();
            }

            if ($data) {
                $data = collection($data)->toArray();
            }
            $draw = request()->post('draw');
            $AllData = [
                // ajax的请求次数，创建唯一标识
                'draw' => $draw,
                // 结果数
                'recordsTotal' => count($data),
                // 总数据量
                'recordsFiltered' => $total,
                // 总数据
                'data' => $data,
            ];
            return json($AllData);
        }
    }
}
