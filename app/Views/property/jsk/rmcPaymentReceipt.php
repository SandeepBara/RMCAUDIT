
<?=$this->include('layout_vertical/header');?>
<?php
$OnePercentPenalty = array_filter($penalty_dtl, function ($var) {
	return ($var['head_name'] == '1% Monthly Penalty');
});
$OnePercentPenalty=array_values($OnePercentPenalty)[0]["amount"] ?? "0.00";

function removeElementWithValue($array, $key, $value){
	foreach($array as $subKey => $subArray){
			if($subArray[$key] == $value){
				unset($array[$subKey]);
			}
	}
	return $array;
}
$penalty_dtl=removeElementWithValue($penalty_dtl, "head_name", "1% Monthly Penalty");
?>
<style>

#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["ulb_mstr_id"];?>.png) !important ;
	background-repeat:no-repeat;
	background-position:center;
}
.container1{
	outline-style: dotted;
	padding: 2%;
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
						<?php
						if($trxn["tran_type"]=="Saf")
						{
							?>
							<li><a href="#"><i class="demo-pli-home"></i></a></li>
							<li><a href="#">SAF</a></li>
							<li><a href="<?=base_url("safdtl/full/".md5($trxn["prop_dtl_id"]));?>">SAF Details</a></li>
							<li class="active">Payment Receipt</li>
							<?php
						}
						if($trxn["tran_type"]=="Property")
						{
							?>
							<li><a href="#"><i class="demo-pli-home"></i></a></li>
							<li><a href="#">Property</a></li>
							<li><a href="<?=base_url("propDtl/full/".md5($trxn["prop_dtl_id"]));?>">Property Details</a></li>
							<li class="active">Payment Receipt</li>
							<?php
						}
						?>
					
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
							<div class="panel panel-dark" id="print_area">
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
											#content-container {padding-top: 0px;}
											#print_watermark {
												background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["ulb_mstr_id"];?>.png) !important;
												background-repeat:no-repeat !important;
												background-position:center !important;
												-webkit-print-color-adjust: exact; 
											}
										}
									</style>
								<div class="panel-body" id="print_watermark">
									<div class="container1">
										
										<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
											<?=$ulb["ulb_name"];?>
										</div>
										
										<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

											<tbody>
												<tr>
													<td height="71" colspan="4" align="center">
														<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">HOLDING TAX RECEIPT </div>
													</td>
												</tr>
												
												<tr>
													<td colspan="3">Receipt No. : &nbsp;<b><?=$trxn["tran_no"];?></b></td>
													<td >Date : &nbsp;<b><?=$trxn["tran_date"];?></b></td>
												</tr>
												<tr>
													<td colspan="3">Department / Section : Revenue Section<br>
												Account Description : Holding Tax &amp; Others</td>
													<td>
														<div >Ward No : &nbsp;<b><?=$prop_dtl["ward_no"];?></b> </div>
														<div >New Ward No : &nbsp;<b><?=$prop_dtl["new_ward_no"];?></b> </div>
														<div >Holding No : &nbsp;<b><?=$prop_dtl["holding_no"];?></b></div>
														<?php if(isset($basic_details[0]['new_holding_no'])){
															if($basic_details[0]['new_holding_no']!=''){  ?>
														<div >New Holding No : &nbsp;<b><?=$basic_details[0]["new_holding_no"];?></b></div>
														<?php } } ?>
													</td>
												</tr>
												
											</tbody>
										</table><br>
										
										<table width="100%" border="0">
											<tbody>
												<tr>
												<td>Received From Mr / Mrs / Miss . : &nbsp;
														<span style="font-size: 14px; font-weight: bold">
														<?php 
														$i=0; 
														foreach($basic_receipt_details as $basic_details) {
															$i++;
															if ($i==1) {
																echo strtoupper($basic_details["owner_name"]);
															} else {
																echo ", ".strtoupper($basic_details["owner_name"]);
															}
														}
														?>
														</span>
													</td>
												</tr>
												<tr>
													<td>Mobile No. : &nbsp;
														<span style="font-size: 14px; font-weight: bold">
														<?=$prop_dtl["mobile_no"];?>
														
														</span>
													</td>
													
												</tr>
												<tr>
													<td>Address : &nbsp;
														<span style="font-size: 14px; font-weight: bold">
															<?=$prop_dtl["prop_address"];?>
															
														</span>
													</td>
													
												</tr>
												<tr>
													<td>
														<div style="float: left;">A Sum of Rs. : &nbsp;</div>
														<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
															<?=$trxn["payable_amt"];?>
															
														&nbsp;
														</div><br>

														<div style="float: left;">(in words) : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
															&nbsp;
														 <?php echo ucwords(getIndianCurrency($trxn["payable_amt"])); ?>
														 Only
														</div>
													</td>
												</tr>
												<?php if($trxn["tran_mode"]=="CASH"){ ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide  : &nbsp;<b><?=$trxn["tran_mode"];?> </b> 
														</div>
												<?php } else if($trxn["tran_mode"]=="ONLINE"){ ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide  : &nbsp;<b><?=$trxn["tran_mode"];?> </b> 
														</div>
												<?php } else if($trxn["tran_mode"]=="CARD"){ ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide  : &nbsp;<b><?=$trxn["tran_mode"];?> </b> 
														</div>
												<?php } else { ?>
												<tr>
													<td height="35">
														<?php if($trxn["tran_mode"]=="CHEQUE"){ ?>
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide Cheque No : &nbsp;
														</div>
														<?php } else{ ?>
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide DD No : &nbsp;
														</div>
														<?php } ?>
														<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_dtl["cheque_no"];?>
														</div>
													</td>
												</tr>
												
												<tr>
													<td height="35">
														<div style="float: left;">Dated  : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_dtl["cheque_date"];?>
														</div>
														<div style="float: left;">Drawn on  : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_dtl["bank_name"];?>
														</div>
													</td>
												</tr>
												<tr>
													<td height="35">
														<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?=$cheque_dtl["branch_name"];?>
														</div>
														<div style="float: left;">Place Of The Bank.</div>
													</td>
												</tr>
												<?php } ?>
												
											</tbody>
										</table>
										<div class="">
											<b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to Realisation</b>
										</div>
										
										<div style="width: 99%; margin: auto; line-height: 35px; border-bottom: #000000 double 2px;"><strong style="font-size: 14px;">HOLDING TAX DETAILS </strong>
										
										</div>
										<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px">
											<tbody>
												<tr style="font-size: 12px;">
													<td width="16%" height="30" nowrap="nowrap"><strong style="font-size: 14px;">Code of Amount</strong></td>
													<td width="34%" align="center"><strong style="font-size: 14px;">Account Description</strong></td>
													<td width="37%" align="center"><strong style="font-size: 14px;">Period</strong></td>
													<td width="13%" align="right"><strong style="font-size: 14px;">Amount</strong></td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100100A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Holding Tax Arrear </span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["arrear_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["arrear_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right"><?=$coll_dtl["arrear_amount"]["holding_tax"];?></td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100100C</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Holding Tax Current </span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["current_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["current_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["current_amount"]["holding_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100200A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Water Tax Arrear </span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["arrear_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["arrear_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["arrear_amount"]["water_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100200C</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Water Tax Current </span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["current_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["current_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["current_amount"]["water_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100400A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Conservancy Tax Arrear </span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["arrear_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["arrear_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["arrear_amount"]["latrine_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style5"><strong style="font-size: 14px;">1100400C</strong></td>
													<td class="auto-style3"><span style="font-size: 15px;margin-left: 40px;">Conservancy Tax Current </span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["current_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["current_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["current_amount"]["latrine_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100500A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Lighting Tax Arrear</span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["arrear_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["arrear_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["arrear_amount"]["lighting_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1100500C</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Lighting Tax Current</span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["current_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["current_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["current_amount"]["lighting_tax"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1105201A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Education Cess Arrear</span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["arrear_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["arrear_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["arrear_amount"]["education_cess"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1105201A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Education Cess Current</span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["current_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["current_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["current_amount"]["education_cess"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1105203A</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Health Cess Arrear</span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["arrear_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["arrear_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["arrear_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["arrear_amount"]["health_cess"] ?? null;?>
													</td>
												</tr>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1105203C</strong></td>
													<td class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Health Cess Current</span></td>
													<td class="auto-style6" align="center">
														<?=$coll_dtl["current_fy"]["min_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["min_year"] ?? null;?> - <?=$coll_dtl["current_fy"]["max_quarter"] ?? null;?>/<?=$coll_dtl["current_fy"]["max_year"] ?? null;?>
													</td>
													<td align="right">
														<?=$coll_dtl["current_amount"]["health_cess"] ?? null;?>
													</td>
												</tr>
												<?php
												
												?>
												<tr>
													<td height="30" class="auto-style4"><strong style="font-size: 14px;">1718002</strong></td>
													<td align="left" class="auto-style2"><span style="font-size: 15px;margin-left: 40px;">Interest on Holding Tax Receivable </span></td>
													<td class="auto-style6" align="center">
													</td>
													<td align="right"><?=$OnePercentPenalty;?></td>
												</tr>

												<tr>
													<td colspan="2">
														<table width="100%" border="0">
															<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$qr_code);?>'>
															<br>
															<?=receipt_url();?>
														</table>
														
														
													</td>
													<td colspan="2">
														<table style="width: 100%; margin-top: 10px;">
															<tr>
																	
																<td align="right" class="auto-style2"><strong style="font-size: 13px;">Total &nbsp;&nbsp;&nbsp;</strong></td>
																<td align="right" nowrap="nowrap">
																	<strong style="font-size: 13px;">
																	<?=(array_sum($coll_dtl["current_amount"]) + array_sum($coll_dtl["arrear_amount"]) + $OnePercentPenalty);?>
																	</strong>
																</td>
															</tr>
															<?php
															foreach($penalty_dtl as $penalty)
															{
																?>
																<tr>
																	
																	<td align="right" class="auto-style2"><strong style="font-size: 13px;"><?=$penalty["head_name"];?>&nbsp;&nbsp;&nbsp;</strong></td>
																	<td align="right" nowrap="nowrap">
																		<strong style="font-size: 13px;">
																		<?=$penalty["amount"];?>
																		</strong>
																	</td>
																</tr>
																<?php
															}
															?>
															
															<tr>
																
																<td class="auto-style6" align="right"><strong style="font-size: 13px;">Amount Received&nbsp;&nbsp;&nbsp;</strong></td>
																<td align="right"><strong style="font-size: 13px;">
																	<?=$trxn["payable_amt"];?>
																	</strong>
																</td>
															</tr>
														</table>
														<br><br><br>
														<table width="100%" border="0" style="float: right;">
															<tbody>
																<tr>
																	<td style="width: 30%;"></td>
																	<td style="width: 70%; font-size:13px;">In Collaboration with<br>
																		<?=$system_name['name']?$system_name['name']:" ";?>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												
											</tbody>
											</table>
											
											
											<div class="col-sm-12 " style="text-align:center;">
												<b>**This is a computer-generated receipt and it does not require a signature.**</b>
											</div>
											
									
									</div>
								</div>
							</div>
							<div class="col-sm-12 text-center">
								<button class="btn btn-mint btn-icon" onclick="printDiv('print_area')"><i class="demo-pli-printer"></i> Print</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			
			
  
<?= $this->include('layout_vertical/footer');?>

<script>
// Collapse Menu Automatically
// $("#container").removeClass("effect aside-float aside-bright mainnav-lg");
// $("#container").addClass("effect aside-float aside-bright mainnav-sm");
</script>