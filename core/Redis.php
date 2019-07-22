<?php
namespace Core;

class Redis{
    public $connect = null;
    private static $obj = null;
    private function __construct()
    {
        $this->connect = new \Redis();
        $this->connect->connect(RDS_IP, RDS_PORD);
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function getConnect(){
        if (self::$obj == null){
            self::$obj = new Redis();
        }
        return self::$obj;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->connect->close();
    }
}