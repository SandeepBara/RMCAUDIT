<?php
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
            <li><a href="#"> Property </a></li>
            <li><a href="#"> GBSAF </a></li>
            <li><a href="<?=base_url('/GsafDocUpload/backToCitizenList');?>"> Back To Citizen List </a></li>
            <li class="active"><a href="#"> View </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
        

              
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> GBSAF Details</h3>
                </div>
                <div class="panel-body">                      
                    <div class="row">
                        <label class="col-md-2 bolder">Application No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $application_detail['application_no']; ?>
                        </div>
                        <label class="col-md-2 bolder">Ward No. </label>
                        <div class="col-md-3 pad-btm">
            							<?php echo $application_detail['ward_no']; ?> 
            						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Application Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $application_detail['application_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Property Type </label>
                        <div class="col-md-3 pad-btm">
                            <?=($application_detail['property_type']=='')?"N/A":$application_detail['property_type'];?> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Onwership Type </label>
                        <div class="col-md-3 pad-btm">
                            <?=($application_detail['ownership_type']=='')?"N/A":$application_detail['ownership_type'];?> 
                        </div>
                        <label class="col-md-2 bolder">Construction Type </label>
                        <div class="col-md-3 pad-btm">
                            <?=($application_detail['construction_type']=='')?"N/A":$application_detail['construction_type'];?> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Road Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $application_detail['road_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Property Type </label>
                        <div class="col-md-3 pad-btm">
                            <?=($application_detail['property_type']=='')?"N/A":$application_detail['property_type'];?> 
                        </div>
                       
                    </div>

                    <div class="row">
                         <label class="col-md-2 bolder">Colony Name </label>
                         <div class="col-md-3 pad-btm">
                            <?php echo $application_detail['colony_name']; ?> 
                         </div>
                         <label class="col-md-2 bolder">Colony Address </label>
                         <div class="col-md-3 pad-btm">
                            <?php echo $application_detail['colony_address']; ?> 
                         </div>
                    </div>

                    <div class="row">
                        <label class="col-md-2 bolder">Application Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $application_detail['application_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Apply Date </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo date('d-m-Y',strtotime($application_detail['apply_date'])); ?> 
                        </div>
                    </div>
                
				
                </div>
            </div>
            
        
            <div class="clear"></div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Details of Authorized Person For The Payment Of Property Tax</h3>
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<!-- <th class="bolder">Officer Name</th>
									<th class="bolder">Mobile No.</th> -->
									<th class="bolder">Designation</th>
									<th class="bolder">Address</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if($owner_details)
								{
									foreach($owner_details as $val)
								{
								?>
								<tr>
									<!-- <td><?php echo $val['officer_name'];?></td>
									<td><?php echo $val['mobile_no'];?></td> -->
									<td><?php echo $val['designation'];?></td>
									<td><?php echo $val['address'];?></td>
									
								</tr>
								<?php
								}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				
          <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Upload Document</h3>
            </div>
           
            <div class="panel">
                
                    <div class="panel-body text-center">
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th>View</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Upload Again</th>
                                        
                                    </tr>
                                </thead>
                                <tbody style="text-align: left;">
                                    <?php
                                    $i=0;
                                        foreach($doc_detail as $doc)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$doc["document_name"];?></td>
                                                
                                                <td><a href="<?=base_url();?>/getImageLink.php?path=<?=$doc['file_name'];?>" target="blank" class="btn btn-primary btn-sm">View</a></td>
                                                <td>
                                                    <?php
                                                    if($doc['verify_status']==1)
                                                    {
                                                        ?>
                                                        <span class="text text-success">Verified</span>
                                                        <?php
                                                    }
                                                    else if($doc['verify_status']==2)
                                                    {
                                                        ?>
                                                        <span class="text text-danger">Rejected</span>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <span class="text text-primary">Not Verified</span>
                                                        <?php
                                                    }
                                                    ?>    
                                                </td>
                                                <td><?=$doc["remarks"];?></td>
                                                <td>
                                                    <?php
                                                    if($doc['verify_status']==2)
                                                    {
                                                        ?>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <input type="hidden" value="<?=$doc["govt_saf_dtl_id"];?>" name="gov_saf_id" />
                                                            <input type="hidden" value="<?=$doc["document_name"];?>" name="document_name" />
                                                            <input type="file" class="" name="application_form" accept=".pdf" required/>
                                                            <input type="submit" class="btn btn-success" name="upload_document" value="Upload" />
                                                        </form>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                </td>
                                                    
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
                
        <div class="panel">
            <div class="text text-center">
                <a href="<?=base_url("GsafDocUpload/revertToOfficer/".md5($application_detail['id']));?>" class="btn btn-primary" onclick="return confirm('Are sure want to send to ULB?')">
                    Send to ULB
                </a>
            </div>
        </div>
        

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_vertical/footer');
	
  
 ?>
 <script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">
   function myPopup(myURL, title, myWidth, myHeight)
   {
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