<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<style>
.row{line-height:25px;}
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
				<li class="active">Trade Licence List</li>
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
							<a href="<?php echo base_url('tradedocument/index') ?>" class="btn btn-default">Back</a>
						</div>
						<h3 class="panel-title">Details Of Municipal Licence For Application No. :- <span style="color:#b9f768;"><b><?=$basic_details['application_no']?$basic_details['application_no']:"N/A"; ?></b></span></h3>
					</div>
					<div class="panel-body">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Status Of Application</h3>
							</div>
							<div class="panel-body">
								<div class="col-sm-8 col-sm-offset-2">
									<b style="color:#60a042;font-size:20px;">Current Status : <span style="color:red;font-size:20px;">
									<?=$application_status?$application_status:"N/A"; ?></span></b>
								</div>
								
							</div>
						</div>
						
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Applicant Request Type Details</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-3">
										<b>Applicant Type :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['application_type']?$basic_details['application_type']:"N/A"; ?>
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
										<b>Business Premises Type :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
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
										<b>Applied Date :</b>
									</div>
									<div class="col-sm-3">
										<?=$holding['apply_date']?$holding['apply_date']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Trade Licence Apply For :</b>
									</div>
									<div class="col-sm-3">
										<?=$holding['licence_for_years']?$holding['licence_for_years']."  Years":"N/A"; ?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Applicant Request Type Details</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-3">
										<b>Name Of Business Firm :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['firm_name']?$basic_details['firm_name']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Address :</b>
									</div>
									<div class="col-sm-3">
										<?=$holding['address']?$holding['address']:"N/A"; ?>
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-3">
										<b>Landmark :</b>
									</div>
									<div class="col-sm-3">
										<?=$holding['landmark']?$holding['landmark']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Date Of Establishment :</b>
									</div>
									<div class="col-sm-3">
										<?=$holding['establishment_date']?$holding['establishment_date']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<b>Ward No. :</b>
									</div>
									<div class="col-sm-3">
										<?=$ward['ward_no']?$ward['ward_no']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Total Area(SQFT) :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['area_in_sqft']?$basic_details['area_in_sqft']:"N/A"; ?>
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
										<b>Holding No. :</b>
									</div>
									<div class="col-sm-3">
										<?=$holding['holding_no']?$holding['holding_no']:"N/A"; ?>
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
										<b>Account No :</b>
									</div>
									<div class="col-sm-3">
										<?=$basic_details['account_no']?$basic_details['account_no']:"N/A"; ?>
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
										<?php foreach($owner_details as $owner_details): ?>
											<tr>
												<td>
													<?=$owner_details['owner_name']?$owner_details['owner_name']:"N/A"; ?>
												</td>
												<td>
													<?=$owner_details['guardian_name']?$owner_details['guardian_name']:"N/A"; ?>
												</td>
												<td>
													<?=$owner_details['mobile']?$owner_details['mobile']:"N/A"; ?>
												</td>
												<td>
													<?=$owner_details['emailid']?$owner_details['emailid']:"N/A"; ?>
												</td>
												 
											</tr>
										<?php endforeach; ?>
										<?php } ?>
									</tbody>
								</table>
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
							</div>
						</div>
						<div class="panel">
						<?php 
                                    unset($_SESSION['url']);
                                    $_SESSION["url"]=$_SERVER['REQUEST_URI'];
                                    ?>
							<div class="panel-body text-center">
							
								<a href="<?php echo base_url('tradeapplylicence/provisional/'.$linkId);?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">Provisional Licence</a>
								
								<a href="<?php echo base_url('tradeapplylicence/view_transaction_receipt/'.md5($payment_dtls['related_id']).'/'.md5($payment_dtls['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View Payment Receipt</a>
							
								<?php
								if($basic_details['document_upload_status']=='0')
								 { 
								?>
								<a href="<?php echo base_url('tradedocument/doc_upload/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Document Upload</a>
								<?php
								}
								else
								{ 
								?>
								<a href="<?php echo base_url('tradedocument/docview/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Document Upload View</a>
								<?php
								}
								?>
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
<?= $this->include('layout_vertical/footer');?>
