<?php
/**
 * 入口文件
 */

//载入框架配置文件
require '../config.php';
//加载拓展自动加载文件
require '../vendor/autoload.php';
//调试模式
if(DEBUG == true)
{
    ini_set("display_errors", "On");
    error_reporting(E_ALL | E_STRICT);
}

//载入函数库
require  '../app/function.php';
//载入路由问价
require  '../core/Route.php';
new Route();

//载入视图文件
//require '../core/View.php';
//echo $_SERVER["QUERY_STRING"];
//echo '<br>'.$_SERVER['PHP_SELF'];
//echo '<br>'.$_SERVER['REQUEST_URI'].'<br>';

//var_dump(parse_url($_SERVER['REQUEST_URI']));
//$v = new \Core\View();
//$v->display('test',['aa'=>1111]);
