<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<style>
	
.row{line-height:25px;}
</style>
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
					<li><a href="#">Water</a></li>
					<li class="active">Consumer Deactivation</li>
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
								<a class="btn btn-default" href="<?php echo base_url('ConsumerDeactivation/detail/'.md5($basic_details['consumer_no']));?>" role="button">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
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
							<div class="row">
								<div class="col-md-2">
									<b>Consumer No :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['consumer_no']; ?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b>Connection Type :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['connection_type']; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Area :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['area_sqft']; ?> (In sqft)
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b>Pipeline Type :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['pipeline_type']; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b> Connetion Through :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['connection_through']; ?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<b> Category :</b>
								</div>
								<div class="col-md-3">
									<?php echo $basic_details['category']; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Property Type:</b>
								</div>
								<div class="col-md-10">
									<?php echo $basic_details['property_type']; ?>
								</div>
							</div>
						</div>
					</div>
	                <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Consumer Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
									  
									  <th class="bolder">Owner Name</th>
									  <th class="bolder">Guardian Name</th>
									  <th class="bolder">Mobile No.</th>
									  <th class="bolder">Email ID</th>
									  <th class="bolder">State</th>
									  <th class="bolder">District</th>
									  <th class="bolder">City</th>
									</tr>
								</thead>
								<tbody>
										
										<?php
								if($consumer_owner_details)
								{
									foreach($consumer_owner_details as $val)
    								{
        								?>
        								<tr>
        									<td><?php echo $val['applicant_name'];?></td>
        									<td><?php echo $val['father_name'];?></td>
        									<td><?php echo $val['mobile_no'];?></td>
        									<td><?php echo $val['email_id']??'N/A';?></td>
        									<td><?php echo $val['state'];?></td>
        									<td><?php echo $val['district'];?></td>
        									<td><?php echo $val['city'];?></td>
        								</tr>
        								<?php
    								}
								}
								else
								{
									?>
									<tr><td colspan=7 class='text-danger text-center'>! No Data</td></tr>
									<?php
								}
								?>
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
	                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=base_url('');?>/ConsumerDeactivation/create">
	                                	
										<div class="form-group">
											<div class="col-md-3">
												<input type="hidden" id="id" name="id" value="<?=(isset($basic_details['id']))?$basic_details['id']:"";?>">
												<input type="hidden" id="ward_mstr_id" name="ward_mstr_id" value="<?=(isset($basic_details['ward_mstr_id']))?$basic_details['ward_mstr_id']:"";?>">
												<label class="control-label" for="doc_path"><b>Upload Document (Only Pdf,JPG,JPGE File) <span class="text-danger">*</span></b> </label>
											</div>
											<div class="col-md-3">
												<input type="file" id="doc_path" name="doc_path" class="form-control" value="" >                                                        
											</div>

											<div class="col-md-3">
												<label class="control-label" for="reason"><b>Reason <span class="text-danger">*</span></b> </label>                                                  
											</div>
											<div class="col-md-3">
												<select name ="reason" id="reason" class="form-control" <?=$count!=""?"onchange='showhide(this.value)'":'';?>>
													<option value="">--select--</option>
													<option value="Duble Connection">Duble Connection</option>
													<option value="Waiver Committee">Waiver Committee</option>
													<option value="No Connection">No Connection</option>
													<!-- <option value="Other">Other</option> -->
												</select>                                                       
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" for="remark"><b>Remarks <span class="text-danger">*</span></b> </label>
											</div>
											<div class="col-md-3">
												<textarea type="text" id="remark" minlength="20" maxlength="240" name="remark" class="form-control" placeholder="Remark" onkeypress="return isAlphaNum(event);"></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3">&nbsp;</div>
											<div class="col-md-3">
												<button class="btn btn-primary btn-block" <?=$count!=""?"style='display:none'":'';?> id="btn_save" name="btn_save" type="submit">Save</button>                                                      
											</div>
										</div>
	                                	<?php 
										 
										if($count!="")
										{?>
	                                		<p id = "payment"style="text-align: center;color: red;font-size:20px;"><b>Payment Not Cleared</b></p>
	                                	<?php 
										}										
										?>
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
	var input = this;console.log(input.files[0].size);
	var ext = $(this).val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['pdf','jpg','jpge']) == -1) {
	    $("#doc_path").val("");
	    alert('invalid Document type');
	}
	if (input.files[0].size > (1048576*2))
	{ // 1MD = 1048576
	    $("#doc_path").val("");
	    alert("Try to upload file less than 2MB!"); 
	}
	else if(input.files[0].size<1024)
	{	//1kb = 1024 Byte
		$("#doc_path").val("");
	    alert("Upload file is Much smaller !"); 
	}
});
$('#btn_save').click(function(){
    var remark = $('#remark').val();
    var doc_path = $('#doc_path').val();
	var reason = $('#reason').val();
	var returns_bool=true;
	if(reason==""){
        $("#reason").css({"border-color":"red"});
        $("#reason").focus();
        returns_bool=false;
    }
    if(doc_path==""){
        $("#doc_path").css({"border-color":"red"});
        $("#doc_path").focus();
        returns_bool=false;
    }
    if(remark==""){
        $("#remark").css({"border-color":"red"});
        $("#remark").focus();
        returns_bool=false;
    }
	return returns_bool;
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
    if($holding=flashToast('holding')){
        echo "modelInfo('".$holding."');";
    }
?>

function showhide(val)
{ 
	<?php 
		$emp_details = session()->get('emp_details');
		$user_type_mstr_id = $emp_details['user_type_mstr_id']??0;
	?>
	if(<?=($user_type_mstr_id==1 || $user_type_mstr_id==2) ? 1:0;?>)
	{
		$('#btn_save').show();
		$('#payment').hide();
	}
	else if(val=='Duble Connection' )
	{ 
		$('#btn_save').show();
		$('#payment').hide();
	}
	else
	{
		$('#btn_save').hide();
		$('#payment').show();
	}
}
</script>>