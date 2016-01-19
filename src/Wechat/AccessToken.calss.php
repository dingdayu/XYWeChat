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
     * @var string  微信APPID
     */
    protected $appId;

    /**
     * @var string  微信秘钥
     */
    protected $appSecret;

    /**
     * @var cache   缓存类
     */
    protected $cache;
    protected $cacheKey = 'access_token';

    // 请求地址
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/token';

    function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->cache     = new Cache($appId);
    }

    /**
     * 获取微信Access_Token
     *
     * @return string   accessToken
     */
    public static function getAccessToken ($refresh = false)
    {
        //检测本地是否已经拥有access_token，并且检测access_token是否过期
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
     * @descrpition 检测微信ACCESS_TOKEN是否过期
     *              -10是预留的网络延迟时间
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