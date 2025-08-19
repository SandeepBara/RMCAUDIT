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
<li><a href="#">Report</a></li>
<li class="active">All Module DCB</li>
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
					<div class="row">
						<div class="col-md-12">
							<form method="post" id="myform">
								<div class="row">
									<label class="col-md-2 text-bold">Financial Year<span class="text-danger">*</span> </label>
									<div class="col-md-3 has-success pad-btm">
										<select name="fin_year" id="fin_year" class="form-control">
											<option value="2022-2023" <?php if(isset($fin_year)){ if($fin_year=='2022-2023'){ echo "selected";} }?>>2022-2023</option>
											<option value="2021-2022" <?php if(isset($fin_year)){ if($fin_year=='2021-2022'){ echo "selected";} }?>>2021-2022</option>
											<option value="2020-2021" <?php if(isset($fin_year)){ if($fin_year=='2020-2021'){ echo "selected";} }?>>2020-2021</option>
											<option value="2019-2020" <?php if(isset($fin_year)){ if($fin_year=='2019-2020'){ echo "selected";} }?>>2019-2020</option>
											<option value="2018-2019" <?php if(isset($fin_year)){ if($fin_year=='2018-2019'){ echo "selected";} }?>>2018-2019</option>
											<option value="2017-2018" <?php if(isset($fin_year)){ if($fin_year=='2017-2018'){ echo "selected";} }?>>2017-2018</option>
											<option value="2016-2017" <?php if(isset($fin_year)){ if($fin_year=='2016-2017'){ echo "selected";} } ?>>2016-2017</option>
											
										</select>
									</div>
								</div>
								<div class="row">
									<label class="col-md-2 text-bold">Property Type<span class="text-danger">*</span> </label>
									<div class="col-md-6">
										<div class="checkbox">
											<input type="checkbox" name="property_type1" id="property_type1" value="1" class="magic-checkbox"  <?php if(isset($property_type1)){ if($property_type1==1){ echo "checked";} }?>>
											<label for="property_type1">Residential</label>
				
											<input type="checkbox"name="property_type2" id="property_type2" value="2" class="magic-checkbox"  <?php if(isset($property_type2)){ if($property_type2==2){ echo "checked";} }?>>
											<label for="property_type2">Non-Residential</label>
											
											<input type="checkbox" name="property_type3" id="property_type3" value="3" class="magic-checkbox"  <?php if(isset($property_type3)){ if($property_type3==3){ echo "checked";} }?>>
											<label for="property_type3">Government</label>
										</div>
									</div>
									<div class="col-md-4">
										<input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">ALL Module Ward Wise DCB</h5>
				</div>
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">

							<tr align="center">
								<th rowspan="2">Ward No.</th>
								<th colspan="3">Property</th>   
								<th colspan="3">Water</th>   
								
							</tr>

							<tr>
								
								<th>Demand</th>
								<th>Collection</th>
								<th>Balance</th>
								<th>Demand</th>
								<th>Collection</th>
								<th>Balance</th>

							</tr>

						</thead>
						<tbody>
							<?php
							if($all_module_dcb)
							{
								
							  $total_water_demand=0;
							  $total_water_coll=0;
							  $total_water_balance=0;
							  
							  $total_prop_balance=0;
							  $total_prop_coll=0;
							  $total_prop_demand=0;
							  
							  $i=0;
							  foreach($all_module_dcb as $val)
							  {

							  	$water_demand=$val['water_demand'];
							  	$water_coll=$val['water_coll'];
							  	
							  	$prop_demand=$val['prop_demand'];
							  	$prop_coll=$val['prop_coll'];

							  	$total_prop_demand=$total_prop_demand+$prop_demand;
							  	$total_prop_coll=$total_prop_coll+$prop_coll;
							  	$total_prop_balance=$total_prop_demand-$total_prop_coll;

							  	$total_water_demand=$total_water_demand+$water_demand;
							  	$total_water_coll=$total_water_coll+$water_coll;
							  	// $total_water_balance=$total_water_demand-isset($total_current_coll)?$total_current_coll:0;
							  	$total_water_balance=$total_water_demand-(isset($total_current_coll)?$total_current_coll:0);

							  	

							?>
							<tr style="text-align: right; ">  
								
								<td><?php echo $val['ward_no'];?></td>
								<td><?php echo number_format(($val['prop_demand']),2);?></td>

								<td><?php echo number_format($val['prop_coll'],2);?></td>

								<td><?php echo number_format((($val['prop_demand'])-$val['prop_coll']),2);?></td>

								

								<td><?php echo number_format(($val['water_demand']),2);?></td>

								<td><?php echo number_format($val['water_coll'],2);?></td>

								<td><?php echo number_format((($val['water_demand'])-$val['water_coll']),2);?></td>

								

							</tr>
							<?php
								$i++;
								}
							?>

								<tr style="text-align: right; font-weight: bold; color: black;">
									<td>Total</td>   
									<td><?php echo number_format($total_prop_demand,2);?></td>   
									<td><?php echo number_format($total_prop_coll,2);?></td>
									<td><?php echo number_format($total_prop_balance,2);?></td>

									<td><?php echo number_format($total_water_demand,2);?></td>   
									<td><?php echo number_format($total_water_coll,2);?></td>
									<td><?php echo number_format($total_water_balance,2);?></td>
									
								</tr>

							<?php 

							}
							?>
						</tbody>  
					</table>
				</div>
			</div>
				
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

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
/*$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#demo_dt_basic').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
        'serverSide': true,
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        "columnDefs": [
            { "orderable": false, "targets": [0, 4] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            {
                extend: "excel",
                footer: { text: '' },
                exportOptions: { columns: [0,1,2,3,4,5,6,7] }
            }],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('WaterSearchConsumer/getPagination');?>',
            "dataType": "text",
            'data': function(data){
                // Read values
                alert(data);
                var ward_id = $('#ward_id').val();
                var keyword = $('#keyword').val();
               // alert(keyword);

                // Append to data
                data.search_by_from_ward_id = ward_id;
                data.search_by_upto_ward_id = keyword;
            }
        },

         'columns': [
            { 'data': 's_no' },
            { 'data': 'id' },
            { 'data': 'ward_no' },
            { 'data': 'ulb_mstr_id' },
            { 'data': 'status' },
        ]
     
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});*/

</script>
