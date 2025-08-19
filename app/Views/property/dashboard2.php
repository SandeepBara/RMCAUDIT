
<?= $this->include('layout_vertical/header');?>
<link id="theme" href="<?=base_url();?>/public/assets/css/theme-dark-full.min.css" rel="stylesheet">
 <!--CONTENT CONTAINER-->
 <style>

.panZoom {
  transition: transform .2s;
  height:120px;
  color:white ;
 
}

.pad-all {
  height:112px;
  
}
.panchart{
  height:220px;
  
}
.padChart{
  height:175px;
  
}

.panBarchart{
  height:380px;
}
.padPieChart{
  height:210px;
}
#dmndColctn text {
    fill: #f1d406fa !important;
}
#collection text {
    fill: #f1d406fa !important;
}
#fieldFinal text {
    fill: #f1d406fa !important;
}
.panbigBarchart{
  height:305px;
  
}
#tradeCollection text {
    fill: #f1d406fa !important;
}
.tradeChart{
  height:295px; 
}
#licence text {
    fill: #f1d406fa !important;
}
#application text {
    fill: #f1d406fa !important;
}
.licenceChart{
  height:200px;
}
.applicationChart{
  height:200px;
}
</style>

    <!--Page content-->
	<div id="content-container">	
        
            <div class="panel-heading" style="background-color:#000;">
                <h3 class="panel-title">
				<div class="col-md-8 col-xs-8">
					<div class="col-md-2 col-xs-2">
						<i class="fa fa-inbox"></i> Dashboard  
					</div>
					<div class="col-md-4 col-xs-4" id="front_show_date_time">
					</div><!-- 14 September 2020, 17:55:12 -->
					
                </div>
				<ul class="nav nav-tabs pull-right">
				  <li><a href="#advertisement" data-toggle="tab">Advertisement</a></li>
				  <li id="trade_click_show"><a href="#trade" data-toggle="tab" onclick="tradeData();">Trade</a></li>
				  <li id="water_click_show"><a href="#water" data-toggle="tab" onclick="waterData();">Water</a></li>
				  <li class="prop_click_show active"><a href="#property" data-toggle="tab" onclick="propertyData();">Property</a></li>
				</ul></h3>
            </div>
		
            <div class="panel-body" style="background-color:#000;">
               <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
			  
			  <!--Start Property page content-->
				
					<div class="chart tab-pane active" id="property" style="position: relative;">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-3 col-xs-3" id="fy_id">
									<div class="form-group">
										<select id="fy_mstr_id" name="fy_mstr_id" class="form-control m-t-xxs" onchange="propertyData();">
											<option value="">Financial Years</option>
											<?php if($fy_years): ?>
												<?php foreach($fy_years as $post): ?>
												<option value="<?=$post['fy']?>" <?=(isset($fy_mstr_id))?$fy_mstr_id==$post["fy"]?"SELECTED":"":"";?>><?=$post['fy'];?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<!-- 14 September 2020, 17:55:12 -->
								<!--<div class="col-md-6 col-xs-6" id="top_extra">
									<div class="form-group">
										<div class="panel-group">
											<button type="button" class="btn" data-toggle="collapse" data-target="#collapse1" style="background-color:#3d4553;">Rebet / Penalty</button>
											
										</div>
									</div>
								</div><!-- 14 September 2020, 17:55:12 -->
							</div>
						</div>
						<div class="row">
						<div class="col-lg-4" style="height: 610px;" id="amntval">
							<div id="loadingDivs" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div class="row">
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										<div class="media-body">
											<p class="mar-no">
												<p>Current House Hold &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="crrntHousehold" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												<p>Current Demand &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="crrntdemand" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										
										<div class="media-body">
											<p class="mar-no">
												<p>Collection House Hold :<b><input type="text" class="form-control" id="currentcolHousehold" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												<p>Current Collection &nbsp;: &nbsp;&nbsp;&nbsp;<b><input type="text" class="form-control" id="currentcolamount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										
										<div class="media-body">
											<p class="mar-no">
												<p>Arrear House Hold &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="arearHousehold" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												<p>Arrear Demand &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="areardemand" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										
										<div class="media-body">
											<p class="mar-no">
												<p>Collection House Hold :<b><input type="text" class="form-control" id="arearcolHousehold" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												<p>Arrear Collection &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="arrearcolamount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											</p>
										</div>
									</div>
								</div>
							</div>
						
							<div id="loadingDivs" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div class="row">
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><b><input type="text" class="form-control" id="dailycolamount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:25px;"></b></p>
											<p class="mar-no">Daily Collection</p>
										</div>
									</div>
								</div>
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										<div class="media-body">
											<p class="mar-no">
												<p>Current Year SAF&nbsp;:&nbsp;&nbsp;<b><input type="text" class="form-control" id="yrsaf" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												<p>Current Month SAF&nbsp;:&nbsp;&nbsp;<b><input type="text" class="form-control" id="mnthsaf" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 12px;"></b></p>
											</p>
											
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><b><input type="text" class="form-control" id="fieldverification" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:25px;"></b></p>
											<p class="mar-no">Field Verification</p>
										</div>
									</div>
								</div>
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										<div class="media-body">
											<p class="mar-no">
												<p>SAM Generated&nbsp;:&nbsp;&nbsp;<b><input type="text" class="form-control" id="sam" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
												<p>FAM Generated&nbsp;:&nbsp;&nbsp;<b><input type="text" class="form-control" id="fam" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										
										<div class="media-body">
											
											<p class="mar-no">
												<p style="margin: 0 0 0px;">New Assessment&nbsp;:&nbsp;&nbsp;<b><input type="text" class="form-control" id="newsaf" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 12px;"></b></p>
												<p style="margin: 0 0 0px;">Re-Assessment&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><input type="text" class="form-control" id="resaf" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 12px;"></b></p>
												<p style="margin: 0 0 0px;">Mutation&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><input type="text" class="form-control" id="mutsaf" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 12px;"></b></p>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-6 panZoom">
									<div class="panel panel-colorful media middle pad-all">
										<div class="media-body">
											<p class="text-2x mar-no text-semibold"><b><input type="text" class="form-control" id="legacy" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:25px;"></b></p>
											<p class="mar-no">Unverified Legacy</p>
										</div>
									</div>
								</div>
								<!--<div class="col-md-12" style="height:185px;">
									<div id="newreMutn" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
								</div>-->
							</div>
						</div>
						<div class="col-lg-8">
							<div class="row">
								<div id="mnthcoll" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
								<div class="col-md-12 panBarchart">
									<div id="chartContainer" style="height: 370px; width: 100%;"></div>
								</div>
							</div>
							<div class="row" style="height:185px;">
								<div class="col-md-6 panchart">
									<div id="dcbload" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
									<div id="dmndColctns" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
								</div>
								<div class="col-md-6 panchart">
									<div id="collload" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
									<div id="collection" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
								</div>
							</div>
							
						</div>
						</div>
						<div class="col-lg-12" style="height: 315px;">
						<div id="dycollload" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div class="row"  style="height:305px;">
								<div class="col-md-12 panbigBarchart">
									<div id="fieldFinal" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
								</div>
								
							</div>
						</div>
					</div>
					
					<!--End Property page content-->
					
					<!--Start Water page content-->
					
				<div class="chart tab-pane" id="water" style="position: relative;">
					<div class="col-md-12" >
						<div class="row">
							<div class="col-md-3 col-xs-3" id="fy_waterid">
								<div class="form-group">
									<select id="waterfy_mstr_id" name="waterfy_mstr_id" class="form-control m-t-xxs" onchange="waterData();">
										<option value="">Financial Years</option>
										<?php if($fy_years): ?>
										<?php foreach($fy_years as $post): ?>
										<option value="<?=$post['fy']?>" <?=(isset($fy_mstr_id))?$fy_mstr_id==$post["fy"]?"SELECTED":"":"";?>><?=$post['fy'];?></option>
										<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</div><!-- 14 September 2020, 17:55:12 -->
							
						</div>
					</div>
					<div class="col-lg-4" style="height: 610px;" id="amntwtrval">
					<div id="loadwtrval" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
						<div class="row">
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="mar-no">
											<p>Connection Count &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="ConnCount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											<p>Connection Amount &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="Connamount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											
										</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									
									<div class="media-body">
										<p class="mar-no">
											<p>Consumer Count &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="Conscount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											<p>Consumer Amount &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="Consamount" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
										</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="mar-no">
											<p>New Connection &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="newconn" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											<p>Regularization &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="regular" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											
										</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="text-2x mar-no text-semibold"><input type="text" class="form-control" id="consum" value="" readonly style="background-color: #3d4553;border: none;color:white;font-size:20px;"></p>
										<p class="mar-no">Total Consumer</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="mar-no">
											<p>Current Demand &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="current_demand" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											<p>Current Collection &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="current_Collec" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											
										</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="mar-no">
											<p>Arrear Demand &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="arrear_demand" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
											<p>Arrear Collection &nbsp;: &nbsp;&nbsp;<b><input type="text" class="form-control" id="arrear_Collec" readonly value="" style="background-color: #3d4553;border: none;color:white;font-size:14px;height: 14px;"></b></p>
										</p>
									</div>
								</div>
							</div>
						</div>
					
						<div class="row" style="height:230px;">
							<div class="col-md-12 panchart" style="height:230px;">
							<div id="loadwtrdmnd" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
								<div id="wtrdmndColctns" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="col-md-12" style="height:300px;">
						<div id="loadwtrmnth" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div id="watermnthcoll" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
						</div>
						<div class="col-md-12" style="height:280px;margin-top:10px;">
						<div id="loadwtrcmp" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div id="watermnthcollcmpr" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
						</div>
					</div>
					<div class="col-md-12" style="height:300px;">
					<div id="loadwtrdy" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
						<div id="watermnthdaycoll" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
					</div>
				</div>
					
					<!--End Water page content-->
					
					<!--Start Trade page content-->
					
					
				<div class="chart tab-pane" id="trade" style="position: relative;">
					
					<div class="col-md-12" >
						<div class="row">
							<div class="col-md-3 col-xs-3" id="fy_tradeid">
								<div class="form-group">
									<select id="trdfy_mstr_id" name="trdfy_mstr_id" class="form-control m-t-xxs" onchange="tradeData();">
										<option value="">Financial Years</option>
										<?php if($fy_years): ?>
										<?php foreach($fy_years as $post): ?>
										<option value="<?=$post['fy']?>" <?=(isset($fy_mstr_id))?$fy_mstr_id==$post["fy"]?"SELECTED":"":"";?>><?=$post['fy'];?></option>
										<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							</div><!-- 14 September 2020, 17:55:12 -->
						</div>
					</div>
					<div class="col-md-4" style="height: 510px;" id="countvalue">
					<div id="loadtradecount" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
						<div class="row">
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="text-2x mar-no text-semibold"><input type="text" class="form-control" id="active" value="0" readonly style="background-color: #3d4553;border: none;color:white;font-size:20px;"></p>
										<p class="mar-no"><b>Active Licence</b></p>
									</div>
								</div>
							</div>
							
							<div class="col-md-6 panZoom">
								<div class="panel panel-colorful media middle pad-all">
									<div class="media-body">
										<p class="text-2x mar-no text-semibold"><input type="text" class="form-control" id="deactive" readonly value="0" style="background-color: #3d4553;border: none;color:white;font-size:20px;"></p>
										<p class="mar-no"><b>Deactive Licence</b></p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12 ">
								<div class="panel panel-colorful media middle pad-all" style="height: 170px;color:#fff;">
									<div class="media-body">
										<b class="text-danger">No. Of Application</b>
										<p class="mar-no">
											<b>New &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<label id="newapp" style="font-size:20px;">0</label></b><br>
											<b>Renewal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<label id="renewal" style="font-size:20px;">0</label></b><br>
											<b>Amendment &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<label id="amendment" style="font-size:20px;">0</label></b><br>
											<b>Surrender &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<label id="surrenderapp" style="font-size:20px;">0</label></b><br>
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 ">
								<div class="panel panel-colorful media middle pad-all" style="height: 185px;color:#fff;">
									<div class="media-body">
										<b class="text-danger">Total Collection</b>
										<p class="mar-no">
											<b>New Licence &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;<label id="newLicence" style="font-size:20px;">0</label></b><br><br>
											<b>Renewal Licence &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;<label id="renewalLicence" style="font-size:20px;">0</label></b><br><br>
											<b>Amendment Licence &nbsp;: &nbsp;&nbsp;<label id="amendmentLicence" style="font-size:20px;">0</label></b><br>
											
										</p>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="col-md-8">
						<div class="col-md-12" style="height: 290px;">
						<div id="loadtradefy" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div id="tradeCollection" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
						</div>
						
						<div class="col-md-12 applicationChart" style="margin-top:10px;">
						<div id="loadtradecmpr" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<div id="trdmnthcollcompare" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
						</div>
					</div>
					
					<div class="col-md-12" style="height: 300px;">
					<div id="loadtradedy" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
						<div id="trademnthdayCollection" style="width: 100%; height: 100%; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
					</div>
					
				</div>
					
				<!--End Trade page content-->


				
			</div>
		</div>
	</div>
	
    
    <!--End page content-->

<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url('');?>/public/assets/js/canvasjs.min.js"></script>
<script>
function CanvasCharList(JsonData) {
	var dataPoints = [];
	var charts = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		theme: "dark2", // "light1", "light2", "dark1", "dark2"
		title: {
			text: "Monthly Collection"
		},
		axisY: {
			title: "Total Collected Amount"
		},
		data: [{
			type: "column",
			dataPoints: dataPoints
		}]
	});
	var monthArr = ['April', 'May', 'June','July', 'August', 'September','October', 'November', 'December','January', 'February', 'March'];
	for (var z = 0; z < JsonData.length; z++) {
		//console.log(JsonData[i]);
		dataPoints.push({
			label: monthArr[z],
			y: JsonData[z]
		});
	}
charts.render();
}

function CanvasChardyList(JsonData) {
	var dataPoints = [];
	var dailycoll = new CanvasJS.Chart("fieldFinal", {
		animationEnabled: true,
		theme: "dark2", // "light1", "light2", "dark1", "dark2"
		title:{
			text: "Daily Collection"
		},
		axisY: {
			title: "Total Collected Amount",
		},
		data: [{
			type: "splineArea",
			color: "#c5721b",
			dataPoints: dataPoints
		}]
	});
	var dyArr = ['01', '02', '03','04', '05', '06','07', '08', '09','10', '11', '12','13', '14', '15',
	'16', '17', '18','19', '20', '21','22', '23', '24','25', '26', '27','28', '29', '30', '31'];
	for (var i = 0; i < JsonData.length; i++) {
		dataPoints.push({
			label: dyArr[i],
			y: JsonData[i]
		});
	}
dailycoll.render();
}

function CanvasChardcbList(JsonData) {
	var dataPoints = [];
	var yy = new CanvasJS.Chart("dmndColctns", {
		theme: "dark2",
		title: {
			text: "Demand Collection Balance"
		},
		
		data: [{
			type: "pie",
			indexLabel: "{y}",
			indexLabelPlacement: "inside",
			indexLabelFontColor: "#d6ea0a",
			indexLabelFontSize: 14,
			indexLabelFontWeight: "bolder",
			showInLegend: true,
			legendText: "{label}",
			dataPoints: dataPoints
		}]
	});
	var name = ['Collection Amount', 'Balance Amount'];
	for (var i = 0; i < JsonData.length; i++) {
		dataPoints.push({
			label:	name[i],
			y: JsonData[i]
		});
	}
	yy.render();

}


function CanvasCharcmprList(JsonData) {
	var d = new Date();
	var n = d.getMonth();
	var crntPoints = [];
	var prvPoints = [];
	var m = n-2;
	var cmpr = new CanvasJS.Chart("collection", {
		theme: "dark2",
		title: {
			text: "Compare Months Collection From Previous Year"
		},
		axisY: {
			title: "Collection Amount",
			titleFontColor: "#d6ea0a",
			lineColor: "#d6ea0a",
			labelFontColor: "#d6ea0a",
			tickColor: "#d6ea0a"
		},
		
		toolTip: {
			shared: true
		},
		legend: {
			cursor:"pointer",
			itemclick: toggleDataSeries
		},
		data: [{
			type: "spline",
			name: "Previous Year Collection",
			showInLegend: true, 
			dataPoints:crntPoints
			
				
		},
		{
			type: "spline",	
			name: "Current Year Collection",
			
			showInLegend: true,
			dataPoints:prvPoints
			
			
		}]
	});
	
	var mnt_name = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
	
	for (var i = 0; i < JsonData[0].length; i++) {
		var s = (2-i);
		crntPoints.push({
			label:	mnt_name[m],
			y: JsonData[0][s]
		});
		m++;
		
	}
	
	for (var i = 0; i < JsonData[1].length; i++) {
		var s = (2-i);
		prvPoints.push({
			label:	name[i],
			y: JsonData[1][s]
		});
	}
	
	cmpr.render();
	function toggleDataSeries(e) {
		if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		}
		else {
			e.dataSeries.visible = true;
		}
		cmpr.render();
	}
}


window.onload = function () {
propertyData();
}
</script>



<script>

function formatAMPM() {
    var d = new Date(),
    seconds = d.getSeconds().toString().length == 1 ? '0'+d.getSeconds() : d.getSeconds(),
    minutes = d.getMinutes().toString().length == 1 ? '0'+d.getMinutes() : d.getMinutes(),
    hours = d.getHours().toString().length == 1 ? '0'+d.getHours() : d.getHours(),
    ampm = d.getHours() >= 12 ? 'PM' : 'AM',
    months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    return days[d.getDay()]+' '+months[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear()+' '+hours+':'+minutes+':'+seconds+' '+ampm;
}
document.getElementById("front_show_date_time").innerHTML = formatAMPM();
window.setInterval(function(){ document.getElementById("front_show_date_time").innerHTML = formatAMPM(); }, 1000);
</script>


<script>
	 $("#container").removeClass("effect aside-float aside-bright mainnav-lg");
    $("#container").addClass("effect aside-float aside-bright mainnav-sm");
</script>
<script src="<?=base_url();?>/public/assets/js/loader.js"></script>


<script type="text/javascript">
     
		function waterdrawChart(JsonData) {
			var dataPoints = [];
			var trade_month = new CanvasJS.Chart("watermnthcoll", {
				animationEnabled: true,
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				title: {
					text: "Monthly Collection"
				},
				axisY: {
					title: "Total Collected Amount"
				},
				data: [{
					type: "column",
					dataPoints: dataPoints
				}]
			});
			var monthArrs = ['April', 'May', 'June','July', 'August', 'September','October', 'November', 'December','January', 'February', 'March'];
			for (var i = 0; i < JsonData.length; i++) {
				//console.log(JsonData[i]);
				//alert(JsonData[i]);
				dataPoints.push({
					label: monthArrs[i],
					y: JsonData[i]
				});
			}
		trade_month.render();
		}
		
		
		function waterChartdyList(JsonData) {
			var dataPoints = [];
			var trddailycoll = new CanvasJS.Chart("watermnthdaycoll", {
				animationEnabled: true,
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				title:{
					text: "Daily Collection"
				},
				axisY: {
					title: "Total Collected Amount",
				},
				data: [{
					type: "splineArea",
					color: "#c5721b",
					dataPoints: dataPoints
				}]
			});
			var dyArr = ['01', '02', '03','04', '05', '06','07', '08', '09','10', '11', '12','13', '14', '15',
			'16', '17', '18','19', '20', '21','22', '23', '24','25', '26', '27','28', '29', '30', '31'];
			for (var i = 0; i < JsonData.length; i++) {
				dataPoints.push({
					label: dyArr[i],
					y: JsonData[i]
				});
			}
		trddailycoll.render();
		}
		
		
		function wtrChartdcbList(JsonData) {
			var dataPoints = [];
			var wtryy = new CanvasJS.Chart("wtrdmndColctns", {
				theme: "dark2",
				title: {
					text: "Demand Collection Balance"
				},
				
				data: [{
					type: "pie",
					indexLabel: "{y}",
					indexLabelPlacement: "inside",
					indexLabelFontColor: "#d6ea0a",
					indexLabelFontSize: 14,
					indexLabelFontWeight: "bolder",
					showInLegend: true,
					legendText: "{label}",
					dataPoints: dataPoints
				}]
			});
			var name = ['Collection Amount', 'Balance Amount'];
			for (var i = 0; i < JsonData.length; i++) {
				dataPoints.push({
					label:	name[i],
					y: JsonData[i]
				});
			}
			wtryy.render();

		}
		
		function waterChartcmprList(JsonData) {
			var d = new Date();
			var n = d.getMonth();
			var crntPoints = [];
			var prvPoints = [];
			var m = n-2;
			var cmpr = new CanvasJS.Chart("watermnthcollcmpr", {
				theme: "dark2",
				title: {
					text: "Compare Months Collection From Previous Year"
				},
				axisY: {
					title: "Collection Amount",
					titleFontColor: "#d6ea0a",
					lineColor: "#d6ea0a",
					labelFontColor: "#d6ea0a",
					tickColor: "#d6ea0a"
				},
				
				toolTip: {
					shared: true
				},
				legend: {
					cursor:"pointer",
					itemclick: toggleDataSeries
				},
				data: [{
					type: "spline",
					name: "Previous Year Collection",
					showInLegend: true, 
					dataPoints:crntPoints
					
						
				},
				{
					type: "spline",	
					name: "Current Year Collection",
					
					showInLegend: true,
					dataPoints:prvPoints
					
					
				}]
			});
			
			var mnt_name = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
			for (var i = 0; i < JsonData[0].length; i++) {
				var s = (2-i);
				crntPoints.push({
					label:	mnt_name[m],
					y: JsonData[0][s]
				});
				m++;
				
			}
			
			for (var i = 0; i < JsonData[1].length; i++) {
				var s = (2-i);
				prvPoints.push({
					label:	name[i],
					y: JsonData[1][s]
				});
			}
			
			cmpr.render();
			function toggleDataSeries(e) {
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				}
				else {
					e.dataSeries.visible = true;
				}
				cmpr.render();
			}
		}
		
	waterData();
    </script>
	
	
	
	<script type="text/javascript">
     
		function tradedrawChart(JsonData) {
			var dataPoints = [];
			var trade_month = new CanvasJS.Chart("tradeCollection", {
				animationEnabled: true,
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				title: {
					text: "Monthly Collection"
				},
				axisY: {
					title: "Total Collected Amount"
				},
				data: [{
					type: "column",
					dataPoints: dataPoints
				}]
			});
			var monthArrs = ['April', 'May', 'June','July', 'August', 'September','October', 'November', 'December','January', 'February', 'March'];
			for (var i = 0; i < JsonData.length; i++) {
				//console.log(JsonData[i]);
				//alert(JsonData[i]);
				dataPoints.push({
					label: monthArrs[i],
					y: JsonData[i]
				});
			}
		trade_month.render();
		}
		
		
		function trdChartdyList(JsonData) {
			var dataPoints = [];
			var trddailycoll = new CanvasJS.Chart("trademnthdayCollection", {
				animationEnabled: true,
				theme: "dark2", // "light1", "light2", "dark1", "dark2"
				title:{
					text: "Daily Collection"
				},
				axisY: {
					title: "Total Collected Amount",
				},
				data: [{
					type: "splineArea",
					color: "#c5721b",
					dataPoints: dataPoints
				}]
			});
			var dyArr = ['01', '02', '03','04', '05', '06','07', '08', '09','10', '11', '12','13', '14', '15',
			'16', '17', '18','19', '20', '21','22', '23', '24','25', '26', '27','28', '29', '30', '31'];
			for (var i = 0; i < JsonData.length; i++) {
				dataPoints.push({
					label: dyArr[i],
					y: JsonData[i]
				});
			}
		trddailycoll.render();
		}
		
		
		function trdChartcmprList(JsonData) {
			var d = new Date();
			var n = d.getMonth();
			var crntPoints = [];
			var prvPoints = [];
			var m = n-2;
			var cmpr = new CanvasJS.Chart("trdmnthcollcompare", {
				theme: "dark2",
				title: {
					text: "Compare Months Collection From Previous Year"
				},
				axisY: {
					title: "Collection Amount",
					titleFontColor: "#d6ea0a",
					lineColor: "#d6ea0a",
					labelFontColor: "#d6ea0a",
					tickColor: "#d6ea0a"
				},
				
				toolTip: {
					shared: true
				},
				legend: {
					cursor:"pointer",
					itemclick: toggleDataSeries
				},
				data: [{
					type: "spline",
					name: "Previous Year Collection",
					showInLegend: true, 
					dataPoints:crntPoints
					
						
				},
				{
					type: "spline",	
					name: "Current Year Collection",
					
					showInLegend: true,
					dataPoints:prvPoints
					
					
				}]
			});
			
			var mnt_name = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
			for (var i = 0; i < JsonData[0].length; i++) {
				var s = (2-i);
				crntPoints.push({
					label:	mnt_name[m],
					y: JsonData[0][s]
				});
				m++;
				
			}
			
			for (var i = 0; i < JsonData[1].length; i++) {
				var s = (2-i);
				prvPoints.push({
					label:	name[i],
					y: JsonData[1][s]
				});
			}
			
			cmpr.render();
			function toggleDataSeries(e) {
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				}
				else {
					e.dataSeries.visible = true;
				}
				cmpr.render();
			}
		}
		
	tradeData();
    </script>
	
	
	

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap" async defer></script>
 
<script>
function propertyData(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard/propertyAjax'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#amntval").css('opacity', '0.3');
			$("#loadingDivs").show();
		},
		success: function(data){
			$("#loadingDivs").hide();
			$("#amntval").css('opacity', '1');
			if(data.response==true){
				//alert(data.fycollection);
				$("#crrntHousehold").val(data.currentHouseHold);
				$("#crrntdemand").val(data.currentDemand);
				$("#arearHousehold").val(data.arearhouseHold);
				$("#areardemand").val(data.areardemand);
				$("#currentcolHousehold").val(data.currentcolhouseHold);
				$("#currentcolamount").val(data.currentcolamount);
				$("#arearcolHousehold").val(data.arearcolhouseHold);
				$("#arrearcolamount").val(data.arearcolamount);
				$("#dailycolamount").val(data.dailycollection);
				$("#legacy").val(data.legac);
				$("#fieldverification").val(data.fieldverification);
				$("#yrsaf").val(data.yearsaf);
				$("#mnthsaf").val(data.monthsaf);
				//$("#daysaf").val(data.daysaf);
				$("#newsaf").val(data.newassessmentno);
				$("#resaf").val(data.reassessmentno);
				$("#mutsaf").val(data.muttationno);
				$("#sam").val(data.reassessmentno);
				$("#fam").val(data.fam);	
				
				fycollection();
			}
		}
	});
}


function fycollection(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard/fycollecAjax'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#chartContainer").css('opacity', '0.3');
			$("#mnthcoll").show();
		},
		success: function(data){
			$("#mnthcoll").hide();
			$("#chartContainer").css('opacity', '1');
			if(data.response==true){
				CanvasCharList(data.fycollection);
			}
			dcb();
		}
	});
}


function dycollection(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard/dycollecAjax'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#fieldFinal").css('opacity', '0.3');
			$("#dycollload").show();
		},
		success: function(data){
			$("#dycollload").hide();
			$("#fieldFinal").css('opacity', '1');
			if(data.response==true){
				CanvasChardyList(data.dycollection);
			}
		}
	});
}
function dcb(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard/dcbAjax'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#dmndColctns").css('opacity', '0.3');
			$("#dcbload").show();
		},
		success: function(data){
			$("#dcbload").hide();
			$("#dmndColctns").css('opacity', '1');
			if(data.response==true){
				CanvasChardcbList(data.dcb);
			}
			compare();
		}
	});
}
function compare(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard/compareAjax'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#collection").css('opacity', '0.3');
			$("#collload").show();
		},
		success: function(data){
			$("#collload").hide();
			$("#collection").css('opacity', '1');
			if(data.response==true){
				CanvasCharcmprList(data.compare);
			}
			dycollection();
		}
	});
}



function waterData(){
	var fy_mstr_id = $("#waterfy_mstr_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/Dashboard_water/ajax_gatewater'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id:fy_mstr_id
			},
			beforeSend: function() {
				$("#amntwtrval").css('opacity', '0.3');
				$("#loadwtrval").show();
			},
			success: function(data){
				$("#loadwtrval").hide();
				$("#amntwtrval").css('opacity', '1');
				console.log(data);
				if(data.response==true){
					//alert(data.consubln);
					$("#ConnCount").val(data.conncount);
					$("#Connamount").val(data.connamount);
					$("#Conscount").val(data.conscount);
					$("#Consamount").val(data.consamount);
					$("#newconn").val(data.newcon);
					$("#regular").val(data.regul);
					$("#consum").val(data.consumer);
					$("#current_demand").val(data.current_demand);
					$("#current_Collec").val(data.current_Collec);
					$("#arrear_demand").val(data.arrear_demand);
					$("#arrear_Collec").val(data.arrear_Collec);
					
				}
				watermnthCollData();
				
			},error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#loadwtrval").hide();
				alert("Status: " + textStatus); 
				alert("Error: " + errorThrown); 
			}  
			
		});
}

function waterdayCollData(){
	var fy_mstr_id = $("#waterfy_mstr_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/Dashboard_water/dy_collwater'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id:fy_mstr_id
			},
			beforeSend: function() {
				$("#watermnthdaycoll").css('opacity', '0.3');
				$("#loadwtrdy").show();
			},
			success: function(data){
				$("#loadwtrdy").hide();
				$("#watermnthdaycoll").css('opacity', '1');
				console.log(data);
				if(data.response==true){
					waterChartdyList(data.dayCollection);
				}
			},error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#loadwtrdy").hide();
				alert("Status: " + textStatus); 
				alert("Error: " + errorThrown); 
			}  
			
		});
}

function watermnthCollData(){
	var fy_mstr_id = $("#waterfy_mstr_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/Dashboard_water/fy_collwater'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id:fy_mstr_id
			},
			beforeSend: function() {
				$("#watermnthcoll").css('opacity', '0.3');
				$("#loadwtrmnth").show();
			},
			success: function(data){
				$("#loadwtrmnth").hide();
				$("#watermnthcoll").css('opacity', '1');
				console.log(data);
				if(data.response==true){
					waterdrawChart(data.mnthCollection);
				}
				waterdcbData();
				
			},error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#loadwtrmnth").hide();
				alert("Status: " + textStatus); 
				alert("Error: " + errorThrown); 
			}  
			
		});
}

function waterdcbData(){
	var fy_mstr_id = $("#waterfy_mstr_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/Dashboard_water/dcbwater'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id:fy_mstr_id
			},
			beforeSend: function() {
				$("#wtrdmndColctns").css('opacity', '0.3');
				$("#loadwtrdmnd").show();
			},
			success: function(data){
				$("#loadwtrdmnd").hide();
				$("#wtrdmndColctns").css('opacity', '1');
				console.log(data);
				if(data.response==true){
					wtrChartdcbList(data.wtrdcb);
				}
				watercompareData();
				
			},error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#loadwtrdmnd").hide();
				alert("Status: " + textStatus); 
				alert("Error: " + errorThrown); 
			}  
			
		});
}

function watercompareData(){
	var fy_mstr_id = $("#waterfy_mstr_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/Dashboard_water/cmprwater'); ?>",
			dataType:"json",
			data:
			{
				fy_mstr_id:fy_mstr_id
			},
			beforeSend: function() {
				$("#watermnthcollcmpr").css('opacity', '0.3');
				$("#loadwtrcmp").show();
			},
			success: function(data){
				$("#loadwtrcmp").hide();
				$("#watermnthcollcmpr").css('opacity', '1');
				console.log(data);
				if(data.response==true){
					waterChartcmprList(data.compare);
				}
				waterdayCollData();
				
			},error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#loadwtrcmp").hide();
				alert("Status: " + textStatus); 
				alert("Error: " + errorThrown); 
			}  
			
		});
}


function tradeData(){
	var fy_mstr_id = $("#trdfy_mstr_id").val();
	//alert(fy_mstr_id);
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard_trade/ajax_gatetrade'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#countvalue").css('opacity', '0.3');
			$("#loadtradecount").show();
		},
		success: function(data){
			$("#loadtradecount").hide();
			$("#countvalue").css('opacity', '1');
			//console.log(data);
			if(data.response==true){
				//alert(data.active);
				$("#active").val(data.active);
				$("#deactive").val(data.deactive);
				$("#newapp").text(data.newapp);
				$("#renewal").text(data.renewal);
				$("#amendment").text(data.amendment);
				$("#surrenderapp").text(data.surrenderapp);
				$("#newLicence").text(data.newLicence);
				$("#renewalLicence").text(data.renewalLicence);
				$("#amendmentLicence").text(data.amendmentLicence);
			}
			mnthColltradeData();
		}
	});
}

function mnthColltradeData(){
	var fy_mstr_id = $("#trdfy_mstr_id").val();
	//alert(fy_mstr_id);
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard_trade/fy_colltrade'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#tradeCollection").css('opacity', '0.3');
			$("#loadtradefy").show();
		},
		success: function(data){
			$("#loadtradefy").hide();
			$("#tradeCollection").css('opacity', '1');
			//console.log(data);
			if(data.response==true){
				tradedrawChart(data.mnthCollection);
			}
			comparetradeData();
		}
	});
}

function comparetradeData(){
	var fy_mstr_id = $("#trdfy_mstr_id").val();
	//alert(fy_mstr_id);
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard_trade/cmprtrade'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#trdmnthcollcompare").css('opacity', '0.3');
			$("#loadtradecmpr").show();
		},
		success: function(data){
			$("#loadtradecmpr").hide();
			$("#trdmnthcollcompare").css('opacity', '1');
			//console.log(data);
			if(data.response==true){
				trdChartcmprList(data.compare);   
			}
			dayColltradeData();
		}
	});
}

function dayColltradeData(){
	var fy_mstr_id = $("#trdfy_mstr_id").val();
	//alert(fy_mstr_id);
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/Dashboard_trade/dy_colltrade'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#trademnthdayCollection").css('opacity', '0.3');
			$("#loadtradedy").show();
		},
		success: function(data){
			$("#loadtradedy").hide();
			$("#trademnthdayCollection").css('opacity', '1');
			//console.log(data);
			if(data.response==true){
				trdChartdyList(data.dayCollection);
			}
		}
	});
}


</script>
