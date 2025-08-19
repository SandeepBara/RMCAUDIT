<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
    .text-x-small{
        font-size: x-small;
    }
    .font-weight-bold
    {
        font-weight: bolder;
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
					<h5 class="panel-title">Details for Water Charges </h5>
				</div>
				<div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
						<div class="table-responsive">
                            <?php
                                $colspan = sizeof($kyes)+1;

                            ?>
							<table id="demo_dt_basic" class="table table-striped table-bordered text-x-small" cellspacing="0" width="100%">
							    
                                <tbody class="bg-trans-dark text-dark">
                                    <tr>
                                        <td colspan="<?=$colspan+1?>">Water Charges Details</td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?=$colspan?>">Are water charges being collected in the ULB? (Yes/No)</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?=$colspan?>">If yes, which entity is collecting water charges in the ULB? (ULB/State Department/Parstatal Agency/Other)</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?=$colspan?>">Upload a copy of the gazette notification that notifies collection of water charges </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?=$colspan?>">AND please fill the following data form</td>
                                        <td></td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Sl No</td>
                                        <td>Indicators</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$val."</td>";
                                            }
                                        ?>
                                        
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>I</td>
                                        <td>Water charges Demand and Collection Details</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Total water charges demand  (INR Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_demand"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Current water charges demand  ( INR Lakh)*</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["current_demand"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Arrear water charges demand  (INR Lakh)*</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["out_standing_degining"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Total water charges collections  (INR Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>Current water charges collections (INR Lakh)*</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["curent_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>                                    
                                    <tr>
                                        <td>F</td>
                                        <td>Arrear water charges collections  (INR Lakh)*</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["arrear_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>II</td>
                                        <td>Water Connection Details</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Total number of connections from which water charges was demanded</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_demand_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Total number of connections from which water charges was collected</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_collection_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>III</td>
                                        <td>Water charges Details by Households/property Type</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>III.I</td>
                                        <td>Residential households/properties</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of water charges demanded (INR Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_residential_hh_demand"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of households/properties from which water charges was demanded</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_residential_hh_demand_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of water charges collected from households/properties (INR/LAKH)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_residential_hh_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of households/properties from which water charges was collected</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_collection_residential_hh_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>III.2</td>
                                        <td>Commercial households/properties</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of water charges demanded (INR/Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_commercial_hh_demand"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of households/properties from which water charges was demanded</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_commercial_hh_demand_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of water charges collected from households/properties  (INR/Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_commercial_hh_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of households/properties from which water charges was collected</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_collection_commercial_hh_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>III.3</td>
                                        <td>Industrial households/properties</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of water charges demanded (INR/Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_institutional_hh_demand"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of households/properties from which water charges was demanded</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_institutional_hh_demand_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of water charges collected from households/properties (INR/Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_institutional_hh_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of households/properties from which water charges was collected</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_collection_institutional_hh_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>III.4</td>
                                        <td>Other households/properties(Please mention name of any other connection type)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Value of water charges demanded (INR/Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["other_hh_demand"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Number of households/properties from which water charges was demanded</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_other_hh_demand_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Value of water charges collected from households/properties (INR/Lakh)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".round($ward_wise_dcb[$val]["total_other_hh_collection"]/100000)."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Number of households/properties from which water charges was collected.</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>".$ward_wise_dcb[$val]["total_collection_other_hh_consumer"]."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>IV</td>
                                        <td>Water Charges Tariff Detail (To be uploaded as proof on city finance) (Please submit the  formula/rates at which different connection types are charged)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>VI</td>
                                        <td>Water Charges cost of service Delivery Details</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>What is the Q&M cost of service delivery for water? (INR Lakh) (Please upload the working sheet for Q&M cost calculation on city finance)</td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <?php
                                            foreach($kyes as $val)
                                            {
                                                echo"<td>"."</td>";
                                            }
                                        ?>
                                    </tr>

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

    

    // $('#myform').validate({ // initialize the plugin
       

    //     rules: {
    //         date_from: {
            	
    //             required: true,
               
    //         },
    //         date_upto: {
            	
    //             required: true,
                
    //         }
    //     }


    // });
    var i = "<?=$colspan+1;?>";
    var column = [];
    for(j=0;j<i;j++)
    {
        column.push(j);
    }
    console.log(column);
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
                // buttons: [
                //     'pageLength',
                // {
                //     text: 'Excel',
                //     extend: "excel",
                //     title: "Details for Water Charges ",
                //     footer: { text: '' },
                //     exportOptions: { columns:column }
                // }, {
                //     text: 'Print',
                //     extend: "print",
                //     title: "Details for2 Water Charges ",
                //     download: 'open',
                //     footer: { text: '' },
                //     exportOptions: { columns: column }
                // }]
    });

});
</script>

<script type="text/javascript">
    
</script>
