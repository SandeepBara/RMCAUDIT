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
					<li><a href="#">Trade</a></li>
					<li class="active">Trade Licence Deactivation</li>
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
								<a class="btn btn-default" href="<?php echo base_url('TradeLicenceDeactivate/detail/'.md5($basic_details['licence_no']));?>" role="button">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-3">
									<?php echo $ward_no; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Establishment Date. :</b>
								</div>
								<div class="col-sm-3">
									<?php echo date('d-m-Y',strtotime($basic_details['establishment_date'])); ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Application No :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['application_no']; ?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b>Licence No:</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['licence_no']; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Firm Name :</b>
								</div>
								<div class="col-md-3">
									<?=(isset($basic_details['firm_name']))?$basic_details['firm_name']:"";?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b>Holding No:</b>
								</div>
								<div class="col-md-3">
									<?=(isset($holding_no))?$holding_no:"";?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Area:</b>
								</div>
								<div class="col-md-3">
									<?=(isset($basic_details['area_in_sqft']))?$basic_details['area_in_sqft']." "."(sqft)":"";?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b>Address:</b>
								</div>
								<div class="col-md-3">
									<?=(isset($basic_details['firm_address']))?$basic_details['firm_address']:"";?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Pin Code:</b>
								</div>
								<div class="col-md-3">
									<?=(isset($basic_details['pin_code']))?$basic_details['pin_code']:"";?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b>Landmark:</b>
								</div>
								<div class="col-md-3">
									<?=(isset($basic_details['landmark']))?$basic_details['landmark']:"";?>
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
									  <th scope="col">Guardian Name</th>
									  <th scope="col">Mobile No</th>
									  <th scope="col">Address</th>
									  <th scope="col">City</th>
									  <th scope="col">District</th>
									  <th scope="col">State</th>
									</tr>
								</thead>
								<tbody>
									<?php if($ownerDetails==""){ ?>
										<tr>
											<td style="text-align:center;"> Data Not Available...</td>
										</tr>
									<?php }else{ ?>
									<?php foreach($ownerDetails as $owner_details): ?>
										<tr>
										  <td><?php echo $owner_details['owner_name']; ?></td>
										  <td><?php echo $owner_details['guardian_name']; ?></td>
										  <td><?php echo $owner_details['guardian_name']; ?></td>
										  <td><?php echo $owner_details['mobile']; ?></td>
										  <td><?php echo $owner_details['address']; ?></td>
										  <td><?php echo $owner_details['city']; ?></td>
										  <td><?php echo $owner_details['district']; ?></td>
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
	                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=base_url('');?>/TradeLicenceDeactivate/create">
	                                    <div class="form-group">
	                                        <div class="col-md-3">
	                                        	<input type="hidden" id="licence_id" name="licence_id" value="<?=(isset($basic_details['id']))?$basic_details['id']:"";?>">
	                                            <label class="control-label" for="doc_path"><b>Upload Document <span class="text-danger">*</span></b> </label>
	                                        </div>
	                                        <div class="col-md-3">
	                                            <input type="file" id="doc_path" name="doc_path" class="form-control" value="" >                                                        
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <div class="col-md-3">
	                                            <label class="control-label" for="remark"><b>Remarks <span class="text-danger">*</span></b> </label>
	                                        </div>
	                                        <div class="col-md-3">
	                                            <textarea type="text" id="remark" minlength="40" maxlength="240" name="remark" class="form-control" placeholder="Remark" onkeypress="return isAlphaNum(event);"></textarea>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <div class="col-md-3">&nbsp;</div>
	                                        <div class="col-md-3">
	                                            <button class="btn btn-primary btn-block" id="btn_save" name="btn_save" type="submit">Deactivate</button>                                                      
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
	}if (input.files[0].size <= 5124) { // 1MD = 1048576
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