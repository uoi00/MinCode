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

header("Access-Control-Allow-Origin: *");
date_default_timezone_set('PRC'); //设置中国时区

//进入路由
$rst = new \Core\Route();
if (is_array($rst->rst) || is_object($rst->rst)) echo json_encode($rst->rst);
else echo $rst->rst;
