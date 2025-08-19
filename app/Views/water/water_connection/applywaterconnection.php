<?php
@session_start();
if($user_type=="")
{   
    echo  $this->include('layout_home/header');
    
}
  # 4	Team Leader	
  # 5	Tax Collector
  # 7	ULB Tax Collector
  else if( $user_type==5 || $user_type==7)
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
<!-- <script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script>  -->

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <?php 
        if($user_type!="" && $user_type!=5)
        { 
            ?>
            <div id="page-head">
                <!--Breadcrumb-->
                <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Water</a></li>
                    <li class="active"><a href="#">Apply Water Connection</a></li>
                </ol>
                <!--End breadcrumb-->
            </div>
            <?php 
        }
    ?>
    <!--Page content-->

    <div id="page-content">
		<?php
		if(isset($_SESSION['msg']))
        {
            ?>
			<p class="bg bg-danger form-control text-center"><?php echo $_SESSION['msg']; unset($_SESSION['msg']);?></p>
		    <?php 
        } 
        elseif(cHasCookie('msg'))
        {
            ?>
			<p class="bg bg-danger form-control text-center"><?php echo cGetCookie('msg'); cDeleteCookie('msg');?></p>
		    <?php 
        }
        ?>
		<form id="form" name="form" method="post" >
			<?php if(isset($validation)){ ?>
				<?= $validation->listErrors(); ?>
			<?php } ?>
			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply Water Connection Form
                        <?php
                            if($user_type=="")
                            {
                                ?>
                                <a href="<?=base_url()?>/WaterApplyNewConnectionCitizen/searchList/<?=md5(1)?>" class="pull-right ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg> Search
                                </a>
                                <?php
                            }
                            elseif($user_type==5)
                            {
                                ?>
                                <a class="pull-right btn btn-info" href="<?=base_url()?>/WaterMobileIndex/index">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>Back
                                </a>
                                <?php
                            }
                        ?>
                    </h3>
                    
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
                             	
                             	<option value="<?php echo $val['id'];?>" <?php if(isset($connection_type_id) && $connection_type_id==$val['id']){echo "selected";}?>><?php echo $val['connection_type'];?></option>
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
										<option value="<?php echo $val['id'];?>" <?php if(isset($conn_through_id) && $conn_through_id==$val['id']){echo "selected"; }?>><?php echo $val['connection_through'];?></option>
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
							<select name="property_type_id" id="property_type_id" class="form-control" onchange="show_flat_count(this.value);show_category(this.value);area_removeReadOnly();">
								<option value="">SELECT</option>
								<?php
								if($property_type_list)
								{

									foreach($property_type_list as $val)
									{

								?>

								<option value="<?php echo $val['id'];?>" <?php if(isset($property_type_id) && $property_type_id==$val['id']){ echo "selected";}?>><?php echo $val['property_type'];?></option>

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
								<option value="OWNER" <?php if(isset($owner_type) && $owner_type=='OWNER'){ echo "selected"; }?>>OWNER</option>
								<option value="TENANT" <?php if(isset($owner_type) && $owner_type=='TENANT'){ echo "selected"; }?>>TENANT</option>
							</select>
						</div>


                    

					</div>
					
					<div class="row">


						<div id="category_block" style="display: none;">
							<label class="col-md-2">Category Type <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<select name="category" id="category" class="form-control">
									<option value="">SELECT</option>
									<option value="APL" <?php if(isset($category) && $category=='APL'){ echo "selected"; }?>>APL</option>
									<option value="BPL" <?php if(isset($owner_type) && $category=='BPL'){ echo "selected"; }?>>BPL</option>
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

								<option value="<?php echo $val['id'];?>" <?php if(isset($pipeline_type_id) && $pipeline_type_id==$val['id']){echo "selected";}?>><?php echo $val['pipeline_type'];?></option>

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
                    <h3 class="panel-title">Applicant Property Details</h3>
                </div>
                <div class="panel-body">
					<div class="row">
                       	<!--<label class="col-md-2">If Holding Exists? <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<select name="holding_exists" id="holding_exists" class="form-control" onchange="show_hide_saf_holding_box(this.value)">
								<option value="">SELECT</option>
								<option value="YES" <?php if(isset($holding_exists) && $holding_exists=='YES'){echo "selected"; }?>>Yes</option>
								<option value="NO" <?php if(isset($holding_exists) && $holding_exists=='NO'){echo "selected"; }?>>No</option>
							</select>
                       </div>-->
                       
						<div id="holding_div" style="display: none;">
							<label class="col-md-2">Holding No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="holding_no" id="holding_no" class="form-control" onChange="validate_holding();" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Enter Holding No." maxlength="16" />
								<input type="hidden" name="prop_id" id="prop_id">
							</div>
						</div>
						<div id="saf_div" style="display: none;">
							<label class="col-md-2">SAF No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="saf_no" id="saf_no" class="form-control" value="<?php echo isset($saf_no)?$saf_no:""; ?>" onblur="validate_saf()"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Enter SAF No.">
								<input type="hidden" name="saf_id" id="saf_id">
							</div>
						</div>
                	</div>
					
					<div class="row">
						<div id="ward_hide">
							<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
                               
							
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">SELECT</option>
									<?php
									if($ward_list)
									{
										foreach($ward_list as $val)
										{
											
									?>
									<option value="<?php echo $val['ward_mstr_id'];?>"><?php echo $val['ward_no']; ?></option>
									<?php			
										}
									}
									?>
								</select>
							</div>
						</div>

						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                        	<input type="number" name="area_in_sqft" id="area_in_sqft" class="form-control"  value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>"  onkeypress="return isNum(event);" placeholder="Enter Total Area in Sqft">
                            <input type="hidden" name="hidden_area_in_sqft" id="hidden_area_in_sqft" class="form-control"  value="<?php echo isset($area_in_sqft)?$area_in_sqft:""; ?>"  onkeypress="return isNum(event);" placeholder="Enter Total Area in Sqft">
						</div>
						
                	</div>
					
					<div class="row">

                        <label class="col-md-2">Landmark<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="landmark" id="landmark" class="form-control" value="<?php echo isset($landmark)?$landmark:""; ?>"  onkeypress="return isAlpha(event);"   placeholder="Enter Landmark">
						</div>
						

						<label class="col-md-2">Pin<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="pin" id="pin" maxlength="6" minlength="6" class="form-control" value="<?php echo isset($pin)?$pin:""; ?>" onkeypress="return isNum(event);"  placeholder="Enter Pin">
						</div>



                	</div>
					<div class="row">
                        <label class="col-md-2">Address<span class="text-danger">*</span></label>
						<div class="col-md-10 pad-btm">
							<textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNum(event);"  ><?php echo isset($address)?$address:"";?>
							</textarea>
						</div>
                    </div>

                    <div class="row">
						<div id="flat_count_box" style="display: none;">
                            <label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" name="flat_count" id="flat_count" class="form-control" value="<?php echo isset($flat_count)?$flat_count:"0"; ?>"  onkeypress="return isNum(event);"   placeholder="Enter No. of Flats">
                            </div>
					    </div>
						
                	</div>
				</div>
			</div>
			
			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Applicant Details</h3>
                </div>
               
					<div class="panel-body table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Owner Name<span class="text-danger">*</span></th>
									<th>Guardian Name</th>
									<th>Mobile No.<span class="text-danger">*</span></th>
									<th>Email ID</th>
									
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
                                        <input type="hidden" name="count" id="count" value="1">
                                        <td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
                                    </tr>
                                    <!-- <div id="owner_append"></div> -->
                                </tbody>
                                <?php
                            }
                            else
                            {
                                //print_var($owner_name);die;
                            	for($i=0; $i < sizeof($owner_name); $i++)
                            	{
                            		//echo $owner_name[$i];
                                    //print_var($i);continue;
                                    ?>
                                    <tbody id="owner_dtl2">
                                        <tr>
                                            <td><input type="text" name="owner_name[]" id="owner_name<?=$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name" value="<?php echo $owner_name[$i];?>" /></td>
                                            <td><input type="text" name="guardian_name[]" id="guardian_name<?=$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name" value="<?php echo $guardian_name[$i];?>" /></td>
                                            <td><input type="text" name="mobile_no[]" id="mobile_no<?=$i;?>" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No." value="<?php echo $mobile_no[$i];?>" /></td>
                                            <td><input type="email" name="email_id[]" id="email_id<?=$i;?>" class="form-control"  placeholder="Email ID" value="<?php echo $email_id[$i];?>" /></td>
                                            
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

            <?php
            if($user_type=='')
            {
                ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Applicant Electricity Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-2">K. No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" name="elec_k_no" id="elec_k_no" class="form-control"  value="<?php echo $elec_k_no??null; ?>">
                            </div>
                            <label class="col-md-2">Bind Book No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" name="elec_bind_book_no" id="elec_bind_book_no" class="form-control"  value="<?php echo isset($elec_bind_book_no)?$elec_bind_book_no:""; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">Account No.</label>
                            <div class="col-md-3 pad-btm">
                            <input type="text" name="elec_account_no" id="elec_account_no" class="form-control"  value="<?php echo isset($elec_account_no)?$elec_account_no:""; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">Category Type</label>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" value="Residential - DS I/II" <?php if($elec_category??null=='RESIDENTIAL - DS I/II'){echo "checked"; }?>>  Residential - DS I/II 
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" value="Commercial - NDS II/III" <?php if($elec_category??null=='COMMERCIAL - NDS II/III'){echo "checked"; }?>>  Commercial - NDS II/III 
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" value="Agriculture - IS I/II" <?php if($elec_category??null=='AGRICULTURE - IS I/II'){echo "checked"; }?>>Agriculture - IS I/II
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" value="Low Tension - LTS" <?php if($elec_category??null=='LOW TENSION - LTS'){echo "checked"; }?>>Low Tension - LTS 
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" value="High Tension - HTS" <?php if($elec_category??null=='HIGH TENSION - HTS'){echo "checked"; }?>>High Tension - HTS
                            </div>                        
                        </div>                    
                    </div>
                </div>
                <?php
            }
            else 
            {
                ?>
                <input type="hidden" name="elec_k_no" id="elec_k_no" >
                <input type="hidden" name="elect_acc_no" id="elect_acc_no" >
                <input type="hidden" name="elect_bind_book_no" id="elect_bind_book_no" >
                <input type="hidden" name="elect_cons_category" id="elect_cons_category" >
                <?php

                }
            ?>


			
			<div class="panel">
				<div class="panel-body text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
			
		</form>
        
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<?php 
    if($user_type=='')
    {
        echo $this->include('layout_home/footer');
    }
	elseif( $user_type==5 || $user_type==7)
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
  


    <?php
        if($user_type!='')
        {?>
            $('#form').validate({ // initialize the plugin counter
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
                    "landmark": {
                        "required":true,
                    },
                    "pin": {
                        "required":true,
                        "digits":true,
                    },
                    "area_in_sqft": {
                        "required":true,
                    },
        
                    "owner_name[]": {
                        "required":true,
                    },
                    "mobile_no[]": {
                        "required": true,
                        "digits": true,
                        "minlength": 10,
                        "maxlength": 10,
                    },
                    
                    
                    
                }
            }); 
            <?php
        }
        else
        {
            ?>
            $('#form').validate({ // initialize the plugin Citizen
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
                    "elec_bind_book_no":"required",
                    "elec_bind_book_no":"required",
                    "elec_bind_book_no":"required",
                    "elec_category":"required",
                    "elec_account_no":"required",
                    "elec_k_no":{"required":true,"digits": true,},
                    "landmark": {
                        "required":true,
                    },
                    "pin": {
                        "required":true,
                        "digits":true,
                    },
                    "area_in_sqft": {
                        "required":true,
                    },

                    "owner_name[]": {
                        "required":true,
                    },
                    "mobile_no[]": {
                        "required": true,
                        "digits": true,
                        "minlength": 10,
                        "maxlength": 10,
                    },
                    
                    "guardian_name[]":{
                        "required":true,
                    },
                    "email_id[]":{
                        "required":true,
                    },
                    
                
                
                }
            });
            <?php
        }
    ?>
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

	
	load();

	$("#holding_no").keypress(function () {
   
        $("#btn_review").attr('disabled', true);
   
	});

	$("#saf_no").keypress(function () {
   
        $("#btn_review").attr('disabled', true);
   
	});

	
	/*jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please"); 
	*/
	

	


    


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
	   

    function show_flat_count(str)
    {	
        var property_type=str;
        if(property_type==7)
        {
          $("#flat_count_box").show();
          $("#flat_count").attr("required", true);
        }
        else
        {
          $("#flat_count_box").hide();
          $("#flat_count").attr("required", false);
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
        


      

       if(holding_exists=='YES')
       {
          $("#holding_div").show();
          $("#saf_div").hide();
          $("#holding_no").attr('required',true);
          $("#saf_no").attr('required',false);
          $("#saf_no").val("");
          $("#saf_id").val("");
          $("#ward_id").val("");
         
          
          
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
        

          $("#ward_id").val('');
          $("#area_in_sqft").val('');
          $("#address").val('');
          $("#pin").val('');
          
          $("#ward_id").attr("readonly",false);
          $("#area_in_sqft").attr("readonly",false);
          $("#address").attr("readonly",false);
          $("#pin").attr("readonly",false);
          

       if(connection_through_id==1)
       {
          $("#holding_div").show();
          $("#holding_no").attr('required',true);
          $("#saf_div").hide();
          $("#saf_no").attr('required',false);
         

       }
       else if(connection_through_id==5)
       {
       	  $("#holding_div").hide();
          $("#holding_no").attr('required',false);
          $("#saf_div").show();
          $("#saf_no").attr('required',true);
          
       }
       else
       {
       	  $("#holding_div").hide();
          $("#holding_no").attr('required',false);
          $("#saf_div").hide();
          $("#saf_no").attr('required',false);
          $("#saf_no").val('');
          $("#holding_no").val('');
          
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
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterApplyNewConnectionCitizen/validate_saf_no");?>',
                dataType: "json",
                data: {
                        "saf_no":saf_no
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success:function(data){
                    $("#loadingDiv").hide();
                    console.log(data);
                    

                if (data.response==true)
                {
                    $("#btn_review").attr('disabled', false);
                        // alert(data.dd.0.owner_name);
                        var tbody="";
                        var i=1;

                        for(var k in data.dd)
                        {
                            //console.log(k, data.dd[k]['owner_name']);
                            tbody+="<tr>";
                            var saf_id=data.dd[k]['id'];
                            var prop_dtl_id=data.dd[k]['prop_dtl_id'];
                            var ward_mstr_id=data.dd[k]['ward_mstr_id'];
                            var ward_no=data.dd[k]['ward_no'];
                            var payment_status=data.dd[k]['payment_status'];
                            var area_sqft=data.dd[k]['total_area_sqft'];
                            var elect_consumer_no=data.dd[k]['elect_consumer_no'];
                            var elect_acc_no=data.dd[k]['elect_acc_no'];
                            var elect_bind_book_no=data.dd[k]['elect_bind_book_no'];
                            var elect_cons_category=data.dd[k]['elect_cons_category'];
                            var prop_pin_code=data.dd[k]['prop_pin_code'];
                            var prop_address=data.dd[k]['prop_address'];
                            //alert(prop_dtl_id);
                            if(payment_status==0)
                            {
                                alert('Please make your payment in SAF first');
                                $("#saf_no").val("");
                                $("#ward_id").val("");
                                $("#area_in_sqft").val("");
                                $("#hidden_area_in_sqft").val('');
                                $("#address").val("");
                                $("#pin").val("");

                                var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

                                $("#owner_dtl").html(tbody);
                                
                                break;
                            }
                            else if(prop_dtl_id!=0)
                            {
                                alert('Your Holding has been generated kindly provide your Holding No.');
                                break;
                            }
                            else
                            {
                                if(owner_type=='OWNER')
                                {
                                    // $("#owner_name").val( data.dd[k]['owner_name']);
                                    tbody+='<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly  placeholder="Owner Name" /></td>';
                                    tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly  placeholder="Guardian Name" /></td>';
                                    tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'"  maxlength="10" minlength="10"  placeholder="Mobile No." /></td>';
                                    tbody+='<td><input type="email" name="email_id[]" id="email_id" class="form-control" " value="'+data.dd[k]['email']+'"  placeholder="Email ID" /></td>';
                                    tbody+="</tr>";
                                    i++;
                                }
                                else
                                {
                                    var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';
                                    $("#owner_dtl").html("");
                                    //  $("#owner_dtl").html(appendData);
                                }
                            }
                        

                        
                        $("#owner_dtl").html(tbody);
                        $("#owner_dtl2").html(tbody);

                        $("#prop_id").val(prop_dtl_id);
                        $("#saf_id").val(saf_id);
                        $("#count").val(i);
                        $("#ward_id").val(ward_mstr_id);
                        $("#ward_id").prop("readonly",true);
                        $("#area_in_sqft").val(area_sqft);
                        $("#hidden_area_in_sqft").val(area_sqft);
                        $("#area_in_sqft").prop("readonly",true);
                    
                        $("#elec_k_no").val(elect_consumer_no);
                        $("#elect_acc_no").val(elect_acc_no);
                        $("#elect_bind_book_no").val(elect_bind_book_no);
                        $("#elect_cons_category").val(elect_cons_category);
                        $("#address").val(prop_address);
                        $("#pin").val(prop_pin_code);
                        $("#address").prop("readonly",true);
                        $("#pin").prop("readonly",true);
                        $("#owner_add").hide();

                        area_removeReadOnly();
                            
                        //  for
                    
                        
                    }
                    
                    
                    // alert(data.data); 
                } else {

                    alert('SAF No. not Found');
                    $("#saf_no").val('');
                    $("#btn_review").attr('disabled', false);

                        $("#holding_no").val("");
                        $("#prop_id").val("");
                        $("#ward_id").val("");
                        $("#elec_k_no").val("");
                        $("#elect_acc_no").val("");
                        $("#elect_bind_book_no").val("");
                        $("#elect_cons_category").val("");
                        $("#address").val("");
                        $("#pin").val("");
                        $("#area_in_sqft").val("");
                        $("#hidden_area_in_sqft").val('');
                        $("#landmark").val("");
                        
                        $("#address").prop("readonly",false);
                        $("#pin").prop("readonly",false);
                        $("#ward_id").prop("readonly",false);
                        $("#area_in_sqft").prop("readonly",false);
                        $("#landmark").prop("readonly",false);
                        
                            
                        var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

                            $("#owner_dtl").html("");

                            $("#owner_dtl").html(tbody);

                }
                //
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loadingDiv").hide();
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });

        }
        else
        {
        

              var appendData = '<tr><td><input type="text" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

            $("#owner_dtl").html("");
            $("#owner_dtl").html(appendData);


        }
        

    }

 
    function validate_holding()
    {
		$("#owner_append").html("");
		var holding_no=$("#holding_no").val();
		// var ward_id=$("#ward_id").val();
		var owner_type=$("#owner_type").val();
		if(owner_type == "" || owner_type == "")
		{
			alert('Please Select Owner Type');
			return false;
		}
        

		// if(holding_no.length != 15 && holding_no.length != 0)
        if(!~jQuery.inArray( holding_no.length, [15,16,10,11,12,13,14] ) && holding_no.length!=0)
		{
			alert('Please Enter 15 digit unique holding no');
			$("#holding_no").focus();
			return false;
		}


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
				beforeSend: function() {
					$("#loadingDiv").show();
				},
                success:function(data){
					$("#loadingDiv").hide();
                  console.log(data);
                   //alert(data);
                   if (data.response==true)
                   {

                   	$("#btn_review").attr('disabled', false);
                   // var obj = JSON.parse(data.dd);
                   // alert(data.dd.0.owner_name);
                    var tbody="";
                    var i=1;
                    //debugger;
                   //console.log(data.dd);
                  for(var k in data.dd) {
                      // console.log(k, data.dd[k]['owner_name']);
                        tbody+="<tr>";
                        var prop_id=data.dd[k]['id'];
                        var ward_mstr_id=data.dd[k]['ward_mstr_id'];
                        var ward_no=data.dd[k]['ward_no'];
                        var area_sqft=data.dd[k]['total_area_sqft'];
                        var elect_consumer_no=data.dd[k]['elect_consumer_no'];
                        var elect_acc_no=data.dd[k]['elect_acc_no'];
                        var elect_bind_book_no=data.dd[k]['elect_bind_book_no'];
                        var elect_cons_category=data.dd[k]['elect_cons_category'];
                        var prop_pin_code=data.dd[k]['prop_pin_code'];
                        var prop_address=data.dd[k]['prop_address'];
                        
                        
                        //alert(owner_type);
                        // in case multiple connection appliy from same holding then holding data will be trieved in case owner himself applied
                        if(owner_type=='OWNER')
                        {
                        	
                        //alert('owner_name');
                        	
                    	//   $("#owner_name").val( data.dd[k]['owner_name']);
                       tbody+='<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'" readonly  placeholder="Owner Name" ></td>';

                       tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'" readonly  placeholder="Guardian Name"></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'"  maxlength=10 minlength=10  placeholder="Mobile No."></td>';

                        tbody+='<td><input type="email" name="email_id[]" id="email_id" class="form-control" " value="'+data.dd[k]['email']+'"  placeholder="Email ID" ></td>';



                        



                       tbody+="</tr>";
                       i++;

                       //alert(tbody);

                       
                     }
                     else
                     {
                            var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" minlength="10" maxlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

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
                     $("#hidden_area_in_sqft").val(area_sqft);                     
                   
                     $("#elec_k_no").val(elect_consumer_no);
                     $("#elect_acc_no").val(elect_acc_no);
                     $("#elect_bind_book_no").val(elect_bind_book_no);
                     $("#elect_cons_category").val(elect_cons_category);
                     $("#address").val(prop_address);
                     $("#pin").val(prop_pin_code);
                     $("#address").prop("readonly",true);
                     $("#pin").prop("readonly",true);
                     $("#owner_add").hide();

                     area_removeReadOnly();
                     
            

           

                 
                    
                     // alert(data.data); 
                   }
                   else 
                   {

                      //data.dd[k]['ward_mstr_id']
                      alert(data.dd.message);
                      //alert('Holding No. not Found');
                      $("#btn_review").attr('disabled', false);

                      $("#holding_no").val("");
                      $("#prop_id").val("");
                      $("#ward_id").val("");
                      $("#elec_k_no").val("");
                      $("#elect_acc_no").val("");
                      $("#elect_bind_book_no").val("");
                      $("#elect_cons_category").val("");
                      $("#address").val("");
                      $("#pin").val("");
                      $("#area_in_sqft").val("");
                      $("#hidden_area_in_sqft").val(''); 
                      $("#address").prop("readonly",false);
                      $("#pin").prop("readonly",false);
                      $("#ward_id").prop("readonly",false);
                      $("#area_in_sqft").prop("readonly",false);
                      
                    	
                      var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

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

    function area_removeReadOnly()
    {
        var prop_id_type = $('#property_type_id').val(); 
        var saf_no=$("#saf_no").val();
        var holding_no=$("#holding_no").val();
        var area = $("#area_in_sqft").val();
        <?php
        if($user_type!='')
        {
            ?>
            var hidden_area_in_sqft= $("#hidden_area_in_sqft").val();
            if(prop_id_type=='7' || prop_id_type=='2')
            $("#area_in_sqft").prop("readonly",false);
            else if((prop_id_type!='7' || prop_id_type!='2') &&(holding_no!='' || saf_no !='') &&  (area!='0' ||  area!=''))
            {
                $("#area_in_sqft").prop("readonly",true); 
                $("#area_in_sqft").val(hidden_area_in_sqft);
            }
            <?php
            }
        ?>

        
    }


    function add_owner()
    {
        var count=$("#count").val();
        var count=parseInt(count)+1;
        
        $("#count").val(count);

        var tbody = document.getElementById('owner_dtl');
        var tr = document.createElement('tr');
        tr.id='del'+count;
        var td = document.createElement('td');
        var input = document.createElement('input');
        input.classList='form-control';

        var td2 = document.createElement('td');
        var input2 = document.createElement('input');
        input2.classList='form-control';

        var td3 = document.createElement('td');
        var input3 = document.createElement('input');
        input3.classList='form-control';

        var td4 = document.createElement('td');
        var input4 = document.createElement('input');
        input4.classList='form-control';

        var td5 = document.createElement('td');
        var i = document.createElement('i');
        var i2 = document.createElement('i');
        i.classList=' fa fa-plus-square';
        i.style='margin-right:1rem; cursor:pointer';
        i2.classList=' fa fa-window-close';
        i2.style='cursor:pointer';
        i.setAttribute('onclick','add_owner()');
        i2.setAttribute('onclick','delete_owner('+count+')');

        // var input2 = input3 = input4 = input;
        // var td2 = td3=td4=td5=td;

        input.name='owner_name[]';
        input.required=true;
        input.id='owner_name'+count;
        input.type='text';        
        input.placeholder='Owner Name';
        input.setAttribute('onkeypress','return isAlpha(event)');
        //input.setAttribute('placeholder','return isAlpha(event)');
        td.append(input);
        tr.append(td);
        
        input2.name='guardian_name[]';
        input2.id='guardian_name'+count;
        input2.type='text';        
        input2.placeholder='Guardian Name';
        input2.setAttribute('onkeypress','return isAlpha(event)');
        td2.append(input2);
        tr.append(td2);

        input3.name='mobile_no[]';
        input3.id='mobile_no'+count;
        input3.type='text';
        input3.required=true;        
        input3.placeholder='Mobile No.';
        input3.setAttribute('onkeypress','return isNum(event)');
        input3.setAttribute('maxlength','10');
        input3.setAttribute('minlength','10');
        td3.append(input3);
        tr.append(td3);

        input4.name='email_id[]';
        input4.id='email_id'+count;
        input4.type='email';      
        input4.placeholder='Email ID';
        td4.append(input4);
        tr.append(td4);

        
        td5.append(i);
        td5.append(i2);
        tr.append(td5);

       tbody.appendChild(tr);
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