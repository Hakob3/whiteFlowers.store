$(document).ready(function () {

    let delivery_price = 0;
    let delivery_multi_price = {};
    let orderId = 0;
    $('.cancel-item').on('click', function () {
        let $thisId = $(this).data('id');
        let $thisParent = $(this).parents('.p-item');
        $.ajax({
            method: 'POST',
            data: {
                fingerprint: setLocalStorageIfNotExist('fingerprint'),
                dataId: $thisId,
            },
            url: '/cartItemCancel',
            success: function (data) {
                if (data.success) {
                    $thisParent.remove();
                    changeTotalPriceSingle();
                }
            }
        });
    })

    $('.amount_cart_items').on('change', function () {
        let $thisId = $(this).data("count");
        let $thisVal = parseInt($(this).val());
        let $thisPrice = parseInt($(this).data("price"));
        $('.fl-item-price[data-id="' + $thisId + '"]').html($thisVal * $thisPrice + ' ');
        changeTotalPriceSingle();
    })

    $('.select-delivery-type').on('change', function () {
        let $thisId = $(this).data('id');
        let price = 0;
        if ($(this).val() === 'in') {
            price = 500;
        } else if ($(this).val() === 'out') {
            price = 1000;
        }
        if ($(this).val() === 'showroom') {
            $('tr[data-toggle-address="' + $thisId + '"]').fadeOut();
        } else {
            $('tr[data-toggle-address="' + $thisId + '"]').fadeIn();
        }
        $('.delivery-price[data-id="' + $thisId + '"]').html(price);

        $bouquets[$thisId]['deliveryPrice'] = price;
        changeDeliveryMultiPrice()
    })


    $('#multi-address-order').on('submit', function (e) {
        e.preventDefault();
        let orderItems = [];
        let customer = {
            name1: $('#flowersName1').val(),
            name2: $('#flowersName2').val(),
            email: $('#flowersEmail').val(),
            ok: $('#flowersOk:checked').length,
            phone: $('#flowersPhone').val(),
        }
        $.each($('.flower-cart'), function (key, val) {
            let cart_id = $(val).find('input[name="cart_id"]').val();
            orderItems.push({
                cart_id: cart_id,
                item_id: $(val).find('input[name="item_id"]').val(),
                date: $(val).find('input[name="date' + cart_id + '"]').val(),
                time_from: $(val).find('input[name="time' + cart_id + '"]').val(),
                time_to: $(val).find('input[name="timeTo' + cart_id + '"]').val(),
                delivery_type: $(val).find('select[name="delivery_type' + cart_id + '"]').val() === 'showroom' ? 'pickup' : 'delivery',
                deliveryWhere: $(val).find('select[name="delivery_type' + cart_id + '"]').val(),
                address: $(val).find('input[name="address' + cart_id + '"]').val(),
                nearest_m_station: $(val).find('select[name="nearest_m_station' + cart_id + '"]').val(),
                metro: $(val).find('input[name="metro' + cart_id + '"]').val(),
                toWhom: $(val).find('input[name="toWhom' + cart_id + '"]').val(),
                toWhomName: $(val).find('input[name="toWhomName' + cart_id + '"]').val(),
                cardText: $(val).find('input[name="cardText' + cart_id + '"]').val(),
            });

            if ($(val).find('input[name="variant' + cart_id + '"]:checked').length) {
                orderItems.item_id = $(val).find('input[name="variant' + orderItems.cart_id + '"]:checked').val()
            }

        })


        $.ajax({
            method: 'POST',
            data: {
                fingerprint: setLocalStorageIfNotExist('fingerprint'),
                orderItems: orderItems,
                customer: customer,
            },
            url: '/createFromMultipleCart',
            success: function (resp) {
                let data = $.parseJSON(resp);
                if (data.error) {

                    if (typeof data.error === 'object') {
                        $.each(data.error, function (key, val) {
                            if ($('#' + key).length > 0) {
                                $('#' + key).addClass('error')
                            }
                        })
                    } else {
                        alert(data.error)
                    }
                } else {
                    if (data.redirectLink) {
                        location.replace(data.redirectLink)
                    }
                }
            }
        });

    }).on('click', function () {
        $('.error').removeClass('error')
    })

    function changeTotalPriceSingle() {
        let $total = 0;
        $.each($('.fl-item-price'), function (key, val) {
            let price = parseInt($(val).html());
            $total += (price);
        });
        let delivery_price = parseInt($('#delivery-price-single').html());

        $total += (delivery_price);

        $('#totalPrice').html($total);
    }

    function changeDeliveryMultiPrice() {
        let $total = 0;
        $.each($bouquets, function (key, val) {
            $total += parseInt(val.deliveryPrice);
            $total += parseInt(val.price);
        });

        $('#totalPrice').html($total);
    }

    $('.radio-variant-single').on('change', function () {
        let $thisId = $(this).data('variantid');
        $('.fl-item-price[data-id="' + $thisId + '"]').html($(this).data('price'));
        $('#data-item' + $thisId).val($(this).val());
        changeTotalPriceSingle();
    })

    $('input[name="deliveryWhereSingle"]').on('change', function () {
        changeDeliveryPrice();
    })

    $('input[name="delivery_type"]').on('change', function () {
        if ($('#delivery_pickup:checked').length) {
            $('.addresses').fadeOut();
        } else {
            $('.addresses').fadeIn()
        }
        changeDeliveryPrice();
    })

    function changeDeliveryPrice() {
        if ($('#delivery_1:checked').length) {
            if ($('#flowersDel1:checked').length) {
                delivery_price = deliveryPriceInMKAD;
            } else {
                delivery_price = deliveryPriceOutMKAD;
            }
        } else {
            delivery_price = 0;
        }
        $('#delivery-price-single').html(delivery_price);
        changeTotalPriceSingle();
    }


    $('#singleForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('fingerprint', setLocalStorageIfNotExist('fingerprint'));

        $.ajax({
            processData: false,
            contentType: false,
            url: '/createFromSingleCart',
            type: 'post',
            data: formData,
            method: 'POST',
            success: function (resp) {
                let data = $.parseJSON(resp);
                if (data.error) {

                    if (typeof data.error === 'object') {
                        $.each(data.error, function (key, val) {
                            if ($('#' + key).length > 0) {
                                $('#' + key).addClass('error')
                            }
                        })
                    } else {
                        alert(data.error)
                    }
                } else {
                    if (data.redirectLink) {
                        location.replace(data.redirectLink)
                    }
                }
            }
        })
    }).on('click', function () {
        $('.error').removeClass('error')
    })

})
