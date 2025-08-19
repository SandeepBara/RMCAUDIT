
<?php
session_start();
 
  echo $this->include('layout_home/header');
 
?>
<!--<style type="text/css">
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
  .bolder{font-weight: bold;}
  
</style>-->
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
  
    <div id="page-content">
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Application Status</h3>
                </div>
                <div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php echo $consumer_details['application_no'];?></span>
							</div>
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;">Application Status: </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php echo $application_status;?></span>
							</div>
						</div>
					</div>
                </div>
            </div>

              
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Connection Details View</h3>
                </div>
                <div class="panel-body">                      
                    <div class="row">
                        <label class="col-md-2 bolder">Type of Connection <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Connection Through <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['connection_through']; ?> 
						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Property Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['property_type']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Pipeline Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['pipeline_type']; ?> 
                        </div>
                    </div>
                
					<div class="row">
                        <label class="col-md-2 bolder">Category <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Owner Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['owner_type']; ?> 
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Property Details</h3>
                </div>
                <div class="panel-body">                     
                    <div class="row">
                        <label class="col-md-2 bolder">Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?>
                        </div>
                        <?php
							if($consumer_details['prop_dtl_id']!="")
							{
                        ?>
                        <label class="col-md-2 bolder">Holding No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['holding_no']; ?> 
						</div>
                        <?php   
							}
							else
							{
						?>
                        <label class="col-md-2 bolder">SAF No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['saf_no']; ?> 
                        </div>
                        <?php
							}
                        ?>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Area in Sqft.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['area_sqft']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Area in Sqmt.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['area_sqmt']; ?> 
                        </div>
                    </div>
                   <div class="row">
                        <label class="col-md-2 bolder">Address<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['address']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Landmark <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                           <?php echo $consumer_details['landmark']; ?> 
                        </div>
                    </div>
					<div class="row">
						<label class="col-md-2 bolder">Pin<span class="text-danger">*</span></label>
						<div class="col-md-3 pad-btm">
						  <?php echo $consumer_details['pin']; ?> 
						</div>
					</div>
                </div>
            </div>
            <div class="clear"></div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Owner Details</h3>
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th class="bolder">Owner Name</th>
									<th class="bolder">Guardian Name</th>
									<th class="bolder">Mobile No.</th>
									<th class="bolder">Email ID</th>
									<th class="bolder">State</th>
									<th class="bolder">District</th>
									<th class="bolder">City</th>
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
									<td><?php echo $val['applicant_name'];?></td>
									<td><?php echo $val['father_name'];?></td>
									<td><?php echo $val['mobile_no'];?></td>
									<td><?php echo $val['email_id'];?></td>
									<td><?php echo $val['state'];?></td>
									<td><?php echo $val['district'];?></td>
									<td><?php echo $val['city'];?></td>
								</tr>
								<?php
								}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				<?php
				if($user_type!=5)
				{
				?>
                
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Electricity Connection Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<label class="col-md-2 bolder">K No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_k_no']; ?>
							</div>
							<label class="col-md-2 bolder">Bind Book No.<span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_bind_book_no']; ?> 
						   </div>
						</div>
						<div class="row">
							<label class="col-md-2 bolder">Electricity Account No. <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_account_no']; ?> 
							</div>
							<label class="col-md-2 bolder">Electricity Category <span class="text-danger">*</span></label>
							<div class="col-md-3 pad-btm">
								<?php echo $consumer_details['elec_category']; ?> 
							</div>
						</div>
					</div>
				</div>
            
            <?php
            }
            ?>

      <div class="panel">
				<div class="panel-body text-center">

                   <input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $water_conn_id; ?>">


                  <?php
                 /* if($consumer_details['payment_status']==0)
                  {
                  ?>  
                 
                   <div class="col-md-3">

                     <a href="<?php echo base_url('WaterDocumentCitizen/doc_upload/'.md5($consumer_details['id']));?>">
                       <button class="btn btn-primary"  name="submit" value="update_application">Update Application</button></a>


                   </div>

                    <?php
                  }*/

                  //echo $consumer_details['doc_status'];

                   
                    ?>  
                    <div class="col-md-3">

                       <a href="<?php echo base_url('WaterDocumentCitizen/doc_upload/'.md5($consumer_details['id']));?>"><button class="btn btn-warning" value="upload_documents">Upload Documents</button></a>

                    </div>
                    <?php
                  

                    if($consumer_details['doc_status']==1)
                    {
                    ?>  
                    <div class="col-md-3">

                      <a href="<?php echo base_url('WaterDocumentCitizen/docview/'.md5($consumer_details['id']));?>">
                       <button class="btn btn-warning" type="submit" name="submit" value="view_documents">View Documents Upladed</button></a>


                    </div>
                    <?php
                     }
                    ?>
                    <?php
                    //print_r($dues);
                    if($dues)
                    {
                    ?>
                    
                    <div class="col-md-3">
                    <a href="<?php echo base_url('WaterPaymentCitizen/payment/'.md5($consumer_details['id']));?>">
                      <button class="btn btn-success" type="submit" name="submit" value="proceed_payment">Proceed to Payment</button>
                    </a>
                    

                    </div>

                    <?php 
                    }
                    if($transaction_count>0)
                    {
                    ?>

                   

                    <div class="col-md-3">
                       <a href="<?php echo base_url('WaterPaymentCitizen/payment/'.md5($consumer_details['id']));?>">
                      <button class="btn btn-success" type="submit" name="submit" value="proceed_payment">View Transaction</button></a>


                    </div>

                    <?php
                    }
                    ?>

                    <div class="col-md-3">

                      <a href="<?php echo base_url('WaterViewConnectionChargeCitizen/fee_charge/'.md5($consumer_details['id']));?>">
                        <button class="btn btn-info" type="submit" name="submit" value="view_connection_fee">View Connection Fee</button></a>


                    </div>
                   
                 
               </div>
            </div>  
     

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 
	if($user_type==4 || $user_type==5 || $user_type==7)
	{

		echo $this->include('layout_mobi/footer');
	}
	else
	{
		echo $this->include('layout_vertical/footer');
	}
  
 ?>
