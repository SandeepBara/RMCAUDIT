<?= $this->include('layout_vertical/header'); ?>
<style type="text/css">
	.error {
		color: red;
	}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<!--Breadcrumb-->
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Property</a></li>
			<li class="active">Search Property</li>
		</ol>
		<!--End breadcrumb-->
	</div>
	<!-- ======= Cta Section ======= -->
	<div id="page-content">
		<form action="<?= base_url('jsk/jsk_Search_Property'); ?>" method="get" role="form" class="php-email-form" id="myform">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Property Search</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-10">
							<div class="radio">
								<input type="radio" id="by_15_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_15_holding" <?= isset($by_holding_owner_dtl) ? (strtolower($by_holding_owner_dtl) == "by_15_holding") ? "checked" : "" : "checked"; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter 15 Digit Unique No.');">
								<label for="by_15_holding_dtl">By 15 Digit Holding Details</label>

								<input type="radio" id="by_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_holding" <?= isset($by_holding_owner_dtl) ? (strtolower($by_holding_owner_dtl) == "by_holding") ? "checked" : "" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Holding No. ');">
								<label for="by_holding_dtl">By Holding Details</label>

								<input type="radio" id="by_owner_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_owner" <?= (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_owner") ? "checked" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name');">
								<label for="by_owner_dtl">By Owner Details</label>

								<input type="radio" id="by_address_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_address" <?= (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_address") ? "checked" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Address');">
								<label for="by_address_dtl">By Address</label>
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-md-1">Ward No.</label>
						<div class="col-md-3">
							<div class="form-group">
								<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
									<option value="">Select</option>
									<?php if ($ward) : ?>
										<?php foreach ($ward as $post) : ?>
											<option value="<?= $post['id'] ?>" <?= (isset($ward_mstr_id) && $ward_mstr_id == $post["id"]) ? "selected" : null; ?>>
												<?= $post['ward_no']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<label for="keyword">
							<?php 
								$keyword_change_id = "";
								if (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_owner") { 
									$keyword_change_id = "Enter Register Mobile No. Or Owner Name";
								} else if (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_address") { 
									$keyword_change_id = "Enter Address"; 
								} else if (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_15_holding") { 
									$keyword_change_id = "Enter 15 Digit Unique No."; 
								} else {
									$keyword_change_id = "Enter Holding No"; 
								}  
							?>
								Enter Keywords
								<i id="keyword_change_id" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="<?=$keyword_change_id;?>"></i>
							</label>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keywords" value="<?= $keyword ?? NULL; ?>">

							</div>
						</div>
						<div class="col-md-3">
							<button type="submit" id="search" class="btn btn-primary">SEARCH</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php
		if (isset($result)) {
		?>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Citizen List :</h3>
				</div>
				<div class="panel-body">
					<div id="saf_distributed_dtl_hide_show">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="ss" class="table table-striped table-bordered" width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-align:center;">
										<thead class="bg-trans-dark text-dark">

											<th>Sl No. </th>
											<th>Ward No </th>
											<th>Holding No </th>
											<th>15 Digit Unique House No. </th>
											<th>Owner(s) Name </th>
											<th>Address </th>
											<th>Mobile No. </th>
											<th>Khata No. </th>
											<th>Plot No. </th>
											<th>Action </th>
										</thead>
										<tbody>
											<?php
											foreach ($result as $post) {
											?>
												<tr>
													<td><?= ++$offset; ?></td>
													<td><?= $post['ward_no']; ?></td>
													<td><?= $post['holding_no']; ?></td>
													<td><?= $post['new_holding_no']; ?></td>
													<td><?= $post['owner_name']; ?></td>
													<td><?= $post['prop_address']; ?></td>
													<td><?= $post['mobile_no']; ?></td>
													<td><?= $post['khata_no']; ?></td>
													<td><?= $post['plot_no']; ?></td>
													<td>
														<?php if ($post['status'] == 0) { ?>
															<span class="text text-danger text-bold">Deactivated</span>
														<?php } ?>
														<a href="<?=base_url()."/".$forward.($post['prop_dtl_id']); ?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
													</td>

												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
									<?= pagination(isset($pager)?$pager:0); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		?>


	</div><br><br><!-- End Contact Section -->

	<?= $this->include('layout_vertical/footer'); ?>
	<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#myform').validate({ // initialize the plugin
				rules: {
					ward_mstr_id: {
						required: "#keyword:blank",
					},
					keyword: {
						required: "#ward_mstr_id:blank",
					}
				}
			});
		});
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>