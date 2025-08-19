<?= $this->include('layout_vertical/header'); ?>
<link href="<?=base_url();?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet"> 
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
			<li><a href="#">Verifications</a></li>
			<li class="active">Search</li>
		</ol>
		<!--End breadcrumb-->
	</div>
	<!-- ======= Cta Section ======= -->
	<div id="page-content">
		<form action="<?= base_url('jsk/physicalVerificationSearch'); ?>" method="get" role="form" class="php-email-form" id="myform">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Search</h3>
				</div>
				<div class="panel-body">					
					<div class="row">
						<label class="col-md-2">Ward No.</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="wardId" name="wardId[]" class="form-control m-t-xxs select2" multiple>
									<option value="">Select</option>
									<?php if ($ward) : ?>
										<?php foreach ($ward as $post) : ?>
											<option value="<?= $post['id'] ?>" <?= (isset($wardId) && in_array($post["id"],$wardId))? "selected" : null; ?>>
												<?= $post['ward_no']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<label for="fromDate" class="col-md-2"> From Date</label>
                        <div class="col-md-2">
                            <input class="form-control" type="date" name="fromDate" id="fromDate" value="<?=$fromDate??""?>" />
                        </div>
                        <label for="uptoDate" class="col-md-2"> Upto Date</label>
                        <div class="col-md-2">
                            <input class="form-control" type="date" name="uptoDate" id="uptoDate" value="<?=$uptoDate??""?>" />
                        </div>
					</div>
					<div class="row">
						<label for="is_property_type_change" class="col-md-2"> Is Property Type Change</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="is_property_type_change" name="is_property_type_change" class="form-control m-t-xxs select2">
									<option value="">Select</option>
									<option value="Yes" <?=($is_property_type_change??"")=="Yes"?"selected":""?> >Yes</option>
									<option value="No" <?=($is_property_type_change??"")=="No"?"selected":""?> >No</option>
								</select>
							</div>
						</div>
						<label for="is_old_ward_change" class="col-md-2"> Is Old Ward Change</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="is_old_ward_change" name="is_old_ward_change" class="form-control m-t-xxs select2">
									<option value="">Select</option>
									<option value="Yes" <?=($is_old_ward_change??"")=="Yes"?"selected":""?> >Yes</option>
									<option value="No" <?=($is_old_ward_change??"")=="No"?"selected":""?> >No</option>
								</select>
							</div>
						</div>
						<label for="is_new_ward_change" class="col-md-2"> Is New Ward Change</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="is_new_ward_change" name="is_new_ward_change" class="form-control m-t-xxs select2">
									<option value="">Select</option>
									<option value="Yes" <?=($is_new_ward_change??"")=="Yes"?"selected":""?> >Yes</option>
									<option value="No" <?=($is_new_ward_change??"")=="No"?"selected":""?> >No</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<label for="is_usage_type_change" class="col-md-2"> Is Usage Type Change</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="is_usage_type_change" name="is_usage_type_change" class="form-control m-t-xxs select2">
									<option value="">Select</option>
									<option value="Yes" <?=($is_usage_type_change??"")=="Yes"?"selected":""?> >Yes</option>
									<option value="No" <?=($is_usage_type_change??"")=="No"?"selected":""?> >No</option>
								</select>
							</div>
						</div>
						<label for="is_occupancy_type_change" class="col-md-2"> Is Occupancy Type Change</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="is_occupancy_type_change" name="is_occupancy_type_change" class="form-control m-t-xxs select2">
									<option value="">Select</option>
									<option value="Yes" <?=($is_occupancy_type_change??"")=="Yes"?"selected":""?> >Yes</option>
									<option value="No" <?=($is_occupancy_type_change??"")=="No"?"selected":""?> >No</option>
								</select>
							</div>
						</div>
						<label for="is_builtup_area_change" class="col-md-2"> Is Builtup Area Change</label>
						<div class="col-md-2">
							<div class="form-group">
								<select id="is_builtup_area_change" name="is_builtup_area_change" class="form-control m-t-xxs select2">
									<option value="">Select</option>
									<option value="Yes" <?=($is_builtup_area_change??"")=="Yes"?"selected":""?> >Yes</option>
									<option value="No" <?=($is_builtup_area_change??"")=="No"?"selected":""?> >No</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<button type="submit" id="search"  class="btn btn-primary">SEARCH</button>
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
					<span class="panel-control"><button class="btn btn-mint" onclick="exportExcel()">Export</button></span>
					<h3 class="panel-title">List :</h3>
				</div>
				<div class="panel-body">
					<div id="saf_distributed_dtl_hide_show">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="ss" class="table table-striped table-bordered" width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-align:center;">
										<thead class="bg-trans-dark text-dark">

											<th>Sl No. </th>
											<th>Holding No </th>
											<th>15 Digit Unique House No. </th>
											<th>Verification Date</th>
											<th>Actual Old Ward No </th>
											<th>PV Old Ward No </th>
											<th>Actual New Ward No </th>
											<th>PV New Ward No </th>
											<th>Actual Property Type </th>
											<th>PV Property Type </th>
											<th>Actual Area of Plot (in decimal) </th>
											<th>PV Area of Plot (in decimal) </th>
                                            <th>ULB EMP</th>
                                            <th>Agency EMP</th>
											<th>Action </th>
										</thead>
										<tbody>
											<?php
											foreach ($result as $post) {
											?>
												<tr>
													<td><?= ++$offset; ?></td>
													<td><?= $post['holding_no']; ?></td>
													<td><?= $post['new_holding_no']; ?></td>
													<td><?= $post['verification_date']; ?></td>
													<td><?= $post['ward_no']; ?></td>
													<td><?= $post['physical_ward_no']; ?></td>
													<td><?= $post['new_ward_no']; ?></td>
													<td><?= $post['physical_new_ward_no']; ?></td>
													<td><?= $post['property_type']; ?></td>
													<td><?= $post['physical_property_type']; ?></td>
													<td><?= $post['area_of_plot']; ?></td>
													<td><?= $post['physical_area_of_plot']; ?></td>
													<td><?= $post['ulb_emp_name']; ?></td>
													<td><?= $post['agency_emp_name']; ?></td>
													<td>
														<a href="<?=base_url("/jsk/viewSurvay/".$post["id"]); ?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
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
<script src="<?=base_url();?>/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

	function exportExcel(){
		const url = new URL(window.location.href);

		// Remove any existing `export` query param
		url.searchParams.delete("export");

		// Append export=true param
		url.searchParams.append("export", "true");

		// Open in a new tab/window
		window.open(url.toString(), "_blank", "noopener");

	}
</script>