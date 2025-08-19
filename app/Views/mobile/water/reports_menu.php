<?= $this->include("layout_mobi/header"); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<!--CONTENT CONTAINER-->
<div id="content-container">
	<!--Page content-->
	<div id="page-content">
		
		<div class="panel-heading flex" style="display: flex;">
			<div style="flex:1;">
				<h3 class="panel-title"><b style="color:white;">Water Reports</b></h3>
			</div>
			<div style="flex:1;text-align:right"><a href="<?=base_url('WaterMobileIndex/index'); ?>" class="btn btn-info">Back</a></div>

		</div>
		<div class="row">
			<a href="<?php echo base_url('TcWaterReportController/datewise_ward_transaction_report/'.md5(1)) ?>">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<div class="panel panel-info panel-colorful">
						<div class="pad-all text-center">
							<span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
							<p><b> Daily Collection</b></p>
						</div>
					</div>
				</div>
			</a>
			<?php
			
			?>
			<a href="<?php echo base_url('TcWaterReportController/tc_team_summary') ?>">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<div class="panel panel-danger panel-colorful">
						<div class="pad-all text-center">
							<span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
							<p><b>Collection Summary</b></p>
						</div>
					</div>
				</div>
			</a>
			<?php
			
			?>
			<a href="<?php echo base_url('TcWaterReportController/report') ?>">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<div class="panel panel-success panel-colorful">
						<div class="pad-all text-center">
							<span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
							<p><b>Ward Wise Water Connection </b></p>
						</div>
					</div>
				</div>
			</a>



		</div>
	</div>
	<!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include("layout_mobi/footer"); ?>