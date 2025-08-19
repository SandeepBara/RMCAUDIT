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
					<li class="active">User Type List</li>
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
								<a href="<?php echo base_url('user_type/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
							</div>
							<h3 class="panel-title"><?= isset($title)?$title:'' ?> User Type</h3>
						</div>
						<!--Horizontal Form-->
						<!--===================================================-->
							<div class="panel-body">
								<form class="form-horizontal" method="post" action="">
									<input type="hidden" name="id" id="id" value="<?=(isset($id))?md5($id):"";?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">User Type<span style="color:red">*</span></label>
										<div class="col-sm-4">
											<input type="text" maxlength="60" placeholder="Enter User Type" id="user_type" name="user_type" class="form-control" value="<?=(isset($user_type))?$user_type:"";?>" onkeypress="return isAlpha(event);">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="user_for">User For<span style="color:red">*</span></label>
										<div class="col-sm-4">
										  <select id="user_for" name="user_for" class="form-control">
												<option value='AGENCY' <?=(isset($user_for))?($user_for=='AGENCY')?"selected":"":"";?>>AGENCY</option>
												<option value='ULB' <?=(isset($user_for))?($user_for=='ULB')?"selected":"":"";?>>ULB</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="design">&nbsp;</label>
										<div class="col-sm-4">
											<button class="col-sm-12 btn btn-success" id="btndesign" name="btndesign" type="submit"><?=(isset($id))?($id=='')?'Submit':'Update':'Submit';?></button>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#btndesign").click(function(){
            var user_type = $("#user_type").val();
                user_type = user_type.trim();
            if(user_type=="")
            {
                $("#user_type").css({"border-color":"red"});
                $("#user_type").focus();
                return false;
            }
        });
        $("#user_type").keyup(function(){$(this).css('border-color','');});
    });
</script>>