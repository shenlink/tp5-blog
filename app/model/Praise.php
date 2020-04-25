<?php

namespace app\model;

use core\lib\Log;
use core\lib\Model;

class Praise extends Model
{
    private static $praise;
    public static function getInstance()
    {
        if (self::$praise) {
            return self::$praise;
        } else {
            self::$praise = new self();
            return self::$praise;
        }
    }

    // 处理确认点赞操作
    public function checkPraise($article_id, $username)
    {
        return $this->table('praise')->where(['username' => "{$username}", 'article_id' => "{$article_id}"])->select();
    }

    // 处理点赞
    public function addPraise($article_id, $author, $title, $username,  $praise_at)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $praiseSql = "insert into praise (article_id,author,title,praise_at,username) values (?,?,?,?,?)";
            $stmt = $pdo->prepare($praiseSql);
            $stmt->bindParam(1, $article_id);
            $stmt->bindParam(2, $author);
            $stmt->bindParam(3, $title);
            $stmt->bindParam(4, $praise_at);
            $stmt->bindParam(5, $username);
            $stmt->execute();
            $articleSql = "update article set praise_count=praise_count+1 where article_id=?";
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

    // 处理取消点赞
    public function cancelPraise($article_id, $username)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $praiseSql = "delete from praise where article_id=? and username=?";
            $stmt = $pdo->prepare($praiseSql);
            $stmt->bindParam(1, $article_id);
            $stmt->bindParam(2, $username);
            $stmt->execute();
            $articleSql = "update article set praise_count=praise_count-1 where article_id=?";
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

    public function delPraise($article_id, $praise_id)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $praiseSql = "delete from praise where praise_id=?";
            $stmt = $pdo->prepare($praiseSql);
            $stmt->bindParam(1, $praise_id);
            $stmt->execute();
            $articleSql = "update article set praise_count=praise_count-1 where article_id=?";
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

    public function getPraise($username, $currentPage = 1, $pageSize)
    {
        return $this->table('praise')->field('praise_id,article_id,author,title,praise_at')->where(['username' => "{$username}"])->order('praise_at desc')->pages($currentPage, $pageSize, "/user/{$username}", 'praise');
    }

    public function getPraiseCount($username)
    {
        return $this->table('praise')->where(['username' => "{$username}"])->count();
    }
}
