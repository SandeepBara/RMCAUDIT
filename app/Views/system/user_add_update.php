<!--DataTables [ OPTIONAL ]-->
<?= $this->include('layout_vertical/header');?>
<!-- <link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">-->
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Add/Update Department</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Masters</a></li>
					<li class="active">Add/Update User</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>


                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel">
					            <div class="panel-heading">
					                <h3 class="panel-title">Add/Update User</h3>
					            </div>

					            <!--Horizontal Form-->
					            <!--===================================================-->
					            <form class="form-horizontal" method="post" action="<?php echo base_url('Ward/add_update/'.$id);?>">
                              <input type="hidden" id="id" name="id" value="<?=(isset($ward['id']))?$ward['id']:'';?>" />
                                    <div class="panel-body">
                                     <div class="pad-btm">
                                         <a href="<?php echo base_url('Ward/wardList');?>" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back </a>
                                    </div>
                                   <div class="form-group">
					                        <label class="col-sm-2 control-label" for="user_name">User Name<span style="color:red"> *</span></label>
					                        <div class="col-sm-4">
					                            <input type="text" maxlength="60" placeholder="Enter User Name" id="user_name" name="user_name" class="form-control" value="<?=(isset($user_name))?$user_name:"";?>" onkeypress="return isAlpha(event);">
					                        </div>
					                    </div>
                               <div class="form-group">
                                  <label class="col-sm-2 control-label" for="user_name">User Name<span style="color:red"> *</span></label>
                                  <div class="col-sm-4">
                                      <input type="text" maxlength="60" placeholder="Enter User Name" id="user_name" name="user_name" class="form-control" value="<?=(isset($user_name))?$user_name:"";?>" onkeypress="return isAlpha(event);">
                                  </div>
                              </div>
					                </div>
					                <div class="panel-footer text-center">
					                    <button class="btn btn-success" id="btn_user" name="btn_user" type="submit"><?=(isset($id))?"Edit User":"Add User";?></button>
					                </div>
                                     <div class="row">
		                                  <div class="col-md-12" style="color: red; text-align: center;">
		                                      <?php
		                                          if(isset($error))
                                                  {
		                                              foreach ($error as $value)
                                                      {
		                                                 echo $value;
		                                                 echo "<br />";
		                                               }
		                                            }
		                                       ?>
		                                     </div>
		                             </div>
					            </form>
					            <!--===================================================-->
					            <!--End Horizontal Form-->

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
<script type="text/javascript">
$(document).ready( function () {
    $("#btn_ward").click(function() {
        var ulb_mstr_id = $("#ulb_mstr_id").val();
        var ward_no = $("#ward_no").val();
            //ward_no = parseInt(ward_no);
        if (ulb_mstr_id =="") {
            $("#ulb_mstr_id").css({"border-color":"red"});
            $("#ulb_mstr_id").focus();
            return false;
          }
          if(ward_no=="")
          {
            $("#ward_no").css({"border-color":"red"});
            $("#ward_no").focus();
            return false;
          }
    });
    $("#ulb_mstr_id").change(function(){$(this).css('border-color','');});
    $("#ward_no").keyup(function(){$(this).css('border-color','');});
});
</script>