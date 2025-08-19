<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
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
					<h5 class="panel-title">Search Consumer</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="post" >
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label" for="ward_id"><b>Ward No.</b><span class="text-danger">*</span> </label>
							</div>
							<div class="col-md-3">
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">Select</option>
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
							<div class="col-md-2">
								 <label class="control-label" for="keyword">
									 <b>Enter Keywords</b>
									 <i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Enter Owner Name or Mobile No. or Consumer No. or Holding No."></i>
									 <!-- <span class="text-danger">*</span> -->
								 </label>								 
							</div>
							<div class="col-md-3">
								 
								 <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo $keyword ?? null; ?>" style="text-transform:uppercase" >
							</div>
							<div class="col-md-2">
								<!-- <label class="control-label" for="department_mstr_id">&nbsp;</label> -->
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Consumer List</h5>
				</div>
				<div class="panel-body table-responsive">
                
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th>S. No.</th>   
								<th>Ward No.</th>   
								<th>Consumer No.</th>
								<th>Category</th>
								<th>Applicant Name</th>
								<th>Mobile No.</th>
								<th>Address.</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($consumer_details)
							{
								$i=1;
								foreach($consumer_details as $val)
								{
									?>
									<tr>  
										<td><?php echo $i; ?></td>
										<td><?php echo $val['ward_no'];?></td>
										<td><?php echo $val['consumer_no'];?></td>
										<td><?php echo $val['category'];?></td>
										<td><?php echo $val['owner_name'];?></td>
										<td><?php echo $val['mobile_no'];?></td>
										<td><?php echo(isset($val['address']) && !empty($val['address']) ? $val['address']:'N/A');?></td>
										<td><a href="<?php echo base_url($view.md5($val['id']));?>" class="btn btn-primary btn-sm" target="blank">View</a></td>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function () 
{
    $('#myform').validate({ // initialize the plugin
        rules: {
            ward_id: {
                required: "#keyword:blank",
            },
            keyword: {
                required: "#ward_id:blank",
            }
        }
    });
});
</script>
<script>
$(document).ready(function(){
    $('#demo_dt_basic').DataTable({
        responsive: false,
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 100, -1 ],
            [ '10 rows', '25 rows', '100 rows', 'Show all' ]
        ],
        buttons: [
            'pageLength',
          {
            text: 'excel',
            extend: "excel",
            title: "Report",
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5] }
        }, {
            text: 'pdf',
            extend: "pdf",
            title: "Report",
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3, 4, 5] }
        }]
    });
});
</script>