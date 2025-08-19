<?= $this->include('layout_vertical/header');?>
<?php 
$display =  isset($user_type_id) && in_array($user_type_id,[1])?true: false;
?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
					<li><a href="#">Masters</a></li>
					<li class="active">Rate Chart List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Rate Chart List</h5>
						</div>
						<div class="panel-body">
							 <div class="pad-btm">
								<?php
								if($display)
								{
									?>
									<a  href="<?php echo base_url('WaterRateChartMaster/create') ?>"><button id="demo-foo-collapse" class="btn btn-purple">Add New  <i class="fa fa-arrow-right"></i></button></a>
									<?php
								}
								?>
							</div>
							<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th>#</th>
										<th>Type</th>
										<th>Propetry Type</th>
										<th>Range From</th>
										<th>Range Upto</th>
										<th>Amount</th>
										<th>Effect Date</th>
										<?php
										if($display)
										{
											?>
											<th >Action</th>
											<?php
										}
										?>
										
									</tr>
								</thead>
								<tbody>
									<?php
									if(isset($rate_chart_list)):
										if(empty($rate_chart_list)):
									?>
										<tr>
											<td colspan="8" style="text-align: center;">Data Not Available!!</td>
										</tr>
									<?php else:
										$i=0;
										foreach ($rate_chart_list as $value):
									?>
										<tr>
											<td><?=++$i;?></td>
											<td><?=$value["type"];?></td>
											<td><?=$value["property_type"];?></td>
											<td><?=$value["range_from"];?></td>
											<td><?=$value["range_upto"];?></td>
											<td><?=$value["amount"];?></td>
											<td><?=$value["effective_date"];?></td>
											<?php
											if($display)
											{
												?>												
												<td>
													<a class="btn btn-primary" href="<?php echo base_url('WaterRateChartMaster/create/'.md5($value['id']));?>" role="button" >Edit</a>
														&nbsp;&nbsp;
												   <button type="button" class="btn btn-primary" onclick="deletefun(<?=$value["id"];?>);">Delete</button> 
												</td>
												<?php
											}
											?>
										</tr>
									<?php endforeach;?>
									<?php endif;  ?>
									<?php endif;  ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
					
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
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
				exportOptions: { columns: [  1,2,3,4,5,6] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [  1,2,3,4,5,6] }
			}]
		});
	});
   function deletefun(ID)
{
    var result = confirm("Do You Want To Delete");
    if(result)
     window.location.replace("<?=base_url();?>/WaterRateChartMaster/delete/"+ID);
}

</script>
