<?= $this->include('layout_vertical/header'); ?>
<div id="content-container">
	<div id="page-head">
		<div id="page-title">
		</div>
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Property</a></li>
			<li><a href="<?=base_url("propDtl/full/".md5($prop_dtl_id));?>">Property Details</a></li>
			<li class="active">Demand Adjustment</li>
		</ol>
	</div>

	<div id="page-content">

			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Property Details</h3>
				</div>
			
<?= $this->include('common/basic_details'); ?>

			</div>


			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Owner Details</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered text-sm">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>Owner Name</th>
											<th>Guardian Name</th>
											<th>Relation</th>
											<th>Mobile No</th>
											<th>Aadhar No.</th>
											<th>PAN No.</th>
											<th>Email</th>
										</tr>
									</thead>
									<tbody id="owner_dtl_append">
									<?php
									if(isset($prop_owner_detail))
									{
										foreach ($prop_owner_detail as $owner_detail)
										{
											?>
											<tr>
												<td><?=$owner_detail['owner_name'];?></td>
												<td><?=$owner_detail['guardian_name'];?></td>
												<td><?=$owner_detail['relation_type'];?></td>
												<td><?=$owner_detail['mobile_no'];?></td>
												<td><?=$owner_detail['aadhar_no'];?></td>
												<td><?=$owner_detail['pan_no'];?></td>
												<td><?=$owner_detail['email'];?></td>
											</tr>
											<?php
										}
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Tax Details</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered text-sm">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Sl No.</th>
									<th>ARV</th>
									<th>Effect From</th>
									<th>Holding Tax</th>
									<th>Water Tax</th>
									<th>Conservancy/Latrine Tax</th>
									<th>Education Cess</th>
									<th>Health Cess</th>
									<th>RWH Penalty</th>
									<th>Quarterly Tax</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							if($prop_tax_list)
							{
								$i=1; $qtr_tax=0;$lenght= sizeOf($prop_tax_list);
								foreach($prop_tax_list as $tax_list)
								{
									$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
									?>
									<tr>
										<td><?=$i++;?></td>
										<td><?=round($tax_list['arv'], 2);?></td>
										<td><?=$tax_list['qtr'];?> / <?=$tax_list['fy'];?></td>
										<td><?=round($tax_list['holding_tax'], 2);?></td>
										<td><?=round($tax_list['water_tax'], 2);?></td>
										<td><?=round($tax_list['latrine_tax'], 2);?></td>
										<td><?=round($tax_list['education_cess'], 2);?></td>
										<td><?=round($tax_list['health_cess'], 2);?></td>
										<td><?=round($tax_list['additional_tax'], 2);?></td>
										<td><?=round($qtr_tax, 2); ?></td>
										<td>
											<?php 
											if($i>$lenght)
											{
												?>
												<span class="text text-success text-bold">Current</span>
												<?php 
											}
											else
											{
												?>
												<span class="text text-danger text-bold">Old</span>
												<?php 
											}
											?>
										</td>
									</tr>
									<?php 
								}
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		<form action="<?=base_url('paymnt_adjust/demand_adjust');?><?="/".md5($prop_dtl_id);?>" enctype="multipart/form-data" method="post" role="form" class="php-email-form">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Demand Adjust</h3>
				</div>
				<div class="panel-body">
					<input type="hidden" id="prop_dtl_id" name="prop_dtl_id" class="form-control" value="<?=$prop_dtl_id;?>">
					<div class="row">
						<div class="col-md-3">
							<label for="upto_fy_mstr_id">Demand Upto Quater/Fy<span class="text-danger">*</span></label>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select id="upto_fy_mstr_id" name="upto_fy_mstr_id" class="form-control m-t-xxs">
									<option value="">Select Financial Year</option>
									<?php if (isset($fydemand)) : ?>
										<?php foreach ($fydemand as $post) : ?>
											<option value="<?=$post['fy_id'];?>"><?=$post['fy'];?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select id="upto_qtr" name="upto_qtr" class="form-control m-t-xxs">
									<option value="">Select Quarter</option>
								</select>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="bill_doc_path">Upload Related Documents<span class="text-danger">*</span></label>
							<br>
							<span class="text-danger">( Preferred pdf : Maximum size of 2MB )</span>
						</div>
						<div class="col-md-6">
							<input type="file" class="form-control" id="bill_doc_path" name="bill_doc_path" accept="application/pdf" />
						</div>
					</div>
					<div class="row" style="line-height:25px;">
						<div class="col-md-3">
							<label for="remark">Remarks<span class="text-danger">*</span></label>
						</div>
						<div class="col-md-9">
							<textarea id="remark" name="remark" rows="2" cols="71"></textarea>
						</div>
					</div>
					<div class="col-md-3 pad-btm">
						<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" value="DEMAND ADJUSTMENT">DEMAND ADJUSTMENT</button>
					</div>
				</div>
			</div>
		</form>


	</div>


	<?= $this->include('layout_vertical/footer'); ?>

	<script>
		$("#btn_submit").click(function() {
			process = true;
			if ($("#upto_fy_mstr_id").val() == "") {
				$("#upto_fy_mstr_id").css('border-color', 'red'); process = false;
			}
			if ($("#upto_qtr").val() == "") {
				$("#upto_qtr").css('border-color', 'red'); process = false;
			}
			if ($("#bill_doc_path").val() == "") {
				$("#bill_doc_path").css('border-color', 'red'); process = false;
			}
			if ($("#remark").val() == "") {
				$("#remark").css('border-color', 'red'); process = false;
			}
			return process;
		});

		$("#bill_doc_path").change(function() {
			var input = this;
			var ext = $(this).val().split('.').pop().toLowerCase();
			if ($.inArray(ext, ['pdf']) == -1) {
				$("#bill_doc_path").val("");
				alert('invalid document type');
			}
			if (input.files[0].size > 2097152) {
				$("#bill_doc_path").val("");
				alert("Try to upload file less than 2MB");
			}
			$(this).css('border-color', '');
		});

		$('#upto_fy_mstr_id').change(function() {
			var prop_dtl_id = $("#prop_dtl_id").val();
			var upto_fy_mstr_id = $("#upto_fy_mstr_id").val();
			if (upto_fy_mstr_id != "") {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('/paymnt_adjust/ajax_gatequarter'); ?>",
					dataType: "json",
					data: {
						upto_fy_mstr_id: upto_fy_mstr_id,
						prop_dtl_id: prop_dtl_id
					},
					success: function(data) {
						if (data.response == true) {
							$("#upto_qtr").html(data.data);
						} else {
							$("#upto_qtr").html("<option value=''>Select Quarter</option>");
						}
					}
				});
			}

		});
		$("#upto_fy_mstr_id").change(function(){ $(this).css('border-color', ''); });
		$("#upto_qtr").change(function(){ $(this).css('border-color', ''); });
		$("#remark").change(function(){ $(this).css('border-color', ''); });
	</script>

	<script>
		function goBack() {
			window.history.back();
		}
	</script>