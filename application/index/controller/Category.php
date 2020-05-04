<?php

namespace app\index\controller;

use app\index\controller\Base;
use app\index\model\Category as CategoryModel;
use app\index\model\Article;
use think\Request;

class Category extends Base
{
    // 展示添加分类页面
    public function addCategory()
    {
        // 标志位，会显示添加分类的内容
        $addCategory = 'addCategory';
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('addCategory', $addCategory);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }

    // 确认添加
    public function checkAddCategory(Request $request)
    {
        if ($request->isAjax()) {
            $status = 0;
            $message = '添加失败';
            $data = $request->post();
            // 确认添加分类
            $result = CategoryModel::create($data);
            if ($result == true) {
                $status = 1;
                $message = '添加成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 当用户输入/category/+分类名后，会访问这个方法
    public function category($category)
    {
        // 先确认有没有这个分类
        $result = CategoryModel::get(['category' => $category]);
        // 分类不存在，定位到错误页面
        if (!$result) {
            $this->error('分类不存在', '/');
        }
        // 获取该分类下的所有文章
        $articles = Article::where(['category' => $category])->order('create_time desc')->paginate(5);
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('articles', $articles);
        $this->view->assign('category', $category);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('category');
    }
}
