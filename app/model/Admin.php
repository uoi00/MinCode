<?php
/**
 * 数据表模型
 */
namespace App\model;
use think\model;
class Admin extends Model
{
    protected $table = 'admin';
    public function getAll()
    {
        $user = Admin::get(1);
        var_dump($user->toArray());
    }
}
