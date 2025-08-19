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
					<li class="active">Rate Chart List</li>
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
								<a href="<?php echo base_url('WaterRateChartMaster/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
							</div>
							<h3 class="panel-title"><?php echo $title; ?> Rate Chart Type</h3>
						</div>
						<!--Horizontal Form-->
						<!--===================================================-->
							<div class="panel-body">
								<form class="form-horizontal" method="post" action="">
									<input type="hidden" name="id" id="id" value="<?=(isset($id))?md5($id):"";?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">Type<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<select name="type" id="type" class="form-control">
												<option value="">Select</option>
												<option value="Fixed" <?php  if($type=='Fixed'){ echo "selected";}?>>Fixed</option>
												<option value="Meter" <?php  if($type=='Meter'){ echo "selected";}?>>Meter</option>
												<option value="Gallon" <?php  if($type=='Gallon'){ echo "selected";}?>>Gallon</option>
											</select>
										</div>
										<label class="col-sm-2 control-label" for="design">Property Type<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<select name="property_type_id" id="property_type_id" class="form-control">
												<option value="">Select</option>
												<?php
												if($property_type)
												{
													foreach($property_type as $val)
													{
												?>
												<option value="<?php echo $val['id'];?>" <?php if($property_type_id==$val['id']){
													echo "selected";
													}?>><?php echo $val['property_type'];?></option>
													<?php
													}

												}
												?>
											</select>
										</div>
									</div>
					   
									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">Range From<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="text" maxlength="60" placeholder="Enter Range From" id="range_from" name="range_from" class="form-control" value="<?=(isset($range_from))?$range_from:"";?>" onkeypress="return isNum(event);">
										</div>
										<label class="col-sm-2 control-label" for="design">Range Upto<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="text" maxlength="60" placeholder="Enter Range Upto" id="range_upto" name="range_upto" class="form-control" value="<?=(isset($range_upto))?$range_upto:"";?>" onkeypress="return isNum(event);">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">Amount<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="text" placeholder="Enter Amount" id="amount" name="amount" class="form-control" value="<?=(isset($amount))?$amount:"";?>" onkeypress="return isNum(event);">
										</div>
										<label class="col-sm-2 control-label" for="design">Effect Date<span style="color:red">*</span></label>
										<div class="col-sm-3">
											<input type="date" id="effect_date" name="effect_date" class="form-control" value="<?=(isset($effect_date))?$effect_date:"";?>" onchange="validate(this.value);">
										</div>
									</div>
									<input type="hidden" name="curr_date" id="curr_date" value="<?php echo $curr_date;?>">
							 
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
<script>
    function validate(str)
    {
        var effect_date=str;
        var curr_date=$("#curr_date").val();

        if(effect_date>curr_date)
        {
            alert("Effect Date can not be greater than Current Date");
            $("#effect_date").val("");

        }
    }
</script>