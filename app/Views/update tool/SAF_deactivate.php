
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
					<li><a href="#">SAF</a></li>
					<li class="active">SAF Deactivate</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

				<div id="page-content">
					<form action="<?php echo base_url('update_tool/saf_deactivate');?>" method="post" role="form" class="php-email-form">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">SAF Deactivate</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-8" id="saf">
										<label class="col-md-3">Enter Saf No. <span class="text-danger">*</span></label>
										<div class="col-md-6 pad-btm">
											<div class="form-group">
												<input type="text" id="saf_no" name="saf_no" class="form-control" style="height:38px;" placeholder="Enter Saf No." value="<?php echo $saf; ?>">
											</div>
										</div>
										<div class="col-md-3 pad-btm">
											<button type="submit" id="search_saf" name="search_saf" class="btn btn-primary" value="search">SEARCH</button>
										</div>
									</div>
								</div>
							 </div>
						</div>
					</form>
					
					<?php if(isset($saf_details)){
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">SAF List :</h3>
						</div>
						<div class="panel-body">
							<div id="saf_distributed_dtl_hide_show">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-align:center;">
												<thead class="bg-trans-dark text-dark">
														<th>Sl No. </th>
														<th>Ward No </th>
														<th>Saf No </th>
														<th>Owner(s) Name </th>
														<th>Address </th>
														<th>Mobile No. </th>
														<th>Khata No. </th>
														<th>Plot No. </th>
														<th>Action </th>
												</thead>
												<tbody>
													<?php if($saf_details):
														$i=1;  ?>
														<?php foreach($saf_details as $post): ?>
													<tr>
														<td><?php echo $i++; ?></td>
														<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
														<td><?=$post['saf_no']?$post['saf_no']:"N/A"; ?></td>
														
														<td>
															<?php echo $post['owner_name']; ?><br>
														</td>
														<td><?=$post['prop_address']?$post['prop_address']:"N/A"; ?></td>
														<td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
														<td><?=$post['khata_no']?$post['khata_no']:"N/A"; ?></td>
														<td><?=$post['payment_status']?$post['payment_status']:"N/A"; ?></td>
														<td>
															<?php if($post['payment_status']==1){ ?>
																<b style="color:green;">Payment Already Done.</b>
															<?php } else { 
																if($post['status']==1){
															?>
																	<button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#deactivate">
																		Deactivate
																	</button>
																<?php } else { ?>
																	<b style="color:red;">SAF Already Deactivated</b>
																<?php } ?>
															<?php } ?>
														</td>
															
													</tr>
													<?php endforeach; ?>
													<?php else: ?>
													<tr>
														<td colspan="9" style="text-align:center;color:red;"> Data Are Not Available!!</td>
													</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					
					
					
					<!-- The Modal -->
					<div class="modal" id="deactivate">
						<div class="modal-dialog">
							<div class="modal-content">
								<form method="post" action="<?php echo base_url('update_tool/saf_deactivate/'.md5($post['saf_dtl_id']));?>">
									<!-- Modal Header -->
									<input type="hidden" id="saf_dtl_id" name="saf_dtl_id" class="form-control" value="<?php echo $post['saf_dtl_id']; ?>">
									<input type="hidden" id="saf_ward_no" name="saf_ward_no" class="form-control" value="<?php echo $post['ward_no']; ?>">
									<input type="hidden" id="saf_no" name="saf_no" class="form-control" value="<?php echo $post['saf_no']; ?>">
									<input type="hidden" id="emp_dtl_id" name="emp_dtl_id" class="form-control" value="1">
									<input type="hidden" id="prop_type_mstr_id" name="prop_type_mstr_id" class="form-control" value="<?php echo $post['prop_type_mstr_id']; ?>">
									
									<div class="modal-header">
									  <h4 class="modal-title">Remarks</h4>
									  <button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									
									<!-- Modal body -->
									<div class="modal-body">
										<textarea class="form-control" rows="5" id="remarks" name="remarks" placeholder="Enter the remarks for deactivate SAF"></textarea>
									</div>
									
									<!-- Modal footer -->
									<div class="modal-footer">
									  <button type="submit" class="btn btn-danger">Deactivate</button>
									</div>
								</form>
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
		
		
		$("#submit").click(function(){
			proceed = true;
            var remark = $("#remarks").val();
            if(remark=="")
			{
				alert("Please mention reason for deactivating");
				$("#remark").css('border-color', 'red');
				return false;
			}

		return process;
		});
		$("#remark").change(function(){ $(this).css('border-color',''); });
		
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