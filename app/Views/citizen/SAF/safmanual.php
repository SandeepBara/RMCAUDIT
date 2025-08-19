<?= $this->include('layout_home/header');?>

<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">How To Apply? (Online Procedure)</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-hover table-vcenter" >
                                <tr>
                                    <td class="text-bold">STEP 1</td>
                                    <td>:</td>
                                    <td>Click on Apply For Assessment' link .</td>
                                </tr>
                                <tr>
                                    <td class="text-bold">STEP 2</td>
                                    <td>:</td>
                                    <td>Fill (<span class="text-danger">*</span>) Mandatory field available on Self Assessment Form.</td>
                                </tr>
                                <tr>
                                    <td class="text-bold">STEP 3</td>
                                    <td>:</td>
                                    <td>Click <span class="text-bold">'Submit'</span> button for save applicant personal details.</td>
                                </tr>
                                <tr>
                                    <td class="text-bold">STEP 4</td>
                                    <td>:</td>
                                    <td>Upload all necessary documents required for Assessment. <br />
                                        Note: Click Here to <a href="#" data-target="#necesaary_document" data-toggle="modal" class="" style="color:#f112d5;font-weight: 700;">View Necessary Documents</a> <br />
                                        ==> Click on 'Save' button to apply.
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="3">
                                        <button type="button" data-target="#existing_holding_details-lg-modal" data-toggle="modal" class="btn btn-mint">Apply Online</button>
                                        <a class="btn btn-mint" href="<?=base_url();?>/CitizenSaf/searchApplication">Already Applied?</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->


<div id="necesaary_document" class="modal fade" tabindex="-1" style="top:10%;height: 70%;max-width: fit-content;left: 25%;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#25476a;">
                <button type="button" class="close" data-dismiss="modal" style="color:white;"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel" style="color:white;">Necessary Document Requirement Details</h4>
            </div>
            <div class="modal-body">
				<table class="table table-striped" width="100%">
				<tbody><tr>
				<td colspan="2"><strong>For New Assessment</strong></td>
				</tr>
				<tr>
				<td><strong>Property Type</strong></td>
				<td><strong>Document To Be Attached(Any One)</strong></td>

				</tr>
				<tr><td>Vacant Land</td><td><table><tbody><tr><td>(a) Registered Sale Deed / Registered Gift Deed / Registered Lease Deed / Will Probate / Khatiyan / Court Decree / Partition Deed / </td></tr><tr><td>(b) Correction Slip / Holding Tax Receipt / Land Revenue Receipt / </td></tr></tbody></table></td>
				</tr><tr><td>Independent Building</td><td><table><tbody><tr><td>(a) Registered Sale Deed / Registered Gift Deed / Registered Lease Deed / Will Probate / Khatiyan / Court Decree / Partition Deed / </td></tr><tr><td>(b) Correction Slip / Holding Tax Receipt / Land Revenue Receipt / </td></tr></tbody></table></td>
				</tr><tr><td>Flats/Units in Multistored Building</td><td><table><tbody><tr><td>(a) Registered Sale Deed / Possession Certificate / </td></tr></tbody></table></td>
				</tr><tr><td>Super Structure</td><td><table><tbody><tr><td>(a) Electricity Bill / Property Address Proof / </td></tr></tbody></table></td>
				</tr>
				</tbody></table>
					<table class="table table-striped" width="100%">
						<tbody>
							<tr>
								<td colspan="2"><strong>Mutation/When Change Owner Name</strong></td>
							</tr>
							<tr>
								<td>Sale</td>
								<td>
									<table>
										<tbody>
											<tr>
												<td>(a) Registered Sale Deed / Registered Gift Deed / Registered Lease Deed / </td>
											</tr>
											<tr>
												<td>(b) Holding Tax Receipt / </td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td>Gift</td>
								<td>
									<table>
										<tbody>
											<tr>
												<td>(a) Registered Sale Deed / Registered Gift Deed / Registered Lease Deed / </td></tr><tr><td>(b) Holding Tax Receipt / </td></tr></tbody></table></td></tr><tr><td>Lease</td><td><table><tbody><tr><td>(a) Registered Sale Deed / Registered Gift Deed / Registered Lease Deed / </td></tr><tr><td>(b) Holding Tax Receipt / </td></tr></tbody></table></td></tr><tr><td>Will</td><td><table><tbody><tr><td>(a) Will Probate / </td></tr><tr><td>(b) Holding Tax Receipt / </td></tr></tbody></table></td></tr><tr><td>Succession</td><td><table><tbody><tr><td>(a) Succession Certificate / Death Certificate / </td></tr><tr><td>(b) Holding Tax Receipt / </td></tr></tbody></table></td></tr><tr><td>Partition</td><td><table><tbody><tr><td>(a) Partition Deed / </td>
											</tr><tr><td>(b) Holding Tax Receipt / </td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="table table-striped" width="100%">
						<tbody>
							<tr>
								<td colspan="2"><strong>For Re Assessment</strong></td>
								</tr>
								<tr>
								<td>Last Holding Receipt</td>
							</tr>
						</tbody>
					</table>

				<div class="modal-footer">
					<button data-dismiss="modal" aria-hidden="true" class="btn btn-danger">Close</button>
				</div>
			</div>
        </div>
    </div>
</div>

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
                <button type="button" class="btn btn-primary" onclick="callModalHoldingDetails();" data-toggle="tooltip" title="Mutation/Re Assessment">YES</button>
                <a href="<?=base_url();?>/CitizenSaf2/AddUpdate" type="button" class="btn btn-primary" data-toggle="tooltip" title="New Assessment">No</a>
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
                            if(isset($wardList)){
                                foreach ($wardList as $ward) {
                            ?>
                            <option value="<?=$ward['id'];?>" <?=(isset($ward_mstr_id))?($ward['id']==$ward_mstr_id)?"selected":"":"";?>><?=$ward['ward_no'];?></option>
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
                    <div class="col-md-12 hidden" id="searchResultTableHideShow">
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
<!--End existing_holding_details Bootstrap Modal-->
<?= $this->include('layout_home/footer');?>
<script>
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php
if ($flashToast = flashToast('safmanual')) {
    echo "modelInfo('".$flashToast."');";
}
?>
function callModalHoldingDetails() {
    // $("#holding_details-lg-modal").modal('show');
    $("#assessmentTypeModel").modal("show");
    $("#existing_holding_details-lg-modal").modal('hide');
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
function searchHoldingNo() {
    var ward_mstr_id = $("#ward_mstr_id_demo").val();
    var previous_holding_no = $("#previous_holding_no").val();
    var assessmentType = $("#newAssessMentType").val();
    var clickBtn = $("#newAssessMentTypeButtonValue").val();
    if (ward_mstr_id!="" && previous_holding_no!="") {
        try{
            $.ajax({
                type:"POST",
                url: "<?=base_url();?>/CitizenSaf2/getDtlByPrevHoldingNo",
                dataType: "json",
                data: {
                        "ward_mstr_id":ward_mstr_id,
                        "holding_no":previous_holding_no,
                        "assessmentType":assessmentType,
                    },
                beforeSend: function() {
                    $("#holding_search").button('loading')
                    $("#searchErrMsgHideShow").addClass("hidden");
                    $("#searchResultTableHideShow").addClass("hidden");
                },
                success:function(data){
                    if(data.response==true){
                        $("#searchResultTableHideShow").removeClass("hidden");
                        var tbody = "";
                        $.each(data.data.prop_dtl, function(k, v) {
                            //display the key and value pair
                            //var v = data.data.prop_owner_dtl;
                            tbody += "<tr>";
                                tbody += "<td>";
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
                                    var dd = "'"+v.id+"'";
                                    var prop_type_mstr_id = v.prop_type_mstr_id;
                                    if(v.saf_no && v.saf_pending_status != 1)
                                    {
                                        tbody += 'Pending('+v.saf_no+')';
                                    }else if(v?.pendingSaf?.id){
                                        tbody += '<span class="text-danger" >Saf Already Apply Track This Saf. Wait For Approval <b>'+v?.pendingSaf?.saf_no+'</b></span>';
                                    }else if(v?.tranVerificationPending?.id){
                                        tbody += '<span class="text-danger" >Tran No <b>'+v?.tranVerificationPending?.tran_no+'</b> Is No Clear By Account. Please Wait For Clarence</span>';
                                    }else if(v?.arrearDemandAmountBlock){
                                        tbody += '<span class="text-danger" >Arrear Demand Is More Than 20000. So That Assessment Is block. <b></b></span>';
                                    }else if(v?.pendingNotice?.id){
                                        tbody += '<span class="text-danger" >Notice Is Generated. Please Clear It First <b>'+'NOT/'+v?.pendingNotice?.notice_no+'</b></span>';
                                    }else if([1,5].includes(parseInt(prop_type_mstr_id)) && assessmentType.toUpperCase()=="MUTATION" ){
                                        tbody += '<span class="text-danger" >SUPER STRUCTURE AND OCCUPIED PROPERTY Can Not Apply Mutation <b></b></span>';
                                    }else{
                                        tbody += '<button type="button" class="btn btn-success" value="" onclick="callModalHoldingOwnerDetails('+dd+', '+prop_type_mstr_id+','+"'"+clickBtn+"'"+')">SELECT</button>';
                                    }
                                    //tbody += '<button type="button" class="btn btn-success" value="" onclick="callModalHoldingOwnerDetails('+dd+', '+prop_type_mstr_id+')">SELECT</button>';
                                tbody += "</td>";
                            tbody += "</tr>";
                        });
                        $("#tbody").html(tbody);
                    }else{
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
        }catch (err) {
            alert(err.message);
        }
    }
}
</script>
<script>
$("#holding_search").click(function() {
    var process = true;
    if ($("#ward_mstr_id_demo").val()=="") {
        $("#ward_mstr_id_demo").css('border-color', 'red'); process = false;
    }
    if ($("#previous_holding_no").val()=="") {
        $("#previous_holding_no").css('border-color', 'red'); process = false;
    }
});
$("#ward_mstr_id_demo").change(function() {
    $("#ward_mstr_id_demo").css('border-color', '');
});
$("#previous_holding_no").keyup(function() {
    $("#previous_holding_no").css('border-color', '');
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

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
