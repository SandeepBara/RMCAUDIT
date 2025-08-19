
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
  
    <!--Page content-->
    <div id="page-content">
            
        
              
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Connection Details View</h3>
                </div>
                <div class="panel-body">   

                    <div class="row">
                        <label class="col-md-2 bolder">Consumer No.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['consumer_no']; ?>
                        </div>

                        <label class="col-md-2 bolder">Category <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>

                      

                    </div>

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
								if($consumer_owner_details)
								{
									foreach($consumer_owner_details as $val)
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

                   <input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_id; ?>">


                    
                    <?php
                    

                    if($dues)
                    {
                    ?>
                    
                    <div class="col-md-3">

                    <a href="<?php echo base_url('WaterUserChargeProceedPaymentCitizen/pay_payment/'.md5($consumer_details['id']));?>" class="btn btn-success">

                      Proceed to Payment

                   </a>


                    </div>

                    <?php 
                    }
                    if($user_type!=5)
                    {
                    ?>

                   

                    <div class="col-md-3">

                      <a href="<?php echo base_url('WaterViewConsumerDueDetailsCitizen/transactionDetails/'.md5($consumer_details['id']));?>" class="btn btn-primary">View Transaction</a>

                    </div>

                    <?php
                    }
                    ?>
                    <!-- <div class="col-md-3">
                        <a href="<?php echo base_url('WaterApplyNewConnectionCitizen/water_connection_view/'.md5($consumer_details['apply_connection_id']));?>" class="btn btn-primary">View Application</a>
                    </div> -->
                   
                    <div class="col-md-3">

                        <a href="<?php echo base_url('WaterViewConsumerDueDetailsCitizen/index/'.md5($consumer_details['id']));?>" class="btn btn-primary">View Dues</a>


                    </div>
                   

                 
               </div>
            </div>  
    


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_home/footer');
	
  
 ?>
