<?php

// Set game to show: PvP for score
Koth_LIB_Game::setGame( Session_LIB::getUserId(), true /* isPvP */, true /* isForScore */ );

// Player display order
$idFirstPlayer  = Koth_LIB_Game::getIdUserPlayer();
$idSecondPlayer = Koth_LIB_Game::getIdOtherPlayer();

// Game data
$gameResults = Koth_LIB_Game::getResults();

// Init
$message    = '';
$experience = 0;

if ( $gameResults->id_winner_user == Session_LIB::getUserId() )
{
    $message    .= 'You won.';
    $experience = $gameResults->xp_winner;
}
else
{
    $message    .= 'You lose.';
    $experience = $gameResults->xp_loser;
}

if ( $gameResults->hp_loser <= 0 )
{
    $message .= ' By Physical.';
}
if ( $gameResults->mp_winner >= Koth_LIB_Game::getMagicThreshold() )
{
    $message .= ' By Magical.';
}
if (  ( $gameResults->hp_loser > 0 )
    &&( $gameResults->mp_winner < Koth_LIB_Game::getMagicThreshold() ) )
{
    $message .= ' By surrender.';
}

$message .= ( $experience ? ALL_EOL . 'Experience points won : ' . $experience . ' XP' : '' );
?>

<br/>

<!-- players -->
<div class="row">
  <div class="col-xs-6">
      <? Koth_LIB_Player::render( $idFirstPlayer ) ?>
  </div>
  <div class="col-xs-6">
      <? Koth_LIB_Player::render( $idSecondPlayer ) ?>
  </div>
</div>

<!-- margin -->
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<!-- close button -->
<div class="text-center">
    <button type="button" class="btn btn-default" id="koth_btn_close_game" id_game="<?= $gameResults->idGame ?>" >
        <i class="glyphicon glyphicon-check"></i>&nbsp;Go back to dashboard
    </button>
</div>

<!-- margin -->
<br/>
<br/>
<br/>

<!-- user message -->
<div class="text-center" style="font-size: 20px;height: 130px;"><?= $message ?></div>

<!-- margin -->
<br/>
