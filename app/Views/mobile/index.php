<?=$this->include("layout_mobi/header");?>
<style>
    .buttonA {
        border: none;
        color: white;
        padding: 10px 25px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
    }
    .buttonx{
        width:150px;
        height:45px;
        border:none;
        outline:none;
        box-shadow:-4px 4px 5px 0 #46403a;
        color:#fff;
        font-size:14px;
        text-shadow:0 1px rgba(0,0,0,0.4);
        background-color:#25476a;
        border-radius:3px;
        font-weight:700
    }
    .buttonx:hover{
        background-color:#FF8000;
        color:#fff;
        cursor:pointer
    }
    .buttonx:active{
        margin-left:-4px;
        margin-bottom:-4px;
        padding-top:2px;
        box-shadow:none
    }
</style>
    <!--CONTENT CONTAINER-->
    <div id="content-container">
        <!--Page content-->
        <div id="page-content">
            <div class="row">
                <?php
                # Agency TC
                //if($user_type_mstr_id == 5)
                if(in_array($user_type_mstr_id,[5,4]))
                {
                    ?>
                    <a href="<?=base_url('Mobi/mobileMenu/property');?>">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-info panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-building fa-4x"></i></span>
                                    <p><b>Property</b></p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?=base_url('WaterMobileIndex/index');?>">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-success panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-tint fa-4x"></i></span>
                                    <p><b>Water</b></p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?=base_url('Mobi/mobileMenu/trade');?>">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-purple panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-trademark fa-4x"></i></span>
                                    <p><b>Trade</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-warning panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-adn fa-4x"></i></span>
                                    <p><b>Advertisment</b></p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?=base_url('visiting_dtl/visit_details');?>">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-danger panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-address-card fa-4x"></i></span>
                                    <p><b>Visting Detail</b></p>
                                </div>
                            </div>
                        </div>
                    </a>


                    <?php
                }
                # ULB TC
                if($user_type_mstr_id == 7)
                {
                    ?>
                    <a href="<?=base_url('SafVerification/field_verification_list');?>" onclick="(function(){ document.getElementById('b_field_verification').innerHTML='Page is loading'; })();">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-success panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
                                    <p><b id="b_field_verification">Field Verification</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="<?=base_url('SafVerification/field_verification_list_new');?>" onclick="(function(){ document.getElementById('b_new_field_verification').innerHTML='Page is loading'; })();">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-warning panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
                                    <p><b id="b_new_field_verification">New Field Verification</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo base_url('WaterHarvestingTC/UTCList') ?>">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-mint panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-tint fa-4x"></i></span>
                                    <p><b>Water Harvesting Field Verification</b></p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?=base_url('SafVerification/objectionMail');?>" onclick="(function(){ document.getElementById('b_verification_a_o').innerHTML='Page is loading'; })();">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="panel panel-purple panel-colorful">
                                <div class="pad-all text-center">
                                    <span class="text-x text-thin"><i class="fa fa-file-image-o fa-4x"></i></span>
                                    <p><b id="b_verification_a_o">Verification (Against Objection)</b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                # Tax Daroga
                if($user_type_mstr_id == 20)
                {
                    ?>
                    <a href="<?php echo base_url('Trade_SI/mobiIndex');?>">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                <div class="panel panel-success panel-colorful">
                                    <div class="pad-all text-center">
                                        <span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
                                        <p><b>(Trade) TD Inbox</b></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <!--  <a href="<?php //echo base_url('trade_SI/mobiIndex');?>">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                <div class="panel panel-info panel-colorful">
                                    <div class="pad-all text-center">
                                        <span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
                                        <p><b>TD outbox</b></p>
                                    </div>
                                </div>
                            </div>
                        </a> -->
                    <?php
                }
                #Junior Engineer
                if($user_type_mstr_id == 13)
                {
                    ?>
                    <a href="<?php echo base_url('WaterfieldSiteInspection/search_consumer_for_siteInspection/mobile');?>">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                <div class="panel panel-success panel-colorful">
                                    <div class="pad-all text-center">
                                        <span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
                                        <p><b>JE Inbox</b></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- <a href="<?php echo base_url('water_da/forward_list2/mobile');?>">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                <div class="panel panel-info panel-colorful">
                                    <div class="pad-all text-center">
                                        <span class="text-x text-thin"><i class="fa fa-id-card-o fa-4x"></i></span>
                                        <p><b>JE outbox</b></p>
                                    </div>
                                </div>
                            </div>
                        </a> -->
                    <?php
                }
                ?>
                <a href="<?=base_url('mobiChngPass/changePwd');?>">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                        <div class="panel panel-info panel-colorful">
                            <div class="pad-all text-center">
                                <span class="text-x text-thin"><i class="fa fa-edit fa-4x"></i></span>
                                <p><b>Change Password</b></p>
                            </div>
                        </div>
                    </div>
                </a>

           <?php ?>
            </div>

            <!--End page content-->
        </div>
    </div>
    <!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
