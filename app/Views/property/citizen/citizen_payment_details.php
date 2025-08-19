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
	  <div class="panel-heading" style="text-align:left;"><h5><b>Payment Details 	
	  <a href="" style="float:right;color:#000;">Back</a></b></h5></div>
	  <div class="panel-body">
		
			
			<form action="<?php echo base_url('Home/search_Property_List');?>" method="post" role="form" class="php-email-form">
			<div class="col-md-12" style="font-size:14px;">
				
				
	
  <table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
  <thead class="thead-light">
  <tr>
						<th colspan="10" style="background-color:#843139;color:white;">Owner Basic Details</th>
						</tr>
  </thead>
									<tbody>
									<?php if($basic_details): ?>
									<tr>
										<td>Ward No.</td>
										<td><strong>:</strong></td>
										<td><?php echo $basic_details['ward_no']; ?>
										</td>
									<tr>
										<td>Holding No.</td>
										<td><strong>:</strong></td>
										<td><?php echo $basic_details['holding_no']; ?>
										</td>
									</tr>
									<?php endif; ?>
									<?php if($owner_details): ?>
									<tr>
										<td>Owner Name</td>
										<td><strong>:</strong></td>
										<td><?php echo $owner_details['owner_name']; ?>
										</td>
									<tr>
										<td>R/W Guardian</td>
										<td><strong>:</strong></td>
										<td><?php echo $owner_details['relation_type']; ?>
										</td>
										
									</tr>
									<tr>
										<td>Guardian's Name</td>
										<td><strong>:</strong></td>
										<td><?php echo $owner_details['guardian_name']; ?>
										</td>
									<tr>
										<td>Mobile No</td>
										<td><strong>:</strong></td>
										<td><?php echo $owner_details['mobile_no']; ?>
										</td>
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
  
  
  	
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  <tr>
						<th colspan="10" style="background-color:#843139;color:white;">Payment Details</th>
						</tr>
  
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
  
			</div><br><br>
			<table class="table">
			<tbody>
			<tr>
			<td></td><td>
				<a href="<?php echo base_url('Home/citizen_due_details/'.$id);?>" type="button" style="float:right;" class="button button1">View Demand Details</a>
			<a href="<?php echo base_url('Home/citizen_property_details/'.$id);?>" type="button" class="button button2">View Property Details</a></td>
			<td></td>
			</tbody>
			</table>
			
							</form>
				
					</div><br><br>
					
					
			
	  </div>
	</div>
	</div>

      </div>
    </section><!-- End Contact Section -->
  
<?= $this->include('layout_horizontal/footer');?>
