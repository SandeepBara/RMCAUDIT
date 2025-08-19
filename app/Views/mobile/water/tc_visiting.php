<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<style type="text/css">
  .error
  {
      color: red;
  }
</style>
	<div id="content-container">
    <!--Page content-->
		<div id="page-content">       
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Visiting Detail</h3>
				</div>
				<div class="panel-body">
					
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-2">Select Module : </div>
								<div class="col-md-2">
									<input type="radio" name="select_module" id="select_module" value="property" onclick="show_box(this.value)"> Property
								</div>
								<div class="col-md-2">
									<input type="radio" name="select_module" id="select_module" value="SAF"  onclick="show_box(this.value)"> SAF
								</div>
								<div class="col-md-2">
									<input type="radio" name="select_module" id="select_module" value="water" onclick="show_box(this.value)"> Water
								</div>
								<div class="col-md-2">
									<input type="radio" name="select_module" id="select_module" value="trade"  onclick="show_box(this.value)"> Trade
								</div>
								

							</div>

						</div>
						
						
					
				</div>

			</div>
		

			<div class="panel panel-bordered panel-dark" id="property" style="display: none;">
				<div class="panel-heading">
					<h3 class="panel-title">Property Visiting Detail</h3>
				</div>
				<div class="panel-body">

				
					<form method="post" action="" id="property_form">
						<div class="row">
							<div class="col-md-1">
								<input type="hidden" name="module_property" id="module" value="property">
								<input type="hidden" name="prop_id"  id="prop_id">

								<label for="exampleInputEmail1">Ward No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="ward_id" id="ward_id" class="form-control" onchange="validate_holding(this.value)">
										<option value="">Select</option>
										<?php
										  if($ward_list):
											foreach($ward_list as $val):
										?>
										 <option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected";} ?>><?php echo $val['ward_no'];?></option>
										<?php
											endforeach;
										  endif;

										?>
									</select>
								</div>
							</div>
							<div class="col-md-1">
								<label for="exampleInputEmail1">Holding No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="holding_no" id="holding_no" value="<?php echo $holding_no;?>" class="form-control" onblur="validate_holding(this.value)">
								</div>
							</div>

							<div class="col-md-1">
								<label for="exampleInputEmail1">Message  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="message_id" id="message_id" class="form-control" onchange="show_other_box(this.value,'property')">
										<option value="">Select</option>
										<?php
											if($feedback_list):
												foreach($feedback_list as $val):
										?>
										<option value="<?php echo $val['id'];?>"><?php echo $val['message'];?></option>
										<?php
												endforeach;
											endif;
										?>
										<option value="0">Others</option>
									</select>
								</div>
							</div>

							<div class="col-md-3" id="other_reason_props" style="display: none;">
								<div class="form-group">
									<textarea name="other_reason_prop" id="other_reason_prop" class="form-control" placeholder="Enter Reason"></textarea>
								</div>
							</div>

						</div>

						
						
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="submit" name="submit_property" id="submit_property" value="Search" class="form-control btn btn-success">
							</div>
						</div>
					</form>
				</div>

			</div>


			<div class="panel panel-bordered panel-dark" id="SAF" style="display: none;">
				<div class="panel-heading">
					<h3 class="panel-title">SAF Visiting Detail</h3>
				</div>
				<div class="panel-body">

					
					<form method="post" action="" id="saf_form">
						<div class="row">
							<div class="col-md-1">
								<label for="exampleInputEmail1">Ward No.  :</label>
								<input type="hidden" name="module_saf"  value="saf">
								<input type="hidden" name="saf_id"  id="saf_id">
								
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="ward_id_saf" id="ward_id_saf" class="form-control"  onchange="validate_saf(this.value)">
										<option value="">Select</option>
										<?php
										  if($ward_list):
											foreach($ward_list as $val):
										?>
										 <option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected";} ?>><?php echo $val['ward_no'];?></option>
										<?php
											endforeach;
										  endif;

										?>

									</select>
								</div>
							</div>
							<div class="col-md-1">
								<label for="exampleInputEmail1">SAF No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="saf_no" id="saf_no" value="<?php echo $saf_no;?>" class="form-control"  onblur="validate_saf(this.value)">
								</div>
							</div>

							<div class="col-md-1">
								<label for="exampleInputEmail1">Message  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="message_id" id="message_id" class="form-control" onchange="show_other_box(this.value,'saf')">
										<option value="">Select</option>
										<?php
											if($feedback_list):
												foreach($feedback_list as $val):
										?>
										<option value="<?php echo $val['id'];?>"><?php echo $val['message'];?></option>
										<?php
												endforeach;
											endif;
										?>
										<option value="0">Others</option>
									</select>
								</div>
							</div>

							<div class="col-md-3" id="other_reason_safs" style="display: none;">
								<div class="form-group">
									<textarea name="other_reason_saf" id="other_reason_saf"  placeholder="Enter Reason" class="form-control"></textarea>
								</div>
							</div>


						</div>


						
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="submit" name="submit_saf" id="submit_saf" value="Search" class="form-control btn btn-success">
							</div>
						</div>
					</form>
				</div>

			</div>

			<div class="panel panel-bordered panel-dark" id="water" style="display: none;">
				<div class="panel-heading">
					<h3 class="panel-title">Water Visiting Detail</h3>
				</div>
				<div class="panel-body">

					
					<form method="post" action="" id="water_form">
						<div class="row">
							<div class="col-md-1">
								<label for="exampleInputEmail1">Ward No.  :</label>
								<input type="hidden" name="module_water" id="module_water">
								<input type="hidden" name="water_conn_id"  id="water_conn_id">

								
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="ward_id_water" id="ward_id_water" class="form-control"  onchange="validate_consumer(this.value),validate_application(this.value)">
										<option value="">Select</option>
										<?php
										  if($ward_list):
											foreach($ward_list as $val):
										?>
										 <option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected";} ?>><?php echo $val['ward_no'];?></option>
										<?php
											endforeach;
										  endif;

										?>
									</select>
								</div>
							</div>
							<div class="col-md-1">
								<label for="exampleInputEmail1">Type :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="type" id="type" class="form-control" onchange="validate_cons_app(this.value)">
										<option value="">Select</option>
										<option value="consumer">Consumer</option>
										<option value="application">Application</option>
										
									</select>
								</div>
							</div>

							<div id="consumer" style="display: none;">
							<div class="col-md-1">
								<label for="exampleInputEmail1">Consumer No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="consumer_no" id="consumer_no" value="<?php echo $consumer_no;?>" class="form-control"  onblur="validate_consumer(this.value)">
								</div>
							</div>
							</div>

							<div id="application" style="display: none;">
							<div class="col-md-1">
								<label for="exampleInputEmail1">Application No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="application_no" id="application_no" value="<?php echo $application_no;?>" class="form-control"  onblur="validate_application(this.value)">
								</div>
							</div>
							</div>

							<div class="col-md-1">
								<label for="exampleInputEmail1">Message  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="message_id" id="message_id" class="form-control"  onchange="show_other_box(this.value,'water')">
										<option value="">Select</option>
										<?php
											if($feedback_list):
												foreach($feedback_list as $val):
										?>
										<option value="<?php echo $val['id'];?>"><?php echo $val['message'];?></option>
										<?php
												endforeach;
											endif;
										?>
										<option value="0">Others</option>
									</select>
								</div>
							</div>



							<div class="col-md-3" id="other_reason_waters" style="display: none;">
								<div class="form-group">
									<textarea name="other_reason_water" id="other_reason_water"  placeholder="Enter Reason" class="form-control"></textarea>
								</div>
							</div>



						</div>


						
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="submit" name="submit_water" id="submit_water" value="Search" class="form-control btn btn-success">
							</div>
						</div>
					</form>
				</div>

			</div>

			<div class="panel panel-bordered panel-dark" id="trade" style="display: none;">
				<div class="panel-heading">
					<h3 class="panel-title">Trade Visiting Detail</h3>
				</div>
				<div class="panel-body">

				
					<form method="post" action="" id="trade_form">
						<div class="row">
							<div class="col-md-1">
								<label for="exampleInputEmail1">Ward No.  :</label>
								<input type="hidden" name="module_trade"  value="trade">
								<input type="hidden" name="license_id" id="license_id"  >

								
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="ward_id_trade" id="ward_id_trade" class="form-control"  onblur="validate_license(this.value)">
										<option value="">Select</option>
										<?php
										  if($ward_list):
											foreach($ward_list as $val):
										?>
										 <option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected";} ?>><?php echo $val['ward_no'];?></option>
										<?php
											endforeach;
										  endif;

										?>
									</select>
								</div>
							</div>
							<div class="col-md-1">
								<label for="exampleInputEmail1">License No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="license_no" id="license_no" value="<?php echo $license_no;?>" class="form-control"  onblur="validate_license(this.value)">
								</div>
							</div>

							<div class="col-md-1">
								<label for="exampleInputEmail1">Message  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="message_id" id="message_id" class="form-control" onchange="show_other_box(this.value,'trade')">
										<option value="">Select</option>
										<?php
											if($feedback_list):
												foreach($feedback_list as $val):
										?>
										<option value="<?php echo $val['id'];?>"><?php echo $val['message'];?></option>
										<?php
												endforeach;
											endif;
										?>
										<option value="0">Others</option>
									</select>
								</div>
							</div>

							<div class="col-md-3" id="other_reason_trades" style="display: none;">
								<div class="form-group">
									<textarea name="other_reason_trade" id="other_reason_trade"  placeholder="Enter Reason" class="form-control"></textarea>
								</div>
							</div>



						</div>


						
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="submit" name="submit_trade" id="submit_trade" value="Search" class="form-control btn btn-success">
							</div>
						</div>
					</form>
				</div>

			</div>


			</div>
		
		</div>

    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

    $('#property_form').validate({ // initialize the plugin
       

        rules: {
            ward_id: {
                required: true,
               
            },
            holding_no: {
                required: true,
               
            },
            message_id: {
                required: true,
               
            },
            other_reason_prop: {
                required: true,
               
            },
           
        }


    });

    $('#saf_form').validate({ // initialize the plugin
       

        rules: {
            ward_id_saf: {
                required: true,
               
            },
            saf_no: {
                required: true,
               
            },
            message_id: {
                required: true,
               
            },
            other_reason_saf: {
                required: true,
               
            },
           
        }


    });

    $('#water_form').validate({ // initialize the plugin
       

        rules: {
            ward_id_water: {
                required: true,
               
            },
            consumer_no: {
                required: true,
               
            },
            application_no: {
                required: true,
               
            },
            type: {
                required: true,
               
            },
            
            message_id: {
                required: true,
               
            },
            other_reason_water: {
                required: true,
               
            },
           
        }


    });

    $('#trade_form').validate({ // initialize the plugin
       

        rules: {
            ward_id_trade: {
                required: true,
               
            },
            license_no: {
                required: true,
               
            },
            message_id: {
                required: true,
               
            },
            other_reason_trade: {
                required: true,
               
            },
            
            
           
        }


    });

});

    function show_box(str)
    {
    	//alert(str);
    	if(str=='property')
    	{
    		$("#property").show();
    		$("#water").hide();
    		$("#trade").hide();
    		$("#SAF").hide();

    	}
    	else if(str=='water')
    	{
    		$("#water").show();
    		$("#property").hide();
    		$("#trade").hide();
    		$("#SAF").hide();
    	}
    	else if(str=='SAF')
    	{
    		$("#SAF").show();
    		$("#water").hide();
    		$("#property").hide();
    		$("#trade").hide();

    	}
    	else
    	{
    		$("#trade").show();
    		$("#water").hide();
    		$("#property").hide();
    		$("#SAF").hide();
    	}
    }


    function validate_holding()
    {
    	
         var holding_no=$("#holding_no").val();
         var ward_id=$("#ward_id").val();

       		
         if(holding_no && ward_id)
         {
          
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("TCVisiting/validate_holding");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no,"ward_id":ward_id
                },
               
                success:function(data){
                 
                	//alert(data);

                	if(data.response==false)
                	{
                		alert("Holding No. does not exist");
                		$("#holding_no").val("");
                		$("#prop_id").val("");
                	}
                	else
                	{
                		$("#prop_id").val(data.prop_id);
                	}
                	

                   } 

               });

                   //
 			}

 	}
           
 	function validate_saf()
    {
    	
         var saf_no=$("#saf_no").val();
         var ward_id_saf=$("#ward_id_saf").val();

        
       		
         if(saf_no && ward_id)
         {

         	// alert(ward_id_saf);
          
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("TCVisiting/validate_saf");?>',
                dataType: "json",
                data: {
                        "saf_no":saf_no,"ward_id":ward_id_saf
                },
               
                success:function(data){
                 
                	

                	if(data.response==false)
                	{
                		alert("SAF No. does not exist");
                		$("#saf_no").val("");
                		$("#saf_id").val("");
                	}
                	else
                	{
                		$("#saf_id").val(data.saf_id);
                	}
                	

                   } 

               });

                   //
 			}

 	}
    

    function validate_consumer()
    {
    	
         var consumer_no=$("#consumer_no").val();
         var ward_id_water=$("#ward_id_water").val();

        
       		
         if(consumer_no && ward_id_water)
         {

         	 //alert(ward_id_water);
          	 // alert(consumer_no);

            $.ajax({
                type:"POST",
                url: '<?php echo base_url("TCVisiting/validate_consumer");?>',
                dataType: "json",
                data: {
                        "consumer_no":consumer_no,"ward_id":ward_id_water
                },
               
                success:function(data){
                 
                	
                	//alert(data);

                	if(data.response==false)
                	{
                		alert("Consumer No. does not exist");
                		$("#consumer_no").val("");
                		$("#water_conn_id").val("");
                	}
                	else
                	{
                		$("#water_conn_id").val(data.consumer_id);
                		$("#module_water").val('water consumer');
                	}
                	

                   } 

               });

                   //
 			}

 	}   

 	function validate_application()
    {
    	
         var application_no=$("#application_no").val();
         var ward_id_water=$("#ward_id_water").val();

        
       		
         if(application_no && ward_id_water)
         {

          	//alert(application_no);
          	//alert(ward_id_water);


            $.ajax({
                type:"POST",
                url: '<?php echo base_url("TCVisiting/validate_application");?>',
                dataType: "json",
                data: {
                        "application_no":application_no,"ward_id":ward_id_water
                },
               
                success:function(data){
                 
                	//alert(data);

                	if(data.response==false)
                	{
                		alert("Application No. does not exist");
                		$("#application_no").val("");
                		$("#water_conn_id").val("");
                	}
                	else
                	{
                		$("#water_conn_id").val(data.water_conn_id);
                		$("#module_water").val('water application');
                	}
                	

                   } 

               });

                   //
 			}

 	}   

 	function validate_license()
    {
    	
         var license_no=$("#license_no").val();
         var ward_id_trade=$("#ward_id_trade").val();

        
       		
         if(license_no && ward_id_trade)
         {

          	//alert(license_no);
          	//alert(ward_id_trade);


            $.ajax({
                type:"POST",
                url: '<?php echo base_url("TCVisiting/validate_license");?>',
                dataType: "json",
                data: {
                        "license_no":license_no,"ward_id":ward_id_trade
                },
               
                success:function(data){
                 
                	//	alert(data.license_id);

                	if(data.response==false)
                	{
                		alert("License No. does not exist");
                		$("#license_no").val("");
                		$("#license_id").val("");
                		
                	}
                	else
                	{
                		$("#license_id").val(data.license_id);
                	}
                	

                   } 

               });

                   //
 			}

 	}   


 	function validate_cons_app(str)
 	{
 		if(str=='application')
 		{
 			$("#application").show();
 			$("#consumer").hide();
 			
 		}
 		else
 		{
 			$("#consumer").show();
 			$("#application").hide();

 		}
 	}

 	function show_other_box(str,argument)
 	{

 		//alert(str);
 		//alert(argument);

 		if(str==0)
 		{
 			if(argument=='property')
 			{
 				$("#other_reason_props").show();
 			}
 			else if(argument=='water')
 			{
 				$("#other_reason_waters").show();
 			}
 			else if(argument=='trade')
 			{
 				$("#other_reason_trades").show();
 			}
 			else
 			{
 				$("#other_reason_safs").show();
 			}
 			
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
<?php 
    if($insert=flashToast('insert'))
    {
        echo "modelInfo('".$insert."');";
    }
  ?>
  


</script>