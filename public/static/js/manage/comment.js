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
                let comment_tr = parseInt($("#comment").children().length);
                let current_page = parseInt($("#commentCurrent").data('current'));
                let pageCount = parseInt($("#commentCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && comment_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && comment_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && comment_tr == 0) {
                    window.location.href = '/user/manage/comment/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}