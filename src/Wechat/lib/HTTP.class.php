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
// | DATE: 2015/12/18 17:12
// +----------------------------------------------------------------------
// | FILE: HTTP.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat\lib;


use XYser\Wechat\AccessToken;

class HTTP extends Curl
{
    /**
     * token
     *
     * @var AccessToken
     */
    protected $token;


    /**
     * @var int 错误重试次数
     */
    protected $retry = 3;

    /**
     * json请求
     *
     * @var bool
     */
    protected $json = false;

    /**
     * 缓存类
     *
     * @var Cache
     */
    protected $cache;

    /**
     * constructor
     *
     * @param AccessToken $token
     */
    public function __construct(AccessToken $token = null)
    {
        $this->token = $token;
        parent::__construct();
    }

    /**
     * 设置请求access_token
     *
     * @param AccessToken $token
     */
    public function setToken(AccessToken $token)
    {
        $this->token = $token;
    }

    public function isJSON(){
        $this->json = true;
    }

    /**
     * 发起一个HTTP/HTTPS的请求
     *
     * @param string $url     接口的URL
     * @param string $method  请求类型   GET | POST
     * @param array  $params  接口参数
     * @param array  $options 其它选项
     * @param int    $retry   重试次数
     *
     * @return array | boolean
     */
    public function request($url, $method = self::GET, $params = array(), $options = array())
    {
        $URL_REFRESH = $url;
        if ($this->token) {
            $url .= (stripos($url, '?') ? '&' : '?').'access_token='.  $this->token->getAccessToken();
        }
        $method = strtoupper($method);

        if ($this->json) {
            $options['json'] = true;
        }

        $response = parent::request($url, $method, $params, $options);

        $this->json = false;

        if (empty($response['data'])) {
            throw new \Exception('服务器无响应');
        }

        if (!preg_match('/^[\[\{]\"/', $response['data'])) {
            return $response['data'];
        }


        $contents = json_decode($response['data'], true);

        // while the response is an invalid JSON structure, returned the source data
        if (JSON_ERROR_NONE !== json_last_error()) {
            return $response['data'];
        }

        if (isset($contents['errcode']) && 0 !== $contents['errcode']) {
            if (empty($contents['errmsg'])) {
                $contents['errmsg'] = 'Unknown';
            }

            // access token 超时重试处理
            if ($this->token && in_array($contents['errcode'], array('40001', '42001')) && $this->retry > 0) {
                // force refresh
                $URL_REFRESH = (stripos($URL_REFRESH, '?') ? '&' : '?').'access_token='.   $this->token->getToken(true);

                return $this->request($URL_REFRESH, $method, $params, $options, --$this->retry);
            }

            throw new \Exception("[{$contents['errcode']}] ".$contents['errmsg'], $contents['errcode']);
        }

        if ($contents === array('errcode' => '0', 'errmsg' => 'ok')) {
            return true;
        }

        return $contents;
    }

    /**
     * 魔术调用
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (stripos($method, 'json') === 0) {
            $method = strtolower(substr($method, 4));
            $this->json = true;
        }

        $result = call_user_func_array(array($this, $method), $args);

        return $result;
    }


}