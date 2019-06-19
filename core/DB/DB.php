<?php
namespace Core\DB;



class DB{

    //SQL记录 为true可以打印执行的SQL语句
    public static $isSqlLog = false;
    //SQL记录 二维数组 第一维语句[0=>语句,1=>参数]
    public static $sqlLog = [];

    /**
     * 连接数据表
     * @param string $tbname 表名
     * @return Query 操作对象
     */
    public static function table(string $tbname){
        $q = new Query();
        return $q->table($tbname);
    }

    /**
     * 执行查询语句
     * @param string $sql 语句
     * @param array $para 参数
     * @return mixed 执行结果
     */
    public static function select(string $sql,$para = []){
        return Query::exec('select',$sql,$para);
    }

    /**
     * 执行修改语句
     * @param string $sql 语句
     * @param array $para 参数
     * @return mixed 执行结果
     */
    public static function update(string $sql,$para = []){
        return Query::exec('update',$sql,$para);
    }
    /**
     * 执行删除语句
     * @param string $sql 语句
     * @param array $para 参数
     * @return mixed 执行结果
     */
    public static function delete(string $sql,$para = []){
        return Query::exec('delete',$sql,$para);
    }
    /**
     * 执行插入语句
     * @param string $sql 语句
     * @param array $para 参数
     * @return mixed 执行结果
     */
    public static function insert(string $sql,$para = []){
        return Query::exec('insert',$sql,$para);
    }
    /**
     * 执行其他SQL语句
     * @param string $sql 语句
     * @param array $para 参数
     * @return mixed 执行结果
     */
    public static function exec(string $sql,$para = []){
        return Query::exec('exec',$sql,$para);
    }

    /**
     * 开启事物
     * @return bool 操作结果
     */
    public static function startwork(){
        return Query::startwork();
    }
    /**
     * 提交事物
     * @return bool 操作结果
     */
    public static function commit(){
        return Query::commit();
    }
    /**
     * 回滚事物
     * @return bool 操作结果
     */
    public static function rollback(){
        return Query::rollwork();
    }

    /**
     * 打印SQL
     */
    public static function getSql(){
        var_dump(self::$sqlLog);
    }
}

//DB::table('test')->where(['id',['>',115]])->page();