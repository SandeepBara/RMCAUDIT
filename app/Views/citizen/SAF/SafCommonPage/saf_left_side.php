<div class="list-group bord-no">
    <?php $url = uri_string(true); $url = explode("/", $url)[1];  ?>
    <div id="sidebarmenu">
        <a href="<?=base_url();?>/CitizenDtl/my_application" class="list-group-item">
        Application Details
		</a>
        
        <a href="<?=base_url();?>/CitizenDtl/document_app" class="list-group-item">
            <?php if(isset($payment_status) && $payment_status == 1){  ?>Documents<?php }else{ echo "Document Upload and payment"; }?>
        </a>
        <?php if(isset($payment_status) && $payment_status == 1){  ?>
        <a href="<?=base_url();?>/CitizenDtl/citizen_saf_payment_details" class="list-group-item">
            Payment Details
        </a>
        <?php }?>
        <a href="<?=base_url();?>/CitizenDtl/safLogout" class="list-group-item">
            Log Out
        </a>
    </div>
</div>