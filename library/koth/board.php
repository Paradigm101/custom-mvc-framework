<?php

// TBD: PvP AND player is not active
class Koth_LIB_Board
{
    private $model;
    private $view;
    private $idUser;

    public function __construct( $idUser )
    {
        $this->model  = new Koth_LIB_Board_Model( $idUser );
        $this->view   = new Koth_LIB_Board_View();
        $this->idUser = $idUser;
    }

    public function render()
    {
        $step     = $this->model->getStep();
        $isActive = $this->model->isActive();

        if ( ( $step == KOTH_STEP_AFTER_ROLL_3 )
          && $isActive )
        {
            Page_LIB::addJavascript($this->getScriptForAck());
        }

        if ( ( ( $step == KOTH_STEP_AFTER_ROLL_1 ) || ( $step == KOTH_STEP_AFTER_ROLL_2 ) || ( $step == KOTH_STEP_START ) )
          && $isActive )
        {
            Page_LIB::addJavascript($this->getScriptForRoll());
        }

        $this->view->assign('step',          $step );
        $this->view->assign('is_active',     $isActive );
        $this->view->assign('activeDice',    $this->model->getDice());
        $this->view->assign('nonActiveDice', $this->model->getDice( true ));
        $this->view->assign('rollable',      $this->model->canUserRoll());
        $this->view->render();
    }

    private function getScriptForRoll()
    {
        return <<<EOD
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

    private function getScriptForAck()
    {
        return <<<EOD
$('#koth_btn_news_ack').click( function (e)
{
    e.preventDefault();
    
    $.ajax({
        type: "POST",
        url: "",
        data: {
            rt: REQUEST_TYPE_AJAX,  // request type
            rn: 'koth_ack_end'          // request name
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
