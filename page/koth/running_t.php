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
$closeBtn = '<button type="button" class="btn btn-default" id="koth_btn_close_game" >' . "\n"
            . '<i class="glyphicon glyphicon-check"></i>&nbsp;Go back to dashboard' . "\n"
        . '</button>' . "\n";

$hideConcede = false;
switch ( Koth_LIB_Game::getStep() )
{
    case KOTH_STEP_START:
        // TBD: hide empty results
        $message = Koth_LIB_Die::displayDice( Koth_LIB_Game::getIdInactivePlayer(), false /* non-Rollable */ ) . ALL_EOL
             . '<div class="text-center" style="font-size: 20px;">Previous player\'s results</div>';
        $button  = Koth_LIB_Game::isUserActive() ? $rollBtn : '';
        break;

    case KOTH_STEP_AFTER_ROLL_1:
        $message = 'Two rolls left';
        $button  = Koth_LIB_Game::isUserActive() ? $rollBtn : '';
        break;

    case KOTH_STEP_AFTER_ROLL_2:
        $message = 'One roll left';
        $button  = Koth_LIB_Game::isUserActive() ? $rollBtn : '';
        break;

    case KOTH_STEP_END_OF_TURN:
        $message = 'End of turn';
        $button  = Koth_LIB_Game::isUserActive() ? $ackBtn : '';
        break;
    
    case KOTH_STEP_GAME_FINISHED:
        $data = Koth_LIB_Game::getResults();

        // TBD: manage user not playing?
        $experience = 0;
        if ( Koth_LIB_Game::isUserPlaying() )
        {
            if ( $data->id_winner_user == Session_LIB::getUserId() )
            {
                $message    = 'You won.';
                $experience = $data->xp_winner;
            }
            else
            {
                $message    = 'You lose.';
                $experience = $data->xp_loser;
            }

            if ( $data->hp_loser <= 0 )
            {
                $message .= ' By Physical.';
            }
            if ( $data->mp_winner >= Koth_LIB_Game::getMagicThreshold() )
            {
                $message .= ' By Magical.';
            }
            if (  ( $data->hp_loser > 0 )
                &&( $data->mp_winner < Koth_LIB_Game::getMagicThreshold() ) )
            {
                $message .= ' By surrender.';
            }
        }

        $message .= ( $experience ? ALL_EOL . 'Experience points won : ' . $experience . ' XP' : '' );
        $button   = $closeBtn;
        $hideConcede = true;
        break;
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
    <button type="button" class="btn btn-default <?= $hideConcede ? 'hidden' : '' ?>" id="koth_btn_concede"><i class="glyphicon glyphicon-new-window"></i>&nbsp;Concede</button>
</div>
