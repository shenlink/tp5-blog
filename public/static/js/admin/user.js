// 恢复被软删除的用户
function unDeleteAll(middle) {
    let temp = middle;
    let username = temp.getAttribute('data-username');
    $.post("/user/unDeleteAll", {
        username: username
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
}

// 拉黑用户
function defriendUser(userId) {
    let temp = userId;
    let id = temp.getAttribute('data-user-id');
    if (id == "1") {
        layer.msg('这是管理员，不能拉黑', {
            time: 1000
        });
        return;
    }
    $.post("/user/defriendUser", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let tr = temp.parentNode.parentNode;
                tr.children[8].innerText = '拉黑';
                let html = createHtml('normalUser', 'delUser', 'user-id', id, '恢复');
                tr.lastElementChild.innerHTML = html;
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    });
}

// 恢复用户到正常状态
function normalUser(userId) {
    let temp = userId;
    let id = temp.getAttribute('data-user-id');
    $.post("/user/normalUser", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let tr = temp.parentNode.parentNode;
                tr.children[8].innerText = '正常';
                let html = createHtml('defriendUser', 'delUser', 'user-id', id, '拉黑');
                tr.lastElementChild.innerHTML = html;
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}

// // 删除用户
function delUser(userId) {
    let temp = userId;
    let id = temp.getAttribute('data-user-id');
    if (id == "1") {
        layer.msg('这是管理员，不能删除', {
            time: 1000
        });
        return;
    }
    if (!confirm('确认删除吗？')) {
        return;
    }
    $.post("/user/delUser", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                let user_tr = parseInt($("#user").children().length);
                let current_page = parseInt($("#userCurrent").data('current'));
                let pageCount = parseInt($("#userCurrent").data('page-count'));
                if (current_page < pageCount) {
                    window.location.reload();
                }
                if (current_page == pageCount && user_tr > 1) {
                    let tr = temp.parentNode.parentNode;
                    let tbody = tr.parentNode;
                    tbody.removeChild(tr);
                }
                if (current_page > 1 && current_page == pageCount && user_tr == 1) {
                    window.location.href = '/admin/manage/aricle/' + (current_page - 1) + '.html';
                }
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}