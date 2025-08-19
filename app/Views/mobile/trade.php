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
	@media only screen and (max-width: 600px) 
	{
			.rprt {
	    		height:126px;
	  	}
	}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">

<!--Page Title-->
<div id="page-title">
</div>
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Trade</a></li>
  </ol>
</div>
    <!--Page content-->
    <div id="page-content">
               
				<div class="row">
					<a href="<?php echo base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.md5(1));?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-info panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
									<p><b>Apply New Licence</b></p>
								</div>
							</div>
						</div>
					</a>
					<a href="<?=base_url('');?>/tradeapplylicence/tobaccoapplynewlicence/<?=md5(1);?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-colorful"  style="background-color: #f50940db;color:white;">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
									<p><b>Apply New Licence (Tobbaco)</b></p>
								</div>
							</div>
						</div>
          </a>
			
					<a href="<?php echo base_url('mobiTradeSearchApplicant/index');?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-mint panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-search fa-4x"></i></span>
									<p><b>Search Application</b></p>
								</div>
							</div>
						</div>
					</a>
				
					<a href="<?php echo base_url('mobitradeapplylicence/index');?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-warning panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-search-plus fa-4x"></i></span>
									<p><b>Search Licence</b></p>
								</div>
							</div>
						</div>
					</a>
                 
					<a href="<?php echo base_url('denial/index');?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-danger panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-ban fa-4x" aria-hidden="true"></i></span>
									<p><b>Denial</b></p>
								</div>
							</div>
						</div>
					</a>
                
					
 
					<!-- <a href="<?php echo base_url('MobiTradeReport/applyLicenceReport');?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-colorful"  style="background-color:#667b74;color:white;">
								<div class="pad-all text-center rprt">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x" ></i></span>
									<p><b>Report</b></p>
								</div>
							</div>
						</div>
					</a> -->
					<a href="<?php echo base_url('MobiTradeReport/reports_menu');?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-colorful"  style="background-color:#667b74;color:white;">
								<div class="pad-all text-center rprt">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x" ></i></span>
									<p><b>Report</b></p>
								</div>
							</div>
						</div>
					</a>
					  
          <!-- <a href="<?php echo base_url('MobiTradeReport/wardWiseDenialReport/');?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-warning  panel-colorful" style="background-color: blueviolet;">
								<div class="pad-all text-center hght">
								<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x" ></i></span>
									<p><b>Ward Wise Denial Report</b></p>
								</div>
							</div>
						</div>
					</a> -->

					<!-- view added on 1st may 2022 -->
					<a href="<?php echo base_url('mobiTradeSarLicence/index/'.md5(4));?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-info panel-colorful">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
									<p><b>Apply Surrender</b></p>
								</div>
							</div>
						</div>
         	</a>
         	<a href="<?php echo base_url('mobiTradeSarLicence/index/'.md5(3));?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-colorful"  style="background-color: #f50940db;color:white;">
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
									<p><b>Apply Amendment</b></p>
								</div>
							</div>
						</div>
         	</a>
         	<a href="<?php echo base_url('mobiTradeSarLicence/index/'.md5(2));?>">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						    <div class="panel panel-dark panel-colorful" >
								<div class="pad-all text-center">
									<span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
									<p><b>Apply Renewal</b></p>
								</div>
							</div>
						</div>
         	</a>

        </div>
	  </div>
 </div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
