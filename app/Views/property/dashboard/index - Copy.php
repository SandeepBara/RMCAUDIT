
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url();?>/public/assets/plugins/morris-js/morris.min.css" rel="stylesheet">

<div id="page-head" style="background-color: #25476a; color: #fff; margin-top: 3%;">				
	<div class="pad-all text-center">
		<h3><?=$ulb_mstr["ulb_name"];?></h3>
	</div>
</div>
<!--Page content-->
<div id="page-content">
	<div class="panel" style="margin-left: 50px;">
		<div class="panel-heading">
			<h3 class="panel-title">
				<div class="col-md-8 col-xs-8">
					<div class="col-md-2 col-xs-2">
					<i class="fa fa-inbox"></i> Dashboard  
					</div>
					<div class="col-md-4 col-xs-4" id="front_show_date_time">Sun Mar 13 2022 12:36:48 PM</div>
					<div class="col-md-4 col-xs-4">
						<select onchange="refreshDashboard(this.value)" id="fyear">
							<option>2016-2017</option>
							<option>2017-2018</option>
							<option>2018-2019</option>
							<option>2019-2020</option>
							<option>2020-2021</option>
							<option>2021-2022</option>
							<option selected>2022-2023</option>
						</select>
					</div>
				</div>
				<ul class="nav nav-tabs pull-right">
					<li><a href="#advertisement" data-toggle="tab">Advertisement</a></li>
					<li id="trade_click_show"><a href="<?=base_url("NewDashboardTrade/index");?>" data-toggle="tab">Trade</a></li>
					<li id="water_click_show"><a href="#water" data-toggle="tab" onclick="waterData();">Water</a></li>
					<li class="prop_click_show active"><a href="#property" data-toggle="tab" onclick="propertyData();">Property</a></li>
				</ul>
			</h3>
		</div>
	
		
		<div class="panel-body">
			
			
			<div class="row">
				<div class="col-md-3">
					<div class="panel panel-warning panel-colorful media middle pad-all">
						<div class="media-left">
							<div class="pad-hor">
								<i class="demo-pli-file-word icon-3x"></i>
							</div>
						</div>
						<div class="media-body">
							<p class="text-2x mar-no text-semibold" id="new_assessment"></p>
							<p class="mar-no">New Assessment</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-info panel-colorful media middle pad-all">
						<div class="media-left">
							<div class="pad-hor">
								<i class="demo-pli-file-zip icon-3x"></i>
							</div>
						</div>
						<div class="media-body">
							<p class="text-2x mar-no text-semibold" id="reassessment"></p>
							<p class="mar-no">Re-Assessment</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-mint panel-colorful media middle pad-all">
						<div class="media-left">
							<div class="pad-hor">
								<i class="demo-pli-camera-2 icon-3x"></i>
							</div>
						</div>
						<div class="media-body">
							<p class="text-2x mar-no text-semibold" id="mutation"></p>
							<p class="mar-no">Mutation</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-danger panel-colorful media middle pad-all">
						<div class="media-left">
							<div class="pad-hor">
								<i class="demo-pli-video icon-3x"></i>
							</div>
						</div>
						<div class="media-body">
							<p class="text-2x mar-no text-semibold" id="legacy"></p>
							<p class="mar-no">Unverified Legacy</p>
						</div>
					</div>
				</div>
		
			</div>
			<div class="row">
				<div class="col-md-4">
					<p class="text-uppercase text-semibold text-main">Daily Collection</p>
					<ul class="list-unstyled">
						<li>
							<div class="media pad-btm">
								<div class="media-left">
									<span class="text-2x text-thin text-main" id="today_collection"></span>
								</div>
								<div class="media-body">
									<p class="mar-no">INR</p>
								</div>
							</div>
						</li>
						<li class="pad-btm">
							<div class="clearfix">
								<p class="pull-left mar-no">Last 7 day</p>
								<p class="pull-right mar-no" id="last7day_collection"></p>
							</div>
							<div class="progress progress-sm">
								<div class="progress-bar progress-bar-info" style="width: 70%;">
									<span class="sr-only">70% Complete</span>
								</div>
							</div>
						</li>
						<li>
							<div class="clearfix">
								<p class="pull-left mar-no">This Month</p>
								<p class="pull-right mar-no" id="thismonth_collection"></p>
							</div>
							<div class="progress progress-sm">
								<div class="progress-bar progress-bar-primary" style="width: 10%;">
									<span class="sr-only">10% Complete</span>
								</div>
							</div>
						</li>
					</ul>
						<div class="col-sm-4 pad-top">
							<div class="text-lg">
								<p class="text-lg text-thin text-main" id="no_of_fam_generated"></p>
							</div>
							<p class="text-sm text-bold text-uppercase">Final Approval</p>
						</div>
						<div class="col-sm-8">
							<button class="btn btn-pink mar-ver">View Details</button>
							<p class="text-xs">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
							<ul class="list-unstyled text-center bord-top pad-top mar-no row">
								<li class="col-xs-4">
									<span class="text-lg text-semibold text-main" id="no_of_sam_generated"></span>
									<p class="text-sm text-muted mar-no">SAM</p>
								</li>
								<li class="col-xs-4">
									<span class="text-lg text-semibold text-main" id="no_of_verification"></span>
									<p class="text-sm text-muted mar-no">Verification</p>
								</li>
								<li class="col-xs-4">
									<span class="text-lg text-semibold text-main" id="no_of_geo_tagging"></span>
									<p class="text-sm text-muted mar-no">Geo Tagging</p>
								</li>
							</ul>
						</div>
				</div>
				<div class="col-md-4"><div id="donutchart1" style=" height: 400px;"></div></div>
				<div class="col-md-4"><div id="donutchart2" style=" height: 400px;"></div></div>
			</div>
		</div>
	</div>

	<div class="panel" style="margin-left: 50px;">
		<div class="panel-heading">
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div id="curve_chart1"></div>
				</div>
				<div class="col-md-12">
					<div id="curve_chart2"></div>
				</div>
			</div>
		</div>
	</div>
</div>
                <!--===================================================-->
                <!--End page content-->

<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    
<script>
// Collapse Menu Automatically
$("#container").removeClass("effect aside-float aside-bright mainnav-lg");
$("#container").addClass("effect aside-float aside-bright mainnav-sm");

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
$("front_show_date_time").html(formatAMPM());
window.setInterval(function(){ document.getElementById("front_show_date_time").innerHTML = formatAMPM(); }, 1000)
</script>

<script type="text/javascript">
	var monthly_data=[];
	var fyear = $("#fyear").val();

	function LoadMonth(){

		var fyear = $("#fyear").val();
		google.charts.load('current', {
			'language': 'en',
			'visualization': 1,
			'packages':['line', 'corechart'],
			
		});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
			
			var data = new google.visualization.DataTable();
			data.addColumn('date', 'Month '+ fyear);
			data.addColumn('number', "No of Transaction");
			data.addColumn('number', "Collection Amount");
			
			//console.log(monthly_data);
			data.addRows(monthly_data);

			var materialOptions = {
				chart: {
					title: 'Monthly Collection Summary FY: ' + fyear,
					
				},
				// width: 1350,
				height: 300,
				series: {
					// Gives each series an axis name that matches the Y-axis below.
					0: {axis: 'Transaction'},
					1: {axis: 'Collection'}
				},
				axes: {
					// Adds labels to each axis; they don't have to match the axis names.
					y: {
						Temps: {label: 'No of Transaction'},
						Daylight: {label: 'Collection Amount'}
					}
				}
			};
			
			var materialChart = new google.charts.Line(document.getElementById('curve_chart2'));
			materialChart.draw(data, materialOptions);
		}
	}
	
    </script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>

	function setDataIntoHtml(obj, monthly_collection)
	{
		var fyear = $("#fyear").val();
		$("#new_assessment").html(obj.new_assessment);
		$("#reassessment").html(obj.reassessment);
		$("#mutation").html(obj.mutation);
		$("#legacy").html(obj.legacy);

		$("#no_of_fam_generated").html(obj.no_of_fam_generated);
		$("#no_of_geo_tagging").html(obj.no_of_geo_tagging);
		$("#no_of_verification").html(obj.no_of_verification);
		$("#no_of_sam_generated").html(obj.no_of_sam_generated);

		$("#today_collection").html(obj.today_collection);
		$("#last7day_collection").html(obj.last7day_collection);
		$("#thismonth_collection").html(obj.thismonth_collection);
		
		
		
		
		// donutchart1
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart1(obj));
		function drawChart1(obj) {
			var data = google.visualization.arrayToDataTable([
				['DCB', 'In INR'],
				['Demand', Math.abs(obj.current_demand)],
				['Collection', Math.abs(obj.current_collection)],
				['Dues', Math.abs(obj.current_demand-obj.current_collection)]
			]);

			var options = {
				title: 'FY : ' + fyear + '- Total Properties: '+ obj.total_property,
				pieHole: 0.4,
				//is3D: true,
			};
			var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));chart.draw(data, options);
		}

		// donutchart2
		google.charts.load("current", {packages:["corechart"]});
		google.charts.setOnLoadCallback(drawChart2(obj));
		function drawChart2(obj) {
			var data = google.visualization.arrayToDataTable([
				['DCB', 'In INR'],
				['Demand', Math.abs(parseFloat(obj.current_demand) + parseFloat(obj.arrear_demand))],
				['Collection', Math.abs(parseFloat(obj.current_collection)+parseFloat(obj.arrear_collection))],
				['Dues', Math.abs((parseFloat(obj.current_demand) + parseFloat(obj.arrear_demand))-(parseFloat(obj.current_collection)+parseFloat(obj.arrear_collection)))]
			]);

			var options = {
				title: 'Property DCB',
				pieHole: 0.4,
			};
			var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
			chart.draw(data, options);
		}

		//curve chart
		
		//monthly_data = [['Month', 'No Of Trxn', 'Collection (in INR)']];
		monthly_data = [];
		for (let x of monthly_collection) {
			
			monthly_data.push([
				new Date(Date.parse(x.month)), parseFloat(x.no_of_trxn), parseFloat(x.total_amount)
			]);
		}
		LoadMonth();
	}
	LoadMonth();
	function refreshDashboard(fy)
	{
		$.ajax({
            type:"POST",
            url: '<?=base_url();?>/NewDashboard/getSummaryData',
            dataType: "json",
			timeout: 5000, // ten seconds in milliseconds
            data: {
                    "fy": fy,
            },
            beforeSend: function() {
				// do nothing
				$("#loadingDiv").show();
            },
            success:function(response){
				$("#loadingDiv").hide();
				if(response.status == true){

					var data = response.data;
					console.log(response);
					var monthly_collection = response.monthly_collection;
					setDataIntoHtml(data, monthly_collection);
					
				}
                else{
					alert(response.message);
				}
            },
			error: function(xhr) {
				// if error occured
				$("#loadingDiv").hide();
				alert("Error occured.please try again");
			},
			complete: function() {
				// do nothing
				$("#loadingDiv").hide();
			},
        });
	}
	refreshDashboard($("#fyear").val());
	
</script>