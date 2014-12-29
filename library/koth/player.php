<?php

// Not really a controller cuz non-abstract
class Koth_LIB_Player
{
    // View/Model
    private $view;
    private $model;

    // IsOther: other player in this user's game
    public function __construct( $idUser, $isOther = false )
    {
        $this->model = new Koth_LIB_Player_Model( $idUser, $isOther );
        $this->view  = new Koth_LIB_Player_View();
    }

    public function render()
    {
        $this->view->assign('player',  $this->model->getPlayerData());
        $this->view->assign('heroDie', $this->model->getHeroDie());
        $this->view->render();
    }
}
