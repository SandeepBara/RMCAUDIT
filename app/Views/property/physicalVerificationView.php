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
            <li class="active">Physical Verification Details</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->

    </div>

    <!-- ======= Cta Section ======= -->

    <div id="page-content">
        <div class="panel panel-bordered panel-dark">            
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
                            <div class="box"> Date : <?=$verification_date??"N/A";?></div>
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
                                    <td>
                                        <div><?= $ward_no??"N/A";?></div>
                                    </td>
                                    <td>
                                        <div><?= $physical_ward_no??"N/A";?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">New Ward No.</td>
                                    <td>
                                        <div><?= $new_ward_no??"N/A"; ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_new_ward_no??"N/A";?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Zone</td>
                                    <td>
                                        <div class=""><?= $zone??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_zone??"N/A";?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Property Type</td>
                                    <td>
                                        <div><?= $property_type??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_property_type??"N/A";?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Area of Plot (in decimal)</td>
                                    <td>
                                        <div class=""><?= $area_of_plot??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_area_of_plot??"N/A";?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Road Type</td>
                                    <td>
                                        <div class=""><?= $road_type??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_road_type??"N/A";?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor">
                                    <td class="labelHeading">Hoarding Board(s)</td>
                                    <td>
                                        <div class=""><?= $is_hoarding_board ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_is_hoarding_board ? "Yes" : "No";?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor holdingBodeData">
                                    <td class="labelHeading">Installation Date of Hoarding Board(s) :</td>
                                    <td><?= $hoarding_installation_date??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_hoarding_installation_date??"N/A" ?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor holdingBodeData">
                                    <td class="labelHeading">Area of Hoarding Board(s) :</td>
                                    <td>
                                        <div class=""><?= $hoarding_area??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <?= $physical_hoarding_area??"N/A" ?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="labelHeading">Mobile Tower</td>
                                    <td>
                                        <div class=""><?= $is_mobile_tower ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_is_mobile_tower ? "Yes" : "No" ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor mobileTower">
                                    <td class="labelHeading">Installation Date of Tower :</td>
                                    <td>
                                        <div class=""><?= $tower_installation_date??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_tower_installation_date ?? "N/A"  ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor mobileTower">
                                    <td class="labelHeading">Area of Tower :</td>
                                    <td>
                                        <div class=""><?=$tower_area??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?=$physical_tower_area??"N/A" ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor">
                                    <td class="labelHeading">Petrol Pump</td>
                                    <td>
                                        <div class=""><?= $is_petrol_pump ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_is_petrol_pump ? "Yes" : "No" ?></div>                                        
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor petrolPump">
                                    <td class="labelHeading">Completion Date of Petrol Pump :</td>
                                    <td>
                                        <div class=""><?= $petrol_pump_completion_date??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_petrol_pump_completion_date??"N/A" ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor petrolPump">
                                    <td class="labelHeading">Under Ground Area :</td>
                                    <td>
                                        <div class=""><?= $under_ground_area??"N/A" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_under_ground_area??"N/A" ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="extraFactor">
                                    <td class="labelHeading">Rainwater Harvesting Provision</td>
                                    <td>
                                        <div class=""><?= $is_water_harvesting ? "Yes" : "No" ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_is_water_harvesting ? "Yes" : "No" ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="lanceOccupiedDate" style="display:<?= ($prop_type_mstr_id == 4 || $physical_prop_type_mstr_id == 4 )? '' : 'none' ?>;">
                                    <td class="labelHeading">Land Occupied Date</td>
                                    <td>
                                        <div class=""><?= $occupation_date??"N/A"; ?></div>
                                    </td>
                                    <td>
                                        <div class=""><?= $physical_occupation_date??"N/A"; ?></div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <?php
                        if (isset($oldFloor)) {

                            foreach ($oldFloor as $key => $floor) {
                                ?>
                                        <div class="panel panel-bordered panel-dark">
                                            <div class="panel-heading">
                                                <h5 class="panel-title"><?= $floor["floor_name"] ?? ""; ?></h5>
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
                                                        <td class="labelHeading">Use Type</td>
                                                        <td>
                                                            <div class=""><?= $floor["usage_type"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_usage_type"] ?></div>                                                            
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Occupancy Type</td>
                                                        <td>
                                                            <div class=""><?= $floor["occupancy_type"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_occupancy_type"] ?></div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Construction Type</td>
                                                        <td>
                                                            <div class=""><?= $floor["construction_type"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_construction_type"] ?></div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Area</td>
                                                        <td>
                                                            <div class=""><?= $floor["builtup_area"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_builtup_area"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_builtup_area"] - $floor["builtup_area"],2) ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="labelHeading">Date From</td>
                                                        <td>
                                                            <div class=""><?= $floor["date_from"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_date_from"] ?></div>
                                                        </td>
                                                        <td> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Date Upto</td>
                                                        <td>
                                                            <div class=""><?= $floor["date_upto"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_date_upto"] ?></div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">CV Rate</td>
                                                        <td>
                                                            <div class=""><?= $floor["cv_rate"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_cv_rate"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_cv_rate"] - $floor["cv_rate"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Occupancy Factor</td>
                                                        <td>
                                                            <div class=""><?= $floor["occupancy_rate"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_occupancy_rate"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_occupancy_rate"] - $floor["occupancy_rate"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Tax Percentage</td>
                                                        <td>
                                                            <div class=""><?= $floor["tax_percent"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_tax_percent"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_tax_percent"] - $floor["tax_percent"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Calculation Factor</td>
                                                        <td>
                                                            <div class=""><?= $floor["calculation_factor"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_calculation_factor"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_calculation_factor"] - $floor["calculation_factor"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Matrix Factor</td>
                                                        <td>
                                                            <div class=""><?= $floor["matrix_factor_rate"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_matrix_factor_rate"] ?></div>
                                                        <td><?= round($floor["physical_matrix_factor_rate"] - $floor["matrix_factor_rate"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Holding Tax</td>
                                                        <td>
                                                            <div class=""><?= $floor["holding_tax"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_holding_tax"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_holding_tax"] - $floor["holding_tax"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Quarterly Holding Tax</td>
                                                        <td>
                                                            <div class=""><?= $floor["quaterly_holding_tax"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_quaterly_holding_tax"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_quaterly_holding_tax"] - $floor["quaterly_holding_tax"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Quarterly RWH Tax</td>
                                                        <td>
                                                            <div class=""><?= $floor["rwh_tax"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_rwh_tax"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_rwh_tax"] - $floor["rwh_tax"],2) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                <?php
                            }
                        }
                        if (isset($newFloor)) {

                            foreach ($newFloor as $key => $floor) {
                                ?>
                                        <div class="panel panel-bordered panel-dark bg-mint">
                                            <div class="panel-heading">
                                                <h5 class="panel-title"><?= $floor["floor_name"] ?? ""; ?> (NEW Floor)</h5>
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
                                                        <td class="labelHeading">Use Type</td>
                                                        <td>
                                                            <div class=""><?= $floor["usage_type"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_usage_type"] ?></div>                                                            
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Occupancy Type</td>
                                                        <td>
                                                            <div class=""><?= $floor["occupancy_type"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_occupancy_type"] ?></div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Construction Type</td>
                                                        <td>
                                                            <div class=""><?= $floor["construction_type"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_construction_type"] ?></div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Area</td>
                                                        <td>
                                                            <div class=""><?= $floor["builtup_area"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_builtup_area"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_builtup_area"] - $floor["builtup_area"],2) ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="labelHeading">Date From</td>
                                                        <td>
                                                            <div class=""><?= $floor["date_from"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_date_from"] ?></div>
                                                        </td>
                                                        <td> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Date Upto</td>
                                                        <td>
                                                            <div class=""><?= $floor["date_upto"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_date_upto"] ?></div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">CV Rate</td>
                                                        <td>
                                                            <div class=""><?= $floor["cv_rate"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_cv_rate"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_cv_rate"] - $floor["cv_rate"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Occupancy Factor</td>
                                                        <td>
                                                            <div class=""><?= $floor["occupancy_rate"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_occupancy_rate"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_occupancy_rate"] - $floor["occupancy_rate"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Tax Percentage</td>
                                                        <td>
                                                            <div class=""><?= $floor["tax_percent"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_tax_percent"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_tax_percent"] - $floor["tax_percent"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Calculation Factor</td>
                                                        <td>
                                                            <div class=""><?= $floor["calculation_factor"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_calculation_factor"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_calculation_factor"] - $floor["calculation_factor"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Matrix Factor</td>
                                                        <td>
                                                            <div class=""><?= $floor["matrix_factor_rate"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_matrix_factor_rate"] ?></div>
                                                        <td><?= round($floor["physical_matrix_factor_rate"] - $floor["matrix_factor_rate"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Holding Tax</td>
                                                        <td>
                                                            <div class=""><?= $floor["holding_tax"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_holding_tax"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_holding_tax"] - $floor["holding_tax"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Quarterly Holding Tax</td>
                                                        <td>
                                                            <div class=""><?= $floor["quaterly_holding_tax"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_quaterly_holding_tax"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_quaterly_holding_tax"] - $floor["quaterly_holding_tax"],2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="labelHeading">Quarterly RWH Tax</td>
                                                        <td>
                                                            <div class=""><?= $floor["rwh_tax"] ?></div>
                                                        </td>
                                                        <td>
                                                            <div class=""><?= $floor["physical_rwh_tax"] ?></div>
                                                        </td>
                                                        <td><?= round($floor["physical_rwh_tax"] - $floor["rwh_tax"],2) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                <?php
                            }
                        }
                        ?>
                        
                        <div class="row">
                            <div class="box">Left Image</div> 
                            <div class="box"><?=$left_image_latitude?></div> 
                            <div class="box"><?=$left_image_longitude?></div>                            
                            <div class="previewArea" id="previewArea_leftImage"><img class="preview" src="<?=base_url("/getImageLink.php?path=".$left_image);?>"/></div>          
                        </div>
                        

                        <div class="row">
                            <div class="box">Right Image</div>
                            <div class="box"><?=$right_image_longitude?></div> 
                            <div class="box"><?=$right_image_longitude?></div> 
                            <div class="previewArea" id="previewArea_rightImage"><img class="preview" src="<?=base_url("/getImageLink.php?path=".$right_image);?>"/></div>
                        </div>

                        <div class="row">
                            <div class="box">Front Image</div>
                            <div class="box"><?=$front_image_longitude?></div> 
                            <div class="box"><?=$front_image_longitude?></div> 
                            <div class="previewArea" id="previewArea_rightImage"><img class="preview" src="<?=base_url("/getImageLink.php?path=".$front_image);?>"/></div>
                        </div>

                        <?php
                            if(isset($rwh_image) && $rwh_image){
                                ?>                                
                                    <div class="row harvestingData">
                                        <div class="box">RWH Image</div>
                                        <div class="box"><?=$rwh_image_longitude?></div> 
                                        <div class="box"><?=$rwh_image_longitude?></div> 
                                        <div class="previewArea" id="previewArea_rightImage"><img class="preview" src="<?=base_url("/getImageLink.php?path=".$rwh_image);?>"/></div>
                                    </div>
                                <?php
                            }
                        ?>

                        <div class="row">
                            <label class="col-md-1">ULB Emp Name</label>
                            <div class="col-md-3">
                                <?= trim($ulb_emp['emp_name'] . ' ' . trim($ulb_emp['middle_name'] . ' ' . $ulb_emp['last_name'])) ?>
                            </div>
                            <label for="mobileNo" class="col-md-1">Mobile No.</label>
                            <div class="col-md-3">
                                <?=$ulb_emp_mobile_no;?>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-1" for="">TC Name</label>
                            <div class="col-md-3">
                                <?= trim($agency_emp['emp_name'] . ' ' . trim($agency_emp['middle_name'] . ' ' . $agency_emp['last_name'])) ?>
                            </div>
                            <label for="mobileNo" class="col-md-1">Mobile No.</label>
                            <div class="col-md-3">
                                <?=$ulb_emp_mobile_no;?>
                            </div>

                        </div>
                    </div>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
</div>


<?= $this->include('layout_vertical/footer'); ?>
