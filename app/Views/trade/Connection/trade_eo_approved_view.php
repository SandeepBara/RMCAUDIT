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
					<li><a href="#">Trade</a></li>
					<li class="active">Trade EO View</li>
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
													<a href="<?php echo base_url('Trade_EO/index') ?>" class="btn btn-default">Back</a>
												</div>
												<h3 class="panel-title">Basic Details</h3>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-2">
														<b>Ward No. :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $ward['ward_no']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Application Type :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['application_type']; ?>
													</div>
												</div>
                                                <div class="row">
													<div class="col-sm-2">
														<b>Application No. :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['application_no']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Firm Type :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['firm_type']; ?>
													</div>
												</div>
                                                <div class="row">
													<div class="col-sm-2">
														<b>Ownership Type :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['ownership_type']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Firm Name :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['firm_name']; ?>
													</div>
												</div>
                                                <div class="row">
													<div class="col-sm-2">
														<b>K No :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['k_no']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Area :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['area_in_sqft']; ?> sqft
													</div>
												</div>
                                                <div class="row">
													<div class="col-sm-2">
														<b>Account No :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['account_no']; ?>
													</div>
                                                    <div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b> Remarks :</b>
													</div>
													<div class="col-sm-3">
														Forwarded for site inspection 
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
													<thead class="thead-light" style="background-color: blanchedalmond;">
														<tr>
														  <th scope="col">Owner Name</th>
														  <th scope="col">Address</th>
														  <th scope="col">Mobile No</th>
                                                            <th></th>
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
															  <td><?php echo $owner_details['owner_name']; ?></td>
															  <td><?php echo $owner_details['address']; ?></td>
															  <td><?php echo $owner_details['mobile']; ?></td>
															</tr>

														<?php endforeach; ?>
														<?php } ?>
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

<?= $this->include('layout_vertical/footer');?>
