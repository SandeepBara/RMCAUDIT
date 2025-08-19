
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Search Property</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

			<div id="page-content">
				<form action="<?php echo base_url('jsk/jsk_Property_Tax');?>" method="post" role="form" class="php-email-form">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Property Search</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-2">
									<label for="keyword">Enter Keywords <span class="text-danger">*</span></label>
								</div>
								<div class="col-md-3 pad-btm">
									<div class="form-group">
										<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keywords" value="<?php echo $keyword; ?>">
										<span class="text-danger">(Enter Holding No. Or 15 Digit Unique No.)</span>
									</div>
								</div>
								<div class="col-md-3 pad-btm">
									<button type="submit" id="search" name="search" class="btn btn-primary" value="search">SEARCH</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<?php
				if(isset($emp_details)){
				?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Citizen List :</h3>
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
													<th>Holding No </th>
													<th>15 Digit Unique House No. </th>
													<th>Owner(s) Name </th>
													<th>Address </th>
													<th>Mobile No. </th>
													<th>Khata No. </th>
													<th>Plot No. </th>
													<th>Action </th>
											</thead>
											<tbody>
												<?php if($emp_details):
													$i=1;  ?>
													<?php foreach($emp_details as $post): ?>
												<tr>
													<td><?php echo $i++; ?></td>
													<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
													<td><?=$post['holding_no']?$post['holding_no']:"N/A"; ?></td>
													<td><?=$post['new_holding_no']?$post['new_holding_no']:"N/A"; ?></td>
													<td>
														<?php echo $post['owner_name']; ?><br>
														
													</td>
													<td><?=$post['prop_address']?$post['prop_address']:"N/A"; ?></td>
													<td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
													<td><?=$post['khata_no']?$post['khata_no']:"N/A"; ?></td>
													<td><?=$post['plot_no']?$post['plot_no']:"N/A"; ?></td>
													<td>
														<?php if($post['status']==0){ ?>
															<b style="color:red;">Deactive</b>
														<?php } ?>
														<a href="<?php echo base_url('jsk/jsk_due_details/'.md5($post['prop_dtl_id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
														
												</tr>
												<?php endforeach; ?>
												<?php else: ?>
												<tr>
													<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
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
				<?php }?>
					
		</div><br>

<?= $this->include('layout_vertical/footer');?>

<!--DataTables [ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#demo_dt_basic').DataTable({
			responsive: true,
			dom: 'Bfrtip',
	        lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	        buttons: [
	        	'pageLength',
	          {
				text: 'excel',
				extend: "excel",
				title: "Report",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}]
		});
	});
	
	

 </script>

