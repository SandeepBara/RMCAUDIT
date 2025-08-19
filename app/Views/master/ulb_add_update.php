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
					<li class="active">ULB List</li>
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
					                <h3 class="panel-title"><?php echo $title; ?> ULB</h3>

					            </div>

					            <!--Horizontal Form-->
					            <!--===================================================-->


					                <div class="panel-body">
                                        <div class="pad-btm">
                                        <a href="<?php echo base_url('public/ulb/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
                                    </div>
                                        <form class="form-horizontal" method="post" action="">
                                            <input type="hidden" name="id" id="id" value="<?php echo $ulb['_id'] ?>">

					                    <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">ULB Name</label>
					                        <div class="col-sm-4">
					                  <input type="text" maxlength="20" placeholder="Enter ULB Name" id="ulb_name" name="ulb_name" class="form-control" value="<?php echo $ulb['ulb_name'] ?>" required >
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">&nbsp;</label>
					                        <div class="col-sm-4">
					                            <button class="btn btn-success" id="btndesign" name="btndesign" type="submit">Submit</button>
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
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
