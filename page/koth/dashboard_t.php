<?php
$userData = Koth_LIB_Game::getUserData();
$heroData = Koth_LIB_Game::getHeroData();
?>

<br/>

<!-- User -->
<strong><?= $userData->user_name ?></strong> - Level <?= $userData->user_level ?>
<div class="progress" title="<?= $userData->user_experience . '/' . $userData->next_level_xp ?> Xp">
    <div class="progress-bar" style="width: <?= floor( $userData->user_experience / $userData->next_level_xp * 100 ) ?>%;">
        <?= $userData->user_experience . '/' . $userData->next_level_xp ?>
    </div>
</div>
<hr/>

<!-- User's heroes -->
<?
    foreach ( $heroData as $hero )
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

<!-- Hero selector -->
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroDropdownBtn">
        Hero&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="heroSelector">
<?php
    foreach ( $heroData as $hero )
    {
        echo '<li><a href="#" data-value="' . $hero->hero_name . '">' . $hero->hero_label . '</a></li>' . "\n";
    }
?>
    </ul>
</div>

<!-- Hero level selector -->
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroLevelDropdownBtn">
        Hero Level&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="heroLevelSelector">
<?php
    foreach ( range(1, 7) as $level )
    {
        echo '<li><a href="#" data-value="' . $level . '">Hero Level ' . $level . '</a></li>' . "\n";
    }
?>
    </ul>
</div>

<!-- Opponent level selector -->
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opponentLevelDropdownBtn">
        Opponent Level&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="opponentLevelSelector">
<?php
    foreach ( range(1, 10) as $level )
    {
        echo '<li><a name="opponentLevelLIA" href="#" data-value="' . $level . '">Opponent Level ' . $level . '</a></li>' . "\n";
    }
?>
    </ul>
</div>

<!-- Opponent AI level selector -->
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opponentAiLevelDropdownBtn">
        Opponent AI Level&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="opponentAiLevelSelector">
<?php
    foreach ( range(0, 2) as $level )
    {
        echo '<li><a name="opponentAILevelLIA" href="#" data-value="' . $level . '">Level ' . $level . '</a></li>' . "\n";
    }
?>
    </ul>
</div>

<!-- Opponent selector -->
<div class="dropdown" style="float: left">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opponentDropdownBtn">
        Opponents&nbsp;<span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-change-label" id="opponentSelector">
        <li><a href="#" data-value="dummy">dummy</a></li>
    </ul>
</div>

<!-- Start button -->
<div class="text-center">
    <button type="button" class="btn btn-default" id="koth_btn_start">Start new game</button>
</div>
