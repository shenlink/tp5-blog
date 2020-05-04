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
    console
    $.post("/article/delArticle", {
        id: id,
        category: category
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