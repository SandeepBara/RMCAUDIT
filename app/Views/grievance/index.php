<?= $this->include('layout_home/header'); ?>

<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<style>
    input {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
    }
</style>

<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Consumer Help Desk</h3>
            </div>
            <div class="panel-body">
                <div class="col-md-6 col-md-offset-3  bg-gray-light pad-ver">
                    <?php if(isset($validation)){ ?>
                        <?= $validation->listErrors(); ?>
                    <?php }else{
                        echo"<div id='error' class='text-danger'></div>";
                    } ?>
                    <div class="row" style="background-color:#25476a; border-radius:5px;">

                        <div class="col-md-12" style="margin-top:10px;color:#fff;">
                            <label class="col-md-4"><b>Grievance Type: </b><span class="text-danger">*</span></label>
                            <div class="col-md-8 pad-btm">
                                <select name="grievance_type" id="grievance_type" class="form-control" onchange="showHideDiv(this.value)">
                                    <option value="">SELECT</option>
                                    <option value="query" <?=isset($grievance_type) && $grievance_type=="query" ? "select":"";?>>Query</option>
                                    <option value="complain" <?=isset($grievance_type) && $grievance_type=="complain" ? "select":"";?>>Complain</option>
                                    <option value="track_application" <?=isset($grievance_type) && $grievance_type=="track_application" ? "select":"";?>>View Application Status</option>
                                </select>
                            </div>
                        </div>
                        <div id="department" class="col-md-12" style="margin-top:10px;color:#fff; display:none;">
                            <label class="col-md-4" for="grievance_department"><b>Grievance Department: </b><span class="text-danger">*</span></label>
                            <div class="col-md-8 pad-btm">
                                <select name="grievance_department" id="grievance_department" class="form-control" onchange="changeInputs()">
                                    <option value="">SELECT MODULE</option>
                                    <option value="PROPERTY" <?=isset($grievance_department) && $grievance_department=="PROPERTY" ? "select":"";?>>PROPERTY</option>
                                    <option value="WATER" <?=isset($grievance_department) && $grievance_department=="WATER" ? "select":"";?>>WATER</option>
                                    <option value="TRADE" <?=isset($grievance_department) && $grievance_department=="TRADE" ? "select":"";?>>TRADE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form_container">
                        <!-- QUERY BOX CONTAINER START HERE -->
                        <div class="query_box" id="query_box" style="display: none;">
                            <form method="post" enctype="multipart/form-data" id="formqueryValidate" action="<?php echo base_url('grievance_new/grievance_insert/'); ?>">
                                <input type="hidden" name="grievance_type" id="grievance_type" value="<?=isset($grievance_type)? $grievance_type : "query";?>">                                
                                <input type="hidden" name="module_id" id="module_id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-4"><b>Your Name: </b><span class="text-danger">*</span></label>
                                        <div class="col-md-8 pad-btm">
                                            <input type="text" name="owner_name" placeholder="Enter your Name" style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-4"><b>Mobile No. : </b><span class="text-danger">*</span></label>
                                        <div class="col-md-8 pad-btm">
                                            <input type="tel" name="mobile_no" id="mobile_nos" class="form-control" maxlength="10" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-4"><b>Your Query: </b><span class="text-danger">*</span></label>
                                        <div class="col-md-8 pad-btm">
                                            <textarea name="queries" id="queries" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row d-flex" style="text-align: center;">

                                    <input type="submit" name="querySave" id="querySave" value="Enquiry" class="btn btn-primary" style="padding: 5px; width:155px; margin-left:190px;">

                                </div>
                            </form>
                        </div>
                        <!-- QUERY BOX CONTAINER END HERE -->


                        <!-- COMPLAINT BOX START HERE -->
                        <div class="complain_box" id="complain_box" style="margin-top: 20px ;">
                            <form id="myForm" enctype="multipart/form-data" >
                                <input type="hidden" name="grievance_type" id="grievance_type" value="complain">

                                <label class="col-md-4" for="complain_type"><b>Complaint Against: </b><span class="text-danger">*</span></label>
                                <div class="col-md-8 pad-btm">
                                    <select name="complain_type" id="complain_type" class="form-control" onchange="showHideComplainType(this.value)">
                                        <option value="">Select Complain Type</option>
                                        <option value="Holding">Holding</option>
                                        <option value="SAF">SAF</option>
                                        <option value="Water Connection">Water Connection</option>
                                        <option value="Water Consumer">Water Consumer</option>
                                        <option value="Trade Application">Trade Application</option>
                                        <option value="Trade License">Trade License</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <!-- HOLDING COMPLAIN BOX START HERE -->
                                    <div style="padding: 15px;">
                                        <div class="row" id="app" style="display: none;">
                                            <label class="col-md-4" for="app_no">
                                                <b id="holding_complain_box_container">Holding No: </b>
                                                <b id="SAF_complain_box_container">SAF No: </b>
                                                <b id="water_consumer_container">Consumer No: </b>
                                                <b id="water_application_container">Water App No: </b>
                                                <b id="trade_application_container" >Trade App No: </b>
                                                <b id="trade_license_container">License No: </b>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-8 pad-btm">
                                                <input type="text" name="app_no" id="app_no" class="form-control" onChange="validate_app_no();"
                                                    value="<?php echo isset($holding_no) ? $holding_no : ""; ?>" onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Enter Holding No."required />
                                            </div>
                                        </div>

                                        <input type="hidden" name="app_id" id="app_id">
                                        <input type="hidden" name="module_id" id="module_id">

                                        <div class="row">
                                            <label class="col-md-4" for="complain_type_id">
                                                <b >Complain Type: </b>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-8 pad-btm" id="camp_type">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="holding_owner_name">Owner Name</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="owner_name" id="holding_owner_name" value="" placeholder="Owner Name">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="holding_mobile_no">Mobile No</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="number" name="mobile_no" style="width: 100%; margin-top:10px;" id="holding_mobile_no"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="holding_ward_no">Ward No</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="ward_no" id="holding_ward_no" value="Ward No">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="holding_address">Address</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea name="address" style="width: 100%; margin-top:10px;" id="holding_address"></textarea>
                                            </div>
                                        </div>
                                        

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="upload_file">Attachment</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="file" name="upload_file" accept="image/*,.pdf" style="width: 100%; margin-top:10px;" id="upload_file" />
                                            </div>
                                        </div>

                                        <div class="row d-flex" style="text-align: center;">
                                            <input type="submit" name="complainSave" id="complainSave" value="Register Complain" class="btn btn-primary" style="padding: 5px; width:155px; margin-left:190px;">
                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- COMPLAINT BOX END HERE -->

                        <!-- APPLICATION STATUS START HERE -->
                        <div class="application_status" id="application_status" style="margin-top: 15px; display:none">
                            <h1>Track Applications</h1>
                            <form action="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="token_no">Token No:</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="token_no"  style="width: 100%; margin-top:10px;" id="token_no" />
                                    </div>
                                </div>
                                <div class="row d-flex" style="text-align: center;">
                                    <input type="button" name="search" id="search" value="Search" class="btn btn-primary" style="padding: 5px; width:155px; margin-left:190px;" onclick="getDtlByToken()">
                                </div>
                                <div class="row table-responsive">
                                    <table class="table table-striped table-bordered text-center" id="result" style="display: none; bordered: 1px; ">
                                    </table>
                                </div>
                            </form>
                        </div>
                        <!-- APPLICATION STATUS END HERE -->
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?= $this->include('layout_home/footer'); ?>
    <script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>

    <script>
        $(document).ready(function() {
            showHideDiv($("#grievance_type").val());
        });
        function showLoading(){
            $("#loadingDiv").show();
        }

        function showHideDiv(grievance_type) {
            console.log("Grivance Type", grievance_type);
            $("#query_box").hide();
            $("#complain_box").hide();
            $("#application_status").hide();

            if (grievance_type == 'query') {
                $("#query_box").show();
            } else if (grievance_type == 'complain') {
                $("#complain_box").show();
            } else if (grievance_type == 'track_application') {
                $("#application_status").show();
            }
            changeInputs();
        }

        function changeInputs(){
            let grievance_type = $("#grievance_type").val();
            if(['complain','query'].includes(grievance_type)){  
                $("#department").show();              
                let department = $("#grievance_department").val();
                let complain_type ="<option value=''>Select Complain Type</option>";
                let module_id = "";
                switch(department){
                    case "PROPERTY": 
                        complain_type+="<option value='Holding'>Holding</option><option value='SAF'>SAF</option>";                        
                        module_id=1;
                        break;
                    case "WATER": 
                        complain_type+="<option value='Water Connection'>Water Connection</option><option value='Water Consumer'>Water Consumer</option>";                        
                        module_id=2;
                        break;
                    case "TRADE": 
                        complain_type+="<option value='Trade Application'>Trade Application</option><option value='Trade License'>Trade License</option>";                        
                        module_id=2;
                        break;
                }                
                $("#module_id").val(module_id);
                $("#complain_type").html(complain_type);
            }
            else{
                $("#department").hide();
            }
        }

        function showHideComplainType(complain_type) {
            console.log("Complain Type", complain_type);
            $("#app_no").val("");
            $("#holding_complain_box_container").hide();
            $("#SAF_complain_box_container").hide();
            $("#water_consumer_container").hide();
            $("#water_application_container").hide();
            $("#trade_application_container").hide();
            $("#trade_license_container").hide();
            $("#app").hide();            

            switch (complain_type) {
                case 'Holding':
                    $("#holding_complain_box_container").show();
                    $("#app_no").attr("placeholder","Enter Valid Holding No");
                    break;
                case 'SAF':
                    $("#SAF_complain_box_container").show();
                    $("#app_no").attr("placeholder","Enter Valid SAF No");
                    break;
                case 'Water Connection':
                    $("#water_application_container").show();
                    $("#app_no").attr("placeholder","Enter Valid Water Application No");
                    break;
                case 'Water Consumer':
                    $("#water_consumer_container").show();
                    $("#app_no").attr("placeholder","Enter Valid Consumer No");
                    break;

                case 'Trade Application':
                    $("#trade_application_container").show();
                    $("#app_no").attr("placeholder","Enter Valid Trade Application No");
                    break;

                case 'Trade License':
                    $("#trade_license_container").show();
                    $("#app_no").attr("placeholder","Enter Valid License No");
                    break;
            }
            if(complain_type!=""){
                $("#app").show();
                getComplainType();
            }
        }

        function getComplainType(){
            var module_id = $("#module_id").val();
            if(module_id){
                $("#camp_type").html("");
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url("grievance_new/getComplainType"); ?>',
                    dataType: "json",
                    data: {
                        "module_id": module_id
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success: function(data) {
                        $("#loadingDiv").hide();
                        console.log("data", data);

                        if (data.response == true) {
                            console.log("Response Array", data?.dd);
                            $("#camp_type").html(data.dd);
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

        function validate_app_no(){
            complain_type = $("#complain_type").val();
            switch (complain_type) {
                case 'Holding':
                    validate_holding();
                    break;
                case 'SAF':
                    validate_saf();
                    break;
                case 'Water Connection':
                    $("#water_application_container").show();
                    break;
                case 'Water Consumer':
                    $("#water_consumer_container").show();
                    break;

                case 'Trade Application':
                    $("#trade_application_container").show();
                    break;

                case 'Trade License':
                    $("#trade_license_container").show();
                    break;
            }
        }
        function validate_holding() {
            var holding_no = $("#app_no").val();

            console.log("Holding From Input", holding_no);

            // if(holding_no.length != 15 && holding_no.length != 0)
            if (!~jQuery.inArray(holding_no.length, [15, 16, 10, 11, 12, 13, 14]) && holding_no.length != 0) {
                alert('Please Enter 15 digit unique holding no');
                $("#holding_no").focus();
                return false;
            }

            if (holding_no) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url("grievance_new/validate_holding"); ?>',
                    dataType: "json",
                    data: {
                        "holding_no": holding_no
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success: function(data) {
                        $("#loadingDiv").hide();
                        console.log("data", data);

                        if (data.response == true) {
                            var holding_data = data.dd;
                            console.log("Response Array", holding_data);

                            if (holding_data.length > 0) {
                                var owner_names = holding_data.map(item => item.owner_name).join(', ');
                                $('#holding_owner_name').val(owner_names);
                                $('#holding_ward_no').val(holding_data[0].ward_no);
                                $('#holding_address').val(holding_data[0].prop_address);
                                $("#holding_mobile_no").val(holding_data[0].mobile_no);
                                $('#app_id').val(holding_data[0].id);
                            } else {
                                $('#holding_owner_name').val('No owners found');
                            }
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

        function validate_saf(){
            var saf_no = $("#app_no").val();

            console.log("saf_no From Input", saf_no);

            if (saf_no) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url("grievance_new/validate_saf"); ?>',
                    dataType: "json",
                    data: {
                        "saf_no": saf_no
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success: function(data) {
                        $("#loadingDiv").hide();
                        console.log("data", data);

                        if (data.response == true) {
                            var holding_data = data.dd;
                            console.log("Response Array", holding_data);

                            if (holding_data.length > 0) {
                                var owner_names = holding_data.map(item => item.owner_name).join(', ');
                                $('#holding_owner_name').val(owner_names);
                                $('#holding_ward_no').val(holding_data[0].ward_no);
                                $('#holding_address').val(holding_data[0].prop_address);
                                $("#holding_mobile_no").val(holding_data[0].mobile_no);
                                $('#app_id').val(holding_data[0].id);
                            } else {
                                $('#holding_owner_name').val('No owners found');
                            }
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

        function getDtlByToken(){
            var token = $("#token_no").val();
            console.log("token From Input", token);
            if (token) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url("grievance_new/getGrievanceDtl"); ?>',
                    dataType: "json",
                    data: {
                        "token_no": token
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success: function(data) {
                        $("#loadingDiv").hide();
                        console.log("data", data);

                        if (data.response == true) {
                            $("#result").show();
                            var result = data.data;
                            let keys = [];
                            for (let key in data?.heading) {
                                keys.push(key);
                            }


                            // List the Keys
                            let text = "";                            
                            
                            var i = 0;
                            var tr="";
                            var th="";
                            for (let h of keys) {
                                    th += "<th style='border:1px solid #ddd'>"+data?.heading[h] + "</th>";
                                }
                            tr+="<tr>"+th+"</tr>";
                            for (i;i<result.length ;i++) {                                                               
                                var dtl = result[i];
                                var td = "";
                                
                                for (let x of keys) {
                                    td += "<td style='border:1px solid #ddd'>"+(dtl[x] ? dtl[x] :"") + "</td>";
                                }
                                tr+="<tr>"+td+"</tr>";
                            } 
                            console.log("tr :",tr);
                            $("#result").html(tr);
                        }
                        else{

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

        $("#myForm").validate({
		
            rules: {
                "payment_mode": {
                    required: true,
                },
                "remarks": {
                    required: true,
                },
                "bank_name": {
                    required: function(element){
                        return $("#payment_mode").val()!="Cash";
                    },
                },
                "branch_name": {
                    required: function(element){
                        return $("#payment_mode").val()!="Cash";
                    },
                },
                "cheque_no1": {
                    required: function(element){
                        return $("#payment_mode").val()!="Cash";
                    },
                },
                "cheque_date": {
                    required: function(element){
                        return $("#payment_mode").val()!="Cash";
                    },
                },
                "total_payable_amount": {
                    required: function(element){
                        return $("#pay_advance").is(":checked");
                    },
                    min : function(element){
                        return Math.ceil($("#total_payable_amount_temp").html());
                    },
                    number: true,
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
                // do other things for a valid form
                var total_amt = parseFloat($("#total_payable_amount_temp").html());
                if($("#pay_advance").is(":checked"))
                {
                    var total_payable_amount = parseFloat($("#total_payable_amount").val());
                    
                    if(total_payable_amount <= total_amt)
                    {
                        alert("Amount Should be greater than Total Paybale Amount");
                        $("#total_payable_amount-error").html("Amount Should be greter than Total Paybale Amount !!!");
                        return false;
                    }
                }
                
                if(confirm("Are you sure want to pay now?"))
                {
                    //$("#loadingPaymentDiv").show();
                    //alert("Form submitted");
                    SubmitComplain();
                    //form.submit();
                }
            }
        });
        function SubmitComplain(){
            
            // var formData = new FormData();
            var form = document.getElementById('myForm');
            var formData = new FormData(form); // Create a new FormData object from the form
            formData.append('ajax', true)

            // Loop through the FormData entries and log the name and value
            for (var [name, value] of formData.entries()) {
                console.log(name, value);
                
            }
            // console.log("formData:",formData);return false;
            // var file = $('#upload_file')[0].files[0]; // Get the file input
            // if(file){
            //     formData.append('upload_file', file); // Append the file to the FormData object
            // }
            

            $.ajax({
                url: "<?=base_url('/grievance_new/grievance_insert');?>", // Replace with your server URL
                type: 'POST',
                data: formData,
                processData: false,  // Prevent jQuery from automatically transforming the data into a query string
                contentType: false,  // Tell jQuery not to set the contentType
                success: function(response){
                    $("#loadingDiv").hide();
                    response = JSON.parse(response);
                    if(response.response){

                    }else{
                        var list = "";
                        validation = Object.values(response.validation);
                        for (let index = 0; index < validation.length; index++) {
                            list += "<li>"+validation[index]+"</li>";                            
                        }
                        console.log("validation:",validation);
                        $("#error").html("<ul>"+list+"</ul>")
                    }
                },
                error: function(xhr, status, error){
                    console.error('File upload failed:', status, error);
                }
            });

        }
    </script>