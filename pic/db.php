<?php
/**
 *  数据库类, 用来创建操作数据库的类
 * Created by PhpStorm.
 * User: JiefKing
 * Date: 2017/4/12
 * Time: 18:39
 *
 * 初步完成时间 2017年4月12日 20:11:30
 *
 * 完善分页方法 和 返回符合条件的记录数 2017年4月12日 22:10:21
 */

    class DB
    {
        private $pdo;
        private $tab;
        private $pk;            // 主键
        private $fields=[];     // 其他字段名
        private $sql;
        private static $obj = null;

        private $returnType = PDO::FETCH_CLASS;


        private function __construct($tab)
        {
            if(!$tab)die('请指定表名.');
            $this -> tab = $tab;
            try{
                // $dsn = 'mysql:host='.HOST.';dbname='.DBNAME.';charset=utf8;';
                 $dsn = DSN;
                $this -> pdo = new PDO($dsn, USER, PWD);
            }catch (PDOException $e){
                die ('数据库连接错误....');
            }
            // 查询主键和所有的字段名
            $this -> getFields ();
        }

        /**
         * 功能 : 获取当前表中的主键 和 所有的字段名
         */
        private function getFields()
        {
            $tmp = $this -> returnType;
            $this -> setReturnType(PDO::FETCH_CLASS);
            $sql = "DESC {$this -> tab};";
            $this->sql = $sql;
            $stmt = $this->pdo->query($sql);
            if ($this->error($stmt)) {
                $obj = $stmt->fetchAll($this -> returnType);
                if ($obj) {
                    $f = [];
                    foreach ($obj as $v) {
                        if ($v->Key == 'PRI') {
                            $this->pk = $v->Field;
                            continue;
                        }
                        $f[] = $v->Field;
                    }
                    $this->fields = $f;
                } else {
                    die('查询字段名时出错....');
                }
            } else {
                die('查询字段名时戳错....');
            }
            $this -> setReturnType($tmp);
        }

        /**
         *  功能 检测是不是有sql语法错误 并返回对应的布尔值或者 插入语句对应的主键
         * @param $a ,删除方法 查询方法 更新方法 公用的 用来检测 是不是有影响行数 成功返回 true 否则返回 false
         * @param bool $b ,插入方法用 用来检测插入成功与否 成功返回 主键 ID 失败返回false
         * @return bool|string
         */
        private function error($a, $b = false)
        {
            if ((int)$this->pdo->errorCode()) {
                print_r($this->sql);
                echo "\n";
                echo $this->pdo->errorInfo()[2];
            }

            if ($a) {
                return true;
            } else if ($b) {
                return $this->pdo->lastInsertId($this -> pk);
            } else {
                return false;
            }
        }

        static function Table($a=null)
        {
            return new self($a);
            if(!self::$obj && $a !== null){
                self::$obj = new self($a);
            } else if($a === null){
                return self::$obj;
            } else {
                self::$obj -> tab = $a;
            }
            
            self::$obj -> getFields ();
            return self::$obj;
        }

        /**
         * 往数据库中插入一条数据
         * @param $data / 字段作为键名 数据作为值的关联数组
         * @return bool|string 成功返回 主键ID 失败返回false
         */
        public function insert($data)
        {
            // 设置临时变量 用来组合字段名 和 对应的值
            $a = $b = '';
            foreach ($data as $k => $v){
                if(!in_array($k,$this -> fields) && $k != $this -> pk) continue;
				$v = addslashes($v);
                $a .= "`{$k}`,";
                $b .= "'{$v}',";
            }
            //  清除临时变量的尾部的逗号
            $a = rtrim($a,',');
            $b = rtrim($b,',');
            // 组合 sql 插入 语句
            $sql = "INSERT INTO {$this -> tab}({$a}) VALUES ({$b});";
            $this -> sql = $sql;
            $res = $this -> pdo -> exec($sql);
            return $this -> error(false, $res);
        }

        public function insertAll(Array $arr)
        {
            if(!is_array($arr)){
                return false;
            }
            if(!is_array(current($arr))){
                return $this -> insert($arr);
            }

            $a = $b = '';
            foreach ($arr as $k => $v){
                $c = '(';
                foreach($v as $kk => $vv){
                    if(!in_array($kk,$this -> fields)) continue;
                    $v = addslashes($vv);
                    if($k == 0){
                        $a .= "`{$kk}`,";
                    }
                    $c .= "'{$v}',";
                }
                $tmp = rtrim($c, ',');
                $tmp .= '),';
                $b .= $tmp;
            }
            //  清除临时变量的尾部的逗号
            $a = rtrim($a,',');
            $b = rtrim($b,',');

            $sql = "INSERT INTO {$this -> tab}({$a}) VALUES {$b}";
            $this -> sql = $sql;
            $res = $this -> pdo -> exec($sql);
            return $this -> error(false, $res);
        }

        /**
         *  删除一条数据库中的记录
         * @param $id / 主键 ID
         * @return bool|string 成功返回true 否则返回false
         */
        public function delete ($id)
        {
            // 组合 sql 语句
            $sql = "DELETE FROM {$this -> tab} WHERE {$this ->pk}='{$id}'";
            $this -> sql = $sql;
            $res = $this -> pdo -> exec($sql);
            return $this -> error($res);
        }

        /** 根据 关联数组 和 主键ID 更新一条记录
         * @param $data . 字段名 值 对应的关联数组
         * @param $idv. 主键ID
         * @return bool|string 成功返回 true 否组 返回 false
         */
        public function update($data, $id)
        {
            // 建立临时变量用来组合 SQL 语句
            $a = '';
            foreach ($data as $k => $v){
                if(!in_array($k, $this -> fields)) continue;
                $v = addslashes($v);
                $a .= "{$k}='{$v}',";
            }
            $a = rtrim($a, ',');
            // 组合 sql 语句
            $sql = "UPDATE {$this -> tab} SET {$a} WHERE {$this -> pk}='{$id}'";
            $this -> sql = $sql;
            $res = $this -> pdo -> exec($sql);
            return $this -> error($res);
        }

        /** 根据条件 和 当前页数, 每页条数, 返回一个数组
         * @param $condition    / 前置条件
         * @param int $p        / 当前页数
         * @param int $n        / 每页条数
         * @return array|bool   / 如果有数据 返回生成好的数组 没有查到数据返回false
         *
         * 数组中数据包括
         *      all         符合条件的记录条数
         *      maxPage     当前参数最大能分的页数
         *      page        当前页码
         *      content     当前页的内容
         */
        public function limit($condition, $p=1,$n=5)
        {
            $all = $this -> rowCount($condition);
            $maxPage = ceil($all/$n);
            if($p <1 ){
                $p = 1;
            }else if($p > $maxPage){
                $p = $maxPage;
            }
            if(!$p)return false;
            $now = ($p - 1)*$n;
            $sql = " {$condition} limit {$now},{$n}";
            return [
                'all' => $all,
                'maxPage' => $maxPage,
                'page' => $p,
                'content' => $this -> select($sql),
                'condition' => $condition,
            ];
        }

        public function rowCount($condition = '')
        {
            $tmp = $this -> returnType;
            $this -> setReturnType(PDO::FETCH_CLASS);
            $data = $this->select($condition, 'count(1) as xxoo')[0]->xxoo;
            $this -> setReturnType($tmp);
            return $data;
        }

        /** 查询一条记录
         * @param $condition / 查询条件
         * @param $field / 查询字段 默认 *
         * @return array|bool 成功返回 结果 失败返回 false 没有记录也认为是查询失败
         */
        public function select($condition = '', $field = '*')
        {
            // 组合sql 查询语句
            $sql = "SELECT {$field} FROM {$this -> tab} {$condition}";
            $this -> sql = $sql;
            // echo $sql;
            $stmt = $this -> pdo -> query($sql);
            if ($this->error($stmt) && $arr = $stmt->fetchAll($this -> returnType)) {
                return $arr;
            } else {
                return false;
            }
        }


        public function find($condition = '', $field = '*')
        {
            // 组合sql 查询语句
            $sql = "SELECT {$field} FROM {$this -> tab} {$condition} limit 1";
            $this -> sql = $sql;
            // echo $sql;
            $stmt = $this -> pdo -> query($sql);
            $arr = $stmt->fetchAll($this -> returnType);
            if ($this->error($stmt) && $arr) {
                return $arr[0];
            } else {
                return false;
            }
        }

        public function getPDO()
        {
            return $this->pdo;
        }
		
		public function setTab($tabName)
		{
			$this -> tab = $tabName;
			$this -> getFields();
			return $this;
		}
		
		public function getSql()
		{
			return $this -> sql;
		}

        public function setReturnType($type)
        {
            $this -> returnType = $type;
            return $this;
        }
    }