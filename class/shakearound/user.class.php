<?php
namespace XYWeChat\ShakeAround;
/**
 * 摇一摇用户类
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月11日14:46:37
 * @Blog: http://blog.dingxiaoyu.com
 */
class User{
    /**
     * 获取摇周边的设备及用户信息
     * 获取设备信息，包括UUID、major、minor，以及距离、openID等信息。
	 * 根据 $ticket 获取用户及设备信息
     *
     * @param $ticket 摇周边业务的ticket，可在摇到的URL中得到，ticket生效时间为30分钟
     * 
     * @return array("data"=>array("pic_url"=>"http://shp.qpic.cn/wechat_shakearound_pic/0/1428377032e9dd2797018cad79186e03e8c5aec8dc/120"), "errcode"=> 0,"errmsg"=> "success.")
     */
    public static function getShakeInfo($ticket){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token='.$accessToken;
        $data = json_encode(array(
			'ticket'=> $ticket)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
}