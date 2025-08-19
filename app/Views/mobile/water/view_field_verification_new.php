<style >
.error
{
    color: red;
}
</style>
<?=$this->include("layout_mobi/header");?>
<script type="text/javascript">
  function OperateDropDown(radio, control, hidden) {

        /*alert(radio);
        alert(control);
        alert(hidden);*/



        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        //alert(hid_val);
        //alert(rdo.value);
        //alert(ctrl);

        if (rdo.value == "1") {
            var opt = ctrl.options;
            var pos = 0;
            for (var j = 0; j < opt.length; j++) {
                if (opt[j].value == hid_val) {
                    pos = j;
                    break;
                }
            }
            ctrl.selectedIndex = pos;
            ctrl.disabled = true;
        }
        else {
            ctrl.selectedIndex = 0;
            ctrl.disabled = false;
        }
    }


    function OperateTexBox(radio, control, hidden) {

        /*alert(radio);
        alert(control);
        alert(hidden);*/

     
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;

       /* alert(rdo.value);
        alert(control);
        alert(hid.value);*/

        
        if (rdo.value == "1") {
            ctrl.value = hid_val;
        

            ctrl.readOnly = true;
        }
        else {
            ctrl.value = "";
            ctrl.readOnly = false;
        }


    }

  
  
</script>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
	<div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">View Site Inspection Details</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo base_url('WaterFieldVerification/save_site_inspetion');?>" id="form_tc_verification" name="FORMNAME1" method="post">
					<input type="hidden" name="corr_ward_mstr_id_t" id="corr_ward_mstr_id_t" value="<?php echo $corr_ward_mstr_id_t;?>">
					<input type="hidden" name="ward_mstr_id_v" id="ward_mstr_id_v" value="<?php echo $ward_mstr_id_v;?>">
					<input type="hidden" name="corr_property_type_t" id="corr_property_type_t" value="<?php echo $corr_property_type_t;?>">
					<input type="hidden" name="property_type_id_v" id="property_type_id_v" value="<?php echo $property_type_id_v;?>">
					<input type="hidden" name="corr_pipeline_type_t" id="corr_pipeline_type_t" value="<?php echo $corr_pipeline_type_t;?>">
					<input type="hidden" name="pipeline_type_id" id="pipeline_type_id" value="<?php echo $pipeline_type_id;?>">
					<input type="hidden" name="property_type_id" id="property_type_id" value="<?php echo $property_type_id;?>">
					<input type="hidden" name="connection_type_id" id="connection_type_id" value="<?php echo $connection_type_id;?>">
					<input type="hidden" name="connection_through_id" id="connection_through_id" value="<?php echo $connection_through_id;?>">
					<input type="hidden" name="category" id="category" value="<?php echo $category;?>">
					<input type="hidden" name="ward_id" id="ward_id" value="<?php echo $ward_mstr_id;?>">
					<input type="hidden" name="pipeline_type_id_v" id="pipeline_type_id_v" value="<?php echo $pipeline_type_id_v;?>">
					<input type="hidden" name="corr_connection_type_t" id="corr_connection_type_t" value="<?php echo $corr_connection_type_t;?>">
					<input type="hidden" name="corr_connection_type_v" id="corr_connection_type_v" value="<?php echo $corr_connection_type_v;?>">
					<input type="hidden" name="corr_connection_through" id="corr_connection_through" value="<?php echo $corr_connection_through;?>">
					<input type="hidden" name="connection_through_v" id="connection_through_v" value="<?php echo $connection_through_v;?>">
					<input type="hidden" name="corr_category" id="corr_category" value="<?php echo $corr_category;?>">
					<input type="hidden" name="corr_category_v" id="corr_category_v" value="<?php echo $corr_category_v;?>">
					<input type="hidden" name="corr_area_sqft_t" id="corr_area_sqft_t" value="<?php echo $corr_area_sqft_t;?>">
					<input type="hidden" name="corr_area_sqft_v" id="corr_area_sqft_v" value="<?php echo $corr_area_sqft_v;?>">
					<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $water_conn_id;?>">
					<input type="hidden" name="area_sqft" id="area_sqft" value="<?php echo $area_sqft;?>">
					<input type="hidden" name="pipeline_size" id="pipeline_size" value="<?php echo $pipeline_size;?>">
					<input type="hidden" name="pipeline_size_type" id="pipeline_size_type" value="<?php echo $pipeline_size_type;?>">
					<input type="hidden" name="permissible_pipe_dia" id="permissible_pipe_dia" value="<?php echo $permissible_pipe_dia;?>">
					<input type="hidden" name="permissible_pipe_qlty" id="permissible_pipe_qlty" value="<?php echo $permissible_pipe_qlty;?>">
					<input type="hidden" name="road_type" id="road_type" value="<?php echo $road_type;?>">
					<input type="hidden" name="ferrule_type_id" id="ferrule_type_id" value="<?php echo $ferrule_type_id;?>">
					


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
										<td><strong><?php echo $sa_ward_no;?></strong></td>
										<input type="hidden" name="sa_ward_no" id="sa_ward_no" value="<?php echo $sa_ward_no; ?>">
									</tr>
									<tr>
										<td>Pipeline Type</td>
										<td><strong><?php echo $sa_pipeline_type;?></strong></td>
										<input type="hidden" name="sa_pipeline_type" id="sa_pipeline_type" value="<?php echo $sa_pipeline_type; ?>">
									</tr>
									<tr>
										<td>Property Type</td>
										<td><strong><?php echo $sa_property_type;?></strong></td>
										<input type="hidden" name="sa_property_type" id="sa_property_type" value="<?php echo $sa_property_type; ?>">
									</tr>
									<tr>
										<td>Connection type</td>
										<td><strong><?php echo $sa_connection_type;?></strong></td>
										<input type="hidden" name="sa_connection_type" id="sa_connection_type" value="<?php echo $sa_connection_type; ?>">
									</tr>
								<!--	<tr>
										<td>Connection Through</td>
										<td><strong><?php echo $sa_connection_through;?></strong></td>
										<input type="hidden" name="sa_connection_through" id="sa_connection_through" value="<?php echo $sa_connection_through; ?>">
									</tr>-->
									<tr>
										<td>Category</td>
										<td><strong><?php echo $sa_category;?></strong></td>
										<input type="hidden" name="sa_category" id="sa_category" value="<?php echo $sa_category; ?>">
									</tr>
									<tr>
										<td>Area in Sqft.</td>
										<td><strong><?php echo $sa_area_sqft;?></strong></td>
										<input type="hidden" name="sa_area_sqft" id="sa_area_sqft" value="<?php echo $sa_area_sqft; ?>">
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
										<td><strong><?php echo $ward_no;?></strong></td>
									</tr>
									<tr>
										<td>Pipeline Type</td>
										<td><strong><?php echo $pipeline_type;?></strong></td>
									</tr>
									<tr>
										<td>Property Type</td>
										<td><strong><?php echo $property_type;?></strong></td>
									</tr>
									<tr>
										<td>Connection Type</td>
										<td><strong><?php echo $connection_type;?></strong></td>
									</tr>
									<!--<tr>
										<td>Connection Through</td>
										<td><strong><?php echo $connection_through;?></strong></td>
									</tr>-->
									<tr>
										<td>Category</td>
										<td><strong><?php echo $category;?></strong></td>
									</tr>
									<tr>
										<td>Area in Sqft.</td>
										<td><strong><?php echo $area_sqft;?></strong></td>
									</tr>
									<tr>
										<td>Distribution Pipeline Size (In MM)  </td>
										<td><strong><?php echo $pipeline_size;?></strong></td>
									</tr>
									<tr>
										<td>Distribution Pipeline Size Type </td>
										<td><strong><?php echo $pipeline_size_type;?></strong></td>
									</tr>
									<tr>
										<td>Permissible Pipe Diameter </td>
										<td><strong><?php echo $permissible_pipe_dia;?></strong></td>
									</tr>
									<tr>
										<td>Permissible Pipe Quality </td>
										<td><strong><?php echo $permissible_pipe_qlty;?></strong></td>
									</tr>
									<tr>
										<td>Permissible Ferule Size</td>
										<td><strong><?php echo $ferrule_type;?></strong></td>
									</tr>
									<tr>
										<td>Road Type</td>
										<td><strong><?php echo $road_type;?></strong></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pad-btm text-center">
							<button type="submit" id="Save" name="Save" class="form-control btn btn-primary" value="Save" onclick="waiting_show()">Save</button>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pad-btm text-center">
							<button type="submit" id="back" name="back" class="form-control btn btn-primary" value="Go Back" onclick="waiting_show()">Go Back</button>
						</div>
						<center id="wait" style="color:red; display:none" >Please wait, your request is being processed...</center>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?= $this->include('layout_mobi/footer');?>

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
function waiting_show()
{
	// alert();
	// $('#back').attr('disabled',true);
	$('#Save').hide();	
	$('#back').hide();							
	$('#wait').show();		
	
	// $('#Save').attr('disabled',true);
	// confirm();	
}
</script>
