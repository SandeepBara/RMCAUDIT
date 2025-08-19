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
                    <li class="active">Mode Wise Collection</li>
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
                                    <h5 class="panel-title">Mode Wise Collection</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('');?>/WaterModeWiseCollection/report">
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
														<label class="control-label" for="Payment Mode"><b>Payment Mode</b><span class="text-danger">*</span> </label>
														<select id="payment_mode" name="payment_mode" class="form-control">
															<option value="">ALL</option>
															<option value="CASH" <?=(isset($payment_mode))?($payment_mode=="CASH")?"selected":"":"";?>>Cash</option>
                                                            <option value="CHEQUE" <?=(isset($payment_mode))?($payment_mode=="CHEQUE")?"selected":"":"";?>>Cheque</option>
															<option value="DD" <?=(isset($payment_mode))?($payment_mode=="DD")?"selected":"":"";?>>DD</option>
														    <option value="ONLINE" <?=(isset($payment_mode))?($payment_mode=="ONLINE")?"selected":"":"";?>>Online</option>
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
                                        <div class="text-center text-danger text-bold">Total Amount: <?php echo isset($total)?number_format($total):0;?> 
                                        </div>
                                    <div class="table-responsive">
                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward No.</th>
                                                <th>Consumer/Application No.</th>
                                                <th>Mobile No.</th>
                                                <th>Transaction No.</th>
                                                <th>Transaction Date</th>
                                                <th>Payment Mode</th>
                                                <th>Amount</th>
                                                <th>Cheque No.</th>
                                                <th>Cheuqe Date</th>
                                                <th>Bank Name</th>
                                                <th>Branch Name</th>
                                                <th>Collected By</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(!isset($demand_transactions) and !isset($new_connection_transactions)):
                                        ?>
                                                <tr>
                                                    <td colspan="13" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                        <?php else:
                                                $i=0;

                                                foreach ($demand_transactions as $value):
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?php echo $value['ward_no'];?></td>
                                                    <td><?php echo $value['consumer_no'];?></td>
                                                    <td><?php echo $value['mobile_no'];?></td>
                                                    <td><?php echo $value['transaction_no'];?></td>
                                                    <td><?php echo $value['transaction_date'];?></td>
                                                    <td><?php echo $value['payment_mode'];?></td>
                                                    <td style="text-align: right;"><?php echo number_format($value['paid_amount']);?></td>
                                                    <td><?php echo $value['cheque_no'];?></td>
                                                    <td><?php echo $value['cheque_date'];?></td>
                                                    <td><?php echo $value['bank_name'];?></td>
                                                    <td><?php echo $value['branch_name'];?></td>
                                                    <td><?php echo $value['emp_name'];?></td>
                                                    
                                                    
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;  ?>

                                         <?php
                                        if(!isset($new_connection_transactions)):
                                        ?>
                                              
                                        <?php else:
                                                $i=0;

                                                foreach ($new_connection_transactions as $value):
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?php echo $value['ward_no'];?></td>
                                                    <td><?php echo $value['application_no'];?></td>
                                                    <td><?php echo $value['mobile_no'];?></td>
                                                    <td><?php echo $value['transaction_no'];?></td>
                                                    <td><?php echo $value['transaction_date'];?></td>
                                                    <td><?php echo $value['payment_mode'];?></td>
                                                    <td style="text-align: right;"><?php echo $value['paid_amount'];?></td>
                                                    <td><?php echo $value['cheque_no'];?></td>
                                                    <td><?php echo $value['cheque_date'];?></td>
                                                    <td><?php echo $value['bank_name'];?></td>
                                                    <td><?php echo $value['branch_name'];?></td>
                                                    <td><?php echo $value['emp_name'];?></td>
                                                    
                                                    
                                                </tr>
                                            <?php endforeach;?>
                                        <?php endif;  ?>
                                        </tbody>
                                         <tfoot>
                                            <tr>
                                              <td colspan="7" class="text-right text-danger text-bold">Total</td>
                                              <td class="text-danger text-bold text-right"><?=(isset($total))?$total:"0";?></td>
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
                exportOptions: { columns: [ 0,1,2,3,4, 5, 6, 7, 8, 9, 10, 11, 12] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4, 5, 6, 7, 12] },
                orientation: 'landscape',//orientamento stampa
            }]
        });
    });
    $('#btn_search').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(from_date==""){
            $("#from_date").css({"border-color":"red"});
            $("#from_date").focus();
            return false;
        }
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