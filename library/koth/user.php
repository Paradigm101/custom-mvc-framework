<?php

abstract class Koth_LIB_User
{
    static private $model;

    static private function getModel()
    {
        if ( !static::$model )
        {
            static::$model = new Koth_LIB_User_Model();
        }
        
        return static::$model;
    }

    static public function getData( $idUser )
    {
        return static::getModel()->getData( $idUser );
    }

    static public function getHeroes( $idUser )
    {
        return static::getModel()->getHeroes( $idUser );
    }
}
