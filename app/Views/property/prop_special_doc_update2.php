<?= $this->include('layout_vertical/header'); ?>
<style type="text/css">
    input {
        cursor: pointer;
    }

    input[type='checkbox'] {
        width: 17px;
        height: 17px;

    }
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Holding</a></li>
            <li><a href="#">Search Property</a></li>
            <li class="active">Update Special</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">


        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Special Document Update</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-sm">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Ward No</th>
                                        <th>Old Holding No</th>
                                        <th>New Holding No</th>
                                        <th>Address</th>
                                        <!-- <th>Ward</th> -->
                                        <!-- <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                            <th>DOB</th>
                                            <th>Gender</th>
                                            <th>Is_Specially_Abled</th>
                                            <th>Is_Armed_Force</th> -->
                                    </tr>
                                </thead>
                                <tbody id="owner_dtl_append">
                                    <?php

                                    if (isset($prop_dtl_basic)) {
                                        // foreach ($owner_details_list as $owner_detail)
                                        // {

                                    ?>
                                        <tr>
                                            <td>
                                                <?= $prop_dtl_basic['ward_no']; ?>
                                            </td>
                                            <td>
                                                <?= $prop_dtl_basic['holding_no']; ?>
                                            </td>
                                            <td>
                                                <?= $prop_dtl_basic['new_holding_no']; ?>
                                            </td>
                                            <td>
                                                <?= $prop_dtl_basic['prop_address']; ?>
                                            </td>


                                        </tr>
                                    <?php
                                        // }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!------- Panel Owner Details-------->
        <div class="container" style="background-color: white;padding:10px 10px 100px 20px">
            <form method="post" enctype="multipart/form-data">
                <div class="row" style="margin-top: 20px;">
                    <div class="row">

                        <div class="col-sm-3 pad-btm">
                            <span>Select Owner</span>
                            <select onchange="show_file_input(this)" id="prop_owner_details_id" name="prop_owner_details_id" class="form-control">
                                <!-- <select id="prop_owner_details_id" name="prop_owner_details_id" class="form-control"> -->
                                <option value="">== SELECT ==</option>
                                <?php if (isset($owner_list)) {

                                    foreach ($owner_list as $key => $owner) { ?>
                                        <option value="<?= $owner['id'] ?>"><?= $owner['owner_name'] ?></option>
                                <?php }
                                } ?>

                            </select>
                        </div>


                    </div>




                    <div id="main_form_container" class="container" style="padding-right:200px;display:none">
                        <div style="border:1px solid grey;padding: 10px 10px 10px 10px;box-shadow:6px 6px 6px grey;">
                            <!-- <div class="row">
                                <div class="col-sm-3">
                                    <h5>Type</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h5>Options</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h5>Document</h5>
                                </div>
                                <div class="col-sm-3">
                                    <h5>Status</h5>
                                </div>
                            </div> -->
                            <!-- <hr> -->
                            <div class="row" style="margin-top: 20px;display:block;" id="female_container">
                                <div class="col-sm-3">
                                    <div>

                                        <input hidden="" type="text" id="gender_status" name="gender_status" value="0">
                                        <div class="form-check">
                                            <input onchange="toggle_container(this)" class="form-check-input" type="checkbox" value="" id="gender_id">
                                            <label class="form-check-label" for="gender_id">
                                                Gender
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 gender_class" style="display: block" id="gender_input">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender_type" id="male" value="Male">
                                        <label class="form-check-label" for="male">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender_type" id="female" checked value="Female">
                                        <label class="form-check-label" for="female">
                                            Female
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender_type" id="other" checked value="Other">
                                        <label class="form-check-label" for="other">
                                            Other
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3 gender_class" style="display: block">
                                    <div id="gender_doc_input" style="display:block;">
                                        <input accept="application/pdf" id="gender_doc_input_value" name="gender_doc" type="file">
                                        <input hidden id="prev_prop_special_gender_id" value="" name="prev_prop_special_gender_id" type="text">
                                    </div>
                                    <div id="gender_doc_image" style="display:block;">
                                        <a id="gender_link" href="" target="_blank">
                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3 gender_class" style="display: block">
                                    <div id="gender_status_text" style="display:block">
                                        <span>No Document Uploaded</span>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <!-- dob -->
                            <div class="row" style="margin-top: 20px;display:block" id="senior_container">
                                <div class="col-sm-3">
                                    <div>

                                        <input hidden="" type="text" id="dob_status" name="dob_status" value="0">
                                        <div class="form-check">
                                            <input onchange="toggle_container(this)" class="form-check-input" type="checkbox" value="" id="dob_id">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                DOB
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 dob_class" style="display: block" id="dob_input">
                                    <div>


                                        <input id="dob_date_value" type="date" name="dob_input" class="form-control" accept=".pdf">
                                        <input hidden id="prev_prop_special_dob_id" value="" name="prev_prop_special_dob_id" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-3 dob_class" style="display: block">
                                    <div id="dob_doc_input" style="display:block;">
                                        <input accept="application/pdf" id="dob_input_value" name="dob_doc" type="file">
                                    </div>
                                    <div id="dob_doc_image" style="display:block">
                                        <a id="dob_link" href="" target="_blank">
                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3 dob_class" style="display: block">
                                    <div id="dob_status_text" style="display:block">
                                        <span>No Document Uploaded</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- handicapped -->
                            <div class="row" style="margin-top: 20px;display:block" id="is_special_container">
                                <div class="col-sm-3">
                                    <div>

                                        <input hidden="" type="text" id="handicapped_status" name="handicapped_status" value="0">
                                        <div class="form-check">
                                            <input onchange="toggle_container(this)" class="form-check-input" type="checkbox" value="" id="hadicapped_id">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Specially-Abled(Handicapped)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 handicapped_class" style="display: block" id="handicapped_input">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="handicapped_radio" value="yes">
                                        <label class="form-check-label" for="male">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="handicapped_radio" checked value="no">
                                        <label class="form-check-label" for="female">
                                            No
                                        </label>
                                    </div>

                                </div>
                                <div class="col-sm-3 handicapped_class" style="display: block">
                                    <div id="handicapped_doc_input" style="display:block;">
                                        <input accept="application/pdf" id="hadicapped_input_value" name="handicapped_doc" type="file">
                                        <input hidden id="prev_prop_special_handicapped_id" value="" name="prev_prop_special_handicapped_id" type="text">
                                    </div>
                                    <div id="handicapped_doc_image" style="display:block">
                                        <a id="handicapped_link" href="" target="_blank">
                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3 handicapped_class" style="display: block">
                                    <div id="handicapped_status_text" style="display:block">
                                        <span>No Document Uploaded</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- //armed -->
                            <div class="row" style="margin-top: 20px;display:block" id="armed_container">
                                <div class="col-sm-3">
                                    <div>

                                        <input hidden="" type="text" id="armed_status" name="armed_status" value="0">
                                        <div class="form-check">
                                            <input onchange="toggle_container(this)" class="form-check-input" type="checkbox" value="" id="armed_id">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Armed Force
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 armed_class" style="display: block" id="armed_input">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="armed_radio" value="yes">
                                        <label class="form-check-label" for="male">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="armed_radio" checked value="no">
                                        <label class="form-check-label" for="female">
                                            No
                                        </label>
                                    </div>

                                </div>
                                <div class="col-sm-3 armed_class" style="display: block">
                                    <div id="armed_doc_input" style="display:block;">
                                        <input accept="application/pdf" id="armed_input_value" name="armed_doc" type="file">
                                        <input hidden id="prev_prop_special_armed_id" value="" name="prev_prop_special_armed_id" type="text">

                                    </div>
                                    <div id="armed_doc_image" style="display:block">
                                        <a id="armed_link" href="" target="_blank">
                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-3 armed_class" style="display: block">
                                    <div id="armed_status_text" style="display:block">
                                        <span>No Document Uploaded</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row" style="margin-top: 20px;display:block" id="transgender_container">
                    
                        <div class="col-sm-3">
                            <div>
                                <span> Transgender Document <span class="text text-danger">(Only .pdf allowed)</span></span>
                                <input hidden="" type="text" id="transgender_doc_status" name="transgender_doc_status" value="0">
                                <input type="file" id="transgender_document_input" name="transgender_doc" class="form-control" accept=".pdf">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="transgender_document_image" style="display:block">
                                <a id="transgender_document" href="" target="_blank">
                                    <img src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="transgender_document_text" style="display:block">
                                <span>No Document Uploaded</span>
                            </div>
                        </div>
                    </div> -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-sm-12">
                                <button class="btn btn-md btn-success" style="display: none;margin:auto" id="upload_button" onclick="return validate_data()" name="upload_special_doc" type="submit">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

        </form>




    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer'); ?>

<script>
    function validate_data() {
        if (document.getElementById('gender_id').checked == true) {
            if ($('#gender_doc_input_value').val() == '') {
                alert('select document for gender')
                return false
            }
        }
        if (document.getElementById('dob_id').checked == true) {
            if ($('#dob_date_value').val() == '') {
                alert('Select DOB')
                return false
            }
            if ($('#dob_input_value').val() == '') {
                alert('select document for DOB')
                return false
            }
        }
        if (document.getElementById('hadicapped_id').checked == true) {
            let handicapped_in_val = $("input[name='handicapped_radio']:checked").val()
            if (handicapped_in_val == "yes") {
                if ($('#hadicapped_input_value').val() == '') {
                    alert('select document for Specially Abled')
                    return false
                }
            } 

        }
        if (document.getElementById('armed_id').checked == true) {
            let armed_in_val = $("input[name='armed_radio']:checked").val()
            if (armed_in_val == "yes") {
                if ($('#armed_input_value').val() == '') {
                    alert('select document for Armed Force')
                    return false
                }
            } 

        }
        if (document.getElementById('gender_id').checked == false && document.getElementById('dob_id').checked == false && document.getElementById('hadicapped_id').checked == false && document.getElementById('armed_id').checked == false) {
            alert('No Category Selected')
            return false
        }
        if (confirm("Confirm Upload Document!") == true) {
            return true
        } else {
            return false
        }

    }

    function toggle_container(e) {
        // console.log(e.id);
        // console.log(e.checked)
        // return;
        // if(e.id=='gender_id'){
        // $('.gender_class').css('display','block')
        // $('.dob_class').css('display','none')
        // $('.handicapped_class').css('display','none')
        // $('.armed_class').css('display','none')
        // }
        // if(e.id=='dob_id'){
        // $('.gender_class').css('display','none')
        // $('.dob_class').css('display','block')
        // $('.handicapped_class').css('display','none')
        // $('.armed_class').css('display','none')
        // }
        // if(e.id=='handicapped_id'){
        // $('.gender_class').css('display','none')
        // $('.dob_class').css('display','none')
        // $('.handicapped_class').css('display','block')
        // $('.armed_class').css('display','none')
        // }
        // if(e.id=='armed_id'){
        // $('.gender_class').css('display','none')
        // $('.dob_class').css('display','none')
        // $('.handicapped_class').css('display','none')
        // $('.armed_class').css('display','block')
        // }
        if (e.id == 'gender_id') {
            if (e.checked == true) {
                // $('.gender_class').css('display', 'block')
                $('#gender_status').val('1')
                console.log($('#gender_status').val())
                // $("#gender_radio_input").prop('required',true);
            } else {
                // $('.gender_class').css('display', 'none')
                $('#gender_status').val('0')
                console.log($('#gender_status').val())

                // $("#gender_radio_input").prop('required',false);


            }

        }
        if (e.id == 'dob_id') {
            if (e.checked == true) {
                // $('.dob_class').css('display', 'block')
                $('#dob_status').val('1')
                console.log($('#dob_status').val())

                // $("#dob_radio_input").prop('required',true);

            } else {
                // $('.dob_class').css('display', 'none')
                $('#dob_status').val('0')
                console.log($('#dob_status').val())

                // $("#dob_radio_input").prop('required',false);

            }

        }
        if (e.id == 'hadicapped_id') {
            if (e.checked == true) {
                // $('.handicapped_class').css('display', 'block')
                $('#handicapped_status').val('1')
                console.log($('#handicapped_status').val())

                // $("#handicapped_radio_input").prop('required',true);

            } else {
                // $('.handicapped_class').css('display', 'none')
                $('#handicapped_status').val('0')
                console.log($('#handicapped_status').val())

                // $("#handicapped_radio_input").prop('required',false);

            }

        }
        if (e.id == 'armed_id') {
            if (e.checked == true) {
                // $('.armed_class').css('display', 'block')
                $('#armed_status').val('1')
                console.log($('#armed_status').val())

                // $("#armed_radio_input").prop('required',true);

            } else {
                // $('.armed_class').css('display', 'none')
                $('#armed_status').val('0')
                console.log($('#armed_status').val())

                // $("#armed_radio_input").prop('required',false);

            }

        }
    }

    //1 owner type condition base input show
    //2 doc_status condition wise
    //3 verficaton status
    function show_file_input(e) {

        $("#gender_id").prop('checked', false);
        $("#gender_id").show()
        $('#gender_input').css('display', 'block')
        $('#gender_doc_input').css('display', 'block')
        $('#gender_doc_image').css('display', 'none')
        $('#gender_status_text').css('display', 'block')
        $('#gender_status_text').html('No Documents Uploaded')
        $('#gender_link').attr("href", '')
        $('#gender_status').val('0')
        $('#prev_prop_special_gender_id').val('')

        $("#dob_id").prop('checked', false);
        $("#dob_id").show()
        $('#dob_input').css('display', 'block')
        $('#dob_doc_input').css('display', 'block')
        $('#dob_doc_image').css('display', 'none')
        $('#dob_status_text').css('display', 'block')
        $('#dob_status_text').html('No Documents Uploaded')
        $('#dob_link').attr("href", '')
        $('#dob_status').val('0')
        $('#prev_prop_special_dob_id').val('')




        $("#hadicapped_id").prop('checked', false);
        $("#hadicapped_id").show()
        $('#handicapped_input').css('display', 'block')
        $('#handicapped_doc_input').css('display', 'block')
        $('#handicapped_doc_image').css('display', 'none')
        $('#handicapped_status_text').css('display', 'block')
        $('#handicapped_status_text').html('No Documents Uploaded')
        $('#handicapped_link').attr("href", '')
        $('#handicapped_status').val('0')
        $('#prev_prop_special_handicapped_id').val('')




        $('#armed_id').prop('checked', false);
        $("#armed_id").show()
        $('#armed_input').css('display', 'block')
        $('#armed_doc_input').css('display', 'block')
        $('#armed_doc_image').css('display', 'none')
        $('#armed_status_text').css('display', 'block')
        $('#armed_status_text').html('No Documents Uploaded')
        $('#armed_link').attr("href", '')
        $('#armed_status').val('0')
        $('#prev_prop_special_armed_id').val('')





        var var_input
        var var_doc_input
        var var_doc_image
        var var_status_text

        var onwer_id = e.value
        if (e.value == '') {

            $('#main_form_container').css('display', 'none')
            $('#upload_button').css('display', 'none')

            return
        }
        $('#main_form_container').css('display', 'block')
        $('#upload_button').css('display', 'block')




        try {
            $.ajax({
                type: "POST",
                url: "<?= base_url('PropSpecialDocUpload/ajaxgetPropOwnerData'); ?>",
                dataType: "json",
                data: {
                    "ownerId": onwer_id
                },
                beforeSend: function() {
                    $("#btn_search").val("LOADING ...");
                    $("#loadingDiv").show();
                    console.log('beofore calling');
                },
                success: function(data) {
                    $("#loadingDiv").hide();
                    console.log("response ", data)

                    if (data['ownerSpecialData'].length > 0) {
                        var dataLengh = data['ownerSpecialData'].length;
                        for (var i = 0; i <= dataLengh - 1; i++) {


                            if (data['ownerSpecialData'][i].other_doc == 'gender_document') {
                                var_ck_id = '#gender_id'
                                var_input = '#gender_input'
                                var_doc_input = '#gender_doc_input'
                                var_doc_image = '#gender_doc_image'
                                var_status_text = '#gender_status_text'
                                var_link = '#gender_link'
                                var_status = '#gender_status'
                                var_prev_prop_doc_id = '#prev_prop_special_gender_id'
                            }
                            if (data['ownerSpecialData'][i].other_doc == 'dob_document') {
                                var_ck_id = '#dob_id'
                                var_input = '#dob_input'
                                var_doc_input = '#dob_doc_input'
                                var_doc_image = '#dob_doc_image'
                                var_status_text = '#dob_status_text'
                                var_link = '#dob_link'
                                var_status = '#dob_status'
                                var_prev_prop_doc_id = '#prev_prop_special_dob_id'




                            }
                            if (data['ownerSpecialData'][i].other_doc == 'handicaped_document') {
                                var_ck_id = '#hadicapped_id'
                                var_input = '#handicapped_input'
                                var_doc_input = '#handicapped_doc_input'
                                var_doc_image = '#handicapped_doc_image'
                                var_status_text = '#handicapped_status_text'
                                var_link = '#handicapped_link'
                                var_status = '#handicapped_status'
                                var_prev_prop_doc_id = '#prev_prop_special_handicapped_id'



                            }
                            if (data['ownerSpecialData'][i].other_doc == 'armed_force_document') {
                                var_ck_id = '#armed_id'
                                var_input = '#armed_input'
                                var_doc_input = '#armed_doc_input'
                                var_doc_image = '#armed_doc_image'
                                var_status_text = '#armed_status_text'
                                var_link = '#armed_link'
                                var_status = '#armed_status'
                                var_prev_prop_doc_id = '#prev_prop_special_armed_id'


                            }

                            //verfication level

                            if (data['ownerSpecialData'][i].verify_status == '0') {
                                $(var_prev_prop_doc_id).val(data['ownerSpecialData'][i].id)
                                $(var_ck_id).prop('checked', false);
                                $(var_ck_id).hide()
                                $(var_input).css('display', 'none')
                                $(var_doc_input).css('display', 'none')
                                $(var_doc_image).css('display', 'block')
                                $(var_status_text).css('display', 'block')
                                $(var_status_text).html('<p class="label label-primary" style="color:white">Pending...</p>')
                                $(var_link).attr("href", "<?= base_url(); ?>/getImageLink.php?path=" + data['ownerSpecialData'][i].doc_path)

                            }
                            if (data['ownerSpecialData'][i].verify_status == '1') {
                                $(var_prev_prop_doc_id).val(data['ownerSpecialData'][i].id)
                                $(var_ck_id).prop('checked', true);
                                $(var_input).css('display', 'block')
                                $(var_doc_input).css('display', 'block')
                                $(var_doc_image).css('display', 'block')
                                $(var_status_text).css('display', 'block')
                                $(var_status_text).html('<p class="label label-success" style="color:white">Verified...</p>')
                                $(var_link).attr("href", "<?= base_url(); ?>/getImageLink.php?path=" + data['ownerSpecialData'][i].doc_path)
                                $(var_status).val('1')

                            }
                            if (data['ownerSpecialData'][i].verify_status == '2') {
                                $(var_prev_prop_doc_id).val(data['ownerSpecialData'][i].id)
                                $(var_ck_id).prop('checked', true);
                                $(var_input).css('display', 'block')
                                $(var_doc_input).css('display', 'block')
                                $(var_doc_image).css('display', 'block')
                                $(var_status_text).css('display', 'block')
                                $(var_status_text).html('<p class="label label-danger" style="color:white">Rejected...</p>')
                                $(var_link).attr("href", "<?= base_url(); ?>/getImageLink.php?path=" + data['ownerSpecialData'][i].doc_path)
                                $(var_status).val('1')


                            }


                        }

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });
        } catch (err) {
            alert(err.message);
        }
    }

    function upload_data() {

    }
</script>