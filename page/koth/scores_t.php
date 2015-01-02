<?php

// TBD: display both players final states
//          display who won/lose and why
//          if it's user's game, display Xp won and hero's status
$data = Koth_LIB_Game::getResults();

if ( $data->isWinner )
{
    $message    = 'You won.';
    $experience = $data->xp_winner;

    if ( $data->hp_loser <= 0 )
    {
        $message .= ' Your opponent has been beaten to death.';
    }
    if ( $data->vp_winner >= Koth_LIB_Game::getVictoryThreshold() )
    {
        $message .= ' You reached victory the threshold.';
    }
}
else
{
    $message    = "You lose.";
    $experience = $data->xp_loser;

    if ( $data->hp_loser <= 0 )
    {
        $message .= ' You have been beaten to death.';
    }
    if ( $data->vp_winner >= Koth_LIB_Game::getVictoryThreshold() )
    {
        $message .= ' Your opponent reached the victory threshold.';
    }
}
?>

<br/>

<!-- Game results -->
<h2>Game score</h2>
<h4><?= $message ?></h4>
You won <?= $experience ?> Xp

<!-- Margin -->
<br/>
<br/>

<!-- Close button -->
<div class="text-center">
    <button type="button" class="btn btn-default" id="koth_btn_close_game">Go back to dashboard</button>
</div>
