<?=$this->include('layout_home/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->
       
        <div id="page-content">
            <div class="panel panel-bordered ">
                    <div class="panel-body">
                        
                        <div class="col-sm-10">

                              
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Due Detail List</h3>
									</div>
									<div class="panel-body">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<tbody>
														<?php 
														if($demand_detail)
														{
															$i=1; $total_due = 0; $j=1;
															foreach($demand_detail as $tot_demand)
															{
																$i==1? $first_qtr = $tot_demand['qtr']:'';
																$i==1? $first_fy = $tot_demand['fy']:''; 
																$demand =$tot_demand['balance'];
																$total_demand = $demand;
																$total_due = $total_due + $total_demand;
																$total_quarter = $i;
																$i++;
															}
															?>
															<tr>
																<td><b style="color:#bf06fb;">Total Dues</b></td>
																<td><strong style="color:#bf06fb;">:</strong></td>
																<td>
																<b style="color:#bf06fb;"><?php echo $total_due; ?></b>
																</td><td></td><td></td>
																<td></td>
															<tr>
																<td>Dues From</td>
																<td><strong>:</strong></td>
																<td>
																	Quarter <?php echo $first_qtr; ?> / Year <?php echo $first_fy; ?>
																</td>
																<td>Dues Upto</td>
																<td><strong>:</strong></td>
																<td>
																	Quarter <?php echo $tot_demand['qtr']; ?> / Year <?php echo $tot_demand['fy']; ?>		</td>
															</tr>
															<tr>
																<td>Total Quarter(s)</td>
																<td><strong>:</strong></td>
																<td colspan="4"><?php echo $total_quarter; ?></td>
																
															</tr>
															<?php 
														}
														?>
													</tbody>
												</table>
											</div>
											<div class="table-responsive">	
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="bg-trans-dark text-dark">
															<th>Sl No.</th>
															<th>Quarter / Year</th>
															<th>Holding Tax</th>
															<th>Water Harvesting Tax</th>
															<th>Demand</th>
													</thead>
													<tbody>
														
														<?php if($demand_detail):
														
														foreach($demand_detail as $demand_detail): 
															$total_demand=0;
															$total_demand = $total_demand + $demand_detail['balance'];
														?>			
														<tr>
															<td><?php echo $j++; ?></td>
															<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fy']; ?></td>
															<td><?php echo $demand_detail['total_tax']; ?></td>
															<td><?php echo $demand_detail['additional_tax']; ?></td>
															<td><?php echo $demand_detail['balance']; ?></td>
															
														</tr>
														<?php endforeach; ?>
														<?php else: ?>
														<tr>
															<td colspan="5" class="text text-success text-bold text-center"> No Dues Are Available!!</td>
														</tr>
														<?php endif ?>
														
													</tbody>
												</table>
												
											</div>
										</div>
									</div>
								</div>
								
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Proceed SAF Payment</h3>
									</div>


									<div class="panel-body">
										<div id="loadingPaymentDiv" style="background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; bottom: -35%; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
										<div class="load1" style="display: none;"><div class="loader"></div></div>
										<form method="post" id="paymentForm" action="<?=base_url("onlinePaysaf/safPaymentProceedCC");?>">
											<div class="row table-responsive">
											<table class="table table-hovered text-lg text-bold">
													<thead class="bg-trans-dark text-dark">
														<tr>
															<th style="width: 25%;"><h5 class="text-main pull-right">Payment Upto Year</h5></th>
															<th style="width: 25%;">
																<select class="form-control" name="fy_mstr_id" id="fy_mstr_id" disabled>
																	<?php
																	foreach($fy_demand as $row)
																	{
																		?>
																		<option value="<?=$row["fy_id"];?>"><?=$row["fy"];?></option>
																		<?php
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
												</table>
											</div>
											
											<div class="row text-center">
												<?php
												// !($is_geo_tag_done??true)
												if(!($is_geo_tag_done??true)){
													?>
													<input type="button"  class="btn btn-primary blink" value="Please Wait For Geo Tag" /> 
													<?php 
												}else{
													?>
														<button type="submit" name="pay_now" class="btn btn-primary" id="pay_now"> Pay Now </button>
													<?php
												}
												?>
												<!--button class="hidden" id="rzp-button1">Pay</button-->
											</div>
										</form>
									</div>
								</div>
                        </div>

                        <div class="col-sm-2">
                            <?=$this->include('citizen/SAF/SafCommonPage/saf_left_side');?>
                        </div>

                    </div>
            </div>
        </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?=$this->include('layout_home/footer');?>
<!--script src="https://checkout.razorpay.com/v1/checkout.js"></script-->
<script>
function calculateAmount()
{
	var fy = $("#fy_mstr_id option:selected").text();
	var qtr = $("#qtr").val();

	$.ajax({
			type: "POST",
			url: "<?=base_url('/CitizenDtl/Ajax_getSAFPayableAmount/'); ?>",
			dataType:"json",
			data:
			{
				fy: fy, qtr: qtr, saf_dtl_id: <?=$saf_dtl_id;?>
			},
			beforeSend: function() {
				$("#pay_now").hide();
				$("#loadingPaymentDiv").show();
			},
			success: function(data)
			{
				$("#loadingPaymentDiv").hide();
				console.log(data);
				if(data.response==true)
				{
					$("#result").html(data.html_data);
					$("#pay_now").show();
				}
			}
		});
}

function getQtr()
{
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
			type: "POST",
			url: "<?=base_url('/CitizenDtl/Ajax_getQtr/'); ?>",
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

/*$(document).ready(function(){
    $("#pay_now").click(function()
    {
		var fy = $("#fy_mstr_id option:selected").text();
		var qtr = $("#qtr").val();
		
        $.ajax({
			type: "POST",
			url: "<?=base_url('/CitizenDtl/Ajax_getOnlineSafPayableAmount');?>",
			dataType:"json",
			data:
			{
				fy: fy, qtr: qtr, saf_dtl_id: <?=$saf_dtl_id;?>
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

							window.location.href = "<?=base_url("CitizenDtl/paymentSuccess/$saf_dtl_id");?>/"+data.pg_mas_id+"/"+razorpay_payment_id+"/"+razorpay_order_id+"/"+razorpay_signature;
						},
						"prefill": {
							"name": data.owner_name,
							"email": data.owner_email,
							"contact": data.owner_contact
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
						window.location.href = "<?=base_url("onlinePay/paymentFailed/$saf_dtl_id");?>/"+data.pg_mas_id+"/"+code+"/"+description+"/"+source+"/"+step+"/"+reason+"/"+order_id+"/"+payment_id;
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
});*/
</script>