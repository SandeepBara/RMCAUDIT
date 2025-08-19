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
					<li class="active">Water JE List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<a href="<?php echo base_url('Water_JE/index') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Ward No. :</b>
								</div>
								<div class="col-sm-3">
									<?=$ward['ward_no']?$ward['ward_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Category :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['category']?$basic_details['category']:N/A; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Application No. :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['application_no']?$basic_details['application_no']:N/A; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Connection Through :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['connection_through']?$basic_details['connection_through']:N/A; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Connection Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['connection_type']?$basic_details['connection_type']:N/A; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Property Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['property_type']?$basic_details['property_type']:N/A; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Pipeline Type :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['pipeline_type']?$basic_details['pipeline_type']:N/A; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Area :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['area_sqft']?$basic_details['area_sqft'].'sqft':N/A; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<b>Address :</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['address']?$basic_details['address']:N/A; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Dealing Assistant Remarks</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['remarks']?$basic_details['remarks']:N/A; ?> 
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Owner Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
									  <th scope="col">Owner Name</th>
									  <th scope="col">Guardian's Name</th>
									  <th scope="col">Mobile No</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php if($owner_details==""){ ?>
										<tr>
											<td style="text-align:center;"> Data Not Available...</td>
										</tr>
									<?php }else{ ?>
									<?php foreach($owner_details as $owner_details): ?>
										<tr>
										  <td><?php echo $owner_details['applicant_name']; ?></td>
										  <td><?php echo $owner_details['father_name']; ?></td>
										  <td><?php echo $owner_details['mobile_no']; ?></td>
										</tr>
										<tr class="success">
											<th>Document Name</th>
											<th>Applicant Image</th>
											<th>Status</th>
											<th>Remarks</th>

										</tr>
										<?php
                                        foreach($owner_details['saf_owner_img_list'] as $imgval):
                                        ?>
										<tr>
											<td>Consumer Photo</td>
											<td><a href="<?=base_url();?>/writable/uploads/<?=$imgval["document_path"];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$imgval["document_path"];?>" style="width: 40px; height: 40px;"></a></td>
											<td><?php
												if($imgval['verify_status']=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($imgval['verify_status']=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												else if($imgval['verify_status']=="0")
												{
													echo "<span class='text-danger'>New</span>";
												}
												?></td>
											<td><?=$imgval['remarks'];?></td>

										</tr>
                                        <?php endforeach; ?>
										<tr class="success">
											<th> Document Name</th>
											<th>Applicant Document</th>
											<th></th>
											<th></th>
										</tr>
										<?php
										foreach($owner_details['saf_owner_doc_list'] as $docval):
										?>
										<tr>
											<td>Photo Id Proof (<span class="text-danger"><?=$docval['document_name'];?></span>)</td>
											<?php
											$exp_ow_doc=explode('.',$docval['document_path']);
											$exp_ow_doc_ext=$exp_ow_doc[1];
											?>
											<td>
												<?php
												if($exp_ow_doc_ext=='pdf')
												{
												?>
												<a href="<?=base_url();?>/writable/uploads/<?=$docval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
												<?php
												}
												else
												{
												?>
												<a href="<?=base_url();?>/writable/uploads/<?=$docval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$docval['document_path'];?>" style="width: 40px; height: 40px;"></img>
												<?php
												}
												?>
											</td>
											<td><?php
												if($docval['verify_status']=="1")
												{
													echo "<span class='text-danger'>Verified</span>";
												}
												else if($docval['verify_status']=="2")
												{
													echo "<span class='text-danger'>Rejected</span>";
												}
												else if($docval['verify_status']=="0")
												{
													echo "<span class='text-danger'>New</span>";
												}
												?></td>
											<td><?=$docval['remarks'];?></td>

										</tr>
                                        <?php endforeach; ?>
										<?php endforeach; ?>
										<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Documents</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<tr>
										<th>Document Name</th>
										<th>Document</th>
										<th>Status</th>
										<th>Remarks</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($payment_receipt_doc as $prval):
									?>
									<tr style="border-bottom:2px solid black;">
										<td>Last Payment Receipt</td>
										<?php
										$exp_pr_doc=explode('.',$prval['document_path']);
										$exp_pr_doc_ext=$exp_pr_doc[1];
										?>
										<td>
											<?php
											if($exp_pr_doc_ext=='pdf')
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$prval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php
											}
											else
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$prval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prval['document_path'];?>" style="width: 40px; height: 40px;"></img>
											<?php
											}
											?>
										</td>

										<td><?php
											if($prval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($prval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											else if($prval['verify_status']=="0")
											{
												echo "<span class='text-danger'>New</span>";
											}
											?></td>
										<td><?=$prval['remarks'];?></td>
									</tr>
									<?php endforeach; ?>
									<?php
									foreach($address_proof_doc as $apval):
									?>
									<tr style="border-bottom:2px solid black;">
										<td>Address Proof (<span class="text-danger"><?=$apval['document_name'];?></span>)</td>
										<?php
										$exp_ap_doc=explode('.',$apval['document_path']);
										$exp_ap_doc_ext=$exp_ap_doc[1];
										?>
										<td>
											<?php
											if($exp_ap_doc_ext=='pdf')
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$apval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php
											}
											else
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$apval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$apval['document_path'];?>" style="width: 40px; height: 40px;"></img>
											<?php
											}
											?>
										</td>
										<td><?php
											if($apval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($apval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											else if($apval['verify_status']=="0")
											{
												echo "<span class='text-danger'>New</span>";
											}
											?></td>
										<td><?=$apval['remarks'];?></td>
									</tr>
									<?php endforeach; ?>

									<?php
									foreach($connection_doc as $cnval):
									?>
									<tr style="border-bottom:2px solid black;">
										<td>Connection Form</td>
										<?php
										$exp_cn_doc=explode('.',$cnval['document_path']);
										$exp_cn_doc_ext=$exp_cn_doc[1];
										?>
										<td>
											<?php
											if($exp_cn_doc_ext=='pdf')
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$cnval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php
											}
											else
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$cnval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$cnval['document_path'];?>" style="width: 40px; height: 40px;"></img>
											<?php
											}
											?>
										</td>
										<td><?php
											if($cnval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($cnval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											else if($cnval['verify_status']=="0")
											{
												echo "<span class='text-danger'>New</span>";
											}
											?></td>
										<td><?=$cnval['remarks'];?></td>
									</tr>
									<?php endforeach; ?>
									<?php
									foreach($electricity_doc as $elval):
									?>
									<tr style="border-bottom:2px solid black;">
										<td>Electricity Bill</td>
										<?php
										$exp_el_doc=explode('.',$elval['document_path']);
										$exp_el_doc_ext=$exp_el_doc[1];
										?>
										<td>
											<?php
											if($exp_el_doc_ext=='pdf')
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$elval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php
											}
											else
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$elval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$elval['document_path'];?>" style="width: 40px; height: 40px;"></img>
											<?php
											}
											?>
										</td>

										<td><?php
											if($elval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($elval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											else if($elval['verify_status']=="0")
											{
												echo "<span class='text-danger'>New</span>";
											}
											?></td>
										<td><?=$elval['remarks'];?></td>

									</tr>
									<?php endforeach; ?>

									<?php
									foreach($meter_bill_doc as $mbval):
									?>
									<tr style="border-bottom:2px solid black;">
										<td>Meter Bill</td>
										<?php
										$exp_mb_doc=explode('.',$mbval['document_path']);
										$exp_mb_doc_ext=$exp_mb_doc[1];
										?>
										<td>
											<?php
											if($exp_mb_doc_ext=='pdf')
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$mbval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php
											}
											else
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$mbval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$mbval['document_path'];?>" style="width: 40px; height: 40px;"></img>
											<?php
											}
											?>
										</td>

										<td><?php
											if($mbval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($mbval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											else if($mbval['verify_status']=="0")
											{
												echo "<span class='text-danger'>New</span>";
											}
											?></td>
										<td><?=$mbval['remarks'];?></td>

									</tr>
									<?php endforeach; ?>
									<?php
									foreach($bpl_doc as $bplval):
									?>
									<tr style="border-bottom:2px solid black;">
										<td>BPL</td>
										<?php
										$exp_bpl_doc=explode('.',$bplval['document_path']);
										$exp_bpl_doc_ext=$exp_bpl_doc[1];
										?>
										<td>
											<?php
											if($exp_bpl_doc_ext=='pdf')
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$bplval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
											<?php
											}
											else
											{
											?>
											<a href="<?=base_url();?>/writable/uploads/<?=$bplval['document_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$bplval['document_path'];?>" style="width: 40px; height: 40px;"></img>
											<?php
											}
											?>
										</td>

										<td><?php
											if($bplval['verify_status']=="1")
											{
												echo "<span class='text-danger'>Verified</span>";
											}
											else if($bplval['verify_status']=="2")
											{
												echo "<span class='text-danger'>Rejected</span>";
											}
											else if($bplval['verify_status']=="0")
											{
												echo "<span class='text-danger'>New</span>";
											}
											?></td>
										<td><?=$bplval['remarks'];?></td>

									</tr>
									<?php endforeach; ?>

								</tbody>
							</table>
						</div>
					</div>
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
										<button class="btn btn-danger" id="btn_verify_submit" name="btn_verify_submit" type="submit">Verify & Forward</button>
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
});
</script>