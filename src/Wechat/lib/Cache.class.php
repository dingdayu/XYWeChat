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
// | DATE: 2015/12/18 16:11
// +----------------------------------------------------------------------
// | FILE: Cache.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat\lib;


class Cache
{
    /**
     * �����ļ�ǰ׺
     *
     * @var string
     */
    protected $prefix;

    /**
     * ���û����ļ�ǰ׺
     *
     * @param string $prefix �����ļ�ǰ׺
     */
    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * set  ���û���
     *
     * @param string    $key        ����
     * @param string    $value      ��ֵ
     * @param int       $lifetime   ��ʱʱ��
     *
     * @throws \Exception    �����ļ�д�����
     */
    public static function set($key, $value, $lifetime = 7200)
    {
        $data = array(
            'data'       => $value,
            'expires_in' => time() + $lifetime - 1000, //����Ч�ڼ���1�룬�������������⣬����ʧЧ����
        );

        if (!file_put_contents(self::getCacheFile($key), serialize($data))) {
            throw new \Exception('Access toekn ����ʧ��');
        }
    }

    /**
     * get  ��ȡ����
     *
     * @param string    $key          ����
     * @param string    $default      Ĭ��ֵ
     *
     * @return string
     */
    public static function get($key, $default = null)
    {
        $return = null;

        $file = self::getCacheFile($key);

        if (file_exists($file) && ($data = unserialize(file_get_contents($file)))) {
            $return = $data['expires_in'] > time() ? $data['data'] : null;
        }

        //�ж�Ĭ��ֵ
        if ($return === null && $default !== null) {
            $return = $default;
        }

        return $return;
    }

    /**
     * del  ɾ������
     *
     * @param string    $key          ����
     *
     * @return bool
     */
    public static function del($key)
    {
        try {
            unlink(self::getCacheFile($key));
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * getCacheFile ��ȡ�����ļ�·��
     *
     * @param string    $key          ����
     *
     * @return string
     */
    protected function getCacheFile($key)
    {
        return sys_get_temp_dir().DIRECTORY_SEPARATOR.md5($this->prefix.$key);
    }
}