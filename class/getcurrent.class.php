<?php
namespace XYWeChat;
/**
 * 常用获取类
 *
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月11日14:46:37
 * @Blog: http://blog.dingxiaoyu.com
 */
class GetCurrent{

	/**
     * 获取自动回复规则
	 * 本接口与自定义菜单查询接口的不同之处在于，本接口无论公众号的接口是如何设置的，都能查询到接口，而自定义菜单查询接口则仅能查询到使用API设置的菜单配置。
	 * Url：http://mp.weixin.qq.com/wiki/7/7b5789bb1262fb866d01b4b40b0efecb.html
     * @return bool|mixed
     *
     * 返回：
     */
    public static function getAutoreply(){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token='.$accessToken;
        return Curl::callWebServer($url, '', 'GET');
    }
}