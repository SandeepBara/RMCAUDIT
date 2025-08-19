
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
					<button onclick="goBack()" class="btn btn-info">Go Back</button>
				</div>
				<h3 class="panel-title">Owner Basic Details</h3>
			</div>
			<?= $this->include('common/basic_details'); ?>
			<div class="panel-body">

				<div class="table-responsive">
					<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
						<thead class="bg-trans-dark text-dark">
							<tr>
							  <th scope="col">Owner Name</th>
							  <th scope="col">R/W Guardian</th>
							  <th scope="col">Guardian's Name</th>
							  <th scope="col">Mobile No</th>
							  <th scope="col">Pan</th>
							  <th scope="col">Aadhar</th>
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
								  <td><?php echo substr_replace($owner_details['mobile_no'], 'XXXXXX', 0, 6); ?></td>
								  <td><?php if($owner_details['pan_no']==''){ echo 'N/A'; }else{ echo substr_replace($owner_details['pan_no'], 'XXXXXX', 0, 6); }  ?></td>
								  <td><?php echo substr_replace($owner_details['aadhar_no'], 'XXXXXXXX', 0, 8); ?></td>
								  <td><?php echo $owner_details['gender']!=''?$owner_details['gender']:"N/A"; ?></td>
								  <td><?php echo $owner_details['dob']!=''?$owner_details['dob']:"N/A"; ?></td>
								  <td><?php echo $owner_details['is_specially_abled']=='f'?"No":"Yes"; ?></td>
								  <td><?php echo $owner_details['is_armed_force']=='f'?"No":"Yes"; ?></td>
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
						<?php if(isset($tax_list)):
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
            <?php
						$i=1;
						if (isset($payment_detail) && is_array($payment_detail)) {
							foreach($payment_detail as $payment_detail) {
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
							<td><a href="<?php echo base_url('CitizenProperty/citizen_payment_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
						</tr>
						<?php
							}
						} if (isset($saf_payment_detail) && is_array($saf_payment_detail)) {
							foreach($saf_payment_detail as $payment_detail) {
						?>
							<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $payment_detail['tran_no']; ?> <span class="label label-primary"> SAF Payment </span></td>
							<td><?php echo $payment_detail['transaction_mode']; ?></td>
							<td><?php echo $payment_detail['tran_date']; ?></td>
							<td><?php echo $payment_detail['from_qtr']; ?></td>
							<td><?php echo $payment_detail['fy']; ?></td>
							<td><?php echo $payment_detail['upto_qtr']; ?></td>
							<td><?php echo $payment_detail['upto_fy']; ?></td>
							<td><?php echo $payment_detail['payable_amt']; ?></td>
							<td><a href="<?php echo base_url('citizenPaymentReceipt/saf_payment_receipt/1/'.md5($payment_detail['id']));?>" target="_blank" class="btn btn-primary" style="color:white;">View</a></td>
						</tr>
						<?php
							}
						} else {
						?>
						<tr>
							<td colspan="10" style="text-align:center;color:red;"> Data Are Not Available !!!</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>

	<div class="panel">
		<div class="panel-body text-center">
			<a href="<?php echo base_url('CitizenProperty/Citizen_due_details/'.$id);?>" type="button" class="btn btn-primary btn-labeled">View Demand Details</a>
			<a href="<?php echo base_url('CitizenProperty/Citizen_property_details/'.$id);?>" type="button" class="btn btn-warning btn-labeled">View Property Details</a>

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
</script>
