
<?=$this->include("layout_mobi/header");?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<!--Select2 [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">  
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#"> Trade </a></li>
            <li><a href="#" class="active">Apply Licence </a></li>
        </ol> -->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post">
           
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>

                <?php if($application_type['id']==1) {?>
                <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <?php                        
                            echo("<h2 class='panel-title'> <a class='pull-right btn btn-info' href='".base_url()."/Mobi/mobileMenu/trade'><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</a></h2>");
                    ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-2">Apply With  <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<select name="applyWith" id="applyWith" class="form-control" onChange="showHideNotice()">
								<option value="">Select</option>
                                <option value="1">Notice No.</option>
								<option value="2">New Licence</option>
							</select>       
						</div>

                        <label class="col-md-2 hideNotice" style="display:none;">Notice No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm hideNotice" style="display:none;">
                        <input type="text"  name="noticeNo" id="noticeNo" class="form-control"  placeholder="Enter Notice No.">             
                        <input type="hidden"  name="dnialID" id="dnialID" class="hideNotice form-control" value="0">             
                    </div>
                        <button onclick="getDenialDetails()" type="button" id="btn_review" name="btn_review" style="display:none;"   class="hideNotice btn btn-primary">Search</button>

                    </div>

                    <div class="row hideNotice" style="display:none;">
                    <label class="col-md-2 hideNotice" style="display:none;">Notice Date. <span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm hideNotice" style="display:none;">
                    <input readonly type="text"  name="noticedate" id="noticedate" class="hideNotice form-control">             
                </div>
                </div>
                </div>
            </div>
            <?php }?>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply Licence (<?=$application_type['application_type'];?>)</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-2">Application Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 control-label text-semibold">
                            <?=$application_type["application_type"]?>
                        </div>
                        <label class="col-md-2">Firm Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm"><?php if($application_type["id"]==1 or $application_type["id"]==3){?>
							<select name="firmtype_id" id="firmtype_id" onchange="forother(this.value); validate_holding()" class="form-control" >
								<option value="">Select</option>
								<?php
								if($firmtypelist)
								{
									foreach($firmtypelist as $val)
									{

										?>
										<option value="<?php echo $val['id'];?>" <?php if(isset($firm_type_id) && $firm_type_id==$val['id']){echo "selected"; }?>><?php echo $val['firm_type'];?></option>
										<?php
									}
								}
								?>
							</select> 
					<?php }else{ echo  $firm_type;} ?>                       
						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm"><?php if($application_type["id"]==1 or $application_type["id"]==3){?>
                          <select name="ownership_type_id" id="ownership_type_id" onchange="validate_holding()" class="form-control">
                          	<option value="">Select</option>
                          	<?php
                          	if($ownershiptypelist)
                          	{

                          		foreach($ownershiptypelist as $val)
                          		{
                                    ?>
                                    <option value="<?php echo $val['id'];?>" <?php if(isset($ownership_type_id) && $ownership_type_id==$val['id']){ echo "selected";}?>><?php echo $val['ownership_type'];?></option>
                                    <?php
                          	    }
                          	}
                          	?>
                          </select>
                          <?php }else{ echo  $ownership_type;} ?> 
                        </div>

                        <label class="col-md-2 classother" style="display: none;">For Other Firm type<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm classother" style="display: none;">
                          <input type="text" name="firmtype_other" id="firmtype_other" class="form-control" value="<?php echo isset($firmtype_other)?$firmtype_other:""; ?>" placeholder="Other Firm type"  onkeypress="return isAlphaNum(event);">  
                        </div>
                        <?php 
                        if($application_type["id"]<>1)
                        {
                            ?> 
                            <label class="col-md-2">License No. </label>
                            <div class="col-md-3 control-label text-semibold">
                                <?=$licencedet["license_no"]?>
                            </div>
                            <?php 
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Details</h3>
                </div>
                <?php if($application_type["id"]==1){
                ?> 
                <div class="panel-body">  

                <!-- <div class="row"  >  
                    <label class="col-md-4"><input type="checkbox" id="chk">&nbsp;Find with 16 digit holding number </label>                 
					</div>   -->
                    <!-- <div class="row"  style="display:none;" id="show_holding">  
 						<label class="col-md-2" id="holding_lebel" >Enter Holding No. </label>
						<div class="col-md-3 pad-btm" id="holding_div" >
                            <input maxlength="16" type="text" name="new_holding_no" id="new_holding_no" class="form-control" onkeyup="validate_new_holding()" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);">
 						<span style="color:red;"> </span>
                         </div>
                         <span style="color:#0a7bef;">(Enter only Pure Commercial or Mix Commercial Holding Type)</span>
					</div>  -->
					<div class="row"  >  
                        <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select name="old_ward_id" id="old_ward_id"  class="form-control" onchange="validate_holding(),onkeyup=openModel()">
                                <option value="">Select</option>
                                <?php
                                  if($ward_list)
                                  {
                                    foreach($ward_list as $val)
                                    {
                                ?>
                                <option value="<?php echo $val['id'];?>" <?php if(isset($ward_id) && $ward_id==$val['id']){echo "selected";} ?>><?php echo $val['ward_no'];?></option>
                                <?php 
                                    } 
                                  }
                                ?>
                            </select>
                        </div>                    
						<label class="col-md-2" id="holding_lebel" >Holding No. <span class="text-danger"></span></label>
						<div class="col-md-3 pad-btm" id="holding_div" >
                            <input type="text" name="holding_no" id="holding_no" class="form-control" onchange="validate_holding()" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);" onblur="woner_permisses()">
                            <span class="text-warning" id="holding_error"></span><span class="text-success" id="holding_success"></span>
                            <input type="hidden" name="prop_id" id="prop_id">

						</div>
					</div>  

                    <input type="hidden" value="0" name="tobacco_status">

					<div class="row">
						<!-- <label class="col-md-2">Firm Name<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input   type="text" name="firm_name" id="firm_name" class="form-control" value="<?php echo isset($firm_name)?$firm_name:""; ?>"  onkeypress="return isAlphaNum(event);">                                                 
                        </div> -->
                        <label class="col-md-2">New Ward No. <span class="text-danger">*</span></label>

                        <div class="col-md-3 pad-btm">
                            <select name="new_ward_id" id="new_ward_id"  class="form-control">
                                <option value="">Select</option>
                                <?php
                                  if($ward_list)
                                  {
                                    foreach($ward_list as $val)
                                    {
                                        ?>
                                        <option value="<?php echo $val['id'];?>" <?php if(isset($ward_id) && $ward_id==$val['id']){echo "selected";} ?>><?php echo $val['ward_no'];?></option>
                                        <?php 
                                    } 
                                  }
                                ?>
                            </select>
                        </div>

						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm"> 
							<input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onblur="show_charge()" value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>" onkeypress="return isNumDot(event);">
						</div>                    
					</div>

                    <div class="row">
                        <label class="col-md-2">Firm Name<span class="text-danger">*</span></label>
						<div class="col-md-8 pad-btm">
							<input   type="text" name="firm_name" id="firm_name" class="form-control" value="<?php echo isset($firm_name)?$firm_name:""; ?>"  onkeypress="return isAlphaNum(event);">                                                 
                        </div>
                    </div>
					
					<div class="row">
						<label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="date" name="firm_date" id="firm_date" class="form-control" value="<?php echo isset($firm_date)?$firm_date:date('Y-m-d'); ?>" onchange="show_charge(); checkDOB()"  onkeypress="return isNum(event);"> 

						</div>
						<label class="col-md-2">Business Address<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input name="firmaddress" id="firmaddress" class="form-control"  onkeypress="return isAlphaNum(event);" value="<?php echo isset($address)?$address:"";?>" >                         
						</div>
					</div>
					
					<div class="row">  
                        <label class="col-md-2">Landmark<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input name="landmark" id="landmark" class="form-control"  value="<?php echo isset($landmark)?$landmark:"";?>" >                         
						</div>

						<label class="col-md-2">Pin Code<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="pin_code" id="pin_code"  maxlength="6" minlength="6" class="form-control" value="<?php echo isset($pin_code)?$pin_code:""; ?>" onkeypress="return isNum(event);">
						</div>
                    </div>

                    <!-- <div class="row"> 
                        <label class="col-md-2">New Ward No. <span class="text-danger">*</span></label>

                        <div class="col-md-3 pad-btm">
                            <select name="new_ward_id" id="new_ward_id"  class="form-control">
                                <option value="">Select</option>
                                <?php
                                  if($ward_list)
                                  {
                                    foreach($ward_list as $val)
                                    {
                                        ?>
                                        <option value="<?php echo $val['id'];?>" <?php if(isset($ward_id) && $ward_id==$val['id']){echo "selected";} ?>><?php echo $val['ward_no'];?></option>
                                        <?php 
                                    } 
                                  }
                                ?>
                            </select>
                        </div>
					</div> -->
                    <div class="row">                        
                        <label class="col-md-2">Owner of Business Premises</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" readonly="" name="owner_business_premises" id="owner_business_premises"  class="form-control" value="<?php echo isset($owner_business_premises)?$owner_business_premises:""; ?>" />
                        </div>
                       
                    </div>
                    <div class="row"> 
                        <label class="col-md-2">Business Description<span class="text-danger">*</span></label>
                        <div class="col-md-8 pad-btm">
                           <textarea name="brife_desp_firm" id="brife_desp_firm" class="form-control" required onkeypress="return isAlphaNum(event);" ><?=isset($_POST['brife_desp_firm'])?$_POST['brife_desp_firm']:''?></textarea>
                        </div>
                    </div>
                </div>
				<?php } else{?>
                <div class="panel-body">
                  
					<div class="row"  >
						<label class="col-md-2" id="saf_lebel" >Holding No. <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm" id="saf_div" >
                            <?php
                                if($application_type["id"]==2)
                                {
                                    ?>
                                        <input type="text" name="holding_no" id="holding_no" class="form-control" onchange="validate_holding()" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);" required>
                                        <span class="text-warning" id="holding_error"></span><span class="text-success" id="holding_success"></span>
                                        <input type="hidden" name="prop_id" id="prop_id">
                                    <?php
                                }
                                else 
                                {
                                    echo $licencedet["holding_no"];
                                }
                                
                            ?>
						</div>
						<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<?=$ward_no?>
                            <input type="hidden" name="old_ward_id" id="old_ward_id" value="<?=$ward_id?>">
 						</div>
					</div>
 					<div class="row">
						<label class="col-md-2">Firm Name<span class="text-danger">*</span></label>
 						<div class="col-md-3 pad-btm">
							<?php  echo $licencedet["firm_name"]; ?>
                             
						</div>
						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">   
							<?php if($application_type["id"]==3){?> <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onblur="show_charge()"  
                            value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>" onkeypress="return isNumDot(event);">
							<?php }else{ echo $licencedet["area_in_sqft"]; ?> 
                            <input type="hidden" name="area_in_sqft" id="area_in_sqft" class="form-control" value="<?php  echo $licencedet["area_in_sqft"]; ?>" >
							<?php }?>
                          
						</div>                    

					</div>
					
					<div class="row">
						<label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<?php  echo $licencedet["establishment_date"]; ?>
                            <input type="hidden" name="firm_date" id="firm_date"  value="<?php echo isset($licencedet["valid_upto"])?$licencedet["valid_upto"]:NULL; ?>" onchange="show_charge(); checkDOB()"  onkeypress="return isNum(event);"> 
						</div>
						<label class="col-md-2">Business Address<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<?php  echo $licencedet["address"]; ?>
						</div>
					</div>
					
					<div class="row">                       
						<label class="col-md-2">Pin Code<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<?php  echo $licencedet["pin_code"]; ?>
						</div>
                        <label class="col-md-2">New Ward No. <span class="text-danger">*</span></label>

                        <div class="col-md-3 pad-btm">
                           <?=$new_ward_no["ward_no"]?>
                        </div>
					</div>

                    <div class="row">                        
                        <label class="col-md-2">Owner of Business Premises</label>
                        <div class="col-md-3 pad-btm">
                           <?php  echo $licencedet["premises_owner_name"]; ?>
                        </div>
                        <label class="col-md-2">Business Description<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <textarea name="brife_desp_firm" id="brife_desp_firm" style="width: 80%;" required readonly><?php  echo $licencedet["brife_desp_firm"]; ?></textarea>
                        </div>
                    </div>
				</div>
				<?php }?>
			</div>

			<div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
								<?php 
                                $zo = 1;
                                # New License, Ammendment
                                if(in_array($application_type["id"], [1, 3]))
                                {
                                    ?>
                                    <!-- <div class="panel panel-bordered panel-dark" > -->
                                        <!-- <div class="panel-heading">
                                            <h3 class="panel-title"> New Owner Details </h3>
                                        </div> -->
                                        <div class="panel-body" style="padding-bottom: 0px;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                    <table class="table table-bordered text-sm" id="ownertable">
                                                        <thead class="bg-trans-dark text-dark">
                                                            <tr>
                                                                <th>Owner Name <span class="text-danger">*</span></th>
                                                                <th>Guardian Name</th>                                            
                                                                <th>Mobile No <span class="text-danger">*</span></th>
                                                                <th>Email Id</th> 
                                                                <th>Add/Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="owner_dtl_append">
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);"  />
                                                            </td>
                                                            <td>
                                                                <input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);"  />
                                                            </td>                                            
                                                            <td>
                                                                <input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);"  maxlength="10" />
                                                            </td>
                                                            <td>
                                                                <input type="email" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value=""   maxlength="30" />
                                                            </td> 
                                                        
                                                            
                                                            <td class="text-2x">
                                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                                                &nbsp;
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                    <?php 
                                }

                                if(isset($ownerdet, $ownerdet) )
                                {
                                    ?>
                                    <!-- <div class="panel panel-bordered panel-dark" >
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Owner Details</h3>
                                        </div> -->
                                        <div class="panel-body" style="padding-bottom: 0px;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                    <table class="table table-bordered text-sm">
                                                        <thead class="bg-trans-dark text-dark">
                                                            <tr>
                                                                <th>Owner Name <span class="text-danger">*</span></th>
                                                                <th>Guardian Name</th>                                            
                                                                <th>Mobile No <span class="text-danger">*</span></th>
                                                                <th>Email Id</th>                                                                 
                                                            </tr>
                                                        </thead>                                   
                                                        <tbody>  
                                                        <?php
                                                        foreach ($ownerdet as  $value)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td> <?=$value["owner_name"]?> </td>
                                                                <td><?=$value["guardian_name"]?></td>                                            
                                                                <td><?=$value["mobile"]?></td>
                                                                <td><?=$value["emailid"]?></td>
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
                                    <!-- </div> -->
								    <?php 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php 
            if(!isset($tobacco_status))
            {
                ?>
                <div class="panel panel-bordered panel-dark" >
                    <div class="panel-heading">
                        <h3 class="panel-title">Nature Of Business</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                <?php
                                if($application_type["id"]==1)
                                {
                                    ?> 
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Business <span class="text-danger">*</span></th>
                                                <!-- <th>Add/Remove</th> -->
                                            </tr>
                                        </thead>
                                        <tbody id="trade_item_append">
                                        <?php
                                        $ti = 0;
                                        if(!empty($tradeitemdet))
                                        {
                                            foreach ($tradeitemdet as  $itemvalue)
                                            {
                                                $ti++;
                                                ?>
                                                <tr>                                            
                                                    <td>


                                                     <!--   <div class="row">
                                                        <select id="demo-select2-multiple-selects" multiple="multiple" class="form-control">
                                                            <optgroup label="Central Time Zone">
                                                                <option value="AL">Alabama</option>
                                                                <option value="AR">Arkansas</option>
                                                                <option value="IL">Illinois</option>
                                                                <option value="IA">Iowa</option>
                                                                <option value="KS">Kansas</option>
                                                                <option value="KY">Kentucky</option>
                                                                <option value="LA">Louisiana</option>
                                                                <option value="MN">Minnesota</option>
                                                                <option value="MS">Mississippi</option>
                                                                <option value="MO">Missouri</option>
                                                                <option value="OK">Oklahoma</option>
                                                                <option value="SD">South Dakota</option>
                                                                <option value="TX">Texas</option>
                                                                <option value="TN">Tennessee</option>
                                                                <option value="WI">Wisconsin</option>
                                                            </optgroup>
                                                        </select>

                                                    </div> -->


                                                        <select id="tade_item<?=$ti?>" name="tade_item[]" class="form-control tade_item demo-select2-multiple-selects" required="required"  onchange="borderNormal(this.id);" multiple="multiple">
                                                            <optgroup label="Central Time Zone">
                                                            <!-- <option value="">SELECT</option> -->
                                                            <?php
                                                            if($tradeitemlist)
                                                            {

                                                                foreach($tradeitemlist as $valit)
                                                                {
                                                                ?>
                                                                <option value="<?php echo $valit['id'];?>" <?php if($itemvalue["id"]==$valit['id']){echo "selected"; }?> ><?php echo "(". $valit['id'].") ".$valit['trade_item'];?></option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    
                                                    <td class="text-2x">
                                                       <!--  <i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>
                                                        &nbsp;  -->
                                                        <?php if($ti>1){?>
                                                        <!-- <i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i> -->
                                                    <?php }?>
                                                    </td>
                                                </tr>
                                                <?php 
                                            }
                                        }
                                        else
                                        {
                                            $ti = 1;
                                            ?>
                                            <tr>                                            
                                                <td>
                                                    <select id="tade_item1" name="tade_item[]" class="form-control tade_item demo-select2-multiple-selects" required="required"  onchange="show_charge();" multiple="multiple">
                                                        <!-- <option value="">SELECT</option> -->
                                                        <?php
                                                        if($tradeitemlist)
                                                        {
                                                            foreach($tradeitemlist as $valit)
                                                            {
                                                            ?>
                                                            <option value="<?php echo $valit['id'];?>" ><?php echo "(". $valit['id'].") ".$valit['trade_item'];?></option>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                
                                                <td class="text-2x">
                                                   <!--  <i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>
                                                    &nbsp; -->
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php 
                                }
                                else
                                {
                                    ?>
                                    <table class="table table-bordered text-sm">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Trade Item <span class="text-danger">*</span></th>
                                                <th>Trade Code <span class="text-danger">*</span></th>                                          
                                            </tr>
                                        </thead>
                                            <?php
                                            $ti = 1;
                                            if(isset($tradedetail))
                                            {
                                                foreach($tradedetail as $trade)
                                                {
                                                    ?>
                                                    <tr>                                            
                                                        <td>
                                                            <input type="hidden" id="trade_item" name="trade_item" class="form-control" value="<?=$trade["trade_code"];?>" />
                                                            <?= "(". $valit['id'].") ".$trade["trade_item"]?>
                                                        </td>
                                                        <td>
                                                            <?=$trade["trade_code"]?>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php 
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <?php 
            }
            else
            {
                ?>
                <div class="panel panel-bordered panel-dark" >
                    <div class="panel-heading">
                        <h3 class="panel-title">Nature Of Business 
                            <span class="text text-danger">
                                <?php if($tobacco_status==1) {echo "Tobbaco";}?></span></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row" >
                            <div class="col-md-12">
                                <div class="">
                                <?php
                                    $ti = 0;//print_var($tradeitemdet);//$tradedetail $itemvalue["id"]
                                    if(!empty($tradeitemdet))
                                    {
                                
                                        //foreach ($tradeitemdet as  $itemvalue)
                                        {
                                            $ti++;
                                            $ti=1;
                                            ?>
                                            <select id="tade_item<?=$ti?>" name="tade_item[]" class="form-control tade_item demo-select2-multiple-selects" required="required"  onchange="borderNormal(this.id);"  multiple="multiple" readonly disabled>
                                                <option value="">SELECT</option>
                                                <?php
                                                if($tradeitemlist)
                                                {

                                                    foreach($tradeitemlist as $valit)
                                                    {
                                                    ?>
                                                    <option value="<?php echo $valit['id'];?>" <?php if(in_array($valit['id'],$tradeitemdet)){echo "selected"; }?> ><?php echo "(". $valit['id'].") ".$valit['trade_item'];?></option>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php 
                                        }
                                    
                                    }
                                    else
                                    {
                                        $ti = 1;
                                        ?>
                                        
                                        <select id="tade_item1" name="tade_item[]" class="form-control tade_item" required="required"  onchange="borderNormal(this.id);" readonly disabled>
                                        <option value="">SELECT</option>
                                        <?php
                                        if($tradeitemlist)
                                        {
                                            foreach($tradeitemlist as $valit)
                                            {
                                                ?>
                                                <option value="<?php echo $valit['id'];?>" <?= ($valit['id']==$licencedet['nature_of_bussiness'])?"selected":'';?>>
                                                    <?php echo "(". $valit['id'].") ".$valit['trade_item']; ?>
                                                        
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        <?php 
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
            } ?>
                <div class="panel-body" style="padding-bottom: 0px; display:none;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
								<?php //f($application_type["id"]==1 or $application_type["id"]==3){
								?> 
                                <table class="table table-bordered text-sm">
                                    
                                    <tbody id="trade_item_append">
									
                                    </tbody>
                                </table>

                                <?php //}?>
                            </div>
                        </div>
                    </div>
                </div>
         	 <?php 
             
                $shw=1;
                if(($application_type["id"]>1)&&($licencedet["area_in_sqft"]==0)){ $shw=0;}
                if($shw==1)
                {
                    # Surrender
                    if($application_type["id"]<>4)
                    {
                        ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Licence Required for the Year</h3>
                            </div>
                            <div class="panel-body">
                            <?php
                            # Renewal
                            if($application_type["id"]==2)
                            {
                                ?>
                                <div class="row">
                                    <label class="col-md-2">License Expire</label>
                                    <div class="col-md-3 pad-btm"> <b> <?=$licencedet['valid_upto'];?> </b> </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="row">                    
                            <label class="col-md-2">License For<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <?php 
                                    
                                    if($application_type["id"]==3)
                                    {
                                        ?>
                                        <select id="licence_for" name="licence_for" class="form-control" onclick="show_charge()">
                                            <option value="1">1 Year</option>
                                        </select>
                                        <?php 
                                    }
                                    else
                                    {
                                        ?>
                                        <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()">
                                            <option value="">--Select--</option>
                                            <option value="1">1 Year</option>
                                            <option value="2">2 Year</option>
                                            <option value="3">3 Year</option>
                                            <option value="4">4 Year</option>
                                            <option value="5">5 Year</option>
                                            <option value="6">6 Year</option>
                                            <option value="7">7 Year</option>
                                            <option value="8">8 Year</option>
                                            <option value="9">9 Year</option>
                                            <option value="10">10 Year</option>
                                        </select>
                                        <?php 
                                    }
                                    ?>
                                </div>                       
                                <label class="col-md-2">Charge Applied<span class="text-danger">*</span></label>

                                <div class="col-md-3 pad-btm">
                                    <input type="text" id="charge"  disabled="disabled" class="form-control" value="<?php echo $rate ?? 0;?>"  onkeypress="return isNum(event);" />
                                </div>
                            </div>  
                            <div class="row">                    
                            <label class="col-md-2">Penalty<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text"  id="penalty"  disabled="disabled" class="form-control" value="<?php echo $penalty ?? 0;?>"  onkeypress="return isNum(event);" />
                                </div>
                            
                                <label class="col-md-2">Denial Amount / Arrers<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text"  id="denialAmnt"  disabled="disabled" class="form-control"  value="0" onkeypress="return isNum(event);" required />
                                </div>
                                </div>

                              
                            <div class="row">

                            <label class="col-md-2">Total Charge<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text"  id="total_charge" name="total_charge"  disabled="disabled" class="form-control" value="<?php echo $total_charge?? 0;?>"  onkeypress="return isNum(event);" min="299" required />
                                </div> 


                            <label class="col-md-2">Payment Mode<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()">
                                            <option value="" >Choose...</option>                                  
                                            <option value="CASH">CASH</option>
                                            <option value="CHEQUE">CHEQUE</option>
                                            <option value="DEMAND DRAFT">DEMAND DRAFT</option>
                                            
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="chqno" style="display: none;">
                            <label class="col-md-2">Cheque/DD Date<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="date" class="form-control" id="chq_date" name="chq_date" value="<?=date("Y-m-d")?>" placeholder="Enter Cheque/DD Date">
                                </div>
                                <label class="col-md-2">Cheque/DD No.<span class="text-danger">*</span></label>

                                <div class="col-md-3 pad-btm">
                                    <input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No." onkeypress="return isNum(event)" maxlength="15">
                                </div>
                            </div>
                            <div class="row" id="chqbank" style="display: none;">
                            <label class="col-md-2">Bank Name<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder=" Enter Bank Name">
                                </div>
                                <label class="col-md-2">Branch Name<span class="text-danger">*</span></label>

                                <div class="col-md-3 pad-btm">
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" placeholder=" Enter Branch Name">
                                </div>
                            </div>
                            </div>
                            </div>
                        <?php 
                    } 
                    ?>
       
                    <div class="panel panel-bordered panel-dark">
                        <div class="col-md-10" id="dd"></div>
                        <div class="panel-body demo-nifty-btn text-center">
                        <?php 
                            $onclick='';
                            if($application_type['id']!=4) // Surrender
                            {
                                $onclick='onclick="return confirmsubmit()"';
                            }
                            ?>
                            <input type="hidden" name="apply_from" value="JSK" />
                            <button type="submit" id="btn_review" name="btn_review" <?=$onclick;?> class="btn btn-primary">SUBMIT</button>
                        </div>
                    </div>
                    <?php 
                }
                else
                {
                    ?>
                    <div class="panel panel-bordered panel-dark">
                           <div class="col-md-10" id="dd"></div>
                        <div class="panel-body demo-nifty-btn text-center" style="color: red;" >Please Update Holding And Area</div>
                    </div>
                    <?php 
                }
                ?>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<!--Select2 [ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>

  
$("#chk").change(function() {
    if(this.checked) {
        $("#show_holding").css("display","block");
        //Do stuff
    }
    else
    {
        $("#show_holding").css("display","none"); 
    }
});



</script>
<script>


function validate_new_holding()
{ 
         var new_holding_no=$("#new_holding_no").val();
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplylicence/new_holding_no_details");?>',
                dataType: "json",
                data: {
                        "new_holding_no":new_holding_no,
                 },               
                success:function(data){
                //console.log(data);
                   if (data.response==true) {

                  // var obj = JSON.parse(data.dd);
                  // alert(data.dd.0.owner_name);
                    var tbody="";
                    var i=1;
                        var prop_id=data.pp['id'];                       
                        var ward_mstr_id=data.pp['ward_mstr_id'];
                        var ward_no=data.pp['ward_no'];
                        var address=data.pp['prop_address'];
                        var city=data.pp['prop_city'];
                        var pincode= data.pp['prop_pin_code'];
                        var owner_business_premises= data.pp['owner_name'];
                        
                    if(prop_id)
                    {
                    $("#prop_id").val(prop_id);
                   // $("#ward_id").val(ward_mstr_id);
                    $("#ward_no").val(ward_no);
                    $("#firmaddress").val(address);
                    $("#pin_code").val(pincode);  
                    $("#owner_business_premises").val(owner_business_premises);
                    }
                    else
                    {
                        alert('Holding No. not Found');
                      $("#holding_no").val("");
                      $("#prop_id").val("");
                      //$("#ward_id").val("");
                      $("#ward_no").val("");
                      $("#firmaddress").val("");
                      $("#pin_code").val(""); 
                      $("#owner_business_premises").val("");
                    }
                  }  
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(JSON.stringify(jqXHR)); alert();
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
              });
        
}
</script>
<script>
    $(document).ready(function(){
        $('.demo-select2-multiple-selects').select2();
        $("#formname").validate({
            rules:{
                firmtype_id:{
                    required:true
                },
                ownership_type_id:{
                    required:true
                },
                old_ward_id:{
                    required:true
                },
                new_ward_id:{
                    required:true
                },                              
                firm_name:{
                    required:true
                },
                firm_date:{
                    required:true
                },
                area_in_sqft:{
                    required:true,
                    min: 10,
                },
                address:{
                    required:true
                },                
                pin_code:{
                    required:true
                },
                // holding_no:{
                //     required:true
                // },                
                chq_date:{
                    required:true
                },                
                chq_no:{
                    required:true
                },                
                bank_name:{
                    required:true
                },                
                branch_name:{
                    required:true
                },                
                
                applyWith:{
                    required:true
                },  
                noticeNo:{
                    required:true
                },
                firmaddress:{
                    required:true
                },
                landmark:{
                    required:true
                },
                licence_for:{
                    required:true
                }, 
                charge:{
                    required:true
                }, 
                total_charge:{
                    required:true
                }, 
                payment_mode:{
                    required:true
                },
                "owner_name[]":{
                    required:true,
                    minlength:3
                },
                "guardian_name[]":
                {
                    minlength:3
                },
                "mobile_no[]":{
                    required:true,
                    minlength:10,
                    maxlength: 10,
                },
                "emailid[]":{
                    email:true
                },
                "id_no[]":{
                    required:true
                },
                firmtype_other:{
                 required:true 
                },

                

            },
            messages:{
                firmtype_id:{
                    required:"Please select Firm Type"
                },
                ownership_type_id:{
                    required:"Please select Ownership Type"
                },
                old_ward_id:{
                    required:"Please select Old Ward No."
                },
                new_ward_id:{
                    required:"Please select New Ward No."
                },
                firm_name:{
                    required:"Please Enter Firm Name"
                },
                holding_no:
                {
                    required:"Please Enter Holding No."
                }, 
                firm_date:{
                    required:"Please Enter Firm Establishment Date"
                },
                area_in_sqft:{
                    required:"Please Enter Area",
                },
                address:{
                    required:"Please Enter Address"
                }, 
                firmaddress:{
                    required:"Please Enter Business Address"
                },
                landmark:{
                    required:"Please Enter Landmark",
                },
                applyWith:{
                    required:"Please Select Apply With",
                },
                noticeNo:{
                    required:"Please Enter Notice No.",
                },
                pin_code:{
                    required:"Please Enter Pincode"  
                },                
                chq_date:{
                    required:"Please Select date"  
                },                
                chq_no:{
                    required:"Please Enter Cheque/DD No."  
                },                
                bank_name:{
                    required:"Please Enter Bank Name"  
                },                
                branch_name:{
                    required:"Please Enter Branch Name"  
                },                
                licence_for:{
                    required:"Please Enter Licence For"  
                }, 

                payment_mode:{
                    required:"Please Enter Payment Mode"  
                },
                "owner_name[]":{
                    required:"Please Enter Owner Name"
                },
                "mobile_no[]":{
                    required:"Please Enter Mobile No."
                },
                "idproof[]":{
                    required:"Please Select Idproof"
                },
                "id_no[]":{
                    required:"Please Enter Id No."
                },
                firmtype_other:{
                 required:"Please Enter Other Firm type"
                }                
            }
        });
    });
	
    

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

function confirmsubmit()
{
    if($("#formname").valid())
    {
    var amt = $('#total_charge').val();
    var del=confirm("Are you sure you want to confirm Payment of Rs "+amt+"?");
    return del;
    }
    else
    {
        return false;
    }
     
}

/*$("#btn_review").click(function(){

  $(".owner_name").each(function() {
                var ID = this.id.split('owner_name')[1];
                var owner_name = $("#owner_name"+ID).val();
                var guardian_name = $("#guardian_name"+ID).val();                
                var mobile_no = $("#mobile_no"+ID).val();                

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
                
            });

   $(".tade_item").each(function() {
                var IDV = this.id.split('tade_item')[1];
                var tade_item = $("#tade_item"+IDV).val();
                         

                if(tade_item==""){
                    $("#tade_item"+IDV).css('border-color', 'red'); process = false;
                }
                
                
            }); 
});*/



     $( document ).ready(function() {
   

    var holding_exists=$("#holding_exists").val();
    //alert(holding_exists);

    if(holding_exists=='YES')
    {
        $("#holding_lebel").show();
        $("#holding_div").show();
        $("#saf_div").hide();
        $("#holding_no").attr('required',true);
        $("#saf_no").attr('required',false);
        

    }
    else if(holding_exists=='NO')
    {

       $("#saf_lebel").show();
       $("#saf_div").show();
       $("#holding_div").hide();
       $("#saf_no").attr('required',true);
       $("#holding_no").attr('required',false);
    }
    });

     var appendData = '<tr><td><input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="email" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value=""  onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i></td></tr>';

        $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e)
        {
            $(this).closest("tr").remove();
        });
    function validate_holding()
    {
        var holding_no=$("#holding_no").val();
        var firmtype_id=$("#firmtype_id").val();        
        var ward_id=$("#old_ward_id").val();
        //alert(<?=$application_type['id']?>);
        var owner_type=$("#ownership_type_id").val();  
        if(ward_id=='' && holding_no!='')
        {
            alert('Pealse select Ward No.');
            return;
        }       
        if(holding_no=="")
        {
            $("#owner_dtl_append").html(appendData);
        }
        else if(<?=$application_type['id']?>!=3)
        {
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplylicence/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no,
                        "ward_mstr_id":ward_id
                }, 
                beforeSend: function() {
                    $('#holding_error').html("Please wait...");
                },              
                success:function(data)
                {
                    if (data.response==true && data.pp != null)
                    {
                        $('#holding_error').html("");
                        var tbody="";
                        var i=1;
                        var prop_id=data.pp['id'];                       
                        var ward_mstr_id=data.pp['ward_mstr_id'];
                        var ward_no=data.pp['ward_no'];
                        var address=data.pp['prop_address'];
                        var city=data.pp['prop_city'];
                        var pincode= data.pp['prop_pin_code'];
                        var owner_business_premises= data.pp['owner_name'];


                        $("#prop_id").val(prop_id);
                        // $("#ward_id").val(ward_mstr_id);
                        $("#ward_no").val(ward_no);
                        $("#firmaddress").val(address);
                        $("#pin_code").val(pincode);  
                        $("#owner_business_premises").val(owner_business_premises);
                        
                    }
                    else
                    {
                        // alert('Holding No. not Found');
                        $('#holding_error').html("<small>Holding no. '"+holding_no+"' not found</small>");
                        $('#holding_success').html("");
                        $("#holding_no").val("");
                        $("#prop_id").val("");
                        //$("#ward_id").val("");
                        $("#ward_no").val("");
                        $("#firmaddress").val("");
                        $("#pin_code").val(""); 
                        $("#owner_business_premises").val("");
                    }
                  
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
              });
        }
          
             
    }
    
    /*function getsqmtr(str)
    {
        var area_in_sqft=str;
        var area_in_sqmt=area_in_sqft/0.092903;
        if(area_in_sqft!="")
        {
          $("#area_in_sqmt").val(area_in_sqmt);
        }
        else
        {
          $("#area_in_sqmt").val("");
        }
        
    }
    function getsqft(str)
    {
        var area_in_sqmt=str;
        var area_in_sqft=0.092903*area_in_sqmt;
        $("#area_in_sqft").val(area_in_sqft);
        //area_in_sqft
    }*/

    function show_hide_saf_holding_box(str)
    {

       var holding_exists=str;
       if(holding_exists=='YES')
       {
          $("#holding_lebel").show();
          $("#holding_div").show();
          $("#saf_div").hide();
          $("#saf_lebel").hide();
          $("#holding_no").attr('required',true);
          $("#saf_no").attr('required',false);
          $("#saf_no").val("");
          $("#saf_id").val("");
          $("#ward_id").val("");
          $("#ward_no").val("");
          $("#firmaddress").val("");
          $("#pin_code").val(""); 

          
        $("#owner_dtl_append").html(appendData);
           
          
       }
       else if(holding_exists=='NO')
       {
          $("#saf_lebel").show();
          $("#saf_div").show();
          $("#holding_div").hide();
          $("#holding_lebel").hide();
          $("#saf_no").attr('required',true);
          $("#holding_no").attr('required',false); 
          $("#holding_no").val(""); 
          $("#prop_id").val("");
          $("#ward_id").val("");
          $("#ward_no").val("");
          $("#firmaddress").val("");
          $("#pin_code").val("");  
          
        $("#owner_dtl_append").html(appendData);
              
       }
       else
       {
          $("#saf_div").hide();
          $("#holding_div").hide();
          $("#saf_no").attr('required',false);
          $("#holding_no").attr('required',false);    
          $("#saf_no").attr('required',false);
          $("#holding_no").attr('required',false);    
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

    function checkDOB() {
        var dateString = document.getElementById('firm_date').value;
        var myDate = new Date(dateString);
        var today = new Date();
        if ( myDate > today ) { 
          //alert(today);
          $("#firm_date").val(today);
            $('#firm_date').after('<p color="Red">You cannot enter future date!.</p>');
            return false;
        }
        return true;
    }

    function validate_saf()
    {


         var saf_no=$("#saf_no").val();         
         //var ward_id=$("#ward_id").val();
         var owner_type=$("#ownership_type_id").val();
         if(saf_no==""){
           $("#owner_dtl_append").html(appendData);
            
            }
            else
            { 

              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplylicence/validate_saf_no");?>',
                dataType: "json",
                data: {
                        "saf_no":saf_no
                },
               
                success:function(data){
                  //console.log(data);
                 // alert(data.payment_status);

                   if (data.response==true) {

                    var tbody="";
                        var i=1;

                        var payment_status = data.sf['payment_status'];
                        var prop_dtl_id=data.sf['prop_dtl_id'];
                        var saf_id=data.sf['id'];
                        var ward_mstr_id=data.sf['ward_mstr_id'];
                        var ward_no=data.sf['ward_no'];
                        var address=data.sf['prop_address'];
                        var city=data.sf['prop_city'];
                        var pincode= data.sf['prop_pin_code'];

                      
                        for(var k in data.dd) {
                          // console.log(k, data.dd[k]['owner_name']);
                           /*var payment_status=data.dd[k]['payment_status'];
                            var prop_dtl_id=data.dd[k]['prop_dtl_id'];*/

                            tbody+="<tr>";
                            

                        //   $("#owner_name").val( data.dd[k]['owner_name']);
                           tbody+='<td><input type="text" name="owner_name[]" id="owner_name'+i+'" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly ></td>';

                           tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name'+i+'" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly></td>';

                            tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no'+i+'" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly></td>';
                            tbody+='<td><input type="text" id="address'+i+'" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td>';
                            tbody+='<td><input type="text" id="city'+i+'" name="city[]"  class="form-control city" placeholder="City" value="'+data.sf['prop_city']+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td>';

                            tbody+='<td><input type="text" id="state'+i+'" name="state[]" readonly  class="form-control state" placeholder="state" value="'+city+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td>';

                         tbody+='<td><input type="text" id="district'+i+'" name="district[]" readonly  class="form-control district" placeholder="district" value="'+city+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td>';

                            
                             tbody+='<td></td>';

                            



                           tbody+="</tr>";
                           i++;

                        }
                          if(payment_status==0)
                          {
                            alert('Please make your payment in SAF first');
                            $("#saf_no").val("");
                          }
                          else if(prop_dtl_id!=0)
                          {
                            alert('Your Holding have been generated kindly provide your Holding no.');
                            $("#saf_no").val("");
                          }
                          else if(payment_status==1)
                          {
                            $("#saf_id").val(saf_id);
                            $("#ward_id").val(ward_mstr_id);
                            $("#ward_no").val(ward_no);
                            $("#firmaddress").val(address);
                            $("#pin_code").val(pincode);                                                        
                            if(owner_type==1){
                              $("#owner_dtl_append").html(tbody);
                            }else{
                              $("#owner_dtl_append").html(appendData);
                            }
                          }
                    
                    
                     // alert(data.data); 
                   } else {

                      alert('SAF No. not Found');
                      $("#saf_no").val("");
                      $("#saf_id").val("");
                      $("#ward_id").val("");
                      $("#ward_no").val("");
                      $("#firmaddress").val("");
                      $("#pin_code").val("");  

                   }
                   //
                },
                error: function(jqXHR, textStatus, errorThrown) {

                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
          }

    }

    var zo = <?=$zo;?>;
    function owner_dtl_append_fun()
    { 
        zo++;
        var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="email" id="emailid'+zo+'" name="emailid[]" class="form-control address" placeholder="Email Id" value=""  onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#owner_dtl_append").append(appendData);
    }
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e)
    { 
        var firmtype_id = $("#firmtype_id").val();
        if(firmtype_id==2)
        { 
            var rowCount = $("#ownertable tbody tr").length; alert(rowCount);
            if(rowCount!=1){
            $(this).closest("tr").remove();
            }
        }
        else
        { alert('Are you sure to remove this row!');
            $(this).closest("tr").remove();
        }
     });

    function forother(firmtype_id)
    { 
        // firmtype_id
        // 5 Other
        // 2 Patnrship
        //alert(firmtype_id);
        if(firmtype_id==5)
        {
            $(".classother").show();
        }
        else if(firmtype_id==2)
        {
            owner_dtl_append_fun();
        }
        else
        {
            $(".classother").hide();
        }
        
        
    }


    function show_district(str,cnt)
    {      

          $.ajax({
            type:"POST",
            url: '<?php echo base_url("tradeapplylicence/getdistrictname");?>',
            dataType: "json",
            data: {
                    "state_name":str
            },
           
            success:function(data){
              //console.log(data);
              var option ="";
              jQuery(data).each(function(i, item){
                  option += '<option value="'+item.name+'">'+item.name+'</option>';
                 // console.log(item.id, item.name)
              });
              $("#district"+cnt).html(option);
                
            }
               
        });

    }
    let denial_ammount = 0;
    function getDenialDetails()
    {
      var noticeNo = $('#noticeNo').val(); 
      var firm_date = $('#firm_date').val(); 
      $("#licence_for option:selected").prop("selected", false);
      if(noticeNo!="")
      {
        jQuery.ajax({  
        type:"POST",
        url: '<?php echo base_url("TradeCitizen/getDenialDetails");?>',
        dataType: "json",
        data: {
            "noticeNo":noticeNo, "firm_date":firm_date
            },
        success: function(data) { 
        if(data!="noData")
        {
            console.log(data);

                     $('#old_ward_id option[value='+data.denialDetails.ward_id+']').prop('selected','selected');
                     $("#holding_no").val(data.denialDetails.holding_no);   
                     $('#firm_name').val(data.denialDetails.firm_name);
                     $("#firmaddress").val(data.denialDetails.address);
                     $('#landmark').val(data.denialDetails.landmark);
                     $('#pin_code').val(data.denialDetails.pincode);
                     $('#owner_business_premises').val(data.denialDetails.applicant_name); 
                     $('#denialAmnt').val(data.denialAmount);   
                     $('#noticedate').val(data.denialDetails.noticedate);
                     $('#dnialID').val(data.denialDetails.id);
                     denial_ammount = data.denialAmount;
         }
         else
         {
             alert("Notice number not found. \nPlease enter correct notice no. \nor check your firm establishment  date. It should not be greater then notice date!");
             $('#noticedate').val("");
             $('#noticeNo').val("");
             $("#owner_business_premises").val("");
             $('#dnialID').val("");
         }
        }  
        }); 
      }
    }
    function show_charge()
    { 

        var timefor = $("#licence_for").val();
        var str =  $("#area_in_sqft").val();
        var edate =  $("#firm_date").val();
        var noticedate = $("#noticedate").val();
        var nature_of_business = $("#tade_item1").val();
        // $('#tade_item1').val();
        // alert(nature_of_business);
        
        console.log(nature_of_business);
        console.log(nature_of_business.length);
        console.log(nature_of_business[0]);

        if(nature_of_business.length<=1){
            nature_of_business=nature_of_business[0];
            // alert(nature_of_business[0]);
        }else{

            nature_of_business ="multiple_values";
            
        }
        //alert(nature_of_business);
        // console.log(nature_of_business);

        if(<?=$application_type['id']?>==1)
        {
            if(edate > noticedate && noticedate!="")
            {
                // $(".hideNotice").css("display","none");
                $("#denialAmnt").val(0);
                alert("Notice date should not be smaller then Firm establishment date");
                // $("#applyWith option:selected").prop("selected", false);
                $("#noticeNo").val("");
                $("#noticedate").val("");
                $("#owner_business_premises").val("");
            }
        }
        if(str!="" && timefor!="")
        {
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplylicence/getcharge");?>',
                dataType: "json",
                data: {
                        "areasqft":str,
                        "applytypeid":<?=$application_type["id"]?>,
                        "estdate":edate, 
                        "licensefor":timefor, 
                        "tobacco_status":0,
                        "nature_of_business":nature_of_business,
                        <?php if($application_type["id"]>1){echo'"apply_licence_id":'.$apply_licence_id??null;}?>
                },
                beforeSend:function(){
                    $("#charge").val(0);
                        $("#penalty").val(0);
                        $("#total_charge").val(0);
                        $("#denialAmnt").val(0);
                },
            
                success:function(data){
                    console.log(data);
                    // alert(denial_ammount);
                    if (data.response==true) 
                    {
                        var cal = data.rate * timefor;
                        $("#charge").val(data.rate);                
                        $("#penalty").val(data.penalty);
                        //$("#total_charge").val(data.total_charge);
                        // var ttlamnt = parseInt(data.total_charge) + parseInt($("#denialAmnt").val());
                        var ttlamnt = parseInt(data.total_charge) + parseInt(denial_ammount);
                        $("#total_charge").val(ttlamnt);
                        // var dna = (ttlamnt-data.penalty);
                        // if(data.penalty >0)
                            $("#denialAmnt").val(parseInt(denial_ammount)+parseInt(data.arear_amount));
                    }
                    else
                    {

                        $("#charge").val(0);
                        $("#penalty").val(0);
                        $("#total_charge").val(0);
                        $("#denialAmnt").val(0);

                    }
                }
                
            });
        }
        
        <?php
            if($application_type["id"]==21)
            {
                ?>
                    var for_year = $('#licence_for').val();                    

                    var valid_from = $('#firm_date').val();
                    // console.log(valid_from,for_year);
                    //alert(for_year);alert(valid_from); 
                    $('#btn_review').display='none';
                    $('#btn_review').hide();                    
                    jQuery.ajax({  
                    type:"POST",
                    url: '<?php echo base_url("TradeCitizen/re_day_diff");?>'+'/'+valid_from+'/'+for_year+'/'+'ajax',
                    dataType: "json", 
                                     
                    success: function(data) {
                        console.log(data); 
                        // alert(data);                        
                        if(parseInt(data.diff_day)<0)
                        {
                            $("#licence_for option:selected").prop("selected", false); 
                            $("#charge").val(''); 
                            $("#penalty").val(''); 
                            $("#total_charge").val('');                                
                        }
                        
                        $('#btn_review').show();
                      
                    }  
                    });
                <?php
            }
        ?>

    }

    var ti = <?=$ti;?>;
    function trade_item_append_fun(){
        ti++;
        // var selected_item = document.getElementsByName("tade_item[]");
        // console.log('selected_item ');
        // console.log(selected_item[0].selected);

        // $("#"+selected_item[0]:selected).remove();
        // var items_id=[];
        // for(var i=0;i<=selected_item.length;i++){
        //     items_id []= selected_item[i].value;
        // }
        var tappendData = '<tr><td><select id="tade_item'+ti+'" name="tade_item[]" required="required" class="form-control tade_item"  onchange="show_charge()"><option value="">SELECT</option><?php if($tradeitemlist){foreach($tradeitemlist as $valit){?><option value="<?php echo $valit['id'];?>" ><?php echo $valit['trade_item'];?></option><?php }}?></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>&nbsp;<i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i></td></tr>';
        $("#trade_item_append").append(tappendData);

        $(".tade_item option[value='185']").each(function() {
            $(this).remove();
        });
    }

    $("#trade_item_append").on('click', '.remove_trade_item', function(e) {
        $(this).closest("tr").remove();
    });

    
    $(".tade_item option[value='185']").each(function() {
        $(this).remove();
    });
    function getpenalty(d1)
    {
        d1 = new Date(d1);
        var d2 = new Date('<?=date("Y-m-d");?>');
        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth();
        months += d2.getMonth();
        month = months <= 0 ? 0 : months;
        return month;
    }

    function myFunction() {
        var mode = document.getElementById("payment_mode").value;
        if (mode == 'CASH' || mode =='')
        {
            $('#chqno').hide(); 
            $('#chqbank').hide();
        }
        else
        {
            $('#chqno').show(); 
            $('#chqbank').show();
        }
    }

function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php if($result = flashToast('applylicence')) { ?>
	modelInfo('<?=$result;?>');
<?php }?>

</script>

<script>
    

</script>
 

 

<!-- <script>
    function getDenialPrice(denialId)
    {
        var applicntype = $("#appltype").val();
        jQuery.ajax({  
        type:"POST",
        url: '<?php echo base_url("TradeCitizen/getDenialAmountById");?>',
        dataType: "json",
        data: {
            "denialId":denialId,
            },
        success: function(data) { 
          if(data.nodata!="nodata"){
            $("#DenialAmount").html(data.denialAmount);
            $("#dnialID").val(denialId);
            if(applicntype == 3)
            {
                show_charge();
            }
           }
        }  
        }); 
    }
</script> -->
<script>

function showHideNotice(){
    denial_amount = 0;
    var aplwth = $('#applyWith').val();
    if(aplwth == 1){
        $(".hideNotice").css("display", "block");
    }else{
        $(".hideNotice").css("display", "none");
    }
}
  
    
    $("#applyWith").change(function() {
        denial_ammount = 0;
        var aplwth = $("#applyWith").val();
        if(aplwth == 1)
        {
            $(".hideNotice").css("display","block");
        }
        else
        {
            $(".hideNotice").css("display","none");
        }
        });

        function wordCounter(text) 
        {
            var text = input.value;
            var wordCount = 0;
            for (var i = 0; i <= text.length; i++) {
                if (text.charAt(i) == ' ') {
                wordCount++;
                }
            }
            count.innerText = wordCount;
        }
        <?php
        if($application_type["id"]==1)
        {
            ?>
            function woner_permisses ()
            { 
                var holding_no = $('#holding_no').val();
                if(holding_no!='')
                { 
                    document.getElementById('owner_business_premises').setAttribute('readonly','');
                }
                else
                {
                    document.getElementById('owner_business_premises').removeAttribute('readonly');
                    document.getElementById('owner_business_premises').setAttribute('onkeypress','return isAlpha(event)');
                }
            }
            $(document).ready(function(){
                woner_permisses();
            });
            <?php
        }
        ?>
</script>

 