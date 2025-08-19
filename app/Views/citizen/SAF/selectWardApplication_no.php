
<?= $this->include('layout_home/header');?>
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
 <!--CONTENT CONTAINER-->
<div id="content-container" style="padding: 20px 0px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Select Ward And (Application Number)</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-12">
                           
							<div class="row">
								<label class="col-md-4">PLEASE SELECT WARD Number <span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                        <option value="">== PLEASE SELECT ==</option>
										<?php foreach($wardlist as $post){ ?>
                                            <option value="<?=$post['id'];?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$post["id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
										<?php } ?>
                                    </select>
                                </div>
							</div><br>
							<div class="row text-center">
								<div class="col-md-8">
									<span class="text-danger"><b>AND</b></span>
								</div>
							</div><br>
							<div class="row">
								<label class="col-md-4">PLEASE ENTER APPLICATION NO. <span class="text-danger">*</span></label>
                                <div class="col-md-4 pad-btm">
									<div class="form-group">
										<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Application Number Or Mobile Number" value="<?php echo $keyword; ?>">
									</div>
								</div>
                                <div class="col-md-2">
                                <button type="SUBMIT" id="submit" name="submit" class="btn btn-block btn-mint">GO NOW</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
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
									<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead class="bg-trans-dark text-dark">
												<th>Sl No. </th>
												<th>Ward No </th>
												<th>Application No. </th>
												<th>Owner(s) Name </th>
												<th>Address </th>
												<th>Khata No. </th>
												<th>Plot No. </th>
												<th>Mobile No. </th>
												<th>Action </th>
										</thead>
										<tbody>
											<?php if($emp_details):
												$i=1;  ?>
												<?php foreach($emp_details as $post): ?>
											<tr>
												<td><?php echo $i++; ?></td>
												<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
												<td><?=$post['saf_no']?$post['saf_no']:"N/A"; ?></td>
												
												<td>
														<?php echo $post['owner_name']; ?><br>
													
												</td>
												<td><?=$post['prop_address']?$post['prop_address']:"N/A"; ?></td>
												<td><?=$post['khata_no']?$post['khata_no']:"N/A"; ?></td>
												<td><?=$post['plot_no']?$post['plot_no']:"N/A"; ?></td>
												<td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
												<td>
													<a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_due_details/'.md5($post['saf_dtl_id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
													
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
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
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