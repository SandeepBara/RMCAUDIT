
<?= $this->include('layout_home/header');?>
<style>
#content-container{
	font-family:verdana;
}
.left_section {
    float: left;
    width: 75px;
    margin-right: 16px;
}

.right_section {
    float: left;
    width: 50%;
    text-align: left;
    margin-top: 20px;
}

.box {
	position: relative;
    width: 310px;
    height: 450px;
    padding: 1rem;
	background: linear-gradient(215deg, rgb(174 119 215) 0%, rgba(22,9,121,1) 35%, rgb(136 102 235) 100%);
    margin-top: 100px;
	border-radius:10px;
}
.photo {
	position: absolute;
    top: -77px;
    left: 100px;
    max-width: 33%;
    height: auto;
    border-radius: 50%;
    border: 5px solid #ffffff;
}
.testimonial {
	font-size: 2.5rem;
    padding-top: 20px;
    line-height: 1.5;
    text-align: center;
	color: #ffff;
}

.name {
  display: block;
  color: #ffff;
  font-size: 1.3rem;
  margin-bottom: 0;
  text-align: center;
  font-weight: bold;
}

.name::before {
  content: '';
  width: 100%;
  height: 1px;
  background: #ffffff;
  display: block;
  margin: 5px auto 10px
}

.ward{
    display: block;
    font-size: 1.5rem;
    text-align: center;
    margin-top: 10px;
    color: #FFFFFF;
}

.ward span{
    color: #ffff;
    font-size: 1.1rem;
    margin-bottom: 0;
    text-align: left !important;
    font-weight: 600;
    margin-top: 10px;
}


.ward::before{
    content: '';
    width: 100%;
    height: 1px;
    background: #ffffff;
    display: block;
}

.emp_code{
    text-align: center;
    margin: 5px;
    font-size: 1.5rem;
}
</style>
 <!--CONTENT CONTAINER-->
<div id="content-container" style="padding:10px;">
    <!--Page content-->
    <div id="page-content" style="padding:0 !important;">
        
			<?php
			if(isset($taxcoll_details)){
			?>
			<div class="panel  panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align:center;">Tax Collector List</h3>
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<?php if($taxcoll_details): $i = 0;?>
								<?php foreach($taxcoll_details as $post): $i = $i+1; ?>
									<?php if($i==1){ ?>
										<div class="row">
									<?php } ?>
										<div class="col-md-3">
											<div class="box">

                                                <?php
                                                $photoPath = $post['photo_path'];

                                                // Check if photo_path is not empty
                                                if (!empty($photoPath)) {
                                                    $imageUrl = $photoPath;
													$imageUrl = base_url("getImageLink.php?path=emp_image/".$photoPath);
                                                } else {
                                                    $imageUrl = base_url() . '/public/assets/img/logo1.png';
                                                }
                                                ?>
												<img src="<?= $imageUrl; ?>" alt="profile" class="photo" >
												<p class="testimonial" style = "margin-top:25px"><i class="fa fa-mobile" aria-hidden="true"></i>&nbsp;<?php echo $post['personal_phone_no']; ?></p>
												<p class="name"><?php echo $post['emp_name']." ".$post['middle_name']." ".$post['last_name']; ?></p>
                                                <p class="emp_code text-danger"><?= $post['employee_code'] ?? 'NA' ?></p>
                                                <p class="ward">Ward List: <br> <span> <?= $post['ward_id'] ?? 'NA' ?></span></p>
											</div>
										</div>
									<?php if($i==4){ $i=0; ?>
										</div>
									<?php } ?>
									<?php endforeach; ?>
								<?php else: ?>
									<div class="col-md-12">
										
										<div class="panel">
											<div class="panel-body text-center">
												<b style="text-align:center;color:red;"> Data Are Not Available!!</b>
											</div>
										</div>
									</div>
							<?php endif; ?>
								
							</div>
						</div>
					</div>
				</div>
				<?php 
				/*
				<div class="panel-body">
					<div id="saf_distributed_dtl_hide_show">
						<div class="row">
							<div class="col-md-12">
							<?php if($taxcoll_details): $i = 0;?>
								<?php foreach($taxcoll_details as $post): $i = $i+1; ?>
									<?php if($i==1){ ?>
										<div class="row">
									<?php } ?>
									<div class="col-md-3" style="border-style: ridge;">
										
										<div class="panel">
											<div class="panel-body text-center">
												<div class="left_section">
													<img alt="Profile Picture" class="img-lg img-circle mar-btm" style="height: 75px; width: 75px;" src="<?=base_url();?>/public/assets/img/logo1.png">
												</div>
												<div class="right_section">
													<!--p class="text-lg text-semibold mar-no text-main">Ward No.</p>
													<p class="text-muted"><?php //echo $post['ward_id']; ?></p-->
													<p class="text-lg text-semibold mar-no text-main" style="color:#0f1151;"><?php echo $post['emp_name']." ".$post['middle_name']." ".$post['last_name']; ?></p>
													<p class="text-lg text-semibold mar-no text-main" style="font-size:15px;">
														<i class="fa fa-mobile" aria-hidden="true"></i>&nbsp;<?php echo $post['personal_phone_no']; ?>
													</p>
												</div>
											</div>
										</div>
									</div>
									<?php if($i==4){ $i=0; ?>
										</div>
									<?php } ?>
										
								<?php endforeach; ?>
								<?php else: ?>
									<div class="col-md-12">
										
										<div class="panel">
											<div class="panel-body text-center">
												<b style="text-align:center;color:red;"> Data Are Not Available!!</b>
											</div>
										</div>
									</div>
							<?php endif; ?>
								
							</div>
						</div>
					</div>
				</div>
				*/ ?>
			</div>
			<?php }?>
    </div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>