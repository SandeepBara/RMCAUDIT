<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Payment Mode Wise Summery</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form method="post">
                        <div class="row">
                            <label class="col-md-2 text-bold">From Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?=$from_date;?>" />
                            </div>
                            <label class="col-md-2 text-bold">Upto Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=$upto_date;?>" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' name='ward_mstr_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($wardList)) {
                                    foreach ($wardList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>' <?=(isset($ward_mstr_id))?($ward_mstr_id==$list['id'])?"selected":"":"";?>><?=$list['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-4 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center">Collection & Refund Description</th>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <th class="text-center">No.of Transaction</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $success_total_no_of_tran = 0;
                                $success_total_payable_amt = 0;
                                $deactivated_total_no_of_tran = 0;
                                $deactivated_total_payable_amt = 0;
								$total_application = 0;
                                $total_amount = 0;
                                if (isset($success_tran_list)) {
                                    
                                    foreach ($success_tran_list as $list) {
                                        $success_total_no_of_tran += $list['no_of_tran'];
                                        $success_total_payable_amt += $list['payable_amt'];
                                ?>
                                    <tr>
                                        <td><?=ucfirst($list['transaction_mode']);?> (PAYMENT)</td>
                                        <td><?=$list['no_of_tran'];?></td>
                                        <td><?=$list['payable_amt'];?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                                <tr>
                                    <td class="text-bold text-info">Total Collection</td>
                                    <td class="text-bold text-info"><?=$success_total_no_of_tran;?></td>
                                    <td class="text-bold text-info"><?=$success_total_payable_amt;?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <?php
                                if (isset($deactivated_tran_list)) {
                                    foreach ($deactivated_tran_list as $list) {
                                        $deactivated_total_no_of_tran += $list['no_of_tran'];
                                        $deactivated_total_payable_amt += $list['payable_amt'];
                                        if($list['transaction_mode']=="CASH") {
                                            $transaction_mode_ = "(REFUND/CANCELLED)";
                                        } else {
                                            $transaction_mode_ = "(CANCELLED/DISHONORED)";
                                        }
                                ?>
                                    <tr>
                                        <td><?=ucfirst($list['transaction_mode'])." ".$transaction_mode_;?></td>
                                        <td><?=$list['no_of_tran'];?></td>
                                        <td><?=$list['payable_amt'];?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                                <tr>
                                    <td class="text-bold text-info">Total Refund/Cancellation</td>
                                    <td class="text-bold text-info"><?=$deactivated_total_no_of_tran;?></td>
                                    <td class="text-bold text-info"><?=$deactivated_total_payable_amt;?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td class="text-bold text-info">Net Collection</td>
                                    <td class="text-bold text-info"><?=$success_total_no_of_tran-$deactivated_total_no_of_tran;?></td>
                                    <td class="text-bold text-info"><?=$success_total_payable_amt-$deactivated_total_payable_amt;?></td>
                                </tr>
                                <tr>
                                    <td class="text-bold text-info"><u>Door to Door</u></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php
                                if (isset($door_to_door_success_tran_list)) {
                                    foreach ($door_to_door_success_tran_list as $list) {
                                        $success_total_no_of_tran += $list['no_of_tran'];
                                        $success_total_payable_amt += $list['payable_amt'];
                                ?>
                                    <tr>
                                        <td><?=ucfirst($list['transaction_mode']);?> (PAYMENT)</td>
                                        <td><?=$list['no_of_tran'];?></td>
                                        <td><?=$list['payable_amt'];?></td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td>N/A</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php } ?>
                                
                                <tr style="background-color: cornsilk;">
                                    <td class="text-bold text-info">
                                        <!-- Button trigger modal -->
                                        <i style="cursor: pointer;" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Click Me For More Info" onclick="$('#jsk_rmc').modal('show');" >Jan Suvidha Kendra(RMC)</i>

                                        
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php
                                    if(isset($jsk_success_tran_list_rmc) && $jsk_success_tran_list_rmc){
                                        foreach($jsk_success_tran_list_rmc as $list){
                                            ?>
                                            <tr style="background-color: cornsilk;">
                                                <td><?=ucfirst($list['transaction_mode']);?> (PAYMENT)</td>
                                                <td><?=$list['no_of_tran'];?></td>
                                                <td><?=$list['payable_amt'];?></td>
                                            </tr>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <tr style="background-color: cornsilk;">
                                            <td>N/A</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                    }                                
                                ?>
                                <tr style="background-color: cornsilk;">
                                    <td class="text-bold text-info">
                                        <i style="cursor: pointer;" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Click Me For More Info" onclick="$('#jsk_out').modal('show');" >Out Side</i>
                                        
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php
                                    if(isset($jsk_success_tran_list_out) && $jsk_success_tran_list_out){
                                        foreach($jsk_success_tran_list_out as $list){
                                            ?>
                                            <tr style="background-color: cornsilk;">
                                                <td><?=ucfirst($list['transaction_mode']);?> (PAYMENT)</td>
                                                <td><?=$list['no_of_tran'];?></td>
                                                <td><?=$list['payable_amt'];?></td>
                                            </tr>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <tr style="background-color: cornsilk;">
                                            <td>N/A</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
								<tr>
                                        <td class="text-bold text-info"><u>Assessment Details</u></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    if (isset($assessment_list)) {
                                        
                                        foreach ($assessment_list as $list) {
                                            $total_application += $list['total_application'];
                                            $total_amount += $list['amount'];
                                    ?>
                                            <tr>
                                                <td><?= ucfirst($list['assessment_type']); ?></td>
                                                <td><?= $list['total_application']; ?></td>
                                                <td><?= $list['amount']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td>N/A</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<!-- Modal -->
<div id="jsk_rmc" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">RMC JSK</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered text-sm">
                        <tbody>
                            <?php
                                if(isset($jsk_rmc_dtl) && $jsk_rmc_dtl){
                                    foreach($jsk_rmc_dtl as $list){
                                        foreach($list as $key=>$item){
                                            if($key==0){
                                                ?>
                                                <tr>
                                                    <td colspan="3" style="text-align: center;color:black;" class="text-secondary"><strong><?=$item["emp_name"];?></strong></td>                                                                                            
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                <td><?=ucfirst($item['transaction_mode']);?></td>
                                                <td><?=$item['no_of_tran'];?></td>
                                                <td><?=$item['payable_amt'];?></td>
                                            </tr>
                                            <?php
                                        }                                                                                
                                        
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="jsk_out" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Out Side JSK</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered text-sm">
                        <tbody>
                            <?php
                                if(isset($jsk_out_dtl) && $jsk_out_dtl){
                                    foreach($jsk_out_dtl as $list){
                                        foreach($list as $key=>$item){
                                            if($key==0){
                                                ?>
                                                <tr>
                                                    <td colspan="3"  style="text-align: center; color:black;"><strong><?=$item["emp_name"];?></strong></td>                                                                                            
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                <td><?=ucfirst($item['transaction_mode']);?></td>
                                                <td><?=$item['no_of_tran'];?></td>
                                                <td><?=$item['payable_amt'];?></td>
                                            </tr>
                                            <?php
                                        }                                                                                
                                        
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var dataTable = $('#dataTableID').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        "paging": false,
        "info": false,
        "searching":false,
        "aaSorting": [],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0, 1, 2 ] }, 
            { "bSearchable": false, "aTargets": [ 0, 1, 2 ] }
        ],
        buttons: [
            {
            text: 'Excel',
            extend: "excel",
            title: "Payment Mode Wise Collection (<?=date ("Y-m-d H:i:s A")?>)",
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2] }
        }, {
            text: 'Print',
            extend: 'print',
            title: "Payment Mode Wise Collection (<?=date ("Y-m-d H:i:s A")?>)",
            exportOptions: { columns: [ 0, 1,2] }
        }]
    });
});
</script>
