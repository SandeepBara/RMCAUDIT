<style>
.error {
    color: red;
}
</style>
<?= $this->include('layout_vertical/header');?>

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
					<li class="active">Connection Fee Chart</li>
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
								<a href="<?php echo base_url('WaterConnectionFeeMaster/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
							</div>
							<h3 class="panel-title"><?php echo $title; ?> Rate Chart Type</h3>
						</div>
                
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="" id="myform">
								<input type="hidden" name="id" id="id" value="<?=(isset($id))?md5($id):"";?>">
								<input type="hidden" name="curr_date" id="curr_date" value="<?php echo $curr_date; ?>">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Connection Type<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<select name="connection_type_id" id="connection_type_id" class="form-control">
											<option value="">Select</option>
											<?php
											if($conn_type_list)
											{
												foreach($conn_type_list as $val)
												{
											?>
											<option value="<?php echo $val['id'];?>" <?php if($connection_type_id==$val['id']){
											echo "selected";
											}?>><?php echo $val['connection_type'];?></option>
											<?php

												}

											}
											?>
										</select>
									</div>
									<label class="col-sm-2 control-label" for="design">Property  Type<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<select name="property_type_id" id="property_type_id" class="form-control">
											<option value="">Select</option>
											<?php
											if($property_type_list)
											{
												foreach($property_type_list as $val)
												{
											?>
											<option value="<?php echo $val['id'];?>" <?php if($property_type_id==$val['id']){ echo "selected"; }?>><?php echo $val['property_type'];?></option>
											<?php
												}
											}
											?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Pipeline Type<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<select name="pipeline_type_id" id="pipeline_type_id" class="form-control">
										<option value="">Select</option>
										<?php
										if($pipeline_list)
										{
											foreach($pipeline_list as $val)
											{
										?>
										<option value="<?php echo $val['id'];?>" <?php if($pipeline_type_id==$val['id']){
											echo "selected";
											}?>><?php echo $val['pipeline_type'];?></option>
											<?php
											}
										}
										?>
										</select>
									</div>
									<label class="col-sm-2 control-label" for="design">Connection Through<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<select name="connection_through_id" id="connection_through_id" class="form-control">
										<option value="">Select</option>
										<?php
										if($conn_through_list)
										{
											foreach($conn_through_list as $val)
											{
										?>
										<option value="<?php echo $val['id'];?>" <?php if($connection_through_id==$val['id']){
											echo "selected";
											}?>><?php echo $val['connection_through'];?></option>
										<?php
											}
										}
										?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Category<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<select name="category" id="category" class="form-control">
											<option value="">Select</option>
											<option value="APL" <?php if($category=="APL"){ echo "selected";}?>>APL</option>
											<option value="BPL" <?php if($category=="BPL"){ echo "selected";}?>>BPL</option>
										</select>
									</div>
									<label class="col-sm-2 control-label" for="design">Registration Fee<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<input type="text" name="reg_fee" id="reg_fee" class="form-control" value="<?php echo
										isset($reg_fee)?$reg_fee:""; ?>" onkeypress="return isNumberKey(event,this);">
									</div>
									
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Proc Fee<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<input type="text" name="proc_fee" id="proc_fee" class="form-control" value="<?php echo isset($proc_fee)?$proc_fee:""; ?>" onkeypress="return isNumberKey(event,this);">
									</div>
									<label class="col-sm-2 control-label" for="design">App Fee<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<input type="text" name="app_fee" id="app_fee" class="form-control" value="<?php echo isset($app_fee)?$app_fee:""; ?>" onkeypress="return isNumberKey(event,this);">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Sec Fee<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<input type="text" name="sec_fee" id="sec_fee" class="form-control" value="<?php echo isset($sec_fee)?$sec_fee:""; ?>" onkeypress="return isNumberKey(event,this);">
									</div>
									<label class="col-sm-2 control-label" for="design">Conn Fee<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<input type="text" name="conn_fee" id="conn_fee" class="form-control" value="<?php echo isset($conn_fee)?$conn_fee:""; ?>" onkeypress="return isNumberKey(event,this);">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Effect Date<span style="color:red">*</span></label>
									<div class="col-sm-3">
										<input type="date" name="effect_date" id="effect_date" class="form-control" value="<?php echo isset($effect_date)?$effect_date:"";?>" onchange="validate(this.value);">
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

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

    $('#myform').validate({ // initialize the plugin
       

        rules: {
            proc_fee: {
                required: true,
               
            },
            app_fee: {
                required: true,
              
            }
        }


    });

});
</script>

<script type="text/javascript">
    
       /*function isNumberKey(txt, evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
          return true;
        } else {
          return false;
        }
      } else {
        if (charCode > 31 &&
          (charCode < 48 || charCode > 57))
          return false;
      }
      return true;
    }*/


     function isNumberKey(evt, obj) {

            var charCode = (evt.which) ? evt.which : event.keyCode
            var value = obj.value;
            var dotcontains = value.indexOf(".") != -1;
            if (dotcontains)
                if (charCode == 46) return false;
            if (charCode == 46) return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

function checkDec(el){
 var ex = /^[0-9]+\.?[0-9]*$/;
 if(ex.test(el.value)==false){
   el.value = el.value.substring(0,el.value.length - 1);
  }
}

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