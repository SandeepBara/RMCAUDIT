
<?= $this->include('layout_vertical/header');?>
<style>
@media print {
	#content-container {padding-top: 0px;}
	#print_watermark {
        background-image:url(<?=base_url()."/public/assets/img/logo/".$ulb_mstr_name["ulb_mstr_id"].".png"; ?>) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact; 
    }
}
#print_watermark{
	background-color:#FFFFFF;
	background-image:url(<?=base_url()."/public/assets/img/logo/".$ulb_mstr_name["ulb_mstr_id"].".png"; ?>) !important;
	background-repeat:no-repeat;
	background-position:center;
	
}
</style>
<!--CONTENT CONTAINER-->		
<div id="content-container">
	<div id="page-head">
		<div id="page-title">
			<!--<h1 class="page-header text-overflow">Designation List</h1>//-->
		</div>
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">GBSAF</a></li>
			<li><a href="<?=base_url("govsafDetailPayment/gov_saf_application_details");?>/<?=md5($tran_mode_dtl["govt_saf_dtl_id"]);?>">GBSAF Details</a></li>
			<li class="active">Payment Receipt</li>
		</ol>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel">
					<div class="panel panel-dark">
						<div class="panel-body" id="print_watermark">
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
							<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
								<tbody>
									<tr>
										<td height="71" colspan="4" align="center">
											<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">HOLDING TAX RECEIPT </div>
										</td>
									</tr>
									
									<tr>
										<td colspan="3">Receipt No. : &nbsp;<b><?=$tran_mode_dtl["tran_no"];?></b></td>
										<td >Date : &nbsp;<b><?=$tran_mode_dtl["tran_date"];?></b></td>
									</tr>
									<tr>
										<td colspan="3">Department / Section : Revenue Section<br>
									Account Description : Holding Tax &amp; Others</td>
										<td>
											<div >Ward No : &nbsp;<b><?=$basic_details["ward_no"];?></b> </div>
											<div >Application No : &nbsp;<b><?=$basic_details["application_no"];?></div>
										</td>
									</tr>
									
								</tbody>
							</table>
							<br>
							<br>
							<table width="100%" border="0">
								<tbody>
									<tr>
										<td>Officer Name / Designation: &nbsp;
											<span style="font-size: 14px; font-weight: bold">
												<?php echo $basic_details["designation"]; ?>
											</span>
										</td>
									</tr>
									
									<tr>
										<td>Building Address : &nbsp;
											<span style="font-size: 14px; font-weight: bold">
												<?php echo $basic_details["building_colony_address"]; ?>
											</span>
										</td>
									</tr>
									<tr>
										<td>
											<div style="float: left;">A Sum of Rs. : &nbsp;</div>
											<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
												<?php echo round($tran_mode_dtl["payable_amt"]);?>.00
												
											&nbsp;
											</div><br>

											<div style="float: left;">(in words) : &nbsp;</div>
											<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
												&nbsp;
												<?php echo ucwords(getIndianCurrency(round($tran_mode_dtl["payable_amt"]))); ?>
											Only
											</div>
										</td>
									</tr>
									
									<?php 
									if($tran_mode_dtl["tran_mode"]=="CASH")
									{
										?>
										<tr>
											<td height="35">
												<div style="float: left;">
													towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide  : &nbsp;<b><?=$tran_mode_dtl["tran_mode"];?> </b> 
												</div>
											</td>
										</tr>
										<?php 
									}
									else if($tran_mode_dtl["tran_mode"]=="ONLINE")
									{
										?>
										<tr>
											<td height="35">
												<div style="float: left;">
													towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide  : &nbsp;<b><?=$tran_mode_dtl["tran_mode"];?> </b> 
												</div>
											</td>
										</tr>
										<?php 
									}
									else
									{
										?>
										<tr>
											<td height="35">
												<?php 
												if($tran_mode_dtl["tran_mode"]=="CHEQUE")
												{
													?>
													<div style="float: left;">
														towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide <b> <?=$tran_mode_dtl["tran_mode"];?> </b> Cheque No : &nbsp;
													</div>
													<?php 
												}
												else
												{
													?>
													<div style="float: left;">
														towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide <b> <?=$tran_mode_dtl["tran_mode"];?> </b> DD No : &nbsp;
													</div>
													<?php 
												}
												?>
												<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
													&nbsp;&nbsp; <?=$chqDD_details["cheque_no"] ?? null;?>
												</div>
											</td>
										</tr>
									
										<tr>
											<td height="35">
												<div style="float: left;">Dated  : &nbsp;</div>
												<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
													&nbsp;&nbsp; <?=$chqDD_details["cheque_date"] ?? null;?>
												</div>
												<div style="float: left;">Drawn on : &nbsp;</div>
												<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px;font-weight: 700;">
													&nbsp;&nbsp; <?=$chqDD_details["bank_name"] ?? null;?>
												</div>
											</td>
										</tr>
										<tr>
											<td height="35">
												<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
													&nbsp;&nbsp; <?=$chqDD_details["branch_name"] ?? null;?>
												</div>
												<div style="float: left;">Place Of The Bank. </div>
											</td>
										</tr>
										<?php 
									}
									?>
								</tbody>
							</table>
							<br>
							<div class="col-sm-12">
								<b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to Realisation</b>
							</div>
							<div style="width: 99%; margin: auto; line-height: 35px; border-bottom: #000000 double 2px;">
								<strong style="font-size: 14px;">HOLDING TAX DETAILS </strong>
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
									<?php if($coll_dtl["holding_tax"]!=0){ ?>
									<tr>
										<td>Holding Tax</td>
										<td><?=$tran_mode_dtl["from_qtr"];?></td>
										<td><?=$tran_mode_dtl["from_fyear"];?></td>
										<td><?=$tran_mode_dtl["upto_qtr"];?></td>
										<td><?=$tran_mode_dtl["upto_fyear"];?></td>
										<td><?=$coll_dtl["holding_tax"];?></td>
									</tr>
									<?php }
									if ($coll_dtl["water_tax"]!=0){ ?>
									<tr>
										<td>Water Tax</td>
										<td><?=$tran_mode_dtl["from_qtr"];?></td>
										<td><?=$tran_mode_dtl["from_fyear"];?></td>
										<td><?=$tran_mode_dtl["upto_qtr"];?></td>
										<td><?=$tran_mode_dtl["upto_fyear"];?></td>
										<td><?=$coll_dtl["water_tax"];?></td>
									</tr>
									<?php } 
									if($coll_dtl["education_cess"]!=0){ ?>
									<tr>
										<td>Education Cess</td>
										<td><?=$tran_mode_dtl["from_qtr"];?></td>
										<td><?=$tran_mode_dtl["from_fyear"];?></td>
										<td><?=$tran_mode_dtl["upto_qtr"];?></td>
										<td><?=$tran_mode_dtl["upto_fyear"];?></td>
										<td><?=$coll_dtl["education_cess"];?></td>
									</tr>
									<?php } 
									if($coll_dtl["health_cess"]!=0){ ?>
									<tr>
										<td>Health Cess</td>
										<td><?=$tran_mode_dtl["from_qtr"];?></td>
										<td><?=$tran_mode_dtl["from_fyear"];?></td>
										<td><?=$tran_mode_dtl["upto_qtr"];?></td>
										<td><?=$tran_mode_dtl["upto_fyear"];?></td>
										<td><?=$coll_dtl["health_cess"];?></td>
									</tr>
									<?php } 
									if($coll_dtl["latrine_tax"]!=0){ ?>
									<tr>
										<td>Latrine Tax</td>
										<td><?=$tran_mode_dtl["from_qtr"];?></td>
										<td><?=$tran_mode_dtl["from_fyear"];?></td>
										<td><?=$tran_mode_dtl["upto_qtr"];?></td>
										<td><?=$tran_mode_dtl["upto_fyear"];?></td>
										<td><?=$coll_dtl["latrine_tax"];?></td>
									</tr>
									<?php } 
									if($coll_dtl["additional_tax"]!=0){ ?>
									<tr>
										<td>RWH Penalty</td>
										<td><?=$tran_mode_dtl["from_qtr"];?></td>
										<td><?=$tran_mode_dtl["from_fyear"];?></td>
										<td><?=$tran_mode_dtl["upto_qtr"];?></td>
										<td><?=$tran_mode_dtl["upto_fyear"];?></td>
										<td><?=$coll_dtl["additional_tax"];?></td>
									</tr>
									<?php } ?>
									
									<?php 
										if($penalty_dtl){ 
											foreach($penalty_dtl as $penalty_dtl_head) {
									?>
									<tr>
										<td colspan="5" style="text-align:right;"><?=$penalty_dtl_head["head_name"];?></td>
										<td><?=$penalty_dtl_head["amount"];?></td>
									</tr>
									<?php 
											} 
									 	}
									 ?>
									<?php $total_paid_amt = $tran_mode_dtl["payable_amt"]; ?>
									<tr>
										<td colspan="5" style="text-align:right;"><b>Total Amount</b></td>
										<td><b><?=$total_paid_amt;?></b></td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:right;"><b>Round Off Amount</b></td>
										<td><b><?=$tran_mode_dtl["round_off"];?></b></td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:right;"><b>Total Paid Amount</b></td>
										<td><b><?php echo round($total_paid_amt);?>.00</b></td>
									</tr>
									
								</tbody>
							</table>
					<br>
							<table width="100%" border="0">
								<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
							</table>
									
							<table width="100%" border="0">
								<tbody>
									<tr>
										<td colspan="2" style="font-size:13px;"><?=receipt_url();?>
										</td>
										<td style="text-align:center; font-size:13px;">In Association with<br>
											Sri Publication & Stationers Pvt. Ltd.
										</td>
									</tr>
								</tbody>
							</table>
						
							<div class="col-sm-12" style="text-align:center;">
								<br>
								<b>**This is a computer-generated receipt and it does not require a signature.**</b>
							</div>
						</div>
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
