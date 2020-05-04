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
                var table = $('#dataTable').DataTable();
                table.row($(this).parents('tr')).remove().draw();
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