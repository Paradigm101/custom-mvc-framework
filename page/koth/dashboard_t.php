<?php
$userData = Koth_LIB_User::getData();
$heroData = Koth_LIB_User::getHeroes();
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
<strong><?= $hero->hero_label ?></strong> - Level <?= $hero->level ?>
<div class="progress" title="<?= $hero->hero_experience . '/' . $hero->next_level_xp ?> Xp">
    <div class="progress-bar" style="width: <?= floor( $hero->hero_experience / $hero->next_level_xp * 100 ) ?>%;">
        <?= $hero->hero_experience . '/' . $hero->next_level_xp ?>
    </div>
</div>
<?
    }
?>
<hr/>

<div class="row">
<!--------------------------------------------------------------- Hero --------------------------------------------------------------->
    <!-- Hero selector -->
    <div class="col-xs-1">
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
    </div>

    <!-- Hero level selector -->
    <div class="col-xs-1">
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
    </div>

    <!-- Margin -->
    <div class="col-xs-1"></div>
    
<!--------------------------------------------------------------- Monster 1 --------------------------------------------------------------->
    <!-- Monster level selector -->
    <div class="col-xs-1">
        <div class="dropdown" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opLvLBtn1">
                Op1 Lvl&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
        <?php
            foreach ( range(1, 10) as $level )
            {
                echo '<li><a name="opLvLLia1" href="#" data-value="' . $level . '">Op1 Lvl' . $level . '</a></li>' . "\n";
            }
        ?>
            </ul>
        </div>
    </div>

    <!-- Monster AI level selector -->
    <div class="col-xs-1">
        <div class="dropdown" style="float: left">
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
        <div class="dropdown" style="float: left">
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
        <div class="dropdown" style="float: left">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="opLvLBtn2">
                Op2 Lvl&nbsp;<span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-change-label">
                <?php
                foreach ( range(1, 10) as $level )
                {
                    echo '<li><a name="opLvLLia2" href="#" data-value="' . $level . '">Op2 Lvl' . $level . '</a></li>' . "\n";
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Monster AI level selector -->
    <div class="col-xs-1">
        <div class="dropdown" style="float: left">
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
        <div class="dropdown" style="float: left">
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
        <div class="dropdown" style="float: left">
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

</div><!-- Eod of row -->

<br/>

<!--------------------------------------------------------------- Starts --------------------------------------------------------------->
<div class="text-center">
    <button type="button" class="btn btn-default" id="koth_btn_start">Start PvE</button>
    <button type="button" class="btn btn-default" id="koth_btn_start_eve">Start EvE</button>
</div>

