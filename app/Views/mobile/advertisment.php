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
					<a href="<?php echo base_url('SafVerification/index') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Field Verification</b></p>
							</div>
						</div>
					</div>
					</a>
					
					<?php if($user_type_mstr_id<>7){?>
					<a href="<?php echo base_url('safdistribution/form_distribute') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Form Distribution</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php }?> 
                                   
                
				<?php if($user_type_mstr_id<>7){?>
				
				
					<a href="<?php echo base_url('mobi/search_Property_Tax') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-purple panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Pay Property Tax</b></p>
							</div>
						</div>
					</div>
					</a>
					<a href="<?php echo base_url('mobi/reports_menu') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-warning panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Reports</b></p>
							</div>
						</div>
					</div>
					</a>
					
                                  
                    
                
				 
					<a href="<?php echo base_url('mobiSaf/searchDistributedDtl') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Assessment</b></p>
							</div>
						</div>
					</div>
					</a>
					
					<a href="<?php echo base_url('WaterMobileIndex/index') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-primary panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Water</b></p>
							</div>
						</div>
					</div>
					</a>
                    
                    
                
            <?php }?>
                
					<a href="<?php echo base_url('mobisafDemandPayment/saf_Property_Tax') ?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-pink panel-colorful">
							<div class="pad-all text-center">
								<span class="text-x text-thin"><i class="fa fa-user-plus fa-4x"></i></span>
								<p><b>Pay SAF Tax</b></p>
							</div>
						</div>
					</div>
					</a>
					
                
                </div>

                    <!--<div class="col-sm-4">
                        <a href="<?php echo base_url('safdistribution/saf_opt') ?>">
                            <div class="panel panel-mint panel-colorful media middle pad-all">
                                <div class="media-left">
                                    <i class="demo-pli-camera-2 icon-2x"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">SAF Distribution</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
						<a href="<?php echo base_url('mobi/search_Property_Tax') ?>">
                        <div class="panel panel-mint panel-colorful media middle pad-all">
                            <div class="media-left">
                                <i class="demo-pli-camera-2 icon-2x"></i>
                            </div>
                            <div class="media-body">
                                <p class="mar-no">Pay Property Tax</p>
                            </div>
                        </div>
						</a>
                    </div>                
                     <div class="col-sm-4">
                        <a href="<?php echo base_url('SafVerification/index') ?>">
                            <div class="panel panel-mint panel-colorful media middle pad-all">
                                <div class="media-left">
                                    <i class="demo-pli-camera-2 icon-2x"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">SAF Verification</p>
                                </div>
                            </div>
                        </a>
                    </div>
					<div class="col-sm-4">
                        <a href="<?php echo base_url('mobisafDemandPayment/saf_Property_Tax') ?>">
                            <div class="panel panel-mint panel-colorful media middle pad-all">
                                <div class="media-left">
                                    <i class="demo-pli-camera-2 icon-2x"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Pay SAF Property Tax</p>
                                </div>
                            </div>
                        </a>
                    </div>
                
                
				
           
        
    </div>
    <!--End page content-->
	</div>
 </div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>