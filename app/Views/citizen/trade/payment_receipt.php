<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name["ulb_mstr_id"];?>.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb_mstr_name["ulb_mstr_id"];?>.png) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}

</style>
<head>
    
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/plugins/pace/pace.min.js"></script>

    
</head>

<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
        <div id="content-container">
			<div id="page-content">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel">
							
				<!-- ======= Cta Section ======= -->
							<div class="panel panel-dark">
								
								<div class="panel-body" id="print_watermark">
									<div class="panel-body" style="border: solid 2px black;">
										<div class="col-sm-1"></div>
										<div class="col-sm-10" style="text-align: center;">
											<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
										</div>
										<div class="col-sm-1 noprint text-right">
											<button class="btn btn-mint btn-icon" onclick="print()"><i class="demo-pli-printer icon-lg"></i></button>
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
														<div style="width: 60%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">MUNICIPAL LICENSE PAYMENT RECEIPT </div>
													</td>
												</tr>
												
												<tr>
													<td colspan="3">Receipt No. : <b><?=$transaction_details["transaction_no"];?></b></td>
													<td >Date :<b><?=date('d-m-Y',strtotime($transaction_details["transaction_date"]));?></b></td>
												</tr>
												<tr>
													<td colspan="3">Department / Section : Municipal License Section<br>
												Account Description : Municipal License Fee Charges</td>
													<td>
														<div >Ward No :<b><?=$ward_no['ward_no'];?></b> </div>
														<div >Application No :<b><?=$applicant_details["application_no"];?></div>
													</td>
												</tr>
												
											</tbody>
										</table><br>
										<br>
										<table width="100%" border="0">
											<tbody>
												<tr>
													<td>Received From Shri / Smt. : 
														<?php //foreach($basic_details as $basic_details):?>
														<span style="font-size: 14px; font-weight: bold">
															<?=$applicant_details["applicant_name"];?>
														</span>
														<?php //endforeach; ?>
													</td>
												</tr>
												<tr>
													<td>Mobile No. :
														<span style="font-size: 14px; font-weight: bold">
															<?=$applicant_details["mobile_no"];?>
														</span>
													</td>
												</tr>
												<tr>
													<td>Address : 
														<span style="font-size: 14px; font-weight: bold">
															<?=$applicant_details["address"];?>
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
												<?php if(in_array(strtolower($transaction_details["payment_mode"]), ['cash', 'online'])){ ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards :<strong style="font-size: 14px;">Municipal License Fee</strong>&nbsp;&nbsp; vide <b><?=$transaction_details["payment_mode"];?> </b> 
														</div>
												<?php } else { ?>
												<tr>
													<td height="35">
														<?php if($transaction_details["payment_mode"]=="CHEQUE"){ ?>
														<div style="float: left;">
															towards :<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; vide Cheque No
														</div>
														<?php } else{ ?>
														<div style="float: left;">
															towards :<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; vide DD No
														</div>
														<?php } ?>
														<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_details["cheque_no"];?>
														</div>
													</td>
												</tr>
												
												<tr>
													<td height="35">
														<div style="float: left;">Dated </div>
														<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_details["cheque_date"];?>
														</div>
														<div style="float: left;">Drawn on </div>
														<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px;">
															&nbsp;&nbsp; <?=$cheque_details["bank_name"];?>
														</div>
													</td>
												</tr>
												<tr>
													<td height="35">
														<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_details["branch_name"];?>
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
										
										<div style="width: 99%; margin: auto; line-height: 35px; border-bottom: #000000 double 2px;"><strong style="font-size: 14px;">MUNICIPAL LICENSE FEE DETAILS </strong>
										
										</div>
										<table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
											<tbody>
												<tr>
													<td><b>Description</b></td>
													
													<td ><b>Total Amount</b></td>
													
												</tr>
												
											
											
												<tr>
													<td>Municipal License Fee</td>
													<td><?php echo $transaction_details['paid_amount']-$transaction_details['penalty'].'.00';?></td>
													
												</tr>
												<tr>
													<!-- <td>Deniel Fee</td>
													<td><?=$transaction_details['penalty']?$transaction_details['penalty']:0.00;?></td> -->
													<td>Delay Fee</td>
													<td><?php echo $delayApplyLicence;?></td>
												</tr>	
												<tr>
													<td>Denial Fee</td>
													<td><?php echo $denialApply?$denialApply:"0.00";?></td>
													
												</tr>												
												<tr>
													<td>Total</td>
													<td><?php echo $transaction_details['paid_amount'];?></td>
													
												</tr>
											</tbody>
										</table><br><br>
										<table width="100%" border="0">
										<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
										</table>
										
										<table width="100%" border="0">
											<tbody>
												<tr>
													<td colspan="2" style="font-size:13px;">For Details Please Visit : udhd.jhakhand.gov.in
														<br>
														Call us at 18008904115 OR 0651-3500700
													</td>
													<td style="text-align:center; font-size:13px;">In Association with<br>
														Sri Publication & Stationers Pvt. Ltd.<br>
														
													</td>
												</tr>
											</tbody>
										</table><br><br>
										<div class="col-sm-12 " style="text-align:center;">
											<b>**This is a computer-generated receipt and it does not require a signature.**</b>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
			

