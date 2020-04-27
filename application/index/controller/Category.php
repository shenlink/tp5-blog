<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Category as CategoryModel;
use app\index\model\Article;
use think\Request;

class Category extends Base
{
    // 添加分类
    public function addCategory()
    {
        $addCategory = 'addCategory';
        $recommends = Article::where('status', 1)->field(['article_id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('addCategory', $addCategory);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }

    // 确认添加
    public function checkAddCategory(Request $request)
    {
        $status = 0;
        $message = '添加失败';
        $data = $request->post();
        $result = CategoryModel::create($data);
        if ($result == true) {
            $status = 1;
            $message = '添加成功';
        }
        return ['status' => $status, 'message' => $message];
    }

    public function _empty($category)
    {
        $result = CategoryModel::get(['category' => $category]);
        if (!$result) {
            $this->error('分类不存在', '/');
        }
        $articles = Article::all(['category' => $category]);
        $recommends = Article::where('status', 1)->field(['article_id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('articles', $articles);
        $this->view->assign('category', $category);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('category');
    }
}
