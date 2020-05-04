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