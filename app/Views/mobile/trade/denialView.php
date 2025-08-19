<?=$this->include("layout_mobi/header");?>

	<div id="content-container">
		<div id="page-content">
			
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title"><b>View Denial</b></h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" method="post">
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="firm_Name"><b>Firm Name</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['firm_name']?$denial_dtls['firm_name']:"N/A" ; ?>
								</div>
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="owner_name"><b>Owner Name</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['owner_name']?$denial_dtls['owner_name']:"N/A" ; ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="ward_no"><b>Ward No.</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['ward_mstr_id']?$denial_dtls['ward_mstr_id']:"N/A" ; ?>
								</div>
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="holding_no"><b>Holding No. (if any)</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['holding_no']?$denial_dtls['holding_no']:"N/A" ; ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="address"><b>Address</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['address']?$denial_dtls['address']:"N/A" ; ?>
								</div>
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="landmark"><b>Landmark</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['landmark']?$denial_dtls['landmark']:"N/A" ; ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="city"><b>City</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['city']?$denial_dtls['city']:"N/A" ; ?>
								</div>
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="pin_code"><b>Pin Code</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['pin_code']?$denial_dtls['pin_code']:"N/A" ; ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="licence_no"><b>License No. (if any)</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['licence_no']?$denial_dtls['licence_no']:"N/A" ; ?>
								</div>
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="mobile_no"><b>Mobile No. (if any)</b> </label>
								</div>
								<div class="col-md-3 pad-btm">
									<?=$denial_dtls['mobile_no']?$denial_dtls['mobile_no']:"N/A" ; ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="remarks"><b>Remarks</b> </label>
								</div>
								<div class="col-md-9 pad-btm">
									<?=$denial_dtls['remarks']?$denial_dtls['remarks']:"N/A" ; ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3 pad-btm">
									<label class="control-label" for="images"><b>Upload Image</b> </label>
								</div>
								<div class="col-md-9 pad-btm">
									<a href="<?=base_url();?>/writable/uploads/<?=$denial_dtls['doc_path'];?>" target="_blank">
										<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
                
<?=$this->include("layout_mobi/footer");?>


<script type="text/javascript">
    $('#from_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
    $('#to_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
	
	
	function modelInfo(msg){
		$.niftyNoty({
			type: 'info',
			icon : 'pli-exclamation icon-2x',
			message : msg,
			container : 'floating',
			timer : 5000
		});
	}
	<?php if($result = flashToast('denialView')) { ?>
		modelInfo('<?=$result;?>');
	<?php }?>
 </script>

