<?= $this->include("layout_mobi/header"); ?>

<style>
	.error {
		color: red;
	}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">

	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading" style="display: flex;">
				<div style="flex:1;">
					<h3 class="panel-title"><b style="color:white;">Search Application</b></h3>
				</div>
				<div style="flex:1;text-align:right"><a href="<?= base_url('Mobi/mobileMenu/property'); ?>" class="btn btn-info">Back</a></div>
			</div>

			<div class="panel-body">
				<form action="<?=base_url('mobisafDemandPayment/list_of_saf_Property');?>" method="get" id="myform">
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<div class="radio">
									<input type="radio" id="by_application_dtl" class="magic-radio" name="by_application_owner_dtl" value="by_application" <?= isset($by_application_owner_dtl) ? (strtolower($by_application_owner_dtl) == "by_application") ? "checked" : "" : "checked"; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Application No.');">
									<label for="by_application_dtl">By Application Details</label>

									<input type="radio" id="by_owner_dtl" class="magic-radio" name="by_application_owner_dtl" value="by_owner" <?= (isset($by_application_owner_dtl) && strtolower($by_application_owner_dtl) == "by_owner") ? "checked" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name');">
									<label for="by_owner_dtl">By Owner Details</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="exampleInputEmail1">Ward No.</label>
							<div class="form-group">
								<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
									<option value="">Select</option>
									<?php if ($ward) : ?>
										<?php foreach ($ward as $post) : ?>
											<option value="<?= $post['ward_mstr_id'] ?>" <?= (isset($ward_mstr_id) && $ward_mstr_id == $post["ward_mstr_id"]) ? "selected" : NULL; ?>><?= $post['ward_no']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<label for="keyword">Enter Keyword <span class="text-danger">*</span></label>
							<div class="form-group">
								<input type="text" id="keyword" name="keyword" class="form-control" placeholder="Enter Keyword No." value="<?php echo $keyword ?? NULL; ?>">
							</div>
						</div>
						<div class="col-md-3">
							<label for="searchs"></label>
							<center><button type="submit" id="search" class="btn btn-primary" style="width:100%;">SEARCH</button></center>
						</div>
					</div>

				</form>
			</div>
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Search Result :</h3>
			</div>
			<div class="table-responsive">
				<table id="demo_dt_basic" class="table table-striped table-bordered text-sm">
					<thead class="bg-trans-dark text-dark">
						<th>Sl No. </th>
						<th>Ward No </th>
						<th>Application No </th>
						<th>Owner(s) Name </th>
						<th>Address </th>
						<th>Khata No. </th>
						<th>Plot No. </th>
						<th>Action </th>
					</thead>
					<tbody>
						<?php if (isset($emp_details) && $emp_details) :
							$i = 1;  ?>
							<?php foreach ($emp_details as $post) : ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?= $post['ward_no'] ? $post['ward_no'] : "N/A"; ?></td>
									<td><?= $post['saf_no'] ? $post['saf_no'] : "N/A"; ?></td>
									<td><?= $post['owner_name'] ? $post['owner_name'] : "N/A"; ?></td>
									<td><?= $post['prop_address'] ? $post['prop_address'] : "N/A"; ?></td>
									<td><?= $post['khata_no'] ? $post['khata_no'] : "N/A"; ?></td>
									<td><?= $post['plot_no'] ? $post['plot_no'] : "N/A"; ?></td>
									<td>
										<a href="<?php echo base_url('mobisafDemandPayment/saf_due_details/' . md5($post['saf_dtl_id'])); ?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
							</tr>
						<?php endif; ?>

					</tbody>
				</table>
				<?= isset($pager) ? pagination($pager) : NULL; ?>
			</div>
		</div>

	</div>
</div>


<?= $this->include("layout_mobi/footer"); ?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
<script>
	$(document).ready(function() {
		$('#myform').validate({ // initialize the plugin
			rules: {
				ward_mstr_id: {
					required: true,
				},
				keyword: {
					required: true,
				}
			}
		});
	});

	$('#search').click(function(){
		if($('#ward_mstr_id').val()!="" && $('#keyword').val()!=""){
			$('#search').html('Please Wait...');
		}
	})
</script>