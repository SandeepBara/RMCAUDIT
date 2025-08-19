<?= $this->include('layout_vertical/header');?>

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
					<li><a href="<?=base_url("WaterApplyNewConnection/water_connection_view/".md5($consumer_details["id"]));?>">View Water Connection</a></li>
					<li class="active">View Documents</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title"> Water Connection Details</h3>
							</div>
							<div class="panel-body">
							<div class="row">
								<label class="col-md-2 bolder">Type of Connection </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['connection_type']; ?>
								</div>
								<label class="col-md-2 bolder">Connection Through </label>
								<div class="col-md-3 pad-btm"><?php echo $consumer_details['connection_through']; ?> </div>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Property Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['property_type']; ?> 
									<?php 
									// Apartment/Multi Stored Unit
									if($consumer_details["property_type_id"]==7)
									{
										echo "($consumer_details[flat_count] Flat)";
									}
									?>
								</div>
								<label class="col-md-2 bolder">Pipeline Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['pipeline_type']; ?> 
								</div>
							</div>
						
							<div class="row">
								<label class="col-md-2 bolder">Category </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['category']; ?> 
								</div>
									<label class="col-md-2 bolder">Owner Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['owner_type']; ?> 
								</div>
							</div>
						</div>
					</div>

					<!------- Panel Owner Details-------->
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Owner Document</h3>
						</div>
						<div class="panel-body" style="padding-bottom: 0px;">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered text-sm">
											<thead class="bg-trans-dark text-dark">
												<tr>
													<th>#</th>
													<th>Owner Name</th>
													<th>Guardian Name</th>
													<th>Mobile No</th>
													<th>Email ID</th>
													<th>Applicant Image</th>
													<th>Applicant Document</th>
												</tr>
											</thead>
											<tbody id="owner_dtl_append">
											<?php
											if(isset($owner_details))
											{
												$i=0;
												foreach ($owner_details as $owner_detail)
												{
													//print_var($owner_detail);
													?>
													<tr>
														<td><?=++$i;?></td>
														
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
														<td>
															<?php
															if ($owner_detail['applicant_img_dtl'])
															{
																$path = $owner_detail['applicant_img_dtl']['doc_path'];
																?>
																	<a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$path;?>','xtf','900','700');">
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
																		<a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$path;?>','xtf','900','700');"> 
																			<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
																			<br><span class="text text-primary"><?=$owner_detail['applicant_doc_dtl']["document_name"];?><span>
																		</a>
																	<?php
																}
																else
																{
																	?>
																		<a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$path;?>','xtf','900','700');">
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
							<h3 class="panel-title">Other Document</h3>
						</div>
						<div class="panel-body" style="padding-bottom: 0px;">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered text-sm">
											<thead class="bg-trans-dark text-dark">
												<tr>
													<th>#</th>
													<th>Document Name</th>
													<th>Document</th>
													<th>Status</th>
													<th>Remarks</th>
												</tr>
											</thead>
											<tbody>
											<?php
											$i=0;
											if(isset($uploaded_doc_list))
											foreach($uploaded_doc_list as $rec)
											{
												if(in_array($rec["doc_group"], ["CONSUMER_PHOTO", "ID Proof"]))
												continue;


												?>
												<tr>
													<td><?=++$i;?></td>
													<td><?=$rec["doc_group"];?></td>
													<td>
														<?php
														$extention = strtolower(explode('.',  $rec["document_path"])[1]);
														if ($extention=="pdf")
														{
															?>
																<a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$rec["document_path"];?>','xtf','900','700');"> 
																	<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
																	<br><span class="text text-primary"><?=$rec["document_name"]!="" ? $rec["document_name"] : $rec["doc_name"]??"";?></span>
																</a>
																
															<?php
														}
														else
														{
															?>
																<a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$rec["document_path"];?>','xtf','900','700');">
																	<img src='<?=base_url();?>/getImageLink.php?path=<?=$rec["document_path"];?>' class='img-lg' />
																	<br><span class="text text-primary"><?=$rec["document_name"];?></span>
																</a>
															<?php
														}
														?>
													</td>
													<td>
														<?php
															if($rec["verify_status"]==1)
															echo '<span class="text text-success text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$rec["remarks"].'">Verfied</span>';
															else if($rec["verify_status"]==2)
															echo '<span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$rec["remarks"].'">Rejected</span>';
															else
															echo '<span class="text text-warning text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$rec["remarks"].'">Pending</span>';
														?>
													</td>
													
													<td><?=$rec["remarks"];?></td>
												</tr>
												<?php
											}
											?>
											</tbody>
										</table>
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

<script type="text/javascript">
function myPopup(myURL, title, myWidth, myHeight)
{
    var left = (screen.width - myWidth) / 2;
    var top = (screen.height - myHeight) / 4;
    var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}
</script>
<?= $this->include('layout_vertical/footer');?>