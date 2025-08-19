<?= $this->include('layout_vertical/header'); ?>
<div id="content-container"><!--CONTENT CONTAINER-->
    <div id="page-head">
        <ol class="breadcrumb"><!--Breadcrumb-->
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Government Self Assessment Form</a></li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <div id="page-content"><!--Page content-->
        <form id="form_saf_property" name="form_saf_property" method="post" action="<?= base_url('Gsaf/add_update'); ?>">
            <!-- Self Assessment Form -->
            <input type="hidden" id="prop_dtl_id" name="prop_dtl_id" value="<?=$prop_dtl_id;?>" />
            <input type="hidden" id="prop_type_mstr_id" name="prop_type_mstr_id" value="2" />
            <input type="hidden" name="building_colony_name" value="<?=$building_colony_name;?>" />
            <input type="hidden" name="office_name" value="<?=$office_name;?>" />
            <input type="hidden" name="ward_mstr_id" value="<?=$ward_mstr_id;?>" />
            <input type="hidden" name="holding_no" value="<?=$holding_no;?>" />
            <input type="hidden" name="govt_building_type_mstr_id" value="<?=$govt_building_type_mstr_id;?>" />
            <input type="hidden" name="building_colony_address" value="<?=$building_colony_address;?>" />
            <input type="hidden" name="prop_usage_type_mstr_id" value="<?=$prop_usage_type_mstr_id;?>" />
            <input type="hidden" name="zone_mstr_id" value="<?=$zone_mstr_id;?>" />
            <input type="hidden" name="road_type_mstr_id" value="<?=$road_type_mstr_id;?>" />
            <input type="hidden" name="area_of_plot" value="<?=$area_of_plot;?>" />

            <!-- Details Of Authorized Person -->
            <input type="hidden" name="designation" value="<?=$designation;?>" />
            <input type="hidden" name="address" value="<?=$address;?>" />
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

            <?php
            if (isset($validation)) {
            ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10 text-danger">
                                <?php
                                $i = 0;
                                foreach ($validation as $errMsg) {
                                    $i++;
                                    echo $i . ") " . $errMsg;
                                    echo ".<br />";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Government Building Self Assessment (GBSAF) Review</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-4">Name of Building</label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= $building_colony_name; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Name of office operated by the Building</label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= $office_name; ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4"> Ward No. </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php
                            if (isset($wardList)) {
                                foreach ($wardList as $wardLists) {
                            ?>
                                    <?= (isset($ward_mstr_id)) ? ($wardLists['id'] == $ward_mstr_id) ? $wardLists['ward_no'] : "" : ""; ?>
                            <?php
                                }
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4"> Holding No.(Previous holding no. if any) </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= ($holding_no!="")?$holding_no:"N/A"; ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4">Building Address </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= $building_colony_address; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Govt. Building Usage Type </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php
                            if (isset($govtBuildTypeList)) {
                                foreach ($govtBuildTypeList as $buildType) {
                            ?>
                                    <?= (isset($govt_building_type_mstr_id)) ? ($buildType['id'] == $govt_building_type_mstr_id) ? $buildType['building_type'] : "" : ""; ?>
                            <?php
                                }
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4">Property Usage Type </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php
                            if (isset($propUsageTypeList)) {
                                foreach ($propUsageTypeList as $propTypeLists) {
                            ?>
                                    <?= (isset($prop_usage_type_mstr_id)) ? ($propTypeLists['id'] == $prop_usage_type_mstr_id) ? $propTypeLists['prop_usage_type'] : "" : ""; ?>
                            <?php
                                }
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Zone </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php
                            if (isset($zone_mstr_id)) {
                                echo $zone_mstr_id;
                            }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4">Width of Road </label>
                        <div class="col-md-3 text-bold">
                            <?php
                            if (isset($roadTypeList)) {
                                foreach ($roadTypeList as $roadType) {
                            ?>
                                    <?= (isset($road_type_mstr_id)) ? ($roadType['id'] == $road_type_mstr_id) ? $roadType['road_type'] : "" : ""; ?>
                            <?php
                                }
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Area of plot (In Decimal) </label>
                        <div class="col-md-3 text-bold">
                            <?=$area_of_plot;?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Details Of Authorized Person for the payment of Property Tax</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-4 text-bold">Authorized Person for the payment of Property Tax</label>
                    </div>
                    <div class="row"><br>
                        <label class="col-md-2">Designation</label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= $designation; ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-2"> Address </label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= $address; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Floor Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">

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
                                        <tbody id="floor_dtl_append" class="text-bold">
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

            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                        <label class="col-md-3">Does Property Have Mobile Tower(s) ?</label>
                        <div class="col-md-3 pad-btm text-bold">
                                <?=(isset($is_mobile_tower))?($is_mobile_tower=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_mobile_tower))?($is_mobile_tower=="0")?"hidden":"":"";?>">
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
                        <div class="col-md-3 pad-btm text-bold">
                            <?=(isset($is_hoarding_board))?($is_hoarding_board=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_hoarding_board))?($is_hoarding_board=="0")?"hidden":"":"";?>">
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
                        <div class="col-md-3 pad-btm text-bold">
                            <?=(isset($is_petrol_pump))?($is_petrol_pump=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($is_petrol_pump))?($is_petrol_pump=="0")?"hidden":"":"";?>">
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
                        <div class="col-md-3 pad-btm text-bold">
                            <?=(isset($is_water_harvesting))?($is_water_harvesting=="1")?"YES":"NO":"N/A";?>
                        </div>
                    </div>
                </div>
            </div>
    



            <?php
    if ($prop_type_mstr_id==4) {
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
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                            Rental Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetOldRuleRentalRateFactor', 'newwindow', 'width=750, height=400'); return false;">Click here</a> here to view rental rate .
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
                                            <th style="width: 100px;">ARV</th>
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
                            <div class="col-md-12 mar-lft">
                                Usage Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetUsageFactor', 'newwindow', 'width=750, height=600'); return false;">Click here</a> to view usage factors.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetOccupancyFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> here to view occupancy factors.
                            </div>
                            <div class="col-md-12 mar-lft pad-btm">
                                Rental Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetRentalRateFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> here to view rental rate .
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
                                                <th style="width: 100px;">ARV</th>
                                                <th>Holding Tax (<span class="text-danger">Quarterly</span>)</th>
                                                <th>RWH Penalty</th>
                                                <th>Quarterly Tax (<span class="text-danger">Total</span>)</th>
                                                
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
                                Capital Value Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetCapitalValueRateFactor/<?= $ward_mstr_id ?>', 'newwindow', 'width=850, height=300'); return false;">Click here</a> to view capital value rate.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Occupancy Factor : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetOccupancyFactor', 'newwindow', 'width=400, height=250'); return false;">Click here</a> here to view occupancy factors.
                            </div>
                            <div class="col-md-12 mar-lft">
                                Matrix Factor Rate : <a href="#" class="text-bold text-dark" onclick="window.open('<?=base_url();?>/citizen_calc_factr/citizengetMatrixFactorRate', 'newwindow', 'width=400, height=250'); return false;">Click here</a> to view Matrix Factor Rate.
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
                            <div class="col-md-12 mar-lft">
                                <label>c. Commercial & greater than 25000 sqft - 0.20%</label>
                            </div>
                        </div>
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
                                                <td style="text-align: right;"><?=number_format($taxDtl['cv_2022_cal_method']['resi_comm_type_rate'], 5);?></td>
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
                                        <table class="table table-bordered text-sm">
                                            <thead class="bg-trans-dark text-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th></th>
                                                    <th>Area Type</th>
                                                    <th>Capital Value Rate</th>
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
                                        foreach ($floorDtlArr as $key=>$taxDtl) {
                                            if ($taxDtl["date_from"]."-01"<="2022-04-01" && $taxDtl["type"]!="floor" && $taxDtl["type"]!="vacant") {
                                        ?>
                                                <tr>
                                                    <td><?=$key+1;?></td>
                                                    <td><?=strtoupper($taxDtl['type']);?></td>
                                                    <td><?=$taxDtl['area_type'];?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['cvr'], 2);?></td>
                                                    <td style="text-align: right;"><?=$taxDtl["cv_2022_cal_method"]['buildup_area'];?></td>
                                                    
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['occupancy_rate'], 2);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['resi_comm_type_rate'], 5);?></td>
                                                    <td style="text-align: right;"><?=number_format($taxDtl["cv_2022_cal_method"]['matrix_factor_rate'], 2);?></td>
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
                                                <th>Property Tax (<span class="text-danger">Quarterly</span>)</th>
                                                <th>Holding Tax (<span class="text-danger">Quarterly</span>)</th>
                                                <th>RWH Penalty</th>
                                                <th>Quarterly Tax (<span class="text-danger">Total</span>)</th>
                                                
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
    }
    ?>






            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn">
                    <div class="row">
                        <div class="col-md-12 pad-btm">
                            <div class="checkbox">
                                <input id="ok" name="ok" class="magic-checkbox" type="checkbox" onclick="confirmCkFun();" value="1">
                                <label for="ok">Above given information is correct to best of citizen's knowledge & belief.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 text-center pad-btm">
                            <button type="SUBMIT" id="btn_back" name="btn_back" class="btn btn-primary" value="EDIT" style="text-align: left;"><i class="fa fa-arrow-left"></i> EDIT</button>
                        </div>
                        <div class="col-md-9 text-center">

                            <button type="SUBMIT" id="btn_submit" name="btn_submit" value="submitt" class="btn btn-primary" disabled>SUBMIT</button>

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
<script type="text/javascript">
    function confirmCkFun() {
        try {
            if ($("#ok").prop("checked") == true) {
                $("#btn_submit").prop("disabled", false);
            } else {
                $("#btn_submit").prop("disabled", true);
            }
        } catch (err) {
            alert(err.message);
        }
    }
</script>