// 搜索
// 确认用户名
function checkUsername() {
    let username = $('#username');
    let usernameValue = username.val();
    let userMessage = $('#userMessage');
    if (usernameValue.length === 0) {
        username.attr('class', "form-control is-invalid");
        userMessage.html(`<span style="color:red;">用户名不能为空</span>`);
        return false;
    } else {
        return true;
    }
}

// 确认密码
function checkPassword() {
    let password = $('#password');
    let passwordValue = password.val();
    let passwordTip = $('#passwordTip');
    if (passwordValue.length === 0) {
        password.attr('class', "form-control is-invalid");
        passwordTip.html(`<span style="color:red;">密码不能为空</span>`);
        return false;
    } else {
        return true;
    }
}

function userOriginal() {
    let username = $('#username');
    let userMessage = $('#userMessage');
    username.attr('class', "form-control");
    userMessage.html(`<img src="/static/image/mess.png" id="userImg">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入用户名`);
}

function passwordOriginal() {
    let password = $('#password');
    let passwordTip = $('#passwordTip');
    password.attr('class', "form-control");
    passwordTip.html(`<img src="/static/image/mess.png" id="passwordImg">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入密码`);
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

function check() {
    return checkUsername() && checkPassword() && checkVerify();
}

// 确认登录
$('#login').on('click', function () {
    let username = $('#username').val();
    let password = $('#password').val();
    let captcha = $('#captcha').val();
    if (check()) {
        $.ajax({
            type: "POST",
            url: "/user/checkLogin",
            data: {
                username: username,
                password: password,
                captcha: captcha
            },
            dataType: "json",
            success: function (data) {
                if (data.status == -1) {
                    alert(data.message);
                    checkVerify();
                } else if (data.status == 1) {
                    layer.msg(data.message, {
                        time: 1000
                    }, function () {
                        window.location.href = '/';
                    });
                } else {
                    layer.msg(data.message, {
                        time: 1000
                    });
                    checkVerify();
                }
            }
        })
    }
});

// 确认验证码
function checkVerify() {
    let verify = $('#verify').val();
    if (verify == '') {
        layer.msg('验证码不能为空', {
            time: 1000
        });
        return false;
    }
    return true;
}

// 刷新验证码
function refreshVerify() {
    let time = Date.parse(new Date()) / 1000;
    $('#captcha_img').attr('src', '/captcha?id=' + time);
}