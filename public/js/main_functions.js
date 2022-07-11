function loader(_parent_block) {
    $(_parent_block).html('<div class="loader text-center"><img src="/images/Blocks-1s-200px.svg" alt="loader..." /></div>');
}

function notificationDanger(_block, $content) {
    $(_block).html(`<div class="card bg-danger text-white shadow">
                            <div class="card-body">
                               {$content}
                            </div>
                        </div>`);
}

function updateURLParameter(url, param, paramVal, changeState = false) {
    let newAdditionalURL = "";
    let _id_parts = url.split('#');
    let _id = _id_parts.length === 2 ? _id_parts[1] : '';
    if (_id !== '') {
        let _l = url.split('#' + _id);
        if (_l.length === 2) {
            url = _l[0]
        }
    }
    let tempArray = url.split("?");
    let baseURL = tempArray[0];
    let additionalURL = tempArray[1];
    let temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                if (tempArray[i] !== '') {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }
    }
    let rows_txt = temp + "" + param + "=" + paramVal;
    let _url = !paramVal || paramVal === '' || (typeof paramVal === 'object' && paramVal.length === 0) ? baseURL + "?" + newAdditionalURL : baseURL + "?" + newAdditionalURL + rows_txt;
    _url = _id !== '' ? _url + '#' + _id : _url;
    if (changeState) {
        history.pushState(null, null, _url);
    }
    return _url;
}

function numberWithSpaces(x) {
    let tt = '';
    if (x !== null) {
        let parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        tt = parts.join(".");
    }
    console.log(tt);
    return tt;
}

function pagination(countData) {

    let pagination = {
        buttonsCount: 0,
        showingData: 150
    };

    pagination.buttonsCount = Math.ceil(parseInt(countData) / pagination.showingData);
    let pagButtons = '';
    let last = '';
    let h = location.href.split('&page=');
    let page = 0;
    let i = 0;
    let href = updateURLParameter(window.location.href, "page", page - 1);
    if (getUrlParameter('page') !== undefined) {
        h = location.href.split('&page=');
        page = parseInt(getUrlParameter('page'));
    }
    pagButtons += '<ul class="pagination justify-content-right">';
    if (pagination.buttonsCount > 5) {
        if (page >= 3) {
            if (h) {
                href = h[0] + '&page=';
            } else {
                href = location.href + '&page=';
            }

            if (!(parseInt(pagination.buttonsCount) - parseInt(page) < 3)) {
                pagButtons += '<li class="page-item">' +
                    '<a class="page-link" href="' + href + '0" >' + 1 + '</a>' +
                    '</li>' +
                    '<li class="page-item">' +
                    '<a href="#" class="page-link" >…</a>' +
                    '</li>' +
                    '<li class="page-item">' +
                    '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", page - 1) + '" >' + page + '</a>' +
                    '</li>' +
                    '<li class="page-item">' +
                    '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", page) + '" >' + (page + 1) + '</a>' +
                    '</li>' +
                    '<li class="page-item">' +
                    '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", page + 1) + '" >' + (page + 2) + '</a>' +
                    '</li>' +
                    '<li class="page-item">' +
                    '<a class="page-link" href="#" >…</a>' +
                    '</li>' +
                    '<li class="page-item">' +
                    '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", (parseInt(pagination.buttonsCount) - 1)) + '" >' + pagination.buttonsCount + '</a>' +
                    '</li>';
            } else {
                pagButtons += '<li  class="page-item">' +
                    '<a class="page-link" href="' + href + '0" >' + 1 + '</a>' +
                    '</li>' +
                    '<li  class="page-item">' +
                    '<a class="page-link" href="#" >…</a>' +
                    '</li>' +
                    '<li  class="page-item">' +
                    '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", (parseInt(pagination.buttonsCount) - 3)) + '" >' + (parseInt(pagination.buttonsCount) - 2) + '</a>' +
                    '</li  class="page-item">' +
                    '<li>' +
                    '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", (parseInt(pagination.buttonsCount) - 2)) + '" >' + (parseInt(pagination.buttonsCount) - 1) + '</a>' +
                    '</li>' +
                    '<li  class="page-item">' +
                    '<a  class="page-link" href="' + updateURLParameter(window.location.href, "page", (parseInt(pagination.buttonsCount) - 1)) + '" >' + pagination.buttonsCount + '</a>' +
                    '</li>';
            }
        } else {
            for (i = 0; i < 5; i++) {
                if (h) {
                    href = h[0] + '&page=' + i;
                    pagButtons += '<li  class="page-item">' +
                        '<a class="page-link" href="' + updateURLParameter(window.location.href, "page", i) + '" >' + (i + 1) + '</a>' +
                        '</li>';
                } else {
                    href = location.href + '&page=' + i;
                }
            }
            if (h) {
                href = h[0] + '&page=';
            } else {
                href = location.href + '&page=';
            }
            let p_link = updateURLParameter(window.location.href, "page", (parseInt(pagination.buttonsCount) - 1));
            pagButtons += '<li  class="page-item">' +
                '<a class="page-link" href="#" >…</a>' +
                '</li>' +
                '<li>' +
                '<a class="page-link" href="' + p_link + '" >' + pagination.buttonsCount + '</a>' +
                '</li>';
        }
    } else {
        for (i = 0; i < pagination.buttonsCount; i++) {
            if (h) {
                href = h[0] + '&page=';
            } else {
                href = location.href + '&page=';
            }
            pagButtons += '<li class="page-item">' +
                '<a class="page-link" href="' + updateURLParameter(window.location.href, 'page', i) + '" >' + (i + 1) + '</a>' +
                '</li>';
        }
    }

    pagButtons += '</ul>';
    if (pagination.buttonsCount > 1) {
        $('#paginationBlock , .paginationBlock').html(pagButtons);

        let activePage = 0;
        if (getUrlParameter('page')) {
            activePage = getUrlParameter('page');
        }
        if ($('.pagination').find('a[href$="&page=' + activePage + '"]').length > 0) {
            $('.pagination').find('a[href$="&page=' + activePage + '"]').parents('li').addClass('active');
        } else {
            $('.pagination').find('a[href*="&page=' + activePage + '&"]').parents('li').addClass('active');
        }
    } else {
        $('#paginationBlock , .paginationBlock').html('');
    }

    let _active_page = getUrlParameter('page');
    $('#paginationBlock , .paginationBlock').find('.page-link[href$="page=' + _active_page + '"]').addClass('active');
}

function getUrlParameter(sParam) {
    let sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLletiables = sPageURL.split('&'),
        sParameterName,
        i;
    for (i = 0; i < sURLletiables.length; i++) {
        sParameterName = sURLletiables[i].split('=');
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

function updateUrlHash($changeState, $newVal, $url = '') {
    if ($url === '') {
        $url = location.href;
    }
    let exploaded = $url.split('#');
    let newUrl = exploaded[0];
    if (exploaded[1] !== undefined) {
        newUrl = exploaded[0] + '#' + $newVal
    }
    if ($changeState) {
        history.pushState(null, null, newUrl);
        location.href = "#item-" + $newVal;
    }
    return newUrl;
}

function setCookieIfNotExist($cookName) {
    let $cookValue = Math.random().toString(36).slice(2);
    if ($.cookie($cookName) === undefined) {
        $.cookie($cookName, $cookValue, {
            expires: 100,          //expires in 100 days
            path: '/',         //The value of the path atribute of the cookie
            //(default: path of page that created the cookie).
            domain: location.hostname, //The value of the domain attribute of the cookie
            //(default: domain of page that created the cookie).
            secure: true         //If set to true the secure attribute of the cookie
            //will be set and the cookie transmission will
            //require a secure protocol (defaults to false).
        });
    }
    return $.cookie($cookName)
}

function setLocalStorageIfNotExist($cookName) {
    let $cookValue = Math.random().toString(36).slice(2);
    if (localStorage.getItem($cookName) === null) {
        localStorage.setItem($cookName, $cookValue)
    }
    return localStorage.getItem($cookName)
}
