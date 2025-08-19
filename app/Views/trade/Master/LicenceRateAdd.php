<?=$this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
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
					<li class="active">Licence Rate List</li>
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
					                <h3 class="panel-title"><?php echo $title; ?> Licence Rate</h3>

					            </div>

					            <!--Horizontal Form-->
					            <!--===================================================-->


					                <div class="panel-body">
                                        <div class="pad-btm">
                                        <a href="<?php echo base_url('TradeMaster/licenceratelist') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back   </button></a>
                                    </div>                                   
                                        <form class="form-horizontal" name="formlicencerate" id="formlicencerate" method="post" action="">
                                            <input type="hidden" name="id" id="id" value="<?php echo $licencerate['id'] ?>">

					                    <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Application Type</label>
					                        <div class="col-sm-4">
					                  <select id="application_type" name="application_type" required="required" class="form-control">
                                        <option value="">== SELECT ==</option>
                                        <?php
                                        if(isset($application_type)){
                                            foreach ($application_type as $value) {
                                        ?>
                                        <option value="<?=$value['id'];?>" <?=(isset($licencerate["application_type_id"]))?($value['id']==$licencerate["application_type_id"])?"selected":"":"";?>><?=$value['application_type'];?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
					                        </div>
					                    </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="design">Range From</label>
                                            <div class="col-sm-4">
                                      <input type="number" maxlength="20" placeholder="Enter Range From" id="range_from" name="range_from" class="form-control" required="required" value="<?php echo $licencerate['range_from'] ?>"  >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="design">Range To</label>
                                            <div class="col-sm-4">
                                      <input type="number" maxlength="20" placeholder="Enter Range To" id="range_to" name="range_to" class="form-control" required="required" value="<?php echo $licencerate['range_to'] ?>"  >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="design">Rate</label>
                                            <div class="col-sm-4">
                                      <input type="number" maxlength="20" placeholder="Enter Rate" id="rate" required="required" name="rate" class="form-control" value="<?php echo $licencerate['rate'] ?>"  >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="design">Effective Date</label>
                                            <div class="col-sm-4">
                                      <input type="date" required="required" placeholder="Enter Effective Date" id="effective_date" name="effective_date" class="form-control" value="<?php echo (isset($licencerate['effective_date']))?$licencerate['effective_date']:date('Y-m-d');?>"  >
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
