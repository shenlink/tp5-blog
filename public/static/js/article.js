// 点赞
$('#praise').on('click', function () {
    let praise = $('#praise');
    let username = praise.data('username');
    let article = $('#article');
    let article_id = article.data('article-id');
    let author = $('#author').text();
    let title = $('#title').text();
    let praise_count = parseInt(praise.text().replace(/[^0-9]/ig, ""));
    let praise_author = $('#praise-all');
    let praise_all = parseInt(praise_author.text());
    if (username == '') {
        layer.msg('请先登录', {
            time: 1000
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: "/praise/checkPraise",
        data: {
            article_id: article_id,
            author: author,
            title: title
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg('点赞成功', {
                    time: 1000
                }, function () {
                    praise.html(`已点赞(${praise_count + 1})&nbsp;&nbsp;&nbsp;&nbsp;`);
                    praise_author.html(praise_all + 1);
                });
            } else if (data.status === 0) {
                layer.msg('点赞失败', {
                    time: 1000
                });
            } else if (data.status === 11) {
                layer.msg('取消点赞', {
                    time: 1000
                }, function () {
                    praise.html(`点赞(${praise_count - 1})&nbsp;&nbsp;&nbsp;&nbsp;`);
                    praise_author.html(praise_all - 1);
                });
            } else {
                layer.msg('取消失败', {
                    time: 1000
                });
            }
        }
    })
});

// 收藏
$('#collect').on('click', function () {
    let collect = $('#collect');
    let username = collect.data('username');
    let article = $('#article');
    let article_id = article.data('article-id');
    let author = $('#author').text();
    let title = $('#title').text();
    let collect_count = parseInt(collect.text().replace(/[^0-9]/ig, ""));
    if (username == '') {
        layer.msg('请先登录', {
            time: 1000
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: "/collect/checkCollect",
        data: {
            article_id: article_id,
            author: author,
            title: title
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    collect.html(`已收藏(${collect_count+1})&nbsp;&nbsp;&nbsp;&nbsp;`);
                });
            } else if (data.status === 0) {
                layer.msg(data.message, {
                    time: 1000
                });
            } else if (data.status === 11) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    collect.html(`收藏(${collect_count-1})&nbsp;&nbsp;&nbsp;&nbsp;`);
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
});

// 分享
$('#share').on('click', function () {
    let share = $('#share');
    let username = share.data('username');
    let article = $('#article');
    let article_id = article.data('article-id');
    let title = $('#title').text();
    let author = $('#author').text();
    let share_count = parseInt(share.text().replace(/[^0-9]/ig, ""));
    if (username == '') {
        layer.msg('请先登录', {
            time: 1000
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: "/share/checkShare",
        data: {
            article_id: article_id,
            author: author,
            title: title
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    share.html(`已分享(${share_count+1})&nbsp;&nbsp;&nbsp;&nbsp;`);
                });
            } else if (data.status === 0) {
                layer.msg(data.message, {
                    time: 1000
                });
            } else if (data.status === 11) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    share.html(`分享(${share_count-1})&nbsp;&nbsp;&nbsp;&nbsp;`);
                });
            } else {
                layer.msg('取消失败', {
                    time: 1000
                });
            }
        }
    })
});

// 获取当前时间
function createTime() {
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth();
    let day = date.getDate();
    let hour = date.getHours();
    let minute = date.getMinutes();
    let second = date.getSeconds();
    if (parseInt(month) < 10) {
        month = (parseInt(month) + 1).toString();
        month = '0' + month;
    }
    if (parseInt(day) < 10) {
        day = '0' + day;
    }
    if (parseInt(hour) < 10) {
        hour = '0' + hour;
    }
    if (parseInt(minute) < 10) {
        minute = '0' + minute;
    }
    if (parseInt(second) < 10) {
        second = '0' + second;
    }
    let RecentTime = `${year}-${month}-${day} ${hour}:${minute}:${second}`;
    return RecentTime;
}

// 创建评论的html
function createComment(username, comment_time, id, content) {
    let comment_html = `<div class="card-body">
                            <h5 class="card-title">
                                <span>${username}</span>
                                <span>
                                    <small class="offset-md-1">
                                        ${comment_time}
                                    </small>
                                </span>
                                <div>
                                    <small class="delComment"
                                        onclick="delComment(this)"
                                        data-comment-id=
                                        ${id}>删除
                                    </small>
                                </div>
                            </h5>
                            <div class="card-text">
                                ${content}
                            </div>
                        </div>`;
    let div = document.createElement("div");
    div.setAttribute('class', 'card');
    div.innerHTML = comment_html;
    return div;
}

// 登录后才能评论，评论内容去掉标签，空格后为空的话，不能评论
let E = window.wangEditor;
let editor = new E('#editor-toolbar', '#editor-content');
editor.create();
$('#comment').on('click', function () {
    let html = editor.txt.html();
    let content = filterXSS(html);
    let content_text = editor.txt.text();
    let article = $('#article');
    let article_id = article.data('article-id');
    let author = $('#author').data('author');
    let title = $('#title').text();
    let username = $('#comment').data('comment');
    let commentContent = $('#comment-content');
    let count = $('#comment-count');
    let comment_count = parseInt(count.text().replace(/[^0-9]/ig, ""));
    let comment_author = $('#comment-all');
    let comment_all = parseInt(comment_author.text());
    let comment_time = createTime();
    if (username == '') {
        layer.msg('登录才能评论', {
            time: 2000
        });
        return;
    }
    if (content_text.match(/^[ ]+$/) || content_text.length == 0) {
        layer.msg('评论内容不能为空', {
            time: 1000
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: "/comment/addComment",
        data: {
            article_id: article_id,
            author: author,
            title: title,
            content: content
        },
        dataType: "json",
        success: function (data) {
            if (data.status == 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    id = data.id;
                    let div = createComment(username, comment_time, id, content);
                    commentContent.prepend(div);
                    count.html(`评论数：${comment_count + 1}`);
                    comment_author.html(comment_all + 1);
                    editor.txt.html('');
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
});

// 删除评论
function delComment(commentId) {
    if (!confirm('确认删除吗？')) {
        return;
    }
    let temp = commentId;
    let id = temp.getAttribute('data-comment-id');
    let article = $('#article');
    let article_id = article.data('article-id');
    let count = $('#comment-count');
    let comment_count = parseInt(count.text().replace(/[^0-9]/ig, ""));
    let commentContent = document.querySelector('#comment-content');
    let comment_author = $('#comment-all');
    let comment_all = parseInt(comment_author.text());
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
                    let card = temp.parentNode.parentNode.parentNode.parentNode;
                    commentContent.removeChild(card);
                    count.html(`评论数：${comment_count - 1}`);
                    comment_author.html(comment_all - 1);
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
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    follow.text('已关注');
                });
            } else if (data === 0) {
                layer.msg(data.message, {
                    time: 1000
                });
            } else if (data === 11) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    follow.text('关注');
                });
            } else {
                layer.msg(data.message, {
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