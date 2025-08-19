<?= $this->include('layout_vertical/header');?>
<?php
$day_date = date('d');
	$month_date = date('m');
	$month_date_view = $month_date-1;
	$year_date = date('Y');
	$start_date = $year_date.'-'.$month_date.'-1';
	$end_date = $year_date.'-'.$month_date.'-'.$day_date;
?>
<style>
img{
	height:200px;
	width:200px;
}
</style>
		<div id="container">
			<div id="page-head">
				<div id="page-title">
					<ul class="nav nav-tabs pull-right">
					  <li id="advertisement_click_show"><a href="#advertisement" data-toggle="tab">Advertisement</a></li>
					  <li id="trade_click_show"><a href="#trade" data-toggle="tab">Trade</a></li>
					  <li id="water_click_show"><a href="#water" data-toggle="tab">Water</a></li>
					  <li class="active"><a href="#property" id="dash_prop" data-toggle="tab">Property</a></li>
					  <li class="pull-left header"><i class="fa fa-inbox"></i> Dashboard</li>
					</ul>
				</div><!--End page title-->
			</div>
            <!-- Tabs within a box -->
            
            <div class="tab-content no-padding" style="background-color:#CCCCCC;">
              <!-- Morris chart - Sales -->
			  <div class="chart tab-pane" id="advertisement" style="position: relative;">

				<div class="row">
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?=$total_user_rows;?></h3>
			
								<p>Total User</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?=$total_demand;?></h3>
			
								<p>Total Demand</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?=$total_collection;?></h3>
			
								<p>Total Collection</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?=$total_dues;?></h3>
			
								<p>Total Dues Amount</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
<!-- Advertisement Total Collection -->			  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="advertisement_total_collection_donut_chart_click"><a href="#advertisement_total_collection_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li class="active"><a href="#advertisement_total_collection_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Collection</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="advertisement_total_collection_donut_chart" style="position: relative;">
									<div id="advertisement_total_collection_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="advertisement_total_collection_pie_chart" style="position: relative;">
									<div id="advertisement_total_collection_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>		
								</div>
							</div>
						</div>
					</section>
<!-- // Advertisement Total Collection -->		
<!-- Advertisement Total Dues -->			  	  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="advertisement_total_dues_donut_chart_click"><a href="#advertisement_total_dues_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li id="advertisement_total_dues_pie_chart_click" class="active"><a href="#advertisement_total_dues_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Dues</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="advertisement_total_dues_donut_chart" style="position: relative;">
										<div id="advertisement_total_dues_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="advertisement_total_dues_pie_chart" style="position: relative;">
									   <div id="advertisement_total_dues_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>					
								</div>
							</div>
						</div>
					</section>
<!-- // Advertisement Total Dues -->
<!-- Advertisement Total Dues -->			  	  
					<section class="col-lg-12 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="advertisement_total_collection_grant_chart_click"><a href="#advertisement_total_collection_grant_chart" data-toggle="tab">basic_line_chart</a></li>
								<li class="active"><a href="#advertisement_total_collection_line_chart" id="dash_prop" data-toggle="tab">line_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i><?=date('d-m-Y',strtotime($start_date));?> <b>TO</b> <?=date('d-m-Y',strtotime($end_date));?> (Daily wise Collection)</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="advertisement_total_collection_grant_chart" style="position: relative;">
										<div id="advertisement_daily_collection_basic_line_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="advertisement_total_collection_line_chart" style="position: relative;">
									   <div id="advertisement_daily_collection_line_chart"><img src="../comman/img/locate-loader.gif" /></div>				
								</div>
							</div>
						</div>
					</section>
<!-- // Advertisement Total Dues -->
              </div>
			  <div class="chart tab-pane" id="trade" style="position: relative;">
 
				<div class="row">
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?=$total_user_rows_t;?></h3>
			
								<p>Total User</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?=$total_demand_t;?></h3>
			
								<p>Total Demand</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?=$total_collection_t;?></h3>
			
								<p>Total Collection</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?=$total_dues_t;?></h3>
			
								<p>Total Dues Amount</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
<!-- Trade Total Collection -->			  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="trade_total_collection_donut_chart_click"><a href="#trade_total_collection_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li class="active"><a href="#trade_total_collection_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Collection</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="trade_total_collection_donut_chart" style="position: relative;">
									<div id="trade_total_collection_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="trade_total_collection_pie_chart" style="position: relative;">
									<div id="trade_total_collection_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>		
								</div>
							</div>
						</div>
					</section>
<!-- // trade Total Collection -->		
<!-- Trade Total Dues -->			  	  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="trade_total_dues_donut_chart_click"><a href="#trade_total_dues_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li id="trade_total_dues_pie_chart_click" class="active"><a href="#trade_total_dues_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Dues</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="trade_total_dues_donut_chart" style="position: relative;">
										<div id="trade_total_dues_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="trade_total_dues_pie_chart" style="position: relative;">
									   <div id="trade_total_dues_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>					
								</div>
							</div>
						</div>
					</section>
<!-- // Trade Total Dues -->
<!-- trade Total Dues -->			  	  
					<section class="col-lg-12 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="trade_total_collection_grant_chart_click"><a href="#trade_total_collection_grant_chart" data-toggle="tab">basic_line_chart</a></li>
								<li class="active"><a href="#trade_total_collection_line_chart" id="dash_prop" data-toggle="tab">line_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i><?=date('d-m-Y',strtotime($start_date));?> <b>TO</b> <?=date('d-m-Y',strtotime($end_date));?> (Daily wise Collection)</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="trade_total_collection_grant_chart" style="position: relative;">
										<div id="trade_daily_collection_basic_line_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="trade_total_collection_line_chart" style="position: relative;">
									   <div id="trade_daily_collection_line_chart"><img src="../comman/img/locate-loader.gif" /></div>				
								</div>
							</div>
						</div>
					</section>
<!-- // Trade Total Dues -->
              </div>
			  <div class="chart tab-pane" id="water" style="position: relative;">

				<div class="row">
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?=$total_user_rows_w;?></h3>
			
								<p>Total User</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?=$total_demand_w;?></h3>
			
								<p>Total Demand</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?=$total_collection_w;?></h3>
			
								<p>Total Collection</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?=$total_dues_w;?></h3>
			
								<p>Total Dues Amount</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
<!-- water Total Collection -->			  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="water_total_collection_donut_chart_click"><a href="#pro_total_collection_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li class="active"><a href="#water_total_collection_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Collection</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="water_total_collection_donut_chart" style="position: relative;">
									<div id="water_total_collection_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="water_total_collection_pie_chart" style="position: relative;">
									<div id="water_total_collection_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>		
								</div>
							</div>
						</div>
					</section>
<!-- // water Total Collection -->		
<!-- water Total Dues -->			  	  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="water_total_dues_donut_chart_click"><a href="#water_total_dues_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li id="water_total_dues_pie_chart_click" class="active"><a href="#water_total_dues_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Dues</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="water_total_dues_donut_chart" style="position: relative;">
										<div id="water_total_dues_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="water_total_dues_pie_chart" style="position: relative;">
									   <div id="water_total_dues_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>					
								</div>
							</div>
						</div>
					</section>
<!-- // water Total Dues -->
<!-- water Total Dues -->			  	  
					<section class="col-lg-12 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="water_total_collection_grant_chart_click"><a href="#water_total_collection_grant_chart" data-toggle="tab">basic_line_chart</a></li>
								<li class="active"><a href="#water_total_collection_line_chart" id="dash_prop" data-toggle="tab">line_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i><?=date('d-m-Y',strtotime($start_date));?> <b>TO</b> <?=date('d-m-Y',strtotime($end_date));?> (Daily wise Collection)</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="water_total_collection_grant_chart" style="position: relative;">
										<div id="water_daily_collection_basic_line_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="water_total_collection_line_chart" style="position: relative;">
									   <div id="water_daily_collection_line_chart"><img src="../comman/img/locate-loader.gif" /></div>				
								</div>
							</div>
						</div>
					</section>
<!-- // Water Total Dues -->
              </div>
<!-- // Water dashboard End -->
              <div class="chart tab-pane active" id="property" style="position: relative;">

				<div class="row">
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?=$total_user_rows;?></h3>
			
								<p>Total User</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?=$total_demand;?></h3>
			
								<p>Total Demand</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?=$total_collection;?></h3>
			
								<p>Total Collection</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?=$total_dues;?></h3>
			
								<p>Total Dues Amount</p>
							</div>
							<div class="icon">
								<i class="fa fa-inbox"></i>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
<!-- property Total Collection -->			  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="pro_total_collection_donut_chart_click"><a href="#pro_total_collection_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li class="active"><a href="#pro_total_collection_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Collection</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="pro_total_collection_donut_chart" style="position: relative;">
									<div id="property_total_collection_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="pro_total_collection_pie_chart" style="position: relative;">
									<div id="property_total_collection_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>		
								</div>
							</div>
						</div>
					</section>
<!-- // property Total Collection -->		
<!-- property Total Dues -->			  	  
					<section class="col-lg-6 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="pro_total_dues_donut_chart_click"><a href="#pro_total_dues_donut_chart" data-toggle="tab">donut_chart</a></li>
								<li id="pro_total_dues_pie_chart_click" class="active"><a href="#pro_total_dues_pie_chart" id="dash_prop" data-toggle="tab">pie_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i> Total Dues</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="pro_total_dues_donut_chart" style="position: relative;">
										<div id="property_total_dues_donut_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="pro_total_dues_pie_chart" style="position: relative;">
									   <div id="property_total_dues_pie_chart"><img src="../comman/img/locate-loader.gif" /></div>					
								</div>
							</div>
						</div>
					</section>
<!-- // property Total Dues -->
<!-- property Total Dues -->			  	  
					<section class="col-lg-12 col-xs-12 connectedSortable">
						<div class="nav-tabs-custom">
							<!-- Tabs within a box -->
							<ul class="nav nav-tabs pull-right">
								<li id="pro_total_collection_grant_chart_click"><a href="#pro_total_collection_grant_chart" data-toggle="tab">basic_line_chart</a></li>
								<li class="active"><a href="#pro_total_collection_line_chart" id="dash_prop" data-toggle="tab">line_chart</a></li>
								<li class="pull-left header"><i class="fa fa-inbox"></i><?=date('d-m-Y',strtotime($start_date));?> <b>TO</b> <?=date('d-m-Y',strtotime($end_date));?> (Daily wise Collection)</li>
							</ul>
							<div class="tab-content no-padding">
								<div class="chart tab-pane" id="pro_total_collection_grant_chart" style="position: relative;">
										<div id="property_daily_collection_basic_line_chart"><img src="../comman/img/locate-loader.gif" /></div>
								</div>
								<div class="chart tab-pane active" id="pro_total_collection_line_chart" style="position: relative;">
									   <div id="property_daily_collection_line_chart"><img src="../comman/img/locate-loader.gif" /></div>				
								</div>
							</div>
						</div>
					</section>
<!-- // property Total Dues -->
              </div>
<!-- // property dashboard End -->
            </div>
         	</div>
		</section>
	</div>
			<!-- /.post -->
</section>
  <!-- /.content-wrapper -->
  
 <script>
 $(document).ready(function() {
	var property = 'property';
// property Total Collection Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/property_total_collection_pie_chart.php",
		data: "property="+property,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#property_total_collection_pie_chart").html(response);
			}
		});
// // property Total Collection Chart End
// property Total Dues Chart Start	
		$.ajax({
		type: "POST",
		url: "support_dashboard/property_total_dues_pie_chart.php",
		data: "property="+property,
		cache: false,
		success: function(response)
			{
				$("#property_total_dues_pie_chart").html(response);
			}
		});
// property Total Dues Chart End
// property Total Collection line Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/property_daily_collection_line_chart.php",
		data: "property="+property,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#property_daily_collection_line_chart").html(response);
			}
		});	
// property Total Collection line Chart End	 
});
// property Click Start
$( "#pro_total_collection_donut_chart_click" ).click(function() {
	var property = 'property';
	$.ajax({
		type: "POST",
		url: "support_dashboard/property_total_collection_donut_chart.php",
		data: "property="+property,
		cache: false,
		success: function(response)
			{
				$("#property_total_collection_donut_chart").html(response);
			}
	});	
});
$( "#pro_total_dues_donut_chart_click" ).click(function() {
	var property = 'property';
	$.ajax({
		type: "POST",
		url: "support_dashboard/property_total_dues_donut_chart.php",
		data: "property="+property,
		cache: false,
		success: function(response)
			{
				$("#property_total_dues_donut_chart").html(response);
			}
	});		
});
$( "#pro_total_collection_grant_chart_click" ).click(function() {
	var property = 'property';
	$.ajax({
		type: "POST",
		url: "support_dashboard/property_daily_collection_basic_line_chart.php",
		data: "property="+property,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#property_daily_collection_basic_line_chart").html(response);
			}
	});			
});
// property Click End
// Water Click Start
$( "#water_click_show" ).click(function() {
	var water = 'water';
// water Total Collection Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/water_total_collection_pie_chart.php",
		data: "water="+water,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#water_total_collection_pie_chart").html(response);
			}
		});
// // water Total Collection Chart End
// water Total Dues Chart Start	
		$.ajax({
		type: "POST",
		url: "support_dashboard/water_total_dues_pie_chart.php",
		data: "water="+water,
		cache: false,
		success: function(response)
			{
				$("#water_total_dues_pie_chart").html(response);
			}
		});
// water Total Dues Chart End
// water Total Collection line Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/water_daily_collection_line_chart.php",
		data: "water="+water,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#water_daily_collection_line_chart").html(response);
			}
		});	
// water Total Collection line Chart End	 
});
$( "#water_total_collection_donut_chart_click" ).click(function() {
	var water = 'water';
	$.ajax({
		type: "POST",
		url: "support_dashboard/water_total_collection_donut_chart.php",
		data: "water="+water,
		cache: false,
		success: function(response)
			{
				$("#water_total_collection_donut_chart").html(response);
			}
	});	
});
$( "#water_total_dues_donut_chart_click" ).click(function() {
	var water = 'water';
	$.ajax({
		type: "POST",
		url: "support_dashboard/water_total_dues_donut_chart.php",
		data: "water="+water,
		cache: false,
		success: function(response)
			{
				$("#water_total_dues_donut_chart").html(response);
			}
	});		
});
$( "#water_total_collection_grant_chart_click" ).click(function() {
	var water = 'water';
	$.ajax({
		type: "POST",
		url: "support_dashboard/water_daily_collection_basic_line_chart.php",
		data: "water="+water,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#water_daily_collection_basic_line_chart").html(response);
			}
	});			
});
// Water Click End
// Trade Click Start
$( "#trade_click_show" ).click(function() {
	var trade = 'trade';
// Trade Total Collection Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/trade_total_collection_pie_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#trade_total_collection_pie_chart").html(response);
			}
		});
// // Trade Total Collection Chart End
// Trade Total Dues Chart Start	
		$.ajax({
		type: "POST",
		url: "support_dashboard/trade_total_dues_pie_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
				$("#trade_total_dues_pie_chart").html(response);
			}
		});
// Trade Total Dues Chart End
// Trade Total Collection line Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/trade_daily_collection_line_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#trade_daily_collection_line_chart").html(response);
			}
		});	
// Trade Total Collection line Chart End	 
});
$( "#trade_total_collection_donut_chart_click" ).click(function() {
	var trade = 'trade';
	$.ajax({
		type: "POST",
		url: "support_dashboard/trade_total_collection_donut_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
				$("#trade_total_collection_donut_chart").html(response);
			}
	});	
});
$( "#trade_total_dues_donut_chart_click" ).click(function() {
	var trade = 'trade';
	$.ajax({
		type: "POST",
		url: "support_dashboard/trade_total_dues_donut_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
				$("#trade_total_dues_donut_chart").html(response);
			}
	});		
});
$( "#trade_total_collection_grant_chart_click" ).click(function() {
	var trade = 'trade';
	$.ajax({
		type: "POST",
		url: "support_dashboard/trade_daily_collection_basic_line_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#trade_daily_collection_basic_line_chart").html(response);
			}
	});			
});
// Trade Click End
// Advertisement Click Start
$( "#advertisement_click_show" ).click(function() {
	var advertisement = 'advertisement';
// Advertisement Total Collection Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/advertisement_total_collection_pie_chart.php",
		data: "advertisement="+advertisement,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#advertisement_total_collection_pie_chart").html(response);
			}
		});
// // Advertisement Total Collection Chart End
// Advertisement Total Dues Chart Start	
		$.ajax({
		type: "POST",
		url: "support_dashboard/advertisement_total_dues_pie_chart.php",
		data: "advertisement="+advertisement,
		cache: false,
		success: function(response)
			{
				$("#advertisement_total_dues_pie_chart").html(response);
			}
		});
// Advertisement Total Dues Chart End
// Advertisement Total Collection line Chart Start
		$.ajax({
		type: "POST",
		url: "support_dashboard/advertisement_daily_collection_line_chart.php",
		data: "trade="+trade,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#advertisement_daily_collection_line_chart").html(response);
			}
		});	
// Advertisement Total Collection line Chart End	 
});
$( "#advertisement_total_collection_donut_chart_click" ).click(function() {
	var advertisement = 'advertisement';
	$.ajax({
		type: "POST",
		url: "support_dashboard/advertisement_total_collection_donut_chart.php",
		data: "advertisement="+advertisement,
		cache: false,
		success: function(response)
			{
				$("#advertisement_total_collection_donut_chart").html(response);
			}
	});	
});
$( "#advertisement_total_dues_donut_chart_click" ).click(function() {
	var advertisement = 'advertisement';
	$.ajax({
		type: "POST",
		url: "support_dashboard/advertisement_total_dues_donut_chart.php",
		data: "advertisement="+advertisement,
		cache: false,
		success: function(response)
			{
				$("#advertisement_total_dues_donut_chart").html(response);
			}
	});		
});
$( "#advertisement_total_collection_grant_chart_click" ).click(function() {
	var advertisement = 'advertisement';
	$.ajax({
		type: "POST",
		url: "support_dashboard/advertisement_daily_collection_basic_line_chart.php",
		data: "advertisement="+advertisement,
		cache: false,
		success: function(response)
			{
					//alert(response);return false;
				$("#advertisement_daily_collection_basic_line_chart").html(response);
			}
	});			
});
// Advertisement Click End
</script>
