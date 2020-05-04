<?php

namespace app\index\controller;

use think\Request;
use app\index\controller\Base;
use app\index\model\Article;
use app\index\model\Announcement as AnnouncementModel;

class Announcement extends Base
{
    // 展示添加公告页面
    public function add()
    {
        // 标志位，会显示添加公告的内容
        $addAnnouncement = 'addAnnouncement';
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('addAnnouncement', $addAnnouncement);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }

    // 确认添加公告
    public function checkAddAnnouncement(Request $request)
    {
        if (request()->isAjax()) {
            $status = 0;
            $message = '添加失败';
            $data = $request->post();
            // 添加公告进数据库
            $result = AnnouncementModel::create($data);
            if ($result == true) {
                $status = 1;
                $message = '添加成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 展示修改公告页面
    public function change(Request $request)
    {
        // 标志位，会显示修改公告的内容
        $changeAnnouncement = 'changeAnnouncement';
        // 获取当前的公告id
        $id = $request->param('id');
        // 获取当前的公告内容
        $announcements = AnnouncementModel::get(['id', $id]);
        // 获取10篇推荐文章
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        // 模板赋值
        $this->view->assign('announcements', $announcements);
        $this->view->assign('changeAnnouncement', $changeAnnouncement);
        $this->view->assign('recommends', $recommends);
        $this->view->assign('title', '修改公告');
        return $this->view->fetch('public/change');
    }

    // 确认修改公告
    public function checkChange(Request $request)
    {
        if (request()->isAjax()) {
            $status = 0;
            $message = '修改失败';
            $data = $request->post();
            $condition = ['id' => $data['id']];
            // 更新该条公告
            $result = AnnouncementModel::update($data, $condition);
            if ($result == true) {
                $status = 1;
                $message = '修改成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }

    // 删除公告
    public function delAnnouncement(Request $request)
    {
        if (request()->isAjax()) {
            $status = 0;
            $message = '删除失败';
            $id = $request->post('id');
            // 删除该条公告
            $result = AnnouncementModel::destroy($id);
            if ($result == true) {
                $status = 1;
                $message = '删除成功';
            }
            return ['status' => $status, 'message' => $message];
        } else {
            return $this->error('非法访问');
        }
    }
}
