<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="#">Water Connection List</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
		<div class="row" >
			<div class="col-md-12">
				<center><b><h4 style="color:red;">
					<?php
					if(!empty($err_msg)){
						echo $err_msg;
					}
					?>
					</h4>
					</b>
				</center>
			</div>
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title"> Water Connection Details </h3>
			</div>
			<div class="panel-body">  
				<div class="row">
					<label class="col-md-2 bolder">Application No. <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm text-success">
						<?php echo $applicant_details['application_no']; ?>
					</div>
					<label class="col-md-2 bolder">Ward No. <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm text-success">
						<?php echo $applicant_details['ward_no']; ?>
					</div>
				</div>

				<div class="row">
					<label class="col-md-2 bolder">Type of Connection <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $applicant_details['connection_type']; ?>
					</div>
					<label class="col-md-2 bolder">Connection Through <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $applicant_details['connection_through']; ?> 
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 bolder">Property Type <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $applicant_details['property_type']; ?> 
					</div>
						<label class="col-md-2 bolder">Pipeline Type <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $applicant_details['pipeline_type']; ?> 
					</div>
				</div>
			
				<div class="row">
					<label class="col-md-2 bolder">Category <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $applicant_details['category']; ?> 
					</div>
						<label class="col-md-2 bolder">Owner Type <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $applicant_details['owner_type']; ?> 
					</div>
				</div>
			</div>
		</div>


            <!-------Owner Details-------->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
				<div class="table-responsive">
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th>Image</th>
								<th>Document</th>
								<th>Upload Document</th>
								<th>Name</th>
								<th>Mobile No.</th>
								<th>Email ID</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($owner_list)):
								 if(empty($owner_list)):
							?>
								<tr>
									<td colspan="4" style="text-align:center;"> Data Not Available...</td>
								</tr>
								<?php else: ?>
								<?php
								 $i=1;
								foreach($owner_list as $value):
								$j=$i++;

								?>
									<tr>
										<td>
											<?php if(empty($value['owner_image'])){ ?>
												N/A
											<?php } else { ?>
												<a href="<?=base_url();?>/writable/uploads/<?=$value['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php } ?>
										</td>
										<td>
											<?php if(empty($value['owner_doc'])){ ?>
												N/A
											<?php } else { ?>
												<a href="<?=base_url();?>/writable/uploads/<?=$value['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php } ?>
										</td>
										<td>
											<?php
											if(empty($value['owner_image'])):
											?>
											<button type="button" class="btn btn-sm btn-info" id="det_click<?=$j;?>" onclick="owner_details(<?=$j;?>);"  >Upload </button>
											<input type="hidden" id="owner_id<?=$j;?>" name="owner_id<?=$j;?>" value="<?=$value['id'];?>"/>
											<?php else: ?>
											<span class="text-danger"><b>Uploaded Successfully!!</b></span>
											<?php endif;  ?>
										</td>
										<td><?=$value['applicant_name'];?><input type="hidden" id="applicant_name<?=$j;?>" value="<?=$value['applicant_name'];?>"/></td>
										<td><?=$value['mobile_no'];?><input type="hidden" id="mobile_no<?=$j;?>" value="<?=$value['mobile_no'];?>"/></td>
										<td><?=$value['email_id'];?><input type="hidden" id="email_id<?=$j;?>" value="<?=$value['email_id'];?>"/></td>
									</tr>
								<?php endforeach; ?>
								<?php endif; ?>
								<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
           
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Other Documents</h3>
			</div>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead class="bg-trans-dark text-dark">
						<tr>
							<th align="left">Document</th>
							<th>Document Image</th>
							<th>Upload</th>                                    
						</tr>
					</thead>
					<tbody>
						
						<tr>
							<td><strong>Address Proof &nbsp;</strong> (<span style="color: #f00">*</span> Image file only)<br/><br/>Document List (Any One)
								<div style="width: 80%; margin: auto">
									<ol>
										<?php //foreach($address_proof_document_list as $add_proof_details): ?>
										<li>&nbsp;<?php //echo $add_proof_details['document_name']; ?></li>  
										<?php //endforeach; ?>
									</ol>
								</div>
							</td>

							<td>
								<?php if($address_prf_doc["document_path"]==""){ ?>
									N/A
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$address_prf_doc['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
								<?php } ?>
							</td>
							
							<td>
								<?php
									if(isset($address_proof_doc_exists)):
										if(empty($address_proof_doc_exists)):
									?>
										<button class="btn btn-success" onclick="address_proof_doc();">Upload Document</button>
								<?php else: ?>
									<span class="text-danger">  <b>Uploaded Successfully !!</b></span>
										<?php endif;  ?>
								<?php endif;  ?>

							</td>
						</tr>

						<tr>
							<td><strong>Upload Connection Form&nbsp;</strong> (<span style="color: #f00">*</span> Single Image file)</td>

							<td>
								<?php if($connection_form_doc["document_path"]==""){ ?>
									N/A
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$connection_form_doc['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
								<?php } ?>
							</td>
							
							<td>
								<?php
									if(isset($connection_doc_exists)):
										if(empty($connection_doc_exists)):
									?>
										<button class="btn btn-success" onclick="connection_form_doc();">Upload Document</button>
								<?php else: ?>
									<span class="text-danger">  <b>Uploaded Successfully !!</b></span>
										<?php endif;  ?>
								<?php endif;  ?>
							</td>
						</tr>
						<tr>
							<td><strong>Upload electricity bill &nbsp;</strong> (<span style="color: #f00">*</span> Single Image file)</td>

							<td>
								<?php if($electricity_bill_doc["document_path"]==""){ ?>
									N/A
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$electricity_bill_doc['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
								<?php } ?>
							</td>
							
							<td>
								<?php
									if(isset($electricity_doc_exists)):
										if(empty($electricity_doc_exists)):
									?>
										<button class="btn btn-success" onclick="electricity_bill_doc();">Upload Document</button>
								<?php else: ?>
									<span class="text-danger">  <b>Uploaded Successfully !!</b></span>
										<?php endif;  ?>
								<?php endif;  ?>
							</td>
						</tr>

						<?php
						if($water_conn_dtl['connection_through_id']==1)
						{
						?>
						<tr>
							<td><strong>Last Payment Receipt of Holding Tax&nbsp;</strong> (<span style="color: #f00">*</span> Single Image file only)</td>
							
							<td>
								<?php if($payment_receipt_doc["document_path"]==""){ ?>
									N/A
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$payment_receipt_doc['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
								<?php } ?>
							</td>

							<td>
								<?php
									if(isset($payment_receipt_doc_exists)):
										if(empty($payment_receipt_doc_exists)):
									?>
										<button class="btn btn-success" onclick="last_payment_doc();">Upload Document</button>
								<?php else: ?>
									<span class="text-danger">  <b>Uploaded Successfully !!</b></span>
										<?php endif;  ?>
								<?php endif;  ?>

							</td>
						</tr>
						<?php
						}
						?>
						
						<?php
						if($water_conn_dtl['connection_type_id']==2)
						{
						?>
						<tr>
							<td><strong>Upload Meter Bill  &nbsp;</strong> (<span style="color: #f00">*</span> Single Image file)</td>

							<td>
								<?php if($meter_bill_doc["document_path"]==""){ ?>
									N/A
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$meter_bill_doc['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
								<?php } ?>
							</td>
							
							<td>
								<?php
									if(isset($meter_bill_doc_exists)):
										if(empty($meter_bill_doc_exists)):
									?>
									   <button class="btn btn-success" onclick="meter_bill_doc();">Upload Document</button>
								<?php else: ?>
									<span class="text-danger">  <b>Uploaded Successfully !!</b></span>
										<?php endif;  ?>
								<?php endif;  ?>                   
							</td>
						</tr>


						<?php
						}
						if($water_conn_dtl['category']=="BPL")
						{
						?>
						<tr>
							<td><strong>Upload BPL Document  &nbsp;</strong> (<span style="color: #f00">*</span> Single Image file)</td>

							<td>
								<?php if($bpl_doc["document_path"]==""){ ?>
									N/A
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$bpl_doc['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
								<?php } ?>
							</td>
							
							<td>
								<?php
									if(isset($bpl_doc_exists)):
										if(empty($bpl_doc_exists)):
									?>
										<button class="btn btn-success" onclick="bpl_doc();">Upload Document</button>
								<?php else: ?>
									<span class="text-danger">  <b>Uploaded Successfully !!</b></span>
										<?php endif;  ?>
								<?php endif;  ?>                   
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
            </div>
		</div><!--End page content-->
	</div><!--END CONTENT CONTAINER-->
<!-- Last Payment Receipt of Holding Tax Document Modal -->
<div id="last_payment_doc_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Receipt</h4>
			</div>
            <form method="post" enctype="multipart/form-data" action="">
			<div class="modal-body">

				<div class="table-responsive">
					<table class="table table-bordered table-hover" >
                        <tr>
							<td>Last Payment Receipt of Holding Tax  (* Single Image file only)</td>
                            </tr>
                        <tr>
                            <td><input type="file" name="last_payment_doc_path" id="last_payment_doc_path" class="form-control" accept=".pdf"/></td>
						</tr>
					</table>
				</div>

			</div>
            <div class="modal-footer">
                <input name="btn_payment_receipt" id="btn_payment_receipt" class="btn btn-success" value="Upload" type="submit">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
                </form>
		</div>
	</div>
</div>
<!-- Address Proof Document Modal -->
<div id="address_proof_doc_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Address Proof</h4>
			</div>
            <form method="post" enctype="multipart/form-data" action="">
			<div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" >
                            <tr>
                                <td>Address Proof  (* Image file only)</td>
                            </tr>
                            <tr>
                                <td>Choose Document to be upload</td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tbody>
                                            <?php
                                            $cn=0;
                                            //foreach($address_proof_document_list as $add_proof_details): 
                                            $cn++;                                            
                                            ?>                                        
                                            <tr>
                                                <td><input name="address_proof_type" id="address_proof_type" value="<?=$add_proof_details['id']; ?>" type="radio" <?php if($cn=='1'){ ?> checked="" <?php } ?> />
                                                    &nbsp; <?=$add_proof_details['document_name']; ?></td>
                                            </tr>
                                            <?php //endforeach; ?>
                                        </tbody>
                                    </table>
                             </td>
                        </tr>
                            <tr>
                                <td><input type="file" name="address_proof_doc_path" id="address_proof_doc_path" class="form-control" accept=".pdf"/></td>
                            </tr>
                        </table>
                    </div>

			</div>
            <div class="modal-footer">
                <input name="btn_address_proof" id="btn_address_proof" class="btn btn-success" value="Upload" type="submit">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
            </form>
		</div>
	</div>
</div>
<!-- Connection Form Document Modal -->
<div id="connection_form_doc_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Document</h4>
			</div>
            <form method="post" enctype="multipart/form-data" action="">
			<div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" >
                            <tr>
                                <td>Connection Form  (* Single Image file only)</td>
                            </tr>
                            <tr>
                                <td><input type="file" name="connection_form_doc_path" id="connection_form_doc_path" class="form-control" accept=".pdf"/></td>
                            </tr>
                        </table>
                    </div>

			</div>
            <div class="modal-footer">
                <input name="btn_connection_form" id="btn_connection_form" class="btn btn-success" value="Upload" type="submit">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
            </form>
		</div>
	</div>
</div>
<!-- Electricity Document Modal -->
<div id="electricity_bill_doc_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Document</h4>
			</div>
            <form method="post" enctype="multipart/form-data" action="">
			<div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" >
                            <tr>
                                <td>Electricity Bill Form  (* Single Image file only)</td>
                            </tr>
                            <tr>
                                <td><input type="file" name="electricity_bill_doc_path" id="electricity_bill_doc_path" class="form-control" accept=".pdf"/></td>
                            </tr>
                        </table>
                    </div>

			</div>
            <div class="modal-footer">
                <input name="btn_electricity_bill" id="btn_electricity_bill" class="btn btn-success" value="Upload" type="submit">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
                </form>
		</div>
	</div>
</div>

<!-- Meter Bill Document Modal -->
<div id="meter_bill_doc_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Document</h4>
			</div>
            <form method="post" enctype="multipart/form-data" action="">
			<div class="modal-body">

				<div class="table-responsive">
					<table class="table table-bordered table-hover" >
                        <tr>
							<td>Meter Bill Doc (* Single Image file only)</td>
                            </tr>
                        <tr>
                            <td><input type="file" name="meter_bill_doc_path" id="meter_bill_doc_path" class="form-control" accept=".pdf"/></td>
						</tr>
					</table>
				</div>

			</div>
            <div class="modal-footer">
                <input name="btn_meter_bill" id="btn_meter_bill" class="btn btn-success" value="Upload" type="submit">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
                </form>
		</div>
	</div>
</div>
<!-- BPL Document Modal -->
<div id="bpl_doc_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload Document</h4>
			</div>
            <form method="post" enctype="multipart/form-data" action="">
			<div class="modal-body">

				<div class="table-responsive">
					<table class="table table-bordered table-hover" >
                        <tr>
							<td>BPL Doc  (* Single Image file only)</td>
                            </tr>
                        <tr>
                            <td><input type="file" name="bpl_doc_path" id="bpl_doc_path" class="form-control" accept=".pdf"/></td>
						</tr>
					</table>
				</div>

			</div>
            <div class="modal-footer">
                <input name="btn_bpl" id="btn_bpl" class="btn btn-success" value="Upload" type="submit">
                <a href="#" class="btn" data-dismiss="modal">Close</a>
            </div>
            </form>
		</div>
	</div>
</div>
		<!-- Owner Modal -->
<div id="owner_details_Modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Owner Document</h4>
			</div>
			<div class="modal-body">
			<form method="post" enctype="multipart/form-data" action="">
                <input type="hidden" name="owner_dtl_id" id="owner_dtl_id" value="">

				<div class="table-responsive">
					<table class="table table-bordered table-hover" >
						<tr>
							<td><b>Name</b></td>
                            <td>:</td>
							<td id="owner_det_name"></td>
                            <td><b>Mobile</b></td>
                            <td>:</td>
							<td id="mobile_det_no"></td>

						</tr>

                        <tr>
							<td>Applicant Image</td>
                            <td>:</td>
							<td colspan="3"><img/></td>
                            <td colspan="4"><input type="file" name="consumer_photo_doc_path" id="consumer_photo_doc_path" class="form-control" accept=".png,.jpg,.jpeg"/></td>
						</tr>
                        <tr>
							<td>Photo Id Proof</td>
                            <td>:</td>
							<td colspan="3">
                                <select id="owner_doc_mstr_id" name="owner_doc_mstr_id" class="form-control">
                                     <option value="">Select</option>
                                     <?php
                                        if(isset($id_proof_doc)){
                                           foreach($id_proof_doc as $photo_proof_details){
                                     ?>
                                     <option value="<?=$photo_proof_details['id']?>" ><?=$photo_proof_details['document_name']?>
                                     </option>
                                     <?php
                                             }
                                         }
                                     ?>
                                </select>
                            </td>
                            <td colspan="4"><input type="file" name="photo_id_proof_doc_path" id="photo_id_proof_doc_path" class="form-control" accept=".pdf " /></td>
						</tr>
						<tr>
							<td colspan="9"><input type="submit" name="btn_owner_doc" value="Save" id="btn_owner_doc" class="btn btn-success"  /></td>
						</tr>

					</table>
				</div>
				</form>
			</div>
			
		</div>
	</div>
</div>

<?= $this->include('layout_vertical/footer');?>

<script>
function owner_details(il)
{
    var owner_id =$('#owner_id'+il).val();
    var owner_name =$('#applicant_name'+il).val();
    var mobile_no =$('#mobile_no'+il).val();
    $('#owner_dtl_id').val(owner_id);
    $('#owner_det_name').html(owner_name);
    $('#mobile_det_no').html(mobile_no);
    $("#owner_details_Modal").modal('show');
}
function last_payment_doc()
{
    $("#last_payment_doc_Modal").modal('show');
}
function address_proof_doc()
{
    $("#address_proof_doc_Modal").modal('show');
}

function connection_form_doc()
{
    $("#connection_form_doc_Modal").modal('show');
}
function electricity_bill_doc()
{
    $("#electricity_bill_doc_Modal").modal('show');
}
function meter_bill_doc()
{
    $("#meter_bill_doc_Modal").modal('show');
}
function bpl_doc()
{
    $("#bpl_doc_Modal").modal('show');
}

</script>
<script type="text/javascript">
$(document).ready( function () {   
    $("#last_payment_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#last_payment_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#last_payment_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
     $("#address_proof_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#address_proof_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#address_proof_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
    $("#connection_form_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#connection_form_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#connection_form_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
    $("#electricity_bill_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#electricity_bill_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#electricity_bill_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
    $("#meter_bill_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#meter_bill_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#meter_bill_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
    $("#bpl_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#bpl_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#bpl_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
    $("#consumer_photo_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
            $("#consumer_photo_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#consumer_photo_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });
    $("#photo_id_proof_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf']) == -1) {
            $("#photo_id_proof_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 2097152) { 
            $("#photo_id_proof_doc_path").val("");
            alert("Try to upload file less than 2MB"); 
        }
        keyDownNormal(input);
    });

    $("#btn_payment_receipt").click(function() {
        var process = true;

        var last_payment_doc_path = $("#last_payment_doc_path").val();
        if (last_payment_doc_path == '') {
            $("#last_payment_doc_path").css({"border-color":"red"});
            $("#last_payment_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_address_proof").click(function() {
        var process = true;

        var address_proof_doc_path = $("#address_proof_doc_path").val();
        if (address_proof_doc_path == '') {
            $("#address_proof_doc_path").css({"border-color":"red"});
            $("#address_proof_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_owner_doc").click(function() {
        var process = true;

        var consumer_photo_doc_path = $("#consumer_photo_doc_path").val();
        if (consumer_photo_doc_path == '') {
            $("#consumer_photo_doc_path").css({"border-color":"red"});
            $("#consumer_photo_doc_path").focus();
            process = false;
          }
        var owner_doc_mstr_id = $("#owner_doc_mstr_id").val();
        if (owner_doc_mstr_id == '') {
            $("#owner_doc_mstr_id").css({"border-color":"red"});
            $("#owner_doc_mstr_id").focus();
            process = false;
          }

        var photo_id_proof_doc_path = $("#photo_id_proof_doc_path").val();
        if (photo_id_proof_doc_path == '') {
            $("#photo_id_proof_doc_path").css({"border-color":"red"});
            $("#photo_id_proof_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_connection_form").click(function() {
        var process = true;

        var connection_form_doc_path = $("#connection_form_doc_path").val();
        if (connection_form_doc_path == '') {
            $("#connection_form_doc_path").css({"border-color":"red"});
            $("#connection_form_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_electricity_bill").click(function() {
        var process = true;

        var electricity_bill_doc_path = $("#electricity_bill_doc_path").val();
        if (electricity_bill_doc_path == '') {
            $("#electricity_bill_doc_path").css({"border-color":"red"});
            $("#electricity_bill_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_consumer_photo").click(function() {
        var process = true;

        var consumer_photo_doc_path = $("#consumer_photo_doc_path").val();
        if (consumer_photo_doc_path == '') {
            $("#consumer_photo_doc_path").css({"border-color":"red"});
            $("#consumer_photo_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_meter_bill").click(function() {
        var process = true;

        var meter_bill_doc_path = $("#meter_bill_doc_path").val();
        if (meter_bill_doc_path == '') {
            $("#meter_bill_doc_path").css({"border-color":"red"});
            $("#meter_bill_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_bpl").click(function() {
        var process = true;

        var bpl_doc_path = $("#bpl_doc_path").val();
        if (bpl_doc_path == '') {
            $("#bpl_doc_path").css({"border-color":"red"});
            $("#bpl_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#last_payment_doc_path").change(function(){$(this).css('border-color','');});
    $("#connection_form_doc_path").change(function(){$(this).css('border-color','');});
    $("#consumer_photo_doc_path").change(function(){$(this).css('border-color','');});
    $("#electricity_bill_doc_path").change(function(){$(this).css('border-color','');});
    $("#meter_bill_doc_path").change(function(){$(this).css('border-color','');});
    $("#bpl_doc_path").change(function(){$(this).css('border-color','');});

});
</script>