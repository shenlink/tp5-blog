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

// 分页
function changePage(page) {
    let temp = page;
    let pagination = temp.getAttribute('data-index');
    if (pagination == 'current_1') {
        layer.msg('已经是第一页了', {
            time: 1000
        });
        return;
    }
    if (pagination == 'current_page') {
        layer.msg('已经是点击页了', {
            time: 1000
        });
        return;
    }
    if (pagination == 'current_end') {
        layer.msg('已经是末页了', {
            time: 1000
        });
        return;
    }
}

// 页数跳转
function jumpPage(pages) {
    let temp = pages;
    let search_type = $(".type").text();
    let count = temp.getAttribute('data-count');
    let pagination = $('.search-type').val();
    let current_page = $('#current').data('pagination');
    if (parseInt(pagination) > parseInt(count)) {
        layer.msg('输入页数太大了', {
            time: 1000
        });
        return;
    }
    if (parseInt(current_page) == parseInt(pagination)) {
        layer.msg('已经是跳转页了', {
            time: 1000
        });
        return;
    }
    if (search_type == '文章查询结果') {
        let type = decodeURI(temp.getAttribute('data-type'));
        window.location.href = `/article/search/${type}/${pagination}.html`;
    } else {
        window.location.href = `/user/search/${type}/${pagination}.html`;
    }
}