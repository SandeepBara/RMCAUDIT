<?= $this->include('layout_home/header'); ?>
<style>
	@media print {
		#print_btn_row {display: none;}
		#content-container {padding-top: 0px;}
		#print_panel {page-break-after: always;}
		.form_content {display: none;}
		#print_watermark {
			background-image: url(<?= base_url("/public/assets/img/logo/1.png"); ?>) !important;
			background-repeat: no-repeat !important;
			background-position: center !important;
			-webkit-print-color-adjust: exact;
		}
	}
	#print_watermark {
		background-color: #FFFFFF;
		background-image: url(<?= base_url("/public/assets/img/logo/1.png"); ?>) !important;
		background-repeat: no-repeat;
		background-position: center;
	}
</style>
<div id="page-content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Payment Receipt
						</h3>
						<button class="btn btn-mint btn-icon" onclick="print()" style="float:right;"><i class="demo-pli-printer icon-lg"></i></button>
					</div>
					<div class="panel-body" id="print_watermark">
						<br><br>

						<div class="col-sm-12" style="text-align: center;">
							<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png'); ?>'>
						</div>
						<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
							<?= $ulb_mstr_name["ulb_name"]; ?>
						</div>

						<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

							<tbody>
								<tr>
									<td height="71" colspan="4" align="center">
										<div style="width: 50%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">HOLDING TAX RECEIPT </div>
									</td>
								</tr>

								<tr>
									<td colspan="3">Receipt No. : &nbsp;<b><?= $tran_list["tran_no"]; ?></b></td>
									<td>Date : &nbsp;<b><?= $tran_list["tran_date"]; ?></b></td>
								</tr>
								<tr>
									<td colspan="3">Department / Section : Revenue Section<br>
										Account Description : Holding Tax &amp; Others</td>
									<td>
										<div>Ward No : &nbsp;<b><?= $tran_list["ward_no"]; ?></b> </div>
										<div>New Ward No : &nbsp;<b><?= $tran_list["new_ward_no"]; ?></b> </div>
										<?php if ($tran_list["holding_no"]!='') { ?>
											<div><?=($tran_list["tran_type"]=="Saf")?"Application No.":"Holding No.";?> : &nbsp;<b><?= $tran_list["holding_no"]; ?></b></div>
										<?php } ?>
										<?php if ($tran_list["new_holding_no"]!='') { ?>
											<div>New Holding No : &nbsp;<b><?= $tran_list["new_holding_no"]; ?></b></div>
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
											<?=$tran_list["owner_name"];?>
										</span>
									</td>
								</tr>
								<tr>
									<td>Address : &nbsp;
										<span style="font-size: 14px; font-weight: bold">
											<?= $tran_list["prop_address"]; ?>
										</span>
									</td>
								</tr>
								<tr>
									<td>
										<div style="float: left;">A Sum of Rs. : &nbsp;</div>
										<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
											<?= $tran_list["payable_amt"]; ?>
											&nbsp;
										</div><br>

										<div style="float: left;">(in words) : &nbsp;</div>
										<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
											&nbsp;
											<?php echo ucwords(getIndianCurrency($tran_list["payable_amt"])); ?>
											Only
										</div>
									</td>
								</tr>
								<?php if ($tran_list["tran_mode"] == "CASH") { ?>
									<tr>
										<td height="35">
											<div style="float: left;">
												towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide : &nbsp;<b><?= $tran_list["tran_mode"]; ?> </b>
											</div>
										<?php } else if ($tran_list["tran_mode"] == "ONLINE") { ?>
									<tr>
										<td height="35">
											<div style="float: left;">
												towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide : &nbsp;<b><?= $tran_list["tran_mode"]; ?> </b>
											</div>
										<?php } else { ?>
									<tr>
										<td height="35">
											<?php if ($tran_list["tran_mode"] == "CHEQUE") { ?>
												<div style="float: left;">
													towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide Cheque No : &nbsp;
												</div>
											<?php } else { ?>
												<div style="float: left;">
													towards : &nbsp;<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; Vide DD No : &nbsp;
												</div>
											<?php } ?>
											<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
												&nbsp;&nbsp; <?= $tran_list["cheque_no"]; ?>
											</div>
										</td>
									</tr>

									<tr>
										<td height="35">
											<div style="float: left;">Dated : &nbsp;</div>
											<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
												&nbsp;&nbsp; <?= $tran_list["cheque_date"]; ?>
											</div>
											<div style="float: left;">Drawn on : &nbsp;</div>
											<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
												&nbsp;&nbsp; <?= $tran_list["bank_name"]; ?>
											</div>
										</td>
									</tr>
									<tr>
										<td height="35">
											<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
												&nbsp;&nbsp; <?= $tran_list["branch_name"]; ?>
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
								if ($tran_list["holding_tax"] != 0) { ?>
									<tr>
										<td>Holding Tax</td>
										<td><?= $tran_list["from_qtr"]; ?></td>
										<td><?= $tran_list["from_fyear"]; ?></td>
										<td><?= $tran_list["upto_qtr"]; ?></td>
										<td><?= $tran_list["upto_fyear"]; ?></td>
										<td><?= $tran_list["holding_tax"]; ?></td>
									</tr>
								<?php }
								if ($tran_list["water_tax"] != 0) { ?>
									<tr>
										<td>Water Tax</td>
										<td><?= $tran_list["from_qtr"]; ?></td>
										<td><?= $tran_list["from_fyear"]; ?></td>
										<td><?= $tran_list["upto_qtr"]; ?></td>
										<td><?= $tran_list["upto_fyear"]; ?></td>
										<td><?= $tran_list["water_tax"]; ?></td>
									</tr>
								<?php }
								if ($tran_list["education_cess"] != 0) { ?>
									<tr>
										<td>Education Cess</td>
										<td><?= $tran_list["from_qtr"]; ?></td>
										<td><?= $tran_list["from_fyear"]; ?></td>
										<td><?= $tran_list["upto_qtr"]; ?></td>
										<td><?= $tran_list["upto_fyear"]; ?></td>
										<td><?= $tran_list["education_cess"]; ?></td>
									</tr>
								<?php }
								if ($tran_list["health_cess"] != 0) { ?>
									<tr>
										<td>Health Cess</td>
										<td><?= $tran_list["from_qtr"]; ?></td>
										<td><?= $tran_list["from_fyear"]; ?></td>
										<td><?= $tran_list["upto_qtr"]; ?></td>
										<td><?= $tran_list["upto_fyear"]; ?></td>
										<td><?= $tran_list["health_cess"]; ?></td>
									</tr>
								<?php }
								if ($tran_list["latrine_tax"] != 0) { ?>
									<tr>
										<td>Latrine Tax</td>
										<td><?= $tran_list["from_qtr"]; ?></td>
										<td><?= $tran_list["from_fyear"]; ?></td>
										<td><?= $tran_list["upto_qtr"]; ?></td>
										<td><?= $tran_list["upto_fyear"]; ?></td>
										<td><?= $tran_list["latrine_tax"]; ?></td>
									</tr>
								<?php }
								if ($tran_list["additional_tax"] != 0) { ?>
									<tr>
										<td>RWH Penalty</td>
										<td><?= $tran_list["from_qtr"]; ?></td>
										<td><?= $tran_list["from_fyear"]; ?></td>
										<td><?= $tran_list["upto_qtr"]; ?></td>
										<td><?= $tran_list["upto_fyear"]; ?></td>
										<td><?= $tran_list["additional_tax"]; ?></td>
									</tr>
								<?php } ?>
								<?php if (isset($tran_list["penalty_dtl"])) { ?>
									<?php foreach ($tran_list["penalty_dtl"] AS $penalty) { ?>
										<tr>
										<td colspan="5" style="text-align:right;">
                                                            <?php if($penalty["head_name"]=="1% Monthly Penalty"){
                                                                echo "1% Monthly Interest";
                                                                }else{
                                                                  echo $penalty["head_name"];
                                                                }
                                                                ?>
                                                        </td>
											<td><?= $penalty["amount"]; ?></td>
										</tr>
									<?php } ?>
								<?php } ?>


								<tr>
									<td colspan="5" style="text-align:right;">Total Payable Amount</td>
									<td><b><?= number_format($tran_list["demand_amt"] + $tran_list["penalty_amt"] - $tran_list["discount_amt"], 2); ?></b></td>
								</tr>
								<tr>
									<td colspan="5" style="text-align:right;"><b>Total Paid Amount</b></td>
									<td><b><?= $tran_list["payable_amt"]; ?></b></td>
								</tr>
							</tbody>
						</table><br>
						<table width="100%" border="0">
							<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/' . $ss); ?>'>
							<!-- <span style="float:right;">
                                <img style="width:120px;height:100px;" src='<?php echo base_url('public/assets/img/chunav_ka_parv.png'); ?>'>
                                <p class="text-center" style="font-weight: 700">Toll Free - 1950</p>
                            </span> -->
						</table>
						<div class="col-sm-12" style="text-align:center;">
							<b>**This is a computer-generated receipt and it does not require a signature.**</b>
						</div>

					</div>

				</div>
				<br><br>
			</div>
		</div>
	</div>
</div>
<?= $this->include('layout_home/footer'); ?>