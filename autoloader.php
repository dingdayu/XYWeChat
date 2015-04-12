<?php
namespace XYWeChat;
/**
 *
 * 自动载入函数
 * @Author: dingdayu (85897970@qq.com)
 * @Time: 2015年4月11日14:46:37
 * @Blog: http://blog.dingxiaoyu.com
 */
 
class Autoloader{
    const NAMESPACE_PREFIX = 'XYWeChat\\';
    /**
     * 向PHP注册在自动载入函数
     */
    public static function register(){
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * 根据类名载入所在文件
     */
    public static function autoload($className){
        $namespacePrefixStrlen = strlen(self::NAMESPACE_PREFIX);
        if(strncmp(self::NAMESPACE_PREFIX, $className, $namespacePrefixStrlen) === 0){
            $className = strtolower($className);
            $filePath = str_replace('\\', DIRECTORY_SEPARATOR, substr($className, $namespacePrefixStrlen));
			$filePath = __DIR__ . (empty($filePath) ? '' : DIRECTORY_SEPARATOR) .'class'. DIRECTORY_SEPARATOR . $filePath . '.class.php';
            //$filePath = realpath(__DIR__ . (empty($filePath) ? '' : DIRECTORY_SEPARATOR) .'class'. $filePath . '.class.php');

			if(file_exists($filePath)){
                require_once $filePath;
            }else{
                echo $filePath;
            }
        }
    }
}