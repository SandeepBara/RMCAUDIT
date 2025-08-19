
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
												<h3 class="panel-title">Owner Basic Details</h3>
											</div>
											<div class="panel-body">
												<?php if($basic_details): ?>
												<div class="row">
													<div class="col-sm-3">
														<b>Ward No. :</b>
													</div>
													
													<div class="col-sm-3">
														<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
													</div>
												</div>	
												<div class="row">
													<div class="col-sm-3">
														<b>Application No. :</b>
													</div>
													
													<div class="col-sm-3">
														<?=$basic_details['saf_no']?$basic_details['saf_no']:"N/A"; ?>
													</div>
												</div><br>
												<?php endif; ?>
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
										</div>
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Tax Details</h3>
											</div>
											<div class="table-responsive">
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="bg-trans-dark text-dark">
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
													</thead>
													<tbody>
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
															<th scope="col"> View</th>
													</thead>
													<tbody>
														<?php if(isset($payment_detail)):
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
															<td><?php echo $payment_detail['upto_fy']; ?></td>
															<td><?php echo $payment_detail['payable_amt']; ?></td>
															<td><a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_payment_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
														</tr>
														<?php endforeach; ?>
														<?php else: ?>
														<tr>
															<td colspan="10" style="text-align:center;color:red;"> Data Not Available...</td>
														</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									
									<div class="panel">
										<div class="panel-body text-center" style="height:70px;">
											<a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_due_details/'.$id);?>" type="button" class="btn btn-primary btn-labeled"><i class="fa fa-eye" aria-hidden="true"></i>View Demand</a>
											<a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_property_details/'.$id);?>" type="button" class="btn btn-warning btn-labeled"><i class="fa fa-eye" aria-hidden="true"></i>View Property</a>
											
										</div>
									</div>
								
							
			</div>
			
			
  
<?= $this->include('layout_home/footer');?>
