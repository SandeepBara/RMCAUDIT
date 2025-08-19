<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>SUDA-JH | Mobile Login</title>
<link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
<style>
  .error {
        color: red;
  }
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-weight: 300;
}
body {
  /* font-family: 'Source Sans Pro', sans-serif; */
  font-family:verdana;
  color: white;
  font-weight: 300;
  background:#e27d60;
}
body ::-webkit-input-placeholder {
  /* WebKit browsers */
  font-family: 'Source Sans Pro', sans-serif;
  color: white;
  font-weight: 300;
}
body :-moz-placeholder {
  /* Mozilla Firefox 4 to 18 */
  font-family: 'Source Sans Pro', sans-serif;
  color: white;
  opacity: 1;
  font-weight: 300;
}
body ::-moz-placeholder {
  /* Mozilla Firefox 19+ */
  font-family: 'Source Sans Pro', sans-serif;
  color: white;
  opacity: 1;
  font-weight: 300;
}
body :-ms-input-placeholder {
  /* Internet Explorer 10+ */
  font-family: 'Source Sans Pro', sans-serif;
  color: white;
  font-weight: 300;
}
.wrapper {
  background: #E27D60;
  /* background: -webkit-linear-gradient(top left, #E27D60 10%, #41B3A3 90%);
  background: linear-gradient(to bottom right, #E8A87C 0%, #41B3A3 100%); */
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  height: 400px;
  /* margin-top: -200px; */
  overflow: hidden;
}
.wrapper.form-success .container h1 {
  -webkit-transform: translateY(85px);
          transform: translateY(85px);
}
.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 80px 0;
  height: 400px;
  text-align: center;
}
.container h1 {
  font-size: 40px;
  -webkit-transition-duration: 1s;
          transition-duration: 1s;
  -webkit-transition-timing-function: ease-in-put;
          transition-timing-function: ease-in-put;
  font-weight: 200;
}
form {
  padding: 20px 0;
  position: relative;
  z-index: 2;
}
/* form input {
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  outline: 0;
  border: 1px solid rgb(212 43 43 / 40%);
  background-color: rgb(26 187 165 / 20%);
  width: 250px;
  border-radius: 3px;
  padding: 10px 15px;
  margin: 0 auto 10px auto;
  display: block;
  text-align: center;
  font-size: 18px;
  color: red;
  -webkit-transition-duration: 0.25s;
          transition-duration: 0.25s;
  font-weight: 300;
} */
/* form input:hover {
  background-color: rgba(255, 255, 255, 0.9);
}
form input:focus {
  background-color: gray;
  width: 300px;
  color: #53e3a6;
} */
/* form button {
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  outline: 0;
  background-color: #17a2b8;
  border: 0;
  padding: 10px 15px;
  color: #fff;
  border-radius: 3px;
  width: 250px;
  cursor: pointer;
  font-size: 18px;
  -webkit-transition-duration: 0.25s;
          transition-duration: 0.25s;
}
form button:hover {
  background-color: #28a745;
} */
</style>

<style>
    .login-container{
      background: linear-gradient(28deg, rgba(155, 34, 195,1) 0%, rgba(253,187,45,1) 100%);
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      width:350px;
      padding:30px;
      text-align:center;
    }
    .login-container img{
      width:80px;
      height:80px;
      margin 0 auto 20px;
      display:block;
    }
    h1{
      font-size:24px;
      margin:10px 0;
      color:#fff;
    }
    .input-container{
      margin-bottom:20px;
      text-align:left;
    }
    input[type="text"],
    input[type="password"] {
      width:100%;
      padding:10px;
      border:none;
      border-bottom:2px solid #fff;
      background-color: transparent;
      color:#e1fd74;
      font-family:verdana;
      transition: border-bottom-color 0.3s ease-in-out;
      outline:none;
    }
    input[type="text"]:focus,
    input[type="password"]:focus{
      border-bottom-color:#fff;
    }
    button{
      background-color:#e1fd74;
      color:#24261a;
      padding:12px 100px;
      border:none;
      border-radius:54px;
      cursor:pointer;
      font-weight:bold;
      transition:background-color 0.3s ease-in-out;
    }
    button:hover{
      background-color: transparent;
      border:1px solid #e1fd74;
      color:#fff;
    }
    .extra-option{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-top:15px;
    }
    .contact-buttons{
      display:none;
    }
    .credit{
      color:#fff;
      margin-top:10px;
      font-size:12px;
    }
</style>

</head>

<body>
    <div class="wrapper" style="top:115px; height:100%;">
      <div class="container login-container">
          <h1>Login</h1>
          <form action="<?php echo base_url('Login/mobi'); ?>" method="post">
            <div class="input-container">
              <input type="hidden" name="ip_address" id="ip_address" value="">
              <input type="text" name="user_id" id="user_id" placeholder="Username" required>
            </div>
            <div class="input-container">
              <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" id="loginn" value="login"><b>Login &rarr;</b></button>
            <div class="col-md-12">
              <span style="color:#fff; font-size:14px;font-weight:900;"><?php echo $errMsg; ?></span>
            </div>
            
          </form>
          <div class="extra-option">
              
          </div>
      </div>
    </div>
  <?php 
  /*
  <div class="wrapper" style="top:200px; height:100%;">
	<div class="container">
		<h1>LOGIN</h1>
		<form class="form" id="form" method="post" action="<?php echo base_url('Login/mobi');?>">
		  <div class="row">
			<div class="col-md-12">
			  <input type="hidden" id="ip_address" name="ip_address" value="">
			  <input type="text" id="user_id" name="user_id" placeholder="Username" required>
			</div>
			<div class="col-md-12">
			  <input type="password" id="password" name="password" placeholder="Password" required>
			</div>
			<div class="col-md-12">
			  <button type="submit" id="loginn" name="loginn" value="login" ><b>Login</b></button>
			</div>
			<div class="col-md-12">
			  <span style="color:red; font-size:14px;font-weight:900;"><?php echo $errMsg ?? null; ?></span>
			</div>
		  </div>
		</form>
	</div>
</div>

*/ ?>
    <script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>
    <script src="<?=base_url();?>/public/assets/otherJs/loginjs.js"></script>
    <script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
</body>
</html>
<script>
$("#loginn").click(function(){
  if ($("#user_id").val()!="" && $("#user_id").val().length>1
        && $("#password").val()!="" && $("#password").val().length>1) {
      $("#loginn").html("Please Wait..");
  }
});
  $("#form").validate({
        rules: {
          "user_id": {
              required: true,
              minlength: 2,
          },
          "password": {
              required: true,
              minlength: 2,              
          },
        }
  });

  function reloadScript(id, src) {
            const oldScript = document.getElementById(id);
            if (oldScript) oldScript.remove();

            const newScript = document.createElement('script');
            newScript.id = id;
            newScript.src = src + '?t=' + Date.now();
            document.head.appendChild(newScript);
        }

            // Example
        // reloadScript('my-script', '<?= base_url(); ?>/public/assets/otherJs/loginjs.js');
</script>
