<?php

include './../XYWeChat.php';

$tousername = "oiRv4t0Rl9qVMT62yDDhtUmlYQC4";
//发送客服内容
$arr =  \XYWeChat\ResponseInitiative::text($tousername, '文本消息内容sdf');

print_r($arr);