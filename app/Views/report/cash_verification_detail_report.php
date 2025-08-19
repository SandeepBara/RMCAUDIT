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
                    <li class="active">Cash Verification Details List</li>
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
                                    <div class="panel-control">
                                    <a href="<?php echo base_url('CashVerificationReport/report');?>" class="btn btn-primary">Back</a>
                                    </div>
                                    <h5 class="panel-title">Cash Verification Details List</h5>
                                </div>
                                <div class="text text-success text-center" style="font-weight: bold;">Total Verified Amount: <?php echo $verified_amt; ?></div>
                                <div class="panel-body">
                                   
                                    <div class="row">
                                    <div class="table-responsive">
                                    
                                     <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            
                                            <tr>
                                                <th>#</th>
                                                <th>Module</th>
                                                <th>Transaction No.</th>
                                                <th>Transaction Date</th>
                                                <th>Payment Mode</th>
                                                <th>Cheque No.</th>
                                                <th>Cheque Date</th>
                                                <th>Bank Name</th>
                                                <th>Branch Name</th>
                                                <th>Payable Amount</th>
                                                <th>Verify Status</th>
                                               
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                   
                                    <?php 
                                            
                                            $i=1;
                                             $total=0;
                                            if($trans_prop):

                                            foreach ($trans_prop as $value):
                                                $total=$total+$value['payable_amt'];
                                    ?>
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td><?php echo $value['tran_type'];?></td>
                                                <td><?=$value['tran_no'];?></td>
                                                <td><?=date('d-m-Y',strtotime($value['tran_date']));?></td>
                                                <td><?=$value['payment_mode'];?></td>
                                                <td><?=$value['cheque_no'];?></td>
                                                <td><?=$value['cheque_date'];?></td>
                                                <td><?=$value['bank_name'];?></td>
                                                <td><?=$value['branch_name'];?></td>
                                                <td><?=$value['payable_amt'];?></td>
                                                
                                                <td><?php if($value['verify_status']==""){ echo "<span style='color:red;'>Not Verified</span>";}else { echo "<span style='color:green;'>Verified</span>"; }?></td>
                                                
                                            </tr>

                                        <?php      
                                                endforeach;?>
                                    <?php endif;  ?>


                                    <?php 
                                           

                                            if($trans_water):

                                            foreach ($trans_water as $value):
                                                 $total=$total+$value['paid_amount'];

                                    ?>
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td>Water</td>
                                                <td><?=$value['transaction_no'];?></td>
                                                <td><?=date('d-m-Y',strtotime($value['transaction_date']));?></td>
                                                <td><?=$value['payment_mode'];?></td>
                                                <td><?=$value['cheque_no'];?></td>
                                                <td><?=$value['cheque_date'];?></td>
                                                <td><?=$value['bank_name'];?></td>
                                                <td><?=$value['branch_name'];?></td>
                                                <td><?=$value['paid_amount'];?></td>
                                                <td><?php if($value['verify_status']==""){ echo "<span style='color:red;'>Not Verified</span>";}else { echo "<span style='color:green;'>Verified</span>"; }?></td>
                                            </tr>

                                        <?php      
                                                endforeach;?>
                                    <?php endif;  ?>


                                    <?php 
                                          
                                            if($trans_trade):

                                            foreach ($trans_trade as $value):
                                                $total=$total+$value['paid_amount'];
                                    ?>
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td>Trade</td>
                                                <td><?=$value['transaction_no'];?></td>
                                                <td><?=date('d-m-Y',strtotime($value['transaction_date']));?></td>
                                                <td><?=$value['payment_mode'];?></td>
                                                <td><?=$value['cheque_no'];?></td>
                                                <td><?=$value['cheque_date'];?></td>
                                                <td><?=$value['bank_name'];?></td>
                                                <td><?=$value['branch_name'];?></td>
                                                <td><?=$value['paid_amount'];?></td>
                                                <td><?php if($value['verify_status']==""){ echo "<span style='color:red;'>Not Verified</span>";}else { echo "<span style='color:green;'>Verified</span>"; }?></td>
                                            </tr>

                                        <?php      
                                                endforeach;?>
                                    <?php endif;  ?>

                                        <tr>
                                            <td colspan="9"></td>
                                            <td style="color: green;">Total: <?php echo $total;?></td>
                                            <td></td>
                                            
                                        </tr>
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
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