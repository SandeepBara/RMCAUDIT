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
<li><a href="#">Secondary</a></li>
<li class="active">Mode Wise Collection</li>
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
								<label class="control-label" for="ward No"><b>Ward No.</b><span class="text-danger">*</span> </label>
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">All</option>
									<?php
									if($ward_list):
										foreach($ward_list as $val):
									?>
									<option value="<?php echo $val['id'];?>" <?php if(isset($ward_id) && $ward_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
									<?php
									endforeach;
									endif;
									?>
								</select>
							</div>
							<div class="col-md-3">
								<label class="control-label" for="ward No"><b>Status</b><span class="text-danger">*</span> </label>
								<select name="level_status" id="level_status" class="form-control">
									<option value="1" <?php if(isset($level_status) and $level_status==1){ echo "selected"; }?>>Approved</option>
									<option value="2" <?php if(isset($level_status) and $level_status==2){ echo "selected"; }?>>Back to Citizen</option>
									<option value="4" <?php if(isset($level_status) and $level_status==4){ echo "selected"; }?>>Rejected</option>
									<option value="0" <?php if(isset($level_status) and $level_status==0){ echo "selected"; }?>>Pending</option>
									<!--<option value="1">Approved</option>-->
									
								</select>
							</div>
							<div class="col-md-2">
								<label class="control-label" for="Date From"><b>Date From</b><span class="text-danger">*</span> </label>
								<input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $date_from; ?>" style="text-transform:uppercase">
							</div>
							<div class="col-md-2">
 								<label class="control-label" for="Date Upto"><b>Date Upto</b><span class="text-danger">*</span> </label>								
 								<input type="date" name="date_upto" id="date_upto" class="form-control" value="<?php echo $date_upto; ?>" style="text-transform:uppercase">
							</div>
							<div class="col-md-2">
								<label class="control-label" for="department_mstr_id">&nbsp;</label>
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="button">Search</button>
							</div>
						</div>
					</form>
                </div>
            </div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Application Level Status Lists</h5>
				</div>
				<div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">                            
							<div class="table-responsive">
								<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>S. No.</th>   
                                            <th>Ward No.</th>   
                                            <th>Application No.</th>
                                            <th>Applicant Name</th>
                                            <th>Mobile No.</th>
                                            <th>Category</th>
                                            <th>Connection Type</th>
                                            <th>Apply Date</th>
                                            <th>View</th>  
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
                [10, 25, 50, 5000],
                ['10 rows', '25 rows', '50 rows', '5000 rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var date_from = $('#date_from').val();
                        var date_upto = $('#date_upto').val();
                        var ward_id = $('#ward_id').val();
                        var level_status = $('#level_status').val();                        
                        d = new Date();
                        var y = d.getFullYear();
                        var month = d.getMonth()+1;
                        month = month<10?'0'+month:month;
                        date = d.getDate();
                        date = date<10?'0'+date:date;
                        new_date = y+'-'+month+'-'+date;                        
                        if (date_from=='' || date_from==undefined) 
                        {
                            date_from = new_date;
                        }
                        if(date_upto=='' || date_upto==undefined)
                        {
                            date_upto=new_date;
                        }
                        if(ward_id=='' || ward_id==undefined)
                        {
                            ward_id='ALL';
                        }    
                        if(level_status=='' || level_status==undefined)
                        {
                            level_status='ALL';
                        }                    
                                        
                        var gerUrl = date_from+'/'+date_upto+'/'+ward_id+"/"+level_status;
                        window.open('<?=base_url();?>/WaterApplicationLevelPendingReport/index_copyExcel/'+gerUrl).opener = null;
                    }
                }
            ],

            /* "columnDefs": [
                { "orderable": false, "targets": [4, 5] }
            ], */
            'ajax': {
                "type": "POST",
                'url':'<?=base_url('WaterApplicationLevelPendingReport/index_copy');?>',            
                dataSrc: function ( data ) {
                    total_collection = data.total;
                    recordsTotal  = data.recordsTotal;
                    console.log(data);
                    $('#total').text(data.Total);
                    $('#apply').text('['+data.apply+']');
                    $('#not_apply').text('['+data.not_apply+']');
                    return data.data;
                },
                
                "deferRender": true,
                "dataType": "json",
                'data': function(data){
                    // Append to data
                    data.date_from = $('#date_from').val();
                    data.date_upto = $('#date_upto').val();
                    data.ward_id = $('#ward_id').val();
                    data.level_status = $('#level_status').val();
                    data.search_entry_type = $('#entry_type').val();
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
                { 'data': 'application_no' },
                { 'data': 'applicant_name' },
                { 'data': 'mobile_no' },
                { 'data': 'category' },
                { 'data': 'connection_type' },
                { 'data': 'apply_date' },
                { 'data': 'view' },                
                // { 'data': 'cheque_no' },
                // { 'data': 'cheque_date' },
                // { 'data': 'bank_name' },
                // { 'data': 'branch_name' },
                // { 'data': 'emp_name' },

                
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    // $("#footerResult").html(" (Total Collection - "+Total+")");
                    // var api = this.api();
                    // $(api.column(14).footer() ).html(Total);
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
