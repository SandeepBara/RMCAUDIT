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
<li><a href="#">Trade</a></li>
<li class="active">Level Pending Report</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			
			
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h5 class="panel-title">Level Wise Pending Report</h5>
					</div>
					<div class="panel-body">
						<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead class="bg-trans-dark text-dark">
								<tr>
									
									<th style="text-align: left;">#</th>   
									<th style="text-align: left;">Level</th>   
									<th style="text-align: center;">Total Pending(s)</th>
									<!-- <th style="text-align: center;">Action</th> -->
									
								</tr>
							</thead>
							<tbody>
								<?php
								if(isset($pending_report) && !empty($pending_report))
								{
									/*$i=0;
									foreach($pending_report as $row)
									{
									*/	?>
										<tr>
											<td> 1. </td>
											<td>Dealing Assistant</td>
											<td align="center"><?=$pending_report["da_total_pending"];?></td>
											
										</tr>
										<tr>
											<td> 2. </td>
											<td>Tax Daroga</td>
											<td align="center"><?=$pending_report["td_total_pending"];?></td>
											
										</tr>
										<tr>
											<td> 3. </td>
											<td>Section Head</td>
											<td align="center"><?=$pending_report["td_total_pending"];?></td>
											
										</tr>

										<tr>
											<td> 3. </td>
											<td>Executive Officer</td>
											<td align="center"><?=$pending_report["eo_total_pending"];?></td>
											
										</tr>
										<!-- <tr>
											<td align="center"> 
												<a href="<?php //base_url("WaterApplicationFormStatusDetailReport/LevelWisePendingList/".md5($row["receiver_user_type_id"])."/".$row["user_type"]);?>" class="btn btn-primary btn-sm">View<a>
											</td>
											
										</tr> -->
										<?php
									// }
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
