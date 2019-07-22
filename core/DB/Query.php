<?php
namespace Core\DB;

class Query
{
    private $and = []; //and条件
    private $or = [];//or条件
    private $sort = [];//排序
    private $group = '';//分组
    private $limit = [];//分页
    private $table = '';//表名
    private $para = []; //要绑定的参数
    private static $dbconnect = null; //数据库连接

    /**
     * 获取数据库连接
     * @return \PDO|null
     */
    private static function getConnect()
    {
        if (self::$dbconnect == null) {
            self::$dbconnect = Connect::getDB();
        }
        return self::$dbconnect->db;
    }

    /**
     * 开启事物
     * @return bool 结果
     */
    public static function startwork()
    {
        //self::getConnect()->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);  /关闭自动提交
        return self::getConnect()->beginTransaction();  #####//开启事务
    }

    /**
     * 提交事物
     * @return bool 执行结果
     */
    public static function commit()
    {
        return self::getConnect()->commit();  //执行整个事务
        // return self::getConnect()->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);  //开启自动提交
    }

    /**
     * 回滚事物
     * @return bool 执行结果
     */
    public static function rollwork()
    {
        return self::getConnect()->rollback();  //撤销全部事务
        // self::getConnect()->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);  //开启自动提交
    }

    //关闭连接
    public static function close()
    {
        Connect::close();
    }

    /**
     * 拼接语句
     * @param $data array where条件
     * @param $ao bool 查询方式 true and false or
     * @return bool|string
     * @throws Exception
     */
    private function sqlAndOr(array $data, bool $ao = true)
    {
        $sql = '';
        foreach ($data as $v) {
            if (!is_array($v[1])) {
                $sql .= '`' . $v[0] . '`=?';
                $this->para[] = $v[1];
            } elseif (strtolower($v[1][0]) == 'in') { //In查询
                if (!is_array($v[1][1])) throw new Exception('in查询的参数为数组');
                $sql .= '`' . $v[0] . '` in (';
                foreach ($v[1][1] as $vv) {
                    $sql .= '?,';
                    $this->para[] = $vv;
                }
                $sql = substr($sql, 0, -1);
                $sql .= ') ';
            } elseif (strtolower($v[1][0]) == 'between') { //范围查询
                $sql .= '`' . $v[0] . '` between ? and ? ';
                $this->para[] = $v[1][1][0];
                $this->para[] = $v[1][1][1];
            } elseif ($v[1][0] == 'like') { //模糊查询
                $sql .= '`' . $v[0] . '` like ? ';
                $this->para[] = $v[1][1];
            } elseif ($v[1][0] == 'inc') { //自增
                $sql .= '`'. $v[0] .'`=`'.$v[0].'`+?';
                $this->para[] = $v[1][1];
            } elseif (in_array($v[1][0], ['>', '<', '=', '!=', '<=', '>='])) { //大于小于等于 不等于 ...
                $sql .= '`' . $v[0] . '` ' . $v[1][0] . ' ?';
                $this->para[] = $v[1][1];
            }

            if ($ao) $sql .= ' and ';
            else $sql .= ' or ';
        }
        if ($ao) $sql = substr($sql, 0, -5);
        else $sql = substr($sql, 0, -4);
        return $sql;
    }

    /**
     * 表明要操作的表
     * @param string $tbname 表名
     * @return Query 数据库连接
     */
    public function table(string $tbname)
    {
        $this->table = $tbname;
        return $this;
    }

    /**
     * 进行where and查询
     * @param array $condition查询条件 多个为and
     *  [
     *      [column,value,],
     *      [column,'>|<|!=|<=|>=',value,],
     *      [column,'in',[v1,v2,...,vn],],
     *      [column,'between',[start,end],]
     *  ]
     * @return Query 数据库连接
     */
    public function where(array $condition)
    {
        if (is_array($condition[0])) {
            $this->and = array_merge($this->and, $condition);
        } else {
            $this->and = array_merge($this->and, [$condition]);
        }
        return $this;
    }

    /**
     * 进行where or查询
     * @param array $condition查询条件 多个为or
     *  [
     *      [column,value,],
     *      [column,'>|<|!=|<=|>=',value,],
     *      [column,'in',[v1,v2,...,vn],],
     *      [column,'between',[start,end],]
     *  ]
     * @return Query 数据库连接
     */
    public function orWhere(array $condition)
    {
        if (is_array($condition[0])) {
            $this->or = array_merge($this->or, $condition);
        } else {
            $this->or = array_merge($this->or, [$condition]);
        }
        return $this;
    }

    /**
     * 分组
     * @param array $condition 要分组的字段 多次使用覆盖
     * @return Query 数据库连接
     */
    public function groupBy(string $condition)
    {
        $this->group = $condition;
        return $this;
    }

    /**
     * 排序方式 默认升序
     * @param string $column 字段
     * @param string $range 排序方式 asc升序 desc降序
     * @return Query 数据库连接
     */
    public function orderBy($column, $range = 'asc')
    {
        $this->sort[] = [$column, $range];
        return $this;
    }

    /**
     * 分页
     * @param int $rows 每页条数
     * @param int $start 跳过条数
     * @return Query 数据库连接
     */
    public function limit($rows, $start = 0)
    {
        $this->limit = [$rows, $start];
        return $this;
    }

    /**
     * 查询
     * @param array $cloumn 要查询的字段
     * @param bool $is_array 输出格式 true数组 false对象
     * @return array 数据结果
     * @throws null
     */
    public function get(array $cloumn = [], bool $is_array = true)
    {
        //构建查询语句
        $sql = 'select ';
        if ($cloumn === []) $sql .= '* ';
        else {
            foreach ($cloumn as $v)
                $sql .= '`' . $v . '`,';
            $sql = substr($sql, 0, -1);
        }
        $sql .= ' from ' . $this->table;
        if ($this->and != []) {
            $sql .= ' where ';
            $sql .= $this->sqlAndOr($this->and, true);
            if ($this->or != []) $sql .= ' or ' . $this->sqlAndOr($this->or, false);
        }
        //组合分组
        if ($this->group != '') $sql .= ' group by `' . $this->group . '` ';
        //组合排序
        if ($this->sort != []) {
            $sql .= ' order by ';
            foreach ($this->sort as $item) {
                $sql .= '`' . $item[0] . '` ' . $item[1] . ',';
            }
            $sql = substr($sql, 0, -1);
        }
        //组合分页
        if ($this->limit != []) {
            $sql .= ' limit ?,?';
            $this->para[] = $this->limit[1];
            $this->para[] = $this->limit[0];
        }
        if (DB::$isSqlLog){
            DB::$sqlLog[] = [$sql,$this->para];
        }
        //执行SQL语句
        $zj = self::getConnect()->prepare($sql);
        $zj->execute($this->para);
        if ($is_array) $zsd = $zj->fetchAll(PDO::FETCH_ASSOC); //返回数组结果
        else $zsd = $zj->fetchAll(PDO::FETCH_CLASS); //返回对象结果
        return $zsd;
    }

    /**
     * 分页
     * @param array $cloumn 查询字段
     * @param int $rows 每页条数
     * @param int $start 起始位置(跳过的条数)
     * @return array 搜索结果 ['total'=>总数,'rows'=>每页数量,'items'=>数据(array)]
     * @throws Exception null
     */
    public function page(array $cloumn = [], int $rows = 15, int $start = 0)
    {
        //查询条数
        $countSql = 'select count(*) as num from ' . $this->table;
        //进行分页
        $dataSql = 'select ';
        if ($cloumn === []) $dataSql .= '* ';
        else {
            foreach ($cloumn as $v)
                $dataSql .= '`' . $v . '`,';
            $dataSql = substr($dataSql, 0, -1);
        }
        $dataSql .= ' from ' . $this->table;
        //条件
        $sql = '';
        if ($this->and != []) {
            $sql .= ' where ';
            $sql .= $this->sqlAndOr($this->and, true);
            if ($this->or != []) $sql .= ' or ' . $this->sqlAndOr($this->or, false);
        }
        //组合分组
        if ($this->group != '') $sql .= ' group by `' . $this->group . '` ';
        //组合排序
        if ($this->sort != []) {
            $sql .= ' order by ';
            foreach ($this->sort as $item) {
                $sql .= '`' . $item[0] . '` ' . $item[1] . ',';
            }
            $sql = substr($sql, 0, -1);
        }
        //拼接总数语句
        $countSql .= $sql;
        //获取数据条数
        $counts = self::getConnect()->prepare($countSql);
        $counts->execute($this->para);
        $count = $counts->fetchAll(PDO::FETCH_CLASS); //返回对象结果
        $count = empty($count[0]->num) ? 0 : $count[0]->num;

        //拼接数据语句
        $dataSql .= $sql . ' limit ' . $start . ',' . $rows;
        //记录SQL
        if (DB::$isSqlLog){
            DB::$sqlLog[] = [$dataSql,$this->para];
        }
        $datas = self::getConnect()->prepare($dataSql);
        $datas->execute($this->para);
        $data = $datas->fetchAll(PDO::FETCH_CLASS); //返回对象结果
        $data = empty($data) ? [] : $data;
        return ['total' => $count, 'rows' => $rows, 'items' => $data];
    }

    /**
     * 插入数据
     * @param array $data 要插入的数据 一维数组插入一条 二维插入多条
     * @param bool $zz 是否返回自增id
     * @return false|int 插入结果 失败|插入的数目
     */
    public function insert(array $data,bool $zz=false)
    {
        $sql = 'insert into ' . $this->table;
        if (isset($data[0]) && is_array($data[0])) {
            $column = array_keys($data[0]);
            if (!is_int($column[0])) {
                $sql .= ' (';
                foreach ($column as $v)
                    $sql .= '`' . $v . '`,';
                $sql = substr($sql, 0, -1) . ') values ';
            } else $sql .= ' values ';
            foreach ($data as $v) {
                $sql .= '(' . join(',', array_fill(0, count($v), '?')) . '),';
                $this->para = array_merge($this->para, array_values($v));
            }
            $sql = substr($sql, 0, -1);
        } else {
            $column = array_keys($data);
            if (!is_int($column[0])) {
                $sql .= ' (';
                foreach ($column as $v)
                    $sql .= '`' . $v . '`,';
                $sql = substr($sql, 0, -1) . ') values ';
            } else $sql .= ' values ';
            $sql .= '(' . join(',', array_fill(0, count($data), '?')) . ')';
            $this->para = array_merge($this->para, array_values($data));
        }
        //记录SQL
        if (DB::$isSqlLog){
            DB::$sqlLog[] = [$sql,$this->para];
        }
        $zj = self::getConnect()->prepare($sql);
        $zid = $zj->execute($this->para);
        if ($zid && $zz) return self::getConnect()->lastInsertId();
        return $zid;
    }

    /**
     * 插入数据
     * @param array $data 要插入的数据
     * @return bool 结果
     * @throws Exception null
     */
    public function update(array $data)
    {
        $sql = 'update ' . $this->table . ' set ';
        foreach ($data as $k => $v) {
            if (empty($v) || is_int($k) || is_array($v) || is_object($v)) throw new Exception('错误的修改数据');
            $sql .= '`' . $k . '`=?,';
            $this->para[] = $v;
        }
        $sql = substr($sql, 0, -1);
        if ($this->and != []) {
            $sql .= ' where ' . $this->sqlAndOr($this->and, true);
            if ($this->or != []) $sql .= ' or ' . $this->sqlAndOr($this->or, false);
        }
        //记录SQL
        if (DB::$isSqlLog){
            DB::$sqlLog[] = [$sql,$this->para];
        }
        $zj = self::getConnect()->prepare($sql);
        $zid = $zj->execute($this->para);
        return $zid;
    }

    /**
     * 删除数据
     * @return bool 结果
     * @throws Exception null
     */
    public function delete()
    {
        $sql = 'delete from ' . $this->table;
        if ($this->and != []) {
            $sql .= ' where ' . $this->sqlAndOr($this->and, true);
            if ($this->or != []) $sql .= ' or ' . $this->sqlAndOr($this->or, false);
        }
        //记录SQL
        if (DB::$isSqlLog){
            DB::$sqlLog[] = [$sql,$this->para];
        }
        $zj = self::getConnect()->prepare($sql);
        $zid = $zj->execute($this->para);
        return $zid;
    }

    /**
     * 执行SQL语句
     * @param string $model 执行模式
     * @param string $sql 语句
     * @param array $para 参数
     * @return mixed 结果
     */
    public static function exec(string $model, string $sql, array $para = [])
    {
        //记录SQL
        if (DB::$isSqlLog){
            DB::$sqlLog[] = [$sql,$para];
        }
        $zj = self::getConnect()->prepare($sql);
        $zid = $zj->execute($para);
        if ($model === 'select') {
            $zsd = $zj->fetchAll(\PDO::FETCH_CLASS);
            return $zsd;
        } else return $zid;
    }
}

//$s = new Query();
//var_dump($s->table('test')->page());

//$rst = $s->table('test')->where(['id',['in',[120,122]]])->where(['ss',12345])->orderBy('id')->get();
//$rst = $s->table('test')->where(['id',['between',[120,122]]])->where(['ss',"12345"])->orderBy('id')->get();
//var_dump($rst);
//var_dump($s->table('test')->insert(
//    ['id'=>112,'double'=>11,'string'=>'aaa']
//    ['id'=>113,'double'=>11,'string'=>'aaa'],
//));

//var_dump($s->table('test')->where(['id','113'])->update(['string'=>'www','int'=>'int'])); //解决自增
//var_dump($s->table('test')->where(['id',['<=',114]])->delete());

//事务
