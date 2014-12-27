<?php

class Koth_LIB_Board
{
    private $model;
    private $view;

    public function __construct( $idUser )
    {
        // TBD: it's not player's turn (PvP)
        
        // Now player is active
        $this->model = new Koth_LIB_Board_Model( $idUser );
        $this->view  = new Koth_LIB_Board_View();
    }

    public function render()
    {
        // Script
        if ( $this->model->canUserRoll() )
        {
            Page_LIB::addJavascript($this->getScript());
        }

        $this->view->assign('dice',     $this->model->getDice());
        $this->view->assign('rollable', $this->model->canUserRoll());
        $this->view->render();
    }

    private function getScript()
    {
        return <<<EOD
// Roll dices
$('#koth_btn_roll').click( function (e)
{
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_roll'         // request name
        },
        success: function()
        {
            location.reload();
        }
    });
});
EOD;
    }
}
