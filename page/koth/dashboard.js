
// Refresh monster selector 1
var refresh_monsters1 = function()
{
    // Launch ajax that refresh monster selector
    $.ajax({
        type: "POST",
        url: "",
        global: false,
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_get_monsters',
            level:      $('#opLvLBtn1').val(),
            ai:         $('#aiLvLBtn1').val(),
            monster:    $('#opBtn1').val()
        },
        success: function( data )
        {
            // Empty list
            $('#opSel1').empty();

            // Refresh monster selector
            data['monsters'].forEach(function(element)
            {
                $('#opSel1').append( '<li><a onclick="monster_change1(\''+element.name+'\');" href="#" data-value="' + element.name + '">' + element.label + '</a></li>' + "\n" );
            });
        }
    });
}

// Refresh monster selector 2
var refresh_monsters2 = function()
{
    // Launch ajax that refresh monster selector
    $.ajax({
        type: "POST",
        url: "",
        global: false,
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_get_monsters',
            level:      $('#opLvLBtn2').val(),
            ai:         $('#aiLvLBtn2').val(),
            monster:    $('#opBtn2').val()
        },
        success: function( data )
        {
            // Empty list
            $('#opSel2').empty();

            // Refresh monster selector
            data['monsters'].forEach(function(element)
            {
                $('#opSel2').append( '<li><a onclick="monster_change2(\''+element.name+'\');" href="#" data-value="' + element.name + '">' + element.label + '</a></li>' + "\n" );
            });
        }
    });
}

var monster_change1 = function(test)
{
    $('#opBtn1').html(test);
    $('#opBtn1').val(test);
};

var monster_change2 = function(test)
{
    $('#opBtn2').html(test);
    $('#opBtn2').val(test);
};

// to change label on specific dropdown menu
$('a[name=opLvLLia1]').click(function(event)
{
    // No mess-up!
    event.preventDefault();
    
    refresh_monsters1();
});

// to change label on specific dropdown menu
$('a[name=opLvLLia2]').click(function(event)
{
    // No mess-up!
    event.preventDefault();
    
    refresh_monsters2();
});

// to change label on specific dropdown menu
$('a[name=aiLvLLia1]').click(function(event)
{
    // No mess-up!
    event.preventDefault();
    
    refresh_monsters1();
});

// to change label on specific dropdown menu
$('a[name=aiLvLLia2]').click(function(event)
{
    // No mess-up!
    event.preventDefault();
    
    refresh_monsters2();
});

// Button start new game vs AI
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
            level:      $('#heroLevelDropdownBtn').val(),
            monster:    $('#opBtn1').val()
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Button start new game AI vs AI (EvE)
$('#koth_btn_start_eve').click( function (event)
{
    // No mess-up!
    event.preventDefault();

    // Launch ajax that start new game
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,  // request type
            rn:         'koth_start_eve',       // request name
            monster1:   $('#opBtn1').val(),
            monster2:   $('#opBtn2').val(),
            occurence:  $('#eveOcBtn').val()
        },
        success: function()
        {
            location.reload();
        }
    });
});
