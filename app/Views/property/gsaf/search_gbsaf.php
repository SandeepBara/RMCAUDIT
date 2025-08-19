<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">

<!--DataTables [ OPTIONAL ]-->

    
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
<li><a href="#">SAF</a></li>
<li class="active">Search GBSAF</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Search GBSAF</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="post" >
						<div class="form-group">
							
							<div class="col-md-5">
								 <label class="control-label" for="ward No"><b>Application No.</b><span class="text-danger">*</span> </label>
								 <input type="text" name="application_no" id="application_no" class="form-control" value="<?=$application_no ?? NULL;?>" style="text-transform:uppercase" placeholder="Enter Application No.">
							</div>
							<div class="col-md-2">
								<label class="control-label" for="department_mstr_id">&nbsp;</label>
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Application Lists</h5>
				</div>
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								
								<th>Ward No.</th>   
								<th>Application No.</th>
								<th>Building Name</th>
								<th>Address</th>
								<th>Apply Date</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($application_detail) && $application_detail)
							{
							
							?>
							<tr>  
								
								<td><?php echo $application_detail['ward_no'];?></td>
								<td><?php echo $application_detail['application_no'];?></td>
								<td><?php echo $application_detail['building_colony_name'];?></td>
								<td><?php echo $application_detail['building_colony_address'];?></td>
								<td><?php echo date('d-m-Y',strtotime($application_detail['apply_date']));?></td>
								<td><a href="<?php echo base_url('GsafDocUpload/docUpload/'.md5($application_detail['id'])); ?>" class="btn btn-info">View</a></td>
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


<script>
    
    $(document).ready(function () 
    {

    

    $('#myform').validate({ // initialize the plugin
       

        rules: {
            application_no: {
                required: true,
               
            }
        }


    });

});
</script>

