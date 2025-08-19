<?=$this->include("layout_mobi/header");?>
<style>
.error {
    color: red;
}
.boldIn{
    font-weight: bold; font-size: 14px; color: #1b0079
}

/* Set the size of the div element that contains the map */
#geo_tagging_map {
  height: 400px;
  /* The height is 400 pixels */
  width: 100%;
  /* The width is the width of the web page */
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Water Survey</h3>
			</div>
			<div class="panel-body">
				<form method="post" action=""  enctype="multipart/form-data">
                    <?php 
                        if(isset($validation))
                        { 
                            ?>
                                <?= $validation->listErrors(); ?>
                            <?php 
                        } 
                    ?>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6">
                                <b>Consumer Number:</b>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="consumer_no" id="consumer_no" value="<?=(isset($_POST["consumer_no"])|| isset($consumer_no))?($_POST["consumer_no"]??$consumer_no):"";?>" class="form-control" required >
                                <input type="hidden" name="consumer_id" id="consumer_id" value="<?=isset($_POST["consumer_id"])?$_POST["consumer_id"]:"";?>" class="form-control">
                            </div>
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">                            
                            <div class="col-sm-6">
                                <b>Holding ID Mapped or Not:</b>
                            </div>
                            <div class="col-sm-6">
                                    <select name="holding_map" id = "holding_map" class="form-control" required onchange="holding_div_show(this.value)">
                                            <option value="" <?=isset($_POST["consumer_id"]) && $_POST["holding_map"]==""?"checked":"";?>>Select</option>
                                            <option value="1" <?=isset($_POST["consumer_id"]) && $_POST["holding_map"]=="1"?"checked":"";?>>Yes</option>
                                            <option value="0" <?=isset($_POST["consumer_id"]) && $_POST["holding_map"]=="2"?"checked":"";?>>No</option>
                                    </select>
                            </div>
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="holding_div">                            
                            <div class="col-sm-6"><b>Holding No:</b> </div>
                            <div class="col-sm-6">
                                <input type = "text" name="holding_no" id="holding_no" class="form-control" value="<?=isset($_POST["holding_no"])? $_POST["holding_no"] :"";?>" onChange="validate_holding();"/>
                                <input type = "hidden" name="prop_id" id="prop_id" class="form-control" value="<?=isset($_POST["prop_id"])? $_POST["prop_id"] :"";?>" />
                                <input type = "hidden" name="saf_id" id="saf_id" class="form-control" value="<?=isset($_POST["saf_id"])? $_POST["saf_id"] :"";?>" />
                            </div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="consumer_address_div">                            
                            <div class="col-sm-6"><b>Water Connect Address :</b> </div>
                            <div class="col-sm-6" id="water_address"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="consumer_ward_div">                            
                            <div class="col-sm-6"><b>Water Connect Ward no :</b> </div>
                            <div class="col-sm-6" id="water_ward_no"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="consumer_owner_div">                            
                            <div class="col-sm-6"><b>Water Owneres Name :</b> </div>
                            <div class="col-sm-6" id="water_owners_name"></div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="consumer_owner_modi_div">                            
                            <div class="col-sm-6"><b>Water Owneres Mobile No :</b> </div>
                            <div class="col-sm-6" id="water_owners_modi"></div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="holding_address_div">                            
                            <div class="col-sm-6"><b>Holding Address :</b> </div>
                            <div class="col-sm-6" id="prop_address"></div>
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="holding_ward_div">                            
                            <div class="col-sm-6"><b>Holding Ward no :</b> </div>
                            <div class="col-sm-6" id="holding_ward_no"></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="holding_owner_div">                            
                            <div class="col-sm-6"><b>Holding Owneres Name :</b> </div>
                            <div class="col-sm-6" id="prop_owners_name"></div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="holding_owner_mobi_div">                            
                            <div class="col-sm-6"><b>Holding Owneres Mobile No :</b> </div>
                            <div class="col-sm-6" id="prop_owners_modi"></div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="reason_div">                            
                            <div class="col-sm-6"><b>Reason for the Property is not Assessed:</b> </div>
                            <div class="col-sm-6">
                                <input type = "text" name="reason_not_map" id="reason_not_map" class="form-control" value="<?=isset($_POST["reason_not_map"])? $_POST["reason_not_map"] :"";?>" required onkeypress="return isAlphaNum(event);"/>
                            </div>
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6"><b>Status of Water Connection Metered or Non-Metered:</b> </div>                           
                            <div class="col-sm-6">
                                <select name="connection_type" id = "connection_type" class="form-control" required>
                                    <option value="" <?=isset($_POST["connection_type"]) && $_POST["connection_type"]==""?"checked":"";?> >Select</option>
                                    <option value="1" <?=isset($_POST["connection_type"]) && $_POST["connection_type"]=="1"?"checked":"";?> >Metered</option>
                                    <option value="3" <?=isset($_POST["connection_type"]) && $_POST["connection_type"]=="3"?"checked":"";?> >Non-Metered</option>
                                </select>
                            </div>
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="meter_no_div">
                            <div class="col-sm-6"><b>Meter No. :</b> </div>                           
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="meter_no" id ="meter_no" value="<?=isset($_POST["meter_no"])? $_POST["meter_no"] :"";?>" /> 
                            </div>
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6"><b>How many hours of water supply do you get each day :</b></div>
                            <div class="col-sm-7">
                                <input type="radio" name="supply_duration" id ="supply_duration_1" value="No Water Supply (Dry)" <?=isset($_POST["supply_duration"]) && $_POST["supply_duration"]=="No Water Supply (Dry)"?"checked":"";?> > No Water Supply (Dry) 
                                <input type="radio" name="supply_duration" id ="supply_duration_2" value="0-1 Hrs" <?=isset($_POST["supply_duration"]) && $_POST["supply_duration"]=="0-1 Hrs"?"checked":"";?>> 0-1 Hrs
                            </div>
                            <div class="col-sm-7">
                                <input type="radio" name="supply_duration" id ="supply_duration_3" value="1-2 Hrs" <?=isset($_POST["supply_duration"]) && $_POST["supply_duration"]=="1-2 Hrs"?"checked":"";?>> 1-2 Hrs
                                <input type="radio" name="supply_duration" id ="supply_duration_4" value="2-4 Hrs" <?=isset($_POST["supply_duration"]) && $_POST["supply_duration"]=="2-4 Hrs"?"checked":"";?>> 2-4 Hrs
                            </div>
                            <div class="col-sm-7">
                                <input type="radio" name="supply_duration" id ="supply_duration_5" value="more than 4 Hrs" <?=isset($_POST["supply_duration"]) && $_POST["supply_duration"]=="more than 4 Hrs"?"checked":"";?>> more than 4 Hrs 
                            </div>
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6"><b >Ever apply for disconnection :</b></div>
                            <div class="col-sm-6">                            
                                <select name="disconnection" id = "disconnection" class="form-control" onchange="disconnection_div_show(this.value)">
                                        <option value="" <?=isset($_POST["disconnection"]) && $_POST["disconnection"]==""?"checked":"";?> >Select</option>
                                        <option value="1" <?=isset($_POST["disconnection"]) && $_POST["disconnection"]=="1"?"checked":"";?>>Yes</option>
                                        <option value="0" <?=isset($_POST["disconnection"]) && $_POST["disconnection"]=="0"?"checked":"";?>>No</option>
                                </select>
                            </div>
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id = "disconnection_div">
                            <div class="col-sm-6"><b>Upload the Application:</b></div>
                            <div class="col-sm-6">
                                <input  type ="file" name="application_form" id = "application_form" class="form-control" capture="camera">
                            </div>                                    
						</div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6"><b>Status of Metered Connection is functional or No :</b></div>
                            <div class="col-sm-6">
                                <input type="radio"  name="meter_status" id ="meter_status_yes" value="1" <?=isset($_POST["meter_status"]) && $_POST["meter_status"]=="1"?"checked":"";?>> Yes
                                <input type="radio" name="meter_status" id ="meter_status_no" value="0" <?=isset($_POST["meter_status"]) && $_POST["meter_status"]=="0"?"checked":"";?>> No
                            </div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6">
                                <b>Any bill is served by TCA : </b>
                            </div>
                            <div class="col-sm-6">
                                <input type="radio" name="bill_status" id ="bill_status_yes" value="1" onClick = "last_bill_serve_date_div_show(this.value)" checked <?=isset($_POST["bill_status"]) && $_POST["bill_status"]=="1"?"checked":"";?>> Yes
                                <input type="radio" name="bill_status" id ="bill_status_no" value="0" onClick = "last_bill_serve_date_div_show(this.value)" <?=isset($_POST["bill_status"]) && $_POST["bill_status"]=="0"?"checked":"";?>> No
                            </div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id="bill_serve_date_div">
                            <div class="col-sm-6"><b>Date of Last bill served:</b></div>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" name="last_bill_serve_date" id ="last_bill_serve_date" max ="<?=date('Y-m-d')?>"  value="<?=isset($_POST["last_bill_serve_date"])?$_POST["last_bill_serve_date"]:"";?>" required/> 
                            </div>
						</div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm" id = "bill_not_serve_reason_div">
                            <div class="col-sm-6"><b>Why bill is not served: (Asked to TC )</b></div>
                            <div class="col-sm-6">
                                <!-- <textarea type="text" class="form-control" name="bill_not_serve_reason" id ="bill_not_serve_reason" onkeypress="return isAlphaNum(event);"><?=isset($_POST["bill_not_serve_reason"])?$_POST["bill_not_serve_reason"]:"";?></textarea>  -->
                                <select class="form-control" name="bill_not_serve_reason" id="bill_not_serve_reason">
                                    <option value="">select</option>
                                    <option value="Deny To Payment" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Deny To Payment" ?"selected":"";?>>Deny To Payment</option>
                                    <option value="Connection Not Availabe" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Connection Not Availabe" ?"selected":"";?>>Connection Not Availabe</option>
                                    <option value="Double Connection" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Double Connection" ?"selected":"";?>>Double Connection</option>
                                    <option value="Pipe Line Damage" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Pipe Line Damage" ?"selected":"";?>>Pipe Line Damage</option>
                                    <option value="Meter Damage"    <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Meter Damage" ?"selected":"";?> >Meter Damage</option>
                                    <option value="Meter Change"    <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Meter Change" ?"selected":"";?> >Meter Change</option>
                                    <option value="Over Reading"    <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Over Reading" ?"selected":"";?> >Over Reading</option>
                                    <option value="Drain Water Supply"  <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Drain Water Supply" ?"selected":"";?> >Drain Water Supply</option>
                                    <option value="No Water Supply" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="No Water Supply" ?"selected":"";?> >No Water Supply</option>
                                    <option value="Property Sale Out"   <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Property Sale Out" ?"selected":"";?> >Property Sale Out</option>
                                    <option value="Illegal Connectio"   <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Illegal Connection" ?"selected":"";?> >Illegal Connection</option>
                                    <option value="Address Not Found" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Address Not Found" ?"selected":"";?>>Address Not Found</option>
                                    <option value="Other Ward No" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Other Ward No" ?"selected":"";?>>Other Ward No</option>
                                    <option value="Miss Behave And Not Interested" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Miss Behave And Not Interested" ?"selected":"";?>>Miss Behave And Not Interested</option>
                                    <option value="Citizen Not Available Or Door Locked" <?=isset($_POST["bill_not_serve_reason"]) && $_POST["bill_not_serve_reason"]=="Citizen Not Available Or Door Locked" ?"selected":"";?>>Citizen Not Available Or Door Locked</option>
                                </select>
                            </div>
						</div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
                            <div class="col-sm-6"><strong> Take Photo of active water connection: </strong></div>
                            <div class="col-xs-4 pad-btm">
                                <img name="image_path" id="image_path" src="<?=base_url();?>/public/assets/img/upload.png" style="width: 80px; height: 80px;" />
                            </div>
                            <div class="col-xs-4 pad-btm">
                                <span id="image_path_container_latitude_span">Latitude: </span>
                                <span id="image_path_container_longitude_span"><br>Longitude: </span>
                                <input type="hidden" name="file_path" id="file_path" />
                                <input type="hidden" name="image_path_container_latitude_text" id="image_path_container_latitude_text" />
                                <input type="hidden" name="image_path_container_longitude_text" id="image_path_container_longitude_text" />
                            </div>
                            <input type="file" id="image_path_container" name="image_path_container" class="form-control" direction_type="front view" accept="image/*" capture="camera"  style="width:250px;" onChange="readURL(this, '#image_path')" />
                            <input type="text" class="form-control readonly" id="image_path_container_text" required  readonly/>
                        </div>
                    </div>  
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad-btm text-center">
                            
							<button type="submit" id="submit" name="submit" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 btn btn-primary" value="submit">SUBMIT</button>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Geo tagged image on map</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div id="map" style="background: pink; height: 400px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?=$this->include("layout_mobi/footer");?>
<script type="text/javascript">
	
    function modelInfo(msg)
    {
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
        if($error=flashToast('error'))
        {
            echo "modelInfo('".$error."');";
        }
    ?>


</script>
<script src="<?=base_url();?>/public/assets/js/exif.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/browser-image-compression@1.0.17/dist/browser-image-compression.js"></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap" async defer></script> -->
<script>
    var default_image = '<?=base_url();?>/public/assets/img/upload.png';
    function readURL(input, target)
    { //debugger;
        if($("#consumer_id").val()=="")
        {
            alert("Please Enter Consumer No" );
            return;
        }
        if(target=='')
        {
            target='#'+input.id+'_container';
        }
        // console.log(input.id);console.log(input);
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
                        // PopupMap(latitude,longitude);
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
            console.log(err);
            alert(err.message);
        }
    }

    function uploadImage(input)
    {
        var input_btn_id=input.id;
        var files = $('#'+ input_btn_id)[0].files;

        var fd = new FormData();
        fd.append('file', files[0]);
        fd.append('consumer_id', $("#consumer_id").val());
        fd.append('latitude', $("#"+ input_btn_id + "_latitude_text").val());
        fd.append('longitude', $("#"+ input_btn_id + "_longitude_text").val());
        fd.append('direction_type', $("#"+ input_btn_id).attr("direction_type"));

        //console.log(input_btn_id);
        $("#"+input_btn_id+"_text").val("Your image is being uploaded");
        $.ajax({
                    url: '<?=base_url();?>/WaterMobileIndex/uploadGeoTagImg_Ajax',
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
                        $("#file_path").val(data.file_path);
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

<script>
    $(document).ready(function ()
	{
        <?php
            if(isset($consumer_no))
            {
                ?>
                search_consumer('<?=$consumer_no?>');
                $("#consumer_no").attr("readonly",true);
                <?php
            }
            if(isset($_POST['holding_no']))
            {
                ?>
                validate_holding();
                <?php
            }
        ?>
        $("#consumer_no").change(function(){
            search_consumer(this.value);
        });

        function search_consumer(consumer_no)
        {
            $("#loadingDiv").show();
            $("#submit").attr('disabled', true);
            $.ajax({
                    url: '<?=base_url();?>/WaterMobileIndex/search_consumerAjax',
                    type: 'POST',
                    data:{'consumer_no':consumer_no},
                    beforeSend: function() {
                        $("#loadingDiv").show();
                        $("#submit").attr('disabled', true);
                    },
                    success: function(response){
                        $("#loadingDiv").hide();
                        $("#submit").attr('disabled', false);
                        var data = JSON.parse(response);
                        if(data.status==true)
                        {
                            console.log(data);
                            $("#consumer_id").val(data.data.consumer_id);
                            $("#water_address").html(data.data.consumer_address);
                            $("#water_owners_name").html(data.data.consumer_owners_name);
                            $("#water_owners_modi").html(data.data.consumer_owners_mobile_no);
                            $("#water_ward_no").html(data.data.consumer_ward_no);
                            $("#holding_ward_no").html(data.data.prop_ward_no);
                            if(data.data.prop_id)
                            {
                                $("#holding_no").val(data.data.holding_no);
                                $("#prop_id").val(data.data.prop_id);
                                $("#holding_map").val(1);

                                $("#prop_address").html(data.data.prop_address);
                                $("#prop_owners_name").html(data.data.prop_owners_name);
                                $("#prop_owners_modi").html(data.data.prop_owners_mobile_no);

                            }
                            else if(data.data.saf_id)
                            {
                                $("#holding_no").val(data.data.saf_no);
                                $("#saf_id").val(data.data.saf_id);
                                $("#holding_map").val(1);

                                $("#prop_address").html(data.data.prop_address);
                                $("#prop_owners_name").html(data.data.prop_owners_name);
                                $("#prop_owners_modi").html(data.data.prop_owners_mobile_no);

                            }
                            if(data.data.saf_id || data.data.prop_id)
                            {
                                $("#holding_div").show();
                                $("#reason_not_map").prop('required', false);
                            }

                            if(data.data.connection_type!="")
                            {
                                $("#connection_type").val(data.data.connection_type);
                                if(data.data.meter_status==1)
                                {
                                    document.getElementById("meter_status_yes").checked = true;
                                }
                                else if(data.data.meter_status==0)
                                {
                                    document.getElementById("meter_status_no").checked = true;
                                }
                            }
                            if(data.data.connection_type==1)
                            {
                                $("#meter_no").val(data.data.meter_no);
                            }
                        }
                        else
                        {
                            $("#consumer_id").val("");
                            $("#holding_no").val("");
                            $("#prop_id").val("");
                            $("#holding_map").val("");
                            $("#saf_id").val("");
                            $("#meter_no").val("")
                            modelInfo(data.message);
                        }
                        
                    },
            });
            
        }
        // $("#consumer_no").change(function(){
        //     var consumer_no = (this.value);
        //     $("#loadingDiv").show();
        //     $("#submit").attr('disabled', true);
        //     $.ajax({
        //             url: '<?=base_url();?>/WaterMobileIndex/search_consumerAjax',
        //             type: 'POST',
        //             data:{'consumer_no':consumer_no},
        //         success: function(response){
        //             var data = JSON.parse(response);
        //             if(data.status==true)
        //             {
        //                 console.log(data);
        //                 $("#consumer_id").val(data.data.consumer_id);
        //                 $("#water_address").html(data.data.consumer_address);
        //                 $("#water_owners_name").html(data.data.consumer_owners_name);
        //                 $("#water_owners_modi").html(data.data.consumer_owners_mobile_no);
        //                 if(data.data.prop_id)
        //                 {
        //                     $("#holding_no").val(data.data.holding_no);
        //                     $("#prop_id").val(data.data.prop_id);
        //                     $("#holding_map").val(1);

        //                     $("#prop_address").html(data.data.prop_address);
        //                     $("#prop_owners_name").html(data.data.prop_owners_name);
        //                     $("#prop_owners_modi").html(data.data.prop_owners_mobile_no);

        //                 }
        //                 else if(data.data.saf_id)
        //                 {
        //                     $("#holding_no").val(data.data.saf_no);
        //                     $("#saf_id").val(data.data.saf_id);
        //                     $("#holding_map").val(1);

        //                     $("#prop_address").html(data.data.prop_address);
        //                     $("#prop_owners_name").html(data.data.prop_owners_name);
        //                     $("#prop_owners_modi").html(data.data.prop_owners_mobile_no);

        //                 }
        //                 if(data.data.saf_id || data.data.prop_id)
        //                 {
        //                     $("#holding_div").show();
        //                     $("#reason_not_map").prop('required', false);
        //                 }

        //                 if(data.data.connection_type!="")
        //                 {
        //                     $("#connection_type").val(data.data.connection_type);
        //                     if(data.data.meter_status==1)
        //                     {
        //                         document.getElementById("meter_status_yes").checked = true;
        //                     }
        //                     else if(data.data.meter_status==0)
        //                     {
        //                         document.getElementById("meter_status_no").checked = true;
        //                     }
        //                 }
        //                 if(data.data.connection_type==1)
        //                 {
        //                     $("#meter_no").val(data.data.meter_no);
        //                 }
        //             }
        //             else{
        //                 $("#consumer_id").val("");
        //                 $("#holding_no").val("");
        //                 $("#prop_id").val("");
        //                 $("#holding_map").val("");
        //                 $("#saf_id").val("");
        //                 $("#meter_no").val("")
        //                 modelInfo(data.message);
        //             }
                    
        //         },
        //     });
        //     $("#loadingDiv").hide();
        //     $("#submit").attr('disabled', false);

        // })        
    });
    function holding_div_show(val)
    {
        console.log(val);
        if(val==0)
        {
            $("#holding_div").hide();
            $("#prop_address").html("");
            $("#prop_owners_name").html("");
            $("#prop_owners_modi").html("");
            $("#holding_ward_no").html("");
            $("#reason_not_map").prop('required', true);
        }
        if(val==1)
        {
            $("#holding_div").show();
            $("#reason_not_map").prop('required', false);
            if($("#holding_no").val())
            {
                validate_holding();
            }
        }
    }

    function disconnection_div_show(val)
    {
        if(val==0)
        {
            $("#disconnection_div").hide();
            $("#application_form").prop('required', false);
        }
        if(val==1)
        {
            $("#disconnection_div").show();
            $("#application_form").prop('required', true);
        }
    }
    function last_bill_serve_date_div_show(val)
    {
        console.log(val);
        if(val==0)
        {
            $("#bill_serve_date_div").hide();
            $("#last_bill_serve_date").prop('required', false);
            $("#last_bill_serve_date").val('');
            $("#bill_not_serve_reason_div").show();
            $("#bill_not_serve_reason").prop('required', true);
            
        }
        else if(val==1)
        {
            $("#bill_serve_date_div").show();
            $("#last_bill_serve_date").prop('required', true);
            $("#bill_not_serve_reason_div").hide();
            $("#bill_not_serve_reason").prop('required', false);
            $("#bill_not_serve_reason").val('');
        }

        else{
            $("#bill_serve_date_div").show();
            $("#last_bill_serve_date").prop('required', false);
            $("#bill_not_serve_reason_div").show();
            $("#bill_not_serve_reason").prop('required', false);
        }
    }
    function validate_holding()
    {
		var holding_no=$("#holding_no").val();
        if(!~jQuery.inArray( holding_no.length, [15,16] ) && holding_no.length!=0)
		{
			alert('Please Enter 15 digit unique holding no');
			$("#holding_no").focus();
			return false;
		}
        if(holding_no )
        {
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterApplyNewConnectionCitizen/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no
                },
				beforeSend: function() {
					$("#loadingDiv").show();
                    $("#submit").attr('disabled', true);
				},
                success:function(data){
					$("#loadingDiv").hide();
                  console.log(data);
                   if (data.response==true)
                   {
                        var prop_id ="";
                        var owner_name="";
                        var adderss="";
                        var mobileno ="";
                        $("#submit").attr('disabled', false);
                        for(var k in data.dd) 
                        {
                            prop_id=data.dd[k]['id'];
                            adderss=data.dd[k]['prop_address'];
                            owner_name +=(","+data.dd[k]['owner_name']);
                            mobileno +=(","+data.dd[k]['mobile_no']);
                        }
                        $("#prop_id").val(prop_id);
                        $("#prop_address").html(adderss.replace(",",""));
                        $("#prop_owners_name").html(owner_name.replace(",",""));
                        $("#prop_owners_modi").html(mobileno.replace(",",""));
            
                   }
                   else 
                   {
                      alert(data.dd.message);
                      $("#submit").attr('disabled', false);                      
                      $("#prop_id").val("");
                      $("#prop_address").html("");
                      $("#prop_owners_name").html("");
                      $("#prop_owners_modi").html("");
                   }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });

        }
        
    }
    // function PopupMap(latitude, longitude)
    // {
    //     console.log(latitude);
    //     console.log(longitude);
    //     initialize(latitude, longitude);
    // }
    // var map;
    // var geocoder;
    // var centerChangedLast;
    // var reverseGeocodedLast;
    // var currentReverseGeocodeResponse;
    // function initialize(latitude, longitude)
    // {	
    //         var latlng = new google.maps.LatLng(latitude,longitude);
    //         var myOptions = {
    //             zoom: 15,
    //             center: latlng,
    //             mapTypeId: google.maps.MapTypeId.ROADMAP
    //         };
    //         map = new google.maps.Map(document.getElementById("map"), myOptions);
    //         geocoder = new google.maps.Geocoder();

    //         var marker = new google.maps.Marker({
    //             position: latlng,
    //             map: map,
    //             title: "Aadrika Enterprises"
    //         });

    // }

    function modelInfo(msg)
    {
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    function modelError(msg)
    {
        $.niftyNoty({
            type: 'danger',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
    if($result = flashToast('message'))
    {    
        ?>
        console.log('<?=$result;?>');
        modelInfo('<?=$result;?>');
        <?php 
    }
    if(isset($message) && trim($message)!="")
    {    
        ?>
        console.log('<?=$message;?>');
        modelInfo('<?=$message;?>');
        <?php 
    }
    if(isset($error) && trim($error)!="")
    {    print_var($error);
        ?>
        console.log('<?=$error;?>');
        modelError('<?=$error;?>');
        <?php 
    }
    ?>
</script>