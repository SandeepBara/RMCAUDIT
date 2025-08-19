<?=$this->include("layout_mobi/header");?>

<style>
	.row{
		line-height: 25px;
	}
	.error{
		color: red;
	}
	.row{
		line-height:25px;
	}
	.bank_dtl{
		display: none;
	}
	#advance_amt{
		font-size: x-small;
		font-weight: bold;
	}
	#pay_now{
		background-color: linear-gradient(45deg, #080303 0%, rgb(255 2 2) 35%, #000000 100%);
		border-color:#000000 !important;
		color:black;
	}
</style>

<!--CONTENT CONTAINER-->
	<div id="content-container">
	<!--Page content-->
		<div id="page-content">	
			
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<div class="panel-control">
							<button type="button" class="btn btn-info btn_wait_load" onclick="history.back()">Back</button>
						</div>
						<h3 class="panel-title">Property Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<label class="col-md-3">Hoding No </label>
							<div class="col-md-3 text-bold pad-btm">
							<?=$new_holding_no;?>
							</div>
							
							<label class="col-md-3">Ward No</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$ward_no;?>
							</div>
						</div>

						<div class="row">
							<label class="col-md-3">Assessment Type</label>
							<div class="col-md-3 text-bold pad-btm">
							<?=$assessment_type;?>
							</div>
							

							<label class="col-md-3">Entry Type</label>
							<div class="col-md-3 text-bold pad-btm">
							<?=($entry_type);?>
							</div>
						</div>

					
						<div class="row">
							<label class="col-md-3">Order Date</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=date("d-m-Y", strtotime($created_on));?>
							</div>
							
							<label class="col-md-3">Old Holding (If Any)</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$holding_no;?>
							</div>
						</div>
						<div class="row">
							<label class="col-md-3">Property Type</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$property_type;?>
							</div>
							<label class="col-md-3">Ownership Type</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$ownership_type;?>
							</div>
						</div>
						<div class="<?=($prop_type_mstr_id==3)?null:"hidden";?>">
							<div class="row">
								<label class="col-md-3">Appartment Name</label>
								<div class="col-md-3 text-bold pad-btm">
									<?=$appartment_name;?>
								</div>
								<label class="col-md-3">Registry Date</label>
								<div class="col-md-3 text-bold pad-btm">
									<?=$flat_registry_date;?>
								</div>
							</div>
						</div>

						<div class="row">
							<label class="col-md-3">Road Type</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$road_type;?>
							</div>

							<label class="col-md-3">Holding Type</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$holding_type;?>
							</div>
						</div>
					
						<div class="row">
							<label class="col-md-3">Rain Water Harvesting</label>
							<div class="col-md-3 text-bold">
								<?=($is_water_harvesting=="t")?"Yes":"No";?>
							</div>
								
							<label class="col-md-3">Zone</label>
							<div class="col-md-3 text-bold">
								<?=($zone_mstr_id==1)?"Zone 1":"Zone 2";?>
							</div>
						</div>
					
					</div>
				</div>


				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Owner Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered text-sm">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>Owner Name</th>
												<th>Guardian Name</th>
												<th>Relation</th>
												<th>Mobile No</th>
												<th>Aadhar No.</th>
												<th>PAN No.</th>
												<th>Email</th>
											</tr>
										</thead>
										<tbody id="owner_dtl_append">
										<?php
										if(isset($prop_owner_detail))
										{
											foreach ($prop_owner_detail as $owner_detail)
											{
												?>
												<tr>
													<td><?=$owner_detail['owner_name'];?></td>
													<td><?=$owner_detail['guardian_name'];?></td>
													<td><?=$owner_detail['relation_type'];?></td>
													<td><?=$owner_detail['mobile_no'];?></td>
													<td><?=$owner_detail['aadhar_no'];?></td>
													<td><?=$owner_detail['pan_no'];?></td>
													<td><?=$owner_detail['email'];?></td>
												</tr>
												<?php
											}
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				
				
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Proceed Property Tax Payment</h3>
					</div>
					<div class="panel-body">
					<?php
					if(!empty($fy_demand))
					{
						?>
						<div id="loadingPaymentDiv" style="background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; bottom: -35%; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
						<div class="load1" style="display: none;"><div class="loader"></div></div>
							<form method="post" id="paymentForm">
								<div class="row">
									<div class="col-md-12" style="display: overflow;">
									<table class="table table-hovered table-responsive text-lg text-bold">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th style="width: 25%;"><h5 class="text-main pull-right">Payment Upto Year</h5></th>
												<th style="width: 25%;">
													<select class="form-control" name="fy_mstr_id" id="fy_mstr_id" onchange="getQtr();">
														<?php
														foreach($fy_demand as $row)
														{
															?>
															<option value="<?=$row["fy_id"];?>"><?=$row["fy"];?></option>
															<?php
															break;
														}
														?>
													</select>
												</th>
												<th style="width: 25%;"><h5 class="text-main pull-right">Payment Upto Quarter</h5></th>
												<th style="width: 25%;">
													<select class="form-control" name="qtr" id="qtr" onchange="calculateAmount();">
													</select>
												</th>
											</tr>
										</thead>
										<tbody id="result">
											
											</tbody>
											<tfoot>
											<tr>
												<td class="pull-right">Payment Mode</td>
												<td>
													<select class="form-control" name="payment_mode" id="payment_mode" onchange="paymentModeChange()">
														<option value="">-- Select --</option>
														<option value="Cash">Cash</option>
														<option value="Cheque">Cheque</option>
														<option value="DD">DD</option>
													</select>
												</td>
												<td class="pull-right">
													Pay Advance? 
													<input type="checkbox" name="pay_advance" id="pay_advance" value="true"/>
												</td>
												<td>
													<input type="text" class="form-control" name="total_payable_amount" id="total_payable_amount" placeholder="Enter Amount" style="display: none;"/>
													<label id="advance_amt" for="total_payable_amount" style="font-size: x-small; font-weight: bold;"></label>
												</td>
											</tr>
											<tr class="bank_dtl">
												<td class="pull-right">Bank Name</td>
												<td><input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" /></td>
												<td class="pull-right">Branch Name</td>
												<td><input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch Name" /></td>
											</tr>
											<tr class="bank_dtl">
												<td class="pull-right">Cheque/DD No</td>
												<td><input type="text" class="form-control" name="cheque_no1" id="cheque_no1" placeholder="Cheque/DD No" /></td>
												<td class="pull-right">Cheque Date</td>
												<td><input type="date" class="form-control" name="cheque_date" id="cheque_date" placeholder="Cheque Date" /></td>
											</tr>
											<tr>
												<td class="pull-right">Remarks</td>
												<td><textarea class="form-control" name="remarks" id="remarks" placeholder="Type your remarks here"></textarea></td>
											</tr>
											</tfoot>
									</table>
									</div>
								</div>
								
								<div class="row text-center">
									<input type="submit" name="pay_now" class="btn btn-primary" id="pay_now" value="Pay Now"  />
									<a href="<?=base_url('visiting_dtl/visit_details')?>" class="btn btn-sm btn-success">Enter Remarks</a>
								</div>
							</form>
						</div>
						<?php
					}
					else
					{
						?>
						<div class="col-xs-12 text text-success text-bold text-center">No Dues</div>
						<?php
					}
					?>
				</div>
			
		</div>
	</div>

	
	
<?=$this->include("layout_mobi/footer");?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
	
	$("#paymentForm").validate({
		
        rules: {
			"payment_mode": {
				required: true,
			},
            "remarks": {
                required: true,
            },
			"bank_name": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"branch_name": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"cheque_no1": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"cheque_date": {
                required: function(element){
                    return $("#payment_mode").val()!="Cash";
                },
            },
			"total_payable_amount": {
				required: function(element){
                    return $("#pay_advance").is(":checked");
                },
				min : function(element){
                    return Math.ceil($("#total_payable_amount_temp").html());
                },
				number: true,
			}
		},
		messages: {
			"payment_mode": "Please Choose Payment Mode",
			"remarks": "Please Enter Your Remarks",
			"bank_name": "Please Enter Bank Name",
			"branch_name": "Please Enter Branch Name",
			"cheque_no1": "Please Enter Cheque/DD No",
			"cheque_date": "Please Enter Cheque/DD Date",
			"total_payable_amount": {
				required: "Please Enter Payable Amount",
				min : "Amount Should be greter than Total Paybale Amount",
				number : "Please Enter Valid Amount",
			}
		},
		submitHandler: function(form)
		{
			// do other things for a valid form
			var total_amt = parseFloat($("#total_payable_amount_temp").html());
			if($("#pay_advance").is(":checked"))
			{
				var total_payable_amount = parseFloat($("#total_payable_amount").val());
				
				if(total_payable_amount <= total_amt)
				{
					alert("Amount Should be greater than Total Paybale Amount");
					$("#total_payable_amount-error").html("Amount Should be greter than Total Paybale Amount !!!");
					return false;
				}
			}
			
			if(confirm("Are you sure want to pay now?"))
			{
				//$("#loadingPaymentDiv").show();
				//alert("Form submitted");
				prop_pay_now();
				//form.submit();
			}
		}
	});

	$(document).ready(function()
	{
		$("#pay_advance").click(function()
		{
			if($("#pay_advance").is(":checked"))
			{
				$("#total_payable_amount").show();
				$("#advance_amt").html(null).show();
			}
			else
			{
				$("#total_payable_amount").hide();
				$("#advance_amt").html(null).hide();
			}
		});

		$("#total_payable_amount").change(function()
		{
			var total_amt = $("#total_payable_amount_temp").html();
			var total_payable_amount = $(this).val();
			if(total_payable_amount-total_amt>0)
			$("#advance_amt").html("Advance Amount: " + (total_payable_amount-total_amt).toFixed(2)).show();
			else
			$("#advance_amt").html(null).hide();
			//$("#total_payable_amount-error").show();
		});
	});

function paymentModeChange()
{
	var payment_mode = $("#payment_mode").val();
	if(payment_mode=='Cash')
	{
		$(".bank_dtl").hide();
	}
	else if (['Cheque', 'DD'].includes(payment_mode))//in_array
	{
		$(".bank_dtl").show();
	}
}

function calculateAmount()
{
	var fy = $("#fy_mstr_id option:selected").text();
	var qtr = $("#qtr").val();

	$.ajax({
			type: "POST",
			url: "<?=base_url('/jsk/Ajax_getPropPayableAmount/'); ?>",
			dataType:"json",
			data:
			{
				fy: fy, qtr: qtr, prop_dtl_id: <?=$prop_dtl_id;?>
			},
			beforeSend: function() {
				$("#loadingPaymentDiv").show();
			},
			success: function(data)
			{
				$("#loadingPaymentDiv").hide();
				console.log(data);
				if(data.response==true)
				{
					$("#result").html(data.html_data);
					$('td').each(function(i){
						$(this).removeClass('pull-right');
					});
				}
			}
		});
}

function prop_pay_now()
{
	var fy = $("#fy_mstr_id option:selected").text();
	var qtr = $("#qtr").val();
	var payment_mode = $("#payment_mode").val();
	var bank_name = $("#bank_name").val();
	var branch_name = $("#branch_name").val();
	var cheque_no1 = $("#cheque_no1").val();
	var cheque_date = $("#cheque_date").val();
	var remarks = $("#remarks").val();
	var pay_advance = $("#pay_advance").val();
	var total_payable_amount = 0;

	if($("#pay_advance").is(":checked"))
	var total_payable_amount = $("#total_payable_amount").val();

	$.ajax({
			type: "POST",
			url: "<?=base_url('/jsk/Ajax_prop_pay_now'); ?>",
			dataType:"json",
			data:
			{
				fy: fy, qtr: qtr, prop_dtl_id: <?=$prop_dtl_id;?>, payment_mode: payment_mode,  remarks: remarks, 
				bank_name: bank_name, branch_name: branch_name, cheque_no1: cheque_no1, cheque_date: cheque_date,
				pay_advance: pay_advance, total_payable_amount: total_payable_amount
			},
			beforeSend: function() {
				$("#loadingPaymentDiv").show();
			},
			success: function(data)
			{
				$("#loadingPaymentDiv").hide();
				console.log(data);
				if(data.response==true)
				{
					window.location.href = data.url;
				}
				else
				{
					modelInfo(data.message);
				}
			}
		});
}

function getQtr()
{
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
			type: "POST",
			url: "<?=base_url('/jsk/Ajax_getTCQtr/'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id: fy_mstr_id, prop_dtl_id: <?=$prop_dtl_id;?>
			},
			beforeSend: function() {
				$("#qtr").html(null);
				$("#loadingPaymentDiv").show();
			},
			success: function(data)
			{
				$("#loadingPaymentDiv").hide();
				//console.log(data);
				if(data.response==true)
				{
					$("#qtr").html(data.data);
					calculateAmount();
				}
			}
		});
}

getQtr();
</script>