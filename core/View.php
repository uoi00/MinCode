<?php
/**
 * 模板文件
 */

namespace Core;
use Jenssegers\Blade\Blade;

class View{

    public function display($file,$opt = null)
    {
        if (!is_array($opt)) $opt = null;
        $views = ROOT . '/view/';
        $cache = ROOT . '/view/';

        $blade = new Blade($views, $cache);

        echo $blade->make('test', ['name' => 'John Doe','num' => 3]);
    }
}