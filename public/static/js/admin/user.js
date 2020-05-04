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
            }, );
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    });
}

// 恢复被软删除的单个用户
function unDelete(middle) {
    let temp = middle;
    let id = temp.getAttribute('data-user-id');
    $.post("/user/unDelete", {
        id: id
    }, function (data) {
        if (data.status === 1) {
            layer.msg(data.message, {
                time: 1000
            }, function () {
                var table = $('#dataTable').DataTable();
                var d = table.row(this).data();
                d.counter++;
                table.row(this).data(d).draw();
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
                var table = $('#dataTable').DataTable();
                var d = table.row(this).data();
                d.counter++;
                table.row(this).data(d).draw();
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
                var table = $('#dataTable').DataTable();
                var d = table.row(this).data();
                d.counter++;
                table.row(this).data(d).draw();
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
                var table = $('#dataTable').DataTable();
                var d = table.row(this).data();
                d.counter++;
                table.row(this).data(d).draw();
            });
        } else {
            layer.msg(data.message, {
                time: 1000
            });
        }
    }, 'json');
}