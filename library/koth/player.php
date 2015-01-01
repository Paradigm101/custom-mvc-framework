<?php

// Not really a controller cuz non-abstract
class Koth_LIB_Player
{
    // View/Model
    private $view;
    private $model;
    private $idUser;

    // IsOther: other player in this user's game
    public function __construct( $idUser, $isOther = false, $isAI = false )
    {
        $this->model = new Koth_LIB_Player_Model( $idUser, $isOther, $isAI );
        $this->view  = new Koth_LIB_Player_View();

        $this->idUser = $idUser;
    }

    public function render()
    {
        $game = new Koth_LIB_Game( $this->idUser );

        $this->view->assign('victoryThreshold',  $game->getVictoryThreshold() );
        $this->view->assign('xpDicePrice',       $game->getXpDicePrice() );
        $this->view->assign('player',            $this->model->getPlayerData() );
        $this->view->assign('heroDie',           $this->model->getHeroDie() );
        $this->view->render();
    }
}
