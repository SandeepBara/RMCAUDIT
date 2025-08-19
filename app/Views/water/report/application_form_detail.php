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
<li class="active">Application Form Details</li>
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
					<h5 class="panel-title">Application Form Detail</h5>
				</div>
				<div class="panel-body">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								
								<th style="text-align: center;">New Connection</th>   
								<th style="text-align: center;">Regularization</th>
								<th style="text-align: center;">JSK Pending</th>
								<th style="text-align: center;">Level Pending</th>
								<th style="text-align: center;">Rejected</th>
								<th style="text-align: center;">Back to Citizen</th>
								<th style="text-align: center;">Approved</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($application_form_detail) && $application_form_detail)
							{
							  	if($ward_id==""){ $ward_id='All'; }
								?>
								<tr>  
								
									<td style="text-align: right; font-weight: bold;">
										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(1));?>"><?php echo $application_form_detail['new_connection'];?></a>
									</td>

									<td style="text-align: right; font-weight: bold;">
										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(2));?>">
											<?php echo $application_form_detail['regularization'];?></a>
									</td>
									<td style="text-align: right; font-weight: bold;">
										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(3));?>">
											<?php echo $application_form_detail['jsk_pending'];?></a>
									</td>
									<td style="text-align: right; font-weight: bold;">

										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(4));?>">
											<?php echo $level_pending_detail['level_pending'];?></a>
									</td>
									<td style="text-align: right; font-weight: bold;">
										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(5));?>">
											<?php echo $level_pending_detail['rejected'];?></a>
									</td>
									<td style="text-align: right; font-weight: bold;">
										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(6));?>">
										<?php echo $level_pending_detail['back_to_citizen'];?></a>
									</td>
									<td style="text-align: right; font-weight: bold;">
										<a href="<?php echo base_url('WaterApplicationFormStatusDetailReport/getParam/'.$date_from.'/'.$date_upto.'/'.$ward_id.'/'.md5(7));?>">
											<?php echo $level_pending_detail['approved'];?></a>
									</td>
									
									
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
    
     function myPopup(myURL, title, myWidth, myHeight) {
            var left = (screen.width - myWidth) / 2;
            var top = (screen.height - myHeight) / 4;
            var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
         }

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
