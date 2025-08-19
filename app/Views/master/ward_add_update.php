<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
					<li class="active">Add/Update Ward</li>
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
					                <h3 class="panel-title">Add/Update Ward</h3>
					            </div>

					            <!--Horizontal Form-->
					            <!--===================================================-->
					            <form class="form-horizontal" method="post" action="<?php echo base_url('public/master/ward_add_update/<?=(isset($ward["id"]))?$ward["id"]:"";?>');?>">
                                    <input type="text" id="_id" name="_id" value="<?=(isset($ward['id']))?$ward['id']:'';?>" hidden/>
                                    <div class="panel-body">
                                        <div class="form-group">
					                        <label class="col-sm-3 control-label" for="dept">Ward Number</label>
					                        <div class="col-sm-6">
					                            <input type="text" maxlength="50" placeholder="Enter Ward Number" id="ward_no" name="ward_no" class="form-control" value="<?=(isset($ward['ward_no']))?$ward['ward_no']:"";?>" onkeypress="return isNumber(event);" >
					                        </div>
					                    </div>
					                </div>
					                <div class="panel-footer text-center">
					                    <button class="btn btn-success" id="btn_ward" name="btn_ward" type="submit"><?=(isset($ward["id"]))?"Edit Ward":"Add Ward";?></button>
                                         <a href="<?php echo base_url('public/master/wardList');?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back To List</a>

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


<script type="text/javascript">
$(document).ready( function () {
    $("#btn_ward").click(function() {
        var btn_ward = $("#btn_ward").val();
        if (btn_ward =="") {
            $("#btn_ward").css({"border-color":"red"});
            $("#btn_ward").focus();
            return false;
          }
    });
    $("#btn_ward").keyup(function(){$(this).css('border-color','');});
});
</script>