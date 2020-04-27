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
        $recommends = Article::where('status', 1)->field(['article_id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
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
        $announcement_id = $request->param('id');
        $announcements = AnnouncementModel::get(['announcement_id', $announcement_id]);
        $recommends = Article::where('status', 1)->field(['article_id', 'title'])->limit(10)->order('comment_count', 'desc')->select();
        $this->view->assign('announcements', $announcements);
        $this->view->assign('changeAnnouncement', $changeAnnouncement);
        $this->view->assign('recommends', $recommends);
        $this->view->assign('title', '修改公告');
        return $this->view->fetch('public/change');
    }
}
