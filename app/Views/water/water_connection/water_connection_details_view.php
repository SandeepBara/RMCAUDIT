<?=$this->include('layout_vertical/header');?>


<!--CONTENT CONTAINER-->
<div id="content-container">
  
    <!--Page content-->
    <div id="page-content">
            
        
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> 
                        Water Connection Details  
                        <?php echo ($user_type==1)?"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;apply_connection_id:$consumer_details[id]":null;?>
                    </h3>
                </div>
                <div class="panel-body">     
                    
                    <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                        <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;"> Your applied application no. is 
                                <span style="color: #ff6a00"><?php echo $consumer_details['application_no'];?></span>. 
                                You can use this application no. for future reference.
                        </span>
                        <br>
                        <br>
                        <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                            Current Status : <span style="color:#009900"><?php echo $application_status;?></span>
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
                            <?php 
                            // Apartment/Multi Stored Unit
                            if($consumer_details["property_type_id"]==7)
                            {
                                echo "($consumer_details[flat_count] Flat)";
                            }
                            ?>
                        </div>
                            <label class="col-md-2 bolder">Pipeline Type </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['pipeline_type']; ?> 
                        </div>
                    </div>
                
					<div class="row">
                        <label class="col-md-2 bolder">Category </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>
                            <label class="col-md-2 bolder">Owner Type </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['owner_type']; ?> 
                        </div>
                    </div>
                    <?php
                    //print_var($consumer_details);exit;
                    ?>
                    <div class="row">
                        <label class="col-md-2 bolder"> Apply From </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['apply_from']; ?> 
                        </div>
                        <label class="col-md-2 bolder"> Apply Date </label>
                        <div class="col-md-3 pad-btm">
							<?php echo $consumer_details['apply_date']; ?> 
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
                        <label class="col-md-2 bolder">Ward No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?>
                        </div>
                        <?php

							if(!in_array($consumer_details['prop_dtl_id'], [0, null,""]) || $consumer_details['holding_no']!=null)
							{
                                ?>
                                <label class="col-md-2 bolder">Holding No. </label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo strtoupper($consumer_details['holding_no']); ?> 
                                </div>
                                <?php   
							}
							else if($consumer_details['saf_no']!=null)
							{
                                ?>
                                <label class="col-md-2 bolder">SAF No. </label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo $consumer_details['saf_no']; ?> 
                                </div>
                                <?php
							}
                        ?>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Area in Sqft.</label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['area_sqft']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Area in Sqmt.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo round($consumer_details['area_sqmt'],2); ?> 
                        </div>
                    </div>
                   <div class="row">
                        <label class="col-md-2 bolder">Address</label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['address']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Landmark </label>
                        <div class="col-md-3 pad-btm">
                           <?php echo $consumer_details['landmark']; ?> 
                        </div>
                    </div>
					<div class="row">
						<label class="col-md-2 bolder">Pin</label>
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
					<div class="panel-body table-responsive">
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

				
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Electricity Connection Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-2 bolder">K No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_k_no']; ?>
                            </div>
                            <label class="col-md-2 bolder">Bind Book No.</label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_bind_book_no']; ?> 
                        </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Electricity Account No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_account_no']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Electricity Category </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_category']; ?> 
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Site Inspection </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Inspected By</th>
                                                <th>Inspected On</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i=0;
                                            if($site_inspection_details)
                                            foreach($site_inspection_details as $inspection)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$inspection["verified_by"];?></td>
                                                    <td><?=$inspection["inspection_date"];?></td>
                                                    <td>
                                                        <?php
                                                        if($inspection["verified_by"]=="JuniorEngineer")
                                                        {
                                                            ?>
                                                            <a onClick="myPopup('<?=base_url('WaterSiteInspection/index/'.md5($consumer_details['id']).'/'.md5($inspection['id']));?>','xtf','900','700');" class="btn btn-primary">
                                                                View
                                                            </a>
                                                            <?php
                                                        }
                                                        if($inspection["verified_by"]=="AssistantEngineer")
                                                        {
                                                            ?>
                                                            <a onClick="myPopup('<?=base_url('WaterTechnicalSiteInspection/view/'.md5($consumer_details['id']).'/'.md5($inspection['id']));?>','xtf','900','700');" class="btn btn-primary">
                                                                View
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text text-danger text-center">Data Are Not Available!!</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Payment Details </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Transaction No</th>
                                                <th>Transaction Type</th>
                                                <th>Payment Mode</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i=0;
                                            if($transaction)
                                            foreach($transaction as $trxn)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$trxn["transaction_no"];?></td>
                                                    <td><?=$trxn["transaction_type"];?></td>
                                                    <td><?=$trxn["payment_mode"];?></td>
                                                    <td><?=$trxn["transaction_date"];?></td>
                                                    <td><?=$trxn["paid_amount"];?></td>
                                                    <td><a href="<?=base_url("WaterPayment/view_transaction_receipt/".md5($trxn["related_id"])."/".md5($trxn["id"]));?>" class="btn btn-primary"> View </a></td>
                                                </tr>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text text-danger text-center">Data Are Not Available!!</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if(isset($level))
                {
                    ?>
                    <div class="panel panel-bordered panel-dark">
                        <div data-toggle="collapse" data-target="#demo" role="type">
                            <div class="panel-heading">
                                <h3 class="panel-title">Level Remarks
                                </h3>
                            </div>
                        </div>
                        
                        <div class="panel-body collapse" id="demo">
                            <div class="nano has-scrollbar" style="height: 60vh">
                                <div class="nano-content" tabindex="0" style="right: -17px;">
                                    <div class="panel-body chat-body media-block">
                                        <?php
                                        $i=0;
                                        foreach($level as $row)
                                        {
                                            ++$i;
                                            ?>
                                            <div class="chat-<?=($i%2==0)?"me":"user";?>">
                                                <div class="media-left">
                                                    <img src="<?=base_url("public/assets/img/")?>/<?=$row["user_type"];?>.png" class="img-circle img-sm" alt="<?=$row["user_type"];?>" title="<?=$row["user_type"];?>" loading="lazy" />
                                                </div>
                                                <div class="media-body">
                                                    <div>
                                                        <p><?=$row["remarks"];?><small>
                                                        <?=date("g:iA", strtotime($row["forward_time"]));?> <?=date("d M, Y", strtotime($row["forward_date"]));?></small></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            
                                        }
                                        ?>
                                    </div>
                                </div>
                            <div class="nano-pane"><div class="nano-slider" style="height: 61px; transform: translate(0px, 0px);"></div></div></div>
                                
                        </div>
                    </div>
                    <?php
                }
                
                ?>

                
                <div class="panel">
                    <div class="panel-body text-center">

                        <?php
                        # print_var($site_inspection_details);
                        // 1	Super Admin
                        // 2	Admin
                        // 3	Project Manager
                        // 4	Team Leader
                        // 5	Tax Collector
                        // 6	Dealing Assistant
                        // 7	ULB Tax Collector
                        // 8	Jsk
                        // 11   Back Office
                        if($application_status=='Approved by Executive Officer')
                        {
                            ?>
                            <a onClick="myPopup('<?=base_url('WaterApplyNewConnection/view_memo/'.md5($consumer_details['id']));?>','xtf','900','700');" class="btn btn-primary">
                            View Memo
                            </a>
                            <?php
                        }
                        if(in_array($user_type, [1, 11]) && ($consumer_details['payment_status']==0 || $consumer_details['level_pending_status']==2)) //level_pending_status=2 mean backtocitizen
                        {
                            
                            ?>
                            <a class="btn btn-primary" href="<?php echo base_url('WaterUpdateApplicationNew/index/'.md5($consumer_details["id"]));?>"> Update Application </button>
                            <?php
                        }

                        if(in_array($user_type, [1, 11]) && ($consumer_details['doc_status']==0 || $consumer_details['level_pending_status']==2 ) ) //level_pending_status=2 mean backtocitizen
                        {
                            ?>  
                            <!-- <a class="btn btn-danger" href="<?php echo base_url('WaterDocument/doc_upload/'.md5($consumer_details["id"]));?>" style="margin-left: 3px;"> Upload Documents </a> -->
                            <a class="btn btn-primary" href="<?php echo base_url('WaterDocument/WaterdocumentUpload/'.md5($consumer_details["id"]));?>" style="margin-left: 3px;"> Upload Documents </a>
                            <?php
                        }

                        if($consumer_details['doc_status']==1)
                        {
                            ?>
                            <a class="btn btn-primary mr-1" href="<?php echo base_url('WaterDocument/ViewDocument/'.md5($consumer_details["id"]));?>" style="margin-left: 3px;"> View Documents </a>
                            <?php
                        }
                        

                        if(($consumer_details['payment_status']==0 || $application_status=='Payment Pending of Diff Amount at Site Inspection' || $pay) && in_array($user_type, [1, 4,5, 8]))
                        {
                            ?>
                            <a class="btn btn-primary" href="<?php echo base_url('WaterPayment/payment/'.md5($consumer_details["id"]));?>"> Proceed to Payment </a>
                            <?php 
                        }

                        if(in_array($user_type, [1, 2]))
                        {
                            
                            ?>
                            
                            <a class="btn btn-primary" href="<?php echo base_url('WaterNewConnectionDeactivation/view/'.md5($consumer_details["id"]));?>"> Deactivate </a>
                            <?php
                        }
                        ?>
                    
                    </div>
                </div>  


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<script type="text/javascript">
function myPopup(myURL, title, myWidth, myHeight)
{
    var left = (screen.width - myWidth) / 2;
    var top = (screen.height - myHeight) / 4;
    var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}
</script>
<?=$this->include('layout_vertical/footer');?>
