<?php

abstract class Koth_LIB_Results
{
    static private $model;
    
    static private function getModel()
    {
        if ( !static::$model )
        {
            static::$model = new Koth_LIB_Results_Model();
        }
        
        return static::$model;
    }
    
    // Transform DB dice results to real impacts
    static private function processResults( $dbResults )
    {
        $results = array();
        foreach ($dbResults as $dbResult )
        {
            $results[ $dbResult->name ][] = $dbResult->value;
        }

        $results2 = array();
        foreach ( $results as $type => $values )
        {
            $results2[$type] = array_sum( $values );
        }

        // TBD: manage combo sets
        
        // TBD: manage combo runs?

        return $results2;
    }

    static public function getUserResults( $idUser, $isUser = true )
    {
        return static::processResults( static::getModel()->getUserResults( $idUser, $isUser ) );
    }

    static public function getPlayerResults( $idUser, $isActive = true )
    {
        return static::processResults( static::getModel()->getPlayerResults( $idUser, $isActive ) );
    }
}
