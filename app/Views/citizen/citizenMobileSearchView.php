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
	.card-btn{
		background: #0f1151;
		border:0;
		color:#ffffff;
		width:90%;
		font-family:verdana;
		font-weight:bold;
		border-radius:20px;
		height:39px;
		transition: all 0.2s ease;
		text-align:center;
		padding:2px 12px;
	}

	.card-btn:hover{
		background:#ffffff;
		color:#0f1151;
		border:2px solid #0f1151;
	}
	.card-btn:focus{
		background:#0f1151;
		outline:0;
	}
	.panel-title{
		font-family:verdana;
		font-size:25px;
		font-weight:700;
	}
	.panel-bodys{
		background-color:white;
		padding:20px 10px 20px 10px;
		border-bottom:6px solid #7a7ee7 !important;
		border-right:6px solid #7a7ee7 !important;
		border-radius:15px;
	}
	#footer{
		height:auto;
	}
</style>
<div id="content-container" style="padding: 10px;">
	<?php

	if (isset($emp_details)) {

	?>
		<div class="panel-body panel-bodys" id="demo">
		<?php } else { ?>
			<div class="panel-heading">
				<!-- <h3 class="panel-title">Enter Unique House Number or Mobile Number</h3> -->
			</div>
			<div class="panel-body" id="demo">
			<?php } ?>

			<form id="search_form" method="GET" action="<?php echo base_url('CitizenProperty/home/'); ?>">
				<div class="row" style="background-color: white;">
					<div class="col-md-12" style="background-color: white;padding:20px 10px 20px 10px;border-bottom:6px solid #7a7ee7 !important;
		border-right:6px solid #7a7ee7 !important;
		border-radius:15px;">
						<div class="row" >
							<div class="col-sm-12" style="background-color: white;">
								<label class="col-md-3">
									PLEASE ENTER UNIQUE HOUSE NO.<span class="text-danger">*</span>
								</label>
								<div class="col-md-3 pad-btm">
									<div class="form-group">
										<input maxlength="16" type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter your 15 digit unique house number" value="<?= isset($keyword) ? $keyword : ''; ?>">
									</div>
								</div>
								<label class="col-md-3">
									MOBILE NO LAST 4 DIGIT.<span class="text-danger">*</span>
								</label>
								<div class="col-md-3 pad-btm">
									<div class="form-group">
										<input maxlength="4" type="text" id="mobile_no" name="mobile_no" class="form-control" style="height:38px;" placeholder="Enter register mobile number last 4 digit" value="<?= isset($mobile_no) ? $mobile_no : ''; ?>">
									</div>
								</div>
								<div class="col-md-4 pad-btm">
									<div class="form-group">
										<button type="submit" id="submit" class="btn btn-block card-btn">
											<i class="fa fa-search">&nbsp;SEARCH</i>
										</button>
									</div>
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
									<?php foreach ($emp_details as $key=>$owner_details) : ?>
										<tr>
											<td><?php echo $owner_details['holding_no']; ?></td>
											<td><?php echo $owner_details['new_holding_no']; ?></td>
											<td><?php echo $owner_details['owner_name']; ?></td>
											<td><?php echo $owner_details['prop_address']; ?></td>
											<td><?php 
												$mob_no_arr = explode(',', $owner_details['mobile_no']);
												$new_mob_no = array();
												foreach($mob_no_arr as $mob)
												{
													$new_mob_no[] = substr_replace($mob, 'XXXXXX', 0, 6);
												}
												echo implode(',', $new_mob_no);
											 ?></td>
											<td><?php echo $owner_details['khata_no']; ?></td>
											<td><?php echo $owner_details['plot_no']; ?></td>
											<td>
												<a class="btn btn-sm btn-primary" id="citypropId" href="<?php echo base_url('CitizenProperty/index22/' . md5($owner_details["prop_dtl_id"])); ?>">View</a>
												<!-- <a class="btn btn-sm btn-primary" id="citypropId<?=$key;?>" onclick="sendOpt('<?=md5($owner_details['prop_dtl_id']);?>','<?=($key);?>')" >View</a>
												<button style="display:none;" id = "btn<?=$key;?>"type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#my-modal<?=($key);?>">Verify OTp</button>
												<div id="my-modal<?=($key);?>" class="modal fade" role="dialog">
													<div class="modal-dialog">

														<div class="modal-content">
															<div class="modal-header" style="background-color: #25476a;">
																<button id ="model-close<?=$key;?>" type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" style="color: white;">Verify OTP <?=$owner_details['new_holding_no'];?></h4>
															</div>
															<form id="otpForm<?=$key;?>" method="post">
																<div class="modal-body">
																	<input type="hidden" class="form-control" id="prop_id<?=$key?>" name="prop_id" value="<?=md5($owner_details['prop_dtl_id']);?>">
																	<input type="hidden" class="form-control" id="type" name="type" value="Property">
																	<div class="row">
																		<label class="col-md-4 text-bold">OTP</label>
																		<div class="col-md-6 has-success pad-btm">
																			<input type="number" id="otp<?=$key?>" name="otp" class="form-control"  value="" required/>
																		</div>
																	</div>																	
																	<button type="button" onclick="verifyOtp(<?=$key;?>)" class="btn btn-primary btn-labeled" style="text-align:center;" id="schedule" name="schedule">Verify</button>
																</div>

															</form>
														</div>
													</div>
												</div> -->

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
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<?= $this->include('layout_home/footer'); ?>

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
				maxlength: 16
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
			},
			mobile_no: {
				required: true,				
				minlength: 4,
				maxlength: 4
			}

		},
		messages: {
			keyword: 'Enter max 16 digit House No.',
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
		
		location.href = "<?php echo base_url('/CitizenProperty/home') ?>"
	}

	
	
</script>
<script>
	function sendOpt(mdId,id)
	{
			try{
				$.ajax({
					type:"POST",
					url: "<?=base_url('CitizenProperty/sendAVerifyOtp');?>/"+mdId,
					dataType: "json",                    
					beforeSend: function() {
						$("#loadingDiv").show();
					},
					success:function(data){
						console.log(data);
						console.log(data?.message);
						$("#loadingDiv").hide();
						modelInfo(data?.message);
						if(data.response==true){
							$('#btn'+id).click();							                         
						}
						
					},
					error: function(jqXHR, textStatus, errorThrown) {
						$("#loadingDiv").hide();
					}
				});
			}catch (err) {
				alert(err.message);
			}
	}

	function verifyOtp(id)
	{
		prop_id = $("#prop_id"+id).val();
		otp = $("#otp"+id).val();
		$("#otp"+id).val("");
		if(otp)
		{
			try{
				$.ajax({
					type:"POST",
					url: "<?=base_url('CitizenProperty/sendAVerifyOtp');?>/"+prop_id+"?otp="+otp,
					dataType: "json",                    
					beforeSend: function() {
						$("#loadingDiv").show();
					},
					success:function(data){
						console.log(data);
						console.log(data?.message);
						$("#loadingDiv").hide();
						modelInfo(data?.message);
						if(data?.response==true){
							if(data?.isExpired || data?.isValidOtp)	
							{
								$("#model-close"+id).click();
							}
							if(data?.isValidOtp){
								$("#loadingDiv").show();
								window.location.href = ("<?php echo base_url('CitizenProperty/index22/' . md5($owner_details["prop_dtl_id"])); ?>");
							}						                         
						}
						
					},
					error: function(jqXHR, textStatus, errorThrown) {
						$("#loadingDiv").hide();
					}
				});
			}catch (err) {
				alert(err.message);
			}
		}
	}
</script>
