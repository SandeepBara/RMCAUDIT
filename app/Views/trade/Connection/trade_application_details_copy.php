<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->

<style>
    .row{line-height:25px;}
    
    /* The Modal (background) */
    .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
    position: relative;
    background-color: #fefefe;
    margin-top: -760px;
    margin-left: 238px;
    padding: 0;
    border: 1px solid #888;
    width: 80%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s;
    text-align: initial;
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
    }

    @keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
    }

    /* The Close Button */
    .close {
    color: black;
    float: right;
    font-size: 16px;
    margin-top:5px!important;
    }

    .close:hover,
    .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
    }

    .modal-header {
    padding: 2px 16px;
    color: white;
    }

    .modal-body {padding: 2px 16px;}

    .modal-footer {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
    }
    /* print  */
    @media print {
        #customer_view_detail;
        #print_watermark {
            background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
            background-repeat:no-repeat !important;
            background-position:center !important;
            -webkit-print-color-adjust: exact; 
        }
    }
    #print_watermark{
        background-color:#FFFFFF;
        background-image:url(<?=base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
        background-repeat:no-repeat;
        background-position:center;
        
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
					<li class="active">Trade Application Details</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <form method="post" class="form-horizontal" action="">
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Trade Application Status</h3>
                            </div>
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <span style="font-weight: bold; font-size: 17px; color: #bb4b0a;"> Your Application No. is <span style="color: #179a07;"><?php echo $basic_details['application_no'];?></span>. Application Status: <?php echo $application_status;?></span>
                                </div> 
                            </div>
                        </div>
                </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                        <!-- <a href="<?php echo base_url('trade_da/track_application_no') ?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</a> -->
                    </div>
                    <h3 class="panel-title">Basic Details</h3>
                </div>

                <div class="panel-body">
                    <span style="color: red">
                        <?php 
                            if(isset($validation))
                            { 
                                ?>
                                <?= $validation->listErrors(); ?>
                                <?php 
                            } 
                        ?>
                    </span>
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
                            <?=$holding['holding_no']?$holding['holding_no']:"N/A"; ?>
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
                            <?=$nature_business['trade_item']?$nature_business['trade_item']:"N/A"; ?>
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
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="thead-light" style="background-color: #e6e6e4;">
                                <tr>
                                    <th style="width:160px;">Document Name</th>
                                    <th style="width:160px;">Document Image</th>
                                    <th style="width:160px;">Status</th>
                                    

                                </tr>
                            </thead>
                        </table>
                        <?php 
                        $cnt=0;
                        $verifystatus=0;
                        $rejectedstatus=0;

                        foreach ($doc_exists as  $value) {
                        $cnt++;
                        ?>
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <tbody>
                                
                                    <tr>
                                        <td class="col-sm-4"><?=$value['doc_for'];?></td>
                                        <td>
                                            <a href="<?=base_url();?>/writable/uploads/<?=$value['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="height: 40px;"></a>
                                        </td>
                                        <td>
                                            <?php
                                            if($value['verify_status']=="0"){?>	
                                            <span class='text-warning'>Pending</span>
                                            <?php }
                                            else if($value['verify_status']==1){ ?>
                                                    <span class='text-success'>Verified</span>
                                            <?php }
                                            else if($value['verify_status']==2){ ?>
                                                <span class='text-danger'>Rejected</span>
                                                
                                            <?php }
                                            ?>
                                        </td>
                                        
                                    </tr>											
                                        
                                
                            </tbody>                                        
                        </table>
                        <div class="modal fade" id="rejectRemarks<?=$cnt?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    
                                    <div class="modal-header">
                                        <h4 class="modal-title">Mention Reason For Document Rejection</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                
                                    
                                    <div class="modal-body">
                                        <textarea type="text" class="form-control"  id="rejectedremarks<?=$cnt?>"  placeholder="Mention Remarks Here"  name="rejectedremarks<?=$cnt?>" ></textarea>
                                        
                                        
                                    </div>
                                
                                    
                                    <div class="modal-footer">
                                        <button type="submit" name="btn_reject" value="<?=$cnt;?>"  class="btn btn-primary">Done</button>
                                    </div>
                                
                                </div>
                            </div>
                        </div>

                    <?php }

                                ?>
                    </div>
                </div>
            </div>

                        
						
            <div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Payment Detail</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-3">
										<b>Processing Fee :</b>
									</div>
									<div class="col-sm-3">
										<?=$payment_dtls['paid_amount']?$payment_dtls['paid_amount']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Transaction Date :</b>
									</div>
									<div class="col-sm-3">
										<?=$payment_dtls['transaction_date']?$payment_dtls['transaction_date']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<b>Payment Through :</b>
									</div>
									<div class="col-sm-3">
										<?=$payment_dtls['payment_mode']?$payment_dtls['payment_mode']:"N/A"; ?>
									</div>
									
								</div>
								<?php if($payment_dtls['payment_mode']=="CHEQUE" || $payment_dtls['payment_mode']=="DD"){ ?>
									<div class="row">
										<div class="col-sm-3">
											<b>Cheque No :</b>
										</div>
										<div class="col-sm-3">
											<?=$payment_dtls['paid_amount']?$payment_dtls['paid_amount']:"N/A"; ?>
										</div>
										<div class="col-sm-3">
											<b>Cheque Date :</b>
										</div>
										<div class="col-sm-3">
											<?=$payment_dtls['transaction_date']?$payment_dtls['transaction_date']:"N/A"; ?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<b>Bank Name :</b>
										</div>
										<div class="col-sm-3">
											<?=$payment_dtls['payment_mode']?$payment_dtls['payment_mode']:"N/A"; ?>
										</div>
										<div class="col-sm-3">
											<b>Branch Name :</b>
										</div>
										<div class="col-sm-3">
											<?=$payment_dtls['address']?$payment_dtls['address']:"N/A"; ?>
										</div>
									</div>
								<?php } ?>
									<div class="row">
										&nbsp;
									</div>
									<div class="row"> 
                                        <?php if($basic_details['application_type_id']!=4){?>
                                         <a target="popup" onclick="window.open('<?php echo base_url('tradeapplylicence/viewTransactionReceipt/'.$linkId);?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('tradeapplylicence/viewTransactionReceipt/'.$linkId);?>" type="button"   class="btn btn-primary" style="color:white;">View Payment Receipt</a></td>
                                        <?php if($basic_details['pending_status']==5){?>
                                         <a  href="<?php echo base_url('Trade_DA/municipalLicence/'.$linkId);?>" 
                                        target="popup"  type="button" class="btn btn-primary" style="color:white;"
                                        onclick="window.open('<?php echo base_url('Trade_DA/municipalLicence/'.$linkId);?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;">
                                        View Trade Licence
                                        </a>
                                        <?php } else {?>
                                        <a  target="popup" onclick="window.open('<?php echo base_url('tradeapplylicence/provisionalCertificate/'.$linkId);?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('tradeapplylicence/provisionalCertificate/'.$linkId);?>" type="button"   class="btn btn-primary" style="color:white;">View Provisional Certificate</a>
                                        <?php } }?>
                                        <!-- The Modal -->
                                    <div id="myModal" class="modal">
                                    <!-- Modal content -->
                                    <div class="modal-content">
                                   <div class="modal-body">
                <div id="page-content">
                <a href="javascript:void(0);" onclick="printPageArea('print_payment_receipt')">Print</a>
                &nbsp;<span style="float:right;" class="close" onclick="clse_mdel()">x</span>
				<div class="row">
					<div class="col-sm-12">
						<div class="panel">
 							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">Payment Receipt	</h3>
								</div>
                                <div id="print_payment_receipt"> 
								<div class="panel-body" id="print_watermark">
									<div class="col-sm-1"></div>
									<div class="col-sm-10" style="text-align: center;">
										<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
									</div>
									<div class="col-sm-1 noprint text-right">
										<!-- <button class="btn btn-mint btn-icon" onclick="print()"><i class="demo-pli-printer icon-lg"></i></button> -->
									</div>
									<div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
									<?=$ulb_mstr_name["ulb_name"];?>
									</div>
									
									<div class="col-sm-12">
										<div class="col-sm-8">

										</div>
										<div class="">
										</div>
									</div>
									<table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

										<tbody>
											<tr>
												<td height="71" colspan="4" align="center">
													<div style="width: 60%; padding: 8px; height: auto; border: #000000 solid 2px; font-family: Arial, Helvetica, sans-serif; font-size: 18px; font-weight: bold">MUNICIPAL LICENSE PAYMENT RECEIPT </div>
												</td>
											</tr>
											<tr>
												<td colspan="3">Receipt No. : <b><?=$transaction_details["transaction_no"];?></b></td>
												<td >Date :<b><?=date('d-m-Y',strtotime($transaction_details["transaction_date"]));?></b></td>
											</tr>
											<tr>
												<td colspan="3">Department / Section : Municipal License Section<br>
                                            Account Description : Municipal License Fee Charges</td>
												<td>
													<div >Ward No :<b><?=$ward_no;?></b> </div>
													<div >Application No :<b><?=$applicant_details["application_no"];?></div>
												</td>
											</tr>
											
										</tbody>
									</table><br>
									<br>
									<table width="100%" border="0">
										<tbody>
											<tr>
												<td>Firm Name : 
													<?php //foreach($basic_details as $basic_details):?>
													<span style="font-size: 14px; font-weight: bold">
														<?=$applicant_details["firm_name"];?>
													</span>
													<?php //endforeach; ?>
												</td>
											</tr>
											<tr>
												<td>Received From Shri / Smt. : 
													<?php //foreach($basic_details as $basic_details):?>
													<span style="font-size: 14px; font-weight: bold">
														<?=$applicant_details["applicant_name"];?>
													</span>
													<?php //endforeach; ?>
												</td>
											</tr>
											<tr>
												<td>Mobile No. : 
													<span style="font-size: 14px; font-weight: bold">
														<?=$applicant_details["mobile_no"];?>
													</span>
												</td>
											</tr>
											<tr>
												<td>Address : 
													<span style="font-size: 14px; font-weight: bold">
														<?=$applicant_details["address"];?>
													</span>
												</td>
											</tr>
											<tr>
												<td>
													<div style="float: left;">A Sum of Rs. </div>
													<div style="width: 200px; height: 15px; line-height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold;">
														<?=$transaction_details["paid_amount"];?>
														
													&nbsp;
													</div><br>

													<div style="float: left;">(in words) </div>
													<div style="border-bottom: #333333 dotted 2px; width: 565px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-size: 14px; font-weight: bold; line-height: 18px;">
														&nbsp;
													 <?php echo ucwords(getIndianCurrency((float)$transaction_details["paid_amount"])); ?>
													&nbsp; Only
													</div>
												</td>
											</tr>
											<?php if($transaction_details["payment_mode"]=="CASH"){ ?>
											<tr>
												<td height="35">
													<div style="float: left;">
														towards :<strong style="font-size: 14px;">Municipal License Fee</strong>&nbsp;&nbsp; vide <b><?=$transaction_details["payment_mode"];?> </b> 
													</div>
											<?php } else { ?>
											<tr>
												<td height="35">
													<?php if($transaction_details["payment_mode"]=="CHEQUE"){ ?>
													<div style="float: left;">
														towards :<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; vide Cheque No
													</div>
													<?php } else{ ?>
													<div style="float: left;">
														towards :<strong style="font-size: 14px;">Holding Tax &amp; Others</strong>&nbsp;&nbsp; vide DD No
													</div>
													<?php } ?>
													<div style="border-bottom: #333333 dotted 2px; width: 275px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
														&nbsp;&nbsp; <?=$cheque_details["cheque_no"];?>
													</div>
												</td>
											</tr>
											
											<tr>
												<td height="35">
													<div style="float: left;">Dated </div>
													<div style="border-bottom: #333333 dotted 2px; width: 180px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
														&nbsp;&nbsp; <?=$cheque_details["cheque_date"];?>
													</div>
													<div style="float: left;">Drawn on </div>
													<div style="border-bottom: #333333 dotted 2px; width: 345px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px;">
														&nbsp;&nbsp; <?=$cheque_details["bank_name"];?>
													</div>
												</td>
											</tr>
											<tr>
												<td height="35">
													<div style="border-bottom: #333333 dotted 2px; width: 499px; height: 20px; float: left; margin-left: 2px; padding-bottom: 3px; font-weight: 700;">
														&nbsp;&nbsp; <?=$cheque_details["branch_name"];?>
													</div>
													<div style="float: left;">Place Of The Bank.</div>
												</td>
											</tr>
											<?php } ?>
											
										</tbody>
									</table><br><br><br>
									<div class="col-sm-12">
											<b>N.B.Online Payment/Cheque/Draft/ Bankers Cheque are Subject to realisation</b>
									</div><br><br>
									
									<div style="width: 99%; margin: auto; line-height: 35px; border-bottom: #000000 double 2px;"><strong style="font-size: 14px;">MUNICIPAL LICENSE FEE DETAILS </strong>
									
									</div>
										<table width="99%" border="1" align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; text-align:center;">
											<tbody>
												<tr>
													<td><b>Description</b></td>
													<td><b>Total Amount</b></td>
												</tr>
												<tr>
													<td>Municipal License Fee</td>
													<td><?php echo $transaction_details['paid_amount']-$transaction_details['penalty'].'.00';?></td>
													
												</tr>
												<tr>
													<td>Deniel Fee</td>
													<td><?php echo $transaction_details['penalty'];?></td>
													
												</tr>													
												<tr>
													<td>Total</td>
													<td><?php echo $transaction_details['paid_amount'];?></td>
													
												</tr>
											</tbody>
										</table><br>
										<table width="100%" border="0">
										<img style="margin-left:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>'>
										</table>
										
										<table width="100%" border="0">
											<tbody>
												<tr>
													<td colspan="2" style="font-size:13px;">For Details Please Visit : www.ranchimunicipal.com
														<br>
														Call us at 18008904115 OR 0651-3500700
													</td>
													<td style="text-align:center; font-size:13px;">In Association with<br>
														Sri Publication & Stationers Pvt. Ltd.<br>
														
													</td>
												</tr>
											</tbody>
										</table><br>
										<div class="col-sm-12 " style="text-align:center;">
											<br>
											<b>**This is a computer-generated receipt and it does not require a signature.**</b>
										</div>
								     </div>
                                     </div>
							      </div>
						      </div>
                           </div>
				          </div>
			             </div>
                        </div>  
                        </div>
                        </div>
                         <!-- model end -->

                    <!-- provitional model start -->
                    <div id="provtnalmodal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                    <div class="modal-body">
                      <div id="page-content">
                       <a href="javascript:void(0);" onclick="printPageArea('printarea_prov')">Print</a>
                      &nbsp; <span style="float:right;" class="close" onclick="clse_mdel_prov()">x</span>
					<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    
                    <h3 class="panel-title">Provisional License  </h3>
 
                </div>

                <div class="panel-body" style="padding-bottom: 0px;" id="printarea_prov">
                    <center>
						<div class="panel-body" id="" style="outline-style: dotted;padding:5px;color:#B9290A; ">
							<div class="col-sm-1"></div>
							<div class="col-sm-10" style="text-align: center;">
								<img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png');?>'>
							</div>
                             <table width="100%">
                                 
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;"><strong><?=strtoupper($ulb['ulb_name']);?></strong><br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;"><strong>Provisional Municipal Trade License</strong><br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>
                                         <center>
                                             <label style="font-size:14px;color:#B9290A;">(This Certificate relates to  Section 155 (i) and 455 (i) Under Jharkhand Municipal Act of 2011)<br/></label><br/>
                                         </center>
                                     </td>
                                 </tr>
                             </table>

							
                             <table style="width:90%;color:#B9290A;">
								<tr>
									<td>
										Application No : <span style="font-weight:bold;" ><?=$basic_details_prov['application_no'];?></span><br/>
									</td>
									<td>
										Provisional License No : <span style="font-weight:bold;" ><?=$basic_details_prov['provisional_license_no'];?></span><br/>
									</td>
									
								</tr>
								<tr>
									 <td>&nbsp;</td>
									 <td>
										Apply Date : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details_prov['apply_date']));?></span><br/>
									</td>
								</tr>
								 <tr>
									 <td>Mr/Mrs : <span style="font-weight:bold;" ><?=$basic_details_prov['applicant_name'];?></span><br/></td>
									 <td rowspan="4" style="text-align: right;">
										<img style="width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ssprov);?>'>
									</td>
								 </tr>
								 <tr>
									 <td>in the : <span style="font-weight:bold;" ><?=$ulb['ulb_name'];?> </span> Municipal Area</td>
								 </tr>
								 <tr>
									 <td>Firm / organization  name : <span style="font-weight:bold;" ><?=$basic_details_prov['firm_name'];?></span></td>
								 </tr>
								
                                 <tr>
                                     <td>Ward No. : <span style="font-weight:bold;" ><?=$ward['ward_no'];?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Business Address : <span style="font-weight:bold;" >
                                        <?=strtoupper($basic_details_prov['address']);?>
                                         </span></td>
                                     
                                 </tr>
                                 <tr>
                                     
                                     <td>For defined Fee : <span style="font-weight:bold;" ><?=$tranProvDtl['paid_amount'];?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Having  receipt no : <span style="font-weight:bold;" >
                                         <?=$tranProvDtl['transaction_no'];?>
                                         </span></td>
                                     
                                 </tr>
                                 <tr>
                                     
                                     <td>Establishment Date : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($basic_details_prov['establishment_date']));?></span></td>
                                 </tr>
                                 <tr>
                                     <td>Valid Upto : <span style="font-weight:bold;" ><?=date('d-m-Y', strtotime($valid_upto));?></span></td>
                                 </tr>
								 <tr>
									 <td>Subject to the following terms, license is granted.</td>
                                 </tr>

                             </table>

                             <table style="width:100%;color:#B9290A;">
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>

                                 <tr>
                                    <td>
                                        <ol>
                                            <li>Business will run according to licence issued.</li>
											<li>Prior Permission from local body is necessary if business is changed.</li>
											<li>Information to local body is necessary for extension of area.</li>
											<li>Prior information to local body regarding winding of business is necessary.</li>
                                            <li>Application for renewal of license is necessary one month before expiry of license.</li>
                                            <li>In the case of delay penalty will be levied according to section 459 of Jharkhand Municipal Act 2011.</li>
                                            <li>Illegal Parking in front of firm in non-permissible.</li>
                                            <li>Sufficient number of containers for disposing-garbage and refuse shall be made available within.</li>
                                            <li>The premises and the licensee will co-operate with the ULB for disposal of such waste.</li>
                                            <li>SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit.</li>
                                            <li>This provisional license is valid for 22 days from the date of apply . In case of no-objection from
											  <strong><?=$ulb['ulb_name'];?></strong>
											,The license shall be deemed approved. </li>
                                            <li>The final license can be downloaded from<span style="font-size:12px;color: #980601"> www.ranchimunicipal.net</span></li>
                                        </ol>
                                    </td>
                                 </tr>
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>
								 <tr>
									<td colspan="3" align="left" style="font-size:12px;color: #980601"><p></p>For More Details Please Visit : www.ranchimunicipal.net<br />Or Call us at 18008904115 or 0651-3500700</td>
								</tr>
                                 <tr>
                                     <td>&nbsp;</td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Note: This is a computer generated Licence. This Licence does not require a physical signature.</td>
                                 </tr>
                             </table>
                             <br />

	                    </div>
					</center>
					<br />
                </div>
            </div>
		</div>
                            </div>
                            </div>
                                </div>
                            <!-- end -->
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
                                                    <?php 
                                                endforeach;
                                            endif;  
                                        ?>
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
                                                    <?php 
                                                endforeach;
                                            endif; 
                                        ?>
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
                                                <?php 
                                            endforeach;
                                        endif;  
                                    ?>
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
                                                <?php 
                                            endforeach;
                                        endif; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
					
                     </div>
                </form>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
///////modal start

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
<!-- //////modal end -->
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
        $("#formname").validate({
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
    });
    /*
    function app_img_remarks_details(il)
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
    });
    */

</script>
 

<script>
    // Get the modal
    var modalpayment = document.getElementById("myModal");
    // Get the button that opens the modal
    var btnpayment = document.getElementById("customer_view_detail");
    // When the user clicks the button, open the modal 
    btnpayment.onclick = function() {
    modalpayment.style.display = "block";
    }
    // When the user clicks on clse_mdel function, close the modal
    function clse_mdel()
    {
        modalpayment.style.display = "none";

    }
</script>

    <!-- provitional model -->
<script>
    // Get the modal
    var modalprov = document.getElementById("provtnalmodal");

    // Get the button that opens the modal
    var btnprov = document.getElementById("provtnal");

    // When the user clicks the button, open the modal 
    btnprov.onclick = function() 
    {
    modalprov.style.display = "block";
    }

    // When the user clicks on clse_mdel_prov function, close the modal
    
    function clse_mdel_prov()
    {
        modalprov.style.display = "none";

    }
    var modalpayment = document.getElementById("myModal");//payment model
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
    if (event.target == modalprov || event.target==modalpayment) {
        modalprov.style.display = "none";
        modalpayment.style.display = "none";
    }
    }
</script>

 
<script>
    function printPageArea(printarea_prov){
        var printContent = document.getElementById(printarea_prov);
        var WinPrint = window.open('', '', 'width=900,height=650');
        WinPrint.document.write(printContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>
<script>
    function printPageArea(print_payment_receipt){
        var printContent = document.getElementById(print_payment_receipt);
        var WinPrint = window.open('', '', 'width=900,height=650');
        WinPrint.document.write(printContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>

