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


Route::rule('user/search/:username', 'user/search');

Route::rule('article/search/:condition', 'article/search');

Route::rule('announcement/checkAddAnnouncement/:content', '@index/announcement/checkAddAnnouncement','post');

Route::rule('announcement/checkChange/:content/:announcement_id', '@index/announcement/checkAddAnnouncement', 'post');

Route::rule('announcement/delAnnouncement/:announcement_id', '@index/announcement/delAnnouncement', 'post');

Route::rule('article/checkWrite/:title/:content/:category', '@index/article/checkWrite', 'post');

Route::rule('article/checkEdit/:article_id/:title/:content/:category', '@index/article/checkEdit', 'post');

Route::rule('article/defriendArticle/:article_id', '@index/article/defriendArticle', 'post');

Route::rule('article/normalArticle/:article_id', '@index/article/normalArticle', 'post');

Route::rule('article/delArticle/:article_id/:category', '@index/article/delArticle', 'post');

Route::rule('category/checkAddCategory/:category', '@index/category/checkAddCategory', 'post');

Route::rule('collect/checkCollect/:article_id/:author/:title', '@index/collect/checkCollect', 'post');

Route::rule('collect/delCollect/:article_id/:collect_id', '@index/collect/delCollect', 'post');

Route::rule('comment/addComment/:article_id/:author/:title/:content', '@index/comment/addComment', 'post');

Route::rule('comment/delComment/:comment_id/:article_id', '@index/comment/delComment', 'post');

Route::rule('follow/checkFollow/:author', '@index/@index/follow/checkFollow', 'post');

Route::rule('follow/delFollow/:author', '@index/@index/follow/delFollow', 'post');

Route::rule('message/checkMessage/:author/:content', '@index/@index/message/checkMessage', 'post');

Route::rule('message/delMessage/:message_id', '@index/message/checkMessage', 'post');

Route::rule('praise/checkPraise/:article_id/:author/:title', '@index/praise/checkPraise', 'post');

Route::rule('praise/delPraise/:article_id/:praise_id', '@index/praise/delPraise', 'post');

Route::rule('receive/checkPraise/:receive_id', '@index/receive/checkPraise', 'post');

Route::rule('receive/delReceive/:receive_id', '@index/receive/delReceive', 'post');

Route::rule('share/checkShare/:article_id/:author/:title', '@index/share/checkShare', 'post');

Route::rule('share/delShare/:share_id/:article_id', '@index/share/delShare', 'post');

Route::rule('user/checkUsername/:username', '@index/user/checkUsername', 'post');

Route::rule('user/checkRegister/:username/:password', '@index/user/checkRegister', 'post');

Route::rule('user/checkLogin/:username/:password', '@index/user/checkLogin', 'post');

Route::rule('user/checkChange/:introduction/:password', '@index/user/checkChange', 'post');

Route::rule('user/defriendUser/:user_id', '@index/user/defriendUser', 'post');

Route::rule('user/normalUser/:user_id', '@index/user/normalUser', 'post');

Route::rule('user/delUser/:user_id', '@index/user/normalUser', 'post');
