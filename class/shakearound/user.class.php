<?php
namespace XYWeChat\ShakeAround;
/**
 * 摇一摇用户类
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月15日17:34:02
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
     * @return array("data"=>array("page_id "=>1421,"openid"=> "oVDmXjp7y8aG2AlBuRpMZTb1-cmA",'beacon_info'=>array("distance"=> 55.00620700469034,"major"=> 10001,"minor"=> 19007,"uuid"=> "FDA50693-A4E2-4FB1-AFCF-C6EB07647825")), "errcode"=> 0,"errmsg"=> "success.")
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