<?=$this->include('layout_vertical/header');?>
<style>
.error{
    color: red;
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
   
    
            <div id="page-head">
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->
                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
                <li><a href="#"><i class="demo-pli-home"></i></a></li>
                <li><a href="#">Property</a></li>
                <li><a href="<?=base_url("propDtl/full/$prop_dtl_id_MD5");?>">Property Details</a></li>
                <li class="active">Apply Objection</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
            </div>

            <div id="page-content">

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Property Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-3">Hoding No </label>
                            <div class="col-md-3 text-bold pad-btm">
                            <?=$holding_no;?>
                            </div>
                            
                            <label class="col-md-3">Ward No</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$ward_no;?>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Assessment Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                            <?=$assessment_type;?>
                            </div>
                            

                            <label class="col-md-3">Entry Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                            <?=($entry_type);?>
                            </div>
                        </div>

                    
                        <div class="row">
                            <label class="col-md-3">Order Date</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=date("d-m-Y", strtotime($created_on));?>
                            </div>
                            
                            <label class="col-md-3">Old Holding (If Any)</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$holding_no;?>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Property Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$property_type;?>
                            </div>
                            <label class="col-md-3">Ownership Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$ownership_type;?>
                            </div>
                        </div>
                        <div class="<?=($prop_type_mstr_id==3)?null:"hidden";?>">
                            <div class="row">
                                <label class="col-md-3">Appartment Name</label>
                                <div class="col-md-3 text-bold pad-btm">
                                    <?=$appartment_name;?>
                                </div>
                                <label class="col-md-3">Registry Date</label>
                                <div class="col-md-3 text-bold pad-btm">
                                    <?=$flat_registry_date;?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Road Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$road_type;?>
                            </div>

                            <label class="col-md-3">Holding Type</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=$holding_type;?>
                            </div>
                        </div>
                    
                        <div class="row">
                            <?php
                            if($ulb["ulb_mstr_id"]==1)
                            {
                                ?>
                                <label class="col-md-3">Rain Water Harvesting</label>
                                <div class="col-md-3 text-bold">
                                <?=($is_water_harvesting=="t")?"Yes":"No";?>
                                </div>
                                <?php
                            }
                            ?>
                            <label class="col-md-3">Zone</label>
                            <div class="col-md-3 text-bold">
                                <?=($zone_mstr_id==1)?"Zone 1":"Zone 2";?>
                            </div>
                        </div>
                    
                    </div>
                </div>


                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Owner Details</h3>
                    </div>
                    <div class="panel-body">
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
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody id="owner_dtl_append">
                                        <?php
                                        if(isset($prop_owner_detail))
                                        {
                                            foreach ($prop_owner_detail as $owner_detail)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=$owner_detail['owner_name'];?></td>
                                                    <td><?=$owner_detail['guardian_name'];?></td>
                                                    <td><?=$owner_detail['relation_type'];?></td>
                                                    <td><?=$owner_detail['mobile_no'];?></td>
                                                    <td><?=$owner_detail['aadhar_no'];?></td>
                                                    <td><?=$owner_detail['pan_no'];?></td>
                                                    <td><?=$owner_detail['email'];?></td>
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
                
                
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Apply for objection</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" name="objectionForm" id="objectionForm" enctype="multipart/form-data">
                            <div class="form-group pad-ver">
                                <label class="col-md-2 control-label text-bold text-dark">Objection On</label>
                                <div class="col-md-4">
            
                                    <!-- Checkboxes 
                                    1	Typographical Error
                                    2	Rainwater Harvesting
                                    3	Road Width
                                    4	Property Type
                                    5	Area of Plot
                                    6	Mobile Tower
                                    7	Hoarding Board
                                    8	Other
                                    9	Floor Detail
                                    -->
                                    <?php
                                    foreach($objectionTypeList as $obj)
                                    {
                                        ?>
                                        <div class="checkbox">
                                            <input id="objection_type<?=$obj["id"];?>" name="objection_type_id[]" value="<?=$obj["id"];?>" class="magic-checkbox" type="checkbox" />
                                            <label for="objection_type<?=$obj["id"];?>"><?=$obj["type"];?></label>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="panel objection_type2_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6"> Objection On: Rainwater Harvesting </th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>As Per Assessment</td>
                                        <td>:</td>
                                        <td><?=($is_water_harvesting=="t")?"Yes":"No";?></td>
                                        <td>As Per Applicant</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" name="is_water_harvesting" id="is_water_harvesting">
                                                <option value="">Select</option>
                                                <option value="t">Yes</option>
                                                <option value="f">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="panel objection_type3_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6"> Objection On: Road Width</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>As Per Assessment</td>
                                        <td>:</td>
                                        <td><?=$road_type;?></td>
                                        <td>As Per Applicant</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" name="road_type_mstr_id" id="road_type_mstr_id">
                                                <option value="">Select</option>
                                                <?php
                                                foreach($roadTypeList as $road)
                                                {
                                                    ?>
                                                    <option value="<?=$road["id"];?>"><?=$road["road_type"];?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>


                            <div class="panel objection_type4_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6"> Objection On: Property Type</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>As Per Assessment</td>
                                        <td>:</td>
                                        <td><?=$property_type;?></td>
                                        <td>As Per Applicant</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" name="property_type_id" id="property_type_id">
                                                <option value="">Select</option>
                                                <?php
                                                foreach($propertyTypeList as $prop)
                                                {
                                                    ?>
                                                    <option value="<?=$prop["id"];?>"><?=$prop["property_type"];?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>

                            <div class="panel objection_type5_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6"> Objection On: Area of plot</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>As Per Assessment</td>
                                        <td>:</td>
                                        <td><?=$area_of_plot;?></td>
                                        <td>As Per Applicant</td>
                                        <td>:</td>
                                        <td>
                                            <input type="number" class="form-control" name="area_of_plot" id="area_of_plot"/>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>

                            <div class="panel objection_type6_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6"> Objection On: Mobile Tower </th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>As Per Assessment</td>
                                        <td>:</td>
                                        <td><?=($is_mobile_tower=="t")?"Yes":"No";?></td>
                                        <td>As Per Applicant</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" name="is_mobile_tower" id="is_mobile_tower">
                                                <option value="">Select</option>
                                                <option value="t">Yes</option>
                                                <option value="f">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="mobile_tower_tr" style="display: none;">
                                        <td>Total Area Covered by Mobile Tower</td>
                                        <td>:</td>
                                        <td><input type="number" name="tower_area" id="tower_area" class="form-control" /></td>
                                        <td>Date of Installation of Mobile Tower</td>
                                        <td>:</td>
                                        <td>
                                            <input type="date" class="form-control" name="tower_installation_date" id="tower_installation_date" />
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>


                            <div class="panel objection_type7_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6"> Objection On: Hording Board </th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>As Per Assessment</td>
                                        <td>:</td>
                                        <td><?=($is_hoarding_board=="t")?"Yes":"No";?></td>
                                        <td>As Per Applicant</td>
                                        <td>:</td>
                                        <td>
                                            <select class="form-control" name="is_hoarding_board" id="is_hoarding_board">
                                                <option value="">Select</option>
                                                <option value="t">Yes</option>
                                                <option value="f">No</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="hoarding_board_tr" style="display: none;">
                                        <td>Total Area Covered by Mobile Tower</td>
                                        <td>:</td>
                                        <td><input type="number" name="hoarding_area" id="hoarding_area" class="form-control" /></td>
                                        <td>Date of Installation of Mobile Tower</td>
                                        <td>:</td>
                                        <td>
                                            <input type="date" class="form-control" name="hoarding_installation_date" id="hoarding_installation_date" />
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="panel objection_type9_panel" style="display: none;">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="8">Objection On: Floor Details</th>
                                        </tr>
                                        <tr>
                                            <th>Particulars <p class="text text-xs text-bold">(As Per)</p></th>
                                            <th>Floor No</th>
                                            <th style="width: 150px;">Usage Type</th>
                                            <th>Occupancy Type</th>
                                            <th>Construction Type</th>
                                            <th>Built Up Area  (in Sq. Ft)</th>
                                            <th>From Date</th>
                                            <th>Upto Date <span class="text-xs">(Leave blank for current date)</span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="floor_dtl_append">
                                        <?php
                                        if(isset($prop_floor_details))
                                        {
                                            foreach ($prop_floor_details as $floor_details)
                                            {
                                                ?>
                                                <tr class="text text-info">
                                                    <td> Assessment </td>
                                                    <td>
                                                        <?=$floor_details['floor_name'];?>
                                                    </td>
                                                    <td>
                                                        <?=$floor_details['usage_type'];?>
                                                    </td>
                                                    <td>
                                                        <?=$floor_details['occupancy_name'];?>
                                                    </td>
                                                    <td>
                                                        <?=$floor_details['construction_type'];?>
                                                    </td>
                                                    <td>
                                                        <?=$floor_details['builtup_area'];?>
                                                    </td>
                                                    <td>
                                                        <?=date('Y-m', strtotime($floor_details['date_from']));?>
                                                    </td>
                                                    <td>
                                                        <?=date('Y-m', strtotime($floor_details['date_upto']));?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td> Applicant</td>
                                                    <td>
                                                        <select name="floor_mstr_id[]" id="" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach($floorList as $row)
                                                            {
                                                                ?>
                                                                <option value="<?=$row["id"];?>" <?=($row["id"]==$floor_details["floor_mstr_id"])?"selected":null;?>>
                                                                <?=$row["floor_name"];?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        
                                                    </td>
                                                    <td>
                                                        <select name="usage_type_mstr_id[]" id="" class="form-control" >
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach($usageTypeList as $row)
                                                            {
                                                                ?>
                                                                <option value="<?=$row["id"];?>" <?=($row["id"]==$floor_details["usage_type_mstr_id"])?"selected":null;?>>
                                                                <?=$row["usage_type"];?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="occupancy_type_mstr_id[]" id="" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach($occupancyTypeList as $row)
                                                            {
                                                                ?>
                                                                <option value="<?=$row["id"];?>" <?=($row["id"]==$floor_details["occupancy_type_mstr_id"])?"selected":null;?>>
                                                                <?=$row["occupancy_name"];?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        
                                                    </td>
                                                    <td>
                                                    <select name="const_type_mstr_id[]" id="" class="form-control">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach($constTypeList as $row)
                                                            {
                                                                ?>
                                                                <option value="<?=$row["id"];?>" <?=($row["id"]==$floor_details["const_type_mstr_id"])?"selected":null;?>>
                                                                <?=$row["construction_type"];?>
                                                                </option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        
                                                    </td>
                                                    <td>
                                                        <input type="number" value="<?=$floor_details['builtup_area'];?>" name="builtup_area[]" id="" class="form-control" />
                                                        
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="panel">
                                <table class="table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th colspan="6">Documents</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Objection Form</td>
                                        <td>:</td>
                                        <td><input type="file" name="objection_form" id="objection_form" accept=".pdf" /></td>
                                        <td>Evidence Document</td>
                                        <td>:</td>
                                        <td><input type="file" name="evidence_document" id="evidence_document" accept=".pdf" /></td>
                                    </tr>
                                    <tr>
                                        <td>Remarks</td>
                                        <td>:</td>
                                        <td><textarea class="form-control" name="remarks" id="remarks"></textarea></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text text-center">
                                <input type="submit" class="btn btn-primary" value="Submit" />
                            </div>
                        </form>
                    </div>
                </div>
              
            </div><!--End page-content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
    $("#objectionForm").validate({
        rules: {
			"is_water_harvesting": {
                required: function(element){
                    return $("#objection_type2").is(":checked");
                },
            },
            "road_type_mstr_id": {
                required: function(element){
                    return $("#objection_type3").is(":checked");
                },
            },
            "property_type_id": {
                required: function(element){
                    return $("#objection_type4").is(":checked");
                },
            },
            "area_of_plot": {
                required: function(element){
                    return $("#objection_type5").is(":checked");
                },
            },
            "is_mobile_tower":{
                required: function(element){
                    return $("#objection_type6").is(":checked");
                },
            },
            "tower_area":{
                required: function(element){
                    return $("#is_mobile_tower").val()=="t";
                },
                number: true,
            }, 
            "tower_installation_date":{
                required: function(element){
                    return $("#is_mobile_tower").val()=="t";
                }
            },
            "is_hoarding_board":{
                required: function(element){
                    return $("#objection_type7").is(":checked");
                },
            },
            "hoarding_area":{
                required: function(element){
                    return $("#is_hoarding_board").val()=="t";
                },
                number: true,
            }, 
            "hoarding_installation_date":{
                required: function(element){
                    return $("#is_hoarding_board").val()=="t";
                }
            },

            "floor_mstr_id[]":{
                required: function(element){
                    return $("#objection_type9").is(":checked");
                },
            },
            "usage_type_mstr_id[]":{
                required: function(element){
                    return $("#objection_type9").is(":checked");
                },
            },
            "occupancy_type_mstr_id[]":{
                required: function(element){
                    return $("#objection_type9").is(":checked");
                },
            },
			"const_type_mstr_id[]":{
                required: function(element){
                    return $("#objection_type9").is(":checked");
                },
            },
            "builtup_area[]":{
                required: function(element){
                    return $("#objection_type9").is(":checked");
                },
            },
            "objection_form":{
                required: true,
            },
            "evidence_document":{
                required: true,
            },
            "remarks":{
                required: true,
            },
            
		},
		messages: {
			"is_water_harvesting": "Choose Water Harvesting",
		},
		submitHandler: function(form)
		{
            $(".magic-checkbox").each(function(e) {
                //If cheked at-least one checkbox then submit form
                if($(this).is(":checked"))
                form.submit();
            });
            return false;
		}
	});

    $(document).ready(function()
	{
		$(".magic-checkbox").click(function()
		{
            var panelid = $(this).prop('id') + "_panel";
			if($(this).is(':checked'))
            {
                console.log(panelid);
                $("."+ panelid).show();
            }
            else
            {
                $("."+ panelid).hide();
            }
		});

        $("#is_mobile_tower").change(function()
		{
            if($("#is_mobile_tower").val()=="t")
            $("#mobile_tower_tr").show();
            else
            $("#mobile_tower_tr").hide();
        });

        $("#is_hoarding_board").change(function()
		{
            if($("#is_hoarding_board").val()=="t")
            $("#hoarding_board_tr").show();
            else
            $("#hoarding_board_tr").hide();
        });

	});

    
</script>