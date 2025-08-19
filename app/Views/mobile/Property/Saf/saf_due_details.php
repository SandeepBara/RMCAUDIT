
<?=$this->include("layout_mobi/header");?>
<style>
	
.row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                

<div id="page-content">
	
	    
	            
	<!-- ======= Cta Section ======= -->
			
				
						<div class="panel panel-bordered panel-dark">
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6 text-danger">
										<b>Application No. :&nbsp; <?=$basic_details['saf_no']; ?></b>
									</div>
									<div class="col-sm-6 text-danger">
										<b>Application Status : &nbsp;<?=$SAFLevelPending; ?></b>
									</div>
								</div>
							</div>
							
							<div class="panel-heading">
								<div class="panel-control">
									<a href="<?php echo base_url('mobisafDemandPayment/saf_Property_Tax');?>" class="btn btn-default">Back</a>
								</div>
								<h3 class="panel-title">Basic Details</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-2">
										<b>Ward No. :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
									</div>
									<div class="col-sm-1">
									</div>
									<div class="col-sm-3">
										<b>Application No. :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['saf_no']?$basic_details['saf_no']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<b>Property Type :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
									</div>
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<b>Ownership Type :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<b>Address :</b>
									</div>
									<div class="col-md-10">
										<?=$basic_details['prop_address']?$basic_details['prop_address']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<b>Area Of Plot :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['area_of_plot']?$basic_details['area_of_plot']:"N/A"; ?> ( In decimal)
									</div>
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<b>Rainwater Harvesting Provision :</b>
									</div>
									<div class="col-md-3">
										<?php if($basic_details['is_water_harvesting']=='t'){ ?>
										YES
										<?php } else if($basic_details['is_water_harvesting']=='f'){ ?>
										No
										<?php }else{ ?>
										N/A
										<?php } ?>
									</div>
									
								</div>
								<div class="row">
									<div class="col-md-2">
										<b>Assessment Type :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['assessment_type']?$basic_details['assessment_type']:"N/A"; ?>
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
											<th scope="col">DOB</th>
											<th scope="col">Gender</th>
											<th scope="col">Is_Specially_Abled</th>
											<th scope="col">Is_Armed_Force</th>
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
										  <td><?= isset($owner_details['dob'])==''?'N/A':$owner_details['dob']; ?></td>
										  <td><?= isset($owner_details['gender'])==''?'N/A':$owner_details['gender']; ?></td>
										  <td><?= isset($owner_details['is_armed_force'])=='f'?'No':'Yes'; ?></td>
										  <td><?= isset($owner_details['is_specially_abled'])=='f'?'No':'Yes'; ?></td>
										</tr>
									<?php endforeach; ?>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Tax Details</h3>
							</div>
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
									<?php if($tax_list):
										$i=1; $qtr_tax=0;  $lenght= sizeOf($tax_list);?>
									<?php foreach($tax_list as $tax_list): 
										$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
									?>
										<tr>
										  <td><?php echo $i++; ?></td>
										  <td><?php echo $tax_list['arv']; ?></td>
										  <td><?php echo $tax_list['qtr'];?>/<?php echo $tax_list['fy']; ?></td>
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
									<?php endforeach ?>
									<?php else: ?>
									<tr>
										<td colspan="11" style="text-align:center;color:red;"> Data Are Not Available!!</td>
									</tr>
									<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Due Detail List</h3>
							</div>
							<?php
							if(isset($Paysafpayment['status']) && in_array($Paysafpayment['status'], [1, 2]))
							{
								?>
								<div class="text text-success text-center" role="alert">
									No Dues Available.
								</div>
								<?php
							}
							else
							{
								?>
								<div class="table-responsive">
									<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif;font-size:12px; ">
										<tbody>
										<?php if(isset($demand_detail)):
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
												<td colspan="4"><?php if($total_quarter){echo $total_quarter; }else{ echo "N/A";} ?></td>
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
												
										</thead>
										<tbody>
											
											<?php if(isset($demand_detail)):
											$total_demand = 0; $j=1;
											foreach($demand_detail as $demand_detail): 
												
												$total_demand = $total_demand + $demand_detail['balance'];
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
								<?php
							}
							?>
						</div>
						<div class="panel">
							<div class="panel-body text-center">
								<a href="<?php echo base_url('mobisafDemandPayment/saf_property_details/'.$id);?>" class="btn btn-primary">View Property Details</a>
								
								<?php 
								if(isset($payment_detail)):?>
									<?php foreach($payment_detail as $payment_detail): ?>
									<?php endforeach;?>
								<?php endif;
								// !($is_geo_tag_done??true)
								if(!($is_geo_tag_done??true)){
									?>
									<a href="<?php echo base_url('SafVerification/verificationUpload/'.$basic_details["saf_dtl_id"]."/0");?>" class="btn btn-primary">Go To Geo tag</a>
									<?php 
								}else{
									if(!empty($demand_detail))
									{
										?>
										<a href="<?php echo base_url('mobisafDemandPayment/saf_confirm_payment/'.$id);?>" class="btn btn-primary">Pay Property Tax</a>
										<?php 
									}
									else
									{
										?>
										<a href="<?php echo base_url('mobisafDemandPayment/saf_payment_details/'.$id);?>" class="btn btn-primary">View Payment Details</a>
										<?php 
									}

								}
								
								?>

							</div>
						</div>
					
				
			
	
</div>
		
			
  


<?=$this->include("layout_mobi/footer");?>