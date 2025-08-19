<?= $this->include('layout_vertical/header'); ?>
<style>
	.row {
		line-height: 25px;
	}
</style>
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">GBSAF</a></li>
			<li><a href="<?= base_url("govsafDetailPayment/gov_saf_application_details/$id"); ?>">GBSAF Details</a></li>
			<li><a href="#" class="active">View Demand</a></li>
		</ol>
	</div>
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">
					Application Details
				</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-2">
						<b>Ward No.</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['ward_no'] ? $appbasic_details['ward_no'] : "N/A"; ?>
					</div>
					<div class="col-md-2">
						<b>Apply Date</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['apply_date'] ? $appbasic_details['apply_date'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<b>Application No.</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['application_no'] ? $appbasic_details['application_no'] : "N/A"; ?>
					</div>
					<div class="col-md-2">
						<b>Application Type</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['application_type'] ? $appbasic_details['application_type'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<b>Officer Name</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['officer_name'] ? $appbasic_details['officer_name'] : "N/A"; ?>
					</div>
					<div class="col-md-2">
						<b>Mobile No.</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['mobile_no'] ? $appbasic_details['mobile_no'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<b>Assessment Type</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['assessment_type'] ? $appbasic_details['assessment_type'] : "N/A"; ?>
					</div>
					<div class="col-md-2">
						<b>Holding No.</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-3">
						<?= $appbasic_details['holding_no'] ? $appbasic_details['holding_no'] : "N/A"; ?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-3">
						<b> Office Name</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['office_name'] ? $appbasic_details['office_name'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Property Type</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['property_type'] ? $appbasic_details['property_type'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Construction Type</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['construction_type'] ? $appbasic_details['construction_type'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Road Type</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['road_type'] ? $appbasic_details['road_type'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Colony Name</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['colony_name'] ? $appbasic_details['colony_name'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Colony Address</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['colony_address'] ? $appbasic_details['colony_address'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Ownership Type</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['ownership_type'] ? $appbasic_details['ownership_type'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Is Water Harvesting</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['is_water_harvesting'] == "t" ? "Yes" : "No"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Is Mobile Tower</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['is_mobile_tower'] == "t" ? "Yes" . "  /  " . "Area" . " " . ":" . " " . $appbasic_details['tower_area'] . "  /  " . "Installation Date" . " " . ":" . " " . $appbasic_details['tower_installation_date'] : "No"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Is Hoarding Board</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['is_hoarding_board'] == "t" ? "Yes" . "  /  " . "Area" . " " . ":" . " " . $appbasic_details['hoarding_area'] . "  /  " . "Installation Date" . " " . ":" . " " . $appbasic_details['hoarding_installation_date'] : "No"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Is Petrol Pump</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $appbasic_details['is_petrol_pump'] == "t" ? "Yes" . "  /  " . "Area" . " " . ":" . " " . $appbasic_details['under_ground_area'] . "  /  " . "Installation Date" . " " . ":" . " " . $appbasic_details['petrol_pump_completion_date'] : "No"; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Authorized Person for the Payment of Property Tax</h3>
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<b>Officer Name</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $paybasic_details['officer_name'] ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Mobile No</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $paybasic_details['mobile_no']; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Email Id</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $paybasic_details['email_id']; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Designation</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $paybasic_details['designation']; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<b>Address</b>
					</div>
					<div class="col-sm-1">
						<b> : </b>
					</div>
					<div class="col-md-8">
						<?= $paybasic_details['address']; ?>
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
					<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th scope="col">Sl No.</th>
								<th scope="col">ARV</th>
								<th scope="col">Effect From</th>
								<th scope="col">Holding Tax</th>
								<th scope="col">Water Tax</th>
								<th scope="col">Conservancy/Latrine Tax</th>
								<th scope="col">Education Cess</th>
								<th scope="col">Health Cess</th>
								<th scope="col">RWH Penalty</th>
								<th scope="col">Quarterly Tax</th>
								<th scope="col">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($tax_list) :
								$i = 1;
								$qtr_tax = 0;
								$lenght = sizeOf($tax_list); ?>
								<?php foreach ($tax_list as $tax_list) :
									$qtr_tax = $tax_list['holding_tax'] + $tax_list['water_tax'] + $tax_list['latrine_tax'] + $tax_list['education_cess'] + $tax_list['health_cess'] + $tax_list['additional_tax'];
								?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $tax_list['arv']; ?></td>
										<td><?php echo $tax_list['qtr']; ?>/<?php echo $tax_list['fy']; ?></td>
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
									<td colspan="11" style="text-align:center;color:red;"> Data Are Not Available!!</td>
								</tr>
							<?php endif; ?>
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
				<div class="table-responsive">
					<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif;font-size:12px; ">
						<tbody>
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
									<td colspan="4"><?php if ($total_quarter) {
														echo $total_quarter;
													} else {
														echo "N/A";
													} ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
						<thead class="bg-trans-dark text-dark">
							<th>Sl No.</th>
							<th>Quarter / Year</th>
							<th>Demand</th>
							<th>Water Harvesting Tax</th>
							<th>Total Tax</th>
						</thead>
						<tbody>

							<?php
							$j = 1;
							if ($demand_detail) :
								$total_demand = 0;
								
								foreach ($demand_detail as $demand_detail) :
									$total_demand = $total_demand + $demand_detail['balance'];
							?>
									<tr>
										<td><?php echo $j++; ?></td>
										<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fyear']; ?></td>
										<td><?php echo $demand_detail['demand_amount']; ?></td>
										<td><?php echo $demand_detail['additional_holding_tax']; ?></td>
										<td><?php echo $demand_detail['amount']; ?></td>
									</tr>
								<?php
								endforeach;
							else :
								?>
								<tr>
									<td colspan="3" style="text-align:center;color:green;"> No Dues Are Available!!</td>
								</tr>
							<?php
							endif
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body text-center">
				<?php if (in_array($user_type_mstr_id, [1, 4])) { ?>
					<a href="<?php echo base_url('govsafDetailPayment/govt_demand_receipt/' . $id); ?>" type="button" class="btn btn-primary btn-labeled" <?php if (!$demand_detail) { echo "disabled";} ?>> <?php if (!$demand_detail) { echo "Demand not available";} else { echo "View Demand Receipt";} ?></a>
					<a href="<?php echo base_url('govsafDetailPayment/govt_proceedPayment/' . $id); ?>" type="button" class="btn btn-primary btn-labeled">Proceed Payment</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?= $this->include('layout_vertical/footer'); ?>