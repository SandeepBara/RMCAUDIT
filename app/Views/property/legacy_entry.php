
<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Property</a></li>
            <li><a href="#">Legacy Entry</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="form_legacy_entry" name="form_legacy_entry" method="post">
            <?php
            if(isset($validation)){
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10 text-danger">
                        <?php 
                        $i=0;
                        foreach ($validation as $errMsg) {
                            $i++;
                            echo $i.") ".$errMsg; echo ".<br />";
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
                    <h3 class="panel-title">Legacy Entry</h3>
                </div>
                <div class="panel-body">


                    <div class="row">
                        <label class="col-md-3">Ward No <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
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
                        <label class="col-md-3">Holding No.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="holding_no" name="holding_no" class="form-control holding_no" placeholder="Holding No." value="" maxlength="20" />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Ownership Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="ownership_type_mstr_id" name="ownership_type_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($ownershipTypeList)){
                                    foreach ($ownershipTypeList as $ownershipType) {
                                ?>
                                <option value="<?=$ownershipType['id'];?>" <?=(isset($ownership_type_mstr_id))?($ownershipType['id']==$ownership_type_mstr_id)?"selected":"":"";?>><?=$ownershipType['ownership_type'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <label class="col-md-3">Property Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="prop_type_mstr_id" name="prop_type_mstr_id" class="form-control" onchange="propTypeMstrChngFun();">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($propTypeList)){
                                    foreach ($propTypeList as $propType) {
                                ?>
                                <option value="<?=$propType['id'];?>" <?=(isset($prop_type_mstr_id))?($propType['id']==$prop_type_mstr_id)?"selected":"":"";?>><?=$propType['property_type'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Road Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="road_type_mstr_id" name="road_type_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($roadTypeList)){
                                    foreach ($roadTypeList as $roadType) {
                                ?>
                                <option value="<?=$roadType['id'];?>" <?=(isset($ward_mstr_id))?($roadType['id']==$road_type_mstr_id)?"selected":"":"";?>><?=$roadType['road_type'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-bordered panel-dark" id="owner_dtl_hide_show">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name <span class="text-danger">*</span></th>
                                            <th>Guardian Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No <span class="text-danger">*</span></th>
                                            <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                            <th>Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                        <tr>
                                            <td>
                                                <input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td>
                                                <input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td>
                                                <select id="relation_type1" name="relation_type[]" class="form-control relation_type" style="width: 100px;" onchange="borderNormal(this.id);">
                                                    <option value="">SELECT</option>
                                                    <option value="S/O">S/O</option>
                                                    <option value="D/O">D/O</option>
                                                    <option value="W/O">W/O</option>
                                                    <option value="C/O">C/O</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                                <input type="text" id="aadhar_no1" name="aadhar_no[]" class="form-control aadhar_no" placeholder="Aadhar No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" />
                                            </td>
                                            <td>
                                                <input type="text" id="pan_no1" name="pan_no[]" class="form-control pan_no" placeholder="PAN No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                                <input type="email" id="email1" name="email[]" class="form-control email" placeholder="Email ID" value="" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td class="text-2x">
                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                                &nbsp;
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Address</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Property Address <span class="text-danger">*</span></label>
                        <div class="col-md-7 pad-btm">
                            <textarea id="prop_address" name="prop_address" class="form-control" placeholder="Property Address" onkeypress="return isAlphaNumCommaSlash(event);"><?=(isset($prop_address))?$prop_address:"";?></textarea>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-md-3">City <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="prop_city" name="prop_city" class="form-control" placeholder="City" value="<?=(isset($ulb_address))?$ulb_address['city']:"";?>" onkeypress="return isAlpha(event);" readonly />
                        </div>
                        <label class="col-md-3">District <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="prop_dist" name="prop_dist" class="form-control" placeholder="District" value="<?=(isset($ulb_address))?$ulb_address['district']:"";?>" onkeypress="return isAlpha(event);" readonly />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">State <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="prop_state" name="prop_state" class="form-control" placeholder="State" value="<?=(isset($ulb_address))?$ulb_address['state']:"";?>" onkeypress="return isAlpha(event);" readonly />
                        </div>
                        <label class="col-md-3">Pin <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="prop_pin_code" name="prop_pin_code" class="form-control" placeholder="Pin" value="<?=(isset($prop_pin_code))?$prop_pin_code:"";?>" onkeypress="return isNum(event);" maxlength="6" />
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Demand Details</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>From Date <span class="text-danger">*</span></th>
                                                <th>To Date <span class="text-xs">(Leave blank for current date)</span></th>
                                                <th>ARV <span class="text-danger">*</span></th>
                                                <th>Water Tax Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody id="floor_dtl_append">
                                            <tr>
                                                <td>
                                                    <input type="month" id="date_from1" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m-d");?>" onchange="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_upto1" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m-d");?>" onchange="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="arv1" name="arv[]" class="form-control arv" placeholder="ARV" value="" style="width: 100px;" onkeypress="return isNumDot(event);" onkeyup="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <select name="water_tax_per" class="form-control" id="water_tax_per">
                                                        <option value="7.5">7.5%</option>
                                                        <option value="12.5">12.5%</option>
                                                    </select>
                                                </td>
                                                <!--<td class="text-2x">
                                                    <i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>
                                                </td>--->
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="table-responsive">
                    <table class="table table-bordered text-sm">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>ARV</th>
                                <th>Effect From</th>
                                <th>Holding Tax</th>
                                <th>Water Tax</th>
                                <th>Conservancy/Latrine Tax</th>
                                <th>Eduction Cess</th>
                                <th>Helth Cess</th>
                                <th>Qatrly Tax</th>
                            <tr>
                        </thead>
                        <tbody id="tax_preview">

                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>  
                </div>       
            </div>  
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn text-center">
                    <button type="button" id="btn_preview" name="btn_preview" class="btn btn-info">PREVIEW</button>
                    <button type="SUBMIT" id="btn_submit" name="btn_submit" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">

    var zo=1;
    function owner_dtl_append_fun(){
        zo++;
        var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><select id="relation_type'+zo+'" name="relation_type[]" class="form-control relation_type" style="width: 100px;"><option value="">SELECT</option><option value="S/O">S/O</option><option value="D/O">D/O</option><option value="W/O">W/O</option><option value="C/O">C/O</option></select></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="aadhar_no'+zo+'" name="aadhar_no[]" class="form-control aadhar_no" placeholder="Aadhar No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="pan_no'+zo+'" name="pan_no[]" class="form-control pan_no" placeholder="PAN No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="email" id="email'+zo+'" name="email[]" class="form-control email" placeholder="Email ID" value="" onkeyup="borderNormal(this.id);" /></td></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#owner_dtl_append").append(appendData);        
    }
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
    });
    /*var zf=1;
    function floor_dtl_append_fun(){
        zf++;
        var appendData = '<tr><td><input type="month" id="date_from'+zf+'" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;" onchange="borderNormal(this.id);" /></td><td><input type="month" id="date_upto'+zf+'" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;" onchange="borderNormal(this.id);" /></td><td><input type="text" id="arv'+zf+'" name="arv[]" class="form-control arv" placeholder="ARV" value="" style="width: 100px;" onkeypress="return isNumDot(event);" onkeyup="borderNormal(this.id);" /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>  &nbsp; <i class="fa fa-window-close remove_floor_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#floor_dtl_append").append(appendData);        
    }
    $("#floor_dtl_append").on('click', '.remove_floor_dtl', function(e) {
        $(this).closest("tr").remove();
    });*/
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

    function isNumDot(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46){
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }else{
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }
    function isEmail(emailVal) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(emailVal) ) {
            return false;
        }else{
            return true;
        }
    }

    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }
    function isDateFormatYYYMMDD(value){
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(value) ) {
            return false;
        }else{
            return true;
        }
    }

    $("#btn_submit").click(function(){
        var process = true;
        var ward_mstr_id = $("#ward_mstr_id").val();
        var holding_no = $("#holding_no").val();
        var ownership_type_mstr_id = $("#ownership_type_mstr_id").val();
        var prop_type_mstr_id = $("#prop_type_mstr_id").val();
        var road_type_mstr_id = $("#road_type_mstr_id").val();
        var prop_address = $("#prop_address").val();
        var prop_city = $("#prop_city").val();
        var prop_dist = $("#prop_dist").val();
        var prop_pin_code = $("#prop_pin_code").val();
        if(ward_mstr_id==""){
            $("#ward_mstr_id").css('border-color', 'red'); process = false;
        }
        if(holding_no==""){
            $("#holding_no").css('border-color', 'red'); process = false;
        }

        if(ownership_type_mstr_id==""){
            $("#ownership_type_mstr_id").css('border-color', 'red'); process = false;
        }
        if(prop_type_mstr_id==""){
            $("#prop_type_mstr_id").css('border-color', 'red'); process = false;
        }
        if(road_type_mstr_id==""){
            $("#road_type_mstr_id").css('border-color', 'red'); process = false;
        }
        $(".owner_name").each(function() {
                var ID = this.id.split('owner_name')[1];
                var owner_name = $("#owner_name"+ID).val();
                var guardian_name = $("#guardian_name"+ID).val();
                var relation_type = $("#relation_type"+ID).val();
                var mobile_no = $("#mobile_no"+ID).val();
                var aadhar_no = $("#aadhar_no"+ID).val();
                var pan_no = $("#pan_no"+ID).val();
                var email = $("#email"+ID).val();

                if(owner_name.length < 3){
                    $("#owner_name"+ID).css('border-color', 'red'); process = false;
                }
                if(guardian_name!=""){
                    if(guardian_name.length < 3){
                        $("#guardian_name"+ID).css('border-color', 'red'); process = false;
                    }
                }
                if(mobile_no.length!=10){
                    $("#mobile_no"+ID).css('border-color', 'red'); process = false;
                }
                if(aadhar_no!=""){
                    if(aadhar_no.length!=12){
                        $("#aadhar_no"+ID).css('border-color', 'red'); process = false;
                    }
                }
                if(pan_no!=""){
                    if(pan_no.length!=10){
                        $("#pan_no"+ID).css('border-color', 'red'); process = false;
                    }
                }
                if(email!=""){
                    if(!isEmail(email)){
                        $("#email"+ID).css('border-color', 'red'); process = false;
                    }
                }
            });
            if (prop_address=="") {
                $("#prop_address").css('border-color', 'red'); process = false;
            }
            if (prop_city=="") {
                 $("#prop_city").css('border-color', 'red'); process = false;
            }
            if (prop_dist=="") {
                $("#prop_dist").css('border-color', 'red'); process = false;
            }
             if (prop_pin_code=="" || prop_pin_code.length!=6) {
                $("#prop_pin_code").css('border-color', 'red'); process = false;
            }
        $(".date_from").each(function() {
                var ID = this.id.split('date_from')[1];

                var date_from = $("#date_from"+ID).val();
                var date_upto = $("#date_upto"+ID).val();
                var arv = $("#arv"+ID).val();
                if (date_from=="") {
                    $("#date_from"+ID).css('border-color', 'red'); process = false;
                } else {
                    if ( !isDateFormatYYYMMDD(date_from+"-01") ){
                        $("#date_from"+ID).css('border-color', 'red'); process = false;
                    }
                }
                if (date_upto!="") {
                    var com_date_from = new Date(date_from+"-01");
                    var com_date_upto = new Date(date_upto+"-01");

                    if ( !isDateFormatYYYMMDD(date_upto+"-01") ){
                        $("#date_upto"+ID).css('border-color', 'red'); process = false;
                    } else if (com_date_from.getTime() >= com_date_upto.getTime()) {
                        $("#date_upto"+ID).css('border-color', 'red'); process = false;
                    }
                }

                if (arv=="") {
                    $("#arv"+ID).css('border-color', 'red'); process = false;
                }
            });

        return process;

        });
    $("#ward_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#ownership_type_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#prop_type_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#holding_no").keyup(function(){ $(this).css('border-color', ''); });
    $("#road_type_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#prop_address").keyup(function(){ $(this).css('border-color', ''); });
    $("#prop_city").keyup(function(){ $(this).css('border-color', ''); });
    $("#prop_dist").keyup(function(){ $(this).css('border-color', ''); });
    $("#prop_pin_code").keyup(function(){ $(this).css('border-color', ''); });


    $("#btn_preview").click(function()
    {
        var date_from1 = $("#date_from1").val().trim();
        var date_upto1 = $("#date_upto1").val().trim();
        var arv1 = $("#arv1").val().trim();
        var water_tax_per = $("#water_tax_per").val().trim();

		$('#btn_preview').html('Proccessing....');
        $.ajax({
			type: "POST",
			url: "<?=base_url('/LegacyEntry/getTax');?>",
			dataType:"json",
			data:
			{
				date_from: date_from1, date_upto:date_upto1, arv:arv1, water_tax_per:water_tax_per
			},
			beforeSend: function() {
				$("#btn_preview").html('<i class="fa fa-refresh fa-spin"></i>').prop('disabled', true);
			},
			success: function(data)
			{
				$("#btn_preview").html('Preview').prop('disabled', false);
				if(data.status==true)
				{
					data = data.data;
					$('#tax_preview').html(data);
				}
				else
				{
					$('#tax_preview').html(data.data);
					return false;
				}
			}
		});
    });
</script>