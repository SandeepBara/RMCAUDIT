<?= $this->include('layout_mobi/header');?>
<style>
    .error {
        color: red;
    }
</style>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li class="active">Water Harvesting Declaration Form</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Water Harvesting Declaration</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form id="form_id" method="post">
                                    <input type="hidden" id="water_harvesting_declaration_mail_inbox_id" name="water_harvesting_declaration_mail_inbox_id" value="<?=$water_harvesting_declaration_mail_inbox_id;?>" />
                                    <input type="hidden" id="water_hrvesting_declaration_dtl_id" name="water_hrvesting_declaration_dtl_id" value="<?=$id;?>" />
                                    <input type="hidden" id="subject" name="subject" value="<?=$subject;?>" />
                                    <div class="row">
                                        <label class="col-md-6"><b>Application No.</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$water_hrvting_application_no;?>
                                        </div>
                                    </div>
                                    <div class="row mar-btn">
                                        <label class="col-md-6"><b>Does Completion of Water Harvesting is done before 31-03-2017?</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$done_before_17_wh;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>15 Digits Holding No./ SAF No.</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$holding_saf_sam_no;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Name</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$owner_name;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Guardian Name</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$guardian_name;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Ward No.</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$ward_no;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Name of Building and Address</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$prop_address;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Mobile No.</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$mobile_no;?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Date of Completion of Water Harvesting Structure</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <?=$water_harvesting_completion_date;?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="water_harvesting_form"><b>Water Harvesting Declaration Form</b></label>
                                        <div id="water_harvesting_form">
                                            <!-- <embed src="<?=base_url();?>/<?="writable/uploads/";?><?=$water_harvesting_form;?>" width="100%" height="400px" />      -->
                                            <embed src="<?=base_url();?>/getImageLink.php?path=<?=$water_harvesting_form;?>" width="100%" height="400px" />                                            

                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <label class="col-md-6"><b>Upload Water Harvesting Image</b></label>
                                        <div class="col-md-3 pad-btm">
                                            <!-- <img src="<?=base_url();?>/<?="writable/uploads/";?><?=$water_harvesting_img;?>" class="img img-thumbnail" /> -->
                                            <img src="<?=base_url();?>/getImageLink.php?path=<?=$water_harvesting_img;?>" class="img img-thumbnail" />

                                        </div>
                                    </div>
                                    <hr />
                                    <?php if ($done_before_17_wh=="YES") { ?>
                                        <div class="row cl_rmc_date_file hidden">
                                            <label class="col-md-6"><b>Application Date</b></label>
                                            <div class="col-md-3 pad-btm">
                                                <?=$rmc_recommended_application_date;?>
                                            </div>
                                        </div>
                                        <div class="form-group cl_rmc_date_file hidden">
                                            <label for="water_harvesting_form"><b>RMC Recommended File</b></label>
                                            <div id="water_harvesting_form">
                                                <embed src="<?=base_url();?>/<?="writable/uploads/";?><?=$rmc_recommended_file;?>" width="100%" height="400px" />                                            
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr />
                                    <div class="row mar-btn">
                                        <div class="col-md-12 pad-btm text-center">
                                            <div class="radio">
                                                <input type="radio" id="verify_status_verify" name="verify_status" class="magic-radio" value="VERIFY" checked />
                                                <label for="verify_status_verify">VERIFY</label>

                                                <input type="radio" id="verify_status_rejected" name="verify_status" class="magic-radio" value="REJECTED" />
                                                <label for="verify_status_rejected">REJECTED</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><b>Enter Remarks</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-12 pad-btm">
                                            <textarea id="msg_body" name="msg_body" class="form-control" placeholder="Remarks"></textarea>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-md-12 text-center">  
                                            <button type="submit" class="btn btn-success" id="btn_submit">SUBMIT</button>
                                            <button type="submit" class="btn btn-success hidden" id="btn_view">PLEASE WAIT...</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_mobi/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script type="text/javascript">
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
$("#btn_submit").click(function() {
    if ($("#msg_body").val()!="") {
        $("#btn_submit").addClass("hidden");
        $("#btn_view").removeClass("hidden");
    }
});
$(document).ready(function() {
    $("#form_id").validate({
        rules: {
            msg_body: {
                required: true,
            },
        }
    });
});
</script>