// 发私信
function addMessage() {
    let author = $('#addMessage').data('author');
    window.location.href = '/message/sendMessage/username/' + author + '.html';
}

// 删除私信
function delMesssage(messageId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = messageId;
    let id = temp.getAttribute('data-message-id');
    $.post("/message/delMessage", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let message_tr = parseInt($("#message").children().length);
                let current_page = parseInt($("#messageCurrent").data('current'));
                let pageCount = parseInt($("#messageCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && message_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && message_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && message_tr == 1) {
                    window.location.href = '/admin/manage/message/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}