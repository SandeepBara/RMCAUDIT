
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
					<li><a href="#">Transaction</a></li>
					<li class="active">Transaction Deactivate</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

				<div id="page-content">
					<form action="<?php echo base_url('update_tool/basic_dtl_update');?>" method="post" role="form" class="php-email-form">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Transaction Deactivate</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-md-2">Select Type <span class="text-danger">*</span></label>
									<div class="col-md-4 pad-btm">
										<div class="form-group">
											<select id="type" name="type" class="form-control m-t-xxs">
												<option value="">Select</option>
												<option value="Saf">SAF</option>
												<option value="Property">Property</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-8" id="transaction">
										<label class="col-md-3">Enter Transaction No. <span class="text-danger">*</span></label>
										<div class="col-md-6 pad-btm">
											<div class="form-group">
												<input type="text" id="holding_no" name="holding_no" class="form-control" style="height:38px;" placeholder="Enter Holding No." value="">
											</div>
										</div>
										<div class="col-md-3 pad-btm">
											<button type="submit" id="search_holding" name="search_holding" class="btn btn-primary" value="search">SEARCH</button>
										</div>
									</div>
								</div>
							 </div>
						</div>
					</form>
					<?php
					if(isset($prop_details)){
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Transaction List :</h3>
						</div>
						<div class="panel-body">
							<div id="saf_distributed_dtl_hide_show">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-align:center;">
												<thead class="bg-trans-dark text-dark">
														<th>Sl No. </th>
														<th>Ward No. </th>
														<th>Transaction No. </th>
														<th>Owner(s) Name </th>
														<th>Address </th>
														<th>Mobile No. </th>
														<th>Khata No. </th>
														<th>Plot No. </th>
														<th>Action </th>
												</thead>
												<tbody>
													<?php if($prop_details):
														$i=1;  ?>
														<?php foreach($prop_details as $post): ?>
													<tr>
														<td><?php echo $i++; ?></td>
														<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
														<td><?=$post['holding_no']?$post['holding_no']:"N/A"; ?></td>
														
														<td>
															<?php echo $post['owner_name']; ?><br>
														</td>
														<td><?=$post['prop_address']?$post['prop_address']:"N/A"; ?></td>
														<td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
														<td><?=$post['khata_no']?$post['khata_no']:"N/A"; ?></td>
														<td><?=$post['plot_no']?$post['plot_no']:"N/A"; ?></td>
														<td>
															<a href="<?php echo base_url('update_tool/update_prop_basic_details/'.md5($post['prop_dtl_id']));?>" type="button" id="prop_basic_detail" class="btn btn-primary" style="color:white;">View</a></td>
															
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