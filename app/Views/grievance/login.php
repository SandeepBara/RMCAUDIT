<?= $this->include('layout_home/header'); ?>

<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<style>
    .login-card-wrapper {
        display: flex;
        justify-content: center;
        width: 380px;
        height: 480px;
        background-color: white;
        background: #ffffff;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        margin: auto;
        padding: 15px;
        border-radius: 9px;
        margin-bottom: 15px;
    }

    .login-card .form-container {
        padding: 16px;
    }

    .login-card .login_container .row,
    .login-card .register_container .row {
        display: flex;
        flex-direction: column;
    }

    .login-card .login_container .row label,
    .login-card .register_container .row label {
        margin-top: 10px;
        /* margin-bottom: 10px; */
        font-size: 15px;
        font-weight: 600;
    }

    .login-card .login_container .row input,
    .login-card .register_container .row input {
        width: 100%;
        padding: 7px;
        margin-top: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
    }

    .login-card .login_container .row .button_container,
    .login-card .register_container .row .button_container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-card .login_container .row .button_container button,
    .login-card .register_container .row .button_container button {
        width: 168px;
        padding: 5px;
        border-radius: 8px;
        background: #141818;
        color: #fff;
        font-size: 17px;
    }

    .registration_link {
        text-align: center;
        font-size: 16px;
    }

    .registration_link span {
        color: blue;
        text-decoration: underline;
        cursor: pointer;
    }
    .login-btn{
        font-size: 1.1em !important;
        width: 128px !important;
    }
    #clock {
            font-size: 1em;
            font-weight: bold;
            text-align: center;
            display: none;
    }
</style>

<div id="content-container" style="padding-top: 10px;">
    <div id="page-content">
        <div class="login-card-container">
            <div class="login-card-wrapper">
                <div class="login-card">
                    <h3 id="form-title">Welcome to Grievance Login</h3>
                    <div id="clock">00:00:00</div>
                    <div class="form-container">
                        <form>
                            <div class="login_container" id="login_container">
                                <div class="row">
                                    <label for="phone">Phone No *</label>
                                    <input type="tel" name="phone" id ="phone" placeholder="Phone Number" />
                                    <div id="login_otp" style="display: none;">
                                        <label for="phone">OTP *</label>
                                        <input type="text" name="otp" id="otp" placeholder="" />

                                    </div>

                                    <div class="button_container">
                                        <button type="button" class="login-btn btn"  id="login" style="display: none;" onclick="citizenLogin()">Login</button>
                                        <button type="button" class="login-btn btn" onclick="showOtp()" id="login_otp_send">Send OTP</button>
                                    </div>

                                    <a href="javascript:void(0)" class="registration_link" onclick="toggleView()">Not Registered yet? <span>Register Here</span></a>
                                </div>
                            </div>

                            <div class="register_container" id="registrer_container" style="display: none;">
                                <div class="row">
                                    <label for="name">Full Name *</label>
                                    <input type="tel" name="name" id="name" placeholder="Full Name" />
                                    <label for="resistor_phone">Phone No *</label>
                                    <input type="tel" name="phone" id="resistor_phone" placeholder="Phone Number" />
                                    <div id="resistor_otp" style="display: none;">
                                        <label for="c_resistor_otp">OTP *</label>
                                        <input type="text" name="otp" id="c_resistor_otp" placeholder="" />
                                    </div>
                                    <div class="button_container">
                                        <button type="button" class="login-btn btn" id="resistor_otp_send" onclick="resistorOtp()">Send OTP</button>
                                        <button type="button" class="login-btn btn" id="resistor" style="display: none;" onclick="resistorCitizen()">Register</button>
                                    </div>
                                    <a href="javascript:void(0)" class="registration_link" onclick="toggleView()">Already have an account? <span>Login Here</span></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout_home/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>

<script>
    function toggleView() {
        const loginContainer = $('#login_container');
        const registerContainer = $('#registrer_container');
        const formTitle = $('#form-title');

        if (loginContainer.is(':visible')) {
            loginContainer.hide();
            registerContainer.show();
            formTitle.text('Register For Grievance');
        } else {
            registerContainer.hide();
            loginContainer.show();
            formTitle.text('Welcome to Grievance Login');
        }
    }

    function modelInfo(msg,type="info")
    {
        $.niftyNoty({
            type: type,
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    function resetLogin(){
        stopTimer();
        $("#login").hide();
        $("#login_otp_send").html("Send OTP");
    }

    function showOtp(){
        let mobileNo = $("#phone").val();
        if(mobileNo){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("grievance_new/login"); ?>',
                dataType: "json",
                data: {
                    "mobile_no": mobileNo,
                    "send_otp":true,
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success: function(data) {
                    $("#loadingDiv").hide();
                    console.log("data", data);
                    if (data.response == true) {
                        modelInfo(data.message,'success');
                        $("#login_otp").show();
                        $("#login").show();
                        $("#login_otp_send").prop('disabled', true);
                        $("#login_otp_send").html("Resend OTP");
                        stopTimer();
                        startTimer();
                        setTimeout(function(){
                            $("#login_otp_send").prop('disabled', false);
                        }, 30000);
                    }else{
                        modelInfo(data.error);
                        $("#login_otp").hide();
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    // alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
    }  

    function citizenLogin(){
        let mobileNo = $("#phone").val();
        let otp = $("#otp").val();
        // alert(mobileNo); alert(otp);
        if(mobileNo && otp){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("grievance_new/login"); ?>',
                dataType: "json",
                data: {
                    "mobile_no": mobileNo,
                    "otp":otp,
                    "login":true,
                    "ajax":true,
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success: function(data) {
                    $("#loadingDiv").hide();
                    console.log("data", data);
                    if (data.response == true) {
                        console.log("res:",data.response);
                        modelInfo(data.message,'success');
                        $("#login_otp").prop('disabled', true);
                        $("#login").prop('disabled', true);
                        stopTimer();
                        window.location.href= data.url;
                    }else{
                        modelInfo(data.errMsg);
                        $("#login_otp").prop('disabled', false);
                        $("#login").prop('disabled', false);
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    // alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
    } 

    function resistorOtp(){
        let mobileNo = $("#resistor_phone").val();
        if(mobileNo){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("grievance_new/resistorCitizen"); ?>',
                dataType: "json",
                data: {
                    "mobile_no": mobileNo,
                    "register_otp":true,
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success: function(data) {
                    $("#loadingDiv").hide();
                    console.log("data", data);
                    if (data.response == true) {
                        modelInfo(data.message,'success');
                        $("#resistor_otp").show();
                        $("#resistor").show();
                        $("#resistor_otp_send").prop('disabled', true);
                        $("#resistor_otp_send").html("Resend OTP");
                        stopTimer();
                        startTimer();
                        setTimeout(function(){
                            $("#resistor_otp_send").prop('disabled', false);
                        }, 30000);
                    }else{
                        modelInfo(data.error);
                        $("#c_resistor_otp").hide();
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    // alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
    }

    function resistorCitizen(){
        let mobileNo = $("#resistor_phone").val();
        let name = $("#name").val();
        let otp = $("#c_resistor_otp").val();
        if(mobileNo && otp){
            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("grievance_new/resistorCitizen"); ?>',
                dataType: "json",
                data: {
                    "mobile_no": mobileNo,
                    "full_name":name,
                    "otp":otp,
                    "register":true,
                    "ajax":true,
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success: function(data) {
                    $("#loadingDiv").hide();
                    console.log("data", data);
                    if (data.response == true) {
                        modelInfo(data.message,'success');
                        $("#resistor_otp_send").prop('disabled', true);
                        $("#resistor").prop('disabled', true);
                        stopTimer();
                        window.location.href= data.url;
                    }else{
                        modelInfo(data.error);
                        $("#resistor_otp_send").prop('disabled', false);
                        $("#resistor").prop('disabled', false);
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loadingDiv").hide();
                    // alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
    }

    let minutes=0;
    let seconds=0;
    let timerInterval;
    function startTimer(){        
        $("#clock").show();
        timerInterval =setInterval(updateClock, 1000);
    }
    function stopTimer(){
        clearInterval(timerInterval); 
        minutes =0;
        seconds =0;
        $("#clock").hide();
    }

    function updateClock() {
            // Get the current time
            
            if(seconds>60){
                seconds =0;
                minutes+=1;
            }else{
                seconds+=1;
            }
            if(minutes>=10){
                stopTimer();
            }
            var sminutes = minutes.toString().padStart(2, '0');
            var sseconds = seconds.toString().padStart(2, '0');

            // Format the time as HH:MM:SS
            var timeString =  sminutes + ':' + sseconds;

            // Update the clock element with the new time
            $('#clock').text(timeString);
    }
</script>
