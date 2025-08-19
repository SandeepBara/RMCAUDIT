<?= $this->include('layout_mobi/header');?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <?php if (isset($errMsg)) { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Errors</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                <?php foreach ($errMsg as $err) { ?>
                    <label class="col-sm-12 text-danger"><?=$err?></label>
                <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h3 class="panel-title">Change Password</h3>
            </div>
            <div class="panel-body">
                <form id="form" method="post" action="">                         
                    <div class="row">
                        <label class="col-sm-2" for="old_password">Old Password <span class="text-danger">*</span></label>
                        <div class="col-sm-4 pad-btm">
                            <input type="password" id="old_password" name="old_password" class="form-control" minlength="5" maxlength="16" placeholder="Enter Old Password" value="<?=(isset($old_password))?$old_password:"";?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2" for="new_password">New Password <span class="text-danger">*</span></label>
                        <div class="col-sm-4 pad-btm">
                            <input type="password" id="new_password" name="new_password" class="form-control" minlength="5" maxlength="16" placeholder="Enter New Password" value="<?=(isset($new_password))?$new_password:"";?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2" for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <div class="col-sm-4 pad-btm">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="5" maxlength="16" placeholder="Enter Confirm Password" value="<?=(isset($confirm_password))?$confirm_password:"";?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                        <button class="btn btn-primary" id="btn_change_password" name="btn_change_password" type="submit">Change Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_mobi/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script type="text/javascript">
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 10000
    });
}
<?php if($flashToast=flashToast('changePwd')){
    echo "modelInfo('".$flashToast."');";
}?>
$(document).ready( function () {
    $("#form").validate({
        rules: {
            "old_password": {
                required: true,
                minlength: 5,
                maxlength: 15,            
            },
            "new_password": {
                required: true,
                minlength: 5,
                maxlength: 15,              
            },
            "confirm_password": {
                required: true,
                minlength: 5,
                maxlength: 15,
                equalTo : "#new_password",
            }
        },
        messages: {
            'confirm_password': {
                equalTo: "confirm password does not match.",
            },
        }
    });
});
</script>