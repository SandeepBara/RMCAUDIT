<?= $this->include('layout_home/header');?>
<style type="text/css">
    .menu_panel_hover:hover {
        background-color: #e0e5ea;
        cursor: pointer;
    }
    .error
    {
        color:red ;
    }

	.row{line-height:25px;}

</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Grievance Management System</h3>
            </div>
			
			<?php 
			if($grievance_sts)
			{
				?>
				<div class="panel-body">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading" style="background-color: #1eb9bf;">
							<div class="panel-control">
								<b>Token No.  </b>
								<b style="color:red;">
									<?=$grievance_sts['token_no']?$grievance_sts['token_no']:"N/A"; ?>
								</b>
							</div>
							<h3 class="panel-title"> Details </h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2">
									<b>Unique No.</b>
								</div>
								<div class="col-sm-3">
									<?=$grievance_sts['unique_no']?$grievance_sts['unique_no']:"N/A"; ?>
								</div>
								
								<div class="col-sm-2">
									<b>Module</b>
								</div>
								<div class="col-sm-3">
									<?=$grievance_sts['module']?$grievance_sts['module']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Ward No.</b>
								</div>
								<div class="col-md-3">
									<?=$grievance_sts['ward_no']?$grievance_sts['ward_no']:"N/A"; ?>
								</div>
								
								<div class="col-md-2">
									<b>Grievance Type</b>
								</div>
								<div class="col-md-3">
									<?=$grievance_sts['grievance']?$grievance_sts['grievance']:"N/A"; ?>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2">
									<b>Mobile No.</b>
								</div>
								<div class="col-md-3">
									<?=$grievance_sts['mobile_no']?$grievance_sts['mobile_no']:"N/A"; ?>
								</div>
								
								<div class="col-md-2">
									<b>Submit Document</b>
								</div>
								<div class="col-md-2">
									<a href="<?=base_url();?>/writable/uploads/<?=$grievance_sts['doc_path'];?>" target="_blank">
										<img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
									</a>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Query</b>
								</div>
								<div class="col-md-10">
									<span style="color:red;"><?=$grievance_sts['query']?$grievance_sts['query']:"N/A"; ?></span>
								</div>
							</div>
							<br>
							<div class="col-md-12">
								<b style="color:red;">LEVEL</b>
							</div>
							<hr>
							<?php
								if($grievance_chat):
								$i=1;
									foreach($grievance_chat as $val):
							?>
							<div class="row">
								<div class="col-md-1">
									<b style="color:blue;float: right;"><?=$i++; ?>.</b>
								</div>
								<div class="col-md-4">
									<?php if($val['to_user_type_id']==11){ ?>
										<b style="color:red;">Back Office | <span style="color:green;"> <?=$val['emp_name']." ".$val['middle_name']." ".$val['last_name'];?> </span>  </b>
									<?php }else if($val['to_user_type_id']==4){ ?>
										<b style="color:red;">Team Leader | <span style="color:green;"> <?=$val['emp_name']." ".$val['middle_name']." ".$val['last_name'];?> </span>  </b>
									<?php }else if($val['to_user_type_id']==3){ ?>
										<b style="color:red;">Project Manager | <span style="color:green;"> <?=$val['emp_name']." ".$val['middle_name']." ".$val['last_name'];?> </span>  </b>
									<?php } ?>
								</div>
								<div class="col-md-7">
									<b style="color:green;"><?=$val['remarks']?$val['remarks']:"N/A"; ?></b>
								</div>
							</div>
							<?php
									endforeach;
								endif;
							?>
						</div>
					</div>
				</div>
				<?php 
			}
			else
			{
				?>
			
				<?php 
				if($message)
				{
					?>
					<div class="panel-body">
						<div class="col-md-6 col-md-offset-3  bg-primary pad-ver">
							<span style="font-weight:bold; font-size:18px;text-align:center;"> <?=$message?$message:"N/A"; ?></span>
						</div>
					</div>
					<?php 
				}
				else
				{
					?>
					<?php 
					if($token_no)
					{
						?>
						<div class="panel-body">
							<div class="col-md-6 col-md-offset-3  bg-primary pad-ver">
								<label class="col-md-4 text-center" style="font-weight:bold; font-size:18px;"><b>Token No. </b></label>
								<span style="font-weight:bold; font-size:18px;text-align:center;"> : <?=$token_no?$token_no:"N/A"; ?></span>
							</div>
						</div>
						<?php 
					}
					else
					{
						?>
						<div class="panel-body">
							<div class="col-md-6 col-md-offset-3  bg-gray-light pad-ver">
								<div class="row" style="background-color:#25476a; border-radius:5px;">
									<div class="col-md-12" style="margin-top:10px;color:#fff;">
										<label class="col-md-4"><b>Grievance Type: </b><span class="text-danger">*</span></label>
										<div class="col-md-8 pad-btm">
											<select name="grievance_type" id="grievance_type" class="form-control" onchange="showHideDiv(this.value)">
												<option value="">SELECT</option>
												<option value="Query">Query</option>
												<option value="Complain">Complain</option>
												<option value="AppStatus">View Application Status</option>
											</select>
										</div>
									</div>
								</div>
							

								<div id="query_box" style="display: none;margin-top:10px;">
									<form method="post" enctype="multipart/form-data" id="formqueryValidate" action="<?php echo base_url('grievance/grievance_query/');?>">
										<input type="hidden" name="grievance_type" id="grievance_type" value="Query">
										<div class="row">
										<div class="col-md-12">
												<label class="col-md-4"><b>Query Detail: </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
												<textarea name="querys" id="querys" class="form-control"></textarea>
												</div>
										</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Mobile No. : </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
												<input type="tel" name="mobile_nos" id="mobile_nos" class="form-control" maxlength="10"/>
												</div>
											</div>
										</div>
										
										<div class="row text-center">
											<div class="col-md-4"></div>
											<div class="col-md-6">
											<input type="submit" name="querySave" id="querySave" value="Enquiry" class="form-control bg bg-info">
											</div>
										</div>
									</form>
								</div>
								
								<div id="complain_box" style="display: none;margin-top:10px;">
									<form method="post" enctype="multipart/form-data" id="formValidate" action="<?php echo base_url('grievance/grievance_insrt/');?>">
										<input type="hidden" name="grievance_type" id="grievance_type" value="Complain">
										<div class="row">
											<div class="col-md-12">
											<label class="col-md-4"><b>Module: </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
													<select name="module" id="module" class="form-control" onchange="showSearchBox(this.value)">
														<option value="">SELECT</option>
														<option value="Holding">Holding</option>
														<option value="SAF">SAF</option>
														<option value="Water Connection">Water Connection</option>
														<option value="Water Consumer">Water Consumer</option>
														<option value="Trade Application">Trade Application</option>
														<option value="Trade License">Trade License</option>
													</select>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Select Grievance: </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm" id="grievance_list">
													<select name="grievance_id" id="grievance_id" class="form-control" >
														<option value="">Please Choose Module</option>
													</select>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Ward No. <span class="text-danger">*</span></b></label>
												<div class="col-md-8 pad-btm">
												<select name="ward_id" id="ward_id" class="form-control">
													<option value="">SELECT</option>
													<?php
															if($ward_list):
																foreach($ward_list as $val):
													?>
															<option value="<?php echo $val['id']; ?>"><?php echo $val['ward_no']; ?></option>
													<?php
																endforeach;
															endif;
													?>
												</select>
												</div>
											</div>
										</div>

										<div class="row" id="search_div" style="display: none;">
											<div class="col-md-12">
												<label class="col-md-4" id="search_text" style="font-weight: 800;"> <span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
												<input type="text" name="search" id="search" class="form-control" onblur="validateSearchBox()">
												</div>
											</div>
											<div class="col-md-12">
												<label class="col-md-4" id="search_name" style="font-weight: 800;"> <span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
													<label id="search_txtname" style="font-weight: 800;"> </label>
												</div>
											</div>
										</div>
									
										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Query Detail: </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
												<textarea name="query" id="query" class="form-control"></textarea>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Mobile No. : </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
													<input type="text" name="mobile_no" id="mobile_no" class="form-control" maxlength="10" />
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Attachment : </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
												<input type="file" name="upload_file" id="upload_file" class="form-control"  value="" accept=".png, .jpg, .jpeg, .pdf" />
												</div>
											</div>
										</div>
										
										<div class="row text-center">
											<div class="col-md-4"></div>
											<div class="col-md-6">
											<input type="submit" name="complainSave" id="complainSave" value="Complain" style="font-weight: 800;" class="form-control bg bg-danger">
											</div>
										</div>
									</form>
								</div>

								<div id="application_status" style="display: none;margin-top:10px;">
									<form method="post" enctype="multipart/form-data" id="formstatusValidate" action="<?php echo base_url('grievance/grievance_status/');?>">
										<div class="row">
											<div class="col-md-12">
												<label class="col-md-4"><b>Token No. : </b><span class="text-danger">*</span></label>
												<div class="col-md-8 pad-btm">
												<input type="text" name="token_no" id="token_no" class="form-control">
												</div>
											</div>
										</div>
										
										<div class="row text-center">
											<div class="col-md-4"></div>
											<div class="col-md-6">
											<input type="submit" name="view_status" id="view_status" value="View Status" class="form-control bg bg-info">
											</div>
										</div>
									</form>
								</div>
								</div>
							</div>
						</div>
						<?php 
					}
				}
			}
			?>
			
        </div>                
    </div>
    <!--End page content-->
	</div>
	</div>
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
    $(document).ready(function(){

       showHideDiv($("#grievance_type").val());

		$("#formqueryValidate").validate({
            rules:{
                "grievance_type":"required",
                "querys":"required",
				"mobile_nos": {required: true, minlength: 10, digits: true}
            },
			messages: {
				grievance_type: 'This field is required',
				querys: 'Please type your query',
				mobile_nos: 'Please Enter Your Mobile No',
			},
            
        });

		$("#formValidate").validate({
            rules:{
                "grievance_type":"required",
                "module":"required",
				"grievance_id": "required",
				"ward_id":"required",
                "search":"required",
				"query":"required",
                "mobile_no": {required: true, minlength: 10, digits: true},
                "upload_file": "required",
                
				
                "search_name":"required"
            },
			messages: {
				grievance_type: 'This field is required',
				module: 'Please Choose Module',
				grievance_id: 'Please Choose Grievance',
				ward_id: 'Please Choose Ward No',
				search: 'This field is required',
				query: 'Please Type Your Query',
				mobile_no: 'Please Enter Your Mobile No',
				upload_file: 'Please Upload File',
				
				
				search_name: 'This field is required',
			},
            
        });

    });

    function showHideDiv(grievance_type)
    {
         $("#complain_box").hide();
		 $("#query_box").hide();
		 $("#application_status").hide();

        if(grievance_type=='Query')
        {
			$("#query_box").show();
            $("#complain_box").hide();
			$("#application_status").hide();
        }
        else if(grievance_type=='Complain')
        {
            $("#complain_box").show();
			$("#query_box").hide();
			$("#application_status").hide();
        }
		else if(grievance_type=='AppStatus')
        {
            $("#complain_box").hide();
			$("#query_box").hide();
			$("#application_status").show();
        }
    }

    function showSearchBox(module_type)
    {
        $("#search_div").hide();
        //alert(module_type);
        if(module_type=='Holding')
        {
            $("#search_div").show();
            $("#search_text").html('Holding No.: <span class="text-danger">*</span>');
			$("#search_name").html('Owner Name: <span class="text-danger">*</span>');
        }
        else if(module_type=='SAF')
        {
            $("#search_div").show();
            $("#search_text").html('SAF No.: <span class="text-danger">*</span>');
			$("#search_name").html('Applicant Name: <span class="text-danger">*</span>');
        }
		else if(module_type=='Water Consumer')
        { 
            $("#search_div").show();
            $("#search_text").html('Consumer No.: <span class="text-danger">*</span>');
			$("#search_name").html('Consumer Name: <span class="text-danger">*</span>');
        }
        else if(module_type=='Trade License')
        {
            $("#search_div").show();
            $("#search_text").html('License No.: <span class="text-danger">*</span>');
			$("#search_name").html('License Owner Name: <span class="text-danger">*</span>');
        }
        else if(module_type=='Water Connection') // || argument=='Trade Application'
        {
            $("#search_div").show();
            $("#search_text").html('Application No.: <span class="text-danger">*</span>');
			$("#search_name").html('Applicant Name: <span class="text-danger">*</span>');
        }
        

        showGrievanceList(module_type);
        validateSearchBox();
    }

    function validateSearchBox()
    {   
        var module_type=$("#module").val();
        var search_box=$("#search").val();
        var ward_id=$("#ward_id").val();
        
        if(module_type && search_box)
        {
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("grievance/validateSearchBox");?>',
                dataType: "json",
                data: {
                        "module":module_type,"ward_id":ward_id,"search_box":search_box
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success:function(data){
					console.log(data);
                    $("#loadingDiv").hide();
					//alert(JSON.parse(data));
					if(data.response==true)
                    {
						$("#search_txtname").html(data.result);
					}
                }

            });
        }
        
    }

   function showGrievanceList(module_type)
   {    
        if(module_type)
        {   
            //alert(module_type);
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("grievance/getGrievanceList");?>',
                dataType: "json",
                data: {
                        "module":module_type
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success:function(data){
                    $("#loadingDiv").hide();
                    //console.log(data.result);
                    if(data.response==true)
                    {
                        var select="<select name='grievance_id' id='grievance_id' class='form-control'><option value=''>SELECT</option>";
                        for(var k in data.result)
                        {
                            select+="<option value='"+data.result[k]['id']+"'>"+data.result[k]['grievance']+"</option>";
                        }
                        select+="</select>";

                        $("#grievance_list").html(select);
                    }
                   
                }

            });
        }

   }


   function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }
</script>