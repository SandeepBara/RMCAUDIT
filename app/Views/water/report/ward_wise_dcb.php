<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">

<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
<!-- <h1 class="page-header text-overflow">Department List</h1>//-->
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!--Breadcrumb-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Water</a></li>
<li class="active">Search Consumer</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Search</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="post" >
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label" for="Fin Year"><b>Financial Year</b><span class="text-danger">*</span> </label>
								<select name="fin_year" id="fin_year" class="form-control">
									<?php
										$fy_list = fy_year_list();
										foreach($fy_list as $val)
										{
											?>
												<option value="<?=$val?>" <?php if($fin_year=="$val"){ echo "selected";}?>><?=$val?></option>
											<?php
										}
									?>									
								</select>
							</div>

							<div class="col-md-3">
								<label class="control-label" for="Fin Year"><b>Property Type</b><span class="text-danger">*</span> </label>
								<select name="property_type" id="property_type" class="form-control">
									<option value="">All</option>
									<?php
										foreach($property_list as $val)
										{
											?>
												<option value="<?=$val['id']?>" <?=isset($property_type) && $property_type==$val['id']?"selected":'';?>><?=$val['property_type']?></option>
											<?php
										}
									?>									
								</select>
							</div>

							
							<div class="col-md-3">
								<label class="control-label">&nbsp;</label>
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>

						</div>
					</form>
				</div>
			</div>

			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Ward Wise DCB</h5>
				</div>
				<div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
						<div class="table-responsive">
							<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead class="bg-trans-dark text-dark">

									<tr style="text-align: center;">
										<th rowspan="2">Ward No.</th> 
										<th rowspan="2">Total Consumer</th> 
										<th rowspan="2">Meter Consumer</th> 
										<th rowspan="2">Non Meter Consumer</th> 
										<th rowspan="2">Outstanding Demand at the begining of the Year</th>   
										<th rowspan="2">Demand for the current Year</th>
										<th rowspan="2">Total Demand</th>
										<th colspan="3">Collection during the year <?php echo $fin_year;?></th>
										<th rowspan="2">Total Collection</th>
										<th colspan="2">Outstanding at year end</th>
										<th rowspan="2">Total Outstanding Demand</th>
									</tr>

									<tr>
										<th>Old Due</th>
										<th>Current</th>
										<th>Advance</th>
										<th>Previous Outstanding</th>
										<th>Current Outstanding</th>
									</tr>

								</thead>
								<tbody>
									<?php
									if($ward_wise_dcb)
									{
									  $total_consumer=0;
									  $total_meter_consumer=0;
									  $total_non_meter_consumer=0;
									  $total_arrear_demand=0;
									  $total_current_demand=0;
									  $total_demand=0;
									  $total_arrear_coll=0;
									  $total_current_coll=0;
									  $total_adv=0;
									  $total_coll=0;
									  $total_arrear_dues=0;
									  $total_current_dues=0;
									  $total_outstanding_dues=0;
									  
									  $i=1;
									  foreach($ward_wise_dcb as $val)
									  {
										$total_consumer+=$val['total_consumer'];
										$total_meter_consumer +=$val['meter_consumer'];
									  	$total_non_meter_consumer +=$val['non_meter_consumer'];
									  	$arr_demand=$val['arrear_demand']-$val['prev_coll_amt'];
									  	$curr_demand=$val['current_demand'];


									  	$total_arrear_demand=$total_arrear_demand+$arr_demand;
									  	$total_current_demand=$total_current_demand+$curr_demand;
									  	$total_demand=$total_arrear_demand+$total_current_demand;


									  	$arrear_coll=$val['arrear_collection'];
									  	$current_coll=$val['curr_collection'];
									  	$advance=$val['advance_amount'];

									  	$total_arrear_coll=$total_arrear_coll+$arrear_coll;
									  	$total_current_coll=$total_current_coll+$current_coll;
									  	$total_adv=$total_adv+$advance;


									  	$total_coll=$total_arrear_coll+$total_current_coll+$total_adv;

									  	$total_arrear_dues=$total_arrear_demand-$total_arrear_coll;
									  	$total_current_dues=$total_current_demand-$total_current_coll;
									  	
									  	$total_outstanding_dues=$total_arrear_dues+$total_current_dues;


									?>
										<tr style="text-align: right; ">  
											<td><?php echo $val['ward_no'];?></td>
											<td><?php echo $val['total_consumer'];?></td>
											<td><?php echo $val['meter_consumer'];?></td>
											<td><?php echo $val['non_meter_consumer'];?></td>
											<td><?php echo number_format(($val['arrear_demand']-$val['prev_coll_amt']),2);?></td>

											<td><?php echo number_format($val['current_demand'],2);?></td>

											<td><?php echo number_format((($val['arrear_demand']-$val['prev_coll_amt'])+$val['current_demand']),2);?></td>

											<td><?php echo number_format($val['arrear_collection'],2);?></td>
											<td><?php echo number_format($val['curr_collection'],2);?></td>
											<td><?php echo number_format($val['advance_amount'],2);?></td>

											<td><?php echo number_format(($val['arrear_collection']+$val['curr_collection']+$val['advance_amount']),2);?></td>

											<td><?php echo number_format((($val['arrear_demand']-$val['prev_coll_amt'])-$val['arrear_collection']),2);?></td>
											<td><?php echo $val['current_demand']-$val['curr_collection'];?></td>

											<td><?php echo number_format(((($val['arrear_demand']-$val['prev_coll_amt'])+$val['current_demand'])-($val['arrear_collection']+$val['curr_collection'])),2);?></td>
																				
										</tr>
										<?php
										$i++;
									}
									?>
										<tr style="text-align: right; font-weight: bold; color: black;">
											<td>Total</td>
											<td><?php echo $total_consumer;?></td>
											<td><?php echo $total_meter_consumer;?></td>
											<td><?php echo $total_non_meter_consumer;?></td>
											<td><?php echo number_format($total_arrear_demand,2);?></td>   
											<td><?php echo number_format($total_current_demand,2);?></td>
											<td><?php echo number_format($total_demand,2);?></td>
											<td><?php echo number_format($total_arrear_coll,2);?></td>
											<td><?php echo number_format($total_current_coll,2);?></td>
											<td><?php echo number_format($total_adv,2);?></td>
											<td><?php echo number_format($total_coll,2);?></td>
											<td><?php echo number_format($total_arrear_dues,2);?></td>
											<td><?php echo number_format($total_current_dues,2);?></td>
											<td><?php echo number_format($total_outstanding_dues,2);?></td>
										</tr>
										<?php 
									}
									?>
								</tbody>  
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
				
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>

<script>
    
    $(document).ready(function () 
    {

    

    $('#myform').validate({ // initialize the plugin
       

        rules: {
            date_from: {
            	
                required: true,
               
            },
            date_upto: {
            	
                required: true,
                
            }
        }


    });

});
</script>

<script type="text/javascript">
$('#demo_dt_basic').DataTable({
			responsive: true,
			dom: 'Bfrtip',
			"paging": false,
			"info": false,
			"searching":false,
			"aaSorting": [],
			"aoColumnDefs": [
				{ "bSortable": false, "aTargets": [ 0, 1, 2 ] }, 
				{ "bSearchable": false, "aTargets": [ 0, 1, 2 ] }
			],
	        buttons: [
	        	'pageLength',
	          {
				text: 'Excel',
				extend: "excel",
				title: "Ward Wise DCB (Water)",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2, 3, 4, 5, 6, 7, 8, 9, 10, 11,12,13] }
			}, {
				text: 'Print',
				extend: "print",
				title: "Ward Wise DCB (Water)",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2, 3, 4, 5, 6, 7, 8, 9, 10, 11,12,13] }
			}]
});
</script>
