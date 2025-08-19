<style >
.error
{
    color: red;
}
</style>
<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
	<div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">View Site Inspection Details</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								  <tr>
									<th colspan="2" class="text-center">
									  <b>Self Assessed</b>
									</th>
								</tr>
							</thead>
							<tbody >
								<tr>
									<td>Ward No.</td>
									<td><strong><?php echo $connection_details['ward_no'];?></strong></td>
								</tr>
								<tr>
									<td>Pipeline Type</td>
									<td><strong><?php echo $connection_details['pipeline_type'];?></strong></td>
								</tr>
								<tr>
									<td>Property Type</td>
									<td><strong><?php echo $connection_details['property_type'];?></strong></td>
								</tr>
								<tr>
									<td>Connection type</td>
									<td><strong><?php echo $connection_details['connection_type'];?></strong></td>
								</tr>
								<tr>
									<td>Connection Through</td>
									<td><strong><?php echo $connection_details['connection_through'];?></strong></td>
								</tr>
								<tr>
									<td>Category</td>
									<td><strong><?php echo $connection_details['category'];?></strong></td>
								</tr>
								<tr>
									<td>Area in Sqft.</td>
									<td><strong><?php echo $connection_details['area_sqft'];?></strong></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								  <tr>
									<th colspan="2"  class="text-center">
									  <b>Verified</b>
									</th>
								</tr>
							</thead>
							<tbody >
								<tr>
									<td>Ward No.</td>
									<td><strong><?php echo $site_inspection_details['ward_no'];?></strong></td>
								</tr>
								<tr>
									<td>Pipeline Type</td>
									<td><strong><?php echo $site_inspection_details['pipeline_type'];?></strong></td>
								</tr>
								<tr>
									<td>Property Type</td>
									<td><strong><?php echo $site_inspection_details['property_type'];?></strong></td>
								</tr>
								<tr>
									<td>Connection Type</td>
									<td><strong><?php echo $site_inspection_details['connection_type'];?></strong></td>
								</tr>
								<tr>
									<td>Connection Through</td>
									<td><strong><?php echo $site_inspection_details['connection_through'];?></strong></td>
								</tr>
								<tr>
									<td>Category</td>
									<td><strong><?php echo $site_inspection_details['category'];?></strong></td>
								</tr>
								<tr>
									<td>Area in Sqft.</td>
									<td><strong><?php echo $site_inspection_details['area_sqft'];?></strong></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pad-btm text-center">
						<a href="<?php echo base_url('WaterMobileIndex/search_consumer');?>" type="button"class="btn btn-primary btn-labeled">Go to Home</a>
					</div>
				</div>
       
        
    </div>
</div>
</div>
</div>

<?= $this->include('water/footer');?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

    $('#form_tc_verification').validate({ // initialize the plugin
       

        rules: {
            pipeline_type_id: {
                required: true,
                
            },
            property_type_id: {
                required: true,
               
            },
            connection_type_id: {
                required: true,
               
            },
            connection_through_id: {
                required: true,
               
            },
            category: {
                required: true,
               
            },
            ward_id: {
                required: true,
               
            },
            area_sqft: {
                required: true,
               
            },
        }


    });

});
</script>
