
// Button concede game
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
