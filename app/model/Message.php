<?php

namespace app\model;

use core\lib\Log;
use core\lib\Model;

class Message extends Model
{

    private static $message;
    public static function getInstance()
    {
        if (self::$message) {
            return self::$message;
        } else {
            self::$message = new self();
            return self::$message;
        }
    }

    public function checkAddmessage($author, $content, $created_at)
    {
        $pdo = $this->init();
        try {
            $pdo->beginTransaction();
            $messageSql = "insert into message (author,content,created_at) values (?,?,?)";
            $stmt = $pdo->prepare($messageSql);
            $stmt->bindParam(1, $author);
            $stmt->bindParam(2, $content);
            $stmt->bindParam(3, $created_at);
            $stmt->execute();
            $receiveSql = "insert into receive (username,content,receive_at) values (?,?,?)";
            $stmt = $pdo->prepare($receiveSql);
            $stmt->bindParam(1, $author);
            $stmt->bindParam(2, $content);
            $stmt->bindParam(3, $created_at);
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

    public function delMessage($message_id)
    {
        return $this->table('message')->where(['message_id'=>"{$message_id}"])->delete();
    }

    public function getAllMessage($currentPage = 1, $pageSize)
    {
        return $this->table('message')->order('created_at desc')->pages($currentPage, $pageSize, '/admin/manage','message');
    }
}