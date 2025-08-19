<?=$this->include("layout_mobi/header");?>


<!--CONTENT CONTAINER-->
	<div id="content-container">
		<!--Page content-->
		<div id="page-content">
			<form  id="myForm" name="myForm" method="post">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><b> Objection - Field Survey </b></h3>
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
                                                
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=($objection_dtl["according_assessment"]=="t")?"Yes":"No";?></td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($objection_dtl["according_assessment"]==$objection_dtl["according_applicant"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td>
													<select class="form-control" name="is_water_harvesting">
														<option value="t" <?=($objection_dtl["according_applicant"]=="t")?"selected":null;?>>Yes</option>
														<option value="f" <?=($objection_dtl["according_applicant"]=="f")?"selected":null;?>>No</option>
													</select>
												</td>
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
											
                                            $according_objection=array_column($according_objection, "id")[0];
                                            ?>
                                            <tr>
                                                
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=$according_assessment;?></td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td>
													<select class="form-control" name="road_type_mstr_id">
														<?php
														foreach($roadTypeList as $row)
														{
															?>
															<option value="<?=$row["id"];?>" <?=($according_objection==$row["id"])?"selected":null;?>><?=$row["road_type"];?></option>
															<?php
														}
														?>
													</select>
												</td>
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
                                            $according_objection=array_column($according_objection, "id")[0];
                                            ?>
                                            <tr>
                                                
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=$according_assessment;?></td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td>
													<select class="form-control" name="prop_type_mstr_id">
														<?php
														foreach($propertyTypeList as $row)
														{
															?>
															<option value="<?=$row["id"];?>" <?=($according_objection==$row["id"])?"selected":null;?>><?=$row["property_type"];?></option>
															<?php
														}
														?>
													</select>
												</td>
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
                                                
                                                <td><?=$objection_dtl["type"];?></td>
                                                <td><?=$according_assessment;?> (Decimal)</td>
                                                <td><img src="<?=base_url('public/assets/img');?>/<?=($according_assessment==$according_objection)?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                <td>
													<input type="number" class="form-control" name="area_of_plot" value="<?=$according_objection;?>" />
												</td>
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
                                                <td>
													<select class="form-control" name="<?=($objection_dtl["objection_type_id"]==6)?"is_mobile_tower":"is_hoarding_board";?>">
														<option value="t" <?=($objection_dtl["according_applicant"]=="t")?"selected":null;?>>Yes</option>
														<option value="f" <?=($objection_dtl["according_applicant"]=="f")?"selected":null;?>>No</option>
													</select>
													<?php 
                                                    if($according_objection=="t")
                                                    {
                                                        echo "Area: $objection_dtl[applicant_area] Sq. feet, Date: $objection_dtl[applicant_date]";
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
                                                        <td>Check</td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["usage_type"]==$objection_floor["usage_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["occupancy_name"]==$objection_floor["occupancy_name"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["construction_type"]==$objection_floor["construction_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["builtup_area"]==$objection_floor["builtup_area"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["carpet_area"]==$objection_floor["carpet_area"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["date_from"]==$objection_floor["date_from"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Objection</td>
                                                        <td>
															<select class="form-control" name="<?=$floor_id;?>_usage_type_mstr_id">
															<?php
															// Id objection applied in this particular parameter then you can change otherwise not/option disabled
															$modify=($assessed_floor["usage_type"]==$objection_floor["usage_type"])?"disabled":null;
															foreach($usageTypeList as $row)
															{
																?>
																<option value="<?=$row["id"];?>" <?=($objection_floor["usage_type_mstr_id"]==$row["id"])?"selected":$modify;?>><?=$row["usage_type"];?></option>
																<?php
															}
															?>
															</select>
														</td>
                                                        <td>
															<select class="form-control" name="<?=$floor_id;?>_occupancy_type_mstr_id">
															<?php
															$modify=($assessed_floor["occupancy_name"]==$objection_floor["occupancy_name"])?"disabled":null;
															foreach($occupancyTypeList as $row)
															{
																?>
																<option value="<?=$row["id"];?>" <?=($objection_floor["occupancy_type_mstr_id"]==$row["id"])?"selected":$modify;?>><?=$row["occupancy_name"];?></option>
																<?php
															}
															?>
															</select>
														</td>
                                                        <td>
															<select class="form-control" name="<?=$floor_id;?>_const_type_mstr_id">
															<?php
															$modify=($assessed_floor["const_type_mstr_id"]==$objection_floor["const_type_mstr_id"])?"disabled":null;
															foreach($constTypeList as $row)
															{
																?>
																<option value="<?=$row["id"];?>" <?=($objection_floor["const_type_mstr_id"]==$row["id"])?"selected":$modify;?>><?=$row["construction_type"];?></option>
																<?php
															}
															?>
															</select>
														</td>
                                                        <td>
															<?php
															$modify=($assessed_floor["builtup_area"]==$objection_floor["builtup_area"])?"readonly":null;
															?>
															<input type="number" class="form-control" name="<?=$floor_id;?>_builtup_area" value="<?=$objection_floor['builtup_area'];?>" <?=$modify;?> />
														</td>
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

                        <div class="row text-center">
							<input type="submit" name="btn_submit" value="Proceed to survey" style="width: 140px" class="btn btn-success" />
						</div>
                    </div>
				</div>
			</form>
		</div>
	</div>


<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<!-- <script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script> -->
<script>

$('.form-control').each(function() {
  console.log(this.name);
  $(this).attr("required", true);
});

</script>