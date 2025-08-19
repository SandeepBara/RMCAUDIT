<?= $this->include('layout_vertical/header');?>
<style>
    .error {
        color: red;
    }
</style>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">Property</a></li>
        <li class="active">Property Transaction Deactivate</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Property Transaction Deactivate</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" id="tran_form" method="get" action="<?=base_url('');?>/PropertyTransactionDeactivate/detail">
                            <div class="form-group">
                               <div class="col-md-3">
                                    <label class="control-label" for="transaction_no">Transation No<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="text"  id="tran_no" name="tran_no" autocomplete="off" class="form-control" placeholder="Enter Transaction Number" value="<?=(isset($tran_no))?$tran_no:"";?>"  onkeypress="return isAlphaNum(event);">
                                    </div>
                                </div>
                                <!-- <div class="col-md-1">
                                    <label class="control-label text-center text-danger"><strong>OR</strong></label>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="cheque_no">Cheque No<span class="text-danger"></span> </label>
                                    <div class="input-group">
                                        <input type="text"  id="cheque_no" name="cheque_no" autocomplete="off" class="form-control" placeholder="Enter Check Number" value="<?=(isset($cheque_no))?$cheque_no:"";?>"  onkeypress="return isAlphaNum(event);">
                                    </div>
                                </div> -->
                                <div class="col-md-2">
                                    <label class="control-label" for="cancel">&nbsp;</label>
                                    <button class="btn btn-primary btn-block" id="btn_cencel" name="btn_cencel" type="submit">Search</button>
                                </div>
                            </div>
                            <?php if (isset($validation) ) { ?>
                            <div class="row">
                                <div class="col-md-12 text-danger">
                                    <?=$validation;?>
                                </div>
                            </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Transaction Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="table-responsive">
                        <table class="table table-striped table-bordered text-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Property Type</th>
                                    <th>Holding/Saf No</th>
                                    <th>Ward No.</th>
                                    <th>Tran Date</th>
                                    <th>Tran No</th>
                                    <th>Tran Mode</th>
                                    <th>Cheque No</th>
                                    <th>Cheque Date</th>
                                    <th>Bank Name</th>
                                    <th>Branch Name</th>
                                    <th>Deactivate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!isset($propertyTransactionList)):
                                ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center;">Data Not Available!!</td>
                                    </tr>
                                <?php else:
                                        $i=0;
                                        foreach ($propertyTransactionList as $value):
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$value['tran_type']??"";?></td>
                                        <td><?=$value['app_no']??"";?></td>
                                        <td><?=$value['ward_no']??"";?></td>
                                        <td><?=$value['tran_date']??"";?></td>
                                        <td><?=$value['tran_no']??"";?></td>
                                        <td><?=$value['tran_mode']??"";?></td>
                                        <td><?=$value['cheque_no']??"N/A"?></td>
                                        <td><?=$value['cheque_date']??"N/A";?></td>
                                        <td><?=$value['bank_name']??"N/A";?></td>
                                        <td><?=$value['branch_name']??"N/A";?></td>
                                        <?php
                                        $remarkAction = "";
                                        /* if ($value['tran_mode']!=date("Y-m-d")) {
                                            $remarkAction = "Only Update Current Date Trnsaction No.";
                                        } else  */
                                        if ($value['tran_mode']=="ONLINE") {
                                            $remarkAction = "This transaction is online payment";
                                        } else if ($value['id']!=$value['max_dtl_id']) {
                                            $remarkAction = "Update Only Last Transaction No.";
                                        } else if ($value['verify_status']!="") {
                                            $remarkAction = "Transaction Already Verified";
                                        }
                                        if ($remarkAction!=""){
                                        ?>
                                        <td>
                                            <span class="text-danger"><?=$remarkAction;?></span>
                                        </td>
                                        <?php
                                        } else {
                                        ?>
                                        <td>
                                            <button data-target="#deactivate-modal-wo-anim" data-toggle="modal" class="btn btn-primary btn-sm link1"
                                            data-tran_id='<?=$value['id']??"";?>'
                                            data-tran_type='<?=$value['tran_type']??"";?>'
                                            data-app_no='<?=$value['app_no']??"";?>'
                                            data-ward_no='<?=$value['ward_no']??"";?>'
                                            data-tran_date='<?=$value['tran_date']??"";?>'
                                            data-tran_no='<?=$value['tran_no']??"";?>'
                                            data-tran_mode='<?=$value['tran_mode']??"";?>'
                                            data-cheque_no='<?=$value['cheque_no']??"";?>'
                                            data-cheque_date='<?=$value['cheque_date']??"";?>'
                                            data-bank_name='<?=$value['bank_name']??"";?>'
                                            data-branch_name='<?=$value['branch_name']??"";?>'
                                            >Deactivate</button>
                                        </td>
                                        <?php
                                        }
                                        ?>
                                        
                                    </tr>
                                <?php endforeach;  ?>
                                <?php endif;  ?>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!--Bootstrap Modal without Animation-->
    <!--===================================================-->
    <div class="modal" id="deactivate-modal-wo-anim" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title">Transaction No. <span id="show_tran_no"></span></h4>
                </div>
                <!--Modal body-->
                <div class="modal-body">
                    <form method="POST" id="form_deactivate" action="<?=base_url();?>/PropertyTransactionDeactivate/detail" enctype="multipart/form-data">
                        <input type="hidden" id="tran_id" name="tran_id" value="" />
                        <input type="hidden" id="tran_date" name="tran_date" value="" />
                        <input type="hidden" id="tran_type" name="tran_type" value="" />
                        <div class="row">
                            <label class="col-md-3 mrg-top">Property Type</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_tran_type">N/A</label>
                            <label class="col-md-3 mrg-top">Ward No.</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_ward_no">N/A</label>
                        </div>
                        <div class="row">
                            <label class="col-md-3 mrg-top">Holding/Saf No.</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_app_no">N/A</label>
                            <label class="col-md-3 mrg-top">Tran Date</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_tran_date">N/A</label>
                        </div>
                        <div class="row">
                            <label class="col-md-3 mrg-top">Tran Mode</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_tran_mode">N/A</label>
                        </div>
                        <div class="row">
                            <label class="col-md-3 mrg-top">Cheque No.</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_cheque_no">N/A</label>
                            <label class="col-md-3 mrg-top">Cheque Date</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_cheque_date">N/A</label>
                        </div>
                        <div class="row">
                            <label class="col-md-3 mrg-top">Bank name</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_bank_name">N/A</label>
                            <label class="col-md-3 mrg-top">Branch Name</label>
                            <label class="col-md-3 mrg-top text-bold" id="show_branch_name">N/A</label>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-sm-4 mrg-top">
                                <label class="control-label">Upload Required Document <span class="text-danger">* <br />(Only .pdf, .png, .jpg)</span></label>
                            </div>
                            <div class="col-md-5 mrg-top">
                                <input type="file" id="required_doc" name="required_doc" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mrg-top">
                                <label class="control-label">Enter Remarks <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5 mrg-top">
                                <textarea id="remarks" name="remarks" class="form-control"></textarea>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" id="submit" class="btn btn-primary">DEACTIVATE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End Bootstrap Modal without Animation-->
<?= $this->include('layout_vertical/footer');?>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
$(".link1").click(function() {
    var data_tran_id = $(this).data('tran_id');
    var data_tran_type = $(this).data('tran_type');
    var data_app_no = $(this).data('app_no');
    var data_ward_no = $(this).data('ward_no');
    var data_tran_date = $(this).data('tran_date');
    var data_tran_no = $(this).data('tran_no');
    var data_tran_mode = $(this).data('tran_mode');
    var data_cheque_no = $(this).data('cheque_no');
    var data_cheque_date = $(this).data('cheque_date');
    var data_bank_name = $(this).data('bank_name');
    var data_branch_name = $(this).data('branch_name');

    $("#show_tran_id").html(data_tran_id);
    $("#show_tran_type").html(data_tran_type);
    $("#show_tran_date").html(data_tran_date);
    $("#show_tran_no").html(data_tran_no);
    $("#show_tran_mode").html(data_tran_mode);
    $("#show_cheque_no").html(data_cheque_no);
    $("#show_cheque_date").html(data_cheque_date);
    $("#show_bank_name").html(data_bank_name);
    $("#show_branch_name").html(data_branch_name);

    $("#tran_id").val(data_tran_id);
    $("#tran_type").val(data_tran_type);
    $("#tran_date").val(data_tran_date);
});
//$(document).ready(function(){
    $("#tran_form").validate({
        rules: {
            tran_no: {
                required: true
            }
        }
    });
    $("#form_deactivate").validate({
        rules: {
            remarks: {
                required: true
            },
            required_doc: {
                required: true,
                extension: "pdf|png|jpeg|jpg"
            }
        }
    });

    
//});
</script>