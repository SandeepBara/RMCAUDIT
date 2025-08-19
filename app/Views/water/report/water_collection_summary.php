<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
#tdId{font-size: medium; font-weight: bold; text-align: right;}
#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
#left{font-size: medium; font-weight: bold; text-align: left;}
.wardClass{font-size: medium; font-weight: bold;}
</style>

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
                    <li class="active">Collection Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Collection Report</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="Ward"><b>Ward No</b><span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<select id="ward_id" name="ward_id" class="form-control">
												   <option value="">ALL</option>  
													<?php foreach($ward_list as $value):?>
													<option value="<?=$value['id']?>" <?=(isset($ward_id))?($ward_id==$value["id"]?"SELECTED":""):"";?>><?=$value['ward_no'];?>
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
												<input type="date" id="date_from" name="date_from" class="form-control" placeholder="From Date" value="<?php echo $date_from; ?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<input type="date" id="date_upto" name="date_upto" class="form-control" placeholder="To Date" value="<?php echo $date_upto; ?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
									</div>
								</div>
								<div class="panel">
									<div class="panel-body text-center">
										<button type="submit" class="btn btn-primary btn-labeled" id="btn_property" name="btn_property">View Collection</button>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="#" download="Collection Report.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Water Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="button" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary" value="Print">
									</div>
								</div>
							</form>
						</div>
					</div>	
					
					<div id="printableArea">
						<div class="panel panel-bordered panel-dark" id="">
							<div class="panel-heading">
								<h3 class="panel-title">Collection Details</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-12">

									<div class="col-md-6">
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading" style="background-color: #298da0;">
												<h3 class="panel-title">Collection & Refund Description</h3>
											</div>
											<div class="panel-body">

												<table class="table table-bordered table-responsive">
													<tr>
														<th id="leftTd">Description</th>
														<th id="leftTd">Consumer</th>
														<th id="leftTd">Transactions</th>
														<th id="leftTd" align="right">Amount</th>
													</tr>
													<tr>
														<td>Cash Payment</td>
														<td style="text-align: right;"><?=isset($cash_consumer)?$cash_consumer:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($cash_count)?$cash_count:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($cash_amt)?$cash_amt:0.00,2)?>&nbsp;&nbsp;</td>
													</tr>
													<tr>
														<td>Cheque Payment</td>
														<td style="text-align: right;"><?=isset($cheque_consumer)?$cheque_consumer:0;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($cheque_count)?$cheque_count:0;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($cheque_amt)?$cheque_amt:0,2)?>&nbsp;&nbsp;</td>
													</tr>
													<tr>
														<td>DD Payment</td>
														<td style="text-align: right;"><?=isset($dd_consumer)?$dd_consumer:0;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($dd_count)?$dd_count:0;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($dd_amt)?$dd_amt:0,2)?>&nbsp;&nbsp;</td>
													</tr>
													<tr>
														<td>Online Payment</td>
														<td style="text-align: right;"><?=isset($online_consumer)?$online_consumer:0;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($online_count)?$online_count:0; ?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($online_amt)?$online_amt:0,2)?>&nbsp;&nbsp;</td>
													</tr>
													<tr style="font-weight:bold">
														<td>Total Collection</td>
														<!-- <td style="text-align: right;"><?php //echo ($cash_consumer+$cheque_consumer+$dd_consumer+$online_consumer)?($cash_consumer+$cheque_consumer+$dd_consumer+$online_consumer):0;?>&nbsp;&nbsp;</td> -->
														<td style="text-align: right;"><?php echo((isset($cash_consumer)?$cash_consumer:0) +(isset($cheque_consumer)?$cheque_consumer:0)+(isset($dd_consumer)?$dd_consumer:0)+(isset($online_consumer) ? $online_consumer:0));?>&nbsp;&nbsp;</td>

														<!-- <td style="text-align: right;"><?php //echo ($cash_count+$cheque_count+$dd_count+$online_count)?($cash_count+$cheque_count+$dd_count+$online_count):0;?>&nbsp;&nbsp;</td> -->
														<td style="text-align: right;"><?php echo ((isset($cash_count)?$cash_count:0)+(isset($cheque_count)?$cheque_count:0)+(isset($dd_count)?$dd_count:0)+(isset($online_count)?$online_count:0));?>&nbsp;&nbsp;</td>

														<td style="text-align: right;"><?php echo number_format(((isset($cash_amt)?$cash_amt:0)+(isset($cheque_amt)?$cheque_amt:0)+(isset($dd_amt)?$dd_amt:0)+(isset($online_amt)?$online_amt:0)),2);?>&nbsp;&nbsp;</td>
													</tr>

												</table>

												<table class="table table-bordered table-responsive">
													<tr>
														<th id="leftTd">Description</th>
														<th id="leftTd">Consumer</th>
														<th id="leftTd">Transactions</th>
														<th id="leftTd"align="right">Amount</th>
													</tr>
													<tr>
														<td>Cash Refund/Cancelled/Dishonoured</td>
														<td style="text-align: right;"><?=isset($bounced_cash_consumer)?$bounced_cash_consumer:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($bounced_cash_count)?$bounced_cash_count:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($bounced_cash_amt)?$bounced_cash_amt:0,2)?>&nbsp;&nbsp;</td>

													</tr>

													<tr>
														<td>Cheque Refund/Cancelled/Dishonoured	</td>
														<td style="text-align: right;"><?=isset($bounced_cheque_consumer)?$bounced_cheque_consumer:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($bounced_cheque_count)?$bounced_cheque_count:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($bounced_cheque_amt)?$bounced_cheque_amt:0,2)?>&nbsp;&nbsp;</td>
														
													</tr>

													<tr>
														<td>DD Refund/Cancelled/Dishonoured</td>
														<td style="text-align: right;"><?=isset($bounced_dd_consumer)?$bounced_dd_consumer:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($bounced_dd_count)?$bounced_dd_count:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($bounced_dd_amt)?$bounced_dd_amt:0,2)?>&nbsp;&nbsp;</td>
														
													</tr>

													<tr>
														<td>Online Refund/Cancelled/Dishonoured</td>
														<td style="text-align: right;"><?=isset($bounced_online_consumer)?$bounced_online_consumer:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=isset($bounced_online_count)?$bounced_online_count:0.00;?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format(isset($bounced_online_amt)?$bounced_online_amt:0,2)?>&nbsp;&nbsp;</td>
														
													</tr>


													<tr style="font-weight:bold" >
														<td>Total Refund/Cancelled/Dishonoured	</td>
														<!-- <td style="text-align: right;"><?=''//($bounced_cash_consumer+$bounced_cheque_consumer+$bounced_dd_consumer+$bounced_online_consumer);?>&nbsp;&nbsp;</td> -->
														<td style="text-align: right;"><?=((isset($bounced_cash_consumer)?$bounced_cash_consumer:0)+(isset($bounced_cheque_consumer)?$bounced_cheque_consumer:0)+(isset($bounced_dd_consumer)?$bounced_dd_consumer:0)+(isset($bounced_online_consumer)?$bounced_online_consumer:0));?>&nbsp;&nbsp;</td>
														<!-- <td style="text-align: right;"><?=''//$bounced_cash_count+$bounced_cheque_count+$bounced_dd_count+$bounced_online_count;?>&nbsp;&nbsp;</td> -->
														<td style="text-align: right;"><?=(isset($bounced_cash_count)?$bounced_cash_count:0)+(isset($bounced_cheque_count)?$bounced_cheque_count:0)+(isset($bounced_dd_count)?$bounced_dd_count:0)+(isset($bounced_online_count)?$bounced_online_count:0);?>&nbsp;&nbsp;</td>
														<td style="text-align: right;"><?=number_format((isset($bounced_cash_amt)?$bounced_cash_amt:0)+(isset($bounced_cheque_amt)?$bounced_cheque_amt:0)+(isset($bounced_dd_amt)?$bounced_dd_amt:0)+(isset($bounced_online_amt)?$bounced_online_amt:0),2)?>&nbsp;&nbsp;</td>
														
													</tr>
												</table>

												<table class="table table-bordered table-responsive">
													<tr style="font-weight:bold">
														<td>Net Balance</td>
														<!-- <td style="text-align: right;"><?php //echo ($cash_consumer+$cheque_consumer+$dd_consumer+$online_consumer)?($cash_consumer+$cheque_consumer+$dd_consumer+$online_consumer):0;?>&nbsp;&nbsp;</td> -->
														<td style="text-align: right;"><?php echo ((isset($cash_consumer)?$cash_consumer:0)+(isset($cheque_consumer)?$cheque_consumer:0)+(isset($dd_consumer)?$dd_consumer:0)+(isset($online_consumer)?$online_consumer:0));?>&nbsp;&nbsp;</td>

														<!-- <td style="text-align: right;"><?php //echo ($cash_count+$cheque_count+$dd_count+$online_count)?($cash_count+$cheque_count+$dd_count+$online_count):0;?>&nbsp;&nbsp;</td> -->
														<td style="text-align: right;"><?php echo ((isset($cash_count)?$cash_count:0)+(isset($cheque_count)?$cheque_count:0)+(isset($dd_count)?$dd_count:0)+(isset($online_count)?$online_count:0));?>&nbsp;&nbsp;</td>

														<td style="text-align: right;"><?php echo number_format(((isset($cash_amt)?$cash_amt:0)+(isset($cheque_amt)?$cheque_amt:0)+(isset($dd_amt)?$dd_amt:0)+(isset($online_amt)?$online_amt:0)),2);?>&nbsp;&nbsp;</td>

													</tr>
												</table>
											
											</div>
										</div>
									</div>


									<div class="col-md-6">
										<div class="panel panel-bordered panel-dark" id="printableArea">
											<div class="panel-heading" style="background-color: #298da0;">
												<h3 class="panel-title">Account Description</h3>
											</div>
											<div class="panel-body">
												<table class="table table-bordered">


												<tr>
													<td colspan="4" style="font-size: larger; font-weight: bold; text-align: center;color: #090f44">Account Description</td>
												</tr>

												<tr>
													<td id="leftTd">Connection Type</td>
													<td style="font-size: medium; font-weight: bold; text-align: right;color: #090f44">Amount</td>
												</tr>

												<tr>
													<td>New Connection (<?php echo isset($new_connection_count)?$new_connection_count:0;?>)	</td>
													<td style="text-align: right;"><?php echo number_format((isset($new_connection_amt)?$new_connection_amt:0),2);?></td>
												</tr>

												<tr>
													<td>Regularization (<?php echo isset($regularization_count)?$regularization_count:0;?>)	</td>
													<td style="text-align: right;"><?php echo number_format((isset($regularization_amt)?$regularization_amt:0),2);?></td>
												</tr>

												


												<tr>
													<td id="leftTd">Meter Type</td>
													<td style="font-size: medium; font-weight: bold; text-align: right;color: #090f44">Amount</td>
												</tr>

												<tr>
													<td>Metered (<?php echo isset($meter_count)?$meter_count:0;?>)	</td>
													<td style="text-align: right;"><?php echo number_format(isset($meter_amount)?$meter_amount:0,2);?></td>
												</tr>

												<tr>
													<td>Non-Metered (<?php echo isset($fixed_count)?$fixed_count:0;?>)	</td>
													<td style="text-align: right;"><?php echo number_format(isset($fixed_amount)?$fixed_amount:0,2);?></td>
												</tr>

												<tr>
													<td>Advance Amount (<?php echo isset($advance_count)?$advance_count:0;?>)	</td>
													<td style="text-align: right;"><?php echo number_format(isset($advance_amount)?$advance_amount:0,2);?></td>
												</tr>

												<tr>
													<table class="table table-bordered table-striped table-responsive">
														<tr>
															<td  id="leftTd">Property Type</td>
															<td  id="leftTd" style="text-align: right;">Amount</td>
														</tr>
														<tr>
															<td >Residential (<?php echo isset($residential_count)?$residential_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($residential_amount)?$residential_amount:0,2);?></td>
														</tr>
														<tr>
															<td >Commercial (<?php echo isset($commercial_count)?$commercial_count:0;?>)</td>
															<td style="text-align: right;" ><?php echo number_format(isset($commercial_amount)?$commercial_amount:0,2);?></td>
														</tr>
														<tr>
															<td >Apartment (<?php echo isset($appartment_count)?$appartment_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($appartment_amount)?$appartment_amount:0,2);?></td>
														</tr>
														<tr>
															<td >Goverment & PSU (<?php echo isset($gov_psu_count)?$gov_psu_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($gov_psu_amount)?$gov_psu_amount:0,2);?></td>
														</tr>
														<tr>
															<td >Institutional (<?php echo isset($institutional_count)?$institutional_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($institutional_amount)?$institutional_amount:0,2);?></td>
														</tr>
														<tr>
															<td >SSI Unit (<?php echo isset($ssi_count)?$ssi_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($ssi_amount)?$ssi_amount:0,2);?></td>
														</tr>
														<tr>
															<td >Trust & NGO (<?php echo isset($trust_ngo_count)?$trust_ngo_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($trust_ngo_amount)?$trust_ngo_amount:0,2);?></td>
														</tr>
														<tr>
															<td >Industrial (<?php echo isset($industrial_count)?$industrial_count:0;?>)</td>
															<td style="text-align: right;"><?php echo number_format(isset($industrial_amount)?$industrial_amount:0,2);?></td>
														</tr>
														
													</table>
												</tr>
											</table>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?=$this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/excellentexport.js"></script>
<script type="text/javascript">
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
    
	
</script>