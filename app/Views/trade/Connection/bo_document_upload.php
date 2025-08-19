<?= $this->include('layout_vertical/header');?>

<?php $display='';?>
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
			<li><a href="#">Trade</a></li>
			<li class="active">Back To Citizen</li>
		</ol>
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<!--End breadcrumb-->
	</div>
	<!--Page content-->
	<!--===================================================-->
	<div id="page-content">
		<div class="row" >
			<div class="col-md-12">
				<b><h4 style="color:red;">
					<?php
					if(!empty($errors)){
						echo $errors;
					}
					?>
				</h4>
			</b>
		</div>
	</div>
	<!-------Owner Details-------->
	<div class="panel panel-bordered panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">Owner Details
				<button onclick="openWin()" class="btn btn-md btn-dark pull-right">Edit Basic Details</button>
			</h3>
		</div>
		<div class="panel-body" style="padding-bottom: 0px;">
			<div class="table-responsive">
				<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Name</th>
							<th>Mobile No.</th>
							<th>Application No.</th>
							<th colspan="2">Firm Name</th>
						</tr>
					</thead>
					<tbody>	
						<?php
						if(isset($owner_list)):
							if(empty($owner_list)):
								?>
								<tr>
									<td style="text-align:center;"> Data Not Available...</td>
								</tr>
							<?php else: ?>
								<?php
								$i=1;
								foreach($owner_list as $value):
									$j=$i++;
									?>
									<tr>
										<td><?=$value['owner_name'];?><input type="hidden" id="owner_name<?=$j;?>" value="<?=$value['owner_name'];?>"/></td>
										<td><?=$value['mobile'];?><input type="hidden" id="mobile_no<?=$j;?>" value="<?=$value['mobile'];?>"/></td>
										<td><?=$trade_conn_dtl['application_no'];?><input type="hidden" id="application_no<?=$j;?>" value="<?=$trade_conn_dtl['application_no'];?>"/></td>
										<td colspan="2"><?=$trade_conn_dtl['firm_name'];?><input type="hidden" id="firm_name<?=$j;?>" value="<?=$trade_conn_dtl['firm_name'];?>"/></td>
										
									</tr>
									
									
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<!-------Propery Type-------->
	<div class="panel panel-bordered panel-dark">
		<div class="panel-heading">
			<h3 class="panel-title">Document Details</h3>

		</div>
		<div class="panel-body" style="">
			<div class="">
				<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Document Name</th>
							<th>Document</th>
							<th>Verify/Reject</th>
							<th>Reject Remarks</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$cnt=0;
						foreach($doc_details as $docval):
							$cnt++;
							?>
							<tr>
								<?php if($docval["firm_owner_dtl_id"]==""): ?>
									<td><span id = "<?=$cnt?>"><?=$docval["doc_for"]?></span> (pdf Only)</td>
								<?php else: ?>
									<td><span id = "<?=$cnt?>"><?=$docval["doc_for"]?></span> (pdf Only)</td>
								<?php endif;?>
								<td>
									<a href="<?=base_url();?>/getImageLink.php?path=<?=$docval['document_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
									<td>
										<?php
										if($docval['verify_status']=="1")
										{
											echo "<span class='text-success'>Verified</span>";
										}
										else if($docval['verify_status']=="2")
										{
											echo "<span class='text-danger'>Rejected</span>";
										}
										else if($docval['verify_status']=="0")
										{
											echo "<span class='text-info'>New</span>";
										}
										?>
									</td>
									<td><?=$docval['remarks'];?></td>
									<td>
										<?php											
										if(($docval['countuploaddoc']["doc_cnt"]>$docval['countrejectdoc']["doc_cnt"]) and  $docval['verify_status']=="0")
										{ 
											?>
											<span class="text-success">Uploaded Successfully!!</span>
											<form method="post" enctype="multipart/form-data" action="">
												<div class="row">
													<div class="col-md-4">
														<input type="hidden" id="doc_for<?=$cnt?>" name="doc_for<?=$cnt?>" value="<?=$docval["doc_for"]?>">
														<input type="hidden" id="firm_owner_dtl_id<?=$cnt?>" name="firm_owner_dtl_id<?=$cnt?>" value="<?=$docval["firm_owner_dtl_id"]?>">
														<?php if($docval["firm_owner_dtl_id"]!="" && $docval["document_id"]>0): ?>
															<select id="doc_mstr_id<?=$cnt?>" name="doc_mstr_id<?=$cnt?>" class="form-control doc_mstr_id" required>
																<option value="">Select</option>
																<?php 
																if($idprooflist)
																{
																	foreach($idprooflist as $proofval)
																	{
																		?>
																		<option value="<?php echo $proofval['id'];?>" <?php if($proofval['id'] == $docval['document_id']){ echo 'selected="selected"'; } ?> ><?=$proofval["doc_name"]?></option>                                           
																		<?php 
																	}
																}
																?>
															</select>
														<?php elseif($docval["firm_owner_dtl_id"]!="" && $docval["document_id"]==0): ?>
															<select id="doc_mstr_id<?=$cnt?>" name="doc_mstr_id<?=$cnt?>" class="form-control doc_mstr_id" required>
																<option value="">Select</option>
																<option value="0" selected>Consumer Photo</option>
															</select>
														<?php else: ?>
															<select id="doc_mstr_id<?=$cnt?>" name="doc_mstr_id<?=$cnt?>" class="form-control doc_mstr_id" required>
																<option value="">Select</option>
																<?php
																if(isset($docval["docfor"])):
																	foreach($docval["docfor"] as $value):
																		?>
																		<option value="<?=$value['id']?>" <?php if($value['id'] == $docval['document_id']){ echo 'selected="selected"'; } ?>><?=$value['doc_name']?></option>
																		<?php 
																	endforeach; 
																	?>
																	<?php 
																endif; 
																?>
															</select>
														<?php endif; ?>
													</div>
													<div class="col-md-4">
														<input type="file" name="doc_path<?=$cnt?>" id="doc_path<?=$cnt?>" class="form-control" onchange="ext(this,<?=$cnt?>)" accept=".pdf" required />
														<span class='text-danger' id="doc_path<?=$cnt?>sms"></span>
													</div>
													<div class="col-md-4">
														<button type="submit" name="btn_doc" value="<?=$cnt?>" id="btn_doc" class="btn btn-warning"  >Edit</button>
													</div>
												</div>
											</form>
											<?php 
										} 
										else if(($docval['countuploaddoc']["doc_cnt"]==$docval['countrejectdoc']["doc_cnt"]) and $docval['verify_status']=="2")
										{
											$display='none';
											?>
											<form method="post" enctype="multipart/form-data" action="">
												<div class="row">
													<div class="col-md-4">
														<input type="hidden" id="doc_for<?=$cnt?>" name="doc_for<?=$cnt?>" value="<?=$docval["doc_for"]?>">
														<input type="hidden" id="firm_owner_dtl_id<?=$cnt?>" name="firm_owner_dtl_id<?=$cnt?>" value="<?=$docval["firm_owner_dtl_id"]?>">

														<?php if($docval["firm_owner_dtl_id"]!="" && $docval["document_id"]>0): ?>
															<select id="doc_mstr_id<?=$cnt?>" name="doc_mstr_id<?=$cnt?>" class="form-control doc_mstr_id" required>
																<option value="">Select</option>
																<?php 
																if($idprooflist)
																{
																	foreach($idprooflist as $proofval)
																	{
																		?>
																		<option value="<?php echo $proofval['id'];?>" <?php if($proofval['id'] == $docval['document_id']){ echo 'selected="selected"'; } ?> ><?=$proofval["doc_name"]?></option>                                           
																		<?php 
																	}
																}
																?>
															</select>
														<?php elseif($docval["firm_owner_dtl_id"]!="" && $docval["document_id"]==0): ?>
															<select id="doc_mstr_id<?=$cnt?>" name="doc_mstr_id<?=$cnt?>" class="form-control doc_mstr_id" required>
																<option value="">Select</option>
																<option value="0">Consumer Photo</option>
															</select>
															
														<?php else: ?>
															<select id="doc_mstr_id<?=$cnt?>" name="doc_mstr_id<?=$cnt?>" class="form-control doc_mstr_id" required>
																<option value="">Select</option>
																<?php
																if(isset($docval["docfor"])):
																	foreach($docval["docfor"] as $value):
																		?>
																		<option value="<?=$value['id']?>" ><?=$value['doc_name']?></option>
																		<?php 
																	endforeach; 
																	?>
																	<?php 
																endif; 
																?>
															</select>
														<?php endif; ?>
													</div>
													<div class="col-md-4">
														<input type="file" name="doc_path<?=$cnt?>" id="doc_path<?=$cnt?>" class="form-control" accept=".pdf" required onchange="ext(this,<?=$cnt?>)" />
														<span class='text-danger' id="doc_path<?=$cnt?>sms"></span>
													</div>
													<div class="col-md-4">
														<button type="submit" name="btn_doc" value="<?=$cnt?>" id="btn_doc" class="btn btn-warning"  >Upload</button>
													</div>
												</div>
											</form>
											<?php
											
											
										}
										?>
									</td>
								</tr>
							<?php endforeach;  ?>
							
							
							
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">BTC Remark (main)</h3>

			</div>
			<div class="panel-body" style="">
				<div class="col-sm-12 text-danger">
					<strong><?php echo (isset($level_sent_back_data))?$level_sent_back_data['remarks']:"N/A";   ?></strong><br>
					<small class="text-dark pull-right">----- Remark Created at : <?= date('d-m-Y: h:i A',strtotime($level_sent_back_data['update_date_time'])); ?></small>

				</div>
			</div>
		</div>
		
		<div class="panel panel-bordered panel-dark">
			<div class="panel-body">
				<div class="col-sm-2 col-sm-offset-5">
					<a class="btn btn-primary" onclick="hideSend()" id="sendToLevel"  href="<?php echo base_url('BO_TBackToCitizen/send_rmc/'.$trade_conn_dtl['id']);?>" role="button" style="display:<?=$display?>">Send To Level</a>
					
				</div>
			</div>
		</div>
		
	</div>
</div>



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
// $( ".doc_mstr_id" ).change(function() {
//      var selectedText = $(this).find("option:selected").text();
//     var selectedid = $(this).attr('id');
//     var res = selectedid[selectedid.length-1];
//     var doc_for_id = 'doc_for'+res;
// 	var firm_owner_dtl_id = $("#firm_owner_dtl_id"+res).val();
//     var doc_for = $("#"+doc_for_id).val();
//  	var doc_for_split = doc_for.split("(");
//     var final_doc_for = doc_for_split[0] + '('+selectedText+')';
// 	if(firm_owner_dtl_id)
// 	{
// 		$("#"+doc_for_id).val(final_doc_for);

// 	}

//  });
</script>
<script type="text/javascript">
	$(document).ready( function () { 
		$("#applicant_image_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
				$("#applicant_image_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 2097152) {
				$("#applicant_image_path").val("");
				alert("Try to upload file less than 2MB"); 
			}
			keyDownNormal(input);
		});
		$("#owner_doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['jpg','jpeg','png','pdf']) == -1) {
				$("#owner_doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 5242880) {
				$("#owner_doc_path").val("");
				alert("Try to upload file less than 5MB"); 
			}
			keyDownNormal(input);
		});
		$("#pr_doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['jpg','jpeg','png','pdf']) == -1) {
				$("#pr_doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 5242880) {
				$("#pr_doc_path").val("");
				alert("Try to upload file less than 5MB"); 
			}
			keyDownNormal(input);
		});
		$("#rc_doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['jpg','jpeg','png','pdf']) == -1) {
				$("#rc_doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 5242880) {
				$("#rc_doc_path").val("");
				alert("Try to upload file less than 5MB"); 
			}
			keyDownNormal(input);
		});
		$("#ad_doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['jpg','jpeg','png','pdf']) == -1) {
				$("#ad_doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 5242880) {
				$("#ad_doc_path").val("");
				alert("Try to upload file less than 5MB"); 
			}
			keyDownNormal(input);
		});
		$("#fa_doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['jpg','jpeg','png','pdf']) == -1) {
				$("#fa_doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 5242880) {
				$("#fa_doc_path").val("");
				alert("Try to upload file less than 5MB"); 
			}
			keyDownNormal(input);
		});
	});
</script>

<script>
	function ext(inputs,count) {
		var input = inputs;
		var text = document.getElementById(count).innerHTML;
		var ext = $(inputs).val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['pdf']) == -1) {
			document.getElementById(inputs.id+"sms").innerHTML='invalid document type';
			$(inputs).val('');
			return;
		}
		else if (input.files[0].size > 2097152)
		{ 
			document.getElementById(inputs.id+"sms").innerHTML='Try to upload file less than 2MB';
            //alert("Try to upload file less than 2MB"); 
            $(inputs).val('');
            return;
        }
        else
        {
        	document.getElementById(inputs.id+"sms").innerHTML='';
        }
        //keyDownNormal(input);
    }
    
    function openWin(){
    	window.open("<?php echo base_url('Trade_Apply_Licence/updateApplicationAtAnyStage/'.md5($owner_list[0]['apply_licence_id'])) ?>","_blank", "width=800,height=800");
    }

</script>
<script>
	function hideSend() {
		$('#sendToLevel').hide();
		$('#loadingDiv').show();

	}
</script>