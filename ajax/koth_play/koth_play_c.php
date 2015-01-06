<?php

abstract class Koth_Play_AJA_C extends Base_AJA_C
{
    static protected function process()
    {
        Log_LIB::trace(Url_LIB::getRequestParam('isDebug'), 'Url_LIB::getRequestParam("isDebug")');
        Log_LIB::trace(Url_LIB::getRequestParam('isPvE'), 'Url_LIB::getRequestParam("isPvE")');
        Log_LIB::trace(Url_LIB::getRequestParam('isPvP'), 'Url_LIB::getRequestParam("isPvP")');
        Log_LIB::trace(Url_LIB::getRequestParam('isEvE'), 'Url_LIB::getRequestParam("isEvE")');
        
        Log_LIB::trace(Url_LIB::getRequestParam('id_hero'), 'Url_LIB::getRequestParam("id_hero")');
        Log_LIB::trace(Url_LIB::getRequestParam('id_hero1'), 'Url_LIB::getRequestParam("id_hero1")');
        Log_LIB::trace(Url_LIB::getRequestParam('id_hero2'), 'Url_LIB::getRequestParam("id_hero2")');
        
        Log_LIB::trace(Url_LIB::getRequestParam('id_monster'), 'Url_LIB::getRequestParam("id_monster")');
        Log_LIB::trace(Url_LIB::getRequestParam('id_monster1'), 'Url_LIB::getRequestParam("id_monster1")');
        Log_LIB::trace(Url_LIB::getRequestParam('id_monster2'), 'Url_LIB::getRequestParam("id_monster2")');
        
        Log_LIB::trace(Url_LIB::getRequestParam('level'), 'Url_LIB::getRequestParam("level")');
        Log_LIB::trace(Url_LIB::getRequestParam('level1'), 'Url_LIB::getRequestParam("level1")');
        Log_LIB::trace(Url_LIB::getRequestParam('level2'), 'Url_LIB::getRequestParam("level2")');
        
        Log_LIB::trace(Url_LIB::getRequestParam('occurence'), 'Url_LIB::getRequestParam("occurence")');
        
        Log_LIB::trace(Url_LIB::getRequestParam('isRandom'), 'Url_LIB::getRequestParam("isRandom")');
        Log_LIB::trace(Url_LIB::getRequestParam('isHero'), 'Url_LIB::getRequestParam("isHero")');
        Log_LIB::trace(Url_LIB::getRequestParam('isDungeon'), 'Url_LIB::getRequestParam("isDungeon")');
        Log_LIB::trace(Url_LIB::getRequestParam('isAdventure'), 'Url_LIB::getRequestParam("isAdventure")');

        return;

        $isDebug = ( ENV == ENV_TEST ? Url_LIB::getRequestParam('isDebug') : false );
        $isPvE   = Url_LIB::getRequestParam('isPvE');
        $isPvP   = Url_LIB::getRequestParam('isPvP');
        $isEvE   = Url_LIB::getRequestParam('isEvE');

        // Debug (only in test dev)
        if ( $isDebug )
        {
            if ( $isPvE )
            {
                Koth_LIB_Game::playDebugPvE( Session_LIB::getUserId(),
                                             Url_LIB::getRequestParam('id_hero'),
                                             Url_LIB::getRequestParam('level'),
                                             Url_LIB::getRequestParam('id_monster') );
            }
            if ( $isPvP )
            {
                // Debug: id_user 1 plays against id_user 2
                $idUser1 = Session_LIB::getUserId();
                $idUser2 = 3 - $idUser1;

                Koth_LIB_Game::playDebugPvP( $idUser1,
                                             Url_LIB::getRequestParam('id_hero1'),
                                             $idUser2,
                                             Url_LIB::getRequestParam('id_hero2'),
                                             Url_LIB::getRequestParam('level1'),
                                             Url_LIB::getRequestParam('level2') );
            }
            if ( $isEvE )
            {
                Koth_LIB_Game::playDebugEvE( Url_LIB::getRequestParam('id_monster1'),
                                             Url_LIB::getRequestParam('id_monster2'),
                                             Url_LIB::getRequestParam('occurence') );
            }
        }
        else
        {
            $isRandom    = Url_LIB::getRequestParam('isRandom');
            $isHero      = Url_LIB::getRequestParam('isHero');
            $isDungeon   = Url_LIB::getRequestParam('isDungeon');
            $isAdventure = Url_LIB::getRequestParam('isAdventure');

            if ( $isPvE && $isRandom )
            {
                Koth_LIB_Game::playRandomPvE( Session_LIB::getUserId() );
            }
            if ( $isPvP && $isRandom )
            {
                Koth_LIB_Game::playRandomPvP( Session_LIB::getUserId() );
            }
            if ( $isPvP && $isHero )
            {
                Koth_LIB_Game::playHeroPvP( Session_LIB::getUserId(), Url_LIB::getRequestParam('id_hero') );
            }
            if ( $isPvE && $isDungeon )
            {
                Koth_LIB_Game::playHeroPvE( Session_LIB::getUserId(), Url_LIB::getRequestParam('id_hero') );
            }
            if ( $isPvE && $isAdventure )
            {
                Koth_LIB_Game::playAdventurePvE( Session_LIB::getUserId(), Url_LIB::getRequestParam('id_hero') );
            }
        }
    }
}
