<?php

abstract class Koth_LIB_Die
{
    // Model
    static private $model;
    
    static private function getModel()
    {
        if ( !static::$model )
        {
            static::$model = new Koth_LIB_Die_Model();
        }
        
        return static::$model;
    }

    // TBD: manage more than 12 dice display
    static public function displayDice( $inputDice, $rollable = false )
    {
        $toDisplay = '';

        // TBD: manage more than 12 dice
        $marginLeft = floor( ( 12 - count( $inputDice ) ) / 2 );
        $marginRight = ceil( ( 12 - count( $inputDice ) ) / 2 );

        // Start row (height is set for images on hover)
        $toDisplay .= '<div class="row" style="height:80px;">';

        // Margin
        if ( $marginLeft )
        {
            $toDisplay .= '<div class="col-xs-' . $marginLeft . '"></div>';
        }

        // Display dice
        foreach ( $inputDice as $dataDie )
        {
            $toDisplay .= '<div class="col-xs-1">';
            $toDisplay .= static::display( $dataDie, $rollable );
            $toDisplay .= '</div>';
        }

        // Margin
        if ( $marginRight )
        {
            $toDisplay .= '<div class="col-xs-' . $marginRight . '"></div>';
        }
        
        // End row
        $toDisplay .= '</div>';
        
        return $toDisplay;
    }

    // TBD: manage die unselected (keep = 0)
    static private function display( $die, $rollable )
    {
        Page_LIB::subscribeClassForJavascript( 'Koth_LIB_Die' );

        $picture  = 'page/koth/image/' . $die->name . '_' . $die->value . '.png';
        $title    = '+' . $die->value . ' ' . ucfirst($die->name);

        // Manage unknown die title
        if ( $die->name == 'unknown' )
        {
            $title = 'Reroll';
        }

        // Make image bigger on mouse over (not for unknown or non-rollable)
        $onMouseOver = '';
        if ( $die->name != 'unknown' && $rollable )
        {
            $bigPicture = explode( '.', $picture );
            $bigPicture[0] .= '_big';
            $bigPicture = implode('.', $bigPicture);

            $onMouseOver .= 'onmouseover="this.src=\'' . $bigPicture . '\'" 
                             onmouseout="this.src=\'' . $picture . '\'"';
        }

        $toDiplay = '<img id="' . $die->id . '"
                          name="die_image' . ( $rollable ? '' : '_non_rollable' ) . '"
                          class="unselectable" 
                          title="' . $title . '"
                          alt="' . $die->label . ' ' . $die->value . '"
                          src="' . $picture . '"
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
    if (  ( $(this).attr('src') == 'page/koth/image/unknown_0.png' )
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
        $(this).attr('src',         'page/koth/image/unselected.png' );
        $(this).attr('onmouseover', 'this.src=\'page/koth/image/unselected_big.png\'' );
        $(this).attr('onmouseout',  'this.src=\'page/koth/image/unselected.png\'' );
    }
});
EOD;
    }
    
    static public function getDice( $isActivePlayer = true )
    {
        return static::getModel()->getDice( $isActivePlayer );
    }

    static public function getDiceNumber( $isOther = false )
    {
        return static::getModel()->getDiceNumber( $isOther );
    }
}
