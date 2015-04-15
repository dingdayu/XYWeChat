<?php
namespace XYWeChat\ShakeAround;
/**
 * 数据统计类
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月15日17:51:18
 * @Blog: http://blog.dingxiaoyu.com
 */
class Statistics{
	
    /**
     * 以设备为维度的数据统计接口
     * 查询单个设备进行摇周边操作的人数、次数，点击摇周边消息的人数、次数；查询的最长时间跨度为30天。
     *
     * @param $device_id 指定页面的设备ID
	 * @param $begin_date 起始日期时间戳，最长时间跨度为30天
	 * @param $end_date 结束日期时间戳，最长时间跨度为30天
     * @param $info array('UUID'=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825','minor '=>'1002','major'=>'1223')
     * 
     * ftime	当天0点对应的时间戳
     * click_pv	点击摇周边消息的次数
     * click_uv	点击摇周边消息的人数
     * shake_pv	摇周边的次数
     * shake_uv	摇周边的人数
     * 
     * @return array("data"=>array(array( "click_pv"=> 0,"click_uv"=> 0,"ftime"=> 1425139200,"shake_pv"=> 0, "shake_uv"=> 0)), "errcode"=> 0,"errmsg"=> "success.")
     */
    public static function device($device_id , $begin_date , $end_date, $info = '' ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/statistics/device?access_token='.$accessToken;
		if(empty($device_id) and $info == ''){
			return false;
		}
        $data = json_encode(array(
			'device_identifier'=>array(
				'device_id'=>$device_id,
				'UUID'=>$info['UUID'],
				'minor '=>$info['minor'],
				'major'=>$info['major']
				), 
			'begin_date'=> $begin_date,
			'end_date'=> $end_date,
			)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
	
	/**
     * 以页面为维度的数据统计接口
     * 查询单个页面通过摇周边摇出来的人数、次数，点击摇周边页面的人数、次数；查询的最长时间跨度为30天。
     *
     * @param $page_id 指定页面的设备ID
	 * @param $begin_date 起始日期时间戳，最长时间跨度为30天
	 * @param $end_date 结束日期时间戳，最长时间跨度为30天
     * 
     * ftime	当天0点对应的时间戳
     * click_pv	点击摇周边页面的次数
     * click_uv	点击摇周边页面的人数
     * shake_pv	摇周边页面的次数
     * shake_uv	摇周边页面的人数
     * 
     * @return array("data"=>array(array( "click_pv"=> 0,"click_uv"=> 0,"ftime"=> 1425139200,"shake_pv"=> 0, "shake_uv"=> 0)), "errcode"=> 0,"errmsg"=> "success.")
     */
    public static function page($page_id , $begin_date , $end_date){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/statistics/page?access_token='.$accessToken;
		if(empty($device_id) and $info == ''){
			return false;
		}
        $data = json_encode(array(
			'page_id'=>$page_id
			'begin_date'=> $begin_date,
			'end_date'=> $end_date,
			)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
}