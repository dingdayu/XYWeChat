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
// | DATE: 2015/12/19 16:43
// +----------------------------------------------------------------------
// | FILE: Media.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat;


class Media
{
    const API_TEMPORARY_UPLOAD    = 'http://file.api.weixin.qq.com/cgi-bin/media/upload';
    const API_FOREVER_UPLOAD      = 'https://api.weixin.qq.com/cgi-bin/material/add_material';
    const API_TEMPORARY_GET       = 'https://api.weixin.qq.com/cgi-bin/media/get';
    const API_FOREVER_GET         = 'https://api.weixin.qq.com/cgi-bin/material/get_material';
    const API_FOREVER_NEWS_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/material/add_news';
    const API_FOREVER_NEWS_UPDATE = 'https://api.weixin.qq.com/cgi-bin/material/update_news';
    const API_FOREVER_DELETE      = 'https://api.weixin.qq.com/cgi-bin/material/del_material';
    const API_FOREVER_COUNT       = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount';
    const API_FOREVER_LIST        = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material';

    /**
     * �����ϴ�������
     *
     * @var array
     */
    protected $allowTypes = array(
        'image',
        'voice',
        'video',
        'thumb',
        'news',
    );

    /**
     * Http����
     *
     * @var Http
     */
    protected $http;

    /**
     * �Ƿ��ϴ������ز�
     *
     * @var bool
     */
    protected $forever = false;

    /**
     * constructor
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->http = new Http(new AccessToken($appId, $appSecret));
    }

    /**
     * �Ƿ�Ϊ�����ز�
     *
     * @return Media
     */
    public function forever()
    {
        $this->forever = true;

        return $this;
    }

    /**
     * �ϴ�ý���ļ�
     *
     * @param string $type
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    protected function upload($type, $path, $params = array())
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new Exception("�ļ������ڻ򲻿ɶ� '$path'");
        }

        if (!in_array($type, $this->allowTypes, true)) {
            throw new Exception("�����ý������ '{$type}'");
        }

        $queries = array('type' => $type);

        $options = array(
            'files' => array('media' => $path),
        );

        $url = $this->getUrl($type, $queries);

        $response = $this->http->post($url, $params, $options);

        $this->forever = false;

        if ($type == 'image') {
            return $response;
        }

        $response = Arr::only($response, array('media_id', 'thumb_media_id'));

        return array_pop($response);
    }

    /**
     * �ϴ���Ƶ
     *
     * �е㲻һ��������
     *
     * @param string $path
     * @param string $title
     * @param string $description
     *
     * @return string
     */
    public function video($path, $title, $description)
    {
        $params = array(
            'description' => JSON::encode(
                array(
                    'title'        => $title,
                    'introduction' => $description,
                )
            ),
        );

        return $this->upload('video', $path, $params);
    }

    /**
     * ����ͼ���ز�
     *
     * @param array $articles
     *
     * @return string
     */
    public function news(array $articles)
    {
        $params = array('articles' => $articles);

        $response = $this->http->jsonPost(self::API_FOREVER_NEWS_UPLOAD, $params);

        return $response['media_id'];
    }

    /**
     * �޸�ͼ����Ϣ
     *
     * @param string $mediaId
     * @param array  $article
     * @param int    $index
     *
     * @return bool
     */
    public function updateNews($mediaId, $article, $index = 0)
    {
        $params = array(
            'media_id' => $mediaId,
            'index'    => $index,
            'articles' => isset($article['title']) ? $article : (isset($article[$index]) ? $article[$index] : array()),
        );

        return $this->http->jsonPost(self::API_FOREVER_NEWS_UPDATE, $params);
    }

    /**
     * ɾ�������ز�
     *
     * @param string $mediaId
     *
     * @return bool
     */
    public function delete($mediaId)
    {
        return $this->http->jsonPost(self::API_FOREVER_DELETE, array('media_id' => $mediaId));
    }

    /**
     * ͼƬ�ز�����
     *
     * @param string $type
     *
     * @return array|int
     */
    public function stats($type = null)
    {
        $response = $this->http->get(self::API_FOREVER_COUNT);

        $response['voice'] = $response['voice_count'];
        $response['image'] = $response['image_count'];
        $response['video'] = $response['video_count'];
        $response['news']  = $response['news_count'];

        $response = new Bag($response);

        return $type ? $response->get($type) : $response;
    }

    /**
     * ��ȡ�����ز��б�
     *
     * example:
     *
     * {
     *   "total_count": TOTAL_COUNT,
     *   "item_count": ITEM_COUNT,
     *   "item": [{
     *             "media_id": MEDIA_ID,
     *             "name": NAME,
     *             "update_time": UPDATE_TIME
     *         },
     *         //���ܻ��ж���ز�
     *   ]
     * }
     *
     * @param string $type
     * @param int    $offset
     * @param int    $count
     *
     * @return array
     */
    public function lists($type, $offset = 0, $count = 20)
    {
        $params = array(
            'type'   => $type,
            'offset' => intval($offset),
            'count'  => min(20, $count),
        );

        return $this->http->jsonPost(self::API_FOREVER_LIST, $params);
    }

    /**
     * ����ý���ļ�
     *
     * @param string $mediaId
     * @param string $filename
     *
     * @return mixed
     */
    public function download($mediaId, $filename = '')
    {
        $params = array('media_id' => $mediaId);

        $method = $this->forever ? 'jsonPost' : 'get';
        $api    = $this->forever ? self::API_FOREVER_GET : self::API_TEMPORARY_GET;

        $contents = $this->http->{$method}($api, $params);

        $filename = $filename ? $filename : $mediaId;

        if (!is_array($contents)) {
            $ext = File::getStreamExt($contents);

            file_put_contents($filename.$ext, $contents);

            return $filename.$ext;
        } else {
            return $contents;
        }
    }

    /**
     * ħ������
     *
     * <pre>
     * $media->image($path); // $media->upload('image', $path);
     * </pre>
     *
     * @param string $method
     * @param array  $args
     *
     * @return string
     */
    public function __call($method, $args)
    {
        $args = array(
            $method,
            array_shift($args),
        );

        return call_user_func_array(array(__CLASS__, 'upload'), $args);
    }

    /**
     * ��ȡAPI
     *
     * @param string $type
     * @param array  $queries
     *
     * @return string
     */
    protected function getUrl($type, $queries = array())
    {
        if ($type === 'news') {
            $api = self::API_FOREVER_NEWS_UPLOAD;
        } else {
            $api = $this->forever ? self::API_FOREVER_UPLOAD : self::API_TEMPORARY_UPLOAD;
        }

        return $api.'?'.http_build_query($queries);
    }
}