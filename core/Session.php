<?php
/**
 * Session类
 */
namespace Core;

if (!session_id()) session_start();

class Session{

    /**
     * 获取一个session值
     * @param string $key 键
     * @return mixed 值
     */
    public static function get(string $key){
        if (isset($_SESSION[$key])) return $_SESSION[$key];
        else return null;
    }

    /**
     * 设置一个session值
     * @param string $key 键
     * @param mixed $value 值
     */
    public static function set(string $key,$value){
        $_SESSION[$key] = $value;
    }

    /**
     * 删除一个session值
     * @param string $key 键
     */
    public static function del(string $key){
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    /**
     * 清空所有session 使用是请注意 防止错误操作
     */
    public static function flush(){
        $_SESSION = null;
    }
}