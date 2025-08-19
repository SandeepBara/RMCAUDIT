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
									<td><?= $owner_detail['mobile_no']; ?></td>

									<td><?= $owner_detail['aadhar_no'] == '' ? 'N/A' : $owner_detail['aadhar_no'];  ?></td>
									<td><?= $owner_detail['pan_no'] == '' ? 'N/A' : $owner_detail['pan_no'];  ?></td>
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
									<td class="text-success">Total Paybale Amount</td>
									<td class="text-success" id="total_payable_amount_temp"><?=$DuesDetails['PayableAmount']; ?></td>
								</tr>
							</tbody>
						</table>
                        <form method="post">
                            <div class="row text-center">
							<?php
									date_default_timezone_set('Asia/Kolkata');
									$proceedToPay = true;
									if ($DuesDetails['PayableAmount']>50000) {
										if (date("h:i:s")>"12:00:00" && date("h:i:s")<"13:00:00") {
											$proceedToPay = true;
										} else {
											$proceedToPay = true;
										}
									}

									if ($proceedToPay){
								?>
									<button type="button" name="pay_now" class="btn btn-primary" id="pay_now">Pay Now </button>
								<?php }else{ ?>
                                	<button type="button" name="pay_now" class="btn btn-primary">Pay Now </button>
								<?php } ?>
                                <button class="hidden" id="rzp-button1">Pay</button>
                            </div>
                        </form>
						<?php

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
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>


$(document).ready(function(){
    $("#pay_now").click(function()
    {
        $.ajax({
			type: "POST",
			url: "<?=base_url('/onlinePay/Ajax_getOnlinePropPayableAmount');?>",
			dataType:"json",
			data:
			{
				fy: '<?=$DuesYear["max_year"];?>', qtr: '<?=$DuesYear["max_quarter"];?>', prop_dtl_id: '<?=$prop_dtl_id;?>'
			},
			beforeSend: function() {
				$("#pay_now").html('<i class="fa fa-refresh fa-spin"></i>').prop('disabled', true);
			},
			success: function(data)
			{
				$("#pay_now").html('Pay Now').prop('disabled', false);;
				console.log(data);
				if(data.status==true)
				{
					data = data.data;
					var options = {
						"key": data.key, // Enter the Key ID generated from the Dashboard
						"amount":   data.amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
						"currency": data.currency,
						"name":     data.name,
						"description":  data.description,
						"image":    "https://cdn.razorpay.com/logos/FF5xcplf8lBIoi_medium.png",
						"order_id": data.order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
						"callback_url": "",
						"handler": function (response)
						{
							console.log(response);
							var razorpay_payment_id=response.razorpay_payment_id;
							var razorpay_order_id=response.razorpay_order_id;
							var razorpay_signature=response.razorpay_signature;
							window.location.href = "<?=base_url("onlinePay/paymentSuccess/$prop_dtl_id");?>/"+data.pg_mas_id+"/"+razorpay_payment_id+"/"+razorpay_order_id+"/"+razorpay_signature;
						},
						"prefill": {
							"name": '<?=$owner_details[0]["owner_name"];?>',
							"email": '<?=$owner_details[0]["email"];?>',
							"contact": '<?=$owner_details[0]["mobile_no"];?>'
						},
						"notes": {
							"address": "Razorpay Corporate Office"
						},
						"theme": {
							"color": "#17ca07"
						}
					};
					console.log(options);
					var rzp1 = new Razorpay(options);
					rzp1.on('payment.failed', function (response)
					{
						var code = (response.error.code);
						var description = (response.error.description);
						var source = (response.error.source);
						var step = (response.error.step);
						var reason = (response.error.reason);
						var order_id = (response.error.metadata.order_id);
						var payment_id = (response.error.metadata.payment_id);
						window.location.href = "<?=base_url("onlinePay/paymentFailed/$prop_dtl_id");?>/"+data.pg_mas_id+"/"+code+"/"+description+"/"+source+"/"+step+"/"+reason+"/"+order_id+"/"+payment_id;
					});
					rzp1.open();
				}
				else
				{
					alert(data.message);
					return false;
				}
			}
		});
    });
});
</script>
