<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SUDA-JH</title>
    <link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/public/assets/css/nifty_mobi.min.css" rel="stylesheet">
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <!--Demo [ DEMONSTRATION ]-->
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo.min.css" rel="stylesheet">
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="<?=base_url();?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/plugins/pace/pace.min.js"></script>

    <!--[ OPTIONAL ]-->
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
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
        .btn-success, .btn-success:focus{
            background-color:#92c755 !important;
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
    </style>
</head>
<script type="text/javascript">
    // window.history.forward();
    // function noBack() { window.history.forward(); }
</script>
<body>
    <div id="loadingDiv" style=" background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
    <div id="container" class="effect aside-float aside-bright mainnav-lg">
        <!--NAVBAR-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <!--Navbar Dropdown-->
                <div class="navbar-content">
					
					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
							<a href="<?=base_url();?>/Login/mobilogout">
								<button type="button" class="btn btn-danger" style="margin-top: 10px;margin-left:10px;float:left;" >LOGOUT</button>
							</a>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="float:right;margin-right:30px;">
							<a href="<?=base_url();?>/mobi/home"> 
								<button type="button" class="btn btn-success btn_wait_load" style="margin-top: 10px;">HOME</button>
							</a>
						</div>
						<!--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="float:right;margin-right:0px;">
							<a href="<?=base_url();?>/mobi/home"> 
								<button type="button" class="btn btn-warning" style="margin-top: 10px;"><i class="demo-pli-bell icon-lg icon-fw"></i><b style="color:red;">1</b></button>
							</a>
						</div> -->
					</div>
					
						
                    <!--<ul class="nav navbar-top-links">
                        
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="demo-pli-bell"></i>
                                <span class="badge badge-header badge-danger"></span>
                            </a>
                            
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list">
                                            <li>
                                                <a href="#" class="media add-tooltip" data-title="Used space : 95%" data-container="body" data-placement="bottom">
                                                    <div class="media-left">
                                                        <i class="demo-pli-data-settings icon-2x text-main"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="text-nowrap text-main text-semibold">HDD is full</p>
                                                        <div class="progress progress-sm mar-no">
                                                            <div style="width: 95%;" class="progress-bar progress-bar-danger">
                                                                <span class="sr-only">95% Complete</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                               
                                <div class="pad-all bord-top">
                                    <a href="#" class="btn-link text-main box-block">
                                        <i class="pci-chevron chevron-right pull-right"></i>Show All Notifications
                                    </a>
                                </div>
                            </div>
                        </li>
                       
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <i class="demo-pli-male"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="#"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="badge badge-danger pull-right">9</span><i class="demo-pli-mail icon-lg icon-fw"></i> Messages</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="label label-success pull-right">New</span><i class="demo-pli-gear icon-lg icon-fw"></i> Settings</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="demo-pli-computer-secure icon-lg icon-fw"></i> Lock screen</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('Login/mobilogout');?>"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                       
                        <li>
                            <a href="#" class="aside-toggle">
                                <i class="demo-pli-dot-vertical"></i>
                            </a>
                        </li>
                    </ul> -->
                </div>
                <!--End Navbar Dropdown-->
            </div>
        </header>
        <!--END NAVBAR-->
    <div class="boxed">
            <!--CONTENT CONTAINER-->
