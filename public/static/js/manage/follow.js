// 取消关注
function delFollow(followName) {
    let temp = followName;
    let author = temp.getAttribute('data-author');
    $.post("/follow/delFollow", {
        author: author,
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