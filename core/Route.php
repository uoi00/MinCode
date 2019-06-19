<?php
/**
 * 路由解析类
 */

use App\Controller;

class Route
{
    /** @var null|array 参数  */
    private $parm = null;
    public function __construct()
    {
        //获取路由 解析路由
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $file = dirname($_SERVER['PHP_SELF']);
            if ($file == '/') $parmstr = substr($_SERVER['REQUEST_URI'], strlen($file) );
            else $parmstr = substr($_SERVER['REQUEST_URI'], strlen($file) + 1);
        }else $parmstr = '';
        //执行路由
        $route = explode('?',$parmstr);
        $this->doRoute($route[0]);
    }

    /**
     * 处理路由路径 是路径规范化
     * @param string $route 路由路径
     * @return string 处理后的路由
     */
    private static function routeString(string $route){
        if ($route[0] == '/') return substr($route,1);
        return $route;
    }

    /**
     * 运行路由组的操作
     * @param array $group 组名称
     * @param bool $opt 操作 true前置 fasle后置
     * @return null
     * @throws null
     */
    private static function runRroup(array $group,bool $opt=true){
        if ($group === []) return null;
        $opt = $opt? 'before' : 'after';
        $groups = include ROOT.'conf/routeGroup.php';
        foreach ($group as $v) {
            if (isset($groups[$v])){
                foreach ($groups[$v][$opt] as $vv) self::runFun($vv);
            }
            else throw new Exception('不存在路由组'.$v);
        }
        return null;
    }

    /**
     * 执行方法
     * @param string $fun 方法名
     * @param array $para 参数
     * @return mixed 执行结果
     * @throws Exception null
     */
    private static function runFun(string $fun,array $para=[]){
        $f = explode('@',$fun);
        if (empty($f[1])) throw new Exception('错误的方法'.$fun);
        $className = new ReflectionClass('App\Controller\\'.$f[0]);
        $obj = $className->newInstanceArgs();
        return call_user_func_array([$obj,$f[1]],$para);
//        $obj = new $f[0]();
//        if (empty($para)) return call_user_func($obj->$para[1]);
//        else
//            return $obj->$f[1]();
    }

    /**
     * 执行路由
     * @param $route string 路由路径
     * @throws null
     */
    private function doRoute(string $route)
    {
        //读取路由配置
        $routeConfig = include ROOT.'/conf/route.php';
        if (empty($routeConfig) || !is_array($routeConfig))
            throw new Exception('请配置路由文件 conf/route.php');
        $setRoute = ''; //在配置文件中设置的路由 并入输入路由匹配的路由路径
        //能否直接找到输入路由的配置
        if (isset($routeConfig[$route])) {
            $routes = $routeConfig[self::routeString($route)];
            $setRoute = $route;
        }
        elseif (isset($routeConfig['/'.$route])) {
            $routes = $routeConfig[self::routeString($route)];
            $setRoute = $route;
        }
        else { //遍历查找
            foreach ($routeConfig as $k=>$v){
                if ($k == '') continue;
                $k = self::routeString($k);
                if (strpos($route,$k) === 0){
                    $routes = $v;
                    $setRoute = $k;
                    break;
                }
            }
        }
        if (empty($routes)) err404(); //找不到匹配路由 返回404退出
        else{
            if ($routes[0] != 'any' && $_SERVER['REQUEST_METHOD'] != strtoupper($routes[0])) err404(); //请求方法不对
            //运行请求组
            self::runRroup($routes[3],true);
            //检测路由参数
            if ($route == $setRoute){
                self::runFun($routes[1]);
            }
            else{
                $para = substr($route,strlen($setRoute)+1); //获取请求路由的参数部分
                $para = explode('/',$para); //解析参数
                if (count($para) > count($routes[2]) ) err404(); //如果请求路由的参数比配置路由多 这是错误请求
                foreach ($routes[2] as $k=>$v){
                    if (empty($para[$k])) { //如果不存在配置路由的对应参数
                        if ($v[0] == '?') $para[] = null; //如果这是一个可选参数
                        else err404(); //如果不是可选参数
                    }
                }
                self::runFun($routes[1],$para);
            }
        }
    }
}