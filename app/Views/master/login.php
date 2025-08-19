
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="<?=base_url();?>/assets/css/nifty.min.css" rel="stylesheet">
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="<?=base_url();?>/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?=base_url();?>/assets/plugins/pace/pace.min.js"></script>   
    <!--[ OPTIONAL ]-->
    <link href="<?=base_url();?>/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div id="container" class="cls-container">
		<div id="bg-overlay"></div>
		<div class="cls-content">
		    <div class="cls-content-sm panel">
		        <div class="panel-body">
		            <div class="mar-ver pad-btm">
		                <h1 class="h1">LOGIN</h1>
		            </div>
		            <form method="post" action="<?=base_url();?>/public/home/dashboard">
                        <div class="form-group text-danger">
                         <?php
                            /*if(!empty($error)){
                                echo $error;
                            }*/
                        ?>
		                </div>
                        <input type="hidden" id="ip_address" name="ip_address" value="">
		                <div class="form-group">
		                    <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Username" autofocus>
		                </div>
                        <div class="input-group mar-btm">
                            <input type="password" id="user_pass" name="user_pass" class="form-control" placeholder="Password">
                            <span class="input-group-addon" id="passToggle" style="cursor:pointer;"><i class="fa fa-eye"></i></span>
                        </div>
		                <div class="checkbox pad-btm text-left">
		                    <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox">
		                    <label for="demo-form-checkbox">Remember me</label>
		                </div>
		                <button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
		            </form>
		        </div>

		        <div class="pad-all">
		            <a href="<?=base_url();?>/Home/index" class="btn-link mar-rgt">Back To Home</a>
		        </div>
		    </div>
		</div>
    </div>
    <script src="<?=base_url();?>/assets/js/jquery.min.js"></script>
    <script src="<?=base_url();?>/assets/otherJs/loginjs.js"></script>
</body>
</html>