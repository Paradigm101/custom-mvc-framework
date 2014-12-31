<br/>
<strong><?= $data['userData']->user_name ?></strong> - Level <?= $data['userData']->user_level ?>
<div class="progress" title="<?= $data['userData']->user_experience . '/' . $data['userData']->next_level_xp ?> Xp">
    <div class="progress-bar" style="width: <?= floor( $data['userData']->user_experience / $data['userData']->next_level_xp * 100 ) ?>%;">
        <?= $data['userData']->user_experience . '/' . $data['userData']->next_level_xp ?>
    </div>
</div>
<hr/>
<?
    foreach ($data['heroesData'] as $hero)
    {
?>
<strong><?= $hero->hero_label ?></strong> - Level <?= $hero->hero_level ?>
<div class="progress" title="<?= $hero->hero_experience . '/' . $hero->next_level_xp ?> Xp">
    <div class="progress-bar" style="width: <?= floor( $hero->hero_experience / $hero->next_level_xp * 100 ) ?>%;">
        <?= $hero->hero_experience . '/' . $hero->next_level_xp ?>
    </div>
</div>
<?
    }
?>
<hr/>
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroDropdownBtn">
        Hero&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="heroModalSelector">
<?php
    foreach ( $data['heroesData'] as $hero )
    {
        echo '<li><a href="#" data-value="' . $hero->hero_name . '">' . $hero->hero_label . '</a></li>' . "\n";
    }
?>
    </ul>
</div>
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroLevelDropdownBtn">
        Hero Level&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="heroLevelModalSelector">
        <li><a href="#" data-value="1">Level 1</a></li>
        <li><a href="#" data-value="2">Level 2</a></li>
        <li><a href="#" data-value="3">Level 3</a></li>
        <li><a href="#" data-value="4">Level 4</a></li>
        <li><a href="#" data-value="5">Level 5</a></li>
    </ul>
</div>
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opponentDropdownBtn">
        Opponents&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="opponentModalSelector">
<?php
    foreach ( $data['opponentsData'] as $opponent )
    {
        echo '<li><a href="#" data-value="' . $opponent->name . '">' . $opponent->label . '</a></li>' . "\n";
    }
?>
    </ul>
</div>
<div class="text-center">
    <button type="button" class="btn btn-default" id="koth_btn_start">Start new game</button>
</div>