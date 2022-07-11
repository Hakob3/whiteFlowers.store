$(document).ready(function () {
    $('#edit-flower-form').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            processData: false,
            contentType: false,
            url: '/editFlower',
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
    })
})