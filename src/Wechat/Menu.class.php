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
// | DATE: 2015/12/18 15:52
// +----------------------------------------------------------------------
// | FILE: Menu.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat;

use XYser\Wechat\lib\HTTP;
/**
 * Class Menu   �˵�������
 *
 * @package XYser\Wechat
 */
class Menu
{
    const API_CREATE = 'https://api.weixin.qq.com/cgi-bin/menu/create';
    const API_GET    = 'https://api.weixin.qq.com/cgi-bin/menu/get';
    const API_DELETE = 'https://api.weixin.qq.com/cgi-bin/menu/delete';
    const API_QUERY  = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info';
    const API_CONDITIONAL = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional';

    const TYPE = array(
        'click',
        'view',
        'scancode_push',
        'scancode_waitmsg',
        'pic_sysphoto',
        'pic_photo_or_album',
        'location_select',
        'media_id',
        'view_limited'
    );

    protected $http;


    function __construct()
    {
        $this->http = new Http(new \AccessToken());
        $this->http->isJSON();
    }

    public function setMenu($menus, $matchrule = null)
    {
        if (!is_array($menus)) {
            throw new \Exception('�Ӳ˵������������������������������', 1);
        }

        $menus = $this->convertMenus($menus);

        if ($matchrule) {
            self::setConditional($menus, $matchrule);
        } else {
            $this->http->jsonPost(self::API_CREATE, array('button' => $menus));
        }

        return true;
    }

    public function setConditional ($menus, $matchrule)
    {
        $this->http->jsonPost(self::API_CREATE, array('button' => $menus, 'matchrule' => $matchrule));
    }

    /**
     * ��ȡ�˵�
     *
     * @return array
     */
    public function getMenu()
    {
        $menus = $this->http->get(self::API_GET);

        return empty($menus['menu']['button']) ? array() : $menus['menu']['button'];
    }

    /**
     * ɾ���˵�
     *
     * @return bool
     */
    public function del()
    {
        $this->http->get(self::API_DELETE);

        return true;
    }

    /**
     * ��ȡ�˵�����ѯ�ӿڣ��ܻ�ȡ�����ⷽʽ���õĲ˵���
     *
     * @return array
     */
    public function getCurrent()
    {
        $menus = $this->http->get(self::API_QUERY);
        return empty($menus) ? array() : $menus;
    }


    /**
     * extractMenus     ת������
     * @param array $menus
     *
     * @return array    ת���������
     */
    protected function convertMenus(array $menus)
    {
        foreach ($menus as $key => $menu) {
            $menus[$key] = $menu->toArray();

            if ($menu->sub_button) {
                $menus[$key]['sub_button'] = $this->convertMenus($menu->sub_button);
            }
        }
        return $menus;
    }
}