<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>

    
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
<li class="active">Ward Wise Collection</li>
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
					<h5 class="panel-title">Ward Wise Collection Report</h5>
				</div>
				<div class="panel-body">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								
								<th>Ward No.</th>   
								<th>Total Consumer</th>
								<th>Total Collection</th>
							
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($ward_wise_collection) && !empty($ward_wise_collection))
							{
							  $i=1;
							  foreach($ward_wise_collection as $val)
							  {
							?>
							<tr>  
								
								<td><?php echo $val['ward_no'];?></td>
								<td><?php echo $val['count_consumer'];?></td>
								<td><?php echo $val['total_collection'];?></td>
								
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
