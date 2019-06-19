<?php
/**
 * 函数库
 */
/**
 * 打印数据并结束程序
 * @param $data mixed 要打印的数据
 */
function dd($data){
    var_dump($data);
    exit();
}

/**
 * 结束程序并返回404错误
 */
function err404(){
    header('HTTP/1.1 404 Not Found');
    exit();
}

/**
 * 判断是否为https请求
 * @return bool 是|否
 */
function isHttps() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}

/**
 * 获取URL
 * @param string $path 路径
 * @return string 全路径
 */
function url(string $path=''){
    var_dump($path);
    $url = isHttps() ? 'https://' : 'http://'.URL_ROOT;
    if ($path == '' || $path == '/') return $url;
    if ($path[0] != '/') return $url.'/'.$path;
    else return $url.$path;
}