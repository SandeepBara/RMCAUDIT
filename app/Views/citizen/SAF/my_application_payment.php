<?= $this->include('layout_home/header'); ?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
	<!--Page content-->

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
														if (isset($payment_detail)) {
															$i = 1;
															foreach ($payment_detail as $payment_detail) {
														?>
                                                                <tr class="<?= ($payment_detail["status"] == 3) ? 'text-danger' : null; ?>">
                                                                    <td><?= $i++; ?></td>
                                                                    <td class="text-bold"><?= $payment_detail['tran_no']; ?></td>
                                                                    <td><?= $payment_detail['transaction_mode'] ?></td>
                                                                    <td><?= $payment_detail['tran_date']; ?></td>
                                                                    <td><?= $payment_detail['from_qtr'] . " / " . $payment_detail['fy']; ?></td>
                                                                    <td><?= $payment_detail['upto_qtr'] . " / " . $payment_detail['upto_fy']; ?></td>
                                                                    <td><?= $payment_detail['payable_amt']; ?></td>
                                                                    <td><a onClick="PopupCenter('<?= base_url("citizenPaymentReceipt/saf_payment_receipt/" . $ulb_mstr_id . "/" . $payment_detail['id']); ?>', 'SAF Citizen Payment Receipt', 1024, 786)" class="btn btn-primary"> View </a></td>
                                                                </tr>
                                                                <?php
															}
														} else {
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
											if ($demand_detail) {
												$total_due = 0;
												foreach ($demand_detail as $tot_demand) {
													$total_due += $tot_demand['balance'];;
												}
											?>
												<tr>
													<td><b style="color:#bf06fb;">Total Dues</b></td>
													<td><strong style="color:#bf06fb;">:</strong></td>
													<td>
														<b style="color:#bf06fb;"><?php echo $total_due; ?></b>
													</td>
													<td></td>
													<td></td>
													<td></td>
												<tr>
													<td>Dues From</td>
													<td><strong>:</strong></td>
													<td>
														Quarter <?php echo $demand_detail[0]['qtr']; ?> / Year <?php echo $demand_detail[0]['fyear']; ?> </td>
													</td>
													<td>Dues Upto</td>
													<td><strong>:</strong></td>
													<td>
														Quarter <?php echo $demand_detail[count($demand_detail)-1]['qtr']; ?> / Year <?php echo $demand_detail[count($demand_detail)-1]['fyear']; ?> </td>
												</tr>
												<tr>
													<td>Total Quarter(s)</td>
													<td><strong>:</strong></td>
													<td colspan="4"><?php echo count($demand_detail); ?></td>

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
											<th>RWH Penalty</th>
											<th>Demand</th>
											<th>Adjust Amount</th>
										</thead>
										<tbody>

											<?php if ($demand_detail) :
												foreach ($demand_detail as $key=>$demand_detail) :
											?>
													<tr>
														<td><?php echo $key+1; ?></td>
														<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fyear']; ?></td>
														<td><?php echo $demand_detail['amount']; ?></td>
														<td><?php echo $demand_detail['additional_amount']; ?></td>
														<td><?php echo $demand_detail['demand_amount']; ?></td>
														<td><?php echo $demand_detail['adjust_amt']; ?></td>

													</tr>
												<?php endforeach; ?>
											<?php else : ?>
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

					<?php
					if ($emp_details_id == 0) {
						if ($payment_status == 0) {
					?>
							<div class="panel text-center">
								<a href="<?= base_url("CitizenDtl/citizen_saf_confirm_payment"); ?>" class="btn btn-primary">Proceed Payment</a>
							</div>
					<?php
						}
					}
					?>
				</div>


				<div class="col-sm-2">
					<?= $this->include('citizen/SAF/SafCommonPage/saf_left_side'); ?>
				</div>



			</div>
		</div>
	</div>
	<!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer'); ?>
<script>
	$(document).ready(function() {
		$("#sidebarmenu a").each(function() {
			//console.log(decodeURIComponent($(this).attr("href")));
			if (decodeURIComponent($(this).attr("href")).replace(/\\/gi, "/") == decodeURIComponent(window.location.href)) {
				$(this).addClass('active');
			}
		});
	});
</script>
