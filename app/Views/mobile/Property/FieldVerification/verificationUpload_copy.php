<?=$this->include("layout_mobi/header");?>
<style>
	.row{
		margin: 0px !important;
	}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Field Verification Upload</h3>
			</div>
            <div class="panel-body">
                <div class="row">

								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is :</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$Saf_detail["saf_no"];?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;">Application Type:   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$Saf_detail["assessment_type"];?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;">Applied Date :   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$Saf_detail["apply_date"];?></span>
								</div>
							
                                       
							<div class="form-group">
								<div class="col-sm-12">
									<div class="panel panel-bordered">
										<div class="panel-heading"  style="background:#1b8388f7;">
											<h3 class="panel-title">Upload Image</h3>

										</div>
										<div class="panel-body">
											<form class="form-horizontal" method="post" enctype="multipart/form-data">
												<div class="form-group">
													
													<div class="row">
														<div class="col-md-4 col-xs-12 pad-btm">
															<strong> Left Image </strong>
														</div>
														<div class="col-xs-4 pad-btm">
															<img name="left_image_path_container" id="left_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
														</div>
														<div class="col-xs-4 pad-btm">
															<span id="left_image_path_latitude_span">Latitude: </span>
															<span id="left_image_path_longitude_span"><br>Longitude: </span>
															<input type="" name="left_image_path_latitude_text" id="left_image_path_latitude_text" />
															<input type="" name="left_image_path_longitude_text" id="left_image_path_longitude_text" />
														</div>
														<div class="col-md-4 col-xs-9 pad-btm">
															<input type="file" id="left_image_path" name="left_image_path" class="form-control" direction_type="right side" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#left_image_path_container')" />
															<input type="text" class="form-control" id="left_image_path_text" required readonly />
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-4 col-sm-12 pad-btm">
															<strong> Right Image </strong>
														</div>
														
														<div class="col-md-4 col-sm-12 pad-btm">
															<input type="file" id="right_image_path" name="right_image_path" class="form-control" value="" accept="image/*" capture="camera"  style="width:250px;" >
														</div>
														<div class="col-md-4 col-sm-12 pad-btm">
															<button class="btn btn-sm btn-danger" id="btn_right_img_upload" name="btn_right_img_upload" type="submit">Upload</button>
														</div>
													</div>
													
													<div class="row">
														<div class="col-md-4 col-sm-12 pad-btm">
															<strong> Front Image </strong>
														</div>
														
														<div class="col-md-4 col-sm-12 pad-btm">
															<input type="file" id="front_image_path" name="front_image_path" class="form-control" value="" accept="image/*" capture="camera"   style="width:250px;">
														</div>
														<div class="col-md-4 col-sm-12 pad-btm">
															<button class="btn btn-sm btn-danger" id="btn_front_img_upload" name="btn_front_img_upload" type="submit">Upload</button>
														</div>
													</div>
													

													<?php 
													if($vis_hoarding_board=='t')
													{
														?>
														<div class="row">
														<div class="col-md-4 col-sm-12 pad-btm">
															<strong> Hoarding Image </strong>
														</div>
														<?php
														if(isset($hoarding_image_exists))
														{
															if(empty($hoarding_image_exists))
															{
																?>
																<div class="col-md-4 col-sm-12 pad-btm">
																	<input type="file" id="hoarding_image_path" name="hoarding_image_path" class="form-control" value="" accept="image/*" capture="camera"  style="width:250px;" >
																</div>
																<div class="col-md-4 col-sm-12 pad-btm">
																	<button class="btn btn-sm btn-danger" id="btn_hoarding_img_upload" name="btn_hoarding_img_upload" type="submit">Upload</button>
																</div>
																<?php 
															}
															else
															{
																?>
																<div class="col-md-4 col-sm-12 pad-btm">
																	<img id="imageresource" src="<?=base_url();?>/writable/uploads<?=$hoarding_image_exists[0]["image_path"];?>" style="width: 80px; height: 80px;">
																</div>
																<?php 
															}
														}
														?>
														</div>
														<?php 
													}
													
													
													if($vis_mobile_tower=='t')
													{
														?>
														<div class="row">
															<div class="col-md-4 col-sm-12 pad-btm">
																<strong> Mobile Tower Image </strong>
															</div>
															<?php
															if(isset($tower_image_exists)):
																if(empty($tower_image_exists)):
																	?>
																	<div class="col-md-4 col-sm-12 pad-btm">
																		<input type="file" id="tower_image_path" name="tower_image_path" class="form-control" value="" accept="image/*" capture="camera"  style="width:250px;" >
																	</div>
																	<div class="col-md-4 col-sm-12 pad-btm">
																		<button class="btn btn-sm btn-danger" id="btn_tower_img_upload" name="btn_tower_img_upload" type="submit">Upload</button>
																	</div>
																	<?php 
																else:
																	?>
																	<div class="col-md-4 col-sm-12 pad-btm">
																		<img id="imageresource" src="<?=base_url();?>/writable/uploads<?=$tower_image_exists[0]["image_path"];?>" style="width: 80px; height: 80px;">
																	</div>
																	<?php 
																endif;
															endif;
															?>
														</div>
														
														<?php 
													}

													if($vis_petrol_pump=='t')
													{
														?>
														<div class="row">  
															<div class="col-md-4 col-sm-12 pad-btm">
																<strong> Petrol Pump Image </strong>
															</div>
															<?php
															if(isset($petrol_pump_image_exists)):
															if(empty($petrol_pump_image_exists)):
															?>
															<div class="col-md-4 col-sm-12 pad-btm">
																<input type="file" id="tower_image_path" name="petrol_pump_image_path" class="form-control" value="" accept="image/*" capture="camera"  style="width:250px;" >
															</div>
															<div class="col-md-4 col-sm-12 pad-btm">
																<button class="btn btn-sm btn-danger" id="btn_petrol_pump_img_upload" name="btn_petrol_pump_img_upload" type="submit">Upload</button>
															</div>
															<?php else: ?>
															<div class="col-md-4 col-sm-12 pad-btm">
																<img id="imageresource" src="<?=base_url();?>/writable/uploads<?=$petrol_pump_image_exists[0]["image_path"];?>" style="width: 80px; height: 80px;">
															</div>
															<?php endif;  ?>
															<?php endif;  ?>
														</div>
														<?php 
													}

													if($vis_water_harvesting=='t')
													{
														?>
														<div class="row">  
															<div class="col-md-4 col-sm-12 pad-btm">
																<strong> Water Harvesting Image </strong>
															</div>
															<?php
															if(isset($harvesting_image_exists)):
															if(empty($harvesting_image_exists)):
															?>
															<div class="col-md-4 col-sm-10 pad-btm">
																<input type="file" id="harvesting_image_path" name="harvesting_image_path" class="form-control" value="" accept="image/*" capture="camera"  style="width:250px;" >
															</div>
															<div class="col-md-4 col-sm-12 pad-btm">
																<button class="btn btn-sm btn-danger" id="btn_harvesting_img_upload" name="btn_harvesting_img_upload" type="submit">Upload</button>
															</div>
															<?php else: ?>
															<div class="col-md-4 col-sm-12 pad-btm">
																<img id="imageresource" src="<?=base_url();?>/writable/uploads<?=$harvesting_image_exists[0]["image_path"];?>" style="width: 80px; height: 80px;">
															</div>
															<?php endif;  ?>
															<?php endif;  ?>
														</div>
													
														<?php 
													}
													?>   
																											
												</div>
											<form>  
										</div>
									</div>
								</div>					                        
							</div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script type="text/javascript">
$(document).ready( function ()
{

     $("#btn_left_img_upload").click(function() {
        var process = true;

        var left_image_path = $("#left_image_path").val();
        if (left_image_path == '') {
            $("#left_image_path").css({"border-color":"red"});
            $("#left_image_path").focus();
            process = false;
        }
		if(process==true){
			$('#btn_left_img_upload').html('uploading...')
		}

        return process;
    });
     $("#btn_right_img_upload").click(function() {
        var process = true;

        var right_image_path = $("#right_image_path").val();
        if (right_image_path == '') {
            $("#right_image_path").css({"border-color":"red"});
            $("#right_image_path").focus();
            process = false;
        }
		if(process==true){
			$('#btn_right_img_upload').html('uploading...')
		}

        return process;
    });
    $("#btn_front_img_upload").click(function() {
        var process = true;


        var front_image_path = $("#front_image_path").val();
        if (front_image_path == '') {
            $("#front_image_path").css({"border-color":"red"});
            $("#front_image_path").focus();
            process = false;
        }

		if(process==true){
			$('#btn_front_img_upload').html('uploading...')
		}
        return process;
    });
    $("#left_image_path").change(function(){$(this).css('border-color','');});
    $("#right_image_path").change(function(){$(this).css('border-color','');});
    $("#front_image_path").change(function(){$(this).css('border-color','');});

});
</script>
<script src="<?=base_url();?>/public/assets/js/exif.js"></script>
<script>
var default_image = '<?=base_url();?>/public/assets/img/upload.png';
function readURL(input, target)
{
	
	console.log(input.id);
	document.getElementById(input.id + "_latitude_span").innerHTML = "";
	document.getElementById(input.id + "_longitude_span").innerHTML = "";
	
	document.getElementById(input.id + "_latitude_text").value = null;
	document.getElementById(input.id + "_longitude_text").value = null;

	try
	{
		if (input.files && input.files[0])
		{
			var fileName = input.value;
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
			ext = ext.toLowerCase();
			if (ext == "jpg" || ext == "jpeg")
			{
				//alert(input.files[0].size);
				var file_size = 5 * 1024 * 1024;
				if (!(input.files[0].size <= file_size)) 
				{
					alert("File size must be upto " + file_size + " MB. ");
					input.value = "";
					$(target).attr('src', default_image).height(150);
					return false;
				}
			}
			else
			{
				alert("Upload JPG/JPEG image only.");
				input.focus();
				input.value = "";
				$(target).attr('src', default_image).height(150);
				return false;
			}
			EXIF.getData(input.files[0], function () {
				var lat = EXIF.getTag(this, "GPSLatitude");
				var long = EXIF.getTag(this, "GPSLongitude");
				if (lat != null && long != null) {
					var lat_ref = EXIF.getTag(this, "GPSLatitudeRef");
					var x = lat_ref == "S" ? -1 : 1;
					var latitude = ((lat[0]) + (lat[1] / 60) + (lat[2] / 3600)) * x;

					var long_ref = EXIF.getTag(this, "GPSLongitudeRef");
					var y = long_ref == "W" ? -1 : 1;
					var longitude = ((long[0]) + (long[1] / 60) + (long[2] / 3600)) * y;
					//alert("Latitude : " + latitude + " ----- Longitude : " + longitude);
					document.getElementById(input.id + "_latitude_span").innerHTML = "Latitude : " + latitude;
					document.getElementById(input.id + "_longitude_span").innerHTML = "<br>Longitude : " + longitude;

					document.getElementById(input.id + "_latitude_text").value = latitude;
					document.getElementById(input.id + "_longitude_text").value = longitude;
					uploadImage(input);
					document.getElementById(input.id + "_text").value = latitude + '@' + longitude  ;

					//document.getElementById(storelonglat).value = latitude + "@" + longitude;;
					//GetAddress(latitude, longitude, address);
					
					displayImageInContiner(target, input.id);
					
					var reader = new FileReader();

					reader.onload = function (e) {
						$(target)
							.attr('src', e.target.result)
							.height(150);
					};

					reader.readAsDataURL(input.files[0]);
				}
				else {
					alert("Image does not contains GPS Coordinate. Please turn enable geo-tagging in your camera.");
					input.focus();
					input.value = "";
					$(target).attr('src', default_image).height(150);
					return false;
				}
			});
		}
		else {
			$(target).attr('src', default_image).height(150);
		}
	}
	catch (err) {
		alert(err.message);
	}
}

function uploadImage(input){
	
	debugger;
	var input_btn_id=input.id;
	var files = $('#'+ input_btn_id)[0].files;

	var fd = new FormData();
	fd.append('file', files[0]);
	fd.append('saf_dtl_id', '<?=$Saf_detail["id"];?>');
	fd.append('latitude', $("#"+ input_btn_id + "_latitude_text").val());
	fd.append('longitude', $("#"+ input_btn_id + "_longitude_text").val());
	fd.append('direction_type', $("#"+ input_btn_id).attr("direction_type"));

	console.log(fd);

	$.ajax({
				url: '<?=base_url();?>/SafVerification/uploadGeoTagImg_Ajax',
				type: 'POST',
				data: fd,
				enctype: 'multipart/form-data',
				contentType:false,
				cache:false,
				processData:false,
              success: function(response){
                 
              },
           });
}

function displayImageInContiner(image_view_id, file_element_name)
{
	//read file object and extract image as encoded url
	//alert(image_view_id);
	file = document.querySelector('input[name="'+file_element_name+'"]').files[0];
	preview = document.querySelector(image_view_id);
	
}
</script>