<?php
/**
 * Index
 */
namespace App\Controller;
class Index{

    public function index(){
        $data = [
            'name' => 'aaaa',
            'num' => 4,
            'arr' => ['a','b','c'],
            'arrk' => ['a'=> 11, 'b'=> 22, 'c' => 33],
            'aa' => [
                ['aa','bb'],
                ['xx','zz'],
            ],
        ];
        display('index',$data);
    }
    public function test(){
        echo 'two test';
    }
}