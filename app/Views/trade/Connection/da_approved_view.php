<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->

<style>
	.row {
		line-height: 25px;
	}
</style>

<link href="<?= base_url(); ?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
			<li class="active">Back To Citizen List</li>
		</ol>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<!--===================================================-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">
					<a href="<?php echo base_url('trade_da/da_approved_list') ?>" class="btn btn-default">Back</a>
				</div>
				<h3 class="panel-title">Basic Details</h3>
			</div>
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
						<?= $holding['holding_no'] ? $holding['holding_no'] : "N/A"; ?>
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
						<b>K No :</b>
					</div>
					<div class="col-sm-4">
						<?= $basic_details['k_no'] ? $basic_details['k_no'] : "N/A"; ?>
					</div>
					<div class="col-sm-3">
						<b>Area :</b>
					</div>
					<div class="col-sm-3">
						<?= $basic_details['area_in_sqft'] ? $basic_details['area_in_sqft'] : "N/A"; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<b>Account No :</b>
					</div>
					<div class="col-sm-4">
						<?= $basic_details['account_no'] ? $basic_details['account_no'] : "N/A"; ?>
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
						<b>Address :</b>
					</div>
					<div class="col-sm-4">
						<?= $holding['address'] ? $holding['address'] : "N/A"; ?>
					</div>
					<div class="col-sm-3">
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
					<thead class="thead-light" style="background-color: blanchedalmond;">
						<tr>
							<th scope="col">Owner Name</th>
							<th scope="col">Guardian Name</th>
							<th scope="col">Mobile No</th>
							<th scope="col">Email Id</th>
							<th scope="col">Id Proof</th>
							<th scope="col">Id No</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if ($owner_details == "") 
						{ 
							?>
							<tr>
								<td style="text-align:center;"> Data Not Available...</td>
							</tr>
							<?php 
						} 
						else 
						{ 
							foreach ($owner_details as $owner_details) 
							{ 
								 ?>
								<tr>
									<td><?= $owner_details['owner_name'] ? $owner_details['owner_name'] : "N/A"; ?></td>
									<td><?= $owner_details['guardian_name'] ? $owner_details['guardian_name'] : "N/A"; ?></td>
									<td><?= $owner_details['mobile'] ? $owner_details['mobile'] : "N/A"; ?></td>
									<td><?= $owner_details['emailid'] ? $owner_details['emailid'] : "N/A"; ?></td>
									<td><?= $owner_details['doc_name'] ? $owner_details['doc_name'] : "N/A"; ?></td>
									<td><?= $owner_details['id_no'] ? $owner_details['id_no'] : "N/A"; ?></td>
								</tr>
								<?php 
							} 
							?>
							<tr class="success">
								<th>Document Name</th>
								<th>Applicant Image</th>
								<th>Status</th>
								<th colspan="3">Remarks</th>
							</tr>
							<?php
								if(empty($owner_details['saf_owner_img_list']))
								{
									?>
									<tr>
                                        <td colspan="4" class="text-danger text-center">! No Data</td>
                                    </tr>
									<?php
								}
								else
								{
									foreach ($owner_details['saf_owner_img_list'] as $imgval) 
									{
										?>
										<tr>
											<td>Consumer Photo (<span class="text-danger"><?= $docval['doc_for']; ?></span>)</td>
											<td><a href="<?= base_url(); ?>/writable/uploads/<?= $imgval["document_path"]; ?>" target="_blank"><img id="imageresource" src="<?= base_url(); ?>/writable/uploads/<?= $imgval["document_path"]; ?>" style="width: 40px; height: 40px;"></a></td>
											<td><?php
												if ($imgval['verify_status'] == "1") {
													echo "<span class='text-danger'>Verified</span>";
												} else if ($imgval['verify_status'] == "2") {
													echo "<span class='text-danger'>Rejected</span>";
												} else if ($imgval['verify_status'] == "0") {
													echo "<span class='text-danger'>New</span>";
												}
												?></td>
											<td><?= $imgval['remarks']; ?></td>

										</tr>
										<?php 
									} 
								}
							?>
							<tr class="success">
								<th> Document Name</th>
								<th>Applicant Document</th>
								<th>Status</th>
								<th colspan="3">Remarks</th>
							</tr>
							<?php
							if(empty($owner_details['saf_owner_doc_list']))
							{
								?>
								<tr>
									<td colspan="4" class="text-danger text-center">! No Data</td>
								</tr>
								<?php
							}
							else
							{
								foreach ($owner_details['saf_owner_doc_list'] as $docval) 
								{
									?>
										<tr>
											<td>Photo Id Proof (<span class="text-danger"><?= $docval['doc_for']; ?></span>)</td>

											<td>

												<a href="<?= base_url(); ?>/writable/uploads/<?= $docval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>

											</td>
											<td><?php
												if ($docval['verify_status'] == "1") {
													echo "<span class='text-danger'>Verified</span>";
												} else if ($docval['verify_status'] == "2") {
													echo "<span class='text-danger'>Rejected</span>";
												} else if ($docval['verify_status'] == "0") {
													echo "<span class='text-danger'>New</span>";
												}
												?></td>
											<td><?= $docval['remarks']; ?></td>

										</tr>
									<?php 
								} 
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
								<th>Document Name</th>
								<th>Document</th>
								<th>Status</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($business_doc_exists as $buval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Business Premises</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $buval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>

									<td><?php
										if ($buval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($buval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($buval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $buval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>

							<?php
							foreach ($tanent_doc_exists as $tanval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Tanented</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $tanval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>

									<td><?php
										if ($tanval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($tanval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($tanval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $tanval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>

							<?php
							foreach ($Pvt_doc_exists as $pvtval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Pvt. Ltd. OR Ltd. Company</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $pvtval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($pvtval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($pvtval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($pvtval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $pvtval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>

							<?php
							foreach ($noc_doc_exists as $nocval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>NOC And NOC Affidavit Document</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $nocval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($nocval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($nocval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($nocval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $nocval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>

							<?php
							foreach ($partnership_doc_exists as $parval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Partnership Document</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $parval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($parval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($parval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($parval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $parval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>

							<?php
							foreach ($sapat_patra_doc_exists as $sapval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Sapat Patra</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $sapval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($sapval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($sapval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($sapval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $sapval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>
							<?php
							foreach ($solid_waste_doc_exists as $solval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Solid Waste User Charge Document</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $solval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($solval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($solval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($solval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $solval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>
							<?php
							foreach ($electricity_doc_exists as $eleval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Electricity Bill</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $eleval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($eleval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($eleval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($eleval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $eleval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>
							<?php
							foreach ($application_doc_exists as $appval) :
							?>
								<tr style="border-bottom:2px solid black;">
									<td>Application Form</td>

									<td>
										<a href="<?= base_url(); ?>/writable/uploads/<?= $appval['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									</td>
									<td><?php
										if ($appval['verify_status'] == "1") {
											echo "<span class='text-danger'>Verified</span>";
										} else if ($appval['verify_status'] == "2") {
											echo "<span class='text-danger'>Rejected</span>";
										} else if ($appval['verify_status'] == "0") {
											echo "<span class='text-danger'>New</span>";
										}
										?></td>
									<td><?= $appval['remarks']; ?></td>
								</tr>
							<?php endforeach; ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>


	</div>
	<!--===================================================-->
	<!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
///////modal start
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
//////modal end
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