<?= $this->include("layout_mobi/header"); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">
					<a href="<?=base_url('SafDistribution/list_of_form_distribute');?>" class="btn btn-info">Back</a>
				</div>
				<h3 class="panel-title"><b>Form Distribute</b></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12">
						<form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="col-sm-6">
										<center>
											<b>
												<h4 style="color:red;">
													<?php
													if (!empty($err_msg)) {
														echo $err_msg;
													}
													?>
												</h4>
											</b>
										</center>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Form No. <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<input type="text" maxlength="10" placeholder="Enter Form No." id="form_no" name="form_no" class="form-control" value="<?= $form_no ?? ""; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Ward No. <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
											<option value="">Select</option>
											<?php
											if (isset($wardList)) {
												foreach ($wardList as $values) {
											?>
													<option value="<?= $values['id'] ?>" <?= isset($ward_mstr_id) ? ($ward_mstr_id == $values['id']) ? "selected" : "" : ""; ?>><?= $values['ward_no'] ?>
													</option>
											<?php
												}
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Owner Name <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<input type="text" placeholder="Enter Owner Name" id="owner_name" name="owner_name" class="form-control" value="<?= $owner_name ?? ""; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Phone No. <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<input type="tel" maxlength="10" placeholder="Enter Phone No." id="phone_no" name="phone_no" class="form-control" value="<?= $phone_no ?? ""; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">Owner Address <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<textarea type="text" placeholder="Enter Owner Address" id="owner_address" name="owner_address" class="form-control"><?= $owner_address ?? ""; ?></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-2 control-label" for="design">&nbsp;</label>
									<div class="col-sm-4">
										<button class="btn btn-success" id="btndesign" style="width:100%;" name="btndesign" type="submit">Submit</button>
									</div>
								</div>
							</div>
							<?php if (isset($validation)) { ?>

								<?= $validation->listErrors(); ?>

							<?php } ?>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!--End page content-->
</div>


<!--END CONTENT CONTAINER-->
<?= $this->include("layout_mobi/footer"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#btndesign").click(function() {
			var process = true;
			var form_no = $("#form_no").val();
			if (form_no == '') {
				$("#form_no").css({
					"border-color": "red"
				});
				$("#form_no").focus();
				process = false;
			}
			var ward_mstr_id = $("#ward_mstr_id").val();
			if (ward_mstr_id == '') {
				$("#ward_mstr_id").css({
					"border-color": "red"
				});
				$("#ward_mstr_id").focus();
				process = false;
			}
			var owner_name = $("#owner_name").val();
			if (owner_name == '') {
				$("#owner_name").css({
					"border-color": "red"
				});
				$("#owner_name").focus();
				process = false;
			}
			var phone_no = $("#phone_no").val();
			if (phone_no == '') {
				$("#phone_no").css({
					"border-color": "red"
				});
				$("#phone_no").focus();
				process = false;
			}
			if (phone_no != "") {
				if (phone_no.length < 10) {
					$("#phone_no").css({
						"border-color": "red"
					});
					$('#phone_no').focus();
					return false;
				}
			}
			var owner_address = $("#owner_address").val();
			if (owner_address == '') {
				$("#owner_address").css({
					"border-color": "red"
				});
				$("#owner_address").focus();
				process = false;
			}

			return process;
		});
		$("#ward_mstr_id").change(function() {
			$(this).css('border-color', '');
		});
		$("#owner_name").keyup(function() {
			$(this).css('border-color', '');
		});
		$("#phone_no").keyup(function() {
			$(this).css('border-color', '');
		});
		$("#owner_address").keyup(function() {
			$(this).css('border-color', '');
		});
		$("#form_no").keyup(function() {
			$(this).css('border-color', '');
		});
	});
</script>