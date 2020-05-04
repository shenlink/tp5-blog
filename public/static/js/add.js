// 添加分类
$('#addCategory').on('click', function () {
    let category = $('#categoryName').val();
    $.ajax({
        type: "POST",
        url: "/category/checkAddCategory",
        data: {
            category: category
        },
        dataType: "json",
        success: function (data) {
            if (data.status == 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    window.history.back(-1);
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
});

// 添加公告
$('#addAnnouncement').on('click', function () {
    let content = $('#content').val();
    $.ajax({
        type: "POST",
        url: "/announcement/checkAddAnnouncement",
        data: {
            content: content
        },
        dataType: "json",
        success: function (data) {
            if (data.status == 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    window.history.back(-1);
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
$('#addMessage').on('click', function () {
    let username = $('#username').val();
    let content = $('#content').val();
    $.ajax({
        type: "POST",
        url: "/message/checkMessage",
        data: {
            username: username,
            content: content
        },
        dataType: "json",
        success: function (data) {
            if (data.status == 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    window.history.back(-1);
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
});