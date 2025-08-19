<?= $this->include('layout_vertical/header');?>
<style>
    .error{
        color: red;
    }
</style>
<div id="content-container">
    <div id="page-head">
        <div id="page-title">
        </div>
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Property</a></li>
            <li><a href="<?=base_url("propDtl/full/".md5($prop_dtl_id));?>">Property Details</a></li>
            <li class="active">Edit Property Details</li>
        </ol>
    </div>
    <div id="page-content" id="divIdPDF">
        <form method="POST" id="prop_form" enctype="multipart/form-data">
            <input type="hidden" id="prop_dtl_id" name="prop_dtl_id" value="<?=$prop_dtl_id;?>" />

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                <div class="row">
					<div class="col-md-6">
						<h3 class="panel-title">Property Details</h3>
					</div>
					<div class="col-md-6 text-right"><button onclick="history.go(-1)" class="btn btn-info">Back</button></div>
				</div>
                    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">New Ward No <span class="text-danger">*</span></label>
                        <div class="col-md-3 text-bold pad-btm">
                        <select class="form-control" name="new_ward_mstr_id">
                            <option value="">Select</option>
                            <?php
                            foreach($ward_list as $ward)
                            {
                                ?>
                                <option value="<?=$ward["ward_mstr_id"];?>" <?=($new_ward_mstr_id==$ward["ward_mstr_id"])?"selected":null;?>>
                                    <?=$ward["ward_no"];?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                        </div>
                        
                        <label class="col-md-3">Old Ward No <span class="text-danger">*</span></label>
                        <div class="col-md-3 text-bold pad-btm">
                        <select class="form-control" name="ward_mstr_id">
                            <option value="">Select</option>
                            <?php
                            foreach($ward_list as $ward)
                            {
                                ?>
                                <option value="<?=$ward["ward_mstr_id"];?>" <?=($ward_mstr_id==$ward["ward_mstr_id"])?"selected":null;?>>
                                    <?=$ward["ward_no"];?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-3">Khata</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <input class="form-control" name="khata_no" value="<?=$khata_no;?>" />
                        </div>
                        

                        <label class="col-md-3">Plot No</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <input class="form-control" name="plot_no" value="<?=$plot_no;?>" />
                        </div>
                    </div>

                   
                    <div class="row">
                        <label class="col-md-3">Village Mauja Name</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <input class="form-control" name="village_mauja_name" value="<?=$village_mauja_name;?>" />
                        </div>

                        <label class="col-md-3">Area Of Plot</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <input class="form-control" name="area_of_plot" value="<?=$area_of_plot;?>" />
                        </div>
                    </div>
                    <div class="row">
                        <fieldset>
                            <legend>Property Address</legend>
                                <div class="row">
                                    <label class="col-md-3">Property Address <span class="text-danger">*</span></label>
                                    <div class="col-md-9 text-bold pad-btm">
                                        <textarea class="form-control" name="prop_address"><?=$prop_address;?></textarea>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-md-3">City <span class="text-danger">*</span></label>
                                    <div class="col-md-3 text-bold pad-btm">
                                        <input class="form-control" name="prop_city" value="<?=$prop_city;?>" />
                                    </div>
                                    <label class="col-md-3">District <span class="text-danger">*</span></label>
                                    <div class="col-md-3 text-bold pad-btm">
                                    <input class="form-control" name="prop_dist" value="<?=$prop_dist;?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-3">State <span class="text-danger">*</span></label>
                                    <div class="col-md-3 text-bold pad-btm">
                                        <input class="form-control" name="prop_state" value="<?=$prop_state;?>" />
                                    </div>
                                    <label class="col-md-3">Pin Code <span class="text-danger">*</span></label>
                                    <div class="col-md-3 text-bold pad-btm">
                                    <input class="form-control" name="prop_pin_code" value="<?=$prop_pin_code;?>" maxlength="6" />
                                    </div>
                                </div>
                        </fieldset>
                    </div>
                    

                    <div class="row">
                        <fieldset>
                            <legend>Correspondence Address</legend>
                                <div class="row">
                                    <label class="col-md-3">Property Address</label>
                                    <div class="col-md-9 text-bold pad-btm">
                                        <textarea class="form-control" name="corr_address"><?=$corr_address;?></textarea>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-md-3">City</label>
                                    <div class="col-md-3 text-bold pad-btm">
                                        <input class="form-control" name="corr_city" value="<?=$corr_city;?>" />
                                    </div>
                                    <label class="col-md-3">District</label>
                                    <div class="col-md-3 text-bold pad-btm">
                                    <input class="form-control" name="corr_dist" value="<?=$corr_dist;?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-3">State</label>
                                    <div class="col-md-3 text-bold pad-btm">
                                        <input class="form-control" name="corr_state" value="<?=$corr_state;?>" />
                                    </div>
                                    <label class="col-md-3">Pin Code</label>
                                    <div class="col-md-3 text-bold pad-btm">
                                <input class="form-control" name="corr_pin_code" value="<?=$corr_pin_code;?>" maxlength="6"/>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Enter Reason For Update <span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            <textarea class="form-control" id="prop_remarks" name="prop_remarks"></textarea>
                        </div>
                    </div>
                    <div class="row mar-top">
                        <div class="col-md-3">
                            <label class="text-bold">
                                Supportive Document <span class="text-danger">*</span><br />
                                <span class="text-danger">Only .pdf allowed</span>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" id="prop_update_file" name="prop_update_file" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <input type="submit" name="save" id="Save" value="Save" class="btn btn-primary" />
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <form method="post" id="owner_form" enctype="multipart/form-data">
            <input type="hidden" id="owner_prop_dtl_id" name="owner_prop_dtl_id" value="<?=$prop_dtl_id;?>" />
            <input type="hidden" id="id" value="" name="id" />
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Owner Name <span class="text-danger">*</span></label>
                                <input type="text" id="owner_name" name="owner_name" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Guardian Name</label>
                                <input type="text" id="guardian_name" name="guardian_name" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Relation</label>
                                <select id="relation_type" name="relation_type" class="form-control relation_type">
                                    <option value="">SELECT</option>
                                    <option value="S/O">S/O</option>
                                    <option value="D/O">D/O</option>
                                    <option value="W/O">W/O</option>
                                    <option value="C/O">C/O</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Mobile No. <span class="text-danger">*</span></label>
                                <input type="text" id="mobile_no" name="mobile_no" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" maxlength="10">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Aadhar No.</label>
                                <input type="text" id="aadhar_no" name="aadhar_no" class="form-control aadhar_no" placeholder="Aadhar No." value="" onkeypress="return isNum(event);" maxlength="12">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">PAN No.</label>
                                <input type="text" id="pan_no" name="pan_no" class="form-control pan_no" placeholder="PAN No." value="" onkeypress="return isAlphaNum(event);" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                                <label class="control-label">Email ID</label>
                                <input type="email" id="email" name="email" class="form-control email" placeholder="Email ID" value="">
                            </div>
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label class="text-bold">
                                Supportive Document <span class="text-danger">*</span><br />
                                <span class="text-danger">Only .pdf allowed</span>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <input type="file" id="owner_update_file" name="owner_update_file" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <input type="submit" name="submit" id="submit_btn" value="Save" class="btn btn-primary" />
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    
                    <table class="table table-bordered text-sm">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>Owner Name</th>
                                <th>Guardian Name</th>
                                <th>Relation</th>
                                <th>Mobile No</th>
                                <th>Aadhar No.</th>
                                <th>PAN No.</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Is Specially Abled?</th>
                                <th>Is Armed Force?</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="owner_dtl_append">
                            <?php
                            if (isset($prop_owner_detail)) {
                                foreach ($prop_owner_detail as $owner_detail) {
                            ?>
                                    <tr>
                                        <td><?= $owner_detail['owner_name']; ?></td>
                                        <td><?= $owner_detail['guardian_name'] == '' ? 'N/A' : $owner_detail['guardian_name'];  ?></td>

                                        <td><?= $owner_detail['relation_type']; ?></td>
                                        <td><?= $owner_detail['mobile_no']; ?></td>

                                        <td><?= $owner_detail['aadhar_no'] == '' ? 'N/A' : $owner_detail['aadhar_no'];  ?></td>
                                        <td><?= $owner_detail['pan_no'] == '' ? 'N/A' : $owner_detail['pan_no'];  ?></td>
                                        <td><?= $owner_detail['email'] == '' ? 'N/A' : $owner_detail['email'];  ?></td>

                                        <td><?= $owner_detail['gender'] == '' ? 'N/A' : $owner_detail['gender'];  ?></td>
                                        <td><?= $owner_detail['dob'] == '' ? 'N/A' : $owner_detail['dob'];  ?></td>
                                        <td><?= $owner_detail['is_specially_abled'] == 't' ? 'Yes' : 'No';  ?></td>
                                        <td><?= $owner_detail['is_armed_force'] == 't' ? 'Yes' : 'No';  ?></td>
                                        <td><button type="button" class="btn btn-info" onclick="EditNow(this);" data-id="<?=$owner_detail['id'];?>" data-owner_name="<?=$owner_detail['owner_name'];?>" data-guardian_name="<?=$owner_detail['guardian_name'];?>" data-relation_type="<?=$owner_detail['relation_type'];?>" data-mobile_no="<?=$owner_detail['mobile_no'];?>" data-email="<?=$owner_detail['email'];?>" data-pan_no="<?=$owner_detail['pan_no'];?>" data-aadhar_no="<?=$owner_detail['aadhar_no'];?>" data-gender="<?=$owner_detail['gender'];?>" data-dob="<?=$owner_detail['dob'];?>" data-is_specially_abled="<?=($owner_detail['is_specially_abled']=="")?"0":1;?>" data-is_armed_force="<?=($owner_detail['is_armed_force']=="")?"0":"1";?>">
                                            Edit 
                                            </button>
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
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
</script><script src="http://modernulb.com/RMCDMC/public/assets/js/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
    function isAlpha(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }

    function EditNow(obj){
        console.log(obj);
        //debugger;
        var id = $(obj).attr('data-id');
        var owner_name = $(obj).attr('data-owner_name');
        var guardian_name = $(obj).attr('data-guardian_name');
        var relation_type = $(obj).attr('data-relation_type');
        var mobile_no = $(obj).attr('data-mobile_no');
        var email = $(obj).attr('data-email');
        var pan_no = $(obj).attr('data-pan_no');
        var aadhar_no = $(obj).attr('data-aadhar_no');

        $("#id").val(id); 
        $("#owner_name").val(owner_name); 
        $("#guardian_name").val(guardian_name); 
        $("#relation_type").val(relation_type); 
        $("#mobile_no").val(mobile_no); 
        $("#email").val(email); 
        $("#pan_no").val(pan_no); 
        $("#aadhar_no").val(aadhar_no); 

        $("#submit_btn").val("Update");
    }
</script>
<script>
    jQuery.validator.addMethod("dateFormatYYYMMDD", function(value, element) {
        return this.optional(element) || /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/i.test(value);
    }, "Invalid format (YYYY-MM-DD)"); 

    jQuery.validator.addMethod("alphaSpace", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
    }, "Letters only please (a-z, A-Z )");

    $("#owner_form").validate({
        rules: {
            "owner_name": {
                required: true,
                alphaSpace: true,
                minlength: 2,
            },
            "guardian_name": {
                required: true,
                alphaSpace: true,
                minlength: 2,
            },
            "relation_type": {
                required: true,
            },
            
            "mobile_no": {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            "aadhar_no": {
                required: false,
                digits: true,
                minlength: 12,
                maxlength: 12,
            },
            "pan_no": {
                required: false,
                minlength: 10,
                maxlength: 10,
            },
            "email": {
                required: false,
                email: true,
            },
            "owner_update_file": {
                required: true,
                extension: "pdf|PDF|Pdf",
            },
        },
        messages: {
            "owner_name": {
                required: "This field is required",
                alphaSpace: "No Special Character Allowed",
                minlength: "Minimum length is 2",
            },
            "gender": {
                required: "This field is required",
            },
            "dob": {
                required: "This field is required",
                dateFormatYYYMMDD : "Invalid date format",
            },
            "guardian_name": {
                required: "This field is required",
                alphaSpace: "No Special Character Allowed",
                minlength: "Minimum length is 2",
            },
            "mobile_no": {
                required: "This field is required",
                digits: "Only Number allowed",
                minlength: "Minimum lenght 10",
                maxlength: "Maximum lenght 10",
            },
            "aadhar_no": {
                required: "This field is required",
                digits: "Only Number allowed",
                minlength: "Minimum lenght 12",
                maxlength: "Maximum lenght 12",
            }, 
            "owner_update_file": {
                required: "This field is required",
                extension: "Please upload .pdf file.",
            }, 
        }  
    });

    $("#prop_form").validate({
        rules: {
            "ward_mstr_id": {
                required: true,
            },
            "new_ward_mstr_id": {
                required: true,
            },
            "prop_address": {
                required: true,
            },
            "prop_city": {
                required: true,
            },
            "prop_dist": {
                required: true,
            },
            "prop_state": {
                required: true,
            },
            "prop_pin_code": {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6,
            },
            "corr_pin_code": {
                required: false,
                digits: true,
                minlength: 6,
                maxlength: 6,
            },
            "prop_remarks": {
                required: true,
            },
            "prop_update_file": {
                required: true,
                extension: "pdf|PDF|Pdf",
            },
            
        }
    });
</script>
