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
        <li><a href="#">Account</a></li>
        <li class="active">Unverified Cheque Details</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Payment Details</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" action="<?=base_url('');?>/UnverifiedCheque/detail">
                            <div class="form-group">
                                <div class="col-md-3">
	                                <label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
	                                <div class="input-group">
	                                    <input type="date" id="from_date" name="from_date" class="form-control" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
	                                </div>
                            	</div>
                           		<div class="col-md-3">
                                    <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="date" id="to_date" name="to_date" class="form-control" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
                                    </div>
                                </div>
                           		<div class="col-md-2">
                                    <label class="control-label" for="collection">&nbsp;</label>
                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Unverified Cheque Details</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>SI.No</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Remarks</th>
                                    <th>Not verified Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!isset($notVerrifiedDetails)):
                                ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center;color: red;">Data Not Available!!</td>
                                    </tr>
                                <?php else:
                                        $i=0;
                                        foreach ($notVerrifiedDetails as $value):
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=($value['created_on']!="")?date('d-m-Y',strtotime($value['created_on'])):"N/A";?></td>
                                        <td><?=$value['empDetails']['emp_name']!=""?$value['empDetails']['emp_name']."  ".$value['empDetails']['last_name']:"N/A";?></td>
                                        <td><?=$value['remarks']!=""?$value['remarks']:"N/A";?></td>
                                        <td><?=$value['total']!=""?$value['total']:"0";?></td>
                                        <td>
                                            <a class="btn btn-primary" href="<?php echo base_url('UnverifiedCheque/detail/'.md5($value['verify_status']));?>" role="button">View</a>
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
            exportOptions: { columns: [ 0, 1,2,3] }
        }, {
            text: 'pdf',
            extend: "pdf",
            title: "Report",
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1,2,3] }
        }]
    });
});
$('#btn_search').click(function(){
	var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
	//var employee_id = $('#employee_id').val();
	var process = true;
	if(from_date==""){
		$("#from_date").css({"border-color":"red"});
        $("#from_date").focus();
        process = false;
	}
	if(to_date==""){
        $("#to_date").css({"border-color":"red"});
        $("#to_date").focus();
        process = false;
    }
    if(from_date>to_date){
        alert('To Date Shoulde Be Greater Than Or Equal To From Date');
        $("#to_date").css({"border-color":"red"});
        $("#to_date").focus();
        process = false;
    }
	return process;
});
$("#to_date").change(function(){$(this).css('border-color','');});
$("#from_date").change(function(){$(this).css('border-color','');});
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
        if($cashVerification=flashToast('cashVerification'))
        {
            echo "modelInfo('".$cashVerification."');";
        }
    ?>
</script>