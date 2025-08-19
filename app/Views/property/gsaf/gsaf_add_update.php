<?=$this->include('layout_vertical/header');?>

<style type="text/css">
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
            <li><a href="#">Government Self Assessment Form</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="form_saf_property" method="post" action="<?=base_url('Gsaf/add_update');?>">
            <input type="hidden" id="prop_type_mstr_id" name="prop_type_mstr_id" value="2" />
            <input type="hidden" id="prop_dtl_id" name="prop_dtl_id" value="<?=(isset($prop_dtl_id))?$prop_dtl_id:"";?>" />
            <?php
            if(isset($validation))
            {
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
                    <h3 class="panel-title">Government Building Self Assessment (GBSAF)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-4">Name of Building <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="building_colony_name" name="building_colony_name" class="form-control" value="<?=(isset($building_colony_name))?$building_colony_name:"";?>" maxlength="255"/>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Name of office operated by the Building<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <input type="text" id="office_name" name="office_name" class="form-control" value="<?=(isset($office_name))?$office_name:"";?>" maxlength="200"/>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4"> Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($wardList))
                                {
                                    foreach ($wardList as $ward)
                                    {
                                        ?>
                                        <option value="<?=$ward['id'];?>" <?=(isset($ward_mstr_id))?($ward['id']==$ward_mstr_id)?"selected":"":"";?>><?=$ward['ward_no'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4"> Holding No.(Previous holding no. if any) </label>
                        <div class="col-md-3 pad-btm">
                          <input type="text" id="holding_no" name="holding_no" class="form-control" value="<?=(isset($holding_no))?$holding_no:"";?>" onblur="SearchHolding()" maxlength="25"/>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4">Building Address <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <textarea type="text" id="building_colony_address" name="building_colony_address" class="form-control"><?=(isset($building_colony_address))?$building_colony_address:"";?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Govt. Building Usage Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="govt_building_type_mstr_id" name="govt_building_type_mstr_id" class="form-control" onchange="propTypeMstrChngFun();">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($govtBuildTypeList)){
                                    foreach ($govtBuildTypeList as $govtBuildType) {
                                ?>
                                <option value="<?=$govtBuildType['id'];?>" <?=(isset($govt_building_type_mstr_id))?($govtBuildType['id']==$govt_building_type_mstr_id)?"selected":"":"";?>><?=$govtBuildType['building_type'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-4">Property Usage Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="prop_usage_type_mstr_id" name="prop_usage_type_mstr_id" class="form-control" onchange="propTypeMstrChngFun();">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($propUsageTypeList)){
                                    foreach ($propUsageTypeList as $propUsageType) {
                                ?>
                                <option value="<?=$propUsageType['id'];?>" <?=(isset($prop_usage_type_mstr_id))?($propUsageType['id']==$prop_usage_type_mstr_id)?"selected":"":"";?>><?=$propUsageType['prop_usage_type'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Zone  <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="zone_mstr_id" name="zone_mstr_id" class="form-control">
                              <option value="">== SELECT ==</option>
                                <option value="1" <?=(isset($zone_mstr_id))?($zone_mstr_id=='1')?"selected":"":"";?>>Zone 1</option>
                                <option value="2" <?=(isset($zone_mstr_id))?($zone_mstr_id=='2')?"selected":"":"";?>>Zone 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4">Width of Road <span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            <select id="road_type_mstr_id" name="road_type_mstr_id" class="form-control">
                              <option value="">== SELECT ==</option>
                              <?php
                              if(isset($roadTypeList)){
                                  foreach ($roadTypeList as $roadType) {
                              ?>
                              <option value="<?=$roadType['id'];?>" <?=(isset($road_type_mstr_id))?($roadType['id']==$road_type_mstr_id)?"selected":"":"";?>><?=$roadType['road_type'];?></option>
                              <?php
                                  }
                              }
                              ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-md-4">Area of plot (In Decimal) <span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            <input type="text" name="area_of_plot" id="area_of_plot" class="form-control" value="<?=(isset($area_of_plot))?$area_of_plot:"";?>" />
                        </div>
                    </div>


                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Details Of Authorized Person for the payment of Property Tax</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-4">Authorized Person for the payment of Property Tax<span class="text-danger">*</span></label>
                    </div>
                    <div class="row"><br>
                        <label class="col-md-2">Designation<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <input type="text" id="designation" name="designation" class="form-control" value="<?=(isset($designation))?$designation:"";?>" maxlength="150" />
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-2"> Address <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <textarea type="address" id="address" name="address" class="form-control"><?=(isset($address))?$address:"";?></textarea>
                        </div>
                    </div>
                </div>
            </div>

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Floor Details</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">

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
                                                <th>Upto Date (YYYY-MM) <br/><span class="text-xs">(Leave blank for current date)</span></th>
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
<script src="<?=base_url();?>/public/assets/otherJs/validation.js">
</script><script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
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

    var zf=1;
    function floor_dtl_append_fun(){
        zf++;
        var appendData = '<tr><td><select id="floor_mstr_id'+zf+'" name="floor_mstr_id[]" class="form-control floor_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($floorList)){ foreach ($floorList as $floor) { ?><option value="<?=$floor['id'];?>"><?=$floor['floor_name'];?></option><?php }} ?></select></td><td><select id="usage_type_mstr_id'+zf+'" name="usage_type_mstr_id[]" class="form-control usage_type_mstr_id" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($usageTypeList)){ foreach ($usageTypeList as $usageType) { ?><option value="<?=$usageType['id'];?>"><?=$usageType['usage_type'];?></option><?php }} ?></select></td><td><select id="occupancy_type_mstr_id'+zf+'" name="occupancy_type_mstr_id[]" class="form-control occupancy_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($occupancyTypeList)){ foreach ($occupancyTypeList as $occupancyType) {?><option value="<?=$occupancyType['id'];?>"><?=$occupancyType['occupancy_name'];?></option><?php }} ?></select></td><td><select id="const_type_mstr_id'+zf+'" name="const_type_mstr_id[]" class="form-control const_type_mstr_id" style="width: 100px;" onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if(isset($constTypeList)){ foreach ($constTypeList as $constType) { ?><option value="<?=$constType['id'];?>"><?=$constType['construction_type'];?></option><?php }} ?></select></td><td><input type="text" id="builtup_area'+zf+'" name="builtup_area[]" class="form-control builtup_area" placeholder="Built Up Area" value="" style="width: 100px;" onkeypress="return isNumDot(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="month" id="date_from'+zf+'" name="date_from[]" class="form-control date_from" value="" max="<?=date('Y-m')?>" style="width: 175px;" onchange="borderNormal(this.id);" /></td><td><input type="month" id="date_upto'+zf+'" name="date_upto[]" class="form-control date_upto" value="" max="<?=date('Y-m')?>" style="width: 175px;" onchange="borderNormal(this.id);" /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="floor_dtl_append_fun();"></i>  &nbsp; <i class="fa fa-window-close remove_floor_dtl" style="cursor: pointer;"></i></td></tr>';
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

    is_mobile_tower_fun();
    is_hoarding_board_fun();
    is_petrol_pump_fun();

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

    function SearchHolding() {
        if($("#ward_mstr_id").val()!='' && $("#holding_no").val()!='') {
            $.ajax({
                type:"POST",
                url: '<?=base_url();?>/Gsaf/ajaxPropDtlIdAddressDtlByHoldingWardNo',
                dataType: "json",
                data: {
                    'ward_mstr_id': $("#ward_mstr_id").val(),
                    'holding_no': $("#holding_no").val()
                },
                success:function(data){
                    if(data.response==true){
                        $("#prop_dtl_id").val(data.data.prop_dtl_id);
                        if ($("#building_colony_address").val()=="") {
                            $("#building_colony_address").val(data.data.prop_address);
                        }
                    } else {
                        $("#prop_dtl_id").val("");
                        $("#holding_no").val("");
                        $("#building_colony_address").val("");
                        modelInfo('Holding No. does not exist.');
                    }
                }
            });
        } else {
            $("#prop_dtl_id").val("");
        }
    }

$(document).ready(function() {
    jQuery.validator.addMethod("dateFormatYYYMMDD", function(value, element) {
        return this.optional(element) || /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/i.test(value);
    }, "Invalid format (YYYY-MM-DD)"); 

    jQuery.validator.addMethod("dateFormatYYYMM", function(value, element) {
        return this.optional(element) || /^([12]\d{3}-(0[1-9]|1[0-2]))+$/i.test(value);
    }, "Invalid format (YYYY-MM)"); 

    jQuery.validator.addMethod("alphaSpace", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
    }, "Letters only please (a-z, A-Z )");

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
            "building_colony_name": {
                required: true,
            },
            "office_name": {
                required: true,
            },
            "ward_mstr_id": {
                required: true,
            },
            "building_colony_address": {
                required: true,
                minlength: 5,
                maxlength: 255,
            },
            "govt_building_type_mstr_id": {
                required: true,
            },
            "prop_usage_type_mstr_id": {
                required: true,
            },
            "zone_mstr_id": {
                required: true,
            }, 
            "road_type_mstr_id": {
                required: true,
            },
            "area_of_plot":{
                required: true,
            },
            "designation": {
                required: true,
                alphaSpace: true,
            },
            "address": {
                required: true,
                minlength: 5,
                maxlength: 255,
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
                maxlength: 8,
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
                maxlength: 8,
            },
            "hoarding_installation_date": {
                required: function(element){
                    return $("#is_hoarding_board").val()==1;
                },
                dateFormatYYYMMDD : true
            },
            "is_petrol_pump": {
                required: function(element){
                    return $("#prop_type_mstr_id").val()!=4;
                }
            },
            "under_ground_area": {
                required: function(element){
                    return ($("#prop_type_mstr_id").val()!=4 && $("#is_petrol_pump").val()==1);
                },
                number: true,
                min_greater_zero: true,
                maxlength: 8,
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
        }
    });
});

</script>
