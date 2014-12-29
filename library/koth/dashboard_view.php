<?php

class Koth_LIB_Dashboard_View extends Base_LIB_View
{
    public function render()
    {
        $data = $this->getData();

        $toDisplay  = "<h2>Dashboard</h2>\n";
        $toDisplay .= "<h3>{$data['userData']->user_name}</h3>\n";
        $toDisplay .= "Level : {$data['userData']->user_level}<br>\n";
        $toDisplay .= "Experience : {$data['userData']->user_experience} / {$data['userData']->next_level_xp}<br>\n";
        $toDisplay .= "<br/>\n";
        $toDisplay .= "<hr/>\n";
        $toDisplay .= "<br/>\n";
        
        foreach ($data['heroesData'] as $hero)
        {
            $toDisplay .= "<h4>{$hero->hero_label}</h4>\n";
            $toDisplay .= "Level : {$hero->hero_level}<br>\n";
            $toDisplay .= "Experience : {$hero->hero_experience} / {$hero->next_level_xp}<br>\n";
        }

        echo $toDisplay;
    }
}
