
/************************************ Single actions ************************************/
var clients_edit_item = function( id_item )
{
    alert('clients_edit_item : ' + id_item);
}

var clients_delete_item = function( id_item )
{
    $.ajax({
        type: "POST",
        url: "",
        data:
        {
            rt: REQUEST_TYPE_AJAX,      // request type
            rn: 'clients_delete_item',    // request name
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
            rn:         'clients_modify_batch',   // request name
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

var clients_modify_batch = function( table_name )
{
    $('#roleForm').attr('tmp_table', table_name);
    $('#roleModal').modal('show');
}

var clients_export_batch = function()
{
    alert('clients_export_batch');
}

var clients_delete_batch = function( tmp_table_name )
{
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:             REQUEST_TYPE_AJAX,      // request type
            rn:             'clients_delete_batch',   // request name
            table_name:     tmp_table_name
        },
        success: function()
        {
            location.reload();
        }
    });
}

/************************************ Global actions ************************************/
var clients_export_all = function()
{
    alert('clients_export_all');
}

var clients_delete_all = function( tmp_table_name )
{
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:             REQUEST_TYPE_AJAX,      // request type
            rn:             'clients_delete_all',     // request name
            table_name:     tmp_table_name
        },
        success: function()
        {
            location.reload();
        }
    });
}
