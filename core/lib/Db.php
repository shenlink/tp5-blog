<?php

namespace core\lib;

use core\lib\Config;
use core\lib\Log;


// 数据库操作类
class Db
{
    private static $db;
    private $table;
    private $field = '*';
    private $order = '';
    private $where;
    private $pdo = null;

    // 单例模式,禁止实例化
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (self::$db) {
            return self::$db;
        } else {
            self::$db = new self();
            return self::$db;
        }
    }

    public function init()
    {
        $config = Config::all('database');
        $type = $config['type'];
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];
        $dbname = $config['dbname'];
        $charset = $config['charset'];
        $dsn = "{$type}:host={$host};charset={$charset};dbname={$dbname}";
        try {
            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            Log::init();
            session_start();
            if (isset($_SESSION)) {
                $username = $_SESSION['username'];
                Log::log("用户{$username}:" . '数据库连接发生错误:' . $e->getMessage(), 'log');
            } else {
                Log::log('数据库连接发生错误:' . $e->getMessage() . 'log');
            }
        }
        return $this->pdo;
    }

    public function fetchSql($statement)
    {
        if (is_array($this->where)) {
            $wheres = $this->fixPrepareWhere($this->where);
            $count = count($wheres);
            for ($i = 0; $i < $count; $i++) {
                $statement = preg_replace('/[?]/', $wheres[$i], $statement, 1);
            }
        }
        return $statement;
    }

    // 针对insert和update语句
    public function getSql($statement, $condition)
    {
        $count = count($condition);
        for ($i = 0; $i < $count; $i++) {
            $statement = preg_replace('/[?]/', $condition[$i], $statement, 1);
        }
        return $statement;
    }

    public function writeLog($realSql)
    {
        Log::init();
        session_start();
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            Log::log("用户{$username}:" . '执行sql操作:' . $realSql);
        } else {
            Log::log('执行sql操作:' . $realSql);
        }
    }

    public function table($table)
    {
        $this->init();
        $this->table = $table;
        return $this;
    }

    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function limit($limit = 1)
    {
        $this->limit = $limit;
        return $this;
    }

    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    public function select()
    {
        $sql = $this->fixsql('select') . ' limit 1';
        $realSql = $this->fetchSql($sql);
        $this->writeLog($realSql);
        if (is_array($this->where)) {
            $wheres = $this->fixPrepareWhere($this->where);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($wheres);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?? false;
    }

    public function selectAll()
    {
        $sql = $this->fixSql('select');
        $realSql = $this->fetchSql($sql);
        $this->writeLog($realSql);
        if (is_array($this->where)) {
            $wheres = $this->fixPrepareWhere($this->where);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($wheres);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($data)
    {
        $sql = $this->fixSql('insert', $data);
        $values = $this->fixPrepareValue($data);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        $realSql = $this->getSql($sql, $values);
        $this->writeLog($realSql);
        $result = $this->pdo->lastInsertId();
        return $result;
    }

    public function update($data)
    {
        $sql = $this->fixSql('update', $data);
        $values = $this->fixPrepareValue($data);
        $wheres = $this->fixPrepareWhere($this->where);
        $allParams = array_merge($values, $wheres);
        if (is_array($data) && is_array($this->where)) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($allParams);
            $realSql = $this->getSql($sql, $allParams);
            $this->writeLog($realSql);
        } else if (is_array($data) && !is_array($this->where)) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            $realSql = $this->getSql($sql, $values);
            $this->writeLog($realSql);
        } else if (!is_array($data) && is_array($this->where)) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($wheres);
            $realSql = $this->getSql($sql, $wheres);
            $this->writeLog($realSql);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $realSql = $this->fetchSql($sql);
            $this->writeLog($realSql);
        }
        $result = $stmt->rowCount();
        return $result;
    }

    public function delete()
    {
        $sql = $this->fixSql('delete');
        $realSql = $this->fetchSql($sql);
        $this->writeLog($realSql);
        if (is_array($this->where)) {
            $wheres = $this->fixPrepareWhere($this->where);
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($wheres);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute();
        }
        return $result;
    }

    // 查询数据总数
    public function count()
    {
        $sql = $this->fixSql('count');
        $realSql = $this->fetchSql($sql);
        $this->writeLog($realSql);
        if (is_array($this->where)) {
            $wheres = $this->fixPrepareWhere($this->where);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($wheres);
            $count = $stmt->fetchColumn(0);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $count = $stmt->fetchColumn(0);
        }
        return $count;
    }

    // 分页
    public function pages($currentPage, $pageSize = 5, $url, $type = 'pagination')
    {
        $count = $this->count();
        $this->limit = ($currentPage - 1) * $pageSize . ',' . $pageSize;
        $items = $this->selectAll();
        $pageHtml = $this->createPages($currentPage, $pageSize, $count, $url, $type);
        return array('items' => $items, 'pageHtml' => $pageHtml);
    }

    // 生成分页pageHtml(bootstrap风格)；currentPage：当前第几页，pageSize:每页大小，count:数据总数
    private function createPages($currentPage, $pageSize, $count, $url, $type)
    {
        // 分页数，向上取整
        $pageHtml = '';
        $separate = '/';
        $pageCount = ceil($count / $pageSize);
        if ($pageCount == 0) {
            return;
        }
        if($currentPage > $pageCount){
            return 'error';
        }
        // 生成首页,生成上一页
        if ($pageCount > 0 && $currentPage >= 1) {
            if ($currentPage == 1) {
                $pageHtml .= "<li data-index='current_1' onclick='changePage(this)' class='page-item'><a class='page-link' href='javascript:void(0)'>首页</a></li>";
                $pageHtml .= "<li data-index='current_1' onclick='changePage(this)' class='page-item'><a class='page-link' href='javascript:void(0)'>上一页</a></li>";
            } else {
                $pageHtml .= "<li class='page-item'><a class='page-link' href='{$url}{$separate}{$type}{$separate}1'>首页</a></li>";
                $prePage = $currentPage - 1;
                $pageHtml .= "<li data-index={$prePage} onclick='changePage(this)' class='page-item'><a class='page-link' href='{$url}{$separate}{$type}{$separate}{$prePage}'>上一页</a></li>";
            }
        }
        $start = $currentPage - 3 >= 1 ? $currentPage - 3 : 1;
        $end = $currentPage + 3 <= $pageCount ? $currentPage + 3 : $pageCount;
        // 生成当前页
        for ($i = $start; $i <= $end; $i++) {
            $pageHtml .= $i == $currentPage ? "<li data-index='current_page' data-current={$i} id='{$type}Current' data-page-count={$pageCount} onclick='changePage(this)' class='page-item active'><a class='page-link' href='javascript:void(0)'>{$i}</a></li>" : "<li data-index={$i} onclick='changePage(this)' class='page-item'><a class='page-link' href='{$url}{$separate}{$type}{$separate}{$i}'>{$i}</a></li>";
        }
        // 生成下一页,生成尾页
        if ($currentPage <= $pageCount) {
            $nextPage = $currentPage + 1;
            if ($nextPage > $pageCount) {
                $pageHtml .= "<li data-index='current_end' onclick='changePage(this)' class='page-item '><a class='page-link' href='javascript:void(0)'>下一页</a></li>";
            } else {
                $pageHtml .= "<li class='page-item '><a class='page-link' href='{$url}{$separate}{$type}{$separate}{$nextPage}'>下一页</a></li>";
            }
            if ($currentPage == $pageCount) {
                $pageHtml .= "<li data-index='current_end' onclick='changePage(this)' class='page-item '><a class='page-link' href='javascript:void(0)'>尾页</a></li>";
            } else {
                $pageHtml .= "<li class='page-item'><a class='page-link' href='{$url}{$separate}{$type}{$separate}{$pageCount}'>尾页</a></li>";
            }
        }
        $pageHtml .= "<li class='page-item'><a class='page-link' href='javascript:void(0)'>共{$pageCount}页</a></li>";
        $pattern = '/[^\x00-\x80]/';
        if (preg_match($pattern, $type)) {
            $type = urlencode($type);
        }
        $pageHtml .= "<form class='form-inline'>
        <input type='number' class='form-control search-type' id='{$type}Jump' min='1' max='{$pageCount}' onkeyup='this.value=this.value.replace(/\D/g,'')' onafterpaste='this.value=this.value.replace(/\D/g,'')'>
        <button type='button' class='btn btn-light current' onclick='jumpPage(this)' data-current={$currentPage} data-page-count={$pageCount} data-type={$type}>跳转</button>
        </form>";
        $pageHtml = '<ul class="pagination justify-content-center">' . $pageHtml . '</ul>';
        return $pageHtml;
    }

    public function fixSql($type, $data = null)
    {
        $sql = '';
        $where = $this->fixWhere();
        if ($type === 'select') {
            $sql = "select {$this->field} from {$this->table} {$where}";
            if ($this->order) {
                $sql .= " order by {$this->order}";
            }
            if ($this->limit) {
                $sql .= " limit {$this->limit}";
            }
            return $sql;
        }
        if ($type == 'count') {
            $where = $this->fixWhere();
            $fieldList = explode(',', $this->field);
            $field = count($fieldList) > 1 ? '*' : $this->field;
            $sql = "select count({$field}) from {$this->table} {$where}";
            return $sql;
        }
        if ($type === 'insert') {
            $sql = "insert into {$this->table}";
            $fields = array();
            $values = array();
            foreach ($data as $key => $value) {
                $fields[] = $key;
                $values[] = '?';
            }
            $sql .= " (" . implode(',', $fields) . ") values (" . implode(',', $values) . ")";
            return $sql;
        }
        if ($type == 'update') {
            if (is_array($data)) {
                $str = '';
                foreach ($data as $key => $value) {
                    $value = '?';
                    $str .= "{$key} = {$value},";
                }
                $str = rtrim($str, ',');
            } else {
                $str = $data;
            }
            $str = $str ? "set {$str}" : '';
            $sql = "update {$this->table} {$str} {$where}";
            return $sql;
        }
        if ($type === 'delete') {
            $sql = "delete from {$this->table} {$where}";
            return $sql;
        }
    }

    private function fixWhere()
    {
        $where = '';
        if (is_array($this->where)) {
            foreach ($this->where as $key => $value) {
                $value = '?';
                $where .= "`{$key}` = {$value} and ";
            }
        } else {
            $where = $this->where;
        }
        $where = rtrim($where, 'and ');
        $where = $where == '' ? '' : "where {$where}";
        return $where;
    }

    public function fixPrepareWhere($prepareWheres)
    {
        $wheres = array();
        foreach ($prepareWheres as $value) {
            $wheres[] = $value;
        }
        return $wheres;
    }

    public function fixPrepareValue($data)
    {
        $values = array();
        foreach ($data as $value) {
            $values[] = $value;
        }
        return $values;
    }
}
