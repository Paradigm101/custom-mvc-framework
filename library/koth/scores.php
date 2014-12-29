<?php

class Koth_LIB_Scores
{
    // View/Model
    private $view;
    private $model;

    public function __construct( $idUser )
    {
        $this->model = new Koth_LIB_Scores_Model( $idUser );
        $this->view  = new Koth_LIB_Scores_View();
    }

    public function render()
    {
        $this->view->assign('experience', $this->model->getExperience() );
        $this->view->render();
    }
}
