<?= $this->include('layout_vertical/header'); ?>
<link href="<?= base_url(); ?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<style>
    .error {
        color: red;
    }

    .labelHeading {
        font-weight: bolder;
    }

    .box {
        border: 2px solid #c58c43;
        padding: 8px;
        text-align: center;
        margin: 4px;
        background-color: #eee;
    }

    .row {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        margin: 8px 0;
    }

    .col {
        flex: 1;
        min-width: 100px;
        margin: 2px;
    }

    .input-cell {
        background-color: #f1f1f1;
        height: 30px;
    }

    /* .bolder-border th,
    .bolder-border td {
        border: 3px solid !important;
    } */

    /* table.verification td:first-child {
        background-color: #000000 !important; 
        font-weight: bold !important;
        color: #FFFFFF;
    } */
    /* table.verification td:nth-child(2) {
        background-color: #007135 !important; 
        font-weight: bold !important;
        color: #FFFFFF;
    } */
    /* table.verification td:nth-child(3) {
        background-color: #3228bfff !important; 
        font-weight: bold !important;
    } */

    /* table.verification td:nth-child(4) {
        background-color: #e9461dff !important; 
        font-weight: bold !important;
    } */

    /* table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    th,
    td {
        border: 1px solid #999;
        padding: 6px;
        text-align: center;
    }

    th {
        background-color: #ddd;
    } */

    .section-title {
        font-weight: bold;
        background-color: #ccc;
        padding: 5px;
        margin: 10px 0 5px;
    }

    /* ✅ Preview style for image/video */
    .preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        margin: 5px;
        border: 1px solid #999;
    }

    .previewArea {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
        justify-content: center;
    }
</style>




<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">

        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->


        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF</a></li>
            <li><a href="<?= base_url("safdtl/full/" . md5($trxn["prop_dtl_id"])); ?>">SAF Details</a></li>
            <li class="active">Payment Receipt</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->

    </div>

    <!-- ======= Cta Section ======= -->

    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title"> Search Property</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" action="">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label" for="holding_no"><b>Holding No.</b> <span class="text-danger">*</span></label>
                                    <input type="text" id="holding_no" name="holding_no" value="<?php echo isset($holding_no) ? $holding_no : ''; ?>" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="">&nbsp;</label>
                                    <button class="btn btn-primary btn-block" id="btn_search" value="true" name="btn_search" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            if (isset($holding)) {
            ?>
                <form id="FORMNAME1" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="prop_dtl_id" value="<?= $holding["id"]; ?>">
                    <div class="panel-heading">
                        <h5 class="panel-title">Physical Verification</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="box">Property Tax</div>
                            <div class="box"> Date : <input class="form-control" type="date" name="date" value="<?= date("Y-m-d") ?>" max="<?= date("Y-m-d") ?>" required /> </div>
                        </div>

                        <div class="row">
                            old Holding No: <div class="box"><?= $holding["holding_no"]; ?></div>
                        </div>
                        <div class="row">
                            New Holding No: <div class="box"><?= $holding["new_holding_no"]; ?></div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered verification bolder-border">
                                <tr>
                                    <th>Property</th>
                                    <th>Actual</th>
                                    <th>Physical Verification</th>
                                    <th>Incremental</th>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Old Ward No.</td>
                                    <td><input type="hidden" name="ward_mstr_id" id="ward_mstr_id" value="<?= $holding['ward_mstr_id'] ?>" />
                                        <div><?= $holding["ward_no"] ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_ward_mstr_id" id="physical_ward_mstr_id" class="form-control variationTaxFactor" required onchange="getNewWardList(this)">
                                            <option value="">select</option>
                                            <?php
                                            foreach ($holding["master_data"]["wardList"] as $item) {
                                            ?>
                                                <option value="<?= $item['id'] ?>"><?= $item["ward_no"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">New Ward No.</td>
                                    <td><input type="hidden" name="new_ward_mstr_id" id="new_ward_mstr_id" value="<?= $holding['new_ward_mstr_id'] ?>" />
                                        <div><?= $holding["new_ward_no"] ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_new_ward_mstr_id" id="physical_new_ward_mstr_id" class="form-control variationTaxFactor" required>
                                            <option value="">select</option>
                                            <?php
                                            foreach ($holding["master_data"]["newWardList"] as $item) {
                                            ?>
                                                <option value="<?= $item['id'] ?>"><?= $item["ward_no"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Zone</td>
                                    <td><input type="hidden" name="zone_mstr_id" id="zone_mstr_id" value="<?= $holding['zone_mstr_id'] ?>" />
                                        <div class=""><?= $holding["zone"] ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_zone_mstr_id" id="physical_zone_mstr_id" class="form-control variationTaxFactor" required>
                                            <option value="">select</option>
                                            <?php
                                            foreach ($holding["master_data"]["zoneList"] as $item) {
                                            ?>
                                                <option value="<?= $item['id'] ?>"><?= $item["zone"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Property Type</td>
                                    <td>
                                        <input type="hidden" name="prop_type_mstr_id" value="<?= $holding['prop_type_mstr_id'] ?>" />
                                        <div><?= $holding["property_type"] ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_prop_type_mstr_id" id="physical_prop_type_mstr_id" class="form-control variationTaxFactor" required onchange="remove_ExteraFloor(this.value);">
                                            <option value="">select</option>
                                            <?php
                                            foreach ($holding["master_data"]["propertyTypeList"] as $item) {
                                            ?>
                                                <option value="<?= $item['id'] ?>"><?= $item["property_type"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Area of Plot (in decimal)</td>
                                    <td><input type="hidden" name="area_of_plot" id="area_of_plot" value="<?= $holding['area_of_plot'] ?>" />
                                        <div class=""><?= $holding["area_of_plot"] ?></div>
                                    </td>
                                    <td>
                                        <input type="number" name="physical_area_of_plot" id="physical_area_of_plot" class="form-control variationTaxFactor" onchange="calculateDiff('area_of_plot','physical_area_of_plot','area_of_plot_diff')" required min="0" step="0.01" placeholder="Enter area in sq. ft." />
                                    </td>
                                    <td><input type="text" class="diff form-control" id="area_of_plot_diff" /></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Road Type</td>
                                    <td><input type="hidden" name="road_type_mstr_id" value="<?= $holding['road_type_mstr_id'] ?>" />
                                        <div class=""><?= $holding["road_type"] ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_road_type_mstr_id" id="physical_road_type_mstr_id" class="form-control variationTaxFactor" required>
                                            <option value="">select</option>
                                            <?php
                                            foreach ($holding["master_data"]["roadList"] as $item) {
                                            ?>
                                                <option value="<?= $item['id'] ?>"><?= $item["road_type"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor">
                                    <td class="labelHeading">Hoarding Board(s)</td>
                                    <td>
                                        <input type="hidden" name="is_hoarding_board" value="<?= $holding['is_hoarding_board'] ?>" />
                                        <div class=""><?= $holding["is_hoarding_board"] ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_is_hoarding_board" id="physical_is_hoarding_board" class="form-control variationTaxFactor" onchange="shoHideExtraData(this,'holdingBodeData')" required>
                                            <option value="">select</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor holdingBodeData">
                                    <td class="labelHeading">Installation Date of Hoarding Board(s) :</td>
                                    <td>
                                        <input type="hidden" name="hoarding_installation_date" value="<?= $holding['hoarding_installation_date'] ?>" />
                                        <div class=""><?= $holding["hoarding_installation_date"] ?></div>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control variationTaxFactor" max="<?= date("Y-m-d") ?>" name="physical_hoarding_installation_date" required />
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor holdingBodeData">
                                    <td class="labelHeading">Area of Hoarding Board(s) :</td>
                                    <td>
                                        <input type="hidden" name="hoarding_area" id="hoarding_area" value="<?= $holding['hoarding_area'] ?>" />
                                        <div class=""><?= $holding["hoarding_area"] ?></div>
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control variationTaxFactor" name="physical_hoarding_area" id="physical_hoarding_area" onchange="calculateDiff('hoarding_area','physical_hoarding_area','hoarding_area_diff')" required />
                                    </td>
                                    <td><input type="text" class="form-control diff" id="hoarding_area_diff" /></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Mobile Tower</td>
                                    <td><input type="hidden" name="is_mobile_tower" value="<?= $holding['is_mobile_tower'] ?>" />
                                        <div class=""><?= $holding["is_mobile_tower"] ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_is_mobile_tower" id="physical_is_mobile_tower" class="form-control variationTaxFactor" onchange="shoHideExtraData(this,'mobileTower')" required>
                                            <option value="">select</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor mobileTower">
                                    <td class="labelHeading">Installation Date of Tower :</td>
                                    <td>
                                        <input type="hidden" name="tower_installation_date" value="<?= $holding['tower_installation_date'] ?>" />
                                        <div class=""><?= $holding["tower_installation_date"] ?></div>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control variationTaxFactor" max="<?= date("Y-m-d") ?>" name="physical_tower_installation_date" required />
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor mobileTower">
                                    <td class="labelHeading">Area of Tower :</td>
                                    <td>
                                        <input type="hidden" name="tower_area" id="tower_area" value="<?= $holding['tower_area'] ?>" />
                                        <div class=""><?= $holding["tower_area"] ?></div>
                                    </td>
                                    <td>
                                        <input type="tel" class="form-control variationTaxFactor" name="physical_tower_area" id="physical_tower_area" onchange="calculateDiff('tower_area','physical_tower_area','tower_area_diff')" required />
                                    </td>
                                    <td><input type="text" class="form-control diff" id="tower_area_diff" /></td>
                                </tr>
                                <tr class="extraFactor">
                                    <td class="labelHeading">Petrol Pump</td>
                                    <td>
                                        <input type="hidden" name="is_petrol_pump" value="<?= $holding['is_petrol_pump'] ?>" />
                                        <div class=""><?= $holding["is_petrol_pump"] ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_is_petrol_pump" id="physical_is_petrol_pump" class="form-control variationTaxFactor" onchange="shoHideExtraData(this,'petrolPump')" required>
                                            <option value="">select</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor petrolPump">
                                    <td class="labelHeading">Completion Date of Petrol Pump :</td>
                                    <td>
                                        <input type="hidden" name="petrol_pump_completion_date" value="<?= $holding['petrol_pump_completion_date'] ?>" />
                                        <div class=""><?= $holding["petrol_pump_completion_date"] ?></div>
                                    </td>
                                    <td>
                                        <input type="date" max="<?= date("Y-m-d") ?>" class="form-control variationTaxFactor" name="physical_petrol_pump_completion_date" required />
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="extraFactor petrolPump">
                                    <td class="labelHeading">Under Ground Area :</td>
                                    <td>
                                        <input type="hidden" name="under_ground_area" id="under_ground_area" value="<?= $holding['under_ground_area'] ?>" />
                                        <div class=""><?= $holding["under_ground_area"] ?></div>
                                    </td>
                                    <td>
                                        <input type="tel" name="physical_under_ground_area" id="physical_under_ground_area" onchange="calculateDiff('under_ground_area','physical_under_ground_area','under_ground_area_diff')" class="form-control variationTaxFactor"  required />
                                    </td>
                                    <td><input type="text" class="form-control diff" id="under_ground_diff" /></td>
                                </tr>
                                <tr class="extraFactor">
                                    <td class="labelHeading">Rainwater Harvesting Provision</td>
                                    <td><input type="hidden" name="is_water_harvesting" value="<?= $holding['is_water_harvesting'] ?>" />
                                        <div class=""><?= $holding["is_water_harvesting"] ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <select name="physical_is_water_harvesting" id="physical_is_water_harvesting" class="form-control variationTaxFactor" onchange="shoHideExtraData(this,'harvestingData')" required>
                                            <option value="">select</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                                <tr class="lanceOccupiedDate" style="display:<?= $holding['prop_type_mstr_id'] == 4 ? '' : 'none' ?>;">
                                    <td class="labelHeading">Land Occupied Date</td>
                                    <td><input type="hidden" name="occupation_date" value="<?= $holding['occupation_date'] ?>" />
                                        <div class=""><?= $holding["occupation_date"]; ?></div>
                                    </td>
                                    <td>
                                        <input type="date" max="<?= date("Y-m-d") ?>" name="physical_occupation_date" id="physical_occupation_date" class="form-control variationTaxFactor" required />

                                    </td>
                                    <td><input type="text" class="form-control diff" /></td>
                                </tr>
                            </table>
                        </div>
                        <?php
                        if (isset($holding["floor_wise_tax"])) {

                            foreach ($holding["floor_wise_tax"] as $key => $floor) {
                        ?>
                                <div class="panel panel-bordered panel-dark" id="old_floor_<?=$key?>">
                                    <input type="hidden" name="floors[<?= $key ?>][old_floor]" value="<?= $key; ?>">
                                    <div class="panel-heading">
                                        <input type="hidden" name="floors[<?= $key ?>][floor_mstr_id]" value="<?= $floor["floor_mstr_id"]; ?>">
                                        <h5 class="panel-title"><?= $floor["floor_name"] ?? ""; ?></h5>
                                    </div>
                                    <div class="panel-body">
                                        <input type="hidden" name="floors[<?= $key; ?>][id]" value="<?= $floor["id"] ?? $floor["floor_name"] ?>" />
                                        <table class="table table-striped table-bordered verification bolder-border">
                                            <tr>
                                                <th>Property</th>
                                                <th>Actual</th>
                                                <th>Physical Verification</th>
                                                <th>Incremental</th>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Use Type</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][usage_type_mstr_id]" value="<?= $floor['usage_type_mstr_id'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["usage_type"] ?></div>
                                                </td>
                                                <td>
                                                    <select class="form-control variationTaxFactor" name="floors[<?= $key; ?>][physical_usage_type_mstr_id]" required>
                                                        <option value="">select</option>
                                                        <?php
                                                        foreach ($holding["master_data"]["usageTypeList"] as $val) {
                                                        ?>
                                                            <option value="<?= $val['id']; ?>"><?= $val['usage_type'] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control diff" /></td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Occupancy Type</td>
                                                <td>
                                                    <input type="hidden" name="floors[<?= $key; ?>][occupancy_type_mstr_id]" value="<?= $floor['occupancy_type_mstr_id'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["occupancy_type"] ?></div>
                                                </td>
                                                <td>
                                                    <select class="form-control variationTaxFactor" name="floors[<?= $key; ?>][physical_occupancy_type_mstr_id]" required>
                                                        <option value="">select</option>
                                                        <?php
                                                        foreach ($holding["master_data"]["occupancyTypeList"] as $val) {
                                                        ?>
                                                            <option value="<?= $val['id']; ?>"><?= $val['occupancy_name'] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td> <input type="text" class="form-control diff" /> </td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Construction Type</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][const_type_mstr_id]" value="<?= $floor['const_type_mstr_id'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["construction_type"] ?></div>
                                                </td>
                                                <td>
                                                    <select class="form-control variationTaxFactor" name="floors[<?= $key; ?>][physical_const_type_mstr_id]" required>
                                                        <option value="">select</option>
                                                        <?php
                                                        foreach ($holding["master_data"]["constructionTypeList"] as $val) {
                                                        ?>
                                                            <option value="<?= $val['id']; ?>"><?= $val['construction_type'] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td> <input type="text" class="form-control diff"  /> </td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Area</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][builtup_area]" id="floors_<?=$key?>_builtup_area" value="<?= $floor['builtup_area'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["builtup_area"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_builtup_area]" id="floors_<?=$key?>_physical_builtup_area" onchange="calculateDiff('floors_<?=$key?>_builtup_area','floors_<?=$key?>_physical_builtup_area','floors_<?=$key?>_builtup_diff')" class="form-control variationTaxFactor" required /></td>
                                                <td><input type="text" class="form-control diff" id="floors_<?=$key?>_builtup_diff" /></td>
                                            </tr>

                                            <tr>
                                                <td class="labelHeading">Date From</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][date_from]" value="<?= $floor['date_from'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["date_from"] ?></div>
                                                </td>
                                                <td><input type="month" max="<?= date('Y-m') ?>" name="floors[<?= $key; ?>][physical_date_from]" class="form-control variationTaxFactor" required /></td>
                                                <td> <input Type="text" class="form-control diff" /> </td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Date Upto</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][date_upto]" value="<?= $floor['date_upto'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["date_upto"] ?></div>
                                                </td>
                                                <td><input type="month" max="<?= date('Y-m') ?>" name="floors[<?= $key; ?>][physical_date_upto]" class="form-control variationTaxFactor" /></td>
                                                <td> <input Type="text" class="form-control diff" /> </td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">CV Rate</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][cv_rate]" id="old_floor_<?=$key?>_cv_rate" value="<?= $floor['cv_rate'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["cv_rate"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_cv_rate]" id="old_floor_<?=$key?>_physical_cv_rate" class="form-control" /></td>
                                                <td> <input Type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_cv_rate" /> </td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Occupancy Factor</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][occupancy_rate]" id="old_floor_<?=$key?>_occupancy_rate" value="<?= $floor['occupancy_rate'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["occupancy_rate"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_occupancy_rate]" id="old_floor_<?=$key?>_physical_occupancy_rate" class="form-control" /></td>
                                                <td> <input Type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_occupancy_rate" /> </td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Tax Percentage</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][tax_percent]" id="old_floor_<?=$key?>_tax_percent" value="<?= $floor['tax_percent'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["tax_percent"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_tax_percent]" id="old_floor_<?=$key?>_physical_tax_percent" class="form-control" /></td>
                                                <td><input type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_tax_percent" /></td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Calculation Factor</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][calculation_factor]" id="old_floor_<?=$key?>_calculation_factor" value="<?= $floor['calculation_factor'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["calculation_factor"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_calculation_factor]" id="old_floor_<?=$key?>_physical_calculation_factor"  class="form-control" /></td>
                                                <td><input type="text" id="old_floor_<?=$key?>_diff_calculation_factor" class="form-control diff" /></td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Matrix Factor</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][matrix_factor_rate]" id="old_floor_<?=$key?>_matrix_factor_rate" value="<?= $floor['matrix_factor_rate'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["matrix_factor_rate"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_matrix_factor_rate]" id="old_floor_<?=$key?>_physical_matrix_factor_rate" class="form-control" /></td>
                                                <td><input type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_matrix_factor_rate" /></td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Holding Tax</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][holding_tax]" id="old_floor_<?=$key?>_holding_tax" value="<?= $floor['holding_tax'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["holding_tax"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_holding_tax]" id="old_floor_<?=$key?>_physical_holding_tax" class="form-control" /></td>
                                                <td><input type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_holding_tax" /></td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Quarterly Holding Tax</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][quaterly_holding_tax]" id="old_floor_<?=$key?>_quaterly_holding_tax" value="<?= $floor['quaterly_holding_tax'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["quaterly_holding_tax"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_quaterly_holding_tax]" id="old_floor_<?=$key?>_physical_quaterly_holding_tax" class="form-control" /></td>
                                                <td><input type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_quaterly_holding_tax" /></td>
                                            </tr>
                                            <tr>
                                                <td class="labelHeading">Quarterly RWH Tax</td>
                                                <td><input type="hidden" name="floors[<?= $key; ?>][rwh_tax]" id="old_floor_<?=$key?>_rwh_tax" value="<?= $floor['rwh_tax'] ?? ""; ?>" />
                                                    <div class=""><?= $floor["rwh_tax"] ?></div>
                                                </td>
                                                <td><input type="text" name="floors[<?= $key; ?>][physical_rwh_tax]" id="old_floor_<?=$key?>_physical_rwh_tax" class="form-control" /></td>
                                                <td><input type="text" class="form-control diff" id="old_floor_<?=$key?>_diff_rwh_tax" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="panel panel-bordered panel-dark" id="ExteraFloor">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>Do You Want To Add Floor</b> &nbsp;&nbsp;
                                    <input type="checkbox" name="isExtraFloorAdd" id="isExtraFloorAdd" onClick="addExtraFloors()">
                                </h3>
                            </div>
                            <div id="showflrr" style="display:none;">
                                <div class="panel-body">
                                    <div class="panel panel-bordered">
                                        <div class="panel-heading" style="background:#1b8388f7; margin-bottom: 10px;">
                                            <h3 class="panel-title"><b> Floor Details </b></h3>
                                        </div>
                                        <div class="panel panel-bordered panel-dark container-fluid" id="new_floor_0">
                                            <input type="hidden" name="newFloors[0][new_floor]" value="0">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Floor No
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select name="newFloors[0][floor_mstr_id]" id="floor_id1" class="form-control variationTaxFactor">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($holding["master_data"]["floorTypeList"] as  $item) {
                                                            ?>
                                                                <option value="<?= $item["id"]; ?>"><?= $item["floor_name"]; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Use Type
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select name="newFloors[0][usage_type_mstr_id]" id="use_type_id1" class="form-control variationTaxFactor">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($holding["master_data"]["usageTypeList"] as $item) {
                                                            ?>
                                                                <option value="<?= $item["id"]; ?>"><?= $item["usage_type"]; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Occupancy Type
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select name="newFloors[0][occupancy_type_mstr_id]" id="occupancy_type_id1" class="form-control variationTaxFactor">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($holding["master_data"]["occupancyTypeList"] as $valoccu) {
                                                            ?>
                                                                <option value="<?php echo $valoccu["id"]; ?>"><?php echo $valoccu["occupancy_name"]; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Construction Type
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <select name="newFloors[0][const_type_mstr_id]" id="construction_type_id1" class="form-control variationTaxFactor">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($holding["master_data"]["constructionTypeList"] as  $valcons) {
                                                            ?>
                                                                <option value="<?php echo $valcons["id"]; ?>"><?php echo $valcons["construction_type"]; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Built Up Area (in Sq. Ft)
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="number" name="newFloors[0][builtup_area]" value="" id="builtup_area1" class="form-control variationTaxFactor">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        Date From
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="month" name="newFloors[0][date_from]" id="occ_mm1" max="<?= date("Y-m"); ?>" class="form-control variationTaxFactor" />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">Add</a></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div id="extra_floor_div"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="box">Left Image</div>
                            <input type="file" name="leftImage" id="fileInput_leftImage" class="box" multiple accept="image/*" />
                            <input type="text" readonly name="latitude_leftImage" id="latitude_leftImage" class="box" required />
                            <input type="text" readonly name="longitude_leftImage" id="longitude_leftImage" class="box" required />
                        </div>
                        <div class="previewArea" id="previewArea_leftImage"></div>

                        <div class="row">
                            <div class="box">Right Image</div>
                            <input type="file" name="rightImage" id="fileInput_rightImage" class="box" multiple accept="image/*" required />
                            <input type="text" readonly name="latitude_rightImage" id="latitude_rightImage" class="box" required />
                            <input type="text" readonly name="longitude_rightImage" id="longitude_rightImage" class="box" required />
                        </div>
                        <div class="previewArea" id="previewArea_rightImage"></div>

                        <div class="row">
                            <div class="box">Front Image</div>
                            <input type="file" name="frontImage" id="fileInput_frontImage" class="box" multiple accept="image/*" required />
                            <input type="text" readonly name="latitude_frontImage" id="latitude_frontImage" class="box" required />
                            <input type="text" readonly name="longitude_frontImage" id="longitude_frontImage" class="box" required />
                        </div>
                        <div class="previewArea" id="previewArea_frontImage"></div>

                        <div class="row harvestingData">
                            <div class="box">RWH Image</div>
                            <input type="file" name="rwhImage" id="fileInput_rwhImage" class="box" multiple accept="image/*" required />
                            <input type="text" readonly name="latitude_rwhImage" id="latitude_rwhImage" class="box" required />
                            <input type="text" readonly name="longitude_rwhImage" id="longitude_rwhImage" class="box" required />
                        </div>
                        <div class="previewArea" id="previewArea_rwhImage"></div>
                        <div class="AdditionDoc">
                            
                        </div>
                        <div class="row">
                            <button type="button" class="btn btn-info text-center" onclick="addAdditionalDoc()" >Add Additional Doc</button>
                        </div>
                        
                        

                        <div class="row">
                            <label class="col-md-1">ULB Emp Name</label>
                            <div class="col-md-3">
                                <select name="ulb_emp_id" class="form-control select2" required>
                                    <option value="">select</option>
                                    <?php
                                    foreach ($ulbEmpList as $utc) {
                                    ?>
                                        <option value="<?= $utc['id'] ?>"><?= trim($utc['emp_name'] . ' ' . trim($utc['middle_name'] . ' ' . $utc['last_name'])) ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label for="mobileNo" class="col-md-1">Mobile No.</label>
                            <div class="col-md-3">
                                <input type='tel' minlength='10' maxlength='10' class="form-control" name='ulb_emp_mobile_no' required />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-1" for="">TC Name</label>
                            <div class="col-md-3">
                                <select name="agency_emp_id" class="form-control select2" required>
                                    <option value="">select</option>
                                    <?php
                                    foreach ($agencyEmpList as $utc) {
                                    ?>
                                        <option value="<?= $utc['id'] ?>"><?= trim($utc['emp_name'] . ' ' . trim($utc['middle_name'] . ' ' . $utc['last_name'])) ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label for="mobileNo" class="col-md-1">Mobile No.</label>
                            <div class="col-md-3">
                                <input type='tel' minlength='10' maxlength='10' class="form-control" name='agency_emp_mobile_no' required />
                            </div>

                        </div>
                        <div class="additionalUser">

                        </div>
                        <div class="row">
                            <button type="button" class="btn btn-info text-center" onclick="addAdditionalUser()" >Add Emp</button>
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-success text-center" name="submit_statement">Submit</button>
                        </div>

                    </div>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
$floorOptions = '';
if (!empty($holding['master_data']['floorTypeList']) && is_array($holding['master_data']['floorTypeList'])) {
    $floorOptions = implode('', array_map(fn($f) => '<option value="' . $f["id"] . '">' . $f["floor_name"] . '</option>', $holding["master_data"]["floorTypeList"]));
}

$useTypeOptions = '';
if (!empty($holding['master_data']['usageTypeList']) && is_array($holding['master_data']['usageTypeList'])) {
    $useTypeOptions = implode('', array_map(fn($u) => '<option value="' . $u["id"] . '">' . $u["usage_type"] . '</option>', $holding["master_data"]["usageTypeList"]));
}

$occupancyOptions = '';
if (!empty($holding['master_data']['occupancyTypeList']) && is_array($holding['master_data']['occupancyTypeList'])) {
    $occupancyOptions = implode('', array_map(fn($o) => '<option value="' . $o["id"] . '">' . $o["occupancy_name"] . '</option>', $holding["master_data"]["occupancyTypeList"]));
}

$constructionOptions = '';
if (!empty($holding['master_data']['constructionTypeList']) && is_array($holding['master_data']['constructionTypeList'])) {
    $constructionOptions = implode('', array_map(fn($c) => '<option value="' . $c["id"] . '">' . $c["construction_type"] . '</option>', $holding["master_data"]["constructionTypeList"]));
}
?>




<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url("/public/assets/js/exif.js"); ?>"></script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
<script src="<?= base_url(); ?>/public/assets/plugins/select2/js/select2.min.js"></script>

<script>
    <?php 
        if($message=flashToast('message'))
        {
            echo "modelInfo('".$message."');";
        }
        if($message=flashToast('error'))
        {
            echo "modelInfo('".$message."');";
        }
    ?>
    
    function getNewWardList(elem) {
        const old_ward_mstr_id = $(elem).val();
        if (old_ward_mstr_id !== "") {
            try {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('CitizenSaf/getNewWardDtlByOldWard'); ?>",
                    dataType: "json",
                    data: {
                        old_ward_mstr_id
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success: function(data) {
                        if (data.response === true) {
                            $("#physical_new_ward_mstr_id").empty().html(data.data);
                        }
                        $("#loadingDiv").hide();
                    },
                    error: function() {
                        $("#loadingDiv").hide();
                    }
                });
            } catch (err) {
                alert(err.message);
            }
        }
    }

    function addExtraFloors() {
        var check = document.getElementById("isExtraFloorAdd");
        // alert(check.checked);
        if (check.checked == true) {
            document.getElementById("showflrr").style.display = "block";
            $("#floor_id1").attr('required', '');
            $("#use_type_id1").attr('required', '');
            $("#occupancy_type_id1").attr('required', '');
            $("#construction_type_id1").attr('required', '');
            $("#builtup_area1").attr('required', '');
            $("#occ_mm1").attr('required', '');
        } else {
            document.getElementById("showflrr").style.display = "none";
            $("#floor_id1").removeAttr('required', '');
            $("#use_type_id1").removeAttr('required', '');
            $("#occupancy_type_id1").removeAttr('required', '');
            $("#construction_type_id1").removeAttr('required', '');
            $("#builtup_area1").removeAttr('required', '');
            $("#occ_mm1").removeAttr('required', '');
        }
    }

    let occ = 0;

    function AddOccupancy() {
        
        ++occ;

        const str = `
        <div class="panel panel-bordered panel-dark container-fluid mt-3" id="floorPanel${occ}">
            <div class="panel-body" id="new_floor_${occ}">
                <input type="hidden" name="newFloors[${occ}][new_floor]" value="${occ}">
                <div class="row mb-2">
                    <div class="col-sm-12"><strong>Floor No</strong></div>
                    <div class="col-sm-12">
                        <select name="newFloors[${occ}][floor_mstr_id]" id="floor_id${occ}" class="form-control variationTaxFactor" onchange="calculateNewTax()" required>
                            <option value="">Select</option>
                            <?= $floorOptions ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-12"><strong>Use Type</strong></div>
                    <div class="col-sm-12">
                        <select name="newFloors[${occ}][usage_type_mstr_id]"  id="use_type_id${occ}" class="form-control variationTaxFactor" onchange="calculateNewTax()" required>
                            <option value="">Select</option>
                            <?= $useTypeOptions ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-12"><strong>Occupancy Type</strong></div>
                    <div class="col-sm-12">
                        <select name="newFloors[${occ}][occupancy_type_mstr_id]"  id="occupancy_type_id${occ}" class="form-control variationTaxFactor" onchange="calculateNewTax()" required>
                            <option value="">Select</option>
                            <?= $occupancyOptions ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-12"><strong>Construction Type</strong></div>
                    <div class="col-sm-12">
                        <select name="newFloors[${occ}][const_type_mstr_id]"  id="construction_type_id${occ}" class="form-control variationTaxFactor" onchange="calculateNewTax()" required>
                            <option value="">Select</option>
                            <?= $constructionOptions ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-12"><strong>Built Up Area (in Sq. Ft)</strong></div>
                    <div class="col-sm-12">
                        <input type="tel" name="newFloors[${occ}][builtup_area]"  id="builtup_area${occ}" class="form-control variationTaxFactor" onchange="calculateNewTax()" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12"><strong>Date From</strong></div>
                    <div class="col-sm-12">
                        <input type="month" name="newFloors[${occ}][date_from]" id="occ_mm${occ}" max="<?= date('Y-m'); ?>" class="form-control variationTaxFactor" onchange="calculateNewTax()" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-end">
                        <button type="button" class="btn btn-danger btn-sm" onclick="RemoveOccupancy(${occ})">Remove</button>
                    </div>
                </div>
            </div>
        </div>`;

        $('#extra_floor_div').append(str);
        $('#countarea').val(occ);
    }

    function RemoveOccupancy(elementId) {
        $("#floorPanel" + elementId).remove();
        calculateNewTax();
    }

    function shoHideExtraData(elem, forEl = '') {
        const val = $(elem).val();

        // You can define what you consider "truthy" here
        const shouldShow = val === 'true' || val === '1' || val === 'yes'; // Customize this as needed

        $("." + forEl).each(function(_, element) {
            if (shouldShow) {
                $(element).show();
                $(element).find("select:not(.diff), input:not(.diff)").attr("required", true);
            } else {
                $(element).hide();
                $(element).find("select:not(.diff), input:not(.diff)").attr("required", false);
            }
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="file"][id^="fileInput_"]').forEach(input => {
            input.addEventListener('change', function() {
                const key = this.id.split('_')[1];
                const previewArea = document.getElementById('previewArea_' + key);
                const latEl = document.getElementById("latitude_" + key);
                const longEl = document.getElementById("longitude_" + key);

                previewArea.innerHTML = '';
                if (latEl) latEl.value = '';
                if (longEl) longEl.value = '';

                Array.from(this.files).forEach(file => {
                    const fileType = file.type;
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        if (fileType.startsWith('image/')) {
                            const tempImg = new Image();
                            tempImg.src = e.target.result;

                            tempImg.onload = function() {
                                EXIF.getData(tempImg, function() {
                                    const lat = EXIF.getTag(this, "GPSLatitude");
                                    const latRef = EXIF.getTag(this, "GPSLatitudeRef");
                                    const long = EXIF.getTag(this, "GPSLongitude");
                                    const longRef = EXIF.getTag(this, "GPSLongitudeRef");

                                    if (lat && long && latRef && longRef) {
                                        const decimalLat = convertDMSToDD(lat, latRef);
                                        const decimalLong = convertDMSToDD(long, longRef);
                                        if (latEl) latEl.value = decimalLat;
                                        if (longEl) longEl.value = decimalLong;
                                    } else {
                                        getCurrentLocation(latEl, longEl);
                                    }
                                });
                            };

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'preview';
                            previewArea.appendChild(img);

                        } else if (fileType.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.src = e.target.result;
                            video.controls = true;
                            video.className = 'preview';
                            previewArea.appendChild(video);
                            getCurrentLocation(latEl, longEl);

                        } else {
                            const doc = document.createElement('p');
                            doc.textContent = `📄 ${file.name}`;
                            previewArea.appendChild(doc);
                            getCurrentLocation(latEl, longEl);
                        }
                    };

                    reader.readAsDataURL(file);
                });
            });
        });

        function convertDMSToDD(dms, ref) {
            const degrees = dms[0];
            const minutes = dms[1];
            const seconds = dms[2];
            let dd = degrees + minutes / 60 + seconds / 3600;
            if (ref === "S" || ref === "W") {
                dd = dd * -1;
            }
            return dd.toFixed(6);
        }

        function getCurrentLocation(latEl, longEl) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        if (latEl) latEl.value = position.coords.latitude.toFixed(6);
                        if (longEl) longEl.value = position.coords.longitude.toFixed(6);
                    },
                    error => {
                        if (latEl) latEl.value = "Location not available";
                        if (longEl) longEl.value = "Location not available";
                    }
                );
            } else {
                if (latEl) latEl.value = "Geolocation not supported";
                if (longEl) longEl.value = "Geolocation not supported";
            }
        }
    });

    $(document).ready(function() {
        $(".select2").select2();
        $("#FORMNAME1").validate({
            rules: {
                ward_mstr_id: {
                    required: true,
                },
                area_of_plot: {
                    required: true,
                    number: true,
                },
            },
            messages: {

            },
        });
        
        $(".variationTaxFactor").on("change", function (event) {
            calculateNewTax();
        });

    });

    function calculateNewTax(){
        const formData = $("#FORMNAME1").serialize(); // Assuming .formData() is a custom jQuery plugin

            $.ajax({
                url: "<?= base_url('/CitizenSaf/TaxCalculate') ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function () {
                    // Optional: Show loader instead of alert
                    console.log("Sending data...");
                },
                success: function (response) {
                    // alert("Success: Tax calculated.");
                    console.log(response); // Useful for debugging
                    console.log("status:",response?.status);
                    if(response?.status){
                        const floors = response?.data?.floors
                        floors?.forEach((item) => {
                                let key = "";
                                let intIndex = -1;

                                if (parseInt(item?.old_floor) > -1) {
                                    key = "old_floor";
                                    intIndex = item.old_floor;
                                } else if (parseInt(item?.new_floor) > -1) {
                                    key = "new_floor";
                                    intIndex = item.new_floor;
                                }

                                if (key && intIndex > -1) {
                                    const taxFields = [
                                        'tax_percent', 'cv_rate', 'occupancy_rate',
                                        'calculation_factor', 'matrix_factor_rate',
                                        'holding_tax', 'quaterly_holding_tax', 'rwh_tax'
                                    ];

                                    taxFields.forEach((tax) => {
                                        console.log("item=",item);
                                        console.log(`\n ${tax}=`,item?.[tax]);
                                        const newVal = item?.[tax]; // correct tax value
                                        const inputId = `#${key}_${intIndex}_${tax}`;
                                        const physicalId = `#${key}_${intIndex}_physical_${tax}`;
                                        const diffId = `#${key}_${intIndex}_diff_${tax}`;

                                        const oldVal = parseFloat($(inputId).val()) || 0;
                                        const physicalVal = parseFloat(newVal) || 0;
                                        const diff = (physicalVal - oldVal).toFixed(2);

                                        $(physicalId).val(physicalVal);
                                        $(diffId).val(diff);

                                        console.log(`Field: ${tax}, Old: ${oldVal}, New: ${physicalVal}, Diff: ${diff}`);
                                    });

                                }
                            });
                    }
                },
                error: function (xhr, status, error) {
                    // alert("Something went wrong: " + error);
                    console.error("Error response:", xhr.responseText);
                }
            });
    }

    let userList = [];
    let counterAdd = 0;
    let counterDoc=0;

    function addAdditionalUser() {
        counterAdd++;

        // If userList is empty, fetch it first and then add the row
        if (userList.length === 0) {
            $.ajax({
                url: "<?=base_url("jsk/getUserListForAdditional")?>",
                type: "post",
                dataType: "json",
                success: function (response) {
                    if (response?.status) {
                        userList = response.data;
                        addUserRow(); // Now add the row
                    }
                }
            });
        } else {
            addUserRow(); // Already fetched, just add row
        }
    }

    function addUserRow() {
        let options = '<option value="">Select</option>';
        options += userList.map(item => {
            const fullName = `${item.emp_name?.trim() || ''} ${item.middle_name?.trim() || ''} ${item.last_name?.trim() || ''}`.trim();
            return `<option value="${item.id}">${fullName}</option>`;
        }).join('');

        const empDiv = `
            <div class="row additionalEmp" id="emp_${counterAdd}">
                <div class="col-md-3">
                    <select name="addition[${counterAdd}][emp][userId]" class="form-control select2" required>
                        ${options}
                    </select>
                </div>                    
                <label for="mobileNo" class="col-md-1">Mobile No.</label>
                <div class="col-md-3">
                    <input type='tel' minlength='10' maxlength='10' class="form-control" name="addition[${counterAdd}][emp][mobileNo]" required />
                </div>
                <div class="col-md-1">
                    <span class='btn btn-sm btn-info' onclick='removeAdditionEmp("emp_${counterAdd}")'>X</span>
                </div>
            </div>
        `;

        $(".additionalUser").append(empDiv);
    }

    function removeAdditionEmp(id) {
        $("#" + id).remove();
    }

    function addAdditionalDoc(){
        ++counterDoc;
        const docDiv = `
            <div class="row" id="doc_${counterDoc}">
                <div class="box">Addition Doc</div>
                <input type="file" name="additionalDoc[${counterDoc}][file]" class="box" multiple accept="image/*,.pdf" required />
                <input type="text" name="additionalDoc[${counterDoc}][name]" placeholder="Document Name" class="box" required />
                <div class="col-md-1">
                    <span class='btn btn-sm btn-info' onclick="removeAdditionEmp('doc_${counterDoc}')">X</span>
                </div>
            </div>
        `;
        $(".AdditionDoc").append(docDiv);
    }


    function calculateDiff(originalId,physicalId,diffId){
        const physicalVal = parseFloat($("#"+physicalId).val()) || 0;
        const originalVal = parseFloat($("#" + originalId).val()) || 0;
        const diff = (physicalVal - originalVal).toFixed(2);
        $("#"+diffId).val(diff);
        console.log("physicalVal:",physicalVal);
        console.log("originalVal:",originalVal);
    }

    $(document).ready(function(){
        $(".diff").attr("readonly",true);
    });


</script>


<script>
    function ValidateRadio() {
        const property_type_id = $("#property_type_id").val();
        const list = [];
        const remarks = $("#remarks").val().trim();
        if (remarks === '') list.push("Enter remarks");

        const checkRadioGroup = (name, label) => {
            const radios = document.getElementsByName(name);
            if (radios.length >= 2 && !radios[0].checked && !radios[1].checked) {
                list.push(label);
            }
        };

        checkRadioGroup('rdo_ward_no', "Ward No");
        checkRadioGroup('rdo_new_ward_no', "New Ward No");
        checkRadioGroup('rdo_property_type', "Property Type");
        checkRadioGroup('rdo_area_of_plot', "Verify Area of plot");
        checkRadioGroup('rdo_street_type', "Verify Road Type");

        const has_hording = $("#has_hording").val();
        if (has_hording == 1) {
            if (!$("#hording_installation_date").val()) list.push("Enter Hording Installation Date");
            if (!$("#total_hording_area").val()) list.push("Enter Hording Area");
        }

        const has_mobile_tower = $("#has_mobile_tower").val();
        if (has_mobile_tower == 1) {
            if (!$("#tower_installation_date").val()) list.push("Enter Mobile Tower Installation Date");
            if (!$("#total_tower_area").val()) list.push("Enter Mobile Tower Occupied Area");
        }

        const rdo_water_harvesting = document.getElementsByName('rdo_water_harvesting');
        if (rdo_water_harvesting.length && !rdo_water_harvesting[0].checked && !rdo_water_harvesting[1].checked && property_type_id != 4) {
            list.push("Verify Water Harvesting");
        }

        const is_petrol_pump = $("#is_petrol_pump").val();
        if (is_petrol_pump == 1) {
            if (!$("#petrol_pump_completion_date").val()) list.push("Enter Petrol Pump Completion Date");
            if (!$("#under_ground_area").val()) list.push("Enter Petrol Pump Underground Area");
        }

        const per = $("#percentage_of_property").val();
        if (per && !isNaN(per)) {
            const val = parseFloat(per);
            if (val < 0 || val > 100) {
                list.push("Invalid Percentage of Property Transferred");
            }
        }

        if (list.length > 0) {
            let msg = 'Please fill the following:\n';
            list.forEach((item, i) => {
                msg += `${i + 1}. ${item}\n`;
            });
            alert(msg);
            return false;
        }

        return true;
    }

    function OperateDropDown(radio, control, hidden) {
        const rdo = $("#" + radio.trim());
        const ctrl = $("#" + control.trim());
        const hid_val = $("#" + hidden.trim()).val();

        if (rdo.val() == "1") {
            ctrl.val(hid_val).prop("disabled", true);
        } else {
            ctrl.prop("selectedIndex", 0).prop("disabled", false);
        }

        if (control.trim() === 'property_type_id') {
            remove_ExteraFloor(ctrl.val());
        }
    }

    function OperateTexBox(radio, control, hidden) {
        const rdo = $("#" + radio.trim());
        const ctrl = $("#" + control.trim());
        const hid_val = $("#" + hidden.trim()).val();

        if (rdo.val() == "1") {
            ctrl.val(hid_val).prop("readonly", true);
        } else {
            ctrl.val("").prop("readonly", false);
        }
    }

    function HideUnhide(sourceId, targetClass, targetDate, targetArea) {
        const val = $("#" + sourceId).val();
        const show = val == "1";
        const targetElements = $("." + targetClass);
        targetElements.toggle(show);
        if (show) {
            $("#" + targetDate).val($("#assess_" + targetDate).val());
            $("#" + targetArea).val($("#assess_" + targetArea).val());
        }
    }

    function checkdate(type, cnt) {
        try {
            const currYear = <?= date('Y') ?>;
            const currMonth = <?= date('m') ?>;
            const mm = $("#occ_mm" + cnt).val();
            const yy = $("#occ_yyyy" + cnt).val();
            const inputDate = parseInt(yy + mm, 10);
            const currentDate = parseInt(currYear + currMonth, 10);

            if (type === 'M') {
                if (isNaN(mm)) {
                    alert("Please enter a valid month.");
                    $("#occ_mm" + cnt).val("").focus();
                } else if (mm < 1 || mm > 12) {
                    alert("Please enter a valid month (1-12).");
                    $("#occ_mm" + cnt).val("").focus();
                } else if (yy && inputDate > currentDate) {
                    alert("Date of completion must be before or equal to the current date.");
                    $("#occ_mm" + cnt).val("");
                    $("#occ_yyyy" + cnt).val("");
                }
            }

            if (type === 'Y') {
                if (isNaN(yy)) {
                    alert("Please enter a valid year.");
                    $("#occ_yyyy" + cnt).val("").focus();
                } else if (yy < 1960) {
                    alert("Year must be after 1960.");
                    $("#occ_yyyy" + cnt).val("").focus();
                } else if (yy > currYear) {
                    alert("Year must not be in the future.");
                    $("#occ_yyyy" + cnt).val("").focus();
                } else if (mm && inputDate > currentDate) {
                    alert("Date of completion must be before or equal to the current date.");
                    $("#occ_mm" + cnt).val("");
                    $("#occ_yyyy" + cnt).val("");
                }
            }
        } catch (err) {
            alert(err.message);
        }
    }

    // function checkEveryRadioBtnSelectedOrNot() {
    //     const property_type_id = $("#property_type_id").val();
    //     const totalRadios = $("input:radio").length;
    //     const totalChecked = $("input:radio:checked").length;

    //     if (totalRadios / 2 !== totalChecked && <?= $prop_type_mstr_id ?> == property_type_id && property_type_id != 4) {
    //         alert("Please answer all questions.");
    //         return false;
    //     }
    //     return true;
    // }

    function remove_new_ward_correct() {
        const hnew_ward_id = $("#hid_new_ward_id").val();
        let new_ward_id = $("#new_ward_id").val();
        new_ward_id = new_ward_id || hnew_ward_id;
        const values = $("#new_ward_id option").map(function() {
            return $(this).val().trim();
        }).get();

        if (!values.includes(new_ward_id)) {
            $('#rdo_new_ward_no1').hide();
        } else {
            $('#rdo_new_ward_no1').show();
        }
    }

    function append_new_ward(value) {
        const old_ward_mstr_id = $("#ward_id").val();
        const old_ward_id_correct = $('#rdo_ward_no2').val();
        const hid_new_ward_id = $("#hid_new_ward_id").val();

        if (old_ward_id_correct == 0 && old_ward_mstr_id !== "") {
            $.ajax({
                type: "POST",
                url: "<?= base_url('CitizenSaf/getNewWardDtlByOldWard'); ?>",
                dataType: "json",
                data: {
                    old_ward_mstr_id
                },
                beforeSend: () => $("#loadingDiv").show(),
                success: function(data) {
                    if (data.response === true) {
                        $('#new_ward_id').empty();
                        const dd = data.data.split("option>");
                        if (dd.length === 2) {
                            $("#new_ward_id").html("<option value=''>==SELECT==</option>" + data.data);
                        } else {
                            $("#new_ward_id").html(data.data);
                        }
                        remove_new_ward_correct();
                    }
                    $("#loadingDiv").hide();
                },
                error: function() {
                    $("#loadingDiv").hide();
                }
            });
        }
    }

    function remove_ExteraFloor(prop_type_id) {
        const isRes = prop_type_id == 4;
        if (!isRes) {
            $('#ExteraFloor, #oldFloors').show();
            $(".oldFloors").find("select, textarea").prop('required', true);
            $(".oldFloors").find("input:radio").prop('disabled', false);
            $(".oldFloors").css("display", "inline-block");
            $("#water_harvesting").prop('required', false);
            $(".lanceOccupiedDate").hide();
        } else {
            $(".oldFloors").find("select, textarea").prop('required', false);
            $(".oldFloors").find("input:radio").prop('disabled', true);
            $(".oldFloors").css("display", "none");
            $("#chkfloor").prop("checked", false);
            $('#ExteraFloor').hide();
            $(".lanceOccupiedDate").show();
            addExtraFloors();
        }
        if (prop_type_id != 4 && <?= $holding["prop_type_mstr_id"] ?> == 4) {
            $("#isExtraFloorAdd").prop('checked', true);
            addExtraFloors();
        }
        if (prop_type_id == 4 && <?= $holding["prop_type_mstr_id"] ?> == 4) {
            $(".extraFactor").each(function(_, element) {
                $(element).hide();
                $(element).find("select, input").attr("required", false);
            });
        }

        if (prop_type_id != 4 && <?= $holding["prop_type_mstr_id"] ?> == 4) {
            $(".pt_details").attr('style', "display:inlineblock");
            $(".extraFactor").each(function(_, element) {
                $(element).show();
                $(element).find("select:not(.diff), input:not(.diff)").attr("required", true);
            });

        }
    }
</script>