<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Comment;
use app\index\model\Follow;
use app\index\model\Receive;

class Manage extends Base
{
    // 显示用户管理页面首页
    public function index()
    {
        $this->isLogin();
        $articleCount = Article::where('author', $this->username)->count();
        $commentCount = Comment::where('author', $this->username)->count();
        $fansCount = Follow::where('author', $this->username)->count();
        $followCount = Follow::where('author', $this->username)->count();
        $allCategory = Article::group('category')->column('category');
        $newArticleCount = Article::where('author', $this->username)->whereTime('create_time', 'today')->count();
        $newFansCount = Article::where('author', $this->username)->whereTime('create_time', 'today')->count();
        $this->view->assign('articleCount', $articleCount);
        $this->view->assign('commentCount', $commentCount);
        $this->view->assign('fansCount', $fansCount);
        $this->view->assign('followCount', $followCount);
        $this->view->assign('allCategory', $allCategory);
        $this->view->assign('newArticleCount', $newArticleCount);
        $this->view->assign('newFansCount', $newFansCount);
        return $this->view->fetch('index');
    }

    public function article()
    {
        $this->isLogin();
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
                    ->field('id,title,status,update_time,category,comment_count,praise_count,collect_count,share_count')
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
        } else {
            return $this->error('非法访问');
        }
    }

    public function comment()
    {
        $this->isLogin();
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
                    ->field('id,username,content,comment_time,title,article_id')
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
        } else {
            return $this->error('非法访问');
        }
    }

    public function follow()
    {
        $this->isLogin();
        return $this->view->fetch('follow');
    }


    public function getFollowData()
    {
        if (request()->isAjax()) {
            $follow = new Follow();
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
                $data = $follow
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('author', 'LIKE', "%$search%")
                    ->where('username', $this->username)
                    ->select();
                $keyword_all_data = $follow
                    ->where('author', 'LIKE', "%$search%")
                    ->where('username', $this->username)
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $follow
                    ->field('id,author,follow_time')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('username', $this->username)
                    ->select();
                $total = $follow::count();
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
        } else {
            return $this->error('非法访问');
        }
    }

    public function fans()
    {
        $this->isLogin();
        return $this->view->fetch('fans');
    }

    public function getFansData()
    {
        if (request()->isAjax()) {
            $follow = new Follow();
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
                $data = $follow
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('username', 'LIKE', "%$search%")
                    ->where('author', $this->username)
                    ->select();
                $keyword_all_data = $follow
                    ->where('username', 'LIKE', "%$search%")
                    ->where('author', $this->username)
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $follow
                    ->field('id,username,follow_time')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('author',$this->username)
                    ->select();
                $total = $follow::count();
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
        } else {
            return $this->error('非法访问');
        }
    }

    public function receive()
    {
        $this->isLogin();
        if($this->username !== $this->admin){
            return $this->view->fetch('receive');
        }else{
            return $this->error('非法请求');
        }

    }

    public function getReceiveData()
    {
        if (request()->isAjax()) {
            $receive = new Receive();
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
                $data = $receive
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->where('username', 'LIKE', "%$search%")
                    ->select();
                $keyword_all_data = $receive
                    ->where('username', 'LIKE', "%$search%")
                    ->select();
                $total = count($keyword_all_data); //获取满足关键词的总记录数
            } else {

                //没有关键词，则查询全部
                $data = $receive
                    ->field('id,content,receive_time')
                    ->order("$order_field $order")
                    ->limit($limit_start, $limit_length)
                    ->select();
                $total = $receive::count();
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
        } else {
            return $this->error('非法访问');
        }
    }
}
