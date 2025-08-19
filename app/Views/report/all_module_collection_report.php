<?= $this->include('layout_vertical/header');?>

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
                    <li class="active">All Module Collection</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-dark">
                                <div class="panel-heading">
                                    <h5 class="panel-title">All Module Collection</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
														<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
														<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                        <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
										<div class="table-responsive">
											<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="width:100px; text-align: center;" colspan="6">Property</th>
														<th style="width:100px; text-align: center;" colspan="4">Water</th>
														<th style="width:100px; text-align: center;"colspan="2">Trade</th>
														<th style="width:100px; text-align: center;">Total</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th style="width:30px; text-align: center;" colspan="2">Property</th>
														<th style="width:30px; text-align: center;" colspan="2">SAF</th>
                                                        <th style="width:30px; text-align: center;" colspan="2">GBSAF</th>
														<th style="width:30px; text-align: center;" colspan="2">New Connection</th>
														<th style="width:30px; text-align: center;" colspan="2">Demand Collection</th>
														<th style="width:30px; text-align: center;" colspan="2">Trade</th>
														<th style="width:100px; text-align: center;"></th>
													</tr>
													<tr>
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
														<th style="width:100px; text-align: center;">Count</th>
														<th style="width:100px; text-align: center;">Total</th>														
														<th style="width:100px; text-align: center;"></th>
													</tr>
													<?php
													if(!isset($prop_count) && !isset($saf_count) && !isset($trade_count))
													{
													?>
													<tr><td colspan="11" style="text-align: center; color: red;">No Records Found!!!</td></tr>
													<?php
													}
													else
													{
													?>
													<tr>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($prop_count)?$prop_count:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($prop_coll)?$prop_coll:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($saf_count)?$saf_count:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($saf_coll)?$saf_coll:0;?></td>
                                                        <td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($govt_saf_count)?$govt_saf_count:0;?></td>
                                                        <td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($govt_saf_coll)?$govt_saf_coll:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($water_new_count)?$water_new_count:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($water_new_coll)?number_format($water_new_coll,2):0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($water_dmd_count)?$water_dmd_count:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($demand_coll)?$demand_coll:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($trade_count)?$trade_count:0;?></td>
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo isset($trade_coll)?$trade_coll:0;?></td>
														<!-- <td style="width:100px; text-align: right; font-weight: bold;"><?php /* echo round($prop_coll+$saf_coll+$water_new_coll+$demand_coll+$trade_coll);*/?></td> -->
														<td style="width:100px; text-align: right; font-weight: bold;"><?php echo round((isset($prop_coll)?$prop_coll:0)+(isset($saf_coll)?$saf_coll:0)+(isset($govt_saf_coll)?$govt_saf_coll:0)+(isset($water_new_coll)?$water_new_coll:0)+(isset($demand_coll)?$demand_coll:0)+(isset($trade_coll)?$trade_coll:0));?></td>
													</tr>
													<?php
													}
													?>										  
												</tbody>
											</table>
										</div>
                                    </div>
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
<!--DataTables [ OPTIONAL ]-->

<script type="text/javascript">
    $(document).ready(function(){
       
        $('#btn_search').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date=="")
            {
                $("#from_date").css({"border-color":"red"});
                $("#from_date").focus();
                return false;
            }
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
    });

</script>