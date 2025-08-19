
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

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
                    
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Basic Details</a></li>
					<li class="active">Update Basic Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



			<!-- ======= Cta Section ======= -->

				<div id="page-content">
					<form action="<?php echo base_url('update_tool/update_prop_basic_details');?>" method="post" role="form" class="php-email-form">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Update Property Basic Details</h3>
							</div>
							<div class="panel-body">
								<input type="hidden" id="old_ward_mstr_id" name="old_ward_mstr_id" class="form-control" value="<?=$prop_details['ward_mstr_id']; ?>">
								<input type="hidden" id="old_new_ward_mstr_id" name="old_new_ward_mstr_id" class="form-control" value="<?=$prop_details['old_ward_mstr_id']; ?>">
								<input type="hidden" id="old_plot_no" name="old_plot_no" class="form-control" value="<?=$prop_details['plot_no']; ?>">
								<input type="hidden" id="old_khata_no" name="old_khata_no" class="form-control" value="<?=$prop_details['khata_no']; ?>">
								<input type="hidden" id="old_mauja_name" name="old_mauja_name" class="form-control" value="<?=$prop_details['village_mauja_name']; ?>">
								<input type="hidden" id="old_prop_address" name="old_prop_address" class="form-control" value="<?=$prop_details['prop_address']; ?>">
								<input type="hidden" id="prop_dtl_id" name="prop_dtl_id" class="form-control" value="<?=$prop_details['prop_dtl_id']; ?>">
								
								<div class="row">
									<label class="col-md-2">Ward No.</label>
									<div class="col-md-4">
										<div class="form-group">
											<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
												<option value="">Select</option>
												<?php if($ward): ?>
												<?php foreach($ward as $post): ?>
												<option value="<?=$post['id']?>" <?=(isset($prop_details))?$prop_details['ward_mstr_id']==$post["id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
									<label class="col-md-2">Holding No.</label>
									<div class="col-md-4">
										<div class="form-group">
											:  <?=$prop_details['holding_no']?$prop_details['holding_no']:"N/A"; ?>
										</div>
									</div>
								</div>
								<div class="row">
									<label class="col-md-2">New Ward No.</label>
									<div class="col-md-4">
										<div class="form-group">
											<select id="new_ward_mstr_id" name="new_ward_mstr_id" class="form-control m-t-xxs">
												<option value="">Select</option>
												<?php if($ward): ?>
												<?php foreach($ward as $post): ?>
												<option value="<?=$post['id']?>" <?=(isset($prop_details))?$prop_details['old_ward_mstr_id']==$post["id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
									<label class="col-md-2">New Holding No.</label>
									<div class="col-md-4">
										<div class="form-group">
											: <?=$prop_details['new_holding_no']?$prop_details['new_holding_no']:"N/A"; ?>
										</div>
									</div>
								</div>
								
								<div class="row">
									<label class="col-md-2">Plot No.</label>
									<div class="col-md-4">
										<div class="form-group">
											<input type="text" id="plot_no" name="plot_no" class="form-control" value="<?=$prop_details['plot_no']?$prop_details['plot_no']:"N/A"; ?>">
										</div>
									</div>
									<label class="col-md-2">Entery Type</label>
									<div class="col-md-4">
										<div class="form-group">
											: <?=$prop_details['entry_type']?$prop_details['entry_type']:"N/A"; ?>
										</div>
									</div>
								</div>
								<div class="row">
									<label class="col-md-2">Khata</label>
									<div class="col-md-4">
										<div class="form-group">
											<input type="text" id="khata_no" name="khata_no" class="form-control" value="<?=$prop_details['khata_no']?$prop_details['khata_no']:"N/A"; ?>">
										</div>
									</div>
									<label class="col-md-2">Area Of Plot</label>
									<div class="col-md-4">
										<div class="form-group">
											: <?=$prop_details['area_of_plot']?$prop_details['area_of_plot']:"N/A"; ?>
										</div>
									</div>
								</div>
								
								<div class="row">
									<label class="col-md-2">Mauja Name</label>
									<div class="col-md-4">
										<div class="form-group">
											<input type="text" id="mauja_name" name="mauja_name" class="form-control" value="<?=$prop_details['village_mauja_name']?$prop_details['village_mauja_name']:"N/A"; ?>">
										</div>
									</div>
									<label class="col-md-2">Address</label>
									<div class="col-md-4">
										<div class="form-group">
											<input type="text" id="prop_address" name="prop_address" class="form-control" value="<?=$prop_details['prop_address']?$prop_details['prop_address']:"N/A"; ?>">
										</div>
									</div>
								</div>
								
								<div class="row">
									<label class="col-md-2">Remarks</label>
									<div class="col-md-4">
										<div class="form-group">
											<textarea  id="loc_remark" name="loc_remark" class="form-control" value="" placeholder="Please mention here reason for updating details"></textarea>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-4 pad-btm">
										<input type="submit" id="prop_basic_detail" class="btn btn-primary" style="color:white;" value="Update">
									</div>
								</div>
								
							</div>
						</div>
					</form>
					<form action="<?php echo base_url('update_tool/update_prop_owner');?>" method="post" role="form" class="php-email-form">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Owner Details</h3>
							</div>
							<?php if($owner_details):?>
								<?php foreach($owner_details as $owner_details): ?>
									<div class="panel-body">
										<input type="hidden" id="old_owner_name" name="old_owner_name" class="form-control" value="<?=$owner_details['owner_name']?$owner_details['owner_name']:"N/A"; ?>">
										<input type="hidden" id="old_relation_type" name="old_relation_type" class="form-control" value="<?=$owner_details['relation_type']?$owner_details['relation_type']:"N/A"; ?>">
										<input type="hidden" id="old_mobile_no" name="old_mobile_no" class="form-control" value="<?=$owner_details['mobile_no']?$owner_details['mobile_no']:"N/A"; ?>">
										<input type="hidden" id="old_guardian_name" name="old_guardian_name" class="form-control" value="<?=$owner_details['guardian_name']?$owner_details['guardian_name']:"N/A"; ?>">
										<input type="hidden" id="prop_dtl_id" name="prop_dtl_id" class="form-control" value="<?=$owner_details['prop_dtl_id']?$owner_details['prop_dtl_id']:"N/A"; ?>">
										
										<div class="row">
											<label class="col-md-2">Owner Name</label>
											<div class="col-md-4">
												<div class="form-group">
													<input type="text" id="owner_name" name="owner_name" class="form-control" value="<?=$owner_details['owner_name']?$owner_details['owner_name']:"N/A"; ?>">
												</div>
											</div>
											<label class="col-md-2">R/W Guardian</label>
											<div class="col-md-4">
												<div class="form-group">
													<input type="text" id="relation_type" name="relation_type" class="form-control" value="<?=$owner_details['relation_type']?$owner_details['relation_type']:"N/A"; ?>">
												</div>
											</div>
										</div>
										<div class="row">
											<label class="col-md-2">Mobile No.</label>
											<div class="col-md-4">
												<div class="form-group">
													<input type="text" id="mobile_no" name="mobile_no" class="form-control" value="<?=$owner_details['mobile_no']?$owner_details['mobile_no']:"N/A"; ?>">
												</div>
											</div>
											<label class="col-md-2">Guardian Name</label>
											<div class="col-md-4">
												<div class="form-group">
													<input type="text" id="guardian_name" name="guardian_name" class="form-control" value="<?=$owner_details['guardian_name']?$owner_details['guardian_name']:"N/A"; ?>">
												</div>
											</div>
										</div>
										<div class="row">
											<label class="col-md-2">Remarks</label>
											<div class="col-md-4">
												<div class="form-group">
													<textarea  id="owner_remark" name="owner_remark" class="form-control" value="" placeholder="Please mention here reason for updating details"></textarea>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-4 pad-btm">
												<input type="submit" id="prop_owner_det" name="prop_owner_det" class="btn btn-primary" style="color:white;" value="Update">
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						
					</form>
				</div><br><br><!-- End Contact Section -->
			</div>

<?= $this->include('layout_vertical/footer');?>

<!--DataTables [ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script>
	
	$(document).ready(function() {
		$("#type").change(function () {
			
			var type = $("#type").val();
			
			if(type=="Saf"){
				$('#saf').show();
				$('#holding').hide();
			}
			else if(type=="Property"){
				$('#holding').show();
				$('#saf').hide();
			}
			
			
		});
    });
	
	function modelInfo(msg){
		$.niftyNoty({
			type: 'info',
			icon : 'pli-exclamation icon-2x',
			message : msg,
			container : 'floating',
			timer : 5000
		});
	}
	<?php if($result = flashToast('SAF_deactivate')) { ?>
		modelInfo('<?=$result;?>');
	<?php }?>	
	
		
</script>