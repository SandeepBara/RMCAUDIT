<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

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
        <li><a href="#">Property</a></li>
        <li class="active">Bank Reconciliation</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <div class="panel-control">
                    <a class="btn btn-default" href="<?php echo base_url('BankReconciliationWaterList/detail');?>" role="button">Back</a>
                </div>
                <h5 class="panel-title">Cheque Detail</h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="<?=base_url('');?>/BankReconciliationWaterList/property">
                    <div class="form-group">
                        <input type="hidden" id="cheque_no" name="cheque_no" value="<?=(isset($chequeDetailsList['cheque_no']))?$chequeDetailsList['cheque_no']:"";?>">
                         <input type="hidden" id="ward_mstr_id" name="ward_mstr_id" value="<?=(isset($chequeDetailsList['ward_mstr_id']))?$chequeDetailsList['ward_mstr_id']:"";?>">
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">
                                    <?=$chequeDetailsList['tran_type']=='Property'?"Holding No":"Saf No";?>
                                </label>
                            </div>
                            <div class="col-md-3">
                               <b><?=$chequeDetailsList['holding'];?></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Owner Name </label>
                            </div>
                            <div class="col-md-3">
                               <b> <?=$chequeDetailsList['owner'];?></b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                       <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Transaction No</label>
                            </div>
                            <div class="col-md-3">
                               <b> <?=$chequeDetailsList['tran_no'];?></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Transaction Date</label>
                            </div>
                            <div class="col-md-3">
                               <b><?=$chequeDetailsList['tran_date'];?></b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                       <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Cheque No</label>
                            </div>
                            <div class="col-md-3">
                               <b> <?=$chequeDetailsList['cheque_no'];?></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Bank Name</label>
                            </div>
                            <div class="col-md-3">
                               <b> <?=$chequeDetailsList['bank_name'];?></b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Branch Name</label>
                            </div>
                            <div class="col-md-3">
                              <b><?=$chequeDetailsList['branch_name'];?></b>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label" for="status">Status<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <select id="status" name="status" class="form-control">
                                   <option value="">--select--</option> 
                                   <option value="1">Clear</option>  
                                   <option value="2">Bounce</option>  
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group hideData" style="display: none;">
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label">Cancelation Charge<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" maxlength="10" id="amount" name="amount" autocomplete="off" class="form-control" placeholder="Enter Cancelation Charge" onkeypress="return isDecNum(this, event);">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label class="control-label" for="status">Reason<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <select id="reason" name="reason" class="form-control">
                                   <option value="">--select--</option> 
                                   <option value="Insufficient funds">Insufficient funds</option>  
                                   <option value="Irregular signature">Irregular signature</option> 
                                   <option value="Stale and post dated cheque">Stale and post dated cheque</option> 
                                   <option value="Alterations">Alterations</option>
                                   <option value="Frozen account">Frozen account</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <!-- <div class="col-md-3">
                                <label class="control-label">&nbsp;&nbsp;&nbsp;</label>
                            </div> -->
                            <div class="col-md-3">
                                <button class="btn btn-primary btn-block" id="btn_save" name="btn_save" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
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
            exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8] }
        }, {
            text: 'pdf',
            extend: "pdf",
            title: "Report",
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8] }
        }]
    });
});
/*$('.hideData').hide();*/
$('#status').change(function(){
    var status = $('#status').val();
    if(status==2){
        $('.hideData').show();
    }
    else{
        $('.hideData').hide();
    }
});
$("#cheque_no").keyup(function(){$(this).css('border-color','');});
$('#btn_save').click(function(){
    var reason = $('#reason').val();
    var amount = $('#amount').val();
    var status = $('#status').val();
    if(status==""){
        $("#status").css({"border-color":"red"});
        $("#status").focus();
        return false;
    }else{
        if(status==2){
           if(amount==""){
                $("#amount").css({"border-color":"red"});
                $("#amount").focus();
                return false;
            }
            if(reason==""){
                $("#reason").css({"border-color":"red"});
                $("#reason").focus();
                return false;
            }
        }
    }
});
$("#reason").keyup(function(){$(this).css('border-color','');});
$("#amount").keyup(function(){$(this).css('border-color','');});
$("#status").change(function(){$(this).css('border-color','');});
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php 
    if($bank_cancel=flashToast('bank_cancel'))
    {
        echo "modelInfo('".$bank_cancel."');";
    }
   
?>
function isDecNum(txt, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}
</script>