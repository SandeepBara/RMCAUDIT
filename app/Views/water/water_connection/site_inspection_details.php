
<?php
	// session_start();
	if(session_id() == ''){
		session_start();
	 }

 echo $this->include('layout_vertical/popup_header');
 
  

?>
<!--<style type="text/css">
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
  .bolder{font-weight: bold;}
  
</style>-->
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">View Site Inspection Details</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Site Inspection Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12">
								<label class="col-sm-3">Inspection By </label><b><?=$site_inspection_details['emp_name'];?> (<?=$site_inspection_details['verified_by'];?>)</b>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<label class="col-sm-3">Date of Verification </label><b><?=$site_inspection_details['created_on'];?></b>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 text-danger text-bold">
								<u>Basic Details</u>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<label class="col-sm-3">Application No</label><label class="col-sm-3"><b><?=$site_inspection_details['application_no'];?></b></label>
								<label class="col-sm-3">Applied Date</label><label class="col-sm-3"><b><?=$site_inspection_details['apply_date'];?></b></label>
								<label class="col-sm-3">Ward No</label><label class="col-sm-3"><b><?=$site_inspection_details['app_ward_no'];?></b></label>
								<label class="col-sm-3">Owner(s) Name</label><label class="col-sm-3"><b><?=$site_inspection_details['applicant_name'];?></b></label>
								<label class="col-sm-3">Mobile No</label><label class="col-sm-3"><b><?=$site_inspection_details['mobile_no'];?></b></label>
							</div>
						</div>	
						
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>S. No.</th>
									<th>Inspection Parameter</th>
									<th>Application Details</th>
									<th>Check</th>
									<th>Inspection Details</th>
								</tr>
							
							</thead>
							<tbody>
								<?php
                				//print_r($site_inspection_details);
								if($site_inspection_details)
								{

								?>
								<tr>
									<td>1.</td>
									<td class="bolder">Property Type</td>
									<td class="bolder"><?php echo $site_inspection_details['app_property_type'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["property_type_campaire"] =="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td class="bolder"><?php echo $site_inspection_details['property_type'];?></td>
								</tr>
								<tr>
									<td>2.</td>
									<td class="bolder">Pipeline Type</td>
									<td class="bolder"><?php echo $site_inspection_details['app_pipeline_type'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["pipeline_type_campaire"] =="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td><?php echo $site_inspection_details['pipeline_type'];?></td>
								</tr>
								<tr>
									<td>3.</td>
									<td class="bolder">Connection Type</td>
									<td class="bolder"><?php echo $site_inspection_details['app_connection_type'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["connection_type_campaire"] =="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td><?php echo $site_inspection_details['connection_type'];?></td>
								</tr>
								<tr>
									<td>4.</td>
									<td class="bolder">Area in Sqft.</td>
									<td class="bolder"><?php echo $site_inspection_details['app_area_sqft'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["area_sqft_campaire"] =="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td><?php echo $site_inspection_details['area_sqft']??0.00;?></td>
								</tr>
								<tr>
									<td>5.</td>
									<td class="bolder">Area in Sqmt.</td>
									<td class="bolder"><?php echo $site_inspection_details['app_area_sqmt'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["area_sqmt_campaire"] =="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td><?php echo $site_inspection_details['area_sqmt']??0.00;?></td>
								</tr>
								<tr>
									<td>6.</td>
									<td class="bolder">Connection Through</td>
									<td class="bolder"><?php echo $site_inspection_details['app_connection_through'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["connection_through_campaire"] =="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td><?php echo $site_inspection_details['connection_through'];?></td>
								</tr>
								<tr>
									<td>7.</td>
									<td class="bolder">Category</td>
									<td class="bolder"><?php echo $site_inspection_details['app_category'];?></td>
									<td><img src="<?=base_url('public/assets/img');?>/<?=($site_inspection_details["category_campaire"]=="t")?"correct":"incorrect";?>.png" style="height: 25px" /></td>
									<td><?php echo $site_inspection_details['category'];?></td>
								</tr>
								<tr>
									<td>8.</td>
									<td colspan="3" class="bolder">Pipeline Size</td>
									<td ><?php echo $site_inspection_details['pipeline_size'];?></td>
								</tr>
								<tr>
									<td>9.</td>
									<td colspan="3" class="bolder">Pipeline Size Type</td>
									<td ><?php echo $site_inspection_details['pipeline_size_type'];?></td>
								</tr>
								<tr>
									<td>10.</td>
				                  	<td colspan="3" class="bolder">Pipeline Size Diameter</td>
				                  	<td ><?php echo $site_inspection_details['pipe_size'];?></td>
				                </tr>
				                <tr>
				                	<td>11.</td>
				                  	<td colspan="3" class="bolder">Pipeline Size Quality</td>
				                  	<td ><?php echo $site_inspection_details['pipe_type'];?></td>
				                </tr>
				                <tr>
				                	<td>12.</td>
				                  	<td colspan="3" class="bolder">Ferrule Type</td>
				                  	<td ><?php echo $site_inspection_details['ferrule_type'];?></td>
				                </tr>
				                <tr>
				                	<td>13.</td>
				                  	<td colspan="3" class="bolder">Road Type</td>
                  					<td ><?php echo $site_inspection_details['road_type'];?></td>
								</tr>

								
								<?php
							
								}
								?>
							</tbody>
						</table>
					</div>
				</div>




    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 		
		//echo $this->include('layout_vertical/footer');		
  		
 ?>
