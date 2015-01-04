
// Close game button
$('#koth_btn_close_game').click( function (e)
{
    // No mess-up!
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,  // request type
            rn:         'koth_close_game',  // request name
            id_game:    $(this).attr('id_game')
        },
        success: function()
        {
            location.reload();
        }
    });
});
