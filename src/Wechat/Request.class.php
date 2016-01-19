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
// | DATE: 2015/12/19 14:51
// +----------------------------------------------------------------------
// | FILE: Request.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat;

use XYser\Wechat\lib\XML;
use XYser\Wechat\lib\Crypt;

class Request
{
    /**
     * 以数组的形式保存微信服务器每次发来的请求
     * @var array
     */
    private $request;

    protected $appId;
    protected $token;
    protected $encodingAESKey;
    protected $security;

    /**
     * 初始化，判断此次请求是否为验证请求，并以数组形式保存
     */
    public function __construct($appId, $token, $encodingAESKey = null) {

        $this->appId          = $appId;
        $this->token          = $token;
        $this->encodingAESKey = $encodingAESKey;
    }

    public function Request(){
        //未通过消息真假性验证
        if ($this->isValid() && $this->validateSignature()) {
            //return $_GET['echostr'];
            echo strip_tags($_GET['echostr']);
            exit;
        }

        //接收并解析数据
        $input = $this->prepareInput();

        //将数组键名转换为小写
        $this->request = array_change_key_case($input, CASE_LOWER);
    }

    protected function prepareInput()
    {
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
                $xmlInput = $GLOBALS['HTTP_RAW_POST_DATA'];
            } else {
                $xmlInput = file_get_contents('php://input');
            }

            if (empty($_REQUEST['echostr']) && empty($xmlInput) && !empty($_REQUEST['signature'])) {
                throw new \Exception("没有读取到消息 XML，请在 php.ini 中打开 always_populate_raw_post_data=On", 500);
            }
        } else {
            $xmlInput = file_get_contents('php://input');
        }

        $input = XML::parse($xmlInput);

        if (!empty($_REQUEST['encrypt_type']) && $_REQUEST['encrypt_type'] === 'aes') {
            $this->security = true;

            $input = $this->getCrypt()->decryptMsg(
                $_REQUEST['msg_signature'],
                $_REQUEST['nonce'],
                $_REQUEST['timestamp'],
                $xmlInput
            );
        }
        return $input;
    }

    /**
     * 获取Crypt服务
     *
     * @return Crypt
     */
    protected function getCrypt()
    {
        static $crypt;

        if (!$crypt) {
            if (empty($this->encodingAESKey) || empty($this->token)) {
                throw new \Exception("加密模式下 'encodingAESKey' 与 'token' 都不能为空！");
            }

            $crypt = new Crypt($this->appId, $this->token, $this->encodingAESKey);
        }

        return $crypt;
    }

    /**
     * 判断此次请求是否为验证请求
     * @return boolean
     */
    private function isValid() {
        return isset($_GET['echostr']);
    }

    /**
     * 判断验证请求的签名信息是否正确
     * @param  string $token 验证信息
     * @return boolean
     */
    private function validateSignature() {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $signatureArray = array($this->token, $timestamp, $nonce);
        sort($signatureArray, SORT_STRING);
        return sha1(implode($signatureArray)) == $signature;
    }

    /**
     * 获取本次请求中的参数，不区分大小
     * @param  string $param 参数名，默认为无参
     * @return mixed
     */
    protected function getRequest($param = FALSE) {
        if ($param === FALSE) {
            return $this->request;
        }
        $param = strtolower($param);
        if (isset($this->request[$param])) {
            return $this->request[$param];
        }
        return NULL;
    }

    /**
     * 分析消息类型，并分发给对应的函数
     * @param  string $request 参数名，默认为无参
     * @return void
     */
    public function run($request = false) {
        if($request) return $this->request;
        return WechatRequest::switchType($this->request);
    }
}