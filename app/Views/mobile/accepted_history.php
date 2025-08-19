<?=$this->include("layout_mobi/header");?>

<style>
.row{line-height:25px;}
</style>
<!--CONTENT CONTAINER-->
	<div id="content-container">
    <!--Page content-->
		<div id="page-content">
			
            <!-------Transfer Mode-------->
			<?php
			if(isset($accepted_history)){
			?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
					<div class="panel-control">
						<a href="<?php echo base_url('Mobi/inbox_details/');?>" type="button"class="btn btn-info btn-labeled">Back</a>
					</div>
                    <h3 class="panel-title">Accepted History</h3>
                </div>
                <div class="panel-body">
					<?php foreach($accepted_history as $value): ?>
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
									<b>TC Accepted Date & Time</b>
								</div>
								<div class="col-sm-3">
									<?=$value['accepted_date_time']?$value['accepted_date_time']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>Remarks</b>
								</div>
								<div class="col-sm-3">
									<?=$value['remarks']?$value['remarks']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
								<?php if($value['status']==2){ ?>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#visit_report">Visit Report</button>
								<?php } else if($value['status']==3){ ?>
									<b style="color:red;">You have already visited.</b>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
                    
					<div id="visit_report" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header" style="background-color: #83d8b9b8;">
									<button type="button" class="close"  style="color: #ce040d;font-size:30px;" data-dismiss="modal">&times;</button>
									<h4 class="modal-title" style="color: #ce040d;">Visiting Report</h4>
								</div>
								<form action="<?php echo base_url('Mobi/accepted_history/');?>" method="post">
									<div class="modal-body">
										<input type="hidden" class="form-control" id="holding_no" name="holding_no" value="<?php echo $value["holding_no"]??null; ?>">
										<input type="hidden" class="form-control" id="new_holding_no" name="new_holding_no" value="<?php echo $value["new_holding_no"]??null; ?>">
										<div class="row">
											<label class="col-md-4 text-bold">Enter Remarks</label>
											<div class="col-md-6 has-success pad-btm">
												<textarea id="visit_remarks" name="visit_remarks" class="form-control" value="" ></textarea>
											</div>
										</div>
										<button type="submit" class="btn btn-success btn-labeled" id="visit_done" name="visit_done">Submit</button>
									</div>
									
								</form>
							</div>
						</div>
					</div>
					<?=pagination($pager);?>			
				</div>
				
			</div>
			
            </div>
			
			
            <?php }?>
        
    </div>
    <!--End page content-->

<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

