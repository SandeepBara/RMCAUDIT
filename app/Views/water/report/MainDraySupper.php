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
                                    <option value="">All</option>
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
                                    <?php
                                        foreach($property_list as $val)
                                        {
                                            ?>
                                                <option value="<?=$val['id']?>" <?php if(isset($property_type) && $property_type==$val['id']){ echo "selected";}?>><?=$val['property_type']?></option>
                                            <?php
                                        }
                                    ?>
                                    
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

                            <div class="col-md-3">
								<label class="control-label" for="Fin Year"><b>Financial Year</b><span class="text-danger">*</span> </label>
								<select name="fin_year" id="fin_year" class="form-control">
									<?php
										$fy_list = fy_year_list();
										foreach($fy_list as $val)
										{
											?>
												<option value="<?=$val?>" ><?=$val?></option>
											<?php
										}
									?>									
								</select>
							</div>
                            
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label>
                                <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="button">Search</button>
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
                                            <th>Ward No.</th>      
											<th>Consumer No.</th>   
											<th>Consumer Name</th>
                                            <th>Mobile No.</th>
                                            <th>Address</th>
											<th>Property Type</th>
                                            <th>Consumer Type</th>
                                            <th>Connection Type</th>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>


<script type="text/javascript">
   //debugger;
    $(document).ready(function(){
        //$.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#demo_dt_basic').DataTable({
            'responsive': true,
            'processing': true,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'ALL rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var ward_id = $('#ward_id').val();
                        var property_type = $('#property_type').val();
                        var category = $('#category').val();
                        var connection_type = $('#connection_type').val();                        
                        var fyear = $('#fin_year').val();

                        if (ward_id=='') 
                        {
                            ward_id = "ALL";
                        }
                        if(property_type=='' || property_type==undefined)
                        {
                            property_type='ALL';
                        }
                        if(category=='' || category==undefined)
                        {
                            category='ALL';
                        }
                        if(connection_type=='' || connection_type==undefined)
                        {
                            connection_type='ALL';
                        }
                                        
                        var gerUrl = ward_id+'/'+property_type+'/'+category+'/'+ connection_type+"/"+fyear;                        
                        window.open('<?=base_url();?>/water_report/WaterConsumerWiseDCBReportExcel2/'+gerUrl).opener = null;
                    }
                }
            ],

            'ajax': {
                "type": "POST",
                'url':'<?=base_url('water_report/MainDraySupper');?>',            
                dataSrc: function ( data ) {
                    total_collection = data.total_collection;
                    recordsTotal  = data.recordsTotal;
                    console.log(data);
                    $('#total').text('['+data.recordsTotal+']');
                    $('#apply').text('['+data.apply+']');
                    $('#not_apply').text('['+data.not_apply+']');
                    return data.data;
                },
                
                "deferRender": true,
                "dataType": "json",
                'data': function(data){
                    // Append to data
                    data.ward_id = $('#ward_id').val();
                    data.property_type = $('#property_type').val();
                    data.category = $('#category').val();
                    data.connection_type = $('#connection_type').val();
                    data.fyear = $('#fin_year').val();
                    // data.search_entry_type = $('#entry_type').val();
                },
                beforeSend: function () {
                    $("#btn_search").val("LOADING ...");
                    $("#loadingDiv").show();
                },
                complete: function () {
                $("#btn_search").val("SEARCH");
                $("#loadingDiv").hide();
                $('#from').text($('#from_date').val());
                $('#to').text($('#upto_date').val());
                },
            },

            'columns': [
                { 'data': 's_no' },
                { 'data': 'ward_no' },
                { 'data': 'consumer_no' },
                { 'data': 'applicant_name' },
                { 'data': 'mobile_no' },
                { 'data': 'address' },
                { 'data': 'property_type' },
                { 'data': 'consumer_type' },
                { 'data': 'connetion_type' },
                { 'data': 'outstanding_at_begin' },
                { 'data': 'current_demand' },
                { 'data': 'total' },
                { 'data': 'arrear_coll' },
                { 'data': 'curr_coll' },
                { 'data': 'old_due' },
                { 'data': 'curr_due' },
                { 'data': 'outstanding' },
                

                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    // $("#footerResult").html(" (Total Collection - "+total_collection+")");
                    // var api = this.api();
                    // $(api.column(14).footer() ).html(total_collection);
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        $('#btn_search').click(function()
        {
            
            from_date_val = $('#from_date').val()
            to_date_val = $('#upto_date').val()
            if(from_date_val=='')
            {
                alert('please select from date !!!')
                return
            }
            if(to_date_val=='')
            {
                alert('please select to date !!!')
                return
            }

            if(new Date(from_date_val) > new Date(to_date_val))
            {
                alert('from data cannot be greater than to data')
                return
            }
            dataTable.draw();
        });
    });
    
</script>
