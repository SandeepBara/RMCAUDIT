
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
                    
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Activate / Deactivate Property</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

				<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Activate / Deactivate Property</h3>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<div class="col-md-4">
											Holding No. 
										</div>
										<div class="col-md-8">
											<b>: <?=$pro_det['holding_no']?$pro_det['holding_no']:"N/A"; ?></b>
										</div>
										<div class="col-md-4">
											Ward No. 
										</div>
										<div class="col-md-8">
											<b>: <?=$pro_det['ward_no']?$pro_det['ward_no']:"N/A"; ?></b>
										</div>
										<div class="col-md-4">
											Owner Name 
										</div>
										<div class="col-md-8">
											<b>: <?=$pro_det['owner_name']?$pro_det['owner_name']:"N/A"; ?></b>
										</div>
										<div class="col-md-4">
											Address
										</div>
										<div class="col-md-8">
											<b>: <?=$pro_det['prop_address']?$pro_det['prop_address']:"N/A"; ?></b>
										</div>
										<div class="col-md-4">
											Mobile No.
										</div>
										<div class="col-md-8">
											<b>: <?=$pro_det['mobile_no']?$pro_det['mobile_no']:"N/A"; ?></b>
										</div>
									</div>
									<div class="col-md-6">
										<div class="col-md-12">
											<form action="<?php echo base_url('update_tool/prop_active_deactive_remark');?>" method="post" role="form" class="php-email-form">
												<input type="hidden" id="prop_dtl_id" name="prop_dtl_id" class="form-control" value="<?php echo $pro_det['prop_dtl_id']; ?>">
												<input type="hidden" id="prop_ward_no" name="prop_ward_no" class="form-control" value="<?php echo $pro_det['ward_no']; ?>">
												<input type="hidden" id="holding_no" name="holding_no" class="form-control" value="<?php echo $pro_det['holding_no']; ?>">
												<input type="hidden" id="emp_dtl_id" name="emp_dtl_id" class="form-control" value="1">
												
												<textarea class="form-control" rows="5" id="remarks" name="remarks" placeholder="Enter the remarks for activate / deactive Property" value=""></textarea>
												<br>
												<input type="file" class="form-control " id="reason_doc" name="reason_doc">
												<br>
												<?php if($pro_det['status']==1){ ?>
													<input type="hidden" id="action_type" name="action_type" class="form-control" value="Deactivate">
													<button type="submit" class="col-md-4 btn btn-danger">Deactivate</button>
												<?php }
												else{ ?>
													<input type="hidden" id="action_type" name="action_type" class="form-control" value="Activate">
													<button type="submit" class="col-md-4 btn btn-success">Activate</button>
												<?php } ?>
											</form>
										</div>
									</div>
								</div>
							</div>
						 </div>
					</div>
					
					
				</div><br><br><!-- End Contact Section -->

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
	
		
	</script>