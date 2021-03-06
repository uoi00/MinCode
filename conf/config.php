<?php
/**
 * 常量配置文件
 */

#################
##  基础配置  ###
#################
//是否开启调试模式
define("DEBUG",true);
//是否使用SQL数据库(MySQL,mssql,Oracle等)
define("ISDB",true);

#################
##  路径配置  ###
#################
//系统斜线
define("DS",DIRECTORY_SEPARATOR);
//框架根目录
define("ROOT",dirname(__FILE__).DS.'..'.DS);
//公共目录
define("PUBLIC",ROOT.'public'.DS);
//URL根目录
define("URL_ROOT",dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
###################
#### Redis配置 ####
###################
//ip
define('RDS_IP','127.0.0.1');
//端口
define('RDS_PORD','6379');
//密码
define('RDS_PWD','');
