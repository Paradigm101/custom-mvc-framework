
/************************************ Single actions ************************************/
var users_edit_item = function( id_item )
{
    alert('users_edit_item : ' + id_item);
}

var users_delete_item = function( id_item )
{
    $.ajax({
        type: "POST",
        url: "",
        data:
        {
            rt: REQUEST_TYPE_AJAX,      // request type
            rn: 'users_delete_item',    // request name
            id: id_item
        },
        success: function()
        {
            location.reload();
        }
    });
}

/************************************ Batch actions ************************************/
// Role Modal: Give focus to first element
$('#roleModal').on('shown.bs.modal', function()
{
    $('#roleDropdownBtn').focus();
});

// to change label on specific dropdown menu
$(".dropdown-menu-change-label li a").click(function()
{
    $('#roleSubmitButton').attr('class', 'btn btn-primary');
    $(this).parents(".dropdown").find('.btn').html($(this).text() + '&nbsp;<span class="caret"></span>');
    $(this).parents(".dropdown").find('.btn').val($(this).data('value'));
});

// Role Form: Click button to submit
$('#roleSubmitButton').click( function (e)
{
    e.preventDefault();
    $('#roleForm').submit();
});

// Role form is being submitted
$('#roleForm').submit( function(e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,      // request type
            rn:         'users_modify_batch',   // request name
            role:       $('#roleDropdownBtn').val(),
            table_name: $('#roleForm').attr('tmp_table')
        },
        success: function() {

            $('#roleModal').modal('hide');
            $('#roleOkModal').modal('show');

            // After a certain time, hide modal and reload page
            setTimeout(function(){

                $('#roleOkModal').modal('hide');
                location.reload();
            }, 1000);
        }
    });
});

var users_modify_batch = function( table_name )
{
    $('#roleForm').attr('tmp_table', table_name);
    $('#roleModal').modal('show');
}

var users_export_batch = function()
{
    alert('users_export_batch');
}

var users_delete_batch = function( tmp_table_name )
{
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:             REQUEST_TYPE_AJAX,      // request type
            rn:             'users_delete_batch',   // request name
            table_name:     tmp_table_name
        },
        success: function()
        {
            location.reload();
        }
    });
}

/************************************ Global actions ************************************/
var users_export_all = function()
{
    alert('users_export_all');
}

var users_delete_all = function( tmp_table_name )
{
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:             REQUEST_TYPE_AJAX,      // request type
            rn:             'users_delete_all',     // request name
            table_name:     tmp_table_name
        },
        success: function()
        {
            location.reload();
        }
    });
}
