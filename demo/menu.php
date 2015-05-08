<?php

include './../XYWeChat.php';

$arr = array(
	array('id'=>1, 'pid'=>'', 'name'=>'用户中心', 'type'=>'click', 'code'=>'test'),
	array('id'=>2, 'pid'=>'', 'name'=>'活动中心', 'type'=>'click', 'code'=>''),
	array('id'=>3, 'pid'=>'', 'name'=>'自助服务', 'type'=>'about', 'code'=>''),
	
	array('id'=>4, 'pid'=>'1', 'name'=>'注册邦定', 'type'=>'click', 'code'=>'tuwen'),
	array('id'=>5, 'pid'=>'1', 'name'=>'实名认证', 'type'=>'click', 'code'=>'duotuwen'),
	array('id'=>6, 'pid'=>'1', 'name'=>'修改信息', 'type'=>'click', 'code'=>'getimg'),
	
	array('id'=>7, 'pid'=>'1', 'name'=>'自驾游', 'type'=>'click', 'code'=>'getimg'),
	array('id'=>8, 'pid'=>'1', 'name'=>'免费领取', 'type'=>'click', 'code'=>'getimg'),
	array('id'=>9, 'pid'=>'2', 'name'=>'热门活动', 'type'=>'view', 'code'=>'http://www.xyser.com/XYWeChat/demo/oauth.php'),
	array('id'=>10, 'pid'=>'2', 'name'=>'扫一扫', 'type'=>'scancode_push', 'code'=>'saosao'),
	
	array('id'=>11, 'pid'=>'2', 'name'=>'景区门票', 'type'=>'scancode_waitmsg', 'code'=>'scancode_waitmsg'),
	array('id'=>12, 'pid'=>'2', 'name'=>'保险业务', 'type'=>'pic_sysphoto', 'code'=>'pic_sysphoto'),
	array('id'=>13, 'pid'=>'3', 'name'=>'汽车保养', 'type'=>'location_select', 'code'=>'location_select'),
);

if($_GET['action'] == 'set'){

	$echo =  \XYWeChat\Menu::setMenu($arr);
	print_r($echo );
}
echo '<br>'.time();