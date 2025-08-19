<?= $this->include('layout_home/header');?>
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

<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Search Applicants</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="post" >
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
							<div class="col-md-4">
								 <label class="control-label" for="ward No"><b>Keyword</b><span class="text-danger">*</span> </label>
								 <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo $keyword; ?>" style="text-transform:uppercase" placeholder="Enter Owner Name or Mobile No. or Application No.">
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
								<th>S. No.</th>   
								<th>Ward No.</th>   
								<th>Application No.</th>
								<th>Category</th>
								<th>Applicant Name</th>
								<th>Mobile No.</th>
								<th>Apply Date</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($application_details)
							{
							  $i=1;
							  foreach($application_details as $val)
							  {
							?>
							<tr>  
								<td><?php echo $i; ?></td>
								<td><?php echo $val['ward_no'];?></td>
								<td><?php echo $val['application_no'];?></td>
								<td><?php echo $val['category'];?></td>
								<td><?php echo $val['owner_name'];?></td>
								<td><?php echo $val['mobile_no'];?></td>
								<td><?php echo date('d-m-Y',strtotime($val['apply_date']));?></td>
								<td><a href="<?php echo base_url($view.md5($val['id'])); ?>" class="btn btn-info">View</a></td>
								</td>     
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


<script>
    
    $(document).ready(function () 
    {

    

    $('#myform').validate({ // initialize the plugin
       

        rules: {
            ward_id: {
                required: true,
               
            },
            keyword: {
                required: true,
                
            }
        }


    });

});
</script>