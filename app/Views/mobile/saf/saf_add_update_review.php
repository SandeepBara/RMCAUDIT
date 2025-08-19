<?= $this->include('layout_mobi/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <form method="post" id="form_saf_property" name="form_saf_property" action="<?=base_url('MobiSaf/addUpdate2');?><?=(isset($param))?"/".$param:"";?>">

            <!-- Self Assessment Form -->
            <input type="hidden" name="has_previous_holding_no" value="<?=$has_previous_holding_no;?>" />
            <input type="hidden" name="prev_prop_dtl_id" value="<?=$prev_prop_dtl_id;?>" />
            <input type="hidden" name="holding_no" value="<?=$holding_no;?>" />
            <input type="hidden" name="is_owner_changed" value="<?=$is_owner_changed;?>" />
            <input type="hidden" name="transfer_mode_mstr_id" value="<?=$transfer_mode_mstr_id ?? 0;?>" />
            <input type="hidden" name="ward_mstr_id" value="<?=$ward_mstr_id;?>" />
            <input type="hidden" name="new_ward_mstr_id" value="<?=$new_ward_mstr_id;?>" />
            <input type="hidden" name="ownership_type_mstr_id" value="<?=$ownership_type_mstr_id;?>" />
            <input type="hidden" name="prop_type_mstr_id" value="<?=$prop_type_mstr_id;?>" />
            <input type="hidden" name="apartment_details_id" value="<?=$apartment_details_id;?>" />
            <input type="hidden" name="flat_registry_date" value="<?=$flat_registry_date;?>" />
            <input type="hidden" name="road_type_mstr_id" value="<?=$road_type_mstr_id;?>" />
            <input type="hidden" name="road_type_width" value="<?=$road_type_width;?>" />
            <input type="hidden" name="zone_mstr_id" value="<?=$zone_mstr_id;?>" />
            <!-- Electricity Details -->
            <div class="hidden">
                <input type="checkbox" name="no_electric_connection" <?=(isset($no_electric_connection))?($no_electric_connection==1)?"checked":"":"";?> value="1" >
            </div>
            <input type="hidden" name="elect_consumer_no" value="<?=$elect_consumer_no??"";?>" />
            <input type="hidden" name="elect_acc_no" value="<?=$elect_acc_no??"";?>" />
            <input type="hidden" name="elect_bind_book_no" value="<?=$elect_bind_book_no??"";?>" />
            <input type="hidden" name="elect_cons_category" value="<?=$elect_cons_category??"";?>" />
            <!-- Building Plan/Water Connection Details -->
            <input type="hidden" name="building_plan_approval_no" value="<?=$building_plan_approval_no??"";?>" />
            <input type="hidden" name="building_plan_approval_date" value="<?=$building_plan_approval_date??"";?>" />
            <input type="hidden" name="water_conn_no" value="<?=$water_conn_no??"";?>" />
            <input type="hidden" name="water_conn_date" value="<?=$water_conn_date??"";?>" />
            <input type="hidden" name="trade_license_no" value="<?=$trade_license_no??"";?>" />

            <!-- Property Details -->
            <input type="hidden" name="khata_no" value="<?=$khata_no??"";?>" />
            <input type="hidden" name="plot_no" value="<?=$plot_no??"";?>" />
            <input type="hidden" name="village_mauja_name" value="<?=$village_mauja_name??"";?>" />
            <input type="hidden" name="area_of_plot" value="<?=$area_of_plot;?>" />
            <!-- Property Address -->
            <input type="hidden" name="prop_address" value="<?=$prop_address??"";?>" />
            <input type="hidden" name="prop_city" value="<?=$prop_city??"";?>" />
            <input type="hidden" name="prop_dist" value="<?=$prop_dist??"";?>" />
            <input type="hidden" name="prop_state" value="<?=$prop_state??"";?>" />
            <input type="hidden" name="prop_pin_code" value="<?=$prop_pin_code??"";?>" />
            <input type="hidden" name="is_corr_add_differ" value="<?=(isset($is_corr_add_differ))?($is_corr_add_differ==1)?$is_corr_add_differ:"":"";?>" />
            <!-- Correspondence Address -->
            <input type="hidden" name="corr_address" value="<?=$corr_address??"";?>" />
            <input type="hidden" name="corr_city" value="<?=$corr_city??"";?>" />
            <input type="hidden" name="corr_dist" value="<?=$corr_dist??"";?>" />
            <input type="hidden" name="corr_pin_code" value="<?=$corr_pin_code??"";?>" />
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
            <input type="hidden" name="is_trust_school" value="<?=$is_trust_school;?>" />

            <input type="hidden" name="land_occupation_date" value="<?=$land_occupation_date;?>" />
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Self Assessment Form Review</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Does the property being assessed has any previous Holding Number?</label>
                        <div class="col-md-3 pad-btm">
                            <?=($has_previous_holding_no==1)?"YES":"NO";?>
                        </div>
                        <div id="previous_holding_no_hide_show" class="<?=($has_previous_holding_no==0)?"hidden":"";?>">
                            <label class="col-md-3">Previous Holding No.</label>
                            <div class="col-md-3 pad-btm">
                                <?=(isset($holding_no))?$holding_no:"N/A";?>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="<?=($has_previous_holding_no==0)?"hidden":"";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? Is owner(s) have been changed.</label>
                            <div class="col-md-3 pad-btm">
                                <?=($is_owner_changed==1)?"YES":"NO";?>
                            </div>
                            <div class="<?=($is_owner_changed==0)?"hidden":"";?>">
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
                        <label class="col-md-3">Ward No.</label>
                        <div class="col-md-3 pad-btm">
                                <?php
                                if(isset($wardList)){
                                    foreach ($wardList as $ward) {
                                        if(isset($ward_mstr_id)){
                                            if($ward['id']==$ward_mstr_id){
                                                $old_ward= $ward['ward_no'];
                                                echo $ward['ward_no'];
                                            }
                                        }
                               
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
                                <!-- <?=(isset($appartment_name))?$appartment_name:"";?> -->
                                <?php
                                if(isset($apartmentDetailsList)){
                                    foreach ($apartmentDetailsList as $apartmentDetails) {
                                ?>
                                <?=(isset($apartment_details_id))?($apartmentDetails['id']==$apartment_details_id)?$apartmentDetails['apartment_name'].' ('.$apartmentDetails['apt_code'].')':"":"";?>
                                <?php
                                    }
                                }else{
                                    echo "N/A";
                                }
                                ?>
                            </div>
                            <label class="col-md-3">Registry Date</label>
                            <div class="col-md-3 pad-btm">
                                <?=(isset($flat_registry_date))?$flat_registry_date:"";?>
                            </div>
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
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Guardian Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No</th>
                                            <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                            <th>Is Armed Force</th>
                                            <th>Is Specially Abled</th>
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
                                                <input type="hidden" name="prev_gender[]" value="<?=$prev_gender[$i];?>" />
                                                <?=($prev_gender[$i]!="")?$prev_gender[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_dob[]" value="<?=$prev_dob[$i];?>" />
                                                <?=($prev_dob[$i]!="")?$prev_dob[$i]:"N/A";?>
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
                                            <td>
                                                <input type="hidden" name="prev_is_specially_abled[]" value="<?=$prev_is_specially_abled[$i];?>" />
                                                <?=($prev_is_specially_abled[$i]!="")?$prev_is_specially_abled[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="prev_is_armed_force[]" value="<?=$prev_is_armed_force[$i];?>" />
                                                <?=($prev_is_armed_force[$i]!="")?$prev_is_armed_force[$i]:"N/A";?>
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
        //if ($has_previous_holding_no==0 || ($has_previous_holding_no==1 && $is_owner_changed==1)) {
        ?>
            <div class="panel panel-bordered panel-dark <?=($has_previous_holding_no==1 && $is_owner_changed==0)?"hidden":NULL;?>">
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
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Mobile No</th>
                                            <th>Is Armed Force</th>
                                            <th>Is Specially Abled</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                <?php
                                if (isset($owner_name)) {
                                    $zo = sizeof($owner_name);
                                    for ($i=0; $i < $zo; $i++) {
                                ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="owner_name[]" value="<?=$owner_name[$i];?>" />
                                                <?=$owner_name[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="gender[]" value="<?=$gender[$i];?>" />
                                                <?php if ($gender[$i]!="") { ?>
                                                    <?=($gender[$i]=="Male")?"Male":"";?>
                                                    <?=($gender[$i]=="Female")?"Female":"";?>
                                                    <?=($gender[$i]=="Other")?"Other":"";?>
                                                <?php } else { ?>
                                                    N/A
                                                <?php }?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="dob[]" value="<?=$dob[$i];?>" />
                                                <input type="hidden" name="guardian_name[]" value="<?=$guardian_name[$i]??"";?>" />
                                                <input type="hidden" name="relation_type[]" value="<?=$relation_type[$i]??"";?>" />
                                                <?=($dob[$i]!="")?$dob[$i]:"N/A";?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="mobile_no[]" value="<?=$mobile_no[$i];?>" />
                                                <input type="hidden" name="aadhar_no[]" value="<?=$aadhar_no[$i]??"";?>" />
                                                <input type="hidden" name="pan_no[]" value="<?=$pan_no[$i]??"";?>" />
                                                <input type="hidden" name="email[]" value="<?=$email[$i]??"";?>" />
                                                <?=$mobile_no[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="is_armed_force[]" value="<?=$is_armed_force[$i];?>" />
                                                <?=$is_armed_force[$i];?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="is_specially_abled[]" value="<?=$is_specially_abled[$i];?>" />
                                                <?=$is_specially_abled[$i];?>
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
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Area of Plot (in Decimal)</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($area_of_plot))?$area_of_plot:"";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 pad-btm">
                            <?php
                            if(isset($roadTypeList)){
                                foreach ($roadTypeList as $roadType) {
                            ?>
                            <?=(isset($road_type_mstr_id))?($roadType['id']==$road_type_mstr_id)?$roadType['road_type']:"":"";?>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <?php if (isset($road_type_mstr_id) && $road_type_mstr_id!=4) { ?>
                            <label class="col-md-3">Road Width</label>
                            <div class="col-md-3 pad-btm">
                                <?=$road_type_width??"N/A";?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- ELECTRICITY DETAILS START HERE -->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Electricity Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div id="no_electric_connection_hide_show" class="hidden">
                        <div class="row">
                            <div class="col-md-12 pad-btm">
                                <div class="checkbox">
                                    <input id="no_electric_connection" name="no_electric_connection" class="magic-checkbox" type="checkbox" onclick="noElectConnCkFun();" <?=(isset($no_electric_connection))?($no_electric_connection==1)?"checked":"":"";?> value="1" >
                                    <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="electric_dtl_hide_show" class="hidden">
                        <div class="row">
                            <label class="col-md-3">Electricity K. No</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="elect_consumer_no" name="elect_consumer_no" class="form-control" placeholder="Electricity K. No" value="<?=(isset($elect_consumer_no))?$elect_consumer_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                        </div>
                        <div class="row">
                            <label class="col-md-3">ACC No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="elect_acc_no" name="elect_acc_no" class="form-control" placeholder="ACC No." value="<?=(isset($elect_acc_no))?$elect_acc_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            </div>
                            <label class="col-md-3">BIND/BOOK No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="elect_bind_book_no" name="elect_bind_book_no" class="form-control" placeholder="BIND/BOOK No." value="<?=(isset($elect_bind_book_no))?$elect_bind_book_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Electricity Consumer Category</label>
                            <div class="col-md-3 pad-btm">
                                <select id="elect_cons_category" name="elect_cons_category" class="form-control">
                                    <option value="">== SELECT ==</option>
                                    <option value="DS I/II/III" <?=(isset($elect_cons_category))?($elect_cons_category=='DS I/II/III')?"selected":"":"";?>>DS I/II/III</option>
                                    <option value="NDS II/III" <?=(isset($elect_cons_category))?($elect_cons_category=='NDS II/III')?"selected":"":"";?>>NDS II/III</option>
                                    <option value="IS I/II" <?=(isset($elect_cons_category))?($elect_cons_category=='IS I/II')?"selected":"":"";?>>IS I/II</option>
                                    <option value="LTS" <?=(isset($elect_cons_category))?($elect_cons_category=='LTS')?"selected":"":"";?>>LTS</option>
                                    <option value="HTS" <?=(isset($elect_cons_category))?($elect_cons_category=='HTS')?"selected":"":"";?>>HTS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <!-- ELECTRICITY DETAILS END HERE -->
             
             <!-- BUILDING PLAN APPROVAL DATE START HERE -->
             <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Building Plan/Water Connection Details</h3>
                 </div>
              <div class="panel-body" style="padding-bottom: 0px;">
                 <div class="row">
                    <label class="col-md-3">Building Plan Approval No. </label>
                     <div class="col-md-3 pad-btm">
                        <input type="hidden" id="building_plan_approval_no" name="building_plan_approval_no" class="form-control" placeholder="Building Plan Approval No." value="<?=(isset($building_plan_approval_no))?$building_plan_approval_no:"";?>" onkeypress="return isAlphaNum(event);" />
                        <?=(isset($building_plan_approval_no))?$building_plan_approval_no:"";?>
                    </div>
                    <label class="col-md-3">Building Plan Approval Date </label>
                     <div class="col-md-3 pad-btm">
                         <input type="hidden" id="building_plan_approval_date" name="building_plan_approval_date" class="form-control" value="<?=(isset($building_plan_approval_date))?$building_plan_approval_date:"";?>" max="<?=date("Y-m-d");?>" />
                         <?=(isset($building_plan_approval_date))?$building_plan_approval_date:"";?>
                     </div>
                  </div>
                 <div class="row">
                    <label class="col-md-3">Water Consumer No. </label>
                        <div class="col-md-3 pad-btm">
                            <input type="hidden" id="water_conn_no" name="water_conn_no" class="form-control" placeholder="Water Consumer No." value="<?=(isset($water_conn_no))?$water_conn_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            <?=(isset($water_conn_no))?$water_conn_no:"";?>
                        </div>
                    <label class="col-md-3">Water Connection Date </label>
                        <div class="col-md-3 pad-btm">
                            <input type="hidden" id="water_conn_date" name="water_conn_date" class="form-control" value="<?=(isset($water_conn_date))?$water_conn_date:"";?>" max="<?=date("Y-m-d");?>" />
                            <?=(isset($water_conn_date))?$water_conn_date:"";?>
                        </div>
                    </div>
                <div class="row">
                 <label class="col-md-3">Trade License No. </label>
                    <div class="col-md-3 pad-btm">
                         <input type="hidden" id="trade_license_no" name="trade_license_no" class="form-control" placeholder="Trade License No." value="<?=(isset($trade_license_no))?$trade_license_no:"";?>" onkeypress="return isAlphaNum(event);" />
                         <?=(isset($trade_license_no))?$trade_license_no:"";?>
                     </div>
                    </div>
                 </div>
                </div>
            </div>
 
            <!-- BUILDING PLAN APPROVAL DATE END HERE -->

            
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
                                                    <input type="hidden" id="prop_floor_details_id<?=$i+1;?>" name="prop_floor_details_id[]" value="<?=isset($prop_floor_details_id[$i])?$prop_floor_details_id[$i]:"";?>" />
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
                            <label class="col-md-3">T   
Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
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
                    <?php if(isset($is_trust_school)){ ?>
                    <div class="row">
                        <label class="col-md-3">Is it a trust ?</label>
                        <div class="col-md-3 pad-btm">
                            <?=(isset($is_trust_school))?($is_trust_school=="1")?"Educational Institution Run By Trust":"Other Organisational Trust":"N/A";?>
                        </div>
                    </div>
                    <?php } ?>
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
        $isSafMHArv = FALSE;
        foreach ($cv_rule_sub as $value) {
            if ($value["type"]!="Vacant Land") {
                $isSafMHArv = TRUE;
                break;
            }
        }
    ?>
            <?php
            if (!empty($new_rule_sub)) {
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Tax - As Per Old Rule (Effect From 01-04-2016 to 31-03-2022)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-danger"><b>Note </b></span> :
                        </div>
                        <div class="col-md-12 mar-lft">
                            Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetOccupancyFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> here to view occupancy factors.
                        </div>
                        <div class="col-md-12 mar-lft pad-btm">
                            Vacant Land Rates : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/getVacantLandRentalRateFactor', 'newwindow', 'width=400, height=300'); return false;">Click here</a> here to view rental rate .
                        </div>
                        <div class="col-md-12">
                            <?php if ($road_type_mstr_id!=4) { ?>
                                <b>Tax = Area <span class="text-danger">(sqm)</span> X Rental Rate X Occupancy Factor</b>
                            <?php } else { ?>
                            <b>Tax = Area <span class="text-danger">(Acre)</span> X Rental Rate X Occupancy Factor</b>
                            <?php } ?>
                        </div>
                    </div>
                    <br />
                    <br />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">Type</th>
                                            <th>Area (in <?=($road_type_mstr_id!=4)?"Sq. Meter":"Acre";?>)</th>
                                            <th>Rental Rate</th>
                                            <th>Occupancy Factor</th>
                                            <th style="text-align: center;">Effect From</th>
                                            <th>Yearly Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                $i = 0;
                                foreach ($new_rule_sub as $value) {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td style="text-align: center;"><?=$value['type'];?></td>
                                        <td style="text-align: right;"><?=$value['area_sqm'];?></td>
                                        <td style="text-align: right;"><?=$value['vacant_land_rate'];?></td>
                                        <td style="text-align: right;"><?=$value['occupancy_factor'];?></td>
                                        <td style="text-align: center;">Quarter : <?=$value['qtr'];?>   Financial Year : <?=$value['fyear'];?> </td>
                                        <td style="text-align: right;"><?=$value['yearly_tax'];?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br />
                    Below taxes are calculated on quarterly basis(Yearly Tax / 4).
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">Effect From</th>
                                            <th>Holding Tax</th>
                                            <th>Quarterly Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                $i = 0;
                                foreach ($safTaxDtl as $taxDtl) {
                                    if ($taxDtl['effected_from']<"2022-04-01" && $taxDtl['yearly_tax']<>0) {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
                                        <td style="text-align: right;"><?=$taxDtl['yearly_tax'];?></td>
                                        <td style="text-align: right;"><?=$taxDtl['quarterly_tax'];?></td>
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
                    <h3 class="panel-title">Tax - As Per Current Rule (Effect From 01-04-2022)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-danger"><b>Note </b></span> :
                        </div>
                        <div class="col-md-12 mar-lft">
                            Circle Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetCapitalValueRateFactor/<?= $old_ward ?>', 'newwindow', 'width=850, height=300'); return false;">Click here</a> to view circle rate.
                        </div>
                        <div class="col-md-12 mar-lft">
                            Matrix Factor Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetMatrixFactorRate', 'newwindow', 'width=400, height=250'); return false;">Click here</a> to view Matrix Factor Rate.
                        </div>
                        <div class="col-md-12 mar-lft">
                            New Vacant Land Rates : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/getVacantLandRentalNewRateFactor', 'newwindow', 'width=400, height=300'); return false;">Click here</a> here to view rental rate .
                        </div>
                        <div class="col-md-12 mar-lft">
                            Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetOccupancyFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> to view occupancy factors.
                        </div>
                    </div>
                    <?php if (!empty($cv_vacant_land_dtl)) { //print_var($cv_vacant_land_dtl);?>
                    <div class="row mar-btm  mar-top">
                        <div class="col-md-12">
                            <?php if ($road_type_mstr_id!=4) { ?>
                                <b>Tax = Area <span class="text-danger">(sqm)</span> X Rental Rate X Occupancy Factor</b>
                            <?php } else { ?>
                            <b>Tax = Area <span class="text-danger">(Acre)</span> X Rental Rate X Occupancy Factor</b>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b>For Vacant</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">Type</th>
                                            <th>Area (in <?=($road_type_mstr_id!=4)?"Sq. Meter":"Acre";?>)</th>
                                            <th>New Rental Rate</th>
                                            <th>Occupancy Factor</th>
                                            <th style="text-align: center;">Effect From</th>
                                            <th>Yearly Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                $i = 0;
                                foreach ($cv_rule_sub as $value) {
                                    if ($value["type"]=="Vacant Land") {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td style="text-align: center;"><?=$value['type'];?></td>
                                        <td style="text-align: right;"><?=$value['area_sqm'];?></td>
                                        <td style="text-align: right;"><?=$value['vacant_land_rate'];?></td>
                                        <td style="text-align: right;"><?=$value['occupancy_factor'];?></td>
                                        <td style="text-align: center;">Quarter : <?=$value['qtr'];?>   Financial Year : <?=$value['fyear'];?> </td>
                                        <td style="text-align: right;"><?=$value['yearly_cv_tax'];?></td>
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
                    <?php if ($isSafMHArv) {?>
                    <div class="row mar-top">
                        <div class="col-md-12 mar-lft">
                            <label>a. Residential - 0.075%</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label>b. Commercial - 0.150%</label>
                        </div>
                        <div class="col-md-12 mar-lft">
                            <label>c. Commercial & greater than 25000 sqft - 0.20%</label>
                        </div>
                    </div>
                    <div class="row mar-btm">
                        <div class="col-md-12">
                            <b>Property Tax = Capital Value Rate X Buildup Area X Occupancy Factor X Tax Percentage X Matrix Factor Rate (<span class="text-danger text-xs">Only in case of 100% residential property</span>)</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b>For Mobile Tower/Hoarding Board</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Area Type</th>
                                            <th>Circle Rate</th>
                                            <th>Buildup Area (in Sq. Ft)</th>
                                            <th>Occupancy Factor</th>
                                            <th>Tax Percentage</th>
                                            <th>Matrix Factor </th>
                                            <th>Effect From</th>
                                            <th style="width: 100px;">Property Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                foreach ($cv_rule_sub as $key=>$taxDtl) {
                                    if ($taxDtl["effected_from"]>="2022-04-01" && $taxDtl["type"]!="Vacant Land") {
                                ?>
                                        <tr>
                                            <td><?=$key+1;?></td>
                                            <td><?=strtoupper($taxDtl['type']);?></td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['cvr'], 2);?></td>
                                            <td style="text-align: right;"><?=$taxDtl['area_sqm'];?></td>
                                            
                                            <td style="text-align: right;"><?=number_format($taxDtl['occupancy_factor'], 2);?></td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['resi_comm_type_rate']*100, 3);?>%</td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['matrix_factor_rate'], 2);?></td>
                                            <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?> Financial Year : <?=$taxDtl['fyear'];?> </td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['yearly_cv_tax'], 2);?></td>
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
                    <?php } ?>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <b>Below taxes are calculated on quarterly basis(Yearly Tax / 4).</b>
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
                                            <th style="width: 100px;">Holding Tax</th>
                                            <th>Quarterly Tax</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                $sn = 0;
                                foreach ($safTaxDtl as $taxDtl) {
                                    if($taxDtl['rule_type']=="CV_RULE" && $taxDtl['yearly_tax']<>0){
                                ?>
                                        <tr>
                                            <td><?=++$sn;?></td>
                                            <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['yearly_tax'], 2);?></td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['quarterly_tax'], 2);?></td>
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
        if (!empty($old_rule_arv_sub)) {
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
                            Rental Rate : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(1)">Click here</a> here to view rental rate .
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
                                foreach ($floorDtlArr as $key=>$taxDtl) {
                                    if ($taxDtl["date_from"]."-01"<"2016-04-01") {
                                ?>
                                        <tr>
                                            <td><?=$key+1?></td>
                                            <td>
                                                <?php
                                                if(isset($usageTypeList)){
                                                    foreach ($usageTypeList as $usageType) {
                                                ?>
                                                <?=($usageType['id']==$taxDtl['usage_type_mstr_id'])?$usageType['usage_type']:"";?>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['old_arv_cal_method']['rental_rate'], 2);?></td>
                                            <td style="text-align: right;"><?=$taxDtl['old_arv_cal_method']['buildup_area'];?></td>
                                            <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
                                            <td style="text-align: right;"><?=number_format($taxDtl['old_arv'], 2);?></td>
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
                                            <th style="width: 100px;">ARV <br />(<span class="text-danger">Annual</span>)</th>
                                            <th>Holding Tax <br />(<span class="text-danger">Quarterly</span>)</th>
                                            <th>Water Tax <br />(<span class="text-danger">Quarterly</span>)</th>
                                            <th>Latrine/Conservancy Tax <br />(<span class="text-danger">Quarterly</span>)</th>
                                            <th>Education Cess <br />(<span class="text-danger">Quarterly</span>)</th>
                                            <th>Health Cess <br />(<span class="text-danger">Quarterly</span>)</th>
                                            <th>Quarterly Tax <br />(<span class="text-danger">Total</span>)</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                foreach ($safTaxDtl as $key=>$taxDtl) {
                                    if($taxDtl['rule_type']=="OLD_RULE"){
                                        if ($taxDtl['arv']<>0) {
                                ?>
                                            <tr>
                                                <td><?=$key+1;?></td>
                                                <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
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
    if ($prop_type_mstr_id!=4) {
        $isCurrentFinancialYearEffected = FALSE;
        foreach ($safTaxDtl as $key => $value) {
            if($value["rule_type"]=="CV_RULE") {
                $isCurrentFinancialYearEffected = TRUE;
                break;
            }
        }
        $isSafMHPArv = FALSE;
        foreach ($floorDtlArr as $key => $value) {
            if($value["type"]!="floor" && $value["type"]!="vacant") {
                $isSafMHPArv = TRUE;
                break;
            }
        }
        if (!empty($new_rule_arv_sub)) {
            //if (isset($isSafNewRuleArv) || isset($isSafMHPArv)) {
                //if ($isSafNewRuleArv==true || $isSafMHPArv==true) {
    ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Annual Rental Value - As ARV Rule (Effect From 01-04-2016 to 31-03-2022)</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-danger"><b>Note </b></span> :
                            </div>
                            <!-- 4:3 aspect ratio -->
                            
                            <!-- <div class="col-md-12 mar-lft">
                                Usage Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetUsageFactor', 'newwindow', 'width=750, height=600'); return false;">Click here</a> to view usage factors.
                            </div> -->
                            <div class="col-md-12 mar-lft">
                                Usage Factor : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(2)">Click here</a> to view usage factors.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(3)">Click here</a> here to view occupancy factors.
                            </div>
                            <div class="col-md-12 mar-lft pad-btm">
                                Rental Rate : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(4)">Click here</a> here to view rental rate .
                            </div>
                            <div class="col-md-12 mar-lft pad-btm">
                                <label>a. Carpet area for residential - 70% of buildup area</label><br />
                                <label>b. Carpet area for commercial - 80% of buildup area</label>
                            </div>
                            <div class="col-md-12">
                                <b>Annual Rental Value (ARV) = Carpet Area X Usage Factor X Occupancy Factor X Rental Rate</b>
                            </div>
                        </div>
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
                                    foreach ($floorDtlArr as $key=>$taxDtl) {
                                        if ($taxDtl["date_from"]."-01"<"2022-04-01" && $taxDtl["type"]=="floor" && $taxDtl["type"]!="vacant") {
                                    ?>
                                            <tr>
                                                <td><?=$key+1;?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['new_arv_cal_method']['usage_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['new_arv_cal_method']['occupancy_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['new_arv_cal_method']['rental_rate'], 2);?></td>
                                                <td style="text-align: right;"><?=$taxDtl['new_arv_cal_method']['carper_area'];?></td>
                                                <td style="text-align: center;">Quarter : <?=($taxDtl["date_from"]."-01"<"2016-04-01")?1:$taxDtl['qtr'];?>   Financial Year : <?=($taxDtl["date_from"]."-01"<"2016-04-01")?"2016-2017":$taxDtl['fyear'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['new_arv'], 2);?></td>
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
                    if ($isSafMHPArv==TRUE) {
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <b>For Mobile Tower/Hoarding Board/Petrol Pump</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
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
                                    foreach ($floorDtlArr as $key=>$taxDtl) {
                                        if ($taxDtl["date_from"]."-01"<"2022-04-01" && $taxDtl["type"]!="floor" && $taxDtl["type"]!="vacant") {
                                    ?>
                                            <tr>
                                                <td><?=$key+1;?></td>
                                                <td><?=strtoupper($taxDtl['type']);?></td>
                                                <td><?=$taxDtl['area_type'];?></td>
                                                <td style="text-align: right;"><?=$taxDtl['builtup_area'];?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl["new_arv_cal_method"]['usage_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl["new_arv_cal_method"]['occupancy_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl["new_arv_cal_method"]['rental_rate'], 2);?></td>
                                                <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['new_arv'], 2);?></td>
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
                                <b>Total Quarterly Tax Details ((ARV X 2%) / 4)</b>
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
                                                <th>ARV</th>
                                                <th>Holding Tax <br />(<span class="text-danger">Quarterly</span>)</th>
                                                <th>RWH Penalty</th>
                                                <th>Quarterly Tax <br />(<span class="text-danger">Total</span>)</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    foreach ($safTaxDtl as $key=>$taxDtl) {
                                        if($taxDtl['rule_type']=="NEW_RULE"){
                                            if ($taxDtl['arv']<>0) {
                                    ?>
                                                <tr>
                                                    <td><?=$key+1;?></td>
                                                    <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['holding_tax'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['additional_tax'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format(($taxDtl['holding_tax']+$taxDtl['additional_tax']), 2);?></td>
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
            <?php } ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Capital Value - As Per Current Rule (Effect From 01-04-2022)</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-danger"><b>Note </b></span> :
                            </div>
                            <div class="col-md-12 mar-lft">
                                Capital Value Rate : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(5)">Click here</a> to view capital value rate.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(6)">Click here</a> here to view occupancy factors.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Matrix Factor Rate : <a href="#" class="text-bold text-dark" onclick="dynamic_rate_modal(7)">Click here</a> to view Matrix Factor Rate.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Calculation Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetUsageFactorCV', 'newwindow', 'width=750, height=600'); return false;">Click here</a> to view calculation factors.
                            </div>
                        </div>
                        <div class="row mar-top">
                            <div class="col-md-12 mar-lft">
                                <label>a. Residential - 0.075%</label>
                            </div>
                            <div class="col-md-12 mar-lft">
                                <label>b. Commercial - 0.150%</label>
                            </div>
                        </div>
                        <?php 
                        $isSafVacantArv = FALSE;
                        foreach ($floorDtlArr as $key => $value) {
                            if($value["type"]=="vacant") {
                                $isSafVacantArv = TRUE;
                                break;
                            }
                        }
                        if ($isSafVacantArv==TRUE) {?>
                        <div class="row mar-btm">
                            <div class="col-md-12">
                                <b>Property Tax = Capital Value Rate X Vacant Area of Plot X Occupancy Factor X Tax Percentage</b>
                            </div>
                        </div>
                        <?php
                        
                        
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <b>For Vacant</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Capital Value Rate</th>
                                                <th>Vacant Area of Plot (in Decimal)</th>
                                                <th>Occupancy Factor </th>
                                                <th>Tax Percentage</th>
                                                <th>Effect From</th>
                                                <th style="width: 100px;">Property Tax</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    $sn = 0;
                                    foreach ($floorDtlArr as $key=>$taxDtl) {
                                        if ($taxDtl["date_from"]."-01"<"2024-04-01" && $taxDtl["type"]=="vacant") {
                                    ?>
                                            <tr>
                                                <td><?=++$sn;?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['cvr'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['buildup_area'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['occupancy_rate'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['resi_comm_type_rate'], 5);?></td>
                                                <td style="text-align: center;">Quarter : <?=($taxDtl["date_from"]."-01"<"2022-04-01")?1:$taxDtl['qtr'];?>   Financial Year : <?=($taxDtl["date_from"]."-01"<"2016-04-01")?"2016-2017":$taxDtl['fyear'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv'], 2);?></td>
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
                        <div class="row mar-btm">
                            <div class="col-md-12">
                                <b>Property Tax = Capital Value Rate X Buildup Area X Occupancy Factor X Tax Percentage X Calculation Factor X Matrix Factor Rate (<span class="text-danger text-xs">Only in case of 100% residential property</span>)</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <b>For Building</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Capital Value Rate</th>
                                                <th>Buildup Area (in Sq. Ft)</th>
                                                <th>Occupancy Factor </th>
                                                <th>Tax Percentage</th>
                                                <th>Calculation Factor </th>
                                                <th>Matrix Factor </th>
                                                <th>Effect From</th>
                                                <th style="width: 100px;">Property Tax</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    $sn = 0;
                                    foreach ($floorDtlArr as $key=>$taxDtl) {
                                        if ($taxDtl["date_from"]."-01"<"2024-04-01" && $taxDtl["type"]=="floor") {
                                    ?>
                                            <tr>
                                                <td><?=++$sn;?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['cvr'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['buildup_area'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['occupancy_rate'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['resi_comm_type_rate']*100, 3);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['calculation_factor'], 2);?></td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['matrix_factor_rate'], 2);?></td>
                                                <td style="text-align: center;">Quarter : <?=($taxDtl["date_from"]."-01"<"2022-04-01")?1:$taxDtl['qtr'];?>   Financial Year : <?=($taxDtl["date_from"]."-01"<"2022-04-01")?"2022-2023":$taxDtl['fyear'];?> </td>
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv'], 2);?></td>
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
                            if ($isSafMHPArv==TRUE) {
                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <b>For Mobile Tower/Hoarding Board/Petrol Pump</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-trans-dark text-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th></th>
                                                    <th>Area Type</th>
                                                    <th>Capital Value Rate</th>
                                                    <th>Buildup Area (in Sq. Ft)</th>
                                                    <th>Occupancy Factor</th>
                                                    <th>Tax Percentage</th>
                                                    <th>Effect From</th>
                                                    <th style="width: 100px;">Property Tax</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                        foreach ($floorDtlArr as $key=>$taxDtl) {
                                            if ($taxDtl["date_from"]."-01"<"2022-04-01" && $taxDtl["type"]!="floor" && $taxDtl["type"]!="vacant") {
                                        ?>
                                                <tr>
                                                    <td><?=$key+1;?></td>
                                                    <td><?=strtoupper($taxDtl['type']);?></td>
                                                    <td><?=$taxDtl['area_type'];?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['cvr'], 2);?></td>
                                                    <td style="text-align: right;"><?=$taxDtl["cv_2022_cal_method"]['buildup_area'];?></td>
                                                    
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['occupancy_rate'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['resi_comm_type_rate'], 5);?></td>
                                                    <td style="text-align: center;">Quarter : <?=($taxDtl["date_from"]."-01"<"2022-04-01")?1:$taxDtl['qtr'];?> Financial Year : <?=($taxDtl["date_from"]."-01"<"2022-04-01")?"2022-2023":$taxDtl['fyear'];?> </td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['cv'], 2);?></td>
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
                                <b>Below taxes are calculated on quarterly basis( Property Tax / 4 ).</b>
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
                                                <th>Property Tax <br />(<span class="text-danger">Annual</span>)</th>
                                                <th>Holding Tax <br />(<span class="text-danger">Quarterly</span>)</th>
                                                <th>RWH Penalty</th>
                                                <th>Quarterly Tax <br />(<span class="text-danger">Total</span>)</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    $sn = 0;
                                    foreach ($safTaxDtl as $taxDtl) {
                                        if($taxDtl['rule_type']=="CV_RULE"){
                                            if ($taxDtl['arv']<>0) {
                                    ?>
                                                <tr>
                                                    <td><?=++$sn;?></td>
                                                    <td style="text-align: center;">Quarter : <?=$taxDtl['qtr'];?>   Financial Year : <?=$taxDtl['fyear'];?> </td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['arv'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['holding_tax'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl['additional_tax'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format(($taxDtl['holding_tax']+$taxDtl['additional_tax']), 2);?></td>
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
    <?php
                //}
            //}
        //} else {
    ?>
            <!-- <div class="panel panel-bordered panel-danger">
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
            </div> -->
    <?php
        //}
    }
    ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn">
                    <div class="row">
                        <div class="col-md-12 pad-btm">
                            <div class="checkbox">
                                <input id="ok" name="ok" class="magic-checkbox" type="checkbox" onclick="confirmCkFun();"  value="1">
                                <label for="ok">I am fully aware of the legal provisions contained in this form and other rules and sections of Jharkhand Muncipal Holding Tax Rule 2013 and Jharkhand Muncipal Act 2011 and above information is correct to best of my knowledge & belief.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 text-center pad-btm">
                            <button type="SUBMIT" id="btn_back" name="btn_back" class="btn btn-primary" value="EDIT" style="text-align: left;"><i class="fa fa-arrow-left"></i> EDIT</button>
                        </div>
                        <div class="col-md-9 text-center">
                            <button type="SUBMIT" id="btn_submit" name="btn_submit" value="submitt" class="btn btn-primary" disabled>SUBMIT</button>
                            <button type="button" id="btn_submit_temp" class="btn btn-primary hidden" disabled>Please Wait...</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--holding_owner_details Bootstrap Modal-->
            <div id="send_otp-lg-modal" class="modal fade" tabindex="1">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                            <h4 class="modal-title">Please enter the 4-digit verification code we sent via SMS:</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label text-bold">Enter OTP</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group has-warning has-feedback">
                                        <input type="text" id="otp" name="otp" class="form-control" value="" />
					                </div>
                                </div>
                                <div class="col-md-7">
                                    <label class="control-label text-bold" id="sendOtpAfterLabel"></label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_opt_resend" name="btn_opt_resend" class="btn btn-primary" value="RE-SEND" disabled>RE-SEND</button>
                            <button type="button" id="btn_opt_verify" name="btn_opt_verify" class="btn btn-primary" value="VERIFY">VERIFY</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--End holding_owner_details Bootstrap Modal-->
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<!--rate chart modal-->
<div id="rate_chart_modal" class="modal fade" >
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="padding:10px 10px 10px 10px;height:50vh">
        <iframe id="rate_iframe" width="100%" height="100%" ></iframe>
            </div>
        </div>
    </div>
<!--rate chart modal-->
<?= $this->include('layout_mobi/footer');?>
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
$("#btn_submit").click(function() {
    $("#btn_submit").addClass("hidden");
    $("#btn_submit_temp").removeClass("hidden");    
});
function dynamic_rate_modal(rate_type){
    console.log(rate_type);
    if(rate_type==1){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetOldRuleRentalRateFactor');?>"
    }
    if(rate_type==2){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetUsageFactor');?>"
    }
    if(rate_type==3){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetOccupancyFactor');?>"
    }
    if(rate_type==4){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetRentalRateFactor');?>"
    }
    if(rate_type==5){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetCapitalValueRateFactor'.'/'.$old_ward);?>"
    }
    if(rate_type==6){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetOccupancyFactor');?>"
    }
    if(rate_type==7){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/citizengetMatrixFactorRate');?>"
    }
    if(rate_type==8){
        rate_chart_src="<?php echo base_url('citizen_calc_factr/getVacantLandRentalRateFactor');?>"
    }
    document.getElementById('rate_iframe').src=rate_chart_src
    $("#rate_chart_modal").modal('show');
    
}
$('#rate_chart_modal').on('hidden.bs.modal', function () {
    document.getElementById('rate_iframe').src=""
})
var intervalId;
function enableResendBtnAfter30Second() {
    var i = 0;
    intervalId = setInterval(function(){ 
        i++;
        if(i=="11") {
            document.getElementById("btn_opt_resend").innerHTML = "RE-SEND";
            $("#btn_opt_resend").prop("disabled", false);
            disableResendBtnAfter30Second();
        } else {
            var ii = i;
            if(i.length==1) {
                ii = "0"+i;
            }
            document.getElementById("btn_opt_resend").innerHTML = "RE-SEND ("+ii+")";
        }
    }, 1000);
}
function disableResendBtnAfter30Second() {
  clearInterval(intervalId);
}
function optSendAjax() {
    <?php
    if ($has_previous_holding_no==0 || ($has_previous_holding_no==1 && $is_owner_changed==0))
    {
        ?>
            var  mobile_no = $('input[name="mobile_no[]"]').val();
        <?php
    }
    else
    {
        ?>
        var mobile_no = $('input[name="prev_mobile_no[]"]').val();
        <?php
    }
    ?>
    if(mobile_no.length==10) {
        var showMobileNo = mobile_no.slice(0, 3)+"XXXX"+mobile_no.slice(7, 10);
        $.ajax({
            type:"POST",
            url: "<?=base_url();?>/CitizenSaf/sendApplicantOptInMobile",
            dataType: "json",
            data: {
                    "mobile_no":mobile_no,
            },
            beforeSend: function() {
				
                $("#otp").val('');
                $("#otp").prop("disabled", true);
                $("#btn_opt_verify").html('otp sending...');
                $("#sendOtpAfterLabel").removeClass("text-success");
                $("#sendOtpAfterLabel").addClass("text-warning");
                $("#sendOtpAfterLabel").html('OTP sent to your mobile number : '+showMobileNo);
                $("#btn_opt_verify").prop("disabled", true);
                $("#btn_opt_resend").prop("disabled", true);
				//alert(mobile_no);
            },
            success:function(data){
                if(data.response==true){
                    $("#sendOtpAfterLabel").html('OTP sent to your mobile number : '+showMobileNo);
                    $("#sendOtpAfterLabel").removeClass("text-warning");
                    $("#sendOtpAfterLabel").addClass("text-success");
                    $("#btn_opt_verify").html('VERIFY');
                    $("#btn_opt_verify").prop("disabled", false);
                    $("#otp").prop("disabled", false);
                    enableResendBtnAfter30Second();
                } else {
                    alert(data.data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }
}
function optVerifyAjax() {
    var otp = $("#otp").val();
    $.ajax({
        type:"POST",
        url: "<?=base_url();?>/CitizenSaf/applicantOptVerify",
        dataType: "json",
        data: {
                "otp":otp,
        },
        beforeSend: function() {
            $("#btn_opt_verify").html('otp verification...');            
            $("#btn_opt_verify").prop("disabled", true);
            $("#btn_opt_resend").prop("disabled", true);
        },
        success:function(data){
            if(data.response==true){
                $("#btn_submit").prop("disabled", false);
                $("#btn_submit").trigger("click");
                document.getElementById("btn_opt_resend").click();
                $("#btn_opt_verify").html('VERIFY');            
                $("#btn_opt_verify").prop("disabled", false);
                $("#btn_opt_resend").prop("disabled", false);
            } else {
                alert("Please re-verify opt...");
                $("#btn_opt_verify").html('VERIFY');            
                $("#btn_opt_verify").prop("disabled", false);
                $("#btn_opt_resend").prop("disabled", false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(JSON.stringify(jqXHR));
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
}
$("#send_otp").click(function(){
    $("#send_otp-lg-modal").modal('show');
    optSendAjax();
});
$("#btn_opt_resend").click(function(){
    optSendAjax();
});
$("#btn_opt_verify").click(function(){
    optVerifyAjax();
});

</script>