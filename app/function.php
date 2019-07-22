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
function err404($a=''){
    header('HTTP/1.1 404 Not Found');
    exit($a);
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

/**
 * 获取请求IP
 * @return string IP
 */
function getIP(){

    if (isset($_SERVER)){

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

            $ip= $_SERVER["HTTP_X_FORWARDED_FOR"];

        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {

            $ip= $_SERVER["HTTP_CLIENT_IP"];

        } else {

            $ip= $_SERVER["REMOTE_ADDR"];

        }

    }
    else {

        if (getenv("HTTP_X_FORWARDED_FOR")){

            $ip= getenv("HTTP_X_FORWARDED_FOR");

        } else if (getenv("HTTP_CLIENT_IP")) {

            $ip= getenv("HTTP_CLIENT_IP");

        } else {

            $ip= getenv("REMOTE_ADDR");

        }

    }
    $ip = null;
    //客户端IP 或 NONE
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }

    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) {
            array_unshift($ips, $ip);
            $ip = null;
        }
        for ($i = 0; $i < count($ips); $i++) {
            if (!preg_grep("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    //客户端IP 或 (最后一个)代理服务器 IP
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * Http请求
 * @param string $url 请求路径
 * @param string $method 请求方式 GET POST PUT DELETE
 * @param array $data 请求数据
 * @param array $cookie cookie
 * @param array $header 请求头
 * @param int $fromtype 表单格式 1json 0普通表单
 * @param int $outtime 超时时间
 * @return mixed 访问结果
 */
function httpSend($url, $method = 'POST', $data = [], $cookie = '', $header = [], $fromtype = 0, $outtime = 15)
{
    // 启动一个CURL会话
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($handle, CURLOPT_HEADER, 0); // 是否显示返回的Header区域内容
    curl_setopt($handle, CURLOPT_HTTPHEADER, $header); //设置请求头
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
    curl_setopt($handle, CURLOPT_COOKIE, $cookie);
    curl_setopt($handle, CURLOPT_TIMEOUT, $outtime); //允许 cURL 函数执行的最长秒数
    if ($fromtype === 1) $data = json_encode($data); //如果为json格式提交转换数据
    switch ($method) {
        case 'GET':
            break;
        case 'POST':
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data); //设置请求体，提交数据包
            break;
        case 'PUT':
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data); //设置请求体，提交数据包
            break;
        case 'DELETE':
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            break;
    }

    $response = curl_exec($handle); // 执行操作
    //$code = curl_getinfo($handle, CURLINFO_HTTP_CODE); // 获取返回的状态码
    curl_close($handle); // 关闭CURL会话
    //if('200'==$code){
        //echo "ok";
    //}
    return $response;
}