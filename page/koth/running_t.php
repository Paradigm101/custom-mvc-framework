<?php

// Player's display
if ( Koth_LIB_Game::isUserPlaying() )
{
    $idFirstPlayer  = Koth_LIB_Game::getIdUserPlayer();
    $idSecondPlayer = Koth_LIB_Game::getIdOtherPlayer();
}
else
{
    list( $idFirstPlayer, $idSecondPlayer ) = Koth_LIB_Game::getIdPlayersByRank();
}

// Init
$message = '';
$button  = '';

// Buttons
$rollBtn = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
            . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
         . '</button>' . "\n";
$ackBtn = '<button type="button" class="btn btn-default" id="koth_btn_ack_eot" >' . "\n"
            . '<i class="glyphicon glyphicon-check"></i>&nbsp;End turn' . "\n"
        . '</button>' . "\n";

// For message and button
switch ( Koth_LIB_Game::getStep() )
{
    case KOTH_STEP_START:
        $message = 'Previous results' . ALL_EOL
        . Koth_LIB_Die::displayDice( Koth_LIB_Game::getIdInactivePlayer(), false /* non-Rollable */, true /* no-Unknown */ ) . ALL_EOL;
        $button = $rollBtn;
        break;

    case KOTH_STEP_AFTER_ROLL_1:
        $message = 'Two rolls left';
        $button  = $rollBtn;
        break;

    case KOTH_STEP_AFTER_ROLL_2:
        $message = 'One roll left';
        $button  = $rollBtn;
        break;

    case KOTH_STEP_END_OF_TURN:
        $message = 'End of turn';
        $button  = $ackBtn;
        break;
}

// Remove action button for inactive user and customize message
if ( !Koth_LIB_Game::isUserActive() )
{
    $message = 'Your opponent is playing : ' . $message;
    $button  = '';
}

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

<!-- active player's dice -->
<?= Koth_LIB_Die::displayDice( Koth_LIB_Game::getIdActivePlayer(), Koth_LIB_Game::canUserRoll() ) ?>

<!-- roll/ack button -->
<div class="text-center"><?= $button ?></div>

<!-- margin -->
<br/>
<br/>
<br/>

<!-- user message -->
<div class="text-center" style="font-size: 20px;height: 130px;"><?= $message ?></div>

<!-- margin -->
<br/>

<!-- Concede button -->
<div style="float:right;">
    <button type="button" class="btn btn-default" id="koth_btn_concede"><i class="glyphicon glyphicon-new-window"></i>&nbsp;Concede</button>
</div>

<script type="text/javascript">
    var isPvP = <?= Koth_LIB_Game::isPvP() ? 1 : 0 ?>;
</script>
