<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
#tdId{font-size: medium; font-weight: bold; text-align: right;}
#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
#left{font-size: medium; font-weight: bold; text-align: left;}
.wardClass{font-size: medium; font-weight: bold;}
</style>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
                    <li class="active">Counter Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Counter Report</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/WaterCollectionSummary/report">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="Ward"><b>Ward No</b><span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
												   <option value="">ALL</option>  
													<?php foreach($ward_list as $value):?>
													<option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
													</option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2"></div>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-6">
												<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
									</div>
								</div>
								<div class="panel">
									<div class="panel-body text-center">
										<button type="submit" class="btn btn-primary btn-labeled" id="btn_property" name="btn_property">View Collection</button>&nbsp;&nbsp;&nbsp;&nbsp;
										<a  href="#" download="Water.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Property Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="button" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary" value="Print">
									</div>
								</div>
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Collection Details</h3>
						</div><br/><br/>
							<table style="width: 98%">
								<tr>
									<td style="width: 49%; vertical-align: top">
										<table class="table table-bordered">
											<tr>
												<td colspan="4" style="font-size: larger; font-weight: bold; text-align: center;color: #090f44">Payment Mode Wise Collection & Collection Description</td>
											</tr>
											<tr>
												<td id="leftTd">Type</td>
												<td id="leftTd">No Of Consumer</td>
												<td id="leftTd">No Of Transaction</td>
												<td id="leftTd">Amount</td>
											</tr>
											<tr>
												<td id="left">Cash Payment</td>
												<td id="tdId"><?=$cash['consumer']!=""?$cash['consumer']:0;?></td>
												<td id="tdId"><?=$cash['id']!=""?round($cash['id']):0;?></td>
												<td id="tdId"><?=$cash['cash']!=""?round($cash['cash']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cheque Payment</td>
												<td id="tdId"><?=$cheque['consumer']!=""?$cheque['consumer']:0;?></td>
												<td id="tdId"><?=$cheque['id']!=""?round($cheque['id']):0;?></td>
												<td id="tdId"><?=$cheque['cheque']!=""?round($cheque['cheque']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">DD Payment</td>
												<td id="tdId"><?=$dd['consumer']!=""?$dd['consumer']:0;?></td>
												<td id="tdId"><?=$dd['id']!=""?round($dd['id']):0;?></td>
												<td id="tdId"><?=$dd['dd']!=""?round($dd['dd']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Online Payment</td>
												<td id="tdId"><?=$online['consumer']!=""?$online['consumer']:0;?></td>
												<td id="tdId"><?=$online['id']!=""?round($online['id']):0;?></td>
												<td id="tdId"><?=$online['online']!=""?round($online['online']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">CARD Payment</td>
												<td id="tdId"><?=$card['consumer']!=""?$card['consumer']:0;?></td>
												<td id="tdId"><?=$card['id']!=""?round($card['id']):0;?></td>
												<td id="tdId"><?=$card['card']!=""?round($card['card']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total Collection</td>
												<td id="tdRight"><?=$total_consumer!=""?$total_consumer:0;?></td>
												<td id="tdRight"><?=$total_transaction!=""?$total_transaction:0;?></td>
												<td id="tdRight"><?=$total_amount!=""?$total_amount.'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cheque Cancalled</td>
												<td id="tdId"><?=$cheque_cancel['consumer']!=""?$cheque_cancel['consumer']:0;?></td>
												<td id="tdId"><?=$cheque_cancel['id']!=""?round($cheque_cancel['id']):0;?></td>
												<td id="tdId"><?=$cheque_cancel['cheque']!=""?round($cheque_cancel['cheque']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">DD Cancalled</td>
												<td id="tdId"><?=$dd_cancel['consumer']!=""?$dd_cancel['consumer']:0;?></td>
												<td id="tdId"><?=$dd_cancel['id']!=""?round($dd_cancel['id']):0;?></td>
												<td id="tdId"><?=$dd_cancel['dd']!=""?round($dd_cancel['dd']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Online Payment Cancalled</td>
												<td id="tdId"><?=$online_cancel['consumer']!=""?$online_cancel['consumer']:0;?></td>
												<td id="tdId"><?=$online_cancel['id']!=""?round($online_cancel['id']):0;?></td>
												<td id="tdId"><?=$online_cancel['online']!=""?round($online_cancel['online']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">CARD Payment Cancalled</td>
												<td id="tdId"><?=$card_cancel['consumer']!=""?$card_cancel['consumer']:0;?></td>
												<td id="tdId"><?=$card_cancel['id']!=""?round($card_cancel['id']):0;?></td>
												<td id="tdId"><?=$card_cancel['card']!=""?round($card_cancel['card']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total Cancellation</td>
												<td id="tdRight"><?=$total_consumer_cancel!=""?$total_consumer_cancel:0;?></td>
												<td id="tdRight"><?=$total_transaction_cancel!=""?$total_transaction_cancel:0;?></td>
												<td id="tdRight"><?=$total_amount_cancel!=""?$total_amount_cancel.'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td style="font-size: medium; font-weight: bold; text-align: left;color: #090f44">Net Collection</td>
												<td id="tdRight"><?=$net_consumer!=""?$net_consumer:0;?></td>
												<td id="tdRight"><?=$net_transaction!=""?$net_transaction:0;?></td>
												<td id="tdRight"><?=$net_amount!=""?$net_amount.'.'.'00':'0.00';?></td>
											</tr>
										</table>
									</td>
									<td style="width: 2%"></td>
									<td style="width: 49%; vertical-align: top">
										<table class="table table-bordered">
											<tr>
												<td colspan="4" style="font-size: larger; font-weight: bold; text-align: center;color: #090f44">Actual Collection Description</td>
											</tr>
											<tr>
												<td id="leftTd">Type</td>
												<td id="leftTd">No Of Consumer</td>
												<td id="leftTd">No Of Transaction</td>
												<td style="font-size: medium; font-weight: bold; text-align: left;color: #090f44">Amount</td>
											</tr>
											<tr>
												<td id="left">New Connection</td>
												<td id="tdId"><?=$new_connection['consumer']!=""?$new_connection['consumer']:0;?></td>
												<td id="tdId"><?=$new_connection['id']!=""?round($new_connection['id']):0;?></td>
												<td id="tdId"><?=$new_connection['new']!=""?round($new_connection['new']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Demand Collection</td>
												<td id="tdId"><?=$demand_collection['consumer']!=""?$demand_collection['consumer']:0;?></td>
												<td id="tdId"><?=$demand_collection['id']!=""?round($demand_collection['id']):0;?></td>
												<td id="tdId"><?=$demand_collection['renewal']!=""?round($demand_collection['renewal']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total Collection</td>
												<td id="tdRight"><?=$total_new_collection!=""?$total_new_collection:0;?></td>
												<td id="tdRight"><?=$total_new_transaction!=""?$total_new_transaction:0;?></td>
												<td id="tdRight"><?=$total_new_amount!=""?$total_new_amount.'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cash Payment</td>
												<td id="tdId"><?=$cash['consumer']!=""?$cash['consumer']:0;?></td>
												<td id="tdId"><?=$cash['id']!=""?round($cash['id']):0;?></td>
												<td id="tdId"><?=$cash['cash']!=""?round($cash['cash']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cheque Payment</td>
												<td id="tdId"><?=$cheque['consumer']!=""?$cheque['consumer']:0;?></td>
												<td id="tdId"><?=$cheque['id']!=""?round($cheque['id']):0;?></td>
												<td id="tdId"><?=$cheque['cheque']!=""?round($cheque['cheque']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">DD Payment</td>
												<td id="tdId"><?=$dd['consumer']!=""?$dd['consumer']:0;?></td>
												<td id='tdId'><?=$dd['id']!=""?round($dd['id']):0;?></td>
												<td id="tdId"><?=$dd['dd']!=""?round($dd['dd']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Online Payment</td>
												<td id="tdId"><?=$online['consumer']!=""?$online['consumer']:0;?></td>
												<td id="tdId"><?=$online['id']!=""?round($online['id']):0;?></td>
												<td id="tdId"><?=$online['online']!=""?round($online['online']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">CARD Payment</td>
												<td id="tdId"><?=$card['consumer']!=""?$card['consumer']:0;?></td>
												<td id="tdId"><?=$card['id']!=""?round($card['id']):0;?></td>
												<td id="tdId"><?=$card['card']!=""?round($card['card']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total Collection</td>
												<td id="tdRight"><?=$total_consumer!=""?$total_consumer:0;?></td>
												<td id="tdRight"><?=$total_transaction!=""?$total_transaction:0;?></td>
												<td id="tdRight"><?=$total_amount!=""?$total_amount.'.'.'00':'0.00';?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="width: 49%">&nbsp;</td>
									<td style="width: 2%">&nbsp;</td>
									<td style="width: 49%">&nbsp;</td>
								</tr>
							</table>
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
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }]
        });
    });
    $('#btn_property').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(to_date==""){
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        if(to_date<from_date){
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
	window.location.reload();
	document.body.innerHTML = data;
	}
</script>