<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->

<style>
	.row {
		line-height: 25px;
	}
</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
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
			<li><a href="#">Trade</a></li>
			<li class="active">Trade SH List</li>
		</ol>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<!--===================================================-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading" style="background-color:#39a9b0;">
				<div class="panel-control">
					<a href="<?php echo base_url('Trade_SH/index') ?>" class="btn btn-default">Back</a>
				</div>
				<h3 class="panel-title">Detail of Applied Form For Municipal License for <b style="color:#ffff00;font-size: 20px;">Application No. : <?= $basic_details['application_no'] ? $basic_details['application_no'] : "N/A"; ?></b></h3>
			</div>
			<div class="panel-body">
				<form method="post" class="form-horizontal" action="">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<a href="<?php echo base_url('Trade_SH/index') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<input type="hidden" name="apply_licence_id" value="<?= md5($basic_details['id']); ?>" />
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-4">
									<?= $ward['ward_no'] ? $ward['ward_no'] : "N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Holding No. :</b>
								</div>
								<div class="col-sm-3">
								<?= $holding['holding_no'] ? (isset($PropSafLink) ? '<u><i> <a class="bg-light text-info  border-none "target="_blank" href="'.$PropSafLink.'">'.$holding['holding_no'].'</a></i></u>' : $holding['holding_no']) : "N/A"; ?>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Application No. :</b>
								</div>
								<div class="col-sm-4">
									<?= $basic_details['application_no'] ? $basic_details['application_no'] : "N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Application Type :</b>
								</div>
								<div class="col-sm-3">
									<?= $basic_details['application_type'] ? $basic_details['application_type'] : "N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Licence For :</b>
								</div>
								<div class="col-sm-4">
									<?= $holding['licence_for_years'] ? $holding['licence_for_years'] . "  Years" : "N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Firm Type :</b>
								</div>
								<div class="col-sm-3">
									<?= $basic_details['firm_type'] ? $basic_details['firm_type'] : "N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Ownership Type :</b>
								</div>
								<div class="col-sm-4">
									<?= $basic_details['ownership_type'] ? $basic_details['ownership_type'] : "N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Firm Name :</b>
								</div>
								<div class="col-sm-3">
									<?= $basic_details['firm_name'] ? $basic_details['firm_name'] : "N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Nature Of Business :</b>
								</div>
								<div class="col-sm-4">
									<?= $nature_business['trade_item'] ? $nature_business['trade_item'] : "N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Apply Date :</b>
								</div>
								<div class="col-sm-3">
									<?= $holding['apply_date'] ? $holding['apply_date'] : "N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>K No :</b>
								</div>
								<div class="col-sm-4">
									<?= $basic_details['k_no'] ? $basic_details['k_no'] : "N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Area :</b>
								</div>
								<div class="col-sm-3">
									<?= $basic_details['area_in_sqft'] ? $basic_details['area_in_sqft'] : "N/A"; ?> Sq. ft
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Account No :</b>
								</div>
								<div class="col-sm-4">
									<?= $basic_details['account_no'] ? $basic_details['account_no'] : "N/A"; ?>
								</div>


								<div class="col-sm-3 text text-danger">
									<b> New Area :</b>
								</div>
								<div class="col-sm-3 text text-danger">
									<?= $taxDarogaVerification['area_in_sqft']; ?> Sq. ft
								</div>


							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Address :</b>
								</div>
								<div class="col-sm-4">
									<?= $holding['address'] ? $holding['address'] : "N/A"; ?>
								</div>

								<div class="col-sm-3">
									<b>Firm Establishment Date :</b>
								</div>
								<div class="col-sm-3">
									<?= $holding['establishment_date'] ? $holding['establishment_date'] : "N/A"; ?>
								</div>


							</div>

							<div class="row">
								<div class="col-sm-2">
									<b>Landmark :</b>
								</div>
								<div class="col-sm-3">
									<?= $holding['landmark'] ? $holding['landmark'] : "N/A"; ?>
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
								<thead class="thead-light" style="background-color: #e6e6e4;">
									<tr>
										<th scope="col">Owner Name</th>
										<th scope="col">Guardian Name</th>
										<th scope="col">Mobile No</th>
										<th scope="col">Email Id</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if ($owner_details == "") {
									?>
										<tr>
											<td style="text-align:center;"> Data Not Available...</td>
										</tr>
										<?php
									} else {
										foreach ($owner_details as $owner_details) {
										?>
											<tr>
												<td><?= $owner_details['owner_name'] ? $owner_details['owner_name'] : "N/A"; ?></td>
												<td><?= $owner_details['guardian_name'] ? $owner_details['guardian_name'] : "N/A"; ?></td>
												<td><?= $owner_details['mobile'] ? $owner_details['mobile'] : "N/A"; ?></td>
												<td><?= $owner_details['emailid'] ? $owner_details['emailid'] : "N/A"; ?></td>
											</tr>
									<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Documents</h3>
						</div>
						<div class="panel-body" style="padding-bottom: 0px;">
							<div class="table-responsive">
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="thead-light" style="background-color: #e6e6e4;">
										<tr>
											<th>#</th>
											<th>Document Name</th>
											<th>Document</th>
											<th>Status</th>
											<!-- <th>Remarks</th> -->
										</tr>
									</thead>
									<tbody>
										<?php
										$cnt = 0;
										foreach ($documents as $doc) 
										{
											?>
												<tr>
													<td><?= ++$cnt; ?></td>
													<td><?= $doc["doc_for"]; ?></td>
													<td><a href="<?= base_url(); ?>/getImageLink.php?path=<?= $doc['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></td>
													<td><?php
														if ($doc["verify_status"] == 0) {
														?>
															<span class="text text-danger"> No Action Taken </span>
														<?php
														}
														if ($doc["verify_status"] == 1) {
														?>
															<span class="text text-success"> Verified </span>
														<?php
														}
														if ($doc["verify_status"] == 2) {
														?>
															<span class="text text-danger"> Rejected </span>
														<?php
														}

														?>
													</td>
													<!-- <th>Remarks</th> -->
												</tr>
											<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<!------------>


					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Payment Detail</h3>
						</div>
						<div class="panel-body">
							<?php
							if(empty($payment_dtls))
							{ 
								?>
									<div class="row">
										<div class="col-sm-8 col-sm-offset-2">
											<h4><b style="color:red;">No Payments Are Available...</b></h4>
										</div>
									</div>
								<?php 
							} 
							else 
							{
								?>
								<table class="table table-responsive">
									<thead>
										<tr>
											<th>Processing Fee :</th>
											<th>Transaction Date :</th>
											<th>Payment Through :</th>
											<th>Payment For :</th>
											<th>View</th>
										</tr>
									</thead>
									<tbody>
									<?php
										foreach($payment_dtls as $val)
										{
											?>
												<tr>
													<td><?=$val['paid_amount'] ? $val['paid_amount'] : "N/A";?></td>
													<td><?=$val['transaction_date'] ? $val['transaction_date'] : "N/A";?></td>
													<td><?=$val['payment_mode'] ? $val['payment_mode'] : "N/A";?></td>
													<td><?=$val['transaction_type'] ? $val['transaction_type'] : "N/A";?></td>
													<td>
														<a onClick="myPopup('<?= base_url('tradeapplylicence/viewTransactionReceipt/' .md5($val['id']));?>','xtf','900','700');" class="btn btn-primary">
															View
														</a>
														<!-- <a target="popup" onclick="window.open('<?php //echo base_url('tradeapplylicence/viewTransactionReceipt/' .md5($val['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php //echo base_url('tradeapplylicence/viewTransactionReceipt/' . md5($val['linkId'])); ?>" type="button" class="btn btn-primary" style="color:white;">View</a> -->
													</td>
												</tr>
											<?php
										}

									?>
									</tbody>
								</table>
								
								<?php 
							}  
							?>

						</div>
					</div>



					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Remarks From Level</h3>
						</div>
						<div class="panel-body">
							<ul class="nav nav-tabs" style="font-size:13px;font-weight: bold;">
								<li class="active" style="background-color:#97c78ebd;"><a data-toggle="tab" href="#Dealing_Officer">Dealing Officer </a></li>
								<li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#tax_daroga">Tax Daroga</a></li>
								<li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#Section_Head">Section Head</a></li>
								<li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#executive_officer">Executive Officer</a></li>
							</ul>
							<div class="tab-content">
								<div id="Dealing_Officer" class="tab-pane fade in active">
									<h3></h3>
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Received Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($delingReceiveDate)) :
												foreach ($delingReceiveDate as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
										<div class="col-sm-3">
											<b style="font-size: 15px;;">Forwarded Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($dealingLevel)) :
												foreach ($dealingLevel as $value) :
											?>
													<b style="font-size: 15px;"> <?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
									<br />
									<br />
									<br />
									<br />
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Remarks:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($dealingLevel)) :
												foreach ($dealingLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
								</div>
								<div id="tax_daroga" class="tab-pane fade">
									<h3></h3>
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Received Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($taxDarogaReceiveDate)) :
												foreach ($taxDarogaReceiveDate as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
										<div class="col-sm-3">
											<b style="font-size: 15px;">Forwarded Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($taxDarogaLevel)) :
												foreach ($taxDarogaLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
									<br />
									<br />
									<br />
									<br />
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Remarks:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($taxDarogaLevel)) :
												foreach ($taxDarogaLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
								</div>
								<div id="Section_Head" class="tab-pane fade">
									<h3></h3>
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Received Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($sectionHeadReceiveDate)) :
												foreach ($sectionHeadReceiveDate as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
										<div class="col-sm-3">
											<b style="font-size: 15px;">Forwarded Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($sectionHeadLevel)) :
												foreach ($sectionHeadLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
									<br />
									<br />
									<br />
									<br />
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Remarks:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($sectionHeadLevel)) :
												foreach ($sectionHeadLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
								</div>
								<div id="executive_officer" class="tab-pane fade">
									<h3></h3>
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Received Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($executiveReceiveDate)) :
												foreach ($executiveReceiveDate as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
										<div class="col-sm-3">
											<b style="font-size: 15px;">Forwarded Date:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($executiveLevel)) :
												foreach ($executiveLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['created_on'] != "" ? date('d-m-Y H:i', strtotime($value['created_on'])) : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
									<br />
									<br />
									<br />
									<br />
									<div class="col-sm-12">
										<div class="col-sm-3">
											<b style="font-size: 15px;">Remarks:</b>
										</div>
										<div class="col-sm-3">
											<?php
											if (isset($executiveLevel)) :
												foreach ($executiveLevel as $value) :
											?>
													<b style="font-size: 15px;"><?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
													<br />
												<?php endforeach; ?>
											<?php endif;  ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>





					<div class="form-group">
						<label class="col-md-2"> <b> Remarks </b> </label>
						<div class="col-md-10">
							<textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" required onkeypress="return isAlphaNum(event)"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2">&nbsp;</label>
						<div class="col-md-10">
							<input type="submit" value="Verify & Forward" class="btn btn-success" id="btn_verify_submit" name="btn_verify_submit" /> &nbsp; &nbsp; &nbsp; &nbsp;
							<input type='submit' value="Backward" class="btn btn-warning" id="btn_backward" name="btn_backward" onclick="return confirm('Are You Sure You Want To Backward Application To The Tax Daroga?')" /> &nbsp; &nbsp; &nbsp; &nbsp;
							<!-- <input type='submit' value="Back To Citizen" class="btn btn-danger" id="btn_backToCitizen" name="btn_backToCitizen" onclick="return confirm('Are You Sure You Want To Backward Application To The Citizen?')" /> -->
						</div>
					</div>


					<!--===================================================-->
					<!--End page content-->
				</form>
			</div>
		</div>
	</div>
	<!--===================================================-->
	<!--END CONTENT CONTAINER-->

	<!-- Creates the bootstrap modal where the image will appear -->
	<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Image preview</h4>
				</div>
				<div class="modal-body">
					<img src="" id="imagepreview" style="width: 400px; height: 264px;">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<?= $this->include('layout_vertical/footer'); ?>
	<script>
		$(function() {
			$('.pop').on('click', function() {
				//alert($(this).find('img').attr('src'));
				$('#imagepreview').attr('src', $(this).find('img').attr('src'));
				$('#imagemodal').modal('show');
			});

		});
	</script>
	<script>
		$(document).ready(function() {

			$("#btn_verify_submit").click(function() {
				var proceed = true;
				var remarks = $("#remarks").val().trim();
				if (remarks == "") {
					$('#remarks').css('border-color', 'red');
					proceed = false;
				}
				return proceed;
			});
			$('#btn_backward').click(function() {
				var proceed = true;
				var remarks = $("#remarks").val().trim();
				if (remarks == "") {
					$('#remarks').css('border-color', 'red');
					proceed = false;
				}
				return proceed;
			});
			$('#btn_backToCitizen').click(function() {
				var proceed = true;
				var remarks = $("#remarks").val().trim();
				if (remarks == "") {
					$('#remarks').css('border-color', 'red');
					proceed = false;
				}
				return proceed;
			});
			$("#remarks").keyup(function() {
				$(this).css('border-color', '');
			});

		});
		/* function backward(ID)
		{
		    var result = confirm("Do You Want To Barckward!!!");
		    if(result)
		     window.location.replace("<?= base_url(); ?>/Trade_SH/backward/"+ID);
		}*/
	</script>
<script type="text/javascript">
	function myPopup(myURL, title, myWidth, myHeight)
	{
		var left = (screen.width - myWidth) / 2;
		var top = (screen.height - myHeight) / 4;
		var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
	}
</script>