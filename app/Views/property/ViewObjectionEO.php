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
                <li class="active">View Objection</li>
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

                        <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                            <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;">
                                Your applied objection no. is 
                                    <span style="color: #ff6a00"><?=$objection["objection_no"];?></span>. 
                                    You can use this objection no. for future reference.
                            </span>
                            <br>
                            <br>
                            <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                                Current Status : <span style="color:#009900"> <?=$objection_status;?></span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <label class="col-md-3">Hoding No </label>
                            <div class="col-md-3 text-bold pad-btm">
                            <?=$new_holding_no;?>
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
                
                <!-- 
                1	Typographical Error
                2	Rainwater Harvesting
                3	Road Width
                4	Property Type
                5	Area of Plot
                6	Mobile Tower
                7	Hoarding Board
                8	Other
                9	Floor Detail  -->
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">View Objection</h3>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-danger text-bold">
                                <u>Objection Details</u>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>			
                                            <th>#</th>
                                            <th>Particular</th>
                                            <th>Self-Assessed</th>
                                            <th>Check</th>
                                            <th>Objection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    foreach($objection_detail as $objection_dtl)
                                    {
                                        # Rainwater Harvesting
                                        if($objection_dtl["objection_type_id"]==2)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=($objection_dtl["according_assessment"]=="t")?"Yes":"No";?></td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($objection_dtl["according_assessment"]==$objection_dtl["according_applicant"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td><?=($objection_dtl["according_applicant"]=="t")?"Yes":"No";?></td>
                                            </tr>
                                            <?php
                                        }
                                        # Road Width
                                        if($objection_dtl["objection_type_id"]==3)
                                        {
                                            $road_type_id=$objection_dtl['according_assessment'];
                                            $according_assessment = array_filter($roadTypeList, function ($var) use ($road_type_id) {
                                                return ($var['id'] == $road_type_id);
                                            });
                                            $according_assessment=array_column($according_assessment, "road_type")[0];
                                            

                                            $road_type_id=$objection_dtl['according_applicant'];
                                            $according_objection = array_filter($roadTypeList, function ($var) use ($road_type_id) {
                                                return ($var['id'] == $road_type_id);
                                            });
                                            $according_objection=array_column($according_objection, "road_type")[0];
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=$according_assessment;?></td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td><?=$according_objection;?></td>
                                            </tr>
                                            <?php
                                        }

                                        # Property Type
                                        if($objection_dtl["objection_type_id"]==4)
                                        {
                                            $prop_type_mstr_id=$objection_dtl['according_assessment'];
                                            $according_assessment = array_filter($propertyTypeList, function ($var) use ($prop_type_mstr_id) {
                                                return ($var['id'] == $prop_type_mstr_id);
                                            });
                                            $according_assessment=array_column($according_assessment, "property_type")[0];
                                            

                                            $prop_type_mstr_id=$objection_dtl['according_applicant'];
                                            $according_objection = array_filter($propertyTypeList, function ($var) use ($prop_type_mstr_id) {
                                                return ($var['id'] == $prop_type_mstr_id);
                                            });
                                            $according_objection=array_column($according_objection, "property_type")[0];
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=$according_assessment;?></td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td><?=$according_objection;?></td>
                                            </tr>
                                            <?php
                                        }

                                        # Area of Plot
                                        if($objection_dtl["objection_type_id"]==5)
                                        {
                                            $according_assessment=$objection_dtl['according_assessment'];
                                            $according_objection=$objection_dtl['according_applicant'];
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=$according_assessment;?> (Decimal)</td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td><?=$according_objection;?> (Decimal)</td>
                                            </tr>
                                            <?php
                                        }

                                        # Mobile Tower
                                        if($objection_dtl["objection_type_id"]==6 || $objection_dtl["objection_type_id"]==7)
                                        {
                                            $according_assessment=$objection_dtl['according_assessment'];
                                            $according_objection=$objection_dtl['according_applicant'];
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?php 
                                                    if($according_assessment=="t")
                                                    {
                                                        echo "Yes ($objection_dtl[assess_area]) (Installation Date: $objection_dtl[assess_date])";
                                                    }
                                                    else
                                                        echo "No";
                                                    ?>
                                                </td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td><?php 
                                                    if($according_objection=="t")
                                                    {
                                                        echo "Yes ($objection_dtl[applicant_area]) (Installation Date: $objection_dtl[applicant_date])";
                                                    }
                                                    else
                                                        echo "No";
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        
                        if(!empty($objection_floor_detail))
                        {
                            ?>
                            <div class="row">
                                <div class="col-sm-12 text-danger text-bold">
                                    <u>Floor Details</u>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-sm">
                                            <thead class="bg-trans-dark text-dark">
                                                <tr>
                                                    <th></th>
                                                    <th>Floor No. </th>
                                                    <th>Usage Type</th>
                                                    <th>Occupancy Type</th>
                                                    <th>Construction Type</th>
                                                    <th>Built Up Area (in Sq. Ft.)</th>
                                                    <th>Carpet Area (in Sq. Ft.)</th>
                                                    <th>Date of Completion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                foreach ($assessment_floor_detail as $assessed_floor)
                                                {
                                                    // $verified_floor = array_filter($verification_floor_data, function ($var) {
                                                    //     return ($var['saf_floor_dtl_id'] == $assessed_floor['id']);
                                                    // });
                                                    $floor_id=$assessed_floor['prop_floor_dtl_id'];
                                                    $objection_floor = array_filter($objection_floor_detail, function ($var) use ($floor_id) {
                                                        return ($var['prop_floor_dtl_id'] == $floor_id);
                                                    });
                                                    
                                                    if(empty($objection_floor))
                                                    {
                                                        // making blank array for preventing from error (undefined index)
                                                        $objection_floor=[
                                                                    "usage_type"=> NULL,
                                                                    "occupancy_name"=> NULL,
                                                                    "construction_type"=> NULL,
                                                                    "builtup_area"=> NULL,
                                                                    "carpet_area"=> NULL,
                                                                    "date_from"=> NULL,
                                                                ];
                                                    }
                                                    else
                                                    {
                                                        $objection_floor=array_merge($objection_floor)[0];
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td>Self Assessed</td>
                                                        <td rowspan="3" style="vertical-align: middle; text-align: center;"><?=$assessed_floor['floor_name'];?></td>
                                                        <td><?=$assessed_floor['usage_type'];?></td>
                                                        <td><?=$assessed_floor['occupancy_name'];?></td>
                                                        <td><?=$assessed_floor['construction_type'];?></td>
                                                        <td><?=$assessed_floor['builtup_area'];?></td>
                                                        <td><?=$assessed_floor['carpet_area'];?></td>
                                                        <td><?=$assessed_floor['date_from'];?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Objection</td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["usage_type"]==$objection_floor["usage_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["occupancy_name"]==$objection_floor["occupancy_name"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["construction_type"]==$objection_floor["construction_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["builtup_area"]==$objection_floor["builtup_area"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["carpet_area"]==$objection_floor["carpet_area"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["date_from"]==$objection_floor["date_from"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Verification</td>
                                                        <td><?=$objection_floor['usage_type'];?></td>
                                                        <td><?=$objection_floor['occupancy_name'];?></td>
                                                        <td><?=$objection_floor['construction_type'];?></td>
                                                        <td><?=$objection_floor['builtup_area'];?></td>
                                                        <td><?=$objection_floor['carpet_area'];?></td>
                                                        <td><?=$objection_floor['date_from'];?></td>
                                                    </tr>
                                                <?php
                                                }
                                            
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                            <div class="row">
                                <div class="col-sm-12 text-danger text-bold">
                                    <u>Objection Documents</u>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        Objection Form
                                    </div>

                                    <div class="col-sm-3">
                                        <a href="javascript: void(0)" class="btn btn-primary btn-sm" onClick="window.open('<?=base_url();?>/getImageLink.php?path=<?=$objection["objection_form"];?>', 'newwindow', 'width=700, height=700'); return false;">View</a>
                                    </div>

                                    <div class="col-sm-3">
                                        Evidence Document
                                    </div>

                                    <div class="col-sm-3">
                                    <a href="javascript: void(0)" class="btn btn-primary btn-sm" onClick="window.open('<?=base_url();?>/getImageLink.php?path=<?=$objection["evidence_document"];?>', 'newwindow', 'width=700, height=700'); return false;">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php
            if($objection["level_status"]==4) // Pending at EO
            {
                ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> &nbsp; </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                
                                <div class="col-md-2">
                                    <input type="radio" name="status" class="status" id="id_forward" value="Forward" /> <label for="id_forward">Approve</label>
                                    <input type="radio" name="status" class="status" id="id_reject" value="Reject" /> <label for="id_reject">Reject</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <form method="POST" action="<?=base_url();?>/EO_SAF/view2/<?=md5($saf_dtl_id);?>" id="forward_form" style="display: none;">
                                <div class="form-group">
                                    <label class="col-md-2 text-bold">Remarks</label>
                                    <div class="col-md-10">
                                        <textarea id="remarks" name="remarks" class="form-control" placeholder="Please Enter Remark" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">&nbsp;&nbsp;&nbsp;</label>
                                    <div class="col-md-10" style="padding: 20px 20px 20px 10px;">
                                        
                                        <button type="submit" class="btn btn-success" id="btn_approved_submit" name="btn_approved_submit">Approve</button>
                                    </div>
                                </div>
                            </form>

                            <form method="POST" id="reject_form" style="display: none;">
                                <div class="form-group">
                                    <label class="col-md-2 text-bold">Reason</label>
                                    <div class="col-md-10">
                                        <textarea id="" name="eo_remarks" class="form-control" placeholder="Please Enter Reason" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">&nbsp;&nbsp;&nbsp;</label>
                                    <div class="col-md-10" style="padding: 20px 20px 20px 10px;">
                                        <button type="submit" class="btn btn-danger" id="btn_reject" name="btn_reject">Reject</button>
                                    </div>
                                </div>
                            </form>

                                
                        </div>
                    </div>
                </div>
                <?php
            }
            ?> 

            <?=$this->include('property/ObjectionRemarks');?>
    </div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script>
$(document).ready(function() {
    $(".status").click(function(){
        
        if(this.id=="id_forward")
        {
            $("#forward_form").show();
            $("#reject_form").hide();
        }
        else if(this.id=="id_reject")
        {
            $("#reject_form").show();
            $("#forward_form").hide();
        }
    });
});
</script>