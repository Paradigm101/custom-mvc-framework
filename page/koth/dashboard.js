
// Button start new game
$('#koth_btn_start').click( function (e)
{
    // No mess-up!
    e.preventDefault();

    // Launch ajax that start new game
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,  // request type
            rn:         'koth_start',       // request name
            hero:       $('#heroDropdownBtn').val(),
            hero_level: $('#heroLevelDropdownBtn').val(),
            opponent:   $('#opponentDropdownBtn').val()
        },
        success: function()
        {
            location.reload();
        }
    });
});
