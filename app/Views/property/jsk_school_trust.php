<?= $this->include('layout_vertical/header'); ?>

<script type="text/javascript">
	function dategreater(to_date) {
		var from_date = document.getElementById("fromdate").value;

		var CurrentDate = new Date();
		var Enddate = new Date(to_date);
		var dd = CurrentDate.getDate();
		var mm = CurrentDate.getMonth() + 1;
		var yyyy = CurrentDate.getFullYear();
		if (dd < 10) {
			dd = '0' + dd;
		}

		if (mm < 10) {
			mm = '0' + mm;
		}
		var today = yyyy + '-' + mm + '-' + dd;
		if (Enddate > CurrentDate) {
			alert('End date is greater than the current date.');
			document.getElementById("todate").value = today;
			document.getElementById("fromdate").value = today;
		}
		var Startdate = new Date(from_date);
		if (Startdate > Enddate) {
			alert('Start date is greater than the End date.');
			document.getElementById("todate").value = today;
			document.getElementById("fromdate").value = today;
		}

	}
</script>

<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
			<li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#">Property </a></li>
			<li><a href="#">JSK </a></li>
			<li class="active">List Of School Run By Trust</li>
		</ol>
	</div>
	<!--Page content-->
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Trust List</h5>
            </div>
			<div class="panel-body">
				<form id="field_verification" method="get">
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label" for="prop_type_mstr_id"><b>From Date</b></label>
                            <input type="date" id="from_date" name="from_date" class="form-control"  value="<?=$from_date??"";?>"/>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="prop_type_mstr_id"><b>Upto Date</b></label>
                            <input type="date" id="upto_date" name="upto_date" class="form-control"  value="<?=$upto_date??"";?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-3">
                            <label class="control-label" for="exampleInputEmail1"><b>Ward No</b></label>
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
								<option value="">ALL</option>
								<?php foreach ($wardList as $value) : ?>
									<option value="<?= $value['ward_mstr_id'] ?>" <?= (isset($ward_mstr_id)) ? $ward_mstr_id == $value["ward_mstr_id"] ? "SELECTED" : "" : ""; ?>><?= $value['ward_no']; ?>
									</option>
								<?php endforeach; ?>
							</select>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="exampleInputEmail1"><b>Keyword :</b></label>
                            <input type="text" name="search_param" id="search_param" value="<?=$search_param??"";?>" class="form-control">
                        </div>
                    </div>
                    
					<div class="form-group">
                        <div class="col-md-12 text-center" style="margin-top: 20px;">
							<input type="submit" id="Search" value="Search" class="btn btn-success" onclick="this.value='Searching Please Wait'">
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
		if (isset($leveldetails)) :
			if (!empty($leveldetails)) : ?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading" style="background-color: #64ab4d;">
						<h3 class="panel-title" style="color: white;"><b>Application List</b></h3>
					</div>
					<div class="panel-body">
						<?php foreach ($leveldetails as  $value) : ?>
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: white;">Property Details</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Ward No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["ward_no"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Assessment Type</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= (trim($value["assessment_type"])=="Mutation")?"Mutation with Reassessment":$value["assessment_type"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Property Type</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["property_type"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Applicant Name</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["owner_name"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Mobile No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["mobile_no"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">SAF No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["saf_no"] ?></strong></label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Holding No.</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?php echo $value["new_holding_no"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label for="exampleInputEmail1">Property Address</label>
										</div>
										<div class="col-sm-2">
											<label for="exampleInputEmail1"><strong><?= $value["prop_address"] ?></strong></label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label>Apply Date</label>
										</div>
										<div class="col-sm-2">
											<label><strong><?= $value["apply_date"] ?></strong></label>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-4 col-xs-4 text-center">
												<a href="jskViewDetails/<?= $value["saf_dtl_id"] ?>"><span class="btn btn-sm btn-info btn_wait_load">Click To Upload Trust Certificate</span></a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<?= pagination($pager); ?>
					</div>
				</div>
		<?php endif;
		endif; ?>

	</div>
	<!--End page content-->
</div>
<?= $this->include('layout_vertical/footer'); ?>

