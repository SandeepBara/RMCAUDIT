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
					<li class="active">Water EO View</li>
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
								<a href="<?php echo base_url('Water_EO/consumer_list') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						
						<div class="panel-body">
							<div class="panel panel-bordered panel-dark" style="padding: 10px;">
								<span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;"> Your consumer no. is 
										<span style="color: #ff6a00"><?=!empty($consumer_details['consumer_no'])?$consumer_details['consumer_no']:"N/A"; ?></span>. 
										Please Note It for future reference.
								</span>
								
							</div>

							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($ward['ward_no'])?$ward['ward_no']:"N/A"; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Category :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['category'])?$basic_details['category']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Application No. :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['application_no'])?$basic_details['application_no']:"N/A"; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Connection Through :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['connection_through'])?$basic_details['connection_through']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Connection Type :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['connection_type'])?$basic_details['connection_type']:"N/A"; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Property Type :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['property_type'])?$basic_details['property_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Pipeline Type :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['pipeline_type'])?$basic_details['pipeline_type']:"N/A"; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Area :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['area_sqft'])?$basic_details['area_sqft'].'sqft':"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Address :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($basic_details['address'])?$basic_details['address']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Consumer No :</b>
								</div>
								<div class="col-sm-3">
									<?=!empty($consumer_details['consumer_no'])?$consumer_details['consumer_no']:"N/A"; ?>
								</div>
								<div class="col-sm-1">
								</div>
								<div class="col-sm-3">
									<b>Initial Reading :</b>
								</div>
								<div class="col-sm-3">
									<!-- <?=$consumer_initial_details['initial_reading'] ?? NULL; ?>  -->
									<?=!empty($consumer_initial_details['initial_reading'])?$consumer_initial_details['initial_reading'] : NULL; ?>
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
								<thead class="bg-trans-dark text-dark">
									<tr>
									  <th scope="col">Owner Name</th>
									  <th scope="col">Father's Name</th>
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
										  <td><?php echo $owner_details['applicant_name']; ?></td>
										  <td><?php echo $owner_details['father_name']; ?></td>
										  <td><?php echo $owner_details['mobile_no']; ?></td>
										</tr>

									<?php endforeach; ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel panel-bordered">
						<div class="panel-body">
							<div class="row text-center">
								<a href="<?=base_url().'/WaterApplyNewConnection/view_memo/'.md5($consumer_details['apply_connection_id'])?>" class="btn btn-info" target="_blank">Memo</a>
							</div>
						</div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

<?= $this->include('layout_vertical/footer');?>
