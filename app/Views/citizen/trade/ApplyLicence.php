<?= $this->include('layout_home/header');?>
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
    
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post"  >
                <?php if(isset($validation)){ ?>
                    <?=$validation->listErrors(); ?>
                <?php } ?>
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
                        <div class="col-md-3 pad-btm">
                            <?php 
                            if($application_type["id"]==1 or $application_type["id"]==3)
                            {
                                ?>
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
					            <?php 
                            }
                            else
                            {
                                echo  $firm_type;
                                if($firm_type == "OTHER")
                                echo "($otherfirmtype)";
                            }
                            ?>                       
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
					<div class="row">
						<label class="col-md-2">Category<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<?php if($application_type["id"]==1){?>
							<select name="category_type_id" id="category_type_id"  class="form-control">
                            <option value="">Select</option>
                            <?php
                            if($categoryTypeDetails)
                            {
								foreach($categoryTypeDetails as $vdata)
                                {
                                    ?>
									<option value="<?php echo $vdata['id'];?>" <?php if(isset($category_type_id) && $category_type_id==$vdata['id']){ echo "selected";}?>><?php echo $vdata['category_type'];?>
									</option>
								    <?php 
                                }
                            }
                            ?>
                          </select>
                          <?php }else{ echo  $categoryDetails["category_type"]?$categoryDetails["category_type"]:"N/A";} ?> 
                        </div>
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
					<div class="row">  
						<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select name="old_ward_id" id="old_ward_id"  class="form-control" onchange="validate_holding(),openModel()">
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
						<label class="col-md-2" id="holding_lebel" >Holding No. </label>

						<div class="col-md-3 pad-btm" id="holding_div" >
                            <input type="text" name="holding_no" id="holding_no" class="form-control" onchange="validate_holding()" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);"  onblur="woner_permisses()">
                            <input type="hidden" name="prop_id" id="prop_id" />
						</div>
					</div>  

                    <input type="hidden" value="0" name="tobacco_status">

					<div class="row">
						<label class="col-md-2">Firm Name<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input  onkeyup="openModel()" type="text" name="firm_name" id="firm_name" class="form-control" value="<?php echo isset($firm_name)?$firm_name:""; ?>"  onkeypress="return isAlphaNum(event);">                       
                            <div class="modal fade" id="firmName" data-keyboard="false" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h4 class="modal-title">Please Select Your Firm</h4>
                                            <button type="button"  class="close closeModel" >&times;</button>

                                            </div>
                                        
                                            <div class="modal-body">
                                            <div class="table-responsive">
                                <table class="table table-bordered text-sm" id="owner_table">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th></th>
                                            <th>Firm Name</th>
                                            <th>Firm Owner Name </th> 
                                            <th>Notice No.</th>                                           
                                            <th>Ward Name</th>
                                            <th>View</th> 
 
                                        </tr>
                                    </thead>
                                    <tbody id="denial_dtl_append">
                                        
                                     </tbody>
                                </table>
                                <a style="color:#ff5e00;">Note :- (Denial Firm List)</a>
                             </div>
                          </div>       
                            <div class="modal-footer">
                                <a style="float:left;color:#0e0c9a" id="DenialAmount"></a>
                                <input type="hidden" name="dnialID" id="dnialID">
                            <button type="button" class="btn btn-primary closeModel" >Ok</button>
                            </div>  
                        </div>
                        </div>
                        </div>
                        </div>

						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm"> 
							<input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onblur="show_charge()" value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>" onkeypress="return isNumDot(event);">
						</div>                    
					</div>
					
					<div class="row">
						<label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="date" name="firm_date" id="firm_date" class="form-control" value="<?php echo isset($firm_date)?$firm_date:""; ?>" onchange="show_charge(); checkDOB()"  onkeypress="return isNum(event);"> 

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
                    <div class="row"> 
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
						<label class="col-md-2">Owner of Business Premises</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" readonly="" name="owner_business_premises" id="owner_business_premises"  class="form-control" value="<?php echo isset($owner_business_premises)?$owner_business_premises:""; ?>" />
                        </div>
					</div>
                    <div class="row"> 
                        <label class="col-md-2">Business Description<span class="text-danger">*</span></label>
                        <div class="col-md-10 pad-btm">
                           <textarea name="brife_desp_firm" id="brife_desp_firm" style="width: 80%;" required><?=isset($_POST['brife_desp_firm'])?$_POST['brife_desp_firm']:''?></textarea>
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
						<label class="col-md-2">Landmark </label>
						<div class="col-md-3 pad-btm">
							<?php  echo $landmark; ?>
						</div>
                    </div>
                    <div class="row"> 
                        <label class="col-md-2">Business Description<span class="text-danger">*</span></label>
                        <div class="col-md-10 pad-btm">
                           <?=isset($licencedet['brife_desp_firm'])?$licencedet['brife_desp_firm']:'N/A'?>
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
                                                    <table class="table table-bordered text-sm">
                                                        <thead class="bg-trans-dark text-dark">
                                                            <tr>
                                                                <th>Owner Name <span class="text-danger">*</span></th>
                                                                <th>Guardian Name <span class="text-danger">*</span></th>                                            
                                                                <th>Mobile No <span class="text-danger">*</span></th>
                                                                <th>Email Id <span class="text-danger">*</span></th> 
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
                                    <div class="panel panel-bordered panel-dark" >
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
                                    </div>
								    <?php 
                                }
                                ?>
                            
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
                                                        <select id="tade_item<?=$ti?>" name="tade_item[]" class="form-control tade_item demo-select2-multiple-selects" required="required"  onchange="borderNormal(this.id);" multiple="multiple">
                                                            <optgroup label="Central Time Zone">
                                                            <!-- <option value="">SELECT</option> -->
                                                            <?php
                                                    if($tradeitemlist)
                                                    {

                                                        foreach($tradeitemlist as $valit)
                                                        {
                                                        ?>
                                                        <option value="<?php echo $valit['id'];?>" <?php if($itemvalue["id"]==$valit['id']){echo "selected"; }?> ><?php echo $valit['trade_item'];?></option>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                        </select>
                                                    </td>
                                                    
                                                    <td class="text-2x">
                                                        <!-- <i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>
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
                                                            <option value="<?php echo $valit['id'];?>" ><?php echo $valit['trade_item'];?></option>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                
                                                <td class="text-2x">
                                                    <!-- <i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i> -->
                                                    &nbsp;
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
                                                            <?=$trade["trade_item"]?>
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
                        <h3 class="panel-title">Nature Of Business <?php if($tobacco_status==1) {echo'<span class="text text-danger">(Tobbaco)</span>';}?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row" >
                            <div class="col-md-12">
                                <div class="">
                                <?php
                                    $ti = 0; 
                                    if(!empty($tradeitemdet))
                                    {
                                
                                        //foreach ($tradedetail as  $itemvalue)
                                        {
                                            $ti++;
                                            ?>
                                            <select id="tade_item<?=$ti?>" name="tade_item[]" class="form-control tade_item demo-select2-multiple-selects" multiple="multiple" required="required"  onchange="borderNormal(this.id);" readonly disabled>
                                                <option value="">SELECT</option>
                                                <?php
                                                if($tradeitemlist)
                                                {

                                                    foreach($tradeitemlist as $valit)
                                                    {
                                                    ?>
                                                    <option value="<?php echo $valit['id'];?>" <?php if(in_array($valit['id'],$tradeitemdet)){echo "selected"; }?> ><?php echo $valit['trade_item'];?></option>
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
                                                <option value="<?php echo $valit['id'];?>" ><?php echo $valit['trade_item'];?></option>
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
            }
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
                                    <input type="text" id="charge"  disabled="disabled" class="form-control" value="<?php echo $rate ?? NULL;?>"  onkeypress="return isNum(event);" />
                                </div>
                            </div>  
                            <div class="row">                    

                            <label class="col-md-2">Penalty<span class="text-danger">*</span></label>

                                <div class="col-md-3 pad-btm">
                                    <input type="text"  id="penalty"  disabled="disabled" class="form-control" value="<?php echo $penalty ?? NULL;?>"  onkeypress="return isNum(event);" />
                                </div>
                            
                                <label class="col-md-2">Denial Amount / Arrers<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text"  id="denialAmnt"  disabled="disabled" class="form-control"   onkeypress="return isNum(event);" required />
                                </div>
                                </div>


                                <div class="row">                    
                                <label class="col-md-2">Total Charge<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text"  id="total_charge"  disabled="disabled" class="form-control" value="<?php echo $total_charge?? NULL;?>"  onkeypress="return isNum(event);" required />
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
                        
                            <input type="hidden" name="apply_from" value="JSK" />
                            <button type="submit" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
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
<?= $this->include('layout_home/footer');?>
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
                    alert(JSON.stringify(jqXHR));
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
                category_type_id:{
                    required:true
                },
                old_ward_id:{
                    required:true
                },
                holding_no:{
                    //required:true
                },
                new_ward_id:{
                    required:true
                },
                owner_business_premises:{
                    required:true
                },
                firm_name:{
                    required:true
                },
                firm_date:{
                    required:true
                },
                area_in_sqft:{
                    required:true
                },
                address:{
                    required:true
                },                
                pin_code:{
                    required:true
                },                
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
                licence_for:{
                    required:true
                },   
                firmaddress:{
                    required:true
                },
                landmark:{
                    required:true
                },
                payment_mode:{
                    required:true
                },
                "owner_name[]":{
                    required:true,
                    minlength:3
                },
                "guardian_name[]":{
                    required:true,
                    minlength:3
                },
                "mobile_no[]":{
                    required:true,
                    minlength:10,
                    maxlength:10,
                },
                "emailid[]":{
                    required:true,
                    email: true,
                },
                "id_no[]":{
                    required:true,
                },
                firmtype_other:{
                    required: true 
                },
                brife_desp_firm:{
                    required: true 
                }

            },
            messages:{
                firmtype_id:{
                    required:"Please select Firm Type"
                },
                ownership_type_id:{
                    required:"Please select Ownership Type"
                },
                category_type_id:{
                    required:"Please select Category type."
                },
                old_ward_id:{
                    required:"Please select Old Ward No."
                },
                holding_no:{
                    required:"Please Enter Holding No"
                },
                new_ward_id:{
                    required:"Please select New Ward No."
                },
                owner_business_premises:{
                    required:"Owner of Business Premises is required. (Please Enter Valid Holding No)"
                },
                firm_name:{
                    required:"Please Enter Firm Name"
                },
                firm_date:{
                    required:"Please Enter Firm Establishment Date"
                },
                area_in_sqft:{
                    required:"Please Enter Area"
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
                "guardian_name[]":{
                    required:"Please Enter Guardian Name"
                },
                "mobile_no[]":{
                    required:"Please Enter Mobile No."
                },
                "emailid[]":{
                    required:"Please Enter Valid Email Id"
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
            },
            submitHandler: function(form) {
                var firmtype_id = $("#firmtype_id").val();
                var totalOwnerCount=$('input[name="owner_name[]"]').length;
                console.log(firmtype_id);
                console.log(totalOwnerCount);
                if(firmtype_id == 2) // Partnership
                {
                    if(totalOwnerCount >= 2){
                        // do nothing
                    }
                    else
                    {
                        alert('Incase of Partnership Firm Type, You have to provide atleast two owner details');
                        return false;
                    }
                }

                <?php 
                if($application_type['id']!=4) // Surrender
                {
                    ?>
                    if(confirmsubmit())
                    {
                        
                        form.submit();
                    }
                    <?php
                }
                else
                {
                    ?>
                    form.submit();
                    <?php
                }
                ?>
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
    var amt = $('#total_charge').val();
    if(amt == "" || amt == null)
    {
        alert("Please Fill Complete Details");
        return false;
    }
    else
    {
        return confirm("Are you sure you want to confirm Payment of Rs "+amt+"?");
    }
}
function ValidateEmail(e) 
{   
    var keyCode = (e.which) ? e.which : e.keyCode;
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(e))
    {
        return (true);
    }
        alert("You have entered an invalid email address!");
        return (false);
}



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

     var appendData = '<tr><td><input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="email" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeypress="return ValidateEmail(event);" onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i></td></tr>';

    function validate_holding()
    {  
        var holding_no=$("#holding_no").val();
        var firmtype_id=$("#firmtype_id").val();        
        var ward_id=$("#old_ward_id").val();
        // alert(ward_id);
        var owner_type=$("#ownership_type_id").val();
        if(ward_id=='' && holding_no!='')
        {
            alert('Please Ward No.');
            return false;
        }         
        if(holding_no=="")
        {
             $("#owner_dtl_append").html(appendData);
        }
        else
        {
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("TradeCitizen/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no,
                        "ward_mstr_id":ward_id
                },               
                success:function(data)
                {
                    if (data.response==true && data.pp != null)
                    {
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
                        alert('Holding No. not Found');
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
          alert(today);
          $("#firm_date").val(today);
            $('#firm_date').after('<p style="color: red;">You cannot enter future date!.</p>');
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
        var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="email" id="emailid'+zo+'" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeypress="return ValidateEmail(event);" onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#owner_dtl_append").append(appendData);
    }
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e)
    {
        $(this).closest("tr").remove();
    });

    var ti = <?=$ti;?>;
    function trade_item_append_fun(){
        ti++;
        var tappendData = '<tr><td><select id="tade_item'+ti+'" name="tade_item[]" required="required" class="form-control tade_item"  onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if($tradeitemlist){foreach($tradeitemlist as $valit){?><option value="<?php echo $valit['id'];?>" ><?php echo $valit['trade_item'];?></option><?php }}?></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>&nbsp;<i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i></td></tr>';
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
    function getDenialPrice(denialId)
    {
        
        jQuery.ajax({  
        type:"POST",
        url: '<?php echo base_url("TradeCitizen/getDenialAmountById");?>',
        dataType: "json",
        data: {
            "denialId":denialId,
            },
        success: function(data) 
        { 
          if(data.nodata!="nodata")
          {
            $("#DenialAmount").html(data.denialAmount);
             denial_ammount =denialAmount;
            $("#dnialID").val(denialId);
           }
        }  
        }); 
    }

    function show_charge()
    { 
        var timefor = $("#licence_for").val();
        var str =  $("#area_in_sqft").val();
        var edate =  $("#firm_date").val();
        var denialamnttxt = $("#DenialAmount").text();
        var denialamnt = denialamnttxt.split("-");
        var denialamntfnl = denialamnt[1];
        denialamntfnl = denialamntfnl=='' || denialamntfnl==NaN || denialamntfnl==undefined ? 0 : denialamntfnl;
        var nature_of_business = $("#tade_item1").val();
        if(nature_of_business.length<=1){
            nature_of_business=nature_of_business[0];
            // alert(nature_of_business[0]);
        }else{

            nature_of_business ="multiple_values";
            
        }

        
        if(str!="" && timefor!="")
        {
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("TradeCitizen/getcharge");?>',
                dataType: "json",
                data: {
                    "areasqft":str,
                    "applytypeid":<?=$application_type["id"]?>, 
                    "estdate":edate,
                    "licensefor":timefor,
                    "tobacco_status": 0,
                    "nature_of_business":nature_of_business,
                    <?php if($application_type["id"]>1){echo'"apply_licence_id":'.($apply_licence_id??null);}?>
            },
            beforeSend:function(){
                $("#charge").val(0);
                $("#penalty").val(0);
                $("#total_charge").val(0);
                $("#denialAmnt").val(0);
            },
            success:function(data)
            {
                console.log(data);
                if (data.response==true)
                {
                    var cal = data.rate * timefor;
                    $("#charge").val(data.rate);                
                    $("#penalty").val(data.penalty);
                    //$("#total_charge").val(data.total_charge);
                    $("#denialAmnt").val(denialamntfnl);
                   var ttlamnt = parseInt(data.total_charge) + parseInt(denialamntfnl);
                    $("#total_charge").val(ttlamnt);
                    // var dna = (ttlamnt-data.penalty);
                    //     if(data.penalty >0)
                    //         $("#denialAmnt").val(dna);
                    $("#denialAmnt").val(parseInt(denialamntfnl)+data.arear_amount);

                }
                else
                {
                    $("#charge").val('');
                    $("#penalty").val('');
                    $("#total_charge").val('');
                    $("#denialAmnt").val('');
                }
            }
        });
        }  
    }

    function borderNormal()
    {

    }

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

    function myFunction() 
    {
        var mode = document.getElementById("payment_mode").value;
        if (mode == 'CASH')
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

function modelInfo(msg)
{
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
    $( document ).ready(function() {
        show_charge();
});
</script>

<script>
    function openModel()
    {
      var firmName = $('#firm_name').val();
      var wardId = $('#old_ward_id').val();
      if(firmName!="" && wardId!="")
      {
        $("#denial_dtl_append").empty();
        jQuery.ajax({  
        type:"POST",
        url: '<?php echo base_url("TradeCitizen/getDenialFirmName");?>',
        dataType: "json",
        data: {
            "firmName":firmName,"wardId":wardId,
            },
        success: function(data) { 
          if(data.nodata!="nodata"){
            document.onkeydown = function (e) {
            return false;}
        $("#DenialAmount").html("");   
        $('#firmName').modal('show');
        $("#denial_dtl_append").html(data.denialDetails);
        
          }
        }  
        }); 
      }
   }

</script>

<script>
   $(".closeModel").click( function()
           {
            if($('.selectFirm').is(':checked')) 
                { 
                    $('#firmName').modal('hide'); 
                    document.onkeydown = function (e) {
                    return true;}
                }
                else
                {
                    alert("Please Select Your Firm!")
                }
           }
        );
</script>

<script>
    
</script>

<script>
    $("#licence_for").change(function()
    {
        if($("#firm_name").val()=="")
        {
            alert("Please Select Firm Name");
            $("#licence_for option:selected").prop("selected", false);
        }
        <?php
            if($application_type["id"]==21)
            {
                ?>
                    var for_year = $('#licence_for').val();
                    var valid_from = $('#firm_date').val();
                    //alert(for_year);alert(valid_from); 
                    $('#btn_review').display='none';
                    $('#btn_review').hide();                    
                    jQuery.ajax({  
                    type:"POST",
                    url: '<?php echo base_url("TradeCitizen/re_day_diff");?>'+'/'+valid_from+'/'+for_year+'/'+'ajax',
                    dataType: "json", 
                                     
                    success: function(data) {
                        console.log(data);                         
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
        
     });

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


 