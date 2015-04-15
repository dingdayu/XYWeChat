<?php
namespace XYWeChat\ShakeAround;
/**
 * 摇一摇页面素材管理
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月11日14:46:37
 * @Blog: http://blog.dingxiaoyu.com
 */
class Material{
    /**
     * 上传图片素材
     * 上传在摇一摇页面展示的图片素材，素材保存在微信侧服务器上。 
	 * 格式限定为：jpg,jpeg,png,gif，图片大小建议120px*120 px，限制不超过200 px *200 px，图片需为正方形。
     *
     * @param $filename，文件绝对路径
     * 
     * @return array("data"=>array("pic_url"=>"http://shp.qpic.cn/wechat_shakearound_pic/0/1428377032e9dd2797018cad79186e03e8c5aec8dc/120"), "errcode"=> 0,"errmsg"=> "success.")
     */
    public static function upload($filename){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/shakearound/material/add?access_token='.$accessToken;
        $data = array();
        $data['media'] = '@'.$filename;
        return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
}