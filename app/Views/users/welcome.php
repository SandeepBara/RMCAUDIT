<?= $this->include('layout_vertical/header');?>

<style>

	.card {
		border-radius: 5px;
		-webkit-box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
		box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
		border: none;
		margin-bottom: 30px
	}


	.bg-c-lite-green {
		background: linear-gradient(to right, #597ac3, #63f2e5)
	}

	.user-profile {
		padding: 15%;
	}


	.m-b-25 {
		margin-bottom: 25px
	}

	.img-radius {
		border-radius: 5px
	}

	h6 {
		font-size: 13px
	}



	@media only screen and (min-width: 1400px) {
		p {
			font-size: 14px
		}
	}

	.b-b-default {
		border-bottom: 2px solid #e0e0e0
	}

	.f-w-600 {
		font-weight: 800;
		color:#545353;
	}

	.m-b-20 {
		margin-bottom: 20px
	}

	.m-t-40 {
		margin-top: 20px
	}

	.img-md-pp {
		width:150px;
		height:150px;
		border-radius:7px;
	}

</style>

<style>
		.n-card::after,
	.n-card img {
		border-radius: 50%;
	}
	.n-card,
	.stats {
		display: flex;
	}

	.n-card {
		padding: 2.5rem 2rem;
		border-radius: 10px;
		background-color: rgba(255, 255, 255, .5);
		max-width: 500px;
		box-shadow: 0 0 30px rgb(128 161 248);
		margin: 1rem;
		position: relative;
		transform-style: preserve-3d;
		overflow: hidden;
		border-bottom: 6px solid #7a7ee7 !important;
    	border-right: 6px solid #7a7ee7 !important;
    	border-radius: 15px !important;
	}
	.n-card::before,
	.n-card::after {
		content: '';
		position: absolute;
		z-index: -1;
	}
	.n-card::before {
		width: 100%;
		height: 100%;
		border: 1px solid #FFF;
		border-radius: 10px;
		top: -.7rem;
		left: -.7rem;
	}
	.n-card::after {
		height: 15rem;
		width: 15rem;
		background-color: #4172f5aa;
		top: -8rem;
		right: -8rem;
		box-shadow: 2rem 6rem 0 -3rem #FFF
	}

	.n-card img {
		width: 8rem;
		min-width: 80px;
		box-shadow: 0 0 0 5px #FFF;
	}

	.infos {
		margin-left: 1.5rem;
	}

	.name {
		margin-bottom: 1rem;
	}
	.name h2 {
		font-size: 2rem;
	}
	.name h4 {
		font-size: 18px;
		color: #333
	}

	.text {
		/* font-size: .9rem; */
		margin-bottom: 2rem;
	}

	.stats {
		margin-bottom: 1rem;
	}
	.stats li {
		min-width: 5rem;
	}
	.stats li h3 {
		font-size: .99rem;
	}
	.stats li h4 {
		font-size: .75rem;
	}

	.links a {
		/* font-family: 'Poppins', sans-serif; */
		min-width: 120px;
		padding: 1rem;
		border: 1px solid #222;
		border-radius: 10px;
		font-weight: bold;
		cursor: pointer;
		transition: all .25s linear;
	}
	.links .follow,
	.links .view:hover {
		background-color: #222;
		color: #FFF;
	}
	.links .view,
	.links .follow:hover{
		background-color: transparent;
		color: #222;
	}

	@media screen and (max-width: 450px) {
		.n-card {
			display: block;
		}
		.infos {
			margin-left: 0;
			margin-top: 1.5rem;
		}
		.links button {
			min-width: 100px;
		}
	}
	.flex-container {
  		display: flex;
  		flex-wrap: nowrap;
  		background-color: DodgerBlue;
	}
	.w-flex{
		background-color: #f1f1f1;
		width: 100px;
		margin: 10px;
		text-align: center;
		line-height: 75px;
		font-size: 30px;
	}
	kbd.bg-primary{
		background: linear-gradient(45deg, #080303 0%, rgb(255 2 2) 35%, #000000 100%);
	}
	/* kbd.label{
		font-size: xx-large;
	} */
</style>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title"><b>Welcome To Profile Page</b></h5>
            </div>
            <div class="panel-body">
			
				<div class="page-content page-container" id="page-content">
					<div class="padding">
						<div class="container d-flex justify-content-center" style="width: 100%;">
							<div class="row">
								<div class="col-sm-6">
									<h4>Profile Section</h4>
									<div class="n-card">
										<div class="img">
											<img src="<?=($emp_details['photo_path']!="")?base_url()."/writable/uploads/emp_image/".$emp_details['photo_path']:base_url()."/public/assets/img/avatar/default_avatar.png";?>" alt="Profile Picture">
										</div>
										<div class="infos">
											<div class="name">
												<h2><?=$emp_details['emp_name']?$emp_details['emp_name']." ".$emp_details['middle_name']." ".$emp_details['last_name']:"N/A"; ?></h2>
												<h4><?=$emp_details['user_name']?$emp_details['user_name']:"N/A"; ?></h4>
												
											</div>
											<p class="text">
												<i class="fa fa-envelope" aria-hidden="true"></i> <?=$emp_details['email_id']?$emp_details['email_id']:"N/A"; ?>
												<i class="fa fa-mobile" aria-hidden="true"></i> <?=$emp_details['personal_phone_no']?$emp_details['personal_phone_no']:"N/A"; ?>
											</p>
											<!-- <ul class="stats">
												<li>
													<h3>15K</h3>
													<h4>Views</h4>
												</li>
												<li>
													<h3>82</h3>
													<h4>Projects</h4>
												</li>
												<li>
													<h3>1.3M</h3>
													<h4>Followers</h4>
												</li>
											</ul> -->
											<div class="links">
												<a href="<?=base_url();?>/Profile/profileDetails" class="follow"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>
												
												<!-- <button class="view">View profile</button> -->
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<h4>Ward Permissions List</h4>
									<!-- <h6 class="text-muted f-w-400"> -->
										<?php  
										if (isset($emp_details['ward_id'])) {
										foreach($emp_details['ward_id'] as $ward){ ?>
											<kbd class="label bg-primary" style="margin-top:50px !important;"><b><?= $ward ?></b></kbd>
										<?php } }  ?>
									<!-- </h6> -->
								</div>
							</div>
							<?php if(isset($login_details)): ?>
								<h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Log In Details</h6>
								<div class="row" style="margin:0px">
									<div class="table-responsive">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>IP Address</th>
													<th>Login Date/Time</th>
												</tr>
											</thead>
											<tbody>
											<?php foreach($login_details as $login_details): ?>
												<tr>
													<td><?=$login_details['ip_address']?$login_details['ip_address']:"N/A"; ?></td>
													<td><?=$login_details['created_on']?$login_details['created_on']:"N/A"; ?></td>
												</tr>
											<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<?php /*
						<div class="row container d-flex justify-content-center" style="width:100%;">
							<div class="col-xl-6 col-md-12">
								<div class="card user-card-full">
									<div class="row m-l-0 m-r-0">
										<div class="col-sm-4 bg-c-lite-green user-profile">
											<div class="card-block text-center text-white">
												<div class="pad-btm">
													<img class="img-square img-md-pp" src="<?=($emp_details['photo_path']!="")?base_url()."/writable/uploads/emp_image/".$emp_details['photo_path']:base_url()."/public/assets/img/avatar/default_avatar.png";?>" alt="Profile Picture">
												</div>
												
												<h6 class="f-w-600"><?=$emp_details['emp_name']?$emp_details['emp_name']." ".$emp_details['middle_name']." ".$emp_details['last_name']:"N/A"; ?></h6>
												<p style="color:white;font-size:14px;font-weight:700;"><?=$emp_details['user_type']?$emp_details['user_type']:"N/A"; ?></p> <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
											</div>
										</div>
										<div class="col-sm-8">
											<h6 class="m-b-20 p-b-5 f-w-600">Ranchi Municipal Corporation</h6>
											<div class="card-block">
												<h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
												<div class="row">
													<div class="col-sm-4">
														<p class="m-b-10 f-w-600">Email</p>
														<h6 class="text-muted f-w-400"><?=$emp_details['email_id']?$emp_details['email_id']:"N/A"; ?></h6>
													</div>
													<div class="col-sm-4">
														<p class="m-b-10 f-w-600">Phone</p>
														<h6 class="text-muted f-w-400"><?=$emp_details['personal_phone_no']?$emp_details['personal_phone_no']:"N/A"; ?></h6>
													</div>
													<div class="col-sm-4">
														<p class="m-b-10 f-w-600">User Id</p>
														<h6 class="text-muted f-w-400"><?=$emp_details['user_name']?$emp_details['user_name']:"N/A"; ?></h6>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														<p class="m-b-10 f-w-600">Ward Permissions</p>
													
														<!-- <h6 class="text-muted f-w-400"> -->
															<?php  
															if (isset($emp_details['ward_id'])) {
															foreach($emp_details['ward_id'] as $ward){ ?>
						                                        <kbd class="label bg-primary" style="margin-top:50px !important;"><b><?= $ward ?></b></kbd>
															<?php } }  ?>
														<!-- </h6> -->
													</div>
												</div>
												<?php if(isset($login_details)): ?>
												<h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Log In Details</h6>
												<div class="row" style="margin:0px">
													<div class="table-responsive">
														<table class="table table-striped">
															<thead>
																<tr>
																	<th>IP Address</th>
																	<th>Login Date/Time</th>
																</tr>
															</thead>
															<tbody>
															<?php foreach($login_details as $login_details): ?>
																<tr>
																	<td><?=$login_details['ip_address']?$login_details['ip_address']:"N/A"; ?></td>
																	<td><?=$login_details['created_on']?$login_details['created_on']:"N/A"; ?></td>
																</tr>
															<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
												<?php endif; ?>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						*/ ?>
					</div>
				</div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
