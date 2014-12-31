<br/>

<!-- players -->
<div class="row">
  <div class="col-xs-6">
      <? $data['game']->getUserPlayer()->render() ?>
  </div>
  <div class="col-xs-6">
      <? $data['game']->getOtherPlayer()->render() ?>
  </div>
</div>
<br/>
<br/>

<!-- board -->
<? $data['game']->getBoard()->render() ?>
<br/>

<!-- Concede button -->
<div style="float:right;">
    <button type="button" class="btn btn-default" id="koth_btn_concede"><i class="glyphicon glyphicon-new-window"></i>&nbsp;Concede</button>
</div>
