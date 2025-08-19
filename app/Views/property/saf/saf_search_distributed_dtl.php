<?= $this->include('layout_vertical/header'); ?>
<style>
    .error {
        color: red;
    }
    @media (max-width:760px) {
        .fixed_container{

            width: 86vw;
        }
    }
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Receive SAF Search</a></li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="search_form" method="post" action="<?= base_url('saf/searchDistributedDtl'); ?>">

            <?php
            if (isset($validation)) {
            ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10 text-danger">
                                <?php
                                foreach ($validation as $errMsg) {
                                    echo $errMsg;
                                    echo ".<br />";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                        <!-- <a href="<?= base_url(); ?>/Saf/addupdate" class="btn btn-mint">Apply Assessment without SAF Form No</a> -->
                        <button type="button" class="btn btn-mint" data-target="#existing_holding_details-lg-modal" data-toggle="modal">Apply without SAF Form No</button>
                    </div>
                    <h3 class="panel-title">Receive SAF Form Search</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-2">SAF Form No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="form_no" name="form_no" class="form-control" placeholder="Enter SAF Form No." maxlength="15" value="<?= $form_no ?? NULL; ?>" />
                        </div>
                        <div class="col-md-3 pad-btm">
                            <button type="submit" id="search" name="search" class="btn btn-primary" value="search">SEARCH</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (isset($saf_distributed_dtl_list)) {
            ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">SAF Detail List</h3>
                    </div>
                    <div class="panel-body">
                        <div id="saf_distributed_dtl_hide_show">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-trans-dark text-dark">
                                                <tr>
                                                    <th>SAF Form No.</th>
                                                    <th>Ward No</th>
                                                    <th>Owner Name</th>
                                                    <th>Phone No</th>
                                                    <th>Address</th>
                                                    <th>SAF No</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_saf_distributed_dtl">
                                                <?php
                                                if (!empty($saf_distributed_dtl_list)) {
                                                    foreach ($saf_distributed_dtl_list as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?= $row['form_no']; ?></td>
                                                            <td><?= $row['ward_no']; ?></td>
                                                            <td><?= $row['owner_name']; ?></td>
                                                            <td><?= $row['phone_no']; ?></td>
                                                            <td><?= $row['owner_address']; ?></td>
                                                            <td><?= $row['saf_no']; ?></td>
                                                            <td>
                                                                <?php
                                                                if ($row['saf_no'] == "" || $row['saf_no'] == NULL) {
                                                                ?>
                                                                    <!-- <a href="<?= base_url(''); ?>/Saf/searchDistributedDtl/<?= md5($row['id']); ?>" class="btn btn-primary">Apply</a> -->
                                                                    <button type="button" id="saf_form_btn" class="btn btn-mint" data-target="#existing_holding_details-lg-modal" data-toggle="modal" value="<?= md5($row['id']); ?>">Apply</button>

                                                                <?php
                                                                }
                                                                ?>
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
                </div>
            <?php
            }
            ?>
        </form>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<!--existing_holding_details Bootstrap Modal-->
<div id="existing_holding_details-lg-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel">Existing Holding Details</h4>
            </div>
            <div class="modal-body">
                <p>Does the property being assessed has any previous Holding Number?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="callModalHoldingDetails();">YES</button>
                <!-- <a href="<?= base_url(); ?>/saf/AddUpdate2" type="button" class="btn btn-primary">No</a> -->


                <a id="link_without" href="<?= base_url('saf/searchDistributedDtl/New-Assessment'); ?>" type="button" class="btn btn-primary">No</a>
                <a style="display: none;" id="link_with_form_id" href="#" type="button" class="btn btn-primary">No</a>

            </div>
        </div>
    </div>
</div>
<!--End existing_holding_details Bootstrap Modal-->
<!--holding_owner_details Bootstrap Modal-->
<div id="holding_owner_details-lg-modal" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Holding Owner Details</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="form_id" name="form_id" value="" />
                    <input type="hidden" id="holding_id" name="holding_id" value="" />
                    <input type="hidden" id="holding_no" name="holding_no" value="" />
                    <input type="hidden" id="ward_mstr_id" name="ward_mstr_id" value="" />
                    <p> Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="YES" class="btn btn-primary assessmentTypeSubmit" value="YES" data-toggle="tooltip" title="Re-Assessment">YES</button>
                    <button type="submit" id="btn_click_for_mutation" name="NO" class="btn btn-primary assessmentTypeSubmit" value="NO" data-toggle="tooltip" title="Mutation">No</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="assessmentTypeModel" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Holding Owner Details</h4>
                </div>
                <div class="modal-body">
                    <p> Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" name="YES" class="btn btn-primary assessmentType" value="YES" data-toggle="tooltip" title="Re-Assessment">YES</button>
                    <button type="button"  name="NO" class="btn btn-primary assessmentType" value="NO" data-toggle="tooltip" title="Mutation">No</button>
                </div>
        </div>
    </div>
</div>
<!--End holding_owner_details Bootstrap Modal-->
<!--existing_holding_details Bootstrap Modal-->
<div id="holding_details-lg-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel">Holding Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="newAssessMentType" name="newAssessMentType">
                    <input type="hidden" id="newAssessMentTypeButtonValue" name="newAssessMentTypeButtonValue">
                    <label class="col-md-2 pad-btm text-center">Ward No <span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <select id="ward_mstr_id_demo" class="form-control">
                            <option value="">== SELECT ==</option>
                            <?php
                            if (isset($wardList)) {
                                foreach ($wardList as $ward) {
                            ?>
                                    <option value="<?= $ward['id']; ?>" <?= (isset($ward_mstr_id)) ? ($ward['id'] == $ward_mstr_id) ? "selected" : "" : ""; ?>><?= $ward['ward_no']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <label class="col-md-3 pad-btm text-center">Holding No <span class="text-danger">*</span></label>
                    <div class="col-md-4 pad-btm">
                        <input type="text" id="previous_holding_no" name="previous_holding_no" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 pad-btm">
                        <button type="button" id="holding_search" name="holding_search" class="btn btn-block btn-mint" onclick="searchHoldingNo();">Search</button>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <div class="row">
                    <div class="col-md-12 hidden" id="searchErrMsgHideShow">
                        <span class="text-danger">Data not found<span>
                    </div>
                    <div class="fixed_container" style="overflow: hidden;">
                        <div class="col-md-12 hidden table-responsive" id="searchResultTableHideShow" style="width:100%">
                            <table class="table table-bordered text-sm">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Holding No</th>
                                        <th>Owner Name</th>
                                        <th>Guardian Name</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End existing_holding_details Bootstrap Modal-->
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $("#saf_form_btn").click(function() {
        let saf_form_id = $("#saf_form_btn").val();
        $("#form_id").val($("#saf_form_btn").val());
        $('#link_without').hide();
        $('#link_with_form_id').show();
        $('#link_with_form_id').attr('href', `<?= base_url('saf/searchDistributedDtl/New-Assessment'); ?>/${saf_form_id}`)
    });

    function callModalHoldingDetails() {
        // $("#holding_details-lg-modal").modal('show');
        $("#assessmentTypeModel").modal("show");
        $("#existing_holding_details-lg-modal").modal('hide');
    }

    function searchHoldingNo() {
        var ward_mstr_id = $("#ward_mstr_id_demo").val();
        var previous_holding_no = $("#previous_holding_no").val();
        var assessmentType = $("#newAssessMentType").val();
        var clickBtn = $("#newAssessMentTypeButtonValue").val();
        if (ward_mstr_id != "" && previous_holding_no != "") {
            try {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url(); ?>/CitizenSaf2/getDtlByPrevHoldingNo",
                    dataType: "json",
                    data: {
                        "ward_mstr_id": ward_mstr_id,
                        "holding_no": previous_holding_no,
			            "assessmentType":assessmentType,
                    },
                    beforeSend: function() {
                        $("#holding_search").button('loading')
                        $("#searchErrMsgHideShow").addClass("hidden");
                        $("#searchResultTableHideShow").addClass("hidden");
                    },
                    success: function(data) {
                        if (data.response == true) {
                            $("#searchResultTableHideShow").removeClass("hidden");
                            var tbody = "";
                            $.each(data.data.prop_dtl, function(k, v) {
                                //display the key and value pair
                                //var v = data.data.prop_owner_dtl;
                                tbody += "<tr>";
                                tbody += "<td id='pv_holding'>";
                                tbody += previous_holding_no;
                                tbody += "</td>";
                                tbody += "<td>";
                                tbody += v.owner_name;
                                tbody += "</td>";
                                tbody += "<td>";
                                tbody += v.guardian_name;
                                tbody += "</td>";
                                tbody += "<td>";
                                tbody += v.prop_address;
                                tbody += "</td>";
                                tbody += "<td>";
                                var dd = "'" + v.id + "'";
                                var prop_type_mstr_id = v.prop_type_mstr_id;
                                // if(v.saf_no && v.saf_pending_status != 1)
                                // {
                                //     tbody += 'Pending('+v.saf_no+')';
                                // }else{
                                //     tbody += '<button type="button" class="btn btn-success" value="" onclick="callModalHoldingOwnerDetails(' + dd + ', ' +prop_type_mstr_id+ ')">SELECT</button>';
                                // }

                                if(v.saf_no && v.saf_pending_status != 1)
                                {
                                    tbody += 'Pending('+v.saf_no+')';
                                }else if(v?.pendingSaf?.id){
                                    tbody += '<span class="text-danger" >Saf Already Applied ! Track This Saf. Wait For Approval <b>'+v?.pendingSaf?.saf_no+'</b></span>';
                                }else if(v?.pendingNotice?.id){
                                    tbody += '<span class="text-danger" >Notice Is Generated. Please Clear It First <b>'+'NOT/'+v?.pendingNotice?.notice_no+'</b></span>';
                                }else if(v?.tranVerificationPending?.id){
                                    tbody += '<span class="text-danger" >Tran No <b>'+v?.tranVerificationPending?.tran_no+'</b> Is Not Clear By Accounts. Please Wait For Clearance</span>';
                                }else if(v?.arrearDemandAmountBlock){
                                    tbody += '<span class="text-danger" >Arrear Demand Is More Than 20000 ! So Assessment Is blocked. <b></b></span>';
                                }else if([1,5].includes(parseInt(prop_type_mstr_id)) && assessmentType.toUpperCase()=="MUTATION" ){
                                    tbody += '<span class="text-danger" >SUPER STRUCTURE AND OCCUPIED PROPERTY Can Not Apply Mutation <b></b></span>';
                                }
                                else{
                                    tbody += '<button type="button" class="btn btn-success" value="" onclick="callModalHoldingOwnerDetails(' + dd + ', ' +prop_type_mstr_id+ ','+"'"+clickBtn+"'"+')">SELECT</button>';
                                }
                                tbody += "</td>";
                                tbody += "</tr>";
                            });
                            $("#tbody").html(tbody);
                        } else {
                            $("#searchErrMsgHideShow").removeClass("hidden");
                        }
                        $("#holding_search").button("reset");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#holding_search").button("reset");
                        /* alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + " : " + errorThrown); */
                    }
                });
            } catch (err) {
                //alert(err.message);
            }
        } else {
            if ($("#ward_mstr_id_demo").val() == "") {
                $("#ward_mstr_id_demo").css('border-color', 'red');
            }
            if ($("#previous_holding_no").val() == "") {
                $("#previous_holding_no").css('border-color', 'red');
            }
        }
    }

    function callModalHoldingOwnerDetails(holdingId, prop_type_mstr_id,clickBtn="") {
        $("#holding_no").val($("#previous_holding_no").val());
        $("#holding_id").val(holdingId);
        $("#ward_mstr_id").val($("#ward_mstr_id_demo").val());
        if (prop_type_mstr_id==1 || prop_type_mstr_id==5) {
            $("#btn_click_for_mutation").addClass("hidden");
        } else {
            $("#btn_click_for_mutation").removeClass("hidden");
        }
        $("#holding_owner_details-lg-modal").modal('show');
        $("#holding_details-lg-modal").modal('hide');
        if(clickBtn && clickBtn!="undefined"){            
            $(".assessmentTypeSubmit").each(function() {
                if ($(this).val() === clickBtn) {
                    $(this).trigger("click");
                    $("#holding_owner_details-lg-modal").modal('hide');
                    return false; //This breaks the loop
                }

            })
        }
    }
    $("#search").click(function(event) {
        var process = true;
        if ($("#saf_no").val() == "") {
            $("#saf_no").css('border-color', 'red');
            process = false;
        }
        return process;
    });
    $("#saf_no").keyup(function() {
        $(this).css('border-color', '');
    });
    $("#ward_mstr_id_demo").change(function() {
        $("#ward_mstr_id_demo").css('border-color', '');
    });
    $("#previous_holding_no").keyup(function() {
        $("#previous_holding_no").css('border-color', '');
    });
    $("#search_form").validate({
        rules: {

            form_no: {
                required: true
            }
        },
        messages: {
            form_no: 'Enter SAF Form No.'

        },

    });

    $(".assessmentType").click(function(){
        let selectedValue = $(this).val(); // Will be "YES" or "NO"
        let modal = $(this).closest(".modal");
        $("#newAssessMentTypeButtonValue").val(selectedValue);
        if(selectedValue=="YES"){
            $("#newAssessMentType").val("Re-Assessment");
            modal.modal("hide"); 
            $("#holding_details-lg-modal").modal("show");
        }
        else if(selectedValue=="NO"){
            $("#newAssessMentType").val("Mutation");
            modal.modal("hide"); 
            $("#holding_details-lg-modal").modal("show");
        }
        else{
            $("#newAssessMentType").val("");
        }

    });
</script>
