<?= $this->include('layout_home/header');?>
<style>
	/*Huge thanks to @tobiasahlin at http://tobiasahlin.com/spinkit/ */
.spinner {
  margin: 100px auto 0;
  width: 70px;
  text-align: center;
}

.spinner > div {
  width: 18px;
  height: 18px;
  background-color: #333;

  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}

.spinner .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.spinner .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bouncedelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% {
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
</style>
<style>
    #container_carousel {
        background: white;
        height:200px;
        padding-bottom: 0px;
    }
    #myCarousel img{
        height: 200px;
    }
    @media(max-width: 858px){
        #container_carousel {
            height:150px;
        }
        #myCarousel img{
            height: 150px;
        }
        #left_image {
            display: none;
        }
        #right_image {
            display: none;
        }
    }

	.hide_trial_version_label{
		background: white;
		width: 63px;
		height: 13px;
		position: absolute;
		bottom: 1px;
		z-index: 2;
	}

</style>
<!--Page content-->
<div id="page-content">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 text-danger text-2x">
            <marquee>
                Toll free number - 1800 890 4115, Whats Up No.- 6206799753
            </marquee>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-lg-4" style="margin-top: 100px;">
            <div class="panel panel-bordered panel-dark widget">
                <div class="widget-body text-center">
                    <img alt="Profile Picture" class="widget-img img-circle img-border-light img-lg" src="<?=base_url();?>/public/assets/other/property-insurance.png" style="box-shadow: 0 0 0 4px #8acbe8; width: 140px; height: 140px; margin-left: -50px; top: -80px;">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <h4 class="text-2x text-bold">PROPERTY TAX</h4>
                            <p class="text-muted text-bold text-primary mar-top" style="font-size: 12px;">Enroll your property or properties and quickly check the outstanding amount and pay it with ease online.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/CitizenProperty/index">PAY PROPERTY TAX</a>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/CitizenSaf2/safmanual">ASSESSMENT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4" style="margin-top: 100px;">
            <div class="panel panel-bordered panel-dark widget">
                <div class="widget-body text-center">
                    <img alt="Profile Picture" class="widget-img img-circle img-border-light img-lg" src="<?=base_url();?>/public/assets/other/water-scarcity.png"  style="box-shadow: 0 0 0 4px #8acbe8; width: 140px; height: 140px; margin-left: -50px; top: -80px;">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <h4 class="text-2x text-bold">WATER USER CHARGES</h4>
                            <p class="text-muted text-bold text-primary mar-top" style="font-size: 12px;">Enroll your Water Meter connection and quickly check the outstanding amount and pay it with ease online.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <!-- <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/WaterConsumerListCitizen/index">PAY WATER USER CHARGE</a> -->
						<a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/WaterApplyNewConnectionCitizen/search/<?=md5(2)?>">PAY WATER USER CHARGE</a>

                        </div>
                        <div class="col-md-6">
                        <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/WaterApplyNewConnectionCitizen/index">APPLY CONNECTION</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4" style="margin-top: 100px;">
            <div class="panel panel-bordered panel-dark widget">
                <div class="widget-body text-center">
                    <img alt="Profile Picture" class="widget-img img-circle" src="<?=base_url();?>/public/assets/other/patent.png" style="box-shadow: 0 0 0 4px #8acbe8; width: 140px; height: 140px; margin-left: -50px; top: -80px;">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <h4 class="text-2x text-bold">MUNCIPAL LICENCE</h4>
                            <p class="text-muted text-bold text-primary mar-top" style="font-size: 12px;">Enroll your municipal license and quickly check the outstanding amount and pay it with ease online.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/Home/TradeMenu">APPLY FOR MUNICIPAL LICENSE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-lg-4" style="margin-top: 100px;">
            <div class="panel panel-bordered panel-dark widget">
                <div class="widget-body text-center">
                    <img alt="Profile Picture" class="widget-img img-circle" src="<?=base_url();?>/public/assets/other/avatar.png" style="box-shadow: 0 0 0 4px #8acbe8; width: 140px; height: 140px; margin-left: -50px; top: -80px;">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <h4 class="text-2x text-bold">TAX COLLECTOR</h4>
                            <p class="text-muted text-bold text-primary mar-top" style="font-size: 12px;">Know your tax collector.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/CitizenProperty/taxCollector">KNOW YOUR TAX COLLECTOR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4" style="margin-top: 100px;">
            <div class="panel panel-bordered panel-dark widget">
                <div class="widget-body" style="padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-2x text-bold">PROPERTY DOCUMENT</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?=base_url();?>/public/download_doc/property/rain_water_harvesting_structure.pdf" target="_blank" style="color:#16cdd9">Rain Water Harvesting Structure Information Booklet</a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/assessment_form.pdf" target="_blank" style="color:#16cdd9">Download-Self Assessment Form</a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/booklet.pdf" target="_blank" style="color:#16cdd9">Download-Information Booklet</a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/supplementary_booklet.pdf" target="_blank" style="color:#16cdd9">Download-Supplementary Information Booklet</a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/owner_annexure.pdf" target="_blank" style="color:#16cdd9">Download From For-More than one owner</a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/unassessed_properties.pdf" target="_blank" style="color:#16cdd9">List of Unassessed Properties</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4" style="margin-top: 100px;">
            <div class="panel panel-bordered panel-dark widget">
                <div class="widget-body" style="padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-2x text-bold">Water DOCUMENT</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?=base_url();?>/public/download_doc/water/New Water Connection Form.pdf" target="_blank" style="color:#16cdd9">WCF -1 (For New Connection) </a><br />
                            <a href="<?=base_url();?>/public/download_doc/water/water meter installation confirmation form.pdf" target="_blank" style="color:#16cdd9">Meter Installation Form</a><br />
                        </div>
                    </div>
                </div>
                <!-- <div class="widget-body" style="padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-2x text-bold">Trade DOCUMENT</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?=base_url();?>/public/download_doc/trade/New Water Connection Form.pdf" target="_blank" style="color:#16cdd9">Trade license Application form</a><br />
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <!-- <div class="col-sm-4" style="margin-top: 100px;">
				<div class="panel-bordered panel-dark" id="property" style="height: 220px; width: 100%;"></div>
        </div>
        <div class="col-sm-4" style="margin-top: 100px;">
            <div class="panel-bordered panel-dark" id="water" style="height: 220px; width: 100%;"></div>
        </div> -->
    </div>
	<div class="row">
		<div class="col-md-12">
			<!-- <div class="col-sm-4">
				<div class="panel-bordered panel-dark" id="property" style="height: 20px; width: 100%;"></div>
			</div> -->
			<!-- <div class="col-sm-4">
				<div class="panel-bordered panel-dark" id="trade" style="height: 200px; width: 100%;"></div>
			</div> -->
			<!-- <div class="col-sm-4">
				<div class="panel-bordered panel-dark" id="water" style="height: 200px; width: 100%;"></div>
			</div> -->
		</div>
		<input type="hidden" name="fy_mstr_id" id="fy_mstr_id" value="<?=getFY()?>">
	</div>
	<div class="row">
		&nbsp;
	</div><br><br>
</div>
<?= $this->include('layout_home/footer');?>
<script src="<?=base_url('');?>/public/assets/js/canvasjs.min.js"></script>
<script type="text/javascript">
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
</script>

<script>

function propertydcbList(JsonData) {
	var dataPoints = [];
	var property = new CanvasJS.Chart("property", {
		theme: "light2",
		animationEnabled: true,
		title: {
			text: "Property DCB"
		},
		data: [{
			type: "doughnut",
			indexLabel: "{symbol}  {y}",
			yValueFormatString: "#,##0.0\"\"",
			showInLegend: true,
			legendText: "{label} : {y}",
			dataPoints: dataPoints
		}]
	});

	var name = ['Total Collection Amount', 'Total Balance Amount'];
	for (var i = 0; i < JsonData.length; i++) {
		dataPoints.push({
			label:	name[i],
			y: JsonData[i]
		});
	}
	property.render();
	$("#property").append('<div class="hide_trial_version_label"></div>');
}

function tradedcbList(JsonData) {
	var dataPoints = [];
	var trade = new CanvasJS.Chart("trade", {
		theme: "light2",
		animationEnabled: true,
		title: {
			text: "Trade Collection Amount"
		},
		data: [{
			type: "doughnut",
			indexLabel: "{symbol}  {y}",
			yValueFormatString: "#,##0.0\"\"",
			showInLegend: true,
			legendText: "{label} : {y}",
			dataPoints: dataPoints
		}]
	});
	var name = ['Collection Amount'];
	for (var i = 0; i < JsonData.length; i++) {
		dataPoints.push({
			label:	name[i],
			y: JsonData[i]
		});
	}
	trade.render();
	$("#trade").append('<div class="hide_trial_version_label"></div>');
}

function waterdcbList(JsonData) {
	var dataPoints = [];
	var water = new CanvasJS.Chart("water", {
		theme: "light2",
		animationEnabled: true,
		title: {
			text: "Water DCB"
		},
		data: [{
			type: "doughnut",
			indexLabel: "{symbol}  {y}",
			yValueFormatString: "#,##0.0\"\"",
			showInLegend: true,
			legendText: "{label} : {y}",
			dataPoints: dataPoints
		}]
	});
	var name = ['Total Collection Amount', 'Total Balance Amount'];
	for (var i = 0; i < JsonData.length; i++) {
		dataPoints.push({
			label:	name[i],
			y: JsonData[i]
		});
	}
	water.render();
	$("#water").append('<div class="hide_trial_version_label"></div>');
}

window.onload = function() {
	dcb();
}


/* function dcb(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/home/dcbbyulbid'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#dcbload").show();
			$('#property').html('<div class="spinner"> <div class="bounce1"></div> <div class="bounce2"></div> <div class="bounce3"></div> </div>');
            $('#water').html('<div class="spinner"> <div class="bounce1"></div> <div class="bounce2"></div> <div class="bounce3"></div> </div>');
		},
		success: function(data){
			$("#dcbload").hide();
			if(data.response==true){
				propertydcbList(data.propdcb);
				//tradedcbList(data.tradedcb);
				waterdcbList(data.wtrdcb);
			}
		}
	});
}
 */
</script>
