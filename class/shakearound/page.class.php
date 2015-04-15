<?php
namespace XYWeChat\ShakeAround;
/**
 * 摇一摇周边/页面管理类
 * Url：http://mp.weixin.qq.com/wiki/5/6626199ea8757c752046d8e46cf13251.html
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月15日14:43:51
 * @Blog: http://blog.dingxiaoyu.com
 */
class Page{

	/**
     * 新增页面
	 * 新增摇一摇出来的页面信息，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。其中，图片必须为用素材管理接口上传至微信侧服务器后返回的链接。
	 *
	 * @param $title 在摇一摇页面展示的主标题，不超过6个字
     * @param $description 在摇一摇页面展示的副标题，不超过7个字
     * @param $description 跳转页面
	 * @param $icon_url 在摇一摇页面展示的图片。图片需先上传至微信侧服务器，用“素材管理-上传图片素材”接口上传图片，返回的图片URL再配置在此处
     * @param $comment 页面的备注信息，不超过15个字
	 *
     * @return array('data'=>array('page_id'=>123),'errcode'=>0,'errmsg'=>'success');
     */
    public static function addPage($title ,$description , $page_url ,$icon_url ,$comment ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/page/add?access_token='.$accessToken;
        $data = json_encode(array(
				'title'=>$title,
				'description'=>$description,
				'icon_url'=>$icon_url,
				'page_url'=>$page_url,
				'comment'=>$comment
			)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 编辑页面信息
	 * 编辑摇一摇出来的页面信息，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。
	 *
	 * @param $page_id 页面id
	 * @param $title 在摇一摇页面展示的主标题，不超过6个字
     * @param $description 在摇一摇页面展示的副标题，不超过7个字
     * @param $description 跳转页面
	 * @param $icon_url 在摇一摇页面展示的图片。图片需先上传至微信侧服务器，用“素材管理-上传图片素材”接口上传图片，返回的图片URL再配置在此处
     * @param $comment 页面的备注信息，不超过15个字
	 *
     * @return array('data'=>array('page_id'=>123),'errcode'=>0,'errmsg'=>'success');
     */
    public static function updatePage($page_id ,$title ,$description , $page_url ,$icon_url ,$comment ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/page/update?access_token='.$accessToken;
        $data = json_encode(array(
				'page_id'=>$page_id,
				'title'=>$title,
				'description'=>$description,
				'icon_url'=>$icon_url,
				'page_url'=>$page_url,
				'comment'=>$comment
			)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 查询页面列表
	 * 查询已有的页面，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。提供两种查询方式，可指定页面ID查询，也可批量拉取页面列表。
	 *
	 * @param $info array( "page_ids"=>array(12345, 23456, 34567)) or array('begin'=>0,'count'=>3)
	 *
     * @return array('data'=>array('pages'=>array(array("comment"=> "","description"=> "test","icon_url"=> "https://www.baidu.com/img/bd_logo1", "page_id"=> 12103,"page_url"=> "http://xw.qq.com/testapi1","title"=> "测试1"),"total_count"=> 2),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function _searchPage($data ){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/page/search?access_token='.$accessToken;
		$data = json_encode($data);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
	/**
     * 查询页面列表
	 * 查询已有的页面，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。提供两种查询方式，可指定页面ID查询，也可批量拉取页面列表。
	 *
	 * @param $begin 开始记录
     * @param $count 返回的数量 
	 *
     * @return array('data'=>array('pages'=>array(array("comment"=> "","description"=> "test","icon_url"=> "https://www.baidu.com/img/bd_logo1", "page_id"=> 12103,"page_url"=> "http://xw.qq.com/testapi1","title"=> "测试1"),"total_count"=> 2),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function getPageList($begin = 0 ,$count = 3){

			$data = array(
					'begin'=>$begin,
					'count'=>$count,
				);

        return self::_searchDevice( $data);
    }
	
	/**
     * 查询指定页面
	 * 查询已有的设备ID、UUID、Major、Minor、激活状态、备注信息、关联门店、关联页面等信息。可指定设备ID或完整的UUID、Major、Minor查询，也可批量拉取设备信息列表。
	 *
	 * @param $info array(12345, 23456, 34567) 页面id数组
	 *
     * @return array('data'=>array('devices'=>array(array("comment"=> "","device_id"=> 10098,"major"=> 10001, "minor"=> 12103,"page_ids"=> "15368","status"=> 1,"poi_id"=> 0,"uuid"=> "FDA50693-A4E2-4FB1-AFCF-C6EB07647825")),"errcode"=> 0,'errmsg'=>'success');
     */
    public static function getPageInfo($page_ids = ''){
		if($page_ids = ''){
			return false;
		}

        $data = array(
			'page_ids'=>array(
				$page_ids
			)
		);
        return self::_searchDevice( $data);
    }
	
	/**
     * 删除页面
	 * 删除已有的页面，包括在摇一摇页面出现的主标题、副标题、图片和点击进去的超链接。只有页面与设备没有关联关系时，才可被删除。
	 *
	 * @param $info array(12345, 23456, 34567) 页面id数组
	 *
     * @return array('data'=>array(),'errcode'=>0,'errmsg'=>'success');
     */
    public static function deletePage($page_ids = '' ){
		if($page_ids = ''){
			return false;
		}
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/page/delete?access_token='.$accessToken;
        $data = json_encode(			
			'page_ids'=>array(
				$page_ids
			)
		);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
	
		
	/**
     * 配置设备与页面的关联关系
	 * 配置设备与页面的关联关系。支持建立或解除关联关系，也支持新增页面或覆盖页面等操作。配置完成后，在此设备的信号范围内，即可摇出关联的页面信息。若设备配置多个页面，则随机出现页面信息。
	 *
     * @param $device_id 设备ID 
	 * @param $info array('1223','1223') 页面id数组
     * @param $bind 关联操作标志位， 0为解除关联关系，1为建立关联关系
     * @param $append 新增操作标志位， 0为覆盖，1为新增
	 * @param $info array('UUID'=>'FDA50693-A4E2-4FB1-AFCF-C6EB07647825','minor '=>'1002','major'=>'1223')
	 *
     * @return array('data'=>array(),'errcode'=>0,'errmsg'=>'success');
     */
    public static function bindPage($device_id ,$page_ids = '', $bind = 1 ,$append = 0 ,$$info = ''){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/device/bindpage?access_token='.$accessToken;
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
			'page_ids'=> $page_ids,
			'bind'=> $bind,
			'append'=> $append,
		);
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
}