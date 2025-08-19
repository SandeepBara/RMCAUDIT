<?=$this->include('layout_vertical/header');?>
<style>
.error {
    color: red;
}
.boldIn{
    font-weight: bold; font-size: 14px; color: #1b0079
}

/* Set the size of the div element that contains the map */
#geo_tagging_map {
  height: 400px;
  /* The height is 400 pixels */
  width: 100%;
  /* The width is the width of the web page */
}
</style>


<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li class="active"><a href="#">SAF</a></li>
            <li class="active"><a href="<?=base_url();?>/safdtl/full/<?=md5($verification_data['saf_dtl_id']);?>">SAF Detail</a></li>
            <li><a href="#">TC Verification</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="backOfficeUpdate" name="backOfficeUpdate">
            <div class="row">
                <div class="col-sm-12">					
                    <!--Default Tabs (Right Aligned)-->
                    <div class="tab-base">
                        <!--Nav tabs-->
                        <ul class="nav nav-tabs tabs-left">
                            <li class="active">
                                <a data-toggle="tab" href="#rgt-tab-1" aria-expanded="true">Verification </a>
                            </li>
                        </ul>
                        <!--Tabs Content-->
                        <div class="tab-content" id="printableArea">
                            <div id="rgt-tab-1" class="tab-pane fade active in">
                                <!-- TC Verification tab start -->
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            Tax Collector Verification Details
                                            <button class="btn btn-default pull-right" onclick="print()"><i class="fa fa-print"></i> Print</button>
                                        </h3>
                                        
                                    </div>
                                    <div class="panel-body">
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">Name of Tax Collector </label><b><?=$verification_data['emp_name'];?> (<?=$verification_data['verified_by'];?>)</b>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">Date of Verification </label><b><?=$verification_data['created_on'];?></b>
											</div>
										</div>
									</div>

									<div class="panel-body">
										<div class="row">
											<div class="col-sm-12 text-danger text-bold">
												<u>Basic Details</u>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">SAF No</label><label class="col-sm-3"><b><?=$assessment_data['saf_no'];?></b></label>
												<label class="col-sm-3">Applied Date</label><label class="col-sm-3"><b><?=$assessment_data['apply_date'];?></b></label>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">Application Type</label><label class="col-sm-3"><b><?=$assessment_data['assessment_type'];?></b></label>
												<label class="col-sm-3">Property Transfer(%)</label><label class="col-sm-3"><b><?=$verification_data['percentage_of_property_transfer'];?></b></label>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">Ward No.</label><label class="col-sm-3"><b><?=$assessment_data['ward_no'];?></b></label>
												<label class="col-sm-3">Holding No.</label><label class="col-sm-3"><b><?=$assessment_data['holding_no'];?></b></label>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">Ownership Type</label><label class="col-sm-3"><b><?=$assessment_data['ownership_type'];?></b></label>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-12">
												<label class="col-sm-3">Owner(s) Name</label>
												<div class="col-sm-9 text-bold pad-btm">
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>Name</th>
																<th>Guardian Name</th>
																<th>Relation</th>
																<th>Mobile No</th>
															</tr>
														</thead>
														<tbody>
															<?php
															foreach($saf_owner_detail as $owner)
															{
																?>  
																<tr>
																	<td><?=$owner["owner_name"];?></td>
																	<td><?=$owner["guardian_name"];?></td>
																	<td><?=$owner["relation_type"];?></td>
																	<td><?=$owner["mobile_no"];?></td>
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
                                    
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12 text-danger text-bold">
                                                <u>Verified Details</u>
                                            </div>
                                            <div class="col-sm-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>			
                                                            <th>#</th>
                                                            <th>Particular</th>
                                                            <th>Self-Assessed</th>
                                                            <th>Check</th>
                                                            <th>Verification</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Ward No</td>
                                                            <td><?=$assessment_data["ward_no"];?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["ward_no"]==$verification_data["ward_no"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=$verification_data["ward_no"];?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Property Type</td>
                                                            <td><?=$assessment_data["property_type"];?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["property_type"]==$verification_data["property_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=$verification_data["property_type"];?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Area of Plot</td>
                                                            <td><?=$assessment_data["area_of_plot"];?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["area_of_plot"]==$verification_data["area_of_plot"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=$verification_data["area_of_plot"];?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Street Type</td>
                                                            <td><?=$assessment_data["road_type"];?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["road_type"]==$verification_data["road_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=$verification_data["road_type"];?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>No. of floors</td>
                                                            <td>N/A</td>
                                                            <td></td>
                                                            <td>N/A</td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>Mobile Tower(s) ?</td>
                                                            <td><?=($assessment_data["is_mobile_tower"]=="t")?"Yes":"No";?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["is_mobile_tower"]==$verification_data["is_mobile_tower"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=($verification_data["is_mobile_tower"]=="t")?"Yes":"No";?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td>Hoarding Board(s) ?</td>
                                                            <td><?=($assessment_data["is_hoarding_board"]=="t")?"Yes":"No";?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["is_hoarding_board"]==$verification_data["is_hoarding_board"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=($verification_data["is_hoarding_board"]=="t")?"Yes":"No";?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>8</td>
                                                            <td>Is Petrol Pump?</td>
                                                            <td><?=($assessment_data["is_petrol_pump"]=="t")?"Yes":"No";?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["is_petrol_pump"]==$verification_data["is_petrol_pump"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=($verification_data["is_petrol_pump"]=="t")?"Yes":"No";?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>9</td>
                                                            <td>Water Harvesting Provision ?</td>
                                                            <td><?=($assessment_data["is_water_harvesting"]=="t")?"Yes":"No";?></td>
                                                            <td><img src="<?=base_url('public/assets/img');?>/<?=($assessment_data["is_water_harvesting"]==$verification_data["is_water_harvesting"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                            <td><?=($verification_data["is_water_harvesting"]=="t")?"Yes":"No";?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                       
                                        <?php
                                        // not vacant land
                                        if($verification_data['prop_type_mstr_id']!=4)
                                        {
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-12 text-danger text-bold">
                                                    <u>Floor Verified Details</u>
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
                                                            if (isset($saf_floor_data))
                                                            {
                                                                foreach ($saf_floor_data as $assessed_floor)
                                                                {
                                                                    // $verified_floor = array_filter($verification_floor_data, function ($var) {
                                                                    //     return ($var['saf_floor_dtl_id'] == $assessed_floor['id']);
                                                                    // });
                                                                    $floor_id=$assessed_floor['id'];
                                                                    $verified_floor = array_filter($verification_floor_data, function ($var) use ($floor_id) {
                                                                        return ($var['saf_floor_dtl_id'] == $floor_id);
                                                                    });
                                                                    
                                                                    if(empty($verified_floor))
                                                                    {
                                                                        // making blank array for preventing from error (undefined index)
                                                                        $verified_floor=[
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
                                                                        $verified_floor=array_merge($verified_floor)[0];
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
                                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["usage_type"]==$verified_floor["usage_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["occupancy_name"]==$verified_floor["occupancy_name"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["construction_type"]==$verified_floor["construction_type"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["builtup_area"]==$verified_floor["builtup_area"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["carpet_area"]==$verified_floor["carpet_area"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                        <td><img src="<?=base_url('public/assets/img');?>/<?=($assessed_floor["date_from"]==$verified_floor["date_from"])?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Verification</td>
                                                                        <td><?=$verified_floor['usage_type'];?></td>
                                                                        <td><?=$verified_floor['occupancy_name'];?></td>
                                                                        <td><?=$verified_floor['construction_type'];?></td>
                                                                        <td><?=$verified_floor['builtup_area'];?></td>
                                                                        <td><?=$verified_floor['carpet_area'];?></td>
                                                                        <td><?=$verified_floor['date_from'];?></td>
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
                                            <?php 

                                            //print_var($ExtraFloorAddedByTC);
                                            if(isset($ExtraFloorAddedByTC) && !empty($ExtraFloorAddedByTC))
                                            {
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-12 text-danger text-bold">
                                                        <u> Extra Floor Added by Tax Collector </u>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered text-sm">
                                                                <thead class="bg-trans-dark text-dark">
                                                                    <tr>
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
                                                                foreach($ExtraFloorAddedByTC as $new_floor)
                                                                {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?=$new_floor['floor_name'];?></td>
                                                                        <td><?=$new_floor['usage_type'];?></td>
                                                                        <td><?=$new_floor['occupancy_name'];?></td>
                                                                        <td><?=$new_floor['construction_type'];?></td>
                                                                        <td><?=$new_floor['builtup_area'];?></td>
                                                                        <td><?=$new_floor['carpet_area'];?></td>
                                                                        <td><?=$new_floor['date_from'];?></td>
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
                                        }


                                        if(isset($safGeoTaggingDtl, $safGeoTaggingDtl))
                                        {
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-12 text-danger text-bold">
                                                    <u>Geo Tagging</u>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered text-sm">
                                                            <thead class="bg-trans-dark text-dark">
                                                                <tr>
                                                                    <th>Location</th>
                                                                    <th>Image</th>
                                                                    <th>Latitude</th>
                                                                    <th>Longitude</th>
                                                                    <th>View image</th>
                                                                    <th>View on google map</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            foreach($safGeoTaggingDtl as $geoTaggingDtl)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td><?=$geoTaggingDtl['direction_type'];?></td>
                                                                    <td>
                                                                        <!-- <img src='<?=base_url();?>/writable/uploads/<?=$geoTaggingDtl['image_path'];?>' class='img-lg' /> -->
                                                                        <img src='<?=base_url();?>/getImageLink.php?path=<?=$geoTaggingDtl['image_path'];?>' class='img-lg' />
                                                                    </td>
                                                                    <td><?=$geoTaggingDtl['latitude'];?></td>
                                                                    <td><?=$geoTaggingDtl['longitude'];?></td>
                                                                    <td><a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$geoTaggingDtl['image_path'];?>" class="btn btn-primary btn-sm">View image</a></td>
                                                                    <td><button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger" onclick="PopupMap('<?=$geoTaggingDtl['latitude'];?>', '<?=$geoTaggingDtl['longitude'];?>');"> View on google map </button></td>
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
                                    </div>
                                </div>
                                <!-- TC Verification tab start End  -->
                            </div>

                           
                        </div>
                    </div>
                    <!--End Default Tabs (Right Aligned)-->
                </div>
            </div> 
        </form>
    </div><!--End page content-->
    <?php if ($verification_data['verified_by']=='ULB TC') { ?>
    <div id="page-content"><!--Page content-->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Quarterly Tax Details</h3>
            </div>
            <div class="panel-body">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">As Per Field Verification :</h3>
                    </div>
                    <div class="panel-body pad-no">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm mar-no">
                                <thead class="bg-gray">
                                    <tr>
                                        <th>#</th>
                                        <th>Effect From</th>
                                        <th>ARV/CV</th>
                                        <th>Holding Tax</th>
                                        <th>Water Tax</th>
                                        <th>Education Cess</th>
                                        <th>Health Cess</th>
                                        <th>Latrine Tax</th>
                                        <th>RWH Penalty</th>
                                        <th>Quarterly Tax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                if(isset($newSafTaxDtl)) {
                                    $i = 0;
                                    foreach ($newSafTaxDtl as $key => $list) {
                                        $holding_tax = $list['holding_tax'];
                                        $water_tax = 0;
                                        $education_cess = 0;
                                        $health_cess = 0;
                                        $latrine_tax = 0;
                                        $additional_tax = $list['additional_tax'];
                                        if ($list["rule_type"]=="OLD_RULE") {
                                            $holding_tax = $list['holding_tax'];
                                            $water_tax = $list['water_tax'];
                                            $education_cess = $list['education_cess'];
                                            $health_cess = $list['health_cess'];
                                            $latrine_tax = $list['latrine_tax'];
                                            $additional_tax = $list['additional_tax'];
                                        }
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td>Quarter : <span class="text-semibold text-purple"><?=$list['qtr'];?></span>, Financial Year : <span class="text-semibold text-purple"><?=$list['fyear'];?></span></td>
                                        <td><?=number_format($list['arv'], 2);?></td>
                                        <td><?=number_format($holding_tax, 2);?></td>
                                        <td><?=number_format($water_tax, 2);?></td>
                                        <td><?=number_format($education_cess, 2);?></td>
                                        <td><?=number_format($health_cess, 2);?></td>
                                        <td><?=number_format($latrine_tax, 2);?></td>
                                        <td><?=number_format($additional_tax, 2);?></td>
                                        <td><?=number_format($holding_tax+$water_tax+$education_cess+$health_cess+$latrine_tax+$additional_tax, 2);?></td>
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

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">As Per Self-Assessment :</h3>
                    </div>
                    <div class="panel-body pad-no">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm mar-no">
                                <thead class="bg-gray">
                                    <tr>
                                        <th>#</th>
                                        <th>Effect From</th>
                                        <th>ARV/CV</th>
                                        <th>Holding Tax</th>
                                        <th>Water Tax</th>
                                        <th>Education Cess</th>
                                        <th>Health Cess</th>
                                        <th>Latrine Tax</th>
                                        <th>RWH Penalty</th>
                                        <th>Quarterly Tax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                if(isset($safTaxDtl)) {
                                    $i = 0;
                                    foreach ($safTaxDtl as $key => $list) {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td>Quarter : <span class="text-semibold text-purple"><?=$list['qtr'];?></span>, Financial Year : <span class="text-semibold text-purple"><?=$list['fyear'];?></span></td>
                                        <td><?=number_format($list['arv'], 2);?></td>
                                        <td><?=number_format($list['holding_tax'], 2);?></td>
                                        <td><?=number_format($list['water_tax'], 2);?></td>
                                        <td><?=number_format($list['education_cess'], 2);?></td>
                                        <td><?=number_format($list['health_cess'], 2);?></td>
                                        <td><?=number_format($list['latrine_tax'], 2);?></td>
                                        <td><?=number_format($list['additional_tax'], 2);?></td>
                                        <td><?=number_format($list['holding_tax']+$list['water_tax']+$list['education_cess']+$list['health_cess']+$list['latrine_tax']+$list['additional_tax'], 2);?></td>
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

                <div class="panel panel-bordered panel-dark mar-no">
                    <div class="panel-heading">
                        <h3 class="panel-title">Differences In :</h3>
                    </div>
                    <div class="panel-body pad-no">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm mar-no">
                                <thead class="bg-gray">
                                    <tr>
                                        <th><span class="text-danger">#</span></th>
                                        <th><span class="text-danger">Effect From</span></th>
                                        <th><span class="text-danger">ARV/CV</span></th>
                                        <th><span class="text-danger">Holding Tax</span></th>
                                        <th><span class="text-danger">Water Tax</span></th>
                                        <th><span class="text-danger">Education Cess</span></th>
                                        <th><span class="text-danger">Health Cess</span></th>
                                        <th><span class="text-danger">Latrine Tax</span></th>
                                        <th><span class="text-danger">RWH Penalty</span></th>
                                        <th><span class="text-danger">Quarterly Tax</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                if(isset($diffTax)) {
                                    $i = 0;
                                    if(!empty($diffTax)) {
                                        foreach ($diffTax as $key => $list) {
                                ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td>Quarter : <span class="text-semibold text-purple"><?=$list['qtr'];?></span>, Financial Year : <span class="text-semibold text-purple"><?=$list['fyear'];?></span></td>
                                            <td><?=number_format($list['arv'], 2);?></td>
                                            <td><?=number_format($list['holding_tax'], 2);?></td>
                                            <td><?=number_format($list['water_tax'], 2);?></td>
                                            <td><?=number_format($list['education_cess'], 2);?></td>
                                            <td><?=number_format($list['health_cess'], 2);?></td>
                                            <td><?=number_format($list['latrine_tax'], 2);?></td>
                                            <td><?=number_format($list['additional_tax'], 2);?></td>
                                            <td><?=number_format($list['holding_tax']+$list['water_tax']+$list['education_cess']+$list['health_cess']+$list['latrine_tax']+$list['additional_tax'], 2);?></td>
                                        </tr>
                                <?php
                                        } 
                                    } else {
                                ?>
                                        <tr>
                                            <td>1</td>
                                            <td>N/A</td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
                                            <td><?=number_format($i, 2);?></td>
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
    </div><!--End page content-->
    <?php } ?>
</div><!--END CONTENT CONTAINER-->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Geo tagged image on map</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div id="map" style="background: pink; height: 400px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap" async defer></script>

<script type="text/javascript">

    
var map;
var geocoder;
var centerChangedLast;
var reverseGeocodedLast;
var currentReverseGeocodeResponse;
function initialize(latitude, longitude) {
//alert(latitude);		
    var latlng = new google.maps.LatLng(latitude,longitude);
    var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    geocoder = new google.maps.Geocoder();

    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        title: "Aadrika Enterprises"
    });

}
function PopupMap(latitude, longitude)
{
    console.log(latitude);
    console.log(longitude);
    initialize(latitude, longitude);
}

function printDiv(divName)
{
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>



<?= $this->include('layout_vertical/footer');?>
