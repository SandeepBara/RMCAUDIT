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
		<form id="form" name="form" method="post" onsubmit="validate_from();" enctype="multipart/form-data" >
			<?php if(isset($validation))
            { 
                ?>
                <div class="text-danger">
                <?= $validation->listErrors(); ?>
                </div>				
			    <?php 
            } 
            ?>
			<div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">                    
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
                <div class="panel-title text-center bg-mint"> 
                        Consuer No.: <?=$consumer_details['consumer_no'];?>
                </div>
				<div class="panel-body">
                    <div class="row">
                        <label class="col-md-2">Type of Connection <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <strong>
                                <?=$consumer_details['connection_type'];?> 
                            </strong>                        
                        </div>
						<label class="col-md-2">Connection Through <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">							
                            <strong>
                                <?=$consumer_details['connection_through'];?> 
                            </strong>						                    
						</div>
					</div>
					
					<div class="row">
                        <label class="col-md-2">Property Type<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">							
                            <strong>
                                <?=$consumer_details['property_type'];?> 
                            </strong>
							
                        </div>

                        <div class="row">
                            <label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <strong>
                                    <?=$consumer_details['flat_count'];?> 
                                </strong>
                            </div>                            						
                	    </div>

					</div>
					
					<div class="row">
						<div >
							<label class="col-md-2">Category Type <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">	
                                <strong>
                                    <?=$consumer_details['category'];?> 
                                </strong>
								
							</div>
						</div>

						<div >
                            <label class="col-md-2">Pipeline Type <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">
                                <strong>
                                    <?=$consumer_details['pipeline_type'];?> 
                                </strong>
                            </div>
                       </div>					 
					</div>

                    <div class="row">
                       
						<div>
                            <?php
                            if(isset($consumer_details['holding_no']))
                            {
                                ?>
                                    <label class="col-md-2">Holding No. <span class="text-danger">*</span></label>
                                    <div class="col-md-3 pad-btm">
                                        <strong>
                                            <?=$consumer_details['holding_no'];?> 
                                        </strong>                                        
                                    </div>
                                <?php
                            }
                            elseif(isset($consumer_details['saf_no']))
                            {
                                ?>
                                    <label class="col-md-2">SAF No. <span class="text-danger">*</span></label>
                                    <div class="col-md-3 pad-btm">
                                        <strong>
                                            <?=$consumer_details['saf_no'];?> 
                                        </strong>  
                                    </div>
                                <?php
                            }
                            ?>
							
						</div>
                        <div>
							<label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">								
                                <strong>
                                    <?=$consumer_details['ward_no'];?> 
                                </strong> 
								
							</div>
						</div>
                	</div>
					
					<div class="row">						

						<label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                        	<strong>
                                <?=$consumer_details['area_sqft'];?> 
                            </strong> 
						</div>
                        <label class="col-md-2">Total Area(in Sq. Mtr) <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
                            <strong>
                                <?=$consumer_details['area_sqmt'];?> 
                            </strong>
						</div>
						
                	</div>
					
					
					<div class="row">
                        <label class="col-md-2">Address<span class="text-danger">*</span></label>
						<div class="col-md-10 pad-btm">
                            <strong>
                                <?=$consumer_details['address'];?> 
                            </strong>
							
						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2">Connection Date<span class="text-danger"></span></label>
						<div class="col-md-10 pad-btm">
                            <strong>
                                <?=date('d-m-Y',strtotime($consumer_details['created_on']));?> 
                            </strong>
							
						</div>                        
                    </div>
				</div>
			</div>
			
			<div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    
                    <h3 class="panel-title">Olde Owner Details</h3>
                </div>
               
					<div class="panel-body table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Owner Name</th>
									<th>Guardian Name</th>
									<th>Mobile No.</th>
									<th>City</th>
                                    <th>District</th>
                                    <th>State</th>
								</tr>
                            </thead>
                                 <tbody>
                                <?php
                            	foreach($owner_name as $key=>$val)
                            	{ 
                                    ?>                                   
                                        <tr>
                                            <td><?php echo $val['applicant_name'];?></td>
                                            <td><?php echo $val['father_name'];?></td>
                                            <td><?php echo $val['mobile_no'];?></td>
                                            <td><?php echo $val['city'];?></td>
                                            <td><?php echo $val['district'];?></td>
                                            <td><?php echo $val['state'];?></td> 
                                        </tr>                     
                                    <?php	
                            	}
                                ?>
                                </tbody>
                                
                        </table>
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
									<th>City</th>
                                    <th>District</th>
                                    <th>State</th>									
									<th colspan="2" id="owner_add">Add</th>
								</tr>
                            </thead>
                            

                            <?php
                            if(!isset($new_owner_name))
                            {
                                ?>
                                <tbody id="owner_dtl">
                                    <tr>
                                        <td><input type="text" name="owner_name[]"  id="owner_name1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name"></td>
                                        <td><input type="text" name="guardian_name[]" id="guardian_name1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name"></td>
                                        <td><input type="text" name="mobile_no[]" id="mobile_no1" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No."></td>
                                        
                                        <td><input type="text" name="city[]" id="city<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="City Name" /></td>
                                        <td><input type="text" name="district[]" id="district<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="District Name"  /></td>
                                        <td><input type="text" name="state[]" id="state<?=$key;?>" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);" placeholder="State Name"  /></td>                                           
                                        <input type="hidden" name="count" id="count" value="1">
                                        <td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
                                    </tr>
                                    <!-- <div id="owner_append"></div> -->
                                </tbody>
                                <?php
                            }
                            else
                            {
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
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-1">Document<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm">                                
                                <input type="file" name = "document" id="document"  accept="pdf,jpg"/>
                            </div>
                            <label class="col-md-2">Remarks<span class="text-danger">*</span></label>
                            <div class="col-md-6 pad-btm">                                
                                <textarea name="remarks" id="remarks" class="from-control col-md-12"  onkeypress="return isAlphaNumCommaSlash(event);"></textarea>
                            </div>
                        </div>
                    </div>
				
			</div>
			
			<div class="panel">
				<div class="panel-body text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary" onclick=" validate_from();" >SUBMIT</button>
                </div>
            </div>
			
		</form>
        
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<?php 
    if(in_array($user_type,[1,2,28,8,11]))
    {
        echo $this->include('layout_vertical/footer');
    }
	elseif($user_type==4 || $user_type==5 || $user_type==7)
	{

		echo $this->include('layout_mobi/footer');
	}
    elseif($user_type=="")
    {   
        echo  $this->include('layout_home/footer');
        
    }
	
  
 ?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">  
        $("#form").validate({
            rules: {                
                'owner_name[]':{
                    required: true
                    },
                'mobile_no[]':{
                    required: true,                    
                    minlength: 10,
                    maxlength: 10,
                },
                'document':{
                    required: true,
                },
                'remarks':{
                    required: true,
                }
                
                    
            }
        });

           

</script>
<script>

    $(document).ready(function () {

       
        load();
        read_only_remove('consumer_check_box', 'consumer_block');
        // read_only_remove('property_check_box','property_block');
        // read_only_remove('owner_check_box','owner_block');
        // read_only_remove('elect_check_box','elect_block');
        $("#holding_no").keypress(function () {
    
            $("#btn_review").attr('disabled', true);
    
        });

        $("#saf_no").keypress(function () {
    
            $("#btn_review").attr('disabled', true);
    
        });

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
        var element_id="del"+count;        
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