// 删除私信
function delReceive(receiveId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = receiveId;
    let id = temp.getAttribute('data-receive-id');
    $.post("/receive/delReceive", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let receive_tr = parseInt($("#receive").children().length);
                let current_page = parseInt($("#receiveCurrent").data('current'));
                let pageCount = parseInt($("#receiveCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && receive_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && receive_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && receive_tr == 1) {
                    window.location.href = '/user/manage/receive/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}