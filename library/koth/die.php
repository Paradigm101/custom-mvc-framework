<?php

class Koth_LIB_Die
{
    private $id;
    private $name;
    private $label;
    private $picture;

    public function __construct( $die = null )
    {
        $this->id      = $die->id;
        $this->name    = $die->name;
        $this->label   = $die->label;
        $this->picture = 'page/koth/image/' . $die->picture;
    }

    public function display()
    {
        // Make image bigger on mouse over (not for unknown)
        $onMouseOver = '';
        if ( $this->name != 'unknown' )
        {
            $bigPicture = explode( '.', $this->picture );
            $bigPicture[0] .= '_big';
            $bigPicture = implode('.', $bigPicture);

            $onMouseOver .= 'onmouseover="this.src=\'' . $bigPicture . '\'" 
                             onmouseout="this.src=\'' . $this->picture . '\'"';
        }

        $toDiplay = '<img id="' . $this->id . '"
                          name="die_image"
                          class="unselectable" 
                          title="Click to keep or re-roll this die"
                          alt="' . $this->label . '"
                          src="' . $this->picture . '"
                          ' . $onMouseOver . ' />';

        return $toDiplay;
    }
}
