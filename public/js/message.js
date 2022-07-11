$(document).ready(function () {

    $(document).on('submit', '#message_form', function (event) {

        event.preventDefault();

        const sendData = new FormData(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: sendData,
            processData: false,
            contentType: false,
            url: '/contact/store',
            method: "POST",
            success: function (data) {
                console.log('success');
                $('#err_name').css({display: 'none'});
                $('#err_text').css({display: 'none'});
                $('#err_email').css({display: 'none'});
                $('#err_subject').css({display: 'none'});
                if (data.status === 'success' || data.status === 'false') {
                    location.reload();
                }
                if (data.success) {
                    $('.success-box').removeClass('d-none');
                    $('.success').html(data.success_msg);
                    $('.sent-message').css({display: 'block'});
                } else {
                    var errors = data.errors;
                    $.each(errors, function (key, value) {
                        $('#err_' + key).css({display: 'block', color: 'red'});
                        $('#err_' + key).html(value);
                    });
                }
            },
            error: function (data) {
                console.log('error!!');
            }
        });
    });

});
