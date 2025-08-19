<?= $this->include('layout_home/header'); ?>

<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<style>
    .login-card-wrapper {
        display: flex;
        justify-content: center;
        width: 380px;
        height: 480px;
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

    .login-card .login_container .row .button_container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-card .login_container .row .button_container button {
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

    .login-btn {
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

<div id="content-container" style="padding-top: 10px; margin-bottom:15%;">
    <div id="page-content">
        <div class="login-card-container">
            <div class="login-card-wrapper">
                <div class="login-card">
                    <h3 id="form-title">Forgot Password</h3>
                    <div id="clock">00:00</div>
                    <div class="form-container">
                        <form id="changePasswordForm">
                            <div class="login_container" id="login_container">
                                <div class="row">
                                    <label for="user_name">User Name <span class="text-danger">*</span></label>
                                    <input type="text" name="user_name" id="user_name" placeholder="User Name" />
                                    
                                    <div id="otp_section" style="display: none;">
                                        <label for="otp_input">OTP <span class="text-danger">*</span></label>
                                        <input type="text" name="otp" id="otp_input" placeholder="Enter OTP" />

                                        <label for="password">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" placeholder="New Password" />

                                        <label for="conform_password">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="conform_password" id="conform_password" placeholder="Confirm Password" />
                                    </div>

                                    <div class="button_container">
                                        <button type="submit" class="login-btn btn" id="submit" style="display: none;">Change</button>
                                        <button type="button" class="login-btn btn" onclick="showOtp()" id="login_otp_send">Send OTP</button>
                                    </div>

                                    <a href="<?= base_url('Login/index') ?>" class="registration_link">Not Registered yet? <span>Login Here</span></a>
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
    function modelInfo(msg, type = "info") {
        $.niftyNoty({
            type: type,
            icon: 'pli-exclamation icon-2x',
            message: msg,
            container: 'floating',
            timer: 5000
        });
    }

    function showOtp() {
        let user_name = $("#user_name").val();
        if (user_name) {
            $.ajax({
                type: "POST",
                url: window.location.href,
                dataType: "json",
                data: {
                    "user_name": user_name,
                    "send_otp": true,
                },
                beforeSend: function () {
                    $("#loadingDiv").show();
                },
                success: function (data) {
                    $("#loadingDiv").hide();
                    if (data.status == true) {
                        modelInfo(data.message, 'success');
                        $("#otp_section").show();
                        $("#submit").show();
                        $("#login_otp_send").prop('disabled', true).html("Resend OTP");
                        stopTimer();
                        startTimer();
                        setTimeout(function () {
                            $("#login_otp_send").prop('disabled', false);
                        }, 30000);
                    } else {
                        modelInfo(data.message);
                        $("#otp_section").hide();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error:", textStatus, errorThrown);
                    alert("Something went wrong.");
                }
            });
        }
    }

    $("#changePasswordForm").validate({
        rules: {
            user_name: { required: true },
            otp: { required: true },
            password: { required: true },
            conform_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            conform_password: {
                equalTo: "Passwords do not match"
            }
        },
        submitHandler: function (form) {
            sendFormData();
        }
    });

    function sendFormData() {
        let form = document.getElementById("changePasswordForm");
        let formData = new FormData(form);
        formData.append("update_password",true);

        $.ajax({
            url: window.location.href,
            type: "post",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $("#loadingDiv").show();
            },
            success: function (response) {
                modelInfo(response?.message, response.status ? 'success' : 'danger');
                if (response.status) {
                    window.location = response?.url;
                } else {
                    $("#loadingDiv").hide();
                }
            },
            error: function (errors) {
                console.error(errors);
                $("#loadingDiv").hide();
            }
        });
    }

    let minutes = 0;
    let seconds = 0;
    let timerInterval;

    function startTimer() {
        $("#clock").show();
        timerInterval = setInterval(updateClock, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        minutes = 0;
        seconds = 0;
        $("#clock").hide();
    }

    function updateClock() {
        if (seconds >= 59) {
            seconds = 0;
            minutes += 1;
        } else {
            seconds += 1;
        }

        if (minutes >= 10) {
            stopTimer();
        }

        let sminutes = minutes.toString().padStart(2, '0');
        let sseconds = seconds.toString().padStart(2, '0');
        $('#clock').text(`${sminutes}:${sseconds}`);
    }
</script>
