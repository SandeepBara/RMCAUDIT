<?= $this->include('layout_home/header'); ?>
<!--CONTENT CONTAINER-->
<style>
	.row {
		line-height: 25px;
	}
</style>
<div id="content-container" style="padding: 20px 0px;">
	<!--Page content-->
	<div id="page-content">

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">
					<?php if (isset($isCalledFotTC['status']) && $isCalledFotTC['status'] == '1') { ?>
						<b style="color:#f13810;"> You are requested TC for payment collection. </b>
					<?php } else if (isset($isCalledFotTC['status']) && $isCalledFotTC['status'] == 2) { ?>
						<b style="color:#f13810;"> TC accept your request for payment collection. </b>
					<?php } ?>

				</div>
				<div class="panel-control">
					<button onclick="goBack()" class="btn btn-info float-right">Go Back</button>
				</div>
				<h3 class="panel-title">Basic Details</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-3">
						<b>Ward No. </b>
					</div>
					<div class="col-sm-3">
						<?= $basic_details['ward_no'] ? $basic_details['ward_no'] : "N/A"; ?>
					</div>

					<div class="col-sm-3">
						<b>Holding No. </b>
					</div>
					<div class="col-sm-3">
						<?php echo $basic_details['holding_no'] ? $basic_details['holding_no'] : "N/A"; ?>
					</div>

				</div>
				<div class="row">
					<div class="col-sm-3">
						<b>Unique Holding No. </b>
					</div>
					<div class="col-sm-3">
						<?php echo $basic_details['new_holding_no'] ? $basic_details['new_holding_no'] : "N/A"; ?>
					</div>
					<div class="col-md-3">
						<b>Property Type </b>
					</div>
					<div class="col-md-3">
						<?php echo $basic_details['property_type'] ? $basic_details['property_type'] : "N/A"; ?>
					</div>


				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Ownership Type </b>
					</div>
					<div class="col-md-3">
						<?php echo $basic_details['ownership_type'] ? $basic_details['ownership_type'] : "N/A"; ?>
					</div>
					<div class="col-md-3">
						<b>Address </b>
					</div>
					<div class="col-md-3">
						<?php echo $basic_details['prop_address'] ? $basic_details['prop_address'] : "N/A"; ?>
					</div>

				</div>
				<div class="row">
					<div class="col-sm-3">
						<b> 15 Digit Unique House No. </b>
					</div>
					<div class="col-sm-3">
						<?php echo $basic_details['new_holding_no'] ? $basic_details['new_holding_no'] : "N/A"; ?>
					</div>
					<div class="col-md-3">
						<b>Area Of Plot </b>
					</div>
					<div class="col-md-3">
						<?php echo $basic_details['area_of_plot'] ? $basic_details['area_of_plot'] : "N/A"; ?>( In decimal)
					</div>


				</div>
				<div class="row">


					<div class="col-md-3">
						<b>Rainwater Harvesting Provision </b>
					</div>
					<div class="col-md-3">
						<?php if ($basic_details['is_water_harvesting'] == 't') { ?>
							YES
						<?php } else if ($basic_details['is_water_harvesting'] == 'f') { ?>
							No
						<?php } else { ?>
							N/A
						<?php } ?>
					</div>
				</div>

			</div>
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Owner Details</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
					<thead class="bg-trans-dark text-dark">
						<tr>
							<th>Owner Name</th>
							<th>Guardian Name</th>
							<th>Relation</th>
							<th>Mobile No</th>
							<th>Aadhar No.</th>
							<th>PAN No.</th>
							<th>Email</th>
							<th>Gender</th>
							<th>DOB</th>
							<th>Is Specially Abled?</th>
							<th>Is Armed Force?</th>
						</tr>
					</thead>
					<tbody id="owner_dtl_append">
						<?php
						# print_var($owner_details);
						if (isset($owner_details)) {
							foreach ($owner_details as $owner_detail) {
						?>
								<tr>
									<td><?= $owner_detail['owner_name']; ?></td>
									<td><?= $owner_detail['guardian_name'] == '' ? 'N/A' : $owner_detail['guardian_name'];  ?></td>

									<td><?= $owner_detail['relation_type']; ?></td>
									<td><?= substr_replace($owner_detail['mobile_no'], 'XXXXXX', 0, 6); ?></td>

									<td><?= $owner_detail['aadhar_no'] == '' ? 'N/A' : substr_replace($owner_detail['aadhar_no'], 'XXXXXXXX', 0, 8);  ?></td>
									<td><?= $owner_detail['pan_no'] == '' ? 'N/A' : substr_replace($owner_detail['pan_no'], 'XXXXXX', 0, 6);  ?></td>
									<td><?= $owner_detail['email'] == '' ? 'N/A' : $owner_detail['email'];  ?></td>

									<td><?= $owner_detail['gender'] == '' ? 'N/A' : $owner_detail['gender'];  ?></td>
									<td><?= $owner_detail['dob'] == '' ? 'N/A' : $owner_detail['dob'];  ?></td>
									<td><?= $owner_detail['is_specially_abled'] == 't' ? 'Yes' : 'No';  ?></td>
									<td><?= $owner_detail['is_armed_force'] == 't' ? 'Yes' : 'No';  ?></td>


								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Tax Details</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
					<thead class="bg-trans-dark text-dark">
						<th scope="col">Sl No.</th>
						<th scope="col">ARV</th>
						<th scope="col">Effect From</th>
						<th scope="col">Holding Tax</th>
						<th scope="col">Water Tax</th>
						<th scope="col">Conservancy/Latrine Tax</th>
						<th scope="col">Education Cess</th>
						<th scope="col">Health Cess</th>
						<th scope="col">RWH Penalty</th>
						<th scope="col">Quarterly Tax</th>
						<th scope="col">Status</th>
					</thead>
					<tbody>
						<?php if ($tax_list) :
							$i = 1;
							$qtr_tax = 0;
							$lenght = sizeOf($tax_list); ?>
							<?php foreach ($tax_list as $tax_list) :
								$qtr_tax = $tax_list['holding_tax'] + $tax_list['water_tax'] + $tax_list['latrine_tax'] + $tax_list['education_cess'] + $tax_list['health_cess'] + $tax_list['additional_tax'];
							?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $tax_list['arv']; ?></td>
									<td><?php echo $tax_list['qtr']; ?> / <?php echo $tax_list['fy']; ?></td>
									<td><?php echo $tax_list['holding_tax']; ?></td>
									<td><?php echo $tax_list['water_tax']; ?></td>
									<td><?php echo $tax_list['latrine_tax']; ?></td>
									<td><?php echo $tax_list['education_cess']; ?></td>
									<td><?php echo $tax_list['health_cess']; ?></td>
									<td><?php echo $tax_list['additional_tax']; ?></td>
									<td><?php echo $qtr_tax; ?></td>
									<?php if ($i > $lenght) { ?>
										<td style="color:red;">Current</td>
									<?php } else { ?>
										<td>Old</td>
									<?php } ?>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="11" style="text-align:center;"> <b>Data not available!!</b></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>



		<div id="loadingDivs" style="display: none; background: url(<?php base_url(); ?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 150px; left: 0; height: 100%; width: 100%; z-index: 9999999;">
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Pay Property Tax</h3>
			</div>

			<div class="panel-body">
				<div class="row">
					<?php
					if ($DuesYear) {
					?>
						<table class="table table-hovered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th style="width: 25%;">From </th>
									<th style="width: 25%;"><?= $DuesYear["min_quarter"]; ?>/<?= $DuesYear["min_year"]; ?></th>
									<th style="width: 25%;">Upto </th>
									<th style="width: 25%;"><?= $DuesYear["max_quarter"]; ?>/<?= $DuesYear["max_year"]; ?></th>
								</tr>
							</thead>
							<tbody id="result">
								<tr>
									<td>Demand Amount</td>
									<td><?=$DuesDetails['DemandAmount']; ?></td>
									<td>Rebate</td>
									<td><?=$DuesDetails['RebateAmount']; ?></td>
								</tr>
								<tr>
									<td>Special Rebate</td>
									<td><?=$DuesDetails['SpecialRebateAmount']; ?></td>
									<td>Other Penalty</td>
									<td><?=$DuesDetails['OtherPenalty']; ?></td>
								</tr>
								<tr>
									<td> 1 % Interest </td>
									<td><?=$DuesDetails['OnePercentPnalty']; ?></td>
									<td>Advance</td>
									<td><?=$DuesDetails['AdvanceAmount']; ?></td>
								</tr>
								<tr>
									<td >	
										<i class="fa fa-info-circle" data-placement="bottom" data-toggle="modal" data-target="#forward_backward_model" title="Penalty Calculation Rule">
										</i> 							
										Notice Penalty 
									</td>
									<td><?=$DuesDetails['noticePenalty'];?></td>
									<td>2% Addition Penalty</td>
									<td><?=$DuesDetails['noticePenaltyTwoPer'];?></td>
								</tr>
								<tr>
									<td class="text-success">Total Paybale Amount</td>
									<td class="text-success" id="total_payable_amount_temp"><?=$DuesDetails['PayableAmount']; ?></td>
								</tr>
							</tbody>
						</table>
						<?php
						if (isset($otpSentSuccessfully) && $otpSentSuccessfully) {
						?>
							<form method="post">
								<div class="row" style="margin: 20px;">
									<div class="col-md-1">
										OTP :
									</div>
									<div class="col-md-3">
										<input type="number" maxlength="4" max="9999" name="otp" placeholder="Enter OTP" class="form-control" required />
									</div>
									<div class="col-md-3">
										<input type="submit" name="verifyOTP" value="Verify OTP" class="btn btn-primary" />
									</div>
								</div>
							</form>
							<?php
						} else {
							# If TC Scheduled then citizen can't proceed payment
							if (empty($isCalledFotTC)) {
							?>
								<div class="text-center">
									<a href="<?= base_url("payOnline/propertyPaymentProceed"); ?>/<?= $id; ?>" class="btn btn-primary btn-labeled"> Proceed to Pay </a>
									<!-- <a href="<?= base_url("CitizenProperty/verifyOTP"); ?>/<?= $id; ?>" class="btn btn-primary btn-labeled"> Proceed to Pay </a> -->
								</div>
						<?php
							}
						}
					} else {
						?>
						<div class="text text-center text-success text-bold">No Dues Available</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>

		<div class="panel text-center">
			<a href="<?php echo base_url('CitizenProperty/Citizen_payment_details/' . $id); ?>" class="btn btn-primary"> Payment Details</a>
			<a href="<?php echo base_url('CitizenProperty/Citizen_property_details/' . $id); ?>" class="btn btn-primary"> Property Details</a>
		</div>

	</div>
</div>




<?= $this->include('layout_home/footer'); ?>
<script>
	function goBack() {
		const last_segment = window.location.pathname.split('/').pop();
		window.location.href = "<?php echo base_url('CitizenProperty/index/'); ?>" + "/" + last_segment
	}
</script>