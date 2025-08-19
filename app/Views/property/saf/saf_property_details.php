
<?= $this->include('layout_vertical/header');?>
<style>
	
.row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">SAF</a></li>
					<li><a href="<?=base_url();?>/safdtl/full/<?=$basic_details['saf_dtl_id'];?>">SAF Details</a></li>
					<li class="active">Demand Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

<!-- ======= Cta Section ======= -->

			<div id="page-content">
										<div class="panel panel-bordered panel-dark">
											
							
											<div class="panel-heading">
												<h3 class="panel-title">Basic Details</h3>
											</div>
											<?= $this->include('common/basic_details_saf'); ?>
															
										</div>
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Owner Details</h3>
											</div>
											<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="bg-trans-dark text-dark">
														<tr>
														<th scope="col">Owner Name</th>
														  <th scope="col">R/W Guardian</th>
														  <th scope="col">Guardian's Name</th>
														  <th scope="col">Mobile No</th>
														  <th scope="col">Aadhar</th>
														  <th scope="col">PAN</th>
														  <th scope="col">Gender</th>
														  <th scope="col">DOB</th>
														  <th scope="col">Is Specially Abled?</th>
														  <th scope="col">Is Armed Force?</th>
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
															  <td><?php echo $owner_details['aadhar_no']; ?></td>
															  <td><?php echo $owner_details['pan_no']; ?></td>
															  <td><?php echo $owner_details['gender']; ?></td>
															  <td><?php echo $owner_details['dob']; ?></td>
															  <td><?=  ($owner_details['is_specially_abled']=='f')?'No':'Yes'; ?></td>
															  <td><?=  ($owner_details['is_armed_force']=='f')?'No':'Yes'; ?></td>
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
											<div class="panel-body">
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
										</div>
										
									
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Due Detail List</h3>
											</div>
											<div class="panel-body">
												<div class="col-md-12">
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
													</div>
													<div class="table-responsive">	
														<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
															<thead class="bg-trans-dark text-dark">
																	<th>Sl No.</th>
																	<th>Quarter / Year</th>
																	<th>Holding Tax</th>
																	<th>RWH Penalty</th>
																	<th>Demand</th>
																	<th>Adjust Amount</th>
															</thead>
															<tbody>
																
																<?php if($demand_detail):
																
																foreach($demand_detail as $demand_detail): 
																	$total_demand=0;
																	$total_demand = $total_demand + $demand_detail['balance'];
																?>			
																<tr>
																	<td><?php echo $j++; ?></td>
																	<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fy']; ?></td>
																	<td><?php echo $demand_detail['total_tax']; ?></td>
																	<td><?php echo $demand_detail['additional_tax']; ?></td>
																	<td><?php if(isset($demand_detail['demand_amount'])){ echo $demand_detail['demand_amount']; } ?></td>
																	<td><?=$demand_detail['adjust_amt']??"0";?></td>
																	
																</tr>
																<?php endforeach; ?>
																<?php else: ?>
																<tr>
																	<td colspan="3" style="text-align:center; color:green;"> No Dues Are Available!!</td>
																</tr>
																<?php endif ?>
																
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										<?php
										if (in_array($emp_details['user_type_mstr_id'], [1, 2, 4,8])) {
										?>
										<div class="panel">
											<div class="panel-body text-center">
												
												<?php 
												if($basic_details['payment_status']==0)
												{
													?>
													<a href="<?=base_url('safDemandPayment/safPaymentProceed/'.$id);?>" class="btn btn-primary btn-labeled">Proceed Payment</a>
													<?php 
												}
												?>
												
											</div>
										</div>
										<?php } ?>
								
			</div>
					
			
			
  
<?= $this->include('layout_vertical/footer');?>