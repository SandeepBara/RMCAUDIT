
<?= $this->include('layout_vertical/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="#">GBSAF</a></li>
		<li class="active">Search Application</li>
        </ol>
    </div>
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Search Application </h3>
			</div>
			<div class="panel-body">
				<form method="get" >
                    <input type="hidden" name="pm" value="true"/>
				<div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="radio">
                            <input type="radio" id="by_app_gbsaf" class="magic-radio" name="by_app" value="GBSAF" <?= isset($by_app) ? (strtolower($by_app) == "gbsaf") ? "checked" : "" : "checked"; ?> >
                            <label for="by_app_gbsaf">By GBSAF</label>

                            <input type="radio" id="by_app_csaf" class="magic-radio" name="by_app" value="CSAF" <?= (isset($by_app) && strtolower($by_app) == "csaf") ? "checked" : ""; ?> >
                            <label for="by_app_csaf">By CSAF</label>
                        </div>
                    </div>
                </div>
				<div class="row">
				
					<div class="col-md-1">
						<label for="ward_mstr_id">Ward No.</label>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs" >
								<option value="">Select</option>
								<?php if($ward): ?>
								<?php foreach($ward as $post): ?>
								<option value="<?=$post['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$post["id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
								<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>

                    <div class="col-md-1">
						<label for="government_type">Government Type</label>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select id="government_type" name="government_type" class="form-control m-t-xxs" >
								<option value="">Select</option>
                                <option value="CENTRAL GOVERNMENT" <?=(isset($government_type))?$government_type=="CENTRAL GOVERNMENT"?"SELECTED":"":"";?> >CENTRAL GOVERNMENT</option>
                                <option value="STATE GOVERNMENT" <?=(isset($government_type))?$government_type=="STATE GOVERNMENT"?"SELECTED":"":"";?> >STATE GOVERNMENT</option>								
							</select>
						</div>
					</div>
				
					<div class="col-md-2">
						<label for="keyword">Enter Keyword </label>
						<i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Enter Application No. Or Colony"></i>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keyword" value="<?php echo $keyword ?? null; ?>" />
						</div>
					</div>
					<div class="col-md-2 pad-btm">
						<button type="submit" id="search" name="search" class="btn btn-primary">SEARCH</button>
					</div>
                    <!-- <div class="col-md-2 pad-btm">
						<button type="button" class="btn btn-info" data-target="#importModal" data-toggle="modal">Import</button>
					</div>
                    <div class="col-md-2 pad-btm">
						<button type="button" name="search" class="btn btn-primary" data-target="#listModal" data-toggle="modal">Report</button>
					</div> -->
				</div>
				</form>
            </div>
        </div>                    
			<?php
			if(isset($govSaf_details))
			{
				?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Search Result</h3>
					</div>
					<div class="panel-body">
						<div id="saf_distributed_dtl_hide_show">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
											<thead class="bg-trans-dark text-dark">												
													<th>SN</th>
                                                    <th>Ward No</th>
                                                    <th>New Ward No</th>
                                                    <th>Holding Assessed
                                                    <th>Holding ID</th>
                                                    <th>SAF No</th>
                                                    <th>Owner Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Department</th>
                                                    <th>Address</th>
                                                    <th>Gov Type</th>
                                                    <th>Arrear Demand</th>
                                                    <th>Current Demand </th>
                                                    <th>Total Collection in FY <?=$fyear?></th>
                                                    <th>Outstanding</th>
                                                    <th>Name and contact details of the concerned person to Whom ULB Meet</th>
                                                    <th>Is Demand Served</th>
                                                    <th>Last Demand Served Date</th>
                                                    <th>Is Demand Notice Served 
                                                    <th>Last Demand Notice Served Date</th>
                                                    <th>Payment Received end of the reporting Period</th>
                                                    <th>Upload the Receiving of Demand Letter and Demand Notice</th>
                                                    <th>Upload the Demand Notice</th>
                                                    <th>Total Notice</th>
                                                    <th></th>
											</thead>
											<tbody>
												<?php if($govSaf_details):
												$i=0;  
                                                ?>
												<?php foreach($govSaf_details as $post): ?>
													
												<tr>
													<td><?php echo ++$i; ?></td>
													<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
                                                    <td><?=$post['new_ward_no']?$post['new_ward_no']:"N/A"; ?></td>
													<td><?=$post['is_holding_assessed']?$post['is_holding_assessed']:"N/A"; ?></td>
                                                    <td><?=$post['holding_no']?$post['holding_no']:"N/A"; ?></td>
													<td><?=$post['application_no']?$post['application_no']:"N/A"; ?></td>
                                                    <td><?=$post['owner_name']?$post['owner_name']:"N/A"; ?></td>
                                                    <td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
													<td><?=$post['office_name']?$post['office_name']:"N/A"; ?></td>
													<td><?=$post['building_colony_address']?$post['building_colony_address']:"N/A"; ?></td>
                                                    <td><?=$post['gov_type']?$post['gov_type']:"N/A"; ?></td>
													<td><?=$post['arrear']?$post['arrear']:"0"; ?></td>
													<td><?=$post['current']?$post['current']:"0"; ?></td>
													<td><?=$post['current_year_collect']?$post['current_year_collect']:"0"; ?></td>
                                                    <td><?=$post['balance']?$post['balance']:"0"; ?></td>
                                                    <td><b>Person:</b><?=$post['contact_person']; ?> <br> <b>Contact:</b> <?=$post["contact_no"]?></td>
                                                    <td class="editable-cell" data-field="is_demand_served" data-id="<?= $post['id'] ?>" data-type="select"  >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm text-primary">✎</span> -->
                                                        <span class="edit-value"><?= $post["is_demand_served"] ?? '' ?></span>

                                                        <select class="edit-input form-control form-control-sm" style="display:none;">
                                                            <option value="">--Select--</option>
                                                            <option value="Yes" <?=$post["is_demand_served"]=="Yes" ? "selected" : ""?>>Yes</option>
                                                            <option value="No" <?=$post["is_demand_served"]=="No" ? "selected" : ""?>>No</option>
                                                        </select>
                                                    </td>
                                                    <td class="editable-cell" data-field="last_demand_served_date" data-id="<?= $post['id'] ?>" data-type="text" >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm text-primary">✎</span> -->
                                                        <span class="edit-value"><?= $post["last_demand_served_date"] ?? '' ?></span>

                                                        <input type="date" value="<?=$post["last_demand_served_date"]??"";?>" class="edit-input form-control form-control-sm" style="display:none;" />
                                                    </td>
                                                    <td class="editable-cell" data-field="is_demand_notice_served" data-id="<?= $post['id'] ?>" data-type="select"  >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm text-primary">✎</span> -->
                                                        <span class="edit-value"><?= $post["is_demand_notice_served"] ?? '' ?></span>

                                                        <select class="edit-input form-control form-control-sm" style="display:none;">
                                                            <option value="">--Select--</option>
                                                            <option value="Yes" <?=$post["is_demand_notice_served"]=="Yes" ? "selected" : ""?>>Yes</option>
                                                            <option value="No" <?=$post["is_demand_notice_served"]=="No" ? "selected" : ""?>>No</option>
                                                        </select>
                                                    </td>
                                                    <td class="editable-cell" data-field="last_demand_notice_served_date" data-id="<?= $post['id'] ?>" data-type="text" >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm bg-info">✎</span> -->
                                                        <span class="edit-value"><?= $post["last_demand_notice_served_date"] ?? '' ?></span>

                                                        <input type="date" value="<?=$post["last_demand_notice_served_date"]??"";?>" class="edit-input form-control form-control-sm" style="display:none;" />
                                                    </td>
                                                    <td class="editable-cell" data-field="is_payment_received" data-id="<?= $post['id'] ?>" data-type="text" >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm text-primary">✎</span> -->
                                                        <span class="edit-value"><?= $post["is_payment_received"] ?? '' ?></span>

                                                        <select class="edit-input form-control form-control-sm" style="display:none;">
                                                            <option value="">--Select--</option>
                                                            <option value="Yes" <?=$post["is_payment_received"]=="Yes" ? "selected" : ""?>>Yes</option>
                                                            <option value="No" <?=$post["is_payment_received"]=="No" ? "selected" : ""?>>No</option>
                                                        </select>
                                                    </td>
                                                    <td class="editable-cell" data-field="upload_receipt_info" data-id="<?= $post['id'] ?>" data-type="file" >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm text-primary">✎</span> -->
                                                        <span class="edit-value">
                                                            <?php
                                                                if($post["upload_receipt_info"]??""){
                                                                    $extention = strtolower(explode('.',  $post["upload_receipt_info"])[1]);
                                                                    if($extention=="pdf"){
                                                                        ?>
                                                                            <a href="<?=base_url("/getImageLink.php?path=".$post["upload_receipt_info"]);?>" target="_blank"> 
                                                                                <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' style="height: 40px; border: 1px solid #ccc; padding: 2px;" />                                                                                
                                                                            </a>                                                                            
                                                                        <?php

                                                                    }else{
                                                                        ?>
                                                                        <a target="_blank" href="<?=base_url("/getImageLink.php?path=".$post["upload_receipt_info"]);?>">
                                                                            <img src='<?=base_url("/getImageLink.php?path=".$post["upload_receipt_info"]);?>' class='img-lg' style="height: 40px; border: 1px solid #ccc; padding: 2px;"  />
                                                                            
                                                                        </a>
                                                                        <?php

                                                                    }
                                                                }else{
                                                                    echo ($post["upload_receipt_info"] ?? ''); 
                                                                }
                                                            ?>
                                                        </span>

                                                        <input type="file" accept=".jpg,.jpeg,.png,.gif,.pdf" value="<?=$post["upload_receipt_info"]??"";?>" class="edit-input form-control form-control-sm" style="display:none;" />
                                                    </td>
                                                    <td class="editable-cell" data-field="notice_path" data-id="<?= $post['id'] ?>" data-type="file" >
                                                        <!-- <span class="edit-icon" style="cursor:pointer;" class="btn btn-sm text-primary">✎</span> -->
                                                        <span class="edit-value">
                                                            <?php
                                                                if($post["notice_path"]??""){
                                                                    $extention = strtolower(explode('.',  $post["notice_path"])[1]);
                                                                    if($extention=="pdf"){
                                                                        ?>
                                                                            <a href="<?=base_url("/getImageLink.php?path=".$post["notice_path"]);?>" target="_blank"> 
                                                                                <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' style="height: 40px; border: 1px solid #ccc; padding: 2px;" />                                                                                
                                                                            </a>                                                                            
                                                                        <?php

                                                                    }else{
                                                                        ?>
                                                                        <a target="_blank" href="<?=base_url("/getImageLink.php?path=".$post["notice_path"]);?>">
                                                                            <img src='<?=base_url("/getImageLink.php?path=".$post["notice_path"]);?>' class='img-lg' style="height: 40px; border: 1px solid #ccc; padding: 2px;"  />
                                                                            
                                                                        </a>
                                                                        <?php

                                                                    }
                                                                }else{
                                                                    echo ($post["notice_path"] ?? ''); 
                                                                }
                                                            ?>
                                                        </span>

                                                        <input type="file" accept=".jpg,.jpeg,.png,.gif,.pdf" value="<?=$post["notice_path"]??"";?>" class="edit-input form-control form-control-sm" style="display:none;" />
                                                    </td>
                                                    <td class="save-btn-cell"><span class="btn btn-sm" onclick="openDtlModal(<?=$post['id']?>)"> <?=$post["total_notice"]?> </sapn></td>
                                                    <td class="save-btn-cell"></td> <!-- Save button will appear here -->

												</tr>
												<?php endforeach; ?>
												<?php else: ?>
												<tr>
													<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
												</tr>

												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php 
			}
			?>
	</div>
</div>
<!-- Modal -->
<div id="importModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" id="importFile">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Import File</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label class="col-md-2 pad-btm text-center">File Name <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="fileName" id="fileName" class="form-control" required />
                        </div>
                        <label class="col-md-3 pad-btm text-center">File <span class="text-danger">*</span></label>
                        <div class="col-md-4 pad-btm">
                            <input type="file" id="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 pad-btm">
                            <button type="button" id="uploadFile" name="uploadFile" class="btn btn-block btn-mint" onclick="submitTheForme();">Upload</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="listModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" id="filterForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">File List</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label class="col-md-2 pad-btm text-center">From Date  <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="date" name="updateDate" class="form-control" />
                        </div>
                        <label class="col-md-3 pad-btm text-center">File Name <span class="text-danger"></span></label>
                        <div class="col-md-4 pad-btm">
                            <input type="text" id="file_name" name="file_name" class="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-2 pad-btm">
                            <button type="button" id="searchFile" name="searchFile" class="btn btn-block btn-mint" onclick="searchFileList();">Search</button>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="responsive" id="fileList">

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end Modal -->

<?= $this->include('layout_vertical/footer');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    
                    title: "Report",
                    footer: true,
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function (data, row, column, node) {
                                // If the node contains a <select>, return selected option text
                                var $node = $(node);
                                // 1. If there's a visible .edit-value span, use that
                                const editValue = $node.find('.edit-value');
                                if (editValue.length) {
                                    return editValue.text().trim();
                                }

                                // 2. If there's a <select>, use selected option's text
                                const select = $node.find('select');
                                if (select.length) {
                                    return select.find('option:selected').text().trim();
                                }

                                // 3. If there's an <input>, use its value
                                const input = $node.find('input');
                                if (input.length) {
                                    return input.val().trim();
                                }

                                // 4. Default fallback to text content
                                return $node.text().trim();

                            }
                        }
                    }

                }, {
                    text: 'pdf',
                    extend: "pdfHtml5",
                    title: "Report",
                    download: 'open',
                    footer: { text: '' },
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function (data, row, column, node) {
                                var $node = $(node);
                                // 1. If there's a visible .edit-value span, use that
                                const editValue = $node.find('.edit-value');
                                if (editValue.length) {
                                    return editValue.text().trim();
                                }

                                // 2. If there's a <select>, use selected option's text
                                const select = $node.find('select');
                                if (select.length) {
                                    return select.find('option:selected').text().trim();
                                }

                                // 3. If there's an <input>, use its value
                                const input = $node.find('input');
                                if (input.length) {
                                    return input.val().trim();
                                }

                                // 4. Default fallback to text content
                                return $node.text().trim();
                            }
                        }
                    }

                }
            ]
        });        
    });
    

    function submitTheForme() {
        const form = document.getElementById("importFile");
        if (!form.checkValidity()) {
            form.reportValidity(); // Show browser-native validation
            return;
        }
        const fileInput = document.getElementById("file");
        const file = fileInput.files[0];

        if (!file) {
            alert("Please select a file.");
            return;
        }
        // Check file extension
        const allowedExtensions = ['xls', 'xlsx', 'csv'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert("Only .xls, .xlsx, and .csv files are allowed.");
            return;
        }


        const formData = new FormData(form);

        $.ajax({
            url: "<?= base_url('/govsafDetailPayment/importExcel') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            contentType: false, // Important for FormData
            processData: false, // Important for FormData
            beforeSend: function () {
                $("#loadingDiv").show();
            },
            success: function (response) {
                $("#loadingDiv").hide();
                console.log(response);
                if(response?.status){
                    $("#importModal").modal("hide");
                    modelInfo(response?.message);
                    form.reset();
                }
            },
            error: function (xhr, status, error) {
                $("#loadingDiv").hide();
                console.error("Error:", error);
            }
        });
    }

    function searchFileList() {
        const form = document.getElementById("filterForm");
        const formData = new FormData(form);
        $.ajax({
            url: "<?= base_url('/govsafDetailPayment/getExcelFileList') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            contentType: false, // Important for FormData
            processData: false, // Important for FormData
            beforeSend: function () {
                $("#loadingDiv").show();
            },
            success: function (response) {
                $("#loadingDiv").hide();
                console.log(response);
                if(response?.status){
                    modelInfo(response?.message);
                    $("#fileList").html(response?.html);
                }
            },
            error: function (xhr, status, error) {
                $("#loadingDiv").hide();
                console.error("Error:", error);
            }
        });
    }

    
    

    $(document).ready(function () {
        // Show input/select on edit icon click
        $(document).on('click', '.edit-icon', function () {
            const $cell = $(this).closest('.editable-cell');
            const $input = $cell.find('.edit-input');
            const originalValue = $cell.find('.edit-value').text().trim();
            const type = $cell.data('type');

            // Save original for cancel
            $cell.data('original', originalValue);

            if (type === 'select') {
                $input.val(originalValue);
            } else if (type === 'file') {
                // Do nothing, file input can't be pre-filled
            } else {
                $input.val(originalValue);
            }

            $cell.find('.edit-value, .edit-icon').hide();
            $input.show().focus();
        });

        // On input/select change or blur
        $(document).on('blur change', '.edit-input', function (e) {
            if (e.type === 'blur' || e.type === 'change') {
                const $input = $(this);
                const $cell = $input.closest('.editable-cell');
                const $row = $cell.closest('tr');
                const type = $cell.data('type');
                let newValue = "";

                if (type === 'file') {
                    const file = $input[0].files[0];
                    const $preview = $cell.find('.edit-value');

                    if (file) {
                        newValue = file.name;

                        // Show preview
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                $preview.html(`<img src="${e.target.result}" alt="Preview" style="height: 40px; border: 1px solid #ccc; padding: 2px;">`);
                            };
                            reader.readAsDataURL(file);
                        } else if (file.type === 'application/pdf') {
                            $preview.html(`<span>📄 ${file.name}</span>`);
                        } else {
                            $preview.text(file.name);
                        }
                    } else {
                        $preview.text(originalValue);
                    }
                }
                else {
                    newValue = $input.val().trim();
                }

                const originalValue = $cell.data('original') || '';

                $cell.find('.edit-value').text(newValue).show();
                $cell.find('.edit-icon').show();
                $input.hide();

                if (newValue !== originalValue) {
                    showSaveButton($row); // only show Save if value changed
                }
            }
        });

        // Cancel logic
        $(document).on('click', '.cancel-btn', function () {
            const $tr = $(this).closest('tr');

            $tr.find('.editable-cell').each(function () {
                const $cell = $(this);
                const type = $cell.data('type');
                const originalValue = $cell.data('original') || '';

                if (type === 'file') {
                    $cell.find('input[type="file"]').val('');
                    $cell.find('.edit-value').text(originalValue).show();
                } else {
                    $cell.find('.edit-value').text(originalValue).show();
                    $cell.find('input.edit-input, select.edit-input').val(originalValue);
                }

                $cell.find('.edit-icon').show();
                $cell.find('.edit-input').hide();
            });

            $tr.find('.save-btn-cell').empty();
        });

        // Save logic with file upload
        $(document).on('click', '.save-row', function () {
            const $row = $(this).closest('tr');
            const id = $(this).data('id');
            const formData = new FormData();

            formData.append('id', id);

            $row.find('.editable-cell').each(function () {
                const $cell = $(this);
                const field = $cell.data('field');
                const type = $cell.data('type');

                if (type === 'file') {
                    const fileInput = $cell.find('input[type="file"]')[0];
                    if (fileInput && fileInput.files.length > 0) {
                        formData.append(field, fileInput.files[0]);
                    }
                } else {
                    const value = $cell.find('.edit-value').text().trim();
                    formData.append(field, value);
                }
            });

            $.ajax({
                url: '<?= base_url("/govsafDetailPayment/updateGBSafDemandReports") ?>',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType:"json",
                success: function (response) {
                    modelInfo(response?.message);
                    if(response?.status){
                        $row.find('.save-btn-cell').html('<span class="text-success">✔ Saved</span>');
                    }
                },
                error: function () {
                    alert("Save failed.");
                }
            });
        });

        function showSaveButton($row) {
            const id = $row.data('id') || $row.find('.editable-cell').first().data('id');
            const $saveCell = $row.find('.save-btn-cell');

            if ($saveCell.find('button').length === 0) {
                $saveCell.html(`
                    <button class="btn btn-sm btn-success save-row" data-id="${id}">💾 Save</button>
                    <button class="btn btn-sm btn-secondary cancel-btn" data-id="${id}">✖ Cancel</button>
                `);
            }
        }
    });




</script>
