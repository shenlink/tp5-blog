<?php

namespace app\model;

use core\lib\Model;

class Category extends Model
{
    private static $category;
    public static function getInstance()
    {
        if (self::$category) {
            return self::$category;
        } else {
            self::$category = new self();
            return self::$category;
        }
    }

    public function getCategory()
    {
        return $this->table('category')->field('category')->order('category_id asc')->selectAll();
    }

    public function checkCategory($category)
    {
        return $this->table('category')->field('category')->where(['category' => "{$category}"])->select();
    }

    // 处理添加分类
    public function addCategory($categoryName)
    {
        return $this->table('category')->insert(['category' => "{$categoryName}"]);
    }


    public function getAllCategory($currentPage = 1, $pageSize)
    {
        return $this->table('category')->field('category_id,category,article_count')->pages($currentPage, $pageSize, '/admin/manage', 'category');
    }
}
