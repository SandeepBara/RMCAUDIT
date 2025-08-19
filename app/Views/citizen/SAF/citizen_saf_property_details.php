
<?= $this->include('layout_home/header');?>
<style>
	
.row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
           <div class="panel-body">
				<div class="row">
					<div class="col-sm-6 text-danger text-center">
						<b>Application No. :&nbsp; <?=$basic_details['saf_no']; ?></b>
					</div>
					<div class="col-sm-6 text-danger">
						<b>Application Status : &nbsp;<?=$SAFLevelPending; ?></b>
					</div>
				</div>
			</div>
			<div id="page-content">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<div class="panel-control">
							<a href="<?php echo base_url('safDemandPayment/saf_due_details/'.$id);?>" class="btn btn-default">Back</a>
						</div>
						<h3 class="panel-title">Basic Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-3">
								<b>Ward No. :</b>
							</div>
							<div class="col-sm-3">
								<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
							</div>
							
							<div class="col-sm-3">
								<b>Application No. :</b>
							</div>
							<div class="col-sm-3">
								<?=$basic_details['saf_no']?$basic_details['saf_no']:"N/A"; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b>Property Type :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
							</div>
							
							<div class="col-md-3">
								<b>Ownership Type :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<b>Area Of Plot :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['area_of_plot']?$basic_details['area_of_plot']:"N/A"; ?>(In desimal)
							</div>
							
							<div class="col-md-3">
								<b>Assessment Type :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['assessment_type']?$basic_details['assessment_type']:"N/A"; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b> Khata :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['khata_no']?$basic_details['khata_no']:"N/A"; ?>
							</div>
							
							<div class="col-md-3">
								<b> Plot No. :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['plot_no']?$basic_details['plot_no']:"N/A"; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b>Mauja Name :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['village_mauja_name']?$basic_details['village_mauja_name']:"N/A"; ?>
							</div>
							
							<div class="col-md-3">
								<b>Rainwater Harvesting Provision :</b>
							</div>
							<div class="col-md-3">
								<?php if($basic_details['is_water_harvesting']=='t'){ ?>
								YES
								<?php } else if($basic_details['is_water_harvesting']=='f') { ?>
								No
								<?php } else {?>
								N/A
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b>Address :</b>
							</div>
							<div class="col-md-9">
								<?=$basic_details['prop_address']?$basic_details['prop_address']:"N/A"; ?>
							</div>
						</div>
					</div>
									
				</div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Owner Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
								  <th scope="col">Owner Name</th>
								  <th scope="col">R/W Guardian</th>
								  <th scope="col">Guardian's Name</th>
								  <th scope="col">Mobile No</th>
								</tr>
							</thead>
							<tbody>
								<?php if($owner_details==""){ ?>
									<tr>
										<td colspan="4" style="text-align:center;color:red;"> Data Not Available...</td>
									</tr>
								<?php }else{ ?>
								<?php foreach($owner_details as $owner_details): ?>
									<tr>
									  <td><?php echo $owner_details['owner_name']; ?></td>
									  <td><?php echo $owner_details['relation_type']; ?></td>
									  <td><?php echo $owner_details['guardian_name']; ?></td>
									  <td><?php echo $owner_details['mobile_no']; ?></td>
									</tr>
								<?php endforeach; ?>
								<?php } ?>
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
							</thead>
							<tbody>
								<?php if($occupancy_detail):
								$i=1;
								?>
								<?php foreach($occupancy_detail as $occupancy_detail): ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $occupancy_detail['floor_name']; ?></td>
									<td><?php echo $occupancy_detail['usage_type']; ?></td>
									<td><?php echo $occupancy_detail['occupancy_name']; ?></td>
									<td><?php echo $occupancy_detail['construction_type']; ?></td>
									<td><?php echo $occupancy_detail['builtup_area']; ?></td>
									<td><?php echo $occupancy_detail['carpet_area']; ?></td>
								</tr>
							<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="7" style="text-align:center;color:red;"> Data Not Available...</td>
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
									<?php if($tax_list):
										$i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
									<?php foreach($tax_list as $tax_list): 
										$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
									?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $tax_list['arv']; ?></td>
									<td>Quarter : <?php echo $tax_list['qtr']; ?> / Year : <?php echo $tax_list['fy']; ?></td>
									<td><?php echo $tax_list['holding_tax']; ?></td>
									<td><?php echo $tax_list['water_tax']; ?></td>
									<td><?php echo $tax_list['latrine_tax']; ?></td>
									<td><?php echo $tax_list['education_cess']; ?></td>
									<td><?php echo $tax_list['health_cess']; ?></td>
									<td><?php echo $tax_list['additional_tax']; ?></td>
									<td><?php echo $qtr_tax; ?></td> 
									<?php if($i>$lenght){ ?>
										<td style="color:red;">Current</td>
									<?php } else { ?>
										<td>Old</td>
									<?php } ?>
								</tr>
								<?php endforeach; ?>
								<?php else: ?>
								<tr>
									<td colspan="11" style="text-align:center;color:red;"> Data Not Available...</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
										
									
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Due Detail List</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<tbody>
								<?php if($demand_detail):
									$i=1; $total_due = 0; $j=1;	
								?>
								<?php foreach($demand_detail as $tot_demand):
									$i==1? $first_qtr = $tot_demand['qtr']:'';
									$i==1? $first_fy = $tot_demand['fy']:''; 
									  $demand =$tot_demand['balance'];
									  $total_demand = $demand;
									  $total_due = $total_due + $total_demand;
									  $total_quarter = $i;
									  $i++;
								?>
								<?php endforeach; ?>
								<tr>
									<td><b style="color:#bf06fb;">Total Dues</b></td>
									<td><strong style="color:#bf06fb;">:</strong></td>
									<td>
									 <b style="color:#bf06fb;"><?php echo $total_due; ?></b>
									</td><td></td><td></td>
									<td></td>
								<tr>
									<td>Dues From</td>
									<td><strong>:</strong></td>
									<td>
										Quarter <?php echo $first_qtr; ?> / Year <?php echo $first_fy; ?>
									</td>
									<td>Dues Upto</td>
									<td><strong>:</strong></td>
									<td>
										Quarter <?php echo $tot_demand['qtr']; ?> / Year <?php echo $tot_demand['fy']; ?>		</td>
								</tr>
								<tr>
									<td>Total Quarter(s)</td>
									<td><strong>:</strong></td>
									<td colspan="4"><?php echo $total_quarter; ?></td>
									
								</tr>
								<?php endif ?>
							</tbody>
						</table>
						
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
									<th>Sl No.</th>
									<th>Quarter / Year</th>
									<th>Demand</th>
							</thead>
							<tbody>
								<?php if($demand_detail):
								$j=1;
								foreach($demand_detail as $demand_detail): 
								?>			
								<tr>
									<td><?php echo $j++; ?></td>
									<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fy']; ?></td>
									<td><?php echo $demand_detail['balance']; ?></td>
								</tr>
								<?php endforeach; ?>
								<?php else: ?>
								<tr>
									<td colspan="3" style="text-align:center;color:green;"> No Dues Are Available!!</td>
								</tr>
								<?php endif ?>
								
							</tbody>
						</table>
					</div>
				</div>
										
				<div class="panel">
					<div class="panel-body text-center" style="height:70px;">
						<?php if($msg['doc_upload_status']==1){ ?>
							<a href="<?php echo base_url('CitizenSAF/documentView/'.$id);?>" type="button" class="btn btn-info btn-labeled"><i class="fa fa-eye" aria-hidden="true"></i> View Document</a>
						<?php } else { ?>
							<a href="<?php echo base_url('CitizenSAF/documentUpload/'.$id);?>" type="button" class="btn btn-mint btn-labeled"><i class="fa fa-upload" aria-hidden="true"></i> Upload Document</a>
						<?php } ?>
						<a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_due_details/'.$id);?>" type="button" class="btn btn-primary btn-labeled"><i class="fa fa-eye" aria-hidden="true"></i> View Demand</a>
						<a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_payment_details/'.$id);?>" type="button" class="btn btn-warning btn-labeled"><i class="fa fa-eye" aria-hidden="true"></i> View Payment</a>
						<?php if($payment_detail['tran_no']==""){ ?>
							<a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_confirm_payment/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Pay Property Tax</a>
						<?php } ?>
						
					</div>
				</div>
								
			</div>
					
			
			
  
<?= $this->include('layout_home/footer');?>