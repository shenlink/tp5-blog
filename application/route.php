<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 引入系统类
use think\Route;


Route::pattern([
    'usernamme' => '\w+',
    'id' => '\d+',
]);


Route::rule('user/search/:username', 'user/search');

Route::rule('article/search/:condition', 'article/search');

Route::rule('announcement/checkAddAnnouncement/:content', '@index/announcement/checkAddAnnouncement', 'post');

Route::rule('announcement/checkChange', '@index/announcement/checkAddAnnouncement', 'post');

Route::rule('announcement/delAnnouncement/:id', '@index/announcement/delAnnouncement', 'post');

Route::rule('article/checkWrite', '@index/article/checkWrite', 'post');

Route::rule('article/checkEdit', '@index/article/checkEdit', 'post');

Route::rule('article/defriendArticle/:id', '@index/article/defriendArticle', 'post');

Route::rule('article/normalArticle/:id', '@index/article/normalArticle', 'post');

Route::rule('article/delArticle', '@index/article/delArticle', 'post');

Route::rule('category/checkAddCategory/:category', '@index/category/checkAddCategory', 'post');

Route::rule('collect/checkCollect', '@index/collect/checkCollect', 'post');

Route::rule('collect/delCollect', '@index/collect/delCollect', 'post');

Route::rule('comment/addComment', '@index/comment/addComment', 'post');

Route::rule('comment/delComment', '@index/comment/delComment', 'post');

Route::rule('follow/checkFollow/:author', '@index/@index/follow/checkFollow', 'post');

Route::rule('follow/delFollow/:author', '@index/@index/follow/delFollow', 'post');

Route::rule('message/checkMessage', '@index/@index/message/checkMessage', 'post');

Route::rule('message/delMessage/:id', '@index/message/checkMessage', 'post');

Route::rule('praise/checkPraise', '@index/praise/checkPraise', 'post');

Route::rule('praise/delPraise', '@index/praise/delPraise', 'post');

Route::rule('receive/checkPraise/:id', '@index/receive/checkPraise', 'post');

Route::rule('receive/delReceive/:id', '@index/receive/delReceive', 'post');

Route::rule('share/checkShare', '@index/share/checkShare', 'post');

Route::rule('share/delShare', '@index/share/delShare', 'post', ['id' => '\d+', 'id' => '\d+']);

Route::rule('user/checkUsername/:username', '@index/user/checkUsername', 'post');

Route::rule('user/checkRegister', '@index/user/checkRegister', 'post');

Route::rule('user/checkChange', '@index/user/checkChange', 'post');

Route::rule('user/defriendUser/:id', '@index/user/defriendUser', 'post');

Route::rule('user/normalUser/:id', '@index/user/normalUser', 'post');

Route::rule('user/delUser/:id', '@index/user/normalUser', 'post');

Route::rule('article/:id', 'article/post', 'get');

Route::rule('category/:category', 'category/category');

Route::rule('announcement/:id', 'announcement/change', 'get');

Route::rule('user/:username', 'user/user');

Route::rule('user/login', 'user/login');

Route::rule('user/checkLogin', 'user/checkLogin','post');

Route::rule('user/register', 'user/register');

Route::rule('user/checkRegister', 'user/checkRegister', 'post');

Route::rule('user/checkLogin', '@index/user/checkLogin', 'post');

Route::rule('user/logout', 'user/logout');

Route::rule('user/change', 'user/change');

Route::rule('user/checkChange', '@index/user/checkChange', 'post');

Route::rule('user/manage', 'user/manage');

Route::rule('user/defriendUser', 'user/defriendUser', 'post');

Route::rule('user/normalUser', 'user/normalUser', 'post');

Route::rule('user/delUser', 'user/delUser', 'post');

Route::rule('category/addCategory', 'category/addCategory', 'post');
