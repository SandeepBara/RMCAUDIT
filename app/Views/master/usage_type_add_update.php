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
					<li class="active">Usage Type List</li>
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
								<a href="<?php echo base_url('usage_type/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
							</div>
							<h3 class="panel-title"><?php echo $title; ?> Usage Type</h3>
						</div>
						<!--Horizontal Form-->
						<!--===================================================-->
							<div class="panel-body">
								<form class="form-horizontal" method="post" action="">
									<input type="hidden" name="id" id="id" value="<?php echo $usage['id'] ?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="usage_type">Usage Type</label>
										<div class="col-sm-4">
											<input type="text" maxlength="20" placeholder="Enter Usage Type" id="usage_type" name="usage_type" class="form-control" value="<?php echo $usage['usage_type'] ?>" onkeypress="return isAlpha(event);">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="usage_code">Usage Code</label>
										<div class="col-sm-4">
											<input type="text" maxlength="20" placeholder="Enter Usage Code" id="usage_code" name="usage_code" class="form-control" value="<?php echo $usage['usage_code'] ?>" onkeypress="return isAlphaNum(event);">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="usage">&nbsp;</label>
										<div class="col-sm-4">
											<button class="btn btn-success" id="btnusage" name="btnusage" type="submit"><?=(isset($usage['id']))?"Edit Usage Type":"Add Usage Type";?></button>
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
        $("#btnusage").click(function(){
            var usage_type = $("#usage_type").val().trim();
            var usage_code = $("#usage_code").val().trim();
            if(usage_type=="")
            {
                $("#usage_type").css({"border-color":"red"});
                $("#usage_type").focus();
                return false;
            }
            if(usage_code=="")
            {
                $("#usage_code").css({"border-color":"red"});
                $("#usage_code").focus();
                return false;
            }
        });
        $("#usage_type").keyup(function(){$(this).css('border-color','');});
        $("#usage_code").keyup(function(){$(this).css('border-color','');});
    });
</script>>