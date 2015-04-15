<?php
namespace XYWeChat\ShakeAround;
/**
 * 摇一摇周边/设备管理类
 * Url：http://mp.weixin.qq.com/wiki/15/b9e012f917e3484b7ed02771156411f3.html
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月15日14:43:59
 * @Blog: http://blog.dingxiaoyu.com
 */
class Device{

	/**
     * 申请设备ID
	 * 申请配置设备所需的UUID、Major、Minor。若激活率小于50%，不能新增设备。单次新增设备超过500个，需走人工审核流程。审核通过后，可用返回的批次ID用“查询设备列表”接口拉取本次申请的设备ID。 
	 *
	 * @param $quantity 申请的设备ID的数量
     * @param $apply_reason 申请理由
	 * @param $comment 备注
     * @param $poi_id 设备关联的门店ID 
	 *
     * @return array('data'=>array('apply_id'=>123,'device_identifiers'=>array("device_id" => 10100,"uuid"=>"FDA50693-A4E2-4FB1-AFCF-C6EB07647825","major"=>10001,"minor"=> 10002)),'errcode'=>0,'errmsg'=>'success');
     */
    public static function applyDevice($quantity ,$apply_reason ,$comment ,$poi_id ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token='.$accessToken;
        $data = json_encode(array('quantity'=>$quantity, 'apply_reason'=>$apply_reason, 'comment'=>$comment, 'poi_id'=>$poi_id));
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 编辑设备信息
	 * 编辑设备的备注信息。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一。 
	 *
     * @param $device_id 设备ID 
	 * @param $comment 备注
	 * @param $info array('UUID'=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825','minor '=>'1002','major'=>'1223')
	 *
     * @return array('data'=>array('data'=>array(),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function updateDevice($device_id ,$comment ,$info = '' ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token='.$accessToken;
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
			'comment'=> $comment)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 配置设备与门店的关联关系
	 * 修改设备关联的门店ID、设备的备注信息。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一。
	 *
	 * @param $poi_id 待关联的门店ID
     * @param $device_id 设备ID 
	 * @param $info array('UUID'=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825','minor '=>'1002','major'=>'1223')
	 *
     * @return array('data'=>array('data'=>array(),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function bindLocation($poi_id ,$device_id ,$info = '' ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/device/bindlocation?access_token='.$accessToken;
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
			'poi_id'=> $poi_id)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 查询设备列表  基础函数
	 * 查询已有的设备ID、UUID、Major、Minor、激活状态、备注信息、关联门店、关联页面等信息。可指定设备ID或完整的UUID、Major、Minor查询，也可批量拉取设备信息列表。
	 *
	 * @param $info array('UUID'=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825','minor '=>'1002','major'=>'1223') or array('begin'=>0,'count'=>3) or array( "apply_id"=>1231,"begin"=> 0,"count"=> 3)
	 *
     * @return array('data'=>array('devices'=>array(array("comment"=> "","device_id"=> 10098,"major"=> 10001, "minor"=> 12103,"page_ids"=> "15368","status"=> 1,"poi_id"=> 0,"uuid"=> "FDA50693-A4E2-4FB1-AFCF-C6EB07647825")),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function _searchDevice($data ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/device/bindlocation?access_token='.$accessToken;
		$data = json_encode($data);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 获取所有设备列表
	 * 查询已有的设备ID、UUID、Major、Minor、激活状态、备注信息、关联门店、关联页面等信息。可指定设备ID或完整的UUID、Major、Minor查询，也可批量拉取设备信息列表。
	 *
	 * @param $begin 开始记录
     * @param $count 返回的数量 
     * @param $apply_id 设备批次 
	 *
     * @return array('data'=>array('devices'=>array(array("comment"=> "","device_id"=> 10098,"major"=> 10001, "minor"=> 12103,"page_ids"=> "15368","status"=> 1,"poi_id"=> 0,"uuid"=> "FDA50693-A4E2-4FB1-AFCF-C6EB07647825")),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function getDeviceList($begin = 0 ,$count = 3 ,$apply_id = ''){
		
		if($apply_id == ''){
			$data = array(
				'begin'=>$begin,
				'count'=>$count,
			);
		}else{
			$data = array(
				'begin'=>$begin,
				'count'=>$count,
				'apply_id'=>$apply_id,
			);
		}
        return self::_searchDevice( $data);
    }
	
	/**
     * 查询指定设备
	 * 查询已有的设备ID、UUID、Major、Minor、激活状态、备注信息、关联门店、关联页面等信息。可指定设备ID或完整的UUID、Major、Minor查询，也可批量拉取设备信息列表。
	 *
     * @param $device_id 设备ID 
	 * @param $info array('UUID'=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825','minor '=>'1002','major'=>'1223')
	 *
     * @return array('data'=>array('devices'=>array(array("comment"=> "","device_id"=> 10098,"major"=> 10001, "minor"=> 12103,"page_ids"=> "15368","status"=> 1,"poi_id"=> 0,"uuid"=> "FDA50693-A4E2-4FB1-AFCF-C6EB07647825")),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function getDeviceInfo($device_id ,$info = ''){
		
		if(empty($device_id) and $info == ''){
			return false;
		}
        $data = array(
			'device_identifier'=>array(
				'device_id'=>$device_id,
				'UUID'=>$info['UUID'],
				'minor '=>$info['minor'],
				'major'=>$info['major']
			)
		);
        return self::_searchDevice( $data);
    }
	
	
}