$(document).ready(function () {

    init()
    function init() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            data: {
                fingerprint: setLocalStorageIfNotExist('fingerprint'),
            },
            url: '/getWishListCount',
            success: function (data) {
                if (data.wish_list_count !== undefined) {
                    $('#wishlist-count').html(data.wish_list_count);
                    $('.basket').find('span').html(data.cart_count)
                }
            }
        });

    }

});
