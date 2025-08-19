<?= $this->include("layout_mobi/header"); ?>

<script type="text/javascript">
	function dategreater(to_date) {
		var from_date = document.getElementById("fromdate").value;

		var CurrentDate = new Date();
		var Enddate = new Date(to_date);
		var dd = CurrentDate.getDate();
		var mm = CurrentDate.getMonth() + 1;
		var yyyy = CurrentDate.getFullYear();
		if (dd < 10) {
			dd = '0' + dd;
		}

		if (mm < 10) {
			mm = '0' + mm;
		}
		var today = yyyy + '-' + mm + '-' + dd;
		if (Enddate > CurrentDate) {
			alert('End date is greater than the current date.');
			document.getElementById("todate").value = today;
			document.getElementById("fromdate").value = today;
		}
		var Startdate = new Date(from_date);
		if (Startdate > Enddate) {
			alert('Start date is greater than the End date.');
			document.getElementById("todate").value = today;
			document.getElementById("fromdate").value = today;
		}

	}
</script>

<!--CONTENT CONTAINER-->
<div id="content-container">
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">

			<div class="panel-heading flex" style="display: flex;">
				<div style="flex:1;">
					<h3 class="panel-title"><b style="color:white;">Field Verification</b></h3>
				</div>
				<div style="flex:1;text-align:right"><a href="<?= base_url('mobi/home'); ?>" class="btn btn-info btn_wait_load">Back</a></div>

			</div>
			<div class="panel-body">
				<form action="<?= base_url(); ?>/SafVerification/<?=$view??'field_verification_list'?>" id="field_verification" method="get">
					<div class="row">
						<div class="col-sm-4">
							<input type="radio" id="date_type1" name="date_type"  value="forword_date" checked />&nbsp;&nbsp;Forword Date&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="date_type2" name="date_type"  value="apply_date" <?=isset($date_type) && $date_type=='apply_date'?"checked":"";?> /> &nbsp;&nbsp;Apply Date&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<div class="col-sm-2">
							<label>From Date</label>
						</div>
						<div class="col-sm-2">
							<input type="date" id="from_date" name="from_date" class="form-control" value="<?=$from_date??"";?>" />
						</div>
						<div class="col-sm-2">
							<label>Upto Date</label>
						</div>
						<div class="col-sm-2">
							<input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=$upto_date??"";?>" />
						</div>
					</div>
					<div class="row">
						<div class="col-sm-1">
							<label for="exampleInputEmail1">Ward No</label>
						</div>
						<div class="col-sm-2">
							<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
								<option value="">ALL</option>
								<?php foreach ($wardList as $value) : ?>
									<option value="<?= $value['ward_mstr_id'] ?>" <?= (isset($ward_mstr_id)) ? $ward_mstr_id == $value["ward_mstr_id"] ? "SELECTED" : "" : ""; ?>><?= $value['ward_no']; ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-sm-2">
							<label for="exampleInputEmail1">Keyword :</label>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<input type="text" name="search_param" id="search_param" value="<?=$search_param??"";?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
							<input type="submit" id="Search" value="Search" class="form-control btn btn-success" onclick="this.value='Searching Please Wait'">
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
		if (isset($leveldetails)) :
			if (!empty($leveldetails)) : ?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading" style="background-color: #64ab4d;">
						<h3 class="panel-title" style="color: white;"><b>Application List</b></h3>
					</div>
					<div class="panel-body">
						<?php foreach ($leveldetails as  $value) : ?>
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: white;">Property Details</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Ward No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["ward_no"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Assessment Type</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= (trim($value["assessment_type"])=="Mutation")?"Mutation with Reassessment":$value["assessment_type"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Property Type</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["property_type"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Applicant Name</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["owner_name"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Mobile No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["mobile_no"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">SAF No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["saf_no"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Holding No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?php echo $value["new_holding_no"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Property Address</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["prop_address"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label>Apply Date</label>
										</div>
										<div class="col-sm-2">
											<label><strong><?= $value["apply_date"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label>Forward Date</label>
										</div>
										<div class="col-sm-2">
											<label><strong><?= $value["forward_date"] ?></strong></label>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-4 col-xs-4 text-center">
												<a href="field_verification/<?= $value["saf_dtl_id"] ?>/<?= $value["id"] ?>"><span class="btn btn-sm btn-info btn_wait_load">Click To Survey</span></a>
										</div>
										<?php
											if (!empty($value["saf_geotag_dtl"])) {
												$saf_geotag_dtl = json_decode($value["saf_geotag_dtl"], true);
												$image_path = str_replace("\\", "/", $saf_geotag_dtl[0]['image_path']);
												//print_var($saf_geotag_dtl);
										?>
										<div class="col-sm-4 col-xs-3 text-center">
											<!-- <button type="button" class="btn btn-sm btn-info" id="image_load_<?=$value['id'];?>" onclick="imageLoadToModel('<?=$value['saf_dtl_id'];?>', this.id)">View Images</button> -->
											<button type="button" class="btn btn-sm btn-info" id="image_load_<?=$value['id'];?>" onclick="imageLoadToModel('<?=$saf_geotag_dtl[0]['direction_type'];?>', '<?=$image_path;?>')">View Images</button>
										</div>

										<div class="col-sm-4 col-xs-3 text-center">
											<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger" onclick="PopupMap('<?=$saf_geotag_dtl[0]['latitude'];?>', '<?=$saf_geotag_dtl[0]['longitude'];?>');">View Map</button>
										</div>
										<?php
											}
										?>

									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<?= pagination($pager); ?>
					</div>
				</div>
		<?php endif;
		endif; ?>

	</div>
	<!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<!--saf property image Bootstrap Modal-->
<div id="saf_geotag_image-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body" id="saf_geotag_image_body">
            </div>
        </div>
    </div>
</div>
<!--End saf property image Bootstrap Modal-->
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
    </div>
  </div>
</div>
<?= $this->include("layout_mobi/footer"); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap" async defer></script>
<script type="text/javascript">
var map;
var geocoder;
var centerChangedLast;
var reverseGeocodedLast;
var currentReverseGeocodeResponse;
function initialize(latitude, longitude) {
    var latlng = new google.maps.LatLng(latitude,longitude);
    var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    geocoder = new google.maps.Geocoder();

    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        title: "Aadrika Enterprises"
    });
}
function PopupMap(latitude, longitude)
{
    console.log(latitude);
    console.log(longitude);
    initialize(latitude, longitude);
}
var imageLoadToModel = function(direction_type, image_path) {
	const imageMain_path = "<?=base_url()?>/getImageLink.php?path=";
	$("#saf_geotag_image-modal").modal('show');
	var appendData = "<div class='row'>";
	appendData += '<div class="col-md-12">';
	appendData +=  '<b>'+direction_type+'</b>';
	appendData += '<img src="<?=base_url()?>/getImageLink.php?path='+image_path+'" style="height: 400px; width: 100%;" loading="lazy" />'
	appendData += '</div>';
	appendData += "</div>";
	console.log(image_path);
	$("#saf_geotag_image_body").html(appendData);
}
</script>
