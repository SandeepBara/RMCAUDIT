<?=$this->include('layout_home/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->
        
        <div id="page-content">
            <div class="panel panel-bordered ">
                    <div class="panel-body">
                        
                        <div class="col-sm-10">
                            <!------- Panel Owner Details-------->
							<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Upload Owner Document</h3>
									</div>
									<div class="panel-body" style="padding-bottom: 0px;">
										<div class="row">
											<div class="col-md-12">
												<div class="table-responsive">
													<table class="table table-bordered text-sm">
														<thead class="bg-trans-dark text-dark">
															<tr>
																<th>Applicant Image</th>
																<th>Applicant Document</th>
																<?php if(isset($is_specially_abled)) {
                                                if($is_specially_abled=='true'){ ?>
                                                 <th>Specially Abled Certificate</th>
                                                <?php  } } ?>
                                                <?php if(isset($is_armed_force)) {
                                                if($is_armed_force=='true'){ ?>
                                                  <th>Armed Force Certificate</th>
                                                <?php  } } ?>
																<th>Upload</th>
																<th>Owner Name</th>
																<th>Guardian Name</th>
																<th>Relation</th>
																<th>Mobile No</th>
																<th>Aadhar No.</th>
																<th>PAN No.</th>
																<th>Email ID</th>
															</tr>
														</thead>
														<tbody id="owner_dtl_append">
													<?php
													$everyDocUploaded=true;
													if (isset($saf_owner_detail))
													{
														foreach ($saf_owner_detail as $owner_detail)
														{
															//print_var($owner_detail);
															?>
															<tr>
																<td>
																<?php
																if ($owner_detail['applicant_img_dtl'])
																{
																	echo "<span class='text-success text-bold'>Uploaded</span>";
																}
																else
																{
																	echo "<span class='text-danger text-bold'>Not Uploaded</span>";
																}
																?>
																</td>
																<td>
																<?php
																if ($owner_detail['applicant_doc_dtl'])
																{
																	echo "<span class='text-success text-bold'>Uploaded</span>";
																		
																}
																else
																{
																	$everyDocUploaded=false;
																	echo "<span class='text-danger text-bold'>Not Uploaded</span>";
																}
																?>
																</td> 
																 <?php if(isset($owner_detail['is_specially_abled'])){
                                                if($owner_detail['is_specially_abled']!='f'){ ?> 
													<td> 
												 <?php
												  
													
														   
															if ($owner_detail['Handicaped_doc_dtl'])
													{
														echo "<span class='text-success text-bold'>Uploaded</span>";
														
															
													}
													else
													{
														$everyDocUploaded=false;
														echo "<span class='text-danger text-bold'>Not Uploaded</span>";
													}
													
													?>
													</td>
													<?php } }  ?> 

													  <?php   if(isset($is_armed_force)){
                                                if($is_armed_force!='false'){ ?>
													 <td> 
													 <?php
												   
													if ($owner_detail['Armed_doc_dtl'])
													{
														
														echo "<span class='text-success text-bold'>Uploaded</span>";
															
													}
													else
													{
														$everyDocUploaded=false;
														echo "<span class='text-danger text-bold'>Not Uploaded</span>";
													}
												
													?>
													</td>
		
													
													<?php } }  /* ?> 

													<!-- previous code  -->
															<!-- <td>
																<?php
																if ($owner_detail['applicant_img_dtl'])
																{
																	$path = $owner_detail['applicant_img_dtl']['doc_path'];
																	?>
																		<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
																			<img src="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" class="img-lg" />
																			
																		</a>
																	<?php
																}
																else
																{
																	echo "<span class='text-danger text-bold'>Not Uploaded</span>";
																}
																?>
																</td>
																<td>
																<?php
																if ($owner_detail['applicant_doc_dtl'])
																{
																	$path = $owner_detail['applicant_doc_dtl']['doc_path'];
																	$extention = strtolower(explode('.', $path)[1]);
																	if ($extention=="pdf")
																	{
																		?>
																			<a href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" target="_blank" > 
																				<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
																				<br><span class="text text-primary"><?=$owner_detail['applicant_doc_dtl']["doc_name"];?><span>
																			</a>
																		<?php
																	}
																	else
																	{
																		?>
																			<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
																				<img src='<?=base_url();?>/getImageLink.php?path=<?=$path;?>' class='img-lg' />
																				<br><span class="text text-primary"><?=$owner_detail['applicant_doc_dtl']["doc_name"];?><span>
																			</a>
																		<?php
																	}
																		
																}
																else
																{
																	$everyDocUploaded=false;
																	echo "<span class='text-danger text-bold'>Not Uploaded</span>";
																}
																?>
																</td> 

																<!-- sepcial case -->
																<!-- <?php  if(isset($is_specially_abled)){
                                                if($is_specially_abled!='false'){ ?> -->
                                            <!-- <td> -->
                                            <!-- <?php
                                          
                                            
                                                   
                                                    if ($owner_detail['Handicaped_doc_dtl'])
                                            {
                                                $path = $owner_detail['Handicaped_doc_dtl']['doc_path'];
                                                // print_r($path);
                                                $extention = strtolower(explode('.', $path)[1]);
                                                if ($extention=="pdf")
                                                {
                                                    ?>
                                                        <a href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" target="_blank" > 
                                                            <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                            <br><span class="text text-primary"><?=$owner_detail['Handicaped_doc_dtl']["doc_name"];?><span>
                                                        </a>
                                                    <?php
                                                }
                                                
                                                    
                                            }
                                            else
                                            {
                                                $everyDocUploaded=false;
                                                echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                            }
                                            
                                            ?>
                                            </td>
                                            <?php } } ?> -->
                                            <!-- <?php   if(isset($is_armed_force)){
                                                if($is_armed_force!='false'){ ?> -->
                                            <!-- <td> -->
                                            <!-- <?php
                                           
                                            if ($owner_detail['Armed_doc_dtl'])
                                            {
                                                $path = $owner_detail['Armed_doc_dtl']['doc_path'];
                                                $extention = strtolower(explode('.', $path)[1]);
                                                if ($extention=="pdf")
                                                {
                                                    ?>
                                                        <a href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" target="_blank" > 
                                                            <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                            <br><span class="text text-primary"><?=$owner_detail['Armed_doc_dtl']["doc_name"];?><span>
                                                        </a>
                                                    <?php
                                                }
                                               
                                                    
                                            }
                                            else
                                            {
                                                $everyDocUploaded=false;
                                                echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                            }
                                        
                                            ?>
                                            </td>

											
                                            <?php } } */ ?> 
																<td>
																<?php if($emp_details_id==0){ ?>
																		<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#owner_details_modal<?=$owner_detail["id"];?>">Click here to upload</button>
																		<!-- Owner Doc Upload Modal -->
																		<div class="modal fade" id="owner_details_modal<?=$owner_detail["id"];?>" role="dialog"">
																			<div class="modal-dialog modal-lg">
																				<!-- Modal content-->
																				<div class="modal-content">
																					<div class="modal-header">
																						<button type="button" class="close" data-dismiss="modal">&times;</button>
																						<h4 class="modal-title">Owner Document</h4>
																					</div>
																					<div class="modal-body">
																						<form method="post" enctype="multipart/form-data">
																							<input type="hidden" name="saf_owner_dtl_id" id="saf_owner_dtl_id" value="<?=$owner_detail["id"];?>" />
																							<div class="table-responsive">
																								<table class="table table-bordered text-sm" >
																									<tr>
																										<td><b>Name</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['owner_name'];?></td>
																										<td><b>Relation</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['relation_type'];?></td>
																										<td><b>Guardian Name</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['guardian_name'];?></td>
																									</tr>
																									<tr>
																										<td><b>Mobile</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['mobile_no'];?></td>
																										<td></td>
																										<td></td>
																										<td></td>
																										<td><b>Aadhar No.</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['aadhar_no'];?></td>
																									</tr>
																									<tr>
																										<td><b>Pan No.</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['pan_no'];?></td>
																										<td></td>
																										<td></td>
																										<td></td>
																										<td><b>Email Id</b></td>
																										<td>:</td>
																										<td><?=$owner_detail['email'];?></td>
																									</tr>
																									<tr>
																										<td>Applicant Image</td>
																										<td>:</td>
																										<td colspan="3">
																											<!-- <img/> -->
																										</td>
																										<td colspan="4">
																											<span class="text text-danger">Only .png and .jpeg allowed</span>
																											<input type="file" name="applicant_image_file" class="form-control" accept=".png, .jpg, .jpeg" required/>
																										</td>
																									</tr>
																									<tr>
																										<td>Document Type</td>
																										<td>:</td>
																										<td colspan="3">
																										
																											<select id="owner_doc_mstr_id" name="owner_doc_mstr_id" class="form-control" required>
																												<option value="">Select</option>
																												<?php
																													if(isset($owner_doc_list))
																													{
																														foreach ($owner_doc_list as $values)
																														{
																															?>
																															<option value="<?=$values['id']?>" ><?=$values['doc_name']?>
																															</option>
																															<?php
																														}
																													}
																												?>
																											</select>
																										</td>
																										<td colspan="4">
																											<span class="text text-danger">Only .pdf allowed</span>
																											<input type="file" name="applicant_doc_file" id="applicant_doc_file" class="form-control" accept=".pdf" required/>
																										</td>
																									</tr>
																									<?php if(isset($owner_detail['is_specially_abled'])){ 
                                                                                    if($owner_detail['is_specially_abled'] !='f') { ?>
                                                                                <tr>
                                                                                    <td>Handicaped Document</td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3">
																						<!-- <img/> -->
																					</td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .pdf allowed</span>
                                                                                        <input hidden type="text" name="is_specially_data"  value="<?php 
                                                                                        if(isset($owner_detail['is_specially_abled'])){
                                                                                            if($owner_detail['is_specially_abled']!='f'){
                                                                                                echo "1";
                                                                                            }else{
                                                                                                echo "2";
                                                                                            }
                                                                                        }
                                                                                        ?>" />
                                                                                        <input type="file" name="handicaped_document" class="form-control" accept=".pdf"required/>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php }  } ?>
                                                                                <?php if(isset($is_armed_force)){ 
                                                                                    if($is_armed_force =='true') { ?>
                                                                                <tr>
                                                                                    <td>Armed Force Document</td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3">
																						<!-- <img/> -->
																				</td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .pdf allowed</span>
                                                                                        <input hidden type="text" name="is_armed_data" value="<?php 
                                                                                        if(isset($is_armed_force)){
                                                                                            if($is_armed_force=='true'){
                                                                                                echo "1";
                                                                                            }else{
                                                                                                echo "2";
                                                                                            }
                                                                                        }
                                                                                        ?>" />
                                                                                        <input type="file" name="armed_force_document" class="form-control" accept=".pdf" required/>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php }  } ?>
																									<tr>
																										<td colspan="9" class="text-right">
																											<input type="submit" name="btn_owner_doc_upload" id="btn_owner_doc_upload" class="btn btn-success" value="UPLOAD" />
																										</td>
																									</tr>

																								</table>
																							</div>
																						</form>
																					</div>
																				</div>
																			</div>
																		</div>
																		<?php } ?>
																</td>
																<td>
																	<?=$owner_detail['owner_name'];?>
																</td>
																<td>
																	<?=$owner_detail['guardian_name'];?>
																</td>
																<td>
																	<?=$owner_detail['relation_type'];?>
																</td>
																<td>
																	<?=$owner_detail['mobile_no'];?>
																</td>
																<td>
																	<?=$owner_detail['aadhar_no'];?>
																</td>
																<td>
																	<?=$owner_detail['pan_no'];?>
																</td>
																<td>
																	<?=$owner_detail['email'];?>
																</td>
																
															</tr>
															<?php
														}
													}
													
													?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							<!------- End Panel Owner Details-------->
							
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">Upload Owner Document</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
									<table class="table table-bordered">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>#</th>
												<!-- <th>Document</th> -->
												<th>Upload Status</th>
												<th>Upload</th>
												<th style="width: 25%;">Document(s) Name</th>
												<th>Reason</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i=0;
											
											foreach($saf_doc_list as $row)
											{
												$docs_name = implode(', ', array_map(function ($entry) {
													return $entry['doc_name'];
												}, $row));

												$document_uploaded=[];
												foreach($uploaded_doc_list as $rec)
												{
													foreach($row as $rec1)
													if($rec["doc_mstr_id"]==$rec1["id"])
													{$document_uploaded=$rec;break;}
												}
												?>
												<tr>
													<td><?=++$i;?></td>
													
													<td>
														<?php 
														if($document_uploaded)
														{
															$extention = strtolower(explode('.',  $document_uploaded["doc_path"])[1]);
															if ($extention=="pdf")
															{
																?>
																	<a href="<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["doc_path"];?>" target="_blank"> 
																		<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
																		<br><span class="text text-primary"><?=$document_uploaded["doc_name"];?></span>
																	</a>
																	
																<?php
															}
															else
															{
																?>
																	<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["doc_path"];?>">
																		<img src='<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["doc_path"];?>' class='img-lg' />
																		<br><span class="text text-primary"><?=$document_uploaded["doc_name"];?></span>
																	</a>
																<?php
															}
														}
														else
														{
															$everyDocUploaded=false;
															?>
															<span class="text text-danger text-bold">Not Uploaded</span>
															<?php
														}
														  ?>
														
													</td> 
													<!--td>
														<?php /*
														if($document_uploaded)
														{
															echo "<p style='color:green'>Document Uploaded</p>";
														}
														else
														{
															$everyDocUploaded=false;
															?>
															<span class="text text-danger text-bold">Not Uploaded</span>
															<?php
														} */
														?>
														
													</td-->
													<td>
													<?php if($emp_details_id==0){ ?>
															<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#btn_upload_modal<?=$i;?>">Click here to upload</button>
															<!-- Owner Doc Upload Modal -->
															<div class="modal fade" id="btn_upload_modal<?=$i;?>" role="dialog">
																<div class="modal-dialog modal-lg">
																	<!-- Modal content-->
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																			<h4 class="modal-title">Upload Document</h4>
																		</div>
																		<div class="modal-body">
																			<form method="post" enctype="multipart/form-data">
																				<input type="hidden" name="other_doc" id="other_doc" value="<?=$row[0]["doc_type"];?>" />
																				<div class="table-responsive">
																					<form method="post" enctype="multipart/form-data">
																					<table class="table table-bordered text-sm" >
																						<tr>
																							<td><b>Document Name</b></td>
																							<td>:</td>
																							<td><select class="form-control" name="doc_mstr_id" id="doc_mstr_id" required>
																									<option value="">Select</option>
																									<?php
																									foreach($row as $select)
																									{
																										?>
																										<option value="<?=$select["id"];?>"><?=$select["doc_name"];?></option>
																										<?php
																									}
																									?>
																								</select>
																							</td>
																							<td>
																								<input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" required />
																								<span class="text text-danger">Only .pdf allowed</span>
																							</td>
																							<td><input type="submit" name="btn_upload" class="btn btn-success" value="Upload" /></td>
																						</tr>
																					</table>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
															<?php } ?>
													</td>
													<td><?=$docs_name;?></td>
													<td><?=implode(" ", explode("_", $row[0]["doc_type"]));?></td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
									</div>
								</div>
							</div>
								
								<?php
								// if($payment_status==0)
								// {
								// 	?>
								<!--div class="panel text-center">
								// 		<a href="<?=base_url("CitizenDtl/citizen_saf_confirm_payment");?>" class="btn btn-primary">Proceed Payment</a>
								// 	</div-->
								<?php
								// }
								?>
                        </div>
						
						<div class="col-sm-2">
							<?=$this->include('citizen/SAF/SafCommonPage/saf_left_side');?>
						</div>
						
						<?php 
						// saf_pending_status
						// 0 Not Approved
						// 1 Approved by EO
						// 2 Backtocitizen
						//echo $everyDocUploaded, $payment_status, $saf_pending_status;
						//$everyDocUploaded=true;
						if($everyDocUploaded==true && $payment_status==0 && $saf_pending_status==0)
						{
							?>
							<div class="panel panel-dark">
								<div class="panel-body">
									<div class="col-sm-2 col-sm-offset-5">
										<!-- <a href="<?php echo base_url('CitizenDtl/send_rmc');?>" class="btn btn-info btn-md" onclick="return confirm('Please make sure you have uploaded a valid documents, otherwise application might be rejected by dealing officer.\n\nAre sure want to send application to ULB?')">Send To ULB
										</a> -->
										<a href="<?=base_url("CitizenDtl/citizen_saf_confirm_payment");?>" class="btn btn-primary">Proceed Payment</a>
									</div>
								</div>
							</div>
							<?php 
						}
						else if($payment_status==0)
						{

							?>
							<!-- <div class="panel panel-dark">
								<div class="panel-body">
									<div class="alert alert-info">
										<strong>Notice!</strong> Payment is not clear.
									</div>
								</div>
							</div> -->
							<?php 

						}
						?>
                    </div>
            </div>
        </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
<script>
$(document).ready(function(){
    $("#sidebarmenu a").each(function()
    {
        //console.log(decodeURIComponent($(this).attr("href")));
        if(decodeURIComponent($(this).attr("href")).replace(/\\/gi, "/") == decodeURIComponent(window.location.href))
        {
            $(this).addClass('active');
        }
    });
});
</script>