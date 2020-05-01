// 编辑文章
function editArticle(articleId) {
    let temp = articleId;
    let id = temp.getAttribute('data-article-id');
    window.location.href = '/article/editArticle/' + id + '.html';
}

// 删除文章
function delArticle(articleId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = articleId;
    let id = temp.getAttribute('data-article-id');
    let category = temp.getAttribute('data-category');
    $.post("/article/delArticle", {
        id: id,
        category: category
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let article_tr = parseInt($("#article").children().length);
                let current_page = parseInt($("#articleCurrent").data('current'));
                let pageCount = parseInt($("#articleCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && article_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && article_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && article_tr == 1) {
                    window.location.href = '/user/manage/article/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}