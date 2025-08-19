<?= $this->include('layout_vertical/header'); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content" id="divIdPDF">
        <form>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details :
                        <span style="color: #3A444E;"><?=$prop_dtl_id??"";?></span>
                    </h3>
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
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Is Specially Abled?</th>
                                            <th>Is Armed Force?</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                        <?php
                                        if (isset($prop_owner_detail)) {
                                            foreach ($prop_owner_detail as $owner_detail) {
												if($owner_detail['status']==1){
                                        ?>
                                                <tr>
                                                    <td><?= $owner_detail['owner_name']; ?></td>
                                                    <td><?= $owner_detail['guardian_name'] == '' ? 'N/A' : $owner_detail['guardian_name'];  ?></td>

                                                    <td><?= $owner_detail['relation_type']; ?></td>
                                                    <td><?= $owner_detail['mobile_no']; ?></td>

                                                    <td><?= $owner_detail['aadhar_no'] == '' ? 'N/A' : $owner_detail['aadhar_no'];  ?></td>
                                                    <td><?= $owner_detail['pan_no'] == '' ? 'N/A' : $owner_detail['pan_no'];  ?></td>
                                                    <td><?= $owner_detail['email'] == '' ? 'N/A' : $owner_detail['email'];  ?></td>

                                                    <td><?= $owner_detail['gender'] == '' ? 'N/A' : $owner_detail['gender'];  ?></td>
                                                    <td><?= $owner_detail['dob'] == '' ? 'N/A' : $owner_detail['dob'];  ?></td>
                                                    <td><?= $owner_detail['is_specially_abled'] == 't' ? 'Yes' : 'No';  ?></td>
                                                    <td><?= $owner_detail['is_armed_force'] == 't' ? 'Yes' : 'No';  ?></td>


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
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-10">
                            <div class="checkbox">
                                <?php
                                if ($no_electric_connection == 't') {
                                    echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                } else {
                                    echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                                }
                                ?>
                                <label for="no_electric_connection">
                                    <span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                            </div>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Electricity K. No</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $elect_consumer_no; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">ACC No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $elect_acc_no; ?>
                        </div>
                        <label class="col-md-3">BIND/BOOK No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $elect_bind_book_no; ?>
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
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Building Plan Approval No. </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $building_plan_approval_no; ?>
                        </div>
                        <label class="col-md-3">Building Plan Approval Date </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $building_plan_approval_date; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Water Consumer No. </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $water_conn_no; ?>
                        </div>
                        <label class="col-md-3">Water Connection Date</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $water_conn_date; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Khata No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $khata_no; ?>
                        </div>
                        <label class="col-md-3">Plot No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $plot_no; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Village/Mauja Name</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $village_mauja_name; ?>
                        </div>
                        <label class="col-md-3">Area of Plot (in Decimal)</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $area_of_plot; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $road_type; ?>
                        </div>

                        <div class="<?= ($prop_type_mstr_id == 4) ? null : "hidden"; ?>">
                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier)</label>
                            <div class="col-md-3 text-bold">
                                <?= $land_occupation_date ?? NULL; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Address</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Property Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?= $prop_address; ?>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $prop_city; ?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $prop_dist; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= $prop_state; ?>
                        </div>
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $prop_pin_code; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Correspondence Address</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Correspondence Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?= $corr_address; ?>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= $corr_city; ?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= $corr_dist; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= $corr_state; ?>
                        </div>
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?= $corr_pin_code; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="floor_dtl_hide_show" class="<?= ($prop_type_mstr_id == 4) ? "hidden" : null; ?>">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Floor Details</h3>
                    </div>
                    <div class="panel-body">
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
                                            if (isset($prop_floor_details)) {
                                                foreach ($prop_floor_details as $floor_details) {
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <?= $floor_details['floor_name']; ?>
                                                        </td>
                                                        <td>
                                                            <?= $floor_details['usage_type']; ?>
                                                        </td>
                                                        <td>
                                                            <?= $floor_details['occupancy_name']; ?>
                                                        </td>
                                                        <td>
                                                            <?= $floor_details['construction_type']; ?>
                                                        </td>
                                                        <td>
                                                            <?= $floor_details['builtup_area']; ?>
                                                        </td>
                                                        <td>
                                                            <?= date('Y-m', strtotime($floor_details['date_from'])); ?>
                                                        </td>
                                                        <td>
                                                            <?= ($floor_details['date_upto'] == "") ? "N/A" : date('Y-m', strtotime($floor_details['date_upto'])); ?>
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
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Does Property Have Mobile Tower(s) ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?= ($is_mobile_tower == "t") ? "Yes" : "No"; ?>
                        </div>
                    </div>
                    <div class="<?= ($is_mobile_tower != "t") ? "hidden" : null; ?>">
                        <div class="row">
                            <label class="col-md-3">Total Area Covered by Mobile Tower & its Supporting Equipments & Accessories (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?= $tower_area; ?>
                            </div>
                            <label class="col-md-3">Date of Installation of Mobile Tower</label>
                            <div class="col-md-3 text-bold">
                                <?= $tower_installation_date; ?>
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
                    <div class="<?= ($is_hoarding_board != "t") ? "hidden" : ""; ?>">
                        <div class="row">
                            <label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?= $hoarding_area; ?>
                            </div>
                            <label class="col-md-3">Date of Installation of Hoarding Board(s)</label>
                            <div class="col-md-3 text-bold">
                                <?= $hoarding_installation_date; ?>
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
                    <div class="<?= ($is_petrol_pump != "t") ? "hidden" : ""; ?>">
                        <div class="row">
                            <label class="col-md-3">Underground Storage Area (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?= $under_ground_area; ?>
                            </div>
                            <label class="col-md-3">Completion Date of Petrol Pump</label>
                            <div class="col-md-3 text-bold">
                                <?= $petrol_pump_completion_date; ?>
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
                                if ($prop_tax_list) {
                                    $i = 1;
                                    $qtr_tax = 0;
                                    $lenght = sizeOf($prop_tax_list);
                                    foreach ($prop_tax_list as $tax_list) {
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
                                            <td>
                                                <?php
                                                if ($i > $lenght) {
                                                ?>
                                                    <span class="text text-success text-bold">Current</span>
                                                <?php
                                                } else {
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


            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment Details</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="bg-trans-dark text-dark">
                                <th>Sl No.</th>
                                <th>Transaction No</th>
                                <th>Payment Mode</th>
                                <th>Date</th>
                                <th>From Quarter / Year</th>
                                <th>Upto Quarter / Year</th>
                                <th>Amount</th>
                                <th>View</th>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if (isset($property_payment_detail)) {
                                    foreach ($property_payment_detail as $payment_detail) {
                                ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td class="text-bold"><?= $payment_detail['tran_no']; ?></td>
                                            <td><?= $payment_detail['transaction_mode'] ?></td>
                                            <td><?= $payment_detail['tran_date']; ?></td>
                                            <td><?= $payment_detail['from_qtr'] . " / " . $payment_detail['fy']; ?></td>
                                            <td><?= $payment_detail['upto_qtr'] . " / " . $payment_detail['upto_fy']; ?></td>
                                            <td><?=number_format($payment_detail['payable_amt'], 2); ?></td>
                                            <td>
                                                <a onclick="PopupCenter('<?= base_url('jsk/payment_jsk_receipt/' . md5($payment_detail['id']));?>', 'Payment Receipt', 1024, 786)" href="#" class="btn btn-primary">View</a>
                                                <!-- <a onclick="PopupCenter('<?= base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb['ulb_mstr_id'].'/'. md5($payment_detail['id']));?>', 'Citizen Payment Receipt', 1024, 786)" href="#" class="btn btn-primary">Citizen View</a> -->
                                                <a onclick="PopupCenter('<?= base_url('CitizenProperty/citizen_payment_receipt/'. md5($payment_detail['id']));?>', 'Citizen Payment Receipt', 1024, 786)" href="#" class="btn btn-primary">Citizen View</a>
                                                <a onclick="PopupCenter('<?= base_url('jsk/rmcPaymentReceipt/'.md5($payment_detail['id']));?>', 'RMC Payment Receipt', 1024, 786)" href="#" class="btn btn-danger">RMC Receipt</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                                if (isset($saf_payment_detail)) {
                                    foreach ($saf_payment_detail as $payment_detail) {
                                    ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td class="text-bold"><?= $payment_detail['tran_no']; ?> <span class="label label-primary"> SAF Payment </span></td>
                                            <td><?= $payment_detail['transaction_mode'] ?></td>
                                            <td><?= $payment_detail['tran_date']; ?></td>
                                            <td><?= $payment_detail['from_qtr'] . " / " . $payment_detail['fy']; ?></td>
                                            <td><?= $payment_detail['upto_qtr'] . " / " . $payment_detail['upto_fy']; ?></td>
                                            <td><?= $payment_detail['payable_amt']; ?></td>
                                            <td><a onclick="PopupCenter('<?=base_url('safDemandPayment/saf_payment_receipt/' . md5($payment_detail['id'])); ?>', 'Payment Receipt', 1024, 786)" href="#" class="btn btn-primary">View</a></td>
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

            <?php
            $safList = safList($prop_dtl_id);
            if($safList){
                ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Apply Saf List</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                <thead class="bg-trans-dark text-dark">
                                    <th>Sl No.</th>
                                    <th>SAF No</th>
                                    <th>Apply Date</th>
                                    <th>Assessment Type</th>
                                    <th>Type</th>
                                    <th>View</th>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($safList as $index=>$saf) {
                                        ?>
                                            <tr>
                                                <td><?= $index+1; ?></td>
                                                <td class="text-bold"><?= $saf['saf_no']; ?></td>
                                                <td><?= $saf['apply_date'] ?></td>
                                                <td><?= $saf['assessment_type']; ?></td>
                                                <td style="color:<?=$saf['type']=='old'?'red':'green';?>"><?= $saf['type']; ?></td>
                                                <td>
                                                    <a href="<?=base_url('safDtl/full/'.$saf["id"]);?>" class="btn btn-primary">View</a>
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
                <?php
            }
            ?>


            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="row">
                        <div class="text text-center">
							<?php if($status != 0){?>
                            <a href="<?= base_url('jsk/jsk_due_details/' . $prop_dtl_id); ?>" class="btn btn-primary">View Demand</a>
							<?php } ?>
                            <?php
                            if (in_array($emp_details['user_type_mstr_id'], [1,2])) {
								if($status != 0){
                            ?>
                                <a href="<?= base_url('paymnt_adjust/demand_adjust/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Adjust Demand</a>
                                <?php } if ($status == 0) {  ?>
                                    <a href="<?= base_url('HoldingDeactivation/view/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Activate Holding</a>
                                <?php } else { ?>
                                    <a href="<?= base_url('HoldingDeactivation/view/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Deactivate Holding</a>
                                <?php } ?>
                            <?php
                            }
							if($status != 0){
                            if (in_array($emp_details['user_type_mstr_id'], [1, 8, 11])) {
                            ?>
                                <a href="<?= base_url('propDtl/applyObjection/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Apply Objection</a>
                            <?php
                            }
                            if (in_array($emp_details['user_type_mstr_id'], [1])) {
                            ?>
                                <a href="<?= base_url('propDtl/edit/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Edit Property</a>
                            <?php
                            }
                            if ($saf_dtl_id > 0) { ?>
                                <a href="<?= base_url('safDtl/full/' . $saf_dtl_id); ?>" class="btn btn-primary" target="_blank">View SAF</a>
                            <?php
                            }
                            if (in_array($emp_details['user_type_mstr_id'], [8,4])) { ?>
								<a href="<?= base_url('PropSpecialDocUpload/PropDetailsWithoutDocUpload/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Consession Details Update </a>
                            <?php } else if (in_array($emp_details['user_type_mstr_id'], [1,2,3])) {?>
								<a href="<?= base_url('PropSpecialDocUpload/PropSpecialDocUpload/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Consession Details Update</a>
							<?php } ?>
                            
                            <?php if ($basic_details_data["prop_type_mstr_id"]!=4 && $basic_details_data["new_holding_no"]!=""){ ?>
                                <a href="<?= base_url('CitizenProperty/comparativeTax/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Comparative Demand</a>
                            <?php }} ?>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-12 text-center mar-top">
							<?php if($status != 0){?>
                            <?php if (in_array($emp_details['user_type_mstr_id'], [1, 2, 9])) { ?>
                                <a href="<?= base_url('propDtl/basicEdit/' . md5($prop_dtl_id)); ?>" class="btn btn-primary">Basic Edit</a>
                            <?php }?>
                            <!-- <?php if (in_array($emp_details['user_type_mstr_id'], [1, 2, 4])) { ?>
                                <a href="<?= base_url('jsk/holding_demand_print/' .$prop_dtl_id); ?>" target="_blank" class="btn btn-primary">Demand Print</a>
                            <?php }?> -->
                            <?php
                            if (in_array($emp_details['user_type_mstr_id'], [1])) {
                            ?>
                                <a href="<?= base_url('propDtl/OnlinePaymentRequest/' . $prop_dtl_id); ?>" class="btn btn-primary">Online Payment Request</a>
                            <?php
                            }
                            ?>
							<a href="<?= base_url('propDtl/Notice/' . $prop_dtl_id); ?>" class="btn btn-primary">Generate Notice</a>
							<?php } ?>
                            <?php PropCertificatList($prop_dtl_id) ;?>
						</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>