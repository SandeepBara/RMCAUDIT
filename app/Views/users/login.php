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
    <link href="<?= base_url(); ?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?= base_url(); ?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="<?= base_url(); ?>/public/assets/css/styles.css" rel="stylesheet">

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
            margin-top: 5rem;
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

    <!-- Header -->
    <!-- <div class="header">
        <div class="logo">
            <img src="http://localhost/RMCHOSTINGER/public/assets/img/logo1.png" alt="" width="100px" height="100px">
        </div>
        RANCHI MUNICIPAL CORPORATION LOGIN
    </div> -->

    <div class="login-container">
        <h1>Login</h1>
        <form method="post" action="<?= base_url('Login/index'); ?>">
            <div class="error-message">
                <?php if (!empty($errMsg)) {
                    echo $errMsg;
                } ?>
            </div>

            <div class="form-group">
                 <input type="hidden" id="ip_address" name="ip_address" value="" />
                <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Username"
                    maxlength="50" value="<?= isset($_POST['user_name']) ? esc($_POST['user_name']) : '' ?>"
                    autofocus />
            </div>

            <div class="form-group password-input-container">
                <input type="password" id="user_pass" name="user_pass" class="form-control" maxlength="50"
                    placeholder="Password" required />
                <i class="fa fa-eye" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>

            <div class="captcha-container">
                <img src='<?= loginCaptcha(); ?>' id="loginCaptcha" />
                <i class="fa fa-refresh" onclick='refrechLoginCaptcha();'></i>
            </div>

            <div class="form-group">
                <input type="number" id="captcha_code" name="captcha_code" class="form-control"
                    placeholder="Enter captcha" maxlength="4" autocomplete="off" pattern="/^-?\d+\.?\d*$/" />
            </div>

            <button type="submit" id="btn_submit" name="btn_submit" class="btn">Sign In</button>

            <div class="login-link">
                <a href="<?= base_url(); ?>/Home/index">Back to Home</a>
            </div>
        </form>

        <div class="footer">
            <p>For password related queries, kindly mail to <a href="mailto:suda.goj@gmail.com">suda.goj@gmail.com</a>
            </p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/public/assets/js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/js/md5.js"></script>
    <script src="<?= base_url(); ?>/public/assets/otherJs/loginjs.js"></script>

    <script>
        // Refresh captcha function
        function refrechLoginCaptcha() {
            $("#loginCaptcha").html("<h4>Please Wait...</h4>");
            $.ajax({
                url: "<?= base_url() . "/login/refrechLoginCaptcha"; ?>",
                success: function (result) {
                    setCaptchaCode(result);
                }
            });
        }

        // Set new captcha code
        let setCaptchaCode = (result) => $("#loginCaptcha").html("<img src='" + result + "'/>");

        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordField = document.getElementById("user_pass");
            const toggleIcon = document.getElementById("togglePassword");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
        function reloadScript(id, src) {
            const oldScript = document.getElementById(id);
            // if (oldScript) oldScript.remove();

            const newScript = document.createElement('script');
            newScript.id = id;
            newScript.src = src + '?t=' + Date.now();
            document.head.appendChild(newScript);
        }

            // Example
        // reloadScript('my-script', '<?= base_url(); ?>/public/assets/otherJs/loginjs.js');
    </script>

</body>

</html>