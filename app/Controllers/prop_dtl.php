<?=$this->include('layout_vertical/header');?>
<?php
$session = session();

?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <form>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Government Property Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Hoding No </label>
                        <div class="col-md-3 text-bold pad-btm">
                        <?=($holding_no!="")?$holding_no:"N/A";?>
                        </div>
                        <label class="col-md-3">Unique Hoding No </label>
                        <div class="col-md-3 text-bold pad-btm">
                        <?=($new_holding_no!="")?$new_holding_no:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Ward No</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($ward_no))?$ward_no:"N/A";?>
                        </div>
                        <label class="col-md-3">Ownership Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($ownership_type))?$ownership_type:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Property Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($property_type))?$property_type:"N/A";?>
                        </div>
                    </div>
                   <?php if($appartment_name){ if($appartment_name!='') { ?>
                        <div class="row">
                            <label class="col-md-3">Appartment Name</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($appartment_name))?$appartment_name:"N/A";?>
                            </div>
                            <label class="col-md-3">Registry Date</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($flat_registry_date))?$flat_registry_date:"N/A";?>
                            </div>
                        </div>
                        <?php } } ?>
                   
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($road_type))?$road_type:"N/A";?>
                        </div>
                    </div>
                <?php
                $db_name = $session->get('ulb_dtl');
                print_var($db_name);
                if ($db_name['ulb_mstr_id']==1)
                {
                    ?>
                    <div class="row">
                        <label class="col-md-3">Zone</label>
                        <div class="col-md-3 text-bold">
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
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append" class="">
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
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                <?php
                                if (isset($prop_owner_detail)) {
                                    foreach ($prop_owner_detail as $owner_detail) {
                                ?>
                                        <tr>
                                            <td>
                                                <?=$owner_detail['owner_name'];?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['guardian_name']!="")?$owner_detail['guardian_name']:"N/A";?>
                                            </td>
                                            <td>
                                            <?php if($owner_detail['relation_type']!=""){?>
                                                <?=($owner_detail['relation_type']=="S/O")?"S/O":"";?>
                                                <?=($owner_detail['relation_type']=="D/O")?"D/O":"";?>
                                                <?=($owner_detail['relation_type']=="W/O")?"W/O":"";?>
                                                <?=($owner_detail['relation_type']=="C/O")?"C/O":"";?>
                                            <?php }else{?>
                                                N/A
                                            <?php }?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['mobile_no'];?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['aadhar_no']!="")?$owner_detail['aadhar_no']:"N/A";?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['pan_no']!="")?$owner_detail['pan_no']:"N/A";?>
                                            </td>
                                            <td>
                                                <?=($owner_detail['email']!="")?$owner_detail['email']:"N/A";?>
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
                    <h3 class="panel-title">Electricity Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row <?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id!=2))?"hidden":"":"";?>">
                        <label class="col-md-10">
                            <div class="checkbox">
                                <?php
                                if($no_electric_connection=='t'){
                                    echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                }else{
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
                            <?=($elect_consumer_no!="")?$elect_consumer_no:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">ACC No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($elect_acc_no!="")?$elect_acc_no:"N/A";?>
                        </div>
                        <label class="col-md-3">BIND/BOOK No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($elect_bind_book_no!="")?$elect_bind_book_no:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Electricity Consumer Category</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($elec_cons_category!="")?$elec_cons_category:"N/A";?>
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
                            <?=($building_plan_approval_no!="")?$building_plan_approval_no:"N/A";?>
                        </div>
                        <label class="col-md-3">Building Plan Approval Date </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($building_plan_approval_date!="")?$building_plan_approval_date:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Water Consumer No. </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($water_conn_no!="")?$water_conn_no:"N/A";?>
                        </div>
                        <label class="col-md-3">Water Connection Date</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($water_conn_date!="")?$water_conn_date:"N/A";?>
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
                            <?=(isset($khata_no))?$khata_no:"";?>
                        </div>
                        <label class="col-md-3">Plot No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($plot_no))?$plot_no:"";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Village/Mauja Name</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($village_mauja_name))?$village_mauja_name:"";?>
                        </div>
                        <label class="col-md-3">Area of Plot (in Decimal)</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($area_of_plot))?$area_of_plot:"";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 text-bold pad-btm">
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
                            <?=(isset($prop_address))?$prop_address:"";?>
                        </div>
                        
                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($prop_city))?$prop_city:"";?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($prop_dist))?$prop_dist:"";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($prop_pin_code))?$prop_pin_code:"";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-5">
                            <div class="checkbox">
                                <?php
                                if(isset($is_corr_add_differ)){
                                    echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                }else{
                                    echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                                }
                                ?>
                                <label>If Corresponding Address Different from Property Address</label>
                                
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark <?=(!isset($is_corr_add_differ))?"hidden":"";?>">
                <div class="panel-heading">
                    <h3 class="panel-title">Correspondence Address</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Correspondence Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?=(isset($corr_address))?$corr_address:"N/A";?>
                        </div>
                        
                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($corr_city))?$corr_city:"N/A";?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?=(isset($corr_dist))?$corr_dist:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?=(isset($corr_state))?$corr_state:"N/A";?>
                        </div>
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?=(isset($corr_pin_code))?$corr_pin_code:"N/A";?>
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
                                                <th>Usege Type</th>
                                                <th>Occupancy Type</th>
                                                <th>Construction Type</th>
                                                <th>Built Up Area  (in Sq. Ft)</th>
                                                <th>From Date</th>
                                                <th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="floor_dtl_append">
                                    <?php
                                    if(isset($prop_floor_details)) {
                                        foreach ($prop_floor_details as $floor_details) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <?=(isset($floor_details['floor_name']))?$floor_details['floor_name']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['usage_type']))?$floor_details['usage_type']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['occupancy_name']))?$floor_details['occupancy_name']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['construction_type']))?$floor_details['construction_type']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['builtup_area']))?$floor_details['builtup_area']:"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['date_from']))?date('Y-m', strtotime($floor_details['date_from'])):"N/A";?>
                                                </td>
                                                <td>
                                                    <?=(isset($floor_details['date_upto']))?date('Y-m', strtotime($floor_details['date_upto'])):"N/A";?>
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
                                <?=(isset($is_mobile_tower))?($is_mobile_tower=="t")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_mobile_tower))?($is_mobile_tower=="f")?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Total Area Covered by Mobile Tower & its
Supporting Equipments & Accessories (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($tower_area))?$tower_area:"";?>
                            </div>
                            <label class="col-md-3">Date of Installation of Mobile Tower</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($tower_installation_date))?$tower_installation_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Does Property Have Hoarding Board(s) ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($is_hoarding_board))?($is_hoarding_board=="t")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_hoarding_board))?($is_hoarding_board=="f")?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($hoarding_area))?$hoarding_area:"";?>
                            </div>
                            <label class="col-md-3">Date of Installation of Hoarding Board(s)</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($hoarding_installation_date))?$hoarding_installation_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Is property a Petrol Pump ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($is_petrol_pump))?($is_petrol_pump=="t")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_petrol_pump))?($is_petrol_pump=="f")?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">    
Underground Storage Area (in Sq. Ft.)</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($under_ground_area))?$under_ground_area:"";?>
                            </div>
                            <label class="col-md-3">Completion Date of Petrol Pump</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($petrol_pump_completion_date))?$petrol_pump_completion_date:"";?>
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Rainwater harvesting provision ?</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($is_water_harvesting))?($is_water_harvesting=="t")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($prop_type_mstr_id))?($prop_type_mstr_id!=4)?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier)</label>
                            <div class="col-md-3 text-bold">
                                <?=(isset($land_occupation_date))?$land_occupation_date:"";?>
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
                                <th>Additional Tax</th>
                                <th>Quarterly Tax</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($prop_tax_list):
                            $i=1; $qtr_tax=0;?>
                        <?php foreach($prop_tax_list as $tax_list): 
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
                            <?php if($currentFY==$tax_list['fy']){ ?>
                                <td class="text-success">Current</td>
                            <?php } else { ?>
                                <td class="text-danger">Old</td>
                            <?php } ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" style="text-align:center;color:red;">Data Are Not Available!!</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Demand Detail</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-sm">
                        <thead class="bg-trans-dark text-dark">
                                <th>Sl No.</th>
                                <th>Quarter / Year</th>
                                <th>Demand</th>
                                <th>Fine</th>
                                <th>Total Demand</th>
                                <th>Paid Status</th>
                        </thead>
                        <tbody>
                        <?php 
                        if ($prop_demand_list) {
                            $i = 1;
                            foreach ($prop_demand_list as $demand_detail) {
                        ?>			
                            <tr>
                                <td><?=$i++;?></td>
                                <td><?=$demand_detail['qtr'];?> / <?=$demand_detail['fy'];?></td>
                                <td><?=round($demand_detail['amount'], 2);?></td>
                                <td><?=round($demand_detail['fine_tax'], 2);?></td>
                                <td><?=round($demand_detail['amount']+$demand_detail['fine_tax'], 2);?></td>
                                <td><?=($demand_detail['paid_status']==1)?"<span class='text-success'>PAID</span>":"<span class='text-danger'>NOT PAID</span>";?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment Details</h3>
                </div>
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
                            if(isset($payment_detail)) {
                            $i=1;
                            ?>
                            <?php foreach($payment_detail as $payment_detail) {
                            ?>
                            <tr>
                                <td><?=$i++;?></td>
                                <td class="text-bold"><?=$payment_detail['tran_no'];?></td>
                                <td><?=$payment_detail['transaction_mode'] ?></td>
                                <td><?=$payment_detail['tran_date'];?></td>
                                <td><?=$payment_detail['from_qtr']." / ".$payment_detail['fy'];?></td>
                                <td><?=$payment_detail['upto_qtr']." / ".$payment_detail['upto_fy'];?></td>
                                <td><?=$payment_detail['payable_amt'];?></td>
                                
                                <td><a href="<?php echo base_url('safDemandPayment/saf_payment_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
                                
                            </tr>
                            <?php } ?>
                            <?php 
                            } else {?>
                            <tr>
                                <td colspan="9" class="text-danger text-bold text-center"> No Any Transaction ...</td>
                            </tr>
                            <?php 
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>