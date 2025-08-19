
<?=$this->include('layout_home/header');?>
<style>
.hide_trial_version_label{
	background: white;
    width: 63px;
	height: 13px;
    position: absolute;
    bottom: 1px;
    z-index: 2;
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
	@media(max-width: 770px){
        #aatambhart {
            display: none;
        }
        #honerperson {
            display: none;
        }
    }
	

</style>

<div id="page-content">
    <div class="panel">
        <div class="panel-body">
			<div class="row">
				<div class="col-md-2" style="height:185px;" id="aatambhart">
					<img src="<?=base_url();?>/public/assets/img/aatmnirbhar.jpg" style="width:100%;height:100%;" class="w3-round" alt="lgimg">
				</div>
				<div class="col-md-8">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="1"></li>
								<li data-target="#myCarousel" data-slide-to="2"></li>
							</ol>
							<div class="carousel-inner">
								<div class="item active" style="padding-top: 0px;">
									<img src="<?=base_url();?>/public/assets/img/IndependenceDay11.jpg" alt="Los Angeles" style="width:100%;">
									
								</div>
								
								<div class="item" style="padding-top: 0px;">
									<img src="<?=base_url();?>/public/assets/img/amrut.jpg" alt="Chicago" style="width:100%;">
									
								</div>
								
								<div class="item" style="padding-top: 0px;">
									<img src="<?=base_url();?>/public/assets/img/property_tax.jpg" alt="New york" style="width:100%;">
									
								</div>
							</div>
							
							<a class="left carousel-control" href="#myCarousel" data-slide="prev" style="padding-left: 20px;">
								<span class="glyphicon glyphicon-chevron-left"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="right carousel-control" href="#myCarousel" data-slide="next" style="padding-right: 20px;">
								<span class="glyphicon glyphicon-chevron-right"></span>
								<span class="sr-only">Next</span>
							</a>
					</div>
				</div>
				<div class="col-md-2" id="honerperson">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
							<div class="item active" style="padding-top: 0px;">
								<img src="<?=base_url();?>/public/assets/img/hemat_soren.jpg" alt="CM" style="width:100%;">
								<div class="carousel-caption" style="right:0px; left:0px; padding-bottom:0px;">
									<p>Minister-in-Charge</p>
									<b>Shri Hemant Soren</b>
								</div>  
							</div>
							<div class="item" style="padding-top: 0px;">
								<img src="<?=base_url();?>/public/assets/img/Secretary_Vinay_kr.jpg" alt="Secretary" style="width:100%;">
								<div class="carousel-caption" style="right:0px; left:0px; padding-bottom:0px;">
									<p>Secretary</p>
									<b>Shri Vinay Kumar Choubey</b>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="page-content">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
					<div class="panel">
						<div class="panel-heading">
							<h5 class="panel-title"> <b><u>Welcome to Urban Development & Housing Department , Jharkhand </u></b></h5>
						</div>
						<div class="panel-body">
							Urban Development & Housing Department , Jharkhand is always devoted for your service. This is your own city. To keep city clean, green & liveable please follow the rules & regulations of Urban Development & Housing Department , Jharkhand. Pay your tax/usercharges on time, registered the incident of birth and death in your family within the prescribed time period. Your comments and suggestions are always welcome for the growth and development of this corporation. Please come forward, take the responsibilities of your duties to be a good citizen and make Jharkhand city, an ideal city.
						</div>
					</div>
                </div>
            </div> 
			<div class="row">
                <div class="col-sm-4">
					<div class="panel-bordered panel-dark" id="property" style="height: 200px; width: 100%;"></div>
				</div>
				<div class="col-sm-4">
					<div class="panel-bordered panel-dark" id="trade" style="height: 200px; width: 100%;"></div>
                </div>
				<div class="col-sm-4">
					<div class="panel-bordered panel-dark" id="water" style="height: 200px; width: 100%;"></div>
                </div>
            </div> 
        </div>
    </div>
</div>
<input type="hidden" name="fy_mstr_id" id="fy_mstr_id" value="2021-2022">

<?php if (isset($ulb_list)) { ?>
<div id="page-content">
    <div class="panel panel-bordered panel-dark">
		<div class="panel-heading" style="background-color:#25476a;">
				<h5 class="panel-title"> <b> SELECT MUNICIPAL - URBAN LOCAL BODIES </b></h5>
			</div>
        <div class="panel-body">
            <div class="row">
				<div class="container-fluid">
				
					<!-----Municipal Corporations--->
					
					<div class="col-md-4">
						<div class="panel panel-bordered panel-dark" style="background-color: aliceblue;">
							<div class="box box-primary">
								<div class="box-header text-center pad-btm">
									<b style="color:black;"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;   Municipal Corporations</b>
								</div>
								<div class="box-body pad-btm" style="padding-left:5px;padding-right:5px;">
									<select name="muncipal_corporation" id="muncipal_corporation" class="form-control" onchange="myFunctionmuncipal_corporation()" style="font-size:12px;">
										<option value="">-----Select Municipal Corporations---</option>
										<?php 
										$i=0;
										foreach ($ulb_list AS $list)
										{
											?>
											<?php if($list['id']==1){ ?>
											<option value="<?=$list['id']?base_url().'/Citizen/index/'.hashEncrypt(md5($list['id'])):"#";?>"><?=$list['ulb_name'];?></option>
											<?php 
										}
										else
										{
											?>
											<option value="#"><?=$list['ulb_name'];?></option>
											<?php 
										} ?>
										<?php $i++;} ?>
									</select>
								</div>
								
								<div class="box-footer text-center pad-btm">
									<a id="Munc" data-toggle="modal" data-target="#myModalMunc" href="#"> <b style="color:black;"><?=$i?$i:0;?> Municipal Corporations</b></a>
								</div>
							</div>
						</div>
					</div>
				
					<!-----Nagar Parishad--->
					
					<div class="col-md-4">
						<div class="panel panel-bordered panel-dark" style="background-color: aliceblue;">
							<div class="box box-primary">
								<div class="box-header text-center pad-btm">
									<b style="color:black;"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;   Nagar Parishad</b>
								</div>
								<div class="box-body pad-btm" style="padding-left:5px;padding-right:5px;">
									<select name="nagar_parishad" id="nagar_parishad" class="form-control" onchange="myFunctionnagar_parishad()" style="font-size:12px;">
										<option value="">-----Select Nagar Parishad---</option>
										<?php $i=0;foreach ($npr_list AS $list) { ?>
										<option value="#"><?=$list['ulb_name'];?></option>
										<?php $i++;} ?>
									</select>
								</div>
								<div class="box-footer text-center pad-btm">
									<a id="NagarPar" data-toggle="modal" data-target="#myModalNagarPar" href="#"> <b style="color:black;"><?=$i?$i:0;?> Nagar Parishad</b></a>
								</div>
							</div>
						</div>
					</div>
				
					<!-----Nagar Panchayat--->
					
					<div class="col-md-4">
						<div class="panel panel-bordered panel-dark" style="background-color: aliceblue;">
							<div class="box box-primary">
								<div class="box-header text-center pad-btm">
									<b style="color:black;"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;   Nagar Panchayat</b>
								</div>
								<div class="box-body pad-btm" style="padding-left:5px;padding-right:5px;">
									<select name="nagar_panchayat" id="nagar_panchayat" class="form-control" onchange="myFunctionnagar_panchayat()" style="font-size:12px;">
										<option value="">-----Select Nagar Panchayat---</option>
										<?php $i=0;foreach ($npy_list AS $list) { ?>
										<option value="#"><?=$list['ulb_name'];?></option>
										<?php $i++;} ?>
									</select>
								</div>
								<div class="box-footer text-center pad-btm">
									<a id="NagarPanc" data-toggle="modal" data-target="#myModalNagarPanc" href="#"> <b style="color:black;"><?=$i?$i:0;?> Nagar Panchayat</b></a>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
            </div>
        </div>
    </div>
</div>

<?php } ?>

<br><br><br>
<div class="modal fade in" id="myModalMunc">
	<div class="modal-dialog" role="document" style="border: 5px solid #1c6d09; border-radius: 3px;">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #1c6d09; color: white; font-weight: bold;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white;">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><b style="color: white;">Municipal Corporations</b></h4>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-bordered">
					<tbody>
						<?php $i=1;foreach ($ulb_list AS $list) { ?>
						<tr>
							<td><?=$i;?></td>
							<td>
								<?php if($list['id']==1){ ?>
									<a id="Munc" href="<?=base_url();?>/Citizen/index/<?=hashEncrypt(md5($list['id']));?>" target="_blank">
										<?=$list['ulb_name'];?>
									</a>
								<?php } else { ?>
									<?=$list['ulb_name'];?>
								<?php } ?>
							</td>
						</tr>
						<?php $i++;} ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade in" id="myModalNagarPar">
	<div class="modal-dialog" role="document" style="border: 5px solid #1c6d09; border-radius: 3px;">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #1c6d09; color: white; font-weight: bold;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white;">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><b style="color: white;">Nagar Parishads</b></h4>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-bordered">
					<tbody>
						<?php $i=1;foreach ($npr_list AS $list) { ?>
						<tr>
							<td><?=$i;?></td>
							<td><?=$list['ulb_name'];?></td>
						</tr>
						<?php $i++;} ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade in" id="myModalNagarPanc">
	<div class="modal-dialog" role="document" style="border: 5px solid #1c6d09; border-radius: 3px;">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #1c6d09; color: white; font-weight: bold;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white;">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><b style="color: white;">Nagar Panchayat</b></h4>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-bordered">
					<tbody>
						<?php $i=1;foreach ($npy_list AS $list) { ?>
						<tr>
							<td><?=$i;?></td>
							<td><?=$list['ulb_name'];?></td>
						</tr>
						<?php $i++;} ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('');?>/public/assets/js/canvasjs.min.js"></script>
<?= $this->include('layout_home/footer');?>
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

function myFunctionmuncipal_corporation() {
  var muncipal_corporation = document.getElementById("muncipal_corporation").value;
  window.open(muncipal_corporation, "_blank");
}
function myFunctionnagar_parishad() {
  var nagar_parishad = document.getElementById("nagar_parishad").value;
  window.location.href = nagar_parishad;
}
function myFunctionnagar_panchayat() {
  var nagar_panchayat = document.getElementById("nagar_panchayat").value;
  window.location.href = nagar_panchayat;
}

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


function dcb(){
	var fy_mstr_id = $("#fy_mstr_id").val();
	$.ajax({
		type: "POST",
		url: "<?php echo base_url('/home/dcb'); ?>",
		dataType:"json",
		data:
		{
			fy_mstr_id:fy_mstr_id
		},
		beforeSend: function() {
			$("#dcbload").show();
		},
		success: function(data){
			$("#dcbload").hide();
			if(data.response==true){
				propertydcbList(data.propdcb);
				tradedcbList(data.tradedcb);
				waterdcbList(data.wtrdcb);
			}
		}
	});
}

</script>
