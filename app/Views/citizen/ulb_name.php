<?php $session = session(); ?>
<div class="panel panel-bordered panel-primary" style="margin: 10px 40px 0px 40px;">
    <div class="panel-heading">
        <h1 class="panel-title text-center"><?= strtoupper($session->get('ulb_dtl')["ulb_name"]);?></h1>
    </div>
</div>