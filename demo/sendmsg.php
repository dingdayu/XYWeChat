<?php
ini_set("display_errors",1);
include './../XYWeChat.php';


$tousername = "oiRv4t0Rl9qVMT62yDDhtUmlYQC4";

//发送客服文本
if($_GET['action'] == 'kefuwenben'){
//发送客服内容
	$arr =  \XYWeChat\ResponseInitiative::text($tousername, '文本消息内容sdf');

	print_r($arr);

}elseif($_GET['action'] == 'kefutuwen'){
		$item = array(
            'title'=>'title',
            'description'=>'description',
            'url'=>'http://c.hiphotos.baidu.com/zhidao/wh%3D600%2C800/sign=955679078b82b9013df8cb3543bd854f/71cf3bc79f3df8dc8baaddb1cc11728b4710287e.jpg',
            'picurl'=>'www.baidu.com',
        );

		$test =  \XYWeChat\ResponseInitiative::news( $tousername, array($item));
		
		print_r($test);
}elseif($_GET['action'] == 'uploadNews'){
		 $neir = file_get_contents('html.txt');
		//echo $neir;
		$item = array(
                            array('thumb_media_id'=>'_5yNFt1geWI0O195hppQ-cVuoZfUU5eEhEddkBFsQQooZgFQE0KiTy8scqfYPkNg' , 'author'=>'作者' . time(), 'title'=>'标题','content'=>  $neir , 'content_source_url'=>'www.lanecn.com', 'digest'=>'摘要', 'show_cover_pic'=>'1')
				);
		print_r($item);
		$test =  \XYWeChat\AdvancedBroadcast::uploadNews( $item);
		
		print_r($test);
		//YsshQHBKxFcrIEYtwWrn-bO5JQW-Yhi8xZNexWNR7MGYPAtHz5r6VNRE77QN7KE2
		//m9C0hzxrpfG1X7iIqEchK7MV7_aqxQxQN_sGLcX0zYqjHxQMHk4Jc9j5tr0KCKzv
}elseif($_GET['action'] == 'preview'){
		//$neir = file_get_contents('html.txt');
		 //echo $neir;
		$item = "m9C0hzxrpfG1X7iIqEchK7MV7_aqxQxQN_sGLcX0zYqjHxQMHk4Jc9j5tr0KCKzv";

		$test2 =  \XYWeChat\AdvancedBroadcast::preview($tousername, 'mpnews' , $test['media_id']);
		
		print_r($test2);
		
}