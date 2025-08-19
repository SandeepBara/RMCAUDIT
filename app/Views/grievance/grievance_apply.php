<?php
    $isCitizen = session()->get("citizen");
    if($isCitizen){
        echo $this->include('layout_home/header'); 
    }else{
        echo $this->include('layout_vertical/header');
    }
?>

<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<style>
    .error{
        color: red;
    }
    .container_wrapper {
        display: flex;
    }

    .content_container {
        padding: 20px;
        width: 100%;
    }

    .left_sidebar_container {
        width: 200px;
        height: 100vh;
        background-color: #5c789f;
        padding: 10px;
        margin-right: 15px;
    }

    .left_sidebar_container ul {
        padding-left: 10px;
    }

    .left_sidebar_container ul li {
        margin-top: 10px;
        list-style: none;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
    }

    .left_sidebar_container ul li:hover {
        background-color: #000000;
        color: #ffffff;
    }

    .left_sidebar_container ul li a {
        font-family: verdana;
        font-size: 16px;
        font-weight: normal;
        color: #ffffff;
    }
</style>

<div class="main_div_container">
    <div class="container_wrapper">
        <!-- CITIZEN GRIEVANCE SIDEBAR START HERE -->
        <div class="left_sidebar_container">
            <?php
            if ($isCitizen) {
                echo $this->include('grievance/layout/sidebar');
            } else {
                echo '<h1>Main User Sidebar</h1>';
            }
            ?>
        </div>
        <!-- CITIZEN GRIEVANCE SIDEBAR END HERE -->

        <!-- CONTENT CONTAINER -->
        <div class="content_container">
            <form id="myForm" enctype="multipart/form-data" >
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php }else{
                    echo"<div id='error' class='text-danger'></div>";
                } ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control name" value="<?=($citizen["name"]??"");?>" <?=$isCitizen?"readonly":"";?> placeholder="Applicant Name" onkeypress="return isAlpha(event);">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="phone">Mobile No <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control phone" placeholder="Mobile No" value="<?=($citizen["phone_no"]??"");?>" <?=$isCitizen?"readonly":"";?>>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="module_id">Grievance For<span class="text-danger">*</span></label>
                            <select id="module_id" name="module_id" class="form-control" onchange="showHideDive()">
                                <option value="">SELECT Grievance For</option>
                                <option value="1" <?= isset($grievance_department) && $grievance_department == "PROPERTY" ? "selected" : ""; ?>>PROPERTY</option>
                                <option value="2" <?= isset($grievance_department) && $grievance_department == "WATER" ? "selected" : ""; ?>>WATER</option>
                                <option value="3" <?= isset($grievance_department) && $grievance_department == "TRADE" ? "selected" : ""; ?>>TRADE</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="app_no_container" style="display: none;">
                        <div class="form-group">
                            <label class="control-label" for="app_no">                            
                                <span id="holding_input_container">Holding / SAF No </span>
                                <span id="water_input_container">Consumer / Application No </span>
                                <span id="trade_input_container">Licence / Application No </span>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="app_no" name="app_no" class="form-control holding_no" placeholder="Holding / SAF NO" onblur="validateAppNO();">
                            <input type="hidden" name="app_id" id="app_id">
                            <input type="hidden" name="app_type" id="app_type">
                        </div>
                    </div>
                    
                </div>

                <!-- PROPERTY COMPLAINT CONTAINER -->
                <div class="row" id="other_dtl_container" style="display: none;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="owner_name">Owner Name <span class="text-danger">*</span></label>
                            <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Owner Name" onkeypress="return isAlpha(event);">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                            <input type="text" id="guardian_name" name="guardian_name" class="form-control" placeholder="Guardian Name" onkeypress="return isAlpha(event);">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="ward_id">Ward No<span class="text-danger">*</span></label>
                            <select name="ward_id" id="ward_id" class="form-control">
                                <option value="">SELECT</option>
                                <?php
                                    if(isset($wardList)){
                                        foreach($wardList as $ward){
                                            ?>
                                            <option value="<?=$ward["id"]?>"><?=$ward["ward_no"]?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="address">Address<span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Address">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="upload_file">Attachment</label>
                            <input type="file" id="upload_file" name="upload_file" accept="image/*,.pdf" class="form-control" placeholder="Upload Attachment">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="queries">Description</label>
                            <textarea id="queries" name="queries" class="form-control" minlength="30"></textarea>
                        </div>
                    </div>

                    <div class="col-md-3" style="padding-top: 20px;">
                        <div class="form-group">
                            <input type="submit" id="saveGrievance" name="saveGrievance" class="form-control btn btn-secondary" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    if ($isCitizen) {
        echo $this->include('layout_home/footer');
    } else {
        echo $this->include('layout_vertical/footer');
    } 
?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
<script>

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
    function showHideDive() {
        var department =$("#module_id").val();
        $('#holding_input_container').hide();
        $('#water_input_container').hide();
        $('#trade_input_container').hide();
        $("#other_dtl_container").hide();
        $('#app_no_container').hide();
        $('#app_no').val("");

        if (department ==1) {
            $('#holding_input_container').show();
            $('#app_no_container').show();
            $("#other_dtl_container").show();
            $("#app_no").attr("placeholder","Enter Valid Holding No");
        } else if (department ==2) {
            $('#water_input_container').show();
            $('#app_no_container').show();
            $("#other_dtl_container").show();
            $("#app_no").attr("placeholder","Enter Consumer / Application NO");
        } else if (department ==3) {
            $('#trade_input_container').show();
            $('#app_no_container').show();
            $("#other_dtl_container").show();
            $("#app_no").attr("placeholder","Enter Trade License / Application NO");
        }
    }
    
    function validateAppNO() {
        var app_no = $("#app_no").val();
        var module_id = $("#module_id").val();

        if (app_no) {
            $.ajax({
                type: "POST",
                url: '<?=base_url("grievance_new/validateAppNO"); ?>',
                dataType: "json",
                data:{
                    "module_id": module_id,
                    "app_no": app_no
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                    $("#saveGrievance").prop("disabled",true);
                },
                success: function(data) {
                    $("#saveGrievance").prop("disabled",false)
                    $("#loadingDiv").hide();
                    console.log("data", data);
                    if (data.status == true) {
                        var responseData = data.data;
                        console.log("Response Array", responseData);

                        if (responseData.length > 0) {
                            var owner_names = responseData.map(item => item.owner_name).join(', ');
                            $('#owner_name').val(owner_names);
                            $("#guardian_name").val(responseData[0].guardian_name);                            
                            $('#address').val(responseData[0].address);
                            if(!<?=$isCitizen?>){
                                $("#mobile_no").val(responseData[0].mobile_no);
                            }
                            $('#app_id').val(responseData[0].id);
                            $("#app_type").val(responseData[0].app_type);
                            $('#ward_id').val(responseData[0].ward_mstr_id);
                            
                        } else {
                            $('#holding_owner_name').val('No owners found');
                        }
                    }else{
                        modelInfo(data.error,"error");
                        $('#owner_name').val("");
                        $("#guardian_name").val("");                            
                        $('#address').val("");
                        if(!<?=$isCitizen?>){
                            $("#mobile_no").val("");
                        }
                        $('#app_id').val("");
                        $("#app_type").val("");
                        $('#ward_id').val("");
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
    }

    $(document).ready(function() {
        showHideDive();
    });

    $("#myForm").validate({		
        rules: {
            "name": {
                required: true,
            },
            "phone": {
                required: true,
            },
            "module_id": {
                required: true,
            },
            "app_no": {
                required: true,
            },
            "owner_name": {
                required: true,
            },
            "guardian_name": {
                required: true,
            },
            "app_id": {
                required: true,
            },
            "ward_id": {
                required: true,
            },
            "address": {
                required: true,
            },
            "upload_file": {
                required: true,
            },
            "queries": {
                required: true,
                minlength : 30,
            }
        },
        messages: {
            "payment_mode": "Please Choose Payment Mode",
            "remarks": "Please Enter Your Remarks",
            "bank_name": "Please Enter Bank Name",
            "branch_name": "Please Enter Branch Name",
            "cheque_no1": "Please Enter Cheque/DD No",
            "cheque_date": "Please Enter Cheque/DD Date",
            "total_payable_amount": {
                required: "Please Enter Payable Amount",
                min : "Amount Should be greter than Total Paybale Amount",
                number : "Please Enter Valid Amount",
            }
        },
        submitHandler: function(form)
        {
            if(confirm("Are you sure want to submit now?"))
            {
                SubmitComplain();
            }
        }
    });

    function SubmitComplain(){    
        var form = document.getElementById('myForm');
        var formData = new FormData(form); 
        formData.append('ajax', true);
        formData.append("grievance_type_id",1);
        for (var [name, value] of formData.entries()) {
            console.log(name, value);
            
        }

        $.ajax({
            url: "<?=base_url('/grievance_new/grievance_insert');?>", // Replace with your server URL
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false,  
            beforeSend: function() {
                    $("#loadingDiv").show();
                    $("#saveGrievance").prop("disabled",true);
            },
            success: function(response){
                $("#loadingDiv").hide();
                response = JSON.parse(response);
                if(response.status){                    
                    modelInfo("Data Saved","success");
                    window.location.href= response.url;
                }else{                    
                    $("#saveGrievance").prop("disabled",false);
                    var list = "";
                    validation = Object.values(response.validation);
                    for (let index = 0; index < validation.length; index++) {
                        list += "<li>"+validation[index]+"</li>";                            
                    }
                    console.log("validation:",validation);
                    $("#error").html("<ul>"+list+"</ul>");
                    modelInfo("Error In Data Storing","warning");
                }
            },
            error: function(xhr, status, error){
                console.error('File upload failed:', status, error);
            }
        });

    }

        
</script>

