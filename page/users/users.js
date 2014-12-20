
/************************************ Single actions ************************************/
var users_edit_item = function( id_item ) {
    alert('users_edit_item : ' + id_item);
}

var users_delete_item = function( id_item ) {
    
    $.ajax({
        type: "POST",
        url: "",
        data: {
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
var users_modify_batch = function() {
    alert('users_modify_batch');
}

var users_export_batch = function() {
    alert('users_export_batch');
}

var users_delete_batch = function( tmp_table_name ) {

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:             REQUEST_TYPE_AJAX,      // request type
            rn:             'users_delete_batch',    // request name
            table_name:     tmp_table_name
        },
        success: function()
        {
            location.reload();
        }
    });
}

/************************************ Global actions ************************************/
var users_export_all = function() {
    alert('users_export_all');
}

var users_delete_all = function( tmp_table_name ) {
    
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
