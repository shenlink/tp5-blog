// 拉黑文章
function defriendArticle(articleId) {
    let temp = articleId;
    let id = temp.getAttribute('data-article-id');
    $.post("/article/defriendArticle", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                var table = $('#dataTable').DataTable();
                var d = table.row(this).data();
                d.counter++;
                table.row(this).data(d).draw();
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// 恢复文章到正常状态
function normalArticle(articleId) {
    let temp = articleId;
    let id = temp.getAttribute('data-article-id');
    $.post("/article/normalArticle", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                var table = $('#dataTable').DataTable();
                var d = table.row(this).data();
                d.counter++;
                table.row(this).data(d).draw();
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// 删除文章
function delArticle(articleId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = articleId;
    let id = temp.getAttribute('data-article-id');
    let category = temp.getAttribute('data-category');
    let author = temp.getAttribute('data-author');
    $.post("/article/delArticle", {
        id: id,
        category: category,
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