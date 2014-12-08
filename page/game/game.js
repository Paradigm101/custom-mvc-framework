
// Start with one dice on each pool
var black_reserve = 5;
var black_tempo   = 0;

// Make dice images not selectable
make_unselectable($('img[name=dice_image]'));

// Remove context menu from dice images
$('img[name=dice_image]').contextmenu( function(event) {
    event.preventDefault();
});

// When user click a dice image from the reserve pool
$('img[name=dice_image]').mousedown( function(event) {

    // Get global attributes
    var reserve = window[$(this).attr('color') + '_reserve'];
    var tempo   = window[$(this).attr('color') + '_tempo'];
    
    var is_reserve  = ( $(this).attr('pool') == 'reserve' );
    var is_tempo    = ( $(this).attr('pool') == 'tempo' );
    var left_click  = ( event.which == 1 );
    var right_click = ( event.which == 3 );

    // left click on reserve or right click on tempo: Add a dice to reserve => remove one from tempo
    if ( ( ( left_click && is_reserve ) || ( right_click && is_tempo ) ) && ( tempo > 0 ) ) {

        tempo--;
        reserve++;
    }
    // left click on tempo or right click on reserve: Add a dice to tempo => remove one from reserve
    else if ( ( ( left_click && is_tempo ) || ( right_click && is_reserve ) ) && ( reserve > 0 ) ) {
        
        tempo++;
        reserve--;
    }

    // Modify pools and images if needed
    if ( window[$(this).attr('color') + '_reserve'] != reserve ) {
        
        $('img[name=dice_image][pool=reserve]').attr('src', 'page/game/image/black dice ' + reserve + ' no_shadow.png');
        $('img[name=dice_image][pool=tempo]').attr('src', 'page/game/image/black dice ' + tempo + ' no_shadow.png');

        // Set global attributes
        window[$(this).attr('color') + '_reserve'] = reserve;
        window[$(this).attr('color') + '_tempo']   = tempo;
    }
});
