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
    $.ajax({
        type: "POST",
        url: "/article/checkWrite",
        data: {
            title: title,
            content: content,
            category: category
        },
        dataType: "json",
        success: function (data) {
            if (data.status === 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    window.location.href = '/user/' + username + '.html';
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        }
    })
});