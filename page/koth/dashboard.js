
// Refresh opponents selector
var refresh_opponents = function()
{
    // Launch ajax that refresh opponent selector
    $.ajax({
        type: "POST",
        url: "",
        global: false,
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_get_opponents',
            level:      $('#opponentLevelDropdownBtn').val(),
            ai:         $('#opponentAiLevelDropdownBtn').val(),
            opponent:   $('#opponentDropdownBtn').val()
        },
        success: function( data )
        {
            // Empty list
            $('#opponentSelector').empty();

            // Refresh opponent selector
            data['opponents'].forEach(function(element)
            {
                $('#opponentSelector').append( '<li><a onclick="opponent_change(\''+element.name+'\');" href="#" data-value="' + element.name + '">' + element.label + '</a></li>' + "\n" );
            });
        }
    });
}

var opponent_change = function(test)
{
    $('#opponentDropdownBtn').html(test);
    $('#opponentDropdownBtn').val(test);
};

// to change label on specific dropdown menu
$('a[name=opponentLevelLIA]').click(function(event)
{
    // No mess-up!
    event.preventDefault();
    
    refresh_opponents();
});

// to change label on specific dropdown menu
$('a[name=opponentAILevelLIA]').click(function(event)
{
    // No mess-up!
    event.preventDefault();
    
    refresh_opponents();
});

// Button start new game
$('#koth_btn_start').click( function (event)
{
    // No mess-up!
    event.preventDefault();

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
