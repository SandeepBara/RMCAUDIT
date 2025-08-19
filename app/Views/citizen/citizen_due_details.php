
<?= $this->include('layout_home/header');?>
 <!--CONTENT CONTAINER-->
 <style>
.row{line-height:25px;}
</style>
<div id="content-container" style="padding: 20px 0px;">
    <!--Page content-->
    <div id="page-content">
	
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">
					<?php if(isset($tccallexit['status'])) { if($tccallexit['status']=='1'){ ?>
						<b style="color:#f13810;"> You are requested TC for payment collection. </b>
					<?php }  else if($tccallexit['status']==2){ ?>
						<b style="color:#38fb15;"> TC accepted your request for payment collection. </b>
					<?php } }?>
					<button onclick="goBack()" class="btn btn-info">Go Back</button>
					
				</div>
				<h3 class="panel-title">Basic Details</h3>
				
			</div>
			<?= $this->include('common/basic_details'); ?>
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
							<th scope="col">PAN</th>
							<th scope="col">Aadhar</th>
							<th scope="col">Gender</th>
							  <th scope="col">DOB</th>
							  <th scope="col">Is Specially Abled?</th>
							  <th scope="col">Is Armed Force?</th>
					</thead>
					<tbody>
					<?php if($owner_details): ?>
						<?php foreach($owner_details as $owner_details): ?>
						<tr>
						  <td><?php echo $owner_details['owner_name']; ?></td>
						  <td><?php echo $owner_details['relation_type']; ?></td>
						  <td><?php echo $owner_details['guardian_name']; ?></td>
						  <td><?php echo substr_replace($owner_details['mobile_no'], 'XXXXXX', 0, 6); ?></td>
						  <td><?php if($owner_details['pan_no']=='' || $owner_details['pan_no']==null){
							  echo "N/A";
						  }else{ echo substr_replace($owner_details['pan_no'], 'XXXXXX', 0, 6); }  ?></td>
						  <td><?php echo substr_replace($owner_details['aadhar_no'], 'XXXXXXXX', 0, 8); ?></td>
						  <td><?php echo $owner_details['gender']!=''?$owner_details['gender']:"N/A"; ?></td>
								  <td><?php echo $owner_details['dob']!=''?$owner_details['dob']:"N/A"; ?></td>
								  <td><?php echo $owner_details['is_specially_abled']=='f'?"No":"Yes"; ?></td>
								  <td><?php echo $owner_details['is_armed_force']=='f'?"No":"Yes"; ?></td>
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
				<h3 class="panel-title">Tax Details</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
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
					<?php if(isset($tax_list)):
						$i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
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
				<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
				<?php if(isset($demand_detail)): ?>
					<thead class="bg-trans-dark text-dark">
					<th>Sl No.</th>
						<th>Quarter/Year</th>
						<th>Quarterly Tax</th>
						<th>RWH Penalty</th>
						<th>Demand Amount</th>
					</thead>
					<tbody>
						<?php 
						$j=1;
						foreach($demand_detail as $demand): 
							$tax = $demand["holding_tax"] + $demand["water_tax"] + $demand["education_cess"] + $demand["health_cess"] + $demand["latrine_tax"];
						?>			
						<tr>
						<td><?= ++$j; ?></td>
									<td><?= $demand['qtr']; ?> / <?= $demand['fy']; ?></td>
									<td><?= $tax; ?></td>
									<td><?= $demand['additional_amount']; ?></td>
									<td><?= $demand['balance']; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php else: ?>
						<tr>
							<td colspan="3" style="text-align:center;color:green;"> No Dues Are Available!!</td>
						</tr>
						
						
					</tbody>
					<?php endif ?>
				</table>
			</div>
		</div>
		
		

		<div class="panel">
			<div class="panel-body text-center">
				<a href="<?php echo base_url('CitizenProperty/Citizen_property_details/'.$id);?>" type="button" class="btn btn-primary btn-labeled">View Property Details</a>
				<a href="<?php echo base_url('CitizenProperty/Citizen_payment_details/'.$id);?>" type="button" class="btn btn-warning btn-labeled">View Payment Details</a>
				<?php //if(isset($tccallexit)){  if($tccallexit==""){ ?>
					<?php if(isset($demand_detail)){ ?>
						<a href="<?php echo base_url('CitizenProperty/Citizen_confirm_payment/'.$id);?>" type="button" class="btn btn-info btn-labeled">Pay Property Tax</a>
						<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#shedule_tc">Shedule TC For Payment Collection</button>
				<?php }  ?>
				<!-- <a href="<?php echo base_url('CitizenPropSpecialDocUpload/CitizenPropSpecialDocUpload/'.$id);?>" type="button" class="btn btn-info btn-labeled">Consession Details Update</a> -->
				
			</div>
		</div>
	
    </div>
	
	<div id="shedule_tc" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color: #83d8b9b8;">
					<button type="button" class="close"  style="color: #ce040d;font-size:30px;" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="color: #ce040d;">Select date & time</h4>
				</div>
				<form action="<?php echo base_url('CitizenProperty/citizen_tc_call/'.$id);?>" method="post">
					<div class="modal-body">
						<input type="hidden" class="form-control" id="ward_no" name="ward_no" value="<?= isset($basic_details["ward_mstr_id"])?$basic_details["ward_mstr_id"]:''; ?>">
						<input type="hidden" class="form-control" id="holding_no" name="holding_no" value="<?php echo $basic_details["holding_no"]; ?>">
						<input type="hidden" class="form-control" id="new_holding_no" name="new_holding_no" value="<?php echo $basic_details["new_holding_no"]; ?>">
						<input type="hidden" class="form-control" id="address" name="address" value="<?php echo $basic_details["prop_address"]; ?>">
						<input type="hidden" class="form-control" id="owner_name" name="owner_name" value="<?php echo $owner_details['owner_name']; ?>">
						<input type="hidden" class="form-control" id="mobile_no" name="mobile_no" value="<?php echo $owner_details["mobile_no"]; ?>">
						<input type="hidden" class="form-control" id="type" name="type" value="Property">
						<div class="row">
							<label class="col-md-4 text-bold">Select Date</label>
							<div class="col-md-6 has-success pad-btm">
								<input type="date" id="shedule_date" name="shedule_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="" />
							</div>
						</div>
						<div class="row">
							<label class="col-md-4 text-bold">Select Time Slot</label>
							<div class="col-md-4 has-success pad-btm">
								<input type="radio" id="timeslot1" name="time" class="magic-radio" value="08:00 AM to 10:00 AM">
								<label for="timeslot1">08:00 AM to 10:00 AM</label> 
							</div>
							<div class="col-md-4 has-success pad-btm">
								<input type="radio" id="timeslot2" name="time" class="magic-radio" value="10:00 AM to 12:00 PM">
								<label for="timeslot2">10:00 AM to 12:00 PM</label> 
							</div>
							<div class="col-md-4">
							</div>
							<div class="col-md-4 has-success pad-btm">
								<input type="radio" id="timeslot3" name="time" class="magic-radio" value="12:00 PM to 02:00 PM">
								<label for="timeslot3">12:00 PM to 02:00 PM</label> 
							</div>
							<div class="col-md-4 has-success pad-btm">
								<input type="radio" id="timeslot4" name="time" class="magic-radio" value="02:00 PM to 04:00 PM">
								<label for="timeslot4">02:00 PM to 04:00 PM</label> 
							</div>
							   <!-- <input type="date" id="from_date" name="from_date" class="form-control" value="<?=isset($from_date)?$from_date:'';?>" />
							</div>-->
						</div>
						<button type="submit" class="btn btn-primary btn-labeled" style="text-align:center;" id="schedule" name="schedule">Shedule</button>
					</div>
					
				</form>
			</div>
		</div>
	</div>	
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>

<script>
	function goBack() {
	  window.history.back();
	}

	function fetchWaterDuesDetail()
	{

	}
</script>