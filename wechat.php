<?php
namespace XYWeChat;
use XYWeChat\Wechat;

/**
 * 微信插件唯一入口文件.
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月11日14:46:37
 * @Blog: http://blog.dingxiaoyu.com
 */
//引入配置文件
include_once __DIR__.'/config.php';
//引入自动载入函数
include_once __DIR__.'/autoloader.php';

//调用自动载入函数
AutoLoader::register();
//初始化微信类
$wechat = new WeChat(WECHAT_TOKEN, TRUE);



//首次使用需要注视掉下面这1行（26行），并打开最后一行（29行）
echo $wechat->run();
//首次使用需要打开下面这一行（29行），并且注释掉上面1行（26行）。本行用来验证URL
//$wechat->checkSignature();