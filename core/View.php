<?php
/**
 * 模板文件
 */
//namespace Core;

class View{

    public function display($file,$opt = null)
    {
//        $loader = new Twig_Loader_Array(array(
//            'index' => 'Hello {{ name }}!',
//        ));
//        $twig = new Twig_Environment($loader);
//        echo $twig->render('index', array('name' => 'Fabien'));

        //设置模板路径
        $loader = new Twig_Loader_Filesystem(TMP_PATH);
        //模板缓存路径
        $twig = new Twig_Environment($loader, array(
            'cache' => TMP_CACHE,
        ));

        echo $twig->render($file, $opt);
    }
}