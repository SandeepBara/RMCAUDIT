<?= $this->include('layout_vertical/header'); ?>
<!--Page content-->
<div id="content-container">
    <div id="page-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-base">
                    <!--Tabs Content-->
                    <div class="tab-content">
                        <div id="demo-lft-tab-1" class="tab-pane fade active in">
                            <div class="row">
                                <!-- Property DCB Current Finacial Year -->
                                <div class="col-md-4 shadow-lg">
                                    <div class="panel panel-info  media middle pad-all p-3"
                                        style="background-color: #03A9F4; box-shadow:5px 5px 5px gray !important;color:white">
                                        <div class="media-left">
                                            <div class="pad-hor">
                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-2x mar-no text-semibold">
                                                <?= in_num_format(round($propReport["total_demand"], 2)); ?>
                                            </p>
                                            <p class="mar-no">Total Demand</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-mint media middle pad-all"
                                        style="background-color: #26A69A;box-shadow:5px 5px 5px gray !important;color:white">
                                        <div class="media-left">
                                            <div class="pad-hor">
                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-2x mar-no text-semibold">
                                                <?= in_num_format(round($propReport['current_demand'], 2)) ?>
                                            </p>
                                            <p class="mar-no">Current Year Demand</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-warning media middle pad-all"
                                        style="background-color: #FFB300;box-shadow:5px 5px 5px gray !important;color:white">
                                        <div class="media-left">
                                            <div class="pad-hor">
                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-2x mar-no text-semibold">
                                                <?= in_num_format(round($propReport['arrear_demand'], 2)) ?>
                                            </p>
                                            <p class="mar-no">Arrear Due Demand</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="padding: 10px;">
                                <div class="col-md-4 shadow-lg">
                                        <div class="panel panel-info  media middle pad-all p-3"
                                            style="background-color: #03A9F4; box-shadow:5px 5px 5px gray !important;color:white">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold">
                                                    <?= in_num_format(round($propReport["current_collection"]+$propReport["arrear_collection"], 2)); ?>
                                                </p>
                                                <p class="mar-no">Total Collection</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 shadow-lg">
                                        <div class="panel panel-info  media middle pad-all p-3"
                                            style="background-color: #03A9F4; box-shadow:5px 5px 5px gray !important;color:white">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold">
                                                    <?= in_num_format(round($propReport["current_collection"], 2)); ?>
                                                </p>
                                                <p class="mar-no">Current Year Collection</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 shadow-lg">
                                        <div class="panel panel-info  media middle pad-all p-3"
                                            style="background-color: #03A9F4; box-shadow:5px 5px 5px gray !important;color:white">
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold">
                                                    <?= in_num_format(round($propReport["arrear_collection"], 2)); ?>
                                                </p>
                                                <p class="mar-no">Arrear Collection</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PROPERTY START HERE -->
                            <div class="row">
                                <div class="col-md-12" style="padding: 10px;">
                                    <div class="panel panel-bordered-dark bg-white" style="padding: 10px;">
                                        <!-- Panel Property 2022 -->
                                        <div class="panel-heading" style="background-color: #25476A;margin-bottom:10px">
                                            <h3 class="panel-title" style="color:white">Current Financial Year
                                                <?= getFY(); ?>(PROPERTY)
                                            </h3>
                                        </div>
                                        <div class="panel-body pad-no">
                                            <div class="row">
                                                <!-- Property Total SAF 2024 -->
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="panel pos-rel"
                                                        style="background-color: #3991db;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                        <div class="media" style="padding: 30px 0px 30px 0px;">
                                                            <div class="media-body text-center">
                                                                <a href="<?php echo base_url('MiniDashboard/wardWiseSafCurrentFy' . '/All'); ?>"
                                                                    class="box-inline text-light">
                                                                    <span
                                                                        class="text-2x text-semibold"><?= $property_pending_at_level_current_year["current_year_total_saf"] ? array_sum(array_column($property_pending_at_level_current_year["current_year_total_saf"], 'total_count')) : 0; ?></span>
                                                                    <br />
                                                                    <span class="text-lg text-semibold">Total SAF</span>

                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php foreach ($property_pending_at_level_current_year["current_year_total_saf"] ?? [] as $current_fy_total_saf) { ?>

                                                    <?php if ($current_fy_total_saf["assessment_type"] != 'Mutation with Reassessment') { ?>
                                                        <div class="col-sm-3 col-md-3">
                                                            <div class="panel pos-rel"
                                                                style="background-color: #8f39da; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px;">
                                                                <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                    <div class="media-body text-center">
                                                                        <a href="<?php echo base_url('MiniDashboard/wardWiseSafCurrentFy' . '/' . $current_fy_total_saf["assessment_type"]); ?>"
                                                                            class="box-inline text-light">

                                                                            <span class="text-2x text-semibold">
                                                                                <?= $current_fy_total_saf["total_count"]; ?></span>
                                                                            <br />
                                                                            <span
                                                                                class="text-lg text-semibold"><?= $current_fy_total_saf["assessment_type"]; ?></span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($current_fy_total_saf["assessment_type"] == 'Mutation with Reassessment') { ?>
                                                        <div class="col-sm-3 col-md-3">
                                                            <div class="panel pos-rel"
                                                                style="background-color: #8f39da; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                                <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                    <div class="media-body text-center">
                                                                        <a href="<?php echo base_url('MiniDashboard/wardWiseSafCurrentFy' . '/' . $current_fy_total_saf["assessment_type"]); ?>"
                                                                            class="box-inline text-light">

                                                                            <span class="text-2x text-semibold">
                                                                                <?= $current_fy_total_saf["total_count"]; ?></span>
                                                                            <br />
                                                                            <span
                                                                                class="text-lg text-semibold"><?= $current_fy_total_saf["assessment_type"]; ?></span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>

                                                <div class="row">
                                                    <!-- TOGAL GEO TAG DONE -->
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="panel pos-rel"
                                                            style="background-color: #dadb38; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                            <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                <div class="media-body text-center">
                                                                    <a href="#" class="box-inline">
                                                                        <span
                                                                            class="text-2x text-semibold text-main"><?= $geotag['count'] ?? 0 ?></span>
                                                                        <br />
                                                                        <span class="text-lg text-semibold">Total Geo
                                                                            Tag
                                                                        </span>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- DEFAULTER LIST -->
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="panel pos-rel"
                                                            style="background-color: #dc673b; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                            <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                <div class="media-body text-center">
                                                                    <a href="<?php echo base_url('propDtl/defaulterNoticesDtl'); ?>"
                                                                        class="box-inline text-light">
                                                                        <span
                                                                            class="text-2x text-semibold"><?= $notice['total_remaining_defaulter'] ?? 0 ?></span>
                                                                        <br />
                                                                        <span class="text-lg text-semibold">Total
                                                                            Defaulter</span>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- TOTAL NOTICE GENERATED -->
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="panel pos-rel"
                                                            style="background-color: #dadb38; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                            <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                <div class="media-body text-center">
                                                                    <a href="<?php echo base_url('propDtl/defaulterNoticesDtl'); ?>"
                                                                        class="box-inline text-dark">
                                                                        <span
                                                                            class="text-2x text-semibold text-main"><?= $notice['total_notice_generated'] ?? 0 ?></span>
                                                                        <br />
                                                                        <span class="text-lg text-semibold">Total Notice
                                                                            Generated</span>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- TOTAL NOTICE SERVED -->
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="panel pos-rel"
                                                            style="background-color: #26A69A;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                            <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                <div class="media-body text-center">
                                                                    <a href="<?php echo base_url('propDtl/defaulterNoticesDtl'); ?>"
                                                                        class="box-inline text-light">
                                                                        <span
                                                                            class="text-2x text-semibold"><?= $notice['total_notice_served'] ?? 0 ?></span>
                                                                        <br />
                                                                        <span class="text-lg text-semibold">Total Notice
                                                                            Served</span>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- TOTAL COLLECTION FROM NOTICE SERVED -->
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="panel pos-rel"
                                                            style="background-color: #FFB300;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                            <div class="media" style="padding: 30px 0px 30px 0px;">
                                                                <div class="media-body text-center">
                                                                    <a href="<?php echo base_url('propDtl/defaulterNoticesDtl'); ?>"
                                                                        class="box-inline">
                                                                        <span
                                                                            class="text-2x text-semibold text-main"><?= $notice['total_payment_received_from_notice'] ?? 0 ?></span>
                                                                        <br />
                                                                        <span class="text-lg text-semibold">No of
                                                                            Collection From Notice</span>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- TOTAL COLLECTION FROM NOTICE SERVED -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- PROPERTY END HERE -->

                            <!-- GBSAF START HERE -->
                            <div class="row">
                                <div class="col-md-12" style="padding: 10px;">
                                    <div class="panel panel-bordered-dark bg-white" style="padding: 10px;">
                                        <!-- Panel Property 2022 -->
                                        <div class="panel-heading"
                                            style="background-color: #25476A; margin-bottom:10px">
                                            <h3 class="panel-title" style="color:white">GBSAF Report
                                        </div>
                                    </div>
                                    <div class="panel-body pad-no">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #3991db; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-body text-center">
                                                            <a href="<?php echo base_url('govsafDetailPayment/govSafList'); ?>"
                                                                class="box-inline text-light" target="_blank">
                                                                <span
                                                                    class="text-2x text-semibold"><?= $gbsaf['govt_saf_count'] ?></span>
                                                                <br />
                                                                <span class="text-lg text-semibold">Total GBSAF</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- TOTAL GBSAF DEMAND -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #3991db; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['total_demand'], 2)) ?>
                                                            </p>
                                                            <p class="mar-no">Total GBSAF Demand</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- TOTAL GBSAF CURRENT DEMAND -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #f7f7f7;box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['current_demand'], 2)) ?>
                                                            </p>
                                                            <p class="mar-no">GBSAF Current Demand</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- GBSAF ARREAR DEMAND -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #db9939; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['arrear_demand']), 2) ?>
                                                            </p>
                                                            <p class="mar-no">GBSAF Arrear Demand</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                            <!-- CURRENT COLLECTION -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #db9939; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['current_collection']), 2) ?>
                                                            </p>
                                                            <p class="mar-no">Current Collection</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                            <!-- ARREAR COLLECTION -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #db9939; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['arear_collection'], 2)) ?>
                                                            </p>
                                                            <p class="mar-no">Arrear Collection</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- CURRENT BALANCE -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #db9939; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['current_demand'], 2)) ?>
                                                            </p>
                                                            <p class="mar-no">Current Balance</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Arrear BALANCE -->
                                            <div class="col-sm-6 col-md-4">
                                                <div class="panel pos-rel"
                                                    style="background-color: #db9939; box-shadow:5px 5px 5px gray !important;border:1px solid white;border-radius:10px; color:white;">
                                                    <div class="media" style="padding: 30px 0px 30px 0px;">
                                                        <div class="media-left">
                                                            <div class="pad-hor">
                                                                <i class="fa fa-inr" style="font-size: 35px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <p class="text-2x mar-no text-semibold">
                                                                <?= in_num_format(round($gbSafDCB['arrear_balance'], 2)) ?>
                                                            </p>
                                                            <p class="mar-no">Arrear Balance</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- GBSAF END EHRE -->


                            <!-- PENDING START HERE -->
                            <div class="row">
                                <div class="col-md-12" style="padding: 10px;">
                                    <div class="panel panel-bordered-dark bg-white" style="padding: 10px;">
                                        <!-- Panel Property 2022 -->
                                        <div class="panel-heading"
                                            style="background-color: #25476A; margin-bottom:10px">
                                            <h3 class="panel-title" style="color:white">PROPERTY PENDING REPORT</h3>
                                        </div>
                                    </div>
                                    <div class="panel-body pad-no">
                                        <div class="row">
                                            <!-- Property Pending Report 2022 -->
                                            <div class="col-sm-4 col-md-3">
                                                <div class="panel pos-rel">
                                                    <div class=""
                                                        style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
                                                        <div class="media pad-ver">
                                                            <div class="media-left">
                                                                <a href="<?php echo base_url('MiniDashboard/level_wardwise_for_currentfy' . '/' . md5(6)); ?>"
                                                                    class="box-inline"><img
                                                                        style="border:2px solid white;padding:5px"
                                                                        alt="Profile Picture" class="img-md img-circle"
                                                                        src="<?= base_url(); ?>/public/assets/img/Dealing%20Assistant.png"></a>
                                                            </div>
                                                            <div class="media-body pad-top">
                                                                <a href="<?php echo base_url('levelwisependingform/levelformdetail/' . md5('6')); ?>"
                                                                    class="box-inline" target="_blank">
                                                                    <span
                                                                        class="text-lg text-semibold text-main">Dealing
                                                                        Assistant</span>
                                                                    <button type="button"
                                                                        class="btn btn-lg btn-default"><i
                                                                            class="fa fa-bullhorn"></i>
                                                                        <?= $property_pending_at_level_current_year["no_of_pending_by_dealing_assistant"]; ?></button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-3">
                                                <div class="panel pos-rel">
                                                    <div
                                                        style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
                                                        <div class="media pad-ver">
                                                            <div class="media-left">
                                                                <a href="<?php echo base_url('MiniDashboard/level_wardwise_for_currentfy' . '/' . md5(7)); ?>"
                                                                    class="box-inline"><img
                                                                        style="border:2px solid white;padding:5px"
                                                                        alt="Profile Picture" class="img-md img-circle"
                                                                        src="<?= base_url(); ?>/public/assets/img/ULB Tax Collector.png"></a>
                                                            </div>
                                                            <div class="media-body pad-top">
                                                                <a href="<?php echo base_url('levelwisependingform/levelformdetail/' . md5('7')); ?>"
                                                                    target="_blank" class="box-inline">
                                                                    <span class="text-lg text-semibold text-main">ULB
                                                                        Tax
                                                                        Collector</span>
                                                                    <button type="button"
                                                                        class="btn btn-lg btn-default"><i
                                                                            class="fa fa-bullhorn"></i>
                                                                        <?= $property_pending_at_level_current_year["no_of_pending_by_ulb_tc"]; ?></button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-3">
                                                <div class="panel pos-rel">
                                                    <div
                                                        style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
                                                        <div class="media pad-ver">
                                                            <div class="media-left">
                                                                <a href="<?php echo base_url('MiniDashboard/level_wardwise_for_currentfy' . '/' . md5(9)); ?>"
                                                                    class="box-inline"><img
                                                                        style="border:2px solid white;padding:5px"
                                                                        alt="Profile Picture" class="img-md img-circle"
                                                                        src="<?= base_url(); ?>/public/assets/img/Property Section Incharge.png"></a>
                                                            </div>
                                                            <div class="media-body pad-top">
                                                                <a href="<?php echo base_url('levelwisependingform/levelformdetail/' . md5('9')); ?>"
                                                                    target="_blank" class="box-inline">
                                                                    <span
                                                                        class="text-lg text-semibold text-main">Section
                                                                        Incharge</span>
                                                                    <button type="button"
                                                                        class="btn btn-lg btn-default"><i
                                                                            class="fa fa-bullhorn"></i>
                                                                        <?= $property_pending_at_level_current_year["no_of_pending_by_section_incharge"]; ?></button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-3">
                                                <div class="panel pos-rel">
                                                    <div
                                                        style="background-color: #BAE6FD;padding:0px 5px 0px 10px;box-shadow:5px 5px 5px gray !important">
                                                        <div class="media pad-ver">
                                                            <div class="media-left">
                                                                <a href="<?php echo base_url('MiniDashboard/level_wardwise_for_currentfy' . '/' . md5(10)); ?>"
                                                                    class="box-inline"><img
                                                                        style="border:2px solid white;padding:5px"
                                                                        alt="Profile Picture" class="img-md img-circle"
                                                                        src="<?= base_url(); ?>/public/assets/img/Executive Officer.png">
                                                                </a>
                                                            </div>
                                                            <div class="media-body pad-top">
                                                                <a href="<?php echo base_url('levelwisependingform/levelformdetail/' . md5('10')); ?>"
                                                                    target="_blank" class="box-inline">
                                                                    <span
                                                                        class="text-lg text-semibold text-main">Executive
                                                                        Officer</span>
                                                                    <button type="button"
                                                                        class="btn btn-lg btn-default"><i
                                                                            class="fa fa-bullhorn"></i>
                                                                        <?= $property_pending_at_level_current_year["no_of_pending_by_executive_officer"]; ?></button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- PENDING END HERE -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer'); ?>