<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<!-- <link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet"> -->
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
                        <li><a href="#"> Water </a></li>
                        <li><a href="<?php echo base_url('water_da/index')?>"> Dealing Assistant Inbox </a></li>
                        <li class="active"> Water Connection View </li>
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
						<span class="btn btn-info"><a href="<?php echo base_url('water_da/index')?>" style="color: white;">Back</a></span>
					</div>
                    <h3 class="panel-title"> Water Connection Details 
                    	<b>Application No- <?php echo $consumer_details['application_no'];?></b>
                    </h3>
                </div>
                <div class="panel-body">                      
                    <div class="row">
                        <label class="col-md-2 bolder">Type of Connection </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Connection Through </label>
                        <div class="col-md-3 pad-btm">
            							<?php echo $consumer_details['connection_through']; ?> 
            						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Property Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['property_type']; ?> 
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
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Property Details</h3>
                </div>
                <div class="panel-body">                     
                    <div class="row">
                        <label class="col-md-2 bolder">Ward No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?>
                        </div>
                        <?php

							if($consumer_details['prop_dtl_id']!="" and $consumer_details['prop_dtl_id']!=0)
							{
                        ?>
                        <label class="col-md-2 bolder">Holding No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['holding_no']; ?> 
						</div>
                        <?php   
							}
							else
							{
						?>
                        <label class="col-md-2 bolder">SAF No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['saf_no']; ?> 
                        </div>
                        <?php
							}
                        ?>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Area in Sqft.</label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['area_sqft']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Area in Sqmt.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo round($consumer_details['area_sqmt'],2); ?> 
                        </div>
                    </div>
                   <div class="row">
                        <label class="col-md-2 bolder">Address</label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['address']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Landmark </label>
                        <div class="col-md-3 pad-btm">
                           <?php echo $consumer_details['landmark']; ?> 
                        </div>
                    </div>
					<div class="row">
						<label class="col-md-2 bolder">Pin</label>
						<div class="col-md-3 pad-btm">
						  <?php echo $consumer_details['pin']; ?> 
						</div>
					</div>
                </div>
            </div>
            <div class="clear"></div>
				
                
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Electricity Connection Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<label class="col-md-2 bolder">K No. </label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_k_no']; ?>
							</div>
							<label class="col-md-2 bolder">Bind Book No.</label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_bind_book_no']; ?> 
						   </div>
						</div>
						<div class="row">
							<label class="col-md-2 bolder">Electricity Account No. </label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_account_no']; ?> 
							</div>
							<label class="col-md-2 bolder">Electricity Category </label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_category']; ?> 
							</div>
						</div>
					</div>
				</div>
					

					<form method="post" class="form-horizontal" action="">
                        <!-------Owner Details-------->
						<div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Owner Details</h3>
                            </div>
							<div class="table-responsive">
								<table class="table table-bordered" id="saf_receive_table" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>Name</th>
											<th>Guardian Name</th>
											<th>Mobile No.</th>
											<th></th>
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
											  <td><?php echo $value['applicant_name']; ?></td>
											  <td><?php echo $value['father_name']; ?></td>
											  <td><?php echo $value['mobile_no']; ?></td>
											</tr>
											<tr class="success">
															<th>Document Name</th>
															<th>Applicant Image</th>
															<th>Verify/Reject</th>
															<th>Reject Remarks</th>

														</tr>
														<tr>
															<td>Applicant Image</td>
															<td><a href="<?=base_url();?>/getImageLink.php?path=<?=$value["applicant_img"];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$value["applicant_img"];?>" style="width: 40px; height: 40px;"></a></td>
															<td>
																<?php
																if($value['applicant_img_verify_status']=="0")
																{
																?>

																<select name="app_img_verify[]" id="app_img_verify<?=$j;?>" class="form-control app_img_verify" onchange="app_img_remarks_details(<?=$j;?>);">
																	<option value="">Select</option>
																	<option value="1">Verify</option>
																	<option value="2">Reject</option>
																</select>
																<br/>
																<input type="hidden" name="applicant_img_id[]" value="<?=$value['applicant_img_id'];?>"/>
																<input type="hidden" id="applicant_img_verify_status<?=$j;?>" value="0"/>
																<?php
																}
																else if($value['applicant_img_verify_status']=="1")
																{
																	echo "<span class='text-danger'>Verified</span>";
																}
																else if($value['applicant_img_verify_status']=="2")
																{
																	echo "<span class='text-danger'>Rejected</span>";
																}
																?>
															</td>
															<td>
																<?php
																if($value['applicant_img_verify_status']=="0")
																{
																?>
																<textarea type="text" placeholder="Enter Remarks" id="app_img_remarks<?=$j;?>" name="app_img_remarks[]" class="form-control app_img_remarks" ></textarea>
																<?php
																}
																?>
															</td>

														</tr>
														<tr class="success">
															<th>Document Name</th>
															<th>Applicant Document</th>
															<th>Verify/Reject</th>
															<th>Reject Remarks</th>

														</tr>
														<tr style="border-bottom: 5px solid #000;">
															<td><?=$value["applicant_doc_name"];?></td>
															
															<td>
																
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$value["applicant_doc"];?>" target="_blank"> <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
																
															 </td>
														  <td>
															  <?php
															  if($value['applicant_doc_verify_status']=="0")
															  {
															  ?>

															  <select name="app_doc_verify[]" id="app_doc_verify<?=$j;?>" class="form-control app_doc_verify" onchange="app_doc_remarks_details(<?=$j;?>);">
																  <option value="">Select</option>
																  <option value="1">Verify</option>
																  <option value="2">Reject</option>
															  </select>
															  <br/>
															  <input type="hidden" name="applicant_doc_id[]" value="<?=$value['applicant_doc_id'];?>"/>
															  <input type="hidden" id="applicant_doc_verify_status<?=$j;?>" value="0"/>
															  <?php
															  }
															  else if($value['applicant_doc_verify_status']=="1")
															  {
																  echo "<span class='text-danger'>Verified</span>";
															  }
															  else if($value['applicant_doc_verify_status']=="2")
															  {
																  echo "<span class='text-danger'>Rejected</span>";
															  }
															  ?>
															</td>
															<td>
																<?php
															  if($value['applicant_doc_verify_status']=="0")
															  {
															  ?>
																<textarea type="text" placeholder="Enter Remarks" id="app_doc_remarks<?=$j;?>" name="app_doc_remarks[]" class="form-control app_doc_remarks" ></textarea>
																<?php
															}
															?>
															</td>


														</tr>
										<?php endforeach; ?>
										<?php endif; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					


                            <!--------prop doc------------>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Document Details</h3>
                            </div>
							<div class="table-responsive">
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th style="width:160px;">Document Name</th>
											<th style="width:160px;">Document Image</th>
											<th style="width:160px;"></th>
											<th style="width:200px;"></th>

										</tr>
									</thead>
									<tbody>

										<?php
										

										if($consumer_details['connection_through_id']==1 and $pr_doc_name!="")
										{


										?>
										<tr>
											<td><?=$pr_doc_nm;?></td>
											<?php
													$exp_pr_doc=explode('.',$pr_doc_name);
													$exp_pr_doc_ext=$exp_pr_doc[1];
													?>
													<td>
														<?php
														if($exp_pr_doc_ext=='pdf')
														{
														?>
														<a href="<?=base_url();?>/getImageLink.php?path=<?=$pr_doc_name;?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
														<?php
														}
														else
														{
														?>
														<a href="<?=base_url();?>/getImageLink.php?path=<?=$pr_doc_name;?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$pr_doc_name;?>" style="width: 40px; height: 40px;"></a>
														<?php
														}
														?>

												 </td>

											<td>
												<?php
												if($pr_doc_verify_status=="0")
												{
												?>

												<input type="hidden" name="pr_document_id" value="<?=$pr_doc_id;?>"/>
												<input type="hidden" id="pr_verify_status" value="0"/>

												<select name="pr_verify" id="pr_verify" class="form-control">
													<option value="">Select</option>
													<option value="1">Verify</option>
													<option value="2">Reject</option>
												</select>
												<br/>
												
												<?php
												}
												else if($pr_doc_verify_status=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($pr_doc_verify_status=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><textarea type="text" placeholder="Enter Remarks" id="pr_remarks" name="pr_remarks" class="form-control" ></textarea></td>
										</tr>

										<?php
										}
										?>

										<tr>
											<td><?=$ap_doc_nm;?> (<span class="text-danger"><?=$ap_document_name;?></span>)</td>
											<?php
															$exp_ap_doc=explode('.',$ap_doc_name);
															$exp_ap_doc_ext=$exp_ap_doc[1];
															?>
															<td>
																<?php
																if($exp_ap_doc_ext=='pdf')
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$ap_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
																<?php
																}
																else
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$ap_doc_name;?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$ap_doc_name;?>" style="width: 40px; height: 40px;"></a>
																<?php
																}
																?>
											 </td>
											<td>
												<?php
												if($ap_doc_verify_status=="0")
												{
												?>

													<input type="hidden" name="ap_document_id" value="<?=$ap_doc_id;?>"/>
												<input type="hidden" id="ap_verify_status" value="0"/>
												<select name="ap_verify" id="ap_verify" class="form-control">
													<option value="">Select</option>
													<option value="1">Verify</option>
													<option value="2">Reject</option>
												</select>
												<br/>

												<?php
												}
												else if($ap_doc_verify_status=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($ap_doc_verify_status=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><textarea type="text" placeholder="Enter Remarks" id="ap_remarks" name="ap_remarks" class="form-control" ></textarea></td>
										</tr>
										<tr>
											<td><?=$cf_doc_nm;?></td>
											<?php
															$exp_cf_doc=explode('.',$cf_doc_name);
															$exp_cf_doc_ext=$exp_cf_doc[1];
															?>
															<td>
																<?php
																if($exp_cf_doc_ext=='pdf')
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$cf_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
																<?php
																}
																else
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$cf_doc_name;?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$cf_doc_name;?>" style="width: 40px; height: 40px;"></a>
																<?php
																}
																?>
											 </td>
											<td>
												<?php
												if($cf_doc_verify_status=="0")
												{
												?>

													<input type="hidden" name="cf_document_id" value="<?=$cf_doc_id;?>"/>
												<input type="hidden" id="cf_verify_status" value="0"/>
												<select name="cf_verify" id="cf_verify" class="form-control">
													<option value="">Select</option>
													<option value="1">Verify</option>
													<option value="2">Reject</option>
												</select>
												<br/>

												<?php
												}
												else if($cf_doc_verify_status=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($cf_doc_verify_status=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><textarea type="text" placeholder="Enter Remarks" id="cf_remarks" name="cf_remarks" class="form-control" ></textarea></td>
										</tr>
										<tr>
											<td><?=$ed_doc_nm;?></td>
											<?php
															$exp_ed_doc=explode('.',$ed_doc_name);
															$exp_ed_doc_ext=$exp_ed_doc[1];
															?>
															<td>
																<?php
																if($exp_ed_doc_ext=='pdf')
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$ed_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
																<?php
																}
																else
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$ed_doc_name;?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$ed_doc_name;?>" style="width: 40px; height: 40px;"></a>
																<?php
																}
																?>
											 </td>
											<td>
												<?php
												if($ed_doc_verify_status=="0")
												{
												?>

													<input type="hidden" name="ed_document_id" value="<?=$ed_doc_id;?>"/>
												<input type="hidden" id="ed_verify_status" value="0"/>
												<select name="ed_verify" id="ed_verify" class="form-control">
													<option value="">Select</option>
													<option value="1">Verify</option>
													<option value="2">Reject</option>
												</select>
												<br/>

												<?php
												}
												else if($ed_doc_verify_status=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($ed_doc_verify_status=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><textarea type="text" placeholder="Enter Remarks" id="ed_remarks" name="ed_remarks" class="form-control" ></textarea></td>
										</tr>
										<?php
										if(isset($mb_doc_nm))
										{ 
										?>

										<tr>

											<td><?=$mb_doc_nm;?></td>
											<?php
															$exp_mb_doc=explode('.',$mb_doc_name);
															$exp_mb_doc_ext=$exp_mb_doc[1];
															?>
															<td>
																<?php
																if($exp_mb_doc_ext=='pdf')
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$mb_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
																<?php
																}
																else
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$mb_doc_name;?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$mb_doc_name;?>" style="width: 40px; height: 40px;"></a>
																<?php
																}
																?>
											 </td>
											<td>
												<?php
												if($mb_doc_verify_status=="0")
												{
												?>

													<input type="hidden" name="mb_document_id" value="<?=$mb_doc_id;?>"/>
												<input type="hidden" id="mb_verify_status" value="0"/>
												<select name="mb_verify" id="mb_verify" class="form-control">
													<option value="">Select</option>
													<option value="1">Verify</option>
													<option value="2">Reject</option>
												</select>
												<br/>

												<?php
												}
												else if($mb_doc_verify_status=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($mb_doc_verify_status=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><textarea type="text" placeholder="Enter Remarks" id="mb_remarks" name="mb_remarks" class="form-control" ></textarea></td>
										</tr>



										<?php 
										}

									if($form['category']=='BPL'){ 
											

											?>
										<tr>
											<td><?=$bpl_doc_nm;?></td>
											<?php
															$exp_bpl_doc=explode('.',$bpl_doc_name);
															$exp_bpl_doc_ext=$exp_bpl_doc[1];
															?>
															<td>
																<?php
																if($exp_bpl_doc_ext=='pdf')
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$bpl_doc_name;?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
																<?php
																}
																else
																{
																?>
																<a href="<?=base_url();?>/getImageLink.php?path=<?=$bpl_doc_name;?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$bpl_doc_name;?>" style="width: 40px; height: 40px;"></a>
																<?php
																}
																?>
											 </td>
											<td>
												<?php
												if($bpl_doc_verify_status=="0")
												{
												?>

													<input type="hidden" name="bpl_document_id" value="<?=$bpl_doc_id;?>"/>
												<input type="hidden" id="bpl_verify_status" value="0"/>
												<select name="bpl_verify" id="bpl_verify" class="form-control">
													<option value="">Select</option>
													<option value="1">Verify</option>
													<option value="2">Reject</option>
												</select>
												<br/>

												<?php
												}
												else if($bpl_doc_verify_status=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($bpl_doc_verify_status=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												?>
											</td>
											<td><textarea type="text" placeholder="Enter Remarks" id="bpl_remarks" name="bpl_remarks" class="form-control" ></textarea></td>
										</tr>
										<?php } ?>
									</tbody>
									<input type="hidden" name="count_app" id="count_app" value="<?=$app_cnt;?>"/>
									<input type="hidden" name="count_change_app" id="count_change_app" value="0"/>
								</table>
							</div>
						</div>
            
                            <!------------>
                            
                            <?=$this->include('water/water_connection/LevelRemarksTab');?>

						<div class="panel panel-bordered panel-dark">
							<div class="panel-body" style="padding-bottom: 0px;">
								 <div class="form-group">
									<label class="col-md-2" >Remarks</label>
									 <div class="col-md-10">
										 <textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" ></textarea>
									 </div>
								</div>
								<?php
								if(isset($form['verification_status']))
								{
									if($form['verification_status']=="0")
									{
                                        ?>
                                        <div class="form-group">
                                            <label class="col-md-2" >&nbsp;</label>
                                            <div class="col-md-10">
                                                <button class="btn btn-primary" id="btn_approve_submit" name="btn_approve_submit" type="submit" style="display:none;">Approve</button>
                                                <button class="btn btn-danger" id="btn_app_submit" name="btn_app_submit" type="submit">Back To Citizen</button>
                                                <!-- <button class="btn btn-danger" id="reject" name="reject" type="submit">Reject</button> -->
                                            </div>
                                        </div>
                                        <?php
									}
								}
								?>
							</div>
						</div>
                    </form>

                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
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

<script>
    function app_img_remarks_details(il)
    {
        debugger;
     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var applicant_img_verify_status =$('#applicant_img_verify_status'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
     //var verified_count = parseInt($("#verified_count").val());
     //alert(app_img_verify);
     if(app_img_verify=="2")
        {
            if(count_change_app>0)
            {
                if(applicant_img_verify_status==1)
                {
                    $("#applicant_img_verify_status"+il).val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }

            $("#app_img_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(app_img_verify=="1")
        {
            if(applicant_img_verify_status==0)
            {
                $("#applicant_img_verify_status"+il).val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);

                $("#app_img_remarks"+il).hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    }
    function app_doc_remarks_details(il)
    {

     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_doc_verify_status =$('#applicant_doc_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
       //alert(app_img_verify);
     if(app_doc_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_doc_verify_status==1){
                   $("#applicant_doc_verify_status"+il).val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }

            $("#app_doc_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(app_doc_verify=="1")
        {
            if(applicant_doc_verify_status==0){
                    $("#applicant_doc_verify_status"+il).val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#app_doc_remarks"+il).hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    }
    $(document).ready(function()
    {
        $(".app_img_remarks").hide();
        $(".app_doc_remarks").hide();
        $("#pr_remarks").hide();
        $("#ap_remarks").hide();
        $("#cf_remarks").hide();
        $("#ed_remarks").hide();
        $("#mb_remarks").hide();
        $("#bpl_remarks").hide();



        $("#pr_verify").on('change',function()
        {
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var pr_verify = $("#pr_verify").val();
        var pr_verify_status = $("#pr_verify_status").val();
        if(pr_verify=="2")
        {
            if(count_change_app>0){
                if(pr_verify_status==1){
                   $("#pr_verify_status").val(0);


                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#pr_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(pr_verify=="1")
        {
            if(pr_verify_status==0){
                    $("#pr_verify_status").val(1);

                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#pr_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#ap_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var ap_verify = $("#ap_verify").val();
        var ap_verify_status = $("#ap_verify_status").val();
        if(ap_verify=="2")
        {
            if(count_change_app>0){
                if(ap_verify_status==1){
                   $("#ap_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                }
            }
            $("#ap_remarks").show();
             $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(ap_verify=="1")
        {
            if(ap_verify_status==0){
                $("#ap_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#ap_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });

    $("#cf_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var cf_verify = $("#cf_verify").val();
        var cf_verify_status = $("#cf_verify_status").val();
        if(cf_verify=="2")
        {
            if(count_change_app>0){
                if(cf_verify_status==1){
                   $("#cf_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#cf_remarks").show();
             $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(cf_verify=="1")
        {
            if(cf_verify_status==0){
                    $("#cf_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#cf_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#ed_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var ed_verify = $("#ed_verify").val();
        var ed_verify_status = $("#ed_verify_status").val();
        if(ed_verify=="2")
        {
            if(count_change_app>0){
                if(ed_verify_status==1){
                   $("#ed_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#ed_remarks").show();
             $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(ed_verify=="1")
        {
            if(ed_verify_status==0){
                $("#ed_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#ed_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });

    $("#mb_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var mb_verify = $("#mb_verify").val();
        var mb_verify_status = $("#mb_verify_status").val();
        if(mb_verify=="2")
        {
            if(count_change_app>0){
                if(mb_verify_status==1){
                   $("#mb_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#mb_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(mb_verify=="1")
        {
            if(mb_verify_status==0){
                $("#mb_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
            $("#mb_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#bpl_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var bpl_verify = $("#bpl_verify").val();
        var bpl_verify_status = $("#bpl_verify_status").val();
        if(bpl_verify=="2")
        {
            if(count_change_app>0){
                if(bpl_verify_status==1){
                   $("#bpl_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#bpl_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(bpl_verify=="1")
        {
            if(bpl_verify_status==0){
                $("#bpl_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
            $("#bpl_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#btn_app_submit").click(function(){
        var proceed = true;

        $('#saf_receive_table').find('.app_img_verify').each(function(){
            $(this).css('border-color','');
            var ID = this.id.split('app_img_verify')[1];
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
             if($(this).val()=='2'){
                 if ($("#app_img_remarks"+ID).val()=="") {
                     $("#app_img_remarks"+ID).css('border-color','red'); 	proceed = false;
                 }

             }
        });
        $('#saf_receive_table').find('.app_doc_verify').each(function(){
            $(this).css('border-color','');
            var IDD = this.id.split('app_doc_verify')[1];
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
             if($(this).val()=='2'){
                 if ($("#app_doc_remarks"+IDD).val()=="") {
                     $("#app_doc_remarks"+IDD).css('border-color','red'); 	proceed = false;
                 }
             }
        });

        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }

        var pr_verify = $("#pr_verify").val();
        if(pr_verify=="")
        {
            $('#pr_verify').css('border-color','red');
            proceed = false;
        }
        if(pr_verify=="2")
        {
            var pr_remarks = $("#pr_remarks").val();
            if(pr_remarks=="")
            {
                $('#pr_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var ap_verify = $("#ap_verify").val();
        if(ap_verify=="")
        {
            $('#ap_verify').css('border-color','red');
            proceed = false;
        }
        if(ap_verify=="2")
        {
            var ap_remarks = $("#ap_remarks").val();
            if(ap_remarks=="")
            {
                $('#ap_remarks').css('border-color','red');
                proceed = false;
            }
        }

        var cf_verify = $("#cf_verify").val();
        if(cf_verify=="")
        {
            $('#cf_verify').css('border-color','red');
            proceed = false;
        }
        if(cf_verify=="2")
        {
            var cf_remarks = $("#cf_remarks").val();
            if(cf_remarks=="")
            {
                $('#cf_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var ed_verify = $("#ed_verify").val();
        if(ed_verify=="")
        {
            $('#ed_verify').css('border-color','red');
            proceed = false;
        }
        if(ed_verify=="2")
        {
            var ed_remarks = $("#ed_remarks").val();
            if(ed_remarks=="")
            {
                $('#ed_remarks').css('border-color','red');
                proceed = false;
            }
        }

        var mb_verify = $("#mb_verify").val();
        if(mb_verify=="")
        {
            $('#mb_verify').css('border-color','red');
            proceed = false;
        }
        if(mb_verify=="2")
        {
            var mb_remarks = $("#mb_remarks").val();
            if(mb_remarks=="")
            {
                $('#mb_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var bpl_verify = $("#bpl_verify").val();
        if(bpl_verify=="")
        {
            $('#bpl_verify').css('border-color','red');
            proceed = false;
        }
        if(bpl_verify=="2")
        {
            var bpl_verify = $("#bpl_verify").val();
            if(bpl_verify=="")
            {
                $('#bpl_verify').css('border-color','red');
                proceed = false;
            }
        }
        return proceed;
    });
    $("#btn_approve_submit").click(function(){
        var proceed = true;

        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        $('#saf_receive_table').find('.app_img_verify').each(function(){
            $(this).css('border-color','');
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

        });
        $('#saf_receive_table').find('.app_doc_verify').each(function(){
            $(this).css('border-color','');
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

        });

        var pr_verify = $("#pr_verify").val();
        if(pr_verify=="")
        {
            $('#pr_verify').css('border-color','red');
            proceed = false;
        }

        var ap_verify = $("#ap_verify").val();
        if(ap_verify=="")
        {
            $('#ap_verify').css('border-color','red');
            proceed = false;
        }



        var cf_verify = $("#cf_verify").val();
        if(cf_verify=="")
        {
            $('#cf_verify').css('border-color','red');
            proceed = false;
        }

        var ed_verify = $("#ed_verify").val();
        if(ed_verify=="")
        {
            $('#ed_verify').css('border-color','red');
            proceed = false;
        }



        var mb_verify = $("#mb_verify").val();
        if(mb_verify=="")
        {
            $('#mb_verify').css('border-color','red');
            proceed = false;
        }

        var bpl_verify = $("#bpl_verify").val();
        if(bpl_verify=="")
        {
            $('#bpl_verify').css('border-color','red');
            proceed = false;
        }

        return proceed;
    });
});
</script>