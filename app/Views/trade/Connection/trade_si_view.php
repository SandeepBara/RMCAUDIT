<?php
//print_var($doc_exists);
?>

<?=$this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
</style>
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
                    <li class="active">Trade Site Inspection List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading"  style="background-color:#39a9b0;">
							<div class="panel-control">
								<a href="<?php echo base_url('Trade_SI/index') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Detail of Applied Form For Municipal License for <b style="color:#ffffff;font-size: 20px;"><?php echo $ulb_dtl['ulb_name']; ?></b></h3>
						</div>
						<div class="panel-body">
							<form method="post" class="form-horizontal" action="">
								<div class="panel panel-bordered panel-dark">
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-4">
												<h2><b>Application No. :</b></h2>
											</div>
											<div class="col-sm-8">
												<h2><b style="color:#39a9b0;"><?php echo $basic_details['application_no']; ?></b></h2>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading"  style="background-color:#e45b1a;">
										<h3 class="panel-title">Send Message To Applicant For Site Inspection</h3>
									</div>
									<div class="panel-body">
										<div class="row">
                                        <?php if($site_inspection_id!=""):?> 
                                            <div class="col-sm-6">
												<h5><b style="color:#92c755;">Message has been sent to applicant for site inspection on <span style="color:#2f7d2e;"><?=$forward_date?></span> at <span style="color:#2f7d2e;"><?=$forward_time?></span></b></h5>
											</div>
                                            <?php else: ?>
                                                <div class="col-sm-4">
												<h4><b>Inspection Date Time :</b></h4>
											</div>
											<div class="col-sm-4">
												<input type="datetime-local" id="site_date" name="site_date" class="form-control" placeholder="Site Inspection Date & Time" value="<?=(isset($site_date))?$site_date:date('Y-m-d\TH:i');?>" min="<?=date('Y-m-d\TH:i');?>">
											    <span style="color:red;"> <?php echo (isset($error))?$error:"" ?></span>
                                            </div>
                                            <?php endif;?>
											<div class="col-sm-4">
												<?php if($site_inspection_id!=""){?> 
														<input type='submit' value="Cancel Message" class="btn btn-success" id="btn_cancel_message" name='btn_cancel_message' onclick="return confirm('Are you sure you want to cancel Message?')"/>
												<?php }else{?>
													<button class="btn btn-success" id="btn_send_message" name="btn_send_message" type="submit">Send Message</button>
                                                    
												<?php }
												?>
											</div>
										</div>
                                        
                                       
                                         <hr>
										 <div class="row">
											<div class="col-sm-2">
												<h4><b>Ward No. :</b></h4>
											</div>
											<div class="col-sm-2">
												<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
													<?php foreach($wardList as $value):?>
													<option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?></option>
													<?php endforeach;?> 
												</select> 
											</div>
											<div class="col-sm-3">
												<h4><b>Total Area(in Sq. Ft) :</b></h4>
											</div>
											<div class="col-sm-2">
												<input type="" id="area_in_sqrt" name="area_in_sqrt" class="form-control" value="<?php echo isset($basic_details["area_in_sqft"]) ? $basic_details["area_in_sqft"] : ""; ?>" onkeypress="return isNumDot(event);">
											</div>
											<div class="col-sm-2">
												<input type='submit' value="Update Ward" class="btn btn-info" id="btn_update_ward" name='btn_update_ward' onclick="return confirm('Are You Sure You Want To Update Ward?')"/>
											</div>
											<input type="hidden" name="apply_licence_id" value="<?=md5($basic_details['id']);?>"/>
											<input type="hidden" name="view_trade_level_pending_id" value="<?=$view_trade_level_pending_id;?>"/>
										</div>
									</div>
								</div>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Basic Details</h3>
									</div>
									
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-3">
												<b>Nature Of Business :</b>
											</div>
											<div class="col-sm-3">
												<?=$nature_business['trade_item']?$nature_business['trade_item']:"N/A"; ?>
											</div>
											<div class="col-sm-3">
												<b>Application Type :</b>
											</div>
											<div class="col-sm-3">
												<?=$basic_details['application_type']?$basic_details['application_type']:"N/A"; ?>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<b>Holding No. :</b>
											</div>
											<div class="col-sm-3">
												<?=$holding['holding_no']?$holding['holding_no']:"N/A"; ?>
											</div>
											<div class="col-sm-3">
												<b>Firm Type :</b>
											</div>
											<div class="col-sm-3">
												<?=$basic_details['firm_type']?$basic_details['firm_type']:"N/A"; ?>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<b>Applied Date :</b>
											</div>
											<div class="col-sm-3">
												<?=$holding['apply_date']?$holding['apply_date']:"N/A"; ?>
											</div>
											<div class="col-sm-3">
												<b>Establishment Date :</b>
											</div>
											<div class="col-sm-3">
												<?=$holding['establishment_date']?$holding['establishment_date']:"N/A"; ?>
											</div>
										</div>  
										<div class="row">
											<div class="col-sm-3">
												<b>Ownership Type :</b>
											</div>
											<div class="col-sm-3">
												<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
											</div>
											<div class="col-sm-3">
												<b>Firm Name :</b>
											</div>
											<div class="col-sm-3">
												<?=$basic_details['firm_name']?$basic_details['firm_name']:"N/A"; ?>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-3">
												<b>Licence Apply For :</b>
											</div>
											<div class="col-sm-3">
												<?=$holding['licence_for_years']?$holding['licence_for_years']." Years":"N/A"; ?>
											</div>
											<div class="col-sm-3">
												<b>Account No :</b>
											</div>
											<div class="col-sm-3">
												<?=$basic_details['account_no']?$basic_details['account_no']:"N/A"; ?>
											</div>
										</div> 
										<div class="row">
											<div class="col-sm-3">
												<b>K No :</b>
											</div>
											<div class="col-sm-3">
												<?=$basic_details['k_no']?$basic_details['k_no']:"N/A"; ?>
											</div>
											<div class="col-sm-3">
												<b>Area sqft:</b>
											</div>
											<div class="col-sm-3">
												<!-- <input type="text" name="area_in_sqft_temp" id="area_in_sqft_temp" value="<?=(isset($basic_details['area_in_sqft']))?$basic_details['area_in_sqft']:"";?>" onkeypress="return isNum(event);"> -->
												<?=$basic_details['area_in_sqft']?$basic_details['area_in_sqft']:"N/A"; ?>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<b>Address :</b>
											</div>
											<div class="col-sm-3">
												<?=$holding['address']?$holding['address']:"N/A"; ?>
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
											<thead class="thead-light" style="background-color: #e6e6e4;">
												<tr>
													<th scope="col">Owner Name</th>
													<th scope="col">Guardian Name</th>
													<th scope="col">Mobile No</th>
													<th scope="col">Email Id</th>
 												</tr>
											</thead>
											<tbody>
												<?php if($owner_details==""){ ?>
													<tr>
														<td style="text-align:center;"> Data Not Available...</td>
													</tr>
												<?php }else{ ?>
												<?php foreach($owner_details as $value): ?>
													<tr>
														<td><?=$value['owner_name']?$value['owner_name']:"N/A"; ?></td>
														<td><?=$value['guardian_name']?$value['guardian_name']:"N/A"; ?></td>
														<td><?=$value['mobile']?$value['mobile']:"N/A"; ?></td>
														<td><?=$value['emailid']?$value['emailid']:"N/A"; ?></td>
 													</tr>
												<?php endforeach; ?>	
                                    		
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</form>
								 
                                            <!--------prop doc------------>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Document Details</h3>

									</div>
									<div class="panel-body" style="padding-bottom: 0px;">
										<div class="table-responsive">
											<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
												<thead class="thead-light" style="background-color: #e6e6e4;">
													<tr>
                                                        <th>#</th>
                                                        <th>Document Name</th>
                                                        <th>Document Image</th>
                                                        <?php 
                                                        if($site_inspection_id!="")
                                                        {
                                                            ?> 
                                                            <th>Verify/Reject</th>
                                                            <?php
                                                        }
                                                        ?>
													</tr>
												</thead>
											    </tbody>
											<?php 
                                    $cnt=0;
                                    foreach ($doc_exists as  $value)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=++$cnt;?></td>
                                            <td><?=$value['doc_for'];?></td>
                                            <td>
                                                <a href="<?=base_url();?>/getImageLink.php?path=<?=$value['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                            </td>
                                            <?php 
                                            if($site_inspection_id!="")
                                            {
                                                ?>
                                                <td>
                                                    <form method="POST">
                                                    <?php
                                                    if(!isset($value['verify_status']['verify_status']) || $value['verify_status']['verify_status']=='')
                                                    {
                                                        ?>					
                                                        <input type="hidden" name="app_doc_id<?=$cnt?>" value="<?=$value['id'];?>">
                                                        <button type="submit" name="btn_verify" value="<?=$cnt?>" class="btn btn-success btn-rounded btn-labeled">
                                                            <i class="btn-label fa fa-check"></i>
                                                            <span> Verify </span>
                                                        </button>
                                                        <a  class="btn btn-danger btn-rounded btn-labeled" role="button" data-toggle="modal" data-target="#rejectRemarks<?=$cnt?>">
                                                            <i class="btn-label fa fa-close"></i>
                                                            <span> Reject </span>
                                                        </a>
                                                        <br/>
                                                        <?php 
                                                    }
                                                    else if($value['verify_status']['verify_status']==1)
                                                    {
                                                        echo "<span class='text-success'> Verified </span>";
                                                        
                                                    }
                                                    else if($value['verify_status']['verify_status']==2)
                                                    {
                                                        echo "<span class='text-danger' title='".$value['verify_status']['remarks']."'> Rejected </span>";
                                                    }
                                                    ?>
                                                        <div class="modal fade" id="rejectRemarks<?=$cnt?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    
                                                                    <div class="modal-header">
                                                                    <h4 class="modal-title">Mention Reason For Document Rejection - <?=$value['doc_for'];?></h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                
                                                                    <div class="modal-body">
                                                                        <textarea type="text" class="form-control"  id="rejectedremarks<?=$cnt?>"  placeholder="Mention Remarks Here"  name="rejectedremarks<?=$cnt?>" onkeypress="return isAlphaNum(event);"></textarea>
                                                                    </div>
                                                                
                                                                    <div class="modal-footer">
                                                                    <button type="submit" name="btn_reject" value="<?=$cnt;?>"  class="btn btn-primary">Reject</button>
                                                                    </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                <?php 
                                            }
                                            ?>
                                        </tr>											
                                        <?php 
                                    }
                                    ?>	
										</tbody>
                                        </table>			
									    </div>
									</div>
								</div>
                                <!------------>
								
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Payment Detail</h3>
									</div>
									<div class="panel-body">
										<?php
										if(empty($payment_dtls))
										{ 
											?>
												<div class="row">
													<div class="col-sm-8 col-sm-offset-2">
														<h4><b style="color:red;">No Payments Are Available...</b></h4>
													</div>
												</div>
											<?php 
										} 
										else 
										{
											?>
											<table class="table table-responsive">
												<thead>
													<tr>
														<th>Processing Fee :</th>
														<th>Transaction Date :</th>
														<th>Payment Through :</th>
														<th>Payment For :</th>
														<th>View</th>
													</tr>
												</thead>
												<tbody>
												<?php
													foreach($payment_dtls as $val)
													{
														?>
															<tr>
																<td><?=$val['paid_amount'] ? $val['paid_amount'] : "N/A";?></td>
																<td><?=$val['transaction_date'] ? $val['transaction_date'] : "N/A";?></td>
																<td><?=$val['payment_mode'] ? $val['payment_mode'] : "N/A";?></td>
																<td><?=$val['transaction_type'] ? $val['transaction_type'] : "N/A";?></td>
																<td>
																	<a onClick="myPopup('<?= base_url('tradeapplylicence/viewTransactionReceipt/' .md5($val['id']));?>','xtf','900','700');" class="btn btn-primary">
																		View
																	</a>
																<!-- <a target="popup" onclick="window.open('<?php echo base_url('tradeapplylicence/viewTransactionReceipt/' .md5($val['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('tradeapplylicence/viewTransactionReceipt/' .md5($val['id'])); ?>" type="button" class="btn btn-primary" style="color:white;">View</a> -->
																</td>
															</tr>
														<?php
													}

												?>
												</tbody>
											</table>
											
                                        	<?php 
										}  
										?>
										
									</div>
								</div>
								
								
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h3 class="panel-title">Remarks From Level</h3>
									</div>
									<div class="panel-body">
                            <ul class="nav nav-tabs"  style="font-size:13px;font-weight: bold;">
                              <li class="active" style="background-color:#97c78ebd;"><a data-toggle="tab" href="#Dealing_Officer">Dealing Officer </a></li>
                              <li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#tax_daroga">Tax Daroga</a></li>
                              <li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#Section_Head">Section Head</a></li>
                              <li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#executive_officer">Executive Officer</a></li>
                            </ul>
                            <div class="tab-content">
                              <div id="Dealing_Officer" class="tab-pane fade in active">
                                <h3></h3>
                                <?php
                                if(isset($dealingLevel)):
                                    foreach ($dealingLevel as $value):
                                ?>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-body">
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Received Date</b>
											</div>
											<div class="col-sm-3">                                       
													<b style="font-size: 15px;">: <?=$value['created_on']!=""?date('d-m-Y H:i:s',strtotime($value['created_on'])):"N/A";?></b>
													<br/>                                            
											</div>
											 <div class="col-sm-3">
												<b style="font-size: 15px;;">Forwarded Date</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['forward_date']!=""?$value['forward_date'].' '.$value['forward_time']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>   								
										<br>
										<br>
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Remarks</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['remarks']!=""?$value['remarks']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>
									</div>
								</div>
                                <?php endforeach;?>
                                        <?php endif;  ?>
                              </div>
                              <div id="tax_daroga" class="tab-pane fade">
                               <h3></h3>
                               <?php
								if(isset($taxDarogaLevel)):
									foreach ($taxDarogaLevel as $value):
								?>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-body">
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Received Date</b>
											</div>
											<div class="col-sm-3">                                       
													<b style="font-size: 15px;">: <?=$value['created_on']!=""?date('d-m-Y H:i:s',strtotime($value['created_on'])):"N/A";?></b>
													<br/>                                            
											</div>
											 <div class="col-sm-3">
												<b style="font-size: 15px;;">Forwarded Date</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['forward_date']!=""?$value['forward_date'].' '.$value['forward_time']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>                                
										<br/>
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Remarks</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['remarks']!=""?$value['remarks']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>
									</div>
								</div>
                                <?php endforeach;?>
                                        <?php endif;  ?>
                              </div>
                              <div id="Section_Head" class="tab-pane fade">
                                <h3></h3>
                                <?php
								if(isset($sectionHeadLevel)):
									foreach ($sectionHeadLevel as $value):
								?>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-body">
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Received Date</b>
											</div>
											<div class="col-sm-3">                                       
													<b style="font-size: 15px;">: <?=$value['created_on']!=""?date('d-m-Y H:i:s',strtotime($value['created_on'])):"N/A";?></b>
													<br/>                                            
											</div>
											 <div class="col-sm-3">
												<b style="font-size: 15px;;">Forwarded Date</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['forward_date']!=""?$value['forward_date'].' '.$value['forward_time']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>                                
										<br/>
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Remarks</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['remarks']!=""?$value['remarks']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>
									</div>
								</div>
                                <?php endforeach;?>
                                        <?php endif;  ?>
                              </div>
                              <div id="executive_officer" class="tab-pane fade">
                                <h3></h3>
                                <?php
								if(isset($executiveLevel)):
									foreach ($executiveLevel as $value):
								?>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-body">
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Received Date</b>
											</div>
											<div class="col-sm-3">                                       
													<b style="font-size: 15px;">: <?=$value['created_on']!=""?date('d-m-Y H:i:s',strtotime($value['created_on'])):"N/A";?></b>
													<br/>                                            
											</div>
											 <div class="col-sm-3">
												<b style="font-size: 15px;;">Forwarded Date</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['forward_date']!=""?$value['forward_date'].' '.$value['forward_time']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>                                
										<br/>
										<div class="col-sm-12">
											<div class="col-sm-3">
												<b style="font-size: 15px;">Remarks</b>
											</div>
											<div class="col-sm-3">                                        
													<b style="font-size: 15px;">: <?=$value['remarks']!=""?$value['remarks']:"N/A";?></b>
													<br/>                                            
											</div>
										</div>
									</div>
								</div>
                                <?php endforeach;?>
                                        <?php endif;  ?>
                              </div>
                            </div>
                        </div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-bordered panel-dark">
											<div class="panel-body" style="padding-bottom: 0px;">
                                                <form method="POST">
												<div class="form-group">
													<label class="col-md-2" >Remarks</label>
													<div class="col-md-10">
                                                    <input type="hidden" name="apply_licence_id" id="apply_licence_id" value="<?=$apply_licence_id;?>"/>
                                                        <input type="hidden" name="area_in_sqft" id="area_in_sqft" value="<?=(isset($basic_details['area_in_sqft']))?$basic_details['area_in_sqft']:"";?>">
														<textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" onkeypress="return isAlphaNum(event);" required></textarea>
													</div>
												</div>
                                                <?php 
                                                if($site_inspection_id!="")
                                                {
                                                    $EveryDocumentVerified='Yes';
                                                    foreach($doc_exists as $value)
                                                    {
                                                        if(!isset($value['verify_status']['verify_status']) || $value['verify_status']['verify_status']=='' || $value['verify_status']['verify_status']==2)
                                                        {
                                                            $EveryDocumentVerified='No';
                                                            break;
                                                        }
                                                    }	

                                                    if($EveryDocumentVerified=='Yes' && $basic_details["is_fild_verification_charge"]!="t")
													{														
														?>
															<div class="form-group">
																<!-- <label class="col-md-2" >&nbsp;</label> -->
																<div class="col-md-2">
																	<button style="margin-bottom: 16px ;margin-top: 16px;" class="btn btn-success" id="btn_verify_submit" name="btn_forward_submit" type="submit" value="Verify & Forward">Verify & Forward</button>
																</div>
															</div>
														<?php
													}elseif($basic_details["is_fild_verification_charge"]=="t"){
														?>
														<div class="form-group">
															<div class="col-md-2">
																<button type="button" style="margin-bottom: 16px ;margin-top: 16px;" class="btn btn-success" >Clear Payment First </button>
															</div>
														</div>
														<?php
													}
                                                    // else
                                                    // {
                                                        ?>
                                                        <?php 
                                                    // }
												}
                                                ?>
                                                	<div class="form-group">
                                                        <!-- <label class="col-md-2" >&nbsp;</label> -->
                                                        <div class="col-md-2">                                            
                                                        	<button style="margin-bottom: 16px ;margin-top: 16px;" class="btn btn-danger" id="btn_backward" name="btn_backward" type="submit">Backward</button>
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
				</div>
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->


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


  <div id="holding_owner_details-lg-modal" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" action="<?=base_url('');?>/Trade_SI/viewDetails">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Site Inspection</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group"> 
                        <input type="hidden" name="apply_licence_id" id="apply_licence_id" value="<?=$apply_licence_id;?>"/>
                        <div class="col-md-4">
                            <label class="control-label" for="from_date"><b>Site Inspection Date</b> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-5">
                            <input type="datetime-local" id="site_date" name="site_date" class="form-control" placeholder="Site Inspection Date & Time" value="<?=(isset($site_date))?$site_date:date('Y-m-d');?>" min="<?=date('Y-m-d');?>">
                        </div>
                        <div class="col-md-1">
                            <label class="control-label" for="cancel">&nbsp;</label>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" id="btn_site_inspection" name="btn_site_inspection" type="submit">Proceed</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="submit" name="proceed" id="proceed" class="btn btn-primary" value="proceed">Proceed</button>
                </div> -->
            </form>
        </div>
    </div>
</div>
////modal end
<?= $this->include('layout_vertical/footer');?>
<script>
$(function() {
        $('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
            $('#imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');   
        });     

});
$(function() {
    $('.pop').on('click', function() {
        $('#site_inspection').modal('show');   
    });     

});


$("#area_in_sqft_temp").change(function()
{
    console.log(this.value);
    $("#area_in_sqft").val(this.value );
});

</script>
<script>

    $("#btn_verify_submit").click(function(){
        var proceed = true;
        var regX = /^[A-Za-z]+$/;
        var remarks = $("#remarks").val();
        if(remarks==""){
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        return proceed;
    });
    $('#btn_send_message').click(function(){
        var site_date = $("#site_date").val().trim();
         if(site_date==""){
            $('#site_date').css('border-color','red');
            return false;
        }
        var site_data_split = site_date.split("T");
         
        alert(hours);
        return false;
         
    });

    
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
    if($licence=flashToast('licence'))
    {
        echo "modelInfo('".$licence."');";
    }
?>
</script>

<script type="text/javascript">
	function myPopup(myURL, title, myWidth, myHeight)
	{
		var left = (screen.width - myWidth) / 2;
		var top = (screen.height - myHeight) / 4;
		var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
	}
</script>