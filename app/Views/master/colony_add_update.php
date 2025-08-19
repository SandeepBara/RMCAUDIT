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
					<li class="active">Colony </li>
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
					                <h3 class="panel-title"><?php echo $title ?? null; ?> Colony</h3>

					            </div>
                      <div class="pad-btm">
                      <a style="float:right;" href="<?php echo base_url('Colony/colonylist') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button>&nbsp;&nbsp;</a>
                    </div>
					            <!--Horizontal Form-->
					            <!--===================================================-->
					                <div class="panel-body">
                            <form class="form-horizontal" method="post" action="">
                              <input type="hidden" name="id" id="id" value="<?php echo $colony['id'] ?? null ?>">

					                    <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Colony Name</label>
					                        <div class="col-sm-4">
					                  <input type="text" placeholder="" id="colony_name" name="colony_name" class="form-control" value="<?php echo $colony['colony_name'] ?? null;?>" required >
					                        </div>
					                    </div>

                              <div class="form-group">
                                 <label class="col-sm-2 control-label" for="design">Colony Address</label>
                                 <div class="col-sm-4">
                           <textarea type="text"  placeholder="" id="colony_address" name="colony_address" class="form-control"  required ><?php echo $colony['colony_address'] ?? null;?></textarea>
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
<?= $this->include('layout_vertical/footer');?>
