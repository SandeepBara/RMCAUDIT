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
					<a href="<?php echo base_url('SafVerification/atc_field_verification_list') ?>" onclick="(function(){ document.getElementById('field_verification_msg_id').innerHTML='Page is loading'; })();">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
								<p><b id="field_verification_msg_id">Field Verification</b></p>
							</div>
						</div>
					</div>
					</a>

					<?php 
					# Agency TC
					// if($user_type_mstr_id==5)
					if(in_array($user_type_mstr_id,[5,4]))
					{
							?>
							<a href="<?php echo base_url('WaterHarvestingTC/ATCList') ?>" onclick="(function(){ document.getElementById('water_harvesting_filed_verification_msg_id').innerHTML='Page is loading'; })();">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div class="panel panel-mint panel-colorful">
										<div class="pad-all text-center">
											<span class="text-x text-thin"><i class="fa fa-tint fa-4x"></i></span>
											<p><b id="water_harvesting_filed_verification_msg_id">Water Harvesting Field Verification</b></p>
										</div>
									</div>
								</div>
							</a>
							<a href="<?php echo base_url('SafVerification/atc_missing_geotag') ?>" onclick="(function(){ document.getElementById('missing_geotagging_msg_id').innerHTML='Page is loading'; })();">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div class="panel panel-mint panel-colorful">
										<div class="pad-all text-center">
											<span class="text-x text-thin"><i class="fa fa-map-marker fa-4x"></i></span>
											<p><b id="missing_geotagging_msg_id">Missing Geotagging</b></p>
										</div>
									</div>
								</div>
							</a>
							<a href="<?php echo base_url('safdistribution/form_distribute_list') ?>" onclick="(function(){ document.getElementById('form_distribution_msg_id').innerHTML='Page is loading'; })();">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
								    <div class="panel panel-success panel-colorful">
										<div class="pad-all text-center">
											<span class="text-x text-thin"><i class="fa fa-file-archive-o fa-4x"></i></span>
											<p><b id="form_distribution_msg_id">Form Distribution</b></p>
										</div>
									</div>
								</div>
							</a>

				
				
							<a href="<?php echo base_url('mobi/list_of_Property') ?>" onclick="(function(){ document.getElementById('pay_property_tax_msg_id').innerHTML='Page is loading'; })();">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
							    <div class="panel panel-purple panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-money fa-4x"></i></span>
										<p><b id="pay_property_tax_msg_id">Pay Property Tax</b></p>
									</div>
								</div>
							</div>
							</a>
							<a href="<?php echo base_url('mobi/reports_menu') ?>" onclick="(function(){ document.getElementById('reports_msg_id').innerHTML='Page is loading'; })();">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
							    <div class="panel panel-warning panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-flag fa-4x"></i></span>
										<p><b id="reports_msg_id">Reports</b></p>
									</div>
								</div>
							</div>
							</a>
							
		                                  
		                    
		                
						 
							<a href="<?php echo base_url('mobiSaf/searchDistributedDtl') ?>" onclick="(function(){ document.getElementById('assessment_msg_id').innerHTML='Page is loading'; })();">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
							    <div class="panel panel-mint panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-book fa-4x"></i></span>
										<p><b id="assessment_msg_id">Assessment</b></p>
									</div>
								</div>
							</div>
							</a>
							
							<a href="<?php echo base_url('mobisafDemandPayment/saf_Property_Tax') ?>" onclick="(function(){ document.getElementById('search_assessment_msg_id').innerHTML='Page is loading'; })();">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
							    <div class="panel panel-pink panel-colorful">
									<div class="pad-all text-center">
										<span class="text-x text-thin"><i class="fa fa-search-plus fa-4x"></i></span>
										<p><b id="search_assessment_msg_id">Search Assessment</b></p>
									</div>
								</div>
							</div>
							</a>
							<a href="<?=base_url('TrustList/index');?>">
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
									<div class="panel panel-primary panel-colorful">
										<div class="pad-all text-center">
											<span class="text-x text-thin"><i class="fa fa-address-card fa-4x"></i></span>
											<p><b>School-Trust List</b></p>
										</div>
									</div>
								</div>
							</a>
            				<?php 
            		}
            		?>
                
					<a href="<?php echo base_url('propDtl/noticeServeList') ?>" onclick="(function(){ document.getElementById('notice_served').innerHTML='Page is loading'; })();">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
							<div class="panel panel-info panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
									<p><b id="notice_served">Notice Serve</b></p>
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