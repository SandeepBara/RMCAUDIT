<?=$this->include('layout_vertical/header');?>
<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}
@media print {
	.page-break	{ display: block; page-break-before: always; }
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
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Property Bulk Payment Receipt</li>
                    </ol>
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
								<div class="panel-heading page-break">
									<h3 class="panel-title">Property Bulk Payment Receipt	
									</h3>
								</div>
								<div class="col-sm-12 noprint text-right mar-top">
									<button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;">PRINT</button>
								</div>
								<div class="col-sm-1 "></div>
									<?php if(isset($printAllData)): ?>
                                    	<?php foreach ($printAllData as $value):$j++;?>
                                    <div class="panel-body" id="print_watermark">
                                    <div class="col-sm-1 "></div>
									<div class="col-sm-10" style="text-align: center;">
										<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
									</div>
										<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
										<?=$value['ulb_mstr_name']["ulb_name"];?>
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
														<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">HOLDING TAX RECEIPT </div>
													</td>
												</tr>
												
												<tr>
													<td colspan="3">Receipt No. : &nbsp;<b><?=$value['tran_mode_dtl']["tran_no"];?></b></td>
													<td >Date : &nbsp;<b><?=$value['tran_mode_dtl']["tran_date"];?></b></td>
												</tr>
												<tr>
													<td colspan="3">Department / Section : Revenue Section<br>
												Account Description : Holding Tax &amp; Others</td>
													<td>
														<div >Ward No : &nbsp;<b><?=$value['holdingward']["ward_no"];?></b> </div>
														<div >Holding No : &nbsp;<b><?=$value['holdingward']["holding_no"];?></div>
													</td>
												</tr>
												
											</tbody>
										</table><br>
										<br>
										<table width="100%" border="0">
											<tbody>
												<tr>
													<td>Received From Mr / Mrs / Miss . : &nbsp;
														<?php $i=0;
														foreach($value['basic_details'] as $details):?>
															<?php if($i>=1){ ?>
																,
															<?php }$i++; ?>
															<span style="font-size: 14px; font-weight: bold">
																<?=strtoupper($details["owner_name"]);?>
															</span>
															
														<?php endforeach; ?>
													</td>
												</tr>
												<tr>
													<td>Mobile No. :  &nbsp;
														<?php $i=0;
														foreach($value['basic_details'] as $details):?>
															<?php if($i>=1){ ?>
																,
															<?php }$i++; ?>
															<span style="font-size: 14px; font-weight: bold">
																<?=strtoupper($details["mobile_no"]);?>
															</span>
														<?php endforeach; ?>
													</td>
												</tr>
												<tr>
													<td>Address :  &nbsp;
														<span style="font-size: 14px; font-weight: bold">
															<?=$value['basic_details'][0]["prop_address"];?>
														</span>
													</td>
												</tr>
												<tr>
													<td>
														<div style="float: left;">A Sum of Rs. : &nbsp;</div>
														<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
															<?=$value['tran_mode_dtl']["payable_amt"];?>
															
														&nbsp;
														</div><br>

														<div style="float: left;">(in words) : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
															&nbsp;
														 <?php echo ucwords(getIndianCurrency($value['tran_mode_dtl']["payable_amt"])); ?>
														 Only
														</div>
													</td>
												</tr>
												<?php if($value['payMode']["transaction_mode"]=="CASH"){ ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide  : &nbsp;<b><?=$value['payMode']["transaction_mode"];?> </b> 
														</div>
												<?php } else { ?>
												<tr>
													<td height="35">
														<?php if($value['payMode']["transaction_mode"]=="CHEQUE"){ ?>
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide Cheque No : &nbsp;
														</div>
														<?php } else{ ?>
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide DD No : &nbsp;
														</div>
														<?php } ?>
														<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$value['chqDD_details']["cheque_no"];?>
														</div>
													</td>
												</tr>
												
												<tr>
													<td height="35">
														<div style="float: left;">Dated  : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$value['chqDD_details']["cheque_date"];?>
														</div>
														<div style="float: left;">Drawn on  : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$value['chqDD_details']["bank_name"];?>
														</div>
													</td>
												</tr>
												<tr>
													<td height="35">
														<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$value['chqDD_details']["branch_name"];?>
														</div>
														<div style="float: left;">Place Of The Bank.</div>
													</td>
												</tr>
												<?php } ?>
												
											</tbody>
										</table><br>
										<div class="col-sm-12">
												<b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to Realisation</b>
										</div><br>
										
										<div style="width: 99%; margin: auto; line-height: 35px; border-bottom: #000000 double 2px;"><strong style="font-size: 14px;">HOLDING TAX DETAILS </strong>
										
										</div>
											<table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
												<tbody>
													<tr>
														<td rowspan="3"><b>Description</b></td>
														<td colspan="4"><b>Period</b></td>
														<td rowspan="3"><b>Total Amount</b></td>
														
													</tr>
													<tr>
														<td colspan="2">From</td>
														<td colspan="2">To</td>
													</tr>
													<tr>
														<td>QTR</td>
														<td>FY</td>
														<td>QTR</td>
														<td>FY</td>
													</tr>
													<?php
													if($value['coll_dtl']["holding_tax"]!=0){ ?>
													<tr>
														<td>Holding Tax</td>
														<td><?=$value['tran_mode_dtl']["from_qtr"];?></td>
														<td><?=$value['fyFrom']["fy"];?></td>
														<td><?=$value['tran_mode_dtl']["upto_qtr"];?></td>
														<td><?=$value['fyUpto']["fyUpto"];?></td>
														<td><?=$value['coll_dtl']["holding_tax"];?></td>
													</tr>
													<?php }
													if($value['coll_dtl']["water_tax"]!=0){ ?>
													<tr>
														<td>Water Tax</td>
														<td><?=$value['tran_mode_dtl']["from_qtr"];?></td>
														<td><?=$value['fyFrom']["fy"];?></td>
														<td><?=$value['tran_mode_dtl']["upto_qtr"];?></td>
														<td><?=$value['fyUpto']["fyUpto"];?></td>
														<td><?=$value['coll_dtl']["water_tax"];?></td>
													</tr>
													<?php } 
													if($value['coll_dtl']["education_cess"]!=0){ ?>
													<tr>
														<td>Education Cess</td>
														<td><?=$value['tran_mode_dtl']["from_qtr"];?></td>
														<td><?=$value['fyFrom']["fy"];?></td>
														<td><?=$value['tran_mode_dtl']["upto_qtr"];?></td>
														<td><?=$value['fyUpto']["fyUpto"];?></td>
														<td><?=$value['coll_dtl']["education_cess"];?></td>
													</tr>
													<?php } 
													if($value['coll_dtl']["health_cess"]!=0){ ?>
													<tr>
														<td>Health Cess</td>
														<td><?=$value['tran_mode_dtl']["from_qtr"];?></td>
														<td><?=$value['fyFrom']["fy"];?></td>
														<td><?=$value['tran_mode_dtl']["upto_qtr"];?></td>
														<td><?=$value['fyUpto']["fyUpto"];?></td>
														<td><?=$value['coll_dtl']["health_cess"];?></td>
													</tr>
													<?php } 
													if($value['coll_dtl']["latrine_tax"]!=0){ ?>
													<tr>
														<td>Latrine Tax</td>
														<td><?=$value['tran_mode_dtl']["from_qtr"];?></td>
														<td><?=$value['fyFrom']["fy"];?></td>
														<td><?=$value['tran_mode_dtl']["upto_qtr"];?></td>
														<td><?=$value['fyUpto']["fyUpto"];?></td>
														<td><?=$value['coll_dtl']["latrine_tax"];?></td>
													</tr>
													<?php } 
													if($value['coll_dtl']["additional_tax"]!=0){ ?>
													<tr>
														<td>RWH Penalty</td>
														<td><?=$value['tran_mode_dtl']["from_qtr"];?></td>
														<td><?=$value['fyFrom']["fy"];?></td>
														<td><?=$value['tran_mode_dtl']["upto_qtr"];?></td>
														<td><?=$value['fyUpto']["fyUpto"];?></td>
														<td><?=$value['coll_dtl']["additional_tax"];?></td>
													</tr>
													<?php } ?>
													<tr>
														<td colspan="5" style="text-align:right;">Rebate Amount</td>
														<td><?=$value['tran_mode_dtl']["discount_amt"];?></td>
													</tr>
													<?php if($value['penalty_dtl']): ?>
													<?php foreach($value['penalty_dtl'] as $penalty_dtl):?>
													<tr>
														<td colspan="5" style="text-align:right;"><?=$penalty_dtl["head_name"];?></td>
														<td><?=$penalty_dtl["amount"];?></td>
													</tr>
													
													<?php endforeach; ?>
													<?php endif; ?>
													<?php $total_paid_amt = $value['tran_mode_dtl']["payable_amt"]; ?>
													<tr>
														<td colspan="5" style="text-align:right;"><b>Total Amount</b></td>
														<td><b><?=$total_paid_amt;?></b></td>
													</tr>
													<tr>
														<td colspan="5" style="text-align:right;"><b>Round Off Amount</b></td>
														<td><b><?=$value['tran_mode_dtl']["round_off"];?></b></td>
													</tr>
													<tr>
														<td colspan="5" style="text-align:right;"><b>Total Paid Amount</b></td>
														<td><b><?php echo round($total_paid_amt);?></b></td>
													</tr>
												</tbody>
											</table><br>
											<table width="100%" border="0">
											<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$value['ss']);?>'>
											</table>
											
											<table width="100%" border="0">
												<tbody>
													<tr>
														<td colspan="2" style="font-size:13px;">For Details Please Visit : www.ranchimunicipal.com
															<br>
															OR Call us at 18008904665
														</td>
														<td style="text-align:center; font-size:13px;">In Association with<br>
															Sri Publication & Stationers Pvt. Ltd.<br>
															Ashok Nagar,<br>
															Ranchi - 834002
														</td>
													</tr>
												</tbody>
											</table><br>
											<div class="col-sm-12 " style="text-align:center;">
												<b>**This is a computer-generated receipt and it does not require a signature.**</b>
											</div>
										</div>
											<?php if($len!=$j){?>
										<div class="page-break"></div>
									<?php }
									?>
										<?php endforeach;?>
								<?php endif; ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			
			
  
<?= $this->include('layout_vertical/footer');?>

