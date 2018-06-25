<?php
/**
 * 入口文件
 */

//载入框架配置文件
require '../config.php';
//调试模式
if(DEBUG == true)
{
    ini_set("display_errors", "On");
    error_reporting(E_ALL | E_STRICT);
}
//加载拓展自动加载文件
require '../vendor/autoload.php';
//载入函数库
require  '../app/function.php';
//载入路由文件
require  '../core/Route.php';
//载入数据库配置文件
require '../core/ConfDB.php';
//根据需求载入连接数据
if (ISDB) ConfDB::conf();
//进入路由
new Route();

//载入视图文件
//require '../core/View.php';
//echo $_SERVER["QUERY_STRING"];
//echo '<br>'.$_SERVER['PHP_SELF'];
//echo '<br>'.$_SERVER['REQUEST_URI'].'<br>';

//var_dump(parse_url($_SERVER['REQUEST_URI']));
//$v = new \Core\View();
//$v->display('test',['aa'=>1111]);
