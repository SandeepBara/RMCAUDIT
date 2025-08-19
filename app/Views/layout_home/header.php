<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SUDA-JH</title>
    <link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css"> -->
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/nifty_home.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <!-- <link href="<?=base_url();?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/plugins/pace/pace.min.js"></script> -->
    <script >
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,hi', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: true}, 'google_translate_element');
        }
    </script>
    <script  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        .nav {
            height: 40px;
            width: 100%;
            background-color: #0f1151;
            position: relative;
            z-index: 33;
        }

        .nav > .nav-header {
            display: inline;
        }

        .nav > .nav-header > .nav-title {
            display: inline-block;
            font-size: 15px;
            color: #fff;
            padding: 10px 10px 10px 10px;
        }

        .nav > .nav-btn {
            display: none;
        }

        .nav > .nav-links {
            display: inline;
            float: right;
            font-size: 18px;
        }

        .nav > .nav-links > a {
            display: inline-block;
            padding: 9px 10px 10px 10px;
            text-decoration: none;
            color: #efefef;
        }

        .nav > .nav-links > a:hover {
            /* background-color: rgba(0, 0, 0, 0.3); */
            color:#ff0000;
        }

        .nav > #nav-check {
            display: none;
        }

        @media (max-width:600px) {
            .nav > .nav-btn {
                display: inline-block;
                position: absolute;
                right: 0px;
                top: 0px;
            }
            .nav > .nav-btn > label {
                display: inline-block;
                width: 50px;
                height: 50px;
                padding: 10px;
            }
            .nav > .nav-btn > label:hover,.nav  #nav-check:checked ~ .nav-btn > label {
                background-color: rgba(0, 0, 0, 0.3);
            }
            .nav > .nav-btn > label > span {
                display: block;
                width: 25px;
                height: 10px;
                border-top: 2px solid #eee;
            }
            .nav > .nav-links {
                position: absolute;
                display: block;
                width: 100%;
                background-color: #333;
                height: 0px;
                transition: all 0.3s ease-in;
                overflow-y: hidden;
                top: 50px;
                left: 0px;
            }
            .nav > .nav-links > a {
                display: block;
                width: 100%;
            }
            .nav > #nav-check:not(:checked) ~ .nav-links {
                height: 0px;
            }
            .nav > #nav-check:checked ~ .nav-links {
                height: calc(100vh - 50px);
                overflow-y: auto;
            }
            }

        #logo_with_title_container {
            padding-top: 5px;
            padding-left: 40px;
            padding-right: 110px;
            padding-bottom: 5px;
        }
        #img_logo {
            width: 70px;
            height: 70px;
            left: 0;
        }
        .img_title {
            font-size: 24px;
        }
        #second_header {
            display: block;
        }
        #first_heading {
            padding: 5px 140px;
        }
        /* 48em = 768px */

        @media (min-width: 48em) {
            .header_menu li {
                float: left;
            }
            .header_menu li a {
                padding: 20px 30px;
            }
            .header_menu .menu {
                clear: none;
                float: right;
                max-height: none;
            }
            .header_menu .menu-icon {
                display: none;
            }
            
        }
        @media(max-width: 768px){
            #logo_with_title_container {
                padding-top: 5px;
                padding-left: 20px;
                padding-right: 20px;
                padding-bottom: 5px;
            }
            #img_logo {
                width: 66px;
                height: 66px;
            }
            .img_title {
                font-size: 18px;
            }
            #second_header {
                display: none;
            }
            #first_heading {
                padding: 5px 20px;
                font-size: 10px;
            }
        }
        @media print {
            #chat-bot-avatar-text-inner {
                visibility: hidden;
            }
        }

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
            border: 1px solid rgb(0 0 0 / 39%);
        }
        #footer{
            height: 150px;
        }
        .link{
            display: inline-block;
        }
        .link li {
            display: inline-block;
            padding: 0;
            font-size: 13px;
            color: #1e157d;
        }
        .link li a{
            display: inline-block;
            padding: 0 8px;
            font-size: 13px;
            color: #bfb8d5;
        }
        #footer{
            background: #0f1151 !important;
            color: #bfb8d5 !important;
        }
        @media screen and (min-width: 700px){
           .flex-viewport .treasure-gallery-card
           {
                float: left;
           }
        }
        #footer{
            position: relative;
        }
        #footer .hide-fixed{
            float:unset !important;
            text-align:right;
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

<style>
	
	.card{
		border-bottom: 6px solid #7a7ee7;
		border-right: 6px solid #7a7ee7;
		border-radius: 35px;
		border-bottom-right-radius: 3px;
		/* padding: 30px; */
		/* padding-left: 5px !important; */
	} 

	.card-btn{
		background: #0f1151;
		border: 0;
		color: #ffffff;
		width: 90%;
		font-weight: bold;
		border-radius: 20px;
		height: 24px;
		transition: all 0.2s ease;
		text-align: center;
		padding: 2px 12px;
	}

	.card-btn:hover {
    	background: #ffffff;
		color:#0f1151;
	}

	.card-btn:focus {
        background: #0f1151;
        outline: 0;  
	}
	
	.card-img:hover > img {
        transform: scale(1.2);
	}

	.card-img img {
		padding: 10px;
		margin-top: 15px;
		margin-bottom: 10px;
		transition: 0.4s ease;
		cursor: pointer;
		/* border: 2px solid red;
    	border-radius: 135px; */
	}
 
</style>

<style>
	#container_carousel {
		background: white;
		height: 400px;
		padding-bottom: 0px;
	}

	#myCarousel img {
		height: 400px;
	}

	@media(max-width: 858px) {
		#container_carousel {
			height: 200px;
		}

		#myCarousel img {
			height: 200px;
		}

		#left_image {
			display: none;
		}

		#right_image {
			display: none;
		}
	}

	@media(max-width: 770px) {
		#aatambhart {
			display: none;
		}

		#honerperson {
			display: none;
		}
	}

	.new-border{
		border-bottom: 6px solid #7a7ee7 !important;
    	border-right: 6px solid #7a7ee7 !important;
    	border-radius: 15px !important;
	}
</style>

<style>
	@media only screen and (max-width: 600px) {
        .panel{
            padding:0px;
        }
        .cardnew{
            margin-right:0px;
            margin-top:20px;
        }
    }
    .cardnew {
        /* background: #fff; */
        border-radius: 4px;
        box-shadow: 10px 10px 0px rgba(34, 35, 58, 0.5);
        max-width: 400px;
        display: flex;
        flex-direction: row;
        border-radius: 25px;
        position: relative;
        margin-right: 15px;
    }                                   
    .cardnew h2 {
        margin: 0;
        padding: 0 1rem;
    }
    .cardnew .title {
        /* padding: 0.5rem; */
        text-align: center;
        color: #0f1151;
        font-weight: bolder;
        font-size: 17px;
        font-family: verdana;
    }
    .cardnew .desc {
        padding: 1rem 1rem;
        font-size: 12px;
        font-family:verdana;
        text-align: -webkit-match-parent;
    }

    .cardnew .desc a{
        color: #000000;
        font-size: 12px;
        font-family: cursive;
        font-weight: 900;
    }
    .cardnew .desc a:hover{
            color: #d90000;
        }
    .cardnew .actions {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        align-items: center;
        padding: 0.5rem 1rem;
    }
    .cardnew svg {
        width: 85px;
        height: 85px;
        margin: 0 auto;
    }

    .img-avatar {
        width: 80px;
        height: 80px;
        position: absolute;
        border-radius: 50%;
        /* border: 4px solid white; */
        /* background-image: linear-gradient(-60deg, #0f1151 0%, #ff0000 100%); */
        top: 30px;
        left: 15px;
        display:block;
    }

    .img-avatar img{
        vertical-align: middle;
        /* opacity: 100%; */
        width: 125px;
    }

    .cardnew-text {
        display: grid;
        grid-template-columns: 1fr 2fr;
    }

    .title-total {
        padding: 2em 1em 1em 1em;
    }

    path {
        fill: white;
    }

    .img-portada {
        width: 100%;
    }

    .portada {
        width: 120%;
        height: 100%;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        background-image: url("<?=base_url()?>/public/assets/img/icons/bg_victor.png");
        opacity: 25%;
        background-position: bottom center;
        background-size: cover;
    }

    button {
        border: none;
        background: none;
        font-size: 24px;
        color: #8bc34a;
        cursor: pointer;
        transition:.5s;
    }

	@media only screen and (max-width: 600px){
		.portada{
			width:85%;
		}
		.img-avatar img{
			width:85px;
		}
	}
</style>
<style>
                .marquee {
                    width: 100%;
                    margin: 0 auto;
                    white-space: nowrap;
                    overflow: hidden;
                    box-sizing: border-box;
                    padding: 6px;
                }

                .marquee span {
                    display: inline-block;
                    padding-left: 100%;
                    text-indent: 0;
                    animation: marquee 40s linear infinite;
                }

                .marquee span:hover {
                    animation-play-state: paused
                }

                /* Make it move */
                @keyframes marquee {
                    0%   { transform: translate(0, 0); }
                    100% { transform: translate(-100%, 0); }
                }
            </style>
</head>
<body>
    <div id="loadingDiv" style="background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
    <div id="container">
        <!--NAVBAR-->
        <div class="panel pad-no mar-no noprint">
            <div class="row">
                <div class="col-md-12" id="first_heading" style="background: linear-gradient(130deg, rgb(92 120 159) 0%, rgba(22,9,121,1) 35%, rgb(92 120 159) 100%);">
                    <div class="row">
                        <div class="col-md-3 col-xs-6" style="color:white;font-family: verdana;">
                            Welcome GUEST | <a href="<?=base_url();?>/Login" style="color:white;">Official Login</a>
                        </div>
                        <div class="col-md-3 col-xs-6" id="front_show_date_time" style="text-align: right;color:white;font-family: verdana;">
                            <!-- 14 September 2020, 17:55:12 -->
                        </div>
                        <div class="col-md-2 col-xs-6"  style="text-align: right;color:white;font-family: verdana;">
                            <div class="phone" style="padding-top:4px;margin-left:0px;">
                                <a href="javascript:increaseFontSize();" style="color:#ffffff; padding-right:5px;" class="whitefo">+A</a>
                                | <a href="#" id="normal" style="color:#ffffff;padding-right:5px;" class="whitefo">A</a>
                                | <a href="javascript:decreaseFontSize();" style="color:#ffffff;padding-right:5px;" class="whitefo">-A</a>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6"  style="text-align: right;color:white;font-family: verdana;">
                            <a href="/home/screenreader" class="whitefo" target="_self" title="Screen Reader Access" style="color:#ffffff;"><i class="fa fa-desktop" aria-hidden="true"></i>Screen Reader</a>
                        </div>
                        <div class="col-md-2 col-xs-6"  style="text-align: right;color:white;font-family: verdana;">
                            <div id="google_translate_element"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel pad-no mar-no noprint">
            <div class="row">
                <div class="col-md-9 col-xs-12" id="logo_with_title_container" style="background-image: url('<?=base_url();?>/public/assets/img/bg.png');">
                    <div class="col-md-1 col-xs-3 pull-left">
                        <img id="img_logo" src="<?=base_url();?>/public/assets/img/logo1.png" alt="Sample Image">
                        
                    </div>
                    <div class="col-md-11 col-xs-9">
                        <p class="text-light img_title" style="font-family:verdana;margin:0 !important;font-family: verdana;"> Ranchi Municipal Corporation </p>
                        <p class="text-light img_title" style="font-family:verdana;margin:0 !important;font-family: verdana;"> रांची नगर निगम </p>
                    </div>
                </div>
                <div class="col-md-3" id="second_header" style="padding-top: 15px;">
                    <div class="row">
                        <div class="col-md-12" style="font-size: 15px; margin-bottom: 5px;font-family: verdana;"><i class="fa fa-user"></i> &nbsp;&nbsp;Welcome Guest</div>
                        <br >
                        <div class="col-md-12" style="font-size: 15px;font-family: verdana;"><a href="<?=base_url();?>/Login"><i class="fa fa-users"></i> &nbsp;Official Login</a></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="nav noprint">
            <input type="checkbox" id="nav-check">
            <div class="nav-header">
            <div class="nav-title">
                <a class="text-light" href="<?=base_url();?>/" style="font-family:verdana;">
                    <i class="fa fa-home"></i>
                    <?= cGetCookie("heading_title")!=""?cGetCookie("heading_title") :'Home' ?>
                </a>
            </div>
            </div>
            <div class="nav-btn">
                <label for="nav-check">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
            </div>

            <!-- <div class="nav-links">
                <a href="<?=base_url();?>/public/apk/sudatms.apk" style="font-family:verdana;" >Downloads APK</a> -->
                <!-- <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('CitizenProperty/taxCollector');?>">Know Your Tax Collector</a> -->
            <!-- </div> -->
        </div>
        <!--END NAVBAR-->
        <div class="boxed">
            <!--CONTENT CONTAINER-->
            