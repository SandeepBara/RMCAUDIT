<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
		<div id="content-container">
			<div id="page-head">
				<div id="page-title">
					<h1 class="page-header text-overflow">DASHBOARD</h1>
				</div><!--End page title-->
			</div>
           
            <?php
            $session = session();
            //print_r($session->get('ulb_dtl'));
		
			
			if($fy_years): ?>
			<?php foreach($fy_years as $fy_years):
			$fy_year = $fy_years['fyid'];
			 ?>
			<?php endforeach; ?>
			<?php endif; ?>
			
			<?php
			if($holding_no):
			 $holding=0;?>
			<?php foreach($holding_no as $holding_no):
			//$hld_id = $holding_no[''];
			$holding++;
			 ?>
			<?php endforeach; ?>
			<?php endif; ?>
			
				<?php if($collection_dtl):
				 $collection=0;?>
				<?php foreach($collection_dtl as $collection_dtl):
					if($collection_dtl['prop_dtl_id']==2 && $collection_dtl['fy_mstr_id']== $fy_year && $collection_dtl['qtr']==4){
						$collection++;
					}
					
				 ?>
				<?php endforeach; ?>
				<?php $not_paymnt= $holding - $collection; ?>
				<?php endif; ?>
			
			
			<?php
			if($total_demand_amount):
			$total_demand = 0; $balance_demand=0; $total_current_demand=0; $total_arrear_demand=0;?>
			<?php foreach($total_demand_amount as $total_demand_amount):
			if($total_demand_amount['status']==1){
				$total_demand = $total_demand + $total_demand_amount['amount'];
			}
			if($total_demand_amount['status']==1 && $total_demand_amount['paid_status']==0){
				$balance_demand = $balance_demand + $total_demand_amount['balance'];
			}
			$total_collected = $total_demand - $balance_demand;
			if($total_demand_amount['fy_mstr_id']==$fy_year){
				$total_current_demand = $total_current_demand + $total_demand_amount['amount'];
			}else{
				$total_arrear_demand = $total_arrear_demand + $total_demand_amount['amount'];
			}
            ?>
			<?php endforeach; ?>
			<?php endif; ?>
			<?php
			if($total_collection_amount):
			$total_collection = 0; ?>
			<?php foreach($total_collection_amount as $total_collection_amount):
			if($total_collection_amount['status']==1){
				$total_collection = $total_collection + $total_collection_amount['payable_amt'];
			}
			?>
			<?php endforeach; ?>
			<?php endif; ?>
<!-- CONTENT CONTAINER-->


    <!--Page content-->
    <div id="page-content">
    	<div class="panel">
		    <div class="panel-heading">
		        <h3 class="panel-title">DASHBOARD</h3>
		    </div>
		    <div class="panel-body">
				<div class="row">
					    <div class="col-sm-6 col-md-4">
					        <div class="row">
					            <div class="col-md-12">
					
					                <div class="panel media  bg-danger pad-all">
					                    <div class="media-left ">
					                        <span class="icon-wrap icon-wrap-sm icon-circle">
					                        <i class="fa fa-suitcase fa-2x"></i>
					                        </span>
					                    </div>
					                    <div class="media-body">
					                        <p class="text-2x mar-no text-semibold"><?php echo $total_demand; ?></p>
					                        <p class="mar-no">Total Demands</p>
					                    </div>
					                </div>
					            </div>
					        </div>
					    </div>
						<div class="col-sm-6 col-md-4">
					        <div class="row">
					            <div class="col-sm-6 col-md-12">
					                <div class="panel media  bg-info pad-all">
										<div class="media-left ">
											<span class="icon-wrap icon-wrap-sm icon-circle">
					                        <i class="fa fa-shield fa-2x"></i>
					                        </span>
					                    </div>
					                    <div class="media-body">
											<p class="text-2x mar-no text-semibold text-danger"><?php echo $balance_demand; ?></p>
					                        <p class="mar-no">Balance Demands</p>
					                    </div>
									</div>
					            </div>
					        </div>
					    </div>
						<div class="col-sm-6 col-md-4">
					        <div class="row">
					            <div class="col-sm-6 col-md-12">
					                <div class="panel media  bg-primary pad-all">
										<div class="media-left ">
											<span class="icon-wrap icon-wrap-sm icon-circle">
					                        <i class="demo-pli-shopping-bag icon-2x"></i>
					                        </span>
					                    </div>
					                    <div class="media-body">
											<p class="text-2x mar-no text-semibold"><?php echo $total_collection; ?></p>
					                        <p class="mar-no">Total Collections</p>
					                    </div>
									</div>
					            </div>
					        </div>
					    </div>
					    
				</div>
				
						<div class="row">
					        <div class="col-md-4">
					            <!-- Area Chart -->
					            <!---------------------------------->
					            <div class="panel">
					                <div class="panel-heading">
					                    <h3 class="panel-title">House Hold Vs Payment Done</h3>
					                </div>
					                <div class="pad-all">
					                    <div id="houseHold_status" style="width: 100%; height: auto; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
					                </div>
					            </div>
					            <!---------------------------------->
					        </div>
					        <div class="col-md-4">
					            <!-- Line Chart -->
					            <!---------------------------------->
					            <div class="panel">
					                <div class="panel-heading">
					                    <h3 class="panel-title">Balance Demands Vs Collected Demands</h3>
					                </div>
					                <div class="pad-all">
					                    <div id="overall_demand_stts" style="width: 100%; height: auto; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
					                </div>
					            </div>
					            <!---------------------------------->
					        </div>
							
							<div class="col-md-4">
					            <!-- Line Chart -->
					            <!---------------------------------->
					            <div class="panel">
					                <div class="panel-heading">
					                    <h3 class="panel-title">Arrear Vs Current Demands</h3>
					                </div>
					                <div class="pad-all">
					                    <div id="arrearCurrent_demand_stts" style="width: 100%; height: auto; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
					                </div>
					            </div>
					            <!---------------------------------->
					        </div>
					    </div>
						<div class="row">
					        <div class="col-md-10">
					            <!-- Area Chart -->
					            <!---------------------------------->
					            <div class="panel">
					                <div class="panel-heading">
					                    <h3 class="panel-title">House Hold Vs Payment Done</h3>
					                </div>
					                <div class="pad-all">
					                    <div id="column_test" style="width: 100%; height: auto; white-space: nowrap; overflow-x: visible; overflow-y: hidden;"></div>
					                </div>
					            </div>
					            <!---------------------------------->
					        </div>
						</div>
					
		</div><!--End panel-->
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
modelInfo('Welcome to Dashboard');
<?php if($flashToast=flashToast('dashboard')){
    echo "modelInfo('".$flashToast."');";
}
if($pwd=flashToast('pwd')){
    echo "modelInfo('".$pwd."');";
}
?>
</script>

<script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
	  
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Total Dues Donut Chart'],
          ['Payment Done',     <?=$collection;?>],
          ['Payment Pendings', <?=$not_paymnt;?>]
        ]);

        var options = {
			title: 'House Hold Vs Payment Done',
			pieHole: 0.4,
			width: '100%',
			height: 275,
        };

        var chart = new google.visualization.PieChart(document.getElementById('houseHold_status'));
        chart.draw(data, options);
      }
	</script>
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Total Dues Donut Chart'],
          ['Total Collected',     <?=$total_collected;?>],
          ['Total Balance',     <?=$balance_demand;?>]
        ]);

        var options = {
			title: 'Balance Demands Vs Collected Demands',
			pieHole: 0.4,
			width: '100%',
			height: 275,
        };

        var chart = new google.visualization.PieChart(document.getElementById('overall_demand_stts'));
        chart.draw(data, options);
      }
	</script>
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Total Dues Donut Chart'],
          ['Arrear Demands',     <?=$total_arrear_demand;?>],
          ['Current Demands',      <?=$total_current_demand;?>]
        ]);

        var options = {
			title: 'Arrear Vs Current Demands',
			pieHole: 0.4,
			width: '100%',
			height: 275,
        };

        var chart = new google.visualization.PieChart(document.getElementById('arrearCurrent_demand_stts'));
        chart.draw(data, options);
      }
	</script>
	
	<script type="text/javascript">
		google.charts.load("current", {packages:["corechart", "bar"]});
		google.charts.setOnLoadCallback(drawChart);
		
		function drawChart() {

			var data = google.visualization.arrayToDataTable([
				['Collection', 'Total Collection'],
				['Arrear Demands',     <?=$total_arrear_demand;?>],
				['Current Demands',      <?=$total_current_demand;?>]
			]);
			
		  var options = {
			title: 'Motivation Level Throughout the Day',
			hAxis: {
			  title: 'Time of Day',
			  width: '100%',
			  height: 475,
			},
			vAxis: {
			  title: 'Amount '
			}
		  };
			
		var chart = new google.visualization.ColumnChart(document.getElementById('column_test'));
        chart.draw(data, options);
		  
		}
	</script>
