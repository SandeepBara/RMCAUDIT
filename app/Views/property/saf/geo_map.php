<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Reports</a></li>
					<li class="active">SAF Distribution Geolocation</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
					                <h5 class="panel-title">SAF Distribution Geolocation</h5>
					            </div>
                                <div class="panel-body">
                                    <?php ///print_r($img_loc);?>
                                    <div class="container" id="map" style="width:100%; height:650px; "></div>
                                </div>
					        </div>
					    </div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>

            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<?php //print_r($img_loc); ?>
<script>
function initMap() {
	var myMapCenter = {lat: 23.170200, lng: 85.279900};
	// Create a map object and specify the DOM element for display.
	var map = new google.maps.Map(document.getElementById('map'), {
		center: myMapCenter,
		zoom: 10
	});	function markStore(storeInfo){
		// Create a marker and set its position.
		var marker = new google.maps.Marker({
			map: map,
			position: storeInfo.location,
			title: storeInfo.name
		});
		// show store info when marker is clicked
		marker.addListener('click', function(){
			showStoreInfo(storeInfo);
		});
	}
	// show store info in text box

function showStoreInfo(storeInfo){
		//window.open("consumer_image_loc.php?consumer_id="+storeInfo.link_id+"");
		//var info_div = document.getElementById('info_div');
	}


	var stores = [

	<?php
	foreach ($img_loc as $value):

			$saf_no =  $value["saf_no"];
            //$ward_no='1';
			$latitude = $value["latitude"];
            $longitude = $value["longitude"];
            //$latitude = 23.170200;
            //$longitude = 85.279900;
			$location='lat:'.$latitude.','.'lng:'.$longitude;


			//$content='SAF No: '.$saf_no.'\n Ward No: '.$ward_no;
        $content='SAF No: '.$saf_no;
        ?>
		{
			name: '<?=$content;?>',
			placeMaker: '<?=$saf_no;?>',
			location: {<?=$location;?>},
			link_id: '<?=md5($saf_no);?>'
		},

<?php endforeach;?>
	];

	stores.forEach(function(store){
		markStore(store);
	});
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap" async defer></script>

