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

// 编辑文章
$('#edit').on('click', function () {
    let title = $('#title').val();
    let content = editor.txt.html();
    let category = $('#category').val();
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
    let article_id = this.getAttribute('data-editArticle');
    $.post("/article/checkEdit", {
        article_id: article_id,
        title: title,
        content: content,
        category: category
    }, function (data) {
        if (data === '1') {
            layer.msg('更改成功', {
                time: 1000
            }, function () {
                window.location.href = '/user/manage.html';
            });
        } else {
            layer.msg('更改失败', {
                time: 1000
            });
        }
    });
});