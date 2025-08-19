<!--DataTables [ OPTIONAL ]-->
<?= $this->include('layout_vertical/header'); ?>
<style>
.error{
    color: red;
}
.cusm-error{
    color: red;
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <div id="page-title">
        </div>
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Login</a></li>
            <li class="active">Change Password</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="panel-title">Change Password</h3>
                            </div>
                            <div class="col-sm-6 text-lg-right" style="padding-right: 30px; padding-top:10px;">
                                <a href="<?php echo base_url('Login/index'); ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i>Back</a>
                            </div>
                        </div>
                    </div>
                    <!--Horizontal Form-->
                    <form id="myform" class="form-horizontal" method="post" action="<?php echo base_url('ChangePassword/changePassword'); ?>">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="old_user_pass">Old Password<span style="color:red"> *</span></label>
                                <div class="col-sm-4">
                                    <input type="password" minlength="2" maxlength="16" placeholder="Enter Old Password" id="old_user_pass" name="old_user_pass" class="form-control pr-password" value="<?= (isset($old_user_pass)) ? $old_user_pass : ""; ?>">
                                    <label id="old_user_pass-not_matched" class="cusm-error"></label>
                                    <span class="cusm-error"><?=isset($validator)?$validator->getError("password"):""?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="New Password">New Password<span style="color:red"> *</span></label>
                                <div class="col-sm-4">
                                    <input type="password" minlength="5" maxlength="16" placeholder="Enter New Password" id="new_pwd" name="new_pwd" class="form-control pr-password" value="<?= (isset($new_pwd)) ? $new_pwd : ""; ?>">
                                    <span class="cusm-error"><?=isset($validator)?$validator->getError("new_pwd"):""?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="cnfpassword">Confirm Password<span style="color:red"> *</span></label>
                                <div class="col-sm-4">
                                    <input type="password" minlength="5" maxlength="16" placeholder="Confirm Password" id="cnfpassword" name="cnfpassword" class="form-control" value="<?= (isset($cnfpassword)) ? $cnfpassword : ""; ?>">
                                    <span class="cusm-error"><?=isset($validator)?$validator->getError("cnfpassword"):""?></span>
                                </div>
                            </div>
                            <div class="form-group text-left text-mint" style=" background-color:#e6ffff; ">
                                <ol>
                                    <li>any set of characters</li>
                                    <li>at least length 5</li>
                                    <li>containing at least one lowercase letter</li>
                                    <li>and at least one uppercase letter</li>
                                    <li>and at least one number</li>
                                    <li>and at least one special characters</li>
                                </ol>
                            </div>
                        </div>
                        <div class="panel-footer text-left">
                            <button class="btn btn-primary" id="btn_changepwd" name="btn_changepwd" type="submit"><?= (isset($id)) ? "Change Password" : "Change Password"; ?></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="color: red; text-align: center;">
                                <?php
                                if (isset($error)) {
                                    foreach ($error as $value) {
                                        echo $value;
                                        echo "<br />";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                    <!--End Horizontal Form-->
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<script src="<?= base_url(); ?>/public/assets/js/md5.js"></script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
    $.validator.addMethod('passwordPolicy', function (value) { 
    return /^\S*(?=\S{5,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=.*[@#$%^\-+=()])\S*$/.test(value); 
}, 'Please enter a valid Strong Password.');
$(document).ready(function () {
    var isPasswordValidate = "";
    $('#myform').validate({ // initialize the plugin
        rules: {
            old_user_pass: {
                required: true,
            },
            new_pwd: {
                required: true,
                minlength: 5,
                passwordPolicy:true
            },
            cnfpassword: {
                required: true,
                minlength: 5,
                equalTo: "#new_pwd",
                passwordPolicy:true
            },
        },
        messages: {
            old_user_pass: {
                required: "Enter your password"
            },
            new_pwd: {
                required: "Enter new password",
                minlength: "Contain at least 5 characters (8+ recommended)"
            },
            cnfpassword: {
                required: "Enter confirm password",
                minlength: "Contain at least 5 characters (8+ recommended)",
                equalTo: "Passwords are not same"
            }
        },
        submitHandler: function (form) { // for demo
            if (isPasswordValidate=="")
                return true;
            else {
                return false;
            }
        }
    });
    $("#old_user_pass").change(function() {
        var old_user_pass = $("#old_user_pass").val();
        if(old_user_pass!="" && old_user_pass.length>1){
            try{
                $.ajax({
                    type:"POST",
                    url: "<?= base_url(); ?>/ChangePassword/oldPassChk",
                    dataType: "json",
                    data: {
                        "old_user_pass":old_user_pass,
                    },
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            isPasswordValidate = "";
                            $("#old_user_pass-not_matched").hide();
                        } else {
                            isPasswordValidate = data.data;
                            $("#old_user_pass-not_matched").show();
                            $("#old_user_pass-not_matched").html(data.data)
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        isPasswordValidate = false;
                    }
                });
            }catch (err) {
                alert(err.message);
            }
        }
    });
});

function show_hide_meter_div(connection_type_id)
	{	
		if(connection_type_id==1)
		{
			$("#meter_div").show();
			$("#label_reading").html("Last Meter Reading (In K.L./Units) <span class='text-danger'>*</span>");
		}
		else if(connection_type_id==2)
		{
			$("#meter_div").show();
			$("#label_reading").html("Last Meter Reading (In Gallon) <span class='text-danger'>*</span>");
		}
		else
		{
			$("#meter_div").hide();
		}
	}
    function modelInfo(msg)
    {
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
            });
    }
    <?php 
        if($error=flashToast('error'))
        { 
            if(is_array($error))
            {
                foreach($error as $val)
                {
                    echo "modelInfo('".$val."');";
                }
            }
            else
            echo "modelInfo('".$error."');";
        }
        if($error=flashToast('message'))
        { 
            if(is_array($error))
            {
                foreach($error as $val)
                {
                    echo "modelInfo('".$val."');";
                }
            }
            else
            echo "modelInfo('".$error."');";
        }
    ?>

</script>