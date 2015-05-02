<?php

include './../XYWeChat.php';

$tousername = "oiRv4t0Rl9qVMT62yDDhtUmlYQC4";
//发送客服内容
//$arr =  \XYWeChat\ResponseInitiative::text($tousername, '文本消息内容sdf');

//print_r($arr);

		$item = array(
            'title'=>'title',
            'description'=>'description',
            'url'=>'http://c.hiphotos.baidu.com/zhidao/wh%3D600%2C800/sign=955679078b82b9013df8cb3543bd854f/71cf3bc79f3df8dc8baaddb1cc11728b4710287e.jpg',
            'picurl'=>'www.baidu.com',
        );

		$test =  \XYWeChat\ResponseInitiative::news( $tousername, array($item));
		
		print_r($test);