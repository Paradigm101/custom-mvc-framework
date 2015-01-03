
// Roll button
$('#koth_btn_roll').click( function (e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_roll'         // request name
        },
        success: function()
        {
            location.reload();
        }
    });
});

// End of turn button
$('#koth_btn_ack_eot').click( function (e)
{
    e.preventDefault();
    
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_ack_end'          // request name
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Concede game button
$('#koth_btn_concede').click( function (e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_concede'      // request name
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Close game button
$('#koth_btn_close_game').click( function (e)
{
    // No mess-up!
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_close_game'   // request name
        },
        success: function()
        {
            location.reload();
        }
    });
});
