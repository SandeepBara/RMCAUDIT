<?=$this->include('layout_vertical/header');?>

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
            <li><a href="#">GBSAF</a></li>
            <li><a href="<?=base_url("govsafDetailPayment/gov_saf_application_details");?>/<?=md5($application_detail["id"]);?>">GBSAF Details</a></li>
            <li><a href="#" class="active">Document Upload</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
        

    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">
								Application Details  
							</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-2">
									<b>Ward No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['ward_no']?$application_detail['ward_no']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Apply Date</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['apply_date']?$application_detail['apply_date']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Application No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['application_no']?$application_detail['application_no']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Application Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['application_type']?$application_detail['application_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Officer Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['officer_name']?$application_detail['officer_name']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Mobile No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['mobile_no']?$application_detail['mobile_no']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<b>Assessment Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['assessment_type']?$application_detail['assessment_type']:"N/A"; ?>
								</div>
								<div class="col-md-2">
									<b>Holding No.</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-3">
									<?=$application_detail['holding_no']?$application_detail['holding_no']:"N/A"; ?>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3">
									<b> Office Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['office_name']?$application_detail['office_name']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Property Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['property_type']?$application_detail['property_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Construction Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['construction_type']?$application_detail['construction_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Road Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['road_type']?$application_detail['road_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Colony Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['colony_name']?$application_detail['colony_name']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Colony Address</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['colony_address']?$application_detail['colony_address']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Ownership Type</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['ownership_type']?$application_detail['ownership_type']:"N/A"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Water Harvesting</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['is_water_harvesting']=="t"?"Yes":"No"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Mobile Tower</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['is_mobile_tower']=="t"?"Yes"."  /  "."Area"." ".":"." ".$application_detail['tower_area']."  /  "."Installation Date"." ".":"." ".$application_detail['tower_installation_date']:"No"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Hoarding Board</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['is_hoarding_board']=="t"?"Yes"."  /  "."Area"." ".":"." ".$application_detail['hoarding_area']."  /  "."Installation Date"." ".":"." ".$application_detail['hoarding_installation_date']:"No"; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Is Petrol Pump</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$application_detail['is_petrol_pump']=="t"?"Yes"."  /  "."Area"." ".":"." ".$application_detail['under_ground_area']."  /  "."Installation Date"." ".":"." ".$application_detail['petrol_pump_completion_date']:"No"; ?>
								</div>
							</div>
						</div>
					</div>
						
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Authorized Person for the Payment of Property Tax</h3>
						</div>

						<div class="panel-body">
						<div class="row">
							<div class="col-md-3">
									<b>Officer Name</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
    								<?=$owner_details['officer_name'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Mobile No</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$owner_details['mobile_no'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Email Id</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$owner_details['email_id'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Designation</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$owner_details['designation'];?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<b>Address</b>
								</div>
								<div class="col-sm-1">
									<b> : </b>
								</div>
								<div class="col-md-8">
									<?=$owner_details['address'];?>
								</div>
							</div>
						</div>
					</div>
          
          
          
                <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Upload Document</h3>
            </div>
           <form method="post" id="myform" enctype='multipart/form-data'>
            <div class="panel">
				<div class="panel-body text-center">
                    <div class="row">
                        <label class="col-md-3 bolder">Application Form(Scan Copy)</label>
                        <?php if($checkExists_mail==0) { ?>
                        <div class="col-md-3 pad-btm">
                            <input type="hidden" name="gov_saf_id" id="gov_saf_id" value="<?php echo $application_detail['id'];?>">
                            <input type="file" name="application_form" id="application_form" accept=".pdf" />
                            <span class="text text-danger"> ( Upload pdf file only) </span>
                        </div>
                        <div class="col-md-3 pad-btm">
                           <input type="submit" name="submit" id="submit" value="Upload" class="btn btn-success">
                        </div>
                        <?php
                        }
                        if($doc_detail['file_name']!="") {
                        ?>
                         <div class="col-md-3 pad-btm">
                            <!--<img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$doc_detail["file_name"];?>" style="width: 40px; height: 40px;" alt="application form">-->
                            <a href="<?=base_url();?>/writable/uploads/<?=$doc_detail["file_name"];?>" target="_blank" class="btn btn-primary">File Uploaded</a>
                        </div>
                        <?php
                        } else {
                            echo "<span style='color:red;'>Not Uploaded!!!</span>";
                        }
                        ?>
                    </div>
                   
               </div>
            </div>  
          </form>
        </div>

        <?php if($doc_detail['file_name']!="" and $checkExists_mail==0 and $application_detail['is_transaction_done']==1) { ?>
            <div class="panel">
                <div class="panel-body text-center">
                    <div class="row">
                        <a href="<?php echo base_url('GsafDocUpload/sendtoRMC/'.md5($application_detail['id'])); ?>" class="btn btn-success">Send to ULB</a>
					</div>
				</div>
            </div>  
            <?php } else if($checkExists_mail==1) { ?>
            <p style="color: green; font-size: 17px; font-weight: bold; text-align: center;">Already Sent to ULB.</p>
            <?php } ?>

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_vertical/footer');
	
  
 ?>
 <script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">
   function myPopup(myURL, title, myWidth, myHeight) {
            var left = (screen.width - myWidth) / 2;
            var top = (screen.height - myHeight) / 4;
            var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
         }



</script>

<script>
    
    $(document).ready(function () 
    {

    

    $('#myform').validate({ // initialize the plugin
       

        rules: {
            application_form: {
                required: true,
               
            }
        }


    });

});
</script>

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
<?php 
    if($message=flashToast('message'))
    {
        echo "modelInfo('".$message."');";
    }
  ?>
  
  
</script>