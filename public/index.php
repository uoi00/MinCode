<?php
/**
 * 入口文件
 */

//载入框架配置文件
require '../base.php';
//加载拓展自动加载文件
require '../vendor/autoload.php';

//载入视图文件
require '../core/View.php';

$v = new \Core\View();
$v->display('test',['aa'=>1111]);
