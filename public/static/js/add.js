// 添加分类
$('#addCategory').on('click', function () {
    let category = $('#categoryName').val();
    $.post("/category/checkAddCategory", {
        category: category
    }, function (data) {
        if (data.status === 1) {
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
    },'json');
});

// 添加公告
$('#addAnnouncement').on('click', function () {
    let content = $('#content').val();
    $.post("/announcement/checkAddAnnouncement", {
        content: content
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    });
});

// 发私信
$('#addMessage').on('click', function () {
    let author = $('#author').val();
    let content = $('#content').val();
    $.post("/message/checkMessage", {
        author: author,
        content: content
    }, function (data) {
        if (data.status === 1) {
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
    });
});