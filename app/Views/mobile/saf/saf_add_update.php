<?= $this->include('layout_mobi/header');?>
<link href="<?=base_url();?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<style type="text/css">
    .error {
        color: red;
    }
    .blink { 
        text-align: center; 
        animation: animate  
            3s linear infinite; 
    } 

    @keyframes animate { 
        0% { 
            opacity: 0; 
        } 
        50% { 
            opacity: 0.7; 
        } 
        100% { 
            opacity: 0; 
        } 
    }
</style>

<?php $session = session(); ?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <form id="form_saf_property" name="form_saf_property" method="post" action="<?=base_url('MobiSaf/AddUpdate2');?><?=(isset($param))?"/".$param:"";?>">
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
                    <h3 class="panel-title">Self Assessment Form (<?=$assessment_type??""?>)
                        <?php
                        if(isset($dueAmount) && $dueAmount){
                            ?>
                                <sapn class="btn btn-primary blink">Old Demand of (<?=$dueAmount;?>) Is Not Clear</sapn>
                            <?php
                        }
                        ?>
                    </h3>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Does the property being assessed has any previous Holding Number? <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="hidden" id="has_previous_holding_no" name="has_previous_holding_no" class="form-control" value="<?=$has_previous_holding_no?>" /> <?=($has_previous_holding_no==1)?"YES":"NO";?>
                        </div>
                        <div class="<?=($has_previous_holding_no==0)?"hidden":"";?>">
                            <label class="col-md-3">Previous Holding No. <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <input type="hidden" id="prev_prop_dtl_id" name="prev_prop_dtl_id" class="form-control" value="<?=(isset($prev_prop_dtl_id))?$prev_prop_dtl_id:"";?>" />
                                <input type="text" id="holding_no" name="holding_no" class="form-control" placeholder="Previous Holding No" value="<?=(isset($holding_no))?$holding_no:"";?>" onkeypress="return isAlphaNum(event);" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="<?=($has_previous_holding_no==1)?"":"hidden";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No. <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <input type="hidden" id="is_owner_changed" name="is_owner_changed" class="form-control" value="<?=$is_owner_changed;?>" /> <?=($is_owner_changed==1)?"YES":"NO";?>
                            </div>
                            <?php if ($is_owner_changed=="1") { ?>
                            <div id="is_owner_changed_tran_property_hide_show" class="">
                                <label class="col-md-3">Mode of transfer of property from previous Holding Owner <span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <select id="transfer_mode_mstr_id" name="transfer_mode_mstr_id" class="form-control">
                                        <option value="">== SELECT ==</option>
                                        <?php foreach ($transferModeList as $transferMode) { ?>
                                            <option value="<?=$transferMode['id'];?>" <?=(isset($transfer_mode_mstr_id))?($transferMode['id']==$transfer_mode_mstr_id)?"selected":"":"";?>><?=$transferMode['transfer_mode'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Ward No <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control" onchange="apartment_map(this.value)">
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
                        <label class="col-md-3">New Ward No <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="new_ward_mstr_id" name="new_ward_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($newWardList)){
                                    foreach ($newWardList as $ward) {
                                ?>
                                    <option value="<?=$ward['id'];?>" <?=(isset($new_ward_mstr_id))?($ward['id']==$new_ward_mstr_id)?"selected":"":"";?>><?=$ward['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
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
                    </div>
                    <div class="row">
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
                    <div id="appartment_name_hide_show" class="hidden">
                        <div class="row">
                            <label class="col-md-3">Appartment Name <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <!-- <input type="text" id="appartment_name" name="appartment_name" class="form-control" placeholder="Appartment Name" value="<?=(isset($appartment_name))?$appartment_name:"";?>" onkeypress="return isAlphaNum(event);" /> -->
                                <select id="apartment_details_id" name="apartment_details_id" class="form-control" style="width: 100%;;">
                                    <option value="">== SELECT ==</option>
                                    <?php
                                    if(isset($apartmentDetailsList)){
                                        foreach ($apartmentDetailsList as $apartmentDetails) {
                                    ?>
                                    <option value="<?=$apartmentDetails['id'];?>" <?=(isset($apartment_details_id))?($apartmentDetails['id']==$apartment_details_id)?"selected":"":"";?> data-option='<?php echo $apartmentDetails['water_harvesting_status'];?>'><?=$apartmentDetails['apartment_name'];?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-md-3">Flat Registry Date</label>
                            <div class="col-md-3 pad-btm">
                                <input type="date" id="flat_registry_date" name="flat_registry_date" class="form-control" placeholder="Flat Registry Date" value="<?=(isset($flat_registry_date))?$flat_registry_date:"";?>" max="<?=date("Y-m-d");?>" />
                            </div>
                        </div>
                    </div>
                <?php
                $db_name = $session->get('ulb_dtl');
                if ($db_name['ulb_mstr_id']==1) {
                ?>
                    <div class="row">
                        <label class="col-md-3">Zone</label>
                        <div class="col-md-3">
                            <select id="zone_mstr_id" name="zone_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <option value="1" <?=(isset($zone_mstr_id))?($zone_mstr_id=='1')?"selected":"":"";?>>Zone 1</option>
                                <option value="2" <?=(isset($zone_mstr_id))?($zone_mstr_id=='2')?"selected":"":"";?>>Zone 2</option>
                            </select>
                        </div>
						<div class="col-md-3">
                            <p><b>Zone 1 : Over bridge to Saheed chowk.</b></p>
							<p><b>Zone 2 : Rest area other than Zone1.</b></p>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <input type="hidden" id="zone_mstr_id" name="zone_mstr_id" value="1" />
                <?php
                }
                ?>
                </div>
            </div>
            
            <?php if ($has_previous_holding_no=="1") { ?>
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=($has_previous_holding_no=="")?"":"Previous Owner Details";?></h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Guardian Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No</th>
                                            <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                            <th>Is Armed Force</th>
                                            <th>Is Specially Abled</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($prop_owner_dtl_list)) {
                                        foreach ($prop_owner_dtl_list as $key=>$prop_owner_dtl) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?=$prop_owner_dtl['owner_name'];?>
                                                    <input type="hidden" id="prev_owner_name<?=$key+1;?>" name="prev_owner_name[]" class="prev_owner_name" value="<?=$prop_owner_dtl['owner_name'];?>" />
                                                </td>
                                                <td>
                                                    <?php if ($prop_owner_dtl['gender']!="") { ?>
                                                        <?=$prop_owner_dtl['gender'];?>
                                                        <input type="hidden" id="prev_gender<?=$key+1;?>" name="prev_gender[]" class="prev_gender" value="<?=$prop_owner_dtl['gender'];?>" />
                                                    <?php } else {?>
                                                        <select id="prev_gender<?=$key+1;?>" name="prev_gender[]" class="form-control prev_gender">
                                                            <option value="">SELECT</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($prop_owner_dtl['dob']!="") { ?>
                                                        <?=$prop_owner_dtl['dob'];?>
                                                        <input type="hidden" id="prev_dob<?=$key+1;?>" name="prev_dob[]" class="prev_dob" value="<?=$prop_owner_dtl['dob'];?>" />
                                                    <?php } else {?>
                                                        <input type="date" id="prev_dob<?=$key+1;?>" name="prev_dob[]" class="form-control prev_dob" placeholder="Date of Birth" max="<?=date("Y-m-d");?>">
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?=$prop_owner_dtl['guardian_name'];?>
                                                    <input type="hidden" id="prev_guardian_name<?=$key+1;?>" name="prev_guardian_name[]" class="prev_guardian_name" value="<?=$prop_owner_dtl['guardian_name'];?>" />
                                                </td>
                                                <td>
                                                    <?=$prop_owner_dtl['relation_type'];?>
                                                    <input type="hidden" id="prev_relation_type<?=$key+1;?>" name="prev_relation_type[]" class="prev_relation_type" value="<?=$prop_owner_dtl['relation_type'];?>" />
                                                </td>
                                                <td>
                                                    <?=$prop_owner_dtl['mobile_no'];?>
                                                    <input type="hidden" id="prev_mobile_no<?=$key+1;?>" name="prev_mobile_no[]" class="prev_mobile_no" value="<?=$prop_owner_dtl['mobile_no'];?>" />
                                                </td>
                                                <td>
                                                    <?=$prop_owner_dtl['aadhar_no'];?>
                                                    <input type="hidden" id="prev_aadhar_no<?=$key+1;?>" name="prev_aadhar_no[]" class="prev_aadhar_no" value="<?=$prop_owner_dtl['aadhar_no'];?>" />
                                                </td>
                                                <td>
                                                    <?=$prop_owner_dtl['pan_no'];?>
                                                    <input type="hidden" id="prev_pan_no<?=$key+1;?>" name="prev_pan_no[]" class="prev_pan_no" value="<?=$prop_owner_dtl['pan_no'];?>" />
                                                </td>
                                                <td>
                                                    <?=$prop_owner_dtl['email'];?>
                                                    <input type="hidden" id="prev_email<?=$key+1;?>" name="prev_email[]" class="prev_email" value="<?=$prop_owner_dtl['email'];?>" />
                                                </td>
                                                <td>
                                                    <?php if ($prop_owner_dtl['is_specially_abled']!="") { ?>
                                                        <?=(in_array($prop_owner_dtl['is_specially_abled'], ['t', 'true', 1]))?"YES":"NO";?>
                                                        <input type="hidden" id="prev_is_specially_abled<?=$key+1;?>" name="prev_is_specially_abled[]" class="prev_is_specially_abled" value="<?=(in_array($prop_owner_dtl['is_specially_abled'], ['t', 'true', 1]))?"YES":"NO";?>" />
                                                    <?php } else {?>
                                                        <select id="prev_is_specially_abled<?=$key+1;?>" name="prev_is_specially_abled[]" class="form-control prev_is_specially_abled valid" aria-invalid="false">
                                                            <option value="NO">NO</option>
                                                            <option value="YES">YES</option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($prop_owner_dtl['is_armed_force']!="") { ?>
                                                        <?=(in_array($prop_owner_dtl['is_armed_force'], ['t', 'true', 1]))?"YES":"NO";?>
                                                        <input type="hidden" id="prev_is_armed_force<?=$key+1;?>" name="prev_is_armed_force[]" class="prev_is_armed_force" value="<?=(in_array($prop_owner_dtl['is_armed_force'], ['t', 'true', 1]))?"YES":"NO";?>" />
                                                    <?php } else {?>
                                                        <select id="prev_is_armed_force<?=$key+1;?>" name="prev_is_armed_force[]" class="form-control prev_is_armed_force">
                                                            <option value="NO">NO</option>
                                                            <option value="YES">YES</option>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        foreach ($prev_owner_name as $key=>$prop_owner_dtl) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <?=$prev_owner_name[$key];?>
                                                    <input type="hidden" id="prev_owner_name<?=$key+1;?>" name="prev_owner_name[]" class="prev_owner_name" value="<?=$prev_owner_name[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_gender[$key];?>
                                                    <input type="hidden" id="prev_gender<?=$key+1;?>" name="prev_gender[]" class="prev_gender" value="<?=$prev_gender[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_dob[$key];?>
                                                    <input type="hidden" id="prev_dob<?=$key+1;?>" name="prev_dob[]" class="prev_dob" value="<?=$prev_dob[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_guardian_name[$key];?>
                                                    <input type="hidden" id="prev_guardian_name<?=$key+1;?>" name="prev_guardian_name[]" class="prev_guardian_name" value="<?=$prev_guardian_name[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_relation_type[$key];?>
                                                    <input type="hidden" id="prev_relation_type<?=$key+1;?>" name="prev_relation_type[]" class="prev_relation_type" value="<?=$prev_relation_type[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_mobile_no[$key];?>
                                                    <input type="hidden" id="prev_mobile_no<?=$key+1;?>" name="prev_mobile_no[]" class="prev_mobile_no" value="<?=$prev_mobile_no[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_aadhar_no[$key];?>
                                                    <input type="hidden" id="prev_aadhar_no<?=$key+1;?>" name="prev_aadhar_no[]" class="prev_aadhar_no" value="<?=$prev_aadhar_no[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_pan_no[$key];?>
                                                    <input type="hidden" id="prev_pan_no<?=$key+1;?>" name="prev_pan_no[]" class="prev_pan_no" value="<?=$prev_pan_no[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_email[$key];?>
                                                    <input type="hidden" id="prev_email<?=$key+1;?>" name="prev_email[]" class="prev_email" value="<?=$prev_email[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_is_specially_abled[$key];?>
                                                    <input type="hidden" id="prev_is_specially_abled<?=$key+1;?>" name="prev_is_specially_abled[]" class="prev_is_specially_abled" value="<?=$prev_is_specially_abled[$key];?>" />
                                                </td>
                                                <td>
                                                    <?=$prev_is_armed_force[$key];?>
                                                    <input type="hidden" id="prev_is_armed_force<?=$key+1;?>" name="prev_is_armed_force[]" class="prev_is_armed_force" value="<?=$prev_is_armed_force[$key];?>" />
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
            <?php } ?>
            <div class="panel panel-bordered panel-dark <?=($has_previous_holding_no=="0" || ($has_previous_holding_no=="1" && $is_owner_changed=="1"))?"":"hidden";?>">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" id="owner_dtl_append_test" style="padding-bottom: 0px;">
                <?php
                $zo = 1;
                if (isset($owner_name)) {
                    $zo = sizeof($owner_name);
                    for($i=0; $i < $zo; $i++){
                ?>
                    <div class="panel panel-bordered-primary new_owner_dtl_panel">
                        <div class="panel-body"style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Owner Name <span class="text-danger">*</span></label>
                                        <input type="text" id="owner_name<?=$i+1;?>" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="<?=$owner_name[$i];?>" onkeypress="return isAlpha(event);" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Gender <span class="text-danger">*</span></label>
                                        <select id="gender<?=$i+1;?>" name="gender[]" class="form-control gender">
                                            <option value="">SELECT</option>
                                            <option value="Male" <?=($gender[$i]=="Male")?"selected":"";?>>Male</option>
                                            <option value="Female" <?=($gender[$i]=="Female")?"selected":"";?>>Female</option>
                                            <option value="Other" <?=($gender[$i]=="Other")?"selected":"";?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">DOB <span class="text-danger">*</span></label>
                                        <input type="date" id="dob<?=$i+1;?>" name="dob[]" class="form-control dob" placeholder="Date of Birth" max="<?=date('Y-m-d', strtotime('-18 years'));?>" value="<?=($dob[$i]!="")?$dob[$i]:"";?>" onkeypress="return isAlpha(event);" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Mobile No. <span class="text-danger">*</span></label>
                                        <input type="text" id="mobile_no<?=$i+1;?>" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="<?=$mobile_no[$i];?>" onkeypress="return isNum(event);" maxlength="10" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Is Armed Force <span class="text-danger">*</span></label>
                                        <select id="is_armed_force<?=$i+1;?>" name="is_armed_force[]" class="form-control is_armed_force" >
                                            <option value="NO" <?=($is_armed_force[$i]=="NO")?"selected":"";?>>NO</option>
                                            <option value="YES" <?=($is_armed_force[$i]=="YES")?"selected":"";?>>YES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Is Specially Abled <span class="text-danger">*</span></label>
                                        <select id="is_specially_abled<?=$i+1;?>" name="is_specially_abled[]" class="form-control is_specially_abled" >
                                            <option value="NO" <?=($is_specially_abled[$i]=="NO")?"selected":"";?>>NO</option>
                                            <option value="YES" <?=($is_specially_abled[$i]=="YES")?"selected":"";?>>YES</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                <label class="control-label"><span style="cursor: pointer;" onclick="owner_dtl_append_fun();">Click Here</span> to Add More Owners</label>
                                    <div class="form-group text-2x">
                                        <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                        <?php if($i!=0){ ?>
                                             | <i class="fa fa-window-close" style="cursor: pointer;"></i>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                } else {
                ?>
                    <div class="panel panel-bordered-primary new_owner_dtl_panel">
                        <div class="panel-body"style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Owner Name <span class="text-danger">*</span></label>
                                        <input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Gender <span class="text-danger">*</span></label>
                                        <select id="gender1" name="gender[]" class="form-control gender">
                                            <option value="">SELECT</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">DOB <span class="text-danger">*</span></label>
                                        <input type="date" id="dob1" name="dob[]" class="form-control dob" placeholder="Date of Birth" max="<?=date('Y-m-d', strtotime('-18 years'));?>"/>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Mobile No. <span class="text-danger">*</span></label>
                                        <input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" maxlength="10" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Is Armed Force <span class="text-danger">*</span></label>
                                        <select id="is_armed_force1" name="is_armed_force[]" class="form-control is_armed_force" >
                                            <option value="NO">NO</option>
                                            <option value="YES">YES</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Is Specially Abled <span class="text-danger">*</span></label>
                                        <select id="is_specially_abled1" name="is_specially_abled[]" class="form-control is_specially_abled" >
                                            <option value="NO">NO</option>
                                            <option value="YES">YES</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label class="control-label"><span style="cursor: pointer;" onclick="owner_dtl_append_fun();">Click Here</span> to Add More Owners</label>
                                    <div class="form-group text-2x">
                                        <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Area of Plot <span class="text-bold">(in Decimal)</span> <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="area_of_plot" name="area_of_plot" class="form-control" placeholder="Area of Plot" <?php if(isset($assessment_type) && $assessment_type=='Re-Assessment' && $area_of_plot>0){echo 'Readonly="Readonly"';} ?> value="<?=(isset($area_of_plot))?$area_of_plot:"";?>" onkeypress="return isNumDot(event);" />
                        </div>
                    </div>
                    <div class="row">
                        <div id="road_width_hide_show">
                            <label class="col-md-3">Road Width <span class="text-bold">(in ft)</span> <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="road_type_width" name="road_type_width" class="form-control" placeholder="Road Width" value="<?=(isset($road_type_width))?$road_type_width:"";?>" maxlength="2" onkeypress="return isNum(event);" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ELECTRICITY DETAILS START HERE -->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Electricity Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div id="no_electric_connection_hide_show" class="hidden">
                        <div class="row">
                            <div class="col-md-12 pad-btm">
                                <div class="checkbox">
                                    <input id="no_electric_connection" name="no_electric_connection" class="magic-checkbox" type="checkbox" onclick="noElectConnCkFun();" <?=(isset($no_electric_connection))?($no_electric_connection==1)?"checked":"":"";?> value="1" >
                                    <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="electric_dtl_hide_show" class="hidden">
                        <div class="row">
                            <label class="col-md-3">Electricity K. No</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="elect_consumer_no" name="elect_consumer_no" class="form-control" placeholder="Electricity K. No" value="<?=(isset($elect_consumer_no))?$elect_consumer_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                        </div>
                        <div class="row">
                            <label class="col-md-3">ACC No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="elect_acc_no" name="elect_acc_no" class="form-control" placeholder="ACC No." value="<?=(isset($elect_acc_no))?$elect_acc_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            </div>
                            <label class="col-md-3">BIND/BOOK No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="elect_bind_book_no" name="elect_bind_book_no" class="form-control" placeholder="BIND/BOOK No." value="<?=(isset($elect_bind_book_no))?$elect_bind_book_no:"";?>" onkeypress="return isAlphaNum(event);" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Electricity Consumer Category</label>
                            <div class="col-md-3 pad-btm">
                                <select id="elect_cons_category" name="elect_cons_category" class="form-control">
                                    <option value="">== SELECT ==</option>
                                    <option value="DS I/II/III" <?=(isset($elect_cons_category))?($elect_cons_category=='DS I/II/III')?"selected":"":"";?>>DS I/II/III</option>
                                    <option value="NDS II/III" <?=(isset($elect_cons_category))?($elect_cons_category=='NDS II/III')?"selected":"":"";?>>NDS II/III</option>
                                    <option value="IS I/II" <?=(isset($elect_cons_category))?($elect_cons_category=='IS I/II')?"selected":"":"";?>>IS I/II</option>
                                    <option value="LTS" <?=(isset($elect_cons_category))?($elect_cons_category=='LTS')?"selected":"":"";?>>LTS</option>
                                    <option value="HTS" <?=(isset($elect_cons_category))?($elect_cons_category=='HTS')?"selected":"":"";?>>HTS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <!-- ELECTRICITY DETAILS END HERE -->

             <!-- BUILDING PALN APPROVAL DATE -->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Building Plan/Water Connection Details</h3>
                 </div>
              <div class="panel-body" style="padding-bottom: 0px;">
                 <div class="row">
                    <label class="col-md-3">Building Plan Approval No. </label>
                     <div class="col-md-3 pad-btm">
                        <input type="text" id="building_plan_approval_no" name="building_plan_approval_no" class="form-control" placeholder="Building Plan Approval No." value="<?=(isset($building_plan_approval_no))?$building_plan_approval_no:"";?>" onkeypress="return isAlphaNum(event);" />
                    </div>
                    <label class="col-md-3">Building Plan Approval Date </label>
                     <div class="col-md-3 pad-btm">
                         <input type="date" id="building_plan_approval_date" name="building_plan_approval_date" class="form-control" value="<?=(isset($building_plan_approval_date))?$building_plan_approval_date:"";?>" max="<?=date("Y-m-d");?>" />
                     </div>
                  </div>
                 <div class="row">
                    <label class="col-md-3">Water Consumer No. </label>
                    <div class="col-md-3 pad-btm">
                        <input type="text" id="water_conn_no" name="water_conn_no" class="form-control" placeholder="Water Consumer No." value="<?=(isset($water_conn_no))?$water_conn_no:"";?>" onkeypress="return isAlphaNum(event);" />
                    </div>
                    <label class="col-md-3">Water Connection Date </label>
                    <div class="col-md-3 pad-btm">
                        <input type="date" id="water_conn_date" name="water_conn_date" class="form-control" value="<?=(isset($water_conn_date))?$water_conn_date:"";?>" max="<?=date("Y-m-d");?>" />
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-3">Trade License No. </label>
                    <div class="col-md-3 pad-btm">
                        <input type="text" id="trade_license_no" name="trade_license_no" class="form-control" placeholder="Trade License No." value="<?=(isset($trade_license_no))?$trade_license_no:"";?>" onkeypress="return isAlphaNum(event);" />
                    </div>
                    
                </div>
            </div>
          <!-- BUILDING PLAN APPROVAL DATE END HERE -->

            <div id="floor_dtl_hide_show" class="hidden">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Floor Details</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-12 pad-btm">
                                <span class="text-bold text-dark">Built Up :</span>
                                <span class="text-thin">It refers to the entire carpet area along with the thickness of the external walls of the apartment. It includes the thickness of the internal walls and the columns.</span>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Floor No <span class="text-danger">*</span></th>
                                                <th>Use Type <span class="text-danger">*</span></th>
                                                <th>Occupancy Type <span class="text-danger">*</span></th>
                                                <th>Construction Type <span class="text-danger">*</span></th>
                                                <th>Built Up Area  (in Sq. Ft) <span class="text-danger">*</span></th>
                                                <th>From Date <span class="text-danger">*</span> <br />(YYYY-MM)</th>
                                                <th>Upto Date <span class="text-xs">(Leave blank for current date)</span> <br />(YYYY-MM)</th>
                                                <th>Add/Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody id="floor_dtl_append">
                                    <?php
                                    $zf = 1;
                                    if(isset($floor_mstr_id)){
                                        $zf = sizeof($floor_mstr_id);
                                        for($i=0; $i < $zf; $i++){
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" id="prop_floor_details_id<?=$i+1;?>" name="prop_floor_details_id[]" value="<?=isset($prop_floor_details_id[$i])?$prop_floor_details_id[$i]:"";?>" />
                                                    <select id="floor_mstr_id<?=$i+1;?>" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" >
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($floorList)){
                                                            foreach ($floorList as $floor) {
                                                        ?>
                                                        <option value="<?=$floor['id'];?>" <?=($floor['id']==$floor_mstr_id[$i])?"selected":"";?>><?=$floor['floor_name'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="usage_type_mstr_id<?=$i+1;?>" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" style="width: 200px;" onchange="checkSchoolTrust(this.value)">
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($usageTypeList)){
                                                            foreach ($usageTypeList as $usageType) {
                                                        ?>
                                                        <option value="<?=$usageType['id'];?>" <?=($usageType['id']==$usage_type_mstr_id[$i])?"selected":"";?>><?=$usageType['usage_type'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="occupancy_type_mstr_id<?=$i+1;?>" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" >
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($occupancyTypeList)){
                                                            foreach ($occupancyTypeList as $occupancyType) {
                                                        ?>
                                                        <option value="<?=$occupancyType['id'];?>" <?=($occupancyType['id']==$occupancy_type_mstr_id[$i])?"selected":"";?>><?=$occupancyType['occupancy_name'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="const_type_mstr_id<?=$i+1;?>" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" >
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($constTypeList)){
                                                            foreach ($constTypeList as $constType) {
                                                        ?>
                                                        <option value="<?=$constType['id'];?>" <?=($constType['id']==$const_type_mstr_id[$i])?"selected":"";?>><?=$constType['construction_type'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="builtup_area<?=$i+1;?>" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="<?=$builtup_area[$i];?>" style="width: 100px;" onkeypress="return isNumDot(event);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_from<?=$i+1;?>" name="date_from[]" class="form-control date_from" value="<?=$date_from[$i];?>" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m");?>"  />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_upto<?=$i+1;?>" name="date_upto[]" class="form-control date_upto" value="<?=$date_upto[$i];?>" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m");?>"  />
                                                </td>
                                                <td class="text-2x">
                                                    <i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>
                                                    &nbsp;
                                                <?php if($i!=0){ ?>
                                                    <i class="fa fa-window-close remove_floor_dtl" style="cursor: pointer;"></i>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }else{
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" id="prop_floor_details_id1" name="prop_floor_details_id[]" value="" />
                                                    <select id="floor_mstr_id1" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" >
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($floorList)){
                                                            foreach ($floorList as $floor) {
                                                        ?>
                                                        <option value="<?=$floor['id'];?>"><?=$floor['floor_name'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="usage_type_mstr_id1" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" style="width: 200px;" onchange="checkSchoolTrust(this.value)">
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($usageTypeList)){
                                                            foreach ($usageTypeList as $usageType) {
                                                        ?>
                                                        <option value="<?=$usageType['id'];?>"><?=$usageType['usage_type'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="occupancy_type_mstr_id1" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" >
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($occupancyTypeList)){
                                                            foreach ($occupancyTypeList as $occupancyType) {
                                                        ?>
                                                        <option value="<?=$occupancyType['id'];?>"><?=$occupancyType['occupancy_name'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="const_type_mstr_id1" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" >
                                                        <option value="">SELECT</option>
                                                        <?php
                                                        if(isset($constTypeList)){
                                                            foreach ($constTypeList as $constType) {
                                                        ?>
                                                        <option value="<?=$constType['id'];?>"><?=$constType['construction_type'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="builtup_area1" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="" style="width: 100px;" onkeypress="return isNumDot(event);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_from1" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m");?>"  />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_upto1" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m");?>"  />
                                                </td>
                                                <td class="text-2x">
                                                    <i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>
                                                </td>
                                            </tr>
                                    <?php
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
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Does Property Have Mobile Tower(s) ? <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select class="form-control" id="is_mobile_tower" name="is_mobile_tower" onchange="is_mobile_tower_fun();">
                                <option value="">SELECT</option>
                                <option value="1" <?=(isset($is_mobile_tower))?($is_mobile_tower=="1")?"selected":"":"";?>>YES</option>
                                <option value="0" <?=(isset($is_mobile_tower))?($is_mobile_tower=="0")?"selected":"":"selected";?>>NO</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden" id="is_mobile_tower_hide_show">
                        <div class="row">
                            <label class="col-md-3">Total Area Covered by Mobile Tower & its
                                Supporting Equipments & Accessories (in Sq. Ft.) <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" id="tower_area" name="tower_area" class="form-control" placeholder="Area" value="<?=(isset($tower_area))?$tower_area:"";?>" onkeypress="return isNumDot(event);" />
                            </div>
                            <label class="col-md-3">Date of Installation of Mobile Tower <span class="text-danger">*</span></label>
                            <div class="col-md-3 ">
                                <input type="date" id="tower_installation_date" name="tower_installation_date" class="form-control" value="<?=(isset($tower_installation_date))?$tower_installation_date:"";?>" max="<?=date("Y-m-d");?>" />
                            </div>
                        </div>
                        <hr />
                    </div>

                    <div class="row">
                        <label class="col-md-3">Does Property Have Hoarding Board(s) ? <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="is_hoarding_board" name="is_hoarding_board" class="form-control" onchange="is_hoarding_board_fun();">
                                <option value="">SELECT</option>
                                <option value="1" <?=(isset($is_hoarding_board))?($is_hoarding_board=="1")?"selected":"":"";?>>YES</option>
                                <option value="0" <?=(isset($is_hoarding_board))?($is_hoarding_board=="0")?"selected":"":"selected";?>>NO</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden" id="is_hoarding_board_hide_show">
                        <div class="row">
                            <label class="col-md-3">Total Area of Wall / Roof / Land (in Sq. Ft.) <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" id="hoarding_area" name="hoarding_area" class="form-control" placeholder="Area" value="<?=(isset($hoarding_area))?$hoarding_area:"";?>" onkeypress="return isNumDot(event);" />
                            </div>
                            <label class="col-md-3">Date of Installation of Hoarding Board(s) <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="date" id="hoarding_installation_date" name="hoarding_installation_date" class="form-control" value="<?=(isset($hoarding_installation_date))?$hoarding_installation_date:"";?>" max="<?=date("Y-m-d");?>" />
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="hidden" id="petrol_pump_dtl_hide_show">
                        <div class="row">
                            <label class="col-md-3">Is property a Petrol Pump ? <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select id="is_petrol_pump" name="is_petrol_pump" class="form-control" onchange="is_petrol_pump_fun();">
                                    <option value="">SELECT</option>
                                    <option value="1" <?=(isset($is_petrol_pump))?($is_petrol_pump=="1")?"selected":"":"";?>>YES</option>
                                    <option value="0" <?=(isset($is_petrol_pump))?($is_petrol_pump=="0")?"selected":"":"selected";?>>NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="hidden" id="is_petrol_pump_hide_show">
                            <div class="row">
                                <label class="col-md-3">    
                                    Underground Storage Area (in Sq. Ft.) <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" id="under_ground_area" name="under_ground_area" class="form-control" placeholder="Area" value="<?=(isset($under_ground_area))?$under_ground_area:"";?>" onkeypress="return isNumDot(event);" />
                                </div>
                               
                                <label class="col-md-3">Completion Date of Petrol Pump <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="date" id="petrol_pump_completion_date" name="petrol_pump_completion_date" class="form-control" value="<?=(isset($petrol_pump_completion_date))?$petrol_pump_completion_date:"";?>" max="<?=date("Y-m-d");?>" />
                                </div>
                              
                            </div>
                            <hr />
                        </div>
                    </div>
                    
                    <div class="hidden" id="is_water_harvesting_hide_show">    
                        <div class="row">
                            <label class="col-md-3">Rainwater harvesting provision ? <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select id="is_water_harvesting" name="is_water_harvesting" class="form-control">
                                    <option value="">SELECT</option>
                                    <option value="1" <?=(isset($is_water_harvesting))?($is_water_harvesting=="1")?"selected":"":"";?>>YES</option>
                                    <option value="0" <?=(isset($is_water_harvesting))?($is_water_harvesting=="0")?"selected":"":"selected";?>>NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="vacant_land_occupation_date_hide_show">
                        <div class="row">
                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier) <span class="text-danger">*</span></label>
                            <div class="col-md-3">
								<?php if($assessment_type == 'New-Assessment' && false ){?>
                                    <input type="text" id="land_occupation_date" name="land_occupation_date" class="form-control" value="2016-04-01" readonly="readonly" />
                                <?php }else{?>
									<input type="date" id="land_occupation_date" name="land_occupation_date" class="form-control" value="<?=(isset($land_occupation_date))?$land_occupation_date:"";?>" max="<?=date("Y-m-d");?>" />
								<?php } ?>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="hidden" id="is_trust_school_hide_show">    
                        <div class="row">
                            <label class="col-md-3">Is it a trust ? <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select id="is_trust_school" name="is_trust_school" class="form-control">
                                    <option value="">SELECT</option>
                                    <option value="1" <?=(isset($is_trust_school))?($is_trust_school=="1")?"selected":"":"";?>>Educational Institution Run By Trust</option>
                                    <option value="2" <?=(isset($is_trust_school))?($is_trust_school=="2")?"selected":"":"";?>>Other Organisational Trust</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn text-center">
                    <?php
                        if(isset($dueAmount) && $dueAmount){
                            ?>
                            <p class="btn btn-primary blink">Old Demand of (<?=$dueAmount;?>) Is Not Clear</p>
                            <?php
                        }
                        else{
                            ?>
                            <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_mobi/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script type="text/javascript">	
    $('#apartment_details_id').select2();
    $("#ward_mstr_id").change(function() {
        var old_ward_mstr_id = $("#ward_mstr_id").val();
        if(old_ward_mstr_id!=""){
            try{
                $.ajax({
                    type:"POST",
                    url: "<?=base_url('CitizenSaf/getNewWardDtlByOldWard');?>",
                    dataType: "json",
                    data: {
                        "old_ward_mstr_id":old_ward_mstr_id,
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success:function(data){
                        if(data.response==true){
                            $("#new_ward_mstr_id").html(data.data)
                        }
                        $("#loadingDiv").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loadingDiv").hide();
                    }
                });
            }catch (err) {
                alert(err.message);
            }
        }
    });

    let propTypeMstrChngFun = function() {
        var prop_type_mstr_id = $("#prop_type_mstr_id").val();
        if(prop_type_mstr_id==""){
            $("#floor_dtl_hide_show").addClass("hidden");
        }else{
            if(prop_type_mstr_id==2){
                $("#no_electric_connection_hide_show").removeClass("hidden");
            }else{
                $("#no_electric_connection").prop( "checked", false);
                $("#electric_dtl_hide_show").removeClass("hidden");
                $("#no_electric_connection_hide_show").addClass("hidden");
            }
            if(prop_type_mstr_id==3){
                $("#appartment_name_hide_show").removeClass("hidden");
            }else{
                $("#appartment_name_hide_show").addClass("hidden");
            }
            if(prop_type_mstr_id==4){
                $("#floor_dtl_hide_show").addClass("hidden"); 
                $("#petrol_pump_dtl_hide_show").addClass("hidden");
                $("#is_water_harvesting_hide_show").addClass("hidden");
                $("#vacant_land_occupation_date_hide_show").removeClass("hidden");
            }else if(prop_type_mstr_id==3){
                $("#petrol_pump_dtl_hide_show").addClass("hidden");
				 $("#is_water_harvesting_hide_show").removeClass("hidden");
				 $("#floor_dtl_hide_show").removeClass("hidden");
            }else{
                $("#floor_dtl_hide_show").removeClass("hidden");
                $("#petrol_pump_dtl_hide_show").removeClass("hidden");
                $("#is_water_harvesting_hide_show").removeClass("hidden");
                $("#vacant_land_occupation_date_hide_show").addClass("hidden");
            }
        }
    }
    

    var zo = <?=$zo;?>;
    let owner_dtl_append_fun = function() {
        zo++;
        var appendData = '<div class="panel panel-bordered-primary new_owner_dtl_panel">';
            appendData += '<div class="panel-body"style="padding: 5px 10px;">';
            appendData += '<div class="row">';
            appendData += '<div class="col-md-2 col-xs-6"><div class="form-group"><label class="control-label">Owner Name <span class="text-danger">*</span></label><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" /></div></div>';
            appendData += '<div class="col-md-2 col-xs-6"><div class="form-group"><label class="control-label">Gender <span class="text-danger">*</span></label><select id="gender'+zo+'" name="gender[]" class="form-control gender"><option value="">SELECT</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select></div></div>';
            appendData += '<div class="col-md-2 col-xs-6"><div class="form-group"><label class="control-label">DOB <span class="text-danger">*</span></label><input type="date" id="dob'+zo+'" name="dob[]" class="form-control dob" placeholder="Date of Birth" max="<?=date('Y-m-d', strtotime('-18 years'));?>" /></div></div>';
            appendData += '<div class="col-md-2 col-xs-6"><div class="form-group"><label class="control-label">Mobile No. <span class="text-danger">*</span></label><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" maxlength="10" /></div></div>';
            appendData += '<div class="col-md-2 col-xs-6"><div class="form-group"><label class="control-label">Is Armed Force <span class="text-danger">*</span></label><select id="is_armed_force'+zo+'" name="is_armed_force[]" class="form-control is_armed_force" ><option value="NO">NO</option><option value="YES">YES</option></select></div></div>';
            appendData += '<div class="col-md-2 col-xs-6"><div class="form-group"><label class="control-label">Is Specially Abled <span class="text-danger">*</span></label><select id="is_specially_abled'+zo+'" name="is_specially_abled[]" class="form-control is_specially_abled" ><option value="NO">NO</option><option value="YES">YES</option></select></div></div>';
            appendData += '</div>';
            appendData += '<div class="row">';
            appendData += '<div class="col-md-2 col-xs-6"><label class="control-label"><span style="cursor: pointer;" onclick="owner_dtl_append_fun();">Click Here</span> to Add More Owners</label><div class="form-group text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> | <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></div></div>';
            appendData += '</div>';
            appendData += '</div>';
            appendData += '</div>';
        $("#owner_dtl_append_test").append(appendData);
    }
    $("#owner_dtl_append_test").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
        $(this).parents(".new_owner_dtl_panel").remove();
    });
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
    });
    let noElectConnCkFun = function() {
        try{
            if($("#no_electric_connection").prop("checked") == true){
                $("#electric_dtl_hide_show").addClass("hidden");
            }else{
                $("#electric_dtl_hide_show").removeClass("hidden");
            }
        } catch (err) {
            alert(err.message);
        }
    }
    let diffAddressCkFun = function() {
        try{
            if($("#is_corr_add_differ").prop("checked") == true){
                $("#corr_address_hide_show").removeClass("hidden");
            }else{
                $("#corr_address_hide_show").addClass("hidden");
            }
        } catch (err) {
            alert(err.message);
        }
    }

    var zf=<?=$zf;?>;
    let floor_dtl_append_fun = function() {
        zf++;
        var appendData = '<tr><td><select id="floor_mstr_id'+zf+'" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" ><option value="">SELECT</option><?php if(isset($floorList)){ foreach ($floorList as $floor) { ?><option value="<?=$floor['id'];?>"><?=$floor['floor_name'];?></option><?php }} ?></select></td><td><select id="usage_type_mstr_id'+zf+'" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" style="width: 200px;" onchange="checkSchoolTrust(this.value)" ><option value="">SELECT</option><?php if(isset($usageTypeList)){ foreach ($usageTypeList as $usageType) { ?><option value="<?=$usageType['id'];?>"><?=$usageType['usage_type'];?></option><?php }} ?></select></td><td><select id="occupancy_type_mstr_id'+zf+'" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" ><option value="">SELECT</option><?php if(isset($occupancyTypeList)){ foreach ($occupancyTypeList as $occupancyType) {?><option value="<?=$occupancyType['id'];?>"><?=$occupancyType['occupancy_name'];?></option><?php }} ?></select></td><td><select id="const_type_mstr_id'+zf+'" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" ><option value="">SELECT</option><?php if(isset($constTypeList)){ foreach ($constTypeList as $constType) { ?><option value="<?=$constType['id'];?>"><?=$constType['construction_type'];?></option><?php }} ?></select></td><td><input type="text" id="builtup_area'+zf+'" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="" style="width: 100px;" onkeypress="return isNumDot(event);" /></td><td><input type="month" id="date_from'+zf+'" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;"  /></td><td><input type="month" id="date_upto'+zf+'" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>  &nbsp; <i class="fa fa-window-close remove_floor_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#floor_dtl_append").append(appendData);
    }
    $("#floor_dtl_append").on('click', '.remove_floor_dtl', function(e) {
        $(this).closest("tr").remove();
    });
    
    let is_mobile_tower_fun = function(){
        if($("#is_mobile_tower").val()=='1'){
            $("#is_mobile_tower_hide_show").removeClass("hidden");
        }else{
            $("#is_mobile_tower_hide_show").addClass("hidden");
        }
    }
    let is_hoarding_board_fun = function(){
        if($("#is_hoarding_board").val()=='1'){
            $("#is_hoarding_board_hide_show").removeClass("hidden");
        }else{
            $("#is_hoarding_board_hide_show").addClass("hidden");
        }
    }
    let is_petrol_pump_fun = function(){
        if($("#is_petrol_pump").val()=='1'){
            $("#is_petrol_pump_hide_show").removeClass("hidden");
        }else{
            $("#is_petrol_pump_hide_show").addClass("hidden");
        }
    }

    noElectConnCkFun();
    diffAddressCkFun();
    propTypeMstrChngFun();
    is_mobile_tower_fun();
    is_hoarding_board_fun();
    is_petrol_pump_fun();

    function isEmail(emailVal) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(emailVal) ) {
            return false;
        }else{
            return true;
        }
    }

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

    function isNumComma(key) {
                var keycode = (key.which) ? key.which : key.keyCode;
                if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
                    return false;
                }else {
                    var parts = key.srcElement.value.split('.');
                    if (parts.length > 1 && keycode == 46)
                        return false;
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
    function isAlphaCheck(val){
        var alpha = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/;
        if (!val.match(alpha)) return false;
        return true;
    }
    function isNumCheck(val){
        var numbers = /^[-+]?[0-9]+$/;
        if (!val.match(numbers)) return false;
        return true;
    }
    function isAlphaNumCheck(val){
        var regex = /^[a-z0-9]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
    function isAlphaNumCommaSlashCheck(val){
        var regex = /^[a-z\d\\/,\s]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
    function isNumDotCheck(val){
        var regex = /^[1-9]\d*(((,\d{3}){1})?(\.\d{1,8})?)$/;
        if (!val.match(regex)) return false;
        return true;
    }
    function isDateFormatYYYMMDDCheck(val){
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(val) ) {
            return false;
        }else{
            return true;
        }
    }
    function isDateFormatYYYMMCheck(val){
        var regex = /^\d{4}-\d{2}$/;
        if (!regex.test(val) ) {
            return false;
        }else{
            var YYMM = val.split("-");
            if(YYMM[0]>2020) {
                return false
            } else if(YYMM[1]>12) {
                return false
            }
            return true;
        }
    }
    
    $(document).ready(function() {
        function modelDanger(msg) {
            $.niftyNoty({
                type: 'danger',
                icon : 'pli-exclamation icon-2x',
                message : msg,
                container : 'floating',
                timer : 5000
            });
        }
        $('#form_saf_property').submit(function() {
            var process = true;
            /* if ($("#prop_type_mstr_id").val()==1 || $("#prop_type_mstr_id").val()==2 || $("#prop_type_mstr_id").val()==5) {
                var isGroundFloor = false;
                $('.floor_mstr_id').each(function() {
                    if ($(this).val()==3) {
                        isGroundFloor = true;
                    }
                });
                if (isGroundFloor==false) {
                    modelDanger("Please select minimum 1 ground floor"); process = false;
                }
            } */
            if ($("#prop_type_mstr_id").val()==3) {
                var apartment_details_id = $("#apartment_details_id").val();
                if (apartment_details_id=="") {
                    $("#apartment_details_id").focus();
                    modelDanger("Please Select Apartment Name."); process = false;
                }
            }
            if ($("#prop_type_mstr_id").val()!=4) {
                var isFloorUptoDateValid = true;
                $('.date_from').each(function() {
                    var IID = this.id;
                    var ID = IID.split("date_from")[1];
                    if ($("#date_upto"+ID).val()!="") {
                        var fit_start  = $("#date_from"+ID).val()+"-01";
                        var fit_end    = $("#date_upto"+ID).val()+"-01";

                        if(new Date(fit_start) > new Date(fit_end)) {
                            isFloorUptoDateValid = false;
                        }
                    }
                });
                if (isFloorUptoDateValid==false) {
                    modelDanger("Your Floor Upto Date is Invalid !!!"); process = false;
                }
            }
            if ($("#prop_type_mstr_id").val()==4) {
                var land_occupation_date = $("#land_occupation_date").val();
                if (land_occupation_date!="") {
                    if ($("#is_mobile_tower").val()==1) {
                        if ($("#tower_installation_date").val()!="") {
                            var tower_installation_date = $("#tower_installation_date").val();
                            if (new Date(land_occupation_date) > new Date(tower_installation_date)) {
                                modelDanger("Invalid Date of Installation of Mobile Tower !!!"); process = false;
                            }
                        }
                    }
                    if ($("#is_hoarding_board").val()==1) {
                        if ($("#hoarding_installation_date").val()!="") {
                            var hoarding_installation_date = $("#hoarding_installation_date").val();
                            if (new Date(land_occupation_date) > new Date(hoarding_installation_date)) {
                                modelDanger("Invalid Date of Installation of Hoarding Board !!!"); process = false;
                            }
                        }
                    }
                }
            }
            return process;
        });
        jQuery.validator.addMethod("dateFormatYYYMMDD", function(value, element) {
            return this.optional(element) || /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/i.test(value);
        }, "Invalid format (YYYY-MM-DD)"); 

        jQuery.validator.addMethod("dateFormatYYYMM", function(value, element) {
            return this.optional(element) || /^([12]\d{3}-(0[1-9]|1[0-2]))+$/i.test(value);
        }, "Invalid format (YYYY-MM)"); 

        jQuery.validator.addMethod("alphaSpace", function(value, element) {
            return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
        }, "Letters only please (a-z, A-Z )");

        jQuery.validator.addMethod("alphaNumCommaSlash", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9- ]+$/i.test(value);
        }, "Letters only please (a-z, A-Z, 0-9, -)");

        jQuery.validator.addMethod("alphaNumhyphen", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9- ]+$/i.test(value);
        }, "Letters only please (a-z, A-Z, 0-9, -)"); 

        jQuery.validator.addMethod("numDot", function(value, element) {
            return this.optional(element) || /^\d+(?:\.\d+)+$/i.test(value);
        }, "Letters only please (0-9.)"); 

        jQuery.validator.addMethod('min_greater_zero', function (value, element) {
            return value > 0;
        }, "Please enter a value greater than 0");

        $("#form_saf_property").validate({
            rules: {
                "has_previous_holding_no": {
                    required: true,                
                },
                "previous_holding_no": {
                    required: function(element){
                        return $("#has_previous_holding_no").val()==1;
                    },                
                },
                "is_owner_changed": {
                    required: function(element){
                        return $("#has_previous_holding_no").val()==1;
                    },                
                },
                "transfer_mode_mstr_id": {
                    required: function(element){
                        return ($("#has_previous_holding_no").val()==1 && $("#is_owner_changed").val()==1);
                    },
                },
                "ward_mstr_id": {
                    required: true,                
                },
                "new_ward_mstr_id": {
                    required: true,                
                },
                "ownership_type_mstr_id": {
                    required: true,                
                },
                "prop_type_mstr_id": {
                    required: true,                
                },
                "flat_registry_date": {
                    dateFormatYYYMMDD: function(element){
                        return ($("#prop_type_mstr_id").val()==3 && $("#flat_registry_date").val()!="");
                    }
                },
                "prev_gender[]": {
                    required: function(element){
                        return ($("#has_previous_holding_no").val()==1 && $("#is_owner_changed").val()==0);
                    }
                },
                "prev_dob[]": {
                    required: function(element){
                        return ($("#has_previous_holding_no").val()==1 && $("#is_owner_changed").val()==0);
                    }
                },
                "owner_name[]": {
                    required: true,
                    alphaSpace: true,
                    minlength: 2,
                },
                "gender[]": {
                    required: true
                },
                "dob[]": {
                    required: true,
                    dateFormatYYYMMDD : true
                },
                "mobile_no[]": {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10,
                },
                "road_type_mstr_id": {
                    required: true,                
                },
                "road_type_width": {
                    required: function(element){
                        return $("#road_type_mstr_id").val()!=4;
                    },
                    digits: true,
                    minlength: 1,
                    maxlength: 2,
                    range: function(element){
                        if($("#road_type_mstr_id").val()==1){
                            return [40, 500];
                        } else if($("#road_type_mstr_id").val()==2){
                            return [20, 39];
                        } else if($("#road_type_mstr_id").val()==3){
                            return [1, 19];
                        }
                    }     
                },
                "area_of_plot": {
                    required: true,
                    number: true,
                    min_greater_zero: true,
                    maxlength: 8,
                },
                "floor_mstr_id[]": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    }
                },
                "usage_type_mstr_id[]": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    }
                },
                "occupancy_type_mstr_id[]": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    }
                },
                "const_type_mstr_id[]": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    }
                },
                "builtup_area[]": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    },
                    number: true,
                    min_greater_zero: true,
                    maxlength: 8,
                },
                "date_from[]": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    },
                    dateFormatYYYMM: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    },
                },
                "date_upto[]": {
                    dateFormatYYYMM : true
                },
                "is_mobile_tower": {
                    required: true
                },
                "tower_area": {
                    required: function(element){
                        return $("#is_mobile_tower").val()==1;
                    },
                    number: true,
                    min_greater_zero: true,
                    maxlength: 4,
                },
                "tower_installation_date": {
                    required: function(element){
                        return $("#is_mobile_tower").val()==1;
                    },
                    dateFormatYYYMMDD : true
                },
                "is_hoarding_board": {
                    required: true
                },
                "hoarding_area": {
                    required: function(element){
                        return $("#is_hoarding_board").val()==1;
                    },
                    number: true,
                    min_greater_zero: true,
                    maxlength: 4,
                },
                "hoarding_installation_date": {
                    required: function(element){
                        return $("#is_hoarding_board").val()==1;
                    },
                    dateFormatYYYMMDD : true
                },
                "is_petrol_pump": {
                    required: function(element){
                        return ($("#prop_type_mstr_id").val()!=4 && $("#prop_type_mstr_id").val()!=3);
                    }
                },
                "under_ground_area": {
                    required: function(element){
                        return ($("#prop_type_mstr_id").val()!=4 && $("#is_petrol_pump").val()==1);
                    },
                    number: true,
                    min_greater_zero: true,
                    maxlength: 4,
                },
                "petrol_pump_completion_date": {
                    required: function(element){
                        return ($("#prop_type_mstr_id").val()!=4 && $("#is_petrol_pump").val()==1);
                    },
                    dateFormatYYYMMDD : true
                },
                "is_water_harvesting": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()!=4;
                    }
                },
                "land_occupation_date": {
                    required: function(element){
                        return $("#prop_type_mstr_id").val()==4;
                    },
                    dateFormatYYYMMDD : true
                },
                "is_trust_school": {
                    required: true,
                }
            }
        });

        $('#apartment_details_id').change(function(){
            var rwh = $('option:selected', this).attr('data-option');
            if(rwh)
            {
                $('#is_water_harvesting').val(rwh);
                $('#is_water_harvesting').attr('disabled', true);
            }
            else{
                $('#is_water_harvesting').attr('disabled', false);
                $('#is_water_harvesting').val(0);
            }
            
        });
    });
    function checkSchoolTrust()
        {
            var val = false;
            $('select[name="usage_type_mstr_id[]"]').each(function(){
                
                val = val?val:($(this).val() == '43' || $(this).val() == '12');
            })

            if(val)
            {
                $('#is_trust_school_hide_show').removeClass("hidden");
            }else{
                $('#is_trust_school_hide_show').addClass("hidden");
                $('#is_trust_school').val("");
            }
        }
        checkSchoolTrust();

        

    function apartment_map(ward_id)
    {
        var old_ward_mstr_id = ward_id;
        if(old_ward_mstr_id!=""){
            try{
                $.ajax({
                    type:"POST",
                    url: "<?=base_url('CitizenSaf/getApartmentListByWard');?>",
                    dataType: "json",
                    data: {
                        "old_ward_mstr_id":old_ward_mstr_id,
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success:function(data){
                        if(data.response==true){
                            $("#apartment_details_id").html(data.data)
                        }
                        $("#loadingDiv").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loadingDiv").hide();
                    }
                });
            }catch (err) {
                alert(err.message);
            }
        }
    }

    $("#trade_license_no").on("blur",validateTradLicense);
    function validateTradLicense() {
        tradeLicenseNo = $('#trade_license_no').val();

        console.log("License No", tradeLicenseNo);

        if (tradeLicenseNo != '') {
            try {
                $.ajax({
                    type: "GET",
                    url: "<?= base_url('CitizenSaf2/validateTradLicense'); ?>" + "/" + tradeLicenseNo,
                    data: {
                        "trade_license_no": tradeLicenseNo,
                    },
                    beforeSend: function () {
                        $("#loadingDiv").show();
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        $("#loadingDiv").hide();
                        if (!data?.status) {
                            $('#trade_license_no').val("");
                        }
                        if (!data?.status && data?.message) {
                            modelInfo(data?.message, "danger");
                        }
                        else if (data?.status) {
                            modelInfo(data?.message, "success");
                        }
                        console.log("message : ", data?.message);
                        console.log("response : ", data);
                    }
                })
            } catch (error) {
                $("#loadingDiv").hide();
                alert(err.message);
            }
        }
    }

    function modelInfo(msg, type = 'info') {
        $.niftyNoty({
            type: type,
            icon: 'pli-exclamation icon-2x',
            message: msg,
            container: 'floating',
            timer: 5000
        });
    }
</script>