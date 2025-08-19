
<?php
if(isset($from) && $from=='citizen')
{ 
	echo $this->include('layout_home/header');
}
else
	echo $this->include('layout_vertical/header');
?>

<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name['ulb_mstr_id']?>.png) !important;
        background-size: 400px 400px;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name['ulb_mstr_id']?>.png) !important;
	background-size: 400px 400px;
	background-repeat:no-repeat;
	background-position:center;
	
}
</style>



<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
		<div id="content-container">
			<div id="page-head">

				<!--Page Title-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<div id="page-title">
					<!--<h1 class="page-header text-overflow">Designation List</h1>//-->
				</div>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End page title-->


				<!--Breadcrumb-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<?php 
				if(!isset($from))
				{
					?>
				<ol class="breadcrumb">
				<li><a href="#"><i class="demo-pli-home"></i></a></li>
				<li><a href="#">Water</a></li>
				<li><a href="<?=base_url('WaterViewConsumerDetails/index/'.$consumer_id)?>">Water Connection Details</a></li>
				<li class="active">Payment Receipt</li>
				</ol>
					<?php
				}
				?>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End breadcrumb-->

			</div>

			<!-- ======= Cta Section ======= -->

			<div id="page-content">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel">
							
							<!-- ======= Cta Section ======= -->
							<div class="panel panel-dark">
						
								<div class="panel-heading">
										<div class="col-sm-6">
											<h3 class="panel-title">Water User Charge Payment Receipt</h3>
										</div>
										<?php 
										if(isset($from) && $from=='citizen')
										{
											?>
										<div class="col-sm-6" style="text-align: right;">
											<a href="<?php echo base_url('WaterViewConsumerDetailsCitizen/index/'.($consumer_id));?>" class="btn btn-info">Back</a>
										</div>
											<?php
										}
										?>
										
								</div>							
							
								<div class="panel-body"  id="print_watermark" style="width:90%;margin-left:5%;outline-style: dotted;padding:5px;color:black; ">
									<style type="text/css" media="print">
										@media print
										{
											/* For Remove Header URL */
											@page {
											margin-top: 0;
											margin-bottom: 0;
											size: portrait;
											size: A4;
											}
											body  {
											padding-top:30px;
											padding-bottom: 5px ; background:#FFFFFF
											}
											/* Enable Background Graphics(ULB Logo) */
											*{
											-webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
											color-adjust: exact !important;                 /*Firefox*/
										}
										} 
									</style>
									<div class="col-sm-1"></div>
										<div class="col-sm-10" style="text-align: center;">
											<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
										</div>
										<div class="col-sm-1 noprint text-right">
											<button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;">PRINT</button>
										</div>
										<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
										<?=$ulb_mstr_name["ulb_name"];?>
										</div>
										
										<div class="col-sm-12">
											<div class="col-sm-8">

											</div>
											<div class="">
											</div>
										</div>
										<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

											<tbody>
												<tr>
													<td height="71" colspan="4" align="center">
														<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">WATER USER CHARGE RECEIPT </div>
													</td>
												</tr>
												
												<tr>
													<td colspan="3">Receipt No. : <b><?=$transaction_details["transaction_no"];?></b></td>
													<td >Date : <b><?=date('d-m-Y',strtotime($transaction_details["transaction_date"]));?></b></td>
												</tr>
												<tr>
													<td colspan="3">Department / Section : Water<br>
												Account Description : Water User Charge</td>
													<td>
														<div >Ward No : <b><?=$consumer_details["ward_no"];?></b> </div>
														<div >Consumer No : <b><?=$consumer_details["consumer_no"];?></b></div>
														<div >Holding No : <b><?=!empty($consumer_details["holding_no"])?$consumer_details["holding_no"]:"N/A";?></b></div>
													</td>
												</tr>
												
											</tbody>
										</table><br>
										<br>
										<table width="100%" border="0">
											<tbody>
												<tr>
													<td>Received From Mr / Mrs / Miss . : &nbsp;
														<span style="font-size: 14px; font-weight: bold">
														<?php 
														$i=0;
														foreach($consumer_owner_details as $applicant_name) {
															$i++;
															if ($i==1) {
																echo strtoupper($applicant_name["applicant_name"]);
															} else {
																echo ", ".strtoupper($applicant_name["applicant_name"]);
															}
														}
														?>
														</span>
													</td>
												</tr>
												<tr>
													<td> Guardian Name: &nbsp;
														<span style="font-size: 14px; font-weight: bold">
														<?php 
														$i=0;
														foreach($consumer_owner_details as $applicant_name) 
														{
															$i++;
															if ($i==1) 
															{
																echo strtoupper($applicant_name["father_name"]);
															} 
															else 
															{
																echo ", ".strtoupper($applicant_name["father_name"]);
															}
														}
														?>
														</span>
													</td>
												</tr>
												<tr>
													<td>Mobile No. : &nbsp;
														<span style="font-size: 14px; font-weight: bold">
														<?php 
														$j=0;
														foreach($consumer_owner_details as $mobile_no) {
															$j++;
															if ($j==1) {
																echo $mobile_no["mobile_no"];
															} else {
																echo ", ".$mobile_no["mobile_no"];
															}
														}
														?>
														</span>
													</td>
													
												</tr>
												
												<tr>
													<td>Address : 
														<span style="font-size: 14px; font-weight: bold">
															<?=isset($applicant_details["address"])?$applicant_details["address"]:'N/A';?>
														</span>
													</td>
												</tr>
												<tr>
													<td>
														<div style="float: left;">A Sum of Rs. </div>
														<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
															<?=$transaction_details["paid_amount"];?>
															
														&nbsp;
														</div><br>

														<div style="float: left;">(in words) </div>
														<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
															&nbsp;
														<?php echo ucwords(getIndianCurrency($transaction_details["paid_amount"])); ?>
														&nbsp; Only
														</div>
													</td>
												</tr>
												<?php if(in_array(strtoupper($transaction_details["payment_mode"]),["CASH",'ONLINE',"CARD","UPI"])){ ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards :<strong style="font-size: 14px;">Water Tax &amp; Others</strong>&nbsp;&nbsp; vide <b><?=$transaction_details["payment_mode"];?> </b> 
														</div>
												<?php } else { ?>
												<tr>
													<td height="35">
														<?php if($transaction_details["payment_mode"]=="CHEQUE"){ ?>
														<div style="float: left;">
															towards :<strong style="font-size: 14px;">Water Tax &amp; Others</strong>&nbsp;&nbsp; vide Cheque No
														</div>
														<?php } else{ ?>
														<div style="float: left;">
															towards :<strong style="font-size: 14px;">Water Tax &amp; Others</strong>&nbsp;&nbsp; vide DD No
														</div>
														<?php } ?>
														<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$transaction_details["cheque_no"];?>
														</div>
													</td>
												</tr>
												
												<tr>
													<td height="35">
														<div style="float: left;">Dated </div>
														<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=date('d-m-Y',strtotime($transaction_details["cheque_date"]));?>
														</div>
														<div style="float: left;">Drawn on </div>
														<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px;">
															&nbsp;&nbsp; <?=$transaction_details["bank_name"];?>
														</div>
													</td>
												</tr>
												<tr>
													<td height="35">
														<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$transaction_details["branch_name"];?>
														</div>
														<div style="float: left;">Place Of The Bank.</div>
													</td>
												</tr>
												<?php } ?>
												
											</tbody>
										</table><br><br><br>
										<div class="col-sm-12">
												<b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to realisation</b>
										</div><br><br>
										
										<div style="width: 99%; margin: auto; line-height: 35px;"><strong style="font-size: 14px;">WATER USER CHARGE DETAILS </strong>
										
										</div>
											<table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
												<tbody>
													<tr>
														<td ><b>Description</b></td>
														
														<td ><b>Total Amount</b></td>
														
													</tr>
													
												
												
													<tr>
														<td>Period: &nbsp; 

														<?php echo date('F',strtotime($transaction_details['from_month'])).' / '.date('Y',strtotime($transaction_details['from_month']));

														if($transaction_details['upto_month']!=$transaction_details['from_month'])
														{

														
														?>

															to
														
														<?php echo date('F',strtotime($transaction_details['upto_month'])).' / '.date('Y',strtotime($transaction_details['upto_month']));


														}
														?>
															

														</td>
														
														<td><?php echo $transaction_details['total_amount'];?></td>
														

													</tr>													

													<tr>
														<td >Penalty</td>
														<td><?php echo $transaction_details['penalty'];?></td>
														
													</tr>
													<tr>
														<td >Rebate</td>
														<td><?php echo $transaction_details['rebate'];?></td>
														
													</tr>
													<tr>
														<td >Paid Amount</td>
														<td><?php echo $transaction_details['paid_amount'];?></td>
														
													</tr>
													<?php
													if(isset($adjustment_amount) && !empty($adjustment_amount))
													{
														?>
														<tr>
															<td>Adjust Amount</td>
															<td><?=$adjustment_amount['amount']?></td>
														</tr>
														<?php
													}
													if(isset($advance_amount) && !empty($advance_amount))
													{
														?>
														<tr>
															<td>Advance Amount</td>
															<td><?=$advance_amount['amount']?></td>
														</tr>
														<?php
													}
													if(isset($meter_reading) && !empty($meter_reading))
													{
														if(!empty($meter_reading['final_reading']))
														{
															?>
															<tr>
																<td>Meter Payment (<?=$meter_reading['initial_reading']?> - <?=$meter_reading['final_reading']?>)</td>
																<td><?=$meter_reading['meter_payment']?></td>

															</tr>
															<?php
														}
														if(!empty($meter_reading['demand_upto']))
														{
															?>
															<tr>
																<td>Fixed Payment (<?=$meter_reading['demand_from']?> - <?=$meter_reading['demand_upto']?>)</td>
																<td><?=$meter_reading['fixed_payment']?></td>
															</tr>
															<?php
														}														
													}
													?>
													<tr>
														<td >Due Amount</td>
														<td><?php echo $transaction_details['due_amount'];?></td>
														
													</tr>
													
												</tbody>
											</table><br><br>
											<table width="100%" border="0">
											<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
											</table>
											
											<table width="100%" border="0">
												<tbody>
													<tr>
														<td colspan="2" style="font-size:13px;">
															<?=receipt_url()?>
														</td>
														<td style="text-align:center; font-size:13px;">In Association with<br>
															<?=Collaboration()?>
															
														</td>
													</tr>
												</tbody>
											</table><br><br>
											<div class="col-sm-12 " style="text-align:center;">
												<b>**This is a computer-generated receipt and it does not require a physical signature.**</b>
											</div>
											
								</div>
								
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
			
			
  
<?php
if(isset($from) && $from=='citizen')
{
	echo $this->include('layout_home/footer');
}
else
	echo $this->include('layout_vertical/footer');
?>
