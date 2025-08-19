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
<li class="active">Site Inspection Details</li>
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
					<form class="form-horizontal" id="myform" method="get" >
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label" for="ward No"><b>Ward No.</b><span class="text-danger">*</span> </label>
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($ward_list):
										foreach($ward_list as $val):
									?>
									<option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
									<?php
									endforeach;
									endif;
									?>
								</select>
							</div>
							<div class="col-md-3">
								 <label class="control-label" for="Date From"><b>Date From</b><span class="text-danger">*</span> </label>
								 <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $date_from; ?>" style="text-transform:uppercase">
							</div>
							<div class="col-md-3">
 								<label class="control-label" for="Date Upto"><b>Date Upto</b><span class="text-danger">*</span> </label>								
 								<input type="date" name="date_upto" id="date_upto" class="form-control" value="<?php echo $date_upto; ?>" style="text-transform:uppercase">
							</div>
							<div class="col-md-3">
								<label class="control-label" for="srchbtn">&nbsp;</label>								
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Site Inspection Detail List</h5>
				</div>
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th>S. No.</th>   
								<th>Ward No.</th>   
								<th>Application No.</th>
								<th>Applicant Name</th>
								<th>Mobile No.</th>
								<th>Apply Date</th>
								<th>Inspection Date</th>
								<th>Inspection By</th>
								<th>Employee Name</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($site_insp_dtls)
							{
							  $i=$offset??0;
							  foreach($site_insp_dtls as $val)
							  {
							?>
							<tr>  
								<td><?php echo ++$i; ?></td>
								<td><?php echo $val['ward_no'];?></td>
								<td><?php echo $val['application_no'];?></td>
								<td><?php echo $val['applicant_name'];?></td>
								<td><?php echo $val['mobile_no'];?></td>
								<td><?php echo $val['apply_date'];?></td>
								<td><?=$val['inspection_date'];?></td>
								<td><?=$val['verified_by'];?></td>
								<td><?=$val['emp_name'];?></td>
								<td><a href="<?php echo base_url().'/WaterApplyNewConnection/water_connection_view/'.md5($val['apply_connection_id']); ?>" class="btn btn-info">View</a></td>
								</td>     
							</tr>
							<?php
								
								}
							}
							?>
						</tbody>  
					</table>
					<?=pagination($count??0);?>
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
