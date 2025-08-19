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
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li class="active"><a href="#">SAF</a></li>
            <li class="active"><a href="<?=base_url();?>/safdtl/full/<?=md5($verification_data['saf_dtl_id']);?>">SAF Detail</a></li>
            <li><a href="#">TC Verification</a></li>
        </ol> -->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <button class="btn btn-sm btn-primary" metaBtn="tcDiv" onclick="tergalBtn('tcDiv')" <?=(isset($sizeOfTc) && $sizeOfTc<=0 ? "disabled" :"");?>>TC Verification</button>
        <button class="btn btn-sm btn-primary" metaBtn="utcDiv" onclick="tergalBtn('utcDiv')" <?=(isset($sizeOfUtc) && $sizeOfUtc<=0 ? "disabled" :"");?>>UTC Verification</button>
        <button class="btn btn-sm btn-primary" metaBtn="tranDiv" onclick="tergalBtn('tranDiv')" <?=(isset($sizeOfTran) && $sizeOfTran<=0 ? "disabled" :"");?>>Tran Dtl</button>
        <a href="<?=base_url("safdtl/full/$safId");?>" target="_blank" class="btn btn-sm btn-primary" metaBtn="tranDiv">Saf full Dtl</a>
        <div id="tcDiv" style="display: block;">
            <?php
                if(isset($tcVerificationCompDtl,$tcVerificationCompDtl)){
                    foreach($tcVerificationCompDtl as $key=>$val){
                        ?>
                            <div id="backOfficeUpdateTc<?=$key;?>" name="backOfficeUpdate">
                                <div class="row">
                                    <div class="col-sm-12">					
                                        <!--Default Tabs (Right Aligned)-->
                                        <div class="tab-base">
                                            <!--Nav tabs-->
                                            <ul class="nav nav-tabs tabs-left">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#rgt-tab-1" aria-expanded="true" onclick="tergalDiv('vt<?=$key;?>')">Verification <?=$key+1;?> </a>
                                                </li>
                                            </ul>
                                            <!--Tabs Content-->
                                            <div class="tab-content" id="printableAreatc<?=$key;?>">
                                                <div id="rgt-tab-1" class="tab-pane fade active in">
                                                    <!-- TC Verification tab start -->
                                                    <div class="panel panel-bordered panel-dark">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title" >
                                                                Tax Collector Verification Details
                                                                <button class="btn btn-default pull-right" onclick="printDiv('printableAreatc<?=$key;?>')"><i class="fa fa-print"></i> Print</button>
                                                            </h3>
                                                            
                                                        </div>
                                                        <div id ="vt<?=$key;?>" style="display: <?=$key==0?'':'none';?>;">
                                                            <div class="panel-body" >
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-3">Name of Tax Collector </label><b><?=$val["verification_data"]['emp_name']??"";?> (<?=$val["verification_data"]['verified_by']??"";?>)</b>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-3">Date of Verification </label><b><?=$val["verification_data"]['created_on'];?></b>
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
                                                                        <label class="col-sm-3">SAF No</label><label class="col-sm-3"><b><?=$val["verification_data"]['application_no']??"";?></b></label>
                                                                        <label class="col-sm-3">Applied Date</label><label class="col-sm-3"><b><?=$val["verification_data"]['apply_date']??"";?></b></label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-3">Application Type</label><label class="col-sm-3"><b><?=$val["verification_data"]['assessment_type']??"";?></b></label>
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
                                                                                    foreach($val["saf_owner_detail"] as $owner)
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
                                                                                <?php
                                                                                if(isset($val["capData"]["safComp"])){
                                                                                    foreach($val["capData"]["safComp"] as $skey=>$safComp){
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?=$skey+1;?></td>
                                                                                                <td><?=$safComp["key"]??"";?></td>
                                                                                                <td><?=$safComp["assetsVal"]??"";?></td>
                                                                                                <td><img src="<?=base_url('public/assets/img');?>/<?=$safComp["flag"]?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                                                <td><?=$safComp["verificationVal"]??"";?></td>
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
                                                                // not vacant land
                                                                if(isset($val["capData"]["floorComp"]))
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
                                                                                        <?php
                                                                                            if(isset($val["capData"]["floorComp"]["head"])){
                                                                                                foreach($val["capData"]["floorComp"]["head"] as $head){
                                                                                                    ?>
                                                                                                            <th><?=$head??"";?></th>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php
                                                                                    if (isset($val["capData"]["floorComp"]["floors"]))
                                                                                    {
                                                                                        foreach ($val["capData"]["floorComp"]["floors"] as $assessed_floor)
                                                                                        {
                                                                                            ?>
                                                                                                <tr>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["val_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["floor_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["usage_type"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["assessedVal"]["occupancy_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["construction_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["builtup_area"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["assessedVal"]["carpet_area"]??"";?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["val_type"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["floor_name"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["usage_type"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["occupancy_name"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["construction_type"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["builtup_area"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["carpet_area"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr style="border-bottom: #1b0079;">
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["val_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["floor_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["usage_type"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["occupancy_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["construction_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["builtup_area"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["carpet_area"]??"";?></td>
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
                                                                    if(isset($val["capData"]["extraFloor"]) && !empty($val["capData"]["extraFloor"]))
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
                                                                                            <?php
                                                                                                if(isset($val["capData"]["extraFloor"]["head"])){
                                                                                                    foreach($val["capData"]["extraFloor"]["head"] as $head){
                                                                                                        ?>
                                                                                                            <th><?=$head??"";?></th>
                                                                                                        <?php
                                                                                                    }
                                                                                                }
                                                                                            ?>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        if(isset($val["capData"]["extraFloor"]["floors"])){
                                                                                            foreach($val["capData"]["extraFloor"]["floors"] as $new_floor)
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
            
            
                                                                if(isset($val["capData"]["safGeoTaggingDtl"],$val["capData"]["safGeoTaggingDtl"]))
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
                                                                                    foreach($val["capData"]["safGeoTaggingDtl"] as $geoTaggingDtl)
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
                                                    </div>
                                                    <!-- TC Verification tab start End  -->
                                                </div>
    
                                            
                                            </div>
                                        </div>
                                        <!--End Default Tabs (Right Aligned)-->
                                    </div>
                                </div> 
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
        <div id="utcDiv" style="display: none;">
            <?php
                if(isset($utcVerificationCompDtl,$utcVerificationCompDtl)){
                    foreach($utcVerificationCompDtl as $key=>$val){
                        ?>
                            <div id="backOfficeUpdateUTc<?=$key;?>" name="backOfficeUpdate">
                                <div class="row">
                                    <div class="col-sm-12">					
                                        <!--Default Tabs (Right Aligned)-->
                                        <div class="tab-base">
                                            <!--Nav tabs-->
                                            <ul class="nav nav-tabs tabs-left">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#rgt-tab-1" aria-expanded="true" onclick="tergalDiv('vut<?=$key;?>')">Verification <?=$key+1;?> </a>
                                                </li>
                                            </ul>
                                            <!--Tabs Content-->
                                            <div class="tab-content" id="printableAreautc<?=$key;?>">
                                                <div id="rgt-tab-1" class="tab-pane fade active in">
                                                    <!-- TC Verification tab start -->
                                                    <div class="panel panel-bordered panel-dark">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title" >
                                                                Tax Collector Verification Details
                                                                <button class="btn btn-default pull-right" onclick="printDiv('printableAreautc<?=$key;?>')"><i class="fa fa-print"></i> Print</button>
                                                            </h3>
                                                            
                                                        </div>
                                                        <div id ="vt<?=$key;?>" style="display: <?=$key==0?'':'none';?>;">
                                                            <div class="panel-body" >
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-3">Name of Tax Collector </label><b><?=$val["verification_data"]['emp_name']??"";?> (<?=$val["verification_data"]['verified_by']??"";?>)</b>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-3">Date of Verification </label><b><?=$val["verification_data"]['created_on'];?></b>
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
                                                                        <label class="col-sm-3">SAF No</label><label class="col-sm-3"><b><?=$val["verification_data"]['application_no']??"";?></b></label>
                                                                        <label class="col-sm-3">Applied Date</label><label class="col-sm-3"><b><?=$val["verification_data"]['apply_date']??"";?></b></label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label class="col-sm-3">Application Type</label><label class="col-sm-3"><b><?=$val["verification_data"]['assessment_type']??"";?></b></label>
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
                                                                                    foreach($val["saf_owner_detail"] as $owner)
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
                                                                                <?php
                                                                                    if(isset($val["capData"]["safComp"])){
                                                                                        foreach($val["capData"]["safComp"] as $skey=>$safComp){
                                                                                            ?>
                                                                                                <tr>
                                                                                                    <td><?=$skey+1;?></td>
                                                                                                    <td><?=$safComp["key"]??"";?></td>
                                                                                                    <td><?=$safComp["assetsVal"]??"";?></td>
                                                                                                    <td><img src="<?=base_url('public/assets/img');?>/<?=$safComp["flag"]?"correct":"incorrect";?>.png" style="height: 25px" /></td>
                                                                                                    <td><?=$safComp["verificationVal"]??"";?></td>
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
                                                                // not vacant land
                                                                if(isset($val["capData"]["floorComp"]))
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
                                                                                        <?php
                                                                                            if(isset($val["capData"]["floorComp"]["head"])){
                                                                                                foreach($val["capData"]["floorComp"]["head"] as $head){
                                                                                                    ?>
                                                                                                            <th><?=$head??"";?></th>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php
                                                                                    if (isset($val["capData"]["floorComp"]["floors"]))
                                                                                    {
                                                                                        foreach ($val["capData"]["floorComp"]["floors"] as $assessed_floor)
                                                                                        {
                                                                                            ?>
                                                                                                <tr>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["val_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["floor_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["usage_type"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["assessedVal"]["occupancy_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["construction_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["assessedVal"]["builtup_area"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["assessedVal"]["carpet_area"]??"";?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["val_type"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["floor_name"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["usage_type"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["occupancy_name"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["construction_type"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["builtup_area"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <img src="<?=base_url('public/assets/img');?>/<?=$assessed_floor["flag"]["carpet_area"]?"correct":"incorrect";?>.png" style="height: 25px" />
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr style="border-bottom: #1b0079;">
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["val_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["floor_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["usage_type"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["occupancy_name"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["construction_type"]??"";?></td>
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["builtup_area"]??"";?></td>
            
                                                                                                    <td><?=$assessed_floor["VerificationVal"]["carpet_area"]??"";?></td>
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
                                                                    if(isset($val["capData"]["extraFloor"]) && !empty($val["capData"]["extraFloor"]))
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
                                                                                            <?php
                                                                                                if(isset($val["capData"]["extraFloor"]["head"])){
                                                                                                    foreach($val["capData"]["extraFloor"]["head"] as $head){
                                                                                                        ?>
                                                                                                            <th><?=$head??"";?></th>
                                                                                                        <?php
                                                                                                    }
                                                                                                }
                                                                                            ?>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        if(isset($val["capData"]["extraFloor"]["floors"])){
                                                                                            foreach($val["capData"]["extraFloor"]["floors"] as $new_floor)
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
            
            
                                                                if(isset($val["capData"]["safGeoTaggingDtl"],$val["capData"]["safGeoTaggingDtl"]))
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
                                                                                    foreach($val["capData"]["safGeoTaggingDtl"] as $geoTaggingDtl)
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
                                                    </div>
                                                    <!-- TC Verification tab start End  -->
                                                </div>
    
                                            
                                            </div>
                                        </div>
                                        <!--End Default Tabs (Right Aligned)-->
                                    </div>
                                </div> 
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
        <div id="tranDiv" style="display: none;">
            <?php
                if(isset($tranDtl,$tranDtl)){
                    ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Payment Details</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                                <thead class="bg-trans-dark text-dark">
                                                    <tr>
                                                        <th>Sl No.</th>
                                                        <th>Transaction No</th>
                                                        <th>Payment Mode</th>
                                                        <th>Date</th>
                                                        <th>From Quarter / Year</th>
                                                        <th>Upto Quarter / Year</th>
                                                        <th>Amount</th>
                                                        <th>View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (isset($tranDtl)) {
                                                        $i = 1;
                                                        foreach ($tranDtl as $payment_detail) {
                                                    ?>
                                                            <tr class="<?= ($payment_detail["status"] == 3) ? 'text-danger' : null; ?>">
                                                                <td><?= $i++; ?></td>
                                                                <td class="text-bold"><?= $payment_detail['tran_no']; ?></td>
                                                                <td><?= $payment_detail['transaction_mode'] ?></td>
                                                                <td><?= $payment_detail['tran_date']; ?></td>
                                                                <td><?= $payment_detail['from_qtr'] . " / " . $payment_detail['fy']; ?></td>
                                                                <td><?= $payment_detail['upto_qtr'] . " / " . $payment_detail['upto_fy']; ?></td>
                                                                <td><?= $payment_detail['payable_amt']; ?></td>

                                                                <td>
                                                                    <a onClick="PopupCenter('<?= base_url('safDemandPayment/saf_payment_receipt/' . md5($payment_detail['id'])); ?>', 'SAF Payment Receipt', 1024, 786)" id="customer_view_detail" class="btn btn-primary">View</a>
                                                                    <a onClick="PopupCenter('<?= base_url("citizenPaymentReceipt/saf_payment_receipt/" . $ulb_mstr_id . "/" . md5($payment_detail['id'])); ?>', 'SAF Citizen Payment Receipt', 1024, 786)" class="btn btn-primary">Citizen View </a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="9" class="text-danger text-bold text-center"> No Any Transaction ...</td>
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
                    <?php
                }
            ?>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->

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

<?= $this->include('layout_vertical/footer');?>
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
        document.body.style.marginTop="30px";
        document.body.style.fontSize="xx-small";
        window.print();
        document.body.style.marginTop="";
        document.body.style.fontSize="";
        document.body.innerHTML = originalContents;
    }

    function tergalDiv(id){
        var displayValue = document.getElementById(id).style.display;
        if(displayValue!="none"){
            $("#"+id).hide();
        }
        else{
            $("#"+id).show();
        }
    }

    function tergalBtn(id){
        var elements = document.querySelectorAll('[metaBtn]');
        elements.forEach(function(button) {
            $("#"+(button.getAttribute('metaBtn'))).hide();
        });
        $("#"+id).show();
    }
</script>



