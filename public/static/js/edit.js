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
    let id = this.getAttribute('data-editArticle');
    $.post("/article/checkEdit", {
        id: id,
        title: title,
        content: content,
        category: category
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                window.location.href = '/user/manage.html';
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    },'json');
});