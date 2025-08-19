<style>
.error {
    color: red;
}
</style>
<?= $this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Apply Water Connection Form</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
    
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-6">
                      <h3 class="panel-title">Apply Water Connection Form</h3>
                    </div>
                    <div class="col-md-6" style="text-align: right;">
                      <span class="btn btn-info"><a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.$water_conn_id);?>"  style="color: white;">Back</a></span>
                    </div>

                  </div>
                  </div>

                </div>

          <?php
            if($connection_dtls['payment_status']==0)
            {
          ?>


        <form id="form" name="form" method="post" action="<?php echo base_url('WaterUpdateApplication/update_application')?>" >



                <div class="panel-body">
                    <div class="row">
                      
                 
                    <div class="row">
                        <label class="col-md-3">Type of Connection <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="connection_type_id" name="connection_type_id" class="form-control">
                                <option value="">== SELECT ==</option>
                              <?php
                              if($conn_type_list)
                              {
                                foreach($conn_type_list as $val)
                                {
                              ?>
                              
                              <option value="<?php echo $val['id'];?>" <?php if($connection_dtls['connection_type_id']==$val['id']){echo "selected";}?>><?php echo $val['connection_type'];?></option>
                              <?php   
                                }
                              }
                              ?>
                            </select>
                        </div>
                        <label class="col-md-3">Connection Through <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
              <select name="conn_through_id" id="conn_through_id" class="form-control" >
                <option value="">Select</option>
                <?php
                if($conn_through_list)
                {
                  foreach($conn_through_list as $val)
                  {

                    ?>
                    <option value="<?php echo $val['id'];?>" <?php if($connection_dtls['connection_through_id']==$val['id']){echo "selected"; }?>><?php echo $val['connection_through'];?></option>
                    <?php
                  }
                }
                ?>
              </select>                        
            </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Property Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <select name="property_type_id" id="property_type_id" class="form-control">
                            <option value="">Select</option>
                            <?php
                            if($property_type_list)
                            {

                              foreach($property_type_list as $val)
                              {
              ?>

              <option value="<?php echo $val['id'];?>" <?php if($connection_dtls['property_type_id']==$val['id']){ echo "selected";}?>><?php echo $val['property_type'];?></option>
                              <?php
                            }
                            }
                            ?>
                          </select>
                        </div>

                            <label class="col-md-3">Pipeline Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <select name="pipeline_type_id" id="pipeline_type_id" class="form-control">
                            <option value="">Select</option>
                            <?php
                            if($pipeline_type_list)
                            {

                              foreach($pipeline_type_list as $val)
                              {
              ?>

              <option value="<?php echo $val['id'];?>" <?php if($connection_dtls['pipeline_type_id']==$val['id']){echo "selected";}?>><?php echo $val['pipeline_type'];?></option>
                              <?php
                            }
                            }
                            ?>
                          </select>
                        </div>

                    </div>
                   
                   <div class="row">
                    <label class="col-md-3">Category Type <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <select name="category" id="category" class="form-control">
                            <option value="">Select</option>
                            <option value="APL" <?php if($connection_dtls['category']=='APL'){ echo "selected"; }?>>APL</option>
                            <option value="BPL" <?php if($connection_dtls['category']=='BPL'){ echo "selected"; }?>>BPL</option>
                            
                          </select>
                        </div>


                   <label class="col-md-3">Owner Type<span class="text-danger">*</span></label>

                   <div class="col-md-3 pad-btm">
                      <select name="owner_type" id="owner_type" class="form-control" onchange="clear_owner(this.value)">
                        <option value="">Select</option>
                        <option value="OWNER" <?php if($connection_dtls['owner_type']){ echo "selected";}?>>OWNER</option>
                        <option value="TENANT">TENANT</option>
                      </select>
                   </div>



                   </div>
             
                </div>
            </div>


             <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Applicant Details</h3>
                </div>
                <div class="panel-body">
                  <div class="row">

                  <label class="col-md-3">Ward No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <select name="ward_id" id="ward_id" class="form-control">
                            <option value="">Select</option>
                            <?php
                              if($ward_list)
                              {
                                foreach($ward_list as $val)
                                {
                            ?>
                            <option value="<?php echo $val['id'];?>" <?php if($connection_dtls['ward_id']==$val['id']){echo "selected";} ?>><?php echo $val['ward_no'];?></option>
                            <?php 
                                } 
                              }
                            ?>
                          </select>
                       </div>

                    
                        <label class="col-md-3">If Holding Exists? <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">

                       <select name="holding_exists" id="holding_exists" class="form-control" onchange="show_hide_saf_holding_box(this.value)">
                           <option value="">Select</option>
                           <option value="YES" <?php if($connection_dtls['holding_no']!=''){echo "selected"; }?>>Yes</option>
                           <option value="NO" <?php if($connection_dtls['holding_no']==''){echo "selected"; }?>>No</option>
                           
                         </select>
                       </div>

                       

                  </div>

                  <div class="row" id="saf_div" style="display: none;">
                      <label class="col-md-3">SAF No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="saf_no" id="saf_no" class="form-control" value="<?php echo isset($connection_dtls['saf_no'])?$connection_dtls['saf_no']:""; ?>" onblur="validate_saf(this.value)"  onkeypress="return isAlphaNum(event);">
                          <input type="hidden" name="saf_id" id="saf_id">
                       </div>

                  </div>

                   <div class="row" id="holding_div">
                      <label class="col-md-3">Holding No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                             <input type="text" name="holding_no" id="holding_no" class="form-control" onblur="validate_holding(this.value);" value="<?php echo isset($connection_dtls['holding_no'])?$connection_dtls['holding_no']:""; ?>"  onkeypress="return isAlphaNum(event);">
                             <input type="hidden" name="prop_id" id="prop_id">
                       </div>

                  </div>



                  <div class="row">

                  <label class="col-md-3">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onkeypress="getsqmtr(this.value);" value="<?php echo isset($connection_dtls['area_sqft'])?$connection_dtls['area_sqft']:""; ?>"  onkeypress="return isNum(event);">
                       </div>

                        <label class="col-md-3">Total Area( in Sq. Mt)<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="area_in_sqmt" id="area_in_sqmt" class="form-control" onkeypress="getsqft(this.value);" value="<?php echo isset($connection_dtls['area_sqmt'])?$connection_dtls['area_sqmt']:""; ?>"  onkeypress="return isNum(event);">
                       </div>


                  </div>


                  <div class="row">

                  <label class="col-md-3">Address<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNum(event);"><?php echo isset($connection_dtls['address'])?$connection_dtls['address']:"";?>
                            
                          </textarea>
                       </div>

                        <label class="col-md-3">Landmark<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="landmark" id="landmark" class="form-control" value="<?php echo isset($connection_dtls['landmark'])?$connection_dtls['landmark']:""; ?>"  onkeypress="return isAlphaNum(event);">
                       </div>


                  </div>

                  <div class="row">

                  <label class="col-md-3">Pin<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="pin" id="pin" maxlength="6" minlength="6" class="form-control" value="<?php echo isset($connection_dtls['pin'])?$connection_dtls['pin']:""; ?>" onkeypress="return isNum(event);">
                       </div>



                  </div>

               </div>
           </div>

<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $connection_dtls['id'];?>">

             <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Applicant Details</h3>
                </div>
                <div class="panel-body">
              
                       <div class="row">
                         <div class="col-md-12">
                           <table class="table table-responsive">
                            <thead>
                              <tr>
                                  <th>Owner Name</th>
                                  <th>Guardian Name</th>
                                  <th>Mobile No.</th>
                                  <th>Email ID</th>
                                  <th>State</th>
                                  <th>District</th>
                                  <th>City</th>
                                  <th>Add</th>
                                  
                              </tr>
                              </thead>
                              <tbody id="owner_dtl">
                                <?php
                                  if($owner_details)
                                  {
                                    foreach($owner_details as $val)
                                    {
                                  ?>
                                  

                               
                              <tr>
                                  <td><input type="text" name="owner_name[]" id="owner_name" class="form-control" value="<?php echo $val['applicant_name'];?>" onkeypress="return isAlpha(event);"></td>

                                  <td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" value="<?php echo $val['father_name'];?>"  onkeypress="return isAlpha(event);"></td>
                                  
                                  <td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" value="<?php echo $val['mobile_no'];?>" onkeypress="return isNum(event);"></td>
                                  
                                  <td><input type="email" name="email_id[]" id="email_id" class="form-control" value="<?php echo $val['email_id'];?>"></td>
                                  
                                  <td><input type="text" name="state[]" id="state" class="form-control" value="<?php echo $val['state'];?>"></td>
                                  
                                  <td><input type="text" name="district[]" id="district" class="form-control" value="<?php echo $val['district'];?>"></td>
                                  
                                  <td><input type="text" name="city[]" id="city" class="form-control"  onkeypress="return isAlpha(event);" value="<?php echo $val['city'];?>"></td>
                                  <input type="hidden" name="count" id="count" value="1">
                                  <td onclick="add_owner()" id="onwer_add">Add</td>
                                  
                              </tr>

                               <?php

                                    }
                                  }
                                ?>


                              </tbody>

                           </table>
                         </div>
                       </div>

                       <div id="owner_append"></div>

                  </div> 

               </div>
           </div>

           <div class="panel panel-bordered panel-dark">
              <div class="panel-heading">
                  <h3 class="panel-title">Applicant Bank Details</h3>
              </div>
                <div class="panel-body">
                  <div class="row">

                  <label class="col-md-3">Bank Name<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                       <input type="text" name="bank_name" id="bank_name" class="form-control"  value="<?php echo isset($connection_dtls['bank_name'])?$connection_dtls['bank_name']:""; ?>"  onkeypress="return isAlpha(event);">
                       </div>

                       
                  <label class="col-md-3">Branch Name<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="branch_name" id="branch_name" class="form-control" value="<?php echo isset($connection_dtls['branch_name'])?$connection_dtls['branch_name']:""; ?>" onkeypress="return isAlpha(event);">
                       </div>


                  </div>
                  <div class="row">

                  <label class="col-md-3">Account No.<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                       <input type="text" name="account_no" id="account_no" class="form-control" value="<?php echo isset($connection_dtls['account_no'])?$connection_dtls['account_no']:""; ?>" onkeypress="return isNum(event);">
                       </div>

                       
                  <label class="col-md-3">IFSC Code<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="<?php echo isset($connection_dtls['ifsc_code'])?$connection_dtls['ifsc_code']:""; ?>" onkeypress="return isAlphaNum(event);">
                       </div>


                  </div>
            </div>
          </div>


           <div class="panel panel-bordered panel-dark">
              <div class="panel-heading">
                  <h3 class="panel-title">Applicant Electricity Details</h3>
              </div>
                <div class="panel-body">
                  <div class="row">

                  <label class="col-md-3">K. No.</label>

                       <div class="col-md-3 pad-btm">
                       <input type="text" name="k_no" id="k_no" class="form-control"  value="<?php echo isset($connection_dtls['elec_k_no'])?$connection_dtls['elec_k_no']:""; ?>">
                       </div>

                       
                  <label class="col-md-3">Bind Book No.</label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="bind_book_no" id="bind_book_no" class="form-control"  value="<?php echo isset($connection_dtls['elec_bind_book_no'])?$connection_dtls['elec_bind_book_no']:""; ?>">
                       </div>


                  </div>
                  <div class="row">

                  <label class="col-md-3">Account No.</label>

                       <div class="col-md-3 pad-btm">
                       <input type="text" name="elec_account_no" id="elec_account_no" class="form-control"  value="<?php echo isset($connection_dtls['elec_account_no'])?$connection_dtls['elec_account_no']:""; ?>">
                       </div>

                       
                  


                  </div>
                   <div class="row">

                  <label class="col-md-2">Category Type</label>

                       <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Residential - DS I/II" <?php if($connection_dtls['elec_category']=='Residential - DS I/II'){echo "checked"; }?>>  Residential - DS I/II 
                      </div>

                       <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Commercial - NDS II/III" <?php if($connection_dtls['elec_category']=='Commercial - NDS II/III'){echo "checked"; }?>>  Commercial - NDS II/III 
                      </div>

                       <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Agriculture - IS I/II" <?php if($connection_dtls['elec_category']=='Agriculture - IS I/II'){echo "checked"; }?>>Agriculture - IS I/II

                      </div>

                      <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Low Tension - LTS" <?php if($connection_dtls['elec_category']=='Low Tension - LTS'){echo "checked"; }?>>Low Tension - LTS 

                      </div>

                      <div class="col-md-2 pad-btm">


                       <input type="radio" name="elec_category" value="High Tension - HTS" <?php if($connection_dtls['elec_category']=='High Tension - HTS'){echo "checked"; }?>>High Tension - HTS

                    

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

      <?php   
        }
        else
        {
      ?>
      <div class="row">
        <div class="col-md-12">
          <p class="text text-primary" style="font-weight: bold;font-size: 17px; text-align: center; ">Sorry, Your payment has been done so you can not update application</p>
        </div>
      </div>
      <?php    
        }
      ?>


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

    $('#form').validate({ // initialize the plugin
       

        rules: {

            property_type_id:"required",
            pipeline_type_id:"required",
            connection_type_id:"required",
            conn_through_id:"required",
            category:"required",
            owner_type:"required",
            holding_exists:"required",
            area_in_sqmt:"required",
            area_in_sqft:"required",
            address:"required",
            landmark:"required",
            pin:{required:true,digits:true,maxlength:6,minlength:6},
            bank_name:"required",
            branch_name:"required",
            account_no:{required:true,digits:true},
            ifsc_code:"required",
            k_no:"required",
            bind_book_no:"required",
            elec_category:"required",
            elec_account_no:"required",
           
           
        }


    });

});
</script>
<script type="text/javascript">
     
  function clear_owner(str)
  {

    var owner_type=str;
    if(owner_type=='TENANT')
    {
          $("[name='owner_name[]']").val("");
          $("[name='guardian_name[]']").val("");
          $("[name='mobile_no[]']").val("");
          $("[name='email_id[]']").val("");
    }
    else
    {
        var holding_no=$("#holding_no").val();

        validate_holding(holding_no);
    }
  }
  

  $( document ).ready(function() {
   

    var holding_exists=$("#holding_exists").val();
    //alert(holding_exists);

    if(holding_exists=='YES')
    {
        $("#holding_div").show();
        $("#saf_div").hide();
        $("#holding_no").attr('required',true);
        $("#saf_no").attr('required',false);
        

    }
    else if(holding_exists=='NO')
    {

       $("#saf_div").show();
       $("#holding_div").hide();
       $("#saf_no").attr('required',true);
       $("#holding_no").attr('required',false);
       
       
    }
    });

    function validate_holding(str)
    {
         
         var holding_no=str;
         var ward_id=$("#ward_id").val();
         // alert(ward_id);
         var owner_type=$("#owner_type").val();

         if(owner_type=='OWNER')
         {


            $.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterApplyNewConnection/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "ward_id":ward_id,"holding_no":holding_no
                },
               
                success:function(data){
                //console.log(data);
                  // alert(data);
                   if (data.response==true) {

                  // var obj = JSON.parse(data.dd);
                  // alert(data.dd.0.owner_name);
                    var tbody="";
                    var i=1;

                  for(var k in data.dd) {
                      // console.log(k, data.dd[k]['owner_name']);
                        tbody+="<tr>";
                        var prop_id=data.dd[k]['id'];

                    //   $("#owner_name").val( data.dd[k]['owner_name']);
                       tbody+='<td><input type="text" name="owner_name[]" id="owner_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly ></td>';

                       tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly></td>';

                        tbody+='<td><input type="text" name="email_id[]" id="email_id" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['email']+'" ></td>';

                        tbody+='<td><input type="text" name="state[]" id="state" class="form-control" onkeypress="return isAlpha(event);" ></td>';

                         tbody+='<td><input type="text" name="district[]" id="district" class="form-control" onkeypress="return isAlpha(event);" ></td>';

                         tbody+='<td><input type="text" name="city[]" id="city" class="form-control" onkeypress="return isAlpha(event);" ></td>';

                        



                       tbody+="</tr>";
                       i++;


                    }
                     $("#owner_dtl").html(tbody);
                     $("#prop_id").val(prop_id);
                     $("#count").val(i);
                     
                 //  for
                   
                    
                     // alert(data.data); 
                   } else {

                      alert('Holding No. not Found');
                      $("#holding_no").val("");

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
          $("#holding_div").show();
          $("#saf_div").hide();
          $("#holding_no").attr('required',true);
          $("#saf_no").attr('required',false);
          $("#saf_no").val("");
          $("#saf_id").val("");
          
       }
       else if(holding_exists=='NO')
       {
          $("#saf_div").show();
          $("#holding_div").hide();
          $("#saf_no").attr('required',true);
          $("#holding_no").attr('required',false); 
          $("#holding_no").val(""); 
          $("#prop_id").val(""); 
          $("[name='owner_name[]']").val("");
          $("[name='guardian_name[]']").val("");
          $("[name='mobile_no[]']").val("");
          $("[name='email_id[]']").val("");
          
              
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
       }


    }

    function validate_saf(str)
    {


         var saf_no=str;
         var ward_id=$("#ward_id").val();

          $.ajax({
            type:"POST",
            url: '<?php echo base_url("WaterApplyNewConnection/validate_saf_no");?>',
            dataType: "json",
            data: {
                    "ward_id":ward_id,"saf_no":saf_no
            },
           
            success:function(data){
              console.log(data);
             // alert(data.payment_status);

               if (data.response==true) {

                if(data.payment_status==0)
                {
                    alert('Please make your payment in SAF first');
                    $("#saf_no").val("");
                }
                else if(data.prop_dtl_id!=0)
                {
                    alert('Your Holding have been generated kindly provide your Holding no.');
                    $("#saf_no").val("");
                }
                else
                {
                  $("#owner_name").html(data.owner_name);
                  $("#mobile_no").html(data.mobile_no);
                  $("#guardian_name").html(data.guardian_name);
                  $("#email_id").html(data.email);
                  $("#saf_id").val(data.saf_id);
                }
                
                
                 // alert(data.data); 
               } else {

                  alert('SAF No. not Found');
                  $("#saf_no").val("");

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

    function add_owner()
    {

        var count=$("#count").val();
        var count=parseInt(count)+1;

        var html="<div class='row' id='del"+count+"'><div class='col-md-12'><table class='table table-responsive'><tr><td><input type='text' name='owner_name"+count+"' class='form-control' required></td><td><input type='text' name='guardian_name"+count+"' class='form-control' required></td><td><input type='email' name='email_id"+count+"' class='form-control'></td><td><input type='text' name='mobile_no"+count+"' class='form-control' required></td><td><input type='text' name='state"+count+"' class='form-control' required></td><td><input type='text' name='district"+count+"' class='form-control' required></td><td><input type='text' name='city"+count+"' class='form-control' ></td><td onclick='add_owner()'>Add</td><td value='"+count+"' onclick='delete_owner("+count+")' class='remove_owner_dtl' >Remove</td></tr></table></div></div>";

        

        $("#owner_append").append(html);
        $("#count").val(count);



    }

    function delete_owner(count)
    {


        var count=count;
       //  alert(count);
        var element_id="del"+count;
        //alert(element_id);


        //element_id.remove(element_id);
       // element_id.parentNode.removeChild(element_id);

         $("#owner_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("div").remove();
    });

    }
</script>