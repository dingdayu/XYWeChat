<?php

include './../XYWeChat.php';

/**
 * 网页授权
 */
/**
 * Description: 获取CODE
 * @param $scope snsapi_base不弹出授权页面，只能获得OpenId;snsapi_userinfo弹出授权页面，可以获得所有信息
 * 将会跳转到redirect_uri/?code=CODE&state=STATE 通过GET方式获取code和state
 */
$redirect_uri = 'http://www.xyser.com/XYWeChat/oauth.php';
$code = \XYWeChat\WeChatOAuth::getCode($redirect_uri, $state=1, $scope='snsapi_userinfo');
/**
 * Description: 通过code换取网页授权access_token
 * 首先请注意，这里通过code换取的网页授权access_token,与基础支持中的access_token不同。
 * 公众号可通过下述接口来获取网页授权access_token。
 * 如果网页授权的作用域为snsapi_base，则本步骤中获取到网页授权access_token的同时，也获取到了openid，snsapi_base式的网页授权流程即到此为止。
 * @param $code getCode()获取的code参数
 */
$code = $_GET['code'];
$access_token = \XYWeChat\WeChatOAuth::getAccessTokenAndOpenId($code);
print_r($access_token);

$userarr = \XYWeChat\WeChatOAuth::getUserInfo($access_token['access_token'],$access_token['openid']);

print_r($userarr);