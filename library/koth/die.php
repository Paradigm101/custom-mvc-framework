<?php

class Koth_LIB_Die
{
    private $id;
    private $name;
    private $label;
    private $picture;

    public function __construct( $die )
    {
        Page_LIB::subscribeClassForJavascript( 'Koth_LIB_Die' );

        $this->id      = $die->id;
        $this->name    = $die->name;
        $this->label   = $die->label;
        $this->picture = 'page/koth/image/' . $die->picture;
    }

    public function display( $isRollable = true )
    {
        // Make image bigger on mouse over (not for unknown or non-rollable)
        $onMouseOver = '';
        if ( $this->name != 'unknown' && $isRollable )
        {
            $bigPicture = explode( '.', $this->picture );
            $bigPicture[0] .= '_big';
            $bigPicture = implode('.', $bigPicture);

            $onMouseOver .= 'onmouseover="this.src=\'' . $bigPicture . '\'" 
                             onmouseout="this.src=\'' . $this->picture . '\'"';
        }

        $toDiplay = '<img id="' . $this->id . '"
                          name="die_image' . ( $isRollable ? '' : '_non_rollable' ) . '"
                          class="unselectable" 
                          title="Click to keep or re-roll this die"
                          alt="' . $this->label . '"
                          src="' . $this->picture . '"
                          ' . $onMouseOver . ' />';

        return $toDiplay;
    }

    static public function getJavascript()
    {
        return <<<EOD
// Make dice images not selectable
make_unselectable($('img[name=die_image]'));

// Remove context menu from die images
$('img[name=die_image]').contextmenu( function(event) {
    event.preventDefault();
});

// Make dice images not selectable
make_unselectable($('img[name=die_image_non_rollable]'));

// Remove context menu from die images
$('img[name=die_image_non_rollable]').contextmenu( function(event) {
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
EOD;
    }
}
