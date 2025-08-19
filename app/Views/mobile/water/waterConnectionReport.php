<?= $this->include("layout_mobi/header"); ?>

<style>
	.buttonA {
		border: none;
		color: white;
		padding: 10px 25px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		transition-duration: 0.4s;
		cursor: pointer;
	}

	.buttonx {
		width: 150px;
		height: 45px;
		border: none;
		outline: none;
		box-shadow: -4px 4px 5px 0 #46403a;
		color: #fff;
		font-size: 14px;
		text-shadow: 0 1px rgba(0, 0, 0, 0.4);
		background-color: #25476a;
		border-radius: 3px;
		font-weight: 700
	}

	.buttonx:hover {
		background-color: #FF8000;
		color: #fff;
		cursor: pointer
	}

	.buttonx:active {
		margin-left: -4px;
		margin-bottom: -4px;
		padding-top: 2px;
		box-shadow: none
	}

	.hght {
		height: 108px;
	}

	@media only screen and (max-width: 600px) {
		.hght {
			height: 108px;
		}
	}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<!--Page Title-->
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<div id="page-title">
			<!-- <h1 class="page-header text-overflow">Department List</h1>//-->
		</div>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End page title-->

		<!--Breadcrumb-->
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!-- <ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Water</a></li>
			<li class="active">Reports </li>
		</ol> -->
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<div id="page-content">

		<div class="panel panel-bordered panel-dark">
		
			<div class="panel-heading flex" style="display: flex;">
				<div style="flex:1;">
					<a class="panel-control btn-info btn-md" href ="<?=base_url('WaterMobileIndex/water_reports_menu')?>"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</a>
					<h3 class="panel-title">
						<b style="color:white;">Search Water Connection</b>						
					</h3>
					
					
				</div>
				

			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="">
					<div class="col-md-3">
						<label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
						<input required type="date" id="from_date" name="from_date" class="form-control frmtodate" placeholder="From Date" value="<?= (isset($to_date)) ? $from_date : date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>">
					</div>
					<div class="col-md-3">
						<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
						<input required type="date" id="to_date" name="to_date" class="form-control frmtodate" placeholder="To Date" value="<?= (isset($to_date)) ? $to_date : date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>">
					</div>
					<div class="col-md-3">
						<label class="control-label" for="to_date"><b>Ward No.</b><span class="text-danger">*</span></label>
						<select name="ward_id" id="ward_id" class="form-control">
							<option value="All">All</option>
							<?php
							if ($ward_list) :
								foreach ($ward_list as $val) :
							?>
									<option value="<?php echo $val['ward_mstr_id']; ?>" <?php if ($ward_id == $val['ward_mstr_id']) {
																							echo "selected";
																						} ?>><?php echo $val['ward_no']; ?></option>
							<?php
								endforeach;
							endif;
							?>
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="department_mstr_id">&nbsp;</label>
						<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
					</div>
				</form>
			</div>
		</div>
		<?php if ($newWaterConnection) {
		?>

			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title"> Water Connection Report</h5>
				</div>
				<div class="panel-body">
					<div class="row" style="padding: 15px;">
						<?php if ($newWaterConnection['count'] == 0) { ?>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-info panel-colorful">
									<div class="pad-all text-center hght">
										<p style="font-size:23px;" class="ndf"><?= $newWaterConnection['count'] ? $newWaterConnection['count'] : "0" ?></p>
										<p><b>New Water Connection</b></p>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<a href="<?php echo base_url('TcWaterReportController/wardWiseWaterConnection/' . base64_encode($from_date) . '/' . base64_encode($to_date) . '/' . base64_encode($ward_id) . '/' . base64_encode("1")); ?>">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div class="panel panel-info panel-colorful">
										<div class="pad-all text-center hght">
											<p style="font-size:23px;" class="ndf"><?= $newWaterConnection['count'] ? $newWaterConnection['count'] : "0" ?></p>
											<p><b>New Water Connection</b></p>
										</div>
									</div>
								</div>
							</a>
						<?php } ?>

						<?php if ($regularization['count'] == 0) { ?>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-mint panel-colorful">
									<div class="pad-all text-center hght">
										<p style="font-size:23px;"><?= $regularization['count'] ? $regularization['count'] : "0" ?></p>
										<p><b>Regularization</b></p>
									</div>
								</div>
							</div>
						<?php } else { ?>
							<a href="<?php echo base_url('TcWaterReportController/wardWiseWaterConnection/' . base64_encode($from_date) . '/' . base64_encode($to_date) . '/' . base64_encode($ward_id) . '/' . base64_encode("2")); ?>">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div class="panel panel-mint panel-colorful">
										<div class="pad-all text-center hght">
											<p style="font-size:23px;" class="ndf"><?= $regularization['count'] ? $regularization['count'] : "0" ?></p>
											<p><b>Regularization</b></p>
										</div>
									</div>
								</div>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<!--End page content-->
	</div>
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include("layout_mobi/footer"); ?>




<script>
	$(".frmtodate").change(function() {
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		if (from_date > to_date) {
			alert("From Date should not be greater then To Date");
			$("#to_date").val("");
		}

	});
	 $('#btn_search').click(function(){

    	if($('#from_date').val()=="" && $('#to_date').val()==""){
    		return false;
    	}else{
    		$('#btn_search').html('Please Wait...');
    		return true;
    	}
    	
    });
</script>