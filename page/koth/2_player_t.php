<?php
$players = $data['players'];
?>

<br/>
<!-- players -->
<div class="row">
  <div class="col-xs-6"><?= $players[1]->display(); ?></div>
  <div class="col-xs-6"><?= $players[2]->display(); ?></div>
</div>
<br/>
<hr/>
<br/>
<!-- board -->
<div class="row">
  <div class="col-xs-12"><?= $data['board']->display(); ?></div>
</div>
<br/>
<hr/>
<br/>
<button type="button" class="btn btn-default" id="koth_btn_concede">Concede</button>
