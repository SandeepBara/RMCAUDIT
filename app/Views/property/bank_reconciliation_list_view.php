<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
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
								<a class="btn btn-default" href="<?php echo base_url('HoldingDeactivation/detail/'.md5($basic_details['holding_no']));?>" role="button">Back</a>
							</div>
							<h3 class="panel-title">Cheque Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-3">
									<?php echo $basic_details['ward_no']; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Holding No. :</b>
								</div>
								<div class="col-sm-3">
									<?php echo $basic_details['holding_no']; ?>
								</div>
							</div>
						</div>
					</div>
	                <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Owner Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="thead-light" style="background-color: blanchedalmond;">
									<tr>
									  <th scope="col">Owner Name</th>
									  <th scope="col">R/W Guardian</th>
									  <th scope="col">Guardian's Name</th>
									  <th scope="col">Mobile No</th>
									</tr>
								</thead>
								<tbody>
									<?php if($owner_details==""){ ?>
										<tr>
											<td style="text-align:center;"> Data Not Available...</td>
										</tr>
									<?php }else{ ?>
									<?php foreach($owner_details as $owner_details): ?>
										<tr>
										  <td><?php echo $owner_details['owner_name']; ?></td>
										  <td><?php echo $owner_details['relation_type']; ?></td>
										  <td><?php echo $owner_details['guardian_name']; ?></td>
										  <td><?php echo $owner_details['mobile_no']; ?></td>
										</tr>
									<?php endforeach; ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Fill out all the details</h3>
						</div>
	                    <div class="panel-body">
	                        <div class ="row">
	                            <div class="col-md-12">
	                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=base_url('');?>/HoldingDeactivation/create">
	                                    <div class="form-group">
	                                        <div class="col-md-3">
	                                        	<input type="hidden" id="prop_dtl_id" name="prop_dtl_id" value="<?=(isset($basic_details['prop_dtl_id']))?$basic_details['prop_dtl_id']:"";?>">
	                                        	<input type="hidden" id="ward_mstr_id" name="ward_mstr_id" value="<?=(isset($basic_details['ward_mstr_id']))?$basic_details['ward_mstr_id']:"";?>">
	                                            <label class="control-label" for="doc_path"><b>Upload Document <span class="text-danger">*</span></b> </label>
	                                        </div>
	                                        <div class="col-md-3">
	                                            <input type="file" id="doc_path" name="doc_path" class="form-control" value="" >                                                        
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <div class="col-md-3">
	                                            <label class="control-label" for="from_date"><b>Remarks <span class="text-danger">*</span></b> </label>
	                                        </div>
	                                        <div class="col-md-3">
	                                            <textarea type="text" id="remark" minlength="40" maxlength="240" name="remark" class="form-control" placeholder="Remark" onkeypress="return isAlphaNum(event);"></textarea>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <div class="col-md-3">&nbsp;</div>
	                                        <div class="col-md-3">
	                                            <button class="btn btn-primary btn-block" id="btn_save" name="btn_save" type="submit">Save</button>                                                      
	                                        </div>
	                                    </div>
	                                </form>
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
<script type="text/javascript">
$("#doc_path").change(function() {
	var input = this;
	var ext = $(this).val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['pdf']) == -1) {
	    $("#doc_path").val("");
	    alert('invalid Document type');
	}if (input.files[0].size > 1048576) { // 1MD = 1048576
	    $("#doc_path").val("");
	    alert("Try to upload file less than 1MB!"); 
	}
});
$('#btn_save').click(function(){
    var remark = $('#remark').val();
    var doc_path = $('#doc_path').val();
    if(doc_path==""){
        $("#doc_path").css({"border-color":"red"});
        $("#doc_path").focus();
        return false;
    }
    if(remark==""){
        $("#remark").css({"border-color":"red"});
        $("#remark").focus();
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
</script>>