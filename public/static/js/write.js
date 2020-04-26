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

// 发表文章
let E = window.wangEditor;
let editor = new E('#content');
editor.create();
$('#publish').on('click', function () {
    let title = $('#title').val();
    let html = editor.txt.html();
    let content = filterXSS(html);
    let category = $('#category').val();
    let username = $("#publish").data('username');
    let content_text = editor.txt.text();
    if (title.match(/^[ ]+$/) || title.length == 0) {
        layer.msg('标题不能为空', {
            time: 1000
        });
        return;
    }
    if (content_text.match(/^[ ]+$/) || content_text.length == 0) {
        layer.msg('文章内容不能为空', {
            time: 1000
        });
        return;
    }
    $.post("/article/checkWrite", {
        title: title,
        content: content,
        category: category
    }, function (data) {
        if (data === '1') {
            layer.msg('发表成功', {
                time: 1000
            }, function () {
                window.location.href = '/user/' + username + '.html';
            });
        } else {
            layer.msg('发表失败', {
                time: 1000
            });
        }
    });
});