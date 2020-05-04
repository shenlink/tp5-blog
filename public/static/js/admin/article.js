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
                let tr = temp.parentNode.parentNode;
                tr.children[3].innerText = '拉黑';
                let html = createHtml('normalArticle', 'delArticle', 'article-id', id, '恢复');
                tr.lastElementChild.innerHTML = html;
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
                let tr = temp.parentNode.parentNode;
                tr.children[3].innerText = '正常';
                let html = createHtml('defriendArticle', 'delArticle', 'article-id', id, '拉黑');
                tr.lastElementChild.innerHTML = html;
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