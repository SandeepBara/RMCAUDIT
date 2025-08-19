<?php echo $this->include('layout_vertical/header'); ?>

<style>
	@media print {
		#print_btn_row {display: none;}
		#content-container {padding-top: 0px;}
		#print_panel {page-break-after: always;}
		.form_content {display: none;}
		#print_watermark {
			background-image: url(<?= base_url("/public/assets/img/logo/".$ulb_mstr_id.".png"); ?>) !important;
			background-repeat: no-repeat !important;
			background-position: center !important;
			-webkit-print-color-adjust: exact;
		}
	}
	#print_watermark {
		background-color: #FFFFFF;
		background-image: url(<?= base_url("/public/assets/img/logo/".$ulb_mstr_id.".png"); ?>) !important;
		background-repeat: no-repeat;
		background-position: center;
	}
</style>
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Property</a></li>
			<li class="active">Payment Receipt</li>
		</ol>
	</div>
	<form action="<?= base_url(); ?>/BulkTaxPrint/bulkTaxPrint2" method="get">
		<div id="page-content" class="form_content">
			<div class="panel panel-mint">
				<div class="panel-heading">
					<h5 class="panel-title">Tax Receipt Bulk Print</h5>
				</div>
				<div class="panel-body">
					<div class="col-md-12">
						<div class="row">
							<label class="col-md-2 text-bold">Transaction Date</label>
							<div class="col-md-3 has-success pad-btm">
								<input type="date" name="tran_date" id="tran_date" class="form-control" value="<?=$tran_date??date('Y-m-d');?>" />
							</div>
							<label class="col-md-2 text-bold">Collector Name</label>
							<div class="col-md-3 has-success pad-btm">
								<select name="tc_id" id='tc_id' class="form-control" required>
									<?php
									if (isset($tc_list)) {
										foreach ($tc_list as $list) {
									?>
										<option value="<?=$list['id'];?>" <?=(isset($tc_id))?($tc_id==$list['id'])?"SELECTED":"":"";?>><?=$list['emp_name'];?></option>
									<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<label class="col-md-2 text-bold">Payment Mode.</label>
							<div class="col-md-3 has-success pad-btm">
								<select name="tran_mode" id='tran_mode' class="form-control" required>
									<option value="CASH" <?=isset($tran_mode)?$tran_mode=="CASH"?"SELECTED":"":"";?>>CASH</option>
									<option value="CHEQUE" <?=isset($tran_mode)?$tran_mode=="CHEQUE"?"SELECTED":"":"";?>>CHEQUE</option>
									<option value="DD" <?=isset($tran_mode)?$tran_mode=="DD"?"SELECTED":"":"";?>>DD</option>
									<option value="CARD" <?=isset($tran_mode)?$tran_mode=="CARD"?"SELECTED":"":"";?>>CARD</option>
								</select>
							</div>
							<label class="col-md-2 text-bold">Transaction Type</label>
							<div class="col-md-3 has-success pad-btm">
								<select name="tran_type" id='tran_type' class="form-control" required>
									<option value="Property" <?=isset($tran_type)?$tran_type=="Property"?"SELECTED":"":"";?>>Property</option>
									<option value="Saf" <?=isset($tran_type)?$tran_type=="Saf"?"SELECTED":"":"";?>>Saf</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-center">
								<input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php
	if (isset($tran_list)) {
		if (count($tran_list) > 0) { ?>
			<div class=".row" style="margin-bottom: 20px;padding:0 50px 0 50px" id="print_btn_row">
				<div class="col-md-6">
					<h5>Total Receipt : <?= count($tran_list) ?></h5>
				</div>
				<div>
					<div class="text-right">
						<button class="btn btn-mint btn-icon" style="cursor:pointer;" onclick="print()"><i class="demo-pli-printer icon-lg"></i>Print All</button>
					</div>
				</div>
			</div>
	<?php }
	}  ?>
	<div id="page-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel">
					<!-- ======= Cta Section ======= -->
					<?php if (isset($tran_list)) {
						foreach ($tran_list as $data) { ?>
							<div id="print_panel" class="panel panel-dark">
								<div class="panel-body" id="print_watermark" style="width:90%;margin-left:5%;outline-style: dotted;padding:5px;color:black; ">
									<div class="col-sm-1"></div>
									<div class="col-sm-10" style="text-align: center;">
										<img style="height:80px;width:80px;" src='<?=base_url();?>/public/assets<?=$logo_path;?>'>
									</div>
									<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
										<?=$ulb_mstr_name;?>
									</div>
									<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
										<tbody>
											<tr>
												<td height="71" colspan="4" align="center">
													<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">HOLDING TAX RECEIPT </div>
												</td>
											</tr>

											<tr>
												<td colspan="3">Receipt No. : &nbsp;<b><?= $data["tran_no"]; ?></b></td>
												<td>Date : &nbsp;<b><?= $data["tran_date"]; ?></b></td>
											</tr>
											<tr>
												<td colspan="3">Department / Section : Revenue Section<br>
													Account Description : Holding Tax &amp; Others</td>
												<td>
													<div>Ward No : &nbsp;<b><?=$data["ward_no"]??"";?></b> </div>
													<div>New Ward No : &nbsp;<b><?=$data["new_ward_no"]??"";?></b> </div>
													<div><?=($data["tran_type"]=="Property")?"Application No.":"Holding No.";?> : &nbsp;<b><?=$data["holding_no"];?></b></div>
													<?php if ($data["new_holding_no"] != '') {  ?>
														<div>New Holding No : &nbsp;<b><?=$data["new_holding_no"];?></b></div>
													<?php } ?>
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
														<?=$data["owner_name"];?>
													</span>
												</td>
											</tr>
											<tr>
												<td>Address : &nbsp;
													<span style="font-size: 14px; font-weight: bold">
														<?=$data["prop_address"];?>
													</span>
												</td>
											</tr>
											<tr>
												<td>
													<div style="float: left;">A Sum of Rs. : &nbsp;</div>
													<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
														<?= $data["payable_amt"]; ?>

														&nbsp;
													</div><br>

													<div style="float: left;">(in words) : &nbsp;</div>
													<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
														&nbsp;
														<?php
														if (isset($data["payable_amt"])) {
															echo ucwords(getIndianCurrency($data["payable_amt"]));
														}
														?>
														Only
													</div>
												</td>
											</tr>
											<?php if ($data["tran_mode"] == "CASH") { ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide : &nbsp;<b><?= $data['tran_mode']; ?> </b>
														</div>
													<?php } else if ($data["tran_mode"] == "CARD") { ?>
												<tr>
													<td height="35">
														<div style="float: left;">
															towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide : &nbsp;<b><?= $tran_mode; ?> </b>
														</div>
													<?php } else { ?>
												<tr>
													<td height="35">
														<?php if ($data["tran_mode"] == "CHEQUE") { ?>
															<div style="float: left;">
																towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide Cheque No : &nbsp;
															</div>
														<?php } else { ?>
															<div style="float: left;">
																towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide DD No : &nbsp;
															</div>
														<?php } ?>
														<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?= $data["cheque_no"]; ?>
														</div>
													</td>
												</tr>

												<tr>
													<td height="35">
														<div style="float: left;">Dated : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?= $data["cheque_date"]; ?>
														</div>
														<div style="float: left;">Drawn on : &nbsp;</div>
														<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?= $data["bank_name"]; ?>
														</div>
													</td>
												</tr>
												<tr>
													<td height="35">
														<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
															&nbsp;&nbsp; <?= $data["branch_name"]; ?>
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
											if ($data["holding_tax"] != 0) { ?>
												<tr>
													<td>Holding Tax</td>
													<td><?= $data["from_qtr"]; ?></td>
													<td><?= $data["from_fyear"]; ?></td>
													<td><?= $data["upto_qtr"]; ?></td>
													<td><?= $data["upto_fyear"]; ?></td>
													<td><?= $data["holding_tax"]; ?></td>
												</tr>
											<?php }
											if ($data["water_tax"] != 0) { ?>
												<tr>
													<td>Water Tax</td>
													<td><?= $data["from_qtr"]; ?></td>
													<td><?= $data["from_fyear"]; ?></td>
													<td><?= $data["upto_qtr"]; ?></td>
													<td><?= $data["upto_fyear"]; ?></td>
													<td><?= $data["water_tax"]; ?></td>
												</tr>
											<?php }
											if ($data["education_cess"] != 0) { ?>
												<tr>
													<td>Education Cess</td>
													<td><?= $data["from_qtr"]; ?></td>
													<td><?= $data["from_fyear"]; ?></td>
													<td><?= $data["upto_qtr"]; ?></td>
													<td><?= $data["upto_fyear"]; ?></td>
													<td><?= $data["education_cess"]; ?></td>
												</tr>
											<?php }
											if ($data["health_cess"] != 0) { ?>
												<tr>
													<td>Health Cess</td>
													<td><?= $data["from_qtr"]; ?></td>
													<td><?= $data["from_fyear"]; ?></td>
													<td><?= $data["upto_qtr"]; ?></td>
													<td><?= $data["upto_fyear"]; ?></td>
													<td><?= $data["health_cess"]; ?></td>
												</tr>
											<?php }
											if ($data["latrine_tax"] != 0) { ?>
												<tr>
													<td>Latrine Tax</td>
													<td><?= $data["from_qtr"]; ?></td>
													<td><?= $data["from_fyear"]; ?></td>
													<td><?= $data["upto_qtr"]; ?></td>
													<td><?= $data["upto_fyear"]; ?></td>
													<td><?= $data["latrine_tax"]; ?></td>
												</tr>
											<?php }
											if ($data["additional_tax"] != 0) { ?>
												<tr>
													<td>RWH Penalty</td>
													<td><?= $data["from_qtr"]; ?></td>
													<td><?= $data["from_fyear"]; ?></td>
													<td><?= $data["upto_qtr"]; ?></td>
													<td><?= $data["upto_fyear"]; ?></td>
													<td><?= $data["additional_tax"]; ?></td>
												</tr>
											<?php } ?>

											<?php if (isset($data["penalty_dtl"])) : ?>
												<?php foreach ($data["penalty_dtl"] as $penalty) : ?>
													<tr>
														<td colspan="5" style="text-align:right;"><?= $penalty["head_name"]; ?></td>
														<td><?= $penalty["amount"]; ?></td>
													</tr>
												<?php endforeach; ?>
											<?php endif; ?>
											<?php $total_paid_amt = $data["payable_amt"]; ?>
											<tr>
												<td colspan="5" style="text-align:right;"><b>Total Amount</b></td>
												<td><b><?= $total_paid_amt; ?></b></td>
											</tr>
											<tr>
												<td colspan="5" style="text-align:right;"><b>Round Off Amount</b></td>
												<td><b><?= $data["round_off"]; ?></b></td>
											</tr>
											<tr>
												<td colspan="5" style="text-align:right;"><b>Total Paid Amount</b></td>
												<td><b><?php echo round($total_paid_amt); ?>.00</b></td>
											</tr>
										</tbody>
									</table><br>
									<table width="100%" border="0">
										<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/' . $data['ss']); ?>'>
									</table>
									<br>
									<div class="col-sm-12 " style="text-align:center;">
										<b>**This is a computer-generated receipt and it does not require a signature.**</b>
									</div>
								</div>

							</div>
					<?php }
					} else {
						echo "<h4 style='color:red;text-align:center;padding:10px 0 10px 0'>No Transaction Found !</h4>";
					} ?>

				</div>
			</div>
		</div>
	</div>
</div>



<?= $this->include('layout_vertical/footer'); ?>

<script>
	// Collapse Menu Automatically
	// $("#container").removeClass("effect aside-float aside-bright mainnav-lg");
	// $("#container").addClass("effect aside-float aside-bright mainnav-sm");
</script>