
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
						<li><a href="#"><i class="demo-pli-home"></i></a></li>
						<li><a href="#">Property</a></li>
						<li><a href="#">Holding</a></li>
						<li class="active">Notice List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

					<div id="page-content">
					<?php
					
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Generated Notice List</h3>
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
														<th>Holding No </th>
														<th>Notice No </th>
														<th>Owner Name </th>
														<th>Mobile No </th>
														<th>Notice Date</th>
                                                        <th>Generated Date</th>
														<th>Action </th>
												</thead>
												<tbody>
													<?php 
													if($result):
														$i=0;
														foreach($result as $post):
															?>
															<tr>
																<td><?=++$i;?></td>
																<td><?=!empty($post['new_ward_no'])?$post['new_ward_no']:$post['ward_no'];?></td>
																<td><?=!empty($post['new_holding_no'])?$post['new_holding_no']:$post['holding_no'];?></td>
																<td>NOTICE/<?=$post['notice_no'];?></td>
																<td><?=$post['owner_name'];?></td>
																<td><?=$post['mobile_no'];?></td>
																<td><?=date('Y-m-d', strtotime($post['notice_date']));?></td>
                                                                <td><?=date('Y-m-d', strtotime($post['created_on']));?></td>
																<td>
																	<a href="<?=base_url('propDtl/ViewNotice/'.md5($post['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
																</td>
															</tr>
															<?php 
														endforeach;
													else:
														?>
														<tr>
															<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
														</tr>
														<?php 
													endif;
													?>
												</tbody>
											</table>
											<?=isset($result['count'])?pagination($result['count']):null;?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					

</div><br><br><!-- End Contact Section -->

<?= $this->include('layout_vertical/footer');?>

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: false,
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
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7] }
            }]
        });
        
    });

</script>
 

