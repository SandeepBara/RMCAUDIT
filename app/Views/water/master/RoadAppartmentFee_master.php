<?= $this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 


<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Add/Update Designation</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Masters</a></li>
					<li class="active">Road Appartment Fee Master </li>
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
								<a href="<?php echo base_url('WaterRoadAppartmetFeeMaster/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
							</div>
							<h3 class="panel-title"><?php echo $title; ?>Road Appartment Fee Master </h3>
						</div>
						<!--Horizontal Form-->
						<!--===================================================-->
							<div class="panel-body">
								<form class="form-horizontal" method="post" action="">
									<input type="hidden" name="id" id="id" value="<?=(isset($id))?md5($id):"";?>">
								
					   
									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">Road Fee<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="text" maxlength="60" placeholder="Enter Road Fee" id="road_fee" name="road_fee" class="form-control" value="<?=(isset($road_fee))?$road_fee:"";?>" onkeypress="return isNum(event);">
										</div>
										<label class="col-sm-2 control-label" for="design">Appartment Fee<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="text" maxlength="60" placeholder="Enter Appartment Fee" id="appartment_fee" name="appartment_fee" class="form-control" value="<?=(isset($appartment_fee))?$appartment_fee:"";?>" onkeypress="return isNum(event);">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">Effect Date<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="date" placeholder="Enter Effect Date" id="effect_date" name="effect_date" class="form-control" value="<?=(isset($effect_date))?$effect_date:"";?>">
										</div>
									
									</div>
								
							 
									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">&nbsp;</label>
										<div class="col-sm-3">
											<button class="col-sm-12 btn btn-success" id="btndesign" name="btndesign" type="submit"><?=(isset($id))?'Update':'Submit';?></button>
										</div>
									</div>
									<?php if(isset($validation)){ ?>
										<?= $validation->listErrors(); ?>
									<?php } ?>
								 </form>
							</div>

						<!--===================================================-->
						<!--End Horizontal Form-->

					</div>
				</div>
			
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
