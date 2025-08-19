
<?=$this->include('layout_vertical/popup_header');?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Technical Inspection Details</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            <form method="post" id="technical_details">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Technical Inspection Details </h3>
					</div>
					<div class="panel-body table-responsive">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th class="bolder">S. No.</th>
									<th class="bolder">Particulars</th>
									<th class="bolder">Details</th>
									
                  
								</tr>
							</thead>
							
							<tbody>
								
								<tr>
									<td>1.</td>
									<td>Size of CI Distribution	</td>
									<td><?=$ae_site_inspection_details['pipeline_size'];?></td>
								</tr>
								<tr>
									<td>2.</td>
									<td>Size of Ferrule	</td>
									<td><?=$ae_site_inspection_details['ferrule_type'];?></td>
								</tr>
								<tr>
									<td>3.</td>
									<td>Water Meter with chamber and Locking Arrangment	</td>
									<td><?=$ae_site_inspection_details['water_lock_arng'];?></td>
								</tr>
								<tr>
									<td>4.</td>
									<td>Gate valve(Full Way Valve) One No. with chamber	</td>
									<td><?=$ae_site_inspection_details['gate_valve'];?></td>
								</tr>
								
								<tr>

									<td>5.</td>
									<td>GI Pipe/Blue HDPE/ PVC Shedule 80</td>
									<td><?=$ae_site_inspection_details['pipe_size'];?></td>
								</tr>
							</tbody>
						
						</table>
					</div>
				</div>
</form>



    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 
	
		//echo $this->include('layout_vertical/footer');
	
  
 ?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script>
    
$(document).ready(function () {

	
	$('#technical_details').validate({ // initialize the plugin
        rules: {

        	"ci_size":"required",
        	"ferrule_type_id":"required",
        	"water_lock_arng":"required",
        	"gate_valve":"required",
        	"pipe_size":"required",
        	
            
        }
    });
});
</script>