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
                    <li class="active">Collection Mode Wise</li>
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
                                    <h5 class="panel-title">Collection Mode Wise</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('');?>/CollectionModeWiseMobi/report">
                                                <div class="form-group">
                                                    <div class="col-md-2">
                                                    <label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
                                                    <div class="input-group">
                                                        <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                                    <div class="input-group">
                                                        <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                                 <div class="col-md-2">
                                                        <label class="control-label" for="module">Module<span class="text-danger">*</span> </label>
                                                           <select id="module" name="module" class="form-control">
                                                               <option value="">--SELECT--</option>
                                                               <option value="PROPERTY" <?=(isset($module))?$module=="PROPERTY"?"SELECTED":"":"";?>>PROPERTY</option>
                                                               <option value="WATER" <?=(isset($module))?$module=="WATER"?"SELECTED":"":"";?>>WATER</option>
                                                               <option value="TRADE" <?=(isset($module))?$module=="TRADE"?"SELECTED":"":"";?>>TRADE</option>
                                                              <!--  <option value="ADVERTISEMENT" <?=(isset($module))?$module=="ADVERTISEMENT"?"SELECTED":"":"";?>>ADVERTISEMENT</option> -->
                                                           </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <label class="control-label" for="Payment Mode"><b>Mode</b><span class="text-danger">*</span> </label>
                                                    <select id="payment_mode" name="payment_mode" class="form-control">
                                                        <option value="">ALL</option>
                                                        <option value="CASH" <?=(isset($payment_mode))?$payment_mode=="CASH"?"SELECTED":"":"";?>>CASH</option>
                                                        <option value="CHEQUE" <?=(isset($payment_mode))?$payment_mode=="CHEQUE"?"SELECTED":"":"";?>>CHEQUE</option>
                                                        <option value="DD" <?=(isset($payment_mode))?$payment_mode=="DD"?"SELECTED":"":"";?>>DD</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
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
                                                <th>#</th>
                                                <th>Ward No</th>
                                                <th>Transaction Date</th>
                                                <th>Transaction No</th>
                                                <th>Payment Type</th>
                                                <th>Application No</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(!isset($tcDetails)):
                                    ?>
                                            <tr>
                                                <td colspan="9" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;

                                            foreach ($tcDetails as $value):
                                    ?>
                                            <tr class='<?php if($value['status']==3){echo "text-danger";}?>'>
                                                <td><?=++$i;?></td>
                                                <td><?=$value['ward_no']!=""?$value['ward_no']:"N/A";?></td>
                                                <td><?=$value['transaction_date']!=""?date('d-m-Y',strtotime($value['transaction_date'])):"N/A";?></td>
                                                <td><?=$value['transaction_no']!=""?$value['transaction_no']:"N/A";?></td>
                                                <td><?=$value['payment_mode']!=""?$value['payment_mode']:"N/A";?></td>
                                                <td><?=$value['application_no']!=""?$value['application_no']:"N/A";?></td>
                                                <td><?=$value['ownerDetails']['owner_name']!=""?$value['ownerDetails']['owner_name']:"N/A";?></td>
                                                <td><?=$value['ownerDetails']['mobile']!=""?$value['ownerDetails']['mobile']:"N/A";?></td>
                                                <td><?php 
                                                 if($value['status']==3){?>
                                                        Not Clear
                                                   <?php  }
                                                     else {?>
                                                        Clear
                                                    <?php } ?>
                                                </td>
                                                <td><?=$value['paid_amount']!=""?$value['paid_amount']:"0";?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    <?php endif;  ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                              <td style="text-align: right;">Total</td>
                                              <td><?=(isset($total))?$total:"0";?></td>
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }]
        });
    });
    $('#btn_search').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var modules = $('#module').val();
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
            if(modules==""){
                $("#module").css({"border-color":"red"});
                $("#module").focus();
                return false;
            }
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").change(function(){$(this).css('border-color','');});
        $("#module").change(function(){$(this).css('border-color','');});
</script>