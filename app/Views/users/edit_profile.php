
<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	#ss{line-height: 25px;}
</style>
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
					<li><a href="#"></a>User</li>
					<li class="active">Profile</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

				<div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel">
					            <div class="panel-heading">
					                <h5 class="panel-title">Profile</h5>

					            </div>
								<div class="panel-body">
									<form action="" method="post" role="form" class="form-horizontal">
										<div class="col-md-3">
											<div class="div_photo_path_preview" style="width:100%; text-align:center;">
	                                        <img id="photo_path_preview" src="<?=($emp_details['photo_path']!="")?base_url()."/writable/uploads/emp_image/".$emp_details['photo_path']:base_url()."/public/assets/img/avatar/default_avatar.png";?>" alt="" style="height:210px; width:200px;"/>
                                    		</div>
										</div>
										<div class="col-md-9">
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="employee_name"><b>Employee Name</b></label>
												</div>
												<div class="col-md-4">
													<?=$emp_details['emp_name']?$emp_details['emp_name']." ".$emp_details['middle_name']." ".$emp_details['last_name']:"N/A"; ?>
												</div>
											</div>
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="guardian Name"><b>Guardian Name</b></label>
												</div>
												<div class="col-md-6">
													<?=$emp_details['guardian_name']?$emp_details['guardian_name']:"N/A"; ?>
												</div>
											</div>
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="personal_phone_no"><b>Phone Number</b></label>
												</div>
												<div class="col-md-6">
													<?=$emp_details['personal_phone_no']?$emp_details['personal_phone_no']:"N/A"; ?>
												</div>
											</div>
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="email_id"><b>Email Id</b></label>
												</div>
												<div class="col-md-6">
													<?=$emp_details['email_id']?$emp_details['email_id']:"N/A"; ?>
												</div>
											</div>
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="user_type"><b>User Type</b></label>
												</div>
												<div class="col-md-6">
													<?=$emp_details['user_type']?$emp_details['user_type']:"N/A"; ?>
												</div>
											</div>
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="ulb"><b>Assigned Ulb</b></label>
												</div>
												<div class="col-md-6">
													<?php
														if(isset($ulb)){
	                                                        foreach ($ulb as $value) {
	                                                            echo $value['ulb_name']."  ";
	                                                        }
	                                                    }
													?>
												</div>
											</div>
											<div class="row" id="ss">
												<div class="col-md-3">
													<label  for="user_type"><b>Assigned Ward</b></label>
												</div>
												<div class="col-md-6">
													<?=$emp_details['ward_id']?$emp_details['ward_id']:"N/A"; ?>
												</div>
											</div>
											
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
  <?= $this->include('layout_vertical/footer');?>

