<?= $this->include('layout_vertical/header');?>
<?php
	$model_owners = "";
	$model_fathers = "";
	$model_mobile = "";
	$model_transection_no = "";
	$model_transection_total_paid = 0;
	$model_transection_total_amount = 0;
	$model_net_due_amount = 0;
?>
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
					<li><a href="#">Water</a></li>
					<li class="active">Water SH List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Water Connection Details View <span class="text-success"><?=$consumer_details['application_no']??'';?></span> <span class="text-danger"> <?=isset($dues) && $dues ?"(All Charge Are Not Clear)":"";?></span></h3>
						</div>
						<div class="panel-body">     

							<div class="row">
								<label class="col-md-2 bolder">Type of Connection </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['connection_type']; ?>
								</div>
								<label class="col-md-2 bolder">Connection Through </label>
								<div class="col-md-5 pad-btm">
									<div class="col-md-3">
										<?php echo $consumer_details['connection_through']; ?> 
									</div>
									<?php
									if(isset($consumer_details['connection_through_id'])  && in_array($consumer_details['connection_through_id'],[1,5]))
									{
										?>
										<a href="<?=!empty($PropSafLink)?$PropSafLink:"#"?>" class="col-md-3 pad-btm text-info link" target="_blank">
											<?=$consumer_details['connection_through_id']==1?$consumer_details['holding_no'] : $consumer_details['saf_no'] ; ?> 
										</a>
										<?php
									}
									?>
								</div>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Ward No </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['ward_no']; ?> 
								</div>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Property Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['property_type']; ?> 
								</div>
									<label class="col-md-2 bolder">Pipeline Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['pipeline_type']; ?> 
								</div>
							</div>
						
							<div class="row">
								<label class="col-md-2 bolder">Category </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['category']; ?> 
								</div>
									<label class="col-md-2 bolder">Owner Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['owner_type']; ?> 
								</div>
							</div>
						</div>
					</div>
				
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Owner Details</h3>
						</div>
						<div class="panel-body">
							<table class="table table-bordered table-responsive">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th class="bolder">Owner Name</th>
										<th class="bolder">Guardian Name</th>
										<th class="bolder">Mobile No.</th>
										<th class="bolder">Email ID</th>
										<th class="bolder">State</th>
										<th class="bolder">District</th>
										<th class="bolder">City</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if($owner_details)
									{
										foreach($owner_details as $val)
									{
									?>
									<tr>
										<td><?php echo $val['applicant_name'];?></td>
										<td><?php echo $val['father_name'];?></td>
										<td><?php echo $val['mobile_no'];?></td>
										<td><?php echo $val['email_id'];?></td>
										<td><?php echo $val['state'];?></td>
										<td><?php echo $val['district'];?></td>
										<td><?php echo $val['city'];?></td>
									</tr>
									<?php
									}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

            		<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Site Inspection </h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead class="bg-trans-dark text-dark">
												<tr>
													<th>#</th>
													<th>Inspected By</th>
													<th>Inspected On</th>
													<th>View</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i=0;
												if($site_inspection_details)
												foreach($site_inspection_details as $inspection)
												{
													?>
													<tr>
														<td><?=++$i;?></td>
														<td><?=$inspection["verified_by"];?></td>
														<td><?=$inspection["inspection_date"];?></td>
														<td>
															<?php
															if($inspection["verified_by"]=="JuniorEngineer")
															{
																?>
																<a onClick="myPopup('<?=base_url('WaterSiteInspection/index/'.md5($consumer_details['id']).'/'.md5($inspection['id']));?>','xtf','900','700');" class="btn btn-primary">
																	View
																</a>
																<?php
															}
															if($inspection["verified_by"]=="AssistantEngineer")
															{
																?>
																<a onClick="myPopup('<?=base_url('WaterTechnicalSiteInspection/view/'.md5($consumer_details['id']).'/'.md5($inspection['id']));?>','xtf','900','700');" class="btn btn-primary">
																	View
																</a>
																<?php
															}
															?>
														</td>
													</tr>
													<?php
												}
												else
												{
													?>
													<tr>
														<td colspan="4" class="text text-danger text-center">Data Are Not Available!!</td>
													</tr>
													<?php
												}
												?>
												<tr>
													
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Document List</h3>
						</div>
						<div class="panel-body" style="padding-bottom: 0px;">
							<div class="table-responsive">
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>#</th>
											<th>Document Name</th>
											<th>Document</th>
											<th>Verify/Reject</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$i=0; 
									//echo"<pre>";print_r($owner_details);echo"</pre>";
									foreach($doc_list as $doc)
									{
										//Checking if consumer document
										$owner_name=NULL;
										if($doc["applicant_detail_id"]>0)
										{
											$applicant_detail_id=$doc['applicant_detail_id']; //echo($applicant_detail_id);
											$owner = array_filter($owner_details, function ($var) use ($applicant_detail_id) {
												//return ($var['id'] == $applicant_detail_id);
												if($var['id'] == $applicant_detail_id)
													return ($var['applicant_name']);
											});
											foreach($owner as $vaule)
												$owner = $vaule;
											 $owner_name='<span class="text text-primary">('.$owner["applicant_name"].')</span>';
											 
										}
										?>
										<tr>
											<td><?=++$i;?></td>
											<td><?=$doc["document_name"];?> <?=$owner_name;?></td>
											<td>
												<a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["document_path"];?>" target="_blank" title="<?=$doc["document_name"];?>">
												<img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
												</a>
											</td>
											<td>
											<?php
											
											if($doc["verify_status"]==0) // Not Verified then verify
											{
												?>
												<form method="POST">
													<input type="hidden" name="applicant_doc_id" value="<?=$doc["id"];?>">
														<button type="submit" name="btn_verify" value="Verify" class="btn btn-success btn-rounded btn-labeled">
															<i class="btn-label fa fa-check"></i>
															<span> Verify </span>
														</button>

														<a class="btn btn-danger btn-rounded btn-labeled" role="button" data-toggle="modal" data-target="#rejectModal<?=$doc["id"];?>">
															<i class="btn-label fa fa-close"></i>
															<span> Reject </span>
														</a>
												</form>

												<div class="modal fade" id="rejectModal<?=$doc["id"];?>" style="display: none;">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
															<h4 class="modal-title"> Mention Reason For Document Rejection - <?=$doc["document_name"];?> <?=$owner_name;?> </h4>
															<button type="button" class="close" data-dismiss="modal">×</button>
															</div>
														
															
															<form method="POST">
																<div class="modal-body">
																	<input type="hidden" name="applicant_doc_id" value="<?=$doc["id"];?>">
																	<textarea type="text" name="remarks" id="remarks1" class="form-control" placeholder="Mention Remarks Here" required=""></textarea>
																</div>
															
															
																<div class="modal-footer">
																<input type="submit" name="btn_reject" value="Reject" class="btn btn-primary">
																</div>
															</form>
														</div>
													</div>
												</div>
												<?php
											}
											if($doc["verify_status"]==1) // Approved
											{
												?>
												<span class="text text-success text-bold">Approved</span>
												<?php
											}
											if($doc["verify_status"]==2) // Rejected
											{
												?>
												<span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" data-original-title="<?=$doc["remarks"];?>">Rejected</span>
												<?php
											}
											?>
											</td>
										</tr>
										<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="col-sm-6">
								<h3 class="panel-title">Payment Details</h3>
							</div>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
							<table class="table table-bordered" style="font-size:12px;">
							<tr>
								<th>S.No.</th>
								<th>Transaction No.</th>
								<th>Transaction Date</th>
								<th>Transaction Type</th>
								<th>Payment Mode</th>
								<th>Total Amount</th>
								<th>Penalty</th>
								<th>Rebate</th>
								<th>Paid Amount</th>
								<th>View</th>
							</tr>

							<tr>
							<?php
								if($transaction_details):
								$i=1;
								foreach($transaction_details as $val):
									$model_transection_no .= $val['transaction_no'].', ';
									$model_transection_total_paid += (!empty(trim($val['paid_amount']))?$val['paid_amount']:0);
									?>
									<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $val['transaction_no'];?></td>
											<td><?php echo date('d-m-Y',strtotime($val['transaction_date']));?></td>
											<td><?php echo $val['transaction_type'];?></td>
											<td><?php echo $val['payment_mode'];?></td>
											<td><?php echo $val['total_amount'];?></td>
											<td><?php echo $val['penalty'];?></td>
											<td><?php echo $val['rebate'];?></td>
											<td><?php echo $val['paid_amount'];?></td>
											<td><a target="blank" href="<?php echo base_url('WaterPayment/view_transaction_receipt/'.md5($val['related_id']).'/'.md5($val['id']));?>" style="color: #1919bd;">View</a></td>
									</tr>
											
									<?php
								endforeach;
								$model_transection_no = trim($model_transection_no,' ,');
								endif;

							?>
							</tr>
						</table>
							</div>
						</div>
					</div>
					<?= $this->include('water/water_connection/LevelRemarksTab');?>

					<div class="panel panel-bordered panel-dark">
						<div class="panel-body" style="padding-bottom: 0px;">
							<form method="post" class="form-horizontal" action="">
								<div class="form-group">
									<label class="col-md-2" >Remarks</label>
									<div class="col-md-10">
										<textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" ></textarea>
									</div>
								</div>
								<?php
								if(isset($form['verification_status']))
								{
								if($form['verification_status']=="0")
								{
								?>
								<div class="form-group">
									<label class="col-md-2" >&nbsp;</label>
									<div class="col-md-10">

										<button class="btn btn-info" id="btn_backward_submit" name="btn_backward_submit" type="submit">Backward</button>
										<!-- <button class="btn btn-danger" id="btn_reject_submit" name="btn_reject_submit" type="submit">Reject</button> -->
										<button class="btn btn-warning" id="btn_backtocitizen_submit" name="btn_backtocitizen_submit" type="submit">Back to Citizen</button>
										
										<button class="btn btn-success" id="btn_verify_submit" name="btn_verify_submit" type="submit">Verify & Forward</button>
										<!--<button class="btn btn-danger" id="btn_back_to_citizen_submit" name="btn_back_to_citizen_submit" type="submit">Back To Citizen</button>-->
									</div>
								</div>
								<?php
									}
								}
								?>
							</form>
						</div>
					</div>
                </div>
            
                <!--===================================================-->
                <!--End page content-->
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<!-- ///////modal start -->
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Image preview</h4>
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- //////modal end -->
<?= $this->include('layout_vertical/footer');?>
<script>
$(function() {
		$('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
			$('#imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		

});
</script>
<script>
$(document).ready(function(){

    $("#btn_verify_submit").click(function(){
        var proceed = true;
        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        return proceed;
    });
    $("#btn_backward_submit").click(function(){
        var proceed = true;
        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }

        return proceed;
    });
});
</script>
<script type="text/javascript">
function myPopup(myURL, title, myWidth, myHeight)
{
    var left = (screen.width - myWidth) / 2;
    var top = (screen.height - myHeight) / 4;
    var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}
</script>