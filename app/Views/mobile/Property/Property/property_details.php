
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
										<b>Holding No. :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['holding_no']?$basic_details['holding_no']:"N/A"; ?>
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
										<?=$basic_details['area_of_plot']?$basic_details['area_of_plot']:"N/A"; ?>(In desimal)
									</div>
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<b>Assessment Type :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['assessment_type']?$basic_details['assessment_type']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<b> Khata :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['khata_no']?$basic_details['khata_no']:"N/A"; ?>
									</div>
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<b> Plot No. :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['plot_no']?$basic_details['plot_no']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<b>Mauja Name :</b>
									</div>
									<div class="col-md-3">
										<?=$basic_details['village_mauja_name']?$basic_details['village_mauja_name']:"N/A"; ?>
									</div>
									<div class="col-md-1">
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
							</div>
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
									</thead>
									<tbody>
										<?php if($owner_details): ?>
											<?php foreach($owner_details as $owner_details): ?>
											<tr>
											  <td><?php echo $owner_details['owner_name']; ?></td>
											  <td><?php echo $owner_details['relation_type']; ?></td>
											  <td><?php echo $owner_details['guardian_name']; ?></td>
											  <td><?php echo $owner_details['mobile_no']; ?></td>
											</tr>
											<?php endforeach; ?>
										<?php else: ?>
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
													$i=1; $qtr_tax=0;  $lenght= sizeOf($tax_list); ?>
												<?php foreach($tax_list as $tax_list): 
													$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
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
												<?php if($i>$lenght){ ?>
													<td style="color:red;">Current</td>
												<?php } else { ?>
													<td>Old</td>
												<?php } ?>
											</tr>
											<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="7" style="text-align:center;color:red;"> Data Are Not Available!!</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Payment Details</h3>
							</div>
							<div class="table-responsive">
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="bg-trans-dark text-dark">
										<th scope="col"> Sl No.</th>
										<th scope="col"> Transaction No</th>
										<th scope="col"> Payment Mode</th>
										<th scope="col"> Date</th>
										<th scope="col"> From Quarter</th>
										<th scope="col"> From Year</th>
										<th scope="col"> Upto Quarter</th>
										<th scope="col"> Upto Year</th>
										<th scope="col"> Amount</th>
										<th scope="col"> Action</th> 
									</thead>
									<tbody>
									  <?php if($payment_detail):
									  $i=1;
									  ?>
									  <?php foreach($payment_detail as $payment_detail): 
									  ?>
										<tr>
											<td><?php echo $i++; ?></td>
											<td><?php echo $payment_detail['tran_no']; ?></td>
											<td><?php echo $payment_detail['transaction_mode']; ?></td>
											<td><?php echo $payment_detail['tran_date']; ?></td>
											<td><?php echo $payment_detail['from_qtr']; ?></td>
											<td><?php echo $payment_detail['fy']; ?></td>
											<td><?php echo $payment_detail['upto_qtr']; ?></td>
											<td><?php echo $payment_detail['fy']; ?></td>
											<td><?php echo $payment_detail['payable_amt']; ?></td>
											<td><a href="<?php echo base_url('Mobi/payment_tc_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
										</tr>
									  <?php endforeach; ?>
									  <?php else: ?>
											<tr>
												<td colspan="10" style="text-align:center;color:red;"> Data Are Not Available!!</td>
											</tr>
									  <?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>-->
						<div id="loadingDivs" style="display: none; background: url(http://192.168.0.16:822/RMCDMC/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 150px; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
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
										$crnt_dm = date('m');
										$crnt_dmr=(12-$crnt_dm)+3;
										$total_mnth = ($total_quarter*3)-$crnt_dmr;
										foreach($demand_detail as $demand_detail): 
										
											$fine_mnth = $total_mnth-(3*$j);
											$penalty = (($demand_detail['balance'])/100)*$fine_mnth;
											if($penalty>0){
												$tol_pent = $penalty;
											}else{
												$tol_pent = 0;
											}
											$total_demand = $demand_detail['balance'] + $tol_pent;
										?>			
										<tr>
											<td><?php echo $j++; ?></td>
											<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fy']; ?></td>
											<td><?php echo $demand_detail['balance']; ?></td>
											
										</tr>
										<?php endforeach; ?>
										<?php else: ?>
										<tr>
											<td colspan="5" style="text-align:center;color:green;"> No Dues Are Available!!</td>
										</tr>
										<?php endif ?>
										
									</tbody>
								</table>
							</div>
						</div>
						<div class="panel">
							<div class="panel-body text-center">
								<a href="<?php echo base_url('mobi/property_due_details/'.$id);?>" type="button" class="btn btn-primary btn-labeled" id="deu_details" name="deu_details">View Demand Details</a>
								<a href="<?php echo base_url('mobi/payment_details/'.$id);?>" type="button" class="btn btn-warning btn-labeled" id="pay_details" name="pay_details">View Payment Details</a>
								<?php if($demand_detail){ ?>
									<a href="<?php echo base_url('mobi/confirm_payment/'.$id);?>" type="button" class="btn btn-purple btn-labeled" id="propTax_details" name="prop_details">Pay Property Tax</a>
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
		$("#content-container").css('opacity', '0.3');
		$("#loadingDivs").show();
		
	});
		
</script>