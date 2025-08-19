<?php
if($user_type==4 || $user_type==5 || $user_type==7)
{
    echo $this->include('layout_mobi/header');
}
else
{
    echo $this->include('layout_vertical/header');
}
?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.$water_conn_id);?>">Water Connection Detail</a></li>
            <li class="active"><a href="#">View Connection Fee</a></li>
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
                        <label class="col-md-2 bolder">Application No</label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php echo $consumer_details['application_no']; ?>
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
                    <h3 class="panel-title"> Water Connection Fee Details </h3>
                </div>
                <div class="panel-body">
                      <table class="table table-condensed" style="width: 50%; margin-left: auto;  margin-right: auto; border: 2px;">
                        <thead>
                            <tr>
                                <th>Particular</th>
                                <th>Charge</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                          if($conn_fee_charge)
                          {
                                ?>
                                    <tr>
                                        <td>Connection Fee</td>
                                        <td><?php echo number_format($conn_fee_charge['conn_fee'], 2, '.', '');?></td>
                                    </tr>
                                    <tr>
                                        <td>Penalty</td>
                                        <td><?php echo number_format($conn_fee_charge['penalty'], 2, '.', '');?></td>
                                    </tr>
                                    <tr>
                                        <td class="text text-success text-bold">Total Amount</td>
                                        <td class="text text-success text-bold"><?php echo number_format($conn_fee_charge['amount'], 2, '.', '');?></td>
                                    </tr>
                                <?php 
                          }
                          ?>
                         
                        </tbody>
                      </table>
                 
                   
                  
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
