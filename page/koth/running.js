
// Button roll
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

// Button acknowledge end of turn
$('#koth_btn_news_ack').click( function (e)
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
