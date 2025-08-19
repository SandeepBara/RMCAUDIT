<?php
@session_start();
if($user_type=="")
{   
    echo  $this->include('layout_home/header');
    
}
  # 4	Team Leader	
  # 5	Tax Collector
  # 7	ULB Tax Collector
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
                    <li class="active"><a href="#">Update Consumer</a></li>
                </ol>
                <!--End breadcrumb-->
            </div>
            <?php 
        }
    ?>
    <!--Page content-->

    <div id="page-content">
		<?php
		if(isset($_SESSION['msg'])){?>
			<p class="bg bg-danger form-control text-center"><?php echo $_SESSION['msg']; unset($_SESSION['msg']);?></p>
		<?php } ?>
		<form id="form" name="form" method="post" onsubmit="validate_from();" >
			<?php if(isset($validation)){ ?>
				<?= $validation->listErrors(); ?>
			<?php } ?>
			<div class="panel panel-bordered panel-dark" id = "consumer_block">
                <div class="panel-heading">
                    <div class="panel-control">
                        <input type="checkbox" name="consumer_check_box" id="consumer_check_box" onclick="read_only_remove('consumer_check_box','consumer_block')" <?=isset($consumer_check_box) && $consumer_check_box ? 'checked':'';?> />
                    </div>
                    <h3 class="panel-title">Update Water Consumer Form
                        <?php
                            if($user_type=="")
                            {
                                ?>
                                <a href="<?=base_url()?>/WaterApplyNewConnectionCitizen/search/<?=md5(1)?>" class="pull-right ">
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
							<select name="property_type_id" id="property_type_id" class="form-control" onchange="show_flat_count(this.value);show_category(this.value);">
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

                        <div class="row">
                            <div id="flat_count_box" style="display: none;">
                                <label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text" name="flat_count" id="flat_count" class="form-control" value="<?php echo isset($flat_count)?$flat_count:""; ?>"  onkeypress="return isNum(event);"   placeholder="Enter No. of Flats">
                                </div>
                            </div>						
                	    </div>

					</div>
					
					<div class="row">


						<div id="category_block" style="display: none;">
							<label class="col-md-2">Category Type <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<select name="category" id="category" class="form-control">
									<option value="">SELECT</option>
									<option value="APL" <?php if(isset($category) && $category=='APL'){ echo "selected"; }?>>APL </option>
									<option value="BPL" <?php if(isset($category) && $category=='BPL'){ echo "selected"; }?>>BPL</option>
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

                    <div class="row">
                       
						<div id="holding_div" style="display: none;">
							<label class="col-md-2">Holding No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="holding_no" id="holding_no" class="form-control" onChange="validate_holding();" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);" placeholder="Enter Holding No." maxlength="15" />
								<input type="hidden" name="prop_id" id="prop_id" value="<?=isset($prop_id)?$prop_id:'';?>">
							</div>
						</div>
						<div id="saf_div" style="display: none;">
							<label class="col-md-2">SAF No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="saf_no" id="saf_no" class="form-control" value="<?php echo isset($saf_no)?$saf_no:""; ?>" onblur="validate_saf()"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Enter SAF No.">
								<input type="hidden" name="saf_id" id="saf_id">
							</div>
						</div>
                        <div id="ward_hide">
							<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<select name="ward_id" id="ward_id" class="form-control">
									<option value=" " >SELECT</option>
									<?php
									if($ward_list)
									{ 
										foreach($ward_list as $val)
										{
                                            ?>
                                            <option value="<?php echo $val['ward_mstr_id'];?>" <?php echo isset($ward_id) && $ward_id ==$val['ward_mstr_id']?"selected":""; ?> ><?php echo $val['ward_no']; ?></option>
                                            <?php			
										}
									}
									?>
								</select>
							</div>
						</div>
                	</div>
					
					<div class="row">						

						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                        	<input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control"  value="<?php echo isset($area_in_sqft)?$area_in_sqft:''; ?>"  onkeypress=" getsqft(this.value);return isNumDot(event);" placeholder="Enter Total Area in Sqft">
						</div>
                        <label class="col-md-2">Total Area(in Sq. Mtr) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                        	<input type="text" name="area_in_sqmt" id="area_in_sqmt" class="form-control"  value="<?php echo isset($area_in_sqmt)?$area_in_sqmt:''; ?>"  onkeypress="getsqmtr(this.value);return isNumDot(event);"  placeholder="Enter Total Area in Sqft">
						</div>
						
                	</div>
					
					
					<div class="row">
                        <label class="col-md-2">Address<span class="text-danger">*</span></label>
						<div class="col-md-10 pad-btm">
							<textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNum(event);"  ><?php echo isset($address)?$address:"";?>
							</textarea>
						</div>
                    </div>
				</div>
			</div>
			
			<div class="panel panel-bordered panel-dark" id = "owner_block">
                <div class="panel-heading">
                    <div class="panel-control">
                        <input type="checkbox" name="owner_check_box" id="owner_check_box" onclick="read_only_remove('owner_check_box','owner_block')" <?=isset($owner_check_box) && $owner_check_box ? 'checked':'';?>/>
                    </div>
                    <h3 class="panel-title">Applicant Details</h3>
                </div>
               
					<div class="panel-body table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Owner Name<span class="text-danger">*</span></th>
									<th>Guardian Name</th>
									<th>Mobile No.<span class="text-danger">*</span></th>
									<th>City</th>
                                    <th>District</th>
                                    <th>State</th>
									<th colspan="2" id="owner_add">Add</th>
								</tr>
                            </thead>
                                 <tbody id="owner_dtl">
                                <?php
                            	foreach($owner_name as $key=>$val)
                            	{ 
                                    ?>                                   
                                        <tr>
                                            <td><input type="text" name="owner_name[]" id="owner_name<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);"  placeholder="Owner Name" value="<?php echo $val['applicant_name'];?>" /></td>
                                            <td><input type="text" name="guardian_name[]" id="guardian_name<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);"  placeholder="Guardian Name" value="<?php echo $val['father_name'];?>" /></td>
                                            <td><input type="text" name="mobile_no[]" id="mobile_no<?=$key;?>" class="form-control" onkeypress="return isNum(event);"  placeholder="Mobile No." value="<?php echo $val['mobile_no'];?>" /></td>
                                            <td><input type="text" name="city[]" id="city<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="City Name" value="<?php echo $val['city'];?>" /></td>
                                            <td><input type="text" name="district[]" id="district<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="District Name" value="<?php echo $val['district'];?>" /></td>
                                            <td><input type="text" name="state[]" id="state<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="State Name" value="<?php echo $val['state'];?>" /></td>                                           
                                            <input type="hidden" name="count" id="count" value="<?=$key?>">
                                            <input type="hidden" name="woner_id<?=$key?>" id="count" value="<?=$val['id'];?>">
                                            <td onclick="add_owner()" colspan="2"  ><i class="form-control fa fa-plus-square add_woner_icon" style="cursor: pointer;"></i></td>
                                        </tr>                     
                                    <?php	
                            	}
                                ?>
                                </tbody>
                                
                        </table>
                    </div>
					<div id="owner_append"></div>
				
			</div>

            <?php
            if(in_array($user_type,[1,2]))
            {
                ?>
                <div class="panel panel-bordered panel-dark" id ="elect_block">
                    <div class="panel-heading">
                        <div class ="panel-control">
                            <input type="checkbox" name="elect_check_box" id="elect_check_box" onclick="read_only_remove('elect_check_box','elect_block');" <?=isset($elect_check_box) && $elect_check_box ? 'checked':'';?>/>
                        </div>
                        <h3 class="panel-title">Applicant Electricity Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-2">K. No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" name="elec_k_no" id="elec_k_no" class="form-control"  value="<?php echo $k_no??null; ?>">
                            </div>
                            <label class="col-md-2">Bind Book No.</label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" name="elec_bind_book_no" id="elec_bind_book_no" class="form-control"  value="<?php echo isset($bind_book_no)?$bind_book_no:""; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">Account No.</label>
                            <div class="col-md-3 pad-btm">
                            <input type="text" name="elec_account_no" id="elec_account_no" class="form-control"  value="<?php echo isset($account_no)?$account_no:""; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2">Category Type</label>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" id="elec_category" value="Residential - DS I/II" <?php if($electric_category_type??null=='RESIDENTIAL - DS I/II'){echo "checked"; }?>>  Residential - DS I/II 
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" id="elec_category" value="Commercial - NDS II/III" <?php if($electric_category_type??null=='COMMERCIAL - NDS II/III'){echo "checked"; }?>>  Commercial - NDS II/III 
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" id="elec_category" value="Agriculture - IS I/II" <?php if($electric_category_type??null=='AGRICULTURE - IS I/II'){echo "checked"; }?>>Agriculture - IS I/II
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" id="elec_category" value="Low Tension - LTS" <?php if($electric_category_type??null=='LOW TENSION - LTS'){echo "checked"; }?>>Low Tension - LTS 
                            </div>
                            <div class="col-md-2 pad-btm">
                                <input type="radio" name="elec_category" id="elec_category" value="High Tension - HTS" <?php if($electric_category_type??null=='HIGH TENSION - HTS'){echo "checked"; }?>>High Tension - HTS
                            </div>                        
                        </div>                    
                    </div>
                </div>
                <?php
            }
            else 
            {
                ?>
                <!-- <input type="hidden" name="elec_k_no" id="elec_k_no" >
                <input type="hidden" name="elect_acc_no" id="elect_acc_no" >
                <input type="hidden" name="elect_bind_book_no" id="elect_bind_book_no" >
                <input type="hidden" name="elect_cons_category" id="elect_cons_category" > -->
                <?php

            }
            ?>


			
			<div class="panel">
				<div class="panel-body text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary" onclick=" validate_from();" >SUBMIT</button>
                </div>
            </div>
			
		</form>
        
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<?php 
    if(in_array($user_type,[1,2]))
    {
        echo $this->include('layout_vertical/footer');
    }
	elseif($user_type==4 || $user_type==5 || $user_type==7)
	{

		echo $this->include('layout_mobi/footer');
	}
	
  
 ?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">  
        $("#form").validate({
            rules: {
                conn_through_id: {
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                connection_type_id: {
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                connection_type_id:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                conn_through_id:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                category:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                pipeline_type_id:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                property_type_id:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                conn_through_id:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                area_in_sqft:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                area_in_sqmt:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                address:{
                    required: function(element) {
                        return $('#consumer_check_box').is(':checked')
                    }
                },
                elec_k_no:{
                    required: function(element) {
                        if($('#elect_check_box').is(':checked') && $('#elec_bind_book_no').val()=='' && $('#elec_account_no').val()=='' && $('#elec_category').val()=='')
                        {
                            return true;
                        }
                    }
                },
                elec_k_no:{
                    required: function(element) {
                        if($('#elect_check_box').is(':checked') && $('#elec_bind_book_no').val()=='' && $('#elec_account_no').val()=='' && $('#elec_category').val()=='')
                        {
                            return true;
                        }
                    }
                },
                elec_category :{
                    required: function(element) {
                        if($('#elect_check_box').is(':checked') && $('#elec_bind_book_no').val()=='' && $('#elec_account_no').val()=='' && $('#elec_k_no').val()=='')
                        {
                            return true;
                        }
                    }
                },
                elec_account_no :{
                    required: function(element) {
                        if($('#elect_check_box').is(':checked') && $('#elec_bind_book_no').val()=='' && $('#elec_k_no').val()=='' && $('#elec_category').val()=='')
                        {
                            return true;
                        }
                    }
                },
                'owner_name[]':{
                    required: function(element) {
                        return $('#owner_check_box').is(':checked')
                    }

                },
                'mobile_no[]':{
                    required: function(element) {
                        return $('#owner_check_box').is(':checked')
                    },
                    minlength: function(element) {
                        if($('#owner_check_box').is(':checked')) {
                            return 10;
                        }
                    },
                    maxlength: function(element) {
                        if($('#owner_check_box').is(':checked')) {
                            return 10;
                        }
                    },
                },
                
                    
            }
        });

           

</script>
<script>

    $(document).ready(function () {

       
        load();
        read_only_remove('consumer_check_box', 'consumer_block');
        // read_only_remove('property_check_box','property_block');
        read_only_remove('owner_check_box','owner_block');
        read_only_remove('elect_check_box','elect_block');
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
        //alert();
        if(area_in_sqft!="")
        {
            $("#area_in_sqft").val(area_in_sqmt);
        }
        else
        {
            $("#area_in_sqft").val("");
        }
    }

    function getsqft(str)
    {
        var area_in_sqmt=str;
        var area_in_sqft=0.092903*area_in_sqmt;
        $("#area_in_sqmt").val(area_in_sqft);
        
    }

    function show_hide_saf_holding_box(str)
    {

    
      $("#owner_append").html("");
       var holding_exists=str;
        // $("input[name='owner_name[]']").attr("readonly",false);
        // $("input[name='guardian_name[]']").attr("readonly",false);
        // $("input[name='mobile_no[]']").attr("readonly",false);
        
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
                    var tbody="";
                    var i=1;

                    for(var k in data.dd)
                    {
                        console.log(k, data.dd[k]['owner_name']);
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
		                    $("#address").val("");
		                    $("#pin").val("");
		                    var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

                            // $("#owner_dtl").html(tbody);
                            
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
                                // $("#owner_dtl").html("");
                                //  $("#owner_dtl").html(appendData);
                            }
                        }
                    

                     
                    //  $("#owner_dtl").html(tbody);
                    //  $("#owner_dtl2").html(tbody);

                     $("#prop_id").val(prop_dtl_id);
                     $("#saf_id").val(saf_id);
                     $("#count").val(i);
                     $("#ward_id").val(ward_mstr_id);
                     $("#ward_id").prop("readonly",true);
                     $("#area_in_sqft").val(area_sqft);
                     $("#area_in_sqft").prop("readonly",true);
                     
                     $("#address").val(prop_address);
                     $("#pin").val(prop_pin_code);
                     $("#address").prop("readonly",true);
                     $("#pin").prop("readonly",true);
                     $("#owner_add").hide();
                     
              		 	
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
                    
                      $("#address").val("");
                      $("#pin").val("");
                      $("#area_in_sqft").val("");
                      $("#landmark").val("");
                      
                      $("#address").prop("readonly",false);
                      $("#pin").prop("readonly",false);
                      $("#ward_id").prop("readonly",false);
                      $("#area_in_sqft").prop("readonly",false);
                      $("#landmark").prop("readonly",false);
                      
                    	
                   
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
        

            //   var appendData = '<tr><td><input type="text" name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

            // $("#owner_dtl").html("");
            // $("#owner_dtl").html(appendData);


        }

    }

 
    function validate_holding()
    {
		$("#owner_append").html("");
		var holding_no=$("#holding_no").val();
		var ward_id=$("#ward_id").val();
        // alert(ward_id);
		var owner_type=$("#owner_type").val();
		if(owner_type == "" || owner_type == "")
		{
			alert('Please Select Owner Type');
			return false;
		}
        

		if(holding_no.length != 15 && holding_no.length != 0)
		{
			alert('Please Enter 15 digit unique holding no');
			$("#holding_no").focus();
			return false;
		}


        if(holding_no)
        {
            $.ajax({
                type:"post",
                url: '<?php echo base_url("WaterApplyNewConnection/checkHoldingExists");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no,
                        "ward_id":ward_id
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
                    console.log(data.dd);
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
                            
                        
                        tbody+='<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlphaNumCommaSlash(event);" value="'+data.dd[k]['owner_name']+'" readonly  placeholder="Owner Name" ></td>';

                        tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlphaNumCommaSlash(event);" value="'+data.dd[k]['guardian_name']+'" readonly  placeholder="Guardian Name"></td>';

                        tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'"  maxlength=10 minlength=10  placeholder="Mobile No."></td>';

                        tbody+='<td><input type="text" name="city[]" id="city" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" value="'+data.dd[k]['city']+'"  placeholder="City Name" ></td>';
                        tbody+='<td><input type="text" name="district[]" id="district" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" value="'+data.dd[k]['district']+'"  placeholder="Distict Name" ></td>';
                        tbody+='<td><input type="text" name="state[]" id="state" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" value="'+data.dd[k]['state']+'"  placeholder="State Name" ></td>';


                        tbody+="</tr>";
                        i++;

                        //alert(tbody);

                        
                        }
                        

                    }

                        // $("#owner_dtl").html(tbody);
                        // $("#owner_dtl2").html(tbody);

                        $("#prop_id").val(prop_id);
                        //$("#count").val(i);
                        $("#ward_id").val(ward_mstr_id);
                        $("#ward_id").prop("readonly",true);
                        $("#area_in_sqft").val(area_sqft);
                        $("#area_in_sqft").prop("readonly",true);
                    
                        // $("#elec_k_no").val(elect_consumer_no);
                        // $("#elect_acc_no").val(elect_acc_no);
                        // $("#elect_bind_book_no").val(elect_bind_book_no);
                        // $("#elect_cons_category").val(elect_cons_category);
                        $("#address").val(prop_address);
                        $("#pin").val(prop_pin_code);
                        $("#address").prop("readonly",true);
                        // $("#pin").prop("readonly",true);
                        // $("#owner_add").hide();
                    
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
                        //$("#ward_id").val("");
                        //$("#elec_k_no").val("");
                        //$("#elect_acc_no").val("");
                        //$("#elect_bind_book_no").val("");
                        //$("#elect_cons_category").val("");
                        $("#address").val("");
                        $("#pin").val("");
                        $("#area_in_sqft").val("");
                        $("#address").prop("readonly",false);
                        $("#pin").prop("readonly",false);
                        //$("#ward_id").prop("readonly",false);
                        $("#area_in_sqft").prop("readonly",false);
                        

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
        getsqmtr($("#area_in_sqft").val());
        //alert($("#area_in_sqft").val());
    }



    function add_owner()
    {
        //alert(document.getElementById("owner_check_box").checked);
        if (!document.getElementById("owner_check_box").checked)
        return false;

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
        var input5 = document.createElement('input');
        input5.classList='form-control';

        var td6 = document.createElement('td');
        var input6 = document.createElement('input');
        input6.classList='form-control';

        var td7 = document.createElement('td');        
        var i = document.createElement('i');        
        var i2 = document.createElement('i');

        i.classList=' fa fa-plus-square add_woner_icon';
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
        input.setAttribute('onkeypress','return isAlphaNumCommaSlash(event)');
        //input.setAttribute('placeholder','return isAlpha(event)');
        td.append(input);
        tr.append(td);
        
        input2.name='guardian_name[]';
        input2.id='guardian_name'+count;
        input2.type='text';        
        input2.placeholder='Guardian Name';
        input2.setAttribute('onkeypress','return isAlphaNumCommaSlash(event)');
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

        input4.name='city[]';
        input4.id='city'+count;
        input4.type='text';      
        input4.placeholder='City Name';
        input4.setAttribute('onkeypress','return isAlphaNumCommaSlash(event)');
        td4.append(input4);
        tr.append(td4);

        input5.name='district[]';
        input5.id='distric'+count;
        input5.type='text';      
        input5.placeholder='District Name';
        input5.setAttribute('onkeypress','return isAlphaNumCommaSlash(event)');
        td5.append(input5);
        tr.append(td5);

        input6.name='state[]';
        input6.id='state'+count;
        input6.type='text';      
        input6.placeholder='State Name';
        input6.setAttribute('onkeypress','return isAlphaNumCommaSlash(event)');
        td6.append(input6);
        tr.append(td6);

        
        td7.append(i);
        td7.append(i2);
        tr.append(td7);

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
    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
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

    function read_only_remove(type,block_name)
    {
        var ch = document.getElementById(type).checked;
        //alert(block_name);
        if( ch==true)
        {
           var dd = $("#"+ block_name).find("select, textarea, input").attr('readonly',false);
           console.log(dd);
           $("#"+ block_name).find("select").attr('disabled',false);
           $("#"+ block_name).find("input:radio").attr('disabled',false);
           
        }
        else
        {
           
            $("#"+ block_name ).find("select, textarea, input").attr('readonly',true);
            $("#"+ block_name).find("select").attr('disabled',true);
            $("#"+ block_name).find("input:radio").attr('disabled',true);
        }
        
    }
</script>