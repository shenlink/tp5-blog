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
    let type = temp.getAttribute('data-type');
    let pagination = $('#paginationJump').val();
    let pageCount = temp.getAttribute('data-page-count');
    let current_page = temp.getAttribute('data-current');
    if (parseInt(pagination) > parseInt(pageCount)) {
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
    if (pagination == '') {
        layer.msg('输入不能为空', {
            time: 1000
        });
        return;
    }
    window.location.href = `/index/index/${type}/${pagination}.html`;
}