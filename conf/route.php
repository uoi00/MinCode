<?php
/**
 * 路由配置
 * 路由路径 开始不加 /
 * 请求方式 get post delete any
 * 请求对象@方法
 * 参数 ？表示可选参数 没有表示必选参数
 * 路由组 一个路由可以有多个组
 */

return [
    '' => [],
    'test' => ['any','Index@index',['name','?age'],[]],
];