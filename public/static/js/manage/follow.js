// 取消关注
function delFollow(followName) {
    let temp = followName;
    let author = temp.getAttribute('data-author');
    $.post("/follow/delFollow", {
        author: author,
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let follow_tr = parseInt($("#follow").children().length);
                let current_page = parseInt($("#followCurrent").data('current'));
                let pageCount = parseInt($("#followCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == 1 && pageCount == 1 && follow_tr == 1) {
                    window.location.reload();
                }
                if (current_page == pageCount && follow_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && follow_tr == 1) {
                    window.location.href = '/user/manage/follow/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}