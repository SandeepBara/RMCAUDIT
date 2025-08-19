<?php
$dispay='';
$user_type = $user_type??'';
if(isset($header) && $header=='Citizen')
{
	echo $this->include('layout_home/header');
	$dispay='style="display:none;"';
}
else
{
	echo $this->include('layout_vertical/header');
}

?>

<style>
<?php
if(isset($header) && $header=='Citizen')
{
	?>
	.width-setup{
		max-width: 50%;
		margin-left: auto;
		margin-right: auto;
	}
	<?php
}
?>
</style>
<style>
.water_mark_cover {position: absolute;top: 0;width: 100%;}
.water_mark_cover span {font-size: 160px;color: #cecece;z-index: -10;position: absolute; width: 100%;transform: rotate(-19deg);margin: 245px 0;}
td {line-height: 1.5em;}
.water_mark {
    display: inline-block;
    width: 99%;
    position: absolute;
    top: 33%;
    /*z-index: -1;*/
    text-align: center;
}
.water_mark img {
    opacity: 0.31;
}

</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
    	<div id="content-container">
			<div id="page-head">
				<!--Breadcrumb-->
				<ol class="breadcrumb" <?=$dispay?>>
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Water</a></li>
					<li><a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.$water_conn_id);?>">Water Connection Detail</a></li>
					<li class="active"><a href="#">View Connection Fee</a></li>
				</ol><!--End breadcrumb-->
			</div>

			<div id="page-content">


							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<div class="col-sm-6">
				                        <h3 class="panel-title">Water Connection Payment Receipt</h3>
				                    </div>
				                     <div class="col-sm-6" style="text-align: right; margin-top: 4px;">
				                     	<?php
										if(isset($header) && $header=='Citizen')
										{
											?>
											<a href="<?php echo base_url('WaterPaymentCitizen/payment/'.md5($applicant_details['id']));?>" class="btn btn-info">Back</a>
											<?php
										}elseif($user_type != null)
										{ 
											?>
											<a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.$water_conn_id);?>" class="btn btn-info"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back </a>
											<?php
										}
				                        ?>
				                    </div>
				                    
								</div>
								<div class="col-sm-12 noprint text-right mar-top">
									<button class="btn btn-mint btn-icon" onclick="printDiv('printarea')" style="height:40px;width:60px;">PRINT</button>
								</div>
								<div id="printarea">

								<div class="panel-body"  style="border: solid 2px black;">

									<style type="text/css" media="print">
									@media print {.dontPrint {display:none;}}
									.water_mark_cover span {font-size: 80px;}
									.water_mark {
									    display: inline-block;
									    width: 99%;
									    position: absolute;
									    top: 33%;
									    /*z-index: -1;*/
									    text-align: center;
									}
									.water_mark img {
									    opacity: 0.31;
									}
									</style>
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
													<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">WATER CONNECTION CHARGE PAYMENT RECEIPT </div>
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
													<div >Ward No : <b><?=$applicant_details["ward_no"];?></b> </div>
													<div >Application No : <b><?=$applicant_details["application_no"];?></div>
												</td>
											</tr>
											
										</tbody>
									</table><br>
									<br>
									<table width="100%" border="0">
										<tbody>
											<tr>
												<td>Received From Mr. / Ms. /Mss. : 
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
													 <?php echo ucwords(getIndianCurrency((float)$transaction_details["paid_amount"])); ?>
													&nbsp; Only
													</div>
												</td>
											</tr>
											<?php if(in_array(strtoupper($transaction_details["payment_mode"]),["CASH","ONLINE","CARD","UPI"])){ ?>
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
														&nbsp;&nbsp; <?=$transaction_details["cheque_date"];?>
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
									</table><br>
									<div class="col-sm-12">
											<b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to realisation.</b>
									</div>
									
									<div style="width: 99%; margin: auto; line-height: 35px;"><strong style="font-size: 14px;">WATER CONNECTION FEE DETAILS </strong>
									
									</div>
										<table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
											<tbody>
												<tr>
													<td><b>Description</b></td>
													
													<td ><b>Total Amount</b></td>
													
												</tr>
												
												
											
												<tr>
													<td><?php if(strtoupper($transaction_details['transaction_type'])==strtoupper('Site Inspection')){echo"Site Inspection Charge";}else{echo"Connection Fee";} ?></td>
													<td><?php echo $transaction_details['total_amount'];?></td>
													
												</tr>
												<tr>
													<td>Penalty</td>
													<td><?php echo $transaction_details['penalty'];?></td>
													
												</tr>
												<tr>
													<td>Rebate</td>
													<td><?php echo $transaction_details['rebate'];?></td>
													
												</tr>
												<tr>
													<td>Payable Amount</td>
													<td><?php echo $transaction_details['paid_amount'];?></td>
													
												</tr>
											</tbody>
										</table><br><br>

										<table width="100%" border="0">
										<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>' title="<?=$path;?>" />
										</table>
										
										<table width="100%" border="0">
											<tbody>
												<tr>
													<td colspan="2" style="font-size:13px;">													
														<?php
														echo receipt_url();
														?>
														
													</td>
													<td style="text-align:center; font-size:13px;">In Collaboration with<br>
														<?=Collaboration()?>
													</td>
												</tr>
											</tbody>
										</table><br><br>
										<div class="col-sm-12 " style="text-align:center;">
											<b>**This is a computer-generated receipt and it does not require a physical signature.**</b>
										</div>
										
								</div>
								<div style="padding: 10px 0; font-size:x-small; font-weight:bold;">
									नोट:-  यह केवल पावती रसीद है I अंतिम जल संयोजन स्वीकृति पेपर नहीं है । जल संयोजन की स्वीकृति के पश्चात् ही रांची नगर निगम के निबंधित प्लम्बर के द्वारा जल संयोजन करवाना सुनिश्चित करें । जल संयोजन स्वीकृति हेतु कार्यालय जलापूर्ति शाखा (तीसरा तल्ला) रांची नगर निगम, रांची से संपर्क करें । यदि बिना जल संयोजन की स्वीकृति के आपके द्वारा जल संयोजन लिया जाता है तो दण्डात्मक करवाई की जा सकती है ।
								</div>
								<div class="water_mark"><img src="<?=base_url();?>/public/assets/img/logo/<?=$ulb_id?>.png"/></div>
							</div>
							<!-- <div class="panel">
								<div class="panel-body text-center">
									<button type="button" class="btn btn-primary btn-labeled" onclick="printDiv('print_page')"><i class="fa fa-print"></i>               Print</button>
									
								</div>
							</div> -->
						
			</div>
		</div>
			
			
<script type="text/javascript">
function printDiv(divName)
{
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;

	document.body.innerHTML = printContents;

	window.print();

	document.body.innerHTML = originalContents;
}
</script>
<?php 
	if($user_type=='')
	  echo $this->include('layout_home/footer');
?>
<?= $this->include('layout_vertical/footer');?>