<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<style>
.row{line-height:25px;}
</style>
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
				<div class="panel-control">
					<a href="<?php echo base_url('SafDistribution/form_distribute_list') ?>" class="btn btn-info"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back To List </a>
				</div>
                <h3 class="panel-title"><b>Form Details</b></h3>
            </div>
            <div class="panel-body">
                <div class="row" style="margin: 0 5px;">
					<div class="col-sm-12">
						<div class="col-sm-6">
							SAF No.
						</div>
						<div class="col-sm-6">
							<b><?=$form['saf_no']?$form['saf_no']:"N/A" ?></b>
						</div>
					</div>
				
					<div class="col-sm-12">
						<div class="col-sm-6">
							Form No.
						</div>
						<div class="col-sm-6">
							<b><?=$form['form_no']?$form['form_no']:"N/A" ?></b>
						</div>
					</div>
				
					<div class="col-sm-12">
						<div class="col-sm-6">
							Ward No.
						</div>
						<div class="col-sm-6">
							<b><?=$ward['ward_no']?$ward['ward_no']:"N/A"; ?></b>
						</div>
					</div>
				
					<div class="col-sm-12">
						<div class="col-sm-6">
							Owner Name
						</div>
						<div class="col-sm-6">
							<b><?=$form['owner_name']?$form['owner_name']:"N/A" ?></b>
						</div>
					</div>
				
					<div class="col-sm-12">
						<div class="col-sm-6">
							Phone No.
						</div>
						<div class="col-sm-6">
							<b><?=$form['phone_no']?$form['phone_no']:"N/A" ?></b>
						</div>
					</div>
				
					<div class="col-sm-12">
						<div class="col-sm-6">
							Owner Address
						</div>
						<div class="col-sm-6">
							<b><?=$form['owner_address']?$form['owner_address']:"N/A" ?></b>
						</div>
					</div>
				
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>