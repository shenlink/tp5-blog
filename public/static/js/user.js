let lis = $('.manage-list');
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

// 删除评论
function delComment(commentId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = commentId;
    let article_id = temp.getAttribute('data-article-id');
    let id = temp.getAttribute('data-comment-id');
    let author = $('#author').data('author');
    $.ajax({
        type: "POST",
        url: "/comment/delComment",
        data: {
            article_id: article_id,
            id: id
        },
        dataType: "json",
        success: function (data) {
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
                        let comment_header = temp.parentNode.parentNode.parentNode;
                        let comment_content = comment_header.nextElementSibling;
                        let table = comment_header.parentNode;
                        table.removeChild(comment_header);
                        table.removeChild(comment_content);
                    }
                    if (current_page > 1 && current_page == pageCount && comment_tr == 1) {
                        let prePage = current_page - 1;
                        window.location.href = `/user/${author}/comment/${prePage}.html`;

                    }
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
}

// 删除点赞
function delPraise(praiseId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = praiseId;
    let article_id = temp.getAttribute('data-article-id');
    let id = temp.getAttribute('data-praise-id');
    let author = $('#author').data('author');
    $.ajax({
        type: "POST",
        url: "/praise/delPraise",
        data: {
            article_id: article_id,
            id: id
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    let praise_tr = parseInt($("#praise").children().length);
                    let current_page = parseInt($("#praiseCurrent").data('current'));
                    let pageCount = parseInt($("#praiseCurrent").data('page-count'));
                    if (current_page < pageCount) {
                        window.location.reload();
                    }
                    if (current_page == 1 && pageCount == 1 && praise_tr == 1) {
                        window.location.reload();
                    }
                    if (current_page == pageCount && praise_tr > 1) {
                        let tr = temp.parentNode.parentNode;
                        let tbody = tr.parentNode;
                        tbody.removeChild(tr);
                    }
                    if (current_page > 1 && current_page == pageCount && praise_tr == 1) {
                        let prePage = current_page - 1;
                        window.location.href = `/user/${author}/praise/${prePage}.html`;
                    }
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
}

// 删除收藏
function delCollect(collectId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = collectId;
    let article_id = temp.getAttribute('data-article-id');
    let id = temp.getAttribute('data-collect-id');
    let author = $('#author').data('author');
    $.ajax({
        type: "POST",
        url: "/collect/delCollect",
        data: {
            article_id: article_id,
            id: id
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    let collect_tr = parseInt($("#collect").children().length);
                    let current_page = parseInt($("#collectCurrent").data('current'));
                    let pageCount = parseInt($("#collectCurrent").data('page-count'));
                    if (current_page < pageCount) {
                        window.location.reload();
                    }
                    if (current_page == 1 && pageCount == 1 && collect_tr == 1) {
                        window.location.reload();
                    }
                    if (current_page == pageCount && collect_tr > 1) {
                        let tr = temp.parentNode.parentNode;
                        let tbody = tr.parentNode;
                        tbody.removeChild(tr);
                    }
                    if (current_page > 1 && current_page == pageCount && collect_tr == 1) {
                        let prePage = current_page - 1;
                        window.location.href = `/user/${author}/collect/${prePage}.html`;
                    }
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
}

// 删除分享
function delShare(shareId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = shareId;
    let article_id = temp.getAttribute('data-article-id');
    let id = temp.getAttribute('data-share-id');
    let author = $('#author').data('author');
    $.ajax({
        type: "POST",
        url: "/share/delShare",
        data: {
            article_id: article_id,
            id: id
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    let share_tr = parseInt($("#share").children().length);
                    let current_page = parseInt($("#shareCurrent").data('current'));
                    let pageCount = parseInt($("#shareCurrent").data('page-count'));
                    if (current_page < pageCount) {
                        window.location.reload();
                    }
                    if (current_page == 1 && pageCount == 1 && share_tr == 1) {
                        window.location.reload();
                    }
                    if (current_page == pageCount && share_tr > 1) {
                        let tr = temp.parentNode.parentNode;
                        let tbody = tr.parentNode;
                        tbody.removeChild(tr);
                    }
                    if (current_page > 1 && current_page == pageCount && share_tr == 1) {
                        let prePage = current_page - 1;
                        window.location.href = `/user/${author}/share/${prePage}.html`;
                    }
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
}

// 关注或取消关注
$('#follow').on('click', function () {
    let follow = $('#follow');
    let username = follow.data('username');
    let author = follow.data('author');
    if (username == '') {
        layer.msg('登录才能关注', {
            time: 2000
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: "/follow/checkFollow",
        data: {
            author: author
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg('关注成功', {
                    time: 1000
                }, function () {
                    follow.text('已关注');
                });
            } else if (data.status === 0) {
                layer.msg('关注失败', {
                    time: 1000
                });
            } else if (data.status === 11) {
                layer.msg('取消关注', {
                    time: 1000
                }, function () {
                    follow.text('关注');
                });
            } else {
                layer.msg('取消失败', {
                    time: 1000
                });
            }
        }
    })
});

// 发私信
function addMessage() {
    let author = $('#addMessage').data('author');
    window.location.href = '/message/sendMessage/username/' + author + '.html';
}
