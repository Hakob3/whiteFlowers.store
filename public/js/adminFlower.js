$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if ($('#variants').length > 0) {
        $('#variants').select2({})
    }
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

                if (data.success) {
                    location.reload()
                } else {
                    $.notify(data.error);
                }
            }
        })
    })
    $('#add-flower-form').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            processData: false,
            contentType: false,
            url: '/addFlower',
            type: 'post',
            data: formData,
            method: 'POST',
            success: function (resp) {
                let data = $.parseJSON(resp);

                if (data.success) {
                    location.reload()
                } else {
                    $.notify(data.error);
                }
            }
        })
    })
})