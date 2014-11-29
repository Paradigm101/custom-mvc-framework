<br>
Probability to draw at least one 2-drop: No-keep, Average, after mulligan:
<br>
<?php
for ( $twoDropNumber = 2; $twoDropNumber <= 8; $twoDropNumber++ ) {
    echo sprintf('2-drop : [%\'01d] - proba : [%\'02d%%]', $twoDropNumber, round( 100 * ( ( 1 - $data['proba'][PROBA_GO_FIRST][PROBA_PRE_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber]
            * $data['proba'][PROBA_GO_FIRST][PROBA_POST_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] ) + ( 1 - $data['proba'][PROBA_GO_SECOND][PROBA_PRE_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber]
            * $data['proba'][PROBA_GO_SECOND][PROBA_POST_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] ) ) / 2 ) ) . ALL_EOL;
}
?>
<hr>
Having failed to draw a 2-drop pre-mulligan, Probability to draw at least one 2-drop post mulligan
<hr>
Go first, % Lost when keeping one card<br>
<?php
for ( $twoDropNumber = 2; $twoDropNumber <= 8; $twoDropNumber++ ) {
    echo sprintf('2-drop : [%\'01d] - proba : [%\'02d%%]', $twoDropNumber, round( 100 * ( $data['proba'][PROBA_GO_FIRST][PROBA_POST_MULLIGAN][PROBA_KEEP_ONE][$twoDropNumber]
            - $data['proba'][PROBA_GO_FIRST][PROBA_POST_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] ) ) ) . ALL_EOL;
}
?>
<br>
Go second, % Lost when keeping one card<br>
<?php
for ( $twoDropNumber = 2; $twoDropNumber <= 28; $twoDropNumber++ ) {
    echo sprintf('2-drop : [%\'01d] - proba : [%\'02d%%]', $twoDropNumber, round( 100 * ( $data['proba'][PROBA_GO_SECOND][PROBA_POST_MULLIGAN][PROBA_KEEP_ONE][$twoDropNumber]
            - $data['proba'][PROBA_GO_SECOND][PROBA_POST_MULLIGAN][PROBA_NO_KEEP][$twoDropNumber] ) ) ) . ALL_EOL;
}
?>
