
// Make dice images not selectable
make_unselectable($('img[name=die_image]'));

// Remove context menu from die images
$('img[name=die_image]').contextmenu( function(event) {
    event.preventDefault();
});

// When user click a dice, it switch to unselected or selected
$('img[name=die_image]').mousedown( function(event)
{
    // Unknown die or not left click: to nothing
    if (  ( $(this).attr('src') == 'page/koth/image/unknown.png' )
        ||( event.which != 1 ) )
    {
        return;
    }

    $.ajax({
        type:   "POST",
        url:    "",
        global: false,
        data: {
            rt:     REQUEST_TYPE_AJAX,
            rn:     'koth_die_change',
            id:     $(this).attr('id'),
            keep:   $(this).attr('saved_src') ? 1 : 0
        }
    });

    // Unselect die => keep it
    if ( $(this).attr('saved_src') )
    {
        // Retore previous state
        $(this).attr('src',         $(this).attr('saved_src'));
        $(this).attr('onmouseover', $(this).attr('saved_onmouseover'));
        $(this).attr('onmouseout',  $(this).attr('saved_onmouseout'));

        // Rince
        $(this).attr('saved_src',         '');
        $(this).attr('saved_onmouseover', '');
        $(this).attr('saved_onmouseout',  '');
    }
    // Select die => roll it
    else
    {
        // Saving previous state
        $(this).attr('saved_src',         $(this).attr('src') );
        $(this).attr('saved_onmouseover', $(this).attr('onmouseover') );
        $(this).attr('saved_onmouseout',  $(this).attr('onmouseout') );

        // Setting new state
        $(this).attr('src',         'page/koth/image/unselected.png');
        $(this).attr('onmouseover', 'this.src=\'page/koth/image/unselected_big.png\'' );
        $(this).attr('onmouseout',  'this.src=\'page/koth/image/unselected.png\'' );
    }
});
