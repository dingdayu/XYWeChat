<?php
// +----------------------------------------------------------------------
// | DINGDAYU [ Rebellious boy ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://dingxiaoyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( dingxiaoyu.com )
// +----------------------------------------------------------------------
// | Author: dingdayu <614422099@qq.com>
// +----------------------------------------------------------------------
// | DATE: 2015/12/18 15:53
// +----------------------------------------------------------------------
// | FILE: AccessToken.calss.php
// +----------------------------------------------------------------------


namespace XYser\Wechat;

use XYser\Wechat\lib\HTTP;
use XYser\Wechat\lib\Cache;

class AccessToken
{

    /**
     * @var string  ΢��APPID
     */
    protected $appId;

    /**
     * @var string  ΢����Կ
     */
    protected $appSecret;

    /**
     * @var cache   ������
     */
    protected $cache;
    protected $cacheKey = 'access_token';

    // �����ַ
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/token';

    function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->cache     = new Cache($appId);
    }

    /**
     * ��ȡ΢��Access_Token
     *
     * @return string   accessToken
     */
    public static function getAccessToken ($refresh = false)
    {
        //��Ȿ���Ƿ��Ѿ�ӵ��access_token�����Ҽ��access_token�Ƿ����
        $accessToken = self::_checkAccessToken();
        if($accessToken === false || $refresh){
            $accessToken = self::_getAccessToken();
        }
        return $accessToken;
    }

    private function _getAccessToken()
    {
        $http = new Http();
        $params = array(
            'appid'      => $this->appId,
            'secret'     => $this->appSecret,
            'grant_type' => 'client_credential',
        );

        $content = $http->get(self::API_TOKEN_GET, $params);

        $this->cache->set($this->cacheKey,$content,$content['expires_in']);

        $accessToken = $content['access_token'];
        return $accessToken;
    }

    /**
     * @descrpition ���΢��ACCESS_TOKEN�Ƿ����
     *              -10��Ԥ���������ӳ�ʱ��
     * @return bool
     */
    private function _checkAccessToken()
    {
        $data = $this->cache->get($this->cacheKey);
        if($data === null) {
            return false;
        } else {
            return $data['access_token'];
        }
    }

}