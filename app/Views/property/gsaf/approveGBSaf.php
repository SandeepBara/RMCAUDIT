<?php
echo $this->include('layout_vertical/header');
?>

<style type="text/css">
.error
{
    color:red ;
}
</style>


<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Inbox (GB SAF)</a></li>
            <li class="active"><a href="#">Verify GB SAF</a></li>
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
                            <?php echo $application_detail['assessment_type']; ?>
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
                                    
									//foreach($owner_details as $val)
								{
								?>
								<tr>
									<!-- <td><?php echo @$owner_details['officer_name'];?></td>
									<td><?php echo @$owner_details['mobile_no'];?></td> -->
									<td><?php echo @$owner_details['designation'];?></td>
									<td><?php echo @$owner_details['address'];?></td>
									
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
								<h3 class="panel-title">Tax Details</h3>
							</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                            <th scope="col">Sl No.</th>
                                            <th scope="col">ARV</th>
                                            <th scope="col">Effect From</th>
                                            <th scope="col">Holding Tax</th>
                                            <th scope="col">Water Tax</th>
                                            <th scope="col">Conservancy/Latrine Tax</th>
                                            <th scope="col">Education Cess</th>
                                            <th scope="col">Health Cess</th>
                                            <th scope="col">RWH Penalty</th>
                                            <th scope="col">Quarterly Tax</th>
                                            <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if($tax_list):
                                            $i=1; $qtr_tax=0; $lenght= sizeOf($tax_list);?>
                                        <?php foreach($tax_list as $tax_list): 
                                            $qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
                                        ?>
                                            <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $tax_list['arv']; ?></td>
                                            <td><?php echo $tax_list['qtr'];?>/<?php echo $tax_list['fy']; ?></td>
                                            <td><?php echo $tax_list['holding_tax']; ?></td>
                                            <td><?php echo $tax_list['water_tax']; ?></td>
                                            <td><?php echo $tax_list['latrine_tax']; ?></td>
                                            <td><?php echo $tax_list['education_cess']; ?></td>
                                            <td><?php echo $tax_list['health_cess']; ?></td>
                                            <td><?php echo $tax_list['additional_tax']; ?></td>
                                            <td><?php echo $qtr_tax; ?></td>
                                            <?php if($i>$lenght){ ?>
                                                <td style="color:red;">Current</td>
                                            <?php } else { ?>
                                                <td>Old</td>
                                            <?php } ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="11" style="text-align:center;color:red;"> Data Are Not Available!!</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
						</div>
				
            <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Verify Document</h3>
            </div>
           
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Document Name</th>
                                    <th>Uploaded On</th>
                                    <th>View</th>
                                    <th>Verify/Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=0;
                                foreach($doc_details as $doc_detail)
                                {
                                    ++$i;
                                    ?>
                                    <tr>
                                        <td>1</td>
                                        <td><?=$doc_detail['document_name'];?></td>
                                        <td><?=$doc_detail['created_on'];?></td>
                                        <td><a href="<?=base_url();?>/getImageLink.php?path=<?=$doc_detail['file_name'];?>" target="blank" class="btn btn-primary btn-sm"> View </a></td>
                                        <td>
                                            <?php
                                            if($doc_detail['verify_status']==0)
                                            {
                                                echo "<span class='text-primary'>Not-Verified</span>";
                                            }
                                            else if($doc_detail['verify_status']==1)
                                            {
                                                echo "<span class='text-success'>Verified</span>";
                                            }
                                            else if($doc_detail['verify_status']==2)
                                            {
                                                echo '<span class="text-danger" title="'.$doc_detail['remarks'].'">Rejected</span> ('.$doc_detail['remarks'].')';
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
                <h3 class="panel-title">Memo Details</h3>
            </div>
           
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Memo No</th>
                                    <th>Generated On</th>
                                    <th>ARV</th>
                                    <th>Quarterly Tax</th>
                                    <th>Effect From</th>
                                    <th>Memo Type</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=0;
                                foreach($memo_list as $row)
                                {
                                    ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$row['memo_no'];?></td>
                                        <td><?=date("Y-m-d", strtotime($row['created_on']));?></td>
                                        <td><?=$row['arv'];?></td>
                                        <td><?=$row['quarterly_tax'];?></td>
                                        <td><?=$row['effect_quarter'];?>/<?=$row['fy'];?></td>
                                        <td class="text-left"><?=$row['memo_type'];?></td>
                                        <td><a href="#" class="btn btn-primary" onclick="window.open('<?=base_url();?>/citizenPaymentReceipt/govt_da_eng_memo_receipt/<?=md5($ulb['ulb_mstr_id']);?>/<?=md5($row['id']);?>', 'newwindow', 'width=1000, height=1000'); return false;">View</a></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                </div>
            </div>
            
            <?php
            # If memo not generated
            if(empty($memo_list))
            {
                ?>
                <div class="panel-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-1"><label class="control-label text-left" for="ward No"><b>Remarks</b><span class="text-danger">*</span> </label></div>
                            <div class="col-md-11">
                                <textarea class="form-control" name="remarks" required></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="text-center">
                            <input type="submit" name="genertaeMemo" class="btn btn-primary" value="Generate Memo & Approve" />
                            <input type="submit" name="back_to_citizen" class="btn btn-primary" value="Back to citizen" />
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>
            
      

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 
echo $this->include('layout_vertical/footer');
?>
