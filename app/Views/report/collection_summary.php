<?= $this->include('layout_vertical/header');?>
<style>
	.row{line-height: 25px;}
	.wardClass{font-size: medium; font-weight: bold;}
	#tdId{font-size: medium; font-weight: bold; text-align: right;}
	#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
	#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
	#left{font-size: medium; font-weight: bold; text-align: left;}
}
</style>
<!-- <style type="text/css" media="print">
.dontprint{ display:none}
</style> -->
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
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/PropertyCollectionSummary/report">
								<div class="row" >
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
										<button type="submit" class="btn btn-primary btn-labeled" id="btn_property" name="btn_property">View Collection</button> &nbsp;&nbsp;&nbsp;&nbsp;
										<a  href="#" download="Property.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Property Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary" value="Print">
									</div>
								</div>
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Collection Details</h3>
							<!-- <div class="col-sm-12 noprint text-right mar-top">
									<button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;">PRINT</button>
								</div> -->
						</div><br/><br/>
						<label style="font-weight: bold; font-size: 16px; color: #090f44">Total No. of Properties Covered : <?=$total_No_holding['holding']!=""?$total_No_holding['holding']:0;?></label><br/>
							<table style="width: 98%">
								<tr >
									<td style="width: 49%; vertical-align: top">
										<table class="table table-bordered">
											<tr>
												<td colspan="3" style="font-size: larger; font-weight: bold; text-align: center;color: #090f44">Collection & Refund Description</td>
											</tr>
											<tr>
												<td id="leftTd">Description</td>
												<td id="leftTd">Holdings</td>
												<td id="leftTd">Amount</td>
											</tr>
											<tr>
												<td id="left">Cash Payment</td>
												<td id="tdId"><?=$cash['holding']!=""?$cash['holding']:0;?></td>
												<td id="tdId"><?=$cash['cash']!=""?round($cash['cash']).'.'.'00':'0.00';?></td>
												
											</tr>
											<tr>
												<td id="left">Cheque Payment</td>
												<td id="tdId"><?=$cheque['holding']!=""?$cheque['holding']:0;?></td>
												<td id="tdId"><?=$cheque['cheque']!=""?round($cheque['cheque']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">DD Payment</td>
												<td id="tdId"><?=$dd['holding']!=""?$dd['holding']:0;?></td>
												<td id="tdId"><?=$dd['dd']!=""?round($dd['dd']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Card Payment</td>
												<td id="tdId"><?=$card['holding']!=""?$card['holding']:0;?></td>
												<td id="tdId"><?=$card['card']!=""?round($card['card']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Online Payment</td>
												<td id="tdId"><?=$online['holding']!=""?$online['holding']:0;?></td>
												<td id="tdId"><?=$online['online']!=""?round($online['online']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Fund Transfer</td>
												<td id="tdId"><?=$fund['holding']!=""?$fund['holding']:0;?></td>
												<td id="tdId"><?=$fund['fund']!=""?round($fund['fund']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">I-SURE Payment</td>
												<td id="tdId"><?=$i_sure['holding']!=""?$i_sure['holding']:0;?></td>
												<td id="tdId"><?=$i_sure['i_sure']!=""?round($i_sure['i_sure']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total Collection</td>
												<td id="tdRight"><?=$total_holding!=""?$total_holding:0;?></td>
												<td id="tdRight"><?=$total_collection!=""?round($total_collection):'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cash Refund/Cancelled</td>
												<td id="tdId"><?=$cancel_cash['holding']!=""?$cancel_cash['holding']:0;?></td>
												<td id="tdId"><?=$cancel_cash['amount']!=""?round($cancel_cash['amount']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cheque Cancelled/Dishonored</td>
												<td id="tdId"><?=$cancel_cheque['holding']!=""?$cancel_cheque['holding']:0;?></td>
												<td id="tdId"><?=$cancel_cheque['cheque']!=""?round($cancel_cheque['cheque']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">DD Cancelled/Dishonored</td>
												<td id="tdId"><?=$cancel_dd['holding']!=""?$cancel_dd['holding']:0;?></td>
												<td id="tdId"><?=$cancel_dd['dd']!=""?round($cancel_dd['dd']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Card Payment Cancelled/Dishonored</td>
												<td id="tdId"><?=$cancel_card['holding']!=""?$card['holding']:0;?></td>
												<td id="tdId"><?=$cancel_card['card']!=""?round($cancel_card['card']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Online Payment Cancelled/Dishonored</td>
												<td id="tdId"><?=$cancel_online['holding']!=""?$cancel_online['holding']:0;?></td>
												<td id="tdId"><?=$cancel_online['online']!=""?round($cancel_online['online']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Fund Transter Refund/Cancelled</td>
												<td id="tdId"><?=$cancel_fund['holding']!=""?$cancel_fund['holding']:0;?></td>
												<td id="tdId"><?=$cancel_fund['fund']!=""?round($cancel_fund['fund']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">I-SURE Payment Cancelled</td>
												<td id="tdId"><?=$cancel_i_sure['holding']!=""?$cancel_i_sure['holding']:0;?></td>
												<td id="tdId"><?=$cancel_i_sure['i_sure']!=""?round($cancel_i_sure['i_sure']).'.'.'00':'0.00';?></td>
											</tr>
											<tr style="font-weight: bold">
												<td id="leftTd">Total Refund/Cancellation</td>
												<td id="tdRight"><?=$cancel_holding!=""?$cancel_holding:0;?></td>
												<td id="tdRight"><?=$total_cancel!=""?round($total_cancel):'0.00';?></td>
											</tr>
											<tr style="font-weight: bold">
												<td id="leftTd">Net Collection</td>
												<td id="tdRight"><?=$net_holding!=""?$net_holding:0;?></td>
												<td id="tdRight"><?=$net_collection!=""?round($net_collection):'0.00';?></td>
											</tr>
										</table>
									</td>
									<td style="width: 2%"></td>
									<td style="width: 49%; vertical-align: top">
										<table class="table table-bordered">
											<tr>
												<td style="font-size: larger; font-weight: bold;color: #090f44">Account Description
												</td>
												<td style="font-size: larger; font-weight: bold; text-align: right;color: #090f44">Amount
												</td>
											</tr>
											<tr>
												<td id="left">Holding Tax</td>
												<td id="tdId"><?=$tax['holding_tax']!=""?round($tax['holding_tax']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Water Tax</td>
												<td id="tdId"><?=$tax['water_tax']!=""?round($tax['water_tax']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Latrine Tax</td>
												<td id="tdId"><?=$tax['latrine_tax']!=""?round($tax['latrine_tax']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Education Cess</td>
												<td id="tdId"><?=$tax['education_cess']!=""?round($tax['education_cess']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Health Cess</td>
												<td id="tdId"><?=$tax['health_cess']!=""?round($tax['health_cess']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total</td>
												<td id="tdRight"><?=$total!=""?round($total).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cash Payment (<?=$cash['id']!=""?$cash['id']:'0';?>)</td>
												<td id="tdId"><?=$cash['cash']!=""?round($cash['cash']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Cheque Payment (<?=$cheque['id']!=""?$cheque['id']:'0';?>)
												</td>
												<td id="tdId"><?=$cheque['cheque']!=""?round($cheque['cheque']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">DD Payment (<?=$dd['id']!=""?$dd['id']:'0';?>)
												</td>
												<td id="tdId"><?=$dd['dd']!=""?round($dd['dd']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Card Payment (<?=$card['id']!=""?$card['id']:'0';?>)
												</td>
												<td id="tdId"><?=$card['card']!=""?round($card['card']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">Online Payment (<?=$online['id']!=""?$online['id']:'0';?>)</td>
												<td id="tdId"><?=$online['online']!=""?round($online['online']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">NEFT Payment (<?=$netPayment['id']!=""?$netPayment['id']:'0';?>)</td>
												<td id="tdId"><?=$netPayment['netPayment']!=""?round($netPayment['netPayment']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">RTGS Payment (<?=$rtgsPayment['id']!=""?$rtgsPayment['id']:'0';?>)
												</td>
												<td id="tdId"><?=$rtgsPayment['rtgsPayment']!=""?round($rtgsPayment['rtgsPayment']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="left">I-SURE Payment (<?=$i_sure['id']!=""?$i_sure['id']:'0';?>)
												</td>
												
												<td id="tdId"><?=$i_sure['i_sure']!=""?round($i_sure['i_sure']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Total Received</td>
												<td id="tdRight"><?=$net_collection!=""?round($net_collection):'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Rebate</td>
												<td id="tdRight"><?=$rebate['rebate']!=""?round($rebate['rebate']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Advance  
												</td>
												<td id="tdRight"><?=$advanced['advance']!=""?round($advanced['advance']).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd">Grand Total 
												</td>
												<td id="tdRight"><?=$grand!=""?round($grand).'.'.'00':'0.00';?></td>
											</tr>
											<tr>
												<td id="leftTd" colspan="2">Total No of Holdings : <?=$net_holding!=""?$net_holding:0;?>
												</td>
												
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
	window.location.reload();
	document.body.innerHTML = data;
	}
</script>