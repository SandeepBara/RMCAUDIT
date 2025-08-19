<?= $this->include('layout_vertical/header');?>
<style>
    .error {
        color: red;
    }
</style>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="<?=base_url("/WaterHarvesting/declarationList")?>">Water Harvesting Declaration List</a></li>
            <li class="active">Water Harvesting Declaration Form</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Water Harvesting Declaration Form</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form id="form_id" method="post" enctype="multipart/form-data">
                                    <div class="row mar-btn">
                                        <label class="col-md-6"><b>Does Completion of Water Harvesting is done before 31-03-2017?</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <div class="radio">
                                                <!-- Inline radio buttons -->
                                                <input type="radio" id="ck_water_harvesting_yes" name="ck_water_harvesting" class="magic-radio" value="YES" <?=isset($ck_water_harvesting)?($ck_water_harvesting=="YES")?"checked":"":"";?>>
                                                <label for="ck_water_harvesting_yes">YES</label>

                                                <input type="radio" id="ck_water_harvesting_no" name="ck_water_harvesting" class="magic-radio" value="NO" <?=isset($ck_water_harvesting)?($ck_water_harvesting=="NO")?"checked":"":"checked";?>>
                                                <label for="ck_water_harvesting_no">NO</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>15 Digits Holding No./ SAF No.</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="holding_saf_sam_no" name="holding_saf_sam_no" class="form-control" placeholder="Holding No" value="<?=(isset($holding_saf_sam_no))?$holding_saf_sam_no:"";?>">
                                        </div>
                                        <input type="hidden" id="prop_dtl_id" name="prop_dtl_id" value="<?=(isset($prop_dtl_id))?$prop_dtl_id:"";?>" />
                                        <input type="hidden" id="saf_dtl_id" name="saf_dtl_id" value="<?=(isset($saf_dtl_id))?$saf_dtl_id:"";?>" />
                                        <input type="hidden" id="ward_mstr_id" name="ward_mstr_id" value="<?=(isset($ward_mstr_id))?$ward_mstr_id:"";?>" />
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Name</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Name" value="<?=(isset($owner_name))?$owner_name:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Guardian Name</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="guardian_name" name="guardian_name" class="form-control" placeholder="Guardian Name" value="<?=(isset($guardian_name))?$guardian_name:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Ward No.</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="ward_no" name="ward_no" class="form-control" placeholder="Ward No." value="<?=(isset($ward_no))?$ward_no:"";?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Name of Building and Address</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="building_name_address" name="building_name_address" class="form-control" placeholder="Name of Building and Address" value="<?=(isset($building_name_address))?$building_name_address:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Mobile No.</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Mobile No." value="<?=(isset($mobile_no))?$mobile_no:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Date of Completion of Water Harvesting Structure</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="date" id="date_of_water_harvesting" name="date_of_water_harvesting" class="form-control" value="<?=(isset($date_of_water_harvesting))?$date_of_water_harvesting:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Upload Water Harvesting Declaration Form</b> <span class="text-danger">*</span><br /><span class="text-danger">(.pdf file only)</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="file" id="water_harvesing_form" name="water_harvesing_form" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6"><b>Upload Water Harvesting Image</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="file" id="water_harvesing_img" name="water_harvesing_img" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="row cl_rmc_date_file hidden">
                                        <label class="col-md-6"><b>Application Date</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="date" id="application_date" name="application_date" class="form-control" value="<?=(isset($application_date))?$application_date:"";?>">
                                        </div>
                                    </div>
                                    <div class="row cl_rmc_date_file hidden">
                                        <label class="col-md-6"><b>Upload RMC Recommended File</b> <span class="text-danger">*</span><br /><span class="text-danger">(.pdf file only)</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="file" id="water_harvesing_rmc_file" name="water_harvesing_rmc_file" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-3">
                                            <img src="<?=base_url();?>/public/assets/img/upload.png" class="img img-thumbnail" id="photo_path_preview" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            
                                        </div>
                                        <div class="col-md-3">
                                            <br />
                                            <button type="submit" class="btn btn-success btn-block" id="btn_submit">SUBMIT</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script type="text/javascript">
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
$(document).ready(function() {
    $('input[type=radio][name=ck_water_harvesting]').change(function() {
        if (this.value == 'NO') {
            $(".cl_rmc_date_file").addClass("hidden");
        }
        else if (this.value == 'YES') {
            $(".cl_rmc_date_file").removeClass("hidden");
        }
    });
    $('input[type=radio][name=ck_water_harvesting]').trigger("change");
    $("#water_harvesing_img").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
            $("#water_harvesing_img").val("");
            $('#photo_path_preview').attr('src', "<?=base_url();?>/public/assets/img/upload.png");
        }
        /* if (input.files[0].size > 4194304) { // 1MB = 1048576, 4194304= 4MB
            $("#water_harvesing_img").val("");
            $('#photo_path_preview').attr('src', "<?=base_url();?>/public/assets/img/upload.png");
            alert("Try to upload file less than 4MB!"); 
        } */
        else{
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo_path_preview').attr('src', e.target.result);
                    $("#is_image").val("is_image");
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });
    $("#holding_saf_sam_no").focusout(function(){
        resetFunction();
        var holding_saf_sam_no = $("#holding_saf_sam_no").val();
        if (holding_saf_sam_no=="") {
            return false;
        }
        $.ajax({
            type:"POST",
            url: '<?=base_url();?>/WaterHarvesting/ajaxGetPropSafDtl',
            dataType: "json",
            data: {
                "holding_saf_sam_no":holding_saf_sam_no,
            },
            beforeSend: function() {
                $("#loadingDiv").show();
            },
            success:function(data){
                if(data.response==1){
                    $("#prop_dtl_id").val(data.data.prop_dtl_id);
                    $("#saf_dtl_id").val(data.data.saf_dtl_id);
                    $("#ward_mstr_id").val(data.data.ward_mstr_id);
                    $("#owner_name").val(data.data.owner_name);
                    $("#guardian_name").val(data.data.guardian_name);
                    $("#building_name_address").val(data.data.prop_address);
                    $("#mobile_no").val(data.data.mobile_no);
                    $("#ward_no").val(data.data.ward_no);
                } else if(data.response==2){
                    modelInfo(data.msg);
                } else if(data.response==0){
                    modelInfo(data.msg);
                }
                $("#loadingDiv").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#loadingDiv").hide();
            }
        });
    })
    const resetFunction = () => {
        $("#prop_dtl_id").val("");
        $("#saf_dtl_id").val("");
        $("#ward_mstr_id").val("");
        $("#owner_name").val("");
        $("#guardian_name").val("");
        $("#building_name_address").val("");
        $("#mobile_no").val("");
        $("#ward_no").val("");
    };
    $("#form_id").validate({
        rules: {
            holding_saf_sam_no: {
                required: true,
            },
            owner_name: {
                required: true,
            },
            guardian_name: {
                required: true,
            },
            mobile_no: {
                required: true,
            },
            ward_no: {
                required: true,
            },
            building_name_address: {
                required: true,
            },
            date_of_water_harvesting: {
                required: true,
            },
            water_harvesing_form: {
                required: true,
                extension: "pdf"
            },
            water_harvesing_img: {
                required: true,
                extension: "jpeg|jpg|png"
            },
            water_harvesing_rmc_file: {
                required: function(element){
                    return $('input[type=radio][name=ck_water_harvesting]').val()=="YES";
                },
                extension: "pdf"
            },
            application_date: {
                required: function(element){
                    return $('input[type=radio][name=ck_water_harvesting]').val()=="YES";
                },
            },
        }
    });
});
</script>