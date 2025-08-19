<?= $this->include('layout_home/header');?>
<!--DataTables [ OPTIONAL ]-->

<style>
.row{line-height:25px;}
</style>

<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<b>Application status : - <?=$application_status;?></b>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-4">
									<?=$ward['ward_no']?$ward['ward_no']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Holding No. :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['holding_no']?$holding['holding_no']:"N/A"; ?>
								</div>
								
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Application No. :</b>
								</div>
								<div class="col-sm-4">
									<?=$basic_details['application_no']?$basic_details['application_no']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Application Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['application_type']?$basic_details['application_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Licence For :</b>
								</div>
								<div class="col-sm-4">
									<?=$holding['licence_for_years']?$holding['licence_for_years']."  Years":"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Firm Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['firm_type']?$basic_details['firm_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Ownership Type :</b>
								</div>
								<div class="col-sm-4">
									<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Firm Name :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['firm_name']?$basic_details['firm_name']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>K No :</b>
								</div>
								<div class="col-sm-4">
									<?=$basic_details['k_no']?$basic_details['k_no']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Area :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['area_in_sqft']?$basic_details['area_in_sqft']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Account No :</b>
								</div>
								<div class="col-sm-4">
									<?=$basic_details['account_no']?$basic_details['account_no']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Firm Establishment Date :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['establishment_date']?$holding['establishment_date']:"N/A"; ?>
								</div>												
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Address :</b>
								</div>
								<div class="col-sm-4">
									<?=$holding['address']?$holding['address']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Landmark :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['landmark']?$holding['landmark']:"N/A"; ?>
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
									<?php if($owner_details==""){ ?>
										<tr>
											<td style="text-align:center;"> Data Not Available...</td>
										</tr>
									<?php }else{ ?>
									<?php foreach($owner_details as $owner_details): ?>
										<tr>
											<td><?=$owner_details['owner_name']?$owner_details['owner_name']:"N/A"; ?></td>
											<td><?=$owner_details['guardian_name']?$owner_details['guardian_name']:"N/A"; ?></td>
											<td><?=$owner_details['mobile']?$owner_details['mobile']:"N/A"; ?></td>
											<td><?=$owner_details['emailid']?$owner_details['emailid']:"N/A"; ?></td>
 										</tr>
									<?php endforeach; ?>
									<?php } ?>
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
											foreach($doc_exists as $buval):
										?>
										<tr style="border-bottom:2px solid black;">
											<td><?=$buval["doc_name"]?></td>
											
											<td>
												<a href="<?=base_url();?>/writable/uploads/<?=$buval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											</td>
											<td><?php
												if($buval['verify_status']=="1")
												{
													echo "<button type='button' class='btn btn-success btn-rounded btn-labeled'>Verified</button>";
												}
												else if($buval['verify_status']=="2")
												{
													echo "<button type='button' class='btn btn-danger btn-rounded btn-labeled'>Rejected</button>";
												} 
												else if($buval['verify_status']=="0")
												{
													echo "<span class='text-info'>New</span>";
												}
												?></td>
											<td><?=$buval['remarks'];?></td>
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
        <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
//////modal end
<?= $this->include('layout_home/footer');?>
<script>
$(function() {
		$('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
			$('#imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		

});
</script>