
<?php
if (session_status() == PHP_SESSION_NONE)
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
                        <label class="col-md-2 bolder">Consumer No.<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['consumer_no']; ?>
                        </div>

                        <label class="col-md-2 bolder">Category <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>

                      

                    </div>

                    <div class="row">
                        <label class="col-md-2 bolder">Type of Connection <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Connection Through <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['connection_through']; ?> 
						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Property Type <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['property_type']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Pipeline Type <span class="text-danger"></span></label>
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
                        <label class="col-md-2 bolder">Ward No. <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?=isset($consumer_details['ward_no'])&& !empty($consumer_details['ward_no'])?$consumer_details['ward_no']:(isset($applicant_details['ward_no']) && !empty($applicant_details['ward_no'])?$applicant_details['ward_no']:'N/A'); ?>
                        </div>
                        <?php
							if($consumer_details['prop_dtl_id']!="")
							{
                        ?>
                        <label class="col-md-2 bolder">Holding No. <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?=isset($consumer_details['holding_no'])&& !empty($consumer_details['holding_no'])?$consumer_details['holding_no']:(isset($applicant_details['holding_no'])&& !empty($applicant_details['holding_no'])?$applicant_details['holding_no']:'N/A'); ?> 
						</div>
                        <?php   
							}
							else
							{
						?>
                        <label class="col-md-2 bolder">SAF No. <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?=isset($consumer_details['saf_no'])  && !empty($consumer_details['saf_no'])?$consumer_details['saf_no']:(isset($applicant_details['saf_no']) && !empty($applicant_details['saf_no'])?$applicant_details['saf_no']:'N/A'); ?> 
                        </div>
                        <?php
							}
                        ?>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Area in Sqft.<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                          <?=isset($consumer_details['area_sqft'])  && !empty($consumer_details['area_sqft'])?$consumer_details['area_sqft']:(isset($applicant_details['area_sqft']) && !empty($applicant_details['area_sqft'])?$applicant_details['area_sqft']:'N/A');  ?> 
                        </div>
                        <label class="col-md-2 bolder">Area in Sqmt.<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?=isset($consumer_details['area_sqmt'])  && !empty($consumer_details['area_sqmt'])?$consumer_details['area_sqmt']:(isset($applicant_details['area_sqmt']) && !empty($applicant_details['area_sqmt'])?$applicant_details['area_sqmt']:'N/A');  ?> 
                        </div>
                    </div>
                   <div class="row">
                        <label class="col-md-2 bolder">Address<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                          <?=isset($consumer_details['address'])?$consumer_details['address']:(isset($applicant_details['address'])?$applicant_details['address']:'N/A'); ?> 
                        </div>
                        <label class="col-md-2 bolder">Landmark <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                           <?=isset($consumer_details['landmark'])?$consumer_details['landmark']:(isset($applicant_details['landmark'])?$applicant_details['landmark']:'N/A'); ?> 
                        </div>
                    </div>
					<div class="row">
						<label class="col-md-2 bolder">Pin<span class="text-danger"></span></label>
						<div class="col-md-3 pad-btm">
						  <?=isset($consumer_details['pin'])?$consumer_details['pin']:(isset($applicant_details['pin'])?$applicant_details['pin']:'N/A'); ?> 
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
                                <td><?php echo $val['applicant_name']??'N/A';?></td>
                                <td><?php echo $val['father_name']??'N/A';?></td>
                                <td><?php echo $val['mobile_no']??'N/A';?></td>
                                <td><?php echo $val['email_id']??'N/A';?></td>
                                <td><?php echo $val['state']??'N/A';?></td>
                                <td><?php echo $val['district']??'N/A';?></td>
                                <td><?php echo $val['city']??'N/A';?></td>
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
                    <h3 class="panel-title">Consumer Connection Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                Connection Type
                            </div>
                            <div class="col-md-3">
                                <?php
                                    $meter_no="N/A";
                                    if($connection_dtls['connection_type']==1)
                                    {
                                        $connection_type='Meter';
                                        $meter_no=$connection_dtls['meter_no'];
                                    }
                                    else if($connection_dtls['connection_type']==2)
                                    {
                                        $connection_type='Gallon';
                                    }   
                                    else
                                    {
                                        $connection_type='Fixed';
                                    }
                                ?>
                                <?php  echo $connection_type;?>
                            </div>
                            <div class="col-md-3">
                                Connection Date
                            </div>
                            <div class="col-md-3">
                                <?php  echo date('d-m-Y',strtotime($connection_dtls['connection_date']));?>
                            </div>
                            <div class="col-md-3">
                                Meter No.
                            </div>
                            <div class="col-md-3">
                                <?php  echo  $meter_no;?>
                            </div>

                            <div class="col-md-3">
                                Last Meter Reading
                            </div>
                            <div class="col-md-3">
                                <?php  echo $last_reading;?>
                            </div>
                        </div>

                    </div>

            
                    
                    

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
                                <label class="col-md-2 bolder">K No. <span class="text-danger"></span></label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo $consumer_details['k_no']; ?>
                                </div>
                                <label class="col-md-2 bolder">Bind Book No.<span class="text-danger"></span></label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo $consumer_details['bind_book_no']; ?> 
                            </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 bolder">Electricity Account No. <span class="text-danger"></span></label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo $consumer_details['account_no']; ?> 
                                </div>
                                <label class="col-md-2 bolder">Electricity Category <span class="text-danger"></span></label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo $consumer_details['electric_category_type']; ?> 
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
