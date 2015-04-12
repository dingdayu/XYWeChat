<?php

include './../XYWeChat.php';

$touser = "oiRv4t0Rl9qVMT62yDDhtUmlYQC4";
$templateId = "BuKHPrOTOugbRch34tTskaKEoPdAJyN8r-4N19lGtJE";

$data = array(
    'title'=>array('value'=>'测试消息。', 'color'=>'#0A0A0A'),
    'time'=>array('value'=>time(), 'color'=>'#CCCCCC'),
);
$url = "http://www.xyser.com/XYWeChat/demo/oauth.php";

//发送模板消息
$arr =  \XYWeChat\TemplateMessage::sendTemplateMessage($data, $touser, $templateId, $url);

print_r($arr);