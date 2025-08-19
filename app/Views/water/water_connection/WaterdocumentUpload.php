<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#"> Water </a></li>
            <li><a href="<?=base_url('WaterApplyNewConnection/water_connection_view/'.md5($apply_connection_id));?>"> Water Connection Details </a></li>
			<li class="active"><a href="#"> Upload Documents </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
	
    <div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="col-sm-6">
                    <h3 class="panel-title">Water Connection Details</h3>
                </div>

			</div>
			<div class="panel-body">  
				<div class="row">
					<label class="col-md-2 bolder">Application No. <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm text-bold">
						<?php echo $connection_dtls['application_no']; ?>
					</div>
				</div>

				<div class="row">
					<label class="col-md-2 bolder">Type of Connection <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $connection_dtls['connection_type']; ?>
					</div>
					<label class="col-md-2 bolder">Connection Through <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $connection_dtls['connection_through']; ?> 
					</div>
				</div>
				<div class="row">
					<label class="col-md-2 bolder">Property Type <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $connection_dtls['property_type']; ?> 
					</div>
						<label class="col-md-2 bolder">Pipeline Type <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $connection_dtls['pipeline_type']; ?> 
					</div>
				</div>
			
				<div class="row">
					<label class="col-md-2 bolder">Category <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $connection_dtls['category']; ?> 
					</div>
						<label class="col-md-2 bolder">Owner Type <span class="text-danger"></span></label>
					<div class="col-md-3 pad-btm">
						<?php echo $connection_dtls['owner_type']; ?> 
					</div>
				</div>
			</div>
		</div>
        

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
                                            <th>Upload</th>
                                            <th>Owner Name</th>
                                            <th>Guardian Name</th>
                                            <th>Mobile No</th>
                                            <th>Email ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
									<?php
									$everyDocUploaded=true;
									if (isset($owner_details))
									{
										foreach ($owner_details as $owner_detail)
										{
											//print_var($owner_detail);
											?>
											<tr>
												<td>
													<?php
													if ($owner_detail['applicant_img_dtl'])
													{
														$path = $owner_detail['applicant_img_dtl']['doc_path'];
														?>
															<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
																<img src="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" class="img-lg" />
																
															</a>
															
															<?php
																if($owner_detail['applicant_img_dtl']['verify_status']??null)
																{
																	echo"<br>";
																	if($owner_detail['applicant_img_dtl']['verify_status']==1)
																	echo '<span class="text text-success text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="">Verfied</span>';
																	else if($owner_detail['applicant_img_dtl']['verify_status']==2)
																	echo '<span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="">Rejected</span>';
																	else
																	echo '<span class="text text-warning text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="">Pending</span>';
																}
															?>

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
																	<br><span class="text text-primary"><?=$owner_detail['applicant_doc_dtl']["document_name"];?><span>
																</a>
																<?php
																	if($owner_detail['applicant_doc_dtl']['verify_status']??null)
																	{
																		echo"<br>";
																		if($owner_detail['applicant_doc_dtl']['verify_status']==1)
																		echo '<span class="text text-success text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="">Verfied</span>';
																		else if($owner_detail['applicant_doc_dtl']['verify_status']==2)
																		echo '<span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="">Rejected</span>';
																		else
																		echo '<span class="text text-warning text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="">Pending</span>';
																	}
																?>

															<?php
														}
														else
														{
															?>
																<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
																	<img src='<?=base_url();?>/getImageLink.php?path=<?=$path;?>' class='img-lg' />
																	<br><span class="text text-primary"><?=$owner_detail['applicant_doc_dtl']["document_name"];?><span>
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
												<td>
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
																			<input type="hidden" name="owner_dtl_id" id="owner_dtl_id" value="<?=$owner_detail["id"];?>" />
																			<div class="table-responsive">
																				<table class="table table-bordered text-sm" >
																					<tr>
																						<td><b>Name</b></td>
																						<td>:</td>
																						<td><?=$owner_detail['applicant_name'];?></td>
																						<td><b>Guardian Name</b></td>
																						<td>:</td>
																						<td><?=$owner_detail['father_name'];?></td>
																					</tr>
																					<tr>
																						<td><b>Mobile</b></td>
																						<td>:</td>
																						<td><?=$owner_detail['mobile_no'];?></td>
																						<td></td>
																						<td></td>
																						<td></td>
																						
																					</tr>
																					<tr>
																						
																						<td></td>
																						<td></td>
																						<td></td>
																						<td><b>Email Id</b></td>
																						<td>:</td>
																						<td><?=$owner_detail['email_id'];?></td>
																					</tr>
																					<tr>
																						<td>Applicant Image</td>
																						<td>:</td>
																						<td colspan="3"><img/></td>
																						<td colspan="4">
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
																							<input type="file" name="applicant_doc_file" id="applicant_doc_file" class="form-control" accept=".pdf" required/>
																						</td>
																					</tr>
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
												</td>
												<td>
													<?=$owner_detail['applicant_name'];?>
												</td>
												<td>
													<?=$owner_detail['father_name'];?>
												</td>
												
												<td>
													<?=$owner_detail['mobile_no'];?>
												</td>
												
												<td>
													<?=$owner_detail['email_id'];?>
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
                <h3 class="panel-title">Upload Other Document</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th>#</th>
                            <th>Document</th>
							<th>Verification Status</th>
                            <th>Upload</th>
                            <th style="width: 25%;">Document(s) Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=0;	
                        foreach($water_doc_list as $row)
                        {
                            $docs_name = implode(', ', array_map(function ($entry) {
                                return $entry['doc_name'];
                            }, $row));

                            $document_uploaded=[];
							$doc_group = array_unique(array_column($row,"doc_for"))[0]??"";
                            foreach($uploaded_doc_list as $rec)
                            {
                                foreach($row as $rec1){
									if($docs_name==""){
										break;
									}
									if($rec["document_id"]==$rec1["id"]){
										$document_uploaded=$rec;
										break;
									}
								}
                            }
                            ?>
                            <tr>
                                <td><?=++$i;?></td>
                                <td>
                                    <?php 
                                    if($document_uploaded)
                                    {
										# print_var($document_uploaded);exit;
                                        $extention = strtolower(explode('.',  $document_uploaded["document_path"])[1]);
                                        if ($extention=="pdf")
                                        {
                                            ?>
                                                <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>','xtf','900','700');"> 
                                                    <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                    <br><span class="text text-primary"><?=$document_uploaded["document_name"];?></span>
                                                </a>
                                                
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>','xtf','900','700');">
                                                    <img src='<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>' class='img-lg' />
                                                    <br><span class="text text-primary"><?=$document_uploaded["document_name"];?></span>
                                                </a>
                                            <?php
                                        }
                                    }
                                    else
                                    {
										if(isset($doc_group) && !in_array($doc_group,["Address Proof","OTHER_DOC"])){
											$everyDocUploaded=(($docs_name??"A")!="")?false:$everyDocUploaded;
										}
                                        ?>
                                        <span class="text text-danger text-bold">Not Uploaded</span>
                                        <?php
                                    }
                                    ?>
                                    
                                </td>
								<td>
                                    <?php 
                                    if($document_uploaded)
                                    {
										if($document_uploaded["verify_status"]==1)
										echo '<span class="text text-success text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$document_uploaded["remarks"].'">Verfied</span>';
										else if($document_uploaded["verify_status"]==2)
										echo '<span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$document_uploaded["remarks"].'">Rejected</span>';
										else
										echo '<span class="text text-warning text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$document_uploaded["remarks"].'">Pending</span>';
                                    }
                                    else
                                    { /*
                                        ?>
										<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>">
											<img src='<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>' class='img-lg' />
											<br><span class="text text-primary"><?=$document_uploaded["doc_name"];?></span>
										</a>
										<?php
									*/
									echo "<span class='text-danger text-bold'>Not Uploaded</span>";
									}
                                    ?>
                                    
                                </td>
                                <td>
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
                                                            <input type="hidden" name="other_doc" id="other_doc" value="<?=$row[0]["doc_for"];?>" />
                                                            <div class="table-responsive">
                                                                <form method="post" enctype="multipart/form-data">
                                                                <table class="table table-bordered text-sm" >
                                                                    <tr>
                                                                        <td><b>Document Name</b></td>
                                                                        <td>:</td>
                                                                        <td>
																			<?php
																				if($docs_name==""){
																					?>
																					<input type="text" name="other_doc" id= "other_doc" value="" required />
																					<input type="hidden" class="form-control" name="doc_mstr_id" id="doc_mstr_id" value="<?=$row[0]["id"];?>" />
																					<?php
																				}
																				else{
																					?>
																						<select class="form-control" name="doc_mstr_id" id="doc_mstr_id" required>
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
																					<?php
																				}
																			?>
                                                                        </td>
                                                                        <td><input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" required></td>
                                                                        <td><input type="submit" name="btn_upload" class="btn btn-success" value="Upload" /></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </td>
                                <td><?=$docs_name !="" ? $docs_name : $row[0]["doc_for"]??"";?></td>
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
        // saf_pending_status
        // 0 Not Approved
        // 1 Approved by EO
        // 2 Backtocitizen
        //echo $everyDocUploaded, $payment_status, $saf_pending_status;
		$payment_status = $connection_dtls["payment_status"];
        if($everyDocUploaded==true && $payment_status==1)
        {
            ?>
            <div class="panel panel-dark">
                <div class="panel-body">
                    <div class="col-sm-2 col-sm-offset-5">
                        <a href="<?php echo base_url('WaterDocument/send_rmc/'.md5($apply_connection_id));?>" class="btn btn-info btn-md">Send To ULB
                        </a>
                    </div>
                </div>
            </div>
		    <?php 
        }
        else if($payment_status==0)
        {
            ?>
            <div class="panel panel-dark">
                <div class="panel-body">
                    <div class="alert alert-info">
                        <strong>Notice!</strong> Payment is not clear.
                    </div>
                </div>
            </div>
		    <?php 
        }
        ?>
	</div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

	
<script type="text/javascript">
function myPopup(myURL, title, myWidth, myHeight)
{
    var left = (screen.width - myWidth) / 2;
    var top = (screen.height - myHeight) / 4;
    var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}
</script>
<?=$this->include('layout_vertical/footer');?>