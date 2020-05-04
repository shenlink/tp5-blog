// 删除评论
function delComment(commentId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = commentId;
    let article_id = temp.getAttribute('data-article-id');
    let id = temp.getAttribute('data-comment-id');
    $.post("/comment/delComment", {
        article_id: article_id,
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
    });
}