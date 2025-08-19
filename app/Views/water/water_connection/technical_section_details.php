
<?=$this->include('layout_vertical/popup_header');?>
<style type="text/css">
	.error
	{
		color: red;
	}
	#footer{
		display: none;
	}
</style>

<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
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
            <form method="post" id="technical_details" enctype="multipart/form-data">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Technical Inspection Details </h3>
					</div>
					<div class="panel-body">
						<?php
							if(isset($error))
							{
								foreach($error as $val)
								{
								?>
									<div class="error"><?=$val?></div>
								<?php
								}
							}
						?>
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
									<td><input type="text" name="ci_size" id="ci_size" value="<?php echo (isset($ae_site_inspection_details))?$ae_site_inspection_details['pipeline_size']:$site_inspection_details['pipeline_size']; ?>" class="form-control" required></td>
								</tr>
								<tr>
									<td>2.</td>
									<td>Size of Ferrule	</td>
									<td>
									<select name="ferrule_type_id" id="ferrule_type_id" class="form-control" required>
										<option value="">Select</option>
										<?php
											if($ferrule_list):
												foreach($ferrule_list as $val):
										?>
										<option value="<?php echo $val['id'];?>" <?php if($val['id']==$ae_site_inspection_details['ferrule_type_id'] or $site_inspection_details['ferrule_type_id']==$val['id']){ echo "selected"; }?>><?php echo $val['ferrule_type'];?></option>
										<?php
												endforeach;
											endif;
										?>
									</select>
									</td>
								</tr>
								<?php //print_r($ae_site_inspection_details);?>
								<tr>
									<td>3.</td>
									<td>Water Meter with chamber and Locking Arrangment	</td>
									<td><input type="text" name="water_lock_arng" id="water_lock_arng"  value="<?php echo (isset($ae_site_inspection_details))?$ae_site_inspection_details['water_lock_arng']:""; ?>" class="form-control" required></td>
								</tr>
								<tr>
									<td>4.</td>
									<td>Gate valve(Full Way Valve) One No. with chamber	</td>
									<td><input type="text" name="gate_valve" id="gate_valve" value="<?php echo (isset($ae_site_inspection_details))?$ae_site_inspection_details['gate_valve']:""; ?>" class="form-control" required></td>
								</tr>
								
								<tr>

									<td>5.</td>
									<td>GI Pipe/Blue HDPE/ PVC Shedule 80</td>
									<td>

										<select class="form-control" name="pipe_size" id="pipe_size" required>
											<option value="15 MM" <?php if("15 MM"==$ae_site_inspection_details['pipe_size'] or $site_inspection_details['pipe_size']=="15 MM"){ echo "selected"; }?>>15 MM</option>

											<option value="20 MM" <?php if("20 MM"==$ae_site_inspection_details['pipe_size'] or $site_inspection_details['pipe_size']=="20 MM"){ echo "selected"; }?>>20 MM</option>

											<option value="25 MM" <?php if("25 MM"==$ae_site_inspection_details['pipe_size'] or $site_inspection_details['pipe_size']=="25 MM"){ echo "selected"; }?>>25 MM</option>
										</select>

									</td>
								</tr>
								<!-- <tr>

									<td>6.</td>
									<td>Meter No.</td>
									<td>
										<input type='text' class="form-control" placeholder="Meter No." id="meter_no" name="meter_no" onkeypress="return isAlphaNumSh(event)" required/>
									</td>
								</tr>
								<tr>

									<td>8.</td>
									<td>Meter Image.</td>
									<td>
										<input type='file' class="form-control" id="meter_img" name="meter_img" onchange=" return fileType(this)" required/>
									</td>
								</tr>
								<tr>

									<td>9.</td>
									<td>Initial Meter Reading</td>
									<td>
										<input type='text' class="form-control" placeholder="Initial Reading" id="init_reading" name="init_reading" onkeypress="return isNumDot(event)" required/>
									</td>
								</tr> -->

								<tr>
									
									<td colspan="3" style="text-align: center;"><input type="submit" name="save" id="save" class="btn btn-success" ></td>

								</tr>
							</tbody>
						
						</table>
					</div>
				</div>
			</form>



    </div><!--End page content-->
</div>


<!--END CONTENT CONTAINER-->
<?php 
	
		echo $this->include('layout_vertical/footer');
	
  
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
			// "meter_no":{
			// 	required:true,
			// 	isAlphaNumCheck:true,
			// },
			// "init_reading":{
			// 	required:true,
			// 	isNumDotCheck:true,
			// },
        	
            
        }
    });
});

function isNumDot(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46){
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }else{
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }
	function isNumDotCheck(val){
        var regex = /^[1-9]\d*(((,\d{3}){1})?(\.\d{1,8})?)$/;
        if (!val.match(regex)) return false;
        return true;
    }
	function isAlphaNumCheck(val){
        var regex = /^[a-z0-9]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
	function fileType(fname)
	{
		var f = fname.value;
		var ext = f.substring(f.lastIndexOf('.') + 1);

		if(ext =="jpg" || ext=="jpeg"|| ext=='pdf')
		{
			return true;
		}
		else
		{
			alert("Upload jpg,jpeg,pdf Images only");
			fname.value='';
			return false;
		}
	}
	function isAlphaNumSh(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 47 || e.which > 57))
            return false;
    }
</script>