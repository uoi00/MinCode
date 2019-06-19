<?php
/**
 * Index
 */
namespace App\Controller;

use Core\DB\DB;

class Index{

    public function index($name,$age=null){
        dd(url('test/test'));
        DB::$isSqlLog = true;
        DB::table('test')->where(['id'=>121])->get();
        DB::getSql();
    }
    public function test(){
        echo 'two test';
    }
}