<?=$this->include('layout_home/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->
      
        <div id="page-content">
            <div class="panel panel-bordered ">
                    <div class="panel-body">
                        
                        <div class="col-sm-10">
                            <!------- Panel Owner Details-------->
							 <!-------Owner Details-------->
								 <!-------Owner Details-------->
								 <div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Owner Details</h3>
									</div>
									<div class="panel-body" style="padding-bottom: 0px;">
										<div class="row">
											<div class="col-md-12">
												<div class="table-responsive">
													<table class="table table-bordered text-sm">
														<thead class="bg-trans-dark text-dark">
															<tr>
																<th>Owner Name</th>
																<th>Guardian Name</th>
																<th>Relation</th>
																<th>Mobile No</th>
																<th>Aadhar No.</th>
																<th>PAN No.</th>
																<th>Email Id</th>
																<th>Applicant Image</th>
																<th>Applicant Document</th>
															</tr>
														</thead>
														<tbody id="owner_dtl_append">
													<?php
													
													if (isset($saf_owner_detail))
													{
														foreach ($saf_owner_detail as $owner_detail)
														{
															
															?>
															<tr>
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
																<td>
																<?php
																if ($owner_detail['applicant_img_dtl'])
																{
																	echo "<span class='text-success text-bold'>Document is uploaded.</span>";
																	/*$path = $owner_detail['applicant_img_dtl']['doc_path'];
																	?>
																		<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
																			<img src="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" class="img-lg" />
																		</a>
																	<?php */
																}
																else
																{
																	echo "<span class='text-warning text-bold'>Document is not uploaded.</span>";
																}
																?>
																</td>
																<td>
																<?php
																if ($owner_detail['applicant_doc_dtl'])
																{
																	echo "<span class='text-success text-bold'>Document is uploaded.</span>";
																	/*$path = $owner_detail['applicant_doc_dtl']['doc_path'];
																	$extention = strtolower(explode('.', $path)[1]);
																	if ($extention=="pdf")
																	{
																		?>
																			<a href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" target="_blank" > 
																				<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
																			</a>
																		<?php
																	}
																	else
																	{
																		?>
																			<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
																				<img src='<?=base_url();?>/getImageLink.php?path=<?=$path;?>' class='img-lg' />
																			</a>
																		<?php
																	} */
																}
																else
																{
																	echo "<span class='text-warning text-bold'>Document is not uploaded.</span>";
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

								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Document Details</h3>
									</div>
									<div class="panel-body">
										<table class="table table-bordered text-sm">
											<thead class="bg-trans-dark text-dark">
												<tr>
													<th>#</th>
													<th>Document Name</th>
													<!-- <th>View</th> -->
													<!-- <th>Upload Status</th> -->
													<th>Status</th>
													<th>Remarks (If Any)</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i=0;
												foreach($uploaded_doc_list as $doc)
												{
													?>
													<tr>
														<td><?=++$i;?></td>
														<td><?=$doc["doc_name"];?></td>
														<!-- <td> -->
															<?php /*
															$extention = strtolower(explode('.', $doc["doc_path"])[1]);
															if ($extention=="pdf")
															{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" target="_blank" > 
																	<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class="img-lg" />
																</a>
																<?php
															}
															else
															{
																?>
																<a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>">
																	<img src="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" class="img-lg" />
																</a>
																<?php
															} */
															?>
														<!-- </td> -->
														<td>
															<?php
															if($doc['verify_status']==0)
															{
																echo "<span class='text-danger text-bold'>Not Verified</span>";
															}
															if($doc['verify_status']==1)
															{
																echo "<span class='text-success text-bold'>Verified</span>";
																echo "<span class='text-primary text-bold'><br>On ".date('d-m-Y', strtotime($doc['verified_on']))."</span>";
															}
															if($doc['verify_status']==2)
															{
																echo "<span class='text-danger text-bold'>Rejected.</span>";
																echo "<span class='text-primary text-bold'><br>On ".date('Y-m-d', strtotime($doc['verified_on']))."</span>";
															}
															?>
														</td>
														<td><?=$doc["remarks"];?></td>
													</tr>
													<?php
												}
												?>
											</tbody>
										</table>
									</div>
								</div>


                        </div>
						
						<div class="col-sm-2">
							<?=$this->include('citizen/SAF/SafCommonPage/saf_left_side');?>
						</div>
						
						
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