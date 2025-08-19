
<?php
//session_start();

  if($user_type==13 || $user_type==5 || $user_type==7 || $user_type==4) 
  {

     echo $this->include('layout_mobi/header');
  }
  else
  {
     echo $this->include('layout_vertical/header');
  }
 

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
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="<?php echo base_url('WaterViewConsumerDetails/index/'.md5($consumer_details['id']));?>" >Water Connection Details</a></li>
            <li class="active">Transaction Details</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
       
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="col-sm-6">
                        <h3 class="panel-title"> Water Connection Details View</h3>
                    </div>
                     <!-- <div class="col-sm-6" style="text-align: right; ">
                        <a href="<?php echo base_url('WaterViewConsumerDetails/index/'.md5($consumer_details['id']));?>" class="btn btn-info">Back</a>
                    </div> -->
                </div>

                <div class="panel-body">   

                    <div class="row">
                        <label class="col-md-2 bolder">Consumer No.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['consumer_no']; ?>
                        </div>

                        <label class="col-md-2 bolder">Category </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>

                      

                    </div>

                    <div class="row">
                        <label class="col-md-2 bolder">Type of Connection </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Connection Through </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['connection_through']; ?> 
						</div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Property Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['property_type']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Pipeline Type </label>
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
                                        <td><?php echo isset($val['applicant_name'])?$val['applicant_name']:'N/A';?></td>
                                        <td><?php echo isset($val['father_name'])?$val['father_name']:'N/A';?></td>
                                        <td><?php echo isset($val['mobile_no'])?$val['mobile_no']:'N/A';?></td>
                                        <td><?php echo isset($val['email_id'])?$val['email_id']:'N/A';?></td>
                                        <td><?php echo isset($val['state'])?$val['state']:'N/A';?></td>
                                        <td><?php echo isset($val['district'])?$val['district']:'N/A';?></td>
                                        <td><?php echo $val['city']??'N/A';?></td>
                                    </tr>
                                    <?php
                                    }
								}
                                else 
                                {
                                    ?>
                                    <tr><td colspan =7 class='text-danger text-center'>! No data</td></tr>
                                    <?php
                                }
								?>
							</tbody>
						</table>
					</div>
				</div>


            


			<div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Transaction Details</h3>
                    </div>
                    <div class="panel-body table-responsive">

                        <table class="table table-bordered ">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th class="bolder">Sl No.</th>
                                    <th class="bolder">Transaction No.</th>
                                    <th class="bolder">Transaction Date</th>
                                    <th class="bolder">Payment Mode</th>
                                    <!-- <th class="bolder">From Month</th>
                                    <th class="bolder">Upto Month</th> -->
                                    <th class="bolder">Total Amount</th>
                                    <th class="bolder">Paid Amount</th>
                                    <th class="bolder">Penalty</th>
                                    <th class="bolder">Rebate</th>
                                    <th class="bolder">Due Amount</th>
                                    <th class="bolder">View</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($transaction_details)
                            {
                                $i = isset($offset)?$offset:0;
                            

                                    foreach($transaction_details as $val)
                                    {
                                        //print_r($val);
                                        ?>
                                        <tr>
                                            <td><?php echo ++$i;?></td>
                                            <td><?php echo $val['transaction_no'];?></td>
                                            <td><?php echo date('d-m-Y',strtotime($val['transaction_date']));?></td>
                                            <td><?php echo $val['payment_mode'];?></td>
                                            <!-- <td><?php echo !empty($val['demand_from'])?date('d-m-Y',strtotime($val['demand_from'])):'N/A';?></td>
                                            <td><?php echo !empty($val['demand_upto'])?date('d-m-Y',strtotime($val['demand_upto'])):'N/A';?></td> -->
                                            <td><?php echo (($val['paid_amount']??0)+($val['due_amount']??0));?></td>
                                            <td><?php echo $val['paid_amount'];?></td>
                                            <td><?php echo $val['penalty'];?></td>
                                            <td><?php echo $val['rebate'];?></td>
                                            <td><?php echo $val['due_amount'];?></td>
                                            <td><a class='btn btn-small btn-primary' href="<?php echo base_url('WaterUserChargePayment/payment_tc_receipt/'.md5($val['related_id']).'/'.md5($val['id']));?>"><i class="bi bi-eye"></i>View</a></td>
                                            
                                            
                                        
                                        </tr>
                                        <?php
                                       
                                    }
                                }
                                else
                                {
                                ?>
                                <tr><td colspan =7 class='text-danger text-center'>! No data</td></tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            
                        </table>
                        
                        <?=pagination($count??0);?>


                    </div>
                </div>

      
            <!-- <div class="panel">
				<div class="panel-body text-center">

                   <input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_id; ?>">


                    
                    <?php
                    //if($dues)
                    {
                    ?>
                    
                    <div class="col-md-3">

                      <a href="<?php echo base_url('WaterUserChargePayment/payment_details/'.md5($consumer_details['id']));?>" class="btn btn-success">Proceed to Payment</a>
                    
                    </div>

                    <?php 
                    }
                    if($user_type!=5)
                    {
                    ?>
                    <div class="col-md-3">

                        <a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.md5($consumer_details['apply_connection_id']));?>" class="btn btn-warning" target="_blank">View Application</a>

                    </div>

                    <?php
                    }
                    ?>
                    
                   
                
                   

                 
               </div>
            </div>   -->
    


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 
	if($user_type==13 || $user_type==5 || $user_type==7 || $user_type==4)
	{

		echo $this->include('layout_mobi/footer');
	}
	else
	{
		echo $this->include('layout_vertical/footer');
	}
  
 ?>
