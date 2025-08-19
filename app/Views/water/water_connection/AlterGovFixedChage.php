<?php 
	if(isset($mobile) && $mobile==1)
		echo$this->include("layout_mobi/header");
	else
		echo$this->include('layout_vertical/header');
?>
<style type="text/css">

	.error
	{
		color:red ;
	}

</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
		<?php
		if(isset($mobile) && $mobile!=1)
		{
		?>
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="<?=base_url()?>/WaterViewConsumerDetails/index/<?=$consumer_id?>" >Water Connection Details</a></li>
            <li><a href="#" class="active">Update Consumer</a></li>
        </ol><!--End breadcrumb-->
		<?php
		}
		?>
    </div>
    <!--Page content-->
    <div id="page-content">
		
		<form id="form" name="form" method="post" enctype="multipart/form-data" >
			<?php if(isset($validation)){ ?>
				<?= $validation->listErrors(); ?>
			<?php } ?>
			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
					<?php
					if(isset($mobile) && $mobile==1)
					{
					?>
					<a href="#" class="btn btn-info pull-right panel-control" onclick="history.back();">
						<i class="fa fa-arrow-left" aria-hidden="true"></i>Back
					</a>
					<?php
					}
					?>
                    <h3 class="panel-title">Update Consumer</h3>					
                </div>
				<div class="panel-body">

                    <div class="row">
                        <label class="col-md-2">Consumer No. <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <b><?=isset($consumer_dtl['consumer_no']) ?$consumer_dtl['consumer_no'] :"N/A"?></b>
                        </div>
					</div>
					
					<div class="row">
                    	<label class="col-md-2">Ward No. <span class="text-danger"></span></label>
						<div class="col-md-3 pad-btm">
                            <b><?=isset($consumer_dtl['ward_no']) ?$consumer_dtl['ward_no'] :"N/A"?></b>
						</div>


                        <label class="col-md-2">Holding No.<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                            <b><?=isset($consumer_dtl['holding_no']) ?$consumer_dtl['holding_no'] :"N/A"?></b>
                        </div>

					</div>
					
					<div class="row">
                    	<label class="col-md-2">Property Type <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                            <b><?=isset($consumer_dtl['property_type']) ?$consumer_dtl['property_type'] :"N/A"?></b>
						</div>

						<div id="category_div" style="display: none;">
	                        <label class="col-md-2">Applicant Category<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<?='Applicant Category'?>
							</div>
						</div>

						
					</div>

					<div class="row">
                    	<label class="col-md-2">Property Address<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                            <b><?=isset($consumer_dtl['address']) ?$consumer_dtl['address'] :"N/A"?></b>
						</div>
					</div>


					<div class="row">
                    	<label class="col-md-2">Total Area (Sq.Ft.)<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <!-- <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onkeypress=" return isNumber(event);" onkeyup="getAreaSqmt();" value="<?=isset($consumer_dtl['area_sqft']) ? $consumer_dtl['area_sqft'] :''?>"> -->
                             <span id="area_in_sqft" onkeyup="getAreaSqmt();"><?=isset($consumer_dtl['area_sqft']) ? $consumer_dtl['area_sqft'] :''?></span>
						</div>

						<label class="col-md-2">Total Area (Sq.Mt.)<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
							 <!-- <input type="text" name="area_in_sqmt" id="area_in_sqmt" class="form-control" onkeypress=" return isNumber(event);" onkeyup="getAreaSqft();" value="<?=isset($consumer_dtl['area_sqmt']) ? $consumer_dtl['area_sqmt'] :''?>"> -->
                             <span id="area_in_sqmt" onkeyup="getAreaSqft();"><?=isset($consumer_dtl['area_sqft']) ? $consumer_dtl['area_sqft'] :''?></span>
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
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Owner Name</th>
                                        <th>Guardian Name</th>
                                        <th>Mobile No.</th>
                                        <th>Email ID</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody id="owner_details">
                                    <?php
                                        foreach($owner_list as $owner)
                                        {
                                    ?>
                                    <tr>
                                        <td><?=isset($owner['applicant_name'])?$owner['applicant_name']:''?></td>
                                        <td><?=isset($owner['father_name'])?$owner['father_name']:''?></td>
                                        <td><?=isset($owner['mobile_no'])?$owner['mobile_no']:''?></td>
                                        <td><?=isset($owner['email'])?$owner['email']:''?></td>
                                        <td><?=isset($owner['city'])?$owner['city']:''?></td>
                                        
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

			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Consumer Meter/Fixed Details</h3>
                </div>
               
					<div class="panel-body">

						<div class="row">
							<label class="col-md-2">Connection Type<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
                                <?php
                                if(in_array($consumer_dtl['connection_type'],[1,2]) &&($consumer_dtl['meter_status']==0))
                                {
                                    $connection_type = "Meter/Fixed";
                                }
                                elseif($consumer_dtl['connection_type']==1)
                                {
                                    $connection_type='Meter';
                                }
                                else if($consumer_dtl['connection_type']==2)
                                {
                                    $connection_type='Gallon';
                                }   
                                else
                                {
                                    $connection_type='Fixed';
                                }
                                ?>
                                <b><?=isset($connection_type) ? $connection_type :"N/A"?></b>
							</div>
							<label class="col-md-2">Date of <?=$connection_type;?><span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
                                <b><?=isset($consumer_dtl['meter_connection_date']) ? date('d-m-Y',strtotime($consumer_dtl['meter_connection_date'])):"N/A"?></b>
                            </div>
						</div>
                        <div class="row">
                            <label class="col-md-2">Rate Per Month (In K.L./Units)<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <input type="text" name="rate_per_month" id="rate_per_month" class="form-control" onkeypress=" return isNumber(event);"  value="<?=isset($consumer_dtl['rate_per_month']) ? $consumer_dtl['rate_per_month'] :''?>" require>                               
                            </div>
                            <label class="col-md-3">Document</label>
                            <div class="col-md-3">
                                <input type="file" name="meter_doc" id="meter_doc" class="form-control">
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
	if(isset($mobile) && $mobile==1)
		echo$this->include("layout_mobi/footer");
	else
		echo $this->include('layout_vertical/footer');
?>


<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>


<script>

$(document).ready(function () 
{

	//getUnitRate();
    getAreaSqmt();
	
	jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please"); 



	$('#form').validate({ // initialize the plugin
        rules: {

        	"rate_per_month":"required",
        	"meter_doc":"required",
           
        }
    });


});

</script>
<script type="text/javascript">
	
	function getHoldingDetails()
	{
		var ward_id=$("#ward_id").val();
		var holding_no=$("#holding_no").val();

		if(ward_id && holding_no)
		{
			//alert('dddddd');

			$.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterAddExistingConsumer/getHoldingDetails");?>',
                dataType:"json",
                data: {
                        "holding_no":holding_no,"ward_id":ward_id
                },
				beforeSend: function() {
					$("#loadingDiv").show();
					$("#btn_review").prop('disabled',true);
					
				},
                success:function(data){
                	//alert(data.response);
                	//console.log(data);
                	$("#loadingDiv").hide();
                	

                	if(data.response==true)
                	{	
                		 $("#btn_review").prop('disabled',false);

                		 var tbody="";
                		 for(var k in data.result)
                		 {
                		 	 var total_area_sqft=data.result[k]['total_area_sqft'];

                		 	 tbody+="<tr><td><input type='text' name='owner_name[]' id='owner_name' value='"+data.result[k]['owner_name']+"' class='form-control' readonly></td><td><input type='text' name='guardian_name[]' id='guardian_name' value='"+data.result[k]['guardian_name']+"' class='form-control' ></td><td><input type='text' name='mobile_no[]' id='mobile_no' value='"+data.result[k]['mobile_no']+"' class='form-control' ></td><td><input type='text' name='email_id[]' id='email_id' value='"+data.result[k]['email_id']+"' class='form-control' ></td></tr>";
                		 	 
                		 }
                		 //alert(tbody);
                		 $("#area_in_sqft").val(total_area_sqft);
                		 $("#owner_details").append(tbody);
                	}
                	else
                	{
                		alert("Holding No. not Found");
                		$("#ward_id").val("");
                		$("#holding_no").val("");
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
					//$("#btn_review").prop('disabled',true);
					
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
		 var area_in_sqft=$("#area_in_sqft").text();
		 //alert(area_in_sqft);
         //var c = isNum(area_in_sqft);
		 if (area_in_sqft == "")
	     area_in_sqft = 0;
	     var area_in_sqmt = parseFloat(area_in_sqft) * 0.0929;
	     

	     area_in_sqmt=area_in_sqmt.toFixed(2);
	     $("#area_in_sqmt").text(area_in_sqmt);

	}

	function getAreaSqft()
	{
		 var area_in_sqmt=$("#area_in_sqmt").text();
		 if (area_in_sqmt == "")
	     area_in_sqmt = 0;
	     var area_in_sqft = parseFloat(area_in_sqmt) * 10.764;
	     area_in_sqft=area_in_sqft.toFixed(2);
	     $("#area_in_sqft").text(area_in_sqft);

	}

    function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
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
    <?php 
    if($error=flashToast('error'))
    { 
        if(is_array($error))
        {
            foreach($error as $val)
            {
                echo "modelInfo('".$val."');";
            }
        }
        else
        echo "modelInfo('".$error."');";
    }
    if($error=flashToast('message'))
    { 
        if(is_array($error))
        {
            foreach($error as $val)
            {
                echo "modelInfo('".$val."');";
            }
        }
        else
        echo "modelInfo('".$error."');";
    }
    ?>
    
</script>

