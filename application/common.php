<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 截取字符串，适用utf-8编码
function truncate($text, $length)
{
    if (mb_strlen($text, 'utf-8') > $length) {
        return mb_substr($text, 0, $length, 'utf-8') . '...';
    }
    return $text;
}
