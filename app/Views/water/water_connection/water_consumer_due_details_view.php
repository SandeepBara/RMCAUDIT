
<?php
@session_start();
echo $this->include('layout_vertical/header'); 
?>
<!--<style type="text/css">
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
  .bolder{font-weight: bold;}
  
</style>-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="<?php echo base_url('WaterViewConsumerDetails/index/'.md5($consumer_details['id']));?>" >Water Connection Details</a></li>
            <li class="active">View Due</li>
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
								{ //print_var($consumer_owner_details);die;
									foreach($consumer_owner_details as $val)
								{
								?>
								<tr>
									<td><?php echo $val['applicant_name'];?></td>
									<td><?php echo $val['father_name'];?></td>
									<td><?php echo $val['mobile_no'];?></td>
									<td><?php echo $val['email_id']??'N/A';?></td>
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


            <!-- hear -->
            <?php
                if(in_array($user_type_id??0,[1,2,4]) && isset($bemand_history))
                {
                    ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Demand History</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-responsive" id="demo_dt_basic2">
                                    <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Upto</th>
                                        <th>Connection Type</th>
                                        <th>Amount</th>
                                        <th>Penalty</th> 
                                        <th>Total Demands</th> 
                                        <th>Demands</th> 
                                        <th>Payment Status</th>
                                        <th>Meter Reading</th>  
                                        <th>Meter No</th>  
                                        <th>Reading Image</th>                      
                                        
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    
                                    foreach($bemand_history as $val)
                                    {
                                    ?>
                                    <tr>
                                        <td><?=$val['demand_from'];?></td>
                                        <td><?=$val['demand_upto'];?></td>
                                        <td><?=$val['connection_type'];?></td>
                                        <td><?=$val['amount'];?></td>
                                        <td><?=$val['penalty'];?></td>
                                        <td><?=isset($val['balance_amount'])?$val['balance_amount']:($val['amount']+$val['penalty']);?></td>
                                        <td><?=isset($val['balance_amount'])?$val['balance_amount']:("N/A");?></td>
                                        <td class = "<?=($val['paid_status']==1?"text-Success":"text-danger");?>"><?=($val['paid_status']==1?"Yes":"No");?></td>
                                        <td><?=$val['current_meter_reading'];?></td>
                                        <td><?=$val['meter_no'];?></td>
                                        <td>
                                            <div class="col-md-3" style="height:50px;width: 100px;">
                                                <?php
                                                    $path = $val['file_name']??null;                                   
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
                                        </td>
                                        
                                    </tr>

                                    <?php    
                                    }
                                    ?>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
            
            <?php
                }
            ?>
            <!-- end hear -->


			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Due Details</h3>
                </div>
                <?php if(isset($due_summary))
                {
                    ?>
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> 
                                <span id="period">Period <i class='text-success'><?=$due_summary['demand_from'].' to ' . $due_summary['demand_upto']?></i></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span id="total_demand"> Total Demand [<i class='text-success'><?=$due_summary['amount'];?></i>]</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span id="total_penalty">Total Penalty [<i class='text-success'><?=$due_summary['penalty'];?></i>]</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                <span id="total_paybel">Total Payble Amount [<i class='text-success'><?=$due_summary['balance_amount'];?></i>]</span>
                            </h3>
                        </div>
                    <?php
                }
                ?>
                <div class="panel-body">

                    <?php
                    if($due_details)
                    {

                    ?>
                    <table class="table table-responsive" id="demo_dt_basic">
                        <thead>
                        <tr>
                            <th>Demand Month</th>
                            <th>Amount</th>
                            <th>Penalty</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        foreach($due_details as $val)
                        {
                            // echo $val['demand_upto'];
                            // echo  $month=date('F',strtotime($val['demand_upto']));
                        ?>
                        <tr>
                            <td><?php echo strtoupper($val['demand_month']);?></td>
                            <td><?php echo $val['current_amount']?></td>
                            <td><?php echo $val['penalty']?></td>
                            
                        </tr>

                        <?php    
                        }
                        ?>
                        </tbody>
                    </table>
                    <div>
                        <a class="btn btn-primary" href="<?=base_url()."/WaterViewConsumerDueDetails/print_demnds/$consumer_id";?>" target="_blank">print Demand</a>
                    </div> 
                    <?php

                    }
                    else
                    {
                        ?>


                        <p style="color: green; font-size: 15px; text-align: center;">No Dues!!!</p>

                        <?php
                    }

                    ?>
    


                </div>
            </div>

      
            <!-- <div class="panel">
				<div class="panel-body text-center">

                   <input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_id; ?>">


                    
                    <?php
                    if($dues)
                    {
                    ?>
                    
                    <div class="col-md-3">

                      <a href="<?php echo base_url('WaterUserChargePayment/payment_details/'.md5($consumer_details['id']));?>" class="btn btn-success">Proceed to Payment</a>
                      
                      
                    </div>

                    <?php 
                    }
                   
                    ?>
                    
                   

                    <div class="col-md-3">

                     <a href="<?php echo base_url('WaterViewConsumerDueDetails/transactionDetails/'.md5($consumer_details['id']));?>" class="btn btn-info">View Transaction</a>

                    </div>

                  
                   
                   
                
               </div>
            </div>   -->
    


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php echo $this->include('layout_vertical/footer'); ?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    //alert("dadsasd");
   $('#demo_dt_basic').DataTable({
            
            responsive: true,
            ordering: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2] }
            }]

   });
   $('#demo_dt_basic2').DataTable({
            
            responsive: true,
            ordering: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }]

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
        timer : 6000
    });
}
<?php 
    if($success_demand=flashToast('success_demand'))
    {
        echo "modelInfo('".$success_demand."');";
    }
  ?>

</script>