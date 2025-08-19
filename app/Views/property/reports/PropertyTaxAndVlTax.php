<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->

        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Report</a></li>
            <li class="active">Property Tax & VL Tax</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Property Tax & VL Tax</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="myform" method="post">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label class="control-label" for="Fin Year"><b>Ward</b><span class="text-danger">*</span> </label>
                                            <select name="ward_id" id="ward_id" class="form-control">
                                                <option value="">All</option>
                                                <?php
                                                foreach ($wardList as $ward) {
                                                ?>
                                                    <option value="<?= $ward["id"]; ?>" <?php if (isset($ward_id) && $ward["id"] == $ward_id) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $ward["ward_no"]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="control-label" for="fyear"><b>Financial Year</b><span class="text-danger">*</span> </label>
                                            <select name="fyear" id="fyear" class="form-control">
                                                <?php
                                                $fy_list = fy_year_list();
                                                foreach ($fy_list as $val) {
                                                ?>
                                                    <option value="<?= $val ?>" <?= isset($fyear) && $fyear == $val ? "selected" : ""; ?>><?= $val ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="control-label">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                        </div>
                                        <?php if (isset($result)) {
                                        ?>
                                            <div class="col-md-2">
                                                <label class="control-label">&nbsp;</label>
                                                <a href="#" onClick="return fnExcelReport();" class="btn btn-primary btn-block">Export to Excel</a>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row" id="printableArea">
                            <div class="table-responsive" id="printableArea">
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th colspan="12">Financial Year</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2"></th>
                                            <th rowspan="3">Type</th>
                                            <th rowspan="2">No of HH</th>
                                            <th colspan="3">Arrear</th>
                                            <th colspan="3">Collection</th>
                                            <th colspan="3">Balance Due</th>
                                        </tr>
                                        <tr>
                                            
                                            <th>Arrear</th>
                                            <th>Current</th>
                                            <th>Total</th>

                                            <th>Arrear</th>
                                            <th>Current</th>
                                            <th>Total</th>

                                            <th>Arrear</th>
                                            <th>Current</th>
                                            <th>Total</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($result)):
                                        ?>
                                            <tr>
                                                <td colspan="12" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                        <?php else:
                                        ?>

                                            <tr>
                                                <th rowspan="9">Total</th>
                                                <td>100% Residential</td>
                                                <td><?= ($result["resident_hh"] ?? 0) + ($result["resident_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_resident_hh"] ?? 0) + ($result["outstanding_resident_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_resident_hh"] ?? 0) + ($result["current_demand_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_resident_hh"] ?? 0) + ($result["outstanding_resident_hh_not_collectable"] ?? 0)) + (($result["current_demand_resident_hh"] ?? 0) + ($result["current_demand_resident_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_resident_hh"] ?? 0) + ($result["arrear_collection_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_resident_hh"] ?? 0) + ($result["current_collection_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_resident_hh"] ?? 0) + ($result["arrear_collection_resident_hh_not_collectable"] ?? 0)) + (($result["current_collection_resident_hh"] ?? 0) + ($result["current_collection_resident_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_resident_hh"] ?? 0) + ($result["arrear_balance_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_resident_hh"] ?? 0) + ($result["current_balance_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_resident_hh"] ?? 0) + ($result["arrear_balance_resident_hh_not_collectable"] ?? 0)) + (($result["current_balance_resident_hh"] ?? 0) + ($result["current_balance_resident_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>
                                            <tr>
                                                <td>100% Non Residential</td>                                              
                                                <td><?= ($result["non_resident_hh"] ?? 0) + ($result["non_resident_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_non_resident_hh"] ?? 0) + ($result["outstanding_non_resident_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_non_resident_hh"] ?? 0) + ($result["current_demand_non_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_non_resident_hh"] ?? 0) + ($result["outstanding_non_resident_hh_not_collectable"] ?? 0)) + (($result["current_demand_non_resident_hh"] ?? 0) + ($result["current_demand_non_resident_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_non_resident_hh"] ?? 0) + ($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_non_resident_hh"] ?? 0) + ($result["current_collection_non_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_non_resident_hh"] ?? 0) + ($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0)) + (($result["current_collection_non_resident_hh"] ?? 0) + ($result["current_collection_non_resident_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_non_resident_hh"] ?? 0) + ($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_non_resident_hh"] ?? 0) + ($result["current_balance_non_resident_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_non_resident_hh"] ?? 0) + ($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0)) + (($result["current_balance_non_resident_hh"] ?? 0) + ($result["current_balance_non_resident_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>

                                            <tr>
                                                <td>Mix Holding</td>
                                                <td><?= ($result["mix_hh"] ?? 0) + ($result["mix_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_mix_hh"] ?? 0) + ($result["outstanding_mix_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_mix_hh"] ?? 0) + ($result["current_demand_mix_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_mix_hh"] ?? 0) + ($result["outstanding_mix_hh_not_collectable"] ?? 0)) + (($result["current_demand_mix_hh"] ?? 0) + ($result["current_demand_mix_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_mix_hh"] ?? 0) + ($result["arrear_collection_mix_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_mix_hh"] ?? 0) + ($result["current_collection_mix_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_mix_hh"] ?? 0) + ($result["arrear_collection_mix_hh_not_collectable"] ?? 0)) + (($result["current_collection_mix_hh"] ?? 0) + ($result["current_collection_mix_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_mix_hh"] ?? 0) + ($result["arrear_balance_mix_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_mix_hh"] ?? 0) + ($result["current_balance_mix_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_mix_hh"] ?? 0) + ($result["arrear_balance_mix_hh_not_collectable"] ?? 0)) + (($result["current_balance_mix_hh"] ?? 0) + ($result["current_balance_mix_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>
                                            <tr>
                                                <td>State Govt Building</td>
                                                <td><?= ($result["state_gov_hh"] ?? 0) + ($result["state_gov_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_state_gov_hh"] ?? 0) + ($result["outstanding_state_gov_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_state_gov_hh"] ?? 0) + ($result["current_demand_state_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_state_gov_hh"] ?? 0) + ($result["outstanding_state_gov_hh_not_collectable"] ?? 0)) + (($result["current_demand_state_gov_hh"] ?? 0) + ($result["current_demand_state_gov_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_state_gov_hh"] ?? 0) + ($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_state_gov_hh"] ?? 0) + ($result["current_collection_state_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_state_gov_hh"] ?? 0) + ($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0)) + (($result["current_collection_state_gov_hh"] ?? 0) + ($result["current_collection_state_gov_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_state_gov_hh"] ?? 0) + ($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_state_gov_hh"] ?? 0) + ($result["current_balance_state_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_state_gov_hh"] ?? 0) + ($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0)) + (($result["current_balance_state_gov_hh"] ?? 0) + ($result["current_balance_state_gov_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>

                                            <tr>
                                                <td>Central Govt Building</td>
                                                <td><?= ($result["central_gov_hh"] ?? 0) + ($result["central_gov_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_central_gov_hh"] ?? 0) + ($result["outstanding_central_gov_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_central_gov_hh"] ?? 0) + ($result["current_demand_central_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_central_gov_hh"] ?? 0) + ($result["outstanding_central_gov_hh_not_collectable"] ?? 0)) + (($result["current_demand_central_gov_hh"] ?? 0) + ($result["current_demand_central_gov_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_central_gov_hh"] ?? 0) + ($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_central_gov_hh"] ?? 0) + ($result["current_collection_central_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_central_gov_hh"] ?? 0) + ($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0)) + (($result["current_collection_central_gov_hh"] ?? 0) + ($result["current_collection_central_gov_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_central_gov_hh"] ?? 0) + ($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_central_gov_hh"] ?? 0) + ($result["current_balance_central_gov_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_central_gov_hh"] ?? 0) + ($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0)) + (($result["current_balance_central_gov_hh"] ?? 0) + ($result["current_balance_central_gov_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>

                                            <tr>
                                                <td>State PSU</td>
                                                <td><?= ($result["state_gov_psu_hh"] ?? 0) + ($result["state_gov_psu_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_state_gov_psu_hh"] ?? 0) + ($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_state_gov_psu_hh"] ?? 0) + ($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_state_gov_psu_hh"] ?? 0) + ($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0)) + (($result["current_demand_state_gov_psu_hh"] ?? 0) + ($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_state_gov_psu_hh"] ?? 0) + ($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_state_gov_psu_hh"] ?? 0) + ($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_state_gov_psu_hh"] ?? 0) + ($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0)) + (($result["current_collection_state_gov_psu_hh"] ?? 0) + ($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_state_gov_psu_hh"] ?? 0) + ($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_state_gov_psu_hh"] ?? 0) + ($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_state_gov_psu_hh"] ?? 0) + ($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0)) + (($result["current_balance_state_gov_psu_hh"] ?? 0) + ($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central PSU</td>                                               
                                                <td><?= ($result["central_gov_psu_hh"] ?? 0) + ($result["central_gov_psu_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_central_gov_psu_hh"] ?? 0) + ($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_central_gov_psu_hh"] ?? 0) + ($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_central_gov_psu_hh"] ?? 0) + ($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0)) + (($result["current_demand_central_gov_psu_hh"] ?? 0) + ($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_central_gov_psu_hh"] ?? 0) + ($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_central_gov_psu_hh"] ?? 0) + ($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_central_gov_psu_hh"] ?? 0) + ($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0)) + (($result["current_collection_central_gov_psu_hh"] ?? 0) + ($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_central_gov_psu_hh"] ?? 0) + ($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_central_gov_psu_hh"] ?? 0) + ($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_central_gov_psu_hh"] ?? 0) + ($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0)) + (($result["current_balance_central_gov_psu_hh"] ?? 0) + ($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Vacant Land</td>
                                                <td><?= ($result["vacant_hh"] ?? 0) + ($result["vacant_hh_not_collectable"] ?? 0); ?></td>
                                                <td><?= round(($result["outstanding_vacant_hh"] ?? 0) + ($result["outstanding_vacant_hh_not_collectable"] ?? 0)) ; ?></td>
                                                <td><?= round(($result["current_demand_vacant_hh"] ?? 0) + ($result["current_demand_vacant_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["outstanding_vacant_hh"] ?? 0) + ($result["outstanding_vacant_hh_not_collectable"] ?? 0)) + (($result["current_demand_vacant_hh"] ?? 0) + ($result["current_demand_vacant_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_collection_vacant_hh"] ?? 0) + ($result["arrear_collection_vacant_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_collection_vacant_hh"] ?? 0) + ($result["current_collection_vacant_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_collection_vacant_hh"] ?? 0) + ($result["arrear_collection_vacant_hh_not_collectable"] ?? 0)) + (($result["current_collection_vacant_hh"] ?? 0) + ($result["current_collection_vacant_hh_not_collectable"] ?? 0))); ?></td>

                                                <td><?= round(($result["arrear_balance_vacant_hh"] ?? 0) + ($result["arrear_balance_vacant_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round(($result["current_balance_vacant_hh"] ?? 0) + ($result["current_balance_vacant_hh_not_collectable"] ?? 0)); ?></td>
                                                <td><?= round((($result["arrear_balance_vacant_hh"] ?? 0) + ($result["arrear_balance_vacant_hh_not_collectable"] ?? 0)) + (($result["current_balance_vacant_hh"] ?? 0) + ($result["current_balance_vacant_hh_not_collectable"] ?? 0))); ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["resident_hh"] ?? 0) + ($result["resident_hh_not_collectable"] ?? 0) +
                                                        ($result["non_resident_hh"] ?? 0) + ($result["non_resident_hh_not_collectable"] ?? 0) +
                                                        ($result["mix_hh"] ?? 0) + ($result["mix_hh_not_collectable"] ?? 0) +
                                                        ($result["state_gov_hh"] ?? 0) + ($result["state_gov_hh_not_collectable"] ?? 0) +
                                                        ($result["central_gov_hh"] ?? 0) + ($result["central_gov_hh_not_collectable"] ?? 0) +
                                                        ($result["state_gov_psu_hh"] ?? 0) + ($result["state_gov_psu_hh_not_collectable"] ?? 0) +
                                                        ($result["central_gov_psu_hh"] ?? 0) + ($result["central_gov_psu_hh_not_collectable"] ?? 0)+
                                                        ($result["vacant_hh"] ?? 0) + ($result["vacant_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["outstanding_resident_hh"] ?? 0) + ($result["outstanding_resident_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_non_resident_hh"] ?? 0) + ($result["outstanding_non_resident_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_mix_hh"] ?? 0) + ($result["outstanding_mix_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_state_gov_hh"] ?? 0) + ($result["outstanding_state_gov_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_central_gov_hh"] ?? 0) + ($result["outstanding_central_gov_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_state_gov_psu_hh"] ?? 0) + ($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_central_gov_psu_hh"] ?? 0) + ($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_vacant_hh"] ?? 0) + ($result["outstanding_vacant_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_demand_resident_hh"] ?? 0) + ($result["current_demand_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_non_resident_hh"] ?? 0) + ($result["current_demand_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_mix_hh"] ?? 0) + ($result["current_demand_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_state_gov_hh"] ?? 0) + ($result["current_demand_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_central_gov_hh"] ?? 0) + ($result["current_demand_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_state_gov_psu_hh"] ?? 0) + ($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_central_gov_psu_hh"] ?? 0) + ($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_vacant_hh"] ?? 0) + ($result["current_demand_vacant_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["outstanding_resident_hh"] ?? 0) + ($result["current_demand_resident_hh"] ?? 0)) + (($result["outstanding_resident_hh_not_collectable"] ?? 0) + ($result["current_demand_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_non_resident_hh"] ?? 0) + ($result["current_demand_non_resident_hh"] ?? 0)) + (($result["outstanding_non_resident_hh_not_collectable"] ?? 0) + ($result["current_demand_non_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_mix_hh"] ?? 0) + ($result["current_demand_mix_hh"] ?? 0)) + (($result["outstanding_mix_hh_not_collectable"] ?? 0) + ($result["current_demand_mix_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_state_gov_hh"] ?? 0) + ($result["current_demand_state_gov_hh"] ?? 0)) + (($result["outstanding_state_gov_hh_not_collectable"] ?? 0) + ($result["current_demand_state_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_central_gov_hh"] ?? 0) + ($result["current_demand_central_gov_hh"] ?? 0)) + (($result["outstanding_central_gov_hh_not_collectable"] ?? 0) + ($result["current_demand_central_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_state_gov_psu_hh"] ?? 0) + ($result["current_demand_state_gov_psu_hh"] ?? 0)) + (($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_central_gov_psu_hh"] ?? 0) + ($result["current_demand_central_gov_psu_hh"] ?? 0)) + (($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["outstanding_vacant_hh"] ?? 0) + ($result["current_demand_vacant_hh"] ?? 0)) + (($result["outstanding_vacant_hh_not_collectable"] ?? 0) + ($result["current_demand_vacant_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>

                                                
                                                <td>
                                                    <?= round(
                                                        ($result["arrear_collection_resident_hh"] ?? 0) + ($result["arrear_collection_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_non_resident_hh"] ?? 0) + ($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_mix_hh"] ?? 0) + ($result["arrear_collection_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_state_gov_hh"] ?? 0) + ($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_central_gov_hh"] ?? 0) + ($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_state_gov_psu_hh"] ?? 0) + ($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_central_gov_psu_hh"] ?? 0) + ($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_vacant_hh"] ?? 0) + ($result["arrear_collection_vacant_hh_not_collectable"] ?? 0) 
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_collection_resident_hh"] ?? 0) + ($result["current_collection_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_non_resident_hh"] ?? 0) + ($result["current_collection_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_mix_hh"] ?? 0) + ($result["current_collection_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_state_gov_hh"] ?? 0) + ($result["current_collection_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_central_gov_hh"] ?? 0) + ($result["current_collection_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_state_gov_psu_hh"] ?? 0) + ($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_central_gov_psu_hh"] ?? 0) + ($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_vacant_hh"] ?? 0) + ($result["current_collection_vacant_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_collection_resident_hh"] ?? 0) + ($result["current_collection_resident_hh"] ?? 0)) + (($result["arrear_collection_resident_hh_not_collectable"] ?? 0) + ($result["current_collection_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_non_resident_hh"] ?? 0) + ($result["current_collection_non_resident_hh"] ?? 0)) + (($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0) + ($result["current_collection_non_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_mix_hh"] ?? 0) + ($result["current_collection_mix_hh"] ?? 0)) + (($result["arrear_collection_mix_hh_not_collectable"] ?? 0) + ($result["current_collection_mix_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_state_gov_hh"] ?? 0) + ($result["current_collection_state_gov_hh"] ?? 0)) + (($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0) + ($result["current_collection_state_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_central_gov_hh"] ?? 0) + ($result["current_collection_central_gov_hh"] ?? 0)) + (($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0) + ($result["current_collection_central_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_state_gov_psu_hh"] ?? 0) + ($result["current_collection_state_gov_psu_hh"] ?? 0)) + (($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_central_gov_psu_hh"] ?? 0) + ($result["current_collection_central_gov_psu_hh"] ?? 0)) + (($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_vacant_hh"] ?? 0) + ($result["current_collection_vacant_hh"] ?? 0)) + (($result["arrear_collection_vacant_hh_not_collectable"] ?? 0) + ($result["current_collection_vacant_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["arrear_balance_resident_hh"] ?? 0) + ($result["arrear_balance_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_non_resident_hh"] ?? 0) + ($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_mix_hh"] ?? 0) + ($result["arrear_balance_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_state_gov_hh"] ?? 0) + ($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_central_gov_hh"] ?? 0) + ($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_state_gov_psu_hh"] ?? 0) + ($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_central_gov_psu_hh"] ?? 0) + ($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_vacant_hh"] ?? 0) + ($result["arrear_balance_vacant_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_balance_resident_hh"] ?? 0) + ($result["current_balance_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_non_resident_hh"] ?? 0) + ($result["current_balance_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_mix_hh"] ?? 0) + ($result["current_balance_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_state_gov_hh"] ?? 0) + ($result["current_balance_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_central_gov_hh"] ?? 0) + ($result["current_balance_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_state_gov_psu_hh"] ?? 0) + ($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_central_gov_psu_hh"] ?? 0) + ($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_vacant_hh"] ?? 0) + ($result["current_balance_vacant_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_balance_resident_hh"] ?? 0) + ($result["current_balance_resident_hh"] ?? 0)) + (($result["arrear_balance_resident_hh_not_collectable"] ?? 0) + ($result["current_balance_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_non_resident_hh"] ?? 0) + ($result["current_balance_non_resident_hh"] ?? 0)) + (($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0) + ($result["current_balance_non_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_mix_hh"] ?? 0) + ($result["current_balance_mix_hh"] ?? 0)) + (($result["arrear_balance_mix_hh_not_collectable"] ?? 0) + ($result["current_balance_mix_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_state_gov_hh"] ?? 0) + ($result["current_balance_state_gov_hh"] ?? 0)) + (($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0) + ($result["current_balance_state_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_central_gov_hh"] ?? 0) + ($result["current_balance_central_gov_hh"] ?? 0)) + (($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0) + ($result["current_balance_central_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_state_gov_psu_hh"] ?? 0) + ($result["current_balance_state_gov_psu_hh"] ?? 0)) + (($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_central_gov_psu_hh"] ?? 0) + ($result["current_balance_central_gov_psu_hh"] ?? 0)) + (($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_vacant_hh"] ?? 0) + ($result["current_balance_vacant_hh"] ?? 0)) + (($result["arrear_balance_vacant_hh_not_collectable"] ?? 0) + ($result["current_balance_vacant_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <!-- collectable -->                                            
                                            <tr>
                                                <th rowspan="5">Collectable</th>
                                                <td>100% Residential</td>
                                                <td><?= ($result["resident_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_resident_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_resident_hh"] ?? 0)) + (($result["current_demand_resident_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_resident_hh"] ?? 0) ) + (($result["current_collection_resident_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_resident_hh"] ?? 0) ) + (($result["current_balance_resident_hh"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>100% Non Residential</td>                                              
                                                <td><?= ($result["non_resident_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_non_resident_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_non_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_non_resident_hh"] ?? 0) ) + (($result["current_demand_non_resident_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_non_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_non_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_non_resident_hh"] ?? 0) ) + (($result["current_collection_non_resident_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_non_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_non_resident_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_non_resident_hh"] ?? 0) ) + (($result["current_balance_non_resident_hh"] ?? 0) )); ?></td>
                                            </tr>

                                            <tr>
                                                <td>Mix Holding</td>
                                                <td><?= ($result["mix_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_mix_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_mix_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_mix_hh"] ?? 0) ) + (($result["current_demand_mix_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_mix_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_mix_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_mix_hh"] ?? 0) ) + (($result["current_collection_mix_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_mix_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_mix_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_mix_hh"] ?? 0) ) + (($result["current_balance_mix_hh"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Vacant Land</td>
                                                <td><?= ($result["vacant_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_vacant_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_vacant_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_vacant_hh"] ?? 0) ) + (($result["current_demand_vacant_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_vacant_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_vacant_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_vacant_hh"] ?? 0) ) + (($result["current_collection_vacant_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_vacant_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_vacant_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_vacant_hh"] ?? 0) ) + (($result["current_balance_vacant_hh"] ?? 0) )); ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["resident_hh"] ?? 0)  +
                                                        ($result["non_resident_hh"] ?? 0)  +
                                                        ($result["mix_hh"] ?? 0) +
                                                        ($result["vacant_hh"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["outstanding_resident_hh"] ?? 0) +
                                                        ($result["outstanding_non_resident_hh"] ?? 0) +
                                                        ($result["outstanding_mix_hh"] ?? 0) +
                                                        ($result["outstanding_vacant_hh"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_demand_resident_hh"] ?? 0) + 
                                                        ($result["current_demand_non_resident_hh"] ?? 0)  + 
                                                        ($result["current_demand_mix_hh"] ?? 0)  + 
                                                        ($result["current_demand_vacant_hh"] ?? 0)   
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["outstanding_resident_hh"] ?? 0) + ($result["current_demand_resident_hh"] ?? 0))  + 
                                                            (($result["outstanding_non_resident_hh"] ?? 0) + ($result["current_demand_non_resident_hh"] ?? 0))  + 
                                                            (($result["outstanding_mix_hh"] ?? 0) + ($result["current_demand_mix_hh"] ?? 0))  +
                                                            (($result["outstanding_vacant_hh"] ?? 0) + ($result["current_demand_vacant_hh"] ?? 0))
                                                    ); ?>
                                                </td>

                                                
                                                <td>
                                                    <?= round(
                                                        ($result["arrear_collection_resident_hh"] ?? 0) + 
                                                        ($result["arrear_collection_non_resident_hh"] ?? 0) + 
                                                        ($result["arrear_collection_mix_hh"] ?? 0) + 
                                                        ($result["arrear_collection_vacant_hh"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_collection_resident_hh"] ?? 0) + 
                                                        ($result["current_collection_non_resident_hh"] ?? 0) + 
                                                        ($result["current_collection_mix_hh"] ?? 0) + 
                                                        ($result["current_collection_vacant_hh"] ?? 0) 
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_collection_resident_hh"] ?? 0) + ($result["current_collection_resident_hh"] ?? 0)) + 
                                                            (($result["arrear_collection_non_resident_hh"] ?? 0) + ($result["current_collection_non_resident_hh"] ?? 0)) + 
                                                            (($result["arrear_collection_mix_hh"] ?? 0) + ($result["current_collection_mix_hh"] ?? 0)) + 
                                                            (($result["arrear_collection_vacant_hh"] ?? 0) + ($result["current_collection_vacant_hh"] ?? 0)) 
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["arrear_balance_resident_hh"] ?? 0) + 
                                                        ($result["arrear_balance_non_resident_hh"] ?? 0) + 
                                                        ($result["arrear_balance_mix_hh"] ?? 0) + 
                                                        ($result["arrear_balance_vacant_hh"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_balance_resident_hh"] ?? 0) + 
                                                        ($result["current_balance_non_resident_hh"] ?? 0) + 
                                                        ($result["current_balance_mix_hh"] ?? 0) + 
                                                        ($result["current_balance_vacant_hh"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_balance_resident_hh"] ?? 0) + ($result["current_balance_resident_hh"] ?? 0)) + 
                                                            (($result["arrear_balance_non_resident_hh"] ?? 0) + ($result["current_balance_non_resident_hh"] ?? 0)) + 
                                                            (($result["arrear_balance_mix_hh"] ?? 0) + ($result["current_balance_mix_hh"] ?? 0)) + 
                                                            (($result["arrear_balance_vacant_hh"] ?? 0) + ($result["current_balance_vacant_hh"] ?? 0)) 
                                                    ); ?>
                                                </td>
                                            </tr>

                                            
                                            <!-- not collectable -->                                            
                                            <tr>
                                                <th rowspan="5">Not Collectable</th>
                                                <td>100% Residential</td>
                                                <td><?= ($result["resident_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_resident_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_resident_hh_not_collectable"] ?? 0)) + (($result["current_demand_resident_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_resident_hh_not_collectable"] ?? 0) ) + (($result["current_collection_resident_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_resident_hh_not_collectable"] ?? 0) ) + (($result["current_balance_resident_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>100% Non Residential</td>                                              
                                                <td><?= ($result["non_resident_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_non_resident_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_non_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_non_resident_hh_not_collectable"] ?? 0) ) + (($result["current_demand_non_resident_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_non_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0) ) + (($result["current_collection_non_resident_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_non_resident_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0) ) + (($result["current_balance_non_resident_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>

                                            <tr>
                                                <td>Mix Holding</td>
                                                <td><?= ($result["mix_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_mix_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_mix_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_mix_hh_not_collectable"] ?? 0) ) + (($result["current_demand_mix_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_mix_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_mix_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_mix_hh_not_collectable"] ?? 0) ) + (($result["current_collection_mix_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_mix_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_mix_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_mix_hh_not_collectable"] ?? 0) ) + (($result["current_balance_mix_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Vacant Land</td>
                                                <td><?= ($result["vacant_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_vacant_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_vacant_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_vacant_hh_not_collectable"] ?? 0) ) + (($result["current_demand_vacant_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_vacant_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_vacant_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_vacant_hh_not_collectable"] ?? 0) ) + (($result["current_collection_vacant_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_vacant_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_vacant_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_vacant_hh_not_collectable"] ?? 0) ) + (($result["current_balance_vacant_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["resident_hh_not_collectable"] ?? 0)  +
                                                        ($result["non_resident_hh_not_collectable"] ?? 0)  +
                                                        ($result["mix_hh_not_collectable"] ?? 0) +
                                                        ($result["vacant_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["outstanding_resident_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_non_resident_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_mix_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_vacant_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_demand_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_non_resident_hh_not_collectable"] ?? 0)  + 
                                                        ($result["current_demand_mix_hh_not_collectable"] ?? 0)  + 
                                                        ($result["current_demand_vacant_hh_not_collectable"] ?? 0)   
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["outstanding_resident_hh_not_collectable"] ?? 0) + ($result["current_demand_resident_hh_not_collectable"] ?? 0))  + 
                                                            (($result["outstanding_non_resident_hh_not_collectable"] ?? 0) + ($result["current_demand_non_resident_hh_not_collectable"] ?? 0))  + 
                                                            (($result["outstanding_mix_hh_not_collectable"] ?? 0) + ($result["current_demand_mix_hh_not_collectable"] ?? 0))  +
                                                            (($result["outstanding_vacant_hh_not_collectable"] ?? 0) + ($result["current_demand_vacant_hh_not_collectable"] ?? 0))
                                                    ); ?>
                                                </td>

                                                
                                                <td>
                                                    <?= round(
                                                        ($result["arrear_collection_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_vacant_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_collection_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_vacant_hh_not_collectable"] ?? 0) 
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_collection_resident_hh_not_collectable"] ?? 0) + ($result["current_collection_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_non_resident_hh_not_collectable"] ?? 0) + ($result["current_collection_non_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_mix_hh_not_collectable"] ?? 0) + ($result["current_collection_mix_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_vacant_hh_not_collectable"] ?? 0) + ($result["current_collection_vacant_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["arrear_balance_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_vacant_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_balance_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_non_resident_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_mix_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_vacant_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_balance_resident_hh_not_collectable"] ?? 0) + ($result["current_balance_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_non_resident_hh_not_collectable"] ?? 0) + ($result["current_balance_non_resident_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_mix_hh_not_collectable"] ?? 0) + ($result["current_balance_mix_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_vacant_hh_not_collectable"] ?? 0) + ($result["current_balance_vacant_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>
                                            </tr>

                                             <!--gov collectable -->                                            
                                             <tr>
                                                <th rowspan="5">Collectable Govt Building </th>
                                                <td>State Govt Building</td>
                                                <td><?= ($result["state_gov_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_state_gov_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_state_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_state_gov_hh"] ?? 0)) + (($result["current_demand_state_gov_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_state_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_state_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_state_gov_hh"] ?? 0) ) + (($result["current_collection_state_gov_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_state_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_state_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_state_gov_hh"] ?? 0) ) + (($result["current_balance_state_gov_hh"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central Govt Building</td>                                              
                                                <td><?= ($result["central_gov_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_central_gov_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_central_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_central_gov_hh"] ?? 0) ) + (($result["current_demand_central_gov_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_central_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_central_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_central_gov_hh"] ?? 0) ) + (($result["current_collection_central_gov_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_central_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_central_gov_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_central_gov_hh"] ?? 0) ) + (($result["current_balance_central_gov_hh"] ?? 0) )); ?></td>
                                            </tr>

                                            <tr>
                                                <td>State PSU</td>
                                                <td><?= ($result["state_gov_psu_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_state_gov_psu_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_state_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_state_gov_psu_hh"] ?? 0) ) + (($result["current_demand_state_gov_psu_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_state_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_state_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_state_gov_psu_hh"] ?? 0) ) + (($result["current_collection_state_gov_psu_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_state_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_state_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_state_gov_psu_hh"] ?? 0) ) + (($result["current_balance_state_gov_psu_hh"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central PSU</td>
                                                <td><?= ($result["central_gov_psu_hh"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_central_gov_psu_hh"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_central_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_central_gov_psu_hh"] ?? 0) ) + (($result["current_demand_central_gov_psu_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_central_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_central_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_central_gov_psu_hh"] ?? 0) ) + (($result["current_collection_central_gov_psu_hh"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_central_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_central_gov_psu_hh"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_central_gov_psu_hh"] ?? 0) ) + (($result["current_balance_central_gov_psu_hh"] ?? 0) )); ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["state_gov_hh"] ?? 0)  +
                                                        ($result["central_gov_hh"] ?? 0)  +
                                                        ($result["state_gov_psu_hh"] ?? 0) +
                                                        ($result["central_gov_psu_hh"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["outstanding_state_gov_hh"] ?? 0) +
                                                        ($result["outstanding_central_gov_hh"] ?? 0) +
                                                        ($result["outstanding_state_gov_psu_hh"] ?? 0) +
                                                        ($result["outstanding_central_gov_psu_hh"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_demand_state_gov_hh"] ?? 0) + 
                                                        ($result["current_demand_central_gov_hh"] ?? 0)  + 
                                                        ($result["current_demand_state_gov_psu_hh"] ?? 0)  + 
                                                        ($result["current_demand_central_gov_psu_hh"] ?? 0)   
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["outstanding_state_gov_hh"] ?? 0) + ($result["current_demand_state_gov_hh"] ?? 0))  + 
                                                            (($result["outstanding_central_gov_hh"] ?? 0) + ($result["current_demand_central_gov_hh"] ?? 0))  + 
                                                            (($result["outstanding_state_gov_psu_hh"] ?? 0) + ($result["current_demand_state_gov_psu_hh"] ?? 0))  +
                                                            (($result["outstanding_central_gov_psu_hh"] ?? 0) + ($result["current_demand_central_gov_psu_hh"] ?? 0))
                                                    ); ?>
                                                </td>

                                                
                                                <td>
                                                    <?= round(
                                                        ($result["arrear_collection_state_gov_hh"] ?? 0) + 
                                                        ($result["arrear_collection_central_gov_hh"] ?? 0) + 
                                                        ($result["arrear_collection_state_gov_psu_hh"] ?? 0) + 
                                                        ($result["arrear_collection_central_gov_psu_hh"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_collection_state_gov_hh"] ?? 0) + 
                                                        ($result["current_collection_central_gov_hh"] ?? 0) + 
                                                        ($result["current_collection_state_gov_psu_hh"] ?? 0) + 
                                                        ($result["current_collection_central_gov_psu_hh"] ?? 0) 
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_collection_state_gov_hh"] ?? 0) + ($result["current_collection_state_gov_hh"] ?? 0)) + 
                                                            (($result["arrear_collection_central_gov_hh"] ?? 0) + ($result["current_collection_central_gov_hh"] ?? 0)) + 
                                                            (($result["arrear_collection_state_gov_psu_hh"] ?? 0) + ($result["current_collection_state_gov_psu_hh"] ?? 0)) + 
                                                            (($result["arrear_collection_central_gov_psu_hh"] ?? 0) + ($result["current_collection_central_gov_psu_hh"] ?? 0)) 
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["arrear_balance_state_gov_hh"] ?? 0) + 
                                                        ($result["arrear_balance_central_gov_hh"] ?? 0) + 
                                                        ($result["arrear_balance_state_gov_psu_hh"] ?? 0) + 
                                                        ($result["arrear_balance_central_gov_psu_hh"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_balance_state_gov_hh"] ?? 0) + 
                                                        ($result["current_balance_central_gov_hh"] ?? 0) + 
                                                        ($result["current_balance_state_gov_psu_hh"] ?? 0) + 
                                                        ($result["current_balance_central_gov_psu_hh"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_balance_state_gov_hh"] ?? 0) + ($result["current_balance_state_gov_hh"] ?? 0)) + 
                                                            (($result["arrear_balance_central_gov_hh"] ?? 0) + ($result["current_balance_central_gov_hh"] ?? 0)) + 
                                                            (($result["arrear_balance_state_gov_psu_hh"] ?? 0) + ($result["current_balance_state_gov_psu_hh"] ?? 0)) + 
                                                            (($result["arrear_balance_central_gov_psu_hh"] ?? 0) + ($result["current_balance_central_gov_psu_hh"] ?? 0)) 
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <!--gov not collectable -->                                            
                                            <tr>
                                                <th rowspan="5">Not Collectable Govt Building </th>
                                                <td>State Govt Building</td>
                                                <td><?= ($result["state_gov_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_state_gov_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_state_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_state_gov_hh_not_collectable"] ?? 0)) + (($result["current_demand_state_gov_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_state_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0) ) + (($result["current_collection_state_gov_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_state_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0) ) + (($result["current_balance_state_gov_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central Govt Building</td>                                              
                                                <td><?= ($result["central_gov_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_central_gov_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_central_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_central_gov_hh_not_collectable"] ?? 0) ) + (($result["current_demand_central_gov_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_central_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0) ) + (($result["current_collection_central_gov_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_central_gov_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0) ) + (($result["current_balance_central_gov_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>

                                            <tr>
                                                <td>State PSU</td>
                                                <td><?= ($result["state_gov_psu_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0) ) + (($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0) ) + (($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0) ) + (($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central PSU</td>
                                                <td><?= ($result["central_gov_psu_hh_not_collectable"] ?? 0) ; ?></td>
                                                <td><?= round(($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0) ) ; ?></td>
                                                <td><?= round(($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0) ) + (($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0) ) + (($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0) )); ?></td>

                                                <td><?= round(($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round(($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0) ); ?></td>
                                                <td><?= round((($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0) ) + (($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0) )); ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["state_gov_hh_not_collectable"] ?? 0)  +
                                                        ($result["central_gov_hh_not_collectable"] ?? 0)  +
                                                        ($result["state_gov_psu_hh_not_collectable"] ?? 0) +
                                                        ($result["central_gov_psu_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["outstanding_state_gov_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_central_gov_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0) +
                                                        ($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_demand_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_demand_central_gov_hh_not_collectable"] ?? 0)  + 
                                                        ($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0)  + 
                                                        ($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0)   
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["outstanding_state_gov_hh_not_collectable"] ?? 0) + ($result["current_demand_state_gov_hh_not_collectable"] ?? 0))  + 
                                                            (($result["outstanding_central_gov_hh_not_collectable"] ?? 0) + ($result["current_demand_central_gov_hh_not_collectable"] ?? 0))  + 
                                                            (($result["outstanding_state_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_demand_state_gov_psu_hh_not_collectable"] ?? 0))  +
                                                            (($result["outstanding_central_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_demand_central_gov_psu_hh_not_collectable"] ?? 0))
                                                    ); ?>
                                                </td>

                                                
                                                <td>
                                                    <?= round(
                                                        ($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_collection_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0) 
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_collection_state_gov_hh_not_collectable"] ?? 0) + ($result["current_collection_state_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_central_gov_hh_not_collectable"] ?? 0) + ($result["current_collection_central_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_state_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_collection_state_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_collection_central_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_collection_central_gov_psu_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["current_balance_state_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_central_gov_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0) + 
                                                        ($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0)  
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                            (($result["arrear_balance_state_gov_hh_not_collectable"] ?? 0) + ($result["current_balance_state_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_central_gov_hh_not_collectable"] ?? 0) + ($result["current_balance_central_gov_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_state_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_balance_state_gov_psu_hh_not_collectable"] ?? 0)) + 
                                                            (($result["arrear_balance_central_gov_psu_hh_not_collectable"] ?? 0) + ($result["current_balance_central_gov_psu_hh_not_collectable"] ?? 0)) 
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <!--Disputed Cases Certified by RMC -->                                            
                                            <tr>
                                                <th rowspan="9">Disputed Cases Certified by RMC </th>
                                                <td>100% Residential</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>100% Non Residential</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>

                                            <tr>
                                                <td>Mix Holding</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>State Govt Building</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central Govt Building</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>

                                            <tr>
                                                <td>State PSU</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central PSU</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Vacant Land</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>

                                            <!--Non Traceable -->                                            
                                            <tr>
                                                <th rowspan="9">Non Traceable </th>
                                                <td>100% Residential</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>100% Non Residential</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>

                                            <tr>
                                                <td>Mix Holding</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>State Govt Building</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central Govt Building</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>

                                            <tr>
                                                <td>State PSU</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Central PSU</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Vacant Land</td>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                            
                                            
                                            <tr>
                                                <th>Total</th>
                                                <td><?= 0 ; ?></td>
                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>

                                                <td><?=0 ; ?></td>
                                                <td><?= 0; ?></td>
                                                <td><?= 0; ?></td>
                                            </tr>
                                           

                                        <?php endif;  ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->
</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">    

    function fnExcelReport() {
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
        var j = 0;
        var tab = document.getElementById('demo_dt_basic'); // id of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var msie = window.navigator.userAgent.indexOf("MSIE ");

        // If Internet Explorer
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();

            sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
        } else {
            // other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        }

        return sa;
    }
</script>