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
     * ���������ʽ����΢�ŷ�����ÿ�η���������
     * @var array
     */
    private $request;

    protected $appId;
    protected $token;
    protected $encodingAESKey;
    protected $security;

    /**
     * ��ʼ�����жϴ˴������Ƿ�Ϊ��֤���󣬲���������ʽ����
     */
    public function __construct($appId, $token, $encodingAESKey = null) {

        $this->appId          = $appId;
        $this->token          = $token;
        $this->encodingAESKey = $encodingAESKey;
    }

    public function Request(){
        //δͨ����Ϣ�������֤
        if ($this->isValid() && $this->validateSignature()) {
            //return $_GET['echostr'];
            echo strip_tags($_GET['echostr']);
            exit;
        }

        //���ղ���������
        $input = $this->prepareInput();

        //���������ת��ΪСд
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
                throw new \Exception("û�ж�ȡ����Ϣ XML������ php.ini �д� always_populate_raw_post_data=On", 500);
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
     * ��ȡCrypt����
     *
     * @return Crypt
     */
    protected function getCrypt()
    {
        static $crypt;

        if (!$crypt) {
            if (empty($this->encodingAESKey) || empty($this->token)) {
                throw new \Exception("����ģʽ�� 'encodingAESKey' �� 'token' ������Ϊ�գ�");
            }

            $crypt = new Crypt($this->appId, $this->token, $this->encodingAESKey);
        }

        return $crypt;
    }

    /**
     * �жϴ˴������Ƿ�Ϊ��֤����
     * @return boolean
     */
    private function isValid() {
        return isset($_GET['echostr']);
    }

    /**
     * �ж���֤�����ǩ����Ϣ�Ƿ���ȷ
     * @param  string $token ��֤��Ϣ
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
     * ��ȡ���������еĲ����������ִ�С
     * @param  string $param ��������Ĭ��Ϊ�޲�
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
     * ������Ϣ���ͣ����ַ�����Ӧ�ĺ���
     * @param  string $request ��������Ĭ��Ϊ�޲�
     * @return void
     */
    public function run($request = false) {
        if($request) return $this->request;
        return WechatRequest::switchType($this->request);
    }
}