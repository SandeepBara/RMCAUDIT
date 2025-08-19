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
					<li class="active">Trade Item List</li>
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
					                <h3 class="panel-title"><?php echo $title; ?> Trade Item</h3>

					            </div>

					            <!--Horizontal Form-->
					            <!--===================================================-->


					                <div class="panel-body">
                                        <div class="pad-btm">
                                        <a href="<?php echo base_url('TradeMaster/tradeitemslist') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
                                    </div>
                                        <form class="form-horizontal" method="post" action="">
                                            <input type="hidden" name="id" id="id" value="<?php echo $tradeitems['id'] ?>">

					                    <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Trade Code</label>
					                        <div class="col-sm-4">
					                  <input type="text"  placeholder="Enter Trade Code" id="trade_code" name="trade_code" class="form-control" value="<?php echo $tradeitems['trade_code'] ?>" required >
					                        </div>
					                    </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="design">Trade Item</label>
                                            <div class="col-sm-4">
                                      <input type="text"  placeholder="Enter Trade Item" id="trade_item" name="trade_item" class="form-control" value="<?php echo $tradeitems['trade_item'] ?>" required >
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