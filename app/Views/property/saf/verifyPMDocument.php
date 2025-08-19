<?= $this->include('layout_vertical/header'); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF</a></li>
            <li class="active">Project Manger Document Verification</li>
        </ol>
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Self Assessment Form
                    <a href="<?= base_url("safdtl/full/" . md5($saf_dtl_id)); ?>" class="btn btn-default pull-right" style="color: black;">View Full SAF Details</a>
                </h3>

            </div>
            <div class="panel-body">


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


                <?= $this->include('common/basic_details_saf'); ?>
            </div>



        </div>
        <!------- Panel Owner Details-------->
        <?php
        // print_var($has_previous_holding_no);
        // print_var($is_owner_changed);

        if ($has_previous_holding_no == 't' && $is_owner_changed == 't') {
        ?>
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Previous Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Relation</th>
                                            <th>Guardian Name</th>
                                            <th>Mobile No</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append" class="">
                                        <?php

                                        if (isset($prev_saf_owner_detail)) {

                                            foreach ($prev_saf_owner_detail as $owner_detail) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?= $owner_detail['owner_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $owner_detail['relation_type']; ?>
                                                    </td>
                                                    <td>
                                                        <? $owner_detail['guardian_name']; ?>
                                                    </td>

                                                    <td>
                                                        <?= $owner_detail['mobile_no']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $owner_detail['email']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $owner_detail['pan_no']; ?>
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
        <?php
        }
        ?>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Owner Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
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
                                        <th>Email ID</th>
                                        <th>DOB</th>
                                        <th>Gender</th>
                                        <th>Is_Specially_Abled</th>
                                        <th>Is_Armed_Force</th>
                                    </tr>
                                </thead>
                                <tbody id="owner_dtl_append">
                                    <?php

                                    if (isset($saf_owner_detail)) {
                                        foreach ($saf_owner_detail as $owner_detail) {

                                    ?>
                                            <tr>
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
                                                <td>
                                                    <?= $owner_detail['dob'] == '' ? 'N/A' : $owner_detail['dob']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['gender'] == '' ? 'N/A' : $owner_detail['gender']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['is_specially_abled'] == 'f' ? 'No' : 'Yes'; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['is_armed_force'] == 'f' ? 'No' : 'Yes'; ?>
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

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Electricity Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row <?= (isset($prop_type_mstr_id)) ? (($prop_type_mstr_id != 2)) ? "hidden" : "" : ""; ?>">
                    <label class="col-md-10">
                        <div class="checkbox">
                            <?php
                            if ($no_electric_connection == 't') {
                                echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                            } else {
                                echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                            }
                            ?>
                            <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                        </div>
                    </label>
                </div>
                <div class="row">
                    <label class="col-md-3">Electricity K. No</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($elect_consumer_no); ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                </div>
                <div class="row">
                    <label class="col-md-3">ACC No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($elect_acc_no); ?>
                    </div>
                    <label class="col-md-3">BIND/BOOK No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($elect_bind_book_no); ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Electricity Consumer Category</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $elect_cons_category; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Building Plan/Water Connection Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Building Plan Approval No. </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($building_plan_approval_no); ?>
                    </div>
                    <label class="col-md-3">Building Plan Approval Date </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($building_plan_approval_date); ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Water Consumer No. </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($water_conn_no); ?>
                    </div>
                    <label class="col-md-3">Water Connection Date</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($water_conn_date); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Khata No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($khata_no); ?>
                    </div>
                    <label class="col-md-3">Plot No.</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($plot_no); ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Village/Mauja Name</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($village_mauja_name); ?>
                    </div>
                    <label class="col-md-3">Area of Plot (in Decimal)</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($area_of_plot); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Address</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Property Address</label>
                    <div class="col-md-7 text-bold pad-btm">
                        <?= ($prop_address); ?>
                    </div>

                </div>
                <div class="row">
                    <label class="col-md-3">City</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($prop_city); ?>
                    </div>
                    <label class="col-md-3">District</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($prop_dist); ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Pin</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= ($prop_pin_code); ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-5">
                        <div class="checkbox">
                            <?php
                            if (isset($is_corr_add_differ)) {
                                echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                            } else {
                                echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                            }
                            ?>
                            <label>If Corresponding Address Different from Property Address</label>

                        </div>
                    </label>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark <?= (!isset($is_corr_add_differ)) ? "hidden" : ""; ?>">
            <div class="panel-heading">
                <h3 class="panel-title">Correspondence Address</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <label class="col-md-3">Correspondence Address</label>
                    <div class="col-md-7 text-bold pad-btm">
                        <?= (isset($corr_address)) ? $corr_address : "N/A"; ?>
                    </div>

                </div>
                <div class="row">
                    <label class="col-md-3">City</label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= (isset($corr_city)) ? $corr_city : "N/A"; ?>
                    </div>
                    <label class="col-md-3">District</label>
                    <div class="col-md-3 text-bold text-bold pad-btm">
                        <?= (isset($corr_dist)) ? $corr_dist : "N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">State</label>
                    <div class="col-md-3 text-bold text-bold pad-btm">
                        <?= (isset($corr_state)) ? $corr_state : "N/A"; ?>
                    </div>
                    <label class="col-md-3">Pin</label>
                    <div class="col-md-3 text-bold text-bold pad-btm">
                        <?= (isset($corr_pin_code)) ? $corr_pin_code : "N/A"; ?>
                    </div>
                </div>
            </div>
        </div>

        <!--------prop doc------------>

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Document</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;" id="documen">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>#</th>
                                <th>Document Name</th>
                                <th>Document</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- ONLY SAF FORM -->
                            <?php
                            $i = 0;

                            foreach ($uploaded_doc_list as $doc) {
                                if ($doc['other_doc'] != 'saf_form') {
                                    continue;
                                }
                                $owner_document = (array)null;
                                if (is_numeric($doc["saf_owner_dtl_id"]) && $doc["saf_owner_dtl_id"] != "") {
                                    $saf_owner_dtl_id = $doc["saf_owner_dtl_id"];
                                    // $owner_document = array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                    //     return ($var['id'] == $saf_owner_dtl_id);
                                    // })[0];
                                    //updated since getting error
                                    $owner_document = isset(array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                        return ($var['id'] == $saf_owner_dtl_id);
                                    })[0]) ? array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                        return ($var['id'] == $saf_owner_dtl_id);
                                    })[0] : null;
                                    //print_var($owner_document);
                                }
                            ?>
                                <tr>
                                    <td><?= ++$i; ?></td>
                                    <td>
                                        <?= $doc["doc_name"]; ?>
                                        <?php
                                        if (isset($owner_document["owner_name"])) {
                                        ?>
                                            <br>
                                            <span class="text text-primary">(<?= $owner_document["owner_name"]; ?>)</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $doc["doc_path"]; ?>" target="_blank">
                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                            <!-- ALL EXCEPT SAF FORM -->
                            <?php
                            $i = 1;
                            $everyDocVerified = true;
                            foreach ($uploaded_doc_list as $doc) {
                                if ($doc['other_doc'] == 'saf_form') {
                                    continue;
                                }
                                $owner_document = (array)null;
                                if (is_numeric($doc["saf_owner_dtl_id"]) && $doc["saf_owner_dtl_id"] != "") {
                                    $saf_owner_dtl_id = $doc["saf_owner_dtl_id"];
                                    // $owner_document = array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                    //     return ($var['id'] == $saf_owner_dtl_id);
                                    // })[0];
                                    //updated since getting error
                                    $owner_document = isset(array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                        return ($var['id'] == $saf_owner_dtl_id);
                                    })[0]) ? array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                        return ($var['id'] == $saf_owner_dtl_id);
                                    })[0] : null;
                                    //print_var($owner_document);
                                }
                            ?>
                                <tr>
                                    <td><?= ++$i; ?></td>
                                    <td>
                                        <?= $doc["doc_name"]; ?>
                                        <?php
                                        if (isset($owner_document["owner_name"])) {
                                        ?>
                                            <br>
                                            <span class="text text-primary">(<?= $owner_document["owner_name"]; ?>)</span>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $doc["doc_path"]; ?>" target="_blank">
                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                        </a>
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
            <div class="panel-body" style="padding-bottom: 0px;">
                <form method="POST">


                    <div class="form-group">
                        <label class="col-md-2">&nbsp;&nbsp;&nbsp;</label>
                        <div class="col-md-10" style="padding: 20px 20px 20px 10px;">


                            <button type="submit" class="btn btn-success" id="btn_forward_from_PM" name="btn_forward_from_PM">Forward</button>

                        </div>
                    </div>

                </form>
            </div>
        </div>
        </form>
        <!-- End page content-->
    </div>
    <?= $this->include('layout_vertical/footer'); ?>

    <script>
        function PopupCenter(url, title, w, h) {
            // Fixes dual-screen position                         Most browsers      Firefox  
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

            width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var left = ((width / 2) - (w / 2)) + dualScreenLeft;
            var top = ((height / 2) - (h / 2)) + dualScreenTop;
            var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

            // Puts focus on the newWindow  
            if (window.focus) {
                //newWindow.focus();  
            }
        }
        <?php
        // return $this->response->redirect();
        if (isset($_GET["memo_id"]) && $_GET["memo_id"] != NULL) {
        ?>

            PopupCenter('<?= base_url('citizenPaymentReceipt/da_eng_memo_receipt/' . md5($ulb_mstr_id) . '/' . ($_GET["memo_id"])); ?>', 'Self Assessment Memo', 1024, 786);

        <?php
        }
        ?>
    </script>