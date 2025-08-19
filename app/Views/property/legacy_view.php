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
					<li><a href="#">Property</a></li>
					<li class="active"> Legacy Entry</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row" >
                        <div class="col-md-12">
                            <center><b><h4 style="color:red;">
                                <?php
                                if(!empty($err_msg)){
                                    echo $err_msg;
                                }
                                ?>
                                </h4>
                                </b></center>
                        </div>
                    </div>
                                        <div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<div class="panel-control">
													<a href="<?php echo base_url('LegacyEntry/form') ?>" class="btn btn-default">Back</a>
												</div>
												<h3 class="panel-title">Basic Details</h3>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-2">
														<b>Old Ward No. :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['ward_no']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Holding No. :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['holding_no']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Property Type :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['property_type']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Ownership Type :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['ownership_type']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Address :</b>
													</div>
													<div class="col-md-10">
														<?php echo $basic_details['prop_address']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Road Type :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['road_type']; ?>
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
														  <th scope="col">R/W Guardian</th>
														  <th scope="col">Guardian's Name</th>
														  <th scope="col">Mobile No</th>
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
															  <td><?php echo $owner_details['relation_type']; ?></td>
															  <td><?php echo $owner_details['guardian_name']; ?></td>
															  <td><?php echo $owner_details['mobile_no']; ?></td>
															</tr>
														<?php endforeach; ?>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="panel-group">
                                        <div class="panel panel-bordered panel-dark">

                                            <div class="panel-heading">
                                                <h3 class="panel-title">Tax Details</h3>
											</div>

                                             <div class="panel-body">
											<div class="table-responsive">												
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="thead-light" style="background-color: blanchedalmond;">
															<th scope="col">ARV</th>
															<th scope="col">Effected From</th>
															<th scope="col">Holding Tax</th>
															<th scope="col">Water Tax</th>
															<th scope="col">Conservancy/Latrine Tax</th>
															<th scope="col">Education Cess</th>
															<th scope="col">Health Cess</th>
															<th scope="col">Quarterly Tax</th>
													</thead>
													<tbody>
														<tr>
															<?php if($tax_list):
																$qtr_tax=0; ?>
															<?php foreach($tax_list as $tax_list): 
																$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
															?>
														<tr>
															<td><?php echo $tax_list['arv']; ?></td>
															<td>Quarter : <?php echo $tax_list['qtr']; ?> / Year : <?php echo $tax_list['fy']; ?></td>
															<td><?php echo $tax_list['holding_tax']; ?></td>
															<td><?php echo $tax_list['water_tax']; ?></td>
															<td><?php echo $tax_list['latrine_tax']; ?></td>
															<td><?php echo $tax_list['education_cess']; ?></td>
															<td><?php echo $tax_list['health_cess']; ?></td>
															<td><?php echo $qtr_tax; ?></td>     
														</tr>
														<?php endforeach; ?>
														<?php else: ?>
														<tr>
															<td colspan="7" style="text-align:center;"> Data Not Available...</td>
														</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
                                                </div>

										</div>
                    </div>
                                        <div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Demand List</h3>
							</div>
							<div class="table-responsive">								
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="bg-trans-dark text-dark">
											<th>Sl No.</th>
											<th>Quarter/Year</th>
											<th>Amount</th>
                                            <th>Balance</th>
											<th>Status</th>
									</thead>
									<tbody>
										<?php if($demand_detail):
										$j=1;
										foreach($demand_detail as $demand_detail): 
										?>			
										<tr>
											<td><?php echo $j++; ?></td>
											<td><?php echo $demand_detail['qtr']; ?> / <?php echo $demand_detail['fy']; ?></td>
											<td><?php echo $demand_detail['amount']; ?></td>
											<td><?php echo $demand_detail['balance']; ?></td>
											<td><?php if($demand_detail['paid_status']=='0'){
                                            ?>
                                                <span class="text-danger"><b>Unpaid</b></span>
                                                <?php
                                            }else{
                                                 ?>
                                                <span class="text-success"><b>Paid</b></span>
                                                <?php
                                            } ?></td>
										</tr>
										<?php endforeach; ?>
										<?php else: ?>
										<tr>
											<td colspan="5" style="text-align:center;color:red;"> Data Are Not Available!!</td>
										</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>
                        <?php
                            if($paid_demand['count_paid_demand']=='0'){
                              if($demand_upd_exist['count_demand']=='0'){
                        ?>
                        <div class="panel">
                            <div class="panel-body text-center">
                                <a href="<?php echo base_url('LegacyEntry/demand_update/'.$id);?>" type="button" class="btn btn-purple btn-labeled">Update Demand</a>
                            </div>
                        </div>
                    <?php }} ?>
                    </div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
