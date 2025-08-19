
<?= $this->include('layout_vertical/header');?>

<!--DataTables [ OPTIONAL ]-->
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
					<li class="active">Search Adjust Property</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

<div id="page-content">

					
                    <form action="<?php echo base_url('paymnt_adjust/search_adjust_Property_List');?>" method="post" role="form" class="php-email-form">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Property Search For Demand Adjustment</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-1">
										<label for="exampleInputEmail1">Ward No.<span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
												<option value="">Select</option>
												<?php if($ward): ?>
												<?php foreach($ward as $post): ?>
												<option value="<?php echo $post['id']; ?>"><?php echo $post['ward_no']; ?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
								
									<div class="col-md-2">
										<label for="holding_no">Enter Keywords <span class="text-danger">*</span></label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter holding No." value="<?php echo $emp_details['holding_no']; ?>">
											<span class="text-danger">(Enter Holding No. Or 15 Digit Unique No. Or Register Mobile No. Or Owner Name)</span>
										</div>
									</div>
									<div class="col-md-3 pad-btm">
										<button type="submit" id="search" name="search" class="btn btn-primary" value="search">SEARCH</button>
									</div>
								</div>
                            </div>
                        </div>
                    </form>
					<?php
					if(isset($emp_details)){
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Citizen List :</h3>
						</div>
						<div class="panel-body">
							<div id="saf_distributed_dtl_hide_show">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
												<thead class="thead-light" style="background-color: blanchedalmond;">
													
														<th>Sl No. </th>
														<th>Ward No </th>
														<th>Holding No </th>
														<th>Owner(s) Name </th>
														<th>Address </th>
														<th>Khata No. </th>
														<th>Plot No. </th>
														<th>Action </th>
												</thead>
												<tbody>
													
													<tr>
														<?php if($emp_details==""): ?>
															<td colspan="8" style="text-align:center;"> Data Are Not Available!!</td>
														<?php else: $i=1;  ?>
														<?php foreach($emp_details as $post): ?>
														<?php endforeach; ?>
														<td><?php echo $i++; ?></td>
														<td><?php echo $post['ward_no']; ?></td>
														<td><?php echo $post['holding_no']; ?></td>
														
														<td>
															<?php foreach($emp_details as $post): ?>
																<?php echo $post['owner_name']; ?><br>
															<?php endforeach; ?>
														</td>
														<td><?php echo $post['prop_address']; ?></td>
														<td><?php echo $post['khata_no']; ?></td>
														<td><?php echo $post['plot_no']; ?></td>
														<?php endif; ?>
														<td>
														<?php if($pay_status['dmnd_adjst_prop_dtl_id']!=""){ ?>
															<b style="color:green;">Already demand adjusted once...</b>
														<?php } else if($pay_status['trans_prop_dtl_id']!=""){ ?>
															<b style="color:#f10cb5;">Already payment started...</b>
														<?php } else { ?>
															<a href="<?php echo base_url('paymnt_adjust/demand_adjust/'.md5($post['prop_dtl_id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">Adjust Demand</a></td>
														<?php } ?>
													</tr>
													
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>
					
					

</div><br><br><!-- End Contact Section -->

<?= $this->include('layout_vertical/footer');?>