
<?php
	session_start();

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
            <li><a href="#">Technical Site Inspection Details</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Technical Site Inspection Details</h3>
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>S. No.</th>
									<th>Inspection Parameter</th>
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
									<td class="bolder">Pipeline Size</td>
									<td><?php echo $site_inspection_details['pipeline_size'];?></td>
								</tr>
								
								<tr>
									<td>2.</td>
				                  	<td class="bolder">Pipeline Size Diameter</td>
				                  	<td><?php echo $site_inspection_details['pipe_size'];?></td>
				                </tr>
				               
				                <tr>
				                	<td>3.</td>
				                  	<td class="bolder">Ferrule Type</td>
				                  	<td><?php echo $site_inspection_details['ferrule_type'];?></td>
				                </tr>
				                <tr>
				                	<td>4.</td>
				                  	<td class="bolder">Water Locking Arrangement</td>
				                  	<td><?php echo $site_inspection_details['water_lock_arng'];?></td>
				                </tr>
				                <tr>
				                	<td>5.</td>
				                  	<td class="bolder">Gate Valve</td>
				                  	<td><?php echo $site_inspection_details['gate_valve'];?></td>
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
		
		echo $this->include('layout_vertical/footer');
		
  		
 ?>
