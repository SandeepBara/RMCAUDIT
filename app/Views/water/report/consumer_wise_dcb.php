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
                            <div class="col-md-2">
                                <label class="control-label" for="Fin Year"><b>Ward</b><span class="text-danger">*</span> </label>
                                <select name="ward_id" id="ward_id" class="form-control">
                                    <!--<option value="">All</option>-->
                                    <?php
                                    foreach ($ward_list as $ward)
                                    {
                                        ?>
                                        <option value="<?=$ward["id"];?>" <?php if(isset($_POST['ward_id']) && $ward["id"]==$_POST['ward_id']){ echo "selected";}?>><?=$ward["ward_no"];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label" for="Fin Year"><b>Property Type</b><span class="text-danger">*</span> </label>
                                <select name="property_type" id="property_type" class="form-control">
                                    <option value="">All</option>
                                    <option value="Residential" <?php if(isset($_POST['property_type']) && $_POST['property_type']=='Residential'){ echo "selected";}?>>Residential</option>
                                    <option value="Non-Residential" <?php if(isset($_POST['property_type']) && $_POST['property_type']=='Non-Residential'){ echo "selected";}?>>Non-Residential</option>
                                    <option value="Government" <?php if(isset($_POST['property_type']) && $_POST['property_type']=='Government'){ echo "selected";}?>>Government</option>
                                    
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label" for="Catgory"><b>Catgory</b><span class="text-danger">*</span> </label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">All</option>
                                    <option value="APL" <?php if(isset($_POST['category']) && $_POST['category']=='APL'){ echo "selected";}?>>APL</option>
                                    <option value="BPL" <?php if(isset($_POST['category']) && $_POST['category']=='BPL'){ echo "selected";}?>>BPL</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label" for="Fin Year"><b>Connection Type</b><span class="text-danger">*</span> </label>
                                <select name="connection_type" id="connection_type" class="form-control">
                                    <option value="">All</option>
                                    <option value="Meter" <?php if(isset($_POST['connection_type']) && $_POST['connection_type']=='Meter'){ echo "selected";}?>>Meter</option>
                                    <option value="Non-Meter" <?php if(isset($_POST['connection_type']) && $_POST['connection_type']=='Non-Meter'){ echo "selected";}?>>Non-Meter</option>
                                </select>
                            </div>

                            
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label>
                                <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Consumer Wise DCB</h5>
				</div>
				<div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
							<div class="table-responsive">
								<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>#</th>   
											<th>Consumer No.</th>   
											<th>Consumer Name</th>
											<th>Property Type</th>
											<th>Outstanding at the begining</th>
											<th>Current Demand</th>
											<th>Total Demand</th>
											<th>Old Due Collection</th>
											<th>Current Collection</th>
											<th>Old Due</th>
											<th>Current Due</th>
											<th>Outstanding Due</th>
											
										</tr>
									</thead>
									<tbody>
										<?php
										if($consumer_wise_dcb)
										{
											  $i=1;
											  foreach($consumer_wise_dcb as $val)
											  {
													?>
													<tr> 
														<td><?php echo $i; ?></td>
														<td><?php echo $val['consumer_no'];?></td>
														<td><?php echo $val['applicant_name'];?></td>
														<td><?php echo $val['property_type'];?></td>
														<td><?php echo $val['outstanding_at_begin'];?></td>
														<td><?php echo $val['current_demand'];?></td>
														<td><?php echo $val['outstanding_at_begin']+$val['current_demand'];?></td>
														<td><?php echo $val['arrear_coll'];?></td>
														<td><?php echo $val['curr_coll'];?></td>
														<td><?php echo $val['old_due'];?></td>
														<td><?php echo $val['curr_due'];?></td>
														<td><?php echo $val['outstanding'];?></td>
													</tr>
													<?php
													$i++;
												}
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
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }]
        });
    });

/*
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#demo_dt_basic').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
        'serverSide': true,
        dom: 'Bfrtip',
        lengthMenu: [
            [10, 25, 50, 5000],
            ['10 rows', '25 rows', '50 rows', '5000 rows']
        ],
        buttons: [
            'pageLength',
            {
                text: 'Excel Export',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    window.open('<?=base_url();?>/WaterConsumerWiseDCBReport/WaterConsumerWiseDCBReportExportExcel').opener = null;
                }
            }
        ],

        'ajax': {
            "type": "POST",
            'url':'<?=base_url('WaterConsumerWiseDCBReport/WaterConsumerWiseDCBReportAjax');?>',
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                console.log(data);
            },
            beforeSend: function () {
                $("#btn_search").val("LOADING ...");
            },
            complete: function () {
               $("#btn_search").val("SEARCH");
            },
        },
        order: [[0, 'DESC']],
        'columns': [
            { 'data': 's_no' },
            { 'data': 'consumer_no' },
            { 'data': 'applicant_name' },
            { 'data': 'property_type' },
            { 'data': 'outstanding_at_begin' },
            { 'data': 'current_demand' },
            { 'data': 'total_demand' },
            { 'data': 'arrear_coll' },
            { 'data': 'curr_coll' },
            { 'data': 'old_due' },
            { 'data': 'curr_due' },
            { 'data': 'outstanding' },

        ]
    });
});
*/

/*
$(document).ready(function(){
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
});
*/
</script>
