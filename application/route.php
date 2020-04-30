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

// 引入路由类
use think\Route;


Route::pattern([
    'usernamme' => '\w+',
    'id' => '\d+',
]);

// announcement
Route::group('announcement', [
    'checkAddAnnouncement'   => ['@index/announcement/checkAddAnnouncement', ['method' => 'post']],
    'delAnnouncement' => ['@index/announcement/delAnnouncement', ['method' => 'post']],
    'checkChange' => ['@index/announcement/checkChange', ['method' => 'post']],
    ':id' => ['announcement/change', ['method' => 'get']]
]);

// article
Route::group('article', [
    'search/:condition'   => ['article/search', ['method' => 'get']],
    'checkWrite' => ['@index/article/checkWrite', ['method' => 'post']],
    'editArticle/:id' => ['@index/article/editArticle'],
    'checkEdit' => ['@index/article/checkEdit', ['method' => 'post']],
    'defriendArticle' => ['@index/article/defriendArticle', ['method' => 'post']],
    'normalArticle' => ['@index/article/normalArticle', ['method' => 'post']],
    'delArticle' => ['@index/article/delArticle', ['method' => 'post']],
    'getNewArticleCount' => ['@index/article/getNewArticleCount', ['method' => 'post']],
    ':id' => ['article/post', ['method' => 'get']],
]);

// 注意，当分组中有:category时，且路由是post方式时，在checkAddCategory后面不能加:id，下同
// category
Route::group('category', [
    'checkAddCategory'   => ['@index/category/checkAddCategory', ['method' => 'post']],
    'addCategory' => ['category/addCategory'],
    'getCategoryCount' => ['category/getCategoryCount'],
    ':category' => ['category/category'],
]);

// collect
Route::group('collect', [
    'checkCollect'   => ['collect/checkCollect', ['method' => 'post']],
    'delCollect' => ['collect/delCollect', ['method' => 'post']],
]);

// comment
Route::group('comment', [
    'addComment'   => ['comment/addComment', ['method' => 'post']],
    'delComment' => ['comment/delComment', ['method' => 'post']],
]);

// follow
Route::group('follow', [
    'getNewFansCount' => ['@index/follow/getNewFansCount', ['method' => 'post']],
    'checkFollow/:author'   => ['@index/follow/checkFollow', ['method' => 'post']],
    'delFollow/:author' => ['@index/follow/delFollow', ['method' => 'post']],
]);

// message
Route::group('message', [
    'checkMessage'   => ['@index/message/checkMessage', ['method' => 'post']],
    'delMessage/:id' => ['@index/message/checkMessage', ['method' => 'post']],
]);

// praise
Route::group('praise', [
    'checkPraise'   => ['@index/praise/checkPraise', ['method' => 'post']],
    'delPraise' => ['@index/praise/delPraise', ['method' => 'post']],
]);

// receive
Route::group('receive', [
    'delReceive/:id' => ['@index/receive/delReceive', ['method' => 'post']],
]);

// share
Route::group('share', [
    'checkShare'   => ['@index/share/checkShare', ['method' => 'post']],
    'delShare' => ['@index/share/delShare', ['method' => 'post']],
]);

// user
Route::group('user', [
    'search/:username'   => ['user/search'],
    'register' => ['user/register'],
    'checkUsername' => ['@index/user/checkUsername', ['method' => 'post']],
    'checkRegister' => ['@index/user/checkRegister', ['method' => 'post']],
    'login' => ['user/login'],
    'checkLogin' => ['user/checkLogin', ['method' => 'post']],
    'logout' => ['user/logout'],
    'change' => ['user/change'],
    'checkChange' => ['@index/user/checkChange', ['method' => 'post']],
    'manage' => ['@index/user/manage'],
    'unDeleteAll' => ['user/unDeleteAll', ['method' => 'post']],
    'unDelete' => ['user/unDelete', ['method' => 'post']],
    'defriendUser' => ['user/defriendUser', ['method' => 'post']],
    'normalUser' => ['user/normalUser', ['method' => 'post']],
    'delUser' => ['@index/user/delUser', ['method' => 'post']],
    'getNewUserCount' => ['@index/user/getNewUserCount', ['method' => 'post']],
    ':username' => ['user/user'],
]);

