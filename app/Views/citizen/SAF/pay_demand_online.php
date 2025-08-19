<?=$this->include('layout_home/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->
        <div class="panel panel-bordered panel-primary">
			<div class="panel-heading">
				<h1 class="panel-title text-center"><?=$ulb_name;?></h1>
			</div>
		</div>
        <div id="page-content">
            <div class="panel panel-bordered ">
                    <div class="panel-body">
                        
                        <div class="col-sm-10">

                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Payment Details</h3>
                                    </div>
                                    <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                                    <thead class="bg-trans-dark text-dark">
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <th>Transaction No</th>
                                                            <th>Payment Mode</th>
                                                            <th>Date</th>
                                                            <th>From Quarter / Year</th>
                                                            <th>Upto Quarter / Year</th>
                                                            <th>Amount</th>
                                                            <th>View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        //print_var($payment_detail);
                                                        if(isset($payment_detail))
                                                        {
                                                            $i=1;
                                                            foreach($payment_detail as $payment_detail)
                                                            {
                                                                ?>
                                                                <tr class="<?=($payment_detail["status"]==3)?'text-danger':null;?>">
                                                                    <td><?=$i++;?></td>
                                                                    <td class="text-bold"><?=$payment_detail['tran_no'];?></td>
                                                                    <td><?=$payment_detail['transaction_mode'] ?></td>
                                                                    <td><?=$payment_detail['tran_date'];?></td>
                                                                    <td><?=$payment_detail['from_qtr']." / ".$payment_detail['fy'];?></td>
                                                                    <td><?=$payment_detail['upto_qtr']." / ".$payment_detail['upto_fy'];?></td>
                                                                    <td><?=$payment_detail['payable_amt'];?></td>
                                                                    <td><a onClick="PopupCenter('<?=base_url("citizenPaymentReceipt/saf_payment_receipt/".$ulb_mstr_id."/".md5($payment_detail['id']));?>', 'SAF Citizen Payment Receipt', 1024, 786)" class="btn btn-primary">Citizen View </a></td>
                                                                </tr>
                                                                <?php 
                                                            }
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td colspan="9" class="text-danger text-bold text-center"> No Any Transaction ...</td>
                                                            </tr>
                                                            <?php 
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
                        </div>

                        <div class="col-sm-2">
                            <?=$this->include('citizen/SAF/SafCommonPage/saf_left_side');?>
                        </div>

                    </div>
            </div>
        </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
<button id="rzp-button1">Pay</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>

//setTimeout(document.getElementById('btnStartVisit').dispatchEvent(new MouseEvent("click"));, 1000);
var options = {
    "key":      "<?=$razorpay_param["key"];?>", // Enter the Key ID generated from the Dashboard
    "amount":   "<?=$razorpay_param["amount"];?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "<?=$razorpay_param["currency"];?>",
    "name":     "<?=$razorpay_param["name"];?>",
    "description":  "<?=$razorpay_param["description"];?>",
    "image":    "https://cdn.razorpay.com/logos/FF5xcplf8lBIoi_medium.png",
    "order_id": "<?=$razorpay_param["order_id"];?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "callback_url": "http://192.168.0.16:81/citizen/online_pay/paymen",
    "handler": function (response)
    {
        console.log(response);
        var razorpay_payment_id=response.razorpay_payment_id;
        var razorpay_order_id=response.razorpay_order_id;
        var razorpay_signature=response.razorpay_signature;

        window.location.href = "<?=base_url("onlinePay/paymentSuccess/$saf_dtl_id/$pg_mas_id/");?>/"+razorpay_payment_id+"/"+razorpay_order_id+"/"+razorpay_signature;
    },
    "prefill": {
        "name": "<?=$razorpay_param["owner_name"];?>",
        "email": "<?=$razorpay_param["owner_email"];?>",
        "contact": "<?=$razorpay_param["owner_contact"];?>"
    },
    "notes": {
        "address": "Razorpay Corporate Office"
    },
    "theme": {
        "color": "#17ca07"
    }
};
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
    window.location.href = "<?=base_url("onlinePay/paymentFailed/$saf_dtl_id/$pg_mas_id/");?>/"+code+"/"+description+"/"+source+"/"+step+"/"+reason+"/"+order_id+"/"+payment_id;
});
document.getElementById('rzp-button1').onclick = function(e)
{
    rzp1.open();
    e.preventDefault();
}

document.getElementById('rzp-button1').onclick();

</script>
