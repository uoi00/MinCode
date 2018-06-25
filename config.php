<?php
/**
 * 常量配置文件
 */

#################
##  基础配置  ###
#################
//是否开启调试模式
define("DEBUG",true);
//默认控制器
define("DFCTR",'Index');
//默认方法
define("DDFUN","index");
//是否使用SQL数据库(MySQL,mssql,Oracle等)
define("ISDB",true);

#################
##  路径配置  ###
#################
//系统斜线
define("DS",DIRECTORY_SEPARATOR);
//框架根目录
define("ROOT",dirname(__FILE__).DS);
//公共目录
define("PUBLIC",ROOT.'public'.DS);
//URL根目录
define("URL_ROOT",dirname($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));

###################
##   模板配置   ###
###################
//模版文件后缀
define("TMP_EXT",'.html');
//模板路径
define("TMP_PATH",ROOT.'templates');
//缓存路径
// 不需要缓存些false 其他写缓存存放路径
//twig引擎的模版缓存有问题 不是很灵活 在测试的时候最好选择false 正式运行的时候写缓存路径
//define("TMP_CACHE",ROOT.'cache'.DS.'template');
define("TMP_CACHE",false);