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
                    window.location.href = '/admin/manage/aricle/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}