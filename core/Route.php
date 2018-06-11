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
        //接受路由 解析路由
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $parmstr = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])) + 1);
            $parm = explode('/', $parmstr);
            $this->ctr = $parm[0] ? $parm[0] : 'Index';
            if (!empty($parm[1])) $this->fun = $parm[1];
        }
        $this->doRoute($this->ctr,$this->fun,null);
    }

    private function doRoute($ctr,$fun,$parm)
    {
        $ctrs = 'App\Controller\\'. $ctr;
        $ctr = new $ctrs();
        $ctr->$fun();
    }
}


//解析中间件


//访问路由