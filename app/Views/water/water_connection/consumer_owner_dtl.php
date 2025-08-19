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
		if(isset($_SESSION['msg']))
        {
            ?>
			<p class="bg bg-danger form-control text-center"><?php echo $_SESSION['msg']; unset($_SESSION['msg']);?></p>
		    <?php 
        } 
        ?>
		<form id="form" name="form" method="post"  enctype="multipart/form-data" >
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
                    <div class="panel-control">
                        
                    </div>
                    <h3 class="panel-title">Update Water Consumer Detatils Form
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
                            <div>
                                <label class="col-md-2">No. of Flats<span class="text-danger">*</span></label>
                                <div class="col-md-3 pad-btm">
                                <strong>
                                    <?=$consumer_details['flat_count'];?> 
                                </strong>
                            </div> 
                            </div>						
                	    </div>

					</div>
					
					<div class="row">


						<div>
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
					
                    <div class="panel panel-bordered panel-dark">
                        <input type="checkbox" name="address_check_box" id="address_check_box" onclick="read_only_remove('address_check_box','address_block')" <?=isset($address_check_box) && $address_check_box ? 'checked':'';?> />
                        <div class="row" id ="address_block">
                            <label class="col-md-2">Ward No
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-3 pad-btm">
                                <select name="ward_mstr_id" id="ward_mstr_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    foreach($ward_list as $val)
                                    {
                                        ?>
                                            <option value="<?=$val['id'];?>" <?=$val['id']==$consumer_details['ward_mstr_id']?"selected":"";?>><?=$val['ward_no']?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-md-2">Address
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-5 pad-btm">
                                <textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);"  ><?php echo isset($consumer_details['address'])?$consumer_details['address']:"";?></textarea>
                            </div>
                        </div>

                    </div>
					<!-- <div class="row" id ="address_block">
                        <label class="col-md-2">Address
                            <span class="text-danger">*</span>
                            <input type="checkbox" name="address_check_box" id="address_check_box" onclick="read_only_remove('address_check_box','address_block')" <?=isset($address_check_box) && $address_check_box ? 'checked':'';?> />
                        </label>
						<div class="col-md-10 pad-btm">
							<textarea name="address" id="address" class="form-control"  onkeypress="return isAlphaNumCommaSlash(event);"  ><?php echo isset($consumer_details['address'])?$consumer_details['address']:"";?></textarea>
						</div>
                    </div> -->
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
                                        </tr>                     
                                    <?php	
                            	}
                                ?>
                                </tbody>
                                
                        </table>
                    </div>
					<div id="owner_append"></div>				
			</div>
            
            <div class="panel">
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
                    <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary"  >SUBMIT</button>
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
                
                address:{
                    required: function(element) {
                        return $('#address_check_box').is(':checked')
                    }
                },
                'document':{
                    required: function(element) {
                        if($('#owner_check_box').is(':checked') || $('#address_check_box').is(':checked'))
                        {
                            return true;
                        }
                        return false;
                    },
                },
                'remarks':{
                    required: function(element) {
                        if($('#owner_check_box').is(':checked') || $('#address_check_box').is(':checked'))
                        {
                            return true;
                        }
                        return false;
                    },
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
                    maxlength: function(element) {
                        if($('#owner_check_box').is(':checked')) {
                            return 10;
                        }
                    },
                    minlength: function(element) {
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

        read_only_remove('address_check_box', 'address_block');              
        
        read_only_remove('owner_check_box','owner_block');
       
        $("#holding_no").keypress(function () {
    
            $("#btn_review").attr('disabled', true);
    
        });

        $("#saf_no").keypress(function () {
    
            $("#btn_review").attr('disabled', true);
    
        });

    });

</script>


<script type="text/javascript">
	
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
        if( ch==true)
        {
           var dd = $("#"+ block_name).find("select, textarea, input").attr('readonly',false);
           console.log(dd);
           $("#"+ block_name).find("select").attr('disabled',false);
           $("#"+ block_name).find("input:radio").attr('disabled',false);
           if(type=='owner_check_box')
           {
                var i = $('#count').val();
                for(i;i>=0;i--)
                {
                    $("#owner_name"+i).attr('readonly',true);                    
                   
                }
               
           }
           
        }
        else
        {
           
            $("#"+ block_name ).find("select, textarea, input").attr('readonly',true);
            $("#"+ block_name).find("select").attr('disabled',true);
            $("#"+ block_name).find("input:radio").attr('disabled',true);
        }
        
    }
</script>