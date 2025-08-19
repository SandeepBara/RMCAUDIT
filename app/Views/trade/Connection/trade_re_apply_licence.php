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
        <li><a href="#">Trade</a></li>
        <li class="active">Re Apply Trade Licence</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Re Apply Trade Licence</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" action="<?=base_url('');?>/TradeReApplyLicence/detail">
                            <div class="form-group">
                               <div class="col-md-3">
                                    <label class="control-label" for="application_no">Application No<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="text"  id="application_no" name="application_no" maxlength="100" autocomplete="off" class="form-control" placeholder="Enter Application Number" value="<?=(isset($application_no))?$application_no:"";?>"  onkeypress="return isAlphaNum(event);">
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                        <option value="">==SELECT==</option>>
                                        <?php foreach($ward_list as $value):?>
                                        <option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
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
                        <h5 class="panel-title">Application Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ward No.</th>
                                    <th>Application No.</th>
                                    <th>Holding.</th>
                                    <th>Owner Name.</th>
                                    <th>Mobile No.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                    //print_r($owner);
                            if(isset($application_details)):
                                  if(empty($application_details)):
                            ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                    </tr>
                            <?php else:
                                    $i=0;
                                    foreach ($application_details as $value):
                            ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$value['ward_no']!=""?$value['ward_no']:"N/A";?></td>
                                        <td><?=$value['application_no']!=""?$value['application_no']:"N/A";?></td>
                                        <td><?=$value['holding_no']!=""?$value['holding_no']:"N/A";?></td>
                                        <td><?=$value['applicant_name']!=""?$value['applicant_name']:"N/A";?></td>
                                        <td><?=$value['mobile_no']!=""?$value['mobile_no']:"N/A";?></td>
                                        <td>
                                            <a class="btn btn-primary" href="<?php echo base_url('TradeReApplyLicence/view/'.md5($value['id']));?>" role="button">View</a>

                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                 <?php endif;  ?>
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
});
$('#btn_cencel').click(function(){
    var process = true;
    var application_no = $('#application_no').val().trim();
    var ward_mstr_id = $('#ward_mstr_id').val();
    if(application_no=="")
    {
        $("#application_no").css({"border-color":"red"});
        $("#application_no").focus();
        process = false;
    }else{
         var regExp = /^[0-9a-zA-Z]+$/;
         if(!regExp.test(application_no)){
            $("#application_no").css({"border-color":"red"});
            $("#application_no").focus();
            process = false;
        }
    }
    if(ward_mstr_id==""){
        $("#ward_mstr_id").css({"border-color":"red"});
        $("#ward_mstr_id").focus();
        process = false;
    }
    return process;
});
$("#application_no").keyup(function(){$(this).css('border-color','');});
$("#ward_mstr_id").change(function(){$(this).css('border-color','');});
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
    if($holding=flashToast('holding'))
    {
        echo "modelInfo('".$holding."');";
    }
?>
</script>