<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Form Distribute View</h3>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" method="post" action="">
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             SAF No.: <b><?php echo $form['saf_no'] ?></b>
					                        </div>
					                    </div>

					                    <div class="form-group">
					                        <div class="col-sm-4">
					                             Ward No.: <b><?php echo $ward['ward_no'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             Owner Name: <b><?php echo $form['owner_name'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             Phone No.: <b><?php echo $form['phone_no'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             Owner Address: <b><?php echo $form['owner_address'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                            <a href="<?php echo base_url('safdistribution/form_distribute') ?>" class="btn btn-danger"> Back </a>
					                        </div>
					                    </div>

                                         </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>