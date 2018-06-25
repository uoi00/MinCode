<?php
/**
 * 函数库
 */
require_once ROOT.'core/View.php';
/**
 * 打印数据并结束程序
 * @param $data mixed 要打印的数据
 */
function dd($data){
    var_dump($data);
    exit();
}

/**
 * 显示模版
 * @param $file string 模版路径
 * @param $opt 模版参数
 * @return null 无返回
 */
function display($file,$opt)
{
    $v = new View();
    $v->display($file.TMP_EXT,$opt);
}