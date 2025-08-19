<?= $this->include('layout_vertical/header'); ?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <form>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Self Assessment Form
                        <span style="color: #3A444E;"><?=$saf_dtl_id??"";?></span></h3>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                        <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;">
                            Your applied application no. is
                            <span style="color: #ff6a00"><?= $saf_no; ?></span>.
                            You can use this application no. for future reference.
                        </span>
                        <br>
                        <br>
                        <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                            Current Status : <span style="color:#009900"> <?= $application_status; ?></span>
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
                                <a class="btn-link text-semibold add-tooltip" data-toggle="tooltip" href="<?=base_url()?>/propDtl/full/<?=$previous_holding_id;?>" target="_blank" data-original-title="View Holding">
                                    <?= $saf_dtl_id=='326854' ? $holding_no : ($old_holding_no);?>
                                </a>
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
                    <?= $this->include('common/basic_details_saf');?>
                </div>
            </div>

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
                                            <th>gender</th>
                                            <th>dob</th>
                                            <th>is_armed_force</th>
                                            <th>is_specially_abled</th>
                                            <th>Applicant Image</th>
                                            <th>Applicant Document</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                        <?php

                                        if (isset($saf_owner_detail)) {
                                            foreach ($saf_owner_detail as $owner_detail) {
												if($owner_detail['status'] == 1)
												{
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?=$owner_detail['owner_name'];?>
                                                    </td>
                                                    <td>
                                                        <?=$owner_detail['guardian_name'];?>
                                                    </td>
                                                    <td>
                                                        <?=$owner_detail['relation_type'];?>
                                                    </td>
                                                    <td>
                                                        <?=$owner_detail['mobile_no'];?>
                                                    </td>
                                                    <td>
                                                        <?=$owner_detail['aadhar_no'];?>
                                                    </td>
                                                    <td>
                                                        <?=$owner_detail['pan_no'];?>
                                                    </td>
                                                    <td>
                                                        <?=$owner_detail['email'];?>
                                                    </td>
                                                    <td>
                                                      
                                                    <?=$owner_detail['gender'];?>
                                                        

                                                    </td>
                                                    <td>
                                                    <?=$owner_detail['dob'];?>
                                                    </td>
                                                    <td>
                                                    <?=isset($owner_detail['is_armed_force'])?($owner_detail['is_armed_force']==1?'Yes':'No'):''  ?>
                                                    </td>
                                                    <td>
                                                    <?=isset($owner_detail['is_specially_abled'])?($owner_detail['is_specially_abled']==1?'Yes':'No'):''  ?>  
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($owner_detail['applicant_img_dtl']) {
                                                            $path = $owner_detail['applicant_img_dtl']['doc_path'];
                                                        ?>
                                                            <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                                                            </a>
                                                        <?php
                                                            // if ($owner_detail['applicant_img_dtl']['verify_status']==0)
                                                            // {
                                                            //     echo "<br /><span class='text-danger text-bold'>Not Verified.</span>";
                                                            // }
                                                            // else if ($owner_detail['applicant_img_dtl']['verify_status']==1)
                                                            // {
                                                            //     echo "<br /><span class='text-success text-bold'>Verified.</span>";
                                                            //     echo "<br /><span class='text-danger text-bold'>Verify date = ".date('Y-m-d', strtotime($owner_detail['applicant_img_dtl']['verified_on']))."</span>";
                                                            // }
                                                        } else {
                                                            echo "<span class='text-danger text-bold'>Document is not uploaded.</span>";
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
                                                                    <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class="img-lg" />
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                                                    <img src="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" class="img-lg" />
                                                                </a>
                                                        <?php
                                                            }
                                                            if ($owner_detail["applicant_doc_dtl"]["verify_status"] == 0) {
                                                                echo '<br /><span class="text-danger text-bold">Not Verified.</span>';
                                                            } else if ($owner_detail["applicant_doc_dtl"]["verify_status"] == 1) {
                                                                echo '<br /><span class="text-success text-bold">Verified.</span>';
                                                                echo '<br /><span class="text-danger text-bold">Verify date = ' . date("Y-m-d", strtotime($owner_detail["applicant_doc_dtl"]["verified_on"])) . '</span>';
                                                            }
                                                        } else {
                                                            echo '<span class="text-danger text-bold">Document is not uploaded.</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                        <?php
												}
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
                                if ($no_electric_connection == "t") {
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
                    <!-- <div class="row">
                        <label class="col-md-3">Property Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?= ($prop_address); ?>
                        </div>
                         <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($prop_dist); ?>
                        </div>
                        
                    </div> -->
                    <div class="row">
                        <label class="col-md-3">Property Address</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($prop_address); ?>
                        </div>
                        <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($prop_state); ?>
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
                    <!-- <div class="row">
                        <label class="col-md-3">Correspondence Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?= (isset($corr_address)) ? $corr_address : "N/A"; ?>
                        </div>
                        
                    </div> -->
                    <div class="row">
                        <label class="col-md-3">Correspondence Address</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= (isset($corr_address)) ? $corr_address : "N/A"; ?>
                        </div>
                        <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= (isset($corr_state)) ? $corr_state : "N/A"; ?>
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
                        <!-- <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= (isset($corr_state)) ? $corr_state : "N/A"; ?>
                        </div> -->
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= (isset($corr_pin_code)) ? $corr_pin_code : "N/A"; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="floor_dtl_hide_show" class="<?= (isset($prop_type_mstr_id)) ? (($prop_type_mstr_id == 4)) ? "hidden" : "" : ""; ?>">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Floor Details</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-12 pad-btm">
                                <span class="text-bold text-dark">Built Up :</span>
                                <span class="text-thin">It refers to the entire carpet area along with the thickness of the external walls of the apartment. It includes the thickness of the internal walls and the columns.</span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Floor No</th>
                                                <th>Usege Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area (in Sq. Ft)</th>
                                                <th>From Date</th>
                                                <th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="floor_dtl_append">
                                            <?php
                                            if (isset($saf_floor_details)) {
                                                foreach ($saf_floor_details as $floor_details) {
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <?= (isset($floor_details['floor_name'])) ? $floor_details['floor_name'] : "N/A"; ?>
                                                        </td>
                                                        <td>
                                                            <?= (isset($floor_details['usage_type'])) ? $floor_details['usage_type'] : "N/A"; ?>
                                                        </td>
                                                        <td>
                                                            <?= (isset($floor_details['occupancy_name'])) ? $floor_details['occupancy_name'] : "N/A"; ?>
                                                        </td>
                                                        <td>
                                                            <?= (isset($floor_details['construction_type'])) ? $floor_details['construction_type'] : "N/A"; ?>
                                                        </td>
                                                        <td>
                                                            <?= (isset($floor_details['builtup_area'])) ? $floor_details['builtup_area'] : "N/A"; ?>
                                                        </td>
                                                        <td>
                                                            <?= (isset($floor_details['date_from'])) ? date('Y-m', strtotime($floor_details['date_from'])) : "N/A"; ?>
                                                        </td>
                                                        <td>
                                                            <?= (isset($floor_details['date_upto'])) ? date('Y-m', strtotime($floor_details['date_upto'])) : "N/A"; ?>
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
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Does Property Have Mobile Tower(s) ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($is_mobile_tower == "t") ? "Yes" : "No"; ?>
                        </div>
                    </div>
                    <div class="<?= ($is_mobile_tower == "t") ? "" : "hidden"; ?>">
                        <div class="row">
                            <label class="col-md-3">Total Area Covered by Mobile Tower & its Supporting Equipments & Accessories (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($tower_area)) ? $tower_area : ""; ?>
                            </div>
                            <label class="col-md-3">Date of Installation of Mobile Tower</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($tower_installation_date)) ? $tower_installation_date : ""; ?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Does Property Have Hoarding Board(s) ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($is_hoarding_board == "t") ? "Yes" : "No"; ?>
                        </div>
                    </div>
                    <div class="<?= ($is_hoarding_board == "t") ? "" : "hidden"; ?>">
                        <div class="row">
                            <label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($hoarding_area)) ? $hoarding_area : ""; ?>
                            </div>
                            <label class="col-md-3">Date of Installation of Hoarding Board(s)</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($hoarding_installation_date)) ? $hoarding_installation_date : ""; ?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Is property a Petrol Pump ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($is_petrol_pump == "t") ? "Yes" : "No"; ?>
                        </div>
                    </div>
                    <div class="<?= ($is_petrol_pump == "t") ? "" : "hidden"; ?>">
                        <div class="row">
                            <label class="col-md-3"> Underground Storage Area (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($under_ground_area)) ? $under_ground_area : ""; ?>
                            </div>
                            <label class="col-md-3">Completion Date of Petrol Pump</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($petrol_pump_completion_date)) ? $petrol_pump_completion_date : ""; ?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Rainwater harvesting provision ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($is_water_harvesting == "t") ? "Yes" : "No"; ?>
                        </div>
                    </div>

                    <!-- Vacant Land -->
                    <div class="<?= ($prop_type_mstr_id == 4) ? "" : "hidden"; ?>">
                        <div class="row">
                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier)</label>
                            <div class="col-md-3 text-bold">
                                <?= (isset($land_occupation_date)) ? $land_occupation_date : ""; ?>
                            </div>
                        </div>
                        <hr />
                    </div>

                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Tax Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
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
                                        <?php if ($saf_tax_list) :
                                            $i = 1;
                                            $qtr_tax = 0;
                                            $lenght = sizeOf($saf_tax_list); ?>
                                            <?php foreach ($saf_tax_list as $tax_list) :
                                                $qtr_tax = $tax_list['holding_tax'] + $tax_list['water_tax'] + $tax_list['latrine_tax'] + $tax_list['education_cess'] + $tax_list['health_cess'] + $tax_list['additional_tax'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= round($tax_list['arv'], 2); ?></td>
                                                    <td><?= $tax_list['qtr']; ?> / <?= $tax_list['fy']; ?></td>
                                                    <td><?= round($tax_list['holding_tax'], 2); ?></td>
                                                    <td><?= round($tax_list['water_tax'], 2); ?></td>
                                                    <td><?= round($tax_list['latrine_tax'], 2); ?></td>
                                                    <td><?= round($tax_list['education_cess'], 2); ?></td>
                                                    <td><?= round($tax_list['health_cess'], 2); ?></td>
                                                    <td><?= round($tax_list['additional_tax'], 2); ?></td>
                                                    <td><?= round($qtr_tax, 2); ?></td>
                                                    <?php if ($i > $lenght) { ?>
                                                        <td class="text-danger">Current</td>
                                                    <?php } else { ?>
                                                        <td>Old</td>
                                                    <?php } ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="11" style="text-align:center;color:red;">Data Are Not Available!!</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Transaction No</th>
                                            <th>Payment Mode</th>
                                            <th>Date</th>
                                            <th>From Quarter / Year</th>
                                            <th>Upto Quarter / Year</th>
                                            <th>Amount</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //print_var($payment_detail);
                                        if (isset($payment_detail)) {
                                            $i = 1;
                                            foreach ($payment_detail as $payment_detail) {
                                        ?>
                                                <tr class="<?= ($payment_detail["status"] == 3) ? 'text-danger' : null; ?>">
                                                    <td><?= $i++; ?></td>
                                                    <td class="text-bold"><?= $payment_detail['tran_no']; ?></td>
                                                    <td><?= $payment_detail['transaction_mode'] ?></td>
                                                    <td><?= $payment_detail['tran_date']; ?></td>
                                                    <td><?= $payment_detail['from_qtr'] . " / " . $payment_detail['fy']; ?></td>
                                                    <td><?= $payment_detail['upto_qtr'] . " / " . $payment_detail['upto_fy']; ?></td>
                                                    <td><?= $payment_detail['payable_amt']; ?></td>

                                                    <td>
                                                        <a onClick="PopupCenter('<?= base_url('safDemandPayment/saf_payment_receipt/' . md5($payment_detail['id'])); ?>', 'SAF Payment Receipt', 1024, 786)" id="customer_view_detail" class="btn btn-primary">View</a>
                                                        <a onClick="PopupCenter('<?= base_url("citizenPaymentReceipt/saf_payment_receipt/" . $ulb_mstr_id . "/" . md5($payment_detail['id'])); ?>', 'SAF Citizen Payment Receipt', 1024, 786)" class="btn btn-primary">Citizen View </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="9" class="text-danger text-bold text-center"> No Any Transaction ...</td>
                                            </tr>
                                        <?php
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
                    <h3 class="panel-title"> Field Verification </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Verified By</th>
                                            <th>Verification On</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 0;
                                    if (isset($verification))
                                        foreach ($verification as $row) {
                                    ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td><?= $row['verified_by']; ?></td>
                                            <td><?= date(DATE_RFC822, strtotime($row['created_on'])); ?></td>
                                            <td><a href="<?= base_url(); ?>/TCVerification/index/<?= md5($row['id']); ?>" class="btn btn-primary" target="_blank"> View </a></td>
                                        </tr>
                                    <?php
                                        }
                                    else {
                                    ?>
                                        <tr>
                                            <td colspan="4" style="text-align:center;color:red;">Data Are Not Available!!</td>
                                        </tr>
                                    <?php
                                    }

                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Memo Details</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Memo No</th>
                                            <th>Generated On</th>
                                            <th>Generated By</th>
                                            <th>ARV</th>
                                            <th>Quarterly Tax</th>
                                            <th>Effect From</th>
                                            <th>Memo Type</th>
                                            <th>Holding No</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 0;
                                    if (isset($memo))
                                        foreach ($memo as $row) {
                                    ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td><?= $row['memo_no']; ?></td>
                                            <td><?= date("Y-m-d", strtotime($row['created_on'])); ?></td>
                                            <td><?= $row['emp_name']; ?></td>
                                            <td><?= $row['arv']; ?></td>
                                            <td><?= $row['quarterly_tax']; ?></td>
                                            <td><?= $row['effect_quarter']; ?>/<?= $row['fy']; ?></td>
                                            <td class="text-left"><?= $row['memo_type']; ?></td>
                                            <td class="text-left"><?= $row['holding_no']; ?></td>
                                            <td><a onClick="PopupCenter('<?= base_url("citizenPaymentReceipt/da_eng_memo_receipt/" . md5($ulb_mstr_id) . "/" . md5($row["id"])); ?>', '<?= $row['memo_type']; ?>', 1024, 786)" href="#" class="btn btn-primary">View</a></td>
                                        </tr>
                                    <?php
                                        }
                                    else {
                                    ?>
                                        <tr>
                                            <td colspan="8" style="text-align:center;color:red;">Data Are Not Available!!</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            if (isset($level)) {
            ?>
                <div class="panel panel-bordered panel-dark">
                    <div data-toggle="collapse" data-target="#demo" role="type">
                        <div class="panel-heading">
                            <h3 class="panel-title">Level Remarks
                            </h3>
                        </div>
                    </div>

                    <div class="panel-body collapse" id="demo">
                        <div class="nano has-scrollbar" style="height: 60vh">
                            <div class="nano-content" tabindex="0" style="right: -17px;">
                                <div class="panel-body chat-body media-block">
                                    <?php
                                    $i = 0;
                                    foreach ($level as $row) {
                                        ++$i;
                                    ?>
                                        <div class="chat-<?= ($i % 2 == 0) ? "user" : "user"; ?>">
                                            <div class="media-left">
                                                <img src="<?= base_url("public/assets/img/") ?>/<?= $row["user_type"]; ?>.png" class="img-circle img-sm" alt="<?= $row["user_type"]; ?>" title="<?= $row["user_type"]; ?>" loading="lazy" />
												<br /> <?=$row["emp_name"]?>
                                            </div>
                                            <div class="media-body">
                                                <div>
                                                    <p><?= !empty($row["remarks"])?$row["remarks"]:'NA'; ?><small>
                                                     <?= date("g:iA", strtotime($row["forward_time"])); ?> <?= date("d M, Y", strtotime($row["forward_date"])); ?></small></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php

                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="nano-pane">
                                <div class="nano-slider" style="height: 61px; transform: translate(0px, 0px);"></div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php
            }
            ?>

            <div class="panel-body">
                <div class="row">
                    <div class="text text-center">
                        <?php
                        if ($payment_status == 0) {
                        ?>
                            <a href="<?= base_url(); ?>/safDemandPayment/saf_property_details/<?= $saf_dtl_id; ?>" class="btn btn-primary">
                                View Demand
                            </a>
                            <?php
                            // 1 Super admin
                            // 3 Project Manager
                            // 8 jsk
                            // 4 Team Leader
                            // 11 Back Office

                            if (in_array($emp_details['user_type_mstr_id'], [1, 4, 8])) {
                            ?>
                                <a href="<?= base_url(); ?>/safDemandPayment/safPaymentProceed/<?= $saf_dtl_id; ?>" class="btn btn-primary">
                                    Proceed Payment
                                </a>
                            <?php
                            }
                            if (in_array($emp_details['user_type_mstr_id'], [1, 11])) {
                            ?>
                                <a href="<?= base_url(); ?>/SafDeactivation/view/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                                    Deactivate Application
                                </a>
                            <?php
                            }
                        }
                        if ($doc_upload_status == 0 && in_array($emp_details['user_type_mstr_id'], [1, 11])) {
                            ?>
                            <a href="<?= base_url(); ?>/SAF/backOfficeSAFUpdate/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                                Update Application
                            </a>                            
							<?php if($payment_status > 0){ ?>
                            <a href="<?= base_url(); ?>/safDoc/SAFdocumentUpload/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                                Upload Document
                            </a>
							<?php }
                             ?>
                             <a href="<?= base_url(); ?>/safDoc/view/<?= $saf_dtl_id; ?>" class="btn btn-primary">
                                View Document
                            </a>
                        <?php
                        }

                        if ($doc_upload_status == 1) {
                        ?>
                            <a href="<?= base_url(); ?>/safDoc/view/<?= $saf_dtl_id; ?>" class="btn btn-primary">
                                View Document
                            </a>
                            <!-- <a href="<?= base_url(); ?>/DocumentVerification/showAllDocumentsToDa/<?= md5($saf_dtl_id); ?>" class="btn btn-primary">
                                View Document
                            </a> -->
                            <?php
                        }

                        if (in_array($emp_details['user_type_mstr_id'], [1, 2, 3]) && isset($Memo)) {
                            $fam = array_filter($Memo, function ($var) {
                                return ($var['memo_type'] == 'FAM');
                            });

                            if (!empty($fam)) {
                            ?>
                                <a href="<?= base_url("safdtl/sendForReVerification/" . md5($saf_dtl_id)); ?>" class="btn btn-primary" data-toggle="tooltip" title="Send Application to Agency TC for Re-Verification">
                                    Send to Agency TC
                                </a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>