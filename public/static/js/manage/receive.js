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