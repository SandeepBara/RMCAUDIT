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
        <li class="active">Holding Deactivation</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Holding Deactivation</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" action="<?=base_url('');?>/HoldingDeactivation/detail">
                            <div class="form-group">
                               <div class="col-md-3">
                                    <label class="control-label" for="holding_no">Holding No<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="text"  id="holding_no" name="holding_no" autocomplete="off" class="form-control" placeholder="Enter Holding Number" value="<?=(isset($holding_no))?$holding_no:"";?>"  onkeypress="return isAlphaNum(event);">
                                    </div>
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
                        <h5 class="panel-title">Holding List</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ward No.</th>
                                    <th>Holding No.</th>
                                    <th>Owner Name</th>
                                    <th>Mobile No.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!isset($propertyDetailsList)):
                                ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                    </tr>
                                <?php else:
                                        $i=0;
                                        foreach ($propertyDetailsList as $value):
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=($value['ward_no']!="")?$value['ward_no']:"";?></td>
                                        <td><?=$value['holding_no']!=""?$value['holding_no']:"";?></td>
                                        <td><?=$value['owner_name']!=""?$value['owner_name']:"";?></td>
                                        <td><?=$value['mobile_no']!=""?$value['mobile_no']:"";?> </td>
                                        <td>
                                            <a class="btn btn-primary" href="<?php echo base_url('HoldingDeactivation/view/'.md5($value['id']));?>" role="button">View</a>
                                        </td>
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
$("#doc_path").change(function() {
    var input = this;
    var ext = $(this).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['pdf']) == -1) {
        $("#doc_path").val("");
        alert('invalid Document type');
    }if (input.files[0].size <= 5124) { // 1MD = 1048576
        $("#doc_path").val("");
        alert("Try to upload file less than 1MB!"); 
    }else{
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              $('#photo_path_preview').attr('src', e.target.result);
              $("#is_image").val("is_image");

            }
            reader.readAsDataURL(input.files[0]);
        }
    }
});
$('#btn_cencel').click(function(){
    var holding_no = $('#holding_no').val();
    if(holding_no=="")
    {
        $("#holding_no").css({"border-color":"red"});
        $("#holding_no").focus();
        return false;
    }
});
$("#holding_no").keyup(function(){$(this).css('border-color','');});
$('#btn_save').click(function(){
    var remark = $('#remark').val();
    var doc_path = $('#doc_path').val();
    if(remark==""){
        $("#remark").css({"border-color":"red"});
        $("#remark").focus();
        return false;
    }
    if(doc_path==""){
        $("#doc_path").css({"border-color":"red"});
        $("#doc_path").focus();
        return false;
    }
});
$("#remark").keyup(function(){$(this).css('border-color','');});
$("#doc_path").change(function(){$(this).css('border-color','');});
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