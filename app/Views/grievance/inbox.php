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
					<li><a href="#">Grievance </a></li>
					<li class="active"><?=(isset($user_type_nm)?preg_replace('/water/i', ' ', $user_type_nm,1):'Dealing Assistant ').' Inbox';?></li>
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

							<form action="" method="post" role="form" class="php-email-form" id="myform">
																
								<div class="panel-body">
									<div class="row">
										<div class="col-md-2"></div>
										<div class="col-md-6">
											<div class="radio">
												<input type="radio" id="by_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_application_no" checked onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Application No ');" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='checked'?'selected':''?>>
												<label for="by_holding_dtl">By Application No.</label>

												<input type="radio" id="by_owner_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_owner"  onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name Or Father Name');" <?=isset($by_holding_owner_dtl) && $by_holding_owner_dtl=='by_owner'?'checked':''?>>
												<label for="by_owner_dtl">By Owner Details</label>
											</div>
										</div>
									</div>
									<div class="row">
										
										<div class="col-md-2">
											<label for="keyword">
												Enter Keywords
												<i id="keyword_change_id" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="Enter Application No"></i>
											</label>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keywords" value="<?=isset($keyword)?$keyword:'';?>">

											</div>
										</div>
										<div class="col-md-3">
											<button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
										</div>
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
												<th>Token No.</th>                                                
												<th>Grievance Type</th>
												<th>Owner Name</th>
												<th>Mobile No.</th>
												<th>Complain Type</th>
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
													<td><?=$value["token_no"];?></td>
													<td><?=$value["grievance_type"];?></td>
													<td><?=$value["owner_name"];?></td>
													<td><?=$value["mobile_no"];?></td>
													<td><?=$value["complain_type"];?></td>
													<td>
                                                        <a class="btn btn-primary" href="<?=base_url('grievance_new/viewGrievance/'.$value['id'])."/".($from??"");?>" >View</a>
																												
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