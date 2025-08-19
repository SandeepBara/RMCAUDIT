<?=$this->include('layout_vertical/header');?>
<style>
.error {
    color: red;
}
</style>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF</a></li>
            <li><a href="<?=base_url();?>/safdtl/full/<?=md5($id);?>">SAF Details</a></li>
            <li class="active">Update Application</li>
        </ol>
        
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="backOfficeUpdate" name="backOfficeUpdate" method="post" action="<?=base_url();?>/SAF/backOfficeSAFUpdate/<?=md5($id);?>">
            <div class="row">
                <div class="col-md-12">					
                    <!--Default Tabs (Right Aligned)-->
                    <div class="tab-base">
                        <!--Nav tabs-->
                        <ul class="nav nav-tabs tabs-left">
                            <li class="active">
                                <a data-toggle="tab" href="#rgt-tab-1" aria-expanded="true"><i class="demo-psi-pen-5"></i></a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#rgt-tab-2" aria-expanded="false"><i class="demo-psi-idea-2"></i></a>
                            </li>
                        </ul>
                        <!--Tabs Content-->
                        <div class="tab-content">
                            <div id="rgt-tab-1" class="tab-pane fade active in">
                                <input type="hidden" id="is_transaction" name="is_transaction" value="<?=(isset($is_transaction) && $is_transaction==true)?1:0;?>" />
                                
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
                                                                <th>Relation <span class="text-danger">*</span></th>
                                                                <th>Guardian Name <span class="text-danger">*</span></th>
                                                                
                                                                <th>Mobile No <span class="text-danger">*</span></th>
                                                                <th>Aadhar No. <span class="text-danger">*</span></th>
                                                                <th>PAN No.</th>
                                                                <th>Email ID</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="owner_dtl_append">
                                                    <?php
                                                    $zo = 1;
                                                    if(isset($owner_dtl_list)){
                                                        $zo = sizeof($owner_dtl_list);
                                                        $i = 0;
                                                        foreach ($owner_dtl_list AS $key=> $owner_dtl) {
                                                            $i++;
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                <input type="hidden" id="saf_owner_detail_id<?=$i;?>" name="saf_owner_detail_id[]" class="form-control saf_owner_detail_id" placeholder="Owner Name" value="<?=$owner_dtl['id'];?>" />
                                                                    <input type="text" id="owner_name<?=$i;?>" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="<?=$owner_dtl['owner_name'];?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                                                </td>
                                                                <td>
                                                                    <select id="relation_type<?=$i;?>" name="relation_type[]" class="form-control relation_type" style="width: 100px;" onchange="borderNormal(this.id);">
                                                                        <option value="">SELECT</option>
                                                                        <option value="S/O" <?=($owner_dtl['relation_type']=="S/O")?"selected":"";?>>S/O</option>
                                                                        <option value="D/O" <?=($owner_dtl['relation_type']=="D/O")?"selected":"";?>>D/O</option>
                                                                        <option value="W/O" <?=($owner_dtl['relation_type']=="W/O")?"selected":"";?>>W/O</option>
                                                                        <option value="C/O" <?=($owner_dtl['relation_type']=="C/O")?"selected":"";?>>C/O</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="guardian_name<?=$i;?>" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="<?=$owner_dtl['guardian_name'];?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                                                </td>
                                                                
                                                                <td>
                                                                    <input type="text" id="mobile_no<?=$i;?>" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="<?=$owner_dtl['mobile_no'];?>" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="aadhar_no<?=$i;?>" name="aadhar_no[]" class="form-control aadhar_no" placeholder="Aadhar No." value="<?=$owner_dtl['aadhar_no'];?>" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" />
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="pan_no<?=$i;?>" name="pan_no[]" class="form-control pan_no" placeholder="PAN No." value="<?=$owner_dtl['pan_no'];?>" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                                                </td>
                                                                <td>
                                                                    <input type="email" id="email<?=$i;?>" name="email[]" class="form-control email" placeholder="Email ID" value="<?=$owner_dtl['email'];?>" onkeyup="borderNormal(this.id);" />
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
                                   
                                <div class="panel panel-bordered panel-dark <?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id==4))?"hidden":"":"";?>">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Electricity Details</h3>
                                    </div>
                                    <div class="panel-body" style="padding-bottom: 0px;">
                                        <div class="row">
                                            <label class="col-md-10">
                                                <div class="checkbox">
                                                    <?php
                                                    if(isset($no_electric_connection) && $no_electric_connection=='t'){
                                                        echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                                    }else{
                                                        echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                                                    }
                                                    ?>
                                                    <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You will have to upload Affidavit Form-I. (Please Tick)</label>
                                                    
                                                </div>
                                            </label>
                                        </div>
                                        <div id="electric_dtl_hide_show" class="">
                                            <div class="row">
                                                <label class="col-md-3">Electricity K. No <?=(isset($no_electric_connection) && $no_electric_connection=='t')?"<span class='text-danger'>*</span>":"";?></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="elect_consumer_no" name="elect_consumer_no" class="form-control" placeholder="Electricity K. No" value="<?=(isset($elect_consumer_no))?$elect_consumer_no:"";?>" onkeypress="return isAlphaNumCommaSlash(event);" onchange="Electricity('1')"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-3">ACC No. <?=(isset($no_electric_connection) && $no_electric_connection=='t')?"<span class='text-danger'>*</span>":"";?></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="elect_acc_no" name="elect_acc_no" class="form-control" placeholder="ACC No." value="<?=(isset($elect_acc_no))?$elect_acc_no:"";?>" onkeypress="return isAlphaNum(event);" onchange="Electricity('2')"/>
                                                </div>
                                                <label class="col-md-3">BIND/BOOK No. <?=(isset($no_electric_connection) && $no_electric_connection=='t')?"<span class='text-danger'>*</span>":"";?></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="elect_bind_book_no" name="elect_bind_book_no" class="form-control" placeholder="BIND/BOOK No." value="<?=(isset($elect_bind_book_no))?$elect_bind_book_no:"";?>" onkeypress="return isAlphaNum(event);" onchange="Electricity('2')"/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-3">Electricity Consumer Category <?=(isset($no_electric_connection) && $no_electric_connection=='t')?"<span class='text-danger'>*</span>":"";?></label>
                                                <div class="col-md-3 pad-btm">
                                                    <select id="elect_cons_category" name="elect_cons_category" class="form-control" onchange="Electricity('2')">
                                                        <option value="">== SELECT ==</option>
                                                        <option value="DS I/II/III" <?=(isset($elect_cons_category))?($elect_cons_category=='DS I/II/III')?"selected":"":"";?>>DS I/II/III</option>
                                                        <option value="NDS II/III" <?=(isset($elect_cons_category))?($elect_cons_category=='NDS II/III')?"selected":"":"";?>>NDS II/III</option>
                                                        <option value="IS I/II" <?=(isset($elec_cons_category))?($elect_cons_category=='IS I/II')?"selected":"":"";?>>IS I/II</option>
                                                        <option value="LTS" <?=(isset($elect_cons_category))?($elect_cons_category=='LTS')?"selected":"":"";?>>LTS</option>
                                                        <option value="HTS" <?=(isset($elect_cons_category))?($elect_cons_category=='HTS')?"selected":"":"";?>>HTS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    </div>
                                </div>
                                
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Property Details</h3>
                                    </div>
                                    <div class="panel-body" style="padding-bottom: 0px;">
                                        <div class="row">
                                            <label class="col-md-3">Khata No. <span class="text-danger">*</span></label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="text" id="khata_no" name="khata_no" class="form-control" placeholder="Khata No." value="<?=(isset($khata_no))?$khata_no:"";?>" onkeypress="return isAlphaNum(event);" />
                                            </div>
                                            <label class="col-md-3">Plot No. <span class="text-danger">*</span></label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="text" id="plot_no" name="plot_no" class="form-control" placeholder="Plot No." value="<?=(isset($plot_no))?$plot_no:"";?>" onkeypress="return isAlphaNum(event);" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-3">Village/Mauja Name <span class="text-danger">*</span></label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="text" id="village_mauja_name" name="village_mauja_name" class="form-control" placeholder="Village/Mauja Name" value="<?=(isset($village_mauja_name))?$village_mauja_name:"";?>" onkeypress="return isAlphaNum(event);" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Correspondence Address</h3>
                                    </div>
                                    <div class="panel-body" style="padding-bottom: 0px;">
                                        <div class="row">
                                            <label class="col-md-5 pad-btm">
                                                <div class="checkbox">
                                                    <input id="is_corr_add_differ" name="is_corr_add_differ" class="magic-checkbox" type="checkbox" onclick="diffAddressCkFun();" <?=(isset($is_corr_add_differ))?($is_corr_add_differ=='t')?"checked":"":"";?> value="1" >
                                                    <label for="is_corr_add_differ">If Corresponding Address Different from Property Address</label>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="<?=($is_corr_add_differ=='f')?"hidden":"";?>" id="corr_address_hide_show">
                                            <div class="row">
                                                <label class="col-md-3">Correspondence Address <span class="text-danger">*</span></label>
                                                <div class="col-md-7 pad-btm">
                                                    <textarea id="corr_address" name="corr_address" class="form-control" placeholder="Property Address" onkeypress="return isAlphaNumCommaSlash(event);"><?=(isset($corr_address))?$corr_address:"";?></textarea>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <label class="col-md-3">City <span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="corr_city" name="corr_city" class="form-control" placeholder="City" value="<?=(isset($corr_city))?$corr_city:"";?>" onkeypress="return isAlpha(event);" />
                                                </div>
                                                <label class="col-md-3">District <span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="corr_dist" name="corr_dist" class="form-control" placeholder="District" value="<?=(isset($corr_dist))?$corr_dist:"";?>" onkeypress="return isAlpha(event);" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-3">State <span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="corr_state" name="corr_state" class="form-control" placeholder="State" value="<?=(isset($corr_state))?$corr_state:"";?>" onkeypress="return isAlpha(event);" />
                                                </div>
                                                <label class="col-md-3">Pin <span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="corr_pin_code" name="corr_pin_code" class="form-control" placeholder="Pin" value="<?=(isset($corr_pin_code))?$corr_pin_code:"";?>" onkeypress="return isNum(event);" maxlength="6" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="rgt-tab-2" class="tab-pane fade">
                                








                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Self Assessment</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <label class="col-md-3">Application No.</label>
                                            <div class="col-md-3 pad-btm">
                                            <?php
                                            
                                            if($flashToast=flashToast('saf_no_encrypted')){
                                                $saf_no_encrypted = $flashToast;
                                            } else if (isset($saf_no)) {
                                                $saf_no_encrypted = $saf_no;
                                            } else {
                                                $saf_no_encrypted = "";
                                            }
                                            if($saf_no_encrypted==""){
                                                echo "N/A";
                                            }else{
                                                echo $saf_no_encrypted;
                                            }
                                            ?>
                                                
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-3">Does the property being assessed has any previous Holding Number? </label>
                                            <div class="col-md-3 pad-btm">
                                                    <?=(isset($has_previous_holding_no))?($has_previous_holding_no=='t')?"YES":"NO":"NO";?>
                                            </div>
                                            <div id="previous_holding_no_hide_show" class="hidden">
                                                <label class="col-md-3">Previous Holding No. </label>
                                                <div class="col-md-3 pad-btm">
                                                    <?=(isset($previous_holding_no))?$previous_holding_no:"";?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="has_prev_holding_dtl_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no=='f')?"hidden":"":"hidden";?>">
                                            <div class="row">
                                                <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No. </label>
                                                <div class="col-md-3 pad-btm">
                                                        <?=(isset($is_owner_changed))?($is_owner_changed=='t')?"YES":"NO":"NO";?>
                                                </div>
                                                <div id="is_owner_changed_tran_property_hide_show" class="hidden">
                                                    <label class="col-md-3">Mode of transfer of property from previous Holding Owner </label>
                                                    <div class="col-md-3 pad-btm">
                                                            <?php
                                                            if(isset($transferModeList)){
                                                                foreach ($transferModeList as $transferMode) {
                                                            ?>
                                                                    <?=(isset($transfer_mode_mstr_id))?($transferMode['id']==$transfer_mode_mstr_id)?$transferMode['transfer_mode']:"":"";?>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-3">Ward No </label>
                                            <div class="col-md-3 pad-btm">
                                                    <?php
                                                    if(isset($wardList)){
                                                        foreach ($wardList as $ward) {
                                                    ?>
                                                        <?=(isset($ward_mstr_id))?($ward['id']==$ward_mstr_id)?$ward['ward_no']:"":"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                            </div>
                                            <label class="col-md-3">Ownership Type </label>
                                            <div class="col-md-3 pad-btm">
                                                    <?php
                                                    if(isset($ownershipTypeList)){
                                                        foreach ($ownershipTypeList as $ownershipType) {
                                                    ?>
                                                            <?=(isset($ownership_type_mstr_id))?($ownershipType['id']==$ownership_type_mstr_id)?$ownershipType['ownership_type']:"":"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-3">Property Type </label>
                                            <div class="col-md-3 pad-btm">
                                                    <?php
                                                    if(isset($propTypeList)){
                                                        foreach ($propTypeList as $propType) {
                                                    ?>
                                                            <?=(isset($prop_type_mstr_id))?($propType['id']==$prop_type_mstr_id)?$propType['property_type']:"":"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                        <div class="<?=(isset($prop_type_mstr_id))?($prop_type_mstr_id!=3)?"hidden":"":"hidden";?>">
                                            <div class="row">
                                                <label class="col-md-3">Appartment Name </label>
                                                <div class="col-md-3 pad-btm">
                                                    <?=(isset($appartment_name))?$appartment_name:"";?>
                                                </div>
                                                <label class="col-md-3">Flat Registry Date </label>
                                                <div class="col-md-3 pad-btm">
                                                    <?=(isset($flat_registry_date))?$flat_registry_date:"";?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    $db_name = $session->get('ulb_dtl');
                                    if ($db_name['ulb_mstr_id']==1) {
                                    ?>
                                        <div class="row">
                                            <label class="col-md-3">Zone </label>
                                            <div class="col-md-3">
                                                    <?=(isset($zone_mstr_id))?($zone_mstr_id=='1')?"Zone 1":"":"";?>
                                                    <?=(isset($zone_mstr_id))?($zone_mstr_id=='2')?"Zone 2":"":"";?>
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
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Property Details</h3>
                                    </div>
                                    <div class="panel-body" style="padding-bottom: 0px;">
                                        <div class="row">
                                            <label class="col-md-3">Area of Plot <span class="text-bold">(in Decimal)</span> </label>
                                            <div class="col-md-3 pad-btm">
                                                <?=(isset($area_of_plot))?$area_of_plot:"";?>
                                            </div>
                                            <label class="col-md-3">Road Type </label>
                                            <div class="col-md-3 pad-btm">
                                                    <?php
                                                    if (isset($roadTypeList)) {
                                                        foreach ($roadTypeList as $roadType) {
                                                    ?>
                                                            <?=(isset($ward_mstr_id))?($roadType['id']==$road_type_mstr_id)?$roadType['road_type']:"":"";?>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
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
                                            <label class="col-md-3">Property Address </label>
                                            <div class="col-md-7 pad-btm">
                                                <input type="hidden" name="prop_address" value="<?=(isset($prop_address))?$prop_address:"";?>" />
                                                <?=(isset($prop_address))?$prop_address:"";?>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <label class="col-md-3">City </label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="hidden" name="prop_city" value="<?=(isset($prop_city))?$prop_city:"";?>" />
                                                <?=(isset($ulb_address))?$ulb_address['city']:"";?>
                                            </div>
                                            <label class="col-md-3">District </label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="hidden" name="prop_dist" value="<?=(isset($prop_dist))?$prop_dist:"";?>" />
                                                <?=(isset($ulb_address))?$ulb_address['district']:"";?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-3">State </label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="hidden" name="prop_state" value="<?=(isset($ulb_address))?$ulb_address['state']:"";?>" />
                                                <?=(isset($ulb_address))?$ulb_address['state']:"";?>
                                            </div>
                                            <label class="col-md-3">Pin </label>
                                            <div class="col-md-3 pad-btm">
                                                <input type="hidden" name="prop_pin_code" value="<?=(isset($prop_pin_code))?$prop_pin_code:"";?>" />
                                                <?=(isset($prop_pin_code))?$prop_pin_code:"";?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="floor_dtl_hide_show" class="<?=($prop_type_mstr_id==4)?"hidden":""?>">
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
                                                                    <th>Floor No </th>
                                                                    <th>Use Type </th>
                                                                    <th>Occupancy Type </th>
                                                                    <th>Construction Type </th>
                                                                    <th>Built Up Area  (in Sq. Ft) </th>
                                                                    <th>From Date </th>
                                                                    <th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="floor_dtl_append">
                                                        <?php
                                                        $zf = 1;
                                                        if(isset($floor_dtl_list)) {
                                                            $zf = sizeof($floor_dtl_list);
                                                            $i = 0;
                                                            foreach ($floor_dtl_list AS $key=> $floor_dtl) {
                                                        ?> 
                                                                <tr>
                                                                    <td>
                                                                            <?php
                                                                            if(isset($floorList)){
                                                                                foreach ($floorList as $floor) {
                                                                            ?>
                                                                                <?=($floor['id']==$floor_dtl['floor_mstr_id'])?$floor['floor_name']:"";?>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                            <?php
                                                                            if(isset($usageTypeList)){
                                                                                foreach ($usageTypeList as $usageType) {
                                                                            ?>
                                                                            <?=($usageType['id']==$floor_dtl['usage_type_mstr_id'])?$usageType['usage_type']:"";?>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                            <?php
                                                                            if(isset($occupancyTypeList)){
                                                                                foreach ($occupancyTypeList as $occupancyType) {
                                                                            ?>
                                                                            <?=($occupancyType['id']==$floor_dtl['occupancy_type_mstr_id'])?$occupancyType['occupancy_name']:"";?>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                            <?php
                                                                            if(isset($constTypeList)){
                                                                                foreach ($constTypeList as $constType) {
                                                                            ?>
                                                                            <?=($constType['id']==$floor_dtl['const_type_mstr_id'])?$constType['construction_type']:"";?>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                        <?=$floor_dtl['builtup_area'];?>
                                                                    </td>
                                                                    <td>
                                                                        <?=($floor_dtl['date_from']!="")?date('Y-m', strtotime($floor_dtl['date_from'])):'';?>
                                                                    </td>
                                                                    <td>
                                                                        <?=($floor_dtl['date_upto']!="")?date('Y-m', strtotime($floor_dtl['date_upto'])):'N/A';?>
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
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-body" style="padding-bottom: 0px;">
                                        <div class="row">
                                            <label class="col-md-3">Does Property Have Mobile Tower(s) ? </label>
                                            <div class="col-md-3 pad-btm">
                                                <?=(isset($is_mobile_tower))?($is_mobile_tower=="t")?"YES":"NO":"NO";?>
                                            </div>
                                        </div>
                                        <div class="<?=(isset($is_mobile_tower))?($is_mobile_tower=="t")?"":"hidden":"hidden";?>">
                                            <div class="row">
                                                <label class="col-md-3">Total Area Covered by Mobile Tower & its
                    Supporting Equipments & Accessories (in Sq. Ft.) </label>
                                                <div class="col-md-3">
                                                    <?=(isset($tower_area))?$tower_area:"";?>
                                                </div>
                                                <label class="col-md-3">Date of Installation of Mobile Tower </label>
                                                <div class="col-md-3 ">
                                                    <?=(isset($tower_installation_date))?$tower_installation_date:"";?>
                                                </div>
                                            </div>
                                            <hr />
                                        </div>

                                        <div class="row">
                                            <label class="col-md-3">Does Property Have Hoarding Board(s) ? </label>
                                            <div class="col-md-3 pad-btm">
                                                <?=(isset($is_hoarding_board))?($is_hoarding_board=="t")?"YES":"NO":"NO";?>
                                            </div>
                                        </div>
                                        <div class="<?=(isset($is_hoarding_board))?($is_hoarding_board=="t")?"":"hidden":"hidden";?>">
                                            <div class="row">
                                                <label class="col-md-3">T   
                    Total Area of Wall / Roof / Land (in Sq. Ft.) </label>
                                                <div class="col-md-3">
                                                    <?=(isset($hoarding_area))?$hoarding_area:"";?>
                                                </div>
                                                <label class="col-md-3">Date of Installation of Hoarding Board(s) </label>
                                                <div class="col-md-3">
                                                    <?=(isset($hoarding_installation_date))?$hoarding_installation_date:"";?>
                                                </div>
                                            </div>
                                            <hr />
                                        </div>
                                        <div class="<?=(isset($prop_type_mstr_id))?($prop_type_mstr_id==4)?"hidden":"":"hidden";?>" id="petrol_pump_dtl_hide_show">
                                            <div class="row">
                                                <label class="col-md-3">Is property a Petrol Pump ? </label>
                                                <div class="col-md-3 pad-btm">
                                                    <?=(isset($is_petrol_pump))?($is_petrol_pump=="t")?"YES":"NO":"NO";?>
                                                </div>
                                            </div>
                                            <div class="<?=(isset($is_petrol_pump))?($is_petrol_pump=="t")?"":"hidden":"hidden";?>">
                                                <div class="row">
                                                    <label class="col-md-3">    
                        Underground Storage Area (in Sq. Ft.) </label>
                                                    <div class="col-md-3">
                                                        <?=(isset($under_ground_area))?$under_ground_area:"";?>
                                                    </div>
                                                    <label class="col-md-3">Completion Date of Petrol Pump </label>
                                                    <div class="col-md-3">
                                                        <?=(isset($petrol_pump_completion_date))?$petrol_pump_completion_date:"";?>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                        </div>
                                        <?php
                                        if(isset($prop_type_mstr_id) && $prop_type_mstr_id!=4) {
                                        ?>
                                        <div class="row">
                                            <label class="col-md-3">Rainwater harvesting provision ? </label>
                                            <div class="col-md-3 pad-btm">
                                                <?=(isset($is_water_harvesting))?($is_water_harvesting=="t")?"YES":"NO":"NO";?>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if(isset($prop_type_mstr_id) && $prop_type_mstr_id==4) {
                                        ?>
                                        <div class="row">
                                            <label class="col-md-3">Date of Possession / Purchase / Acquisition (Whichever is earlier) </label>
                                            <div class="col-md-3">
                                            <?=(isset($land_occupation_date))?$land_occupation_date:"";?>
                                            </div>
                                        </div>
                                        <hr />
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    <!--End Default Tabs (Right Aligned)-->
                </div>
            </div>     
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body demo-nifty-btn text-center">
                    <button type="SUBMIT" id="btn_update" name="btn_update" class="btn btn-primary">UPDATE</button>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
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
    <?php
    if($flashToast = flashToast('saf_back_office_update')) {
        echo "modelInfo('".$flashToast."')";
    }
    ?>

    function diffAddressCkFun(){
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
    diffAddressCkFun();

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
    let e_dtl='';
    $(document).ready(function(){
        var e_c_no=$('#elect_consumer_no').val();
        var e_a_no=$('#elect_acc_no').val();
        var e_b_no=$('#elect_bind_book_no').val();
        var e_c_cno=$('#elect_cons_category').val();
        if(e_a_no || e_b_no || e_c_cno)
        {
            e_dtl=2; 
        }
        if(e_c_no)
        {
            e_dtl=1;
        }
       
        $('#btn_update').click(function(){

            if ($('#is_transaction').val()==0) {
                $(".owner_name").each(function() {
                    var ID = this.id.split('owner_name')[1];
                    $("#owner_name"+ID).rules("add", { required:true });
                    $("#mobile_no"+ID).rules("add", { required:true, minlength:10, maxlength:10, digits: true });
                });
            } else {
                $(".owner_name").each(function() {
                    var ID = this.id.split('owner_name')[1];
                    $("#owner_name"+ID).rules('remove',  'required');
                    $("#mobile_no"+ID).rules('remove',  'required');
                });
            }
            
            if ($('#is_corr_add_differ').prop("checked") == true) {
                $('#corr_address').rules('add',  { required: true });
                $('#corr_city').rules('add',  { required: true });
                $('#corr_dist').rules('add',  { required: true });
                $('#corr_state').rules('add',  { required: true });
                $('#corr_pin_code').rules('add',  { required: true });
            } else {
                $('#corr_address').rules('remove',  'required');
                $('#corr_city').rules('remove',  'required');
                $('#corr_dist').rules('remove',  'required');
                $('#corr_state').rules('remove',  'required');
                $('#corr_pin_code').rules('remove',  'required');
            }
        });        
        
        $("#backOfficeUpdate").validate({
            rules:{
                khata_no:{
                    required:true
                },
                plot_no:{
                    required:true
                },
                village_mauja_name:{
                    required:true
                },
                'owner_name[]':{
                    required:true
                },
                'guardian_name[]':{
                    required:true
                },
                'relation_type[]':{
                    required:true
                },
                'mobile_no[]':{
                    required:true,
                    digits: true,
                },
                'aadhar_no[]':{
                    required: true,
                    digits: true,
                    minlength: 12,
                    maxlength: 12,
                },
                'email[]':{
                    required: false,
                    email: true,
                },
                'pan_no[]':{
                    required: false,
                },

            <?php
            if(isset($no_electric_connection) && $no_electric_connection=='t'){
            ?>
                // elect_consumer_no:{
                //     required:true
                // },
                // elect_acc_no:{
                //     required:true
                // },
                // elect_bind_book_no:{
                //     required:true
                // },
                // elect_cons_category:{
                //     required:true
                // },
                "elect_consumer_no": {
                    required: function(element){ ///$('#is_corr_add_differ').prop("checked")                        
                        return (jQuery.inArray(<?="'".$prop_type_mstr_id."'"?>, ['1', '3', '5']) !== -1 &&  e_dtl==1 ? true : false);
                    },        
                },
                "elect_acc_no": { 
                    required: function(element){ 
                        data=(jQuery.inArray(<?="'".$prop_type_mstr_id."'"?>, ['1', '3', '5']) !== -1 &&  e_dtl==2 ? true : false);
                        //alert(data);
                        return data;
                    },        
                },
                "elect_bind_book_no": {
                    required: function(element){
                        return (jQuery.inArray(<?="'".$prop_type_mstr_id."'"?>, ['1', '3', '5']) !== -1 &&  e_dtl==2 ? true : false);
                    },        
                },
                "elect_cons_category": {
                    required: function(element){
                        return (jQuery.inArray(<?="'".$prop_type_mstr_id."'"?>, ['1', '3', '5']) !== -1 &&  e_dtl==2 ? true : false);
                    },        
                },
            <?php
            }
            ?>
            }
        });
    });
    function Electricity(choise)
    {
         e_dtl=parseInt(choise);
         if(e_dtl==2)
         {
            $('#elect_consumer_no').val('');
        
         }
         else
         { 
            $('#elect_acc_no').val('');
            $('#elect_bind_book_no').val('');
            $('#elect_cons_category').val(''); 
         }
    }
</script>