<?php
    header("content-type:text/html;charset=utf-8");
    
    //定义项目路径
    define('APP_PATH','./Application/');
    define('APP_DEBUG',true);

    //开闭安全文件
    define('BUILD_DIR_SECURE', true);

    //加载核心文件
    require './ThinkPHP/ThinkPHP.php';
    
?>
