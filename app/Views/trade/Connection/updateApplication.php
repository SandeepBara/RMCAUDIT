<?= $this->include('layout_vertical/header'); ?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<link href="<?= base_url(); ?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Trade </a></li>
            <li class="active"><a href="#">Update Apply Licence </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="POST" enctype="multipart/form-data">
            <?php
            if (isset($validation)) {
            ?>
                <?= $validation->listErrors(); ?>

            <?php
            }
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">     
                    <div class ="panel-control">
                        <input type="checkbox" onclick="showHidBlock('logs');"/>
                    </div>               
                    <h3 class="panel-title">Application Update History</h3>
                </div>
                <div class="panel-body" id="logs" style="display: none;">
                    <div class="panel panel-bordered panel-dark">
                        <div class="panel-heading"> 
                            <div class ="panel-control">
                                <input type="checkbox" onclick="showHidBlock('locense_log');"/>
                            </div>                   
                            <h3 class="panel-title">License Update History</h3>
                        </div>
                        <div class="panel-body" id ="locense_log">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table id="empTable" class="table table-striped table-bordered text-sm">
                                            <thead>
                                                <tr>
                                                    <?php
                                                        foreach($heading as $val)
                                                        {
                                                            ?> 
                                                            <th><?=$val?></th>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if(isset($appLogs["logs"]))
                                                {
                                                    foreach(json_decode($appLogs["logs"],true) as $log)
                                                    {
                                                        ?>
                                                        <tr>
                                                            <?php
                                                        foreach($heading as $val)
                                                        {
                                                            
                                                            {
                                                                if($val=="supproting_doc")
                                                                {
                                                                    ?>
                                                                    <td><a href="<?=base_url()?>/getImageLink.php?path=<?=$log[$val]??"";?>"target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="height: 40px;"></a></td>
                                                                    <?php
                                                                    continue;
                                                                }
                                                                ?>  
                                                                <td><?=$log[$val]??"";?></td>
                                                                <?php
    
                                                            }
                                                        }
                                                        ?>
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
                    <div class="panel panel-bordered panel-dark" >
                        <div class="panel-heading">  
                            <div class ="panel-control">
                                <input type="checkbox" onclick="showHidBlock('owner_log');"/>
                            </div>                  
                            <h3 class="panel-title">Owner Update History</h3>
                        </div>
                        <div class="panel-body" id="owner_log">
                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="empTable2" class="table table-striped table-bordered text-sm">
                                                <thead>
                                                    <tr>
                                                        <?php
                                                            foreach($wonerHeadings as $val)
                                                            {
                                                                ?> 
                                                                <th><?=$val?></th>
                                                                <?php
                                                            }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(isset($ownereLogsList["logs"]))
                                                    {
                                                        foreach(json_decode($ownereLogsList["logs"],true) as $log)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <?php
                                                            foreach($wonerHeadings as $val)
                                                            {
                                                                if($val=="supproting_doc")
                                                                {
                                                                    ?>
                                                                    <td><a href="<?=base_url()?>/getImageLink.php?path=<?=$log[$val]??"";?>"target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="height: 40px;"></a></td>
                                                                    <?php
                                                                    continue;
                                                                }
                                                                ?>  
                                                                <td><?=$log[$val]??"";?></td>
                                                                <?php
                                                            }
                                                            ?>
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
            </div>


            <div class="panel panel-bordered panel-dark" id ="firm_bolck">
                <div class="panel-heading">
                    <div class ="panel-control">
                        <input type="checkbox" name="firm_check_box" id="firm_check_box" onclick="read_only_remove('firm_check_box','firm_bolck');" <?=isset($_POST["firm_check_box"]) && $_POST["firm_check_box"] ? 'checked':'';?>/>
                    </div>
                    <h3 class="panel-title">Firm Details</h3>
                </div>
                <input type="hidden" id="id" name="id" value="<?= (isset($id)) ? $id : ""; ?>">
                <div class="panel-body">
                    <div class="row">
                        <div class="row" style="line-height: 35px;">
                            <label class="col-md-2">Application No.:-</label>
                            <div class="col-md-3 control-label text-semibold">
                                <?= $application_no ?? null ?>
                            </div>
                            <label class="col-md-2">License No.:-</label>
                            <div class="col-md-3 control-label text-semibold">
                                <?= $license_no ?? "N/A" ?>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">Application Type <span class="text-danger">*</span></label>
                            <div class="col-md-3 control-label text-semibold">
                                <select name="application_type_id" id="application_type_id" class="form-control">
                                    <?php
                                        foreach($application_type_list as $val)
                                        {
                                            ?>
                                            <option value="<?=$val["id"];?>" <?=$application_type_id && $application_type_id==$val["id"] ? "selected":"";?>><?=$val["application_type"];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <label class="col-md-2">Firm Type <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select name="firmtype_id" id="firmtype_id" onchange="forother(this.value)" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    if ($firmtypelist) {
                                        foreach ($firmtypelist as $val) 
                                        {
                                            ?>
                                            <option value="<?= $val['id']; ?>" <?= isset($firm_type_id) && ($firm_type_id == $val['id']) ? "selected" : "" ?>>
                                                <?= $val['firm_type']; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">                            
                            <label class="col-md-2" id="holding_lebel">Holding No.<span class="text-danger"></span></label>
                            <div class="col-md-3 pad-btm" id="holding_div">
                                <input type="text" name="holding_no" id="holding_no" class="form-control" onchange="validate_holding()" value="<?= isset($holding_no) ? $holding_no : ""; ?>" onkeypress="return isAlphaNum(event);">
                                <input type="hidden" name="prop_id" id="prop_id" value="<?php echo isset($prop_dtl_id) ? $prop_dtl_id : ""; ?>">
                            </div>
                            <label class="col-md-2 classother" style="display: none;">For Other Firm type<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm classother" style="display: none;">
                                <input type="text" name="firmtype_other" id="firmtype_other" class="form-control" value="<?= isset($firmtype_other) ? $firmtype_other : ""; ?>" placeholder="Other Firm type" onkeypress="return isAlphaNum(event);">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select name="ownership_type_id" id="ownership_type_id" onchange="validate_holding()" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    if ($ownershiptypelist) 
                                    {

                                        foreach ($ownershiptypelist as $val) 
                                        {
                                            ?>

                                            <option value="<?= $val['id']; ?>" <?= isset($ownership_type_id) && ($ownership_type_id == $val['id']) ? "selected" : "" ?>>
                                                <?= $val['ownership_type']; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-md-2">Category<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <select name="category_type_id" id="category_type_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    if ($categoryTypeDetails) {
                                        foreach ($categoryTypeDetails as $vdata) {
                                    ?>
                                            <option value="<?= $vdata['id']; ?>" <?= isset($category_type_id) && ($category_type_id == $vdata['id']) ? "selected" : "" ?>>
                                                <?= $vdata['category_type']; ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                <option value="">Select</option>
                                <?php
                                foreach ($ward_list as $value) {
                                ?>
                                    <option value="<?= $value['id'] ?>" <?= (isset($ward_mstr_id)) && $ward_mstr_id == $value["id"] ? "SELECTED" : ""; ?>> <?= $value['ward_no']; ?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div> 
                        <label class="col-md-2">New Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="new_ward_mstr_id" name="new_ward_mstr_id" class="form-control">
                                <option value="">Select</option>
                                <?php
                                foreach ($ward_list as $value) {
                                ?>
                                    <option value="<?= $value['id'] ?>" <?= (isset($new_ward_mstr_id)) ? ($new_ward_mstr_id == $value["id"] ? "SELECTED" : "") : ""; ?>> <?= $value['ward_no']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>                       
                    </div>

                    <div class="row">
                        <label class="col-md-2">Firm Name<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="firm_name" id="firm_name" class="form-control" value="<?= isset($firm_name) ? $firm_name : ""; ?>" onkeypress="return isAlphaNumCommaSlashAmperstandApos(event);">
                        </div>

                        <label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" value="<?= isset($area_in_sqft) ? $area_in_sqft : ""; ?>" onkeypress="return isNumDot(event);" />
                        </div>
                    </div>
                    <div class="row">

                        <label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="date" name="firm_date" id="firm_date" class="form-control" value="<?= isset($establishment_date) ? $establishment_date : date('Y-m-d'); ?>" onkeypress="return isNum(event);"  />
                        </div>

                        <label class="col-md-2">Address<span class="text-danger">*</span></label>

                        <div class="col-md-3 pad-btm">
                            <input name="firmaddress" id="firmaddress" class="form-control" onkeypress="return isAlphaNum(event);" value="<?= isset($address) ? $address : ""; ?>">

                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">landmark<span class="text-danger">*</span></label>

                        <div class="col-md-3 pad-btm">
                            <input type="text" name="landmark" id="landmark" class="form-control" value="<?= isset($landmark) ? $landmark : ""; ?>" onkeypress="return isAlphaNum(event);">
                        </div>

                        <label class="col-md-2">Pin Code<span class="text-danger"></span></label>
                        <input type="hidden" value="<?= $application_type_id ?>" name="application_type_id">
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="pin_code" id="pin_code" maxlength="6" class="form-control" value="<?= isset($pin_code) ? $pin_code : ""; ?>" onkeypress="return isNum(event);">
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-2">Owner of Business Premises<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="owner_business_premises" id="owner_business_premises" class="form-control" value="<?= isset($premises_owner_name) ? $premises_owner_name : ""; ?>" onkeypress="return isAlphaNumCommaSlash(event);">
                        </div>
                        
                    </div>
                    <div class="row">
                        <label class="col-md-2">Business Description<span class="text-danger">*</span></label>
                        <div class="col-md-8 pad-btm">
                            <textarea name="brife_desp_firm" id="brife_desp_firm" class="form-control" required onkeypress="return isAlphaNum(event);"><?= isset($brife_desp_firm) ? $brife_desp_firm : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">License For Year<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select name="licence_for_years" id="licence_for_years" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                for ($i=1; $i < 11 ; $i++) 
                                { 
                                    ?>
                                    <option value="<?=$i;?>" <?=$licence_for_years==$i ? "SELECTED":"";?>><?=$i?> Year</option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">                                
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Nature Of Business <span class="text-danger"><?=$tobacco_status?"(Tombaco)":""?> *</span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="trade_item_append">
                                        <?php
                                        $ti = 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <select id="tade_item" name="tade_item[]" class="form-control tade_item demo-select2-multiple-selects" required="required" multiple="multiple">
                                                    <optgroup label="Central Time Zone">
                                                        <?php
                                                        if ($tradeitemlist) 
                                                        {
                                                            foreach ($tradeitemlist as $valit) 
                                                            {
                                                                ?>
                                                                <option value="<?=$valit['id']; ?>" <?=in_array($valit['id'],explode(",",$nature_of_bussiness))? "selected":""?>> <?=("(" . $valit['id'] . ") " . $valit['trade_item']); ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                </select>
                                            </td>
                                            <td class="text-2x">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>

            <div class="panel panel-bordered panel-dark" id ="owner_dtl_block">
                <div class="panel-heading">
                    <div class ="panel-control">
                        <input type="checkbox" name="owner_dtl_check_box" id="owner_dtl_check_box" onclick="read_only_remove('owner_dtl_check_box','owner_dtl_block');" <?=isset($_POST["owner_dtl_check_box"]) && $_POST["owner_dtl_check_box"] ? 'checked':'';?>/>
                    </div>
                    <h3 class="panel-title">Firm Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name <span class="text-danger">*</span></th>
                                            <th>Guardian Name <span class="text-danger">*</span></th>
                                            <th>Mobile No <span class="text-danger">*</span></th>
                                            <th>Email Id <span class="text-danger"></span></th>
                                            <th>Address <span class="text-danger">*</span></th>
                                            <th>Action </th>
                                            <th>Old Owner Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                        <?php
                                        $zo = 0;
                                        if (isset($ownerDetails, $ownerDetails)) 
                                        {
                                            foreach ($ownerDetails as  $value) 
                                            {
                                                $zo++;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="owner_name_id[]" value="<?= $value['id'] != "" ? $value['id'] : ""; ?>">
                                                        <input type="text" id="owner_name<?= $zo; ?>" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="<?= $value['owner_name'] != "" ? $value['owner_name'] : ""; ?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="guardian_name<?= $zo; ?>" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="<?= $value['guardian_name'] != "" ? $value['guardian_name'] : ""; ?>" onkeypress="return isAlphaNumCommaSlash(event);" onkeyup="borderNormal(this.id);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="mobile_no<?= $zo; ?>" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="<?= $value['mobile'] != "" ? $value['mobile'] : ""; ?>" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="emailid<?= $zo; ?>" name="emailid[]" class="form-control address" placeholder="Email Id" value="<?= $value['emailid'] != "" ? $value['emailid'] : ""; ?>" onkeyup="borderNormal(this.id);" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="address<?= $zo; ?>" name="address[]" class="form-control address" onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Address" value="<?= $value['address'] != "" ? $value['address'] : ""; ?>" onkeyup="borderNormal(this.id);" />
                                                    </td>
                                                    <td class="text-2x">
                                                        <i class="fa fa-plus-square" id="my_owner_plus" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                                        &nbsp;
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" id="old_owner_remove<?= $zo; ?>" name="old_owner_remove[]"/>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } 
                                        else 
                                        {
                                            $zo = 1;
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlphaNumCommaSlash(event);" onkeyup="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                                </td>
                                                <td>
                                                    <input type="text" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" />
                                                </td>
                                                <td>
                                                    <input type="text" id="address1" name="address[]" class="form-control address" onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Address" value="" onkeyup="borderNormal(this.id);" />

                                                </td>
                                                <td class="text-2x">
                                                    <i class="fa fa-plus-square" id="my_owner_plus" style="cursor: pointer; display:none" onclick="owner_dtl_append_fun();"></i> &nbsp;
                                                    <?php
                                                    if ($zo > 1) {
                                                    ?>
                                                        <i class="fa fa-window-close remove_owner_dtl" id="my_owner_minus" style="cursor: pointer; display:none;"></i>
                                                    <?php
                                                    }
                                                    ?>
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

            <div class="panel panel-bordered panel-dark">
                <div class="row panel-body">
                    <label for="suport_doc" class="col-md-2">Suporting Doc<span class="text-danger">*</span></label>
                    <div class="col-md-3" id="document">
                        <input type="file" name="suport_doc" id = "suport_doc" class="form-control" onchange="checkFileExtension()"/>
                    </div>
                    <div class="panel-body demo-nifty-btn text-center">
                        <button class="btn btn-primary" id="btn_review" name="btn_review" type="submit"><?= (isset($id)) ? "SAVE" : "Submit"; ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        read_only_remove('firm_check_box', 'firm_bolck');
        read_only_remove('owner_dtl_check_box', 'owner_dtl_block');
        var wardmsid = $('#ward_mstr_id').val();

        var valNature = $('#tade_item1').val();
        const nobs_val = [];

        $('.tade_item').each(function() {
            var currentElement = $(this);
            nobs_val.push(currentElement.val());

        });
        console.log(nobs_val[0]);
        if (nobs_val.length == 1 && nobs_val[0] == 198) {
            $('#my_plus').css('display', 'none');
        } else {

            $('#my_plus').css('display', 'block');
            $('#my_minus').css('display', 'block');
        }

        // code ends here
        var firm_estd_date = $('#firm_date').val();
        if (valNature == 198 && (new Date(firm_estd_date) < new Date('2021-01-01'))) {

            $('#my_plus').css('display', 'none');

            $('#tade_item1').click(function() {
                alert('This field can\'t be changed');
                $('#tade_item1').val(valNature);

            });

            $('#tade_item1').change(function() {
                alert('This field can\'t be changed');
                $('#tade_item1').val(valNature);

            });

            $('#tade_item2').click(function() {
                alert('This field can\'t be changed');
                $('#tade_item1').val(valNature);

            });

            $('#tade_item2').change(function() {
                alert('This field can\'t be changed');
                $('#tade_item1').val(valNature);

            });

        }
        
        $("#formname").validate({
            rules: {
                suport_doc: {
                    required: function(element) {
                        return ($('#firm_check_box').is(':checked') || $('#owner_dtl_check_box').is(':checked'))
                    }
                },
                firmtype_id: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                ownership_type_id: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                ward_mstr_id: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                new_ward_mstr_id: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                firm_name: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                firm_date: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                area_in_sqft: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                address: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                holding_no: {
                    required: function(element) {
                        return ($('#firm_check_box').is(':checked') && (<?=$application_type_id;?>!=1))
                    }
                },

                landmark: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                pin_code: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                new_ward_mstr_id: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                firmtype_other: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                owner_business_premises: {
                    required: function(element) {
                        return $('#firm_check_box').is(':checked')
                    }
                },
                doc_path: {
                    required: false
                },
                remark: {
                    required: false
                },
                category_type_id: {
                    required: false
                },
                "owner_name[]": {
                    required: function(element) {
                        return $('#owner_dtl_check_box').is(':checked')
                    },
                    minlength: 3
                },
                "guardian_name[]": {
                    required: function(element) {
                        return $('#owner_dtl_check_box').is(':checked')
                    },
                    minlength: 3
                },
                "mobile_no[]": {
                    required: function(element) {
                        return $('#owner_dtl_check_box').is(':checked')
                    },
                    minlength: 10
                },
                "emailid[]": {
                    required: false,
                    email: true
                },
                "address[]": {
                    required: false,

                },
                "id_no[]": {
                    required: false
                }
            },
            messages: {
                suport_doc:{
                    required:"Supporting document is Requird",
                    extension:"Enter Vlide Document Extention"
                },
                firmtype_id: {
                    required: "Please select Firm Type"
                },
                ownership_type_id: {
                    required: "Please select Ownership Type"
                },
                ward_mstr_id: {
                    required: "Please select Ward No."
                },
                new_ward_mstr_id: {
                    required: "Please Select New Ward No."
                },
                firm_name: {
                    required: "Please Enter Firm Name"
                },
                firm_date: {
                    required: "Please Enter Firm Establishment Date"
                },
                area_in_sqft: {
                    required: "Please Enter Area"
                },
                address: {
                    required: "Please Enter Address"
                },
                landmark: {
                    required: "Please Enter Landmark"
                },
                pin_code: {
                    required: "Please Enter Pincode"
                },
                holding_no: {
                    required: "Please Enter Holding No."
                },
                doc_path: {
                    required: "Please Select Document"
                },
                new_ward_mstr_id: {
                    required: "Please Select New Ward No."
                },
                remark: {
                    required: "Please Enter Remark"
                },
                category_type_id: {
                    required: "Please Select Category"
                },
                "owner_name[]": {
                    required: "Please Enter Owner Name"
                },
                "guardian_name[]": {
                    required: "Please Enter Guardian Name"
                },
                "mobile_no[]": {
                    required: "Please Enter Mobile No."
                },
                "emailid[]": {
                    required: "Please Enter Email Address"
                },
                "address[]": {
                    required: "Please Enter Address"
                },
                "id_no[]": {
                    required: "Please Enter Id No."
                },
                firmtype_other: {
                    required: "Please Enter Other Firm type"
                },
                owner_business_premises: {
                    required: "Please Enter Owner Business Premises"
                }
            }
        });
    });

    function isAlpha(e) {
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }

    function isNumDot(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode == 46) {
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length == 0) {
                return false;
            }
        } else {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }

    function isAlphaNum(e) {
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e) {
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlashAmperstandApos(e) {

        var keyCode = (e.which) ? e.which : e.keyCode;
        if (e.which != 39 && e.which != 38 && e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }


    function isDateFormatYYYMMDD(value) {
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(value)) {
            return false;
        } else {
            return true;
        }
    }


    $("#btn_review").click(function() {

        $(".owner_name").each(function() {
            var ID = this.id.split('owner_name')[1];
            var owner_name = $("#owner_name" + ID).val();
            var guardian_name = $("#guardian_name" + ID).val();
            var mobile_no = $("#mobile_no" + ID).val();

            if (owner_name.length < 3) {
                $("#owner_name" + ID).css('border-color', 'red');
                process = false;
            }
            if (guardian_name != "") {
                if (guardian_name.length < 3) {
                    $("#guardian_name" + ID).css('border-color', 'red');
                    process = false;
                }
            }
            if (mobile_no.length != 10) {
                $("#mobile_no" + ID).css('border-color', 'red');
                process = false;
            }

        });

        $(".tade_item").each(function() {
            var IDV = this.id.split('tade_item')[1];
            var tade_item = $("#tade_item" + IDV).val();
            if (tade_item == "") {
                $("#tade_item" + IDV).css('border-color', 'red');
                process = false;
            }
        });
    });

    $(document).ready(function() {


        $('.demo-select2-multiple-selects').select2();

        var firmtype_id = $('#firmtype_id').val();
        if (firmtype_id == 2 || firmtype_id == 3) {
            $('#my_owner_plus').css('display', 'block');
            $('#my_owner_minus').css('display', 'block');

        } else {
            $('#my_owner_plus').css('display', 'none');
            $('#my_owner_minus').css('display', 'none');
        }

        $('#firmtype_id').change(function() {

            var firmtype_id = $('#firmtype_id').val();
            if (firmtype_id == 2 || firmtype_id == 3) {
                $('#my_owner_plus').css('display', 'block');
                $('#my_owner_minus').css('display', 'block');

            } else {
                $('#my_owner_plus').css('display', 'none');
                $('#my_owner_minus').css('display', 'none');
            }
        });

        var holding_exists = $("#holding_exists").val();

        if (holding_exists == 'YES') {
            $("#holding_lebel").show();
            $("#holding_div").show();
            $("#saf_div").hide();
            $("#holding_no").attr('required', true);
            $("#saf_no").attr('required', false);

        } else if (holding_exists == 'NO') {
            $("#saf_lebel").show();
            $("#saf_div").show();
            $("#holding_div").hide();
            $("#saf_no").attr('required', true);
            $("#holding_no").attr('required', false);
        }
    });

    var appendData = '<tr><td><input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlphaNumCommaSlash(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value=""  onkeyup="borderNormal(this.id);"  /></td><td><input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNumCommaSlash(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i></td></tr>';

    function validate_holding() {

        var holding_no = $("#holding_no").val();

        var firmtype_id = $("#firmtype_id").val();
        var ward_id = $("#ward_mstr_id").val();
        var owner_type = $("#ownership_type_id").val();;
        if (!~jQuery.inArray( holding_no.length, [15,16] ) && holding_no.length!=0) 
        {
            alert('Enter Valide Holding No.');
            $("#holding_no").val("<?=$holding_no?>");
            $("#prop_id").val("<?=$prop_dtl_id?>");
            return;
        }
        if (holding_no == "") 
        {
            alert('Please Enter Holding No.');
            $("#holding_no").val("<?=$holding_no?>");
            $("#prop_id").val("<?=$prop_dtl_id?>");
            return;
        } 
        $.ajax({
            type: "POST",
            url: '<?php echo base_url("tradeapplylicence/validate_holding_no"); ?>',
            dataType: "json",
            data: {
                "holding_no": holding_no,
                "ward_mstr_id": ward_id
            },
            success: function(data) {
                console.log(data);
                if (data.response == true && data.pp != null) 
                { 
                    var tbody = "";
                    var i = 1;
                    var prop_id = data.pp['id'];
                    var ward_mstr_id = data.pp['ward_mstr_id'];
                    var ward_no = data.pp['ward_no'];
                    var address = data.pp['prop_address'];
                    var city = data.pp['prop_city'];
                    var pincode = data.pp['prop_pin_code'];
                    var owner_business_premises = data.pp['owner_name'];
                    $("#prop_id").val(prop_id);
                    if(confirm("Do You Want to Update Ward No."))
                    {
                        $("#ward_mstr_id").val(ward_mstr_id);
                    }
                    if(confirm("Do You Want to Update Address"))
                    {
                        $("#firmaddress").val(address);
                    }
                    if(confirm("Do You Want to Update Pin Code"))
                    {
                        $("#pin_code").val(pincode);
                    }
                    if(confirm("Do You Want to Update Business Premises"))
                    {
                        $("#owner_business_premises").val(owner_business_premises);
                    }
                } 
                else 
                {
                    alert('Holding No. not Found');
                    $("#holding_no").val("<?=$holding_no?>");
                    $("#prop_id").val("<?=$prop_dtl_id?>");

                    $("#ward_mstr_id").val("<?=$ward_mstr_id?>");
                    $("#firmaddress").val("<?=$address?>");
                    $("#pin_code").val("<?=$pin_code?>");
                    $("#owner_business_premises").val("<?=$premises_owner_name?>");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });

    }

    function forother(str) {

        if (str == 5) {
            $(".classother").show();
        } else {
            $(".classother").hide();
        }
    }

    function getsqmtr(str) {
        var area_in_sqft = str;
        var area_in_sqmt = area_in_sqft / 0.092903;
        if (area_in_sqft != "") {
            $("#area_in_sqmt").val(area_in_sqmt);
        } else {
            $("#area_in_sqmt").val("");
        }

    }

    function getsqft(str) {
        var area_in_sqmt = str;
        var area_in_sqft = 0.092903 * area_in_sqmt;
        $("#area_in_sqft").val(area_in_sqft);

    }

    function show_hide_saf_holding_box(str) {

        var holding_exists = str;
        if (holding_exists == 'YES') {
            $("#holding_lebel").show();
            $("#holding_div").show();
            $("#saf_div").hide();
            $("#saf_lebel").hide();
            $("#holding_no").attr('required', true);
            $("#saf_no").attr('required', false);
            $("#saf_no").val("");
            $("#saf_id").val("");
            $("#ward_id").val("");
            $("#ward_no").val("");
            $("#firmaddress").val("");
            $("#pin_code").val("");


            $("#owner_dtl_append").html(appendData);


        } else if (holding_exists == 'NO') {
            $("#saf_lebel").show();
            $("#saf_div").show();
            $("#holding_div").hide();
            $("#holding_lebel").hide();
            $("#saf_no").attr('required', true);
            $("#holding_no").attr('required', false);
            $("#holding_no").val("");
            $("#prop_id").val("");
            $("#ward_id").val("");
            $("#ward_no").val("");
            $("#firmaddress").val("");
            $("#pin_code").val("");

            $("#owner_dtl_append").html(appendData);

        } else {
            $("#saf_div").hide();
            $("#holding_div").hide();
            $("#saf_no").attr('required', false);
            $("#holding_no").attr('required', false);
            $("#saf_no").attr('required', false);
            $("#holding_no").attr('required', false);
            $("#holding_no").val("");
            $("#prop_id").val("");
            $("#saf_no").val("");
            $("#saf_id").val("");
            $("#ward_id").val("");
            $("#ward_no").val("");
            $("#firmaddress").val("");
            $("#pin_code").val("");
        }

    }

    function validate_saf() {
        var saf_no = $("#saf_no").val();

        var owner_type = $("#ownership_type_id").val();
        if (saf_no == "") {
            $("#owner_dtl_append").html(appendData);

        } else {

            $.ajax({
                type: "POST",
                url: '<?php echo base_url("tradeapplylicence/validate_saf_no"); ?>',
                dataType: "json",
                data: {
                    "saf_no": saf_no
                },

                success: function(data) {

                    if (data.response == true) {

                        var tbody = "";
                        var i = 1;

                        var payment_status = data.sf['payment_status'];
                        var prop_dtl_id = data.sf['prop_dtl_id'];
                        var saf_id = data.sf['id'];
                        var ward_mstr_id = data.sf['ward_mstr_id'];
                        var ward_no = data.sf['ward_no'];
                        var address = data.sf['prop_address'];
                        var city = data.sf['prop_city'];
                        var pincode = data.sf['prop_pin_code'];
                        for (var k in data.dd) {


                            tbody += "<tr>";

                            tbody += '<td><input type="text" name="owner_name[]" id="owner_name' + i + '" class="form-control" onkeypress="return isAlpha(event);" value="' + data.dd[k]['owner_name'] + '" readonly ></td>';

                            tbody += '<td><input type="text" name="guardian_name[]" id="guardian_name' + i + '" class="form-control" onkeypress="return isAlphaNumCommaSlash(event);" value="' + data.dd[k]['guardian_name'] + '" readonly></td>';

                            tbody += '<td><input type="text" name="mobile_no[]" id="mobile_no' + i + '" class="form-control" onkeypress="return isNum(event);" value="' + data.dd[k]['mobile_no'] + '" readonly></td>';
                            tbody += '<td><input type="text" id="address' + i + '" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td>';
                            tbody += '<td><input type="text" id="city' + i + '" name="city[]"  class="form-control city" placeholder="City" value="' + data.sf['prop_city'] + '" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td>';

                            tbody += '<td><input type="text" id="state' + i + '" name="state[]" readonly  class="form-control state" placeholder="state" value="' + city + '" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td>';

                            tbody += '<td><input type="text" id="district' + i + '" name="district[]" readonly  class="form-control district" placeholder="district" value="' + city + '" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td>';
                            tbody += '<td></td>';

                            tbody += "</tr>";
                            i++;

                        }
                        if (payment_status == 0) {
                            alert('Please make your payment in SAF first');
                            $("#saf_no").val("");
                        } else if (prop_dtl_id != 0) {
                            alert('Your Holding have been generated kindly provide your Holding no.');
                            $("#saf_no").val("");
                        } else if (payment_status == 1) {
                            $("#saf_id").val(saf_id);
                            $("#ward_id").val(ward_mstr_id);
                            $("#ward_no").val(ward_no);
                            $("#firmaddress").val(address);
                            $("#pin_code").val(pincode);
                            if (owner_type == 1) {
                                $("#owner_dtl_append").html(tbody);
                            } else {
                                $("#owner_dtl_append").html(appendData);
                            }
                        }

                    } else {

                        alert('SAF No. not Found');
                        $("#saf_no").val("");
                        $("#saf_id").val("");
                        $("#ward_id").val("");
                        $("#ward_no").val("");
                        $("#firmaddress").val("");
                        $("#pin_code").val("");

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }

    }

    var zo = <?= $zo; ?>;

    function owner_dtl_append_fun() {
        zo++;
        var appendData = '<tr><td><input type="text" id="owner_name' + zo + '" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name' + zo + '" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlphaNumCommaSlash(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no' + zo + '" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="emailid' + zo + '" name="emailid[]" class="form-control address" placeholder="Email Id" value=""  onkeyup="borderNormal(this.id);"  /></td><td><input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNumCommaSlash(event);" onkeyup="borderNormal(this.id);" maxlength="30" /></td> <td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp;<i class="fa fa-window-close remove_owner_dtl" id="my_owner_minus"  style="cursor: pointer; display: block;"></i> </td></tr>';
        $("#owner_dtl_append").append(appendData);
    }

    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
    });

    var ti = <?= $ti; ?>;

    function trade_item_append_fun() 
    {
        ti++;
        var tappendData = '<tr><td><select id="tade_item' + ti + '" name="tade_item[]" required="required" class="form-control tade_item"  onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if ($tradeitemlist) {
                                                                                                                                                                                                                    foreach ($tradeitemlist as $valit) { ?><option value="<?php echo $valit['id']; ?>" ><?php echo $valit['trade_item']; ?></option><?php }
                                                                                                                                                                                                                                                                                                                                            } ?></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;display:block;" onclick="trade_item_append_fun();"></i>&nbsp;<i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i></td></tr>';
        $("#trade_item_append").append(tappendData);
    }
    $("#trade_item_append").on('click', '.remove_trade_item', function(e) {
        $(this).closest("tr").remove();
    });

    function show_district(str, cnt) {

        $.ajax({
            type: "POST",
            url: '<?php echo base_url("tradeapplylicence/getdistrictname"); ?>',
            dataType: "json",
            data: {
                "state_name": str
            },

            success: function(data) {

                var option = "";
                jQuery(data).each(function(i, item) {
                    option += '<option value="' + item.name + '">' + item.name + '</option>';

                });
                $("#district" + cnt).html(option);

            }

        });

    }

    function myFunction() {
        var mode = document.getElementById("payment_mode").value;
        if (mode == 'CASH') {
            $('#chqno').hide();
            $('#chqbank').hide();
        } else {
            $('#chqno').show();
            $('#chqbank').show();
        }
    }

    $("#doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['pdf']) == -1) {
            $("#doc_path").val("");
            alert('invalid Document type');
        }
        if (input.files[0].size > 1048576) { // 1MD = 1048576
            $("#doc_path").val("");
            alert("Try to upload file less than 1MB!");
        }
    });


    <?php
    # Remove Tobbaco from option in case of non-tobbaco license
    if ($tobacco_status == 0) {
    ?>
        $('.tade_item  option[value="185"]').remove();
    <?php
    }
    ?>
    function read_only_remove(type,block_name)
    {
        var ch = document.getElementById(type).checked;
        // alert(type);
        if( ch==true)
        {
           var dd = $("#"+ block_name).find("select, textarea, input").attr('readonly',false);
        //    console.log(dd);
           $("#"+ block_name).find("select").attr('disabled',false);
           $("#"+ block_name).find("input:radio").attr('disabled',false);
           
        }
        else
        {
            // console.log($("#"+ block_name).find("select, textarea, input"));
            $("#"+ block_name ).find("select, textarea, input").attr('readonly',true);
            $("#"+ block_name).find("select").attr('disabled',true);
            $("#"+ block_name).find("input:radio").attr('disabled',true);
        }
        
    }
    function checkFileExtension() {
        fileName = $('#suport_doc').val();
        extension = fileName.split('.').pop();
        if(!~jQuery.inArray(extension.toLowerCase(), ["pdf","jpeg","jpg","png"]))
        {
            alert("Evter Valide Document");
            $('#suport_doc').val("");
        }
    };

    function showHidBlock(id)
    {
        var displays=(document.getElementById(id).style.display);
        if(displays!='none')
        {
            document.getElementById(id).style.display="none";
        }
        else{
            document.getElementById(id).style.display="block";
        }
    }
</script>