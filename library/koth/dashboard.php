<?php

class Koth_LIB_Dashboard
{
    // View/Model
    private $view;
    private $model;

    public function __construct( $idUser )
    {
        $this->model = new Koth_LIB_Dashboard_Model( $idUser );
        $this->view  = new Koth_LIB_Dashboard_View();
    }

    public function render()
    {
        $this->view->assign('userData',   $this->model->getUserData() );
        $this->view->assign('heroesData', $this->model->getHeroesData() );
        $this->view->render();
    }
}
