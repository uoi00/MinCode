<?php
/**
 * 连接配置SQL数据库
 */
use think\Db;
class ConfDB{

    public static function conf()
    {
        Db::setConfig(require(ROOT.'dbconf.php'));
    }
}