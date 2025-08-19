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
                    <li><a href="#">Report</a></li>
                    <li class="active">All Module Collection  Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Search Collection</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/all_module_CollectionSummary/all_module_collection_details">
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
												<option value="<?=$value['id']?>" <?=(isset($team_leader))?$team_leader==$value["id"]?"SELECTED":"":"";?>><?=$value['emp_name'];?>
												</option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-md-3">
											<label class="control-label" for="Ward"><b>Tax Collector </b><span class="text-danger">*</span></label>
											<select id="tax_collector" name="tax_collector" class="form-control">
												<option value="">All</option>  
											</select>
											&nbsp;&nbsp;<span class="text-danger" id="slct_tc"></span>
										</div>
										<div class="col-md-2">
											<label class="control-label" for="department_mstr_id">&nbsp;</label><br>
											<input  style="margin-top:2px;" type="submit" class="btn btn-primary btn-labeled" id="view_collection" name="view_collection" value="View Collection">
										</div>	 
									</div>
								</div>
							</form>
						</div>
					</div>	
					<div id="loadingDivs" style="display: none; background: url(http://192.168.0.16:822/RMCDMC/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 150px; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Collection Summary</h3>
						</div>
						<div class="table-responsive">
							<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="width:100px; text-align: center;" colspan="2"></th>
										<th style="width:100px; text-align: center;" colspan="4">Property</th>
										<th style="width:100px; text-align: center;" colspan="4">Water</th>
										<th style="width:100px; text-align: center;"colspan="2">Trade</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th style="width:30px; text-align: center;" colspan="2"></th>
										<th style="width:30px; text-align: center;" colspan="2">Property</th>
										<th style="width:30px; text-align: center;" colspan="2">SAF</th>
										<th style="width:30px; text-align: center;" colspan="2">New Connection</th>
										<th style="width:30px; text-align: center;" colspan="2">Demand Collection</th>
										<th style="width:30px; text-align: center;" colspan="2">Trade</th>
									</tr>
									<tr>
										<th style="width:100px; text-align: center;">SL. No.</th>
										<th style="width:100px; text-align: center;">TC Name</th>
										<th style="width:100px; text-align: center;">Count</th>
										<th style="width:100px; text-align: center;">Total</th>
										<th style="width:100px; text-align: center;">Count</th>
										<th style="width:100px; text-align: center;">Total</th>
										<th style="width:100px; text-align: center;">Count</th>
										<th style="width:100px; text-align: center;">Total</th>
										<th style="width:100px; text-align: center;">Count</th>
										<th style="width:100px; text-align: center;">Total</th>
										<th style="width:100px; text-align: center;">Count</th>
										<th style="width:100px; text-align: center;">Total</th>									
									</tr>
									<?php
									if(!isset($coll_list))
									{ ?>
									<tr>
										<td colspan="12" style="text-align: center; color: red;">No Records Found!!!</td>
									</tr>
									<?php
									}
									else
									{
										$i=1;
										foreach($coll_list as $val){
											
									?>
									<tr>
										<td style="width:100px; text-align: right; font-weight: bold;"><?=$i++;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?=$val['emp_name'];?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['prop_coll'])?$val['prop_coll']['propcount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['prop_coll'])?$val['prop_coll']['proppaid_amount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['saf_coll'])?$val['saf_coll']['propcount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['saf_coll'])?$val['saf_coll']['propcount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['newwater_coll'])?$val['newwater_coll']['newcount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['newwater_coll'])?$val['newwater_coll']['newpaid_amount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['demandwater_coll'])?$val['demandwater_coll']['demandcount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['demandwater_coll'])?$val['demandwater_coll']['demandpaid_amount']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['trade_coll'])?$val['trade_coll']['count']:0;?></td>
										<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($val['trade_coll'])?$val['trade_coll']['paid_amount']:0;?></td>
																			
									</tr>
									<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
					
					
					<div class="panel" style="margin-top:-19px;">
						<div class="panel-body text-right">
							<a  style="margin-top:2px;" href="#" download="Trade.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Property Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
    url: "<?=base_url('all_module_CollectionSummary/get_tax_collector_ajax');?>",
	data:{team_leader_id:team_leader_id},
    dataType:"json",
    success: function(response){
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

 