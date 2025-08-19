<style >
.error
{
    color: red;
}
</style>
<?=$this->include("layout_mobi/header");?>

<script type="text/javascript">
  function OperateDropDown(radio, control, hidden) {

        /*alert(radio);
        alert(control);
        alert(hidden);*/



        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        //alert(hid_val);
        //alert(rdo.value);
        //alert(ctrl);

        if (rdo.value == "1") {
            var opt = ctrl.options;
            var pos = 0;
            for (var j = 0; j < opt.length; j++) {
                if (opt[j].value == hid_val) {
                    pos = j;
                    break;
                }
            }
            ctrl.selectedIndex = pos;
            ctrl.disabled = true;
        }
        else {
            ctrl.selectedIndex = 0;
            ctrl.disabled = false;
        }
    }


    function OperateTexBox(radio, control, hidden) {

        /*alert(radio);
        alert(control);
        alert(hidden);*/

     
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;

        // alert(rdo.value);
        // alert(control);
        // alert(hid.value);

        
        if (rdo.value == "1") {
            ctrl.value = hid_val;
        

            ctrl.readOnly = true;
        }
        else {

            
        	

            ctrl.value = "";
            ctrl.readOnly = false;
            ctrl.disabled = false;
            
        }


    }

  
  
</script>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
	<div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Water Application Site Inspection</h3>
			</div>
			<form action="<?php echo base_url('WaterFieldVerification/view_site_inspetion');?>" id="form_tc_verification" name="FORMNAME1" method="post">

				<div class="panel-body">
					
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is : </span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$connection_dtls["application_no"];?></span>
							</div>
							<input type="hidden" name="application_no" id="application_no" value="<?php echo $connection_dtls['application_no']; ?>">
							<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $connection_dtls['id'];?>">
							<input type="hidden" name="site_inspection_id" id="site_inspection_id" value="<?php echo $site_inspection_id;?>">
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;">Applied Date :  </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=date('d-m-Y',strtotime($connection_dtls['apply_date']));?></span>
								<input type="hidden" name="apply_date" id="apply_date" value="<?php echo date('d-m-Y',strtotime($connection_dtls['apply_date']));?>">
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Ward No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['ward_no']; ?>
							
						</h3>
					</div>
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_ward_mstr_id_t" id="corr_ward_mstr_id_t" value="1" onClick="OperateDropDown('corr_ward_mstr_id_t', 'ward_id', 'ward_mstr_id_v')" <?=(isset($corr_ward_mstr_id_t))?($corr_ward_mstr_id_t==1)?"checked":"":"";?>>   Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_ward_mstr_id_t" id="corr_ward_mstr_id_f" value="0" onClick="OperateDropDown('corr_ward_mstr_id_f', 'ward_id', 'ward_mstr_id_v')" <?=(isset($corr_ward_mstr_id_t))?($corr_ward_mstr_id_t==0)?"checked":"":"";?>>   InCorrect
							</div>
							<input type="hidden" name="ward_mstr_id_v" id="ward_mstr_id_v" value="<?php echo $connection_dtls['ward_id']; ?>">
							<input type="hidden" name="sa_ward_no" id="sa_ward_no" value="<?php echo $connection_dtls['ward_no']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($ward_list)
									{
										foreach($ward_list as $val)
										{
									?>
									<option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){echo "selected";}?>><?php echo $val['ward_no'];?></option>
									<?php  
										}  
									}
									?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Property Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['property_type']; ?></h3>

					</div>
					<div class="panel-body">	
						<div class="row">
							<input type="hidden" name="sa_property_type" id="sa_property_type" value="<?php echo $connection_dtls['property_type']; ?>" >
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_property_type_t" id="corr_property_type_t" value="1" onClick="OperateDropDown('corr_property_type_t', 'property_type_id', 'property_type_id_v')"  <?=(isset($corr_property_type_t))?($corr_property_type_t==1)?"checked":"":"";?>>  Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_property_type_t" id="corr_property_type_f" value="0" onClick="OperateDropDown('corr_property_type_f', 'property_type_id', 'property_type_id_v')"  <?=(isset($corr_property_type_t))?($corr_property_type_t==0)?"checked":"":"";?>>  InCorrect
							</div>
							<input type="hidden" name="property_type_id_v" id="property_type_id_v" value="<?php echo $connection_dtls['property_type_id']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<select name="property_type_id" id="property_type_id" class="form-control" onchange="show_flat_count(this.value)">
									<option value="">Select</option>
									<?php
									if($property_type_list)
									{
										foreach($property_type_list as $val)
										{
									?>
									<option value="<?php echo $val['id'];?>" <?php if($property_type_id==$val['id']){echo "selected";}?>><?php echo $val['property_type'];?></option>
									<?php  
										}  
									}
									?>
								</select>
							</div>



							<div id="flat_count_box" style="display: none;">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
								<div class="col-md-3 pad-btm">
									<input type="text" name="flat_count" id="flat_count" class="form-control" value="<?php echo isset($flat_count)?$flat_count:""; ?>"  onkeypress="return isNum(event);"   placeholder="Enter No. of Flats">
								</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Pipeline Type  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['pipeline_type']; ?>
							</h3>
					</div>
					<div class="panel-body">	
						<div class="row">
							<input type="hidden" name="sa_pipeline_type" id="sa_pipeline_type" value="<?php echo $connection_dtls['pipeline_type']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_pipeline_type_t" id="corr_pipeline_type_t" value="1" onClick="OperateDropDown('corr_pipeline_type_t', 'pipeline_type_id', 'pipeline_type_id_v')" <?=(isset($corr_pipeline_type_t))?($corr_pipeline_type_t==1)?"checked":"":"";?>>  Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_pipeline_type_t" id="corr_pipeline_type_f" value="0" onClick="OperateDropDown('corr_pipeline_type_f', 'pipeline_type_id', 'pipeline_type_id_v')" <?=(isset($corr_pipeline_type_t))?($corr_pipeline_type_t==0)?"checked":"":"";?>>  InCorrect
							</div>
							<input type="hidden" name="pipeline_type_id_v" id="pipeline_type_id_v" value="<?php echo $connection_dtls['pipeline_type_id']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<select name="pipeline_type_id" id="pipeline_type_id" class="form-control">
									<option value="">Select</option>
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
			
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Connection Type  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['connection_type']; ?>
							</h3>
					</div>
					<div class="panel-body">	
						<div class="row">
							<input type="hidden" name="sa_connection_type" id="sa_connection_type" value="<?php echo $connection_dtls['connection_type']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_connection_type_t" id="corr_connection_type_t" value="1"  onClick="OperateDropDown('corr_connection_type_t', 'connection_type_id', 'corr_connection_type_v')"  <?=(isset($corr_connection_type_t))?($corr_connection_type_t==1)?"checked":"":"";?>>  Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_connection_type_t" id="corr_connection_type_f" value="0"  onClick="OperateDropDown('corr_connection_type_f', 'connection_type_id', 'corr_connection_type_v')" <?=(isset($corr_connection_type_t))?($corr_connection_type_t==0)?"checked":"":"";?>>  InCorrect
							</div>
							<input type="hidden" name="corr_connection_type_v" id="corr_connection_type_v" value="<?php echo $connection_dtls['connection_type_id']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<select name="connection_type_id" id="connection_type_id" class="form-control">
									<option value="">Select</option>
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
						</div>
					</div>
				</div> 
				<input type="hidden" name="connection_through_id" id="connection_through_id" value="<?php echo $connection_dtls['connection_through_id']; ?>">
				<!--	<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Connection Through  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['connection_through']; ?>
							</h3>
					</div>
					<div class="panel-body">	
						<div class="row">
							<input type="hidden" name="sa_connection_through" id="sa_connection_through" value="<?php echo $connection_dtls['connection_through']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_connection_through" id="corr_connection_through_t" value="1" onClick="OperateDropDown('corr_connection_through_t', 'connection_through_id', 'connection_through_v')" <?=(isset($corr_connection_through))?($corr_connection_through==1)?"checked":"":"";?>>  Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_connection_through" id="corr_connection_through_f" value="0" onClick="OperateDropDown('corr_connection_through_f', 'connection_through_id', 'connection_through_v')" <?=(isset($corr_connection_through))?($corr_connection_through==0)?"checked":"":"";?>>  InCorrect
							</div>
							<input type="hidden" name="connection_through_v" id="connection_through_v" value="<?php echo $connection_dtls['connection_through_id']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<select name="connection_through_id" id="connection_through_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($conn_through_list)
									{
										foreach($conn_through_list as $val)
										{
									?>
									<option value="<?php echo $val['id'];?>" <?php if($connection_through_id==$val['id']){echo "selected";}?>><?php echo $val['connection_through'];?></option>
									<?php  
										}  
									}
									?>
								</select>
							</div>
						</div>
					</div>
				</div> -->



				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Category  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['category']; ?>
							</h3>
					</div>
					<div class="panel-body">	
						<div class="row">
							<input type="hidden" name="sa_category" id="sa_category" value="<?php echo $connection_dtls['category']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_category" id="corr_category_t" value="1"  onClick="OperateDropDown('corr_category_t', 'category', 'corr_category_v')" <?=(isset($corr_category))?($corr_category==1)?"checked":"":"";?>>  Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_category" id="corr_category_f" value="0"  onClick="OperateDropDown('corr_category_f', 'category', 'corr_category_v')" <?=(isset($corr_category))?($corr_category==0)?"checked":"":"";?>>  InCorrect
							</div>
							<input type="hidden" name="corr_category_v" id="corr_category_v" value="<?php echo $connection_dtls['category']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<select name="category" id="category" class="form-control">
									<option value="">Select</option>
									<option value="APL" <?php if($category=="APL"){echo "selected";}?>>APL</option>
									<option value="BPL" <?php if($category=="BPL"){echo "selected";}?>>BPL</option>
								</select>
							</div>
						</div>
					</div>
				</div> 
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Area of Plot(in Sqft)  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Self Assessed: <?php echo $connection_dtls['area_sqft']; ?>
							</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_area_sqft_t" id="corr_area_sqft_t" value="1"  onClick="OperateTexBox('corr_area_sqft_t', 'area_sqft', 'corr_area_sqft_v')" <?=(isset($corr_area_sqft_t))?($corr_area_sqft_t==1)?"checked":"":"";?>>  Correct
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="radio" name="corr_area_sqft_t" id="corr_area_sqft_f" value="0"  onClick="OperateTexBox('corr_area_sqft_f', 'area_sqft', 'corr_area_sqft_v')" <?=(isset($corr_area_sqft_t))?($corr_area_sqft_t==0)?"checked":"":"";?>>  InCorrect
							</div>
							<input type="hidden" name="corr_area_sqft_v" id="corr_area_sqft_v" value="<?php echo $connection_dtls['area_sqft']; ?>">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="text" name="area_sqft" id="area_sqft" class="form-control" value="<?php echo isset($area_sqft)?$area_sqft:"";?>">
							</div>
						</div>
					</div>
				</div> 


				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Distribution Pipeline Size (In MM)  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
						</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<input type="text" name="pipeline_size" id="pipeline_size" class="form-control" <?=(isset($pipeline_size))?$pipeline_size:"";?>> 
							</div>
							
						</div>
					</div>
				</div> 
				
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Distribution Pipeline Size Type  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								
								<input type="radio"   name="pipeline_size_type" id="pipe_type_ci"  value="CI"  <?=(isset($pipeline_size_type))?($pipeline_size_type=='CI')?"checked":"":"";?> />&nbsp;CI
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name="pipeline_size_type" id="pipe_type_di" value="DI"  <?=(isset($pipeline_size_type))?($pipeline_size_type=='DI')?"checked":"":"";?> />&nbsp;DI
							</div>
							
						</div>
					</div>
				</div> 
				
				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Permissible Pipe Diameter  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								
							<input type="radio"   name="permissible_pipe_dia" id="permissible_pipe_dia_15" value="15 MM"  <?=(isset($permissible_pipe_dia))?($permissible_pipe_dia=="15 MM")?"checked":"":"";?> />&nbsp;15 MM
							&nbsp;&nbsp;
							<input type="radio"   name="permissible_pipe_dia" id="permissible_pipe_dia_20" value="20 MM"  <?=(isset($permissible_pipe_dia))?($permissible_pipe_dia=="20 MM")?"checked":"":"";?> />&nbsp;20 MM
							&nbsp;&nbsp;
							<input type="radio"   name="permissible_pipe_dia" id="permissible_pipe_dia_25" value="25 MM" <?=(isset($permissible_pipe_dia))?($permissible_pipe_dia=="25 MM")?"checked":"":"";?> />&nbsp;25 MM
							</div>
							
						</div>
					</div>
				</div> 



				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Permissible Pipe Quality &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								
							<input type="radio"   name="permissible_pipe_qlty" id="permissible_pipe_qlty_gi" value="GI"   <?=(isset($permissible_pipe_qlty))?($permissible_pipe_qlty=="GI")?"checked":"":"";?>  />&nbsp;GI
									&nbsp;&nbsp;
									<input type="radio"   name="permissible_pipe_qlty" id="permissible_pipe_qlty_hdpe" value="HDPE" <?=(isset($permissible_pipe_qlty))?($permissible_pipe_qlty=="HDPE")?"checked":"":"";?>  />&nbsp;HDPE
									&nbsp;&nbsp;
									<input type="radio"   name="permissible_pipe_qlty" id="permissible_pipe_qlty_pvc" value="PVC 80" <?=(isset($permissible_pipe_qlty))?($permissible_pipe_qlty=="PVC 80")?"checked":"":"";?>   />&nbsp;PVC 80



							</div>
							
						</div>
					</div>


				</div> 



				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Permissible Ferule Size &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								
								<select name="ferrule_type_id" id="ferrule_type_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($ferrule_list)
									{

										foreach($ferrule_list as $val)
										{
									?>
									<option value="<?php echo $val['id'];?>" <?php if(isset($ferrule_type_id) and $ferrule_type_id==$val['id']){ echo "selected"; } ?>><?php echo $val['ferrule_type'];?></option>
									<?php  
										}  
									}
									?>
								</select>

									
							</div>
							
						</div>
					</div>
				</div> 


				<div class="panel panel-bordered">
					<div class="panel-heading" style="background:#1b8388f7;">
						<h3 class="panel-title">Road Type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						</h3>
					</div>
					
					<div class="panel-body">	
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
									
									<input type="radio"  name="road_type" id="rmc" value="1"  <?=(isset($road_type))?($road_type=="1")?"checked":"":"";?>  />
									&nbsp;RMC
									&nbsp;&nbsp;
									<input type="radio"   name="road_type" id="pwd" value="0"  <?=(isset($road_type))?($road_type=="0")?"checked":"":"";?> />&nbsp;PWD
									
									
							</div>
							
						</div>
					</div>
				</div> 

				<div class="panel-body">
					<div class="row">
						<center><input type="submit" name="Proceed" value="Proceed" id="Proceed" class="btn btn-success"></center>
						<center id="wait" style="color:red; display:none" >Please wait, your request is being processed...</center>
					</div>
				</div>

			</form>
        </div>
       
    </div>
</div>



<?= $this->include('layout_mobi/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 


<script>
    
  


    $(document).ready(function () 
    {

        var rdo_ward_id=$("#corr_ward_mstr_id_t").is(":checked");
        var rdo_pipeline_type=$("#corr_pipeline_type_t").is(":checked");
        var rdo_connection_type=$("#corr_connection_type_t").is(":checked");
        var rdo_connection_through=$("#corr_connection_through_t").is(":checked");
        var rdo_category=$("#corr_category_t").is(":checked");
        var rdo_area_sqft=$("#corr_area_sqft_t").is(":checked");
        var rdo_property_type=$("#corr_property_type_t").is(":checked");

        //alert(rdo_connection_through);

        // var rdo_connection_through=$("#corr_connection_through").val();
        //alert(rdo_connection_through);
        if(rdo_ward_id==true)
        {
            $("#ward_id").attr("disabled",true);
        }
        if(rdo_pipeline_type==true)
        {
            $("#pipeline_type_id").attr("disabled",true);
        }
        if(rdo_connection_type==true)
        {
            $("#connection_type_id").attr("disabled",true);
        }
        if(rdo_connection_through==true)
        {
            $("#connection_through_id").attr("disabled",true);
        }
        if(rdo_category==true)
        {
            $("#category").attr("disabled",true);
        }
        if(rdo_area_sqft==true)
        {
            $("#area_sqft").attr("disabled",true);
        }
        if(rdo_property_type==true)
        {
            $("#property_type_id").attr("disabled",true);
        }


		$('#form_tc_verification').validate({ // initialize the plugin
		

			rules: {
				"pipeline_type_id": {
					required: true,
					
				},
				"property_type_id": {
					required: true,
				
				},
				"connection_type_id": {
					required: true,
				
				},
				
				"category": {
					required: true,
				
				},
				"ward_id": {
					required: true,
				
				},
				"area_sqft": {
					required: true,
				
				},
				"pipeline_size": {
					required: true,
				
				}, 
				"pipeline_size_type": {
					required: true,
				
				},
				"permissible_pipe_dia": {
					required: true,
				
				},
				"permissible_pipe_qlty": {
					required: true,
				
				},
				"ferrule_type_id": {
					required: true,
				
				},
				"road_type": {
					required: true,
				
				},
				"corr_ward_mstr_id_t":{
					required: true,
				
				},
				"corr_property_type_t":{
					required: true,
				
				},
				"corr_pipeline_type_t":{
					required: true,
				
				},
				"corr_connection_type_t":{
					required: true,
				
				},
				"corr_connection_through":{
					required: true,
				
				},
				"corr_category":{
					required: true,
				
				},
				"corr_area_sqft_t":{
					required: true,
				
				},
				
			}


		});

		$('#Proceed').click('on',function(){
			
			if($('#form_tc_verification').valid())
			{
				$('#Proceed').hide();							
				$('#wait').show();				
			}
			else
			{
				$('#Proceed').show();
				$('#wait').hide();
			}
			
		});


});


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
</script>
