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
                    <li class="active">Bank Reconciliation List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Bank Reconciliation List</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('');?>/BankReconciliationReport/generation">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                                    <div class="input-group">
                                                        <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                <label class="control-label" for="module">Module<span class="text-danger">*</span> </label>
                                                   <select id="module" name="module" class="form-control">
                                                       <option value="">--SELECT--</option>
                                                       <option value="PROPERTY" <?=(isset($module))?$module=="PROPERTY"?"SELECTED":"":"";?>>PROPERTY</option>
                                                       <option value="WATER" <?=(isset($module))?$module=="WATER"?"SELECTED":"":"";?>>WATER</option>
                                                       <option value="TRADE" <?=(isset($module))?$module=="TRADE"?"SELECTED":"":"";?>>TRADE</option>
                                                     <!--   <option value="ADVERTISEMENT" <?=(isset($module))?$module=="ADVERTISEMENT"?"SELECTED":"":"";?>>ADVERTISEMENT</option> -->
                                                   </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="holding">&nbsp;</label>
                                                    <button class="btn btn-primary btn-block" id="btn_holding" name="btn_holding" type="submit">Search</button>
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
                                                <th>#</th>
                                                <th>Ward No</th>
                                                <th>Holding No/SAF No</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No</th>
                                                <th>Cancel Date</th>
                                                <th>Cheque No</th>
                                                <th>Bank Name</th>
                                                <th>Branch Name</th>
                                                <th>Transaction Date</th>
                                                <th>Transaction No</th>
                                                <th>Reason</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(!isset($bankCancelData)):
                                    ?>
                                    <tr>
                                        <td colspan="13" style="text-align: center;">Data Not Available!!!</td>
                                    </tr>
                                    <?php else:
                                        $i=0;
                                        foreach ($bankCancelData as $value):
                                    ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$value['ward_no']!=""?$value['ward_no']:"N/A";?></td>
                                            <td><?=$value['holding_no']!=""?$value['holding_no']:$value['saf_no'];?></td>
                                            <td><?=$value['owner']!=""?$value['owner']:"N/A";?></td>
                                            <td><?=$value['mobile_no']!=""?$value['mobile_no']:"N/A";?></td>
                                            <td><?=$value['cancel_date']!=""?date('d-m-Y',strtotime($value['cancel_date'])):"N/A";?></td>
                                            <td><?=$value['cheque_no']!=""?$value['cheque_no']:"N/A";?></td>
                                            <td><?=$value['bank_name']!=""?$value['bank_name']:"N/A";?></td>
                                            <td><?=$value['branch_name']!=""?$value['branch_name']:"N/A";?></td>
                                            <td><?=$value['tran_date']!=""?date('d-m-Y',strtotime($value['tran_date'])):"N/A";?></td>
                                            <td><?=$value['tran_no']!=""?$value['tran_no']:"N/A";?></td>
                                            <td><?=$value['reason']!=""?$value['reason']:"N/A";?></td>
                                            <td><?=$value['amount']!=""?$value['amount']:"N/A";?></td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;  ?>
                                        </tbody>
                                        <!-- <tfoot>
                                            <tr>
                                              <td colspan="9" style="text-align: right;">Total</td>
                                              <td><?=(isset($total))?$total:"";?></td>
                                            </tr>
                                          </tfoot> -->
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9,10,11,12] }
            }/*, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9,10,11,12] }
            }*/]
        });
        
    });
    $('#btn_holding').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var mod = $('#module').val();
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
        if(mod=="")
        {
            $("#module").css({"border-color":"red"});
            $("#module").focus();
            return false;
        }
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});
    $("#module").change(function(){$(this).css('border-color','');});
</script>