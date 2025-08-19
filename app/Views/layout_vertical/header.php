<!DOCTYPE html>
<html lang="en">
<?php $session = session(); ?>
<?php
$uri = clone \Config\Services::request()->uri;
$uriarr = explode("/", $uri->getPath());
$className = strtoupper($uriarr[0]);
$function = strtoupper($uriarr[1] ?? "");
$session = session();
$CMethods = ($session->get("CMethods") ?? "");
$client = new \Predis\Client();
$filePermession = $client->get("filePermession_" . $session->get("emp_details")["user_type_mstr_id"]);
if ($filePermession) {
    $filePermession = json_decode($filePermession, true);
    $functionArr = array_map(function ($val) {
        return strtoupper($val["function_name"]);
    }, $filePermession[$className] ?? []);
    // print_var($filePermession[$className]);
    if ($session->get("emp_details")["user_type_mstr_id"]!=1 && (!isset($filePermession[strtoupper($className)]) || (!in_array($function, $functionArr ?? [])))) {

        // die("you are not allowed to access this page!!!!....");
    }
}

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Municipal Corporation</title>
    <link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/common.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>
    <style>
        body {
            color: #000000;
        }
        .form-control {
            color: #000000;
            border: 1px solid rgb(0 0 0 / 39%);
        }
        #container .table th{
            color: #000000;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000;
        }
        @keyframes blink {
            0%, 100% {
            opacity: 1;
            }
            50% {
            opacity: 0;
            }
        }

        .blink {
            animation: blink 2.5s infinite; /* Blinks every second */
        }
        .stars-blink{
            background: radial-gradient(circle, red, #8bff008c, #d700ff03);

        }
    </style>
</head>
<body>
    
    <div id="loadingDiv" style="background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
        <!--NAVBAR-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <div class="navbar-header"><!--Brand logo & name-->
                    <a href="<?=base_url();?>/Dashboard/welcome" class="navbar-brand">
					
                        <img src="<?php echo base_url('public/assets/img/logo1.png');?>" alt="Nifty Logo" class="brand-icon" style="height:40px; width:40px; margin-left:12px; margin-top:10px;">
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

                        if($session->has('emp_details'))
                        {
                            $emp_details = $session->get('emp_details');
                            if(sizeof($emp_details['ulb_list'])>1)
                            {
                                if($session->has('ulb_dtl'))
                                {
                                    $get_ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
                                }
                                ?>
                                <select id="select_ulb_mstr_id" class="form-control" style="width: 220px;">
                                    <!-- <option value="">SELECT ULB</option> -->
                                    <?php
                                    foreach ($emp_details['ulb_list'] as $ulbList)
                                    {
                                        ?>
                                        <option value="<?=md5($ulbList['ulb_mstr_id']);?>" <?=(isset($get_ulb_mstr_id))?($ulbList['ulb_mstr_id']==$get_ulb_mstr_id)?"selected":"":"";?> ><?=strtoupper($ulbList['ulb_name']);?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <?php
                            }
                            else
                            {
                                //print_r($_SESSION['ulb_dtl']);
                                ?>
                                <h4 style="color: white;"><?=$_SESSION['ulb_dtl']['ulb_name'];?></h4>
                                <?php
                            }
                        }
                        ?>
                            </div>
                        </li><!--End Search-->
                    </ul>
                    <ul class="nav navbar-top-links">
                        <!--Notification dropdown-->
                        <li class="dropdown" title="Notification">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="demo-pli-bell"></i>
                                <div id="bell"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list noti_list" id="my_div">

                                        </ul>
                                    </div>
                                </div>

                                <div class="pad-all bord-top">
                                    <!--<a href="<?//=URLROOT;?>/NotificationController/AllNotificationList" class="btn-link text-main box-block">
                                        <i class="pci-chevron chevron-right pull-right"></i>Show All Notifications
                                    </a>-->
                                </div>
                            </div>
                        </li>
                        <!--End notifications dropdown-->

                        <!--User dropdown-->
                        <li id="dropdown-user" class="dropdown" title="User">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <i class="demo-pli-male"></i>
                                </span>
                            </a>
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
                        <li title="Tab Setting">
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


                        <!-- The Modal Cancel Invoice-->




        <div class="boxed">
