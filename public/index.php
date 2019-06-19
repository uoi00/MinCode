<?php
/**
 * 入口文件
 */

//var_dump($_SERVER);
//exit();

//载入框架配置文件
require '../conf/config.php';
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

//进入路由
new Route();

