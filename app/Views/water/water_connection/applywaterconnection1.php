<?php
session_start();

	//echo "---".$user_type;

  if($user_type=="")
  {
  	  echo  $this->include('layout_home/header');
	 //echo $this->include('layout_horizontal/header');
  }
  else if($user_type==4 || $user_type==5 || $user_type==7)
  {
     echo $this->include('layout_mobi/header');
  }
  else
  {
     echo $this->include('layout_vertical/header');
  }
  

?>
<style type="text/css">

	.error
	{
		color:red ;
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
            <li><a href="#">Apply Water Connection Form</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
		<?php
		if(isset($_SESSION['msg'])){?>
			<p class="bg bg-danger form-control text-center"><?php echo $_SESSION['msg']; unset($_SESSION['msg']);?></p>
		<?php } ?>
		<form id="form" name="form" method="post" >
			<?php if(isset($validation)){ ?>
				<?= $validation->listErrors(); ?>
			<?php } ?>
			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply Water Connection Form</h3>
                </div>
				<div class="panel-body">
                    <div class="row">
                        <label class="col-md-2">Type of Connection <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <select id="connection_type_id" name="connection_type_id" class="form-control">
                                <option value="">SELECT</option>
                             	<?php
                             	if($conn_type_list)
                             	{
                             		foreach($conn_type_list as $val)
                             		{
                             	?>
                             	
                             	<option value="<?php echo $val['id'];?>" <?php if($connection_type_id==$val['id']){echo "selected";}?>><?php echo $val['connection_type'];?></option>
                             	<?php		
                             		}
                             	}
                             	?>
                            </select>
                        </div>
						<label class="col-md-2">Connection Through <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<select name="conn_through_id" id="conn_through_id" class="form-control" onchange="show_hide_holding_box(this.value)">
								<option value="">SELECT</option>
								<?php
								if($conn_through_list)
								{
									foreach($conn_through_list as $val)
									{

										?>
										<option value="<?php echo $val['id'];?>" <?php if($conn_through_id==$val['id']){echo "selected"; }?>><?php echo $val['connection_through'];?></option>
										<?php
									}
								}
								?>
							</select>                        
						</div>
					</div>
					
					<div class="row">
                        <label class="col-md-2">Property Type<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<select name="property_type_id" id="property_type_id" class="form-control" onchange="show_flat_count(this.value);show_category(this.value);">
								<option value="">SELECT</option>
								<?php
								if($property_type_list)
								{

									foreach($property_type_list as $val)
									{

								?>

								<option value="<?php echo $val['id'];?>" <?php if($property_type_id==$val['id']){ echo "selected";}?>><?php echo $val['property_type'];?></option>

								<?php
									}
								}
								?>
							</select>
                        </div>


                        <label class="col-md-2">Owner Type<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<select name="owner_type" id="owner_type" class="form-control" onchange="validate_saf(),validate_holding()">
								<option value="">SELECT</option>
								<option value="OWNER" <?php if($owner_type=='OWNER'){ echo "selected"; }?>>OWNER</option>
								<option value="TENANT" <?php if($owner_type=='TENANT'){ echo "selected"; }?>>TENANT</option>
							</select>
						</div>


                    

					</div>
					
					<div class="row">


						<div id="category_block" style="display: none;">
							<label class="col-md-2">Category Type <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<select name="category" id="category" class="form-control">
									<option value="">SELECT</option>
									<option value="APL" <?php if($category=='APL'){ echo "selected"; }?>>APL</option>
									<option value="BPL" <?php if($category=='BPL'){ echo "selected"; }?>>BPL</option>
								</select>
							</div>
						</div>

						<div id="pipeline_div" style="display: none;">
						<label class="col-md-2">Pipeline Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<select name="pipeline_type_id" id="pipeline_type_id" class="form-control">
								<option value="">SELECT</option>
								<?php
								if($pipeline_type_list)
								{

									foreach($pipeline_type_list as $val)
									{

								?>

								<option value="<?php echo $val['id'];?>" <?php if($pipeline_type_id==$val['id']){echo "selected";}?>><?php echo $val['pipeline_type'];?></option>

									<?php

									}
								}
								?>
							</select>
                        </div>
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
                       	<!--<label class="col-md-2">If Holding Exists? <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<select name="holding_exists" id="holding_exists" class="form-control" onchange="show_hide_saf_holding_box(this.value)">
								<option value="">SELECT</option>
								<option value="YES" <?php if($holding_exists=='YES'){echo "selected"; }?>>Yes</option>
								<option value="NO" <?php if($holding_exists=='NO'){echo "selected"; }?>>No</option>
							</select>
                       </div>-->
                       
						<div id="holding_div" style="display: none;">
							<label class="col-md-2">Holding No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="holding_no" id="holding_no" class="form-control" onblur="validate_holding();" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);" placeholder="Enter Holding No.">
								<input type="hidden" name="prop_id" id="prop_id">
							</div>
						</div>
						<div id="saf_divs" style="display: none;">
							<label class="col-md-2">SAF No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="saf_no" id="saf_no" class="form-control" value="<?php echo isset($saf_no)?$saf_no:""; ?>" onblur="validate_saf()"  onkeypress="return isAlphaNum(event);">
								<input type="hidden" name="saf_id" id="saf_id">
							</div>
						</div>
                	</div>
					
					<div class="row">
						<div id="ward_hide">
							<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">

								<!--<span id="ward_show"></span>
								<input type="hidden" name="ward_id" id="ward_id" class="form-control">-->

								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($ward_list)
									{
										foreach($ward_list as $val)
										{
											
									?>
									<option value="<?php echo $val['id'];?>"><?php echo $val['ward_no']; ?></option>
									<?php			
										}
									}
									?>
								</select>
							</div>
						</div>

						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                        	<input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control"  value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>"  onkeypress="return isNum(event);" placeholder="Enter Total Area in Sqft">
						</div>
						
                	</div>
					
					<div class="row">
						<label class="col-md-2">Address<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNum(event);"  ><?php echo isset($address)?$address:"";?>
							</textarea>
						</div>

						<label class="col-md-2">Pin<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="pin" id="pin" maxlength="6" minlength="6" class="form-control" value="<?php echo isset($pin)?$pin:""; ?>" onkeypress="return isNum(event);"  placeholder="Enter Pin">
						</div>



                	</div>
					<div class="row">
                     	<label class="col-md-2">Landmark<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="landmark" id="landmark" class="form-control" value="<?php echo isset($landmark)?$landmark:""; ?>"  onkeypress="return isAlpha(event);"   placeholder="Enter Landmark">
						</div>

						<div id="flat_count_box" style="display: none;">
						<label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="flat_count" id="flat_count" class="form-control" value="<?php echo isset($flat_count)?$flat_count:""; ?>"  onkeypress="return isNum(event);"   placeholder="Enter No. of Flats">
						</div>
					    </div>
						
                	</div>
				</div>
			</div>
			
			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Applicant Details</h3>
                </div>
               
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Owner Name</th>
									<th>Guardian Name</th>
									<th>Mobile No.</th>
									<th>Email ID</th>
									<th>State</th>
									<th>District</th>
									<th>City</th>
									<th colspan="2" id="owner_add">Add</th>
								</tr>
                            </thead>

                            <?php
                            //print_r($owner_name);

                        
                            if(!isset($owner_name))
                            {
                            	

                            ?>
                            

							<tbody id="owner_dtl">
								<tr>
									<td><input type="text" name="owner_name[]"  id="owner_name1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name"></td>
									<td><input type="text" name="guardian_name[]" id="guardian_name1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name"></td>
									<td><input type="text" name="mobile_no[]" id="mobile_no1" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No."></td>
									<td><input type="email" name="email_id[]" id="email_id1" class="form-control"  placeholder="Email ID"></td>
									<td>
										<select name="state[]" id="state1" class="form-control" onchange="show_district(this.value,1)">
											<option value="">Select</option>
											<?php
											if($state_list):
												foreach($state_list as $val):
											?>
											<option value="<?php echo $val['name'];?>"><?php echo $val['name'];?></option>
											<?php
												endforeach;
											endif;
											?>
										</select>
									</td>
									<td>
										<select name="district[]" id="district1" class="form-control">
											<option value="">Select</option>
										</select>
									</td>
									<td><input type="text" name="city[]" id="city1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="City"></td>
									<input type="hidden" name="count" id="count" value="1">
									<td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
								</tr>
                            </tbody>

                            <?php	
                            }
                            else
                            {
                            	
                            	for($i=0;$i<sizeof($owner_name);$i++)
                            	{
                            		 //echo $owner_name[$i];




                            ?>
                            <tbody id="owner_dtl2">
                            	<tr>
									<td><input type="text" name="owner_name[]" id="owner_name<?=++$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name" value="<?php echo $owner_name[$i];?>"></td>
									<td><input type="text" name="guardian_name[]" id="guardian_name<?=++$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name" value="<?php echo $guardian_name[$i];?>"></td>
									<td><input type="text" name="mobile_no[]" id="mobile_no<?=++$i;?>" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No." value="<?php echo $mobile_no[$i];?>"></td>
									<td><input type="email" name="email_id[]" id="email_id<?=++$i;?>" class="form-control"  placeholder="Email ID" value="<?php echo $email_id[$i];?>"></td>
									<td>
										<select name="state[]" id="state<?=++$i;?>" class="form-control" onchange="show_district(this.value,1)">
											<option value="">Select</option>
											<?php
											if($state_list):
												foreach($state_list as $val):
													

											?>
											<option value="<?php echo $val['name'];?>" <?php if(strtoupper($state[$i])==strtoupper($val['name'])){ echo "selected"; }?>><?php echo $val['name'];?></option>
											<?php
												endforeach;
											endif;
											?>
										</select>
									</td>
									<td>
										<select name="district[]" id="district<?=++$i;?>" class="form-control">
											<option value="">Select</option>
										</select>
									</td>
									<td><input type="text" name="city[]" id="city<?=++$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="City"></td>
									<input type="hidden" name="count" id="count" value="1">
									<td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
								</tr>

                            </tbody>
                            <?php	

                            	}
                            }
                            ?>
                        </table>
                    </div>
					<div id="owner_append"></div>
				
			</div>
			
			<input type="hidden" name="elec_k_no" id="elec_k_no" >
			<input type="hidden" name="elect_acc_no" id="elect_acc_no" >
			<input type="hidden" name="elect_bind_book_no" id="elect_bind_book_no" >
			<input type="hidden" name="elect_cons_category" id="elect_cons_category" >
			
			<div class="panel">
				<div class="panel-body text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
			
		</form>
        
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<?php 
	if($user_type==4 || $user_type==5 || $user_type==7)
	{

		echo $this->include('layout_mobi/footer');
	}
	else
	{
		echo $this->include('layout_vertical/footer');
	}
  
 ?>


<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">
	 /*$(window).load(function()  {
		alert("dsaavvdsads");

		var connection_through_id=$("#conn_through_id").val();
     	//alert(connection_through_id);
     	show_hide_holding_box(connection_through_id);

      var saf_no=$("#saf_no").val();
      var holding_no=$("#holding_no").val();
      var owner_type=$("#owner_type").val();
      
     	
      

      if(saf_no)
      {
          validate_saf();
      }

      if(holding_no and connection_through_id==1)
      {
         
          validate_holding();
          $("#onwer_add").remove();

      }


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


});*/

</script>
<script>

$(document).ready(function () {
	
	

	
/*	jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please"); 

*/
	

	$('#form').validate({ // initialize the plugin
        rules: {

        	"connection_type_id":"required",
        	"conn_through_id":"required",
        	"category":"required",
        	"pipeline_type_id":"required",
        	"property_type_id":"required",
        	"owner_type":"required",
        	"ward_id":"required",
        	"holding_no":"required",
        	"address":"required",
        	"flat_count":"required",

        	"landmark":{
        		"required":true,
            	"lettersonly":true,

        	},
        	
            "owner_name[]": {
				"required":true,
            	"lettersonly":true,

            },
            "mobile_no[]": {
            	"required":true,
            	"digits":true,
            	"minlength":10,
            	"maxlength":10,

            },
            "pin":{
				"required":true,
            	"digits":true,


            },
            "area_in_sqft":{
            	"required":true,
            }
            
        }
    });


    	load();


});


function load()
{
		//alert("dsaddsa");

		var connection_through_id=$("#conn_through_id").val();
     	//alert(connection_through_id);
     	var property_type_id=$("#property_type_id").val();
     	show_hide_holding_box(connection_through_id);

     	show_category(property_type_id);

      var saf_no=$("#saf_no").val();
      var holding_no=$("#holding_no").val();
      var owner_type=$("#owner_type").val();
      
     	
      

      if(saf_no)
      {
          validate_saf();
      }

      if(holding_no && connection_through_id==1)
      {
         
          validate_holding();
          $("#onwer_add").remove();

      }
}
</script>


<script type="text/javascript">
	   
  /*$("#btn_review").click(function(){
        var process = true;
      


        var property_type_id = $("#property_type_id").val();
        var pipeline_type_id = $("#pipeline_type_id").val();
        var connection_type_id = $("#connection_type_id").val();
        var connection_through_id = $("#conn_through_id").val();

        var category = $("#category").val();
        var ward_id = $("#ward_id").val();
       
        var holding_exists = $("#holding_exists").val();
        var area_in_sqft = $("#area_in_sqft").val();
        var area_in_sqmt = $("#area_in_sqmt").val();
        var address = $("#address").val();

        var landmark = $("#landmark").val();
        var pin = $("#pin").val();
        var owner_type = $("#owner_type").val();
        //var owner_name = $("#owner_name").val();
        var mobile_no = $("[name='mobile_no[]']").val();
      
        var holding_no = $("#holding_no").val();
        var saf_no = $("#saf_no").val();
        var owner_name=$("[name='owner_name[]']").val();
        


        if ( property_type_id=="" ) {
            
       
                $("#property_type_id").css('border-color', 'red');

                 process = false;
            
        }

         if ( pipeline_type_id=="" ) {
            
       
                $("#pipeline_type_id").css('border-color', 'red'); process = false;
            
        }

         if ( connection_type_id=="" ) {
            
       
                $("#connection_type_id").css('border-color', 'red'); process = false;
            
        }

         if ( connection_through_id=="" ) {
            
       
                $("#conn_through_id").css('border-color', 'red'); process = false;
            
        }

         if ( category=="" ) {
            
       
                $("#category").css('border-color', 'red'); process = false;
            
        }
         if ( ward_id=="" ) {
            
       
                $("#ward_id").css('border-color', 'red'); process = false;
            
        }
        if ( address=="" ) {
            
       
                $("#address").css('border-color', 'red'); process = false;
            
        }

         if ( holding_exists=="" ) {
            
              
                $("#holding_exists").css('border-color', 'red'); process = false;
            
        }
         if ( holding_exists!="" ) {
            
              if(holding_exists=='YES' &&  holding_no=="")
              {

                $("#holding_no").css('border-color', 'red'); process = false;
              }
              else if(holding_exists=='NO' &&  saf_no=="")
              {
                  $("#saf_no").css('border-color', 'red'); process = false;
              }
            
        }

         if ( area_in_sqft=="" ) {
            
       
                $("#area_in_sqft").css('border-color', 'red'); process = false;
            
        }
         if ( area_in_sqmt=="" ) {
            
       
                $("#area_in_sqmt").css('border-color', 'red'); process = false;
            
        }
         if ( address=="" ) {
            
       
                $("#address").css('border-color', 'red'); process = false;
            
        }
        

         if ( landmark=="" ) {
            
       
                $("#landmark").css('border-color', 'red'); process = false;
            
        }
         if ( pin=="" ) {
            
       
                $("#pin").css('border-color', 'red'); process = false;
            
        }

          if ( owner_type=="" ) {
            
       
                $("#owner_type").css('border-color', 'red'); process = false;
            
        }

        if ( owner_name=="" ) {
            
       
                $("[name='owner_name[]']").css('border-color', 'red'); process = false;
            
        }

          if (mobile_no=="") {
            
       
                $("[name='mobile_no[]']").css('border-color', 'red'); process = false;
            
            }
           
            
        
        return process;
    });
	
	*/


    /* $( document ).ready(function() {
   
     	//alert('ds');
     
    });
*/

    function show_district(str,cnt)
    {      

       // alert(cnt);

          $.ajax({
            type:"POST",
            url: '<?php echo base_url("tradeapplylicence/getdistrictname");?>',
            dataType: "json",
            data: {
                    "state_name":str
            },
           
            success:function(data){
             // console.log(data);
              var option ="";
              jQuery(data).each(function(i, item){
                  option += '<option value="'+item.name+'">'+item.name+'</option>';
                  console.log(item.name, item.name)
              });
              $("#district"+cnt).html(option);
            //  $("[name='district[]']").html(option);
            }
               
        });

    }
    function validate_holding()
    {
    	//alert('hhhh');
         
         $("#owner_append").html("");

         var holding_no=$("#holding_no").val();

         // var ward_id=$("#ward_id").val();
         
         var owner_type=$("#owner_type").val();
          //  alert(owner_type);
          // alert(ward_id);
          //alert(holding_no);
       

         if(holding_no )
         {
          // alert(holding_no);
         

            
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterApplyNewConnectionCitizen/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no
                },
               
                success:function(data){
                 // console.log(data);
                   //alert(data);
                   if (data.response==true) {

                   // var obj = JSON.parse(data.dd);
                   // alert(data.dd.0.owner_name);
                    var tbody="";
                    var i=1;

                  for(var k in data.dd) {
                      // console.log(k, data.dd[k]['owner_name']);
                        tbody+="<tr>";
                        var prop_id=data.dd[k]['id'];
                        var ward_mstr_id=data.dd[k]['ward_mstr_id'];
                        var ward_no=data.dd[k]['ward_no'];
                        var area_sqft=data.dd[k]['area_of_plot']*436;
                        var elect_consumer_no=data.dd[k]['elect_consumer_no'];
                        var elect_acc_no=data.dd[k]['elect_acc_no'];
                        var elect_bind_book_no=data.dd[k]['elect_bind_book_no'];
                        var elect_cons_category=data.dd[k]['elect_cons_category'];
                        var prop_pin_code=data.dd[k]['prop_pin_code'];
                        var prop_address=data.dd[k]['prop_address'];
                        
                        
                        //alert(area_sqft);
                        
                        if(owner_type=='OWNER')
                        {

                        	//alert('owner_name');

                    //   $("#owner_name").val( data.dd[k]['owner_name']);
                       tbody+='<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly  placeholder="Owner Name" ></td>';

                       tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly  placeholder="Guardian Name"></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly maxlength=10 minlength=10  placeholder="Mobile No."></td>';

                        tbody+='<td><input type="email" name="email_id[]" id="email_id" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['email']+'"  placeholder="Email ID" readonly></td>';

                        tbody+='<td><input type="text" name="state[]"  value="'+data.state+'" readonly class="form-control"></td>';

                         tbody+='<td><input type="text" name="district[]"  value="'+data.district+'" readonly class="form-control"></td>';

                         tbody+='<td><input type="text" name="city[]" value="'+data.city+'" readonly class="form-control"></td>';

                        



                       tbody+="</tr>";
                       i++;

                       //alert(tbody);

                       
                     }
                     else
                     {
                            var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" minlength="10" maxlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td><select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control"><option value="">Select</option><?php if($state_list): foreach($state_list as $val) :?><option value="<?php echo $val['name'];?>"><?php echo $val['name'];?></option><?php endforeach; endif;?> </select></td><td><select name="district[]" id="district1" class="form-control"><option value="">Select</option></select></td><td><input type="text" name="city[]" class="form-control" placeholder="City"></td><td onclick="add_owner()">Add</td></tr>';

                            //alert(tbody);

                             $("#owner_dtl").html("");
                             $("#owner_dtl2").html("");
                             
                           //  $("#owner_dtl").html(appendData);
                     }

                    }

                     $("#owner_dtl").html(tbody);
                     $("#owner_dtl2").html(tbody);

                     $("#prop_id").val(prop_id);
                     $("#count").val(i);
                     $("#ward_id").val(ward_mstr_id);
                     $("#ward_id").prop("readonly",true);
                     $("#area_in_sqft").val(area_sqft);
                     $("#area_in_sqft").prop("readonly",true);
                    // $("#ward_show").html(ward_no);
                     $("#elec_k_no").val(elect_consumer_no);
                     $("#elect_acc_no").val(elect_acc_no);
                     $("#elect_bind_book_no").val(elect_bind_book_no);
                     $("#elect_cons_category").val(elect_cons_category);
                     $("#address").val(prop_address);
                     $("#pin").val(prop_pin_code);
                     $("#address").prop("readonly",true);
                     $("#pin").prop("readonly",true);
                     $("#owner_add").hide();

                     
            

           

                 
                    
                     // alert(data.data); 
                   } else {

                      alert('Holding No. not Found');
                      $("#holding_no").val("");
                      $("#prop_id").val("");
                      $("#ward_id").val("");
                      $("#elec_k_no").val("");
                      $("#elect_acc_no").val("");
                      $("#elect_bind_book_no").val("");
                      $("#elect_cons_category").val("");
                      $("#address").val("");
                      $("#pin").val("");
                      $("#address").prop("readonly",false);
                      $("#pin").prop("readonly",false);
                      $("#ward_id").prop("readonly",false);
                      
                      //$("#ward_show").val("");
                      var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td><select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control"><option value="">Select</option><?php if($state_list): foreach($state_list as $val) :?><option value="<?php echo $val['name'];?>"><?php echo $val['name'];?></option><?php endforeach; endif;?> </select></td><td><select name="district[]" id="district1" class="form-control"><option value="">Select</option></select></td><td><input type="text" name="city[]" class="form-control" placeholder="City"></td><td onclick="add_owner()">Add</td></tr>';

                          $("#owner_dtl").html("");

                          $("#owner_dtl").html(tbody);

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


    function show_flat_count(str)
    {	
        var property_type=str;
     	
        if(property_type==7)
        {
          $("#flat_count_box").show();
          $("#flat_count").attr("required",true);
        }
        else
        {
          $("#flat_count_box").hide();
          $("#flat_count").attr("required",false);
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

    
      $("#owner_append").html("");
       var holding_exists=str;
      // alert("sasa");
       // $("[type=owner_name[]]").val(""); 
       // $("#mobile_no[]").val(""); 
        //$("#guardian_name[]").val(""); 
        //$("#prop_id").val(""); 
          
        $("input[name='owner_name[]']").val("");
        $("input[name='guardian_name[]']").val("");
        $("input[name='mobile_no[]']").val("");

        $("input[name='owner_name[]']").attr("readonly",false);
        $("input[name='guardian_name[]']").attr("readonly",false);
        $("input[name='mobile_no[]']").attr("readonly",false);
        


        $("input[name='state[]']").attr("readonly",false);
        $("input[name='district[]']").attr("readonly",false);
        $("input[name='city[]']").attr("readonly",false);
        $("input[name='email_id[]']").attr("readonly",false);
        
         $("input[name='state[]']").val("");
        $("input[name='district[]']").val("");
        $("input[name='city[]']").val("");
        $("input[name='email_id[]']").val("");
        

       if(holding_exists=='YES')
       {
          $("#holding_div").show();
          $("#saf_div").hide();
          $("#holding_no").attr('required',true);
          $("#saf_no").attr('required',false);
          $("#saf_no").val("");
          $("#saf_id").val("");
          $("#ward_id").val("");
          $("#ward_show").html("");
          
          
       }
       else if(holding_exists=='NO')
       {
          $("#saf_div").show();
          $("#holding_div").hide();
          $("#saf_no").attr('required',true);
          $("#holding_no").attr('required',false); 
          $("#holding_no").val(""); 
          $("#prop_id").val(""); 
          $("#ward_id").val("");
          $("#ward_show").html("");

          
          
              
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

    function show_hide_holding_box(str)
    {

    	
       $("#owner_append").html("");
       var connection_through_id=str;
       //alert(connection_through_id);
      	
          	
        $("input[name='owner_name[]']").val("");
        $("input[name='guardian_name[]']").val("");
        $("input[name='mobile_no[]']").val("");

        $("input[name='owner_name[]']").attr("readonly",false);
        $("input[name='guardian_name[]']").attr("readonly",false);
        $("input[name='mobile_no[]']").attr("readonly",false);
        


        $("input[name='state[]']").attr("readonly",false);
        $("input[name='district[]']").attr("readonly",false);
        $("input[name='city[]']").attr("readonly",false);
        $("input[name='email_id[]']").attr("readonly",false);
        
         $("input[name='state[]']").val("");
        $("input[name='district[]']").val("");
        $("input[name='city[]']").val("");
        $("input[name='email_id[]']").val("");
        

       if(connection_through_id==1)
       {
          $("#holding_div").show();
          $("#holding_no").attr('required',true);
        
          
       }
       else
       {
       	  $("#holding_div").hide();
          $("#holding_no").attr('required',false);
        
       }
     

    }


    function validate_saf()
    {

    	//alert('ssss');

        $("#owner_append").html("");
         

         var saf_no=$("#saf_no").val();

         //var ward_id=$("#ward_id").val();
         var owner_type=$("#owner_type").val();
        // alert(saf_no);
        

         if(saf_no )
         {

         //alert("saf_no");

          $.ajax({
            type:"POST",
            url: '<?php echo base_url("WaterApplyNewConnectionCitizen/validate_saf_no");?>',
            dataType: "json",
            data: {
                    "saf_no":saf_no
            },
           
            success:function(data){
             // console.log(data);
             // alert(data.payment_status);

               if (data.response==true) {

                if(data.payment_status==0)
                {
                    alert('Please make your payment in SAF first');
                    $("#saf_no").val("");
                }
               
                else
                {
                       // alert(data.dd.0.owner_name);
                    var tbody="";
                    var i=1;

                  for(var k in data.dd) {
                      // console.log(k, data.dd[k]['owner_name']);
                        tbody+="<tr>";
                        var saf_id=data.dd[k]['id'];
                        var prop_dtl_id=data.dd[k]['prop_dtl_id'];
                        var ward_id=data.dd[k]['ward_mstr_id'];
                        var ward_no=data.dd[k]['ward_no'];

                        //alert(prop_dtl_id);

                        if(prop_dtl_id!=0)
                        {
                            alert('Your Holding has been generated kindly provide your Holding No.');
                            break;
                        }

                        if(owner_type=='OWNER')
                        {


                    //   $("#owner_name").val( data.dd[k]['owner_name']);
                       tbody+='<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly  placeholder="Owner Name"></td>';

                       tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly  placeholder="Guardian Name"></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'" readonly maxlength="10" minlength="10"  placeholder="Mobile No."></td>';

                        tbody+='<td><input type="email" name="email_id[]" id="email_id" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['email']+'"  placeholder="Email ID" readonly></td>';

                        tbody+='<td><input type="text" name="state[]" value="'+data.state+'" class="form-control" readonly></td>';

                         tbody+='<td><input type="text" name="district[]" class="form-control" value="'+data.district+'" readonly></td>';

                         tbody+='<td><input type="text" name="city[]" id="city" class="form-control" onkeypress="return isAlpha(event);"  placeholder="City" value="'+data.city+'" readonly></td>';

                        



                       tbody+="</tr>";
                       i++;

                       }
                       else
                       {

                          var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td><select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control"><option value="">Select</option><?php if($state_list): foreach($state_list as $val) :?><option value="<?php echo $val['name'];?>"><?php echo $val['name'];?></option><?php endforeach; endif;?> </select></td><td><select name="district[]" id="district1" class="form-control"><option value="">Select</option></select></td><td><input type="text" name="city[]" class="form-control" placeholder="City"></td><td onclick="add_owner()">Add</td></tr>';

                            $("#owner_dtl").html("");
                          //  $("#owner_dtl").html(appendData);

                       }

                    }
                     $("#owner_dtl").html(tbody);
                     $("#saf_id").val(saf_id);
                     $("#count").val(i);
                     $("#ward_id").val(ward_id);
                     $("#ward_show").html(ward_no);


              
                    

                     
                 //  for
                   
                    
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
        else
        {
        

              var appendData = '<tr><td><input type="text" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td><select name="state[]" id="state1" onchange="show_district(this.value,1)" class="form-control"><option value="">Select</option><?php if($state_list): foreach($state_list as $val) :?><option value="<?php echo $val['name'];?>"><?php echo $val['name'];?></option><?php endforeach; endif;?> </select></td><td><select name="district[]" id="district1" class="form-control"><option value="">Select</option></select></td><td><input type="text" name="city[]" class="form-control" placeholder="City"></td><td onclick="add_owner()">Add</td></tr>';

            $("#owner_dtl").html("");
            $("#owner_dtl").html(appendData);


        }

    }

 

    function add_owner()
    {

        var count=$("#count").val();
        var count=parseInt(count)+1;
        //alert('sasa');

        var html="<div class='row' id='del"+count+"'><div class='col-md-12'><table class='table table-responsive'><tr><td><input type='text' name='owner_name[]' id='owner_name"+count+"' class='form-control' placeholder='Owner Name' required></td><td><input type='text' name='guardian_name[]' id='guardian_name"+count+"'  class='form-control'  placeholder='Guardian Name'></td><td><input type='text' name='mobile_no[]' id='mobile_no"+count+"'  maxlength='10' minlength='10' class='form-control' required placeholder='Mobile No.'><td><input type='email' name='email_id[]' id='email_id"+count+"' class='form-control' placeholder='Email ID'></td></td><td><select name='state[]' id='state"+count+"' class='form-control' onchange='show_district(this.value,"+count+")'><option value=''>Select</option><?php if($state_list): foreach($state_list as $val):?><option value='<?php echo $val['name']?>'><?php echo $val['name'];?></option><?php endforeach; endif; ?></select></td><td><select name='district[]' id='district"+count+"' class='form-control'><option value=''>Select</option></select></td><td><input type='text' name='city[]' id='city"+count+"' class='form-control' placeholder='City'></td><td onclick='add_owner()'><i class='form-control fa fa-plus-square'></i></td><td value='"+count+"' onclick='delete_owner("+count+")' class='remove_owner_dtl' ><i class='form-control fa fa-window-close'></i></td></tr></table></div></div>";

        

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

         /*$("#owner_append").on('click', '.remove_owner_dtl', function(e) {
             $(this).closest("div").remove();

     

        });*/
        

        $("#del"+count).remove();
    }

    function show_category(property_type_id)
    {
    	var property_type_id=property_type_id;
    	if(property_type_id==1)
    	{

    		$("#category_block").show();
    		$("#pipeline_div").show();
    		
    	}
    	else
    	{
    		$("#category_block").hide();
    		$("#pipeline_div").hide();
    	}
    }
</script>