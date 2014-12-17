
// Script for commands page
$(function(){

    // Delete database
    $('#cmd_btn_delete_db').click( function (e) {

        // No mess-up!
        e.preventDefault();

        // Launch ajax that delete the DB
        $.ajax({
            type: "POST",
            url: "",
            data: {
                rt:     REQUEST_TYPE_AJAX,  // request type
                rn:     'delete_db'         // request name
            },
            success: function( data ) {

                // Display server's message for user
                $('#cmd_div_answer').html( data.message );
            }
        });
    });

    // Create database
    $('#cmd_btn_create_db').click( function (e) {

        // No mess-up!
        e.preventDefault();

        // Launch ajax that create the DB
        $.ajax({
            type: "POST",
            url: "",
            data: {
                rt:     REQUEST_TYPE_AJAX,  // request type
                rn:     'create_db'         // request name
            },
            success: function( data ) {

                // Display server's message for user
                $('#cmd_div_answer').html( data.message );
            }
        });
    });

    // Reset database
    $('#cmd_btn_reset_db').click( function (e) {

        // No mess-up!
        e.preventDefault();

        // Launch ajax that reset the DB
        $.ajax({
            type: "POST",
            url: "",
            data: {
                rt:     REQUEST_TYPE_AJAX,  // request type
                rn:     'reset_db'         // request name
            },
            success: function( data ) {

                // Display server's message for user
                $('#cmd_div_answer').html( data.message );
            }
        });
    });
});
