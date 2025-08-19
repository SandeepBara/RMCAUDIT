<script>
    if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
        window.location.href = '<?=base_url();?>/saf/searchDistributedDtl';
    }
</script>
<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Self Assessment Form</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="form_saf_property" name="form_saf_property" method="post" action="<?=base_url('saf/addUpdate');?>">
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
                    <h3 class="panel-title">Self Assessment Form</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Application No.</label>
                        <div class="col-md-3 pad-btm">
                            <?php
                            echo $session->get('form_no');
                            // if($flashToast=flashToast('saf_no_encrypted')){
                            //     $saf_no_encrypted = $flashToast['saf_no'];
                            //     $ward_mstr_id = $flashToast['ward_mstr_id'];
                            // } else if (isset($saf_no)) {
                            //     $saf_no_encrypted = $saf_no;
                            // } else {
                            //     $saf_no_encrypted = "";
                            // }
                            // if($saf_no_encrypted==""){
                            //     echo "N/A";
                            // }else{
                            //     echo $saf_no_encrypted;
                            // }
                            ?>
                            <input type="hidden" id="saf_no" name="saf_no" class="form-control" value="<?=$session->get('saf_distributed_dtl_id');;?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Does the property being assessed has any previous Holding Number? <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="has_previous_holding_no" name="has_previous_holding_no" class="form-control" onchange="hasPrevHoldingNoChngFun();">
                                <option value="">== SELECT ==</option>
                                <option value="1" <?=(isset($has_previous_holding_no))?($has_previous_holding_no=='1')?"selected":"":"";?>>YES</option>
                                <option value="0" <?=(isset($has_previous_holding_no))?($has_previous_holding_no=='0')?"selected":"":"";?>>NO</option>
                            </select>
                        </div>
                        <input type="hidden" id="hasPrevOwnerDtlCount" name="hasPrevOwnerDtlCount" value="0" />
                        <input type="hidden" id="isPrevPaymentCleared" name="isPrevPaymentCleared" value="NO" />
                        <div id="previous_holding_no_hide_show" class="hidden">
                            <label class="col-md-3">Previous Holding No. <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" id="previous_holding_no" name="previous_holding_no" class="form-control" placeholder="Previous Holding No" value="<?=(isset($previous_holding_no))?$previous_holding_no:"";?>" onkeypress="return isAlphaNum(event);" />
                                <label id="previous_holding_no_msg" for="previous_holding_no" class="control-label text-danger"></label>
                            </div>
                        </div>
                    </div>
                    <div id="has_prev_holding_dtl_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed select Yes. <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select id="is_owner_changed" name="is_owner_changed" class="form-control" onchange="isOwnerChangedChngFun();">
                                    <option value="">== SELECT ==</option>
                                    <option value="1" <?=(isset($is_owner_changed))?($is_owner_changed=='1')?"selected":"":"";?>>YES</option>
                                    <option value="0" <?=(isset($is_owner_changed))?($is_owner_changed=='0')?"selected":"":"";?>>NO</option>
                                </select>
                            </div>
                            <div id="is_owner_changed_tran_property_hide_show" class="hidden">
                                <label class="col-md-3">Mode of transfer of property from previous Holding Owner <span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <select id="transfer_mode_mstr_id" name="transfer_mode_mstr_id" class="form-control">
                                        <option value="">== SELECT ==</option>
                                        <?php
                                        if(isset($transferModeList)){
                                            foreach ($transferModeList as $transferMode) {
                                        ?>
                                        <option value="<?=$transferMode['id'];?>" <?=(isset($transfer_mode_mstr_id))?($transferMode['id']==$transfer_mode_mstr_id)?"selected":"":"";?>><?=$transferMode['transfer_mode'];?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Old Ward No <span class="text-danger">*</span></label>
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
                        <label class="col-md-3">New Ward No <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="new_ward_mstr_id" name="new_ward_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
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
                                <input type="text" id="appartment_name" name="appartment_name" class="form-control" placeholder="Appartment Name" value="<?=(isset($appartment_name))?$appartment_name:"";?>" onkeypress="return isAlphaNumCommaSlash(event);" />
                            </div>
                            <label class="col-md-3">Flat Registry Date <span class="text-danger">*</span></label>
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
                        <label class="col-md-3">Zone <span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            <select id="zone_mstr_id" name="zone_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <option value="1" <?=(isset($zone_mstr_id))?($zone_mstr_id=='1')?"selected":"":"";?>>Zone 1</option>
                                <option value="2" <?=(isset($zone_mstr_id))?($zone_mstr_id=='2')?"selected":"":"";?>>Zone 2</option>
                            </select>
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

            <div class="panel panel-bordered panel-mint hidden" id="previous_owner_dtl_hide_show">
                <div class="panel-heading">
                    <h3 class="panel-title">Previous Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Guardian Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No</th>
                                            <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="previous_owner_dtl_append">

                                    </tbody>
                                </table>
                            </div>
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
                                <?php
                                $zo = 1;
                                if(isset($owner_name)){
                                    $zo = sizeof($owner_name);
                                    for($i=0; $i < $zo; $i++){
                                ?>
                                        <tr>
                                            <td>
                                                <input type="text" id="owner_name<?=$i+1;?>" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="<?=$owner_name[$i];?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td>
                                                <input type="text" id="guardian_name<?=$i+1;?>" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="<?=$guardian_name[$i];?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td>
                                                <select id="relation_type<?=$i+1;?>" name="relation_type[]" class="form-control relation_type" style="width: 100px;" onchange="borderNormal(this.id);">
                                                    <option value="">SELECT</option>
                                                    <option value="S/O" <?=($relation_type[$i]=="S/O")?"selected":"";?>>S/O</option>
                                                    <option value="D/O" <?=($relation_type[$i]=="D/O")?"selected":"";?>>D/O</option>
                                                    <option value="W/O" <?=($relation_type[$i]=="W/O")?"selected":"";?>>W/O</option>
                                                    <option value="C/O" <?=($relation_type[$i]=="C/O")?"selected":"";?>>C/O</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="mobile_no<?=$i+1;?>" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="<?=$mobile_no[$i];?>" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                                <input type="text" id="aadhar_no<?=$i+1;?>" name="aadhar_no[]" class="form-control aadhar_no" placeholder="Aadhar No." value="<?=$aadhar_no[$i];?>" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" />
                                            </td>
                                            <td>
                                                <input type="text" id="pan_no<?=$i+1;?>" name="pan_no[]" class="form-control pan_no" placeholder="PAN No." value="<?=$pan_no[$i];?>" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                                <input type="email" id="email<?=$i+1;?>" name="email[]" class="form-control email" placeholder="Email ID" value="<?=$email[$i];?>" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td class="text-2x">
                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                                &nbsp;
                                            <?php if($i!=0){?>
                                                <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }else{
                                ?>
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
            <div class="panel panel-bordered panel-dark hidden" id="electric_dtl_hide_show">
                <div class="panel-heading">
                    <h3 class="panel-title">Electricity Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div id="no_electric_connection_hide_show" class="">
                        <div class="row">
                            <div class="col-md-12 pad-btm">
                                <div class="checkbox">
                                    <input id="no_electric_connection" name="no_electric_connection" class="magic-checkbox" type="checkbox" <?=(isset($no_electric_connection))?($no_electric_connection==1)?"checked":"":"";?> />
                                    <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
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
                        <label class="col-md-3">Area of Plot <span class="text-bold">(in Decimal)</span> <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="area_of_plot" name="area_of_plot" class="form-control" placeholder="Area of Plot" value="<?=(isset($area_of_plot))?$area_of_plot:"";?>" onkeypress="return isNumDot(event);" />
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
                                                <th>From Date (YYYY-MM) <span class="text-danger">*</span></th>
                                                <th>Upto Date (YYYY-MM) <span class="text-xs">(Leave blank for current date)</span></th>
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
                                                    <select id="floor_mstr_id<?=$i+1;?>" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);">
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
                                                    <select id="usage_type_mstr_id<?=$i+1;?>" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" style="width: 200px;" onchange="borderNormal(this.id);">
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
                                                    <select id="occupancy_type_mstr_id<?=$i+1;?>" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);">
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
                                                    <select id="const_type_mstr_id<?=$i+1;?>" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);">
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
                                                    <input type="text" id="builtup_area<?=$i+1;?>" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="<?=$builtup_area[$i];?>" style="width: 100px;" onkeypress="return isNumDot(event);" onkeyup="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_from<?=$i+1;?>" name="date_from[]" class="form-control date_from" value="<?=$date_from[$i];?>" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m-d");?>" onchange="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_upto<?=$i+1;?>" name="date_upto[]" class="form-control date_upto" value="<?=$date_upto[$i];?>" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m-d");?>" onchange="borderNormal(this.id);" />
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
                                                    <select id="floor_mstr_id1" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);">
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
                                                    <select id="usage_type_mstr_id1" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" style="width: 200px;" onchange="borderNormal(this.id);">
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
                                                    <select id="occupancy_type_mstr_id1" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);">
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
                                                    <select id="const_type_mstr_id1" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);">
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
                                                    <input type="text" id="builtup_area1" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="" style="width: 100px;" onkeypress="return isNumDot(event);" onkeyup="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_from1" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m-d");?>" onchange="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="month" id="date_upto1" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;" max="<?=date("Y-m-d");?>" onchange="borderNormal(this.id);" />
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
                                <option value="0" <?=(isset($is_mobile_tower))?($is_mobile_tower=="0")?"selected":"":"";?>>NO</option>
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
                                <option value="0" <?=(isset($is_hoarding_board))?($is_hoarding_board=="0")?"selected":"":"";?>>NO</option>
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
                                    <option value="0" <?=(isset($is_petrol_pump))?($is_petrol_pump=="0")?"selected":"":"";?>>NO</option>
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
                                    <option value="0" <?=(isset($is_water_harvesting))?($is_water_harvesting=="0")?"selected":"":"";?>>NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="vacant_land_occupation_date_hide_show">
                        <div class="row">
                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier) <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="date" id="land_occupation_date" name="land_occupation_date" class="form-control" value="<?=(isset($land_occupation_date))?$land_occupation_date:"";?>" />
                            </div>
                        </div>
                        <hr />
                    </div>

                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
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
    function hasPrevHoldingNoChngFun(){
        var has_previous_holding_no = $("#has_previous_holding_no").val();
        var is_owner_changed = $("#is_owner_changed").val();
        var hasPrevOwnerDtlCount = $("#hasPrevOwnerDtlCount").val();
        if(has_previous_holding_no==1){
            $("#has_prev_holding_dtl_hide_show").removeClass("hidden");
            $("#previous_holding_no_hide_show").removeClass("hidden");
        }else{
            $("#has_prev_holding_dtl_hide_show").addClass("hidden");
            $("#previous_holding_no_hide_show").addClass("hidden");
        }
        if (has_previous_holding_no==1 && hasPrevOwnerDtlCount!=0){
            $("#previous_owner_dtl_hide_show").removeClass("hidden");
        }else{
            $("#previous_owner_dtl_hide_show").addClass("hidden");
        }
        if (has_previous_holding_no==1 && is_owner_changed==0) {
            $("#owner_dtl_hide_show").addClass("hidden");
        }else{
            $("#owner_dtl_hide_show").removeClass("hidden");
        }
    }
    function isOwnerChangedChngFun(){
        var has_previous_holding_no = $("#has_previous_holding_no").val();
        var is_owner_changed = $("#is_owner_changed").val();
        if(is_owner_changed==1){
            $("#is_owner_changed_tran_property_hide_show").removeClass("hidden");
        }else{
            $("#is_owner_changed_tran_property_hide_show").addClass("hidden");
        }
        if (has_previous_holding_no==1 && is_owner_changed==0) {
            $("#owner_dtl_hide_show").addClass("hidden");
        }else{
            $("#owner_dtl_hide_show").removeClass("hidden");
        }
    }

    $("#ward_mstr_id").change(function() {
        var old_ward_mstr_id = $("#ward_mstr_id").val();
        if(old_ward_mstr_id!=""){
            try{
                $.ajax({
                    type:"POST",
                    url: "<?=base_url('saf/getNewWardDtlByOldWard');?>",
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
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            }catch (err) {
                alert(err.message);
            }
        }
    });
    

    $("#previous_holding_no").blur(function(){
        var has_previous_holding_no = $("#has_previous_holding_no").val();
        var is_owner_changed = $("#is_owner_changed").val();
        var previous_holding_no = $("#previous_holding_no").val();
        if(previous_holding_no!=""){
            try{
                $.ajax({
                    type:"POST",
                    url: "<?=base_url('saf/getPrevHoldingDtl');?>",
                    dataType: "json",
                    data: {
                            "has_previous_holding_no":has_previous_holding_no,
                            "is_owner_changed":is_owner_changed,
                            "previous_holding_no":previous_holding_no
                        },
                    beforeSend: function() {
                        $("#previous_holding_no_msg").html("");
                        $("#hasPrevOwnerDtlCount").val(0);
                        $("#previous_owner_dtl_hide_show").addClass("hidden");
                        $("#previous_owner_dtl_append").html("");
                        $("#loadingDiv").show();
                    },
                    success:function(data){
                        if(data.response==true){
                            var owner_dtl = data.data;
                            var hasPrevOwnerDtlCount = 0;
                            var tbody = "";
                            for ( var i = 0; i < owner_dtl.length; i++ ) {
                                tbody += '<tr>';
                                    tbody += '<th>';
                                        tbody += owner_dtl[i]['owner_name'];
                                        tbody += '<input type="hidden" id="prev_owner_name'+i+1+'" name="prev_owner_name[]" class="prev_owner_name" value="'+owner_dtl[i]['owner_name']+'" />';
                                    tbody += '</th>';

                                    tbody += '<th>';
                                        tbody += owner_dtl[i]['guardian_name'];
                                        tbody += '<input type="hidden" id="prev_guardian_name'+i+1+'" name="prev_guardian_name[]" class="prev_guardian_name" value="'+owner_dtl[i]['guardian_name']+'" />';
                                    tbody += '</th>';

                                    tbody += '<th>';
                                        tbody += owner_dtl[i]['relation_type'];
                                        tbody += '<input type="hidden" id="prev_relation_type'+i+1+'" name="prev_relation_type[]" class="prev_relation_type" value="'+owner_dtl[i]['relation_type']+'" />';
                                    tbody += '</th>';

                                    tbody += '<th>';
                                        tbody += owner_dtl[i]['mobile_no'];
                                        tbody += '<input type="hidden" id="prev_mobile_no'+i+1+'" name="prev_mobile_no[]" class="prev_mobile_no" value="'+owner_dtl[i]['mobile_no']+'" />';
                                    tbody += '</th>';

                                    tbody += '<th>';
                                        var aadhar_no = "";
                                        if (owner_dtl[i]['aadhar_no']!=null) {
                                            aadhar_no = owner_dtl[i]['aadhar_no'];
                                        }
                                        tbody += aadhar_no;
                                        tbody += '<input type="hidden" id="prev_aadhar_no'+i+1+'" name="prev_aadhar_no[]" class="prev_aadhar_no" value="'+aadhar_no+'" />';
                                    tbody += '</th>';

                                    tbody += '<th>';
                                        tbody += owner_dtl[i]['pan_no'];
                                        tbody += '<input type="hidden" id="prev_pan_no'+i+1+'" name="prev_pan_no[]" class="prev_pan_no" value="'+owner_dtl[i]['pan_no']+'" />';
                                    tbody += '</th>';

                                    tbody += '<th>';
                                        tbody += owner_dtl[i]['email'];
                                        tbody += '<input type="hidden" id="prev_email'+i+1+'" name="prev_email[]" class="prev_email" value="'+owner_dtl[i]['email']+'" />';
                                    tbody += '</th>';
                                tbody += '</tr>';
                                hasPrevOwnerDtlCount = i+1;
                            }
                            $("#previous_owner_dtl_append").html(tbody);
                            if (has_previous_holding_no==1) {
                                $("#previous_owner_dtl_hide_show").removeClass("hidden");
                            }
                            if (has_previous_holding_no==1 && is_owner_changed==0) {
                                $("#owner_dtl_hide_show").addClass("hidden");
                            }else{
                                $("#owner_dtl_hide_show").removeClass("hidden");
                                $("#isPrevPaymentCleared").val(data.payment_dtl);
                            }

                            $("#hasPrevOwnerDtlCount").val(hasPrevOwnerDtlCount);
                        }else{
                            $("#previous_holding_no_msg").html("holding no. does not exist !!!");
                        }
                        $("#loadingDiv").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loadingDiv").hide();
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            }catch (err) {
                alert(err.message);
            }
        } else {
            $("#previous_holding_no_msg").html("");
            $("#hasPrevOwnerDtlCount").val(0);
            $("#isPrevPaymentCleared").val("NO");
            $("#previous_owner_dtl_hide_show").addClass("hidden");
            $("#previous_owner_dtl_append").html("");

        }
    });

    function propTypeMstrChngFun(){
        var prop_type_mstr_id = $("#prop_type_mstr_id").val();
        if(prop_type_mstr_id==""){
            $("#floor_dtl_hide_show").addClass("hidden");
        }else{
            if (prop_type_mstr_id==2) {
                $("#electric_dtl_hide_show").removeClass("hidden");
            } else {
                $("#electric_dtl_hide_show").addClass("hidden");
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
            }else{
                $("#floor_dtl_hide_show").removeClass("hidden");
                $("#petrol_pump_dtl_hide_show").removeClass("hidden");
                $("#is_water_harvesting_hide_show").removeClass("hidden");
                $("#vacant_land_occupation_date_hide_show").addClass("hidden");
            }
        }
    }
    var zo = <?=$zo;?>;
    function owner_dtl_append_fun(){
        zo++;
        var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><select id="relation_type'+zo+'" name="relation_type[]" class="form-control relation_type" style="width: 100px;"><option value="">SELECT</option><option value="S/O">S/O</option><option value="D/O">D/O</option><option value="W/O">W/O</option><option value="C/O">C/O</option></select></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="aadhar_no'+zo+'" name="aadhar_no[]" class="form-control aadhar_no" placeholder="Aadhar No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="pan_no'+zo+'" name="pan_no[]" class="form-control pan_no" placeholder="PAN No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="email" id="email'+zo+'" name="email[]" class="form-control email" placeholder="Email ID" value="" onkeyup="borderNormal(this.id);" /></td></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#owner_dtl_append").append(appendData);
    }
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
    });
    var zf=<?=$zf;?>;
    function floor_dtl_append_fun(){
        zf++;
        var appendData = '<tr><td><select id="floor_mstr_id'+zf+'" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($floorList)){ foreach ($floorList as $floor) { ?><option value="<?=$floor['id'];?>"><?=$floor['floor_name'];?></option><?php }} ?></select></td><td><select id="usage_type_mstr_id'+zf+'" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($usageTypeList)){ foreach ($usageTypeList as $usageType) { ?><option value="<?=$usageType['id'];?>"><?=$usageType['usage_type'];?></option><?php }} ?></select></td><td><select id="occupancy_type_mstr_id'+zf+'" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($occupancyTypeList)){ foreach ($occupancyTypeList as $occupancyType) {?><option value="<?=$occupancyType['id'];?>"><?=$occupancyType['occupancy_name'];?></option><?php }} ?></select></td><td><select id="const_type_mstr_id'+zf+'" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($constTypeList)){ foreach ($constTypeList as $constType) { ?><option value="<?=$constType['id'];?>"><?=$constType['construction_type'];?></option><?php }} ?></select></td><td><input type="text" id="builtup_area'+zf+'" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="" style="width: 100px;" onkeypress="return isNumDot(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="month" id="date_from'+zf+'" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;" onchange="borderNormal(this.id);" /></td><td><input type="month" id="date_upto'+zf+'" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;" onchange="borderNormal(this.id);" /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>  &nbsp; <i class="fa fa-window-close remove_floor_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#floor_dtl_append").append(appendData);
    }
    $("#floor_dtl_append").on('click', '.remove_floor_dtl', function(e) {
        $(this).closest("tr").remove();
    });
    function is_mobile_tower_fun(){
        if($("#is_mobile_tower").val()=='1'){
            $("#is_mobile_tower_hide_show").removeClass("hidden");
        }else{
            $("#is_mobile_tower_hide_show").addClass("hidden");
        }
    }
    function is_hoarding_board_fun(){
        if($("#is_hoarding_board").val()=='1'){
            $("#is_hoarding_board_hide_show").removeClass("hidden");
        }else{
            $("#is_hoarding_board_hide_show").addClass("hidden");
        }
    }
    function is_petrol_pump_fun(){
        if($("#is_petrol_pump").val()=='1'){
            $("#is_petrol_pump_hide_show").removeClass("hidden");
        }else{
            $("#is_petrol_pump_hide_show").addClass("hidden");
        }
    }

    propTypeMstrChngFun();
    is_mobile_tower_fun();
    is_hoarding_board_fun();
    is_petrol_pump_fun();
    hasPrevHoldingNoChngFun();
    isOwnerChangedChngFun();
    $("#previous_holding_no").trigger("keyup");

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
    $("#btn_review").click(function(){ 
        var process = true;

        var has_previous_holding_no = $("#has_previous_holding_no").val();
        var previous_holding_no = $("#previous_holding_no").val();
        var hasPrevOwnerDtlCount = $("#hasPrevOwnerDtlCount").val();
        var isPrevPaymentCleared = $("#isPrevPaymentCleared").val();

        var is_owner_changed = $("#is_owner_changed").val();
        var transfer_mode_mstr_id = $("#transfer_mode_mstr_id").val();

        var ward_mstr_id = $("#ward_mstr_id").val();
        var new_ward_mstr_id = $("#new_ward_mstr_id").val();
        var ownership_type_mstr_id = $("#ownership_type_mstr_id").val();
        var prop_type_mstr_id = $("#prop_type_mstr_id").val();
        var road_type_mstr_id = $("#road_type_mstr_id").val();

        var appartment_name = $("#appartment_name").val();
        var flat_registry_date = $("#flat_registry_date").val();

        var zone_mstr_id = $("#zone_mstr_id").val();

        var area_of_plot = $("#area_of_plot").val();
        var prop_address = $("#prop_address").val();

        var prop_city = $("#prop_city").val();
        var prop_dist = $("#prop_dist").val();
        var prop_pin_code = $("#prop_pin_code").val();


        var is_mobile_tower = $("#is_mobile_tower").val();
        var tower_area = $("#tower_area").val();
        var tower_installation_date = $("#tower_installation_date").val();

        var is_hoarding_board = $("#is_hoarding_board").val();
        var hoarding_area = $("#hoarding_area").val();
        var hoarding_installation_date = $("#hoarding_installation_date").val();

        var is_petrol_pump = $("#is_petrol_pump").val();
        var under_ground_area = $("#under_ground_area").val();
        var petrol_pump_completion_date = $("#petrol_pump_completion_date").val();

        var is_water_harvesting = $("#is_water_harvesting").val();

        var land_occupation_date = $("#land_occupation_date").val();

        var todayDate = new Date();

        if(has_previous_holding_no==""){
            $("#has_previous_holding_no").css('border-color', 'red'); process = false;
        }else{
            if(has_previous_holding_no==1){
                if(previous_holding_no.length < 4){
                    $("#previous_holding_no").css('border-color', 'red'); process = false;
                }
                if(is_owner_changed==""){
                    $("#is_owner_changed").css('border-color', 'red'); process = false;
                }else{
                    if(is_owner_changed==1){
                        if(transfer_mode_mstr_id==""){
                            $("#transfer_mode_mstr_id").css('border-color', 'red'); process = false;
                        }
                    }
                }
            }
        }

        if(ward_mstr_id==""){
            $("#ward_mstr_id").css('border-color', 'red'); process = false;
        }
        if(new_ward_mstr_id==""){
            $("#new_ward_mstr_id").css('border-color', 'red'); process = false;
        }

        if(ownership_type_mstr_id==""){
            $("#ownership_type_mstr_id").css('border-color', 'red'); process = false;
        }

        if(prop_type_mstr_id==""){
            $("#prop_type_mstr_id").css('border-color', 'red'); process = false;
        }else{
            if(prop_type_mstr_id==3){
                if(appartment_name.length < 3){
                    $("#appartment_name").css('border-color', 'red'); process = false;
                }
                if(flat_registry_date==""){
                    $("#flat_registry_date").css('border-color', 'red'); process = false;
                }else{
                    var comDate = new Date(flat_registry_date);
                    if(comDate.getTime() > todayDate.getTime()){
                        $("#flat_registry_date").css('border-color', 'red'); process = false;
                    }
                }
            }
        }

        if(road_type_mstr_id==""){
            $("#road_type_mstr_id").css('border-color', 'red'); process = false;
        }

        if(zone_mstr_id==""){
            $("#zone_mstr_id").css('border-color', 'red'); process = false;
        }

        if ((has_previous_holding_no==1) ) {
            if (hasPrevOwnerDtlCount==0) {
                if (previous_holding_no!="") {
                    modelInfo("holding no "+previous_holding_no+" does't exist !!!"); process = false;
                }
            }else{
                if ( is_owner_changed==1 && isPrevPaymentCleared=="NO" ) {
                    modelInfo("holding no : "+previous_holding_no+", please clear tax. !!!"); process = false;
                }
            }
        }
        if (has_previous_holding_no==0 || (has_previous_holding_no==1 && is_owner_changed==1) ) {
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
        }

        if (area_of_plot=="") {
            $("#area_of_plot").css('border-color', 'red'); process = false;
        }

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

        if (prop_type_mstr_id!=4 ) {
            var isGroundFloor = false;
            var isGroundFloorIncrease = false;
            $(".floor_mstr_id").each(function() {
                var ID = this.id.split('floor_mstr_id')[1];
                var floor_mstr_id = $("#floor_mstr_id"+ID).val();
                var usage_type_mstr_id = $("#usage_type_mstr_id"+ID).val();
                var occupancy_type_mstr_id = $("#occupancy_type_mstr_id"+ID).val();
                var const_type_mstr_id = $("#const_type_mstr_id"+ID).val();
                var builtup_area = $("#builtup_area"+ID).val();
                var date_from = $("#date_from"+ID).val();
                var date_upto = $("#date_upto"+ID).val();

                if (floor_mstr_id=="") {
                    $("#floor_mstr_id"+ID).css('border-color', 'red'); process = false;
                }
                if (usage_type_mstr_id=="") {
                    $("#usage_type_mstr_id"+ID).css('border-color', 'red'); process = false;
                }
                if (occupancy_type_mstr_id=="") {
                    $("#occupancy_type_mstr_id"+ID).css('border-color', 'red'); process = false;
                }
                if (const_type_mstr_id=="") {
                    $("#const_type_mstr_id"+ID).css('border-color', 'red'); process = false;
                }
                if (builtup_area=="" || builtup_area < 1) {
                    $("#builtup_area"+ID).css('border-color', 'red'); process = false;
                }
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

            });
        }

        if (is_mobile_tower=="") {
            $("#is_mobile_tower").css('border-color', 'red'); process = false;
        } else {
            if (is_mobile_tower==1) {
                if (tower_area == "" && tower_area < 1) {
                    $("#tower_area").css('border-color', 'red'); process = false;
                }
                if ( tower_installation_date=="" || !isDateFormatYYYMMDD(tower_installation_date) ){
                    $("#tower_installation_date").css('border-color', 'red'); process = false;
                }

            }
        }
        if (is_hoarding_board=="") {
            $("#is_hoarding_board").css('border-color', 'red'); process = false;
        } else {
            if (is_hoarding_board==1) {
                if (hoarding_area == "" && hoarding_area < 1) {
                    $("#hoarding_area").css('border-color', 'red'); process = false;
                }
                if ( hoarding_installation_date=="" || !isDateFormatYYYMMDD(hoarding_installation_date) ){
                    $("#hoarding_installation_date").css('border-color', 'red'); process = false;
                }

            }
        }

        if ( prop_type_mstr_id==4 ) {
            if ( land_occupation_date=="" || !isDateFormatYYYMMDD(land_occupation_date) ){
                $("#land_occupation_date").css('border-color', 'red'); process = false;
            }
        } else {
            if (is_petrol_pump=="") {
                $("#is_petrol_pump").css('border-color', 'red'); process = false;
            } else {
                if (is_petrol_pump==1) {
                    if (under_ground_area == "" && under_ground_area < 1) {
                        $("#under_ground_area").css('border-color', 'red'); process = false;
                    }
                    if ( petrol_pump_completion_date=="" || !isDateFormatYYYMMDD(petrol_pump_completion_date) ){
                        $("#petrol_pump_completion_date").css('border-color', 'red'); process = false;
                    }

                }
            }
            if ( is_water_harvesting=="" ) {
                $("#is_water_harvesting").css('border-color', 'red'); process = false;
            }
        }

        return process;
    });

    function borderNormal(ID) {
        $("#"+ID).css('border-color', '');
    }
    $("#has_previous_holding_no").change(function(){ $(this).css('border-color', ''); });
    $("#previous_holding_no").keyup(function(){ $(this).css('border-color', ''); });
    $("#is_owner_changed").change(function(){ $(this).css('border-color', ''); });
    $("#transfer_mode_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#ward_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#new_ward_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#ownership_type_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#prop_type_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#appartment_name").keyup(function(){ $(this).css('border-color', ''); });
    $("#flat_registry_date").change(function(){ $(this).css('border-color', ''); });
    $("#road_type_mstr_id").change(function(){ $(this).css('border-color', ''); });
    $("#zone_mstr_id").change(function(){ $(this).css('border-color', ''); });

    $("#building_plan_approval_date").keyup(function(){ $(this).css('border-color', ''); });
    $("#building_plan_approval_date").change(function(){ $(this).css('border-color', ''); });
    $("#water_conn_date").keyup(function(){ $(this).css('border-color', ''); });
    $("#water_conn_date").change(function(){ $(this).css('border-color', ''); });

    $("#khata_no").keyup(function(){ $(this).css('border-color', ''); });
    $("#plot_no").keyup(function(){ $(this).css('border-color', ''); });
    $("#village_mauja_name").keyup(function(){ $(this).css('border-color', ''); });
    $("#area_of_plot").keyup(function(){ $(this).css('border-color', ''); });

    $("#prop_address").keyup(function(){ $(this).css('border-color', ''); });
    $("#prop_city").keyup(function(){ $(this).css('border-color', ''); });
    $("#prop_dist").keyup(function(){ $(this).css('border-color', ''); });
    $("#prop_pin_code").keyup(function(){ $(this).css('border-color', ''); });

    $("#corr_address").keyup(function(){ $(this).css('border-color', ''); });
    $("#corr_city").keyup(function(){ $(this).css('border-color', ''); });
    $("#corr_dist").keyup(function(){ $(this).css('border-color', ''); });
    $("#corr_pin_code").keyup(function(){ $(this).css('border-color', ''); });

    $("#is_mobile_tower").change(function(){ $(this).css('border-color', ''); });
    $("#tower_area").keyup(function(){ $(this).css('border-color', ''); });
    $("#tower_installation_date").change(function(){ $(this).css('border-color', ''); });
    $("#tower_installation_date").keyup(function(){ $(this).css('border-color', ''); });

    $("#is_mobile_tower").change(function(){ $(this).css('border-color', ''); });
    $("#tower_area").keyup(function(){ $(this).css('border-color', ''); });
    $("#tower_installation_date").change(function(){ $(this).css('border-color', ''); });
    $("#tower_installation_date").keyup(function(){ $(this).css('border-color', ''); });

    $("#is_hoarding_board").change(function(){ $(this).css('border-color', ''); });
    $("#hoarding_area").keyup(function(){ $(this).css('border-color', ''); });
    $("#hoarding_installation_date").change(function(){ $(this).css('border-color', ''); });
    $("#hoarding_installation_date").keyup(function(){ $(this).css('border-color', ''); });

    $("#is_petrol_pump").change(function(){ $(this).css('border-color', ''); });
    $("#under_ground_area").keyup(function(){ $(this).css('border-color', ''); });
    $("#petrol_pump_completion_date").change(function(){ $(this).css('border-color', ''); });
    $("#petrol_pump_completion_date").keyup(function(){ $(this).css('border-color', ''); });

    $("#is_water_harvesting").change(function(){ $(this).css('border-color', ''); });

    $("#land_occupation_date").change(function(){ $(this).css('border-color', ''); });
    $("#land_occupation_date").keyup(function(){ $(this).css('border-color', ''); });


// for selected New Ward in edit mode 
<?php
    if(isset($ward_mstr_id))
    {
        ?>
        $.ajax({
            type:"POST",
            url: "<?=base_url('saf/getNewWardDtlByOldWard');?>",
            dataType: "json",
            data: {
                "old_ward_mstr_id":<?=$ward_mstr_id;?>,
            },
            beforeSend: function() {
                $("#loadingDiv").show();
            },
            success:function(data){
                if(data.response==true){
                    $("#new_ward_mstr_id").html(data.data);
                    $("#new_ward_mstr_id").val(<?=$new_ward_mstr_id;?>);
                }
                $("#loadingDiv").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#loadingDiv").hide();
                alert(JSON.stringify(jqXHR));
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
        <?php
    }
    ?>
</script>
