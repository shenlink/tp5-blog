<?php

namespace app\model;

use core\lib\Log;
use core\lib\Model;

class Collect extends Model
{
    private static $collect;
    public static function getInstance()
    {
        if (self::$collect) {
            return self::$collect;
        } else {
            self::$collect = new self();
            return self::$collect;
        }
    }

    public function getCollect($username, $currentPage = 1, $pageSize)
    {
        return $this->table('collect')->field('collect_id,article_id,author,title,collect_at')->where(['username' => "{$username}"])->order('collect_at desc')->pages($currentPage, $pageSize, "/user/{$username}", 'collect');
    }

    // 处理确认收藏操作
    public function checkCollect($article_id, $username)
    {
        return $this->table('collect')->where(['article_id' => "{$article_id}", 'username' => "{$username}"])->select();
    }

    // 添加收藏
    public function addCollect($article_id, $author, $title, $username, $collect_at)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $collectSql = "insert into collect (article_id,author,username,title,collect_at) values (?,?,?,?,?)";
            $stmt = $pdo->prepare($collectSql);
            $stmt->bindParam(1, $article_id);
            $stmt->bindParam(2, $author);
            $stmt->bindParam(3, $username);
            $stmt->bindParam(4, $title);
            $stmt->bindParam(5, $collect_at);
            $stmt->execute();
            $articleSql = "update article set collect_count=collect_count+1 where article_id=?";
            $stmt = $pdo->prepare($articleSql);
            $stmt->bindParam(1, $article_id);
            $stmt->execute();
            $pdo->commit();
            return true;
        } catch (\PDOException $e) {
            Log::init();
            session_start();
            $username = $_SESSION['username'];
            Log::log("用户{$username}:" . '执行sql语句发生错误:' . $e->getMessage());
            $pdo->rollBack();
            return false;
        }
    }

    // 取消收藏
    public function cancelCollect($article_id, $username)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $collectSql = "delete from collect where article_id=? and username=?";
            $stmt = $pdo->prepare($collectSql);
            $stmt->bindParam(1, $article_id);
            $stmt->bindParam(2, $username);
            $stmt->execute();
            $articleSql = "update article set collect_count=collect_count-1 where article_id=?";
            $stmt = $pdo->prepare($articleSql);
            $stmt->bindParam(1, $article_id);
            $stmt->execute();
            $pdo->commit();
            return true;
        } catch (\PDOException $e) {
            Log::init();
            session_start();
            $username = $_SESSION['username'];
            Log::log("用户{$username}:" . '执行sql语句发生错误:' . $e->getMessage());
            $pdo->rollBack();
            return false;
        }
    }

    // 删除收藏
    public function delCollect($article_id, $collect_id)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $collectSql = "delete from collect where collect_id=?";
            $stmt = $pdo->prepare($collectSql);
            $stmt->bindParam(1, $collect_id);
            $stmt->execute();
            $articleSql = "update article set collect_count=collect_count-1 where article_id=?";
            $stmt = $pdo->prepare($articleSql);
            $stmt->bindParam(1, $article_id);
            $stmt->execute();
            $pdo->commit();
            return true;
        } catch (\PDOException $e) {
            Log::init();
            session_start();
            $username = $_SESSION['username'];
            Log::log("用户{$username}:" . '执行sql语句发生错误:' . $e->getMessage());
            $pdo->rollBack();
            return false;
        }
    }
}
