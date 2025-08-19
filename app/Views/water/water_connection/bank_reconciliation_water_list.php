<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>
<style type="text/css">
	.text-bold {
		font-weight: 900;
	}
</style>
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
	<div id="page-head">
		<!--Breadcrumb-->
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Accounts</a></li>
			<li class="active">Bank Reconciliation</li>
		</ol>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<!--===================================================-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h5 class="panel-title">Bank Reconciliation</h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<form class="form-horizontal" method="GET" action="<?= base_url(''); ?>/BankReconciliationAllModuleList/detail">
						<div class="row">
							<div class="col-md-6">
								<div class="radio">
									<input type="radio" id="by_holding_dtl" class="magic-radio" name="filter_date" value="by_tr_date" checked>
									<label for="by_holding_dtl">By Transaction Date</label>

									<input type="radio" id="by_owner_dtl" class="magic-radio" name="filter_date" value="by_ch_clear_date" <?= isset($filter_date) && $filter_date == 'by_ch_clear_date' ? 'checked' : '' ?>>
									<label for="by_owner_dtl">By Cheque Clearence Date</label>
									<!-- <input type="radio" id="by_cheque_dtl" class="magic-radio" name="filter_date" value="by_cheque_no" <?= isset($filter_date) && $filter_date == 'by_cheque_no' ? 'checked' : '' ?>>
									<label for="by_cheque_dtl">By Cheque No.</label> -->
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2" id="from_date_cont">
								<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
								<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?= (isset($from_date)) ? $from_date : date('Y-m-d'); ?>">
							</div>
							<div class="col-md-2" id="to_date_cont">
								<label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
								<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?= (isset($to_date)) ? $to_date : date('Y-m-d'); ?>">
							</div>

							<div class="col-md-2" id="module_cont">
								<label class="control-label" for="module">Module<span class="text-danger">*</span> </label>
								<select id="module" name="module" class="form-control">
									<option value="">--SELECT--</option>
									<option value="PROPERTY" <?= (isset($module)) ? $module == "PROPERTY" ? "SELECTED" : "" : ""; ?>>PROPERTY</option>
									<option value="SAF" <?= (isset($module)) ? $module == "SAF" ? "SELECTED" : "" : ""; ?>>SAF</option>
									<option value="GBSAF" <?= (isset($module)) ? $module == "GBSAF" ? "SELECTED" : "" : ""; ?>>GBSAF</option>
									<option value="WATER" <?= (isset($module)) ? $module == "WATER" ? "SELECTED" : "" : ""; ?>>WATER</option>
									<option value="TRADE" <?= (isset($module)) ? $module == "TRADE" ? "SELECTED" : "" : ""; ?>>TRADE</option>

									<!--  <option value="ADVERTISEMENT" <?= (isset($module)) ? $module == "ADVERTISEMENT" ? "SELECTED" : "" : ""; ?>>ADVERTISEMENT</option> -->
								</select>
							</div>

							<div class="col-md-2" id="payment_mode_cont">
								<label class="control-label" for="payment_mode">Payment Mode<span class="text-danger">*</span> </label>
								<select id="payment_mode" name="payment_mode" class="form-control">
									<option value="">--ALL--</option>
									<option value="CHEQUE" <?= (isset($module)) ? $module == "CHEQUE" ? "SELECTED" : "" : ""; ?>>CHEQUE</option>
									<option value="DD" <?= (isset($module)) ? $module == "DD" ? "SELECTED" : "" : ""; ?>>DD</option>
									<option value="NEFT" <?= (isset($module)) ? $module == "NEFT" ? "SELECTED" : "" : ""; ?>>NEFT</option>
								</select>
							</div>

							<div class="col-md-2" id="verification_cont">
								<label class="control-label" for="module">Verification Status<span class="text-danger"></span> </label>
								<select id="Status" name="status" class="form-control">
									<option value="">--ALL--</option>
									<option value="pending" <?= (isset($status)) ? $status == "pending" ? "SELECTED" : "" : ""; ?>>Pending</option>
									<option value="clear" <?= (isset($status)) ? $status == "clear" ? "SELECTED" : "" : ""; ?>>Clear</option>
									<option value="bounced" <?= (isset($status)) ? $status == "bounced" ? "SELECTED" : "" : ""; ?>>Bounced</option>

								</select>
							</div>
							<!-- SEARCH BY CHEQUE NUMBER -->
							<div class="col-md-2" id="chequ_no_input_container">
								<label class="control-label" for="module">Cheque no.<span class="text-danger"></span> </label>
								<input id="cheque_no_input" type="text" value="<?= isset($cheque_no_input) ? $cheque_no_input : '' ?>" name="cheque_no_input" class="form-control" placeholder="Enter cheque no.">
							</div>
							<div class="col-md-2">
								<label class="control-label" for="cancel">&nbsp;</label>
								<button class="btn btn-primary btn-block" id="btn_cencel" name="btn_cencel" type="submit">Search</button>
							</div>
						</div>
					</form>









				</div>
			</div>
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6"><h5 class="panel-title">Cheque Details </h5></div>
					<?php if(isset($chequeDetails)) {  if(!empty($chequeDetails)) { ?>
					<div class="col-md-6" style="padding-top:5px;text-align:right;padding-right:30px"><a href='<?= base_url("/BankReconciliationAllModuleList/ajaxBankReconcilliationDataDownload?cheque_no_input=".(isset($cheque_no_input)?$cheque_no_input:'')."&payment_mode=".(isset($payment_mode)?$payment_mode:'')."&from_date=".(isset($from_date)?$from_date:'')."&to_date=".(isset($to_date)?$to_date:'')."&module=".(isset($module)?$module:'')."&filter_date=".(isset($filter_date)?$filter_date:'')."&status=".(isset($status)?$status:'')); ?>'><button class="btn btn-success btn-sm">Download Excel</button></a></div>
					<?php } } ?>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered text-sm" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th>#</th>
								<th>Ward No.</th>
								<th>Tran No.</th>
								<th>Tran Date</th>
								<th>Payment Mode</th>
								<th>Transaction Type</th>
								<th>Cheque Date</th>
								<th>Cheque No</th>
								<th>Bank Name</th>
								<th>Branch Name</th>
								<th>Tran Amount</th>
								<th>Clearance Date</th>
								<th>Remarks</th>
								<th>TC Name</th>
								<th>Action</th>

							</tr>
						</thead>
						<tbody>
							<?php
							if (!isset($chequeDetails)) :

							?>
								<tr>
									<td colspan="7" style="text-align: center;">Data Is Not Available!!</td>
								</tr>
								<?php else :
								$i = 0;
								foreach ($chequeDetails as $value) :

								?>
									<tr class='<?php if ($value['status'] == 3) {
													echo "text-danger";
												} else if ($value['status'] == 1) {
													echo "text-success";
												} ?>'>
										<td><?= ++$i; ?></td>
										<td><?= $value['ward_no'] ?></td>
										<td><?= ($value['tran_no'] ?? $value['transaction_no']); ?></td>
										<td><?= date('d-m-Y', strtotime($value['tran_date'] ?? $value['transaction_date'])); ?></td>
										<td><?= ($value['payment_mode'] != "") ? $value['payment_mode'] : ""; ?></td>
										<td><?= $value['tran_type'] ?? $value['tran_type']; ?></td>
										<td><?= $value['cheque_date'] != "" ? date('d-m-Y', strtotime($value['cheque_date'])) : ""; ?> </td>
										<td><?= ($value['cheque_no'] != "") ? $value['cheque_no'] : ""; ?></td>
										<td><?= $value['bank_name'] != "" ? $value['bank_name'] : ""; ?></td>
										<td><?= $value['branch_name'] != "" ? $value['branch_name'] : ""; ?></td>
										<td><?= isset($module) && in_array($module, ["WATER", "TRADE"]) ? ($value['paid_amount'] ?? $value['paid_amount']) : ($value['payable_amt'] ?? $value['payable_amt']); ?></td>
										<td><?= isset($value['clear_bounce_date']) ? $value['clear_bounce_date'] : ""; ?></td>
										<td><?= isset($value['clear_bounce_remarks']) ? $value['clear_bounce_remarks'] : ""; ?></td>
										<td><?= isset($value['emp_name']) ? $value['emp_name'] : ""; ?></td>

										<td>


											<?php
											/* echo date('d-m-Y',strtotime($value['tran_date']));
												echo " ";
												echo date('d-m-Y',strtotime(date('d-m-Y').'-3 months')); */
											if (isset($module) && in_array($module, ["WATER", "TRADE"])) {
												$makeDate = date('Y-m-d', strtotime(date('Y-m-d') . '-6 months'));
												$compareDate = date('Y-m-d', strtotime($value['transaction_date']));
											} else {
												$makeDate = date('Y-m-d', strtotime(date('Y-m-d') . '-6 months'));
												$compareDate = date('Y-m-d', strtotime($value['tran_date']));
											}
											if ($makeDate < $compareDate) { 
												$value['branch_name'] = str_replace("&#39;","", $value['branch_name']);
											?>
												<button class="btn btn-primary btn-sm" type="button" onclick="chequeDetailsData(<?= $value["cheque_dtl_id"] . ",'" . $value['cheque_no'] . "','" . $value['branch_name'] . "'" . ",'" . $value['bank_name'] . "'" . ",'" . $value['cheque_date'] . "'," . $value['id'] . ",'" . $value['verify_status'] . "'"; ?>);">
													View</button>

											<?php }    ?>




										</td>

									</tr>
								<?php endforeach;  ?>
							<?php endif;  ?>
						</tbody>
					</table>
					<?= pagination(($pager) ?? 0); ?>
				</div>

			</div>
		</div>
	</div>

	<!--===================================================-->
	<!--End page content-->
	<!--existing_holding_details Bootstrap Modal-->
	<div id="holding_owner_details-lg-modal" class="modal fade" tabindex="1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="height:380px;">
				<form method="POST" action="<?= base_url(''); ?>/BankReconciliationAllModuleList/cheque_verification" class="form">
					<div class="modal-header btn-primary">
						<button type="button" class="close" data-dismiss="modal" style="color: white"><i class="pci-cross pci-circle"></i></button>
						<h4 class="modal-title" id="myLargeModalLabel" style="color: white">Cheque/DD Clearance</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label col-md-6">Cheque No</label>

								<div class="col-md-6">
									<input type="hidden" id="cheque" name="cheque_no" value="" />
									<span id="cheque_apan" class="text-bold"></span>
								</div>
							</div>
							<div class="col-md-6">
								<label class="control-label col-md-6">Cheque Date</label>

								<div class="col-md-6">
									<input type="hidden" id="cheque_date" name="cheque_date" value="" />
									<span id="che_date" class="text-bold"></span>
								</div>
							</div>
						</div>
						<br>
						<div class="form-group">
							<div class="col-md-6">

								<label class="control-label col-md-6">Branch Name</label>

								<div class="col-md-6">
									<input type="hidden" id="branch_name" name="branch_name" value="" />
									<span id="branch_apan" class="text-bold"></span>
								</div>
							</div>
							<div class="col-md-6">

								<label class="control-label col-md-6">Bank Name</label>

								<div class="col-md-6">
									<input type="hidden" id="bank_name" name="bank_name" value="" />
									<span id="bank_span" class="text-bold"></span>
								</div>
							</div>
						</div>
						<br>

						<div id="message"></div>
						<div id="show_hide_block">
							<div class="form-group">
								<div class="col-md-12">

									<label class="control-label col-md-3" for="status">Status<span class="text-danger">*</span></label>

									<div class="col-md-3">
										<select id="status" name="bn_status" name="status" class="form-control pull-left" onchange="show_cancellation_charge(this.value)">
											<option value="">--select--</option>
											<option value="1">Clear</option>
											<option value="3">Bounce</option>
										</select>
									</div>
								</div>
							</div>
							<br>
							<br>


							<div class="form-group" style="display: none;" id="cancellation">
								<div class="col-md-4" id="bounce_container1">



									<!-- <div class="col-md-6"> -->
									<label class="control-label">Cancelation Charge<span class="text-danger">*</span></label>
									<input type="text" maxlength="10" id="amount" name="amount" autocomplete="off" class="form-control" placeholder="Cancelation Charge" onkeypress="return isDecNum(this, event);">
								</div>
								<!-- </div> -->
								<div class="col-md-4" id="bounce_container2">



									<!-- <div class="col-md-4"> -->
									<label class="control-label col-md-6" for="reason">Reason<span class="text-danger">*</span></label>
									<select id="reason" name="reason" class="form-control" onchange="show_remarks_modal(this)">
										<option value="">--select--</option>
										<option value="Insufficient funds">Insufficient funds</option>
										<option value="Irregular signature">Irregular signature</option>
										<option value="Stale and post dated cheque">Stale and post dated cheque</option>
										<option value="Alterations">Alterations</option>
										<option value="Frozen account">Frozen account</option>
										<option value="other">Other</option>
									</select>
									<div id="remarks_input" style="display: none">
										<textarea placeholder="Enter Remarks" name="remarks_input_value" id="remarks_input_value" cols="30" rows="5"></textarea>
									</div>
									<!-- </div> -->

								</div>
								<div class="col-md-4" id="clear_container1">
									<label for="clear_bounce_date">Clearance Date<span class="text-danger">*</span></label>
									<input required class="form-control" type="date" name="clear_bounce_date" id="clear_bounce_date" value="<?= date('d-m-Y') ?>">
								</div>
							</div>
							<br>


							<div class="form-group" style="display: none;">
								<div class="col-md-6">
									<div class="col-md-3">
										<label class="control-label" for="status">hiddenFiled<span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<input type="hidden" id="from" name="from_date" value="" />
										<input type="hidden" id="to" name="to_date" value="" />
										<input type="hidden" id="payment_mode_input" name="payment_mode_input" value="" />
										<input type="hidden" id="mod" name="mod" value="" />
										<input type="hidden" id="id" name="id" value="" />
										<input type="hidden" id="transaction_id" name="transaction_id" value="" />

									</div>
								</div>
							</div>
							<br>

						</div>
						<div class="modal-footer">
							<center>
								<button type="submit" name="proceed" id="proceed" class="btn btn-primary" value="proceed">Proceed</button>
							</center>
						</div>
					</div>
			</div>
			</form>
		</div>
	</div>

	<!--End existing_holding_details Bootstrap Modal-->

	<!--===================================================-->
	<!--END CONTENT CONTAINER-->
	<?= $this->include('layout_vertical/footer'); ?>
	<!--DataTables [ OPTIONAL ]-->
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
	<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.hideData').hide();
		});
		$('#btn_cencel').click(function() {
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();
			var mod = $('#module').val();
			if (mod == "") {
				$("#module").css({
					"border-color": "red"
				});
				$("#module").focus();
				return false;
			}
			if (from_date == "") {
				$("#from_date").css({
					"border-color": "red"
				});
				$("#from_date").focus();
				return false;
			}
			if (to_date == "") {
				$("#to_date").css({
					"border-color": "red"
				});
				$("#to_date").focus();
				return false;
			}
			if (to_date < from_date) {
				alert("To Date Should Be Greater Than Or Equals To From Date");
				$("#to_date").css({
					"border-color": "red"
				});
				$("#to_date").focus();
				return false;
			}
		});
		$('#status').change(function() {
			var status = $('#status').val();
			if (status == "2") {
				$('.hideData').show();
			} else {
				$('#reason').val('');
				$('#amount').val('');
				$('.hideData').hide();
			}
		});
		$('#proceed').click(function() {
			var reason = $('#reason').val();
			var amount = $('#amount').val();
			var status = $('#status').val();
			if (status == "") {
				$("#status").css({
					"border-color": "red"
				});
				$("#status").focus();
				return false;
			} else {
				if (status == '3') {
					if (reason == "") {
						$("#reason").css({
							"border-color": "red"
						});
						$("#reason").focus();
						return false;
					}
					if (amount == "") {
						$("#amount").css({
							"border-color": "red"
						});
						$("#amount").focus();
						return false;
					}
				}
			}
		});
		$("#from_date").change(function() {
			$(this).css('border-color', '');
		});
		$("#to_date").change(function() {
			$(this).css('border-color', '');
		});
		$("#module").change(function() {
			$(this).css('border-color', '');
		});
		$("#reason").change(function() {
			$(this).css('border-color', '');
		});
		$("#amount").keyup(function() {
			$(this).css('border-color', '');
		});
		$("#status").keyup(function() {
			$(this).css('border-color', '');
		});

		function modelInfo(msg) {
			$.niftyNoty({
				type: 'info',
				icon: 'pli-exclamation icon-2x',
				message: msg,
				container: 'floating',
				timer: 5000
			});
		}
		<?php
		if ($bank_cancel = flashToast('bank_cancel')) {
			echo "modelInfo('" . $bank_cancel . "');";
		}
		?>
		//Start

		//End
		function chequeDetailsData(id, cheque_no, branch_name, bank_name, cheque_date, transaction_id, verify_status) {
			// console.log('ck id',id);
			// return
			$('#cheque').val(cheque_no);
			// $('#payment_mode_input').val(payment_mode_input);
			$('#cheque_apan').html(cheque_no);
			$('#cheque_date').val(cheque_date);
			$('#che_date').html(cheque_date);
			$('#branch_name').val(branch_name);
			$('#branch_apan').html(branch_name);
			$('#bank_name').val(bank_name);
			$('#bank_span').html(bank_name);
			$('#from').val($('#from_date').val());
			$('#to').val($('#to_date').val());
			$('#mod').val($('#module').val());
			$('#id').val(id);
			$("#transaction_id").val(transaction_id);
			$("#holding_owner_details-lg-modal").modal("show");
			if (verify_status != 1) {
				$("#message").html('<p style="color:red; text-align:center;">Cash Not Verified!!</p>');
				// $("#show_hide_block").hide();
			} else {
				$("#message").html('');
				// $("#show_hide_block").show();
			}
		}

		function isDecNum(txt, evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode == 46) {
				//Check if the text already contains the . character
				if (txt.value.indexOf('.') === -1) {
					return true;
				} else {
					return false;
				}
			} else {
				if (charCode > 31 &&
					(charCode < 48 || charCode > 57))
					return false;
			}
			return true;
		}

		function show_cancellation_charge(status) {


			if (status == 3) {
				console.log('inside 3')
				$("#cancellation").show();
				$("#bounce_container1").show();
				$("#bounce_container2").show();
				$("#clear_container1").show();
			} else if (status == 1) {
				$("#cancellation").show();
				$("#bounce_container1").hide();
				$("#bounce_container2").hide();
				$("#clear_container1").show();
			} else {
				console.log('inside ')

				$("#cancellation").hide();
				$("#bounce_container1").hide();
				$("#bounce_container2").hide();
				$("#clear_container1").hide();
			}


		}

		function show_remarks_modal(e) {
			// console.log(e.value,'value  ')
			if (e.value == 'other') {
				$("#remarks_input").css('display', 'block');
			} else {
				$("#remarks_input").css('display', 'none');
			}
		}
	</script>