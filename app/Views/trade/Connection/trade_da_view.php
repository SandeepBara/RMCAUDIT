<?= $this->include('layout_vertical/header');?>
<?php $display=''; ?>
<!--DataTables [ OPTIONAL ]-->

<style>
.row{line-height:25px;}
.error{
    color: red;
}
</style>

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
					<li><a href="#">Trade</a></li>
					<li class="active">Trade DA List</li>
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
								<a href="<?php echo base_url('trade_da/index') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>

						<div class="panel-body">
                            <span style="color: red"><?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?></span>
							<div class="row">
								<div class="col-sm-3">
                                    <b>Ward No. :</b>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $ward['ward_no']; ?>
                                </div>
                               
                                <input type="hidden" name="apply_licence_id" value="<?=md5($basic_details['id']);?>"/>
								<div class="col-sm-3">
									<b>Holding No. :</b>
								</div>
								<div class="col-sm-3">
									  <!-- <u><i> <a class="bg-light text-info  border-none " onclick="openPropertyDetails1234(<?= $basic_details['prop_dtl_id'] ?>)"><?= $holding['holding_no'] ?></a></i></u> -->
                                      <u><i> <a class="bg-light text-info  border-none "target="_blank" href="<?=isset($PropSafLink)?$PropSafLink:"#";?>"><?= $holding['holding_no'] ?></a></i></u>
								</div>
								
							</div>
							<div class="row">
								<div class="col-sm-3">
									<b>Application No. :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['application_no']?$basic_details['application_no']:"N/A"; ?>
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
									<b>Licence For :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['licence_for_years']?$holding['licence_for_years']."  Years":"N/A"; ?>
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
									<b>Nature Of Business :</b>
								</div>
								<div class="col-sm-3">
									<?php echo "( ". $holding['nature_of_bussiness']." ) "; ?>
									 <?= $nature_business['trade_item']?$nature_business['trade_item']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Cateogry Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$category_type['category_type']?$category_type['category_type']:"N/A"; ?>
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
									<b>Area :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['area_in_sqft']?$basic_details['area_in_sqft']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<b>Account No :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['account_no']?$basic_details['account_no']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Firm Establishment Date :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['establishment_date']?date('d-m-Y',strtotime($holding['establishment_date'])):"N/A"; ?>
								</div>												
							</div>
							<div class="row">
								<div class="col-sm-3">
									<b>Address :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['address']?$holding['address']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Landmark :</b>
								</div>
								<div class="col-sm-3">
									<?=$holding['landmark']?$holding['landmark']:"N/A"; ?>
								</div>												
							</div>
						</div>
					</div>
					
                        <!-------Owner Details-------->
						<div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Owner Details</h3>
                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="saf_receive_table" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: #e6e6e4;">
                                            <tr>
                                                <th scope="col">Owner Name</th>
												<th scope="col">Guardian Name</th>
												<th scope="col">Mobile No</th>
												<th scope="col">Email Id</th>
												<th scope="col">Address</th>
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
													<td><?=$value['owner_name']?$value['owner_name']:"N/A"; ?></td>
													<td><?=$value['guardian_name']?$value['guardian_name']:"N/A"; ?></td>
													<td><?=$value['mobile']?$value['mobile']:"N/A"; ?></td>
													<td><?=$value['emailid']?$value['emailid']:"N/A"; ?></td>
													<td><?=$value['address']?$value['address']:"N/A"; ?></td>
                                                   
                                                </tr>                                                
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </tbody>
					                </table>
                                </div>
                            </div>
                        </div>

                       
                            <!--------prop doc------------>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Document Details</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-hovered table-responsive table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: #e6e6e4;">
                                            <tr>
                                                <th>#</th>
                                                <th>Document Name </th>
                                                <th>Document Image</th>
                                                <th>Verfication Status</th>
                                                <th>Verify/Reject</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    
                                            <?php 
                                            $cnt=0;
                                            $verifystatus=0;
                                            $rejectedstatus=0;
                                            //print_var($documents);
                                            foreach ($documents as  $value)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$cnt;?></td>
                                                    <td><?=$value['doc_for'];?></td>
                                                    <td>
                                                        <a href="<?=base_url();?>/getImageLink.php?path=<?=$value['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                    </td>
                                                    <td>
                                                    	<?php 

                                                    	if($value['verify_status']==1)
                                                        {
                                                            $verifystatus=1;
                                                            echo "<span class='text-success'>Verified</span>";
                                                        }
                                                        else if($value['verify_status']==2)
                                                        {
                                                            $rejectedstatus=1;
                                                            echo '<span class="text-danger" title="'.$value['remarks'].'">Rejected</span>';
                                                        }else{
                                                        	 $rejectedstatus=0;
                                                            echo '<span class="text-danger" title="'.$value['remarks'].'">Pending</span>';
                                                        }

                                                    	 ?>
                                                    </td>
                                                    <td style="width:220px;">
                                                        <?php
                                                        // if($value['verify_status']=="0")
                                                        // {   $display='disabled';
                                                            ?>
                                                            <form method="POST">
                                                                <input type="hidden" name="app_doc_id" value="<?=$value['id'];?>">
                                                                <input type="hidden" value="<?=$form['id']?>" name="level_pending_id">
                                                                <input type="hidden" value="Verified" name="rejectedremarks">
                                                                <button  type="submit" name="btn_verify" value="Verify" class="btn btn-success btn-rounded btn-labeled">
                                                                    <i class="btn-label fa fa-check"></i>
                                                                    <span> Verify </span>
                                                                </button>

                                                                <a  class="btn btn-danger btn-rounded btn-labeled" role="button" data-toggle="modal" data-target="#rejectRemarks<?=$cnt?>">
                                                                    <i class="btn-label fa fa-close"></i>
                                                                    <span> Reject </span>
                                                                </a>
                                                            </form>
                                                            
                                                            <br/>
                                                            <?php 
                                                        // }
                                                        // else if($value['verify_status']==1)
                                                        // {
                                                        //     $verifystatus=1;
                                                        //     echo "<span class='text-success'>Verified</span>";
                                                        // }
                                                        // else if($value['verify_status']==2)
                                                        // {
                                                        //     $rejectedstatus=1;
                                                        //     echo '<span class="text-danger" title="'.$value['remarks'].'">Rejected</span>';
                                                        // }
                                                        ?>

                                                        <div class="modal fade" id="rejectRemarks<?=$cnt?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    
                                                                    <div class="modal-header">
                                                                    <h4 class="modal-title"> Mention Reason For Document Rejection - <?=$value['doc_for'];?> </h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                
                                                                    
                                                                    <form method="POST" name="">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="app_doc_id" value="<?=$value['id'];?>">
                                                                            <input type="hidden" value="<?=$form['id']?>" name="level_pending_id">
                                                                            <textarea type="text" name="rejectedremarks" id="rejectedremarks" class="form-control" placeholder="Mention Remarks Here" onkeypress="return isAlphaNum(event);"></textarea>
                                                                        </div>
                                                                    
                                                                    
                                                                        <div class="modal-footer">
                                                                        <input type="submit" name="btn_reject" value="Reject" class="btn btn-primary" />
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>											
                                                <?php 
                                            }
                                            ?>
                                        </tbody>                                        
                                        </table>
                                </div>
							</div>
						</div>

                        
						
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Payment Detail</h3>
							</div>
							<div class="panel-body">
								<!-- <div class="row">
									<div class="col-sm-3">
										<b>Processing Fee :</b>
									</div>
									<div class="col-sm-3">
										<?php //echo $payment_dtls['paid_amount']?$payment_dtls['paid_amount']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Transaction Date :</b>
									</div>
									<div class="col-sm-3">
										<?php //echo $payment_dtls['transaction_date']?$payment_dtls['transaction_date']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<b>Payment Through :</b>
									</div>
									<div class="col-sm-3">
										<?php //echo $payment_dtls['payment_mode']?$payment_dtls['payment_mode']:"N/A"; ?>
									</div>
									
								</div> -->
								<?php 
                                if(isset($payment_dtls['payment_mode'])&&$payment_dtls['payment_mode']=="CHEQUE" || isset($payment_dtls['payment_mode'])&&$payment_dtls['payment_mode']=="DD")
                                { 
                                    ?>
									<!-- <div class="row">
										<div class="col-sm-3">
											<b>Cheque No :</b>
										</div>
										<div class="col-sm-3">
											<?php //echo $payment_dtls['paid_amount']?$payment_dtls['paid_amount']:"N/A"; ?>
										</div>
										<div class="col-sm-3">
											<b>Cheque Date :</b>
										</div>
										<div class="col-sm-3">
											<?php //echo $payment_dtls['transaction_date']?$payment_dtls['transaction_date']:"N/A"; ?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<b>Bank Name :</b>
										</div>
										<div class="col-sm-3">
											<?php //echo $payment_dtls['payment_mode']?$payment_dtls['payment_mode']:"N/A"; ?>
										</div>
										<div class="col-sm-3">
											<b>Branch Name :</b>
										</div>
										<div class="col-sm-3">
											<?php //echo $payment_dtls['address'] ?? "N/A"; ?>
										</div>
									</div> -->
								    <?php 
                                } 
                                ?>
									<!-- <div class="row">
										&nbsp;
									</div>
									<div class="row">
										<a href="<?php //echo base_url('tradeapplylicence/view_transaction_receipt/'.md5($payment_dtls['related_id']).'/'.md5($payment_dtls['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View Payment Receipt</a>
									</div> -->

                                    <div class="row">
                                        <div class="col-md-12 table-responsive">
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
                                                    if(empty($payment_dtls))
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td colspan="5" class="text-danger text-center">! No Data</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    else
                                                    {  
                                                        foreach($payment_dtls as $val)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td><?=$val['paid_amount'] ? $val['paid_amount'] : "N/A";?></td>
                                                                <td><?=$val['transaction_date'] ? $val['transaction_date'] : "N/A";?></td>
                                                                <td><?=$val['payment_mode'] ? $val['payment_mode'] : "N/A";?></td>
                                                                <td><?=$val['transaction_type'] ? $val['transaction_type'] : "N/A";?></td>
                                                                <td>
                                                                <a target="popup" onclick="window.open('<?php echo base_url('tradeapplylicence/viewTransactionReceipt/' .md5($val['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('tradeapplylicence/viewTransactionReceipt/' . md5($val['id'])); ?>" type="button" class="btn btn-primary" style="color:white;">View</a>
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
						
					<!------------>
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
													<b style="font-size: 15px;">: <?=$value['created_on']!=""?date('Y-m-d',strtotime($value['created_on'])):"N/A";?></b>
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
						<div class="panel panel-bordered panel-dark">
							<div class="panel-body" style="padding-bottom: 0px;">
                            <form method="POST" style="margin-top: 10px;" id="forward_backward">
								 <div class="form-group">
									<label class="col-md-2" >Remarks</label>
									 <div class="col-md-10">
                                        <input type="hidden" value="<?=$form['id']?>" name="level_pending_id">
										<textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" onkeypress="return isAlphaNum(event);"></textarea>
									 </div>
								</div>
                                
                                <div class="form-group">
                                    <label class="col-md-2" >&nbsp;</label>
                                    <div class="col-md-10" style="margin-top: 15px; margin-bottom: 15px;">
                                    
                                    <?php
                                		# Checking is there any rejected documents  -- (If rejected docs found then cant approve/forward)
										$rejectedDocFound=1;
										foreach($documents as $docs)
										{
											//check if document is rejected
											if($docs['verify_status']==2)
											{

												$rejectedDocFound=1;
                                        		break;
											}
											//check if document is pending
											elseif($docs['verify_status']==0){
												$rejectedDocFound=0;
												break;
											}
											//check for other than the given option
											else{
												$rejectedDocFound=2;
											}
										}

										// echo $rejectedDocFound;
										if($rejectedDocFound==1 || $rejectedDocFound==0)
										{	?>
											<button class="btn btn-danger" id="btn_app_submit" name="btn_app_submit" type="submit">Back To Citizen</button>

									<?php }
										else
										{ ?>
											<button class="btn btn-success" id="btn_approve_submit" name="btn_approve_submit" type="submit" required <?=$display?>>Approve & Forward</button>

									<?php } ?> 
                                    </div>
                            </div>
                            </form>
						</div>
                     </div>
                
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->


<!-- The Modal -->
	
  
  
  
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
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>

     $(document).ready(function(){
        $('form[name="formname"]').validate({
            rules:{
                rejectedremarks:{
                    required:true
                }

            },
            messages:{
                rejectedremarks:{
                    required:"Please Enter Remarks"
                }               
            }
        });

        $('#forward_backward').validate({
            rules:{
                remarks:{
                    required : true,
                }

            },
            messages:{
                remarks:{
                    required : "Please Enter Remarks",
                }               
            }
        });

    });

    
    /*function app_img_remarks_details(il)
    {
     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_img_verify_status =$('#applicant_img_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
        //alert(app_img_verify);
     if(app_img_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_img_verify_status==1){
                   $("#applicant_img_verify_status"+il).val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }+

            $("#app_img_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(app_img_verify=="1")
        {
            if(applicant_img_verify_status==0){
                    $("#applicant_img_verify_status"+il).val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#app_img_remarks"+il).hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
                
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
            $("#btn_update_ward").css("display","block");
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
                $("#btn_update_ward").css("display","none");
            }
        }
    }
$(document).ready(function(){
    $(".app_img_remarks").hide();
    $(".app_doc_remarks").hide();
    $("#bu_remarks").hide();
    $("#tan_remarks").hide();
	$("#pvt_remarks").hide();
    $("#noc_remarks").hide();
    $("#Par_remarks").hide();
    $("#sap_remarks").hide();
    $("#sol_remarks").hide();
	$("#ele_remarks").hide();
    $("#app_remarks").hide();

    $("#bu_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var bu_verify = $("#bu_verify").val();
        var bu_verify_status = $("#bu_verify_status").val();
        if(bu_verify=="2")
        {
            if(count_change_app>0){
                if(bu_verify_status==1){
                   $("#bu_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#bu_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(bu_verify=="1")
        {
            if(bu_verify_status==0){
                $("#bu_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#bu_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
    
	$("#noc_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var noc_verify = $("#noc_verify").val();
        var noc_verify_status = $("#noc_verify_status").val();
        if(noc_verify=="2")
        {
            if(count_change_app>0){
                if(noc_verify_status==1){
                   $("#noc_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#noc_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(noc_verify=="1")
        {
            if(noc_verify_status==0){
                $("#noc_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#noc_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	
	$("#pvt_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var pvt_verify = $("#pvt_verify").val();
        var pvt_verify_status = $("#pvt_verify_status").val();
        if(pvt_verify=="2")
        {
            if(count_change_app>0){
                if(pvt_verify_status==1){
                   $("#pvt_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#pvt_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(pvt_verify=="1")
        {
            if(pvt_verify_status==0){
                $("#pvt_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#pvt_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	
	$("#tan_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var tan_verify = $("#tan_verify").val();
        var tan_verify_status = $("#tan_verify_status").val();
        if(tan_verify=="2")
        {
            if(count_change_app>0){
                if(tan_verify_status==1){
                   $("#tan_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#tan_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(tan_verify=="1")
        {
            if(tan_verify_status==0){
                $("#tan_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#tan_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	
	$("#Par_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var Par_verify = $("#Par_verify").val();
        var Par_verify_status = $("#Par_verify_status").val();
        if(Par_verify=="2")
        {
            if(count_change_app>0){
                if(Par_verify_status==1){
                   $("#Par_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#Par_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(Par_verify=="1")
        {
            if(Par_verify_status==0){
                $("#Par_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#Par_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	$("#sap_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var sap_verify = $("#sap_verify").val();
        var sap_verify_status = $("#sap_verify_status").val();
        if(sap_verify=="2")
        {
            if(count_change_app>0){
                if(sap_verify_status==1){
                   $("#sap_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#sap_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(sap_verify=="1")
        {
            if(sap_verify_status==0){
                $("#sap_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#sap_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	$("#sol_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var sol_verify = $("#sol_verify").val();
        var sol_verify_status = $("#sol_verify_status").val();
        if(sol_verify=="2")
        {
            if(count_change_app>0){
                if(sol_verify_status==1){
                   $("#sol_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#sol_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(sol_verify=="1")
        {
            if(sol_verify_status==0){
                $("#sol_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#sol_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	$("#ele_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var ele_verify = $("#ele_verify").val();
        var ele_verify_status = $("#ele_verify_status").val();
        if(ele_verify=="2")
        {
            if(count_change_app>0){
                if(ele_verify_status==1){
                   $("#ele_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#ele_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(ele_verify=="1")
        {
            if(ele_verify_status==0){
                $("#ele_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#ele_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    });
	
	$("#app_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var app_verify = $("#app_verify").val();
        var app_verify_status = $("#app_verify_status").val();
        if(app_verify=="2")
        {
            if(count_change_app>0){
                if(app_verify_status==1){
                   $("#app_verify_status").val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#app_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(app_verify=="1")
        {
            if(app_verify_status==0){
                $("#app_verify_status").val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#app_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
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
        var rc_verify = $("#rc_verify").val();
        if(rc_verify=="")
        {
            $('#rc_verify').css('border-color','red');
            proceed = false;
        }
        if(rc_verify=="2")
        {
            var rc_remarks = $("#rc_remarks").val();
            if(rc_remarks=="")
            {
                $('#rc_remarks').css('border-color','red');
                proceed = false;
            }
        }

        var ad_verify = $("#ad_verify").val();
        if(ad_verify=="")
        {
            $('#ad_verify').css('border-color','red');
            proceed = false;
        }
        if(ad_verify=="2")
        {
            var ad_remarks = $("#ad_remarks").val();
            if(ad_remarks=="")
            {
                $('#ad_remarks').css('border-color','red');
                proceed = false;
            }
        }

        var fa_verify = $("#fa_verify").val();
        if(fa_verify=="")
        {
            $('#fa_verify').css('border-color','red');
            proceed = false;
        }
        if(fa_verify=="2")
        {
            var fa_remarks = $("#fa_remarks").val();
            if(fa_remarks=="")
            {
                $('#fa_remarks').css('border-color','red');
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

        var rc_verify = $("#rc_verify").val();
        if(rc_verify=="")
        {
            $('#rc_verify').css('border-color','red');
            proceed = false;
        }

        var ad_verify = $("#ad_verify").val();
        if(ad_verify=="")
        {
            $('#ad_verify').css('border-color','red');
            proceed = false;
        }        

        var fa_verify = $("#fa_verify").val();
        if(fa_verify=="")
        {
            $('#fa_verify').css('border-color','red');
            proceed = false;
        }       

        return proceed;
    });
});*/


    function openPropertyDetails(prop_dtl_id) {


        var prop_dtl = '<?= $basic_details['prop_dtl_id'] ?>';
        // alert(prop_dtl_id)
        window.open('<?= base_url()?>/propDtl/full/'+prop_dtl)
    }
   
    </script>