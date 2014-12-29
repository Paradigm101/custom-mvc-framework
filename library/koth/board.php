<?php

class Koth_LIB_Board
{
    private $model;
    private $view;

    // TBD: PvP AND player is not active
    public function __construct( $idUser )
    {
        $this->model = new Koth_LIB_Board_Model( $idUser );
        $this->view  = new Koth_LIB_Board_View();
    }

    public function render()
    {
        $this->view->assign('dice',     $this->model->getDice());
        $this->view->assign('rollable', $this->model->canUserRoll());
        $this->view->render();
    }
}
