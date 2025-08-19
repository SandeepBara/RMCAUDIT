<!--citizenMobileSearchView.php-->

<?= $this->include('layout_home/header'); ?>
<!--CONTENT CONTAINER-->
<style>
	.row {
		line-height: 25px;
	}
	.error{
		color: red;
	}
</style>
<div id="content-container" style="padding: 20px 100px 20px 100px;">
	<?php

	if (isset($emp_details)) {

	?>
		<!-- <a href="#" data-toggle="collapse" data-target="#demo">
					<div class="panel-heading">
						<h3 class="panel-title">Enter Unique House Number
							<i class="fa fa-arrow-down text-right" aria-hidden="true" style="float: right;margin-top: 13px;margin-right: 10px;color: aqua;font-size: larger;"></i>
						</h3>
					</div>
				</a> -->
		<div class="panel-body" id="demo">
		<?php } else { ?>
			<div class="panel-heading">
				<!-- <h3 class="panel-title">Enter Unique House Number or Mobile Number</h3> -->
			</div>
			<div class="panel-body" id="demo">

				<!--holding_owner_details Bootstrap Modal-->
            <div id="send_otp-lg-modal" class="modal fade" tabindex="1">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                            <h4 class="modal-title">Please enter the 4-digit verification code we sent via SMS:</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label text-bold">Enter OTP</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group has-warning has-feedback">
                                        <input type="text" id="otp" name="otp" class="form-control" value="" />
					                </div>
                                </div>
                                <div class="col-md-7">
                                    <label class="control-label text-bold" id="sendOtpAfterLabel"></label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_opt_resend" name="btn_opt_resend" class="btn btn-primary" value="RE-SEND" disabled>RE-SEND</button>
                            <button type="submit" id="btn_opt_verify" name="btn_opt_verify" class="btn btn-primary" value="VERIFY">VERIFY</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--End holding_owner_details Bootstrap Modal-->
			<?php } ?>

			<form id="search_form" method="POST" action="<?php echo base_url('CitizenProperty/index/'); ?>">
				<div class="row" style="background-color: white;">
					<div class="col-md-12" style="background-color: white;padding:20px 10px 20px 10px;border:0.5px solid gray;box-shadow:5px 5px 5px black">
						<div class="row" >
							<div class="col-sm-12" style="background-color: white;">
								<label class="col-md-3">PLEASE ENTER UNIQUE HOUSE NO.<span class="text-danger">*</span></label>
								<div class="col-md-4 pad-btm">
									<div class="form-group">
										<input maxlength="15" type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter your 15 digit unique house number" value="<?= isset($keyword) ? $keyword : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="col-md-5 text-right">
									OR
								</div>
							</div>
							<div class="col-sm-12">
								<label class="col-md-3">ENTER MOBILE NO.<span class="text-danger">*</span></label>
								<div class="col-md-4 pad-btm">
									<div class="form-group">
										<input maxlength="10" type="text" id="mobile_input" name="mobile_input" class="form-control" style="height:38px;" placeholder="Enter valid mobile no" value="<?= isset($mobile_input) ? $mobile_input : ''; ?>" onkeypress="return isNum(event)">
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="col-md-2 col-md-offset-4">
									<button type="submit" id="submit" name="submit" class="btn btn-block btn-mint">GO NOW</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
		</div>

		<?php if (isset($pager)) {
			if ($pager == 0) {
				echo "<h4 style='color:red;margin-left:20px'>Data Not Found !!</h4>";
			}
		}    ?>
		<!--Page content-->
		<?php if (isset($emp_details)) : ?>
			<div id="page-content" style="padding: 20px 100px 20px 100px;">

				<div class="panel panel-bordered panel-dark" style="background-color:white">
					<div class="panel-heading">
						<h3 class="panel-title">Owner Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<th scope="col">Holding No</th>
								<th scope="col">15 Digit Unique House No.</th>
								<th scope="col">Owner(s) Name</th>
								<th scope="col">Address</th>
								<th scope="col">Mobile No</th>
								<th scope="col">Khata No.</th>
								<th scope="col">Plot No.</th>
								<th scope="col">Action</th>


							</thead>
							<tbody>
								<?php if ($emp_details) : ?>
									<?php foreach ($emp_details as $owner_details) : ?>
										<tr>
											<td><?php echo $owner_details['holding_no']; ?></td>
											<td><?php echo $owner_details['new_holding_no']; ?></td>
											<td><?php echo $owner_details['owner_name']; ?></td>
											<td><?php echo $owner_details['prop_address']; ?></td>
											<td><?php echo $owner_details['mobile_no']; ?></td>
											<td><?php echo $owner_details['khata_no']; ?></td>
											<td><?php echo $owner_details['plot_no']; ?></td>
											<td>
											<!-- 	<a class="btn btn-sm btn-primary" id="citypropId" href="<?php //echo base_url('CitizenProperty/index22/' . md5($owner_details["prop_dtl_id"])); ?>">View</a> -->
												<button type="button" id="send_otp" name="send_otp" value="submitt" class="btn btn-primary" disabled>SUBMIT</button> 
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="4" style="text-align:center;color:red;"> Data Is Not Available!!</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>

					</div>
				</div>



			</div>
		<?php endif; ?>
		<!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer'); ?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
	function goBack() {
		window.history.back();
	}

	var validator = $("#search_form").validate({
		rules: {
			keyword: {
				required: function() {
					if ($('#mobile_input').val() == '' || $('#mobile_input').val() == null) {
						return true;
					} else {
						return false;
					}
				},
				maxlength: 15
			},
			mobile_input: {
				required: function() {
					if ($('#keyword').val() == '' || $('#keyword').val() == null) {
						return true;
					} else {
						return false;
					}
				},
				
				minlength: 10
			}

		},
		messages: {
			keyword: 'Enter 15 digit House No.',
			mobile_input: 'Enter 10 digit mobile no.'

		},

	});

	$("#keyword").keypress(function() {
		$("#search_form").validate().resetForm();
	});
	$("#mobile_input").keypress(function() {
		$("#search_form").validate().resetForm();
	});

	function isNum(e) {
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
	}
	$(document).keydown(function(event) {
		if (event.which == '13') {
			$("#submit").trigger("click");
			console.log('enter pressedd')
		}
	});

	function goBack() {
		
		location.href = "<?php echo base_url('/CitizenProperty/index') ?>"
	}


	$("#send_otp").click(function(){
    $("#send_otp-lg-modal").modal('show');
    	// optSendAjax();
	});
	$("#btn_opt_resend").click(function(){
	    // optSendAjax();
	});
	$("#btn_opt_verify").click(function(){
	    // optVerifyAjax();
	});
	
	function confirmCkFun(){
    try{
        if($("#ok").prop("checked") == true){
            $("#send_otp").prop("disabled", false);
        }else{
            $("#send_otp").prop("disabled", true);
        }
    } catch (err) {
        alert(err.message);
    }
}
/* $("#btn_submit").click(function() {
    $("#btn_submit").addClass("hidden");
    $("#btn_submit_temp").removeClass("hidden");    
}); */
var intervalId;
function enableResendBtnAfter30Second() {
    var i = 0;
    intervalId = setInterval(function(){ 
        i++;
        if(i=="11") {
            document.getElementById("btn_opt_resend").innerHTML = "RE-SEND";
            $("#btn_opt_resend").prop("disabled", false);
            disableResendBtnAfter30Second();
        } else {
            var ii = i;
            if(i.length==1) {
                ii = "0"+i;
            }
            document.getElementById("btn_opt_resend").innerHTML = "RE-SEND ("+ii+")";
        }
    }, 1000);
}
function disableResendBtnAfter30Second() {
  clearInterval(intervalId);
}
function optSendAjax() {
    <?php if ($has_previous_holding_no==1 && $is_owner_changed==1) { ?>
        var  mobile_no = $('input[name="mobile_no[]"]').val();
    <?php } else if ($has_previous_holding_no==0 && $is_owner_changed==0) { ?>
        var  mobile_no = $('input[name="mobile_no[]"]').val();
    <?php } else { ?>
    var mobile_no = $('input[name="prev_mobile_no[]"]').val();
    <?php } ?>
    if(mobile_no.length==10) {
        var showMobileNo = mobile_no.slice(0, 3)+"XXXX"+mobile_no.slice(7, 10);
        $.ajax({
            type:"POST",
            url: "<?=base_url();?>/CitizenSaf2/sendApplicantOptInMobile",
            dataType: "json",
            data: {
                    "mobile_no":mobile_no,
            },
            beforeSend: function() {
                $("#otp").val('');
                $("#otp").prop("disabled", true);
                $("#btn_opt_verify").html('otp sending...');
                $("#sendOtpAfterLabel").removeClass("text-success");
                $("#sendOtpAfterLabel").addClass("text-warning");
                $("#sendOtpAfterLabel").html('OTP sent to your mobile number : '+showMobileNo);
                $("#btn_opt_verify").prop("disabled", true);
                $("#btn_opt_resend").prop("disabled", true);
            },
            success:function(data){
                if(data.response==true){
                    $("#sendOtpAfterLabel").html('OTP sent to your mobile number : '+showMobileNo);
                    $("#sendOtpAfterLabel").removeClass("text-warning");
                    $("#sendOtpAfterLabel").addClass("text-success");
                    $("#btn_opt_verify").html('VERIFY');
                    $("#btn_opt_verify").prop("disabled", false);
                    $("#otp").prop("disabled", false);
                    enableResendBtnAfter30Second();
                } else {
                    alert(data.data);
                    $("#btn_opt_resend").prop("disabled", false);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                /* alert(JSON.stringify(jqXHR));
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown); */
            }
        });
    }
}
function optVerifyAjax() {
    var otp = $("#otp").val();
    $.ajax({
        type:"POST",
        url: "<?=base_url();?>/CitizenSaf2/applicantOptVerify",
        dataType: "json",
        data: {
                "otp":otp,
        },
        beforeSend: function() {
            $("#btn_opt_verify").html('otp verification...');            
            $("#btn_opt_verify").prop("disabled", true);
            $("#btn_opt_resend").prop("disabled", true);
        },
        success:function(data){
            if(data.response==true){
                $("#btn_submit").prop("disabled", false);
                $("#btn_submit").trigger("click");
                document.getElementById("btn_opt_resend").click();
                $("#btn_opt_verify").html('VERIFY');            
                $("#btn_opt_verify").prop("disabled", false);
                $("#btn_opt_resend").prop("disabled", false);
                // $("form_submit").trigger("click");
            } else {
                alert("Please re-verify otp...");
                $("#btn_opt_verify").html('VERIFY');            
                $("#btn_opt_verify").prop("disabled", false);
                $("#btn_opt_resend").prop("disabled", false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            /* alert(JSON.stringify(jqXHR));
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown); */
        }
    });
}
</script>