<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SUDA-JH | Login</title>
    <link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="<?=base_url();?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/plugins/pace/pace.min.js"></script>
    <!--[ OPTIONAL ]-->
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0; 
        }
        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
        .cls-container{
            /* background-image: url("<?=base_url();?>/public/assets/img/jharkhand_map.png"); */
            background:linear-gradient(194deg, rgba(2,0,36,1) 0%, rgba(121,9,9,1) 45%, rgba(0,212,255,1) 100%);
            background-repeat: no-repeat, repeat;
            background-size: cover;
        }
        .panel-body{
            background-color: #ffffff94;
            border: 2px solid black;
            border-radius: 35px;
        }

        .btn{
            background-color: #0f1151;
            border-color: #ff0000 !important;
            color: #fff;
            border-radius: 35px;
        }
        .btn:hover{
            color: #000000 !important;
            background-color: #ffff !important;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div id="container" class="cls-container">
		<div id="bg-overlay"></div>
		<div class="cls-content">
		    <div class="cls-content-sm panel">
		        <div class="panel-body">
		            <div class="mar-ver pad-btm">
		                <!-- <h1 class="h1">LOGIN</h1> -->
		            </div>
		            <form method="post" action="<?=base_url('Login/index');?>">
                        <div class="form-group text-danger">
                         <?php
                            if(!empty($errMsg)){
                                echo $errMsg;
                            }
                        ?>
		                </div>
                        <input type="hidden" id="ip_address" name="ip_address" value="" />
		                <div class="form-group">
		                    <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Username" maxlength="50" value="<?=isset($_POST['user_name'])?$_POST['user_name']:''?>" autofocus />
		                </div>
                        <div class="input-group mar-btm">
                            <input type="password" id="user_pass" name="user_pass" class="form-control" maxlength="50" placeholder="Password" required />
                            <span class="input-group-addon" id="passToggle" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                        </div>
                        <div class="input-group mar-btm">
                            <span class="input-group-btn" id="loginCaptcha">
                                <img src='<?=loginCaptcha();?>' />
                            </span>
                            <span class="input-group-btn">
                                <i class="fa fa-refresh" style="font-size: 32px; margin-top: 10px; cursor: pointer;" onclick='function refrechLoginCaptcha() {$("#loginCaptcha").html("<h4>Please Wait...</h4>"); $.ajax({url: "<?=base_url()."/login/refrechLoginCaptcha";?>", success: function(result){ setCaptchaCode(result); }}); }; refrechLoginCaptcha();'></i>
                            </span>
                        </div>
                        <div class="form-group">
                            <input type="number" id="captcha_code" name="captcha_code" class="form-control" placeholder="captcha code" maxlength="4" autocomplete="off"  pattern="/^-?\d+\.?\d*$/" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" require />
                        </div>
		                <button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
		            </form>
		        </div>

		        <div class="pad-all">
		            <a href="<?=base_url();?>/Home/index" class="btn-link mar-rgt" style="color: white;font-size: 15px;font-weight: bold;">Back To Home</a>
		        </div>


                <p class="text-light">For password related query, Kindly mail to suda.goj@gmail.com  </p>
		    </div>
		</div>
    </div>
    <script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>
    <script src="<?=base_url();?>/public/assets/js/md5.js"></script>
    <script src="<?=base_url();?>/public/assets/otherJs/loginjs.js"></script>
</body>
</html>
<script>
let setCaptchaCode=(result)=>$("#loginCaptcha").html("<img src='"+result+"'/>"); 
</script>

