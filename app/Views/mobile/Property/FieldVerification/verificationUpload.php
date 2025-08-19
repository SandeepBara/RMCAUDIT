<?=$this->include("layout_mobi/header");?>
<style>
.pad-btm{
	margin-left: 15px;;
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
															<strong> Front Image </strong>
														</div>
														<div class="col-xs-4 pad-btm">
															<img name="front_image_path_container" id="front_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
														</div>
														<div class="col-xs-4 pad-btm">
															<span id="front_image_path_latitude_span">Latitude: </span>
															<span id="front_image_path_longitude_span"><br>Longitude: </span>
															<input type="hidden" name="front_image_path_latitude_text" id="front_image_path_latitude_text" />
															<input type="hidden" name="front_image_path_longitude_text" id="front_image_path_longitude_text" />
														</div>
														<div class="col-md-4 col-xs-9 pad-btm">
															<input type="file" id="front_image_path" name="front_image_path" class="form-control" direction_type="front view" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#front_image_path_container')" />
															<input type="text" class="form-control readonly" id="front_image_path_text" required  />
														</div>
													</div>

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
															<input type="hidden" name="left_image_path_latitude_text" id="left_image_path_latitude_text" />
															<input type="hidden" name="left_image_path_longitude_text" id="left_image_path_longitude_text" />
														</div>
														<div class="col-md-4 col-xs-9 pad-btm">
															<input type="file" id="left_image_path" name="left_image_path" class="form-control" direction_type="left side" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#left_image_path_container')" />
															<input type="text" class="form-control readonly" id="left_image_path_text" required  />
														</div>
													</div>


													<div class="row">
														<div class="col-md-4 col-xs-12 pad-btm">
															<strong> Right Image </strong>
														</div>
														<div class="col-xs-4 pad-btm">
															<img name="right_image_path_container" id="right_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
														</div>
														<div class="col-xs-4 pad-btm">
															<span id="right_image_path_latitude_span">Latitude: </span>
															<span id="right_image_path_longitude_span"><br>Longitude: </span>
															<input type="hidden" name="right_image_path_latitude_text" id="right_image_path_latitude_text" />
															<input type="hidden" name="right_image_path_longitude_text" id="right_image_path_longitude_text" />
														</div>
														<div class="col-md-4 col-xs-9 pad-btm">
															<input type="file" id="right_image_path" name="right_image_path" class="form-control" direction_type="right side" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#right_image_path_container')" />
															<input type="text" class="form-control readonly" id="right_image_path_text" required  />
														</div>
													</div>


													

													
													

													<?php 
													if($vis_hoarding_board=='t')
													{
														?>
														<div class="row">
															<div class="col-md-4 col-xs-12 pad-btm">
																<strong> Hoarding Image </strong>
															</div>
															<div class="col-xs-4 pad-btm">
																<img name="hoarding_image_path_container" id="hoarding_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
															</div>
															<div class="col-xs-4 pad-btm">
																<span id="hoarding_image_path_latitude_span">Latitude: </span>
																<span id="hoarding_image_path_longitude_span"><br>Longitude: </span>
																<input type="hidden" name="hoarding_image_path_latitude_text" id="hoarding_image_path_latitude_text" />
																<input type="hidden" name="hoarding_image_path_longitude_text" id="hoarding_image_path_longitude_text" />
															</div>
															<div class="col-md-4 col-xs-9 pad-btm">
																<input type="file" id="hoarding_image_path" name="hoarding_image_path" class="form-control" direction_type="Hoarding Board" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#hoarding_image_path_container')" />
																<input type="text" class="form-control readonly" id="hoarding_image_path_text" required  />
															</div>
														</div>
														<?php 
													}
													
													
													if($vis_mobile_tower=='t')
													{
														?>
														<div class="row">
															<div class="col-md-4 col-xs-12 pad-btm">
																<strong> Tower Image </strong>
															</div>
															<div class="col-xs-4 pad-btm">
																<img name="tower_image_path_container" id="tower_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
															</div>
															<div class="col-xs-4 pad-btm">
																<span id="tower_image_path_latitude_span">Latitude: </span>
																<span id="tower_image_path_longitude_span"><br>Longitude: </span>
																<input type="hidden" name="tower_image_path_latitude_text" id="tower_image_path_latitude_text" />
																<input type="hidden" name="tower_image_path_longitude_text" id="tower_image_path_longitude_text" />
															</div>
															<div class="col-md-4 col-xs-9 pad-btm">
																<input type="file" id="tower_image_path" name="tower_image_path" class="form-control" direction_type="Mobile Tower" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#tower_image_path_container')" />
																<input type="text" class="form-control readonly" id="tower_image_path_text" required  />
															</div>
														</div>
														<?php 
													}

													if($vis_petrol_pump=='t')
													{
														?>
														<div class="row">
															<div class="col-md-4 col-xs-12 pad-btm">
																<strong> Petrol Pump </strong>
															</div>
															<div class="col-xs-4 pad-btm">
																<img name="petrol_pump_image_path_container" id="petrol_pump_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
															</div>
															<div class="col-xs-4 pad-btm">
																<span id="petrol_pump_image_path_latitude_span">Latitude: </span>
																<span id="petrol_pump_image_path_longitude_span"><br>Longitude: </span>
																<input type="hidden" name="petrol_pump_image_path_latitude_text" id="petrol_pump_image_path_latitude_text" />
																<input type="hidden" name="petrol_pump_image_path_longitude_text" id="petrol_pump_image_path_longitude_text" />
															</div>
															<div class="col-md-4 col-xs-9 pad-btm">
																<input type="file" id="petrol_pump_image_path" name="petrol_pump_image_path" class="form-control" direction_type="Petrol Pump" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#petrol_pump_image_path_container')" />
																<input type="text" class="form-control readonly" id="petrol_pump_image_path_text" required  />
															</div>
														</div>
														<?php 
													}

													if($vis_water_harvesting=='t')
													{
														?>
														<div class="row">
															<div class="col-md-4 col-xs-12 pad-btm">
																<strong> Water Harvesting </strong>
															</div>
															<div class="col-xs-4 pad-btm">
																<img name="water_harvesting_image_path_container" id="water_harvesting_image_path_container" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
															</div>
															<div class="col-xs-4 pad-btm">
																<span id="water_harvesting_image_path_latitude_span">Latitude: </span>
																<span id="water_harvesting_image_path_longitude_span"><br>Longitude: </span>
																<input type="hidden" name="water_harvesting_image_path_latitude_text" id="water_harvesting_image_path_latitude_text" />
																<input type="hidden" name="water_harvesting_image_path_longitude_text" id="water_harvesting_image_path_longitude_text" />
															</div>
															<div class="col-md-4 col-xs-9 pad-btm">
																<input type="file" id="water_harvesting_image_path" name="water_harvesting_image_path" class="form-control" direction_type="Water Harvesting" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#water_harvesting_image_path_container')" />
																<input type="text" class="form-control readonly" id="water_harvesting_image_path_text" required  />
															</div>
														</div>
														<?php 
													}
													?>   
													<div class="row text-center">
														<input type="submit" name="submit" value="Save" class="btn btn-primary"/>
													</div>													
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

<script src="<?=base_url();?>/public/assets/js/exif.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/browser-image-compression@1.0.17/dist/browser-image-compression.js"></script>
<script>
$(".readonly").on('keydown paste focus mousedown', function(e){
	if(e.keyCode != 9) // ignore tab
		e.preventDefault();
});
var default_image = '<?=base_url();?>/public/assets/img/upload.png';
function readURL(input, target)
{
	if(target=='')
	{
		target='#'+input.id+'_container';
	}
	//console.log(input.id);console.log(input);
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
					document.getElementById(input.id + "_text").value = latitude + '@' + longitude  ;
					
					displayImageInContiner(target, input.id);
					compressImage(input);
					uploadImage(input);
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

function uploadImage(input)
{
	var input_btn_id=input.id;
	var files = $('#'+ input_btn_id)[0].files;

	var fd = new FormData();
	fd.append('file', files[0]);
	fd.append('saf_dtl_id', '<?=$Saf_detail["id"];?>');
	fd.append('latitude', $("#"+ input_btn_id + "_latitude_text").val());
	fd.append('longitude', $("#"+ input_btn_id + "_longitude_text").val());
	fd.append('direction_type', $("#"+ input_btn_id).attr("direction_type"));

	//console.log(input_btn_id);
	$("#"+input_btn_id+"_text").val("Your image is being uploaded");
	$.ajax({
				url: '<?=base_url();?>/SafVerification/uploadGeoTagImg_Ajax',
				type: 'POST',
				data: fd,
				enctype: 'multipart/form-data',
				contentType:false,
				cache:false,
				processData:false,
              success: function(response){
				  var data = JSON.parse(response);
				  if(data.status==true){
					//modelInfo(data.message);
					$("#"+input_btn_id+"_text").val(data.message);
				  }
				  else{
					modelInfo(data.message);
					$("#"+input_btn_id+"_text").val(null);
				  }
                
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

function compressImage (event, useWebWorker=true) 
{
	var file = event.files[0];
	var logDom, progressDom
	console.log('input', file)
	imageCompression.getExifOrientation(file).then(function (o) 
	{
		console.log('ExifOrientation', o)
	})

	var options = {
		maxSizeMB: parseFloat(1),//mb
		maxWidthOrHeight: parseFloat(1024), //maxWidthOrHeight px 
		useWebWorker: useWebWorker,
		//onProgress: onProgress
	}
	imageCompression(file, options)
	.then(function (output) {
		//console.log('output_my', output)
		//uploadToServer(output,event.id)
		return output;
	});
}
</script>
