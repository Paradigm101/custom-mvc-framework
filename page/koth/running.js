
// Roll button
$('#koth_btn_roll').click( function (e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:     REQUEST_TYPE_AJAX,
            rn:     'koth_roll',
            isPvP:  isPvP
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
            rt:     REQUEST_TYPE_AJAX,
            rn:     'koth_ack_end',
            isPvP:  isPvP
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
            rt:     REQUEST_TYPE_AJAX,
            rn:     'koth_concede',
            isPvP:  isPvP
        },
        success: function()
        {
            location.reload();
        }
    });
});
