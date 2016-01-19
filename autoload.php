<?php

spl_autoload_register(function ($class) {
    if (false !== stripos($class, 'XYser\Wechat')) {
        require_once __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 4)).'.class.php';
    }
});
