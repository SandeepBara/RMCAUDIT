<?= $this->include('layout_home/header'); ?>
<!--CONTENT CONTAINER-->
<style>
	.row {
		line-height: 25px;
	}
</style>
<div id="content-container" style="padding: 20px 0px;">
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">
					<button onclick="goBack()" class="btn btn-info">Go Back</button>
				</div>
				<h3 class="panel-title">Basic Details</h3>
				
			</div>
			
			<?= $this->include('common/basic_details'); ?>
			
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Owner Details</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
					<thead class="bg-trans-dark text-dark">
						<th scope="col">Owner Name</th>
						<th scope="col">R/W Guardian</th>
						<th scope="col">Guardian's Name</th>
						<th scope="col">Mobile No</th>
						<th scope="col">Pan</th>
						<th scope="col">Aadhar</th>
						<th scope="col">Gender</th>
						<th scope="col">DOB</th>
						<th scope="col">Is Specially Abled?</th>
						<th scope="col">Is Armed Force?</th>
					</thead>
					<tbody>
						<?php if ($owner_details) : ?>
							<?php foreach ($owner_details as $owner_details) : ?>
								<tr>
									<td><?php echo $owner_details['owner_name']; ?></td>
									<td><?php echo $owner_details['relation_type']; ?></td>
									<td><?php echo $owner_details['guardian_name']; ?></td>
									<td><?php echo substr_replace($owner_details['mobile_no'], 'XXXXXX', 0, 6); ?></td>
									<td><?php if ($owner_details['pan_no'] == '') {
											echo 'N/A';
										} else {
											echo substr_replace($owner_details['pan_no'], 'XXXXXX', 0, 6);
										}  ?></td>
									<td><?php echo substr_replace($owner_details['aadhar_no'], 'XXXXXXXX', 0, 8); ?></td>
									<td><?php echo $owner_details['gender'] != '' ? $owner_details['gender'] : "N/A"; ?></td>
									<td><?php echo $owner_details['dob'] != '' ? $owner_details['dob'] : "N/A"; ?></td>
									<td><?php echo $owner_details['is_specially_abled'] == 'f' ? "No" : "Yes"; ?></td>
									<td><?php echo $owner_details['is_armed_force'] == 'f' ? "No" : "Yes"; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="4" style="text-align:center;color:red;"> Data Are Not Available!!</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Floor Details</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
					<thead class="bg-trans-dark text-dark">
						<th scope="col">Sl No.</th>
						<th scope="col">Floor</th>
						<th scope="col">Use Type</th>
						<th scope="col">Occupancy Type</th>
						<th scope="col">Construction Type</th>
						<th scope="col">Total Area (in Sq. Ft.)</th>
						<th scope="col">Total Taxable Area (in Sq. Ft.)</th>
						<th scope="col">From</th>
						<th scope="col">Upto</th>
					</thead>
					<tbody>
						<?php if (isset($occupancy_detail)) :
							$i = 1;
						?>
							<?php foreach ($occupancy_detail as $occupancy_detail) : ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $occupancy_detail['floor_name']; ?></td>
									<td><?php echo $occupancy_detail['usage_type']; ?></td>
									<td><?php echo $occupancy_detail['occupancy_name']; ?></td>
									<td><?php echo $occupancy_detail['construction_type']; ?></td>
									<td><?php echo $occupancy_detail['builtup_area']; ?></td>
									<td><?php echo $occupancy_detail['carpet_area']; ?></td>
									<td><?= isset($occupancy_detail['date_from'])?$occupancy_detail['date_from']:'' ?></td>
									<td><?php if(isset($occupancy_detail['date_upto'])) {

									 if ($occupancy_detail['date_upto'] == '' || $occupancy_detail['date_upto'] == null) {
											echo "N/A";
										} else {
											echo $occupancy_detail['date_upto'];
										} }  ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="7" style="text-align:center;color:red;"> Data Are Not Available!!</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Tax Details</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
					<thead class="bg-trans-dark text-dark">
						<th scope="col">SL. No.</th>
						<th scope="col">ARV</th>
						<th scope="col">Effected From</th>
						<th scope="col">Holding Tax</th>
						<th scope="col">Water Tax</th>
						<th scope="col">Conservancy/Latrine Tax</th>
						<th scope="col">Education Cess</th>
						<th scope="col">Health Cess</th>
						<th scope="col">RWH Penalty</th>
						<th scope="col">Quarterly Tax</th>
						<th scope="col">Status</th>
					</thead>
					<tbody>
						<tr>
							<?php if (isset($tax_list)) :
								$i = 1;
								$qtr_tax = 0;
								$lenght = sizeOf($tax_list); ?>
								<?php foreach ($tax_list as $tax_list) :
									$qtr_tax = $tax_list['holding_tax'] + $tax_list['water_tax'] + $tax_list['latrine_tax'] + $tax_list['education_cess'] + $tax_list['health_cess'] + $tax_list['additional_tax'];
								?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $tax_list['arv']; ?></td>
							<td><?php echo $tax_list['qtr']; ?> / <?php echo $tax_list['fy']; ?></td>
							<td><?php echo $tax_list['holding_tax']; ?></td>
							<td><?php echo $tax_list['water_tax']; ?></td>
							<td><?php echo $tax_list['latrine_tax']; ?></td>
							<td><?php echo $tax_list['education_cess']; ?></td>
							<td><?php echo $tax_list['health_cess']; ?></td>
							<td><?php echo $tax_list['additional_tax']; ?></td>
							<td><?php echo $qtr_tax; ?></td>
							<?php if ($i > $lenght) { ?>
								<td style="color:red;">Current</td>
							<?php } else { ?>
								<td>Old</td>
							<?php } ?>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="11" style="text-align:center;color:red;"> Data Is Not Available!!</td>
					</tr>
				<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>




		<div class="panel">
			<div class="panel-body text-center">
				<a href="<?php echo base_url('CitizenProperty/Citizen_due_details/' . $id); ?>" type="button" class="btn btn-primary btn-labeled">View Demand Details</a>
				<a href="<?php echo base_url('CitizenProperty/Citizen_payment_details/' . $id); ?>" type="button" class="btn btn-warning btn-labeled">View Payment Details</a>
			</div>
		</div>

	</div>
	<!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer'); ?>

<script>
	function goBack() {
		window.history.back();
	}
</script>