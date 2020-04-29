// 修改公告
function changeAnnouncement(announcementId) {
    let temp = announcementId;
    let id = temp.getAttribute('data-announcement-id');
    window.location.href = '/announcement/' + id + '.html';
}

// 删除公告
function delAnnouncement(announcementId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = announcementId;
    let id = temp.getAttribute('data-announcement-id');
    $.post("/announcement/delAnnouncement", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let announcement_tr = parseInt($("#announcement").children().length);
                let current_page = parseInt($("#announcementCurrent").data('current'));
                let pageCount = parseInt($("#announcementCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && announcement_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && announcement_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && announcement_tr == 1) {
                    window.location.href = '/admin/manage/announcement/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// 新增公告
function addAnnouncement() {
    window.location.href = '/announcement/add.html';
}