<?=$this->include('layout_vertical/header');?>

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
            <li><a href="#" class="active">Water Connection Details</a></li>
            <!-- <li class="active">View Due</li> -->
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Connection Details </h3>
                </div>
                <div class="panel-body">   

                    <div class="row">
                        <label class="col-md-2 bolder">Consumer No.<!--<span class="text-danger">*</span>--></label>
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
                    <div class="row">
                        <label class="col-md-2 bolder">Apply From <span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['apply_from']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Consumer Connection Date<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo date('d-m-Y',strtotime($consumer_details['created_on']));?> 
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
									<td><?=isset($val['applicant_name'])? $val['applicant_name'] :'';?></td>
									<td><?=isset($val['father_name'])? $val['father_name'] :'';?></td>
									<td><?=isset($val['mobile_no'])? $val['mobile_no'] :'';?></td>
									<td><?=isset($val['email_id'])? $val['email_id'] :'';?></td>
									<td><?=isset($val['state'])? $val['state'] :'';?></td>
									<td><?=isset($val['district'])? $val['district'] :'';?></td>
									<td><?=isset($val['city'])? $val['city'] :'';?></td>
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
						<h3 class="panel-title">Electricity Connection Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<label class="col-md-2 bolder">K No. <span class="text-danger"></span></label>
							<div class="col-md-3 pad-btm">
								<?=isset($consumer_details['k_no'])&&!empty($consumer_details['k_no'])?$consumer_details['k_no']:'N/A'; ?>
							</div>
							<label class="col-md-2 bolder">Bind Book No.<span class="text-danger"></span></label>
							<div class="col-md-3 pad-btm">
								<?=isset($consumer_details['bind_book_no']) &&!empty($consumer_details['bind_book_no']) ?$consumer_details['bind_book_no']:'N/A'; ?> 
						   </div>
						</div>
						<div class="row">
							<label class="col-md-2 bolder">Electricity Account No. <span class="text-danger"></span></label>
							<div class="col-md-3 pad-btm">
                                <?=isset($consumer_details['account_no']) &&!empty($consumer_details['account_no']) ?$consumer_details['account_no']:'N/A'; ?> 
							</div>
							<label class="col-md-2 bolder">Electricity Category <span class="text-danger"></span></label>
							<div class="col-md-3 pad-btm">
                                <?=isset($consumer_details['electric_category_type']) &&!empty($consumer_details['electric_category_type']) ?$consumer_details['electric_category_type']:'N/A'; ?>
							</div>
						</div>
					</div>
				</div>
            
           
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Meter/Fixed Connection Details</h3>
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
                                if(in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                                {
                                    $connection_type = "Meter/Fixed";
                                    $meter_no=$connection_dtls['meter_no'];
                                }
                                elseif($connection_dtls['connection_type']==1)
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
                        <?=$connection_type;?> Connection Date
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
                        <?php
                        if(in_array($connection_dtls['connection_type'],[1,2]))
                        {
                            ?>
                            <div class="col-md-3">
                                Last Reading Image
                            </div>
                            <div class="col-md-3" style="height:50px;width: 100px;">
                                <?php
                                    $path = $ReadingImg['file_name']??null;                                   
                                    $extention = strtolower(explode('.', $path)[1]??null);
                                    if ($extention=="pdf")
                                    {
                                        ?>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?='RANCHI/meter_image/'.$path;?>" target="_blank"> 
                                                <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                            </a>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <a target = "_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path?>">
                                                <img style="height:100%;width: 100%;" src="<?=base_url();?>/getImageLink.php?path=<?=$path?>" alt="No File"/>
                                            </a>
                                        <?php
                                    }                                    
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                </div>

           
                
                

            </div>
        </div>


            
            <div class="panel">
				<div class="panel-body text-center">
					<input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_id; ?>">
					<?php 
                    // 1->supperAdmin, 2->Admin, 3->project_manager, 8->jsk, 28->back office
                    if($meter_status['meter_no']=="")
                    {
                        ?>
					
						<a href="<?php echo base_url('WaterUpdateConsumerConnectionMeterDoc/index/'.md5($consumer_details['id']).'/'.md5($meter_status['id']));?>" class="btn btn-primary">
						Update Meter Doc and No.</a>
                    
                        <?php 
                    }
                    if(in_array($user_type,[1,2,8,28,4]))
                    {
                        ?>
                        <a href="<?php echo base_url('WaterViewConsumerDetails/update_consumer/'.md5($consumer_details['id']));?>" class="btn btn-primary">
						Update Consumer</a>
                        <?php
                    }
                    if($dues && in_array($user_type,[1,4,5,8]))
                    {
                        ?>
                    
						<a href="<?php echo base_url('WaterUserChargePayment/payment_details/'.md5($consumer_details['id']));?>" class="btn btn-primary">
						Proceed to Payment</a>
                    
                        <?php 
                    }
                    if($user_type!=5)
                    {
                        ?>
                    
						<a href="<?php echo base_url('WaterViewConsumerDueDetails/transactionDetails/'.md5($consumer_details['id']));?>" class="btn btn-primary">View Transaction</a>
                    
                        <?php 
                    }
                    if($consumer_details['apply_from']!='Existing' || !empty($consumer_details['apply_connection_id']))
                    {
                        ?>
                        <a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.md5($consumer_details['apply_connection_id']));?>" target="_blank" class="btn btn-primary">View Application</a>
                        <?php
                    }
                    ?>
                    
                        
                    
                    
                        <a href="<?php echo base_url('WaterViewConsumerDetails/demand_generate/'.md5($consumer_details['id']));?>" class="btn btn-primary">Generate Demand</a>
                                        
                    
                        <a href="<?php echo base_url('WaterViewConsumerDueDetails/index/'.md5($consumer_details['id']));?>" class="btn btn-primary">View Dues</a>

                        <a href="<?php echo base_url('WaterViewConsumerDetails/Notice/'.$consumer_details['id']);?>" class="btn btn-primary my-2">Generate Notice</a>
                    
               </div>
            </div>  
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php echo $this->include('layout_vertical/footer');?>
