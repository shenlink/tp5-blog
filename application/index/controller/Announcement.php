<?php

namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use app\index\model\Article;
use app\index\model\Announcement as AnnouncementModel;

class Announcement extends Base
{
    // 添加公告
    public function add()
    {
        $addAnnouncement = 'addAnnouncement';
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('addAnnouncement', $addAnnouncement);
        $this->view->assign('recommends', $recommends);
        return $this->view->fetch('public/add');
    }

    // 确认添加
    public function checkAddAnnouncement(Request $request)
    {
        $status = 0;
        $message = '添加失败';
        $data = $request->post();
        $result = AnnouncementModel::create($data);
        if ($result == true) {
            $status = 1;
            $message = '添加成功';
        }
        return ['status' => $status, 'message' => $message];
    }

    // 修改公告
    public function change(Request $request)
    {
        $changeAnnouncement = 'changeAnnouncement';
        $id = $request->param('id');
        $announcements = AnnouncementModel::get(['id', $id]);
        $recommends = Article::where('status', 1)->field(['id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('announcements', $announcements);
        $this->view->assign('changeAnnouncement', $changeAnnouncement);
        $this->view->assign('recommends', $recommends);
        $this->view->assign('title', '修改公告');
        return $this->view->fetch('public/change');
    }

    // 确认修改
    public function checkChange(Request $request)
    {
        $status = 0;
        $message = '修改失败';
        $data = $request->post();
        $condition = ['id' => $data['id']];
        $result = AnnouncementModel::update($data, $condition);
        if ($result == true) {
            $status = 1;
            $message = '修改成功';
        }
        return ['status' => $status, 'message' => $message];
    }

    // 删除公告
    public function delAnnouncement(Request $request)
    {
        $status = 0;
        $message = '删除失败';
        $id = $request->post('id');
        $result = AnnouncementModel::destroy($id);
        if ($result == true) {
            $status = 1;
            $message = '删除成功';
        }
        return ['status' => $status, 'message' => $message];
    }
}
