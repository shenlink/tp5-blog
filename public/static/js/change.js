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

// 确认密码是否符合要求
function checkPassword() {
    let password = $('#password');
    let passwordValue = password.val();
    let passwordTip = $('#passwordTip');
    if (passwordValue.length === 0) {
        password.attr('class', "form-control is-invalid");
        passwordTip.html(`<span style="color:red;">密码不能为空</span>`);
        return false;
    } else if (passwordValue.length < 6 || passwordValue.length > 16) {
        passwordTip.html(`<span style="color:red;">密码位数不符合要求,要求6到16位</span>`);
        return false;
    } else {
        // 正则表达式正向预查，匹配含数字，小写字母，大写字母和特殊字符的字符串
        let reg = /(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{6,16}/;
        if (reg.test(passwordValue)) {
            passwordTip.html(`<span style="color:green;">密码符合要求</span>`);
            return true;
        } else {
            passwordTip.html(`<span style="color:red;">密码不符合要求</span>`);
            return false;
        }
    }
}

// 确认密码
function checkConPassword() {
    let password = $('#password');
    let passwordValue = password.val();
    let confirmPassword = $('#confirmPassword');
    let conPasswordValue = confirmPassword.val();
    let conPasswordTip = $('#conPasswordTip');
    if (conPasswordValue.length === 0) {
        confirmPassword.attr('class', "form-control is-invalid");
        conPasswordTip.html(`<span style="color:red;">确认密码不能为空</span>`);
        return false;
    }
    if (passwordValue === conPasswordValue) {
        conPasswordTip.html(`<span style="color:green;">两次密码一致</span>`);
        return true;
    } else {
        conPasswordTip.html(`<span style="color:red;">两次密码不一致</span>`);
        return false;
    }
}

function passwordOriginal() {
    let password = $('#password');
    let passwordTip = $('#passwordTip');
    password.attr('class', "form-control");
    passwordTip.html(`<img src="/static/image/mess.png" id="passwordImg">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;密码为6到16位，且必须包含数字，小写字母，大写字母和特殊字符`);
}

// 确认密码输入框失去焦点后，再次获得焦点时，恢复到初始样式，提示也会恢复到初始值
function conPasswordOriginal() {
    let confirmPassword = $('#confirmPassword');
    let conPasswordTip = $('#conPasswordTip');
    confirmPassword.attr('class', "form-control");
    conPasswordTip.html(`<img src="/static/image/mess.png" id="conPasswordImg">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    确认密码 `);
}

// 获取眼睛图案
let passwordEye = $('#passwordEye');
let password = $('#password');
let flag = false;

function clickEye() {
    if (flag == false) {
        password.attr('type', 'text');
        passwordEye.attr('src', '/static/image/open.png');
        passwordEye.attr('alt', '隐藏密码');
        flag = true;
    } else {
        password.attr('type', 'password');
        passwordEye.attr('src', '/static/image/close.png');
        passwordEye.attr('alt', '显示密码');
        flag = false;
    }
}

// 获取眼睛图案
let conPasswordEye = $('#conPasswordEye');
let confirmPassword = $('#confirmPassword');
let conFlag = false;

function clickConEye() {
    if (conFlag == false) {
        confirmPassword.attr('type', 'text');
        conPasswordEye.attr('src', '/static/image/open.png');
        conPasswordEye.attr('alt', '隐藏密码');
        conFlag = true;
    } else {
        confirmPassword.attr('type', 'password');
        conPasswordEye.attr('src', '/static/image/close.png');
        conPasswordEye.attr('alt', '显示密码');
        conFlag = false;
    }
}

// 表单提交验证
function check() {
    return checkPassword() && checkConPassword();
}

// 确认修改
$('#change').on('click', function () {
    let username = $("input[ name='username' ] ").val()
    let password = $('#password').val();
    let introduction = $('#introduction').val();
    if (check()) {
        $.post("/user/checkChange", {
            username: username,
            password: password,
            introduction: introduction
        }, function (data) {
            if (data.status == 1) {
                layer.msg(data.message, {
                    time: 1000
                }, function () {
                    location.href = '/user/' + username;
                });
            } else {
                layer.msg(data.message, {
                    time: 1000
                });
            }
        },'json');
    }
});

// 修改公告
$('#changeAnnouncement').on('click', function () {
    let content = $("input[ name='content' ] ").val()
    let announcement_id = $('#announcement-id').val();
    $.post("/announcement/checkChangeAnnouncement", {
        content: content,
        announcement_id: announcement_id
    }, function (data) {
        if (data === '1') {
            layer.msg('修改成功', {
                time: 1000
            }, function () {
                window.history.back(-1);
            });
        } else {
            layer.msg('修改失败', {
                time: 1000
            });
        }
    });
});