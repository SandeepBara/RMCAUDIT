<?= $this->include('layout_vertical/header');?>
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
					<li class="active">Water EO List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Water Connection Details View - <?php echo $consumer_details['application_no']??null; ?> </h3>
						</div>
						<div class="panel-body">     
							<div class="row">
								<label class="col-md-2 bolder">Type of Connection </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['connection_type']??null; ?>
								</div>
								<label class="col-md-2 bolder">Connection Through </label>
								<div class="col-md-3 pad-btm">
												<?php echo $consumer_details['connection_through']??null; ?> 
											</div>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Property Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['property_type']??null; ?> 
								</div>
									<label class="col-md-2 bolder">Pipeline Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['pipeline_type']??null; ?> 
								</div>
							</div>
						
							<div class="row">
								<label class="col-md-2 bolder">Category </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['category']??null; ?> 
								</div>
									<label class="col-md-2 bolder">Owner Type </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['owner_type']??null; ?> 
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Property Details</h3>
						</div>
						<div class="panel-body">                     
							<div class="row">
								<label class="col-md-2 bolder">Ward No. </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['ward_no']??null; ?>
								</div>
								<?php

									if($consumer_details['prop_dtl_id']!="" and $consumer_details['prop_dtl_id']!=0)
									{
								?>
								<label class="col-md-2 bolder">Holding No. </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['holding_no']; ?> 
								</div>
								<?php   
									}
									else
									{
								?>
								<label class="col-md-2 bolder">SAF No. </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['saf_no']; ?> 
								</div>
								<?php
									}
								?>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Area in Sqft.</label>
								<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['area_sqft']; ?> 
								</div>
								<label class="col-md-2 bolder">Area in Sqmt.</label>
								<div class="col-md-3 pad-btm">
									<?php echo round($consumer_details['area_sqmt'],2); ?> 
								</div>
							</div>
						<div class="row">
								<label class="col-md-2 bolder">Address</label>
								<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['address']; ?> 
								</div>
								<label class="col-md-2 bolder">Landmark </label>
								<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['landmark']; ?> 
								</div>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Pin</label>
								<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['pin']; ?> 
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"> Owner Details</h3>
						</div>
						<div class="panel-body table-responsive">
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
							<h3 class="panel-title">Electricity Connection Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<label class="col-md-2 bolder">K No. </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['elec_k_no']; ?>
								</div>
								<label class="col-md-2 bolder">Bind Book No.</label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['elec_bind_book_no']; ?> 
							</div>
							</div>
							<div class="row">
								<label class="col-md-2 bolder">Electricity Account No. </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['elec_account_no']; ?> 
								</div>
								<label class="col-md-2 bolder">Electricity Category </label>
								<div class="col-md-3 pad-btm">
									<?php echo $consumer_details['elec_category']; ?> 
								</div>
							</div>
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
							<h3 class="panel-title">Document List</h3>
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
									foreach($doc_list as $doc)
									{
										//Checking if consumer document
										$owner_name=NULL;
										if($doc["applicant_detail_id"]>0)
										{
											$applicant_detail_id=$doc['applicant_detail_id'];
											$owner = array_filter($owner_details, function ($var) use ($applicant_detail_id) {
												//return ($var['id'] == $applicant_detail_id);
											//})[0];
											if($var['id'] == $applicant_detail_id)
													return ($var['applicant_name']);
											});
											foreach($owner as $vaule)
												$owner = $vaule;
											 //$owner_name='<span class="text text-primary">('.$owner["applicant_name"].')</span>';
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
							<h3 class="panel-title">Payment Details</h3>
						</div>

						<div class="panel-body table-responsive" style="padding-bottom: 0px;">
						 
							<table class="table table-responsive table-bordered table-striped">
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

				                    <?php
					                    if($transaction_details):
					                      $i=1;
					                      foreach($transaction_details as $val):
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
					                        <td><a href="<?php echo base_url('WaterPayment/view_transaction_receipt/'.md5($water_conn_id).'/'.md5($val['id']));?>" style="color: #1919bd;">View</a></td>
					                  </tr>
					                        
					                   <?php
					                      endforeach;
					                    endif;

					                   ?>


							</table>
						
						</div>
					</div>


					<?= $this->include('water/water_connection/LevelRemarksTab');?>
					
				    <div class="panel panel-bordered panel-dark">
						<div class="panel-body" style="padding-bottom: 0px;">
							<form method="post" class="form-horizontal" action="">
							
								<div class="form-group">
									<label class="col-md-2" >Remarks</label>
									<div class="col-md-10">
									   <textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" onkeypress ="return isAlphaNumCommaSlash(event);"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2" >&nbsp;</label>
									<div class="col-md-10">
									   <button class="btn btn-success" id="btn_approved_submit" name="btn_approved_submit" type="submit">Approve</button>
									   <!-- <button class="btn btn-danger" id="btn_reject_submit" name="btn_reject_submit" type="submit">Reject</button> -->
									   <button class="btn btn-warning" id="btn_backtocitizen_submit" name="btn_backtocitizen_submit" type="submit">Back to Citizen</button>
									   
									   <button class="btn btn-info" id="btn_backward_submit" name="btn_backward_submit" type="submit">Backward</button>
									</div>
								</div>                                                      
							</form>
						</div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
///////modal start
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
//////modal end
<?= $this->include('layout_vertical/footer');?>
<script>

	 function myPopup(myURL, title, myWidth, myHeight) {
            var left = (screen.width - myWidth) / 2;
            var top = (screen.height - myHeight) / 4;
            var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
         }




    $(function() {
        $('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
            $('#imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');   
        });
    });

    $(document).ready(function(){
     
        $("#approval_yes").click(function(){
            $('#remarks_div').css('display','none');
            $('#button_div').css('display','block');
            $('#btn_approved_submit').css('display','block');
            $('#btn_backward_submit').css('display','none');
        });
        $("#approval_no").click(function(){
            $('#remarks_div').css('display','block');
            $('#button_div').css('display','block');
            $('#btn_approved_submit').css('display','none');
            $('#btn_backward_submit').css('display','block');
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
		$("#btn_approved_submit").click(function(){
            var proceed = true;
            var remarks = $("#remarks").val();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }
            return proceed;
        });
		$("#btn_backtocitizen_submit").click(function(){
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
	function isAlphaNumCheck(val){
        var regex = /^[a-z0-9]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
    function isAlphaNumCommaSlashCheck(val){
        var regex = /^[a-z\d\\/,\s]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
	function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }
</script>
