<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="<?=base_url()."/levelwisependingform/reportlevelpending";?>">Level Wise Pending Report</a></li>
		<li class="active">Employee Wise Pending Report</li>
		</ol>
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading" style="background-color: #298da0;">
				<h3 class="panel-title">Employee Wise Pending Report (<?=$user_type;?>)</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-responsive" id="level_list">
					<thead>
						<tr style="background-color: #e6e1e1;">
							<th>Sl No.</th>
							<th>Employee Name</th>
							<th>Total No of Form(s)</th>
							<th>Wise Ward Wise</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$i=0;
						if (isset($user_wise_pending_result)) {
							foreach ($user_wise_pending_result AS $rec) {
								//print_var($rec);
						?>
								<tr>
									<td><?=++$i;?></td>
									<td><?=$rec["emp_name"];?></td>
									<td><?=$rec["levelform"];?></td>
									<td><a href="<?php echo base_url('levelwisependingform/reportUserWiseWardWireLevelPending/'.$rec["receiver_user_type_id"].'/'.$rec["emp_dtl_id"].'/'.$rec["emp_name"]);?>" type="button" class="btn btn-primary btn-labeled">View Ward Wise Details</a></td>
								</tr>
						<?php
							}
						} else {
						?>
							<tr>
								<td>No Records found</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						<?php 
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script>
	$(document).ready( function () {
		$('#level_list').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [
                [-1, 10, 25, 50],
                ['Show all', '10 rows', '25 rows', '50 rows']
            ],
			searching: false, 
			paging: false,
			"ordering": false,
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                
                title: "Employee Wise Pending Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2] }
            }, {
                text: 'pdf',
                extend: "pdfHtml5",
                title: "Employee Wise Pending Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2] }
            }]
		});
	} );
</script>