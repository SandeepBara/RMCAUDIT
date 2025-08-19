<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Self Assessment Form Review </a></li>
        </ol><!--End breadcrumb-->
    </div>
    
    <!--Page content-->
    <div id="page-content">
        <form method="post" id="form_saf_property" name="form_saf_property" action="<?=base_url('saf/addUpdate');?>">

            <!-- Self Assessment Form -->
            <input type="hidden" name="saf_distributed_dtl_id" value="<?=$session->get('saf_distributed_dtl_id');?>" />
            <input type="hidden" name="has_previous_holding_no" value="<?=$has_previous_holding_no;?>" />
            <input type="hidden" name="previous_holding_no" value="<?=$previous_holding_no;?>" />
            <input type="hidden" name="hasPrevOwnerDtlCount" value="<?=$hasPrevOwnerDtlCount;?>" />
            <input type="hidden" name="isPrevPaymentCleared" value="<?=$isPrevPaymentCleared;?>" />
            <input type="hidden" name="is_owner_changed" value="<?=$is_owner_changed;?>" />
            <input type="hidden" name="transfer_mode_mstr_id" value="<?=$transfer_mode_mstr_id;?>" />
            <input type="hidden" name="ward_mstr_id" value="<?=$ward_mstr_id;?>" />
            <input type="hidden" name="new_ward_mstr_id" value="<?=$new_ward_mstr_id;?>" />
            <input type="hidden" name="ownership_type_mstr_id" value="<?=$ownership_type_mstr_id;?>" />
            <input type="hidden" name="prop_type_mstr_id" value="<?=$prop_type_mstr_id;?>" />
            <input type="hidden" name="appartment_name" value="<?=$appartment_name;?>" />
            <input type="hidden" name="flat_registry_date" value="<?=$flat_registry_date;?>" />
            <input type="hidden" name="road_type_mstr_id" value="<?=$road_type_mstr_id;?>" />
            <input type="hidden" name="zone_mstr_id" value="<?=$zone_mstr_id;?>" />


            <!-- Electricity Details -->
            <input type="hidden" name="no_electric_connection" value="<?=(isset($no_electric_connection))?true:false;?>" >
            <!-- Property Details -->
            <input type="hidden" name="area_of_plot" value="<?=$area_of_plot;?>" />
            <!-- Property Address -->
            <input type="hidden" name="prop_address" value="<?=$prop_address;?>" />
            <input type="hidden" name="prop_city" value="<?=$prop_city;?>" />
            <input type="hidden" name="prop_state" value="<?=$prop_state;?>" />
            <input type="hidden" name="prop_dist" value="<?=$prop_dist;?>" />
            <input type="hidden" name="prop_pin_code" value="<?=$prop_pin_code;?>" />
            <!-- Other Details -->
            <input type="hidden" name="is_mobile_tower" value="<?=$is_mobile_tower;?>" />
            <input type="hidden" name="tower_area" value="<?=$tower_area;?>" />
            <input type="hidden" name="tower_installation_date" value="<?=$tower_installation_date;?>" />

            <input type="hidden" name="is_hoarding_board" value="<?=$is_hoarding_board;?>" />
            <input type="hidden" name="hoarding_area" value="<?=$hoarding_area;?>" />
            <input type="hidden" name="hoarding_installation_date" value="<?=$hoarding_installation_date;?>" />

            <input type="hidden" name="is_petrol_pump" value="<?=$is_petrol_pump;?>" />
            <input type="hidden" name="under_ground_area" value="<?=$under_ground_area;?>" />
            <input type="hidden" name="petrol_pump_completion_date" value="<?=$petrol_pump_completion_date;?>" />

            <input type="hidden" name="is_water_harvesting" value="<?=$is_water_harvesting;?>" />

            <input type="hidden" name="land_occupation_date" value="<?=$land_occupation_date;?>" />
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Self Assessment Form Review</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Application No.</label>
                        <div class="col-md-3 pad-btm">
                        <?=$session->get('form_no');?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Does the property being assessed has any previous Holding Number?</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($has_previous_holding_no))?($has_previous_holding_no==1)?"YES":"NO":"N/A";?>
                        </div>
                        <div id="previous_holding_no_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                            <label class="col-md-3">Previous Holding No.</label>
                            <div class="col-md-3 pad-btm">
                                <?=(isset($previous_holding_no))?$previous_holding_no:"N/A";?>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div id="has_prev_holding_dtl_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? Is owner(s) have been changed.</label>
                            <div class="col-md-3 pad-btm">
                                <?=(isset($is_owner_changed))?($is_owner_changed==1)?"YES":"NO":"N/A";?>
                            </div>
                            <div id="is_owner_changed_tran_property_hide_show" class="<?=(isset($is_owner_changed))?($is_owner_changed==0)?"hidden":"":"";?>">
                                <label class="col-md-3">Mode of transfer of property from previous Holding Owner</label>
                                <div class="col-md-3 pad-btm">
                                        <?php
                                        if(isset($transferModeList)){
                                            foreach ($transferModeList as $transferMode) {
                                        ?>
                                        <?=(isset($transfer_mode_mstr_id))?($transferMode['id']==$transfer_mode_mstr_id)?$transferMode['transfer_mode']:"":"";?>
                                        <?php
                                            }
                                        }else{
                                            echo "N/A";
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="row">
                        <label class="col-md-3">Old Ward No</label>
                        <div class="col-md-3 pad-btm">
                                <?php
                                if(isset($wardList)){
                                    foreach ($wardList as $ward) {
                                ?>
                                <?=(isset($ward_mstr_id))?($ward['id']==$ward_mstr_id)?$ward['ward_no']:"":"";?>
                                <?php
                                    }
                                }else{
                                    echo "N/A";
                                }
                                ?>
                        </div>
                        <label class="col-md-3">New Ward No</label>
                        <div class="col-md-3 pad-btm">
                                <?php
                                if(isset($wardList)){
                                    foreach ($wardList as $ward) {
                                ?>
                                <?=(isset($new_ward_mstr_id))?($ward['id']==$new_ward_mstr_id)?$ward['ward_no']:"":"";?>
                                <?php
                                    }
                                }else{
                                    echo "N/A";
                                }
                                ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Ownership Type</label>
                        <div class="col-md-3 pad-btm">
                            <?php
                            if(isset($ownershipTypeList)){
                                foreach ($ownershipTypeList as $ownershipType) {
                            ?>
                            <?=(isset($ownership_type_mstr_id))?($ownershipType['id']==$ownership_type_mstr_id)?$ownershipType['ownership_type']:"":"";?>
                            <?php
                                }
                            }else{
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Property Type</label>
                        <div class="col-md-3 pad-btm">
                            <?php
                            if(isset($propTypeList)){
                                foreach ($propTypeList as $propType) {
                            ?>
                            <?=(isset($prop_type_mstr_id))?($propType['id']==$prop_type_mstr_id)?$propType['property_type']:"":"";?>
                            <?php
                                }
                            }else{
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="<?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id!=3))?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Appartment Name</label>
                            <div class="col-md-3 pad-btm">
                                <?=(isset($appartment_name))?$appartment_name:"";?>
                            </div>
                            <label class="col-md-3">Registry Date</label>
                            <div class="col-md-3 pad-btm">
                                <?=(isset($flat_registry_date))?$flat_registry_date:"";?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 pad-btm">
                            <?php
                            if(isset($roadTypeList)){
                                foreach ($roadTypeList as $roadType) {
                            ?>
                            <?=(isset($ward_mstr_id))?($roadType['id']==$road_type_mstr_id)?$roadType['road_type']:"":"";?>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php
                $db_name = $session->get('ulb_dtl');
                if ($db_name['ulb_mstr_id']==1) {
                ?>
                    <div class="row">
                        <label class="col-md-3">Zone</label>
                        <div class="col-md-3">
                            <?=(isset($zone_mstr_id))?($zone_mstr_id==1)?"Zone 1":"Zone 2":"N/A";?>
                        </div>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>

        <?php
        if ($has_previous_holding_no==1) {
        ?>
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Previous Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
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
                                if(isset($prev_owner_name)){
                                    $zo = sizeof($prev_owner_name);
                                    for($i=0; $i < $zo; $i++){
                                ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="prev_owner_name[]" value="<?=$prev_owner_name[$i];?>" />
                                                <?=$prev_owner_name[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_guardian_name[]" value="<?=$prev_guardian_name[$i];?>" />
                                                <?=($prev_guardian_name[$i]!="")?$prev_guardian_name[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_relation_type[]" value="<?=$prev_relation_type[$i];?>" />
                                            <?php if($prev_relation_type[$i]!=""){?>
                                                <?=($prev_relation_type[$i]=="S/O")?"S/O":"";?>
                                                <?=($prev_relation_type[$i]=="D/O")?"D/O":"";?>
                                                <?=($prev_relation_type[$i]=="W/O")?"W/O":"";?>
                                                <?=($prev_relation_type[$i]=="C/O")?"C/O":"";?>
                                            <?php }else{?>
                                                N/A
                                            <?php }?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_mobile_no[]" value="<?=$prev_mobile_no[$i];?>" />
                                                <?=$prev_mobile_no[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_aadhar_no[]" value="<?=$prev_aadhar_no[$i];?>" />
                                                <?=($prev_aadhar_no[$i]!="")?$prev_aadhar_no[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_pan_no[]" value="<?=$prev_pan_no[$i];?>" />
                                                <?=($prev_pan_no[$i]!="")?$prev_pan_no[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_email[]" value="<?=$prev_email[$i];?>" />
                                                <?=($prev_email[$i]!="")?$prev_email[$i]:"N/A";?>
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

        <?php
        if ($has_previous_holding_no==0 || ($has_previous_holding_no==1 && $is_owner_changed==1)) {
        ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
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
                                if(isset($owner_name)){
                                    $zo = sizeof($owner_name);
                                    for($i=0; $i < $zo; $i++){
                                ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="owner_name[]" value="<?=$owner_name[$i];?>" />
                                                <?=$owner_name[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="guardian_name[]" value="<?=$guardian_name[$i];?>" />
                                                <?=($guardian_name[$i]!="")?$guardian_name[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="relation_type[]" value="<?=$relation_type[$i];?>" />
                                            <?php if($relation_type[$i]!=""){?>
                                                <?=($relation_type[$i]=="S/O")?"S/O":"";?>
                                                <?=($relation_type[$i]=="D/O")?"D/O":"";?>
                                                <?=($relation_type[$i]=="W/O")?"W/O":"";?>
                                                <?=($relation_type[$i]=="C/O")?"C/O":"";?>
                                            <?php }else{?>
                                                N/A
                                            <?php }?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="mobile_no[]" value="<?=$mobile_no[$i];?>" />
                                                <?=$mobile_no[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="aadhar_no[]" value="<?=$aadhar_no[$i];?>" />
                                                <?=($aadhar_no[$i]!="")?$aadhar_no[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="pan_no[]" value="<?=$pan_no[$i];?>" />
                                                <?=($pan_no[$i]!="")?$pan_no[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="email[]" value="<?=$email[$i];?>" />
                                                <?=($email[$i]!="")?$email[$i]:"N/A";?>
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
            <div class="panel panel-bordered panel-dark <?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id==4))?"hidden":"":"";?>">
                <div class="panel-heading">
                    <h3 class="panel-title">Electricity Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-10">
                            <div class="checkbox">
                                <?php
                                if(isset($no_electric_connection)){
                                    echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                }else{
                                    echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                                }
                                ?>
                                <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>

                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 pad-btm">
                            <?php
                            if(isset($roadTypeList)){
                                foreach ($roadTypeList as $roadType) {
                            ?>
                            <?=(isset($ward_mstr_id))?($roadType['id']==$road_type_mstr_id)?$roadType['road_type']:"":"";?>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <label class="col-md-3">Area of Plot <span class="text-danger">(in Decimal)</span></label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($area_of_plot))?$area_of_plot:"";?>
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
                        <div class="col-md-7 pad-btm">
                            <?=(isset($prop_address))?$prop_address:"";?>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($prop_city))?$prop_city:"";?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($prop_dist))?$prop_dist:"";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">State</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($prop_state))?$prop_state:"";?>
                        </div>
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($prop_pin_code))?$prop_pin_code:"";?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="floor_dtl_hide_show" class="<?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id==4))?"hidden":"":"";?>">
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
                                                <th>Use Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area  (in Sq. Ft)</th>
                                                <th>From Date</th>
                                                <th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="floor_dtl_append">
                                    <?php
                                    if(isset($floor_mstr_id)){
                                        for($i=0; $i < sizeof($floor_mstr_id); $i++){
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="floor_mstr_id[]" value="<?=$floor_mstr_id[$i];?>" />
                                                    <?php
                                                    if(isset($floorList)){
                                                        foreach ($floorList as $floor) {
                                                    ?>
                                                    <?=($floor['id']==$floor_mstr_id[$i])?$floor['floor_name']:"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="usage_type_mstr_id[]" value="<?=$usage_type_mstr_id[$i];?>" />
                                                    <?php
                                                    if(isset($usageTypeList)){
                                                        foreach ($usageTypeList as $usageType) {
                                                    ?>
                                                    <?=($usageType['id']==$usage_type_mstr_id[$i])?$usageType['usage_type']:"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="occupancy_type_mstr_id[]" value="<?=$occupancy_type_mstr_id[$i];?>" />
                                                    <?php
                                                    if(isset($occupancyTypeList)){
                                                        foreach ($occupancyTypeList as $occupancyType) {
                                                    ?>
                                                    <?=($occupancyType['id']==$occupancy_type_mstr_id[$i])?$occupancyType['occupancy_name']:"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="const_type_mstr_id[]" value="<?=$const_type_mstr_id[$i];?>" />
                                                    <?php
                                                    if(isset($constTypeList)){
                                                        foreach ($constTypeList as $constType) {
                                                    ?>
                                                    <?=($constType['id']==$const_type_mstr_id[$i])?$constType['construction_type']:"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="builtup_area[]" value="<?=$builtup_area[$i];?>" />
                                                    <?=$builtup_area[$i];?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="date_from[]" value="<?=$date_from[$i];?>" />
                                                    <?=$date_from[$i];?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="date_upto[]" value="<?=$date_upto[$i];?>"  />
                                                    <?=($date_upto[$i]!="")?$date_upto[$i]:"N/A";?>
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
                        <div class="col-md-3 pad-btm">
                                <?=(isset($is_mobile_tower))?($is_mobile_tower=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_mobile_tower))?($is_mobile_tower=="0")?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Total Area Covered by Mobile Tower & its
Supporting Equipments & Accessories (in Sq. Ft.)</label>
                            <div class="col-md-3">
                                <?=(isset($tower_area))?$tower_area:"";?>
                            </div>
                            <label class="col-md-3">Date of Installation of Mobile Tower</label>
                            <div class="col-md-3 ">
                                <?=(isset($tower_installation_date))?$tower_installation_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Does Property Have Hoarding Board(s) ?</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($is_hoarding_board))?($is_hoarding_board=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_hoarding_board))?($is_hoarding_board=="0")?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
                            <div class="col-md-3">
                                <?=(isset($hoarding_area))?$hoarding_area:"";?>
                            </div>
                            <label class="col-md-3">Date of Installation of Hoarding Board(s)</label>
                            <div class="col-md-3">
                                <?=(isset($hoarding_installation_date))?$hoarding_installation_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Is property a Petrol Pump ?</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($is_petrol_pump))?($is_petrol_pump=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_petrol_pump))?($is_petrol_pump=="0")?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">
Underground Storage Area (in Sq. Ft.)</label>
                            <div class="col-md-3">
                                <?=(isset($under_ground_area))?$under_ground_area:"";?>
                            </div>
                            <label class="col-md-3">Completion Date of Petrol Pump</label>
                            <div class="col-md-3">
                                <?=(isset($petrol_pump_completion_date))?$petrol_pump_completion_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Rainwater harvesting provision ?</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($is_water_harvesting))?($is_water_harvesting=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($prop_type_mstr_id))?($prop_type_mstr_id!=4)?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier)</label>
                            <div class="col-md-3">
                                <?=(isset($land_occupation_date))?$land_occupation_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                </div>
            </div>
    <?php
    if ($prop_type_mstr_id==4) {
    ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Tax - As Per Current Rule (Effect From 01-04-2016)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-danger"><b>Note </b></span> :
                        </div>
                        <div class="col-md-12 mar-lft">
                            Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/saf/getOccupancyFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> here to view occupancy factors.
                        </div>
                        <div class="col-md-12 mar-lft pad-btm">
                            Vacant Land Rates : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/saf/getVacantLandRentalRateFactor', 'newwindow', 'width=400, height=300'); return false;">Click here</a> here to view rental rate .
                        </div>
                        <div class="col-md-12">
                            <b>Tax = Area <span class="text-danger">(m<sup>2</sup>)</span> X Occupancy Factor X Rental Rate
                        </div>
                    </div>
                    <br />
                    <div class="bord-all pad-all">
                        <div class="row">
                            <label class="col-md-2 col-xs-11">Vacant Land (in Sq. Mtr.)</label>
                            <label class="col-md-1 col-xs-1 text-bold">:</label>
                            <div class="col-md-3 col-xs-12 mar-btm">
                                <?=$vacantLandDtl['vacant_land_area_sqm'];?>
                            </div>
                            <label class="col-md-2 col-xs-11">Applied Rate</label>
                            <label class="col-md-1 col-xs-1 text-bold">:</label>
                            <div class="col-md-3 col-xs-12 mar-btm">
                                <?=$vacantLandDtl['applied_rate'];?>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 col-xs-11">Yearly Holding Tax</label>
                            <label class="col-md-1 col-xs-1 text-bold">:</label>
                            <div class="col-md-3 col-xs-12 mar-btm">
                                <?=$vacantLandDtl['yearly_holding_tax'];?>
                            </div>
                            <label class="col-md-2 col-xs-11">Quarterly Holding Tax</label>
                            <label class="col-md-1 col-xs-1 text-bold">:</label>
                            <div class="col-md-3 col-xs-12 mar-btm">
                                <?=$vacantLandDtl['qtr_holding_tax'];?>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 col-xs-11">Area in Sq. Ft.</label>
                            <label class="col-md-1 col-xs-1 text-bold">:</label>
                            <div class="col-md-3 col-xs-12 mar-btm">
                                <?=$vacantLandDtl['vacant_land_area_sqft'];?>
                            </div>
                            <label class="col-md-2 col-xs-11">Effect From</label>
                            <label class="col-md-1 col-xs-1 text-bold">:</label>
                            <div class="col-md-3 col-xs-12 mar-btm">
                                Quarter :<?=$vacantLandDtl['qtr'];?> FY :<?=$vacantLandDtl['fy'];?>
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                <?php
                if ($isSafVacantLandMH==true)
                {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">Type</th>
                                            <th>Area (in Sq. Ft)</th>
                                            <th>Usage Factor</th>
                                            <th>Occupancy Factor</th>
                                            <th style="text-align: center;">Effect From</th>
                                            <th>Yearly Holding Tax</th>
                                            <th>Quarterly Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($vacantLandMHDtl))
                                    {
                                        $i = 0;
                                        foreach ($vacantLandMHDtl as $value)
                                        {
                                            ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td style="text-align: center;"><?=$value['type'];?></td>
                                                    <td style="text-align: right;"><?=$value['area_sqm'];?></td>
                                                    <td style="text-align: right;"><?=$value['usage_factor'];?></td>
                                                    <td style="text-align: right;"><?=$value['occupancy_factor'];?></td>
                                                    <td style="text-align: center;">Quarter : <?=$value['qtr'];?>   Financial Year : <?=$value['fy'];?> </td>
                                                    <td style="text-align: right;"><?=$value['yearly_tax'];?></td>
                                                    <td style="text-align: right;"><?=($value['yearly_tax']/4);?></td>
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
                    <br />
                    <?php
                }
                ?>
                    Quarterly Tax Details
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">Effect From</th>
                                            <th>Yearly Holding Tax</th>
                                            <th>Quarterly Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($safTaxDtl))
                                    {
                                        $i = 0;
                                        foreach ($safTaxDtl as $taxDtl)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fy'];?> </td>
                                                <td style="text-align: right;"><?=$taxDtl['holding_tax_yearly'];?></td>
                                                <td style="text-align: right;"><?=$taxDtl['holding_tax_qtr'];?></td>
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
    } else {
        if (isset($isSafOldRuleArv)) {
            if ($isSafOldRuleArv==true) {
    ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Annual Rental Value - As Per Old Rule (Effect Upto 31-03-2016)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-danger"><b>Note </b></span> :
                        </div>
                        <div class="col-md-12 pad-btm mar-lft">
                            Rental Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/saf/getOldRuleRentalRateFactor', 'newwindow', 'width=750, height=400'); return false;">Click here</a> here to view rental rate .
                        </div>
                        <div class="col-md-12 pad-btm mar-lft">
                            <label> <b>Annual Rental Value (ARV) </b> = Builtup Area X Rental Rate</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label> After calculating the A.R.V. the rebates are allowed in following manner : -</label>
                        </div>
                        <div class="col-md-12 pad-btm mar-lft">
                            <label> Holding older than 25 years (as on 1967-68) - 10% Own occupation</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label> a. Residential - 30%</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label> b. Commercial - 15%</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Usage Type</th>
                                            <th>Rental Rate</th>
                                            <th>Built Up Area  (in Sq. Ft)</th>
                                            <th>Effect From</th>
                                            <th style="width: 100px;">ARV</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                if (isset($safOldRuleArv)) {
                                    $i = 0;
                                    foreach ($safOldRuleArv as $taxDtl) {
                                ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td>
                                                <?php
                                                if(isset($usageTypeList)){
                                                    foreach ($usageTypeList as $usageType) {
                                                ?>
                                                <?=($usageType['id']==$taxDtl['usage_type'])?$usageType['usage_type']:"";?>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['rental_rate'], 2);?></td>
                                            <td style="text-align: right;"><?=$taxDtl['buildup_area'];?></td>
                                            <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fy'];?> </td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
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
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-danger"><b>Note </b></span> : Tax at the following rates are imposed on the claculated ARV as per old rule <br>
                        </div>
                        <div class="col-md-12 mar-lft">
                            (a) Holding tax 12.5%
                        </div>
                        <div class="col-md-12 mar-lft">
                            (b) Latrine tax 7.5%
                        </div>
                        <div class="col-md-12 mar-lft">
                            (c) Water tax 7.5%
                        </div>
                        <div class="col-md-12 mar-lft">
                            (d) Health cess 6.25%
                        </div>
                        <div class="col-md-12 mar-lft pad-btm">
                            (e) Education cess 5.0%
                        </div>
                        <div class="col-md-12">
                            <b>Below taxes are calculated on quarterly basis( Yearly Tax / 4 ).</b>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Effect From</th>
                                            <th style="width: 100px;">ARV</th>
                                            <th>Holding Tax</th>
                                            <th>Water Tax</th>
                                            <th>Latrine/Conservancy Tax</th>
                                            <th>Education Cess</th>
                                            <th>Health Cess</th>
                                            <th>Quarterly Tax</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                if(isset($safTaxDtl)){
                                    $i = 0;
                                    foreach ($safTaxDtl as $taxDtl) {
                                        if($taxDtl['fyID']<47){
                                            if ($taxDtl['arv']<>0) {
                                ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fy'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['holding_tax'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['water_tax'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['latrine_tax'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['education_cess'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['health_cess'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['holding_tax']+$taxDtl['water_tax']+$taxDtl['latrine_tax']+$taxDtl['education_cess']+$taxDtl['health_cess'], 2);?></td>
                                            </tr>
                                <?php
                                            }
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
    <?php
            }
        }
    }
    ?>
    <?php
    if ($prop_type_mstr_id!=4) {
        if ($isCurrentFinancialYearEffected==true) {
            if (isset($isSafNewRuleArv) || isset($isSafMHPArv)) {
                if ($isSafNewRuleArv==true || $isSafMHPArv==true) {
    ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Annual Rental Value - As Per Current Rule (Effect From 01-04-2016)</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-danger"><b>Note </b></span> :
                            </div>
                            <div class="col-md-12 mar-lft">
                                Usage Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/saf/getUsageFactor', 'newwindow', 'width=750, height=600'); return false;">Click here</a> to view usage factors.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/saf/getOccupancyFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> here to view occupancy factors.
                            </div>
                            <div class="col-md-12 mar-lft pad-btm">
                                Rental Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/saf/getRentalRateFactor', 'newwindow', 'width=750, height=300'); return false;">Click here</a> here to view rental rate .
                            </div>
                            <div class="col-md-12">
                                <b>Annual Rental Value (ARV) = Carpet Area X Usage Factor X Occupancy Factor X Rental Rate
                            </div>
                        </div>
                    <?php
                    if ($isSafNewRuleArv==true) {
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Usage Factor</th>
                                                <th>Occupancy Factor</th>
                                                <th>Rental Rate</th>
                                                <th>Carpet Area  (in Sq. Ft)</th>
                                                <th>Effect From</th>
                                                <th style="width: 100px;">ARV</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(isset($safNewRuleArv)){
                                        $i = 0;
                                        foreach ($safNewRuleArv as $taxDtl) {
                                    ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['usage_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['occupancy_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['rental_rate'], 2);?></td>
                                                <td style="text-align: right;"><?=$taxDtl['carpet_area'];?></td>
                                                <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fy'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
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
                    }
                    if ($isSafMHPArv==true) {
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th>Area Type</th>
                                                <th>Area  (in Sq. Ft)</th>
                                                <th>Usage Factor</th>
                                                <th>Occupancy Factor</th>
                                                <th>Rental Rate</th>
                                                <th>Effect From</th>
                                                <th style="width: 100px;">ARV</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(isset($safMHPArv)){
                                        $i = 0;
                                        foreach ($safMHPArv as $taxDtl) {
                                    ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$taxDtl['type'];?></td>
                                                <td><?=$taxDtl['area_type'];?></td>
                                                <td style="text-align: right;"><?=$taxDtl['area'];?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['usage_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['occupancy_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['rental_rate'], 2);?></td>
                                                <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fy'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
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
                    }
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <b>Total Quarterly Tax Details</b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Effect From</th>
                                                <th style="width: 100px;">ARV</th>
                                                <th>Holding Tax</th>
                                                <th>RWH Penalty</th>
                                                <th>Quarterly Tax</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if (isset($safTaxDtl)) {
                                        $i = 0;
                                        foreach ($safTaxDtl as $taxDtl) {
                                            if ($taxDtl['fyID']>=47) {
                                                if ($taxDtl['arv']<>0) {
                                    ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fy'];?> </td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['holding_tax'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['additional_tax'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format(($taxDtl['holding_tax']+$taxDtl['additional_tax']+$taxDtl['latrine_tax']+$taxDtl['education_cess']+$taxDtl['health_cess']), 2);?></td>
                                                </tr>
                                    <?php
                                                }
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
    <?php
                }
            }
        } else {
    ?>
            <div class="panel panel-bordered panel-danger">
                <div class="panel-body demo-nifty-btn">
                    <div class="row">
                        <div class="col-md-12 pad-btm text-center">
                            <label class="text-danger text-2x text-bold"><u>INCORRECT ASSESSMENT</u></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 pad-top">
                            <label class="text-danger">Note : <span class="text-bold"> Can not proceed without any assessment for current financial year.</span></label>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn">
                    <div class="row">
                        <div class="col-md-12 pad-btm">
                            <div class="checkbox">
                                <input id="ok" name="ok" class="magic-checkbox" type="checkbox" onclick="confirmCkFun();"  value="1" >
                                <label for="ok">Above given information is correct to best of citizen's knowledge & belief.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 text-center pad-btm">
                            <button type="SUBMIT" id="btn_back" name="btn_back" class="btn btn-primary" value="EDIT" style="text-align: left;"><i class="fa fa-arrow-left"></i> EDIT</button>
                        </div>
                        <div class="col-md-9 text-center">
                            <?php if( $isCurrentFinancialYearEffected==true ) { ?>
                                <button type="SUBMIT" id="btn_submit" name="btn_submit" value="submitt" class="btn btn-primary" disabled>SUBMIT</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
function confirmCkFun(){
    try{
        if($("#ok").prop("checked") == true){
            $("#btn_submit").prop("disabled", false);
        }else{
            $("#btn_submit").prop("disabled", true);
        }
    } catch (err) {
        alert(err.message);
    }
}
</script>
