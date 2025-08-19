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
					
				</div>
			</div>
					
                
            
            <!-------Owner Details-------->
				
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
					<tbody id="tr_tbody">
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
                
            
			
			<!-------ARV List----->
					
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Tax List</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<th scope="col">Sl No.</th>
								<th scope="col">ARV</th>
								<th scope="col">Effect From</th>
								<th scope="col">Holding Tax</th>
								<th scope="col">Water Tax</th>
								<th scope="col">Conservancy/<br>Latrine Tax</th>
								<th scope="col">Education Cess</th>
								<th scope="col">Health Cess</th>
								<th scope="col">RWH Penalty</th>
								<th scope="col">Quarterly Tax</th>
								<th scope="col">Status</th>
							</thead>		
							<tbody id="tr_tbody">
								<?php if($tax_list):
								$i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
								<?php foreach($tax_list as $tax_list): 
								$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
								?>
								<tr>
								  <td><?php echo $i++; ?></td>
								  <td><?php echo $tax_list['arv']; ?></td>
								  <td><?php echo $tax_list['qtr'];?> / <?php echo $tax_list['fy']; ?></td>
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
										<td colspan="10" style="text-align:center;color:red;"> Data Are Not Available!!</td>
									</tr>
								<?php endif ?>
							</tbody>
						</table>
					</div>
				</div>
					
					
					<!-------Due Detail List-------->
				<div id="loadingDivs" style="display: none; background: url(http://192.168.0.16:822/RMCDMC/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 150px; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Due Detail List</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<tbody>
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
						<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:12px;">
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
						<a href="<?php echo base_url('mobi/property_details/'.$id);?>" type="button"class="btn btn-primary btn-labeled" id="prop_details" name="prop_details">View Property Details</a>
						<a href="<?php echo base_url('mobi/payment_details/'.$id);?>" type="button" class="btn btn-warning btn-labeled" id="pay_details" name="pay_details">View Payment Details</a>
						<?php if($demand_detail){ ?>
							<a href="<?php echo base_url('mobi/confirm_payment/'.$id);?>" type="button" class="btn btn-purple btn-labeled" id="propTax_details" name="prop_details">Pay Property Tax</a>
						<?php } ?>
					</div>
				</div>
			</div>
	
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script>

	$("#prop_details").click(function(){
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
