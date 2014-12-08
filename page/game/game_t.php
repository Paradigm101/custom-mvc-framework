<?php
//    foreach ( array('black'/*, 'green', 'red', 'blue', 'white', 'purple'*/) as $color ) {
//        
//        $srcImage  = 'page/game/image/black dice 0 no_shadow.png';
////        $highImage = 'page/game/image/' . $color . ' dice highlighted.png';
//        
//        echo '<div><img color="' . $color . '" '
//                    . 'name="dice_image" '
//                    . 'class="unselectable" '
//                    . 'title="Left click to add a ' . ucfirst($color) . ' dice, right click to remove a ' . ucfirst($color) . ' dice" '
//                    . 'src="' . $srcImage . '" '
////                    . 'onmouseover="this.src=\'' . $highImage . '\'" '
////                    . 'onmouseout="this.src=\'' . $srcImage . '\'" '
//                    . '/></div>';
//    }
?>
<div>Reserve<img color="black" 
                 name="dice_image" 
                 pool="reserve"
                 class="unselectable" 
                 title="Left click to add a Black dice, right click to remove a Black dice"
                 src="page/game/image/black dice 5 no_shadow.png"/></div>
 <br>
 <hr>
 <br>
<div>Tempo<img color="black" 
               name="dice_image" 
               pool="tempo"
               class="unselectable" 
               title="Left click to add a Black dice, right click to remove a Black dice"
               src="page/game/image/black dice 0 no_shadow.png"/></div>
 <br>
 <hr>
 <br>
<div class="row" style="border:solid 1px;">
  <div class="col-xs-6" style="background-color: red;height: 500px;">.col-md-6</div>
  <div class="col-xs-6" style="background-color: green;">.col-md-6</div>
</div>
<div style="border:solid 1px;">
  <div class="col-md-6" style="background-color: yellow;">.col-md-6</div>
  <div class="col-md-6" style="background-color: blue;">.col-md-6</div>
</div>