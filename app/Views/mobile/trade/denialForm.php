<?=$this->include("layout_mobi/header");?>
<style>
	.error{
		color:red;
	}
</style>
	<div id="content-container">
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
				<div class="panel-control">
					<a href="<?php echo base_url('Mobi/mobileMenu/trade') ?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
				</div>
					<h5 class="panel-title"><b>Apply For Denial</b></h5>
				</div>
				<div class="panel-body">
				<form id="formname" name="form" method="post"  enctype="multipart/form-data">
				<?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>
				      <div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="firm_Name"><b>Firm Name<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="firm_Name" name="firm_Name" class="form-control" placeholder="Enter Firm Name" value="">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="owner_name"><b>Owner Name </b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="owner_name" name="owner_name" class="form-control" onkeypress="return isAlpha(event);" placeholder="Enter Owner Name" value="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="ward_no"><b>Ward No.<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-3 pad-btm">
								<select name="new_ward_id" id="new_ward_id"  class="form-control" onchange="validate_holding()">
									<option value="">Select</option>
									<?php
									if($ward_list)
									{
										foreach($ward_list as $val)
										{
									?>
									<option value="<?php echo $val['id'];?>" ><?php echo $val['ward_no'];?></option>
									<?php 
										} 
									}
									?>
                               </select>
								</div>
								<div class="col-md-3">
									<label class="control-label" for="holding_no"><b>Holding No. (if any)</b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="holding_no" name="holding_no" class="form-control" placeholder="Enter Holding No. (if any)" onblur="validate_holding()">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="address"><b>Address<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-3">
									<textarea type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value=""></textarea>
								</div>
								<div class="col-md-3">
									<label class="control-label" for="landmark"><b>Landmark<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="landmark" name="landmark" class="form-control" placeholder="Enter Landmark" value="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="city"><b>City<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="city" name="city" onkeypress="return isAlpha(event);" class="form-control" placeholder="Enter City" value="">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="pin_code"><b>Pin Code<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="pin_code" name="pin_code" maxlength="6" minlength="6" onkeypress="return isNum(event);" class="form-control" placeholder="Enter Pin Code" value="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="licence_no"><b>License No. (if any)</b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="licence_no" name="licence_no" class="form-control" placeholder="Enter License No. (if any)" onblur="validate_licence_no()">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="mobile_no"><b>Mobile No. (if any)</b> </label>
								</div>
								<div class="col-md-3">
									<input type="text" id="mobileno" name="mobileno" maxlength="10" minlength="10" onkeypress="return isNum(event);" class="form-control" placeholder="Enter Mobile No. (if any)" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="remarks"><b>Remarks<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-9">
									<textarea id="remarks" name="remarks" class="form-control" placeholder="Enter Remarks" value=""></textarea>
								</div>
							</div>

							<input type="hidden" name="ipaddress" value="<?php echo getenv("REMOTE_ADDR");?>" >
							<div class="form-group">
								<div class="col-md-3">
									<label class="control-label" for="images"><b>Upload Image<span class="text-danger">*</span></b> </label>
								</div>
								<div class="col-md-9">
									<input type="file" onchange="ownerpreviewFile(this);" id="images" name="images" class="form-control" value="">
								</div>
							</div>
							 
							 
							<div class="form-group">
								<div class="col-md-4 col-md-offset-4">
									<input class="btn btn-success btn-block" name="btn_denial" type="submit" id="Denial" value="Denial">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
                
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>


<script type="text/javascript">
	function modelInfo(msg){
		$.niftyNoty({
			type: 'info',
			icon : 'pli-exclamation icon-2x',
			message : msg,
			container : 'floating',
			timer : 5000
		});
	}
	<?php if($result = flashToast('denialForm')) { ?>
		modelInfo('<?=$result;?>');
	<?php }?>
	
 </script>


<script>
    $(document).ready(function(){
        $("#formname").validate({
            rules:{
                firm_Name:{
                    required:true
                },
                new_ward_id:{
                    required:true
                },
                address:{
                    required:true
                },
                landmark:{
                    required:true
                },
                city:{
                    required:true,
                 },
                pin_code:{
                    required:true,
					minlength:6,
                    maxlength:6,
					number:true
                },
				mobileno:{
					minlength:10,
                    maxlength:10,
					number:true
                },
				location:{
                   required:true
				},
                remarks:{
                    required:true
                },
                images:{
                    required:true
                }   

            },
            messages:{
                firm_Name:{
                    required:"Please Enter  Firm Name"
                },
                new_ward_id:{
                    required:"Please Select Ward"
                },
                address:{
                    required:"Please Enter Address"
                },
                landmark:{
                    required:"Please Enter Landmark"
                },
                city:{
                    required:"Please Enter City "
                },
				location:{
                    required:"Please Enter Location"
                },
                pin_code:{
                    required:"Please Enter Pin Code"
                },
                remarks:{
                    required:"Please Enter Remarks"
                },
                images:{
                    required:"Please Select File"
                }                
            }
        });
    });

    </script>

	<script>
		function isAlpha(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
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

    

    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }
</script>

<script>
	  function validate_holding()
    {   
         var holding_no=$("#holding_no").val();;
         var ward_id=$("#new_ward_id").val();        
         if(holding_no!="" && ward_id!=""){
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("denial/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no,
                        "ward_mstr_id":ward_id
                },               
                success:function(data){
                console.log(data);  
                  if (data.response==true) { 
                    var tbody="";
                    var i=1;
                        var address=data.pp['prop_address'];
                        var city=data.pp['prop_city'];
                        var pincode= data.pp['prop_pin_code'];
                        var owner_business_premises= data.pp['owner_name'];
     
                    $("#city").val(city);
                    $("#address").val(address);
                    $("#pin_code").val(pincode);  
                    $("#owner_name").val(owner_business_premises);
                      
                  }
                  else{
                      alert("Holding number not found.\nPlease check your ward number with holding number!");
                      $("#holding_no").val("");
                  }    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
              }); 
    }     
    }
</script>

<script>
	  function validate_licence_no()
    {   
         var licence_no=$("#licence_no").val();;         
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("denial/validate_licence_no");?>',
                dataType: "json",
                data: {
                        "licence_no":licence_no,
                },               
                success:function(data){
                console.log(data);
                  if (data.response==true) { 
					  return true;
                  }
                  else{
                      alert("Licence  number not found.");
                      $("#licence_no").val("");
                  }  
                   
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
              });      
    }
</script>

<script>
// $("#Denial").on("mouseover", function () {
//     validate_holding();
// 	validate_licence_no();
// });
</script>

<script>
    function ownerpreviewFile(input){
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var f = input.files[i];
                var ext = f.name.split('.').pop().toLowerCase();
                if ($.inArray(ext, ['jpg','pdf','png']) == -1) {
                    alert('Invalid file extension!');
                    $("#images").val("");
                    return false;
                }
                else if(f.size > 2e+6)
                {
                    alert('File size exceed. Please Upload less then or equal to 2 MB');
                    $("#images").val("");
                    return false;
                }
                 
                // reader.readAsDataURL(input.files[i]);
            }
        }
    }
</script>



