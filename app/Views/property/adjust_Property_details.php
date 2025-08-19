
<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Due Property Details</li>
                    </ol>
                </div>

				<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<button onclick="goBack()" class="btn btn-info">Go Back</button>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-3">
									<b>Ward No. </b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-3">
									<b>Holding No. </b>
								</div>
								<div class="col-sm-3">
									<?php echo $basic_details['holding_no']?$basic_details['holding_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Property Type </b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
								</div>
								
								<div class="col-md-3">
									<b>Ownership Type </b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Address </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['prop_address']?$basic_details['prop_address']:"N/A"; ?>
								</div>
								<div class="col-md-3">
									<b>15 Digit Unique House No. </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['new_holding_no']?$basic_details['new_holding_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Area Of Plot </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['area_of_plot']?$basic_details['area_of_plot']:"N/A"; ?>( In decimal)
								</div>
								
								<div class="col-md-3">
									<b>Rainwater Harvesting Provision </b>
								</div>
								<div class="col-md-3">
									<?php if($basic_details['is_water_harvesting']=='t'){ ?>
									YES
									<?php } else if($basic_details['is_water_harvesting']=='f'){ ?>
									No
									<?php } else { ?>
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
								<?php if($tax_list):
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
							<h3 class="panel-title">Payment Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
										<th> Sl No.</th>
										<th> Transaction No</th>
										<th> Payment Mode</th>
										<th> Date</th>
										<th> From Quarter</th>
										<th> From Year</th>
										<th> Upto Quarter</th>
										<th> Upto Year</th>
										<th> Amount</th>
										<th colspan="2"> Action</th>
								</thead>
								<tbody>
									<?php if($payment_detail):
									$i=1;
									?>
									<?php foreach($payment_detail as $payment_detail): 
									?>
									<tr>
										<td><?=$i++; ?></td>
										<td><?=$payment_detail['tran_no']; ?>  <span class="label label-primary"> <?=$payment_detail['tran_type']; ?></span></td>
										<td><?=$payment_detail['transaction_mode']; ?></td>
										<td><?=$payment_detail['tran_date']; ?></td>
										<td><?=$payment_detail['from_qtr']; ?></td>
										<td><?=$payment_detail['fy']; ?></td>
										<td><?=$payment_detail['upto_qtr']; ?></td>
										<td><?=$payment_detail['fy']; ?></td>
										<td><?=$payment_detail['payable_amt']; ?></td>
										<td><a href="<?=base_url('jsk/payment_jsk_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
										<td><a href="<?=base_url('amount_adjustment/transaction_adjust/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">Add To Adjustment</a></td>
										
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
									<?php if($payment_detail_saf):
									?>
									<?php foreach($payment_detail_saf as $payment_detail_saf): 
									?>
									<tr>
										<td><?=$i++; ?></td>
										<td><?=$payment_detail_saf['tran_no']; ?>  <span class="label label-primary"> <?=$payment_detail_saf['tran_type']; ?></span></td>
										<td><?=$payment_detail_saf['transaction_mode']; ?></td>
										<td><?=$payment_detail_saf['tran_date']; ?></td>
										<td><?=$payment_detail_saf['from_qtr']; ?></td>
										<td><?=$payment_detail_saf['fy']; ?></td>
										<td><?=$payment_detail_saf['upto_qtr']; ?></td>
										<td><?=$payment_detail_saf['fy']; ?></td>
										<td><?=$payment_detail_saf['payable_amt']; ?></td>
										<td><a href="<?=base_url('safDemandPayment/saf_payment_receipt/'.md5($payment_detail_saf['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
										<td><a href="<?=base_url('amount_adjustment/transaction_adjust/'.md5($payment_detail_saf['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">Add To Adjustment</a></td>
									</tr>
									<?php endforeach; ?>
									<?php else:?>
									<tr>
										<td colspan="10" style="text-align:center;color:red;"> Data Are Not Available !!!</td>
									</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Adjustment Amount</h3>
						</div>
						<div class="panel-body">
							<div class="panel">
								<div class="panel-body text-center">
									<?php if($rest_amnt>0){ ?>
										<b style="color:red;">Advance Amount : <?=$rest_amnt; ?></b>
									<?php } else { ?>
										<b style="color:red;">There is no adjustment amount</b>
									<?php } ?>
								</div>
							</div>
							<div class="panel panel-bordered">
								<div class="panel-heading">
									<h3 class="panel-title">New Adjustment</h3>
								</div>
								<div class="panel-body">
									<form action="<?php echo base_url('amount_adjustment/amount_adjust/'.md5($basic_details['prop_dtl_id']));?>" method="post" role="form" enctype="multipart/form-data">
										<input type="hidden" id="prop_id" name="prop_id" class="form-control" value="<?=$basic_details['prop_dtl_id'];?>">
										<div class="row">
											<div class="col-md-3">
												<label for="keyword">
													Amount
												</label>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" id="amount" name="amount" class="form-control" placeholder="Enter Amount" value="">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-3">
												<label for="keyword">
													Reason
												</label>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select name="reason" id="reason" required="" class="form-control">
														<option value="">Select Reason</option>
														<option value="LAST PAYMENT WAS NOT UP TO DATE">LAST PAYMENT WAS NOT UP TO DATE</option>
														<option value="DUE TO RE-ASSESSMENT">DUE TO RE-ASSESSMENT</option>
														<option value="ARV &amp; TAXES CHANGED AFTER NAME TRANSFER">ARV &amp; TAXES CHANGED AFTER NAME TRANSFER</option>
														<option value="CLERICAL ERROR IN TAX DETAILS">CLERICAL ERROR IN TAX DETAILS</option>
														<option value="INTEREST NOT RECOVERED">INTEREST NOT RECOVERED</option>
														<option value="ACCIDENTLY COLLECTION">ACCIDENTLY COLLECTION</option>
														<option value="ONLINE PAYMENT FAILURE">ONLINE PAYMENT FAILURE</option>
														<option value="PAYMENT IN DUPLICATE HOLDING">PAYMENT IN DUPLICATE HOLDING</option>
														<option value="EXTRA PAID IN SAF">EXTRA PAID IN SAF</option>
														<option value="ADDITIONAL AMOUNT PAID IN WATERHARVESTING">ADDITIONAL AMOUNT PAID IN WATERHARVESTING</option>
														<option value="TAX COLLECTED BY THE NIGAM TAX COLLECTOR WHILE COLLECTION WAS STOPPED">TAX COLLECTED BY THE NIGAM TAX COLLECTOR WHILE COLLECTION WAS STOPPED</option>
														<option value="PENALTY COLLECTED IN SAF">PENALTY COLLECTED IN SAF</option>
														<option value="INCORRECT ASSESSMENT">INCORRECT ASSESSMENT</option>
														<option value="ADJUSTMENT AMOUNT WAS NOT BE ADJUSTED IN THE TRANSACTION">ADJUSTMENT AMOUNT WAS NOT BE ADJUSTED IN THE TRANSACTION</option>
														<option value="EXTRA AMOUNT PAID IN GBSAF">EXTRA AMOUNT PAID IN GBSAF</option>
														<option value="LESS AMOUNT PAID IN GBSAF">LESS AMOUNT PAID IN GBSAF</option>
														<option value="WHEN PAYABLE AMOUNT HAD NOT EQUAL TO PAID AMOUNT, THE DIFFERENCE AMOUNT HAS ENTERED FOR ADJUSTMENT">WHEN PAYABLE AMOUNT HAD NOT EQUAL TO PAID AMOUNT, THE DIFFERENCE AMOUNT HAS ENTERED FOR ADJUSTMENT</option>
														<option value="ACCESS AREA ADDED IN SAF">ACCESS AREA ADDED IN SAF</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-3">
												<label for="keyword">
													Remarks
												</label>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<textarea id="remarks" name="remarks" class="form-control" value=""></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-3">
												<label for="bill_doc_path">Upload Related Documents<span class="text-danger">*</span></label>
												<br>
												<span class="text-danger">( Preferred pdf : Maximum size of 2MB )</span>
											</div>
											<div class="col-md-6">
												<input type="file" class="form-control" id="bill_doc_path" name="bill_doc_path" accept=".pdf" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 col-md-offset-4 pad-btm">
												<button type="submit" id="search" name="search" class="col-md-12 btn btn-primary" value="search">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>
		
	
<?= $this->include('layout_vertical/footer');?>
	<script>
		function goBack() {
		  window.history.back();
		}
	</script>
	<script>
		function modelInfo(msg){
			$.niftyNoty({
				type: 'info',
				icon : 'pli-exclamation icon-2x',
				message : msg,
				container : 'floating',
				timer : 5000
			});
		}
		<?php 
			if($holding=flashToast('holding'))
			{
				echo "modelInfo('".$holding."');";
			}
		?>
	</script>