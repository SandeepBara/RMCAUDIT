<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
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
                                            <form class="form-horizontal" method="post" action="<?=base_url('');?>/AllModuleCollection/report">
                                                <div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
														<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-3">
														<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
														<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-3">
														<label class="control-label" for="Payment Mode"><b>Mode</b><span class="text-danger">*</span> </label>
														<select id="tran_mode" name="tran_mode" class="form-control">
															<option value="ALL" <?=(isset($tran_mode))?($tran_mode=="ALL")?"selected":"":"";?>>ALL</option>
															<option value="CASH" <?=(isset($tran_mode))?($tran_mode=="CASH")?"selected":"":"";?>>CASH</option>
															<option value="CHEQUE" <?=(isset($tran_mode))?($tran_mode=="CHEQUE")?"selected":"":"";?>>CHEQUE</option>
															<option value="DD" <?=(isset($tran_mode))?($tran_mode=="DD")?"selected":"":"";?>>DD</option>
															<option value="ONLINE" <?=(isset($tran_mode))?($tran_mode=="ONLINE")?"selected":"":"";?>>ONLINE</option>
														</select>
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
                                                <th style="width:30px;">#</th>
                                                <th style="width:50px;">Date</th>
                                                <th style="width:100px;">Property</th>
                                                <th style="width:100px;">Trade</th>
                                                <th style="width:100px;">Water</th>
                                                <th style="width:100px;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                        $groundTotal =0;
                                        $totalProperty =0;
                                        $totalTrade =0;
                                        $totalWater =0;
                                    
                                    if(!isset($allmodulecollection)):
                                    ?>
                                            <tr>
                                                <td colspan="6" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;

                                            foreach ($allmodulecollection as $value):

                                                $groundTotal=$groundTotal+$value['m_total_amount'];
                                                $totalProperty=$totalProperty+$value['m_property_amount'];
                                                $totalTrade=$totalTrade+$value['m_trade_amount'];
                                                $totalWater=$totalWater+$value['m_water_amount'];
                                    ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value['m_date']!=""?date('d-m-Y',strtotime($value['m_date'])):"N/A";?></td>
                                                <td class="text-right pad-no"><?=$value['m_property_amount']!=""?round($value['m_property_amount']).".00":"0";?></td>
                                                <td class="text-right pad-no"><?=$value['m_trade_amount']!=""?round($value['m_trade_amount']).".00":"0";?></td>
                                                <td class="text-right pad-no"><?=$value['m_water_amount']!=""?round($value['m_water_amount']).".00":"0";?></td>
                                                <td class="text-right pad-no"><?=$value['m_total_amount']!=""?round($value['m_total_amount']).".00":"0";?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    <?php endif;  ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            <td colspan="3" class="text-right text-danger text-bold pad-no"><?=(isset($totalProperty))?round($totalProperty).".00":"0";?></td>
                                            <td class="text-right text-danger text-bold pad-no"><?=(isset($totalTrade))?round($totalTrade).".00":"0";?></td>
                                            <td class="text-right text-danger text-bold pad-no"><?=(isset($totalWater))?round($totalWater).".00":"0";?></td>
                                            <td class="text-right text-danger text-bold pad-no"><?=(isset($groundTotal))?round($groundTotal).".00":"0";?></td>
                                            </tr>
                                        </tfoot>
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
                exportOptions: { columns: [ 0, 1,2,3,4,5] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5] }
            }]
        });
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