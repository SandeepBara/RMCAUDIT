
<?= $this->include('layout_vertical/header');?>
<?php
	$model_owners = "";
	$model_fathers = "";
	$model_mobile = "";
	$model_transection_no = "";
	$model_transection_total_paid = 0;
	$model_transection_total_amount = 0;
	$model_net_due_amount = 0;
?>
<style >
.error
{
    color: red;
}
</style>


<script type="text/javascript">
	function OperateDropDown(radio, control, hidden) {
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        
        if (rdo.value == "1") {
            var opt = ctrl.options;
            var pos = 0;
            for (var j = 0; j < opt.length; j++) {
                if (opt[j].value == hid_val) {
                    pos = j;
                    break;
                }
            }
            ctrl.selectedIndex = pos;
            ctrl.disabled = true;
        }
        else {
            ctrl.selectedIndex = 0;
            ctrl.disabled = false;
        }
    }


    function OperateTexBox(radio, control, hidden) {
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;

        if (rdo.value == "1") {
            ctrl.value = hid_val;
            ctrl.readOnly = true;
        }
        else {
            ctrl.value = "";
            ctrl.readonly = false;
            ctrl.disabled = false;
            
        }
    }

  
  
</script>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
	<div id="page-content"> 
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading" style="background-color:#39a9b0;">
				<div class="panel-control">
					<b style="font-size:16px;">Applied Date :  </b>&nbsp;&nbsp;&nbsp;<b style="font-size:16px;"><?=date('d-m-Y',strtotime($connection_dtls['apply_date']));?></b>
					<a href="<?php echo base_url('WaterfieldSiteInspection/search_consumer_for_siteInspection') ?>" class="btn btn-default">Back</a>
				</div>
				<h3 class="panel-title">Apply For Water Connection For <b style="font-size:20px;"> Application no. :-  <?=$connection_dtls["application_no"];?></b></h3>
			</div>
			<div class="panel-body">
			<form id="form_tc_verification" name="FORMNAME1" method="post">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Applicant Connection Request Type Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<input type="hidden" name="application_no" id="application_no" value="<?php echo $connection_dtls['application_no']; ?>">
							<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $connection_dtls['id'];?>">
							<input type="hidden" name="apply_date" id="apply_date" value="<?php echo date('d-m-Y',strtotime($connection_dtls['apply_date']));?>">
								
							<div class="col-md-12" style="line-height:50px;">
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;font-size:16px;"> Type of Request	:</span>&nbsp;&nbsp;&nbsp;
									<span style="color: #179a07;font-size:16px;"><?=$connection_dtls['connection_type'];?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;font-size:16px;">Connection Through :  </span>&nbsp;&nbsp;&nbsp;
									<span style="color: #179a07;font-size:16px;"><?=$connection_dtls['connection_through'];?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;font-size:16px;">Property Type :  </span>&nbsp;&nbsp;&nbsp;
									<span style="color: #179a07;font-size:16px;"><?=$connection_dtls['property_type'];?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Applicant Property Details</h3>
					</div>
					<div class="panel-body">
						<div class="row" style="font-size:14px;line-height:30px;">
							<div class="col-md-12">
								<div class="col-md-3">
									Ward No <span style="font-weight: bold; color: red;"> * </span>
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['ward_no']?$connection_dtls['ward_no']:"N/A";?>
								</div>
								<div class="col-md-3">
									Holding No   
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['holding_no']?$connection_dtls['holding_no']:"N/A";?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Total Constructed Area (SQFT) <span style="font-weight: bold; color: red;"> * </span>
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['area_sqft']?$connection_dtls['area_sqft']:"N/A";?>
								</div>
								<div class="col-md-3">
									Total Constructed Area (SQMT) <span style="font-weight: bold; color: red;"> * </span>
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['area_sqmt']?number_format($connection_dtls['area_sqmt'],2):"N/A";?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Address <span style="font-weight: bold; color: red;"> * </span>
								</div>
								<div class="col-md-9">
									: <?=$connection_dtls['address']?$connection_dtls['address']:"N/A";?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Landmark 
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['landmark']?$connection_dtls['landmark']:"N/A";?>
								</div>
								<div class="col-md-3">
									Pin Code  <span style="font-weight: bold; color: red;"> * </span>
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['pin']?$connection_dtls['pin']:"N/A";?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Applicant Category 
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['category']?$connection_dtls['category']:"N/A";?>
								</div>
								<div class="col-md-3">
									Application Date 
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['apply_date']?$connection_dtls['apply_date']:"N/A";?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Communication Address 
								</div>
								<div class="col-md-3">
									:<?=$connection_dtls['address']?$connection_dtls['address']:"N/A";?>
								</div>
								<div class="col-md-3">
									Owner Type 
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['owner_type']?$connection_dtls['owner_type']:"N/A";?>
								</div>
							</div>
						</div>
					</div>
				</div>
		
		
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
								if($applicant_details)
								{
									foreach($applicant_details as $val)
									{
										$model_fathers .=$val['father_name'].','; 
										$model_owners .=$val['applicant_name'].','; 
										$model_mobile .=$val['mobile_no'].','; 
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
									$model_fathers = trim($model_fathers,','); 
									$model_owners =trim($model_owners,','); 
									$model_mobile =trim($model_mobile,',');
								}
								?>
							</tbody>
						</table>
					</div>
				</div>


				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Applicant Electricity Connection Details</h3>
					</div>
					<div class="panel-body">
						<div class="row" style="font-size:14px;line-height:30px;">
							<div class="col-md-12">
								<div class="col-md-3">
									K No. 
								</div>
								<div class="col-md-9">
									: <?=$connection_dtls['elec_k_no']?$connection_dtls['elec_k_no']:"N/A";?>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<span style="font-weight: bold; color: red;"> OR </span>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Bind/Book No 
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['elec_bind_book_no']?$connection_dtls['elec_bind_book_no']:"N/A";?>
								</div>
								<div class="col-md-3">
									Account No  
								</div>
								<div class="col-md-3">
									: <?=$connection_dtls['elec_account_no']?$connection_dtls['elec_account_no']:"N/A";?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									Category Type 
								</div>
								<div class="col-md-9">
									: <?=$connection_dtls['elec_category']?$connection_dtls['elec_category']:"N/A";?>
								</div>
							</div>
						</div>
					</div>
				</div>
		
				<div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Document List</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th>Document</th>
                                        <th>Verify/Reject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=0;
                                foreach($applicant_doc_details as $doc)
                                {
                                    //Checking if consumer document
									
                                    $owner_name=NULL;
                                    if($doc["applicant_detail_id"]>0)
                                    { 
                                        $applicant_detail_id=$doc['applicant_detail_id'];
                                        $owner = array_filter($applicant_details, function ($var) use ($applicant_detail_id) {
                                            return ($var['id'] == $applicant_detail_id);
                                        });
										$owner = array_values($owner)[0]??[];
										//print_var($owner);die;
                                        $owner_name='<span class="text text-primary">('.$owner["applicant_name"].')</span>';
                                    }
                                    ?>
                                     <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$doc["document_name"];?> <?=$owner_name;?></td>
                                        <td>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["document_path"];?>" target="_blank" title="<?=$doc["document_name"];?>">
                                            <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
                                            </a>
                                        </td>
                                        <td>
                                        <?php
                                        
                                        if($doc["verify_status"]==1) // Approved
                                        {
                                            ?>
                                            <span class="text text-success text-bold">Approved</span>
                                            <?php
                                        }
                                        if($doc["verify_status"]==2) // Rejected
                                        {
                                            ?>
                                            <span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" data-original-title="<?=$doc["remarks"];?>">Rejected</span>
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
		
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<div class="col-sm-6">
							<h3 class="panel-title">Payment Details</h3>
						</div>
						<div class="col-sm-6 panel-title text-lg-right ">
							<button type="button" data-toggle="modal" data-target="#Site_Inspection" class="btn btn-warning" style="margin-top:4px;" ><i class="fa fa-arrow-left"></i><b> Site Inspection Details </b></button>
						</div>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
						<table class="table table-bordered" style="font-size:12px;">
						<tr>
							<th>S.No.</th>
							<th>Transaction No.</th>
							<th>Transaction Date</th>
							<th>Transaction Type</th>
							<th>Payment Mode</th>
							<th>Total Amount</th>
							<th>Penalty</th>
							<th>Rebate</th>
							<th>Paid Amount</th>
							<th>View</th>
						</tr>

						 <tr>
						   <?php
							if($transaction_details):
							  $i=1;
							  foreach($transaction_details as $val):
								$model_transection_no .= $val['transaction_no'].', ';
								$model_transection_total_paid += (!empty(trim($val['paid_amount']))?$val['paid_amount']:0);
								?>
								<tr>
										<td><?php echo $i;?></td>
										<td><?php echo $val['transaction_no'];?></td>
										<td><?php echo date('d-m-Y',strtotime($val['transaction_date']));?></td>
										<td><?php echo $val['transaction_type'];?></td>
										<td><?php echo $val['payment_mode'];?></td>
										<td><?php echo $val['total_amount'];?></td>
										<td><?php echo $val['penalty'];?></td>
										<td><?php echo $val['rebate'];?></td>
										<td><?php echo $val['paid_amount'];?></td>
										<td><a target="blank" href="<?php echo base_url('WaterPayment/view_transaction_receipt/'.md5($connection_dtls['id']).'/'.md5($val['id']));?>" style="color: #1919bd;">View</a></td>
								</tr>
										
								<?php
							  endforeach;
							  $model_transection_no = trim($model_transection_no,' ,');
							endif;

						   ?>
						 </tr>
					   </table>
						</div>
					</div>
				</div>
		
				<?= $this->include('water/water_connection/LevelRemarksTab');?>

				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Remarks</h3>
					</div>
					<div class="panel-body">
						<textarea type="text" placeholder="Enter Remarks" id="remarks_si" name="remarks_si" class="form-control"></textarea>
					</div>

					<?php 
					if($si_level_verify_dtls['verification_status']==0)
					{ ?>
						<div class="panel panel-bordered">
							<div class="panel-body">
								<div style="text-align: center">
									<button type="button" data-toggle="modal" data-target="#Site_Inspection" class="btn btn-primary">Schedule For Inspection</button>
									<input type="submit" name="Backward_si" id="Backward_si" value="Backward" class="btn btn-primary">
									<!-- <input type="submit" name="reject" id="reject" value="Reject" class="btn btn-danger"> -->
									<input type="submit" name="backtocitizen" id="backtocitizen" value="Back to Citizen" class="btn btn-warning">
									<a href="<?=base_url().'/WaterApplyNewConnection/updat_application/'.$app_id;?>" target="_blanck" class="btn btn-mint">Update Application</a>
									<?php 
										if(!empty($si_verify_dtls) && isset($si_verify_dtls['payment_status']) && $si_verify_dtls['payment_status']==0)
										{
											?>
										<!-- <a target='_blank'class="btn btn-success" href="<?php echo base_url('Water_report/tc_pay/'.md5($connection_dtls['id']));?>"> Pay Now</a> -->
										<?php 
										} 
										?>
										<?php 
										if($si_verify_dtls['verified_status']==1 and $si_verify_dtls['payment_status']==1)
										{ 
											?>
										<input type="submit" name="Forward_si" id="Forward_si" value="Forward" class="btn btn-success" >
										<?php 
										}
										else
										{	
											if($si_verify_dtls['verified_status']==1 and $si_verify_dtls['payment_status']==0)
											{	
												$status="Payment Pending".(isset($total_amount) && $total_amount>0 ?"( RS-/ $total_amount )":"" ); 
											}
											else if($si_verify_dtls['verified_status']==0)
											{
												$status="Verification Pending";
											}
											echo "<p style='color:red; font-weight:bold; font-size:17px;'>".$status."</p>";
											
										}
									?>
								</div>
							</div>
						</div>
					<?php 
					} 
					?>
						
				</div>

				

				</form>
			</div>
		</div>
    </div>
</div>

<!-- Modal start-->
<form method="post" id="site_inspection_form">
<div class="modal fade" id="Site_Inspection" role="dialog" style="width:95%;padding-left:60px;">
	<div class="modal-dialog" style="width:95%;padding-left:60px;">
<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Site Inspection Form</h4>
			</div>
			<div class="modal-body">
				<div id="printableArea" style="margin-bottom: 20px">
					<?php 
					if(empty($SI_date_time)):
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Send Site Inspection Date And Time To Citizen</h3>
						</div>
						<div class="panel-body">
							<?php $cur_dt = date("Y-m-d"); ?>
							<div class="col-md-12">
								<span style="font-weight: bold; color: red;"> 
									Please provide date of inspection to inform consumer, and please be on time you have specified.
								</span><br><br>
								<input type="hidden" name="related_id" id="related_id" value="<?php echo $connection_dtls['id']; ?>">
								<input type="hidden" name="mobile_no" id="mobile_no" value="<?php echo $applicant_details[0]['mobile_no']??null; ?>">
								<input type="hidden" name="app_id" id="app_id" value="<?php echo $app_id; ?>">
								<input type="hidden" name="inspection_curntdate" id="inspection_curntdate" value="<?php echo $cur_dt; ?>">
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									Inspection Date : <input name="inspection_date" id="inspection_date" value="" type="date" class="form-control">
								</div>
								<div class="col-md-4">
									Inspection Time : <input name="inspection_time" id="inspection_time" value="" type="time" class="form-control">
								</div>
								
								<div class="col-md-4">
									<br>
									<input type="submit" name="set_si" id="set_si" value="Set" class="col-sm-4 btn btn-primary">
								</div>
							</div>
						</div>
					</div>
					<?php else: ?>
						
					<input type="hidden" name="related_id" id="related_id" value="<?php echo $connection_dtls['id']; ?>">
					<input type="hidden" name="app_id" id="app_id" value="<?php echo $app_id; ?>">
					
					<div class="panel panel-bordered">
						<div class="panel-body">
							<input type="hidden" name="related_id" id="related_id" value="<?php echo $connection_dtls['id']; ?>">
							
							<div class="col-md-12">
								<div class="col-md-4">
									<span style="font-weight: bold; color: #f44336;">Inspection Date : </span> <span style="font-weight: bold; color: #179a07;"><?=date('d-m-Y', strtotime($SI_date_time['inspection_date']));?> </span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #f44336;">Inspection Time : </span><span style="font-weight: bold; color: #179a07;"> <?php echo $SI_date_time['inspection_time']; ?> </span>
								</div>
								<div class="col-md-4">
									
									<input type="submit" name="cancl_si" id="cancl_si" value="Cancel Site Inspection" class="btn btn-danger">
								</div>
							</div>
						</div>
					</div>

					<!-- ############################ -->

					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Background Information</h3>
						</div>
						<div class="panel-body">
							<div class="row" style="font-size:14px;line-height:30px;">
								<div class="col-md-12">
									<div class="col-md-3">
										Applicant Id <span style="font-weight: bold; color: red;"> </span>
									</div>
									<div class="col-md-3">
										: <?=$connection_dtls['application_no']?$connection_dtls['application_no']:"N/A";?>
									</div>
									<div class="col-md-3">
										Ward No   
									</div>
									<div class="col-md-3">
										: <?=$connection_dtls['ward_no']?$connection_dtls['ward_no']:"N/A";?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-3">
										Applicant Name<span style="font-weight: bold; color: red;"></span>
									</div>
									<div class="col-md-3">
										: <?=$model_owners?$model_owners:"N/A";?>
									</div>
									<div class="col-md-3">
									   Applicant Category <span style="font-weight: bold; color: red;"></span>
									</div>
									<div class="col-md-3">
										: <?=$connection_dtls['category']?$connection_dtls['category']:"N/A";?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-3">
										Guardian Name <span style="font-weight: bold; color: red;"></span>
									</div>
									<div class="col-md-9">
										: <?=$model_fathers?$model_fathers:"N/A";?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-3">
										Correspindence Address<span style="font-weight: bold; color: red;"></span>
									</div>
									<div class="col-md-9">
										: <?=$connection_dtls['address']?$connection_dtls['address']:"N/A";?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-3">
										Mobile No<span style="font-weight: bold; color: red;"></span>
									</div>
									<div class="col-md-9">
										: <?=$model_mobile?$model_mobile:"N/A";?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-3">
										Transaction No 
									</div>
									<div class="col-md-3">
										: <?=$model_transection_no?$model_transection_no:"N/A";?>
									</div>
									<div class="col-md-3">
										Total Paid Amount <span style="font-weight: bold; color: red;"></span>
									</div>
									<div class="col-md-3">
										: <?=$model_transection_total_paid?$model_transection_total_paid:"0";?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-3">
										Total Amount 
									</div>
									<div class="col-md-3">
										: <?=$connection_charge['total_connection_charge']?$connection_charge['total_connection_charge']:"0";?>
									</div>
									<div class="col-md-3">
										Net Due Amount
									</div>
									<div class="col-md-3">
										: <?=$connection_charge['unpaid_connection_charge']?$connection_charge['unpaid_connection_charge']:"0";?>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Upload TS Map</h3>
						</div>
						<div class="panel-body">
							<table cellpadding="1" cellspacing="1">
	
								<tr>
									<td width="180" style="width: 180px; height: 30px; font-weight: bold">Map Without Road Cutting</td>
									<td width="14"><b>:</b></td>
									<td><input type="radio" required name="map_type" id="north_map" value="Map_North_new.png" <?php echo isset($si_verify_dtls['ts_map']) && $si_verify_dtls['ts_map'] =='Map_North_new.png'?"checked":"";?>/></td>
									<td>
										<a>
											<img id="north_map1" onClick="but1();return false;" alt="North Map" src="<?=base_url("public/assets/img/water/RANCHI/");?>/Map_North_new.png" width="150" tooltip="Click here to view the Map" style="cursor: pointer" />
										</a>
									</td>
								</tr>
	
								<tr>
									<td width="180" style="width: 180px; height: 30px; font-weight: bold">Map With Road Cutting</td>
									<td width="14"><b>:</b></td>
									<td><input type="radio" name="map_type" id="south_map" required value="Map_South_new.png" <?php echo isset($si_verify_dtls['ts_map']) && $si_verify_dtls['ts_map'] =='Map_South_new.png'?"checked":"";?> /></td>
									<td>
										<a>
											<img id="south_map1" onClick="but2();return false;" alt="South Map" src="<?=base_url("public/assets/img/water/RANCHI/");?>/Map_South_new.png" width="150" tooltip="Click here to view the Map" style="cursor: pointer" />
										</a>
									</td>
								</tr>
	
								<br />
	
								&nbsp;
	
	
	
	
							</table>
						</div>
					</div>

					<!-- ############################ -->
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Verification Report</h3>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>SL. NO</th>
											<th>Particulars</th>
											<th>Proposed By Applicant</th>
											<th>Inspection Report</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>Property Type</td>
											<td><?=$connection_dtls['property_type']?$connection_dtls['property_type']:"N/A";?></td>
											<td>
												<select class="form-control" name="uses_type_id" id="uses_type_id" required onchange="show_flat_count(this.value)">
													<option value="">Select</option>
													<?php
													if(isset($property_type_list)){
													   foreach($property_type_list as $prp_list){
													?>
													<option value="<?=$prp_list['id'];?>" <?=(isset($si_verify_dtls))?$si_verify_dtls['property_type_id']==$prp_list["id"]?"SELECTED":"":"";?>><?=$prp_list['property_type'];?></option>
													<?php }
													} ?>
												</select>
											</td>
										</tr>
										<tr id="flat_count_box" style="display: none;">
											<td colspan="2"></td>
											<td>No. of Flats</td>
											<td><input type="text" name="flat_count" id="flat_count" class="form-control" placeholder="Enter No. of Flats" value="<?php echo isset($si_verify_dtls['flat_count'])?$si_verify_dtls['flat_count']:"";?>"></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Total Area in SQ. Ft</td>
											<td><?=$connection_dtls['area_sqft']?$connection_dtls['area_sqft']:"N/A";?></td>
											<td>
												<input name="areasqft" id="areasqft" value="<?=$si_verify_dtls['area_sqft']?$si_verify_dtls['area_sqft']:"";?>" type="text" class="form-control" required onKeyPress="return isNumberdecimal(event)" autocomplete="off" ><span id="errorareasqft" style="color:#F00"></span>
											</td>
										</tr>
										<input type="hidden" name="connection_through_id" id="connection_through_id" value="<?php echo $connection_dtls['connection_through_id'];?>">
										<tr>
											<td>3</td>
											<td>Distribution Pipeline Report</td>
											<td><?=$connection_dtls['pipeline_type']?$connection_dtls['pipeline_type']:"N/A";?></td>
											<td>
												<?php
												if(isset($pipeline_type_list)){
												   foreach($pipeline_type_list as $pip_list){
												?>
												<input type="radio" required="" name="new_pipeline" id="new_pipeline" value="<?=$pip_list['id'];?>" <?=(isset($si_verify_dtls))?$si_verify_dtls['pipeline_type_id']==$pip_list["id"]?"CHECKED":"":"";?>> <?=$pip_list['pipeline_type'];?>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											   <?php }
												} ?>
												
											</td>
										</tr>
										<tr>
											<td>4</td>
											<td>Distribution Pipeline Size (In MM)</td>
											<td>N/A</td>
											<td><input type="text" name="pipelinesize" id="pipelinesize" value="<?=$si_verify_dtls['pipeline_size']?$si_verify_dtls['pipeline_size']:"";?>" class="form-control" required onKeyPress="return isNumberdecimal(event)" autocomplete="off"></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Distribution Pipeline Size Type</td>
											<td>N/A</td>
											<td>
												<select class="form-control" name="pipe_type" id="pipe_type" required>
													<option value="<?=$si_verify_dtls['pipeline_size_type']?$si_verify_dtls['pipeline_size_type']:"";?>"><?=$si_verify_dtls['pipeline_size_type']?$si_verify_dtls['pipeline_size_type']:"Select";?></option>
													<option value="CI">CI</option>
													<option value="DI">DI</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>6</td>
											<td>Is Application Comes Under Regularization</td>
											<td><?=$connection_dtls['connection_type']?$connection_dtls['connection_type']:"N/A";?></td>
											<td>
												<select class="form-control" name="is_regularization" id="is_regularization" required>
													<option value="">Select</option>
													<?php
													if(isset($conn_type_list)){
													   foreach($conn_type_list as $con_typ_list){
													?>
													<option value="<?=$con_typ_list['id'];?>" <?=(isset($si_verify_dtls))?$si_verify_dtls['connection_type_id']==$con_typ_list["id"]?"SELECTED":"":"";?>><?=$con_typ_list['connection_type'];?></option>
													<?php }
													} ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>7</td>
											<td>Permissible Pipe Diameter</td>
											<td>N/A</td>
											<td>
												<select class="form-control" name="permissible_pipe_dia" id="permissible_pipe_dia" required>
													<option value="<?=$si_verify_dtls['pipe_size']?$si_verify_dtls['pipe_size']:"";?>"><?=$si_verify_dtls['pipe_size']?$si_verify_dtls['pipe_size']:"Select";?></option>
													<option value="15 MM">15 MM</option>
													<option value="20 MM">20 MM</option>
													<option value="25 MM">25 MM</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>8</td>
											<td>Permissible Pipe Quality</td>
											<td>N/A</td>
											<td>
												<select class="form-control" name="permissible_pipe_qlty" id="permissible_pipe_qlty" required>
													<option value="<?=$si_verify_dtls['pipe_type']?$si_verify_dtls['pipe_type']:"";?>"><?=$si_verify_dtls['pipe_type']?$si_verify_dtls['pipe_type']:"Select";?></option>
													<option value="GI">GI</option>
													<option value="HDPE">HDPE</option>
													<option value="PVC 80">PVC 80</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>9</td>
											<td>Permissible Ferule Size</td>
											<td>N/A</td>
											<td>
												<select class="form-control" name="ferrule_size" id="ferrule_size" required>
													<option value="">Select</option>
													<?php
													if(isset($ferrule_list)){
													   foreach($ferrule_list as $fer_list){
													?>
													<option value="<?=$fer_list['id'];?>" <?=(isset($si_verify_dtls))?$si_verify_dtls['ferrule_type_id']==$fer_list["id"]?"SELECTED":"":"";?>><?=$fer_list['ferrule_type'];?></option>
													<?php }
													} ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>10</td>
											<td>Road Type</td>
											<td>N/A</td>
											<td>
												<select class="form-control" name="road_type" id="road_type" required>
													<option value="<?=$si_verify_dtls['road_type']?$si_verify_dtls['road_type']:"";?>"><?=$si_verify_dtls['road_type']?$si_verify_dtls['road_type']:"Select";?></option>
													<option value="RMC">RMC</option>
													<option value="PWD">PWD</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>11</td>
											<td>Applicant Category</td>
											<td><?=$connection_dtls['category']?$connection_dtls['category']:"N/A";?></td>
											<td>
												<select class="form-control" name="applicant_category" id="applicant_category" required>
													<option value="<?=$si_verify_dtls['category']?$si_verify_dtls['category']:"";?>"><?=$si_verify_dtls['category']?$si_verify_dtls['category']:"Select";?></option>
													<option value="APL">APL</option>
													<option value="BPL">BPL</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div class="table-responsive">
						<table>
							<tbody>
								<tr>
									<td>
										I, .............................. being a Municipal PLI/JE, inspected the site for the
										provision of water supply system indicate above. I certify that this insepection was done within the guidlines established by RMC and was completed in a through and completed manner. I recommend this for technical approval.
									</td>
								</tr>
								<tr>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<td>
										Date of Inspection: &nbsp;<strong><?=date('d-m-Y', strtotime($SI_date_time['inspection_date']));?></strong>
										<span style="float: right">(Signature)</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div style="text-align: center">
						<?php 
						//echo $si_verify_dtls['payment_status'];
						if($si_verify_dtls['payment_status']==0)
						{
						?>
							<input type="submit" name="update_si" id="update_si" value="Save" class="btn btn-primary">
							<center id="wait" style="color:red; display:none" >Please wait, your request is being processed...</center>
							&nbsp;&nbsp;&nbsp;
						<?php
						}
						?>
						
						<input type="button" value="Print" class="btn btn-warning" onclick="printDiv('printableArea')">
					</div>
					
					<?php endif;  ?>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
</form>
	
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 


<script type="text/javascript">
	
	$(document).ready(function () 
    {	
		document. getElementById("cancl_si").onclick=function(){
			//alert('cancl_si');
			$('#site_inspection_form').validate({ // initialize the plugin
	        
			
				rules: {
					"uses_type_id":{
						required:false,
					},
					"areasqft":{
						required:false,
					},
					"new_pipeline":{
						required:false,
					},
					"pipelinesize":{
						required:false,
					},
					"pipe_type":{
						required:false,
					},
					"is_regularization":{
						required:false,
					},
					"permissible_pipe_dia":{
						required:false,
					},
					"permissible_pipe_qlty":{
						required:false,
					},
					"ferrule_size":{
						required:false,
					},
					"road_type":{
						required:false,
					},
					"applicant_category":{
						required:false,
					},
					"map_type":{
						required:false,
					}
					
					
				}


			});
		}

		document. getElementById("update_si").onclick=function(){
			// alert('update_si');
			$('#site_inspection_form').validate({ // initialize the plugin
	        
			
				rules: {
					"uses_type_id":{
						required:true,
					},
					"areasqft":{
						required:true,
					},
					"new_pipeline":{
						required:true,
					},
					"pipelinesize":{
						required:true,
					},
					"pipe_type":{
						required:true,
					},
					"is_regularization":{
						required:true,
					},
					"permissible_pipe_dia":{
						required:true,
					},
					"permissible_pipe_qlty":{
						required:true,
					},
					"ferrule_size":{
						required:true,
					},
					"road_type":{
						required:true,
					},
					"applicant_category":{
						required:true,
					}
					
					
				}


			});
			
			if($('#site_inspection_form').valid())
			{
				$('#update_si').hide();
				$('#wait').show();	
			}
			else{
				$('#update_si').show();
				$('#wait').hide();	
			}			
			return ($('#site_inspection_form').valid());
		}
	    // $('#site_inspection_form').validate({ // initialize the plugin
	        
			
	    //     rules: {
	    //     	"uses_type_id":{
	    //     		required:true,
	    //     	},
	    //     	"areasqft":{
	    //     		required:true,
	    //     	},
	    //     	"new_pipeline":{
	    //     		required:true,
	    //     	},
	    //     	"pipelinesize":{
	    //     		required:true,
	    //     	},
	    //     	"pipe_type":{
	    //     		required:true,
	    //     	},
	    //     	"is_regularization":{
	    //     		required:true,
	    //     	},
	    //     	"permissible_pipe_dia":{
	    //     		required:true,
	    //     	},
	    //     	"permissible_pipe_qlty":{
	    //     		required:true,
	    //     	},
	    //     	"ferrule_size":{
	    //     		required:true,
	    //     	},
	    //     	"road_type":{
	    //     		required:true,
	    //     	},
	    //     	"applicant_category":{
	    //     		required:true,
	    //     	}
	            
	            
	    //     }


	    // });

    });

</script>
<script>
    
  


    $(document).ready(function () 
    {

    	var property_type_id=$("#uses_type_id").val();
    	if(property_type_id==7)
    	{
    		$("#flat_count_box").show();
    	}
    	else
    	{
    		$("#flat_count_box").hide();
    	}

    $('#form_tc_verification').validate({ // initialize the plugin
        

        rules: {
        	"remarks_si":{
        		required:true,
        	}
            
        }


    });
	
<?php
	 if($isModalOpen=flashToast('isModalOpen')){
?>
	$('#Site_Inspection').modal('show');
<?php
	}
?>
	

});


    function show_flat_count(str)
    {
        var property_type=str;
     
        if(property_type==7)
        {
          $("#flat_count_box").show();
          $("#flat_count").attr("required",true);
        }
        else
        {
          $("#flat_count_box").hide();
          $("#flat_count").attr("required",false);
        }
        
    }
	
	
	$("#set_si").click(function() {
        var process = true;		
        var inspection_date = $("#inspection_date").val();
		var inspection_time = $("#inspection_time").val();
		var inspection_curntdate = $("#inspection_curntdate").val();
        if (inspection_date < inspection_curntdate) {
            $("#inspection_date").css({"border-color":"red"});
            $("#inspection_date").focus();
            process = false;
        }
		if (inspection_time == '') {
            $("#inspection_time").css({"border-color":"red"});
            $("#inspection_time").focus();
            process = false;
        }
		if(process==true)
		{ 	
			$("#set_si").hide();
		}		
		
        return process;
    });
    $("#inspection_date").change(function(){$(this).css('border-color','');});
	$("#inspection_time").change(function(){$(this).css('border-color','');});
	
	
	
	
	
	/*$("#update_si").click(function() {
        var process = true;

        var uses_type_id = $("#uses_type_id").val();
		var areasqft = $("#areasqft").val();
		var new_pipeline = $("#new_pipeline").val();
		var pipelinesize = $("#pipelinesize").val();
		var pipe_type = $("#pipe_type").val();
		var is_regularization = $("#is_regularization").val();
		var permissible_pipe_dia = $("#permissible_pipe_dia").val();
		var permissible_pipe_qlty = $("#permissible_pipe_qlty").val();
		var ferrule_size = $("#ferrule_size").val();
		var road_type = $("#road_type").val();
		var applicant_category = $("#applicant_category").val();
		
		if (uses_type_id == '') {
            $("#uses_type_id").css({"border-color":"red"});
            $("#uses_type_id").focus();
            process = false;
        }
		if (areasqft == '') {
            $("#areasqft").css({"border-color":"red"});
            $("#areasqft").focus();
            process = false;
        }
		if (new_pipeline == '') {
            $("#new_pipeline").css({"border-color":"red"});
            $("#new_pipeline").focus();
            process = false;
        }
		if (pipelinesize == '') {
            $("#pipelinesize").css({"border-color":"red"});
            $("#pipelinesize").focus();
            process = false;
        }
		if (pipe_type == '') {
            $("#pipe_type").css({"border-color":"red"});
            $("#pipe_type").focus();
            process = false;
        }
		if (is_regularization == '') {
            $("#is_regularization").css({"border-color":"red"});
            $("#is_regularization").focus();
            process = false;
        }
		if (permissible_pipe_dia == '') {
            $("#permissible_pipe_dia").css({"border-color":"red"});
            $("#permissible_pipe_dia").focus();
            process = false;
        }
		if (permissible_pipe_qlty == '') {
            $("#permissible_pipe_qlty").css({"border-color":"red"});
            $("#permissible_pipe_qlty").focus();
            process = false;
        }
		if (ferrule_size == '') {
            $("#ferrule_size").css({"border-color":"red"});
            $("#ferrule_size").focus();
            process = false;
        }
		if (road_type == '') {
            $("#road_type").css({"border-color":"red"});
            $("#road_type").focus();
            process = false;
        }
		if (applicant_category == '') {
            $("#applicant_category").css({"border-color":"red"});
            $("#applicant_category").focus();
            process = false;
        }
        return process;
    });
    $("#uses_type_id").change(function(){$(this).css('border-color','');});
	$("#areasqft").change(function(){$(this).css('border-color','');});
	$("#new_pipeline").change(function(){$(this).css('border-color','');});
	$("#pipelinesize").change(function(){$(this).css('border-color','');});
	$("#pipe_type").change(function(){$(this).css('border-color','');});
	$("#is_regularization").change(function(){$(this).css('border-color','');});
	$("#permissible_pipe_dia").change(function(){$(this).css('border-color','');});
	$("#permissible_pipe_qlty").change(function(){$(this).css('border-color','');});
	$("#ferrule_size").change(function(){$(this).css('border-color','');});
	$("#road_type").change(function(){$(this).css('border-color','');});
	$("#applicant_category").change(function(){$(this).css('border-color','');});
	
	
	$("#Backward_si").click(function() {
        var process = true;

        var remarks_si = $("#remarks_si").val();
		if (remarks_si == '') {
            $("#remarks_si").css({"border-color":"red"});
            $("#remarks_si").focus();
            process = false;
        }
        return process;
    });
	$("#remarks_si").change(function(){$(this).css('border-color','');});

	
	$("#Forward_si").click(function() {
        var process = true;

        var remarks_si = $("#remarks_si").val();
		if (remarks_si == '') {
            $("#remarks_si").css({"border-color":"red"});
            $("#remarks_si").focus();
            process = false;
        }
        return process;
    });
	$("#remarks_si").change(function(){$(this).css('border-color','');});
	
	*/
	
	
	function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
		alert();
        w=window.open();
        w.document.write(printContents);
        w.print();
        w.close();
    }
	function isNumberdecimal(evt) 
	{
		evtid=evt.target.id;
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57))
		{
			

		   $("#error"+evtid).html("Numbers Only").show().fadeOut("slow");
		   $("#"+evtid).val("");
			return false;
		}
		return true;
	}
</script>
