
<?= $this->include('layout_vertical/header');?>

<style>
	
.row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
					<div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Property Details</li>
                    </ol>
                </div>

				<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<div class="panel-control">
								<button onclick="goBack()" class="btn btn-info">Go Back</button>
							</div>
							<h3 class="panel-title">Basic Details</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-3">
									<b>Ward No.</b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-3">
									<b>Holding No. </b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['holding_no']?$basic_details['holding_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Property Type </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
								</div>
								
								<div class="col-md-3">
									<b>Ownership Type </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Address </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['prop_address']?$basic_details['prop_address']:"N/A"; ?>
								</div>
								<div class="col-sm-3">
									<b>15 Digit Unique House No. </b>
								</div>
								<div class="col-sm-3">
									<?=$basic_details['new_holding_no']?$basic_details['new_holding_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Area Of Plot </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['area_of_plot']?$basic_details['area_of_plot']:"N/A"; ?> ( In decimal)
								</div>
								
								<div class="col-md-3">
									<b>Mauja Name </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['village_mauja_name']?$basic_details['village_mauja_name']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b> Khata </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['Khata_no']?$basic_details['Khata_no']:"N/A"; ?>
								</div>
								
								<div class="col-md-3">
									<b> Plot No. </b>
								</div>
								<div class="col-md-3">
									<?=$basic_details['plot_no']?$basic_details['plot_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								
								<div class="col-md-3">
									<b>Rainwater Harvesting Provision </b>
								</div>
								<div class="col-md-3">
									<?php if($basic_details['is_water_harvesting']=='t'){ ?>
									YES
									<?php } else if($basic_details['is_water_harvesting']=='f') { ?>
									No
									<?php } else { ?>
									N/A
									<?php } ?>
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
									  <th scope="col">Owner Name</th>
									  <th scope="col">R/W Guardian</th>
									  <th scope="col">Guardian's Name</th>
									  <th scope="col">Mobile No</th>
								</thead>
								<tbody>
									<?php if($owner_details): ?>
										<?php foreach($owner_details as $owner_details): ?>
										<tr>
										  <td><?php echo $owner_details['owner_name']; ?></td>
										  <td><?php echo $owner_details['relation_type']; ?></td>
										  <td><?php echo $owner_details['guardian_name']; ?></td>
										  <td><?php echo $owner_details['mobile_no']; ?></td>
										</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="4" style="text-align:center;color:red;"> Data Are Not Available!!</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Tax Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<th scope="col">SL. No.</th>
									<th scope="col">ARV</th>
									<th scope="col">Effected From</th>
									<th scope="col">Holding Tax</th>
									<th scope="col">Water Tax</th>
									<th scope="col">Conservancy/Latrine Tax</th>
									<th scope="col">Education Cess</th>
									<th scope="col">Health Cess</th>
									<th scope="col">RWH Penalty</th>
									<th scope="col">Quarterly Tax</th>
									<th scope="col">Status</th>
								</thead>
								<tbody>
									
										<?php if($tax_list):
											$i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
										<?php foreach($tax_list as $tax_list): 
											$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
										?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $tax_list['arv']; ?></td>
										<td><?php echo $tax_list['qtr']; ?> / <?php echo $tax_list['fy']; ?></td>
										<td><?php echo $tax_list['holding_tax']; ?></td>
										<td><?php echo $tax_list['water_tax']; ?></td>
										<td><?php echo $tax_list['latrine_tax']; ?></td>
										<td><?php echo $tax_list['education_cess']; ?></td>
										<td><?php echo $tax_list['health_cess']; ?></td>
										<td><?php echo $tax_list['additional_tax']; ?></td>
										<td><?php echo $qtr_tax; ?></td>  
										<?php if($i>$lenght){ ?>
											<td style="color:red;">Current</td>
										<?php } else { ?>
											<td>Old</td>
										<?php } ?>
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="11" style="text-align:center;color:red;"> Data Are Not Available!!</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					
						<form action="<?php echo base_url('remove_waterharvesting/remove_additionaltax_details');?>" method="post" role="form" class="php-email-form" enctype="multipart/form-data">		
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">RWH Penalty</h3>
								</div>
								<div id="loadingDivs" style="display: none; background: url(http://192.168.0.113/RMCDMC/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
								<input type="hidden" class="form-control" id="custm_id" name="custm_id" value="<?php echo $basic_details["prop_dtl_id"]; ?>">
								<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
									<tbody>
										<tr>
											<td>Due Upto Year</td>
											<td>
												<div class="form-group">
													<select id="due_upto_year" name="due_upto_year" class="form-control m-t-xxs">
														<option value="">Select Financial Year</option>
														<?php if($fydemand): ?>
														<?php foreach($fydemand as $post): ?>
														<option value="<?php echo $post['fy_id']; ?>"><?php echo $post['fy']; ?></option>
														<?php endforeach; ?>
														<?php endif; ?>
													</select>
												</div>
											</td>
											<td>Due Upto Quarter <span class="text-danger">*</span>
											</td>
											<td>
												<select class="form-control" id="date_upto_qtr" name="date_upto_qtr">
													<option value="" >Choose...</option>
												</select>
											</td>
										</tr>
										<tr>
											<td colspan="2">Upload Document </td>
											<td colspan="2">
												<div class="form-group">
													<input type="file" class="form-control" id="doc_path" name="doc_path" value="" accept=".pdf" />
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="2">Remarks </td>
											<td colspan="2">
												<div class="form-group">
													<textarea class="form-control" id="remarks" name="remarks" value=""></textarea>
												</div>
											</td>
										</tr>
										
									</tbody>
								</table><br><br>
							</div>
							<div class="panel">
								<div class="panel-body text-center">
								<?php if($basic_details['is_water_harvesting']=='t'){ ?>
									<button type="submit" class="btn btn-purple btn-labeled" id="add_additional_tax" name="add_additional_tax">Add RWH Penalty</button>
								<?php } else if($basic_details['is_water_harvesting']=='f'){ ?>
									<button type="submit" class="btn btn-danger btn-labeled" id="remove_additional_tax" name="remove_additional_tax">Remove RWH Penalty</button>
								<?php } ?>
								</div>
							</div>
						</form>
					
					
				</div>




		<?= $this->include('layout_vertical/footer');?>

		<script>
		function goBack() {
		  window.history.back();
		}
		
		$("#doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['pdf']) == -1) {
				$("#doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 5242880) { 
				$("#doc_path").val("");
				alert("Try to upload file less than 5MB"); 
			}
			keyDownNormal(input);
		});
		
		$('#due_upto_year').change(function(){
			var custm_id = $("#custm_id").val();
			var due_upto_year = $("#due_upto_year").val();
			if(due_upto_year!=""){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('/remove_waterharvesting/ajax_gatequarter'); ?>",
					dataType:"json",
					data:
					{
						due_upto_year:due_upto_year,custm_id:custm_id
					},
					beforeSend: function() {
						$("#loadingDivs").show();
					},
					success: function(data){
						$("#loadingDivs").hide();
						console.log(data);
						if(data.response==true){
							$("#date_upto_qtr").html(data.data);
						}else{
							$("#date_upto_qtr").html("<option value=''>Select Quarter</option>");
						}
					}
				});
			}

		});
		
		
		
		
		$("#remove_additional_tax").click(function(){
			proceed = true;
            var date_upto_qtr = $("#date_upto_qtr").val();
            var due_upto_year = $("#due_upto_year").val();
			var doc_path = $("#doc_path").val();
            var remarks = $("#remarks").val();
			
			if(due_upto_year=="")
			{
				alert("Please Select Financial Year");
				$("#due_upto_year").css('border-color', 'red');
				return false;
			}
            if(date_upto_qtr=="")
			{
				alert("Please Select Quater");
				$("#date_upto_qtr").css('border-color', 'red');
				return false;
			}

			
			if(doc_path=="")
			{
				alert("Please upload document for removing water harvesting");
				$("#doc_path").css('border-color', 'red');
				return false;
			}

			if(remarks=="")
			{
				alert("Please mention remarks for removing water harvesting");
				$("#remarks").css('border-color', 'red');
				return false;
			}
			
			confirm("Are you sure to remove water harvesting");
			$("#remove_additional_tax").hide();
		return process;
		 });
		 $("#due_upto_year").change(function(){ $(this).css('border-color',''); });
		 $("#date_upto_qtr").change(function(){ $(this).css('border-color',''); });
		 $("#doc_path").change(function(){ $(this).css('border-color',''); });
		 $("#remarks").change(function(){ $(this).css('border-color',''); });
		 
		</script>
