<?php
// Init
$message = '';
$button  = '';

// Buttons
$rollBtn = '<button type="button" class="btn btn-default" id="koth_btn_roll">' . "\n"
            . '<i class="glyphicon glyphicon-share"></i>&nbsp;Roll' . "\n"
         . '</button>' . "\n";
$ackBtn = '<button type="button" class="btn btn-default" id="koth_btn_news_ack" >' . "\n"
            . '<i class="glyphicon glyphicon-check"></i>&nbsp;End turn' . "\n"
        . '</button>' . "\n";

switch ( Koth_LIB_Game::getStep() )
{
    case KOTH_STEP_START:
        $message = Koth_LIB_Die::displayDice( Koth_LIB_Die::getDice( false /* non-Active */ ), false /* non-Rollable */ ) . ALL_EOL
             . '<div class="text-center" style="font-size: 20px;">Previous player\'s results</div>';
        $button  = $rollBtn;
        break;

    case KOTH_STEP_AFTER_ROLL_1:
        $message = 'Two rolls left';
        $button  = $rollBtn;
        break;

    case KOTH_STEP_AFTER_ROLL_2:
        $message = 'One roll left';
        $button  = $rollBtn;
        break;

    case KOTH_STEP_AFTER_ROLL_3:
        $message = 'End of turn';
        $button  = $ackBtn;
        break;
}

// Remove button for non-active user
if ( !Koth_LIB_Game::isUserActive() )
{
    $button  = '';
}
?>

<br/>

<!-- players -->
<div class="row">
  <div class="col-xs-6">
      <? Koth_LIB_Player::render() ?>
  </div>
  <div class="col-xs-6">
      <? Koth_LIB_Player::render( true /* isOtherUser */ ) ?>
  </div>
</div>

<!-- margin -->
<br/>
<br/>

<!-- board -->
<?= Koth_LIB_Die::displayDice( Koth_LIB_Die::getDice(), Koth_LIB_Game::canUserRoll() ) ?>

<!-- button -->
<div class="text-center"><?= $button ?></div>

<!-- margin -->
<br/>
<br/>
<br/>

<!-- message -->
<div class="text-center" style="font-size: 20px;height: 130px;"><?= $message ?></div>

<!-- margin -->
<br/>

<!-- Concede button -->
<div style="float:right;">
    <button type="button" class="btn btn-default" id="koth_btn_concede"><i class="glyphicon glyphicon-new-window"></i>&nbsp;Concede</button>
</div>
