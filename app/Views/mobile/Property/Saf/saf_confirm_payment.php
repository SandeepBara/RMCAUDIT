<?=$this->include("layout_mobi/header");?>
<style>
.error{
	color: red;
}
.row{
	line-height:25px;
}
.bank_dtl{
	display: none;
}
#advance_amt:{
	font-size: x-small;
    font-weight: bold;
}
</style>

<!--CONTENT CONTAINER-->
	<!--===================================================-->
	<div id="content-container">
           
		<div id="page-content">
				
			
			<input type="hidden" class="form-control" id="custm_id" name="custm_id" value="<?php echo $basic_details["saf_dtl_id"]; ?>">
			<input type="hidden" class="form-control" id="emp_id" name="emp_id" value="<?php echo $emp_details_id; ?>">
			<input type="hidden" class="form-control" id="ward_mstr_id" name="ward_mstr_id" value="<?php echo $basic_details["ward_mstr_id"]; ?>">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-6 text-danger">
								<b>Application No. :&nbsp; <?=$basic_details['saf_no']; ?></b>
							</div>
						</div>
					</div>

					<div class="panel-heading">
						<div class="panel-control">
							<a href="" class="btn btn-default">Back</a>
						</div>
						<h3 class="panel-title">Owner Basic Details</h3>
					</div>
					
					<div class="panel-body">
						<?php if($basic_details): ?>
						<div class="row">
							<div class="col-sm-3">
								<b>Ward No. :</b>
							</div>
							
							<div class="col-sm-3">
								<?php echo $basic_details['ward_no']; ?>
							</div>
						</div>	
						<div class="row">
							<div class="col-sm-3">
								<b>Application No. :</b>
							</div>
							
							<div class="col-sm-3">
								<?php echo $basic_details['saf_no']; ?>
							</div>
						</div>
						<?php endif; ?>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
									  <th scope="col">Owner Name</th>
									  <th scope="col">R/W Guardian</th>
									  <th scope="col">Guardian's Name</th>
									  <th scope="col">Mobile No</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if($owner_details=="")
									{
										?>
										<tr>
											<td colspan="4" style="text-align:center;"> Data Not Available...</td>
										</tr>
										<?php 
									}
									else
									{
										foreach($owner_details as $owner_details)
										{
											?>
											<tr>
											<td><?php echo $owner_details['owner_name']; ?></td>
											<td><?php echo $owner_details['relation_type']; ?></td>
											<td><?php echo $owner_details['guardian_name']; ?></td>
											<td><?php echo $owner_details['mobile_no']; ?></td>
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
										
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Tax Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
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
								</tr>
							</thead>
							<tbody>
								<?php if($tax_list):
							  $i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
							  <?php foreach($tax_list as $tax_list): 
							  $qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
							  ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $tax_list['arv']; ?></td>
									<td><?php echo $tax_list['qtr']; ?>/<?php echo $tax_list['fy']; ?></td>
									<td><?php echo $tax_list['holding_tax']; ?></td>
									<td><?php echo $tax_list['water_tax']; ?></td>
									<td><?php echo $tax_list['latrine_tax']; ?></td>
									<td><?php echo $tax_list['education_cess']; ?></td>
									<td><?php echo $tax_list['health_cess']; ?></td>
									<td><?php echo $tax_list['additional_tax']; ?></td>
									<td><?php echo $qtr_tax; ?></td>
									<?php if($i>$lenght){ ?>
										<td style="color:red;">Current</td>
									<?php } else { ?>
										<td>Old</td>
									<?php } ?>
								</tr>
								<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="11" style="text-align:center;"> <b>Data not available!!</b></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Proceed SAF Payment</h3>
					</div>
					<div class="panel-body">
					<div id="loadingPaymentDiv" style="background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; bottom: -35%; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
					<div class="load1" style="display: none;"><div class="loader"></div></div>
						<?php
						if(!empty($fy_demand))
						{
							?>
							<form method="post" id="paymentForm">
								<div class="row">
									<div class="col-md-12" style="overflow: scroll;">
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
												<td colspan="1">
													<select class="form-control" name="payment_mode" id="payment_mode" onchange="paymentModeChange()">
														<option>Cash</option>
														<option>Cheque</option>
														<option>DD</option>
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
												<td colspan="3"><textarea class="form-control" name="remarks" id="remarks" placeholder="Type your remarks here"></textarea></td>
											</tr>
											</tfoot>
									</table>
									</div>
								</div>
								
								<div class="row text-center">
									<?php
									// !($is_geo_tag_done??true)
									if(!($is_geo_tag_done??true)){
										?>
										<a href="<?php echo base_url('SafVerification/verificationUpload/'.$basic_details["saf_dtl_id"]."/0");?>" class="btn btn-primary blink">Go To tag First</a>
										<?php 
									}else{
										?>
										<input type="submit" name="pay_now" class="btn btn-primary" id="pay_now" value="Pay Now"  />
										<?php
									}
									?>
								</div>
							</form>
							<?php
						}
						else
						{
							?>
							<div class="row text-center">
								
								<span class="text text-success text-bold text-center">No Demand Available</span>
									
							</div>
							<?php
						}
						?>
						
					</div>
				</div>
			</div>
						
		
			
  
<?= $this->include('layout_mobi/footer');?>

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
			var total_amt = $("#total_payable_amount_temp").html();
			if($("#pay_advance").is(":checked"))
			{
				var total_payable_amount = $("#total_payable_amount").val();
				total_amt = parseFloat(total_amt);
				total_payable_amount = parseFloat(total_payable_amount);
				
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
				saf_pay_now();
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
			}
			else
			{
				$("#total_payable_amount").hide();
			}
		});

		$("#total_payable_amount").change(function()
		{
			var total_amt = $("#total_payable_amount_temp").html();
			var total_payable_amount = $(this).val();
			if(total_payable_amount-total_amt>0)
			$("#advance_amt").html("Advance Amount: " + (total_payable_amount-total_amt).toFixed(2));
			else
			$("#advance_amt").html(null);
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
			url: "<?=base_url('/safDemandPayment/Ajax_getSAFPayableAmount/'); ?>",
			dataType:"json",
			data:
			{
				fy: fy, qtr: qtr, saf_dtl_id: <?=$saf_dtl_id;?>
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

function saf_pay_now()
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
	var total_payable_amount = $("#total_payable_amount").val();
	

	$.ajax({
			type: "POST",
			url: "<?=base_url('/safDemandPayment/Ajax_saf_pay_now'); ?>",
			dataType:"json",
			data:
			{
				fy: fy, qtr: qtr, saf_dtl_id: <?=$saf_dtl_id;?>, payment_mode: payment_mode,  remarks: remarks, 
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
			},
			error: function (error) {
				console.log(error);
			}
		});
}

function getQtr()
{
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
			type: "POST",
			url: "<?=base_url('/safDemandPayment/Ajax_getQtr/'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id: fy_mstr_id, saf_dtl_id: <?=$saf_dtl_id;?>
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