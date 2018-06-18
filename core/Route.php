<?php
/**
 * 路由解析类
 */
//namespace Core;

class Route
{
    /** @var string 控制器 */
    private $ctr = 'Index';
    /** @var string 方法 */
    private $fun = 'index';
    /** @var null|array 参数  */
    private $parm = null;
    public function __construct()
    {
        //获取路由 解析路由
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $parmstr = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])) + 1);
            if (false){
                $route = require_once ROOT.'app/Middle.php';
            }else {
                $parm = explode('/', $parmstr);
                $this->ctr = $parm[0] ? $parm[0] : 'Index';
                if (!empty($parm[1])) $this->fun = $parm[1];
                for ($i = 2; isset($parm[$i]); $i++) {
                    if (empty($parm[$i])) break;
                    if (isset($parm[$i + 1])) $_GET[$parm[$i]] = $parm[$i + 1];
                    else break;
                    $i++;
                }
            }
        }
        //执行路由
        $this->doRoute($this->ctr,$this->fun,null);
    }

    /**
     * 执行路由
     * @param $ctr string 控制器
     * @param $fun string 方法
     * @param $parm string 参数(暂未使用)
     */
    private function doRoute($ctr,$fun,$parm)
    {
        $ctr = ucfirst($ctr);
        $ctrs = 'App\Controller\\'. $ctr;
        if (!class_exists($ctrs)) exit('不存在控制器');
        $ctr = new $ctrs();
        if (!method_exists($ctr,$fun)) exit('不存在方法');
        $ctr->$fun();
    }

    /**
     * 执行前置方法
     * @param $name string 方法名
     */
    private function doMiddle($name)
    {
        $ctr = new app\Middle();
        $ctr->$name();
    }
}