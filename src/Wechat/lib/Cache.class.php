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
     * 缓存文件前缀
     *
     * @var string
     */
    protected $prefix;

    /**
     * 设置缓存文件前缀
     *
     * @param string $prefix 缓存文件前缀
     */
    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * set  设置缓存
     *
     * @param string    $key        键名
     * @param string    $value      键值
     * @param int       $lifetime   超时时间
     *
     * @throws \Exception    缓存文件写入错误
     */
    public static function set($key, $value, $lifetime = 7200)
    {
        $data = array(
            'data'       => $value,
            'expires_in' => time() + $lifetime - 1000, //将有效期减少1秒，以免因网络问题，出现失效现象
        );

        if (!file_put_contents(self::getCacheFile($key), serialize($data))) {
            throw new \Exception('Access toekn 缓存失败');
        }
    }

    /**
     * get  获取缓存
     *
     * @param string    $key          键名
     * @param string    $default      默认值
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

        //判断默认值
        if ($return === null && $default !== null) {
            $return = $default;
        }

        return $return;
    }

    /**
     * del  删除缓存
     *
     * @param string    $key          键名
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
     * getCacheFile 获取缓存文件路径
     *
     * @param string    $key          键名
     *
     * @return string
     */
    protected function getCacheFile($key)
    {
        return sys_get_temp_dir().DIRECTORY_SEPARATOR.md5($this->prefix.$key);
    }
}