<?php

/**
 * 微信插件唯一入口文件.
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月11日14:46:37
 * @Blog: http://blog.dingxiaoyu.com
 */
include './../XYWeChat.php';

//初始化微信类
$wechat = new \XYWeChat\WeChat(TRUE);

echo $wechat->run();
