<?= $this->include('layout_vertical/header');?>

    
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
<li class="active">Back To Citizen</li>
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
								 <label class="control-label" for="Date From"><b>Date From</b><span class="text-danger">*</span> </label>
								 <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $date_from; ?>" style="text-transform:uppercase">
							</div>
							<div class="col-md-3">
 								<label class="control-label" for="Date Upto"><b>Date Upto</b><span class="text-danger">*</span> </label>								
 								<input type="date" name="date_upto" id="date_upto" class="form-control" value="<?php echo $date_upto; ?>" style="text-transform:uppercase">
							</div>
							<div class="col-md-3">
								<label class="control-label" for="ward No"><b>Ward No.</b></label>
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
								<label class="control-label" for="srchbtn">&nbsp;</label>								
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Back to Citizen Lists</h5>
				</div>
				<div class="panel-body table-responsive">
					<?php
						if(isset($backtocitizen_list))
						{
							?>
								<button class="bg-success" onclick="ExportToExcel('xlsx')" >Export Excel</button> 
							<?php
						}
					?>
					<table id="demo_dt_basic" class="table table-striped table-bordered " cellspacing="0" width="100%">
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
								<th>Remarks</th>
								<th>User Type</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($backtocitizen_list))
							{
							  $i=1;
							  foreach($backtocitizen_list as $val)
							  {
									?>
									<tr>  
										<td><?php echo $i; ?></td>
										<td><?php echo $val['ward_no'];?></td>
										<td><?php echo $val['application_no'];?></td>
										<td><?php echo $val['applicant_name'];?></td>
										<td><?php echo $val['mobile_no'];?></td>
										<td><?php echo $val['category'];?></td>
										<td><?php echo $val['connection_type'];?></td>
										<td><?php echo date('d-m-Y', strtotime($val['apply_date']));?></td>
										<td><?php echo $val['remarks']??"";?></td>
										<td><?php echo $val['user_type']??"";?></td>
										<td><a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.md5($val['apply_connection_id'])); ?>" class="btn btn-primary">View</a></td>
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
<script>
	function ExportToExcel(type, fn, dl) 
    {       
        var from_date = $('#date_from').val();
        var to_date = $('#date_upto').val();
        var ward_id = $('#ward_id').val();
        if (ward_id=='') 
        {
            ward_id = "ALL";
        }                           
        var gerUrl = from_date+'/'+to_date+'/'+ward_id;
        window.open('<?=base_url();?>/water_report/BTCExportExcel/'+gerUrl).opener = null;
    }
</script>