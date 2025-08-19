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
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
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

 <div id="page-content">


					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel">
					            <div class="panel-heading">
					                <h5 class="panel-title">ULB List</h5>

					            </div>
	<form action="<?php echo base_url('public/Sub_item/store');?>" name="post_form" id="post_form" method="post" accept-charset="utf-8">
							<div class="container">
									<?php if($posts): ?>
									<?php foreach($posts as $post): ?>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Enter Your Name <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" id="name" name="name" class="form-control m-t-xxs" value="<?php echo $post['name']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Enter Your Email <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" id="email" name="email" class="form-control m-t-xxs" value="<?php echo $post['email']; ?>">
                                                </div>
                                            </div>
                                        </div>
										<div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Enter Your Password <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" id="pass" name="pass" class="form-control m-t-xxs" value="">
                                                </div>
                                            </div>
                                        </div>
										<div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Enter Your Mobile <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" id="mobile" name="mobile" class="form-control m-t-xxs" value="<?php echo $post['mobile']; ?>">
                                                </div>
                                            </div>
                                        </div>
										<div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Select Course Type <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="course" name="course" class="form-control m-t-xxs">
                                                        <option value="">Select</option>
                                                        <option value="PHP">PHP</option>
                                                        <option value="JAVA">JAVA</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">&nbsp;</div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="submit" id="btn_add_more_item" name="btn_add_more_item" class="btn btn-md btn-success btn-block" value='ADD'>
                                                </div>
                                            </div>
											<div class="col-md-2">
                                                <div class="form-group">
                                                    <a href="<?php echo base_url('public/Sub_item/store/$post["id"]');?>" id="btn_add_more_item" name="btn_add_more_item" class="btn btn-md btn-success btn-block">Edit  <i class="fa fa-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
										<?php endforeach; ?>
         <?php endif; ?>
							</div>
		

	</form>
</div>
</div>
</div>
</div>
</div>
