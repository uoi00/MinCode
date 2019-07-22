<?php
namespace Core\DB;

/**
 * 连接数据库
 */
class Connect{
    public $db = null;//数据库连接
    private static $obj = null; //数据库连接

    /**
     * 单例模式 禁止直接new对象
     */
    private function __construct()
    {
        // 端口
        $conf = include ROOT.'conf/dbconf.php';
        $dsn = $conf['type'].':dbname='.$conf['database'].';host='.$conf['hostname'];
        $options = [
            \PDO::ATTR_STRINGIFY_FETCHES=>false, //禁止强行把参数转化成字符串
            \PDO::ATTR_EMULATE_PREPARES=>false, //禁用预处理语句的模拟
            \PDO::ATTR_ORACLE_NULLS=>\PDO::NULL_NATURAL, //不转换NULL和空字符串
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,//设置报错级别
        ];
        $this->db = new \PDO($dsn, $conf['username'], $conf['password'],$options);
        $this->db->query("set names ".$conf['charset']);
    }

    /**
     * 禁止clone对象
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->close();
    }

    //关闭连接
    public function close()
    {
         $this->db= null;
    }

    /**
     * 获取连接对象
     * @return Connect|null
     */
    public static function getDB(){
        if (self::$obj === null) {
            self::$obj = new Connect();
        }
        return self::$obj;
    }
}
