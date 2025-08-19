
<?= $this->include('layout_vertical/header');?>
<style>
 .row{line-height:25px;}
</style>
            <div id="content-container">

				<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">
								Application Details  
								<?=($user_type_mstr_id=="1")?" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;govt_saf_dtl_id:  $appbasic_details[id]":null;?>
							</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-2">
									<b>Ward No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['ward_no']?$appbasic_details['ward_no']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Apply Date</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['apply_date']?$appbasic_details['apply_date']:"N/A"; ?>
								</div>

								<div class="col-md-2">
									<b>New Ward No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['new_ward_no']?$appbasic_details['new_ward_no']:"N/A"; ?>
								</div>

							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Application No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['application_no']?$appbasic_details['application_no']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Application Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['application_type']?$appbasic_details['application_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Officer Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['officer_name']?$appbasic_details['officer_name']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Mobile No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['mobile_no']?$appbasic_details['mobile_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Assessment Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['assessment_type']?$appbasic_details['assessment_type']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Holding No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$appbasic_details['holding_no']?$appbasic_details['holding_no']:"N/A"; ?>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3">
									<b> Office Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['office_name']?$appbasic_details['office_name']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Property Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['property_type']?$appbasic_details['property_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Construction Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['construction_type']?$appbasic_details['construction_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Road Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['road_type']?$appbasic_details['road_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Colony Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['colony_name']?$appbasic_details['colony_name']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Colony Address</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['colony_address']?$appbasic_details['colony_address']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Ownership Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['ownership_type']?$appbasic_details['ownership_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Water Harvesting</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['is_water_harvesting']=="t"?"Yes":"No"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Mobile Tower</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['is_mobile_tower']=="t"?"Yes"."  /  "."Area"." ".":"." ".$appbasic_details['tower_area']."  /  "."Installation Date"." ".":"." ".$appbasic_details['tower_installation_date']:"No"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Hoarding Board</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['is_hoarding_board']=="t"?"Yes"."  /  "."Area"." ".":"." ".$appbasic_details['hoarding_area']."  /  "."Installation Date"." ".":"." ".$appbasic_details['hoarding_installation_date']:"No"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Petrol Pump</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$appbasic_details['is_petrol_pump']=="t"?"Yes"."  /  "."Area"." ".":"." ".$appbasic_details['under_ground_area']."  /  "."Installation Date"." ".":"." ".$appbasic_details['petrol_pump_completion_date']:"No"; ?>
								</div>
							</div>
						</div>
					</div>
						
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Authorized Person for the Payment of Property Tax</h3>
						</div>

						<div class="panel-body">
						<div class="row">
							<div class="col-md-3">
									<b>Officer Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$paybasic_details['officer_name']?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Mobile No</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$paybasic_details['mobile_no'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Email Id</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$paybasic_details['email_id'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Designation</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$paybasic_details['designation'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Address</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$paybasic_details['address'];?>
								</div>
							</div>
						</div>
					</div>
						
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Floor Details</h3>
						</div>

						<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
									  <th scope="col">Sl No.</th>
									  <th scope="col">Floor Name</th>
									  <th scope="col">Usage Type</th>
									  <th scope="col">Construction Type</th>
									  <th scope="col">Occupancy Name</th>
									  <th scope="col">Builtup Area</th>
									  <th scope="col">Carpet Area</th>
									  <th scope="col">Date From</th>
									  <th scope="col">Date Upto</th>
									</tr>
								</thead>
								<tbody>
								<?php if($floor_detail):
									$i=1;
									foreach($floor_detail as $floor_detail): 
								?>
									<tr>
									  <td><?php echo $i++; ?></td>
									  <td><?=$floor_detail['floor_name']?$floor_detail['floor_name']:"N/A"; ?></td>
									  <td><?=$floor_detail['usage_type']?$floor_detail['usage_type']:"N/A"; ?></td>
									  <td><?=$floor_detail['construction_type']?$floor_detail['construction_type']:"N/A"; ?></td>
									  <td><?=$floor_detail['occupancy_name']?$floor_detail['occupancy_name']:"N/A"; ?></td>
									  <td><?=$floor_detail['builtup_area']?$floor_detail['builtup_area']:"N/A"; ?></td>
									  <td><?=$floor_detail['carpet_area']?$floor_detail['carpet_area']:"N/A"; ?></td>
									  <td><?=$floor_detail['date_from']?$floor_detail['date_from']:"N/A"; ?></td>
									  <td><?=$floor_detail['date_upto']?$floor_detail['date_upto']:"N/A"; ?></td>
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
						</div>
					</div>
					
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Tax ARV Details</h3>
						</div>
						<div class="panel-body">
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
									$i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
								<?php foreach($tax_list as $tax_list): 
									$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
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
								<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="11" style="text-align:center;color:red;"> Data Are Not Available!!</td>
									</tr>
								<?php endif; ?>
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
									<th scope="col"> Sl No.</th>
									<th scope="col"> Transaction No</th>
									<th scope="col"> Payment Mode </th>
									<th scope="col"> Date</th>
									<th scope="col"> Payment From</th>
									<th scope="col"> Payment Upto</th>
									<th scope="col"> Amount</th>
									<th scope="col"> Status</th>
									<th scope="col"> View</th>
								</thead>
								<tbody>
									<?php if(isset($pymnt_detail)):
									$i=1;
									?>
									<?php foreach($pymnt_detail as $payment_detail): 
									?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><b><?php echo $payment_detail['tran_no']; ?></b></td>
										<td><?php echo $payment_detail['tran_mode']; ?></td>
										<td><?php echo $payment_detail['tran_date']; ?></td>
										<td><?php echo $payment_detail['from_qtr']." / ".$payment_detail['from_fyear']; ?></td>
										<td><?php echo $payment_detail['upto_qtr']." / ".$payment_detail['upto_fyear']; ?></td>
										<td><?php echo $payment_detail['payable_amt']; ?></td>
										<td><?php
												if($payment_detail['status']==1){
													echo '<span class="text-success"> Payment Done </span>';
												}
												elseif($payment_detail['status']==2){
													echo '<span class="text-warning"> Payment not clear </span>';
												}
												elseif($payment_detail['status']==3){
													echo '<span class="text-danger"> Cheque/DD Bounce </span>';
												}
											?>
										</td>
										<td><a onclick="PopupCenter('<?=base_url('govsafDetailPayment/govsaf_payment_receipt2/'.md5($payment_detail['id']));?>', 'Payment Receipt', 1024, 786)" href="#" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="9" style="text-align:center;color:red;"> Data Not Available...</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Memo Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead class="bg-trans-dark text-dark">
												<tr>
													<th>#</th>
													<th>Memo No</th>
													<th>Generated On</th>
													<th>ARV</th>
													<th>Quarterly Tax</th>
													<th>Effect From</th>
													<th>Memo Type</th>
													<th>View</th>
												</tr>
											</thead>
											<?php
											$i=0;
											if($Memo)
											foreach($Memo as $row)
											{
												?>
												<tr>
													<td><?=++$i;?></td>
													<td><?=$row['memo_no'];?></td>
													<td><?=date("Y-m-d", strtotime($row['created_on']));?></td>
													<td><?=$row['arv'];?></td>
													<td><?=$row['quarterly_tax'];?></td>
													<td><?=$row['effect_quarter'];?>/<?=$row['fy'];?></td>
													<td class="text-left"><?=$row['memo_type'];?></td>
													<td><a href="#" class="btn btn-primary" onclick="window.open('<?=base_url();?>/citizenPaymentReceipt/da_eng_memo_receipt/<?=md5($ulb['ulb_mstr_id']);?>/<?=md5($row['id']);?>', 'newwindow', 'width=1000, height=1000'); return false;">View</a></td>
												</tr>
												<?php
											}
											else
											{
												?>
												<tr>
													<td colspan="8" style="text-align:center;color:red;">Data Are Not Available!!</td>
												</tr>
												<?php
											}
											?>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="panel">
						<div class="panel-body text-center">

							<a href="<?php echo base_url('govsafDetailPayment/gov_saf_due_details/'.$id);?>" class="btn btn-primary btn-labeled">View Demand</a>
							<?php if ($appbasic_details["colony_mstr_id"]!="") { ?>
								<a href="<?php echo base_url('GovtCitizenProperty/colonycomparativeTax/'.$appbasic_details['id']);?>" class="btn btn-primary btn-labeled">Comparative Demand</a>
							<?php } else { ?>
								<a href="<?php echo base_url('GovtCitizenProperty/comparativeTax/'.$appbasic_details['id']);?>" class="btn btn-primary btn-labeled">Comparative Demand</a>
							<?php } ?>
							<a href="<?=base_url('govsafDetailPayment/gbSafNoticeGenerate/'.$appbasic_details['id'])?>" class="btn btn-primary btn-labeled">Generate Notice</a>
							<?php
							// 1	Super Admin
							// 4	Team Leader
							// 8	Jsk

							if(in_array($user_type_mstr_id, [1, 4, 8]))
							{
							?>
								<a href="<?php echo base_url('GsafDocUpload/docUpload/'.$id);?>" class="btn btn-primary btn-labeled"> Document Upload </a>
							<?php
							}
							?>
							
							
						</div>
					</div>
				</div>
			</div>
			
  
<?= $this->include('layout_vertical/footer');?>