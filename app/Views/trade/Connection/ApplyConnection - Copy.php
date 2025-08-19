
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
            <li><a href="#">Apply New License </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post" >
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply New License</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                      
                 
                    <div class="row">
                        <label class="col-md-3">Application Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 control-label text-semibold">
                            New License
                        </div>
                        <label class="col-md-3">Firm Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<select name="firmtype_id" id="firmtype_id" class="form-control" >
								<option value="">Select</option>
								<?php
								if($firmtypelist)
								{
									foreach($firmtypelist as $val)
									{

										?>
										<option value="<?php echo $val['id'];?>" <?php if($conn_through_id==$val['id']){echo "selected"; }?>><?php echo $val['firm_type'];?></option>
										<?php
									}
								}
								?>
							</select>                        
						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <select name="ownership_type_id" id="ownership_type_id" onchange="validate_holding(),validate_saf()" class="form-control">
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
                        </div>

                        

                    </div>
                   
                   
             
                </div>
            </div>


            



             


             <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Details</h3>
                </div>
                <div class="panel-body">
                  <div class="row">

                  <label class="col-md-3">Ward No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <select name="ward_id" id="ward_id" onchange="validate_holding(),validate_saf()" class="form-control">
                            <option value="">Select</option>
                            <?php
                              if($ward_list)
                              {
                                foreach($ward_list as $val)
                                {
                            ?>
                            <option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){echo "selected";} ?>><?php echo $val['ward_no'];?></option>
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
                           <option value="YES" <?php if($holding_exists=='YES'){echo "selected"; }?>>Yes</option>
                           <option value="NO" <?php if($holding_exists=='NO'){echo "selected"; }?>>No</option>
                           
                         </select>
                       </div>

                       

                  </div>

                  <div class="row" id="saf_div" style="display: none;">
                      <label class="col-md-3">SAF No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="saf_no" id="saf_no" class="form-control" value="<?php isset($saf_no)?$saf_no:""; ?>" onblur="validate_saf(this.value)"  onkeypress="return isAlphaNum(event);">
                          <input type="hidden" name="saf_id" id="saf_id">
                       </div>

                  </div>

                   <div class="row" id="holding_div" style="display: none;">
                      <label class="col-md-3">Holding No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                             <input type="text" name="holding_no" id="holding_no" class="form-control" onblur="validate_holding()" value="<?php isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);">
                             <input type="hidden" name="prop_id" id="prop_id">
                       </div>

                  </div>



                  <div class="row">

                    <label class="col-md-3">Firm Name<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="firm_name" id="firm_name" class="form-control" onkeypress="getsqft(this.value);" value="<?php echo isset($area_in_sqmt)?$area_in_sqmt:""; ?>"  onkeypress="return isNum(event);">
                       </div>

                  <label class="col-md-3">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onkeypress="getsqmtr(this.value);" value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>"  onkeypress="return isNum(event);">
                       </div>

                        


                  </div>


                  <div class="row">

                  <label class="col-md-3">Address<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNum(event);"><?php echo isset($address)?$address:"";?>
                            
                          </textarea>
                       </div>

                        <label class="col-md-3">Landmark<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <input type="text" name="landmark" id="landmark" class="form-control" value="<?php echo isset($landmark)?$landmark:""; ?>"  onkeypress="return isAlphaNum(event);">
                       </div>


                  </div>

                  <div class="row">

                  <label class="col-md-3">Pin<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text" name="pin" id="pin" maxlength="6" minlength="6" class="form-control" value="<?php echo isset($pin)?$pin:""; ?>" onkeypress="return isNum(event);">
                       </div>



                  </div>

               </div>
           </div>

           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Owner Details</h3>
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
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>District</th>
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
                                                <input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" />
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

                                                <option value="<?php echo $valo['id'];?>" ><?php echo $valo['name'];?></option>
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
            </div>
         	 </div>
           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Items of Trade</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Trade Item <span class="text-danger">*</span></th>
                                            
                                            <th>Add/Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="trade_item_append">
                                <?php
                                $ti = 1;
                                
                                ?>
                                        <tr>                                            
                                            <td>
                                                <select id="tade_item1" name="tade_item[]" class="form-control tade_item"  onchange="borderNormal(this.id);">
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
                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         	 

        	 <!-- <div class="panel panel-bordered panel-dark">
	            <div class="panel-heading">
	                <h3 class="panel-title">Applicant Electricity Details</h3>
	            </div>
                <div class="panel-body">
                	<div class="row">

            			<label class="col-md-3">K. No.</label>

                   	   <div class="col-md-3 pad-btm">
                       <input type="text" name="k_no" id="k_no" class="form-control"  value="<?php echo isset($k_no)?$k_no:""; ?>">
                       </div>

                       
            			<label class="col-md-3">Bind Book No.</label>

                   	   <div class="col-md-3 pad-btm">
                         <input type="text" name="bind_book_no" id="bind_book_no" class="form-control"  value="<?php echo isset($bind_book_no)?$bind_book_no:""; ?>">
                       </div>


                	</div>
          			  <div class="row">

            			<label class="col-md-3">Account No.</label>

                   	   <div class="col-md-3 pad-btm">
                       <input type="text" name="elec_account_no" id="elec_account_no" class="form-control"  value="<?php echo isset($elec_account_no)?$elec_account_no:""; ?>">
                       </div>

                       
            			


                	</div>
                	 <div class="row">

            			<label class="col-md-2">Category Type</label>

                   	   <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Residential - DS I/II" <?php if($elec_category=='Residential - DS I/II'){echo "checked"; }?>>	Residential - DS I/II 
                   	  </div>

                   	   <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Commercial - NDS II/III" <?php if($elec_category=='Commercial - NDS II/III'){echo "checked"; }?>>	Commercial - NDS II/III 
                   	  </div>

                   	   <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Agriculture - IS I/II" <?php if($elec_category=='Agriculture - IS I/II'){echo "checked"; }?>>Agriculture - IS I/II

                   	  </div>

                   	  <div class="col-md-2 pad-btm">

                       <input type="radio" name="elec_category" value="Low Tension - LTS" <?php if($elec_category=='Low Tension - LTS'){echo "checked"; }?>>Low Tension - LTS 

                      </div>

                      <div class="col-md-2 pad-btm">


                       <input type="radio" name="elec_category" value="High Tension - HTS" <?php if($elec_category=='High Tension - HTS'){echo "checked"; }?>>High Tension - HTS

                    

                       </div>

                       
            			


                	</div>

            </div>
        	</div> -->

                  
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
                }
            },
            messages:{
                firmtype_id:{
                    required:"Please select Firm Type"
                }
            }
        });
    });

</script>


<script type="text/javascript">

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

     var appendData = '<tr><td><input type="text" id="owner_name1" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="address1" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="city1" name="city[]"  class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control"><option value="">Select</option> <?php if($statelist){foreach($statelist as $valo){?><option value="<?php echo $valo['id'];?>" ><?php echo $valo['name'];?></option><?php }}?> </select></td><td><select name="district[]" id="district1" class="form-control"><option value="">Select</option></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i></td></tr>';

    function validate_holding()
    {
         
         var holding_no=$("#holding_no").val();;
         var ward_id=$("#ward_id").val();        
         // alert(ward_id);
         var owner_type=$("#ownership_type_id").val();         
         if(holding_no==""){
           $("#owner_dtl_append").html(appendData);
            
            }
            else
            { 
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplyconnection/validate_holding_no");?>',
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
                       tbody+='<td><input type="text" name="owner_name"'+i+' id="owner_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly ></td>';

                       tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly></td>';
                        tbody+='<td><input type="text" id="address'+i+'" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td>';
                        tbody+='<td><input type="text" id="city'+i+'" name="city[]"  class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td>';

                        tbody+='<td><select name="state[]" id="state'+i+'" onchange="show_district(this.value,'+i+')" class="form-control"><option value="">Select</option> <?php if($statelist){foreach($statelist as $valo){?><option value="<?php echo $valo['id'];?>" ><?php echo $valo['name'];?></option><?php }}?> </select></td>';

                         tbody+='<td><select name="district[]" id="district'+i+'" class="form-control"><option value="">Select</option></select></td>';

                        
                         tbody+='<td></td>';

                        



                       tbody+="</tr>";
                       i++;

                    }
                    $("#prop_id").val(prop_id);
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

          
        $("#owner_dtl_append").html(appendData);
           
          
       }
       else if(holding_exists=='NO')
       {
          $("#saf_div").show();
          $("#holding_div").hide();
          $("#saf_no").attr('required',true);
          $("#holding_no").attr('required',false); 
          $("#holding_no").val(""); 
          $("#prop_id").val(""); 
          
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
       }


    }

    function validate_saf()
    {


         var saf_no=$("#saf_no").val();         
         var ward_id=$("#ward_id").val();
         var owner_type=$("#ownership_type_id").val();
         if(saf_no==""){
           $("#owner_dtl_append").html(appendData);
            
            }
            else
            { 

              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplyconnection/validate_saf_no");?>',
                dataType: "json",
                data: {
                        "ward_id":ward_id,"saf_no":saf_no
                },
               
                success:function(data){
                  console.log(data);
                 // alert(data.payment_status);

                   if (data.response==true) {

                    var tbody="";
                        var i=1;
                      
                        for(var k in data.dd) {
                          // console.log(k, data.dd[k]['owner_name']);
                           var payment_status=data.dd[k]['payment_status'];
                            var prop_dtl_id=data.dd[k]['prop_dtl_id'];

                            tbody+="<tr>";
                            var saf_id=data.dd[k]['id'];

                        //   $("#owner_name").val( data.dd[k]['owner_name']);
                           tbody+='<td><input type="text" name="owner_name"'+i+' id="owner_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly ></td>';

                           tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly></td>';

                            tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly></td>';
                            tbody+='<td><input type="text" id="address'+i+'" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td>';
                            tbody+='<td><input type="text" id="city'+i+'" name="city[]"  class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td>';

                            tbody+='<td><select name="state[]" id="state'+i+'" onchange="show_district(this.value,'+i+')" class="form-control"><option value="">Select</option> <?php if($statelist){foreach($statelist as $valo){?><option value="<?php echo $valo['id'];?>" ><?php echo $valo['name'];?></option><?php }}?> </select></td>';

                             tbody+='<td><select name="district[]" id="district'+i+'" class="form-control"><option value="">Select</option></select></td>';

                            
                             tbody+='<td></td>';

                            



                           tbody+="</tr>";
                           i++;

                        }
                          if(data.payment_status==0)
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
        var appendData = '<tr><td><input type="text" id="owner_name'+zo+'" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name'+zo+'" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no'+zo+'" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><input type="text" id="address'+zo+'" name="address[]" class="form-control address" placeholder="Address" value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="12" /></td><td><input type="text" id="city'+zo+'" name="city[]"  class="form-control city" placeholder="City" value="" onkeypress="return isAlphaNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" /></td><td><select name="state[]" id="state'+zo+'" onchange="show_district(this.value,'+zo+')" class="form-control"><option value="">Select</option> <?php if($statelist){foreach($statelist as $valo){?><option value="<?php echo $valo['id'];?>" ><?php echo $valo['name'];?></option><?php }}?> </select></td><td><select name="district[]" id="district'+zo+'" class="form-control"><option value="">Select</option></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="owner_dtl_append_fun();"></i> &nbsp; <i class="fa fa-window-close remove_owner_dtl" style="cursor: pointer;"></i></td></tr>';
        $("#owner_dtl_append").append(appendData);
    }
    $("#owner_dtl_append").on('click', '.remove_owner_dtl', function(e) {
        $(this).closest("tr").remove();
    });

     var ti = <?=$ti;?>;
    function trade_item_append_fun(){
        ti++;
        var tappendData = '<tr><td><select id="tade_item1" name="tade_item[]" class="form-control tade_item"  onchange="borderNormal(this.id);"><option value="">SELECT</option><?php if($tradeitemlist){foreach($tradeitemlist as $valit){?><option value="<?php echo $valit['id'];?>" ><?php echo $valit['trade_item'];?></option><?php }}?></select></td><td class="text-2x"><i class="fa fa-plus-square" style="cursor: pointer;" onclick="trade_item_append_fun();"></i>&nbsp;<i class="fa fa-window-close remove_trade_item" style="cursor: pointer;"></i></td></tr>';
        $("#trade_item_append").append(tappendData);
    }
    $("#trade_item_append").on('click', '.remove_trade_item', function(e) {
        $(this).closest("tr").remove();
    });

    function show_district(str,cnt)
    {      

          $.ajax({
            type:"POST",
            url: '<?php echo base_url("tradeapplyconnection/getdistrictname");?>',
            dataType: "json",
            data: {
                    "state_id":str
            },
           
            success:function(data){
              console.log(data);
              var option ="";
              jQuery(data).each(function(i, item){
                  option += '<option value="'+item.id+'">'+item.name+'</option>';
                  console.log(item.id, item.name)
              });
              $("#district"+cnt).html(option);
                
            }
               
        });

    }

    


</script>