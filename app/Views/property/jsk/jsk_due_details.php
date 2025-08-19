<?= $this->include('layout_vertical/header'); ?>
<style>
	.row {
		line-height: 25px;
	}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
<!--=================================================== god-->
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
			<li><a href="<?= base_url("propDtl/full/$prop_dtl_id_MD5"); ?>">Property Details</a></li>
			<li class="active">Due Property Details</li>
		</ol>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>

	<!-- ======= Cta Section ======= -->

	<div id="page-content">



		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Property Details
				</h3>

			</div>
			
			<?= $this->include('common/basic_details'); ?>
			
		</div>


		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Owner Details</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered text-sm">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th>Owner Name</th>
										<th>Guardian Name</th>
										<th>Relation</th>
										<th>Mobile No</th>
										<th>Aadhar No.</th>
										<th>PAN No.</th>
										<th>Email</th>
										<th>Gender</th>
										<th>DOB</th>
										<th>Is Specially Abled?</th>
										<th>Is Armed Force?</th>
									</tr>
								</thead>
								<tbody id="owner_dtl_append">
									<?php
									if (isset($prop_owner_detail)) {
										foreach ($prop_owner_detail as $owner_detail) {
									?>
											<tr>
												<td><?= $owner_detail['owner_name']; ?></td>
												<td><?= $owner_detail['guardian_name']; ?></td>
												<td><?= $owner_detail['relation_type']; ?></td>
												<td><?= $owner_detail['mobile_no']; ?></td>
												<td><?= $owner_detail['aadhar_no']; ?></td>
												<td><?= $owner_detail['pan_no']; ?></td>
												<td><?= $owner_detail['email']; ?></td>
												<td><?= $owner_detail['gender'] == '' ? 'N/A' : $owner_detail['gender'];  ?></td>
												<td><?= $owner_detail['dob'] == '' ? 'N/A' : $owner_detail['dob'];  ?></td>
												<td><?= $owner_detail['is_specially_abled'] == '' ? 'No' : 'Yes';  ?></td>
												<td><?= $owner_detail['is_armed_force'] == '' ? 'No' : 'Yes';  ?></td>

											</tr>
									<?php
										}
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
				<h3 class="panel-title">Tax Details</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered text-sm">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th>Sl No.</th>
								<th>ARV</th>
								<th>Effect From</th>
								<th>Holding Tax</th>
								<th>Water Tax</th>
								<th>Conservancy/Latrine Tax</th>
								<th>Education Cess</th>
								<th>Health Cess</th>
								<th>RWH Penalty</th>
								<th>Quarterly Tax</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($prop_tax_list) {
								$i = 1;
								$qtr_tax = 0;
								$lenght = sizeOf($prop_tax_list);
								foreach ($prop_tax_list as $tax_list) {
									$qtr_tax = $tax_list['holding_tax'] + $tax_list['water_tax'] + $tax_list['latrine_tax'] + $tax_list['education_cess'] + $tax_list['health_cess'] + $tax_list['additional_tax'];
							?>
									<tr>
										<td><?= $i++; ?></td>
										<td><?= round($tax_list['arv'], 2); ?></td>
										<td><?= $tax_list['qtr']; ?> / <?= $tax_list['fy']; ?></td>
										<td><?= round($tax_list['holding_tax'], 2); ?></td>
										<td><?= round($tax_list['water_tax'], 2); ?></td>
										<td><?= round($tax_list['latrine_tax'], 2); ?></td>
										<td><?= round($tax_list['education_cess'], 2); ?></td>
										<td><?= round($tax_list['health_cess'], 2); ?></td>
										<td><?= round($tax_list['additional_tax'], 2); ?></td>
										<td><?= round($qtr_tax, 2); ?></td>
										<td>
											<?php
											if ($i > $lenght) {
											?>
												<span class="text text-success text-bold">Current</span>
											<?php
											} else {
											?>
												<span class="text text-danger text-bold">Old</span>
											<?php
											}
											?>
										</td>
									</tr>
							<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Due Detail List</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered text-sm">
					<thead>
						<?php if ($demand_detail) :
							$i = 1;
							$total_due = 0;
						?>
							<?php foreach ($demand_detail as $tot_demand) :
								$i == 1 ? $first_qtr = $tot_demand['qtr'] : '';
								$i == 1 ? $first_fy = $tot_demand['fy'] : '';

								$total_demand = $tot_demand['balance'];
								$total_due = $total_due + $total_demand;
								$total_quarter = $i;
								$i++;

							?>
							<?php endforeach; ?>
							<tr>
								<td><b style="color:#bf06fb;">Total Dues</b></td>
								<td><strong style="color:#bf06fb;">:</strong></td>
								<td colspan="4">
									<b style="color:#bf06fb;"><?php if ($total_due) {
																	echo $total_due;
																} else {
																	echo "N/A";
																} ?></b>
								</td>
							<tr>
								<td>Dues From</td>
								<td><strong>:</strong></td>
								<td>
									Quarter <?php echo $first_qtr; ?> / Year <?php echo $first_fy; ?>
								</td>
								<td>Dues Upto</td>
								<td><strong>:</strong></td>
								<td>
									Quarter <?php echo $tot_demand['qtr']; ?> / Year <?php echo $tot_demand['fy']; ?> </td>
							</tr>
							<tr>
								<td>Total Quarter(s)</td>
								<td><strong>:</strong></td>
								<td colspan="4"><?php 
											if ($total_quarter) {
													echo $total_quarter;
												} else {
													echo "N/A";
												} ?></td>
							</tr>
						<?php endif; ?>
						</tbody>
				</table>
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
					<thead class="bg-trans-dark text-dark">
						<th>Sl No.</th>
						<th>Quarter/Year</th>
						<th>Quarterly Tax</th>
						<th>RWH Penalty</th>
						<th>Balance Amount</th>
						<th>Demand Amount</th>
					</thead>
					<tbody>
						<?php
						$j = 0;
						if ($demand_detail) {
							foreach ($demand_detail as $demand) {
								//$tax = $demand["holding_tax"] + $demand["water_tax"] + $demand["education_cess"] + $demand["health_cess"] + $demand["latrine_tax"];
								$tax = $demand["amount"];
						?>
								<tr>
									<td><?= ++$j; ?></td>
									<td><?= $demand['qtr']; ?> / <?= $demand['fy']; ?></td>
									<td><?= $tax; ?></td>
									<td><?= $demand['additional_amount'];?></td>
									<td><?= $demand['balance'];?></td>
									<td><?= $demand['demand_amount'];?></td>
								</tr>
							<?php
							}
						} else {
							?>
							<tr>
								<td colspan="5" class="text text-success text-bold text-center"> No Dues Are Available!! </td>
							</tr>
						<?php
						}
						?>

					</tbody>
				</table>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body text-center">
				<?php
				if ($entry_type == 'Legacy') {
					if ($paid_demand['count_paid_demand'] == '0') {
						if ($demand_upd_exist['count_demand'] == '0' && in_array($user_type_id, [1])) {
				?>
							<a href="<?= base_url('LegacyEntry/demand_update/' . $iprop_dtl_id_MD5d); ?>" type="button" class="btn btn-danger btn-labeled">Update Demand</a>
					<?php
						}
					}
				}
				// 3 Project Manager
				// 4 Team Leader
				// 8 jsk
				// 11 Back Office
				if ($demand_detail && in_array($user_type_id, [1, 4, 8])) {
					?>
					<!-- <a href="<?= base_url('jsk/jsk_confirm_payment/' . $prop_dtl_id_MD5); ?>" class="btn btn-primary">Pay Property Tax</a> -->
					<!-- Developed by Manas Singh which is little bit slow -->
					<a href="<?= base_url('jsk/propertyPaymentProceed/' . $prop_dtl_id_MD5); ?>" class="btn btn-primary">Pay Property Tax </a>
				<?php
				}
				if ($demand_detail && in_array($user_type_id, [1, 2, 4])) {
				?>
					<a href="<?= base_url('jsk/holding_demand_print/' .$prop_dtl_id); ?>" target="_blank" class="btn btn-primary">Demand Print</a>
				<?php
				}
				?>

			</div>
		</div>

	</div>


	<?= $this->include('layout_vertical/footer'); ?>