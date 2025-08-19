<?php
if(session_status()==PHP_SESSION_NONE)
	session_start();

echo $this->include('layout_vertical/header');

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
            <li><a href="#">Add Existing Consumer</a></li>
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
                    <h3 class="panel-title">Add Existing Consumer</h3>
                </div>
				<div class="panel-body">
					<div <?php if(isset($_SESSION['message']) && $_SESSION['message']!=""){ echo 'class="bg-danger" style="padding:10px; text-align:center;"'; } ?>><?php echo  isset($_SESSION['message'])?$_SESSION['message']:''; unset($_SESSION['message']);?></div>

                    <div class="row">
                        <label class="col-md-2">Old Consumer No. <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="old_consumer_no" id="old_consumer_no" class="form-control">
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






						<!-- <label class="col-md-2">SAF No. <span class="text-danger" id="required_saf_no">*</span></label>
						<div class="col-md-3 pad-btm">
							<input type="text" name="saf_no" id="saf_no" class="form-control" value="" onblur="validate_saf()" onkeypress="return isAlphaNumCommaSlash(event);" placeholder="Enter SAF No." required>
							<input type="hidden" name="saf_id" id="saf_id">
						</div> -->
					</div>
					
					<div class="row">
                    	<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							<select name="ward_id" id="ward_id" class="form-control" onblur="getHoldingDetails();">
								<option value="">SELECT</option>
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


                        <div id="holding_div" style="display: none;">
							<label class="col-md-2">Holding No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="holding_no" id="holding_no" class="form-control" onChange="getHoldingDetails();" value="<?php echo isset($holding_no)?$holding_no:""; ?>"  onkeypress="return isAlphaNum(event);" placeholder="Enter Holding No." maxlength="16" />
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
                    	<label class="col-md-2">Property Type <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							  <select name="property_type_id" id="property_type_id" class="form-control" onchange="getUnitRate();show_flat_count(this.value);show_category(this.value);">
								<option value="">SELECT</option>
								<?php
								if($property_type_list)
								{
									foreach($property_type_list as $val)
									{
										
								?>
								<option value="<?php echo $val['id'];?>"><?php echo $val['property_type']; ?></option>
								<?php			
									}
								}
								?>
							</select>
						</div>			
						<label class="col-md-2">juidco Consumer <span class="text-danger">*</span></label>
						<div class="form-check form-switch">
							<input class="form-radio-input" type="radio" id="mySwitch" name="juidco_consumer" value="1" <?=isset($juidco_consumer) && $juidco_consumer=='1'?'checked':""; ?> required >
							<label class="form-check-label" for="mySwitch">Yes</label>
							<input class="form-radio-input" type="radio" id="mySwitch2" name="juidco_consumer" value="0" <?=isset($juidco_consumer) && $juidco_consumer=='0'?'checked':""; ?>>
							<label class="form-check-label" for="mySwitch">No</label>
						</div>
						
					</div>

					<div class="row">
                    	<!-- <label class="col-md-2">Property Address<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <textarea name="address" id="address" class="form-control"></textarea>
						</div> -->
																		
					</div>


					<div class="row">
                    	<label class="col-md-2">Total Area (Sq.Ft.)<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onkeypress="return isNum(event);" onkeyup="getAreaSqmt();">
						</div>

						<label class="col-md-2">Total Area (Sq.Mt.)<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <input type="text" name="area_in_sqmt" id="area_in_sqmt" class="form-control" onkeypress="return isNum(event);" onkeyup="getAreaSqft();">
						</div>	
					</div>
					<div class="row">
						<div id="category_div" style="display: none;">
	                        <label class="col-md-2">Applicant Category<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<select name="applicant_category" id="applicant_category" class="form-control" onchange="maditer_hoding()">
									<option value="">SELECT</option>
									<option value="APL">APL</option>
									<option value="BPL">BPL</option>
								</select>
							</div>
						</div>
						<div id="pipeline_div" style="display: none;">
							<label class="col-md-2">Pipeline Type <span class="text-danger"></span></label>
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
						<div id="flat_count_box" style="display: none;">
							<label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<input type="text" name="flat_count" id="flat_count" class="form-control" value="<?php echo isset($flat_count)?$flat_count:""; ?>"  onkeypress="return isNum(event);"   placeholder="Enter No. of Flats">
							</div>
					    </div>
					</div>

					<div class="row">
                        <label class="col-md-2">Address<span class="text-danger">*</span></label>
						<div class="col-md-10 pad-btm">
							<textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNum(event);"  ><?php echo isset($address)?$address:"";?></textarea>
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
								</tr>
                            </thead>
                            <tbody id="owner_details">
								<tr>
									<td><input type="text" name="owner_name[]" id="owner_name<?=$i??'';?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name" value="<?php echo $owner_name[$i??'']??'';?>" /></td>
									<td><input type="text" name="guardian_name[]" id="guardian_name<?=$i??'';?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name" value="<?php echo $guardian_name[$i??'']??'';?>" /></td>
									<td><input type="text" name="mobile_no[]" id="mobile_no<?=$i??'';?>" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No." value="<?php echo $mobile_no[$i??'']??'';?>" /></td>
									<td><input type="email" name="email_id[]" id="email_id<?=$i??'';?>" class="form-control"  placeholder="Email ID" value="<?php echo $email_id[$i??'']??'';?>" /></td>
									
									<input type="hidden" name="count" id="count" value="1">
									<td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
								</tr>
                            </tbody>
                          
                        </table>
                    </div>
					
			</div>

			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Consumer Connection Details</h3>
                </div>
               
					<div class="panel-body">

						<div class="row">
							<label class="col-md-2">Connection Type<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								 <select name="connection_type_id" id="connection_type_id" class="form-control" onchange="getUnitRate();">
								 	<option value="">SELECT</option>
								 	<option value="1">METER</option>
								 	<option value="2">GALLON</option>
								 	<option value="3">FIXED</option>
								 </select>
							</div>
							<label class="col-md-2">Date of Connection<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								 <input type="date" name="connection_date" id="connection_date" class="form-control">
							</div>
						</div>

						<div id="meter_div" style="display: none;">
							<div class="row">
								<label class="col-md-2">Meter No.<span class="text-danger">*</span></label>
								<div class="col-md-3 pad-btm">
									<input type="text" name="meter_no" id="meter_no" class="form-control">
								</div>
								<label class="col-md-2">Meter Type<span class="text-danger">*</span></label>
								<div class="col-md-3 pad-btm">
									 <select name="meter_type" id="meter_type" class="form-control" onchange="meterFixed(this.value)">
									 	<option value="">SELECT</option>
									 	<option value="0">Fixed</option>
									 	<option value="1">Not Fixed</option>
									 </select> 
								</div>
								
							</div>
							<div class="row">
								<label class="col-md-2" id="label_reading"></label>
								<div class="col-md-3 pad-btm">
									<input type="text" name="initial_reading" id="initial_reading" class="form-control" onkeypress="return isNumDot(event);">
								</div>
							</div>
						</div>
						
						
						
                    </div>
					
			</div>

			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Demand Details</h3>
                </div>
               	<div class="panel-body">
					<div class="row">
						<label class="col-md-2">Demand Upto<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <input type="date" name="demand_upto" id="demand_upto" class="form-control">
						</div>
						<label class="col-md-2">Unit Rate<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm" id="append_rate">
							
						</div>
						
                    </div>
					
					<div class="row">
						<label class="col-md-2">Arrear Amount<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <input type="text" name="arrear_amount" id="arrear_amount" class="form-control" onkeypress="return isNumDot(event);">
						</div>
						
                    </div>
                </div>
			</div>
			
		
			
			<div class="panel">
				<div class="panel-body text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                </div>
            </div>
			
		</form>
        
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<?php 
	echo $this->include('layout_vertical/footer');
 ?>


<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>


<script>

$(document).ready(function () {

	getUnitRate();
	
	jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please"); 



	$('#form').validate({ // initialize the plugin
        rules: {

        	"address":"required",
        	"ward_id":"required",
        	"conn_through_id":"required",
        	"property_type_id":"required",
        	"applicant_category":"required",        	
        	"area_in_sqft":"required",
        	"area_in_sqmt":"required",
        	"connection_type_id":"required",
        	"connection_date":"required",
        	"meter_no":"required",
        	"meter_type":"required",
        	"initial_reading":"required",
        	"demand_upto":"required",
        	"unit_rate":"required",
        	"arrear_amount":"required",
        	
            "owner_name[]": {
				"required":true,

            },
            
            "email_id[]": {
				"required":false,
				"email":true,
            },
            "mobile_no[]": {
            	"required":true,
            	"digits":true,
            	"minlength":10,
            	"maxlength":10,

            }
           
        }
    });


});

</script>
<script type="text/javascript">

	function add_owner()
    {
        var count=$("#count").val();
        var count=parseInt(count)+1;
        
        $("#count").val(count);

        var tbody = document.getElementById('owner_details');
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
	function getHoldingDetails()
	{
		var ward_id=$("#ward_id").val();
		var holding_no=$("#holding_no").val();
		var connectiont_through = $('#conn_through_id').val();
		if(ward_id == "" && connectiont_through == 1)
		{
			alert('Please Select Ward No.');
			return false;
		}
		console.log(holding_no.length);
		console.log(!~jQuery.inArray(holding_no.length,[15,16]))	;

		if(!~jQuery.inArray(holding_no.length,[15,16]) && connectiont_through == 1)
		{
			alert('Please Enter 15 digit unique holding no');
			$("#holding_no").focus();
			return false;
		}
		if(ward_id && holding_no)
		{
			//alert('dddddd');

			$.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterAddExistingConsumer/getHoldingDetails");?>',
				//url: '<?php echo base_url("WaterApplyNewConnectionCitizen/validate_holding_no"); ?>',
                dataType:"json",
                data: {
                        "holding_no":holding_no,"ward_id":ward_id
                },
				beforeSend: function() {
					$("#loadingDiv").show();
					$("#btn_review").prop('disabled',true);
					
				},
                success:function(data){
                	//console.log(data);
                	$("#loadingDiv").hide();
                	
					if (data.response == true) 
					{

						$("#btn_review").attr('disabled', false);
						// var obj = JSON.parse(data.dd);
						// alert(data.dd.0.owner_name);
						var tbody = "";
						var i = 1;
						//debugger;
						console.log(data.dd);
						for (var k in data.dd) 
						{  
							$("#saf_no").prop("required", false);	
							console.log(k, data.dd[k]['owner_name']);
							tbody += "<tr>";
							var prop_id = data.dd[k]['id'];
							var ward_mstr_id = data.dd[k]['ward_mstr_id'];
							var ward_no = data.dd[k]['ward_no'];
							var area_sqft = data.dd[k]['total_area_sqft'];
							var elect_consumer_no = data.dd[k]['elect_consumer_no'];
							var elect_acc_no = data.dd[k]['elect_acc_no'];
							var elect_bind_book_no = data.dd[k]['elect_bind_book_no'];
							var elect_cons_category = data.dd[k]['elect_cons_category'];
							var prop_pin_code = data.dd[k]['prop_pin_code'];
							var prop_address = data.dd[k]['prop_address'];
							// in case multiple connection appliy from same holding then holding data will be trieved in case owner himself applied
							//if (owner_type == 'OWNER')
							{

								//alert('owner_name');

								//   $("#owner_name").val( data.dd[k]['owner_name']);
								tbody += '<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="' + data.dd[k]['owner_name'] + '"   placeholder="Owner Name" ></td>';

								tbody += '<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="' + data.dd[k]['guardian_name'] + '"   placeholder="Guardian Name"></td>';

								tbody += '<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="' + data.dd[k]['mobile_no'] + '"  maxlength=10 minlength=10  placeholder="Mobile No."></td>';

								tbody += '<td><input type="email" name="email_id[]" id="email_id" class="form-control" " value="' + data.dd[k]['email'] + '"  placeholder="Email ID" ></td>';







								tbody += "</tr>";
								i++;

								//alert(tbody);


							} 
							

						}

						$("#owner_details").html(tbody);
						$("#owner_dtl2").html(tbody);

						$("#prop_id").val(prop_id);
						$("#holding_no").prop("readonly", true);
						$("#saf_no").prop("readonly", false);
						$("#saf_no").val('');
						$("#count").val(i);
						$("#ward_id").val(ward_mstr_id);
						$("#ward_id").prop("readonly", true);
						$("#area_in_sqft").val(area_sqft);
						$("#area_in_sqft").prop("readonly", true);

						$("#elec_k_no").val(elect_consumer_no);
						$("#elect_acc_no").val(elect_acc_no);
						$("#elect_bind_book_no").val(elect_bind_book_no);
						$("#elect_cons_category").val(elect_cons_category);
						$("#address").val(prop_address);
						$("#pin").val(prop_pin_code);
						$("#address").prop("readonly", false);
						$("#pin").prop("readonly", true);
						$("#owner_add").hide();
						// alert(data.data); 
						getAreaSqmt();
					} 
					else 
					{

						//data.dd[k]['ward_mstr_id']
						alert(data.dd.message);
						//alert('Holding No. not Found');
						$("#btn_review").attr('disabled', false);
						$("#saf_no").prop("readonly", false);
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
						$("#address").prop("readonly", false);
						$("#pin").prop("readonly", false);
						$("#ward_id").prop("readonly", false);
						$("#area_in_sqft").prop("readonly", false);


						var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

						$("#owner_dtl").html("");

						$("#owner_details").html(tbody);

					}

                }

              });
		
		}
		
	}
	
	function getUnitRate()
	{	
		var property_type_id=$("#property_type_id").val();
		var connection_type_id=$("#connection_type_id").val();

		show_hide_category_div(property_type_id);
		show_hide_meter_div(connection_type_id);

		if(property_type_id=="")
		{
			$("#append_rate").text('Please select Property Type');
		}
		else if(connection_type_id=="")
		{
			$("#append_rate").text('Please select Connection Type');
		}
		else
		{	
			

			$.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterAddExistingConsumer/getUnitRateDetails");?>',
                dataType:"json",
                data: {
                        "property_type_id":property_type_id,"connection_type_id":connection_type_id
                },
				beforeSend: function() {
					$("#loadingDiv").show();
					$("#btn_review").prop('disabled',true);
					
				},
                success:function(data){
                	//alert(data.result);
                	//console.log(data);

                	$("#loadingDiv").hide();
					

                	if(data.response==true)
                	{	
                		 //console.log(data.result);
                		 $("#btn_review").prop('disabled',false);
                		 var select="<select name='unit_rate' id='unit_rate' class='form-control'><option value=''>SELECT</option>";
                		 for(var k in data.result)
                		 {
                		 	 //console.log(k);
                		 	 select+="<option value='"+data.result[k]['amount']+"'>"+data.result[k]['amount']+"</option>";

                		 }
                		 select+="</select>";
                		 //alert(select);
                		 $("#append_rate").html(select);
                	}
                	else
                	{
                		$("#append_rate").text('Rate Not Found');
                	}
                }

              });
		}
	}

	function getAreaSqmt()
	{
		 var area_in_sqft=$("#area_in_sqft").val();
		 //alert(area_in_sqft);
		 if (area_in_sqft == "")
	     area_in_sqft = 0;
	     var area_in_sqmt = parseFloat(area_in_sqft) * 0.0929;
	     //alert(area_in_sqmt);

	     area_in_sqmt=area_in_sqmt.toFixed(2);
	     $("#area_in_sqmt").val(area_in_sqmt);
		 

	}

	function getAreaSqft()
	{
		 var area_in_sqmt=$("#area_in_sqmt").val();
		 if (area_in_sqmt == "")
	     area_in_sqmt = 0;
	     var area_in_sqft = parseFloat(area_in_sqmt) * 10.764;
	     area_in_sqft=area_in_sqft.toFixed(2);
	     $("#area_in_sqft").val(area_in_sqft);
		 

	}

	function show_hide_category_div(property_type_id)
	{
		if(property_type_id==1)
		{
			$("#category_div").show();
		}
		else
		{
			$("#category_div").hide();
		}
	}
	function show_hide_meter_div(connection_type_id)
	{	
		if(connection_type_id==1)
		{
			$("#meter_div").show();
			$("#label_reading").html("Last Meter Reading (In K.L./Units) <span class='text-danger'>*</span>");
		}
		else if(connection_type_id==2)
		{
			$("#meter_div").show();
			$("#label_reading").html("Last Meter Reading (In Gallon) <span class='text-danger'>*</span>");
		}
		else
		{
			$("#meter_div").hide();
		}
	}
	function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }
	function maditer_hoding()
	{
		var applicant_category = $('#applicant_category').val();
		var property_type_id = $('#property_type_id').val();
		if(property_type_id == 1 && applicant_category=='APL')
		{
			document.getElementById('holding_no').required=true;
			document.getElementById('required_hoding').innerHTML='*';
			document.getElementById('saf_no').required=false;
			document.getElementById('required_saf_no').innerHTML='';
			//alert('apl');
			
		}
		else
		{
			document.getElementById('holding_no').required=false;
			document.getElementById('required_hoding').innerHTML='';
			document.getElementById('saf_no').required=true;
			document.getElementById('required_saf_no').innerHTML='*';
			mediter_saf();			
		}

	}
	function mediter_saf()
	{
		var applicant_category = $('#applicant_category').val();
		var property_type_id = $('#property_type_id').val();
		var holding_no=$('#holding_no').val();
		if(property_type_id == 1 && applicant_category=='BPL' && holding_no!='')
		{
			document.getElementById('holding_no').required=true;
			document.getElementById('required_hoding').innerHTML='*';
			document.getElementById('saf_no').required=false;			
			document.getElementById('required_saf_no').innerHTML='';
			
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

</script>



<script>
	
	function validate_saf() 
	{
		$("#owner_append").html("");
		var saf_no = $("#saf_no").val();
		var owner_type = $("#owner_type").val();
		if (saf_no) 
		{
			$.ajax({
				type: "POST",
				// url: '<?=base_url()?>/WaterApplyNewConnectionCitizen/validate_saf_no',
				url: '<?=base_url()?>/WaterAddExistingConsumer/validate_saf_no',				
				dataType: "json",
				data: {
					"saf_no": saf_no
				},
				beforeSend: function() {
					$("#loadingDiv").show();
				},
				success: function(data) 
				{
					$("#loadingDiv").hide();
					console.log(data);


					if (data.response == true)
					{
						$("#btn_review").attr('disabled', false);						
						var tbody = "";
						var i = 1;
						
						for (var k in data.dd) 
						{
							console.log(k, data.dd[k]['owner_name']);
							tbody += "<tr>";
							var saf_id = data.dd[k]['id'];
							var prop_dtl_id = data.dd[k]['prop_dtl_id'];
							var ward_mstr_id = data.dd[k]['ward_mstr_id'];
							var ward_no = data.dd[k]['ward_no'];
							var payment_status = data.dd[k]['payment_status'];
							var area_sqft = data.dd[k]['total_area_sqft'];
							var elect_consumer_no = data.dd[k]['elect_consumer_no'];
							var elect_acc_no = data.dd[k]['elect_acc_no'];
							var elect_bind_book_no = data.dd[k]['elect_bind_book_no'];
							var elect_cons_category = data.dd[k]['elect_cons_category'];
							var prop_pin_code = data.dd[k]['prop_pin_code'];
							var prop_address = data.dd[k]['prop_address'];
							//alert(prop_dtl_id);
							if (payment_status == 0) 
							{
								alert('Please make your payment in SAF first');
								$("#saf_no").val("");
								$("#ward_id").val("");
								$("#area_in_sqft").val("");
								$("#address").val("");
								$("#pin").val("");

								var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()">Add</td></tr>';

								$("#owner_dtl").html(tbody);

								break;
							} 
							else if (prop_dtl_id != 0) 
							{
								alert('Your Holding has been generated kindly provide your Holding No.');
								break;
							} 
							else 
							{ 
								$("#saf_no").prop("readonly", true);
								$("#holding_no").prop("readonly", false);
								$("#holding_no").val('');
								
								//if (owner_type == 'OWNER') 
								{
									// $("#owner_name").val( data.dd[k]['owner_name']);
									tbody += '<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="' + data.dd[k]['owner_name'] + '" readonly  placeholder="Owner Name" /></td>';
									tbody += '<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="' + data.dd[k]['guardian_name'] + '" readonly  placeholder="Guardian Name" /></td>';
									tbody += '<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="' + data.dd[k]['mobile_no'] + '"  maxlength="10" minlength="10"  placeholder="Mobile No." /></td>';
									tbody += '<td><input type="email" name="email_id[]" id="email_id" class="form-control" " value="' + data.dd[k]['email'] + '"  placeholder="Email ID" /></td>';
									tbody += "</tr>";
									i++;
								} 								
							}


							//alert(tbody);
							$("#owner_details").html(tbody);
							$("#owner_dtl2").html(tbody);

							$("#prop_id").val(prop_dtl_id);
							$("#saf_id").val(saf_id);							
							$("#count").val(i);
							$("#ward_id").val(ward_mstr_id);
							$("#ward_id").prop("readonly", true);
							$("#area_in_sqft").val(area_sqft);
							$("#area_in_sqft").prop("readonly", true);

							$("#elec_k_no").val(elect_consumer_no);
							$("#elect_acc_no").val(elect_acc_no);
							$("#elect_bind_book_no").val(elect_bind_book_no);
							$("#elect_cons_category").val(elect_cons_category);
							$("#address").val(prop_address);
							$("#pin").val(prop_pin_code);
							$("#address").prop("readonly", false);
							$("#pin").prop("readonly", false);
							$("#owner_add").hide();
							getAreaSqmt();

						}
					} 
					else 
					{
						$("#holding_no").prop("readonly", false);
						alert(data.dd.message);
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
						$("#landmark").val("");

						$("#address").prop("readonly", false);
						$("#pin").prop("readonly", false);
						$("#ward_id").prop("readonly", false);
						$("#area_in_sqft").prop("readonly", false);
						$("#landmark").prop("readonly", false);


						var tbody = '<tr><td><input type="text"  name="owner_name[]" class="form-control owner_name" placeholder="Owner Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="guardian_name1" name="guardian_name[]" class="form-control guardian_name" placeholder="Guardian Name" value="" onkeypress="return isAlpha(event);" onkeyup="borderNormal(this.id);" /></td><td><input type="text" id="mobile_no1" name="mobile_no[]" class="form-control mobile_no" placeholder="Mobile No." value="" onkeypress="return isNum(event);" onkeyup="borderNormal(this.id);" maxlength="10" minlength="10" /></td><td><input type="email" id="email_id" name="email_id[]"  class="form-control city" placeholder="Email Id" value="" onkeyup="borderNormal(this.id);" /></td><td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td></tr>';

						$("#owner_details").html("");

						$("#owner_details").html(tbody);

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

			$("#owner_details").html("");
			$("#owner_details").html(appendData);


		}

	}

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
	function show_hide_holding_box(str)
    {

    	
       $("#owner_append").html("");
       var connection_through_id=str;
    	// alert(connection_through_id);
		var add = document.getElementsByClassName('fa-plus-square');
        //  alert(add.length); 	
		//  console.log(add);		 
        // $("input[name='owner_name[]']").val("");
        // $("input[name='guardian_name[]']").val("");
        // $("input[name='mobile_no[]']").val("");

        // $("input[name='owner_name[]']").attr("readonly",false);
        // $("input[name='guardian_name[]']").attr("readonly",false);
        // $("input[name='mobile_no[]']").attr("readonly",false);
        

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
		  if(add.length==0)
		  {
			$('#owner_details tr:nth-last-child(1)').append('<td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>');
			
		  }
          
       }
     	

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
	function meterFixed(type)
	{
		var property_type_id = $("#property_type_id").val();
		var connection_type_id = $("#connection_type_id").val();
		if(type==0 && property_type_id==3 && connection_type_id==1)
		{
			$("#label_reading").html("Rate Per Month (In K.L./Units) <span class='text-danger'>*</span>");

		}
		else
		{
			show_hide_meter_div(connection_type_id);
		}
	}
</script>

