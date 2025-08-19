<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
					<li><a href="#">Document Upload</a></li>
					<li class="active">Document Upload</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
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
                                </b></center>
                        </div>
                    </div>

					<!-------Owner Details-------->
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Owner Details</h3>
						</div>
						<div class="table-responsive">
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Name</th>
										<th>Mobile No.</th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(isset($owner_list)):
										if(empty($owner_list)):
									?>
									<tr>
										<td style="text-align:center;"> Data Not Available...</td>
									</tr>
									<?php else: ?>
									<?php
									$i=1;
									foreach($owner_list as $value):
									$j=$i++;
									?>
									<tr>
										<td><?=$value['applicant_name'];?><input type="hidden" id="owner_name<?=$j;?>" value="<?=$value['applicant_name'];?>"/></td>
										<td><?=$value['mobile_no'];?><input type="hidden" id="mobile_no<?=$j;?>" value="<?=$value['mobile_no'];?>"/></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr class="success">
										<th>Document Name</th>
										<th>Applicant Image</th>
										<th>Verify/Reject</th>
										<th>Reject Remarks</th>
										<th></th>
									</tr>
									<?php
									$img_val=0;
									foreach($value['saf_owner_img_list'] as $imgval):
									$img_val++;
									?>
									<tr>
										<td>Consumer Photo</td>
										<td><a href="<?=base_url();?>/writable/uploads/<?=$imgval["document_path"];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$imgval["document_path"];?>" style="width: 40px; height: 40px;"></a></td>
										<td>
											<?php
											if($imgval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($imgval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											?>
										</td>
										<td><?=$imgval['remarks'];?></td>
										<td>
											<?php
											if($imgval['verify_status']=="2")
											{
												if($img_val=='1')
												{
													if($value['img_stts']=='1')
													{
													?>
														<span class="text-success">Uploaded Successfully!!</span>
													<?php
													}
													else
													{
													?>
													<form method="post" enctype="multipart/form-data" action="">
														<input type="hidden" name="saf_own_dtl_id" value="<?=$value['id'];?>"/>
														<input type="hidden" name="owner_img_mstr_id" value="<?=$imgval['doc_mstr_id'];?>"/>
														<div class="row">
															<div class="col-md-8">
																<input type="file" name="applicant_image_path" id="applicant_image_path" class="form-control" accept=".png,.jpg,.jpeg" required />
															</div>
															<div class="col-md-4">
																<input type="submit" name="btn_owner_img" value="Upload" id="btn_owner_img" class="btn btn-info"  />
															</div>
														</div>
													</form>
													<?php
													}
												}
											}
											?>
										</td>
									</tr>
									<?php endforeach; ?>
									<tr class="success">
										<th>Document Name</th>
										<th>Applicant Document</th>
										<th>Verify/Reject</th>
										<th>Reject Remarks</th>
										<th></th>
									</tr>
									<?php
									$doc_val=0;
									foreach($value['saf_owner_doc_list'] as $docval):
									$doc_val++;
									?>
									<tr>
										<td>Photo Id Proof (<span class="text-danger"><?=$docval['document_name']?></span>)</td>
										
										<td>
										
										<a href="<?=base_url();?>/writable/uploads/<?=$docval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
										
										<td>
											<?php
											if($docval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($docval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											?>
										</td>
										<td><?=$docval['remarks'];?></td>
										<td>
											<?php
											if($docval['verify_status']=="2")
											{
												if($doc_val=='1')
												{
													if($value['doc_stts']=='1')
													 {
														?>
														<span class="text-success">Uploaded Successfully!!</span>
														<?php
													 }
													 else
													 {
												  ?>
												<form method="post" enctype="multipart/form-data" action="">
													<input type="hidden" name="saf_owner_dtl_id" value="<?=$value['id'];?>"/>
													<div class="row">
														<div class="col-md-4">
															<select id="owner_doc_mstr_id" name="owner_doc_mstr_id" class="form-control" required>
																<option value="">Select</option>
																<?php
														if(isset($photo_id_proof_document_list)){
															foreach ($photo_id_proof_document_list as $values){
																?>
																<option value="<?=$values['id']?>" ><?=$values['document_name']?>
																</option>
																<?php
															}
														}
														?>
															</select>
														</div>
														<div class="col-md-4">
															<input type="file" name="owner_doc_path" id="owner_doc_path" class="form-control" accept=".pdf" required />
														</div>
														<div class="col-md-4">
															<input type="submit" name="btn_owner_doc" value="Upload" id="btn_owner_doc" class="btn btn-info"  />
														</div>
													</div>
												</form>
												<?php
												}
											}
											}
											?>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php endforeach; ?>
									<?php endif; ?>
									<?php endif; ?>
								</tbody>
							</table>
                        </div>
					</div>
            

					<!-------Propery Type-------->
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Document Details</h3>

						</div>
						<div class="table-responsive">
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Document Name</th>
										<th>Document</th>
										<th>Verify/Reject</th>
										<th>Reject Remarks</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$pr_doc_val=0;
									foreach($payment_receipt_doc as $prdocval):
									$pr_doc_val++;
									?>
									<tr>
										<td>Payment Receipt</td>
										
										<td>
										
										<a href="<?=base_url();?>/writable/uploads/<?=$prdocval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
										
										<td>
											<?php
											if($prdocval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($prdocval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											?>
										</td>
										<td><?=$prdocval['remarks'];?></td>
										<td>
										<?php
										if($prdocval['verify_status']=="2")
										{
											if($pr_doc_val=='1')
											{
												if($pr_stts=='1')
												{
													?>
													<span class="text-success">Uploaded Successfully!!</span>
													<?php
												}
												else
												{
												?>
											<form method="post" enctype="multipart/form-data" action="">
												<div class="row">

													<div class="col-md-8">                                                                        
														<input type="file" name="pr_doc_path" id="pr_doc_path" class="form-control" accept=".pdf" required />
													</div>
													<div class="col-md-4">
														<input type="submit" name="btn_pr_doc" value="Upload" id="btn_pr_doc" class="btn btn-info"  />
													</div>
												</div>
											</form>
											<?php
											}
											}
											}
											?>
										</td>
									</tr>
									<?php endforeach;  ?>
									<?php
									$ap_doc_val=0;
									foreach($address_proof_doc as $apdocval):
									$ap_doc_val++;
									?>
									<tr>
										<td>Address Proof <span class="text-danger">(<?=$apdocval['document_name'];?>)</span></td>
										
										<td>
										
										<a href="<?=base_url();?>/writable/uploads/<?=$apdocval['document_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
										
										<td>
											<?php
											if($apdocval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($apdocval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											?>
										</td>
										<td><?=$apdocval['remarks'];?></td>
										<td>
											<?php
											if($apdocval['verify_status']=="2")
											{
												if($ap_doc_val=='1')
												{
													if($ap_stts=='1')
													{
														?>
														<span class="text-success">Uploaded Successfully!!</span>
														<?php
													}
													else
													{
												?>
												<form method="post" enctype="multipart/form-data" action="">
													<div class="row">
														<div class="col-md-4">
															<select id="ap_doc_mstr_id" name="ap_doc_mstr_id" class="form-control" required>
																<option value="">Select</option>
																<?php
														if(isset($address_proof_document_list)){
															foreach ($address_proof_document_list as $values){
																?>
																<option value="<?=$values['id']?>" ><?=$values['document_name']?>
																</option>
																<?php
															}
														}
																?>
															</select>
														</div>
														<div class="col-md-4">
															<input type="file" name="ap_doc_path" id="ap_doc_path" class="form-control" accept=".pdf" required />
														</div>
														<div class="col-md-4">
															<input type="submit" name="btn_ap_doc" value="Upload" id="btn_ap_doc" class="btn btn-info"  />
														</div>
													</div>
												</form>
												<?php
												}
												}
												}
												?>
										</td>
									</tr>
                                    <?php endforeach;  ?>
									<?php
									$cf_doc_val=0;
									foreach($connection_doc as $cfdocval):
									$cf_doc_val++;
									?>
									<tr>
										<td>Connection Form</td>
										
										<td>
										
										<a href="<?=base_url();?>/writable/uploads/<?=$cfdocval['document_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
										
										<td>
											<?php
											if($cfdocval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($cfdocval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											?>
										</td>
										<td><?=$cfdocval['remarks'];?></td>
										<td>
											<?php
											if($cfdocval['verify_status']=="2")
											{
												if($cf_doc_val=='1')
												{
													if($cf_stts=='1')
													{
														?>
														<span class="text-success">Uploaded Successfully!!</span>
														<?php
													}
													else
													{
													?>
													<form method="post" enctype="multipart/form-data" action="">
														<div class="row">

															<div class="col-md-8">
																<input type="file" name="cf_doc_path" id="cf_doc_path" class="form-control" accept=".pdf" required />
															</div>
															<div class="col-md-4">
																<input type="submit" name="btn_cf_doc" value="Upload" id="btn_cf_doc" class="btn btn-info"  />
															</div>
														</div>
													</form>
													<?php
													}
													}
													}
													?>
										</td>
									</tr>
										<?php endforeach;  ?>
										<?php
										$ed_doc_val=0;
										foreach($electricity_doc as $eddocval):
										$ed_doc_val++;
										?>
										<tr>
										<td>Electricity Bill</td>
										
										<td>
										
										<a href="<?=base_url();?>/writable/uploads/<?=$eddocval['document_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
										
										<td>
											<?php
											if($eddocval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($eddocval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											?>
										</td>
										<td><?=$eddocval['remarks'];?></td>
										<td>
										<?php
										if($eddocval['verify_status']=="2")
										{
											if($ed_doc_val=='1')
											{
												if($ed_stts=='1')
												{
													?>
													<span class="text-success">Uploaded Successfully!!</span>
													<?php
												}
												else
												{
												?>
										<form method="post" enctype="multipart/form-data" action="">
											<div class="row">

												<div class="col-md-8">
													<input type="file" name="ed_doc_path" id="ed_doc_path" class="form-control" accept=".pdf" required />
												</div>
												<div class="col-md-4">
													<input type="submit" name="btn_ed_doc" value="Upload" id="btn_ed_doc" class="btn btn-info"  />
												</div>
											</div>
										</form>
										<?php
										}
										}
										}
										?>
									</td>
								</tr>
								<?php endforeach;  ?>
								<?php
								$mb_doc_val=0;
								foreach($meter_bill_doc as $mbdocval):
								$mb_doc_val++;
								?>
								<tr>
								<td>Meter Bill</td>
								
								<td>
								
								<a href="<?=base_url();?>/writable/uploads/<?=$mbdocval['document_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
								
								<td>
									<?php
									if($mbdocval['verify_status']=="1")
									{
										echo "<span class='text-danger'>Verified</span>";
									}
									else if($mbdocval['verify_status']=="2")
									{
										echo "<span class='text-danger'>Rejected</span>";
									}
									?>
								</td>
								<td><?=$mbdocval['remarks'];?></td>
								<td>
								<?php
								if($mbdocval['verify_status']=="2")
								{
									if($mb_doc_val=='1')
									{
										if($mb_stts=='1')
										{
											?>
											<span class="text-success">Uploaded Successfully!!</span>
											<?php
										}
										else
										{
										?>
													<form method="post" enctype="multipart/form-data" action="">
														<div class="row">

															<div class="col-md-8">
																<input type="file" name="mb_doc_path" id="mb_doc_path" class="form-control" accept=".pdf" required />
															</div>
															<div class="col-md-4">
																<input type="submit" name="btn_mb_doc" value="Upload" id="btn_mb_doc" class="btn btn-info"  />
															</div>
														</div>
													</form>
													<?php
													}
													}
													}
													?>
												</td>
											</tr>
											<?php endforeach;  ?>
											<?php
											// echo ($water_con_dtl['category']);
											if($water_con_dtl['category']=='BPL')
											{
											$bpl_doc_val=0;
											foreach($bpl_doc as $bpldocval):
											$bpl_doc_val++;
											?>
											<tr>
											<td>BPL</td>
											
											<td>
											
											<a href="<?=base_url();?>/writable/uploads/<?=$bpldocval['document_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
											
											<td>
												<?php
												if($bpldocval['verify_status']=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($bpldocval['verify_status']=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><?=$bpldocval['remarks'];?></td>
											<td>
											<?php
											if($bpldocval['verify_status']=="2")
											{
												if($bpl_doc_val=='1')
												{
													if($bpl_stts=='1')
													{
														?>
														<span class="text-success">Uploaded Successfully!!</span>
														<?php
													}
													else
													{

													?>
										<form method="post" enctype="multipart/form-data" action="">
											<div class="row">

												<div class="col-md-8">
													<input type="file" name="bpl_doc_path" id="bpl_doc_path" class="form-control" accept=".pdf" required />
												</div>
												<div class="col-md-4">
													<input type="submit" name="btn_bpl_doc" value="Upload" id="btn_bpl_doc" class="btn btn-info"  />
												</div>
											</div>
										</form>
										<?php
										}
										}
										}
										?>
									</td>
								</tr>
								<?php endforeach;
								}
								?>
							</tbody>
						</table>
					</div>
                </div>
            </div>
		
			<!--===================================================-->
			<!--End page content-->
	</div>
	<!--===================================================-->
	<!--END CONTENT CONTAINER-->
	<!----->
///////modal start
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Image preview</h4>
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
//////modal end
<?= $this->include('layout_vertical/footer');?>

<script>
$(function() {
		$('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
			$('#imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		

});
</script>
<script type="text/javascript">
    $(document).ready( function () {
        $("#applicant_image_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#applicant_image_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#applicant_image_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
        $("#owner_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#owner_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#owner_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
        $("#pr_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#pr_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#pr_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
        $("#ap_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#ap_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#ap_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
        $("#cf_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#cf_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#cf_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
        $("#ed_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#ed_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#ed_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
        $("#mb_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#mb_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#mb_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });

        $("#bpl_doc_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                $("#bpl_doc_path").val("");
                alert('invalid document type');
            }
            if (input.files[0].size > 2097152) { 
                $("#bpl_doc_path").val("");
                alert("Try to upload file less than 2MB"); 
            }
            keyDownNormal(input);
        });
    });
</script>