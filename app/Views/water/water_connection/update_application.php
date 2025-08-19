<?=$this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<style>
.error {
    color: red;
}
</style>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#"> Water </a></li>
            <li><a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.$water_conn_id);?>"> Water Connection Details </a></li>
            <li class="active"><a href="#">Update Water Connection </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">

		<?php if(isset($_SESSION['validation'])){ ?>
			<?= $_SESSION['validation']->listErrors(); 
			unset($_SESSION['validation']);?>
		<?php } ?>
		<form id="form" name="form" method="post" action="<?php echo base_url('WaterUpdateApplication/update_application')?>" >
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.$water_conn_id);?>" class="panel-control btn btn-info" ><i class="fa fa-arrow-left"></i> Back</a>
				
				<h3 class="panel-title">Update Water Connection </h3>

			</div>
		
		
		
			<div class="panel-body">
				<div class="row">
					<label class="col-md-2">Application No.<span class="text-danger">*</span></label>
					<div class="col-md-3  pad-btm"><b style="color: green; font-size: 14px;"><?php echo $connection_dtls['application_no'];?></b>
					</div>
					<label class="col-md-2">Owner Type<span class="text-danger">*</span></label>
					<div class="col-md-3  pad-btm"><b><?php echo $connection_dtls['owner_type'];?></b>
					</div>
				</div>
				<div class="row">
					<label class="col-md-2">Type of Connection <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<select id="connection_type_id" name="connection_type_id" class="form-control readonly" readonly disabled>
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
					<label class="col-md-2">Connection Through <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<select name="conn_through_id" id="conn_through_id" class="form-control readonly" readonly disabled>
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
					<label class="col-md-2">Property Type <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<select name="property_type_id" id="property_type_id" class="form-control readonly" onchange="show_box(this.value)" readonly disabled>
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
				
				</div>
				<div class="row" id="box" style="display: block;">

					<label class="col-md-2">Pipeline Type <span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
						<select name="pipeline_type_id" id="pipeline_type_id" class="form-control readonly" readonly disabled>
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

					<label class="col-md-2">Category Type <span class="text-danger">*</span></label>
					<div class="col-md-3 pad-btm">
						<select name="category" id="category" class="form-control readonly" readonly disabled>
							<option value="">Select</option>
							<option value="APL" <?php if($connection_dtls['category']=='APL'){ echo "selected"; }?>>APL</option>
							<option value="BPL" <?php if($connection_dtls['category']=='BPL'){ echo "selected"; }?>>BPL</option>
						</select>
					</div>
				</div>
			</div>
			<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $connection_dtls['id'];?>">
        </div>

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Applicant Electricity Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
					<label class="col-md-2">K. No.</label>
                    <div class="col-md-3 pad-btm">
						<input type="text" name="elec_k_no" id="elec_k_no" class="form-control"  value="<?php echo $connection_dtls['elec_k_no']; ?>">
                    </div>
					<label class="col-md-2">Bind Book No.</label>
                    <div class="col-md-3 pad-btm">
                        <input type="text" name="elec_bind_book_no" id="elec_bind_book_no" class="form-control"  value="<?php echo isset($connection_dtls['elec_bind_book_no'])?$connection_dtls['elec_bind_book_no']:""; ?>">
                    </div>
                </div>
                <div class="row">
					<label class="col-md-2">Account No.</label>
                    <div class="col-md-3 pad-btm">
                       <input type="text" name="elec_account_no" id="elec_account_no" class="form-control"  value="<?php echo isset($connection_dtls['elec_account_no'])?$connection_dtls['elec_account_no']:""; ?>">
                    </div>
                </div>
                <div class="row">
					<label class="col-md-2">Category Type</label>
                    <div class="col-md-2 pad-btm">
						<input type="radio" name="elec_category" value="Residential - DS I/II" <?php if($connection_dtls['elec_category']=='RESIDENTIAL - DS I/II'){echo "checked"; }?>>  Residential - DS I/II 
                    </div>
                    <div class="col-md-2 pad-btm">
						<input type="radio" name="elec_category" value="Commercial - NDS II/III" <?php if($connection_dtls['elec_category']=='COMMERCIAL - NDS II/III'){echo "checked"; }?>>  Commercial - NDS II/III 
                    </div>
                    <div class="col-md-2 pad-btm">
						<input type="radio" name="elec_category" value="Agriculture - IS I/II" <?php if($connection_dtls['elec_category']=='AGRICULTURE - IS I/II'){echo "checked"; }?>>Agriculture - IS I/II
                    </div>
                    <div class="col-md-2 pad-btm">
						<input type="radio" name="elec_category" value="Low Tension - LTS" <?php if($connection_dtls['elec_category']=='LOW TENSION - LTS'){echo "checked"; }?>>Low Tension - LTS 
                    </div>
                    <div class="col-md-2 pad-btm">
						<input type="radio" name="elec_category" value="High Tension - HTS" <?php if($connection_dtls['elec_category']=='HIGH TENSION - HTS'){echo "checked"; }?>>High Tension - HTS
                    </div>
                    <input type="hidden" name="water_conn_id2" id="water_conn_id2" value="<?php echo $connection_dtls['id']; ?>">
                </div>
				<div class="panel-body demo-nifty-btn text-center">
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary" value="btn_review">SUBMIT</button>
                </div>
            </div>
		</div>
        </form>
        
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Applicant Details</h3>
			</div>
			<div class="panel-body">
                <table class="table table-bordered table-responsive">
					<thead class="bg-trans-dark text-dark">
                        <tr>
                            <th style="font-size: 14px;">Owner Name</th>
                            <th style="font-size: 14px;">Guardian Name</th>
                            <th style="font-size: 14px;">Mobile No.</th>
                            <th style="font-size: 14px;">Email ID</th>
                            <th style="font-size: 14px;">State</th>
                            <th style="font-size: 14px;">District</th>
                            <th style="font-size: 14px;">City</th>

                            <?php 
                            //print_var($connection_dtls['owner_type']);
                            //if($connection_dtls['owner_type']=='TENANT')
                            {
                                ?>
                            	<th style="font-size: 14px;"> Edit </th>
                                <?php 
                            }
                            //if($connection_dtls['owner_type']=='TENANT')
                            {
                                ?>
                                <th> Delete </th>
                                <?php 
                            }
                            ?>
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
                                    <td style="font-size: 14px;"><?php echo $val['applicant_name'];?></td>
                                    <input type="hidden" name="applicant_name<?php echo $val['id'];?>" id="applicant_name<?php echo $val['id'];?>" value="<?php echo $val['applicant_name'];?>">
                                    <td  style="font-size: 14px;"><?php echo $val['father_name'];?></td>
                                    <input type="hidden" name="father_name<?php echo $val['id'];?>" value="<?php echo $val['father_name'];?>" id="father_name<?php echo $val['id'];?>">
                                    <td  style="font-size: 14px;"><?php echo $val['mobile_no'];?></td>
                                    <input type="hidden" name="mobile_no<?php echo $val['id'];?>" value="<?php echo $val['mobile_no'];?>" id="mobile_no<?php echo $val['id'];?>">
                                    <td  style="font-size: 14px;"><?php echo $val['email_id'];?></td>
                                    <input type="hidden" name="email_id<?php echo $val['id'];?>" value="<?php echo $val['email_id'];?>" id="email_id<?php echo $val['id'];?>">
                                    <td  style="font-size: 14px;"><?php echo $val['state'];?></td>
                                    <input type="hidden" name="state<?php echo $val['id'];?>" value="<?php echo $val['state'];?>" id="state<?php echo $val['id'];?>">
                                    <td  style="font-size: 14px;"><?php echo $val['district'];?></td>
                                    <input type="hidden" name="district<?php echo $val['id'];?>" value="<?php echo $val['district'];?>" id="district<?php echo $val['id'];?>">
                                    <td  style="font-size: 14px;"><?php echo $val['city'];?></td>
                                    <input type="hidden" name="city<?php echo $val['id'];?>" value="<?php echo $val['city'];?>" id="city<?php echo $val['id'];?>">

                                    <?php 
                                    //if($connection_dtls['owner_type']=='TENANT')
                                    {
                                        ?>
                                        <td style="font-size: 14px; cursor: pointer; color:blue;" onclick="edit_owner(<?php echo $val['id'];?>)" >Edit</td>
                                        <?php 
                                    }
                                    //if($connection_dtls['owner_type']=='TENANT')
                                    {
                                        ?>
                                        <td  style="font-size: 14px; cursor: pointer; color:blue;" data-toggle="modal" data-target="#myModal<?php echo $val['id'];?>">Delete</td>
                                        <?php 
                                    }
                                    ?>
                                    
                                    <input type="hidden" name="count" id="count" value="1">
                                </tr>


                                    <!-- Modal -->
                                    <div id="myModal<?php echo $val['id'];?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Delete Confirmation</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p style="color: red; ">Are You Sure want to delete this ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="delete_owner(<?php echo $val['id'];?>)">Delete</button>

                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                                            </div>

                                            </div>
                                        </div>
                                    </div>



                                    <?php
                            }
                        }
                        else
                        {
                            ?>
                            <tr>
                                <td colspan="9" style="font-size: 14px; text-align: center;" >No Data Found</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

	

        <form method="post" id="aaplicant_form" name="aaplicant_form" action="<?php echo base_url('WaterUpdateApplication/update_application')?>" >   
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Applicant Details</h3>
                </div>
                <div class="panel-body">
					<div class="row">
                        <label class="col-md-2">Applicant Name</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="applicant_name" id="applicant_name" class="form-control" onkeypress="return isAlpha(event);">
                        </div>
                        <label class="col-md-2">Guardian Name</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="guardian_name" id="guardian_name" class="form-control"  onkeypress="return isAlpha(event);">
                        </div>
					</div>
					<div class="row">
                        <label class="col-md-2">Mobile No.</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="mobile_no" id="mobile_no" class="form-control" maxlength="10" minlength="10" onkeypress="return isNum(event);">
                        </div>
                        <label class="col-md-2">Email ID</label>
                        <div class="col-md-3 pad-btm">
                            <input type="email" name="email_id" id="email_id" class="form-control">
                        </div>
                    </div>
					<div class="row">
                        <label class="col-md-2">State</label>
                        <div class="col-md-3 pad-btm">
                            <select name="state" id="state" class="form-control" onchange="show_district(this.value)">
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
                        </div>
                        <label class="col-md-2">District</label>
                        <div class="col-md-3 pad-btm">
                            <select name="district" id="district" class="form-control">
								<option value="">Select</option>
                            </select>
                        </div>
                        
                    </div>
                 
					<div class="row">
                        <label class="col-md-2">City</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="city" id="city" class="form-control"  onkeypress="return isAlpha(event);">
                        </div>
                    </div>
                  
					<div class="row">
						<input type="hidden" name="water_conn_id" id="connection_dtls" value="<?php echo $connection_dtls['id']; ?>">
						<input type="hidden" name="owner_id" id="owner_id" >
                        <?php   if($connection_dtls['doc_verify_status']==1){$display = 'style = "display:none;"';} ?>
					</div>
					<div class="row">
						<div class="col-md-5"></div>
						<input type="SUBMIT" name="add" id="add" class="col-md-2 btn btn-success" value="Add" <?=$display??null?>>
                        <?php 
                        if($connection_dtls['doc_verify_status']==1) 
                        {
                            echo"<span class='col-md-12 text-center text-warning'>Can't Update Ownere Becouse Document Verified By D.A.</span>";
                        }                        
                        ?>
					</div>
                
                </div>
            </div>
        </form>



    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
     function show_district(str)
    {      

      //  alert(str);

          $.ajax({
            type:"POST",
            url: '<?php echo base_url("tradeapplylicence/getdistrictname");?>',
            dataType: "json",
            data: {
                    "state_name":str
            },
           
            success:function(data){
             // alert(data);

             // console.log(data);
              var option ="";
              jQuery(data).each(function(i, item){
                  option += '<option value="'+item.name+'">'+item.name+'</option>';
                  console.log(item.name, item.name)
              });
              $("#district").html(option);
            //  $("[name='district[]']").html(option);
            }
               
        });

    }


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
            elec_bind_book_no:"required",
            elec_category:"required",
            elec_account_no:"required",
            elec_k_no:{required:true,digits:true},
            
           
           
        }


    });

});
</script>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {
     

    $('#aaplicant_form').validate({ // initialize the plugin
       

        rules: {

            applicant_name: {
                required: true,
                
               
            },
            
             mobile_no: {
                required: true,
                digits:true,
                minlength:10,
                maxlength:10,
              
            },
             state: {
                required: true,
              
            },
             district: {
                required: true,
              
            },
           
            
        }


    });

});

</script>

<script type="text/javascript">
  function edit_owner(argument)
  {
    
   // alert(argument);
   var applicant_name=$("#applicant_name"+argument).val();
   var father_name=$("#father_name"+argument).val();
   var email_id=$("#email_id"+argument).val();
   var mobile_no=$("#mobile_no"+argument).val();
   var state=$("#state"+argument).val();
   var district=$("#district"+argument).val();
   var city=$("#city"+argument).val();
   
   //alert("applicant_name"+argument);

   //alert(applicant_name);
  //alert(state);

   $("#applicant_name").val(applicant_name);
   $("#guardian_name").val(father_name);
   $("#email_id").val(email_id);
   $("#mobile_no").val(mobile_no);
   $("#state select").val(state);
   $("#district select").val(district);
   $("#city").val(city);
   $("#owner_id").val(argument);

  	

   $("#add").val("Update");


  }
  function show_box(property_type_id)
  {
  		var property_type_id=property_type_id;
  		if(property_type_id==1)
  		{
  			$("#box").show();
  		}
  		else
  		{
  			$("#box").hide();
  		}

  }
  function delete_owner(argument)
  {
      
      var owner_id=argument;
      //alert(owner_id);
      var water_conn_id=$("#water_conn_id").val();
      //alert(water_conn_id);
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('WaterUpdateApplicationNew/delete_owner');?>",
        data: {owner_id:owner_id,water_conn_id:water_conn_id},
        dataType: "json",
        success: function(result){
          	// alert(result);
           // console.log(result.response);
            if(result.response==true)
            {
               	
            	
                location.reload(true); 
                
            }
            else
            {
               alert('You should have atleast one Owner');
               // location.reload(true); 
                //break;

            }
        }
    });

  }
</script>
