<?php

include 'XYWeChat.php';

/**
 * ��ҳ��Ȩ
 */
/**
 * Description: ��ȡCODE
 * @param $scope snsapi_base��������Ȩҳ�棬ֻ�ܻ��OpenId;snsapi_userinfo������Ȩҳ�棬���Ի��������Ϣ
 * ������ת��redirect_uri/?code=CODE&state=STATE ͨ��GET��ʽ��ȡcode��state
 */
$redirect_uri = 'http://www.xyser.com/XYWeChat/oauth.php';
$code = \XYWeChat\WeChatOAuth::getCode($redirect_uri, $state=1, $scope='snsapi_userinfo');
/**
 * Description: ͨ��code��ȡ��ҳ��Ȩaccess_token
 * ������ע�⣬����ͨ��code��ȡ����ҳ��Ȩaccess_token,�����֧���е�access_token��ͬ��
 * ���ںſ�ͨ�������ӿ�����ȡ��ҳ��Ȩaccess_token��
 * �����ҳ��Ȩ��������Ϊsnsapi_base���򱾲����л�ȡ����ҳ��Ȩaccess_token��ͬʱ��Ҳ��ȡ����openid��snsapi_baseʽ����ҳ��Ȩ���̼�����Ϊֹ��
 * @param $code getCode()��ȡ��code����
 */
$code = $_GET['code'];
$access_token = \XYWeChat\WeChatOAuth::getAccessTokenAndOpenId($code);
print_r($access_token);

$userarr = \XYWeChat\WeChatOAuth::getUserInfo($access_token['access_token'],$access_token['openid']);

print_r($userarr);