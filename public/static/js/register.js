// 确认用户名
function checkUsername() {
    let username = $('#username');
    let usernameValue = username.val();
    let userMessage = $('#userMessage');
    if (usernameValue.length === 0) {
        username.attr('class', 'form-control is-invalid');
        userMessage.html(`<span style="color:red;">用户名不能为空</span>`);
        return false;
    }
    let reg = /^(?=.*[a-z])[a-z0-9]{4,16}$/i;
    if (!reg.test(usernameValue)) {
        userMessage.html(`<span style="color:red;">用户名不符合要求</span>`);
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/user/checkUsername",
        data: {
            username: usernameValue
        },
        dataType: "json",
        success: function (data) {
            if (data.status == 1) {
                userMessage.html(`<span style="color:red;">${data.message}</span>`);
            } else {
                userMessage.html(`<span  style="color:green;">${data.message}</span>`);
            }
        }
    });
    return true;
}

function checkAjax() {
    let userMessage = $('#userMessage');
    if (userMessage.css('color') == 'red') {
        return false;
    } else {
        return true;
    }
}

// 确认密码是否符合要求
function checkPassword() {
    let password = $('#password');
    let passwordValue = password.val();
    let passwordTip = $('#passwordTip');
    if (passwordValue.length === 0) {
        password.attr('class', 'form-control is-invalid');
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
        confirmPassword.attr('class', 'form-control is-invalid');
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

// 用户名输入框失去焦点后，再次获得焦点时，恢复到初始样式，提示也会恢复到初始值
function userOriginal() {
    let username = $('#username');
    let userMessage = $('#userMessage');
    username.attr('class', 'form-control');
    userMessage.html(`<img src="/static/image/mess.png" id="userImg">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入用户名，用户名至少包含1个字母，可以包含数字，4到16位`);
}

// 密码输入框失去焦点后，再次获得焦点时，恢复到初始样式，提示也会恢复到初始值
function passwordOriginal() {
    let password = $('#password');
    let passwordTip = $('#passwordTip');
    password.attr('class', 'form-control');
    passwordTip.html(`<img src="/static/image/mess.png" id="passwordImg">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;密码为6到16位，且必须包含数字，小写字母，大写字母和特殊字符`);
}

// 确认密码输入框失去焦点后，再次获得焦点时，恢复到初始样式，提示也会恢复到初始值
function conPasswordOriginal() {
    let confirmPassword = $('#confirmPassword');
    let conPasswordTip = $('#conPasswordTip');
    confirmPassword.attr('class', 'form-control');
    conPasswordTip.html(`<img src="/static/image/mess.png" id="conPasswordImg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    确认密码`);
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
    return checkUsername() && checkAjax() && checkPassword() && checkConPassword() && checkVerify();
}

// 确认注册
$('#register').on('click', function () {
    let username = $('#username').val();
    let password = $('#password').val();
    let captcha = $('#captcha').val();
    if (check()) {
        $.ajax({
            type: "POST",
            url: "/user/checkRegister",
            data: {
                username: username,
                password: password,
                captcha: captcha
            },
            dataType: "json",
            success: function (data) {
                if (data.status === 1) {
                    layer.msg(data.message, {
                        time: 1000
                    }, function () {
                        location.href = '/user/login.html';
                    });
                } else {
                    layer.msg(data.message, {
                        time: 1000
                    }, function () {
                        refreshVerify();
                    });
                }
            }
        });
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