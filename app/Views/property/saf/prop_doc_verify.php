<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->

<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Property </a></li>
			<li><a href="#">Property </a></li>
			<li class="active">Document Verification List</li>
		</ol>
	</div>
	<div id="page-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h5 class="panel-title">Dealing Assistant Inbox List</h5>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<form class="form-horizontal" method="get" action="<?=base_url('documentverification/prop_inbox_index');?>">
									<div class="form-group">
										<div class="col-md-4">
											<label class="control-label" for="from_date"><b>From Date</b> </label>
											<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?= (isset($from_date)) ? $from_date : date('Y-m-d'); ?>" max="<?=date('Y-m-d');?>" />
										</div>
										<div class="col-md-4">
											<label class="control-label" for="to_date"><b>To Date</b> </label>
											<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?= (isset($to_date)) ? $to_date : date('Y-m-d'); ?>" max="<?=date('Y-m-d');?>" />
										</div>
										
									</div>
									<div class="form-group">
										
										<div class="col-md-4">
											<label class="control-label" for="btn_search">&nbsp;</label>
											<button class="btn btn-success btn-block" id="btn_search" type="submit">Search</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="row">
							<div class="table-responsive">
								<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>#</th>
											<th>Ward No.</th>
											<th>Old Holding No.</th>
											<th>New Holding No.</th>
											<th>Khata No.</th>
											<th>Plot No.</th>
											<th>Address.</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										//print_r($owner);
										if (isset($inboxList)) :
											if (empty($inboxList)) :
										?>
												<tr>
													<td colspan="7" style="text-align: center;">Data Not Available!!</td>
												</tr>
												<?php else :
												$i = 0;
												foreach ($inboxList as $value) :
												?>
													<tr>
														<td><?= ++$i; ?></td>
														<td><?= isset($value["ward_no"])? $value['ward_no']:"null"; ?></td>
														
														<td><?= isset($value["holding_no"])? $value['holding_no']:"null"; ?></td>
														<td><?= isset($value["new_holding_no"])? $value['new_holding_no']:"null"; ?></td>
														<td><?= isset($value["khata_no"])? $value['khata_no']:"null"; ?></td>
														<td><?= isset($value["plot_no"])? $value['plot_no']:"null"; ?></td>
														<td><?= isset($value["prop_address"])? $value['prop_address']:"null"; ?></td>
														
														<td>
															<a class="btn btn-primary" href="<?php echo base_url('DocumentVerification/verifySpecialDocument'.'/'.$value['prop_dtl_id']); ?>">View</a>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php endif;  ?>
										<?php endif;  ?>
									</tbody>
								</table>
								<?=pagination($pager);?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->include('layout_vertical/footer'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#from_date").change(function() {
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();

			var startDay = new Date(from_date);
			var endDay = new Date(to_date);

			if ((startDay.getTime()) > (endDay.getTime())) {
				alert("Please select valid To Date!!");
				$("#from_date").val('');
			}
		});
		$("#to_date").change(function() {
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();

			var startDay = new Date(from_date);
			var endDay = new Date(to_date);

			if ((startDay.getTime()) > (endDay.getTime())) {
				alert("Please select valid To Date!!");
				$("#to_date").val('');
			}
		});

		$("#btn_search").click(function() {
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			if (from_date == "") {
				alert("Please Select From Date");
				$('#from_date').focus();
				return false;
			}

			if (to_date == "") {
				alert("Please Select To date");
				$('#to_date').focus();
				return false;
			}
		});
		
	});
</script>