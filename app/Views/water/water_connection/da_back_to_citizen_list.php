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
					<li class="active">Dealing Assistant Back To Citizen</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Search</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?php echo base_url('water_da/da_back_to_citizen_list');?>">
								<div class="form-group">
									<div class="col-md-3">
										<label class="control-label" for="from_date"><b>From Date</b> </label>
											<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" />
									</div>
									<div class="col-md-3">
										<label class="control-label" for="to_date"><b>To Date</b> </label>
											<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" />
									</div>
									<div class="col-md-3">
										<label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
										<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
										   <option value="All">ALL</option> 
											<?php foreach($wardList as $value):?>
											<option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
											</option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-md-2">
										<label class="control-label" for="department_mstr_id">&nbsp;</label>
										<button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">List</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="table-responsive">
									<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>#</th>
												<th>Ward No.</th>
												<th>Application No.</th>
												<th>Owner Name</th>
												<th>Guardian Name</th>
												<th>Mobile No.</th>
												<th>Connection Type</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
		                                    <?php
											if(isset($posts["result"]))
											foreach($posts["result"] as $value)
											{
												?>
												<tr>
												<td><?=++$posts['offset'];?></td>
													<td><?=$value["ward_no"];?></td>
													<td><?=$value["application_no"];?></td>
													<td><?=$value["applicant_name"];?></td>
													<td><?=$value["father_name"];?></td>
													<td><?=$value["mobile_no"];?></td>
													<td><?=$value["connection_type"];?></td>
													<td>
														<!-- <a class="btn btn-primary" href="<?php echo base_url('water_da/boc_document_verification_view/'.md5($value['id']));?>" role="button">View</a> -->
														<a class="btn btn-primary" href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.md5($value['apply_connection_id']));?>" >View</a>
													</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>
								<?=isset($posts['count'])?pagination($posts['count']):null;?>
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