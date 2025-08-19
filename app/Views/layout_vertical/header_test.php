<?php $session = session(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Municipal Corporation</title>
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
</head>
<body>
    <div id="loadingDiv" style="display: none; background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
        <!--NAVBAR-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <div class="navbar-header"><!--Brand logo & name-->
                    <a href="<?=base_url();?>/Dashboard" class="navbar-brand">
                        <img src="<?=base_url();?>/public/assets/img/jharkhand_sarkar_logo.PNG" alt="Nifty Logo" class="brand-icon" style="height:40px; width:40px; margin-left:12px; margin-top:10px;">
                        <div class="brand-title">
                            <span class="brand-text">JHARKHAND</span>
                        </div>
                    </a>
                </div><!--End brand logo & name-->

                <!--Navbar Dropdown-->
                <div class="navbar-content">
                    <ul class="nav navbar-top-links">
                        <li class="tgl-menu-btn"><!--Navigation toogle button-->
                            <a class="mainnav-toggle" href="#">
                                <i class="demo-pli-list-view"></i>
                            </a>
                        </li> <!--End Navigation toogle button-->
                        <li><!--Search-->
                            <div class="custom-search-form">
                        <?php

                        if($session->has('emp_details')){
                            $emp_details = $session->get('emp_details');
                            if(sizeof($emp_details['ulb_list'])!=1){
                                if($session->has('ulb_dtl')){
                                    $get_ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                }
                        ?>
                                <select id="select_ulb_mstr_id" class="form-control">
                                    <option value="">SELECT ULB</option>
                        <?php
                                foreach ($emp_details['ulb_list'] as $ulbList) {
                        ?>
                                <option value="<?=$ulbList['ulb_mstr_id'];?>" <?=(isset($get_ulb_mstr_id))?($ulbList['ulb_mstr_id']==$get_ulb_mstr_id)?"selected":"":"";?> ><?=$ulbList['short_ulb_name'];?></option>
                        <?php
                                }
                        ?>
                            </select>
                        <?php
                            }
                        }
                        ?>
                            </div>
                        </li><!--End Search-->
                    </ul>
                    <ul class="nav navbar-top-links">
                        <!--Notification dropdown-->
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="demo-pli-bell"></i>
                                <div id="bell">
                              
                            </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list noti_list" id="my_div">
                                            
                                        </ul>
                                    </div>
                                </div>

                                <div class="pad-all bord-top">
                                  <a href="<?//=URLROOT;?>/NotificationController/AllNotificationList" class="btn-link text-main box-block">
                                        <i class="pci-chevron chevron-right pull-right"></i>Show All Notifications
                                    </a>
                                </div>
                            </div>
                        </li>
                        <!--End notifications dropdown-->

                        <!--User dropdown-->
                        <li id="dropdown-user" class="dropdown">
                            <div>
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                    <span class="ic-user pull-right">
                                        <i class="demo-pli-male"></i>
                                    </span>
                                </a>
                            </div>


                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="<?=base_url();?>/Profile/profileDetails"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>
                                    </li>
									<li>
                                        <a href="<?=base_url();?>/ChangePassword/changePassword"><i class="demo-pli-pencil icon-lg icon-fw"></i> Change Password</a>
                                    </li>
                                    <li>
                                        <a href="<?=base_url();?>/Login/logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--End user dropdown-->
                        <li>
                            <a href="#" class="aside-toggle">
                                <i class="demo-pli-dot-vertical"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--End Navbar Dropdown-->
            </div>
        </header>
        <!--END NAVBAR-->


