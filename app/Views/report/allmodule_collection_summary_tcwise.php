<?= $this->include('layout_vertical/header');?>

<style>
.row{line-height:25px;}
#tdId{font-size: medium; font-weight: bold; text-align: right;}
#leftTd{font-size: medium; font-weight: bold; text-align: center;color: #090f44;}
#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
#left{font-size: medium; font-weight: bold; text-align: left;}
.wardClass{font-size: medium; font-weight: bold;}
td{
	font-size:12px!important;
}

.tr{
	border:1px solid red;	
}
div.scrollmenu {
   overflow: auto;
  white-space: nowrap;
}


@media only screen and (max-width: 600px) {
  #ttl_cons{
    margin-left: 100px !important;
  }
  #ttl_collloectn{
    margin-left: 100px !important;
  }
}
</style>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- <link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet"> -->
<script src="<?=base_url();?>/public/assets/otherJs/ExcelExport.js"></script>
    
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->

                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">All Module</a></li>
                    <li><a href="#">Report</a></li>
                    <li class="active">TC Wise Collection  Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Team Summary</h5>
						</div>
						<div class="panel-body">
						<div id="loadingDivs" style="display: none; background: url(<?=base_url('');?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 70%; width: 100%; z-index: 9999999;"></div>
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/TradeCollectionSummary/collection_details">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-2">
											<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>								 
											<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
										</div>
										<div class="col-md-2">
											<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>							 
											<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
										</div>
										<div class="col-md-3">
											<label class="control-label" for="Ward"><b>Team Leader</b><span class="text-danger">*</span> </label>
											<select id="team_leader" name="team_leader" class="form-control">
												<option value="all">All</option>  
												<?php foreach($team_leader as $value):?>
												<option value="<?=$value['id']?>" ><?=$value['emp_name'];?>
												</option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-md-3">
											<label class="control-label" for="Ward"><b>Tax Collector </b><span class="text-danger">*</span></label>
											<select id="tax_collector" name="tax_collector" class="form-control">
												<option value="">All</option>  
											</select>
											<span style="color:#6583b9;" id="slct_tc"></span>
										</div>
										<div class="col-md-2">
											<label class="control-label" for="department_mstr_id">&nbsp;</label><br>
											<button  style="margin-top:2px;" type="button" class="btn btn-primary btn-labeled" id="view_collection" name="view_collection" onclick="get_collection()">View Collection</button>&nbsp;&nbsp;&nbsp;&nbsp;
										</div>	 
									</div>
								</div>
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading text-center" style="height:149px;">
							<h3 class="panel-title">RANCHI MUNICIPAL CORPORATION</h3>
							<h5 class="panel-title" style="margin: -20px;font-size: 12px;">Team Summary Report</h5>
							<h5 class="panel-title" style="font-size: 12px;" id="from_date_to_date">From -- To --</h5>
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td colspan="2" style="text-align:center;"><b>Total</b></td>
										<td>Property Count <b style="color:#1af30c;" id="prpcnt"></b></td>
										<td>Property Collections <b style="color:#1af30c;" id="prpcll"></b></td>
										<td>Water Count <b style="color:#1af30c;" id="wtrcnt"></b></td>
										<td>Water Collections <b style="color:#1af30c;" id="wtrcll"></b></td>
										<td>Trade Count <b style="color:#1af30c;" id="trdcnt"></b></td>
										<td>Trade Collections <b style="color:#1af30c;" id="trdcll"></b></td>
										<td>Total Collections <b style="color:#1af30c;" id="ttlcll"></b></td>
									</tr>
								</tbody>
							</table>
						</div>
						<table class="table table-bordered">
							
							<thead>
								<th id="leftTd"><b>SN</b></th>
								<th id="leftTd"><b>Tax Collector</b></th>
								<th id="leftTd"><b>Property Count</b></th>
								<th id="leftTd"><b>Property Collections</b></th>
								<th id="leftTd"><b>Water Count</b></th>
								<th id="leftTd"><b>Water Collections</b></th>
								<th id="leftTd"><b>Trade Count</b></th>
								<th id="leftTd"><b>Trade Collections</b></th>
								<th id="leftTd"><b>Total Collection</b></th>
							</thead>
							<tbody id="collec_dtls">
								<tr>
									<td colspan="9" style="text-align:center;">No Data</td>
								</tr>
							</tbody>
							
						</table>
					</div>
					<div class="panel" style="margin-top:-19px;">
						<div class="panel-body text-right">
							<a  style="margin-top:2px;" href="#" download="AllModuleReport.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Property Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<input style="margin-top:2px;" type="button" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary" value="Print">
						</div>
					</div>
				</div>
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
     
    $('#view_collection').click(function(){
        $("#to_date").css({"border-color":""});
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(to_date=="")
        {
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        if(to_date<from_date)
        {
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});

    function printDiv(divName) { //alert('asfasdf'); return false;
 	var printData = document.getElementById(divName).innerHTML;
	var data = document.body.innerHTML;
	document.body.innerHTML = printData;
	window.print();
	//window.location.reload();
	document.body.innerHTML = data;
	}
</script>

<script>
$( "#team_leader" ).change(function() {
  var team_leader_id = $("#team_leader").val();
  if(team_leader_id == "all")
  {
   $("#view_collection").prop("disabled",false);
   $("#slct_tc").text('');

  }
  else
  {
    $("#slct_tc").text('Please Select Tax Collector');
    $("#view_collection").prop("disabled",true);
  }
 	$.ajax({
    type: "POST",
    url: "<?=base_url('AllmoduleCollectionSummary_TCwise/get_tax_collector_ajax');?>",
	data:{team_leader_id:team_leader_id},
    dataType:"json",
	beforeSend: function() {
		//$("#amntval").css('opacity', '0.3');
		$("#loadingDivs").show();
	},
    success: function(response){
		$("#loadingDivs").hide();
        $("#tax_collector").empty().html(response);
    }
  });
});
</script>
 
 <script>
 $( "#tax_collector" ).change(function() {
    var tax_collector = $("#tax_collector").val();
    if(tax_collector =="")
    {
        $("#view_collection").prop("disabled",true);
        $("#slct_tc").text('Please Select Tax Collector');
    }
    else{
        $("#view_collection").prop("disabled",false);
        $("#slct_tc").text('');

    }

 });

 </script>
<script>
 function get_collection()
 {
 	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var tax_collector_id = $("#tax_collector").val();
   	$.ajax({
    type: "POST",
    url: "<?=base_url('AllmoduleCollectionSummary_TCwise/get_collection_details_ajax');?>",
	data:{from_date:from_date,to_date:to_date,tax_collector_id:tax_collector_id},
    dataType:"json",
	beforeSend: function() {
		//$("#amntval").css('opacity', '0.3');
		$("#loadingDivs").show();
	},
    success: function(response){
		$("#loadingDivs").hide();
 		console.log(response);
        $("#from_date_to_date").text(response.from_date_to_date);
        $("#prpcnt").empty().html(response.propcount);
		$("#prpcll").empty().html(response.propcll);
		$("#wtrcnt").empty().html(response.wtrcount);
		$("#wtrcll").empty().html(response.wtrcll);
		$("#trdcnt").empty().html(response.trdcount);
		$("#trdcll").empty().html(response.trdcll);
		$("#ttlcll").empty().html(response.totlcll);
        $("#collec_dtls").empty().html(response.output_tbl);
    }
  });
  }
  get_collection()
 </script>
 