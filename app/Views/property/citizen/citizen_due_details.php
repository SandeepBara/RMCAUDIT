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
	  <div class="panel-heading" style="text-align:left;"><h5><b>Due Property Details	
	  <a href="" style="float:right;color:#000;">Back</a></b></h5></div>
	  <div class="panel-body">
		
			
			<form action="<?php echo base_url('Home/search_Property_List');?>" method="post" role="form" class="php-email-form">
			<div class="col-md-12" style="font-size:14px;">
				<div class="panel-heading" style="text-align:left;color:#3c96f3;"><h5><b>Basic Details	</b></div></br>
				<table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
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
										<?php echo $basic_details['holding_no']; ?>
										</td>
									</tr>
									<tr>
										<td>15 Digit House No.</td>
										<td><strong>:</strong></td>
										<td>
										 N/A
										</td>
										<td></td>
										<td></td>
										<td></td>
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
										<?php echo $basic_details['ownership_type']; ?>	</td>
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
										<td>Area Of Plot</td>
										<td><strong>:</strong></td>
										<td>
										 <?php echo $basic_details['area_of_plot']; ?>
										</td>
										<td>Rainwater Harvesting Provision</td>
										<td><strong>:</strong></td>
										<td>
										<?php echo $basic_details['is_water_harvesting']; ?>
										</td>
									</tr>
									<?php endif; ?>
									</tbody>
									</table>
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
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
      <th scope="col">Sl No.</th>
      <th scope="col">ARV</th>
      <th scope="col">Effect From</th>
      <th scope="col">Holding Tax</th>
	  <th scope="col">Water Tax</th>
      <th scope="col">Conservancy/Latrine Tax</th>
      <th scope="col">Education Cess</th>
      <th scope="col">Health Cess</th>
	  <th scope="col">Quarterly Tax</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
  <?php if($tax_list):
  $i=1; $qtr_tax=0; ?>
  <?php foreach($tax_list as $tax_list): 
  $qtr_tax=$tax_list['hold_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
  ?>
    <tr>
      <td><?php echo $i++; ?></td>
      <td><?php echo $tax_list['arv']; ?></td>
      <td><?php echo $tax_list['']; ?></td>
	  <td><?php echo $tax_list['hold_tax']; ?></td>
	  <td><?php echo $tax_list['water_tax']; ?></td>
      <td><?php echo $tax_list['latrine_tax']; ?></td>
      <td><?php echo $tax_list['education_cess']; ?></td>
	  <td><?php echo $tax_list['health_cess']; ?></td>
	  <td><?php echo $qtr_tax; ?></td>
      <td>Current</td>
    </tr>
	<?php endforeach; ?>
	<?php endif; ?>
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
										 	Quarter <?php echo $tot_demand['qtr']; ?> / Year <?php echo $first_fy; ?>
										</td>
										<td>Dues Upto</td>
										<td><strong>:</strong></td>
										<td>
											Quarter <?php echo $first_qtr; ?> / Year <?php echo $tot_demand['fy']; ?>		</td>
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
				<?php $j=1; ?>
	<?php foreach($demand_detail as $demand_detail): 
  
  ?>			
				
										<tr>
<td><?php echo $j++; ?></td>
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
					<a href="<?php echo base_url('Home/citizen_property_details/'.$id);?>" type="button" style="float:left;" class="button button1">View Property Details</a>
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