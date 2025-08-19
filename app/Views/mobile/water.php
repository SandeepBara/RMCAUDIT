<?=$this->include("layout_mobi/header");?>

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
	.buttonx{
width:150px;
height:45px;
border:none;
outline:none;
box-shadow:-4px 4px 5px 0 #46403a;
color:#fff;
font-size:14px;
text-shadow:0 1px rgba(0,0,0,0.4);
background-color:#25476a;
border-radius:3px;
font-weight:700
}
.buttonx:hover{
background-color:#FF8000;
color:#fff;
cursor:pointer
}
.buttonx:active{
margin-left:-4px;
margin-bottom:-4px;
padding-top:2px;
box-shadow:none
}

</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
               
				<div class="row">

					<?php
						if($user_type_mstr_id!=13)
						{
							?>

							<a href="<?php echo base_url('WaterApplyNewConnectionMobi') ?>">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-info panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-plug fa-4x"></i></span>
										<p><b>Apply Connection</b></p>
									</div>
								</div>
							</div>
							</a>
							
							<a href="<?php echo base_url('WaterSearchConsumerMobile/search_consumer_tc') ?>">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-purple panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-search fa-4x"></i></span>
										<p><b>Search Consumer</b></p>
									</div>
								</div>
							</div>
							</a>
							
							<a href="<?php echo base_url('WaterSearchApplicantsMobile/search_applicants_tc') ?>">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-success panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-search-plus fa-4x"></i></span>
										<p><b>Water Connection Payment</b></p>
									</div>
								</div>
							</div>
							</a>
							
							
							<!-- <a href="<?php echo base_url('WaterSearchConsumerMobile/search_consumer_tc/update') ?>">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-warning panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-search fa-4x"></i></span>
										<p><b>Update Consumer Connection Type</b></p>
									</div>
								</div>
							</div>
							</a> -->
							<a href="<?php echo base_url('WaterMobileIndex/water_reports_menu') ?>">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-warning panel-colorful" style="background-color:#667b74;color:white;">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-bar-chart fa-4x"></i></span>
										<p><b>Reports</b></p>
									</div>
								</div>
							</div>
							</a>
							<?php
						}

						if($user_type_mstr_id==13)
						{
							?>

							<a href="<?php echo base_url('WaterMobileIndex/search_consumer') ?>">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								<div class="panel panel-purple panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-flag fa-4x"></i></span>
										<p><b>Site Inspection</b></p>
									</div>
								</div>
							</div>
							</a>
							
						
							<?php
							
						}
					?>

					<a href="<?php echo base_url('WaterSearchConsumerMobile/search_consumer_survey/survey') ?>" onclick="(function(){ document.getElementById('missing_geotagging_msg_id').innerHTML='Page is loading'; })();">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
							<div class="panel panel-mint panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-map-marker fa-4x"></i></span>
									<p><b id="missing_geotagging_msg_id">Water Survey</b></p>
								</div>
							</div>
						</div>
					</a>

				</div>	
                     
    <!--End page content-->
	</div>
 </div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>