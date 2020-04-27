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

// 发私信
$('#addMessage').on('click', function () {
    let author = $('#author').val();
    let content = $('#content').val();
    $.post("/message/checkAddMessage", {
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