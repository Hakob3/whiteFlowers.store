$(document).ready(function () {
    let delivery_price = 0;
    let orderId = 0;

    if ($('#flowersPhone').length) {
        var phoneMask = IMask(
            document.getElementById('flowersPhone'), {
                mask: '+{7}(000)000-00-00'
            });
    }




    $('input[name="delivery_type"]').on('change', function () {
        if ($('#delivery_pickup:checked').length) {
            $('.addresses').fadeOut();

        } else {
            $('.addresses').fadeIn()
        }
        changeDeliveryPrice()
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
        $('#deliveryPrice').html(delivery_price);
    }

    $('input[name="deliveryWhere"]').on('change', function () {
        changeDeliveryPrice()
    })

    $('input[name="variant"]').on('change', function () {
        bouquetPrice = $(this).data('price');
        $('#bouquetPrice').html(bouquetPrice);
    })

    $('#flowerOrderForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('fingerprint', setLocalStorageIfNotExist('fingerprint'));

        $.ajax({
            processData: false,
            contentType: false,
            url: '/createOrder',
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
