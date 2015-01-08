<?php

$user   = Koth_LIB_User::getData( Session_LIB::getUserId() );
$heroes = Koth_LIB_User::getHeroes( Session_LIB::getUserId() );
?>

<br/>

<!-- User -->
<div class="media">
    <div class="media-left">
        <a href="#"><img src="http://placehold.it/100" class="img-thumbnail pull-left"></a>
    </div>
    <div class="media-body">
        <strong><?= $user->name ?></strong><br>
        Level <?= $user->level ?>
        <div class="progress" style="width:700px;" title="<?= $user->experience . '/' . $user->next_level_xp ?> Xp">
            <div class="progress-bar" style="width: <?= floor( $user->experience / $user->next_level_xp * 100 ) ?>%;">
                <?= $user->experience . '/' . $user->next_level_xp ?>
            </div>
        </div>
    </div>
</div>

<!-- Margin -->
<hr/>

<!-- User's heroes -->
<div class="row">
    <?php foreach ( $heroes as $hero ) { ?>
        <div class="col-xs-6">
            <div class="media" style="margin-bottom:15px;">
                <div class="media-left">
                    <a href="#"><img src="http://placehold.it/64" class="img-thumbnail pull-left"></a>
                </div>
                <div class="media-body">
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-xs-3">
                            <strong><?= $hero->label ?></strong>
                            Level <?= $hero->level ?>
                        </div>
                        <div class="col-xs-3">
                            <?php if ( ( !Koth_LIB_Game::isQueuedInHeroPvP( Session_LIB::getUserId(), $hero->id ) )
                                     &&( !Koth_LIB_Game::isPlayingPvP( Session_LIB::getUserId() ) ) ) { ?>
                                <button type="button" class="btn btn-default" id="<?= $hero->id ?>" name="koth_btn_hero_pvp">Hero PvP</button>
                            <?php } ?>
                        </div>
                        <div class="col-xs-3">
                            <?php if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) ) { ?>
                                <button type="button" class="btn btn-default" id="<?= $hero->id ?>" name="koth_btn_adventure_pve">Adventure PvE</button>
                            <?php } ?>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                    <div style="width:350px;" class="progress" title="<?= $hero->label . ' ' . $hero->experience . '/' . $hero->next_level_xp ?> Xp">
                        <div class="progress-bar" style="width: <?= floor( $hero->experience / $hero->next_level_xp * 100 ) ?>%;">
                            <?= $hero->experience . '/' . $hero->next_level_xp ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Margin -->
<hr/>

<!--------------------------------------------------------------- Buttons ------------------------------------------------------------->
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-1">
    <?php if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) ) { ?>
        <button type="button" class="btn btn-default" id="koth_btn_random_pve">Rdm PvE</button>
    <?php } ?>
    </div>
    <div class="col-xs-1">
    <?php if ( ( !Koth_LIB_Game::isQueuedInRandomPvP( Session_LIB::getUserId() ) )
            && ( !Koth_LIB_Game::isPlayingPvP( Session_LIB::getUserId() ) ) ) { ?>
        <button type="button" class="btn btn-default" id="koth_btn_random_pvp">Rdm PvP</button>
    <?php } ?>
    </div>
    <div class="col-xs-1"></div>
    <div class="col-xs-1">
        <?php if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) ) { ?>
            <div class="dropup">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="dungBtn">
                    Dungeon&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-change-label">
                    <li><a href="#" data-value="1">Dung. 1</a></li>
                    <li><a href="#" data-value="2">Dung. 2</a></li>
                    <li><a href="#" data-value="3">Dung. 3</a></li>
                </ul>
            </div>
        <?php } ?>
    </div>
    <div class="col-xs-1">
        <?php if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) ) { ?>
            <div class="dropup">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroDungBtn">
                    Hero&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-change-label">
                <?php
                    foreach ( $heroes as $hero )
                    {
                        echo '<li><a href="#" data-value="' . $hero->id . '">' . $hero->label . '</a></li>' . "\n";
                    }
                ?>
                </ul>
            </div>
        <?php } ?>
    </div>
    <div class="col-xs-1">
    <?php if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) ) { ?>
        <button type="button" class="btn btn-default" id="koth_btn_dongeon_pve">Dongeon PvE</button>
    <?php } ?>
    </div>
    <div class="col-xs-1"></div>
    <div class="col-xs-1">
    <?php if ( !Koth_LIB_Game::isPlayingPvE( Session_LIB::getUserId() ) && ( ENV == ENV_TEST ) ) { ?>
        <button type="button" class="btn btn-default" id="koth_btn_debug_pve">Debug PvE</button>
    <?php } ?>
    </div>
    <div class="col-xs-1">
    <?php if ( !Koth_LIB_Game::isPlayingPvP( Session_LIB::getUserId() ) && ( ENV == ENV_TEST ) ) { ?>
        <button type="button" class="btn btn-default" id="koth_btn_debug_pvp">Debug PvP</button>
    <?php } ?>
    </div>
    <div class="col-xs-1">
    <?php if ( ENV == ENV_TEST ) { ?>
        <button type="button" class="btn btn-default" id="koth_btn_debug_eve">Debug EvE</button>
    <?php } ?>
    </div>
</div>

<!-- Margin -->
<hr/>

<div class="row">
<!--------------------------------------------------------------- Hero 1 --------------------------------------------------------------->

    <!-- Hero selector -->
    <div class="col-xs-2">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroBtn1">
                Hero 1&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( $heroes as $hero )
                {
                    echo '<li><a href="#" data-value="' . $hero->id . '">' . $hero->label . ' 1</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Hero level selector -->
    <div class="col-xs-2">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroLvLBtn1">
                Hero1 Lvl&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( range(1, 7) as $level )
                {
                    echo '<li><a href="#" data-value="' . $level . '">Hero1 Lvl' . $level . '</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

<!--------------------------------------------------------------- Hero 2 --------------------------------------------------------------->

    <!-- Hero selector -->
    <div class="col-xs-2">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroBtn2">
                Hero 2&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( $heroes as $hero )
                {
                    echo '<li><a href="#" data-value="' . $hero->id . '">' . $hero->label . ' 2</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Hero level selector -->
    <div class="col-xs-2">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroLvLBtn2">
                Hero2 Lvl&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( range(1, 7) as $level )
                {
                    echo '<li><a href="#" data-value="' . $level . '">Hero2 Lvl' . $level . '</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Margin -->
    <div class="col-xs-5"></div>

</div><!-- End of row -->

<!-- Margin -->
<br/>

<div class="row">
<!--------------------------------------------------------------- Monster 1 --------------------------------------------------------------->

    <!-- Monster level selector -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opLvLBtn1">
                Op1 Lvl&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( range(1, 9) as $level )
                {
                    echo '<li><a name="opLvLLia1" href="#" data-value="' . $level . '">Op1 Lvl' . $level . '</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Monster AI level selector -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="aiLvLBtn1">
                Op1 AI&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( range(0, 3) as $level )
                {
                    echo '<li><a name="aiLvLLia1" href="#" data-value="' . $level . '">Op1 AI ' . $level . '</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Monster selector -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opBtn1">
                Choose&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" id="opSel1">
            </ul>
        </div>
    </div>

    <!-- Margin -->
    <div class="col-xs-1"></div>
    
<!--------------------------------------------------------------- Monster 2 --------------------------------------------------------------->
    <!-- Monster level selector -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opLvLBtn2">
                Op2 Lvl&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
                <?php
                foreach ( range(1, 9) as $level )
                {
                    echo '<li><a name="opLvLLia2" href="#" data-value="' . $level . '">Op2 Lvl' . $level . '</a></li>' . "\n";
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Monster AI level selector -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="aiLvLBtn2">
                Op2 AI&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
            <?php
                foreach ( range(0, 3) as $level )
                {
                    echo '<li><a name="aiLvLLia2" href="#" data-value="' . $level . '">Op2 AI ' . $level . '</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Monster selector -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opBtn2">
                Choose&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu scrollable-menu" id="opSel2">
            </ul>
        </div>
    </div>

    <!-- Margin -->
    <div class="col-xs-1"></div>
    
<!--------------------------------------------------------------- EvE --------------------------------------------------------------->
    <!-- Number of game -->
    <div class="col-xs-1">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="eveOcBtn">
                EvE#&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label" id="eveOcSel">
            <?php
                foreach ( range(1, 10) as $occurence )
                {
                    echo '<li><a href="#" data-value="' . $occurence . '">EvE# ' . $occurence . '</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Margin -->
    <div class="col-xs-3"></div>
    
</div><!-- End of row -->
