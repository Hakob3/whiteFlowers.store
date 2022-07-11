$(document).ready(function () {

    init();

    function init() {
        let st = getUrlParameter('status');
        if (st) {
            $('#filter-status').val(st);
        }
    }

    $('#filter-status').on('change', function () {
        let $thisVal = $(this).val();
        updateURLParameter('', 'status', $thisVal, true);
        location.reload()
    })
});
