// 左边的导航切换
let lis = $('.list-group-item');
let items = $('.manage-item');
// for循环在页面加载完成之后就已经执行完了，这时候lis的index索引已经赋值完成,然后执行lis[i].click事件注册，待点击之后就触发
for (let i = 0; i < lis.length; i++) {
    lis[i].setAttribute('data-index', i);
    lis[i].onclick = function () {
        let index = this.getAttribute('data-index');
        for (let i = 0; i < items.length; i++) {
            items[i].style.display = 'none';
        }
        items[index].style.display = 'block';
    }
}

// 编辑文章
function editArticle(articleId) {
    let temp = articleId;
    let article_id = temp.getAttribute('data-article-id');
    window.location.href = '/article/editArticle/article/' + article_id + '.html';
}

// 删除文章
function delArticle(articleId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = articleId;
    let article_id = temp.getAttribute('data-article-id');
    let category = temp.getAttribute('data-category');
    $.post("/article/delArticle", {
        article_id: article_id,
        category: category
    }, function (data) {
        if (data === '1') {
            layer.msg('删除成功', {
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
            layer.msg('删除失败', {
                time: 1000
            });
        }
    });
}

// 删除评论
function delComment(commentId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = commentId;
    let article_id = temp.getAttribute('data-article-id');
    let comment_id = temp.getAttribute('data-comment-id');
    $.post("/comment/delComment", {
        article_id: article_id,
        comment_id: comment_id
    }, function (data) {
        if (data === '1') {
            layer.msg('删除成功', {
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
            layer.msg('删除失败', {
                time: 1000
            });
        }
    });
}

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
                let follow_tr = parseInt($("#follow").children().length);
                let current_page = parseInt($("#followCurrent").data('current'));
                let pageCount = parseInt($("#followCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && follow_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && follow_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && follow_tr == 1) {
                    window.location.href = '/user/manage/follow/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// 删除私信
function delReceive(receiveId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = receiveId;
    let receive_id = temp.getAttribute('data-receive-id');
    $.post("/receive/delReceive", {
        receive_id: receive_id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let receive_tr = parseInt($("#receive").children().length);
                let current_page = parseInt($("#receiveCurrent").data('current'));
                let pageCount = parseInt($("#receiveCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && receive_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && receive_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && receive_tr == 1) {
                    window.location.href = '/user/manage/receive/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// 分页
function changePage(page) {
    let temp = page;
    let pagination = temp.getAttribute('data-index');
    if (pagination == 'current_1') {
        layer.msg('已经是第一页了', {
            time: 1000
        });
        return;
    }
    if (pagination == 'current_page') {
        layer.msg('已经是当前页了', {
            time: 1000
        });
        return;
    }
    if (pagination == 'current_end') {
        layer.msg('已经是末页了', {
            time: 1000
        });
        return;
    }
}

// 页数跳转
function jumpPage(pages) {
    let temp = pages;
    let type = temp.getAttribute('data-type');
    let pageCount = temp.getAttribute('data-page-count');
    let current_page = temp.getAttribute('data-current');
    switch (type) {
        case 'article':
            pagination = $(`#articleJump`).val();
            break;
        case 'comment':
            pagination = $(`#commentJump`).val();
            break;
        case 'follow':
            pagination = $(`#followJump`).val();
            break;
        case 'fans':
            pagination = $(`#fansJump`).val();
            break;
    }
    if (parseInt(pagination) > parseInt(pageCount)) {
        layer.msg('输入页数太大了', {
            time: 1000
        });
        return;
    }
    if (parseInt(current_page) == parseInt(pagination)) {
        layer.msg('已经是跳转页了', {
            time: 1000
        });
        return;
    }
    if (pagination == '') {
        layer.msg('输入不能为空', {
            time: 1000
        });
        return;
    }
    window.location.href = `/user/manage/${type}/${pagination}.html`;
}