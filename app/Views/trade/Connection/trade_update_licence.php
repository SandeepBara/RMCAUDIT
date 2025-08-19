
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
            <li><a href="#">Trade </a></li>
            <li class="active"><a href="#">Update Apply Licence </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post" enctype="multipart/form-data">
            <?php if(isset($validation)){ ?>
                <?= $validation->listErrors(); ?>

            <?php } ?>
            <input type="hidden" id="id" name="id" value="<?=(isset($id))?$id:"";?>">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                  <div class="panel-control">
                    <a class="btn btn-default" href="<?php echo base_url('Trade_Apply_Licence/getApplyLicenceDetails/');?>" role="button"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</a>
                </div>
                <h3 class="panel-title">Basic Details</h3>
            </div>


            <div class="panel-body">
                <div class="row">
                  <div class="row" style="line-height: 35px;">
                    <label class="col-md-2">Application No.:-</label>
                    <div class="col-md-3 control-label text-semibold">
                      <?=$application_no??null?>
                  </div>
                  <?php  if($application_type_id!=1):?>
                    <label class="col-md-2">License No.:-</label>
                    <div class="col-md-3 control-label text-semibold">
                      <?=$license_no?>
                  </div>
              <?php endif;?>
          </div>
          <div class="row">
            <label class="col-md-2">Application Type <span class="text-danger">*</span></label>
            <div class="col-md-3 control-label text-semibold">
                <?=$application_type["application_type"]?>
            </div>
            <label class="col-md-2">Firm Type <span class="text-danger">*</span></label>
            <div class="col-md-3 pad-btm"><?php if($application_type_id==1 or $application_type_id==3){?>
               <select name="firmtype_id" id="firmtype_id" onchange="forother(this.value),validate_holding()" class="form-control" >
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
        <?php }else{
           echo  $firm_type["firm_type"];} ?>                       
       </div>
   </div>
   <div class="row">
    <label class="col-md-2">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
    <div class="col-md-3 pad-btm"><?php if($application_type_id==1 or $application_type_id==3){?>
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
<?php }else{ echo  $ownership_type["ownership_type"];} ?> 
</div>
<label class="col-md-2">Category<span class="text-danger">*</span></label>
<div class="col-md-3 pad-btm">
  <?php if($application_type_id==1 or $application_type_id==3){?>
      <select name="category_type_id" id="category_type_id"  class="form-control">
        <option value="">Select</option>
        <?php
        if($categoryTypeDetails){
          foreach($categoryTypeDetails as $vdata){?>
            <option value="<?php echo $vdata['id'];?>" <?php if($category_type_id==$vdata['id']){ echo "selected";}?>><?php echo $vdata['category_type'];?>
        </option>
    <?php }
}
?>
</select>
<?php }else{ echo  $category_type["category_type"];} ?> 
</div>
</div>
<div class="row">
 <label class="col-md-2 classother" style="display: none;">For Other Firm type<span class="text-danger">*</span></label>
 <div class="col-md-3 pad-btm classother" style="display: none;">
  <input type="text" name="firmtype_other" id="firmtype_other" class="form-control" value="<?php echo isset($firmtype_other)?$firmtype_other:""; ?>" placeholder="Other Firm type"  onkeypress="return isAlphaNum(event);">  
</div>
</div>
</div>
</div>
</div>
<div class="panel panel-bordered panel-dark">
    <div class="panel-heading">
        <h3 class="panel-title">Firm Details</h3>
    </div>
    <?php 

                 // New License
    if($application_type_id==1)
    {
        ?> 
        <div class="panel-body">                  
            <div class="row"  >    
                <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>

                <div class="col-md-3">
                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                        <option value="">Select</option>
                        <?php foreach($ward_list as $value):?>
                            <option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>                  
            <label class="col-md-2" id="holding_lebel" >Holding No.<span class="text-danger">*</span></label>
            <div class="col-md-3 pad-btm" id="holding_div" >
                <input type="text" name="holding_no" id="holding_no" class="form-control" onblur="validate_holding()" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);">
                <input type="hidden" name="prop_id" id="prop_id" value="<?php echo isset($prop_dtl_id)?$prop_dtl_id:""; ?>">
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
                value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>" onkeypress="return isNumDot(event);" readonly />
            </div>                    
        </div>
        <div class="row">

            <label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>

            <div class="col-md-3 pad-btm">

                <input type="date" name="firm_date" id="firm_date" class="form-control" value="<?php echo isset($establishment_date)?$establishment_date:date('Y-m-d'); ?>"  onkeypress="return isNum(event);" readonly />                     
            </div>

            <label class="col-md-2">Address<span class="text-danger">*</span></label>

            <div class="col-md-3 pad-btm">
                <input name="firmaddress" id="firmaddress" class="form-control" readonly="readonly" onkeypress="return isAlphaNum(event);" value="<?php echo isset($address)?$address:"";?>" >                         

            </div>
        </div>
        <div class="row">
            <label class="col-md-2">landmark<span class="text-danger">*</span></label>

            <div class="col-md-3 pad-btm">
                <input type="text" name="landmark"  id="landmark" class="form-control" value="<?php echo isset($landmark)?$landmark:""; ?>"  onkeypress="return isAlphaNum(event);">
            </div>

            <label class="col-md-2">Pin Code<span class="text-danger">*</span></label>
            <input type="hidden" value="<?=$application_type_id?>" name="application_type_id">
            <div class="col-md-3 pad-btm">
                <input type="text" name="pin_code" id="pin_code" readonly="readonly" maxlength="6" minlength="6" class="form-control" value="<?php echo isset($pin_code)?$pin_code:""; ?>" onkeypress="return isNum(event);">
            </div>
        </div>

        <div class="row">                        
            <label class="col-md-2">Owner of Business Premises<span class="text-danger">*</span></label>
            <div class="col-md-3 pad-btm">
                <input type="text"  name="owner_business_premises" id="owner_business_premises"  class="form-control" value="<?php echo isset($premises_owner_name)?$premises_owner_name:""; ?>" onkeypress="return isAlphaNum(event);">
            </div>
            <label class="col-md-2">New Ward No. <span class="text-danger">*</span></label>

            <div class="col-md-3 pad-btm">
                <select id="new_ward_mstr_id" name="new_ward_mstr_id" class="form-control">
                    <option value="">Select</option>
                    <?php foreach($ward_list as $value):?>
                        <option value="<?=$value['id']?>" <?=(isset($new_ward_mstr_id))?$new_ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
</div>
<?php 
} 
else
{
    ?>
    <div class="panel-body">
        <div class="row">

            <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>  
            <div class="col-md-3 pad-btm">
                <?php if($application_type_id==1 or $application_type_id==3){?>                   
                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                        <option value="">ALL</option>  
                        <?php foreach($ward_list as $value):?>
                            <option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"selected":"":"";?>><?=$value['ward_no'];?>
                        </option>
                    <?php endforeach;?>
                </select>
            <?php }else{ echo $ward["ward_no"];}?>
        </div>
        <label class="col-md-2" id="saf_lebel" >Holding No.</label>

        <div class="col-md-3 pad-btm">
            <?=$holding_no??null?><input type="hidden" name="holding_no" id="holding_no" value="<?=$holding_no??null;?>">
        </div>

    </div>
    <div class="row">

        <label class="col-md-2">Firm Name<span class="text-danger">*</span></label>

        <div class="col-md-3 pad-btm">
            <?php  echo $firm_name; ?>
        </div>

        <label class="col-md-2"> Total Area(in Sq. Ft) <span class="text-danger">*</span></label>

        <div class="col-md-3 pad-btm">   
            <?php 

            if($application_type_id==3)
            {
                ?>
                <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onblur="show_charge()"  
                value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>" onkeypress="return isNumDot(event);" readonly />
                <?php 
            }
            else
            {
                echo $area_in_sqft;
            }
            ?>

        </div>                    
    </div>
    <div class="row">

        <label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>

        <div class="col-md-3 pad-btm">
            <?php  echo $establishment_date; ?>
        </div>

        <label class="col-md-2">Address<span class="text-danger">*</span></label>

        <div class="col-md-3 pad-btm">
            <?php  echo $firm_address ?? NULL; ?>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2">Landmark </label>
        <div class="col-md-3 pad-btm">
            <?php  echo $landmark; ?>
        </div>
        <label class="col-md-2">Pin Code<span class="text-danger">*</span></label>
        <div class="col-md-3 pad-btm">
            <?php  echo $pin_code; ?>
        </div>
    </div>
</div>
<?php 
}
?>
</div>

<div class="panel panel-bordered panel-dark" >
    <div class="panel-heading">
        <h3 class="panel-title">Firm Owner Details</h3>
    </div>
    <div class="panel-body" style="padding-bottom: 0px;">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive"><?php 
                ?> 

                <table class="table table-bordered text-sm">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th>Owner Name <span class="text-danger">*</span></th>
                            <th>Guardian Name <span class="text-danger">*</span></th>                                            
                            <th>Mobile No <span class="text-danger">*</span></th>
                            <th>Email Id <span class="text-danger">*</span></th>
                            <th>Address <span class="text-danger">*</span></th>
                            <!-- <th>Add/Remove</th> -->
                        </tr>
                    </thead>
                    <tbody id="owner_dtl_append">
                        <?php
                        $zo = 0;
                        if(isset($ownerDetails, $ownerDetails))
                        {
                            foreach ($ownerDetails as  $value)
                            {
                                $zo++;  
                                if(in_array($application_type_id, [1, 3]))
                                {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="owner_name_id[]" value="<?=$value['id']!=""?$value['id']:"";?>">
                                            <input type="text" id="owner_name<?=$zo;?>" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="<?=$value['owner_name']!=""?$value['owner_name']:"";?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                        </td>
                                        <td>
                                            <input type="text" id="guardian_name<?=$zo;?>" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="<?=$value['guardian_name']!=""?$value['guardian_name']:"";?>" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                        </td>                                            
                                        <td>
                                            <input type="text" id="mobile_no<?=$zo;?>" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="<?=$value['mobile']!=""?$value['mobile']:"";?>" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                        </td>
                                        <td>
                                            <input type="text" id="emailid<?=$zo;?>" name="emailid[]" class="form-control address" placeholder="Email Id" value="<?=$value['emailid']!=""?$value['emailid']:"";?>" onkeyup="borderNormal(this.id);"  />
                                        </td>
                                        <td>
                                            <input type="text" id="address<?=$zo;?>" name="address[]" class="form-control address" placeholder="Address" value="<?=$value['address']!=""?$value['address']:"";?>" onkeyup="borderNormal(this.id);"  />
                                        </td>
                                                        <!-- <td class="text-2x">
                                                            <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i>
                                                            &nbsp;
                                                            <?php if($zo>1){?>
                                                            <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i>
                                                        <?php }?>
                                                    </td> -->
                                                </tr>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>  
                                                <tr>
                                                    <td><?=$value['owner_name']!=""?$value['owner_name']:"";?></td>
                                                    <td><?=$value['guardian_name']!=""?$value['guardian_name']:"";?></td>                                            
                                                    <td><?=$value['mobile']!=""?$value['mobile']:"";?></td>
                                                    <td><?=$value['emailid']!=""?$value['emailid']:"";?></td>
                                                    <td> <?php $value["document_id"];?></td>
                                                    <td> <?=$value['id_no']!=""?$value['id_no']:"";?></td>                                                                           
                                                </tr>
                                                <?php 
                                            }

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
                                                <input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" />
                                            </td>                                            
                                            <td>
                                                <input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" />
                                            </td>
                                            <td>
                                                <input type="text" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" />
                                            </td>
                                            <td>
                                                <select name="idproof[]" id="idproof1" style="width: 100px;" class="form-control">
                                                 <option value="">Select</option>
                                                 <?php 
                                                 if(isset($idprooflist, $idprooflist))
                                                    foreach($idprooflist as $proofval)
                                                    {
                                                        ?>
                                                        <option value="<?=$proofval["id"]?>"><?=$proofval["doc_name"]?></option>                                           
                                                        <?php 
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="id_no1" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  />
                                            </td>

                                            <td class="text-2x">
                                                <i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp;
                                                <?php 
                                                if($zo>1)
                                                {
                                                    ?>
                                                    <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i>
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

        <?php
        if($tobacco_status==0){
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
                                if($application_type_id==1 or $application_type_id==3)
                                {
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
                                            if(!empty($tradeitemdet)){
                                                /*if(!empty($tradeitemdet)){*/
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
                                                                            <option value="<?php echo $valit['id'];?>" <?php if($itemvalue["id"]==$valit['id']){echo "selected"; }?> ><?php echo $valit['trade_item'];?></option>
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
                                                    /*}*/
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

            <?php
        }
        else
        {
            ?>
            <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Nature Of Business <span class="text text-danger">(Tobbaco)</span></h3>
                </div>
                <div class="panel-body">
                    <div class="row" >
                        <div class="col-md-12">
                            <div class="">
                                <?php
                                $ti = 0;
                                if(!empty($tradeitemdet))
                                {

                                    foreach ($tradeitemdet as  $itemvalue)
                                    {
                                        $ti++;
                                        ?>
                                        <select id="tade_item<?=$ti?>" name="tade_item[]" class="form-control tade_item" required="required"  onchange="borderNormal(this.id);" readonly disabled>
                                            <option value="">SELECT</option>
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
        ?>







         <!--   <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
              <h3 class="panel-title">Fill out all the details</h3>
            </div>
                <div class="panel-body">
                    <div class ="row">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label" for="doc_path"><b>Upload Document (Only Pdf) <span class="text-danger">*</span></b> </label>
                            </div>
                            <div class="col-md-3">
                                <input type="file" id="doc_path" name="doc_path" class="form-control" value="" >                                                        
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label" for="from_date"><b>Remarks <span class="text-danger">*</span></b> </label>
                            </div>
                            <div class="col-md-9">
                                <textarea type="text" id="remark" minlength="40" maxlength="240" name="remark" class="form-control" placeholder="Remark" onkeypress="return isAlphaNum(event);"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  -->
            <div class="panel panel-bordered panel-dark">
                <div class="col-md-10" id="dd"></div>
                <div class="panel-body demo-nifty-btn text-center">
                    <button class="btn btn-primary" id="btn_review" name="btn_review" type="submit"><?=(isset($id))?"SAVE":"Submit";?></button>
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
                ward_mstr_id:{
                    required:true
                },
                new_ward_mstr_id:{
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
                holding_no:
                {
                    required:true
                },
                
                landmark:{
                    required: true
                },
                pin_code:{
                    required:true
                },
                new_ward_mstr_id:
                {
                    required:true
                },               
                doc_path:{
                    required:true
                },  
                remark:{
                    required:true
                },  
                category_type_id:{
                    required:true
                },
                "owner_name[]":{
                    required:true,
                    minlength: 3
                },
                "guardian_name[]":{
                    required:true,
                    minlength: 3
                },
                "mobile_no[]":{
                    required:true,
                    minlength: 10
                },
                "emailid[]":{
                    required:true,
                    email:true
                },
                "address[]":{
                    required:true,
                    
                },
                "id_no[]":{
                    required:true
                },
                firmtype_other:{
                   required:true 
               },
               owner_business_premises:{
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
            ward_mstr_id:{
                required:"Please select Ward No."
            }, 
            new_ward_mstr_id:{
                required:"Please Select New Ward No."
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
            landmark:{
                required:"Please Enter Landmark"
            },                
            pin_code:{
                required:"Please Enter Pincode"  
            },
            holding_no:
            {
                required:"Please Enter Holding No."
            },                
            doc_path:{
                required:"Please Select Document"  
            },
            new_ward_mstr_id:
            {
                required:"Please Select New Ward No."  
            }, 
            remark:{
                required:"Please Enter Remark"  
            }, 
            category_type_id:{
                required:"Please Select Category"  
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
                required:"Please Enter Email Address"
            },
            "address[]":{
                required:"Please Enter Address"
            },
            "id_no[]":{
                required:"Please Enter Id No."
            },
            firmtype_other:{
               required:"Please Enter Other Firm type"
           },
           owner_business_premises:{
               required:"Please Enter Owner Business Premises"
           }                   
       }
   });
});
/*$(function() { $('#formname').validate(); 
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
                                            });*/
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

var appendData = '<tr><td><input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="emailid1" name="emailid[]" class="form-control address" placeholder="Email Id" value=""  onkeyup="borderNormal(this.id);"  /></td><td><input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><select name="idproof[]" id="idproof1" style="width: 100px;" class="form-control"></select></td><td><input type="text" id="id_no1" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i></td></tr>';

function validate_holding()
{
    //debugger;
    var holding_no=$("#holding_no").val();
        //alert(holding_no);
        var firmtype_id=$("#firmtype_id").val();        
        var ward_id=$("#ward_mstr_id").val();
        var owner_type=$("#ownership_type_id").val();         
        if(holding_no=="" && ward_id !='')
        {

            alert('First Select Your Holding No.');
            return;
        }
        else
        { 
          $.ajax({
            type:"POST",
            url: '<?php echo base_url("tradeapplylicence/validate_holding_no");?>',
            dataType: "json",
            data: {
                "holding_no":holding_no,
                "ward_mstr_id":ward_id
            },               
            success:function(data)
            {
                console.log(data.response);return;
                if (data.response==true)
                {
                    if(data.pp==null){
                      alert('Holding No. not Found');
                      $("#holding_no").val("");
                      $("#prop_id").val("");
                          //$("#ward_id").val("");
                          $("#ward_no").val("");
                          $("#firmaddress").val("");
                          $("#pin_code").val(""); 
                          $("#owner_business_premises").val(""); 

                      }else{


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

  function forother(str){
  //alert(str);
  if(str==5){
   $(".classother").show();
}else{
    $(".classother").hide();
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
    var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="emailid'+zo+'" name="emailid[]" class="form-control address" placeholder="Email Id" value=""  onkeyup="borderNormal(this.id);"  /></td><td><select name="idproof[]" id="idproof'+zo+'" style="width: 100px;" class="form-control"><option value="">Select</option><option value="1">Passport</option><option value="2">PAN Card</option><option value="3">UID ( Aadhar Card)</option><option value="4">Bank Passbook with Photo</option><option value="5">Photo Identity Card</option></select></td><td><input type="text" id="id_no'+zo+'" name="id_no[]" style="width: 100px;" class="form-control id_no" placeholder="Id No." value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);"  /></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
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
                "areasqft":str,"applytypeid":<?=$application_type_id?>
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
$("#doc_path").change(function() {
  var input = this;
  var ext = $(this).val().split('.').pop().toLowerCase();
  if($.inArray(ext, ['pdf']) == -1) {
      $("#doc_path").val("");
      alert('invalid Document type');
  }if (input.files[0].size > 1048576) { // 1MD = 1048576
      $("#doc_path").val("");
      alert("Try to upload file less than 1MB!"); 
  }
});


<?php
# Remove Tobbaco from option in case of non-tobbaco license
if($tobacco_status==0)
{
    ?>
    $('.tade_item  option[value="185"]').remove();
    <?php
}
?>
</script>