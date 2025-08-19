<?= $this->include('layout_vertical/header'); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Property</a></li>
            <li class="active">Upload Consession Document View</li>
        </ol>
    </div>
    <!--Page content-->
    <div id="page-content">


        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-sm">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Ward No</th>
                                        <th>Old Holding No.</th>
                                        <th>New Holding No.</th>
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

                                    if (isset($prop_dtl)) {
                                        // foreach ($owner_details_list as $owner_detail)
                                        // {

                                    ?>
                                        <tr>
                                            <td>
                                                <?= $prop_dtl['ward_mstr_id']; ?>
                                            </td>
                                            <td>
                                                <?= $prop_dtl['holding_no']; ?>
                                            </td>
                                            <td>
                                                <?= $prop_dtl['new_holding_no']; ?>
                                            </td>
                                            <td>
                                                <?= $prop_dtl['prop_address']; ?>
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
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Upload Owner Document</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-sm">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <!-- <th>Applicant Image</th>
                                            <th>Applicant Document</th> -->
                                        <th>Upload</th>
                                        <th>Owner Name</th>
                                        <th>Guardian Name</th>
                                        <th>Relation</th>
                                        <th>Mobile No</th>
                                        <th>Aadhar No.</th>
                                        <th>PAN No.</th>
                                        <th>Email ID</th>
                                    </tr>
                                </thead>
                                <tbody id="owner_dtl_append">
                                    <?php
                                    $everyDocUploaded = true;
                                    if (isset($sepcial_doc_dtl_grouped_by_owner)) {
                                        foreach ($sepcial_doc_dtl_grouped_by_owner as $key => $owner_detail) {
                                            //print_var($owner_detail);
                                    ?>
                                            <tr>





                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#owner_details_modal<?= $owner_detail["prop_owner_details_id"]; ?>">Click here to upload</button>
                                                    <!-- Owner Doc Upload Modal -->
                                                    <div class="modal fade" id="owner_details_modal<?= $owner_detail["prop_owner_details_id"]; ?>" role="dialog">
                                                        <div class="modal-dialog modal-lg">
                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">Owner Document</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="<?= base_url('UploadConessionDocument/ConsessionDocUpload'); ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" name="prop_owner_details_id" id="prop_owner_details_id<?= $key ?>" value="<?= $owner_detail["prop_owner_details_id"]; ?>" />
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered text-sm">
                                                                                <tr>
                                                                                    <td><b>Name</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['owner_name']; ?></td>
                                                                                    <td><b>Relation</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['relation_type']; ?></td>
                                                                                    <td><b>Guardian Name</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['guardian_name']; ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Mobile</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['mobile_no']; ?></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td><b>Aadhar No.</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['aadhar_no']; ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>Pan No.</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['pan_no']; ?></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td><b>Email Id</b></td>
                                                                                    <td>:</td>
                                                                                    <td><?= $owner_detail['email']; ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Gender Document<span style="color:red;"> *</span></td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3"><img /></td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .pdf allowed</span>
                                                                                        <input hidden type="text" value="1" name="gender_status" />
                                                                                        <input hidden type="text" value="" name="gender_prop_special_doc_insert_id" id="gender_prop_special_doc_insert_id<?= $key ?>" />
                                                                                        <input type="file" name="gender_doc_file" class="form-control" accept=".pdf" required />
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>DOB Document<span style="color:red"> *</span></td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3"><img /></td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .pdf allowed</span>
                                                                                        <input hidden type="text" value="1" name="dob_status" />
                                                                                        <input hidden type="text" value="" name="dob_prop_special_doc_insert_id" id="dob_prop_special_doc_insert_id<?= $key ?>" />
                                                                                        <input type="file" name="dob_doc_file" class="form-control" accept=".pdf" required />
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="handicapped_row<?=$key?>">
                                                                                    <td>Handicapped Document</td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3"><img /></td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .pdf allowed</span>
                                                                                        <input hidden type="text" value="" name="handicapped_status" id="handicapped_status<?= $key ?>" />
                                                                                        <input hidden type="text" value="" name="handicapped_prop_special_doc_insert_id" id="handicapped_prop_special_doc_insert_id<?= $key ?>" />
                                                                                        <input type="file"
                                                                                         name="handicapped_doc_file" id="handicapped_doc_fil<?= $key ?>" class="form-control" accept=".pdf" />
                                                                                    </td>
                                                                                </tr>
                                                                                <tr id="armed_row<?=$key?>"  >
                                                                                    <td>Armed Force Document</td>
                                                                                    <td>:</td>
                                                                                    <td colspan="3">

                                                                                    </td>
                                                                                    <td colspan="4">
                                                                                        <span class="text text-danger">Only .pdf allowed</span>
                                                                                        <input hidden type="text" value="" name="armed_status" id="armed_status<?= $key ?>" />
                                                                                        <input hidden type="text" value="" name="armed_prop_special_doc_insert_id" id="armed_prop_special_doc_insert_id<?= $key ?>" />
                                                                                        <input type="file" name="armed_doc_file" id="armed_doc_file<?= $key ?>" id="armed_doc_file" class="form-control" accept=".pdf" />
                                                                                    </td>
                                                                                </tr>


                                                                                <tr>
                                                                                    <td colspan="9" class="text-right">
                                                                                        <a onclick="return getPropData( <?= $key; ?>)"   class="btn btn-success" >Upload doc </a>
                                                                                        <input style="visibility: hidden;"  type="submit" name="btn_owner_doc_upload" id="btn_owner_doc_upload<?= $key; ?>" class="btn btn-success" value="UPLOAD" />
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['owner_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['guardian_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['relation_type']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['mobile_no']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['aadhar_no']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['pan_no']; ?>
                                                </td>
                                                <td>
                                                    <?= $owner_detail['email']; ?>
                                                </td>

                                            </tr>
                                    <?php
                                        }
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!------- End Panel Owner Details-------->


        <!--------prop doc------------>







        <!-- End page content-->
    </div>
    <?= $this->include('layout_vertical/footer'); ?>

    <script>
        function getPropData(e) {
            var onwer_id = $('#prop_owner_details_id' + e).val()

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
                        // return false

                        if (data['ownerSpecialData'].length > 0) {
                            var dataLengh = data['ownerSpecialData'].length;
                            for (var i = 0; i <= dataLengh - 1; i++) {


                                if (data['ownerSpecialData'][i].other_doc == 'gender_document') {

                                    console.log('setting gender doc id ', data['ownerSpecialData'][i].id)
                                    $('#gender_prop_special_doc_insert_id' + e).val(data['ownerSpecialData'][i].id)
                                }
                                if (data['ownerSpecialData'][i].other_doc == 'dob_document') {

                                    console.log('setting dob doc id ', data['ownerSpecialData'][i].id)
                                    $('#dob_prop_special_doc_insert_id' + e).val(data['ownerSpecialData'][i].id)




                                }
                                if (data['ownerSpecialData'][i].other_doc == 'handicaped_document') {

                                    if (data['ownerSpecialData'][i].is_specially_abled == 'yes') {
                                        $('#handicapped_status' + e).val('1')
                                    } else {
                                        $('#handicapped_status' + e).val('0')
                                    }
                                    console.log('setting handi doc id ', data['ownerSpecialData'][i].id)

                                    $('#handicapped_prop_special_doc_insert_id' + e).val(data['ownerSpecialData'][i].id)
                                    if(data['ownerSpecialData'][i].is_specially_abled == 'yes'){
                                        $("#handicapped_doc_fil"+e).prop('required',true);
                                        // $('#handicapped_row' + e).css('display','block')

                                    }else{
                                        $("#handicapped_doc_fil"+e).prop('required',false);
                                        // $('#handicapped_row' + e).css('display','none')
                                    }



                                }
                                if (data['ownerSpecialData'][i].other_doc == 'armed_force_document') {

                                    if (data['ownerSpecialData'][i].is_armed_force == 'yes') {
                                        $('#armed_status' + e).val('1')
                                    } else {
                                        $('#armed_status' + e).val('0')
                                    }
                                    console.log('setting armed doc id ', data['ownerSpecialData'][i].id)

                                    $('#armed_prop_special_doc_insert_id' + e).val(data['ownerSpecialData'][i].id)
                                    if(data['ownerSpecialData'][i].is_armed_force == 'yes'){
                                        $("#armed_doc_file"+e).prop('required',true);
                                        // $('#armed_row' + e).css('display','block')
                                    }else{
                                        $("#armed_doc_file"+e).prop('required',false);
                                        // $('#armed_row' + e).css('display','block')

                                    }


                                }



                            }

                            $('#btn_owner_doc_upload'+e).click()

                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('error')
                    }
                });
            } catch (err) {
                alert(err.message);
            }
            // return false
        }
    </script>