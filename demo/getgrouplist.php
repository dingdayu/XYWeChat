<?php

include './../XYWeChat.php';

echo '获取用户列表<br>';

$openId = "oiRv4t0Rl9qVMT62yDDhtUmlYQC4";
//获取用户列表
$arr =  \XYWeChat\UserManage::getGroupList();
print_r($arr);

echo '<hr>获取获取粉丝列表<br>';

//获取获取粉丝列表
$arr = \XYWeChat\UserManage::getFansList();
print_r($arr);

echo '<hr>获取用户基本信息<br>';

//获取用户基本信息
$arr = \XYWeChat\UserManage::getUserInfo($arr['next_openid']);
print_r($arr);