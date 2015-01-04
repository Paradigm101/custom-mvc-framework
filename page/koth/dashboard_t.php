<?php
$user   = Koth_LIB_User::getData( Session_LIB::getUserId() );
$heroes = Koth_LIB_User::getHeroes( Session_LIB::getUserId() );
?>

<br/>

<!-- User -->
<strong><?= $user->name ?></strong> - Level <?= $user->level ?>
<div class="progress" title="<?= $user->experience . '/' . $user->next_level_xp ?> Xp">
    <div class="progress-bar" style="width: <?= floor( $user->experience / $user->next_level_xp * 100 ) ?>%;">
        <?= $user->experience . '/' . $user->next_level_xp ?>
    </div>
</div>
<hr/>

<!-- User's heroes -->
<?
    foreach ( $heroes as $hero )
    {
?>
<div class="row">
    <div class="col-xs-1">
        <strong><?= $hero->label ?></strong><br/>
        Level <?= $hero->level ?>
    </div>
    <div class="col-xs-1">
        <button type="button" class="btn btn-default" id="<?= $hero->name ?>" name="koth_btn_hero_pvp">PvP</button>
    </div>
    <div class="col-xs-10"></div>
</div>
<div style="margin-bottom:30px;" class="progress" title="<?= $hero->experience . '/' . $hero->next_level_xp ?> Xp">
    <div class="progress-bar" style="width: <?= floor( $hero->experience / $hero->next_level_xp * 100 ) ?>%;">
        <?= $hero->experience . '/' . $hero->next_level_xp ?>
    </div>
</div>
<?
    }
?>

<!-- Margin -->
<hr/>

<div class="row">
<!--------------------------------------------------------------- Hero 1 --------------------------------------------------------------->

    <!-- Margin -->
    <div class="col-xs-1"></div>

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
                    echo '<li><a href="#" data-value="' . $hero->name . '">' . $hero->label . ' 1</a></li>' . "\n";
                }
            ?>
            </ul>
        </div>
    </div>

    <!-- Hero level selector -->
    <div class="col-xs-2">
        <div class="dropup" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="heroLvLBtn1">
                Hero2 Lvl&nbsp;<span class="caret"></span>
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

    <!-- Margin -->
    <div class="col-xs-1"></div>

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
                    echo '<li><a href="#" data-value="' . $hero->name . '">' . $hero->label . ' 2</a></li>' . "\n";
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
    <div class="col-xs-2"></div>

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

<!-- Margin -->
<br/>

<!--------------------------------------------------------------- Starts --------------------------------------------------------------->
<div class="text-center">
    <button type="button" class="btn btn-default" id="koth_btn_start">Start PvE</button>
    <button type="button" class="btn btn-default" id="koth_btn_start_eve">Start EvE</button>
    <button type="button" class="btn btn-default" id="koth_btn_start_pvp">Start PvP</button>
</div>

