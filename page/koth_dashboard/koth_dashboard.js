
// Random PvE
$('#koth_btn_random_pve').click( function (event)
{
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_play',
            isRandom:   1,
            isPvE:      1
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Random PvP
$('#koth_btn_random_pvp').click( function (event)
{
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_play',
            isRandom:   1,
            isPvP:      1
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Debug PvE
$('#koth_btn_debug_pve').click( function (event)
{
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_play',
            isDebug:    1,
            isPvE:      1,
            id_hero:    $('#heroBtn1').val(),
            level:      $('#heroLvLBtn1').val(),
            id_monster: $('#opBtn1').val()
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Debug EvE
$('#koth_btn_debug_eve').click( function (event)
{
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:             REQUEST_TYPE_AJAX,
            rn:             'koth_play',
            isDebug:        1,
            isEvE:          1,
            id_monster1:    $('#opBtn1').val(),
            id_monster2:    $('#opBtn2').val(),
            occurence:      $('#eveOcBtn').val()
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Debug PvP
$('#koth_btn_debug_pvp').click( function (event)
{
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_play',
            isDebug:    1,
            isPvP:      1,
            id_hero1:   $('#heroBtn1').val(),
            level1:     $('#heroLvLBtn1').val(),
            id_hero2:   $('#heroBtn2').val(),
            level2:     $('#heroLvLBtn2').val()
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Hero PvP
$('[name=koth_btn_hero_pvp]').click(function(event)
{
    event.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt:         REQUEST_TYPE_AJAX,
            rn:         'koth_play',
            isPvP:      1,
            isHero:     1,
            id_hero:    $(this).attr('id')
        },
        success: function()
        {
            location.reload();
        }
    });
});

// Refresh monster selector 1
var refresh_monsters1 = function()
{
    // Launch ajax that refresh monster selector
    $.ajax({
        type: "POST",
        url: "",
        global: false,
        data: {
            rt:     REQUEST_TYPE_AJAX,
            rn:     'koth_get_monsters',
            level:  $('#opLvLBtn1').val(),
            ai:     $('#aiLvLBtn1').val()
        },
        success: function( data )
        {
            // Empty list
            $('#opSel1').empty();

            // Refresh monster selector
            data['monsters'].forEach(function(element)
            {
                $('#opSel1').append( '<li><a onclick="monster_change1('+element.id+',\''+element.label+'\');" href="#" data-value="' + element.id + '">' + element.label + '</a></li>' + "\n" );
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
            rt:     REQUEST_TYPE_AJAX,
            rn:     'koth_get_monsters',
            level:  $('#opLvLBtn2').val(),
            ai:     $('#aiLvLBtn2').val()
        },
        success: function( data )
        {
            // Empty list
            $('#opSel2').empty();

            // Refresh monster selector
            data['monsters'].forEach(function(element)
            {
                $('#opSel2').append( '<li><a onclick="monster_change2('+element.id+',\''+element.label+'\');" href="#" data-value="' + element.id + '">' + element.label + '</a></li>' + "\n" );
            });
        }
    });
}

var monster_change1 = function(id, label)
{
    $('#opBtn1').html(label);
    $('#opBtn1').val(id);
};

var monster_change2 = function(id, label)
{
    $('#opBtn2').html(label);
    $('#opBtn2').val(id);
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
