<?php
/**
 * Test
 */
namespace App\Controller;
use App\model\Admin;

class Test{
    public function index(){
//        echo 'this test index';
        $a = new Admin();
        $a->getAll();
    }
    public function test(){
        echo 'this test test';
    }
}