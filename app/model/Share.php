<?php

namespace app\model;

use core\lib\Log;
use core\lib\Model;

class Share extends Model
{
    private static $share;
    public static function getInstance()
    {
        if (self::$share) {
            return self::$share;
        } else {
            self::$share = new self();
            return self::$share;
        }
    }

    // 处理确认分享操作,这应该只传入一个id就可以了
    public function checkShare( $article_id,$username)
    {
        return $this->table('share')->where([ 'article_id' => "{$article_id}",'username' => "{$username}"])->select();
    }

    // 处理分享
    public function addShare($article_id, $author, $title,$username,  $share_at)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $shareSql = "insert into share (article_id,author,title,share_at,username) values (?,?,?,?,?)";
            $stmt = $pdo->prepare($shareSql);
            $stmt->bindParam(1, $article_id);
            $stmt->bindParam(2, $author);
            $stmt->bindParam(3, $title);
            $stmt->bindParam(4, $share_at);
            $stmt->bindParam(5, $username);
            $stmt->execute();
            $articleSql = "update article set share_count=share_count+1 where article_id=?";
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

    // 处理取消分享
    public function cancelShare($article_id,$username)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $shareSql = "delete from share where article_id=? and username=?";
            $stmt = $pdo->prepare($shareSql);
            $stmt->bindParam(1, $article_id);
            $stmt->bindParam(2, $username);
            $stmt->execute();
            $articleSql = "update article set share_count=share_count-1 where article_id=?";
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

    public function delShare($article_id, $share_id)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $shareSql = "delete from share where share_id=?";
            $stmt = $pdo->prepare($shareSql);
            $stmt->bindParam(1, $share_id);
            $stmt->execute();
            $articleSql = "update article set share_count=share_count-1 where article_id=?";
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

    public function getShare($username,$currentPage=1, $pageSize=5)
    {
        return $this->table('share')->field('share_id,article_id,author,title,username,share_at')->where(['username'=>"{$username}"])->pages($currentPage, $pageSize, "/user/{$username}",'share');
    }
}
