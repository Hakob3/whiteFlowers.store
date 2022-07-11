//datepicker min value
window.onload = function () {
    var datepicker = document.getElementById('flowersDate');

    if (datepicker) {
        var currentDate = new Date();
        datepicker.setAttribute("min", currentDate.toISOString().split("T")[0]);
        datepicker.valueAsDate = currentDate;
    }
}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function setLocalStorageIfNotExist($cookName) {
    let $cookValue = Math.random().toString(36).slice(2);
    if (localStorage.getItem($cookName) === null) {
        localStorage.setItem($cookName, $cookValue)
    }
    return localStorage.getItem($cookName)
}

initAjax();

function initAjax() {
    $.ajax({
        method: 'POST',
        data: {
            fingerprint: setLocalStorageIfNotExist('fingerprint'),
        },
        url: '/cartItemsCount',
        success: function (data) {
            $('#cart_item_count').html(data);
        }
    });

}


$('.add-in-cart').on('click', function () {
    let $thisId = $(this).data('id');
    $.ajax({
        method: 'POST',
        data: {
            fingerprint: setLocalStorageIfNotExist('fingerprint'),
            dataId: $thisId
        },
        url: '/addCartItem',
        success: function (data) {
            $('#cart_item_count').html(data);
        }
    });
})
