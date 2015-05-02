<?php

include './../XYWeChat.php';

$arr = array(
	array('id'=>1, 'pid'=>'', 'name'=>'获取信息', 'type'=>'click', 'code'=>'test'),
	array('id'=>2, 'pid'=>'', 'name'=>'测试方法', 'type'=>'click', 'code'=>''),
	array('id'=>3, 'pid'=>'', 'name'=>'关于框架', 'type'=>'about', 'code'=>''),
	array('id'=>4, 'pid'=>'1', 'name'=>'获取图文', 'type'=>'click', 'code'=>'tuwen'),
	array('id'=>5, 'pid'=>'1', 'name'=>'获多图文', 'type'=>'click', 'code'=>'duotuwen'),
	array('id'=>6, 'pid'=>'1', 'name'=>'获取图片', 'type'=>'click', 'code'=>'getimg'),
	array('id'=>7, 'pid'=>'2', 'name'=>'获取资料', 'type'=>'view', 'code'=>'http://www.xyser.com/XYWeChat/demo/oauth.php'),
	array('id'=>8, 'pid'=>'2', 'name'=>'扫一扫', 'type'=>'scancode_push', 'code'=>'saosao'),
	array('id'=>9, 'pid'=>'2', 'name'=>'扫一扫消息', 'type'=>'scancode_waitmsg', 'code'=>'scancode_waitmsg'),
	array('id'=>10, 'pid'=>'2', 'name'=>'拍照', 'type'=>'pic_sysphoto', 'code'=>'pic_sysphoto'),
	array('id'=>11, 'pid'=>'3', 'name'=>'发送位置', 'type'=>'location_select', 'code'=>'location_select'),
);

if($_GET['action'] == 'set'){

	$echo =  \XYWeChat\Menu::setMenu($arr);
	print_r($echo );
}
echo '<br>'.time();