<?php $session = session(); ?>
<div class="panel panel-bordered panel-dark">
    <?php $saf_dtl = $session->get('saf_dtl'); ?>
    <div class="panel-heading">
        <h3 class="panel-title">Application Details</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3 mar-top text-semibold">
                Acknowledgement No
            </div>
            <div class="col-md-9 mar-top">
                <?=$saf_dtl['saf_no']?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mar-top text-semibold">
                Application Type
            </div>
            <div class="col-md-9 mar-top">
                <?=$saf_dtl['assessment_type']?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mar-top text-semibold">
                Property Type
            </div>
            <div class="col-md-9 mar-top">
                <?=$saf_dtl['property_type']?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mar-top text-semibold">
                Ownership Type
            </div>
            <div class="col-md-9 mar-top">
                <?=$saf_dtl['ownership_type']?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mar-top text-semibold">
                Property Owner
            </div>
            <div class="col-md-9 mar-top">
                <?=$saf_dtl['owner_name']?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mar-top text-semibold">
                Address
            </div>
            <div class="col-md-9 mar-top">
                <?=$saf_dtl['prop_address']?>, 
                <?=$saf_dtl['prop_city']?>
            </div>
        </div>
    </div>
</div>