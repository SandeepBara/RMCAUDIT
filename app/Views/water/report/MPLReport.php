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
<li class="active">MPL</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Report</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="post" >
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label" for="Fin Year"><b>Financial Year</b><span class="text-danger">*</span> </label>
								<select name="fyear" id="fyear" class="form-control">
									<?php
										foreach($fyList as $val)
										{
											?>
												<option value="<?=$val?>" <?=($fyear=="$val")?"selected":"";?>><?=$val?></option>
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
					<h5 class="panel-title">MPL Report</h5>
				</div>
				<div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
						<div class="table-responsive">
							<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead class="bg-trans-dark text-dark">
									<tr style="text-align: center;">
										<th rowspan="2">Bill Served Months</th> 
										<th colspan="3">Status Of Water Connection</th> 
										<th colspan="3">Status Of Bill Served</th>   
										<th rowspan="2">Total Demand Generated Against bill Served</th>
										<th rowspan="2">Total DemandAmount Collected From The Demand Generated During The Month</th>
										<th rowspan="2"> Amont Collection From The Demand Genrated Previous Month</th>
										<th rowspan="2">Total Amount Collected</th>
									</tr>
									<tr>
										<th>Metered Water Connection</th>
										<th>Non-Metered Water Connection</th>
                                        <th>Total Water Connection</th>
										<th>To Metered Water Connection</th>
										<th>To Non-Metered Water Connection</th>
										<th>Total Water Bill Served</th>
									</tr>
                                    <tr>
										<th>1</th>
										<th>2</th>
										<th>3</th>
										<th>4=2+3</th>
										<th>5</th>
                                        <th>6</th>
                                        <th>7=5+6</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                        <th>11=9+10</th>
									</tr>

								</thead>
								<tbody>
									<?php
                                        foreach($demands as $val)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$val["date"]??"";?></td>
                                                <td><?=$val["total_meter_consumer"]??"";?></td>
                                                <td><?=$val["total_fixed_consumer"]??"";?></td>
                                                <td><?=$val["total_consumer"]??"";?></td>
                                                <td><?=$val["total_meter_demand"]??"";?></td>
                                                <td><?=$val["total_fixed_demand"]??"";?></td>
                                                <td><?=$val["total_demand_served"]??"";?></td>
                                                <td><?=$val["current_demand"]??"";?></td>
                                                <td><?=$val["curr_coll"]??"";?></td>
                                                <td><?=$val["arrear_coll"]??"";?></td>
                                                <td><?=$val["total_collection"]??"";?></td>
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
				exportOptions: { columns: [ 0, 1,2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
			}, {
				text: 'Print',
				extend: "print",
				title: "Ward Wise DCB (Water)",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
			}]
});
</script>
