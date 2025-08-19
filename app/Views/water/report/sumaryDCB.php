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
            <li class="active">Water Physical Status</li>
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
                        <h5 class="panel-title">Physical Status</h5>
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
                                                foreach ($ward_list as $ward) {
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
                                            <label class="control-label" for="Fin Year"><b>Financial Year</b><span class="text-danger">*</span> </label>
                                            <select name="fin_year" id="fin_year" class="form-control">
                                                <?php
                                                $fy_list = fy_year_list();
                                                foreach ($fy_list as $val) {
                                                ?>
                                                    <option value="<?= $val ?>" <?= isset($fin_year) && $fin_year == $val ? "selected" : ""; ?>><?= $val ?></option>
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
                                            <th rowspan="2">Type of Connection</th>
                                            <th>No of</th>
                                            <th colspan="3">Demand</th>
                                            <th colspan="3">Collection</th>
                                            <th colspan="3">Balance Due</th>
                                        </tr>
                                        <tr>
                                            <th>Water Connection</th>
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
                                                <td colspan="11" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                        <?php else:
                                        ?>

                                            <tr>
                                                <th rowspan="5">Total</th>
                                                <td>Residential</td>
                                                <td><?= $result["resident_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["resident_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["resident_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["resident_current_demand"] ?? 0) + ($result["resident_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["resident_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["resident_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["resident_arrear_coll"] ?? 0) + ($result["resident_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["resident_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["resident_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["resident_old_due"] ?? 0) + ($result["resident_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Commercial</td>
                                                <td><?= $result["comercial_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["comercial_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["comercial_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["comercial_current_demand"] ?? 0) + ($result["comercial_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["comercial_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["comercial_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["comercial_arrear_coll"] ?? 0) + ($result["comercial_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["comercial_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["comercial_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["comercial_old_due"] ?? 0) + ($result["comercial_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Institution</td>
                                                <td><?= $result["instit_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["instit_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["instit_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["instit_current_demand"] ?? 0) + ($result["instit_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["instit_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["instit_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["instit_arrear_coll"] ?? 0) + ($result["instit_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["instit_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["instit_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["instit_old_due"] ?? 0) + ($result["instit_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Industrial</td>
                                                <td><?= $result["indust_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["indust_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["indust_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["indust_current_demand"] ?? 0) + ($result["indust_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["indust_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["indust_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["indust_arrear_coll"] ?? 0) + ($result["indust_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["indust_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["indust_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["indust_old_due"] ?? 0) + ($result["indust_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["resident_consumer"] ?? 0) +
                                                        ($result["comercial_consumer"] ?? 0) +
                                                        ($result["instit_consumer"] ?? 0) +
                                                        ($result["indust_consumer"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["resident_outstanding_at_begin"] ?? 0) +
                                                            ($result["comercial_outstanding_at_begin"] ?? 0) +
                                                            ($result["instit_outstanding_at_begin"] ?? 0) +
                                                            ($result["indust_outstanding_at_begin"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["resident_current_demand"] ?? 0) +
                                                            ($result["comercial_current_demand"] ?? 0) +
                                                            ($result["instit_current_demand"] ?? 0) +
                                                            ($result["indust_current_demand"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["resident_current_demand"] ?? 0) + ($result["resident_outstanding_at_begin"] ?? 0)) +
                                                            (($result["comercial_current_demand"] ?? 0) + ($result["comercial_outstanding_at_begin"] ?? 0)) +
                                                            (($result["instit_current_demand"] ?? 0) + ($result["instit_outstanding_at_begin"] ?? 0)) +
                                                            (($result["indust_current_demand"] ?? 0) + ($result["indust_outstanding_at_begin"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["resident_arrear_coll"] ?? 0) +
                                                            ($result["comercial_arrear_coll"] ?? 0) +
                                                            ($result["instit_arrear_coll"] ?? 0) +
                                                            ($result["indust_arrear_coll"] ?? 0)
                                                    ); ?>
                                                <td>
                                                    <?= round(
                                                        ($result["resident_curr_coll"] ?? 0) +
                                                            ($result["comercial_curr_coll"] ?? 0) +
                                                            ($result["instit_curr_coll"] ?? 0) +
                                                            ($result["indust_curr_coll"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["resident_arrear_coll"] ?? 0) + ($result["resident_curr_coll"] ?? 0)) +
                                                            (($result["comercial_arrear_coll"] ?? 0) + ($result["comercial_curr_coll"] ?? 0)) +
                                                            (($result["instit_arrear_coll"] ?? 0) + ($result["instit_curr_coll"] ?? 0)) +
                                                            (($result["indust_arrear_coll"] ?? 0) + ($result["indust_curr_coll"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["resident_old_due"] ?? 0) +
                                                            ($result["comercial_old_due"] ?? 0) +
                                                            ($result["instit_old_due"] ?? 0) +
                                                            ($result["indust_old_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["resident_curr_due"] ?? 0) +
                                                            ($result["comercial_curr_due"] ?? 0) +
                                                            ($result["instit_curr_due"] ?? 0) +
                                                            ($result["indust_curr_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["resident_old_due"] ?? 0) + ($result["resident_curr_due"] ?? 0)) +
                                                            (($result["comercial_old_due"] ?? 0) + ($result["comercial_curr_due"] ?? 0)) +
                                                            (($result["instit_old_due"] ?? 0) + ($result["instit_curr_due"] ?? 0)) +
                                                            (($result["indust_old_due"] ?? 0) + ($result["indust_curr_due"] ?? 0))
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th rowspan="5">Collectable Metered Water Connection</th>
                                                <td>Residential</td>
                                                <td><?= $result["meter_collectable_resident_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_collectable_resident_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_resident_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_resident_current_demand"] ?? 0) + ($result["meter_collectable_resident_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_resident_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_resident_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_resident_arrear_coll"] ?? 0) + ($result["meter_collectable_resident_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_resident_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_resident_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_resident_old_due"] ?? 0) + ($result["meter_collectable_resident_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Commercial</td>
                                                <td><?= $result["meter_collectable_comercial_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_collectable_comercial_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_comercial_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_comercial_current_demand"] ?? 0) + ($result["meter_collectable_comercial_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_comercial_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_comercial_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_comercial_arrear_coll"] ?? 0) + ($result["meter_collectable_comercial_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_comercial_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_comercial_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_comercial_old_due"] ?? 0) + ($result["meter_collectable_comercial_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Institution</td>
                                                <td><?= $result["meter_collectable_instit_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_collectable_instit_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_instit_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_instit_current_demand"] ?? 0) + ($result["meter_collectable_instit_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_instit_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_instit_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_instit_arrear_coll"] ?? 0) + ($result["meter_collectable_instit_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_instit_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_instit_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_instit_old_due"] ?? 0) + ($result["meter_collectable_instit_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Industrial</td>
                                                <td><?= $result["meter_collectable_indust_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_collectable_indust_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_indust_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_indust_current_demand"] ?? 0) + ($result["meter_collectable_indust_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_indust_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_indust_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_indust_arrear_coll"] ?? 0) + ($result["meter_collectable_indust_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_collectable_indust_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_collectable_indust_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_collectable_indust_old_due"] ?? 0) + ($result["meter_collectable_indust_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["meter_collectable_resident_consumer"] ?? 0) +
                                                        ($result["meter_collectable_comercial_consumer"] ?? 0) +
                                                        ($result["meter_collectable_instit_consumer"] ?? 0) +
                                                        ($result["meter_collectable_indust_consumer"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_collectable_resident_outstanding_at_begin"] ?? 0) +
                                                            ($result["meter_collectable_comercial_outstanding_at_begin"] ?? 0) +
                                                            ($result["meter_collectable_instit_outstanding_at_begin"] ?? 0) +
                                                            ($result["meter_collectable_indust_outstanding_at_begin"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_collectable_resident_current_demand"] ?? 0) +
                                                            ($result["meter_collectable_comercial_current_demand"] ?? 0) +
                                                            ($result["meter_collectable_instit_current_demand"] ?? 0) +
                                                            ($result["meter_collectable_indust_current_demand"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["meter_collectable_resident_current_demand"] ?? 0) + ($result["meter_collectable_resident_outstanding_at_begin"] ?? 0)) +
                                                            (($result["meter_collectable_comercial_current_demand"] ?? 0) + ($result["meter_collectable_comercial_outstanding_at_begin"] ?? 0)) +
                                                            (($result["meter_collectable_instit_current_demand"] ?? 0) + ($result["meter_collectable_instit_outstanding_at_begin"] ?? 0)) +
                                                            (($result["meter_collectable_indust_current_demand"] ?? 0) + ($result["meter_collectable_indust_outstanding_at_begin"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["meter_collectable_resident_arrear_coll"] ?? 0) +
                                                            ($result["meter_collectable_comercial_arrear_coll"] ?? 0) +
                                                            ($result["meter_collectable_instit_arrear_coll"] ?? 0) +
                                                            ($result["meter_collectable_indust_arrear_coll"] ?? 0)
                                                    ); ?>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_collectable_resident_curr_coll"] ?? 0) +
                                                            ($result["meter_collectable_comercial_curr_coll"] ?? 0) +
                                                            ($result["meter_collectable_instit_curr_coll"] ?? 0) +
                                                            ($result["meter_collectable_indust_curr_coll"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["meter_collectable_resident_arrear_coll"] ?? 0) + ($result["meter_collectable_resident_curr_coll"] ?? 0)) +
                                                            (($result["meter_collectable_comercial_arrear_coll"] ?? 0) + ($result["meter_collectable_comercial_curr_coll"] ?? 0)) +
                                                            (($result["meter_collectable_instit_arrear_coll"] ?? 0) + ($result["meter_collectable_instit_curr_coll"] ?? 0)) +
                                                            (($result["meter_collectable_indust_arrear_coll"] ?? 0) + ($result["meter_collectable_indust_curr_coll"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["meter_collectable_resident_old_due"] ?? 0) +
                                                            ($result["meter_collectable_comercial_old_due"] ?? 0) +
                                                            ($result["meter_collectable_instit_old_due"] ?? 0) +
                                                            ($result["meter_collectable_indust_old_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_collectable_resident_curr_due"] ?? 0) +
                                                            ($result["meter_collectable_comercial_curr_due"] ?? 0) +
                                                            ($result["meter_collectable_instit_curr_due"] ?? 0) +
                                                            ($result["meter_collectable_indust_curr_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["meter_collectable_resident_old_due"] ?? 0) + ($result["meter_collectable_resident_curr_due"] ?? 0)) +
                                                            (($result["meter_collectable_comercial_old_due"] ?? 0) + ($result["meter_collectable_comercial_curr_due"] ?? 0)) +
                                                            (($result["meter_collectable_instit_old_due"] ?? 0) + ($result["meter_collectable_instit_curr_due"] ?? 0)) +
                                                            (($result["meter_collectable_indust_old_due"] ?? 0) + ($result["meter_collectable_indust_curr_due"] ?? 0))
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th rowspan="5">Not Collectable Metered Water Connection</th>
                                                <td>Residential</td>
                                                <td><?= $result["meter_not_collectable_resident_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_not_collectable_resident_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_resident_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_resident_current_demand"] ?? 0) + ($result["meter_not_collectable_resident_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_resident_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_resident_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_resident_arrear_coll"] ?? 0) + ($result["meter_not_collectable_resident_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_resident_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_resident_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_resident_old_due"] ?? 0) + ($result["meter_not_collectable_resident_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Commercial</td>
                                                <td><?= $result["meter_not_collectable_comercial_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_not_collectable_comercial_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_comercial_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_comercial_current_demand"] ?? 0) + ($result["meter_not_collectable_comercial_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_comercial_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_comercial_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_comercial_arrear_coll"] ?? 0) + ($result["meter_not_collectable_comercial_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_comercial_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_comercial_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_comercial_old_due"] ?? 0) + ($result["meter_not_collectable_comercial_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Institution</td>
                                                <td><?= $result["meter_not_collectable_instit_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_not_collectable_instit_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_instit_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_instit_current_demand"] ?? 0) + ($result["meter_not_collectable_instit_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_instit_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_instit_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_instit_arrear_coll"] ?? 0) + ($result["meter_not_collectable_instit_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_instit_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_instit_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_instit_old_due"] ?? 0) + ($result["meter_not_collectable_instit_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Industrial</td>
                                                <td><?= $result["meter_not_collectable_indust_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["meter_not_collectable_indust_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_indust_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_indust_current_demand"] ?? 0) + ($result["meter_not_collectable_indust_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_indust_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_indust_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_indust_arrear_coll"] ?? 0) + ($result["meter_not_collectable_indust_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["meter_not_collectable_indust_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["meter_not_collectable_indust_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["meter_not_collectable_indust_old_due"] ?? 0) + ($result["meter_not_collectable_indust_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["meter_not_collectable_resident_consumer"] ?? 0) +
                                                        ($result["meter_not_collectable_comercial_consumer"] ?? 0) +
                                                        ($result["meter_not_collectable_instit_consumer"] ?? 0) +
                                                        ($result["meter_not_collectable_indust_consumer"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_not_collectable_resident_outstanding_at_begin"] ?? 0) +
                                                            ($result["meter_not_collectable_comercial_outstanding_at_begin"] ?? 0) +
                                                            ($result["meter_not_collectable_instit_outstanding_at_begin"] ?? 0) +
                                                            ($result["meter_not_collectable_indust_outstanding_at_begin"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_not_collectable_resident_current_demand"] ?? 0) +
                                                            ($result["meter_not_collectable_comercial_current_demand"] ?? 0) +
                                                            ($result["meter_not_collectable_instit_current_demand"] ?? 0) +
                                                            ($result["meter_not_collectable_indust_current_demand"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["meter_not_collectable_resident_current_demand"] ?? 0) + ($result["meter_not_collectable_resident_outstanding_at_begin"] ?? 0)) +
                                                            (($result["meter_not_collectable_comercial_current_demand"] ?? 0) + ($result["meter_not_collectable_comercial_outstanding_at_begin"] ?? 0)) +
                                                            (($result["meter_not_collectable_instit_current_demand"] ?? 0) + ($result["meter_not_collectable_instit_outstanding_at_begin"] ?? 0)) +
                                                            (($result["meter_not_collectable_indust_current_demand"] ?? 0) + ($result["meter_not_collectable_indust_outstanding_at_begin"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["meter_not_collectable_resident_arrear_coll"] ?? 0) +
                                                            ($result["meter_not_collectable_comercial_arrear_coll"] ?? 0) +
                                                            ($result["meter_not_collectable_instit_arrear_coll"] ?? 0) +
                                                            ($result["meter_not_collectable_indust_arrear_coll"] ?? 0)
                                                    ); ?>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_not_collectable_resident_curr_coll"] ?? 0) +
                                                            ($result["meter_not_collectable_comercial_curr_coll"] ?? 0) +
                                                            ($result["meter_not_collectable_instit_curr_coll"] ?? 0) +
                                                            ($result["meter_not_collectable_indust_curr_coll"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["meter_not_collectable_resident_arrear_coll"] ?? 0) + ($result["meter_not_collectable_resident_curr_coll"] ?? 0)) +
                                                            (($result["meter_not_collectable_comercial_arrear_coll"] ?? 0) + ($result["meter_not_collectable_comercial_curr_coll"] ?? 0)) +
                                                            (($result["meter_not_collectable_instit_arrear_coll"] ?? 0) + ($result["meter_not_collectable_instit_curr_coll"] ?? 0)) +
                                                            (($result["meter_not_collectable_indust_arrear_coll"] ?? 0) + ($result["meter_not_collectable_indust_curr_coll"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["meter_not_collectable_resident_old_due"] ?? 0) +
                                                            ($result["meter_not_collectable_comercial_old_due"] ?? 0) +
                                                            ($result["meter_not_collectable_instit_old_due"] ?? 0) +
                                                            ($result["meter_not_collectable_indust_old_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["meter_not_collectable_resident_curr_due"] ?? 0) +
                                                            ($result["meter_not_collectable_comercial_curr_due"] ?? 0) +
                                                            ($result["meter_not_collectable_instit_curr_due"] ?? 0) +
                                                            ($result["meter_not_collectable_indust_curr_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["meter_not_collectable_resident_old_due"] ?? 0) + ($result["meter_not_collectable_resident_curr_due"] ?? 0)) +
                                                            (($result["meter_not_collectable_comercial_old_due"] ?? 0) + ($result["meter_not_collectable_comercial_curr_due"] ?? 0)) +
                                                            (($result["meter_not_collectable_instit_old_due"] ?? 0) + ($result["meter_not_collectable_instit_curr_due"] ?? 0)) +
                                                            (($result["meter_not_collectable_indust_old_due"] ?? 0) + ($result["meter_not_collectable_indust_curr_due"] ?? 0))
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th rowspan="5">Collectable Non-Metered Water Connection</th>
                                                <td>Residential</td>
                                                <td><?= $result["fixed_collectable_resident_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_collectable_resident_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_resident_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_resident_current_demand"] ?? 0) + ($result["fixed_collectable_resident_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_resident_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_resident_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_resident_arrear_coll"] ?? 0) + ($result["fixed_collectable_resident_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_resident_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_resident_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_resident_old_due"] ?? 0) + ($result["fixed_collectable_resident_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Commercial</td>
                                                <td><?= $result["fixed_collectable_comercial_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_collectable_comercial_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_comercial_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_comercial_current_demand"] ?? 0) + ($result["fixed_collectable_comercial_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_comercial_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_comercial_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_comercial_arrear_coll"] ?? 0) + ($result["fixed_collectable_comercial_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_comercial_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_comercial_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_comercial_old_due"] ?? 0) + ($result["fixed_collectable_comercial_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Institution</td>
                                                <td><?= $result["fixed_collectable_instit_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_collectable_instit_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_instit_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_instit_current_demand"] ?? 0) + ($result["fixed_collectable_instit_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_instit_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_instit_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_instit_arrear_coll"] ?? 0) + ($result["fixed_collectable_instit_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_instit_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_instit_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_instit_old_due"] ?? 0) + ($result["fixed_collectable_instit_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Industrial</td>
                                                <td><?= $result["fixed_collectable_indust_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_collectable_indust_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_indust_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_indust_current_demand"] ?? 0) + ($result["fixed_collectable_indust_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_indust_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_indust_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_indust_arrear_coll"] ?? 0) + ($result["fixed_collectable_indust_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_collectable_indust_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_collectable_indust_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_collectable_indust_old_due"] ?? 0) + ($result["fixed_collectable_indust_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["fixed_collectable_resident_consumer"] ?? 0) +
                                                        ($result["fixed_collectable_comercial_consumer"] ?? 0) +
                                                        ($result["fixed_collectable_instit_consumer"] ?? 0) +
                                                        ($result["fixed_collectable_indust_consumer"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_collectable_resident_outstanding_at_begin"] ?? 0) +
                                                            ($result["fixed_collectable_comercial_outstanding_at_begin"] ?? 0) +
                                                            ($result["fixed_collectable_instit_outstanding_at_begin"] ?? 0) +
                                                            ($result["fixed_collectable_indust_outstanding_at_begin"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_collectable_resident_current_demand"] ?? 0) +
                                                            ($result["fixed_collectable_comercial_current_demand"] ?? 0) +
                                                            ($result["fixed_collectable_instit_current_demand"] ?? 0) +
                                                            ($result["fixed_collectable_indust_current_demand"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["fixed_collectable_resident_current_demand"] ?? 0) + ($result["fixed_collectable_resident_outstanding_at_begin"] ?? 0)) +
                                                            (($result["fixed_collectable_comercial_current_demand"] ?? 0) + ($result["fixed_collectable_comercial_outstanding_at_begin"] ?? 0)) +
                                                            (($result["fixed_collectable_instit_current_demand"] ?? 0) + ($result["fixed_collectable_instit_outstanding_at_begin"] ?? 0)) +
                                                            (($result["fixed_collectable_indust_current_demand"] ?? 0) + ($result["fixed_collectable_indust_outstanding_at_begin"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["fixed_collectable_resident_arrear_coll"] ?? 0) +
                                                            ($result["fixed_collectable_comercial_arrear_coll"] ?? 0) +
                                                            ($result["fixed_collectable_instit_arrear_coll"] ?? 0) +
                                                            ($result["fixed_collectable_indust_arrear_coll"] ?? 0)
                                                    ); ?>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_collectable_resident_curr_coll"] ?? 0) +
                                                            ($result["fixed_collectable_comercial_curr_coll"] ?? 0) +
                                                            ($result["fixed_collectable_instit_curr_coll"] ?? 0) +
                                                            ($result["fixed_collectable_indust_curr_coll"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["fixed_collectable_resident_arrear_coll"] ?? 0) + ($result["fixed_collectable_resident_curr_coll"] ?? 0)) +
                                                            (($result["fixed_collectable_comercial_arrear_coll"] ?? 0) + ($result["fixed_collectable_comercial_curr_coll"] ?? 0)) +
                                                            (($result["fixed_collectable_instit_arrear_coll"] ?? 0) + ($result["fixed_collectable_instit_curr_coll"] ?? 0)) +
                                                            (($result["fixed_collectable_indust_arrear_coll"] ?? 0) + ($result["fixed_collectable_indust_curr_coll"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["fixed_collectable_resident_old_due"] ?? 0) +
                                                            ($result["fixed_collectable_comercial_old_due"] ?? 0) +
                                                            ($result["fixed_collectable_instit_old_due"] ?? 0) +
                                                            ($result["fixed_collectable_indust_old_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_collectable_resident_curr_due"] ?? 0) +
                                                            ($result["fixed_collectable_comercial_curr_due"] ?? 0) +
                                                            ($result["fixed_collectable_instit_curr_due"] ?? 0) +
                                                            ($result["fixed_collectable_indust_curr_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["fixed_collectable_resident_old_due"] ?? 0) + ($result["fixed_collectable_resident_curr_due"] ?? 0)) +
                                                            (($result["fixed_collectable_comercial_old_due"] ?? 0) + ($result["fixed_collectable_comercial_curr_due"] ?? 0)) +
                                                            (($result["fixed_collectable_instit_old_due"] ?? 0) + ($result["fixed_collectable_instit_curr_due"] ?? 0)) +
                                                            (($result["fixed_collectable_indust_old_due"] ?? 0) + ($result["fixed_collectable_indust_curr_due"] ?? 0))
                                                    ); ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th rowspan="5">Not Collectable Non-Metered Water Connection</th>
                                                <td>Residential</td>
                                                <td><?= $result["fixed_not_collectable_resident_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_not_collectable_resident_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_resident_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_resident_current_demand"] ?? 0) + ($result["fixed_not_collectable_resident_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_resident_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_resident_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_resident_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_resident_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_resident_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_resident_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_resident_old_due"] ?? 0) + ($result["fixed_not_collectable_resident_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Commercial</td>
                                                <td><?= $result["fixed_not_collectable_comercial_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_not_collectable_comercial_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_comercial_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_comercial_current_demand"] ?? 0) + ($result["fixed_not_collectable_comercial_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_comercial_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_comercial_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_comercial_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_comercial_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_comercial_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_comercial_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_comercial_old_due"] ?? 0) + ($result["fixed_not_collectable_comercial_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Institution</td>
                                                <td><?= $result["fixed_not_collectable_instit_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_not_collectable_instit_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_instit_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_instit_current_demand"] ?? 0) + ($result["fixed_not_collectable_instit_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_instit_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_instit_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_instit_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_instit_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_instit_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_instit_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_instit_old_due"] ?? 0) + ($result["fixed_not_collectable_instit_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Industrial</td>
                                                <td><?= $result["fixed_not_collectable_indust_consumer"] ?? 0; ?></td>
                                                <td><?= round($result["fixed_not_collectable_indust_outstanding_at_begin"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_indust_current_demand"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_indust_current_demand"] ?? 0) + ($result["fixed_not_collectable_indust_outstanding_at_begin"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_indust_arrear_coll"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_indust_curr_coll"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_indust_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_indust_curr_coll"] ?? 0)); ?></td>

                                                <td><?= round($result["fixed_not_collectable_indust_old_due"] ?? 0); ?></td>
                                                <td><?= round($result["fixed_not_collectable_indust_curr_due"] ?? 0); ?></td>
                                                <td><?= round(($result["fixed_not_collectable_indust_old_due"] ?? 0) + ($result["fixed_not_collectable_indust_curr_due"] ?? 0)); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>
                                                    <?= (($result["fixed_not_collectable_resident_consumer"] ?? 0) +
                                                        ($result["fixed_not_collectable_comercial_consumer"] ?? 0) +
                                                        ($result["fixed_not_collectable_instit_consumer"] ?? 0) +
                                                        ($result["fixed_not_collectable_indust_consumer"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_not_collectable_resident_outstanding_at_begin"] ?? 0) +
                                                            ($result["fixed_not_collectable_comercial_outstanding_at_begin"] ?? 0) +
                                                            ($result["fixed_not_collectable_instit_outstanding_at_begin"] ?? 0) +
                                                            ($result["fixed_not_collectable_indust_outstanding_at_begin"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_not_collectable_resident_current_demand"] ?? 0) +
                                                            ($result["fixed_not_collectable_comercial_current_demand"] ?? 0) +
                                                            ($result["fixed_not_collectable_instit_current_demand"] ?? 0) +
                                                            ($result["fixed_not_collectable_indust_current_demand"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["fixed_not_collectable_resident_current_demand"] ?? 0) + ($result["fixed_not_collectable_resident_outstanding_at_begin"] ?? 0)) +
                                                            (($result["fixed_not_collectable_comercial_current_demand"] ?? 0) + ($result["fixed_not_collectable_comercial_outstanding_at_begin"] ?? 0)) +
                                                            (($result["fixed_not_collectable_instit_current_demand"] ?? 0) + ($result["fixed_not_collectable_instit_outstanding_at_begin"] ?? 0)) +
                                                            (($result["fixed_not_collectable_indust_current_demand"] ?? 0) + ($result["fixed_not_collectable_indust_outstanding_at_begin"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["fixed_not_collectable_resident_arrear_coll"] ?? 0) +
                                                            ($result["fixed_not_collectable_comercial_arrear_coll"] ?? 0) +
                                                            ($result["fixed_not_collectable_instit_arrear_coll"] ?? 0) +
                                                            ($result["fixed_not_collectable_indust_arrear_coll"] ?? 0)
                                                    ); ?>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_not_collectable_resident_curr_coll"] ?? 0) +
                                                            ($result["fixed_not_collectable_comercial_curr_coll"] ?? 0) +
                                                            ($result["fixed_not_collectable_instit_curr_coll"] ?? 0) +
                                                            ($result["fixed_not_collectable_indust_curr_coll"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["fixed_not_collectable_resident_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_resident_curr_coll"] ?? 0)) +
                                                            (($result["fixed_not_collectable_comercial_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_comercial_curr_coll"] ?? 0)) +
                                                            (($result["fixed_not_collectable_instit_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_instit_curr_coll"] ?? 0)) +
                                                            (($result["fixed_not_collectable_indust_arrear_coll"] ?? 0) + ($result["fixed_not_collectable_indust_curr_coll"] ?? 0))
                                                    ); ?>
                                                </td>

                                                <td>
                                                    <?= round(
                                                        ($result["fixed_not_collectable_resident_old_due"] ?? 0) +
                                                            ($result["fixed_not_collectable_comercial_old_due"] ?? 0) +
                                                            ($result["fixed_not_collectable_instit_old_due"] ?? 0) +
                                                            ($result["fixed_not_collectable_indust_old_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        ($result["fixed_not_collectable_resident_curr_due"] ?? 0) +
                                                            ($result["fixed_not_collectable_comercial_curr_due"] ?? 0) +
                                                            ($result["fixed_not_collectable_instit_curr_due"] ?? 0) +
                                                            ($result["fixed_not_collectable_indust_curr_due"] ?? 0)
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?= round(
                                                        (($result["fixed_not_collectable_resident_old_due"] ?? 0) + ($result["fixed_not_collectable_resident_curr_due"] ?? 0)) +
                                                            (($result["fixed_not_collectable_comercial_old_due"] ?? 0) + ($result["fixed_not_collectable_comercial_curr_due"] ?? 0)) +
                                                            (($result["fixed_not_collectable_instit_old_due"] ?? 0) + ($result["fixed_not_collectable_instit_curr_due"] ?? 0)) +
                                                            (($result["fixed_not_collectable_indust_old_due"] ?? 0) + ($result["fixed_not_collectable_indust_curr_due"] ?? 0))
                                                    ); ?>
                                                </td>
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