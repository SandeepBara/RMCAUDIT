<?=$this->include("layout_mobi/header");?>

<style>
.row{line-height:25px;}
</style>
<!--CONTENT CONTAINER-->
	<div id="content-container">
    <!--Page content-->
		<div id="page-content">
			
            <!-------Transfer Mode-------->
			<?php if(isset($posts)){ ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
					<div class="panel-control">
						<a href="<?php echo base_url('Mobi/accepted_history/');?>" type="button"class="btn btn-info btn-labeled">Accepted History</a>
					</div>
                    <h3 class="panel-title">Inbox Details</h3>
                </div>
                <div class="panel-body">
					
					<?php foreach($posts as $value): ?>
					<form method="post" enctype="multipart/form-data" id="formValidate" action="<?php echo base_url('Mobi/accept_request/');?>">
						<input type="hidden" name="new_holding_no" id="new_holding_no" value="<?=$value['new_holding_no']?$value['new_holding_no']:"N/A"; ?>">
						<input type="hidden" name="holding_no" id="holding_no" value="<?=$value['holding_no']?$value['holding_no']:"N/A"; ?>">
						<input type="hidden" name="mobile_no" id="mobile_no" value="<?=$value['mobile_no']?$value['mobile_no']:"N/A"; ?>">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading" style="background-color:#509096;">
								<h3 class="panel-title"><b style="color:#41f705;"> Type :- </b><?=$value['type']?$value['type']:"N/A"; ?></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-3">
										<b>Owner Name</b>
									</div>
									<div class="col-sm-3">
										<?=$value['owner_name']?$value['owner_name']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Mobile No.</b>
									</div>
									<div class="col-sm-3">
										<?=$value['mobile_no']?$value['mobile_no']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<b>Holding No.</b>
									</div>
									<div class="col-sm-3">
										<?=$value['holding_no']?$value['holding_no']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>15 Digit Unique No.</b>
									</div>
									<div class="col-sm-3">
										<?=$value['new_holding_no']?$value['new_holding_no']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<b>Request Time</b>
									</div>
									<div class="col-sm-3">
										<?=$value['shedule_time']?$value['shedule_time']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Request Date</b>
									</div>
									<div class="col-sm-3">
										<?=$value['shedule_date']?$value['shedule_date']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<b>Address</b>
									</div>
									<div class="col-sm-3">
										<?=$value['address']?$value['address']:"N/A"; ?>
									</div>
									<div class="col-sm-3">
										<b>Subject</b>
									</div>
									<div class="col-sm-3">
										<?=$value['subject']?$value['subject']:"N/A"; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<input type="submit" name="accept_request" id="accept_request" value="Accept Request" style="font-weight: 800;" class="form-control bg bg-danger">
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php endforeach; ?>
					<?=pagination($pager);?>
                </div>
            </div>
			
            <?php }?>
        
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

