<?= $this->include("layout_mobi/header"); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<!--CONTENT CONTAINER-->
<div id="content-container">
	<!--Page content-->
	<div id="page-content">
		
		<div class="panel-heading flex" style="display: flex;">
			<div style="flex:1;">
				<h3 class="panel-title"><b style="color:white;">Trade Reports</b></h3>
			</div>
			<div style="flex:1;text-align:right"><a href="<?=base_url('Mobi/mobileMenu/trade'); ?>" class="btn btn-info">Back</a></div>

		</div>
		<div class="row">
            <a href="<?php echo base_url('MobiTradeReport/applyLicenceReport');?>">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="panel panel-colorful"  style="background-color:#667b74;color:white;">
                        <div class="pad-all text-center rprt">
                            <span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x" ></i></span>
                            <p><b>Report</b></p>
                        </div>
                    </div>
                </div>
            </a>
			
			<a href="<?php echo base_url('MobiTradeReport/wardWiseDenialReport/');?>">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="panel panel-warning  panel-colorful" style="background-color: blueviolet;">
                        <div class="pad-all text-center hght">
                        <span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x" ></i></span>
                            <p><b>Ward Wise Denial Report</b></p>
                        </div>
                    </div>
                </div>
            </a>
			
			<a href="<?php echo base_url('MobiTradeReport/tc_team_summary') ?>">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<div class="panel panel-danger panel-colorful">
						<div class="pad-all text-center">
							<span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
							<p><b>Collection Summary</b></p>
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