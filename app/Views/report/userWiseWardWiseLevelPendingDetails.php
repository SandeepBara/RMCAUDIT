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
				<h3 class="panel-title">Ward Wise Pending Report (<?=$empname;?>)</h3>
			</div>
			<div class="panel-body">
				<form method="get">
					<div class="row">
						<div class="col-md-2">
							<div class="radio">
								<input type="radio" id="by_all" class="magic-radio" name="step_type" value="by_all" <?=isset($_GET["step_type"])?($_GET["step_type"]=="by_all")?"checked":"":"checked"?>>
								<label for="by_all">All</label>

								<input type="radio" id="by_date_range" class="magic-radio" name="step_type" value="by_date_range" <?=isset($_GET["step_type"])?($_GET["step_type"]=="by_date_range")?"checked":"":""?>>
								<label for="by_date_range">With Date Range</label>
							</div>
						</div>
						<div class="col-md-6 <?=isset($_GET["step_type"])?($_GET["step_type"]=="by_date_range")?"":"hidden":"hidden"?>" id="date_range_hide">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										From Date
										<input type="date" id="from_date" name="from_date" class="form-control" value="<?=isset($_GET["from_date"])?$_GET["from_date"]:date("Y-m-d")?>" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										Upto Date
										<input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=isset($_GET["upto_date"])?$_GET["upto_date"]:date("Y-m-d")?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2">
						<button type="submit" id="search" class="btn btn-primary">SEARCH</button>
						</div>
					</div>
				</form>
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-responsive" id="level_list">
					<thead>
						<tr style="background-color: #e6e1e1;">
							<th>Sl No.</th>
							<th>Ward No</th>
							<th>Total No of Form(s)</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$i=0;
						if (isset($user_wise_pending_result)) {
							$total = 0;
							foreach ($user_wise_pending_result AS $rec) {
								//print_var($rec);
								$total +=$rec["levelform"];
						?>
								<tr>
									<td><?=++$i;?></td>
									<td><?=$rec["ward_no"];?></td>
									<td><?=$rec["levelform"];?></td>
								</tr>
						<?php
							}
						?>
								<tr>
									<td></td>
									<td>Total</td>
									<td><?=$total;?></td>
								</tr>
						<?php
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
	$('input[type=radio][name=step_type]').change(function() {
		if (this.value == 'by_date_range') {
			$('#date_range_hide').removeClass("hidden");
		}
		else if (this.value == 'by_all') {
			$('#date_range_hide').addClass("hidden");
		}
	});
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
                
                title: "<?=$empname;?> - Ward Wise Pending Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2] }
            }, {
                text: 'pdf',
                extend: "pdfHtml5",
                title: "<?=$empname;?>- Ward Wise Pending Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2] }
            }]
		});
	} );
</script>