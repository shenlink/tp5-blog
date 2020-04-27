// 搜索
$('#search').on('click', function () {
    let type = $('#type').val();
    if (type == '2') {
        searchContent = encodeURIComponent($('#searchContent').val());
        if (searchContent == '') {
            layer.msg('查询条件不能为空', {
                time: 1000
            });
            return;
        }
    } else {
        searchContent = $('#searchContent').val();
        if (searchContent == '') {
            layer.msg('查询条件不能为空', {
                time: 1000
            });
            return;
        }
        let reg = /^(?=.*[a-z])[a-z0-9]{4,16}$/i;
        if (!reg.test(searchContent)) {
            layer.msg('用户名不符合要求', {
                time: 1000
            });
            return;
        }
    }
    if (type == '1') {
        window.location.href = `/user/search/${searchContent}/1.html`;
    } else {
        window.location.href = `/article/search/${searchContent}/1.html`;
    }
});

// 左边的导航栏切换
let lis = $('.list-group-item');
let items = $('.manage-item');
// for循环在页面加载完成之后就已经执行完了，这时候lis的index索引已经赋值完成,然后执行lis[i].click事件注册，待点击之后就触发
for (let i = 0; i < lis.length; i++) {
    lis[i].setAttribute('index', i);
    lis[i].onclick = function () {
        let index = this.getAttribute('index');
        for (let i = 0; i < items.length; i++) {
            items[i].style.display = 'none';
        }
        items[index].style.display = 'block';
    }
}

function createHtml(function1, function2, data, value, action) {
    let html = `<button class="btn btn-primary btn-sm" onclick="${function1}(this)" data-${data}=${value}>
        ${action}
    </button>
    <button class="btn btn-primary btn-sm" onclick="${function2}(this)" data-${data}=${value}>
        删除
    </button>`;
    return html;
}

// 拉黑用户
function defriendUser(userId) {
    let temp = userId;
    let user_id = temp.getAttribute('data-user-id');
    if (user_id == "1") {
        layer.msg('这是管理员，不能拉黑', {
            time: 1000
        });
        return;
    }
    $.post("/user/defriendUser", {
        user_id: user_id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let tr = temp.parentNode.parentNode;
                tr.children[8].innerText = '拉黑';
                let html = createHtml('normalUser', 'delUser', 'user-id', user_id, '恢复');
                tr.lastElementChild.innerHTML = html;
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    });
}

// 恢复用户到正常状态
function normalUser(userId) {
    let temp = userId;
    let user_id = temp.getAttribute('data-user-id');
    $.post("/user/normalUser", {
        user_id: user_id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let tr = temp.parentNode.parentNode;
                tr.children[8].innerText = '正常';
                let html = createHtml('defriendUser', 'delUser', 'user-id', user_id, '拉黑');
                tr.lastElementChild.innerHTML = html;
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// // 删除用户
function delUser(userId) {
    let temp = userId;
    let user_id = temp.getAttribute('data-user-id');
    if (user_id == "1") {
        layer.msg('这是管理员，不能删除', {
            time: 1000
        });
        return;
    }
    if (!confirm('确认删除吗？')) {
        return;
    }
    $.post("/user/delUser", {
        user_id: user_id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let user_tr = parseInt($("#user").children().length);
                let current_page = parseInt($("#userCurrent").data('current'));
                let pageCount = parseInt($("#userCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == pageCount && user_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && user_tr == 1) {
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

// 拉黑文章
function defriendArticle(articleId) {
    let temp = articleId;
    let article_id = temp.getAttribute('data-article-id');
    $.post("/article/defriendArticle", {
        article_id: article_id
    }, function (data) {
        if (data === '1') {
            layer.msg('拉黑成功', {
                time: 1000
            }, function () {
                let tr = temp.parentNode.parentNode;
                tr.children[3].innerText = '拉黑';
                let html = createHtml('normalArticle', 'delArticle', 'article-id', article_id, '恢复');
                tr.lastElementChild.innerHTML = html;
            });
        } else {
            layer.msg('拉黑失败', {
                time: 1000
            });
        }
    });
}

// 恢复文章到正常状态
function normalArticle(articleId) {
    let temp = articleId;
    let article_id = temp.getAttribute('data-article-id');
    $.post("/article/normalArticle", {
        article_id: article_id
    }, function (data) {
        if (data === '1') {
            layer.msg('恢复成功', {
                time: 1000
            }, function () {
                let tr = temp.parentNode.parentNode;
                tr.children[3].innerText = '正常';
                let html = createHtml('defriendArticle', 'delArticle', 'article-id', article_id, '拉黑');
                tr.lastElementChild.innerHTML = html;
            });
        } else {
            layer.msg('恢复失败', {
                time: 1000
            });
        }
    });
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
                    window.location.href = '/admin/manage/aricle/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg('删除失败', {
                time: 1000
            });
        }
    });
}

// 新增分类
function addCategory() {
    window.location.href = '/category/addCategory.html';
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
                if (current_page > 1 && current_page == pageCount && comment_tr == 1) {
                    window.location.href = '/admin/manage/comment/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg('删除失败', {
                time: 1000
            });
        }
    });
}

// 修改公告
function changeAnnouncement(announcementId) {
    let temp = announcementId;
    let announcement_id = temp.getAttribute('data-announcement-id');
    window.location.href = '/announcement/changeAnnouncement/id/' + announcement_id + '.html';
}

// 删除公告
function delAnnouncement(announcementId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = announcementId;
    let announcement_id = temp.getAttribute('data-announcement-id');
    $.post("/announcement/delAnnouncement", {
        announcement_id: announcement_id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let announcement_tr = parseInt($("#announcement").children().length);
                let current_page = parseInt($("#announcementCurrent").data('current'));
                let pageCount = parseInt($("#announcementCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && announcement_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && announcement_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && announcement_tr == 1) {
                    window.location.href = '/admin/manage/announcement/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// 新增公告
function addAnnouncement() {
    window.location.href = '/announcement/addAnnouncement.html';
}


// 发私信
function addMessage() {
    let author = $('#addMessage').data('author');
    window.location.href = '/message/addMessage/username/' + author + '.html';
}

// 删除私信
function delMesssage(messageId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = messageId;
    let message_id = temp.getAttribute('data-message-id');
    $.post("/message/delMessage", {
        message_id: message_id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let message_tr = parseInt($("#message").children().length);
                let current_page = parseInt($("#messageCurrent").data('current'));
                let pageCount = parseInt($("#messageCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && message_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && message_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && message_tr == 1) {
                    window.location.href = '/admin/manage/message/' + (current_page - 1) + '.html';
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
        layer.msg('已经是点击页了', {
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
        case 'user':
            pagination = $(`#userJump`).val();
            break;
        case 'article':
            pagination = $(`#articleJump`).val();
            break;
        case 'category':
            pagination = $(`#categoryJump`).val();
            break;
        case 'comment':
            pagination = $(`#commentJump`).val();
            break;
        case 'announcement':
            pagination = $(`#announcementJump`).val();
            break;
        case 'message':
            pagination = $(`#messageJump`).val();
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
    window.location.href = `/admin/manage/${type}/${pagination}.html`;
}