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
<div class="row">
    <div class="col-xs-12"><? $data['game']->getBoard()->render() ?></div>
</div>
<br/>

<!-- new -->
<div class="row">
    <div class="col-xs-12"><? $data['game']->getNews()->render() ?></div>
</div>
<br/>
<br/>
<br/>
<br/>
<div class="row">
    <div class="col-xs-10"></div>
    <div class="col-xs-1"><button type="button" class="btn btn-default" id="koth_btn_concede">Concede</button></div>
    <div class="col-xs-1"></div>
</div>
