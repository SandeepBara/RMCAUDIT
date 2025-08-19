<style>
	.error {
		color: red;
	}
</style>
<?= $this->include('layout_home/header'); ?>
<style>
		#footer{
		position: absolute;
	}

</style><!--CONTENT CONTAINER-->
<div id="content-container" style="padding: 0px 0px;">

	<!--Page content-->
	<div id="page-content">
		<div class="panel">
				<button style="float: right;margin-bottom:10px;margin-right:20px !important" onclick="goBack()" class="btn btn-info">Back</button>
				<?php
				if(isset($found_status)){
					if($found_status==0){
						echo "<h4 style='color:red'>No Data Found !</h4>";
					}
				}
				if (isset($emp_details)) {
				?>
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="panel ">

								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											<div class="panel panel-bordered panel-dark">
												<div class="panel-heading">
													<h3 class="panel-title">Property</h3>
												</div>

												<div class="panel-body mar-all" style="border: 1px solid #3a444e;">
													<form action="<?php echo base_url('CitizenProperty/Citizen_confirm_payment/' . md5($emp_details["prop_dtl_id"])); ?>" method="post">
														<div class="row pad-btm">
															<div class="col-md-5">
																<b>Unique House No. </b>
															</div>
															<div class="col-md-7">
																<?php echo $emp_details['new_holding_no'] ? $emp_details['new_holding_no'] : "N/A"; ?>
															</div>
														</div>
														<div class="row pad-btm">
															<div class="col-md-5">
																<b>Owner Name </b>
															</div>
															<div class="col-md-7">
																<?php echo $emp_details['owner_name'] ? $emp_details['owner_name'] : "N/A"; ?>
															</div>
														</div>
														<div class="row pad-btm">
															<div class="col-md-5">
																<b>Address </b>
															</div>
															<div class="col-md-7">
																<?php echo $emp_details['prop_address'] ? $emp_details['prop_address'] : "N/A"; ?>
															</div>
														</div>
														<?php if (isset($demand_detail)) {
															$i = 1;
														?>
															<?php foreach ($demand_detail as $tot_demand) {
																$i == 1 ? $first_qtr = $tot_demand['qtr'] : '';
																$i == 1 ? $first_fy = $tot_demand['fy'] : '';
																$i++;
															?>
															<?php } ?>
															<div class="row pad-btm">
																<div class="col-md-2">
																	<b>Dues From</b>
																</div>
																<div class="col-md-3">
																	<?php echo $first_qtr; ?> / <?php echo $first_fy; ?>
																</div>
																<div class="col-md-2">
																	<b>Dues Upto</b>
																</div>
																<div class="col-md-3">
																	<?php echo $tot_demand['qtr']; ?> / <?php echo $tot_demand['fy']; ?>
																	<input type="hidden" class="form-control" id="due_upto_year" name="due_upto_year" value="<?php echo $tot_demand['fy']; ?>">
																	<input type="hidden" class="form-control" id="date_upto_qtr" name="date_upto_qtr" value="<?php echo $tot_demand['qtr']; ?>">
																	<input type="hidden" class="form-control" id="ful_qtr" name="ful_qtr" value="<?php echo $length; ?>">
																	<input type="hidden" class="form-control" id="total_qrt" name="total_qrt" value="<?php echo $length; ?>">
																</div>
															</div>

															<div class="row pad-btm">
																<div class="col-md-5">
																	<b>Due Amount </b>
																</div>
																<div class="col-md-7">
																	<?php echo $total_amount ? $total_amount : "N/A"; ?>
																</div>
															</div>
															<?php if (isset($tccallexit)) { ?>
																<b class="text text-danger">Your are requested to Tax Collector for payment collection.</b>
															<?php } else { ?>
																<div class="row pad-btm text-center">
																	<a href="<?php echo base_url('CitizenProperty/Citizen_confirm_payment/' . md5($emp_details["prop_dtl_id"])); ?>" class="btn btn-primary btn-rounded btn-labeled">Pay Property Tax</a>
																	<button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#shedule_tc">Schedule Tax Collector</button>
																</div>
															<?php } ?>
														<?php } else { ?>
															<div class="row pad-btm text-center">
																<b style="color:green;">No Dues</b>
															</div>
														<?php } ?>
													</form>
												</div>
											</div>

											<div class="panel panel-bordered panel-dark" id="waterPanel" style="display: block;">
												<div class="panel-heading">
													<h3 class="panel-title">Water</h3>
												</div>
												<div id="waterResultBox">
													<i class="fa fa-refresh fa-spin text text-center"></i>
												</div>
											</div>

											<div class="panel panel-bordered panel-dark" id="tradePanel" style="display: block;">
												<div class="panel-heading">
													<h3 class="panel-title">Trade</h3>
												</div>
												<div id="tradeResultBox">
													<i class="fa fa-refresh fa-spin text text-center"></i>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">Direct Link</h3>
								</div>
								<div class="panel-body">
									<div id="saf_distributed_dtl_hide_show">
										<div class="row">
											<div class="col-md-12 text-center" id="directLink">
											<div class="row pad-btm">
												<b><a href="<?php echo base_url('CitizenPropSpecialDocUpload/CitizenPropSpecialDocUpload/'. md5($emp_details["prop_dtl_id"]));?>" class="btn btn-primary btn-rounded btn-hover-primary">Concession Details Update</a></b>
												</div>
												<div class="row pad-btm">
													<b><a href="<?php echo base_url('CitizenProperty/Citizen_due_details/' . md5($emp_details["prop_dtl_id"])); ?>" class="btn btn-primary btn-rounded btn-hover-primary">View Demand Details</a></b>
												</div>
												<div class="row pad-btm">
													<b><a href="<?php echo base_url('CitizenProperty/Citizen_payment_details/' . md5($emp_details["prop_dtl_id"])); ?>" class="btn btn-primary btn-rounded btn-hover-primary">View Payment Details</a></b>
												</div>
												<div class="row pad-btm">
													<b><a href="<?php echo base_url('CitizenProperty/Citizen_property_details/' . md5($emp_details["prop_dtl_id"])); ?>" class="btn btn-primary btn-rounded btn-hover-primary">View Property Details</a></b>
												</div>
												<?php if ($emp_details["prop_type_mstr_id"]!=4 && $emp_details["new_holding_no"]!=""){ ?>
												<div class="row pad-btm">
													<b><a href="<?php echo base_url('CitizenProperty/comparativeTax/' . md5($emp_details["prop_dtl_id"])); ?>" class="btn btn-primary btn-rounded btn-hover-primary">View Comparative Demand</a></b>
												</div>
												<?php } ?>
												<?php
												if ($is_trust == 1 && $trust_type['trust_type']=='')
												{ ?>
												<div class="row pad-btm">
													<b><a href="<?php echo base_url('CitizenProperty/OnlineViewDetails/' . md5($emp_details["saf_dtl_id"])); ?>" class="btn btn-primary btn-rounded btn-hover-primary">Upload Trust Documents</a></b>
												</div>
												<?php } ?>
												<div class="row pad-btm">
													<b><?php PropCertificatList($emp_details["prop_dtl_id"]) ;?></b>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
				?>
				
		</div>
		<div id="shedule_tc" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="background-color: #25476a;">
						<button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" style="color: white;">Select date & time</h4>
					</div>
					<form action="<?=  base_url('CitizenProperty/citizen_tc_call/' . md5(isset($emp_details["prop_dtl_id"])?$emp_details["prop_dtl_id"]:'')); ?>" method="post">
						<div class="modal-body">
							<input type="hidden" class="form-control" id="ward_mst_id" name="ward_mst_id" value="<?= isset($emp_details["old_ward_mstr_id"])?$emp_details["old_ward_mstr_id"]:''; ?>">
							<input type="hidden" class="form-control" id="ward_no" name="ward_no" value="<?= isset($emp_details["ward_no"])?$emp_details["ward_no"]:''; ?>">
							<input type="hidden" class="form-control" id="prop_dtl_id" name="prop_dtl_id" value="<?= isset($emp_details["prop_dtl_id"])?$emp_details["prop_dtl_id"]:''; ?>">
							<input type="hidden" class="form-control" id="holding_no" name="holding_no" value="<?= isset($emp_details["holding_no"])?$emp_details["holding_no"]:''; ?>">
							<input type="hidden" class="form-control" id="new_holding_no" name="new_holding_no" value="<?= isset($emp_details["new_holding_no"])?$emp_details["new_holding_no"]:''; ?>">
							<input type="hidden" class="form-control" id="address" name="address" value="<?= isset($emp_details["prop_address"])?$emp_details["prop_address"]:''; ?>">
							<input type="hidden" class="form-control" id="owner_name" name="owner_name" value="<?= isset($emp_details["owner_name"])?$emp_details["owner_name"]:''; ?>">
							<input type="hidden" class="form-control" id="mobile_no" name="mobile_no" value="<?= isset($emp_details["mobile_no"])?$emp_details["mobile_no"]:''; ?>">
							<input type="hidden" class="form-control" id="type" name="type" value="Property">
							<div class="row">
								<label class="col-md-4 text-bold">Select Date</label>
								<div class="col-md-6 has-success pad-btm">
									<input type="date" id="shedule_date" name="shedule_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="" required/>
								</div>
							</div>
							<div class="row">
								<label class="col-md-4 text-bold">Select Time Slot</label>
								<div class="col-md-4 has-success pad-btm">
									<input type="radio" id="timeslot1" name="time" class="magic-radio" value="08:00 AM to 10:00 AM" required >
									<label for="timeslot1">08:00 AM to 10:00 AM</label>
								</div>
								<div class="col-md-4 has-success pad-btm">
									<input type="radio" id="timeslot2" name="time" class="magic-radio" value="10:00 AM to 12:00 PM" required>
									<label for="timeslot2">10:00 AM to 12:00 PM</label>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4 has-success pad-btm">
									<input type="radio" id="timeslot3" name="time" class="magic-radio" value="12:00 PM to 02:00 PM" required>
									<label for="timeslot3">12:00 PM to 02:00 PM</label>
								</div>
								<div class="col-md-4 has-success pad-btm">
									<input type="radio" id="timeslot4" name="time" class="magic-radio" value="02:00 PM to 04:00 PM" required>
									<label for="timeslot4">02:00 PM to 04:00 PM</label>
								</div>
								<!-- <input type="date" id="from_date" name="from_date" class="form-control" value="<?= isset($from_date)?$from_date:''; ?>" />
							</div>-->
							</div>
							<button type="submit" class="btn btn-primary btn-labeled" style="text-align:center;" id="schedule" name="schedule">Shedule</button>
						</div>

					</form>
				</div>
			</div>
		</div>
		<!--End page content-->
	</div>
	<!--END CONTENT CONTAINER-->
	<?= $this->include('layout_home/footer'); ?>
	<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
	<script>
		
		function fetchWaterData() {
			var prop_dtl_id = '<?= isset($emp_details['prop_dtl_id'])?$emp_details['prop_dtl_id']:''; ?>';
			if (prop_dtl_id != null && prop_dtl_id != null) {
				$.ajax({
					url: "<?php echo base_url("WaterUserChargeProceedPaymentCitizen/fetchWaterData"); ?>",
					type: "post", //request type,
					dataType: 'json',
					data: {
						prop_dtl_id: prop_dtl_id
					},
					success: function(result) {
						var out = '';
						console.log(result)
						if (result.status) {
							var data = result.data;
							for (let i = 0; i < data.length; i++) {
								var row = data[i];
								out += '<div class="panel panel-bordered panel-dark mar-all">\
								<div class="panel-body">\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b>Consumer No. </b>\
											</div>\
											<div class="col-md-7">' + row.consumer_no + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b>Consumer Name </b>\
											</div>\
											<div class="col-md-7">' + row.name + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b>Connection Type </b>\
											</div>\
											<div class="col-md-7">' + row.connection_type + '\
											</div>\
										</div>';
								if (row.toalPayableAmount != 0) {
									out += '<div class="row pad-btm">\
											<div class="col-md-5">\
												<b> Dues Period </b>\
											</div>\
											<div class="col-md-7">' + row.demand_from + ' to ' + row.demand_upto + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b> Dues Amount </b>\
											</div>\
											<div class="col-md-7">' + row.toalPayableAmount + '\
											</div>\
										</div>\
										<div class="row pad-btm text-center">\
											<form method="POST" >\
												<input type="hidden" name="consumer_id" value="' + row.id + '">\
												<input type="hidden" name="ward_mstr_id" value="' + row.ward_mstr_id + '">\
												<input type="hidden" name="due_from" value="' + row.demand_from + '">\
												<input type="hidden" name="month" value="' + row.demand_upto + '">\
												<input type="hidden" name="downloadReceipt" value="true">\
												<a class="btn btn-primary btn-rounded btn-labeled" id="pay" href="<?=base_url('WaterUserChargeProceedPaymentCitizen/pay_payment')?>/'+row.consumer_id_MD5+'">Pay User Charge</a>\
											</form>\
										</div>';
								} else {
									out += '<div class="row pad-btm text-center">\
										<b style="color:green;">No Dues Are Available</b>\
									</div>';
								}
								out += '</div>\
								</div>';
							}
							var directLink = '<div class="row pad-btm">\
											<b><a href="<?php echo base_url('WaterViewConsumerDetailsCitizen/index'); ?>/' + result.data[0].consumer_id_MD5 + '"  class="btn btn-lg btn-primary btn-rounded btn-hover-primary">View Water User Charge</a></b>\
										</div>';


							$("#directLink").append(directLink);

							$("#waterPanel").show();
						} else {
							out += '<div class="panel panel-bordered panel-dark mar-all">\
								<div class="panel-body">\
									<span class="text text-danger"> Not Available </span>\
								</div>\
							  </div>';
						}
						$("#waterResultBox").html(out);
					}
				});
			}
		}

		function fetchTradeData() {
			var prop_dtl_id = '<?= isset($emp_details['prop_dtl_id'])?$emp_details['prop_dtl_id']:''; ?>';
			if (prop_dtl_id != null && prop_dtl_id != '') {
				$.ajax({
					url: "<?php echo base_url("TradeCitizen/fetchTradeData"); ?>",
					type: "post", //request type,
					dataType: 'json',
					data: {
						prop_dtl_id: prop_dtl_id
					},
					success: function(result) {
						var out = '';
						console.log(result)
						if (result.status) {

							var data = result.data;
							for (let i = 0; i < data.length; i++) {
								var row = data[i];
								out += '<div class="panel panel-bordered panel-dark mar-all">\
								<div class="panel-body">\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b>License/Application No. </b>\
											</div>\
											<div class="col-md-7">' + row.applic_no + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b>Firm Name </b>\
											</div>\
											<div class="col-md-7">' + row.firm_name + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b> Application Type </b>\
											</div>\
											<div class="col-md-7">' + row.application_type + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-5">\
												<b> Owner Name </b>\
											</div>\
											<div class="col-md-7">' + row.premises_owner_name + '\
											</div>\
										</div>';
								if (row.pending_status != 5) {
									out += '<div class="row pad-btm">\
											<div class="col-md-5">\
												<b> Application Status </b>\
											</div>\
											<div class="col-md-7 text text-success">' + row.application_status + '\
											</div>\
										</div>\
										<div class="row pad-btm">\
											<div class="col-md-6">\
												<a href="<?php echo base_url("TradeCitizen/provisional"); ?>/' + row.apply_license_id_MD5 + '" class="btn btn-primary btn-rounded btn-labeled" target="blank">View Provisional License</a>\
											</div>\
											<div class="col-md-6">\
											<a href="<?php echo base_url("TradeCitizen/trade_licence_view"); ?>/' + row.apply_license_id_MD5 + '" class="btn btn-primary btn-rounded btn-labeled" target="blank">View Application</a>\
											</div>\
										</div>';
								} else {
									out += '<div class="row pad-btm">\
											<div class="col-md-5">\
												<b> Expires on </b>\
											</div>\
											<div class="col-md-7">' + row.valid_upto + '\
											</div>\
									 </div>';

									if (row.yetToBeExpire <= 30)
										out += '<div class="row pad-btm">\
											<div class="col-md-6 text text-danger">\
												<b> Expired (' + row.yetToBeExpire + ') </b>\
											</div>\
											<div class="col-md-6">\
											<a href="<?php echo base_url("TradeCitizen/applynewlicence/" . md5(2)); ?>/' + row.apply_license_id_MD5 + '" class="btn btn-primary btn-rounded btn-labeled" target="blank">Apply for renewal</a>\
											</div>\
										</div>';
									else
										out += '<div class="row pad-btm">\
											<div class="col-md-6">\
											</div>\
											<div class="col-md-6">\
											<a href="<?php echo base_url("TradeCitizen/trade_licence_view"); ?>/' + row.apply_license_id_MD5 + '" class="btn btn-primary btn-rounded btn-labeled" target="blank">View</a>\
											</div>\
										</div>';

								}
								out += '</div>\
								</div>';
							}

							$("#tradePanel").show();
						} else {
							out += '<div class="panel panel-bordered panel-dark mar-all">\
								<div class="panel-body">\
									<span class="text text-danger"> Not Available </span>\
								</div>\
							  </div>';
						}
						$("#tradeResultBox").html(out);
					}
				});
			}
		}

		fetchWaterData();
		fetchTradeData();
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
					}
				}

			},
			messages: {
				keyword: 'Enter 15 digit House No.',
				mobile_input: 'Enter mobile no.'

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
						$( "#submit" ).trigger( "click" );
                    }
                });

				function goBack(){
					
				location.href="<?php echo base_url('/CitizenProperty/index')?>"
				}
	</script>