<?php

namespace app\model;

use core\lib\Log;
use core\lib\Model;

class Follow extends Model
{

    private static $follow;
    public static function getInstance()
    {
        if (self::$follow) {
            return self::$follow;
        } else {
            self::$follow = new self();
            return self::$follow;
        }
    }

    public function getFollow($username, $currentPage = 1, $pageSize)
    {
        return $this->table('follow')->field('author,follow_at')->where(['username' => "{$username}"])->order('follow_at desc')->pages($currentPage, $pageSize, '/user/manage','follow');
    }

    public function getFans($username,$currentPage = 1, $pageSize)
    {
        return $this->table('follow')->field('username,follow_at')->where(['author' => "{$username}"])->order('follow_at desc')->pages($currentPage, $pageSize, '/user/manage', 'fans');
    }

    // 处理确认关注操作
    public function checkFollow($author, $username)
    {
        return $this->table('follow')->where(['author' => "{$author}", 'username' => "{$username}"])->select();
    }

    // 处理关注
    public function addFollow($author, $username, $follow_at)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $followSql = "insert into follow (author,username,follow_at) values (?,?,?)";
            $stmt = $pdo->prepare($followSql);
            $stmt->bindParam(1, $author);
            $stmt->bindParam(2, $username);
            $stmt->bindParam(3, $follow_at);
            $stmt->execute();
            $userFansSql = "update user set fans_count=fans_count+1 where username=?";
            $stmt = $pdo->prepare($userFansSql);
            $stmt->bindParam(1, $author);
            $stmt->execute();
            $userFollowSql = "update user set follow_count=follow_count+1 where username=?";
            $stmt = $pdo->prepare($userFollowSql);
            $stmt->bindParam(1, $username);
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

    // 处理取消关注
    public function cancelFollow($author, $username)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $followSql = "delete from follow where author=? and username=?";
            $stmt = $pdo->prepare($followSql);
            $stmt->bindParam(1, $author);
            $stmt->bindParam(2, $username);
            $stmt->execute();
            $userFansSql = "update user set fans_count=fans_count-1 where username=?";
            $stmt = $pdo->prepare($userFansSql);
            $stmt->bindParam(1, $author);
            $stmt->execute();
            $userFollowSql = "update user set follow_count=follow_count-1 where username=?";
            $stmt = $pdo->prepare($userFollowSql);
            $stmt->bindParam(1, $username);
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
