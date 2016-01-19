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
// | DATE: 2015/12/19 16:45
// +----------------------------------------------------------------------
// | FILE: File.class.php
// +----------------------------------------------------------------------


namespace XYser\Wechat\lib;

use finfo;

class File
{
    /**
     * ��Ӧ�ļ�����
     *
     * @var array
     */
    protected static $extensionMap = array(
        'application/msword'                                                        => '.doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => '.docx',
        'application/rtf'                                                           => '.rtf',
        'application/vnd.ms-excel'                                                  => '.xls',
        'application/x-excel'                                                       => '.xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => '.xlsx',
        'application/vnd.ms-powerpoint'                                             => '.ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => '.pptx',
        'application/vnd.ms-powerpoint'                                             => '.pps',
        'application/vnd.openxmlformats-officedocument.presentationml.slideshow'    => '.ppsx',
        'application/pdf'                                                           => '.pdf',
        'application/x-shockwave-flash'                                             => '.swf',
        'application/x-msdownload'                                                  => '.dll',
        'application/octet-stream'                                                  => '.exe',
        'application/octet-stream'                                                  => '.msi',
        'application/octet-stream'                                                  => '.chm',
        'application/octet-stream'                                                  => '.cab',
        'application/octet-stream'                                                  => '.ocx',
        'application/octet-stream'                                                  => '.rar',
        'application/x-tar'                                                         => '.tar',
        'application/x-compressed'                                                  => '.tgz',
        'application/x-zip-compressed'                                              => '.zip',
        'application/x-compress'                                                    => '.z',
        'audio/wav'                                                                 => '.wav',
        'audio/x-ms-wma'                                                            => '.wma',
        'video/x-ms-wmv'                                                            => '.wmv',
        'video/mp4'                                                                 => '.mp4',
        'audio/mpeg'                                                                => '.mp3',
        'audio/amr'                                                                 => '.amr',
        'application/vnd.rn-realmedia'                                              => '.rm',
        'audio/mid'                                                                 => '.mid',
        'image/bmp'                                                                 => '.bmp',
        'image/gif'                                                                 => '.gif',
        'image/png'                                                                 => '.png',
        'image/tiff'                                                                => '.tiff',
        'image/jpeg'                                                                => '.jpg',
        'text/plain'                                                                => '.txt',
        'text/xml'                                                                  => '.xml',
        'text/html'                                                                 => '.html',
        'text/css'                                                                  => '.css',
        'text/javascript'                                                           => '.js',
        'message/rfc822'                                                            => '.mhtml',
    );

    /**
     * �����ļ�����ȡ
     *
     * @param  string $content �ļ���
     *
     * @return string �ļ�����
     */
    public static function getStreamExt($content)
    {
        $finfo = new finfo(FILEINFO_MIME);

        $mime = $finfo->buffer($content);

        return isset(self::$extensionMap[$mime]) ? self::$extensionMap[$mime] : '';
    }
}