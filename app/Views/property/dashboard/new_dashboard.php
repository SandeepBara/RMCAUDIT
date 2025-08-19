<?= $this->include('layout_vertical/header'); ?>
<!--Page content-->
<div id="content-container">
    <div id="page-content">
        <div class="row">
            <div class="col-lg-12">

                <div class="tab-base">
                    <!--Nav Tabs-->

                    <div class="text-center">

                        <!--Tabs Content-->
                        <div class="tab-content">
                            <div id="demo-lft-tab-1" class="tab-pane fade active in">
                                <div class="row">
                                    <!-- PROPERTY CARD -->
                                    <div class="col-md-6 shadow-lg">
                                        <div class="card">
                                        <a href="<?= base_url() . '/MiniDashboard' . '/propertyDashboard' ?>">
                                            <div class="panel panel-info  media middle pad-all p-3"
                                                style="background-color: #03A9F4;box-shadow:5px 5px 5px gray !important;color:white;">
                                                <div class="media-left">
                                                    <div class="pad-hor">
                                                        <i class="fa fa-home" style="font-size: 35px;"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="text-2x mar-no text-semibold">
                                                        PROPERTY
                                                    </p>

                                                </div>
                                            </div>
                                        </a>
                                        </div>
                                        

                                    </div>

                                    <!-- WATER CARD -->
                                    <div class="col-md-6 shadow-lg">
                                        <a href="#">
                                            <div class="panel panel-info  media middle pad-all p-3"
                                                style="background-color: #03A9F4;box-shadow:5px 5px 5px gray !important;color:white">
                                                <div class="media-left">
                                                    <div class="pad-hor">
                                                        <i class="fa fa-tint" style="font-size: 35px;"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="text-2x mar-no text-semibold">
                                                        WATER
                                                    </p>

                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- TRADE CARD -->
                                    <div class="col-md-6 shadow-lg">
                                        <a href="#">
                                            <div class="panel panel-info  media middle pad-all p-3"
                                                style="background-color: #03A9F4;box-shadow:5px 5px 5px gray !important;color:white">
                                                <div class="media-left">
                                                    <div class="pad-hor">
                                                        <i class="fa fa-briefcase" style="font-size: 35px;"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <p class="text-2x mar-no text-semibold">
                                                        TRADE
                                                    </p>

                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                </div>

                            </div>
                            <!-- End Property -->
                            <!-- Start Trade  -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer'); ?>