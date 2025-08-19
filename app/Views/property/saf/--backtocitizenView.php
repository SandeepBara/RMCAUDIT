<?= $this->include('layout_vertical/header'); ?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF</a></li>
            <li><a href="<?= base_url("BOC_SAF/index"); ?>">Back To Citizen List</a></li>
            <li class="active">View</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Self Assessment Detail</h3>
            </div>
            <div class="panel-body">

                <div class="row">
                    <label class="col-md-3">Application No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($saf_no != "") ? $saf_no : "N/A"; ?>
                    </div>

                    <label class="col-md-3">Assessment Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?php

                        echo $assessment_type;
                        if ($assessment_type == 'Mutation') {
                            echo '  (' . $transfer_mode . ')';
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3">Apply Date</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($apply_date != "") ? $apply_date : "N/A"; ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-3">Does the property being assessed has any previous Holding Number? </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= (isset($has_previous_holding_no)) ? ($has_previous_holding_no == 't') ? "Yes" : "No" : "N/A"; ?>
                    </div>
                    <?php
                    if ($has_previous_holding_no == 't') {
                    ?>

                        <label class="col-md-3">Previous Holding No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($holding_no); ?>
                        </div>

                    <?php
                    }
                    ?>

                </div>
                <hr />
                <div id="has_prev_holding_dtl_hide_show" class="<?= (isset($has_previous_holding_no)) ? ($has_previous_holding_no == 0) ? "hidden" : "" : ""; ?>">
                    <div class="row">
                        <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= (isset($is_owner_changed)) ? ($is_owner_changed == 1) ? "YES" : "NO" : "N/A"; ?>
                        </div>
                        <div id="is_owner_changed_tran_property_hide_show" class="<?= (isset($is_owner_changed)) ? ($is_owner_changed == 0) ? "hidden" : "" : ""; ?>">
                            <label class="col-md-3">Mode of transfer of property from previous Holding Owner</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?= (isset($transfer_mode)) ? $transfer_mode : "N/A"; ?>
                            </div>
                        </div>
                    </div>
                    <hr />
                </div>
                <div class="row">
                    <label class="col-md-3">Ward No</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= (isset($ward_no)) ? $ward_no : "N/A"; ?>
                    </div>
                    <label class="col-md-3">Ownership Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= (isset($ownership_type)) ? $ownership_type : "N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Property Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= (isset($property_type)) ? $property_type : "N/A"; ?>
                    </div>
                </div>
                <div class="<?= (isset($prop_type_mstr_id)) ? (($prop_type_mstr_id != 3)) ? "hidden" : "" : ""; ?>">
                    <div class="row">
                        <label class="col-md-3">Appartment Name</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= (isset($appartment_name)) ? $appartment_name : "N/A"; ?>
                        </div>
                        <label class="col-md-3">Registry Date</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= (isset($flat_registry_date)) ? $flat_registry_date : "N/A"; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Road Type</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= (isset($road_type)) ? $road_type : "N/A"; ?>
                    </div>
                </div>
                <?php

                if ($ulb_mstr_id == 1) {
                ?>
                    <div class="row">
                        <label class="col-md-3">Zone</label>
                        <div class="col-md-3 text-bold">
                            <?= (isset($zone_mstr_id)) ? ($zone_mstr_id == 1) ? "Zone 1" : "Zone 2" : "N/A"; ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <!------- Panel Owner Details-------->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Upload Owner Document</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-sm">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Applicant Image</th>
                                        <th>Applicant Document</th>
                                        <?php if (isset($saf_owner_detail)) {
                                            $check_special_data = 0;
                                            foreach ($saf_owner_detail as $s_owner) {
                                                if ($s_owner['is_specially_abled'] == 't') {
                                                    $check_special_data++;
                                                }
                                            }
                                            if ($check_special_data != 0) { ?>
                                                <th>Specially Abled Certificate </th>
                                            <?php  }

                                            $check_special_data = 0;
                                            foreach ($saf_owner_detail as $s_owner) {
                                                if ($s_owner['is_armed_force'] == 't') {
                                                    $check_special_data++;
                                                }
                                            }
                                            if ($check_special_data != 0) { ?>
                                                <th>Armed Force Certificate</th>
                                        <?php  }
                                        } ?>
                                        <?php if (isset($saf_owner_detail)) {
                                            $check_special_data = 0;
                                            foreach ($saf_owner_detail as $s_owner) {
                                                if ($s_owner['gender'] == 'Female' || $s_owner['gender'] == 'Other') {
                                                    $check_special_data++;
                                                }
                                            }
                                            if ($check_special_data != 0) { ?>
                                                <th>Gender Document </th>
                                            <?php  }

                                            $check_special_data = 0;
                                            foreach ($saf_owner_detail as $s_owner) {
                                                $dob_year = date('Y', strtotime($s_owner['dob']));
                                                $current_year = date('Y');
                                                $c_age = $current_year - $dob_year;
                                                if ($c_age > 60) {
                                                    $check_special_data++;
                                                }
                                            }
                                            if ($check_special_data != 0) { ?>
                                                <th>DOB Document</th>
                                        <?php  }
                                        } ?>
                                        <th>Upload</th>
                                        <th>Owner Name</th>
                                        <th>Guardian Name</th>
                                        <th>Relation</th>
                                        <th>Mobile No</th>
                                        <th>Aadhar No.</th>
                                        <th>PAN No.</th>
                                        <th>Email ID</th>
                                    </tr>
                                </thead>
                                <tbody id="owner_dtl_append">
                                    <?php
                                    $everyDocUploaded = true;
                                    if (isset($saf_owner_detail)) {
                                        foreach ($saf_owner_detail as $owner_detail) {
                                            //print_var($owner_detail);
                                    ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if ($owner_detail['applicant_img_dtl']) {
                                                        $path = $owner_detail['applicant_img_dtl']['doc_path'];
                                                    ?>
                                                        <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                            <img src="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" class="img-lg" />

                                                        </a>
                                                    <?php
                                                    } else {
                                                        echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($owner_detail['applicant_doc_dtl']) {
                                                        $path = $owner_detail['applicant_doc_dtl']['doc_path'];
                                                        $extention = strtolower(explode('.', $path)[1]);
                                                        if ($extention == "pdf") {
                                                    ?>
                                                            <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                                                <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                            </a>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                                                            </a>
                                                        <?php
                                                        }
                                                        ?>
                                                        <br>
                                                        <span class="text text-primary"><?= $owner_detail['applicant_doc_dtl']["doc_name"]; ?><span>
                                                                <?php
                                                                if ($owner_detail['applicant_doc_dtl']["verify_status"] == 0) {
                                                                ?><br>
                                                                    <span class="text text-primary text-bold">Not Verified</span>
                                                                <?php
                                                                } else if ($owner_detail['applicant_doc_dtl']["verify_status"] == 1) {
                                                                ?><br>
                                                                    <span class="text text-success text-success text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['applicant_doc_dtl']["remarks"]; ?>">Verfied</span>
                                                                <?php
                                                                } else if ($owner_detail['applicant_doc_dtl']["verify_status"] == 2) {
                                                                    $everyDocUploaded = false;
                                                                ?><br>
                                                                    <span class="text text-success text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['applicant_doc_dtl']["remarks"]; ?>">Rejected</span>
                                                            <?php
                                                                }
                                                            } else {
                                                                $everyDocUploaded = false;
                                                                echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                                            }
                                                            ?>
                                                </td>

                                                <!-- SPECIALLY ABLED CASE -->
                                                <?php
                                                if ($owner_detail['is_specially_abled'] == 't') { ?>
                                                    <td>
                                                        <?php
                                                        if ($owner_detail['Handicaped_doc_dtl']) {
                                                            $path = $owner_detail['Handicaped_doc_dtl']['doc_path'];
                                                            $extention = strtolower(explode('.', $path)[1]);
                                                            if ($extention == "pdf") {
                                                        ?>
                                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>
                                                            <br>
                                                            <span class="text text-primary"><?= $owner_detail['Handicaped_doc_dtl']["doc_name"]; ?><span>
                                                                    <?php
                                                                    if ($owner_detail['Handicaped_doc_dtl']["verify_status"] == 0) {
                                                                    ?><br>
                                                                        <span class="text text-primary text-bold">Not Verified</span>
                                                                    <?php
                                                                    } else if ($owner_detail['Handicaped_doc_dtl']["verify_status"] == 1) {
                                                                    ?><br>
                                                                        <span class="text text-success text-success text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['Handicaped_doc_dtl']["remarks"]; ?>">Verfied</span>
                                                                    <?php
                                                                    } else if ($owner_detail['Handicaped_doc_dtl']["verify_status"] == 2) {
                                                                        $everyDocUploaded = false;
                                                                    ?><br>
                                                                        <span class="text text-success text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['Handicaped_doc_dtl']["remarks"]; ?>">Rejected</span>
                                                                <?php
                                                                    }
                                                                } else {
                                                                    $everyDocUploaded = false;
                                                                    echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                                                }
                                                                ?>
                                                    </td>

                                                <?php }
                                                ?>

                                                <!-- ARMED CASE -->

                                                <?php
                                                if ($owner_detail['is_armed_force'] == 't') { ?>
                                                    <td>
                                                        <?php
                                                        if ($owner_detail['Armed_doc_dtl']) {
                                                            $path = $owner_detail['Armed_doc_dtl']['doc_path'];
                                                            $extention = strtolower(explode('.', $path)[1]);
                                                            if ($extention == "pdf") {
                                                        ?>
                                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>
                                                            <br>
                                                            <span class="text text-primary"><?= $owner_detail['Armed_doc_dtl']["doc_name"]; ?><span>
                                                                    <?php
                                                                    if ($owner_detail['Armed_doc_dtl']["verify_status"] == 0) {
                                                                    ?><br>
                                                                        <span class="text text-primary text-bold">Not Verified</span>
                                                                    <?php
                                                                    } else if ($owner_detail['Armed_doc_dtl']["verify_status"] == 1) {
                                                                    ?><br>
                                                                        <span class="text text-success text-success text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['Armed_doc_dtl']["remarks"]; ?>">Verfied</span>
                                                                    <?php
                                                                    } else if ($owner_detail['Armed_doc_dtl']["verify_status"] == 2) {
                                                                        $everyDocUploaded = false;
                                                                    ?><br>
                                                                        <span class="text text-success text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['Armed_doc_dtl']["remarks"]; ?>">Rejected</span>
                                                                <?php
                                                                    }
                                                                } else {
                                                                    $everyDocUploaded = false;
                                                                    echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                                                }
                                                                ?>
                                                    </td>

                                                <?php }
                                                ?>
                                                <!-- GENDER CASE -->
                                                <td>
                                                <?php
                                                if ($owner_detail['gender'] == 'Female' || $owner_detail['gender'] == 'Other') { ?>
                                                   
                                                        <?php
                                                        if ($owner_detail['gender_doc_dtl']) {
                                                            $path = $owner_detail['gender_doc_dtl']['doc_path'];
                                                            $extention = strtolower(explode('.', $path)[1]);
                                                            if ($extention == "pdf") {
                                                        ?>
                                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>
                                                            <br>
                                                            <span class="text text-primary"><?= $owner_detail['gender_doc_dtl']["doc_name"]; ?><span>
                                                                    <?php
                                                                    if ($owner_detail['gender_doc_dtl']["verify_status"] == 0) {
                                                                    ?><br>
                                                                        <span class="text text-primary text-bold">Not Verified</span>
                                                                    <?php
                                                                    } else if ($owner_detail['gender_doc_dtl']["verify_status"] == 1) {
                                                                    ?><br>
                                                                        <span class="text text-success text-success text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['gender_doc_dtl']["remarks"]; ?>">Verfied</span>
                                                                    <?php
                                                                    } else if ($owner_detail['gender_doc_dtl']["verify_status"] == 2) {
                                                                        $everyDocUploaded = false;
                                                                    ?><br>
                                                                        <span class="text text-success text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['gender_doc_dtl']["remarks"]; ?>">Rejected</span>
                                                                <?php
                                                                    }
                                                                } else {
                                                                    $everyDocUploaded = false;
                                                                    echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                                                }
                                                                ?>

                                                <?php }
                                                ?>
                                                    </td>

                                                <!-- DOB CASE -->

                                                <?php
                                                $dob_year = date('Y', strtotime($owner_detail['dob']));
                                                $current_year = date('Y');
                                                $c_age = $current_year - $dob_year;
                                                if ($c_age > 60) { ?>
                                                    <td>
                                                        <?php
                                                        if ($owner_detail['dob_doc_dtl']) {
                                                            $path = $owner_detail['dob_doc_dtl']['doc_path'];
                                                            $extention = strtolower(explode('.', $path)[1]);
                                                            if ($extention == "pdf") {
                                                        ?>
                                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>
                                                            <br>
                                                            <span class="text text-primary"><?= $owner_detail['dob_doc_dtl']["doc_name"]; ?><span>
                                                                    <?php
                                                                    if ($owner_detail['dob_doc_dtl']["verify_status"] == 0) {
                                                                    ?><br>
                                                                        <span class="text text-primary text-bold">Not Verified</span>
                                                                    <?php
                                                                    } else if ($owner_detail['dob_doc_dtl']["verify_status"] == 1) {
                                                                    ?><br>
                                                                        <span class="text text-success text-success text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['dob_doc_dtl']["remarks"]; ?>">Verfied</span>
                                                                    <?php
                                                                    } else if ($owner_detail['dob_doc_dtl']["verify_status"] == 2) {
                                                                        $everyDocUploaded = false;
                                                                    ?><br>
                                                                        <span class="text text-success text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $owner_detail['dob_doc_dtl']["remarks"]; ?>">Rejected</span>
                                                                <?php
                                                                    }
                                                                } else {
                                                                    $everyDocUploaded = false;
                                                                    echo "<span class='text-danger text-bold'>Not Uploaded</span>";
                                                                }
                                                                ?>
                                                    </td>

                                                <?php }
                                                ?>
                                                <td>
                                                    <?php
                                                    // if($everyDocUploaded==false || in_array($owner_detail['applicant_doc_dtl']["verify_status"], [2])) // 2 Rejected
                                                    // {

                                                    ?>
                                                    

                                                    <button id="click_here_to_first_upload" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#owner_details_modal<?= $owner_detail["id"]; ?>">Click here to upload</button>

                                                    <!-- Owner Doc Upload Modal -->
                                                    <div class="modal fade" id="owner_details_modal<?= $owner_detail["id"]; ?>" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Owner Document</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="saf_owner_dtl_id" id="saf_owner_dtl_id" value="<?= $owner_detail["id"]; ?>" />
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered text-sm">
                                                                                <tr>
                                                                                    <td><b>Name</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['owner_name']; ?></td>
                                                                                    <td><b>Relation</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['relation_type']; ?></td>
                                                                                    <td><b>Guardian Name</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['guardian_name']; ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Mobile</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['mobile_no']; ?></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td><b>Aadhar No.</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['aadhar_no']; ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Pan No.</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['pan_no']; ?></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td><b>Email Id</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['email']; ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Applicant Image</td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3"><img /></td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .png and .jpeg allowed</span>
                                                                                        <input type="file" name="applicant_image_file" class="form-control" accept=".png, .jpg, .jpeg" />
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                                $upload_doc_button_show=0;
                                                                                if ($owner_detail['applicant_doc_dtl']["verify_status"] != 1) {
                                                                                    $upload_doc_button_show++;
                                                                                ?>
                                                                                    <tr>
                                                                                        <td>Document Type</td>
                                                                                        <td>:</td>
                                                                                        <td colspan="3">
                                                                                            <select id="owner_doc_mstr_id" name="owner_doc_mstr_id" class="form-control" required>
                                                                                                <option value="">Select</option>
                                                                                                <?php
                                                                                                if (isset($owner_doc_list)) {
                                                                                                    foreach ($owner_doc_list as $values) {
                                                                                                ?>
                                                                                                        <option value="<?= $values['id'] ?>"><?= $values['doc_name'] ?>
                                                                                                        </option>
                                                                                                <?php
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </td>
                                                                                        <td colspan="4">
                                                                                            <input hidden type="text" name="applicant_doc_data" value="<?php
                                                                                                                                                        if ($owner_detail['applicant_doc_dtl']["verify_status"] != 1) {
                                                                                                                                                            echo "1";
                                                                                                                                                        } else {
                                                                                                                                                            echo "2";
                                                                                                                                                        }

                                                                                                                                                        ?>" />
                                                                                            <input type="file" name="applicant_doc_file" id="applicant_doc_file" class="form-control" accept=".pdf" required />
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php

                                                                                } ?>
                                                                                <!-- SPCIAL DOCUMENT UPLOAD -->
                                                                                <?php if (isset($owner_detail["is_specially_abled"])) {
                                                                                    if ($owner_detail["is_specially_abled"] != 'f') {
                                                                                        if ($owner_detail['Handicaped_doc_dtl']["verify_status"] != 1) {
                                                                                            $upload_doc_button_show++;
                                                                                             ?>
                                                                                            <tr>
                                                                                                <td>Handicaped Document</td>
                                                                                                <td>:</td>
                                                                                                <td colspan="3"><img /></td>
                                                                                                <td colspan="4">
                                                                                                    <span class="text text-danger">Only .pdf allowed</span>
                                                                                                    <input hidden type="text" name="is_specially_data" value="<?php
                                                                                                                                                                if (isset($owner_detail["is_specially_abled"])) {
                                                                                                                                                                    if ($owner_detail["is_specially_abled"] == 't') {
                                                                                                                                                                        echo "1";
                                                                                                                                                                    } else {
                                                                                                                                                                        echo "2";
                                                                                                                                                                    }
                                                                                                                                                                }
                                                                                                                                                                ?>" />
                                                                                                    <input type="file" name="handicaped_document" class="form-control" accept=".pdf" required />
                                                                                                </td>
                                                                                            </tr>
                                                                                <?php }
                                                                                    }
                                                                                } ?>
                                                                                <!-- ARMED DOCUMENT UPLOAD -->
                                                                                <?php if (isset($owner_detail["is_armed_force"])) {
                                                                                    if ($owner_detail["is_armed_force"] != 'f') {
                                                                                        if ($owner_detail['Armed_doc_dtl']["verify_status"] != 1) {
                                                                                            $upload_doc_button_show++;
                                                                                ?>
                                                                                            <tr>
                                                                                                <td>Armed Force Document</td>
                                                                                                <td>:</td>
                                                                                                <td colspan="3"><img /></td>
                                                                                                <td colspan="4">
                                                                                                    <span class="text text-danger">Only .pdf allowed</span>
                                                                                                    <input hidden type="text" name="is_armed_data" value="<?php
                                                                                                                                                            if (isset($owner_detail["is_armed_force"])) {
                                                                                                                                                                if ($owner_detail["is_armed_force"] == 't') {
                                                                                                                                                                    echo "1";
                                                                                                                                                                } else {
                                                                                                                                                                    echo "2";
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                            ?>" />
                                                                                                    <input type="file" name="armed_force_document" class="form-control" accept=".pdf" required />
                                                                                                </td>
                                                                                            </tr>
                                                                                <?php }
                                                                                    }
                                                                                } ?>
                                                                                <!-- GENDER DOCUMENT INPUT -->
                                                                                <?php if (isset($owner_detail["gender"])) {
                                                                                    if ($owner_detail["gender"] == 'Female' || $owner_detail["gender"] == 'Other') {
                                                                                        if ($owner_detail['gender_doc_dtl']["verify_status"] != 1) { 
                                                                                            $upload_doc_button_show++;
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td>Gender Document</td>
                                                                                                <td>:</td>
                                                                                                <td colspan="3"><img /></td>
                                                                                                <td colspan="4">
                                                                                                    <span class="text text-danger">Only .pdf allowed</span>
                                                                                                    <input hidden type="text" name="gender_data" value="<?php

                                                                                                                                                        if ($owner_detail["gender"] == 'Female' || $owner_detail["gender"] == 'Other') {
                                                                                                                                                            echo "1";
                                                                                                                                                        } else {
                                                                                                                                                            echo "2";
                                                                                                                                                        }

                                                                                                                                                        ?>" />
                                                                                                    <input type="file" name="gender_document" class="form-control" accept=".pdf" required />
                                                                                                </td>
                                                                                            </tr>
                                                                                <?php }
                                                                                    }
                                                                                } ?>

                                                                                <!-- DOB DOCUMENT INPUT -->
                                                                                <?php if (isset($owner_detail["dob"])) {
                                                                                    $dob_year = date('Y', strtotime($owner_detail['dob']));
                                                                                    $current_year = date('Y');
                                                                                    $c_age = $current_year - $dob_year;
                                                                                    if ($c_age > 60) {
                                                                                        if ($owner_detail['dob_doc_dtl']["verify_status"] != 1) { 
                                                                                            $upload_doc_button_show++;
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td>DOB Document</td>
                                                                                                <td>:</td>
                                                                                                <td colspan="3"><img /></td>
                                                                                                <td colspan="4">
                                                                                                    <span class="text text-danger">Only .pdf allowed</span>
                                                                                                    <input hidden type="text" name="dob_data" value="<?php

                                                                                                                                                        $dob_year = date('Y', strtotime($owner_detail['dob']));
                                                                                                                                                        $current_year = date('Y');
                                                                                                                                                        $c_age = $current_year - $dob_year;
                                                                                                                                                        if ($c_age > 60) {
                                                                                                                                                            echo "1";
                                                                                                                                                        } else {
                                                                                                                                                            echo "2";
                                                                                                                                                        }

                                                                                                                                                        ?>" />
                                                                                                    <input type="file" name="dob_document" class="form-control" accept=".pdf" required />
                                                                                                </td>
                                                                                            </tr>
                                                                                <?php }
                                                                                    }
                                                                                } ?>
                                                                                <?php /* if($upload_doc_button_show != 0){
                                                                                      ?>
                                                                                <tr>
                                                                                    <td colspan="9" class="text-right">
                                                                                        <input type="submit" name="btn_owner_doc_upload" id="btn_owner_doc_upload" class="btn btn-success" value="UPLOAD" />
                                                                                    </td>
                                                                                </tr>
                                                                                <?php }else{
                                                                                    echo "<script> 
                                                                                    document.getElementById('click_here_to_first_upload').style.display='none';
                                                                                     </script>";
                                                                                } */?>
                                                                                <tr>
                                                                                    <td colspan="9" class="text-right">
                                                                                        <input type="submit" name="btn_owner_doc_upload" id="btn_owner_doc_upload" class="btn btn-success" value="UPLOAD" onclick="this.value='Uploading...';" />
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    // }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['owner_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['guardian_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['relation_type']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['mobile_no']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['aadhar_no']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['pan_no']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['email']; ?>
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
            </div>
        </div>
        <!------- End Panel Owner Details-------->

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Upload Owner Document</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>#</th>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Upload</th>
                                <th style="width: 25%;">Document(s) Name</th>
                                <th>Document(s) Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- ONLY SAF_FORM -->
                            <?php
                            $i = 0;

                            foreach ($saf_doc_list as $row) {
                                $docs_name = implode(', ', array_map(function ($entry) {
                                    return $entry['doc_name'];
                                }, $row));

                                $document_uploaded = [];
                                foreach ($uploaded_doc_list as $rec) {
                                    foreach ($row as $rec1)
                                        if ($rec["doc_mstr_id"] == $rec1["id"]) {
                                            $document_uploaded = $rec;
                                            break;
                                        }
                                }
                                if(isset($document_uploaded['other_doc'])){
                                if ($document_uploaded['other_doc'] != 'saf_form') {
                                    continue;
                                }
                            }
                            ?>
                                <tr>
                                    <td><?= ++$i; ?></td>

                                    <td>
                                        <?php
                                        if ($document_uploaded) {
                                            $extention = strtolower(explode('.',  $document_uploaded["doc_path"])[1]);
                                            if ($extention == "pdf") {
                                        ?>
                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $document_uploaded["doc_path"]; ?>" target="_blank">
                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                    <br><span class="text text-primary"><?= $document_uploaded["doc_name"]; ?></span>
                                                </a>

                                            <?php
                                            } else {
                                            ?>
                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $document_uploaded["doc_path"]; ?>">
                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $document_uploaded["doc_path"]; ?>' class='img-lg' />
                                                    <br><span class="text text-primary"><?= $document_uploaded["doc_name"]; ?></span>
                                                </a>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <span class="text text-danger text-bold">Not Uploaded</span>
                                        <?php
                                        }
                                        ?>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td><?= $docs_name; ?></td>
                                    <td><?= implode(" ", explode("_", $row[0]["doc_type"])); ?></td>
                                </tr>
                            <?php
                            }
                            ?>

                            <!-- ALL DOCUMENT EXCEPT SAF_FORM -->
                            <?php
                            $i = 1;

                            foreach ($saf_doc_list as $row) {
                                $docs_name = implode(', ', array_map(function ($entry) {
                                    return $entry['doc_name'];
                                }, $row));

                                $document_uploaded = [];
                                foreach ($uploaded_doc_list as $rec) {
                                    foreach ($row as $rec1)
                                        if ($rec["doc_mstr_id"] == $rec1["id"]) {
                                            $document_uploaded = $rec;
                                            break;
                                        }
                                }
                                if(isset($document_uploaded['other_doc'])){
                                
                                if ($document_uploaded['other_doc'] == 'saf_form') {
                                    continue;
                                }
                            }
							//print_var($saf_doc_list);
                            ?>
                                <tr>
                                    <td><?= ++$i; ?></td>

                                    <td>
                                        <?php
                                        if ($document_uploaded) {
                                            $extention = strtolower(explode('.',  $document_uploaded["doc_path"])[1]);
                                            if ($extention == "pdf") {
                                        ?>
                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $document_uploaded["doc_path"]; ?>" target="_blank">
                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                    <br><span class="text text-primary"><?= $document_uploaded["doc_name"]; ?></span>
                                                </a>

                                            <?php
                                            } else {
                                            ?>
                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $document_uploaded["doc_path"]; ?>">
                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $document_uploaded["doc_path"]; ?>' class='img-lg' />
                                                    <br><span class="text text-primary"><?= $document_uploaded["doc_name"]; ?></span>
                                                </a>
                                            <?php
                                            }
                                        } else {
                                            $everyDocUploaded = false;
                                            ?>
                                            <span class="text text-danger text-bold">Not Uploaded</span>
                                        <?php
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if (isset($document_uploaded["verify_status"])) {
                                            if ($document_uploaded["verify_status"] == 0) {
												$everyDocUploaded = true;
                                        ?>
                                                <span class="text text-primary text-bold" role="button">Not Verified</span>
                                            <?php
                                            } else if ($document_uploaded["verify_status"] == 1) {
                                            ?>
                                                <span class="text text-success text-bold" role="button" data-toggle="tooltip" title="<?= $document_uploaded["remarks"]; ?>">Verified</span>
                                            <?php
                                            } else if ($document_uploaded["verify_status"] == 2) {
                                                $everyDocUploaded = false;
                                            ?>
                                                <span class="text text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $document_uploaded["remarks"]; ?>">Rejected</span>
                                            <?php
                                            }
                                        } else {
                                            $everyDocUploaded = false;
                                            ?>
                                            <span class="text text-danger text-bold" role="button">Not Uploaded</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        //print_var();
                                        if (!isset($document_uploaded["verify_status"])  || in_array($document_uploaded["verify_status"], [0, 2])) //0 Not Verified, 2 Rejected
                                        {
                                        ?>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#btn_upload_modal<?= $i; ?>">Click here to upload</button>
                                            <!-- Owner Doc Upload Modal -->
                                            <div class="modal fade" id="btn_upload_modal<?= $i; ?>" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Upload Document</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="other_doc" id="other_doc" value="<?= $row[0]["doc_type"]; ?>" />
                                                                <div class="table-responsive">
                                                                    <form method="post" enctype="multipart/form-data">
                                                                        <table class="table table-bordered text-sm">
                                                                            <tr>
                                                                                <td><b>Document Name</b></td>
                                                                                <td>:</td>
                                                                                <td><select class="form-control" name="doc_mstr_id" id="doc_mstr_id" required>
                                                                                        <option value="">Select</option>
                                                                                        <?php
                                                                                        foreach ($row as $select) {
                                                                                        ?>
                                                                                            <option value="<?= $select["id"]; ?>"><?= $select["doc_name"]; ?></option>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td><input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" required></td>
                                                                                <td><input type="submit" name="btn_upload" class="btn btn-success" value="Upload" /></td>
                                                                            </tr>
                                                                        </table>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td><?= $docs_name; ?></td>
                                    <td><?= implode(" ", explode("_", $row[0]["doc_type"])); ?></td>
                                </tr>
                            <?php
                            }
                            ?>

                            <?php /* if (!empty($additional_document)) {  */?>
                                <tr>
                                    <td><?= ++$i; ?></td>

                                    <td>
                                        <?php
                                        if (isset($additional_document[0])) {
                                            $extention = strtolower(explode('.',  $additional_document[0]["doc_path"])[1]);
                                            if ($extention == "pdf") {
                                        ?>
                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $additional_document[0]["doc_path"]; ?>" target="_blank">
                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                    <br><span class="text text-primary"><?= $additional_document[0]["doc_name"]; ?></span>
                                                </a>

                                            <?php
                                            } else {
                                            ?>
                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $additional_document[0]["doc_path"]; ?>">
                                                    <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $additional_document[0]["doc_path"]; ?>' class='img-lg' />
                                                    <br><span class="text text-primary"><?= $additional_document[0]["doc_name"]; ?></span>
                                                </a>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <span class="text text-danger text-bold">Not Uploaded</span>
                                        <?php
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if (isset($additional_document[0]["verify_status"])) {
                                            if ($additional_document[0]["verify_status"] == 0) {
                                        ?>
                                                <span class="text text-primary text-bold" role="button">Not Verified</span>
                                            <?php
                                            } else if ($additional_document[0]["verify_status"] == 1) {
                                            ?>
                                                <span class="text text-success text-bold" role="button" data-toggle="tooltip" title="<?= $additional_document[0]["remarks"]; ?>">Verified</span>
                                            <?php
                                            } else if ($additional_document[0]["verify_status"] == 2) {
                                                // $everyDocUploaded = false;
                                            ?>
                                                <span class="text text-danger text-bold" role="button" data-toggle="tooltip" title="<?= $additional_document[0]["remarks"]; ?>">Rejected</span>
                                            <?php
                                            }
                                        } else {
                                            // $everyDocUploaded = false;
                                            ?>
                                            <span class="text text-danger text-bold" role="button">Not Uploaded</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        //print_var();
                                        if (!isset($additional_document[0]["verify_status"])  || in_array($additional_document[0]["verify_status"], [0, 2])) //0 Not Verified, 2 Rejected
                                        {
                                        ?>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#btn_upload_modal<?= $i; ?>">Click here to upload</button>
                                            <!-- Owner Doc Upload Modal -->
                                            <div class="modal fade" id="btn_upload_modal<?= $i; ?>" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Upload Document</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data">
                                                                <input type="hidden" name="other_doc" id="other_doc" value="<?= $row[0]["doc_type"]; ?>" />
                                                                <div class="table-responsive">
                                                                    <form method="post" enctype="multipart/form-data">
                                                                        <table class="table table-bordered text-sm">
                                                                            <tr>
                                                                                <td><b>Document Name</b></td>
                                                                                <td>:</td>
                                                                                <td><select class="form-control" name="doc_mstr_id" id="doc_mstr_id" required>
                                                                                        <option value="">Select</option>
                                                                                        <?php
                                                                                        foreach ($additional_doc_list as $select) {
                                                                                        ?>
                                                                                            <option value="<?= $select["id"]; ?>"><?= $select["doc_name"]; ?></option>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td><input type="file" name="additional_doc_file" id="additional_doc_file" class="form-control" accept=".pdf" required></td>
                                                                                <td><input type="submit" name="btn_upload_additional" class="btn btn-success" value="Upload" /></td>
                                                                            </tr>
                                                                        </table>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>Additional Document</td>
                                    <td>Additional Document</td>
                                </tr>
                            <?php /* } */?>
                            <?php /* if (!empty($additional_document)) {  */ ?>
                                <tr>
                                    <td><?= ++$i; ?></td>

                                    <td>
                                    <span class="text text-danger text-bold">Not Uploaded</span>
                                    </td>
                                    <td>
                                        <span class="text text-danger text-bold" role="button">Not Uploaded</span>
                                    </td>
                                    <td>
  
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#btn_upload_modal<?= $i; ?>">Click here to upload</button>
                                        <!-- Owner Doc Upload Modal -->
                                        <div class="modal fade" id="btn_upload_modal<?= $i; ?>" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Upload Document</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="other_doc" id="other_doc" value="<?= $row[0]["doc_type"]; ?>" />
                                                            <div class="table-responsive">
                                                                <form method="post" enctype="multipart/form-data">
                                                                    <table class="table table-bordered text-sm">
                                                                        <tr>
                                                                            <td><b>Document Name</b></td>
                                                                            <td>:</td>
                                                                            <td><select class="form-control" name="doc_mstr_id" id="doc_mstr_id" required>
                                                                                    <option value="">Select</option>
                                                                                    <?php
                                                                                    foreach ($additional_doc_list as $select) {
                                                                                    ?>
                                                                                        <option value="<?= $select["id"]; ?>"><?= $select["doc_name"]; ?></option>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <td><input type="file" name="extra_doc_file" id="extra_doc_file" class="form-control" accept=".pdf" required></td>
                                                                            <td><input type="submit" name="btn_upload_extra" class="btn btn-success" value="Upload" /></td>
                                                                        </tr>
                                                                    </table>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td>Extra Document</td>
                                    <td>Extra Document</td>
                                </tr>
                            <?php /* } */?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-body">
                <div class="col-md-12 text-center">
                    <a href="<?= base_url(); ?>/safDoc/view/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                        View Document
                    </a>
                    <a href="<?= base_url(); ?>/SAF/backOfficeSAFUpdate/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                        Update Application
                    </a>

                    <!-- <a href="<?= base_url(); ?>/safDoc/SAFdocumentUpload/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                        Upload Document
                    </a> -->
                    <?php
                    if ($everyDocUploaded == true && $saf_pending_status == 2) {
                    ?>
                    <!-- //new change level id passing -->
                        <a href="<?php echo base_url('safDoc/re_send_rmc/' . $saf_dtl_id."/".$levelPassId); ?>" class="btn btn-info btn-md" id="btn_send_to_ulb">
                            Send To ULB
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!------- End Panel Property Document Details-------->
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer'); ?>
<script>
$("#btn_send_to_ulb").click(function() {
    $("#btn_send_to_ulb").hide();
})
</script>