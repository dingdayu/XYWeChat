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
// | DATE: 2015/12/19 14:36
// +----------------------------------------------------------------------
// | FILE: Crypt.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat\lib;


class Crypt
{
    /**
     * Ӧ��ID
     *
     * @var string
     */
    protected $appId;

    /**
     * Ӧ��token
     *
     * @var string
     */
    protected $token;

    /**
     * �����õ�AESkey
     *
     * @var string
     */
    protected $AESKey;

    /**
     * ���С
     *
     * @var int
     */
    protected $blockSize;

    const ERROR_INVALID_SIGNATURE   = -40001; // У��ǩ��ʧ��
    const ERROR_PARSE_XML            = -40002; // ����xmlʧ��
    const ERROR_CALC_SIGNATURE      = -40003; // ����ǩ��ʧ��
    const ERROR_INVALID_AESKEY      = -40004; // ���Ϸ���AESKey
    const ERROR_INVALID_APPID       = -40005; // У��AppIDʧ��
    const ERROR_ENCRYPT_AES         = -40006; // AES����ʧ��
    const ERROR_DECRYPT_AES         = -40007; // AES����ʧ��
    const ERROR_INVALID_XML         = -40008; // ����ƽ̨���͵�xml���Ϸ�
    const ERROR_BASE64_ENCODE       = -40009; // Base64����ʧ��
    const ERROR_BASE64_DECODE       = -40010; // Base64����ʧ��
    const ERROR_XML_BUILD            = -40011; // �����ʺ����ɻذ�xmlʧ��

    /**
     * constructor
     *
     * @param string $appId
     * @param string $token
     * @param string $encodingAESKey
     */
    public function __construct($appId, $token, $encodingAESKey)
    {
        if (!extension_loaded('mcrypt')) {
            throw new \Exception('Mcrypt ��չδ��װ��δ����');
        }

        if (strlen($encodingAESKey) !== 43) {
            throw new \Exception('Invalid AESKey.', self::ERROR_INVALID_AESKEY);
        }

        $this->appId     = $appId;
        $this->token     = $token;
        $this->AESKey    = base64_decode($encodingAESKey.'=', true);
        $this->blockSize = 32;// mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    }

    /**
     * ������ƽ̨�ظ��û�����Ϣ���ܴ��.
     * <ol>
     *    <li>��Ҫ���͵���Ϣ����AES-CBC����</li>
     *    <li>���ɰ�ȫǩ��</li>
     *    <li>����Ϣ���ĺͰ�ȫǩ�������xml��ʽ</li>
     * </ol>
     *
     * @param string $xml       ����ƽ̨���ظ��û�����Ϣ��xml��ʽ���ַ���
     * @param string $nonce     ������������Լ����ɣ�Ҳ������URL������nonce
     * @param int    $timestamp ʱ����������Լ����ɣ�Ҳ������URL������timestamp
     *
     * @return string ���ܺ�Ŀ���ֱ�ӻظ��û������ģ�����msg_signature, timestamp,
     *                nonce, encrypt��xml��ʽ���ַ���
     */
    public function encryptMsg($xml, $nonce = null, $timestamp = null)
    {
        $encrypt = $this->encrypt($xml, $this->appId);

        !is_null($nonce) || $nonce = substr($this->appId, 0, 10);
        !is_null($timestamp) || $timestamp = time();

        //���ɰ�ȫǩ��
        $signature = $this->getSHA1($this->token, $timestamp, $nonce, $encrypt);

        $response = array(
            'Encrypt'      => $encrypt,
            'MsgSignature' => $signature,
            'TimeStamp'    => $timestamp,
            'Nonce'        => $nonce,
        );

        //������Ӧxml
        return XML::build($response);
    }

    /**
     * ������Ϣ����ʵ�ԣ����һ�ȡ���ܺ������.
     * <ol>
     *    <li>�����յ����������ɰ�ȫǩ��������ǩ����֤</li>
     *    <li>����֤ͨ��������ȡxml�еļ�����Ϣ</li>
     *    <li>����Ϣ���н���</li>
     * </ol>
     *
     * @param string $msgSignature ǩ��������ӦURL������msg_signature
     * @param string $nonce        ���������ӦURL������nonce
     * @param string $timestamp    ʱ��� ��ӦURL������timestamp
     * @param string $postXML      ���ģ���ӦPOST���������
     *
     * @return array
     */
    public function decryptMsg($msgSignature, $nonce, $timestamp, $postXML)
    {
        //��ȡ����
        $array = XML::parse($postXML);

        if (empty($array)) {
            throw new \Exception('Invalid xml.', self::ERROR_PARSE_XML);
        }

        $encrypted  = $array['Encrypt'];

        //��֤��ȫǩ��
        $signature = $this->getSHA1($this->token, $timestamp, $nonce, $encrypted);

        if ($signature !== $msgSignature) {
            throw new \Exception('Invalid Signature.', self::ERROR_INVALID_SIGNATURE);
        }

        return XML::parse($this->decrypt($encrypted, $this->appId));
    }

    /**
     * �����Ľ��м���
     *
     * @param string $text  ��Ҫ���ܵ�����
     * @param string $appId app id
     *
     * @return string ���ܺ������
     */
    private function encrypt($text, $appId)
    {
        try {
            //���16λ����ַ�������䵽����֮ǰ
            $random = $this->getRandomStr();
            $text   = $random.pack('N', strlen($text)).$text.$appId;

            // �����ֽ���
            // $size   = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv     = substr($this->AESKey, 0, 16);

            //ʹ���Զ������䷽ʽ�����Ľ��в�λ���
            $text   = $this->encode($text);

            mcrypt_generic_init($module, $this->AESKey, $iv);

            //����
            $encrypted = mcrypt_generic($module, $text);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);

            //ʹ��BASE64�Լ��ܺ���ַ������б���
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), self::ERROR_ENCRYPT_AES);
        }
    }

    /**
     * �����Ľ��н���
     *
     * @param string $encrypted ��Ҫ���ܵ�����
     * @param string $appId     app id
     *
     * @return string ���ܵõ�������
     */
    private function decrypt($encrypted, $appId)
    {
        try {
            //ʹ��BASE64����Ҫ���ܵ��ַ������н���
            $ciphertext = base64_decode($encrypted, true);
            $module     = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv         = substr($this->AESKey, 0, 16);

            mcrypt_generic_init($module, $this->AESKey, $iv);

            //����
            $decrypted = mdecrypt_generic($module, $ciphertext);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), self::ERROR_DECRYPT_AES);
        }

        try {
            //ȥ����λ�ַ�
            $result = $this->decode($decrypted);

            //ȥ��16λ����ַ���,�����ֽ����AppId
            if (strlen($result) < 16) {
                return '';
            }

            $content   = substr($result, 16, strlen($result));
            $listLen   = unpack('N', substr($content, 0, 4));
            $xmlLen    = $listLen[1];
            $xml       = substr($content, 4, $xmlLen);
            $fromAppId = trim(substr($content, $xmlLen + 4));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), self::ERROR_INVALID_XML);
        }

        if ($fromAppId !== $appId) {
            throw new \Exception('Invalid appId.', self::ERROR_INVALID_APPID);
        }

        return $xml;
    }

    /**
     * �������16λ�ַ���
     *
     * @return string ���ɵ��ַ���
     */
    private function getRandomStr()
    {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'), 0, 16);
    }

    /**
     * ����SHA1ǩ��
     *
     * @return string
     */
    public function getSHA1()
    {
        try {
            $array = func_get_args();
            sort($array, SORT_STRING);

            return sha1(implode($array));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), self::ERROR_CALC_SIGNATURE);
        }
    }

    /**
     * ����Ҫ���ܵ����Ľ�����䲹λ
     *
     * @param string $text ��Ҫ������䲹λ����������
     *
     * @return string ���������ַ���
     */
    public function encode($text)
    {
        //������Ҫ����λ��
        $padAmount = $this->blockSize - (strlen($text) % $this->blockSize);

        $padAmount = $padAmount !== 0 ? $padAmount : $this->blockSize;

        //��ò�λ���õ��ַ�
        $padChr = chr($padAmount);

        $tmp = '';

        for ($index = 0; $index < $padAmount; $index++) {
            $tmp .= $padChr;
        }

        return $text.$tmp;
    }

    /**
     * �Խ��ܺ�����Ľ��в�λɾ��
     *
     * @param string $decrypted ���ܺ������
     *
     * @return string ɾ����䲹λ�������
     */
    public function decode($decrypted)
    {
        $pad = ord(substr($decrypted, -1));

        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }

        return substr($decrypted, 0, (strlen($decrypted) - $pad));
    }
}