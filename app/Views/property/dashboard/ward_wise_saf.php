<?= $this->include('layout_vertical/header'); ?>
<link href="<?= base_url(); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<!--Page Title-->
		<div id="page-title">

		</div>
		<!--End page title-->
		<!--Breadcrumb-->
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('MiniDashboard/index/'); ?>"><i class="demo-pli-home"></i></a></li>
			<li><a href="#" class="active">Ward Wise Saf</a></li>
		</ol>
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Ward Wise Saf Report</h3>
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
						$i = 0;
						if (isset($result)) {
							$total = 0;
							foreach ($result as $rec) {
								//print_var($rec);
								$total += $rec["saf_count"];
								?>
								<tr>
									<td><?= ++$i; ?></td>
									<td><?= $rec["ward_no"]; ?></td>
									<td><a style="color: #0963eb;"
											href="<?php echo base_url('MiniDashboard/' . $propcess . '/' . $assessement . '/' . md5($rec["ward_mstr_id"])); ?>"><strong><?= $rec["saf_count"]; ?></strong></a>
									</td>
								</tr>
								<?php
							}
							?>
							<tr>
								<td></td>
								<td>Total</td>
								<td><a style="color: #0963eb;"
										href="<?php echo base_url('MiniDashboard/' . $propcess . '/' . $assessement); ?>"><strong><?= $total; ?></strong></a>
								</td>
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
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script>
	$('input[type=radio][name=step_type]').change(function () {
		if (this.value == 'by_date_range') {
			$('#date_range_hide').removeClass("hidden");
		}
		else if (this.value == 'by_all') {
			$('#date_range_hide').addClass("hidden");
		}
	});
	$(document).ready(function () {
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

					title: "Ward Wise saf Report",
					footer: { text: '' },
					exportOptions: { columns: [0, 1, 2] }
				}, {
					text: 'pdf',
					extend: "pdfHtml5",
					title: "Ward Wise saf Report",
					download: 'open',
					footer: { text: '' },
					exportOptions: { columns: [0, 1, 2] }
				}]
		});
	});
</script>