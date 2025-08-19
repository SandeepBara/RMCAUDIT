<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>RANCHI MUNICIPAL CORPORATION LOGIN</title>
    <link rel="icon" href="<?= base_url(); ?>/public/assets/img/favicon.ico">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link href="<?=base_url();?>/public/assets/css/common.css" rel="stylesheet">
    <script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-image: url("<?= base_url(); ?>/public/assets/img/logo/ranchimuncipal_logo.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            opacity: 1.4;
        }
        .login-container {
            margin-top: 45%;
            background: rgba(255, 255, 255, 0.95);
            /* height: 400px; */
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 10px;
            border: 12px #2c3e50;
        }
        .login-container h1 {
            text-align: center;
            /* margin-bottom: 10px; */
            font-size: 24px;
            color: #2c3e50;
        }

        .form-group input {
            border-radius: 25px;
            padding: 12px 20px;
            border: 1px solid #ccc;
            width: 100%;
            margin: 10px 0;
            font-size: 16px;
            transition: border-color 0.3s;
            border-color: #3498db;
        }

        .form-group input:active {
            border-color: #3498db;
            border: 2px;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }

        .password-input-container {
            position: relative;
        }

        .password-input-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #3498db;
        }

        .btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-sm{
            background-color: #3498db;
            color: #fff;
            border: none;
            width: 49%;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .captcha-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .captcha-container img {
            width: 100px;
            height: 40px;
            object-fit: contain;
            cursor: pointer;
        }

        .captcha-container i {
            font-size: 22px;
            cursor: pointer;
            color: #3498db;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }

        .footer a {
            color: #3498db;
            text-decoration: none;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }

        
    </style>
</head>

<body>
    <div id="loadingDiv" style="background: url(<?=base_url();?>/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
    <div id="container">
        <div class="login-container">
            <h1>Login</h1>
            <form id="otpForm" method="post">
                <div class="error-message">
                    <?php if (!empty($errMsg)) {
                        echo $errMsg;
                    } ?>
                </div>
                <input type="hidden" id="ip_address" name="ip_address" value="" />
                <div class="form-group ">
                    <input type="text" id="otp" name="otp" class="form-control" maxlength="50"
                        placeholder="OTP" required />
                </div> 
                
                <div class="form-group " style="text-align: center;">
                    <button type="button" class="btn-sm" id="resendOtp" >Resend OTP</button>
                    <button type="submit" class="btn-sm" id="singIn">Sing In</button>
                </div>
    
                <div class="login-link">
                    <a href="<?=$from=="WEB"?base_url("/Home/index"):base_url("/Login/mobi"); ?>">Back to Home</a>
                </div>
            </form>
    
            <div class="footer">
                <p>For password related queries, kindly mail to <a href="mailto:suda.goj@gmail.com">suda.goj@gmail.com</a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
     <script src="<?=base_url();?>/public/assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url();?>/public/assets/js/nifty.min.js"></script>
    <script src="<?=base_url();?>/public/assets/plugins/masked-input/jquery.maskedinput.min.js"></script>
    <script src="<?=base_url();?>/public/assets/plugins/select2/js/select2.min.js"></script>
    <script src="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?=base_url();?>/public/assets/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/otherJs/loginjs.js"></script> 
    <script>
        $(document).ready(function(){
            $("#loadingDiv").hide();
            $("#resendOtp").on("click",function(event){
                let formData = new FormData();
                formData.append("resendOtp",true);
                $.ajax({
                    url:window.location.href,
                    type:"POST",
                    data:formData,
                    processData: false, // Required when sending FormData
                    contentType: false, // Required when sending FormData

                    dataType:"json",
                    beforeSend:function(){
                        $("#loadingDiv").show();
                    },
                    success:function(response){
                        $("#loadingDiv").hide();
                        if(response.status){
                            modelInfo(response?.message);
                        }
                    },
                    error:function(error){
                        console.log(error);
                    }
                }) 
            });
            
        });
        
    function devToolCheck() {
        
        // Fallback using debugger trick
        const start = performance.now();
        debugger;
        const end = performance.now();

        if (end - start > 100) {
            // Developer tools are open (timing delay from paused debugger)
            window.location.href = '<?=base_url("Login")?>';
        }

        // Re-run periodically
    };
    <?= getenv("CI_ENVIRONMENT")=="production"?"setTimeout(devToolCheck, 2000);":"";?>

    function modelInfo(msg)
    {
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
    if($result = flashToast('message'))
    {
        ?>
            modelInfo('<?=$result;?>');
        <?php 
    }
    ?>
    
    </script>



</body>

</html>