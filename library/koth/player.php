<?php

// TBD: extend this service for every user id (e.g. for spectator mode)
abstract class Koth_LIB_Player
{
    // View/Model
    static private $view;
    static private $model;

    static private function getModel()
    {
        if ( !static::$model )
        {
            static::$model = new Koth_LIB_Player_Model();
        }
        
        return static::$model;
    }

    static private function getView()
    {
        if ( !static::$view )
        {
            static::$view = new Koth_LIB_Player_View();
        }
        
        return static::$view;
    }

    static public function getResults( $idPlayer )
    {
        return static::getModel()->getResults( $idPlayer );
    }

    static public function storeResults( $results, $idActivePlayer )
    {
        static::getModel()->storeResults( $results, $idActivePlayer );
    }
    
    static public function render( $idPlayer )
    {
        static::getView()->assign('player',  static::getModel()->getPlayerData( $idPlayer ) );
        static::getView()->assign('heroDie', static::getModel()->getHeroDie( $idPlayer ) );
        static::getView()->render();
    }
}
