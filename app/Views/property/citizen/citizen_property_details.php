<?= $this->include('layout_horizontal/header');?>

  <!-- ======= Hero Section ======= -->
  
	<section id="cta" class="cta">
      <img src="<?=base_url();?>/public/assets/img/gallery/thumbs/swatch-bharat2.jpg" alt="Los Angeles" width="100%" height="300px" style="margin-top: 73px;">
    </section><!-- End Cta Section -->

	<!-- ======= Cta Section ======= -->
   
    <section id="contact" class="contact" style="margin-top: -205px;">
      <div class="container">
        <div class="col-lg-12">
			<div class="panel panel-default">
	  <div class="panel-heading" style="text-align:left;"><h5><b>Property Services Property Details	
	  <a href="" style="float:right;color:#000;">Back</a></b></h5></div>
	  <div class="panel-body">
		
			
			<form action="<?php echo base_url('Home/search_Property_List');?>" method="post" role="form" class="php-email-form">
			<div class="col-md-12" style="font-size:14px;">
				
				<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
				<thead class="thead-light">
					<tr>
						<th colspan="6" style="background-color:#843139;color:white;">Basic Details</th>
						</tr>
									<tbody>
									<?php if($basic_details): ?>
									<tr>
										<td>Ward No.</td>
										<td><strong>:</strong></td>
										<td>
										 <?php echo $basic_details['ward_no']; ?>
										</td>
										<td>Holding No.</td>
										<td><strong>:</strong></td>
										<td>
										<?php echo $basic_details['holding_no']; ?></td>
									</tr>
									<tr>
										<td>New Ward No</td>
										<td><strong>:</strong></td>
										<td>
										 <?php echo $basic_details['ward_no']; ?>
										</td>
										<td>15 Digit House No.</td>
										<td><strong>:</strong></td>
										<td>N/A</td>
									</tr>
									<tr>
										<td>Property Type</td>
										<td><strong>:</strong></td>
										<td>
										<?php echo $basic_details['property_type']; ?>
										</td>
										<td>Ownership Type</td>
										<td><strong>:</strong></td>
										<td>
										<?php echo $basic_details['ownership_type']; ?></td>
									</tr>
									<tr>
										<td>Address</td>
										<td><strong>:</strong></td>
										<td>
										 <?php echo $basic_details['prop_address']; ?>
										</td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>Mohalla</td>
										<td><strong>:</strong></td>
										<td>
										GT ROAD HINDPIRHI
										</td>
										<td>Entry Type</td>
										<td><strong>:</strong></td>
										<td>
										ASSESSMENT										</td>
									</tr>
									<tr>
										<td>Khata</td>
										<td><strong>:</strong></td>
										<td>
										 <?php echo $basic_details['khata_no']; ?>  
										</td>
										<td>Plot No.</td>
										<td><strong>:</strong></td>
										<td>
										<?php echo $basic_details['plot_no']; ?> </td>
									</tr>
									<tr>
										<td>Mauja Name</td>
										<td><strong>:</strong></td>
										<td>
										 N/A 
										</td>
										<td>Width Of Road</td>
										<td><strong>:</strong></td>
										<td>
										 N/A 
										</td>
										
									</tr>
									
									<tr>
										<td>Area Of Plot</td>
										<td><strong>:</strong></td>
										<td>
										 <?php echo $basic_details['area_of_plot']; ?> 
										</td>
										<td>Rainwater Harvesting Provision</td>
										<td><strong>:</strong></td>
										<td>
										<?php echo $basic_details['is_water_harvesting']; ?></td>
									</tr>
									<tr>
										<td>Thana</td>
										<td><strong>:</strong></td>
										<td>
										 N/A 
										</td>
										<td></td>
										<td></td>
										<td>
										</td>
									</tr>
									<?php endif; ?>
									</tbody>
									</thead>
									</table>
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  <tr>
						<th colspan="10" style="background-color:#843139;color:white;">Owner Details</th>
						</tr>
    <tr>
      <th scope="col">Owner Name</th>
      <th scope="col">R/W Guardian</th>
      <th scope="col">Guardian's Name</th>
      <th scope="col">Mobile No</th>
    </tr>
  </thead>
  <tbody>
	<?php if($owner_details): ?>
    <tr>
      <td><?php echo $owner_details['owner_name']; ?></td>
      <td><?php echo $owner_details['relation_type']; ?></td>
      <td><?php echo $owner_details['guardian_name']; ?></td>
	  <td><?php echo $owner_details['mobile_no']; ?></td>
    </tr>
	<?php endif; ?>
	</tbody>
	</table>
	<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  <tr>
						<th colspan="6" style="background-color:#843139;color:white;">Occupancy Details</th>
						</tr>
    <tr>
      <th scope="col">Sl No.</th>
      <th scope="col">Construction Type</th>
      <th scope="col">Occupancy Type</th>
      <th scope="col">Use Type</th>
	  <th scope="col">Total Area (in Sq. Ft.)</th>
      <th scope="col">Total Taxable Area (in Sq. Ft.)</th>
      
    </tr>
  </thead>
  <tbody>
  <?php if($occupancy_detail):
  $i=1;
  ?>
	<?php foreach($occupancy_detail as $occupancy_detail): ?>
    <tr>
      <td><?php echo $i++; ?></td>
	  <td><?php echo $occupancy_detail['construction_type']; ?></td>
	  <td><?php echo $occupancy_detail['occupancy_name']; ?></td>
      <td><?php echo $occupancy_detail['usage_type']; ?></td>
      <td><?php echo $occupancy_detail['builtup_area']; ?></td>
	  <td><?php echo $occupancy_detail['builtup_area']; ?></td>
    </tr>
	<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
	</table>
	<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  <tr>
						<th colspan="10" style="background-color:#843139;color:white;">Tax Details</th>
						</tr>
  </thead>
  <tbody>
  
  <table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
									<tbody>
									<?php if($tax_list):?>
									<?php foreach($tax_list as $tax_arv): 
										$total_arv = 0;
										$total_arv = $total_arv + $tax_arv['arv'];
									?>
									<?php endforeach; ?>
									<tr>
										<td><b style="color:#bf06fb;">Total ARV</b></td>
										<td><strong style="color:#bf06fb;">:</strong></td>
										<td>
										 <b style="color:#bf06fb;"><?php echo $total_arv;?></b>
										</td>
									<tr>
										<td>Effect From</td>
										<td><strong>:</strong></td>
										<td>
										 	Quarter - 4 : Year - 1993-1994
										</td>
										
									</tr>
									<?php endif; ?>
									</tbody>
									</table>
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
    <tr>
      <th scope="col">Holding Tax</th>
      <th scope="col">Water Tax</th>
      <th scope="col">Conservancy/Latrine Tax</th>
      <th scope="col">Education Cess</th>
      <th scope="col">Health Cess</th>
	  <th scope="col">Quarterly Tax</th>
      
    </tr>
  </thead>
  <tbody>
    <tr>
	<?php if($tax_list):
  $qtr_tax=0; ?>
  <?php foreach($tax_list as $tax_list): 
  $qtr_tax=$tax_list['hold_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
  ?>
    <tr>
      
	  <td><?php echo $tax_list['hold_tax']; ?></td>
	  <td><?php echo $tax_list['water_tax']; ?></td>
      <td><?php echo $tax_list['latrine_tax']; ?></td>
      <td><?php echo $tax_list['education_cess']; ?></td>
	  <td><?php echo $tax_list['health_cess']; ?></td>
	  <td><?php echo $qtr_tax; ?></td>
      
    </tr>
	<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
	</table>
  </tbody>
  </table>
  
  	<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  <tr>
						<th colspan="10" style="background-color:#843139;color:white;">Payment Details</th>
						</tr>
  </thead>
  <tbody>
 
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  
    <tr>
      <th scope="col"> Sl No.</th>
      <th scope="col"> Transaction No</th>
      <th scope="col"> Payment Mode</th>
      <th scope="col"> Date</th>
	  <th scope="col"> From Quarter</th>
      <th scope="col"> From Year</th>
      <th scope="col"> Upto Quarter</th>
      <th scope="col"> Upto Year</th>
      <th scope="col"> Amount</th>
	  <th scope="col"> View</th>
      
    </tr>
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
	  <td><a href="<?php echo base_url('Home/citizen_due_details');?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
      
    </tr>
	<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
	</table>
  </tbody>
  </table>
			</div>			
				</form>
				<form action="" method="post" role="form" class="php-email-form">
						<div class="table-responsive">
						<div class="panel-heading" style="text-align:left;color:#3c96f3;"><h5><b>Due Detail List	</b></div></br>
				<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<tbody>
<?php if($demand_detail):
  $i=1; $total_due = 0;
	
  ?>
  <?php foreach($demand_detail as $tot_demand):
$i==1? $first_qtr = $tot_demand['qtr']:'';
$i==1? $first_fy = $tot_demand['fy']:''; 
  $demand =$tot_demand['amount']+$tot_demand['holding_tax']+$tot_demand['water_tax']+$tot_demand['education_cess']+$tot_demand['health_cess']+$tot_demand['lighting_tax']+$tot_demand['latrine_tax']+$tot_demand['harvest_tax'];
  $total_demand = $demand;
  $total_due = $total_due + $total_demand;
  $z=0;
  $z=$z+$i;
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
										<td><?php echo $z; ?></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									
									</tbody>
									</table>
			<table class="table table-striped table-hover" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
				<tbody>
				<tr style="background-color:#e2b4b4;">
					<th>Sl No.</th>
<th>Year</th>
<th>Quarter</th>
<th>Due Date</th>
<th>Demand</th>
<th>Addtional Holding Tax</th>
<th>Diff. Penalty</th>
<th>Total Demand</th>
				</tr>
	<?php foreach($demand_detail as $demand_detail): 
  
  ?>			
				
										<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $demand_detail['fy']; ?></td>
<td><?php echo $demand_detail['qtr']; ?></td>
<td><?php echo $demand_detail['']; ?></td>
<td><?php echo $demand; ?></td>
<td><?php echo 0.00; ?></td>
<td><?php echo 0.00; ?></td>
<td><?php echo $total_demand; ?></td>
</tr>
<?php endforeach; ?>
	<?php endif; ?>


											</tbody></table>
					</div><br><br>
					<table class="table" style="align:center;">
					
					<th>
					<a href="<?php echo base_url('Home/citizen_due_details/'.$id);?>" type="button" style="float:left;" class="button button1">View Demand Details</a>
			<a href="<?php echo base_url('Home/citizen_payment_details/'.$id);?>" type="button" class="button button2">View Payment Details</a>
			<a href="<?php echo base_url('Home/citizen_confirm_payment/'.$id);?>" type="button" class="button button5">Pay Property Tax Online</a>
			<th>
			</table>
							</form>
			
	  </div>
	</div>
	</div>

      </div>
    </section><!-- End Contact Section -->
  

<?= $this->include('layout_horizontal/footer');?>