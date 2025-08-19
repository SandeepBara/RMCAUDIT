
<?= $this->include('layout_vertical/header');?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Apply Licence </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post"  >
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply Licence</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                      
                 
                    <div class="row">
                        <label class="col-md-2">Application Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 control-label text-semibold">
                            <?=$application_type["application_type"]?><?=$_SERVER['http_x_forwarded_for']?>
                        </div>
                        <label class="col-md-2">Firm Type<?php echo $firm_type_id;?> <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm"><?php if($application_type["id"]==1 or $application_type["id"]==3){?>
							<select name="firmtype_id" id="firmtype_id" onchange="validate_holding()" class="form-control" >
								<option value="">Select</option>
								<?php
								if($firmtypelist)
								{
									foreach($firmtypelist as $val)
									{

										?>
										<option value="<?php echo $val['id'];?>" <?php if($firm_type_id==$val['id']){echo "selected"; }?>><?php echo $val['firm_type'];?></option>
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

							<option value="<?php echo $val['id'];?>" <?php if($ownership_type_id==$val['id']){ echo "selected";}?>><?php echo $val['ownership_type'];?></option>
                          		<?php
                          	}
                          	}
                          	?>
                          </select>
                          <?php }else{ echo  $ownership_type;} ?> 
                        </div>

                        

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

                     

                  <div class="row"  >                      
                       <label class="col-md-2" id="holding_lebel" >Holding No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm" id="holding_div" >
                             <input type="text" name="holding_no" id="holding_no" class="form-control" onblur="validate_holding()" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);">
                             <input type="hidden" name="prop_id" id="prop_id">
                       </div>

                       <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                        <input type="hidden" name="ward_id" id="ward_id"><input type="text" name="ward_no" value="<?php echo isset($ward_no)?$ward_no:""; ?>"  readonly="readonly" id="ward_no" style="border: 0; ">
                         
                       </div>

                  </div>  

                  <div class="row">

                    <label class="col-md-2">Firm Name<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                       
                         <input type="text" name="firm_name" id="firm_name" class="form-control" value="<?php echo isset($firm_name)?$firm_name:""; ?>"  onkeypress="return isAlphaNum(event);">                       
                       </div>

                  <label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm"> 

                          <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onblur="show_charge()"  
                           value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>" onkeypress="return isNumDot(event);">
                            
                       </div>                    


                  </div>
                  <div class="row">

                    <label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                       
                         <input type="date" name="firm_date" id="firm_date" class="form-control" value="<?php echo isset($firm_date)?$firm_date:date('Y-m-d'); ?>"  onkeypress="return isNum(event);">                     
                       </div>
                   
                       <label class="col-md-2">Address<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input name="firmaddress" id="firmaddress" class="form-control" readonly="readonly" onkeypress="return isAlphaNum(event);" value="<?php echo isset($address)?$address:"";?>" >                         
                          
                       </div>
                        


                  </div>


                  <div class="row">

                  

                        <label class="col-md-2">Landmark</label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="landmark"  id="landmark" class="form-control" value="<?php echo isset($landmark)?$landmark:""; ?>"  onkeypress="return isAlphaNum(event);">
                       </div>

                       <label class="col-md-2">Pin Code<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="pin_code" id="pin_code" readonly="readonly" maxlength="6" minlength="6" class="form-control" value="<?php echo isset($pin_code)?$pin_code:""; ?>" onkeypress="return isNum(event);">
                       </div>
                  </div>

                  

                 

                  
                 

               </div>
               <?php } else{?>
                <div class="panel-body">
                  

                  <div class="row"  >
                      <label class="col-md-2" id="saf_lebel" >Holding / SAF No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm" id="saf_div" >
                          <?=$applylicencedet["holding_no"]?>
                       </div>
                       

                       <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                       <?=$applylicencedet["ward_no"]?>
                         
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
                            <input type="hidden" name="area_in_sqft" id="area_in_sqft" class="form-control"   
                           value="<?php  echo $licencedet["area_in_sqft"]; ?>" >
                         <?php }?>
                          
                       </div>                    


                  </div>
                  <div class="row">

                    <label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                        <?php  echo $licencedet["establishment_date"]; ?>
                       </div>
                   
                       <label class="col-md-2">Address<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <?php  echo $licencedet["firm_address"]; ?>
                            
                          
                       </div>
                        


                  </div>


                  <div class="row">

                  

                        <label class="col-md-2">Landmark</label>

                       <div class="col-md-3 pad-btm">
                          <?php  echo $licencedet["landmark"]; ?>
                       </div>

                       <label class="col-md-2">Pin Code<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <?php  echo $licencedet["pin_code"]; ?>
                       </div>
                  </div>

                  

                 

                  
                 

               </div>
               <?php }?>
           </div>

           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive"><?php if($application_type["id"]==1){
                          ?> 
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name <span class="text-danger">*</span></th>
                                            <th>Guardian Name</th>                                            
                                            <th>Mobile No <span class="text-danger">*</span></th>
                                            <th>Email Id</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Id Proof</th>
                                            <th>Id No.</th>
                                            <th>Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                      
                                <?php
                                $zo = 1;
                                
                                ?>
                                        <tr>
                                            <td>
                                                <input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td>
                                                <input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>                                            
                                            <td>
                                                <input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                                <input type="text" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" />
                                            </td>
                                            <td>
                                                <input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" />
                                            </td>
                                            <td>
                                                <input type="text" id="city1" name="city[]" class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                              <select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control">
                                              <option value="">Select</option>
                                              <?php
                                              if($statelist)
                                              {

                                                foreach($statelist as $valo)
                                                {
                                                ?>

                                                <option value="<?php echo $valo['name'];?>" ><?php echo $valo['name'];?></option>
                                                <?php
                                                }
                                              }
                                              ?>
                                            </select>
                                            </td>
                                            <td>
                                                <select name="district[]" id="district1" class="form-control">
                                                <option value="">Select</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="idproof[]" id="idproof1" style="width: 100px;" class="form-control">
                                                <option value="">Select</option>
                                                <option value="PAN Card">PAN Card</option>
                                                <option value="Aadhaar">Aadhaar</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="id_no1" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  />
                                            </td>
                                             
                                            <td class="text-2x">
                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                                &nbsp;
                                            </td>
                                        </tr>
                                
                                    </tbody>
                                </table>
                                <?php }else{?>
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name <span class="text-danger">*</span></th>
                                            <th>Guardian Name</th>                                            
                                            <th>Mobile No <span class="text-danger">*</span></th>
                                            <th>Email Id</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Id Proof</th>
                                            <th>Id No.</th>                                            
                                        </tr>
                                    </thead>                                   
                                      
                                <?php
                                $zo = 1;
                                if(isset($ownerdet)){
                                  if(!empty($ownerdet)){

                                    foreach ($ownerdet as  $value) {                                    
                                
                                ?>
                                        <tr>
                                            <td>
                                                <?=$value["owner_name"]?>
                                            </td>
                                            <td>
                                                <?=$value["guardian_name"]?>
                                            </td>                                            
                                            <td>
                                                <?=$value["mobile"]?>
                                            </td>
                                             <td>
                                               <?=$value["address"]?>
                                            </td>
                                            <td>
                                               <?=$value["address"]?>
                                            </td>
                                            <td>
                                                <?=$value["city"]?>
                                            </td>
                                            <td>
                                              <?=$value["district"]?>
                                            </td>
                                            <td>
                                                <?=$value["state"]?>
                                            </td> 
                                             <td>
                                              <?=$value["district"]?>
                                            </td>
                                            <td>
                                                <?=$value["state"]?>
                                            </td>                                             
                                            
                                        </tr>
                                      <?php     }
                                            }
                                         }
                                      ?>
                                
                                    </tbody>
                                </table>
                              <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         	
           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Nature Of Business</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                              <?php if($application_type["id"]==1 or $application_type["id"]==3){
                          ?> 
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Business <span class="text-danger">*</span></th>
                                            
                                            <th>Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="trade_item_append">
                                <?php
                                $ti = 0;
                                if(isset($tradeitemdet)){
                                  if(!empty($tradeitemdet)){
                                    foreach ($tradeitemdet as  $itemvalue) {
                                     
                                   $ti++;
                                ?>
                                        <tr>                                            
                                            <td>
                                                <select id="tade_item<?=$ti?>" name="tade_item[]" class="form-control tade_item" required="required"  onchange="borderNormal(this.id);">
                                                    <option value="">SELECT</option>
                                                    <?php
                                              if($tradeitemlist)
                                              {

                                                foreach($tradeitemlist as $valit)
                                                {
                                                ?>

                                                <option value="<?php echo $valit['id'];?>" <?php if($itemvalue["trade_items_id"]==$valit['id']){echo "selected"; }?> ><?php echo $valit['trade_item'];?></option>
                                                <?php
                                                }
                                              }
                                              ?>
                                                </select>
                                            </td>
                                            
                                            <td class="text-2x">
                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>
                                                &nbsp;
                                                <?php if($ti>1){?>
                                                <i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i>
                                              <?php }?>
                                            </td>
                                        </tr>
                                  <?php 
                                      }
                                  }
                                }else{
                                   $ti = 1;
                                   ?>
                                   <tr>                                            
                                            <td>
                                                <select id="tade_item1" name="tade_item[]" class="form-control tade_item" required="required"  onchange="borderNormal(this.id);">
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
                                            </td>
                                            
                                            <td class="text-2x">
                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>
                                                &nbsp;
                                            </td>
                                        </tr>
                                   <?php 
                                }
                                   ?>
                                    </tbody>
                                </table>

                                <?php }else{?>

                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Trade Item <span class="text-danger">*</span></th>
                                            <th>Trade Code <span class="text-danger">*</span></th>                                          
                                            
                                        </tr>
                                    </thead>
                                    
                                <?php
                                $ti = 1;
                                 if(isset($tradeitemdet)){
                                  if(!empty($tradeitemdet)){

                                    foreach ($tradeitemdet as  $tradevalue) {   
                                ?>
                                        <tr>                                            
                                            <td>
                                                 <?=$tradevalue["trade_item"]?>
                                            </td>
                                            <td>
                                                 <?=$tradevalue["trade_code"]?>
                                            </td>
                                            
                                            
                                        </tr>
                                      <?php  }
                                          }
                                        }
                                       ?>
                                
                                    </tbody>
                                </table>
                              <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         	 
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Licence Required for the Year</h3>
                </div>
                <div class="panel-body">               



                  
                  <div class="row">                    

                   <label class="col-md-2">Licence For<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                        <?php if($application_type["id"]==3){ ?>
                         <input type="hidden" id="licence_for"  value="3"><?=$Chargeforyear?>
                       <?php }else{
                        ?>
                        <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()">
                           <option value="">--Select--</option>
                           <option value="1">1 Year</option>
                           <option value="3">3 Year</option>
                           <option value="5">5 Year</option>
                         </select>
                        
                        <?php 
                       } ?>
                       </div>                       
                       <label class="col-md-2">Charge Applied<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text"  id="charge"  disabled="disabled" class="form-control" value="<?php if($application_type["id"]==3){ echo $rate; }?>"  onkeypress="return isNum(event);">
                       </div>
 


                  </div>   

                  <div class="row">

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
                          <input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No.">
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
        	 

                  
            <div class="panel panel-bordered panel-dark">
                <div class="col-md-10" id="dd"></div>
                <div class="panel-body demo-nifty-btn text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function(){
        $("#formname").validate({
            rules:{
                firmtype_id:{
                    required:true
                },
                ownership_type_id:{
                    required:true
                },
                ward_id:{
                    required:true
                },
                holding_exists:{
                    required:true
                },
                saf_no:{
                    required:true
                },
                holding_no:{
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
                payment_mode:{
                    required:true
                }                 
            },
            messages:{
                firmtype_id:{
                    required:"Please select Firm Type"
                },
                ownership_type_id:{
                    required:"Please select Ownership Type"
                },
                ward_id:{
                    required:"Please select Ward No."
                },
                holding_exists:{
                    required:"Please select "
                },
                saf_no:{
                    required:"Please Enter SAF No."
                },
                holding_no:{
                    required:"Please Enter Holding No."
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
                }                 
            }
        });
    });
$(function() { $('#formname').validate(); 
              $("input[id^='tade_item']").each(function () 
                                                {$(this).rules("add", {required:true})
                                                ;}) 
              $("input[id^='owner_name']").each(function () 
                                                {$(this).rules("add", {required:true})
                                                ;}) 
              $("input[id^='mobile_no']").each(function () 
                                                {$(this).rules("add", {required:true})
                                                ;}) 
              $("input[id^='idproof']").each(function () 
                                                {$(this).rules("add", {required:true})
                                                ;})
              $("input[id^='id_no']").each(function () 
                                                {$(this).rules("add", {required:true})
                                                ;})
             });
/*$('#formname').on('submit', function(event) {
        //Add validation rule for dynamically generated name fields
    $('.tade_item').each(function() {
        $(this).rules("add", 
            {
                required: true,
                messages: {
                    required: "Item is required",
                }
            });
    });
    //Add validation rule for dynamically generated email fields
    // prevent default submit action         
            //event.preventDefault();

            // test if form is valid 
            if($('#formname').validate().form()) {
                console.log("validates");
            } else {
                console.log("does not validate");
            }
    
});
$("formname").validate();*/
    

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


$("#btn_review").click(function(){

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
});

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

     var appendData = '<tr><td><input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="city1" name="city[]"  class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control"><option value="">Select</option> <?php if($statelist){foreach($statelist as $valo){?><option value="<?php echo $valo['name'];?>" ><?php echo $valo['name'];?></option><?php }}?> </select></td><td><select name="district[]" id="district1" class="form-control"><option value="">Select</option></select></td><td><select name="idproof[]" id="idproof1" style="width: 100px;" class="form-control"><option value="">Select</option><option value="PAN Card">PAN Card</option><option value="Aadhaar">Aadhaar</option></select></td><td><input type="text" id="id_no1" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i></td></tr>';

    function validate_holding()
    {
         
         var holding_no=$("#holding_no").val();;
         var firmtype_id=$("#firmtype_id").val();        
         // alert(ward_id);
         var owner_type=$("#ownership_type_id").val();         
         if(holding_no==""){
           $("#owner_dtl_append").html(appendData);
            
            }
            else
            { 
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplylicence/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no
                },               
                success:function(data){
                //console.log(data);
                  //alert('aaa');
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

                    for(var k in data.dd) {
                      // console.log(k, data.dd[k]['owner_name']);
                     
                        tbody+="<tr>";
                      
                        
                        
                    //   $("#owner_name").val( data.dd[k]['owner_name']);
                       tbody+='<td><input type="text" name="owner_name[]" id="owner_name'+i+'" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly ></td>';

                       tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name'+i+'" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no'+i+'" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly></td>';
                        tbody+='<td><input type="text" name="emailid[]" id="emailid'+i+'" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['email']+'" readonly></td>';
                        tbody+='<td><input type="text" id="address'+i+'" name="address[]" class="form-control address" placeholder="Address" value="'+address+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td>';
                        tbody+='<td><input type="text" id="city'+i+'" name="city[]"  class="form-control city" placeholder="City" value="'+city+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td>';

                        tbody+='<td><input type="text" id="state'+i+'" name="state[]" readonly  class="form-control state" placeholder="state" value="'+city+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td>';

                         tbody+='<td><input type="text" id="district'+i+'" name="district[]" readonly  class="form-control district" placeholder="district" value="'+city+'" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td>';

                         tbody+='<td><select name="idproof[]" id="idproof'+i+'" style="width: 100px;" class="form-control"><option value="">Select</option><option value="PAN Card">PAN Card</option><option value="Aadhaar">Aadhaar</option></select></td><td><input type="text" id="id_no'+i+'" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  />';

                         if(firmtype_id==2){
                            if(i>1){
                              tbody+='<td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td>';
                            }else{
                              tbody+='<td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp;</td>';
                            }
                          }else{
                            tbody+='<td></td>';
                          }
                        



                       tbody+="</tr>";
                       i++;

                    }
                    $("#prop_id").val(prop_id);
                    $("#ward_id").val(ward_mstr_id);
                    $("#ward_no").val(ward_no);
                    $("#firmaddress").val(address);
                    $("#pin_code").val(pincode);  
                    if(owner_type==1){
                     $("#owner_dtl_append").html(tbody);
                    }else{
                     
                       $("#owner_dtl_append").html(appendData);
                    }
                     

                 //  for
                   
                    
                     // alert(data.data); 
                  } else {

                      alert('Holding No. not Found');
                      $("#holding_no").val("");
                      $("#prop_id").val("");
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

    function getsqmtr(str)
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
    function owner_dtl_append_fun(){
        zo++;
        var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="address'+zo+'" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="emailid'+zo+'" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="city'+zo+'" name="city[]"  class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><select name="state[]" id="state'+zo+'" onchange="show_district(this.value,'+zo+')" class="form-control"><option value="">Select</option> <?php if($statelist){foreach($statelist as $valo){?><option value="<?php echo $valo['name'];?>" ><?php echo $valo['name'];?></option><?php }}?> </select></td><td><select name="district[]" id="district'+zo+'" class="form-control"><option value="">Select</option></select></td><td><select name="idproof[]" id="idproof'+zo+'" style="width: 100px;" class="form-control"><option value="">Select</option><option value="PAN Card">PAN Card</option><option value="Aadhaar">Aadhaar</option></select></td><td><input type="text" id="id_no'+zo+'" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#owner_dtl_append").append(appendData);
    }
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
    });

     var ti = <?=$ti;?>;
    function trade_item_append_fun(){
        ti++;
        var tappendData = '<tr><td><select id="tade_item'+ti+'" name="tade_item[]" required="required" class="form-control tade_item"  onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if($tradeitemlist){foreach($tradeitemlist as $valit){?><option value="<?php echo $valit['id'];?>" ><?php echo $valit['trade_item'];?></option><?php }}?></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>&nbsp;<i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i></td></tr>';
        $("#trade_item_append").append(tappendData);
    }
    $("#trade_item_append").on('click', '.remove_trade_item', function(e) {
        $(this).closest("tr").remove();
    });

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

    function show_charge()
    { var timefor = $("#licence_for").val();
      var str =  $("#area_in_sqft").val();
      //alert(timefor);
      if(str!=""){
          $.ajax({
            type:"POST",
            url: '<?php echo base_url("tradeapplylicence/getcharge");?>',
            dataType: "json",
            data: {
                    "areasqft":str,"applytypeid":<?=$application_type["id"]?>
            },
           
            success:function(data){
             // console.log(data);
             // alert(data);
              if (data.response==true) {
                $("#charge").val(data.rate * timefor);
              }
              else{

                $("#charge").val('');
            }
              }
               
        });
        }  

    }

    function myFunction() {
  var mode = document.getElementById("payment_mode").value;
  if (mode == 'CASH') {
    $('#chqno').hide(); 
    $('#chqbank').hide();
  } else{
    $('#chqno').show(); 
    $('#chqbank').show();
  }
}


</script>