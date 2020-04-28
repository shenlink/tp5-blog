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

Route::rule('announcement/checkChange/:content/:announcement_id', '@index/announcement/checkAddAnnouncement', 'post', ['announcement_id'=>'\d+']);

Route::rule('announcement/delAnnouncement/:announcement_id', '@index/announcement/delAnnouncement', 'post', ['announcement_id'=>'\d+']);

Route::rule('article/checkWrite/:title/:content/:category', '@index/article/checkWrite', 'post');

Route::rule('article/checkEdit/:article_id/:title/:content/:category', '@index/article/checkEdit', 'post', ['article_id' => '\d+']);

Route::rule('article/defriendArticle/:article_id', '@index/article/defriendArticle', 'post', ['article_id' => '\d+']);

Route::rule('article/normalArticle/:article_id', '@index/article/normalArticle', 'post', ['article_id' => '\d+']);

Route::rule('article/delArticle/:article_id/:category', '@index/article/delArticle', 'post', ['article_id' => '\d+']);

Route::rule('category/checkAddCategory/:category', '@index/category/checkAddCategory', 'post');

Route::rule('collect/checkCollect/:article_id/:author/:title', '@index/collect/checkCollect', 'post', ['article_id' => '\d+']);

Route::rule('collect/delCollect/:article_id/:collect_id', '@index/collect/delCollect', 'post', ['article_id' => '\d+']);

Route::rule('comment/addComment/:article_id/:author/:title/:content', '@index/comment/addComment', 'post', ['article_id' => '\d+']);

Route::rule('comment/delComment/:comment_id/:article_id', '@index/comment/delComment', 'post', ['article_id' => '\d+', 'comment_id' => '\d+']);

Route::rule('follow/checkFollow/:author', '@index/@index/follow/checkFollow', 'post');

Route::rule('follow/delFollow/:author', '@index/@index/follow/delFollow', 'post');

Route::rule('message/checkMessage/:author/:content', '@index/@index/message/checkMessage', 'post', ['message_id' => '\d+']);

Route::rule('message/delMessage/:message_id', '@index/message/checkMessage', 'post', ['message_id' => '\d+']);

Route::rule('praise/checkPraise/:article_id/:author/:title', '@index/praise/checkPraise', 'post', ['article_id' => '\d+']);

Route::rule('praise/delPraise/:article_id/:praise_id', '@index/praise/delPraise', 'post');

Route::rule('receive/checkPraise/:receive_id', '@index/receive/checkPraise', 'post');

Route::rule('receive/delReceive/:receive_id', '@index/receive/delReceive', 'post', ['receive_id' => '\d+']);

Route::rule('share/checkShare/:article_id/:author/:title', '@index/share/checkShare', 'post', ['article_id' => '\d+']);

Route::rule('share/delShare/:share_id/:article_id', '@index/share/delShare', 'post', ['share_id' => '\d+', 'article_id' => '\d+']);

Route::rule('user/checkUsername/:username', '@index/user/checkUsername', 'post');

Route::rule('user/checkRegister/:username/:password', '@index/user/checkRegister', 'post');

Route::rule('user/checkLogin/:username/:password', '@index/user/checkLogin', 'post');

Route::rule('user/checkChange/:introduction/:password', '@index/user/checkChange', 'post');

Route::rule('user/defriendUser/:user_id', '@index/user/defriendUser', 'post');

Route::rule('user/normalUser/:user_id', '@index/user/normalUser', 'post', ['user_id' => '\d+']);

Route::rule('user/delUser/:user_id', '@index/user/normalUser', 'post', ['user_id' => '\d+']);

Route::rule('article/:article_id', 'article/post','get',['article_id'=>'\d+']);

Route::rule('category/:category', 'category/category');

Route::rule('announcement/:id', 'announcement/change', 'get', ['id' => '\d+']);

Route::rule('user/:name', 'user/user');
