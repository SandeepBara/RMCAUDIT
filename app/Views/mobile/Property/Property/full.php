
<?=$this->include("layout_mobi/header");?>
<style>
	.row{line-height:25px;}
</style>
<!--CONTENT CONTAINER-->
	<div id="content-container">
    <!--Page content-->
		<div id="page-content">
						
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<div class="panel-control">
									<button type="button" class="btn btn-info btn_wait_load" onclick="history.back()">Back</button>
								</div>
								<h3 class="panel-title">Property Details</h3>
								
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-3">Hoding No.</label>
									<div class="col-md-3 text-bold pad-btm">
									<?=$holding_no;?>
									</div>
									
									<label class="col-md-3">New Holding No.</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$new_holding_no;?>
									</div>
								</div>

								<div class="row">
									<label class="col-md-3">Assessment Type</label>
									<div class="col-md-3 text-bold pad-btm">
									<?=$assessment_type;?>
									</div>
									
									<label class="col-md-3">Ward No</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$ward_no;?>
									</div>
									
								</div>

							
								<div class="row">
									<label class="col-md-3">Order Date</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=date("d-m-Y", strtotime($created_on));?>
									</div>
									<label class="col-md-3">Entry Type</label>
									<div class="col-md-3 text-bold pad-btm">
									<?=($entry_type);?>
									</div>
									
								</div>
								<div class="row">
									<label class="col-md-3">Property Type</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$property_type;?>
									</div>
									<label class="col-md-3">Old Holding (If Any)</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$holding_no;?>
									</div>
								</div>
								<div class="<?=($prop_type_mstr_id==3)?null:"hidden";?>">
									<div class="row">
										<label class="col-md-3">Appartment Name</label>
										<div class="col-md-3 text-bold pad-btm">
											<?=$appartment_name;?>
										</div>
										<label class="col-md-3">Ownership Type</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$ownership_type;?>
									</div>
									</div>
								</div>

								<div class="row">
									<label class="col-md-3">Road Type</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$road_type;?>
									</div>
									<label class="col-md-3">Registry Date</label>
										<div class="col-md-3 text-bold pad-btm">
											<?=$flat_registry_date;?>
										</div>
									
								</div>
							
								<div class="row">
									<label class="col-md-3">Rain Water Harvesting</label>
									<div class="col-md-3 text-bold">
										<?=($is_water_harvesting=="t")?"Yes":"No";?>
									</div>
									<label class="col-md-3">Holding Type</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$holding_type;?>
									</div>
									
								</div>
								<div class="row">
									<label class="col-md-3">Apartment Code</label>
									<div class="col-md-3 text-bold">
										<?= $apt_code==''?"N/A":$apt_code;?>
									</div>
									<label class="col-md-3">Zone</label>
									<div class="col-md-3 text-bold">
										<?=($zone_mstr_id==1)?"Zone 1":"Zone 2";?>
									</div>
										
								</div>
							
							</div>
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
														<th>DOB</th>
														<th>Gender</th>
														<th>Is_Specially_Abled</th>
														<th>Is_Armed_Force</th>
													</tr>
												</thead>
												<tbody id="owner_dtl_append">
												<?php
												if(isset($prop_owner_detail))
												{
													foreach ($prop_owner_detail as $owner_detail)
													{
														?>
														<tr>
															<td><?=$owner_detail['owner_name'];?></td>
															<td><?=$owner_detail['guardian_name'];?></td>
															<td><?=$owner_detail['relation_type'];?></td>
															<td><?=$owner_detail['mobile_no'];?></td>
															<td><?=$owner_detail['aadhar_no'];?></td>
															<td><?=$owner_detail['pan_no'];?></td>
															<td><?=$owner_detail['email'];?></td>
															<td><?=  $owner_detail['dob']==''?'N/A':$owner_detail['dob']; ?></td>
															<td><?= $owner_detail['gender']==''?'N/A':$owner_detail['gender']; ?></td>
															<td><?=  $owner_detail['is_specially_abled']==''?'No':'Yes'; ?></td>
															<td><?=  $owner_detail['is_armed_force']==''?'No':'Yes'; ?></td>
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
								<h3 class="panel-title">Electricity Details</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-10">
										<div class="checkbox">
											<?php
											if($no_electric_connection=='t')
											{
												echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
											}
											else
											{
												echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
											}
											?>
											<label for="no_electric_connection">
											<span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
										</div>
									</label>
								</div>
								<div class="row">
									<label class="col-md-3">Electricity K. No</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$elect_consumer_no;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
								</div>
								<div class="row">
									<label class="col-md-3">ACC No.</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$elect_acc_no;?>
									</div>
									<label class="col-md-3">BIND/BOOK No.</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$elect_bind_book_no;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3">Electricity Consumer Category</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$elect_cons_category;?>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Building Plan/Water Connection Details</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-3">Building Plan Approval No. </label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$building_plan_approval_no;?>
									</div>
									<label class="col-md-3">Building Plan Approval Date </label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$building_plan_approval_date;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3">Water Consumer No. </label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$water_conn_no;?>
									</div>
									<label class="col-md-3">Water Connection Date</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$water_conn_date;?>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Property Details</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-3">Khata No.</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$khata_no;?>
									</div>
									<label class="col-md-3">Plot No.</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$plot_no;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3">Village/Mauja Name</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$village_mauja_name;?>
									</div>
									<label class="col-md-3">Area of Plot (in Decimal)</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$area_of_plot;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3">Road Type</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$road_type;?>
									</div>

									<div class="<?=($prop_type_mstr_id==4)?null:"hidden";?>">
										<label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier)</label>
										<div class="col-md-3 text-bold">
											<?=$land_occupation_date ?? NULL;?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Property Address</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-3">Property Address</label>
									<div class="col-md-7 text-bold pad-btm">
										<?=$prop_address;?>
									</div>
									
								</div>
								<div class="row">
									<label class="col-md-3">City</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$prop_city;?>
									</div>
									<label class="col-md-3">District</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$prop_dist;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3">Pin</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$prop_pin_code;?>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Correspondence Address</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-3">Correspondence Address</label>
									<div class="col-md-7 text-bold pad-btm">
										<?=$corr_address;?>
									</div>
									
								</div>
								<div class="row">
									<label class="col-md-3">City</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=$corr_city;?>
									</div>
									<label class="col-md-3">District</label>
									<div class="col-md-3 text-bold text-bold pad-btm">
										<?=$corr_dist;?>
									</div>
								</div>
								<div class="row">
									<label class="col-md-3">State</label>
									<div class="col-md-3 text-bold text-bold pad-btm">
										<?=$corr_state;?>
									</div>
									<label class="col-md-3">Pin</label>
									<div class="col-md-3 text-bold text-bold pad-btm">
										<?=$corr_pin_code;?>
									</div>
								</div>
							</div>
						</div>

						<div id="floor_dtl_hide_show" class="<?=($prop_type_mstr_id==4)?"hidden":null;?>">
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">Floor Details</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12 pad-btm">
											<span class="text-bold text-dark">Built Up :</span>
											<span class="text-thin">It refers to the entire carpet area along with the thickness of the external walls of the apartment. It includes the thickness of the internal walls and the columns.</span>

										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table table-bordered text-sm">
													<thead class="bg-trans-dark text-dark">
														<tr>
															<th>Floor No</th>
															<th>Usege Type</th>
															<th>Occupancy Type</th>
															<th>Construction Type</th>
															<th>Built Up Area  (in Sq. Ft)</th>
															<th>From Date</th>
															<th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
														</tr>
													</thead>
													<tbody id="floor_dtl_append">
												<?php
												if(isset($prop_floor_details))
												{
													foreach ($prop_floor_details as $floor_details)
													{
														?>
														<tr>
															<td>
																<?=$floor_details['floor_name'];?>
															</td>
															<td>
																<?=$floor_details['usage_type'];?>
															</td>
															<td>
																<?=$floor_details['occupancy_name'];?>
															</td>
															<td>
																<?=$floor_details['construction_type'];?>
															</td>
															<td>
																<?=$floor_details['builtup_area'];?>
															</td>
															<td>
																<?=date('Y-m', strtotime($floor_details['date_from']));?>
															</td>
															<td>
																<?=($floor_details['date_upto']!="")?date('Y-m', strtotime($floor_details['date_upto'])):"N/A";?>
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
								</div>
							</div>
						</div>

						<div class="panel panel-bordered panel-dark">
							<div class="panel-body">
								<div class="row">
									<label class="col-md-3">Does Property Have Mobile Tower(s) ?</label>
									<div class="col-md-3 text-bold pad-btm">
											<?=($is_mobile_tower=="t")?"Yes":"No";?>
									</div>
								</div>
								<div class="<?=($is_mobile_tower=="f")?"hidden":null;?>">
									<div class="row">
										<label class="col-md-3">Total Area Covered by Mobile Tower & its Supporting Equipments & Accessories (in Sq. Ft.)</label>
										<div class="col-md-3 text-bold">
											<?=$tower_area;?>
										</div>
										<label class="col-md-3">Date of Installation of Mobile Tower</label>
										<div class="col-md-3 text-bold">
											<?=$tower_installation_date;?>
										</div>
									</div>
									<hr />
								</div>

								<div class="row">
									<label class="col-md-3">Does Property Have Hoarding Board(s) ?</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=($is_hoarding_board=="t")?"Yes":"No";?>
									</div>
								</div>
								<div class="<?=($is_hoarding_board=="f")?"hidden":"";?>">
									<div class="row">
										<label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
										<div class="col-md-3 text-bold">
											<?=$hoarding_area;?>
										</div>
										<label class="col-md-3">Date of Installation of Hoarding Board(s)</label>
										<div class="col-md-3 text-bold">
											<?=$hoarding_installation_date;?>
										</div>
									</div>
									<hr />
								</div>

								<div class="row">
									<label class="col-md-3">Is property a Petrol Pump ?</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=($is_petrol_pump=="t")?"Yes":"No";?>
									</div>
								</div>
								<div class="<?=($is_petrol_pump=="f")?"hidden":"";?>">
									<div class="row">
										<label class="col-md-3">Underground Storage Area (in Sq. Ft.)</label>
										<div class="col-md-3 text-bold">
											<?=$under_ground_area;?>
										</div>
										<label class="col-md-3">Completion Date of Petrol Pump</label>
										<div class="col-md-3 text-bold">
											<?=$petrol_pump_completion_date;?>
										</div>
									</div>
									<hr />
								</div>

								<div class="row">
									<label class="col-md-3">Rainwater harvesting provision ?</label>
									<div class="col-md-3 text-bold pad-btm">
										<?=($is_water_harvesting=="t")?"Yes":"No";?>
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
										if($prop_tax_list)
										{
											$i=1; $qtr_tax=0;$lenght= sizeOf($prop_tax_list);
											foreach($prop_tax_list as $tax_list)
											{
												$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
												?>
												<tr>
													<td><?=$i++;?></td>
													<td><?=round($tax_list['arv'], 2);?></td>
													<td><?=$tax_list['qtr'];?> / <?=$tax_list['fy'];?></td>
													<td><?=round($tax_list['holding_tax'], 2);?></td>
													<td><?=round($tax_list['water_tax'], 2);?></td>
													<td><?=round($tax_list['latrine_tax'], 2);?></td>
													<td><?=round($tax_list['education_cess'], 2);?></td>
													<td><?=round($tax_list['health_cess'], 2);?></td>
													<td><?=round($tax_list['additional_tax'], 2);?></td>
													<td><?=round($qtr_tax, 2); ?></td>
													<td>
														<?php 
														if($i>$lenght)
														{
															?>
															<span class="text text-success text-bold">Current</span>
															<?php 
														}
														else
														{
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
								<h3 class="panel-title">Payment Details</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead class="bg-trans-dark text-dark">
											<th>Sl No.</th>
											<th>Transaction No</th>
											<th>Payment Mode</th>
											<th>Date</th>
											<th>From Quarter / Year</th>
											<th>Upto Quarter / Year</th>
											<th>Amount</th>
											<th>View</th>
										</thead>
										<tbody>
											<?php 
											$i=0;
											if(isset($property_payment_detail))
											{
												foreach($property_payment_detail as $payment_detail)
												{
													?>
													<tr>
														<td><?=++$i;?></td>
														<td class="text-bold"><?=$payment_detail['tran_no'];?></td>
														<td><?=$payment_detail['transaction_mode'] ?></td>
														<td><?=$payment_detail['tran_date'];?></td>
														<td><?=$payment_detail['from_qtr']." / ".$payment_detail['fy'];?></td>
														<td><?=$payment_detail['upto_qtr']." / ".$payment_detail['upto_fy'];?></td>
														<td><?=$payment_detail['payable_amt'];?></td>
														<td><a href="<?=base_url('Mobi/payment_tc_receipt/'.md5($payment_detail['id']));?>" class="btn btn-primary btn_wait_load">View</a></td>
													</tr>
													<?php 
												}
											}
											if(isset($saf_payment_detail))
											{
												foreach($saf_payment_detail as $payment_detail)
												{
													?>
													<tr>
														<td><?=++$i;?></td>
														<td class="text-bold"><?=$payment_detail['tran_no'];?> <span class="label label-primary"> SAF Payment </span></td>
														<td><?=$payment_detail['transaction_mode'] ?></td>
														<td><?=$payment_detail['tran_date'];?></td>
														<td><?=$payment_detail['from_qtr']." / ".$payment_detail['fy'];?></td>
														<td><?=$payment_detail['upto_qtr']." / ".$payment_detail['upto_fy'];?></td>
														<td><?=$payment_detail['payable_amt'];?></td>
														<!-- <td><a href="<?=base_url('safDemandPayment/saf_payment_receipt/'.md5($payment_detail['id']));?>" class="btn btn-primary">View</a></td> -->
														<td><a href="<?=base_url('mobisafDemandPayment/saf_payment_receipt/'.md5($payment_detail['id']));?>" class="btn btn-primary btn_wait_load">View</a></td>
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
									<?php if($demand_detail):
										$i=1; $total_due = 0;
									?>
									<?php foreach($demand_detail as $tot_demand):
										$i==1? $first_qtr = $tot_demand['qtr']:'';
										$i==1? $first_fy = $tot_demand['fy']:''; 
										  
										  $total_demand = $tot_demand['balance'];
										  $total_due = $total_due + $total_demand;
										  $total_quarter=$i;
										  $i++;
	  
									?>
									<?php endforeach; ?>
										<tr>
											<td><b style="color:#bf06fb;">Total Dues</b></td>
											<td><strong style="color:#bf06fb;">:</strong></td>
											<td colspan="4">
											 <b style="color:#bf06fb;"><?php if($total_due){echo $total_due; }else{ echo "N/A";} ?></b>
											</td>
										<tr>
											<td>Dues From</td>
											<td><strong>:</strong></td>
											<td>
												<?php echo $first_qtr; ?> / <?php echo $first_fy; ?>
											</td>
											<td>Dues Upto</td>
											<td><strong>:</strong></td>
											<td>
												<?php echo $tot_demand['qtr']; ?> / <?php echo $tot_demand['fy']; ?>		</td>
										</tr>
										<tr>
											<td>Total Quarter(s)</td>
											<td><strong>:</strong></td>
											<td colspan="4"><?php if($total_quarter){echo $total_quarter; }else{ echo "N/A";} ?></td>
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
										<th>Demand Aount</th>
									</thead>
									<tbody>
										<?php 
										$j=0;
										if($demand_detail)
										{
											foreach($demand_detail as $demand)
											{
												$tax = $demand["holding_tax"]+$demand["water_tax"]+$demand["education_cess"]+$demand["health_cess"]+$demand["latrine_tax"];
												?>			
												<tr>
													<td><?=++$j;?></td>
													<td><?=$demand['qtr'];?> / <?=$demand['fy'];?></td>
													<td><?=$tax;?></td>
													<td><?=$demand['additional_tax'];?></td>
													<td><?=$demand['balance'];?></td>
												</tr>
												<?php 
											}
										}
										else
										{
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
								<?php if($demand_detail){ ?>
									<a href="<?php echo base_url('Mobi/confirm_payment/'.md5($prop_dtl_id));?>" type="button" class="btn btn-primary" id="propTax_details" name="prop_details">Pay Property Tax</a>
								<?php } ?>
								<a href="<?php echo base_url('Mobi_PropSpecialDocUpload/CitizenPropDetailsWithoutDocUpload/'.md5($prop_dtl_id));?>" id="consession_details" class="btn btn-primary">Consession Details Update</a>
								<?php if ($prop_type_mstr_id!=4 && $new_holding_no!=""){ ?>
                                	<a href="<?= base_url('CitizenProperty/comparativeTax/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Comparative Demand</a>
                            <?php } ?>
							</div>
						</div>
	</div>
					
			
			
  
<?=$this->include("layout_mobi/footer");?>
<script>

	$("#deu_details").click(function(){
		$("#content-container").css('opacity', '0.3');
		$("#loadingDivs").show();
		
	});
	$("#pay_details").click(function(){
		$("#content-container").css('opacity', '0.3');
		$("#loadingDivs").show();
		
	});
	$("#propTax_details").click(function(){
		$("#propTax_details").html("Please Wait...");
		
	});
	$("#consession_details").click(function(){
		$("#consession_details").html("Please Wait...");
		
	});

</script>