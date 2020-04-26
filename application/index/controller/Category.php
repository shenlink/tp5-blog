<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Category as CategoryModel;
use app\index\model\Article;

class Category extends Base
{
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
