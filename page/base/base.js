
$(document).ajaxStart(function () {
    $('#ajaxModal').modal('show');
});

$(document).ajaxStop(function () {
    $('#ajaxModal').modal('hide');
});
