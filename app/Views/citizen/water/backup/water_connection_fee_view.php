
<?php

  session_start();
  echo $this->include('layout_home/header');
 

?>


<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">View Water Connection</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
					<div class="panel-control">
						<span class="btn btn-info"><a href="<?php echo base_url('WaterApplyNewConnectionCitizen/water_connection_view/'.$water_conn_id);?>"  style="color: white;">Back</a></span>
					</div>
                    <h3 class="panel-title" style="color: white;">Water Application Status</h3>
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
                    <h3 class="panel-title"> Water Connection Fee Details </h3>
                </div>
                <div class="panel-body">
                      <table class="table table-bordered table-responsive">
                        <thead class="bg-trans-dark text-dark">
							<th>Proc Fee</th>
							<th>Reg Fee</th>
							<th>App Fee</th>
							<th>Sec Fee</th>
							<th>Conn Fee</th>
							<th>Total Fee</th>
                        </thead>
                        <tbody>
                          <?php
                          if($conn_fee_charge_details)
                          {
                           ?>
                          <tr>
                              <td><?php echo $conn_fee_charge_details['proc_fee'];?></td>
                              <td><?php echo $conn_fee_charge_details['reg_fee'];?></td>
                              <td><?php echo $conn_fee_charge_details['app_fee'];?></td>
                              <td><?php echo $conn_fee_charge_details['sec_fee'];?></td>
                              <td><?php echo $conn_fee_charge_details['conn_fee'];?></td>
                              <td><?php echo $conn_fee_charge['amount'];?></td>
                              
                          </tr>

                          <?php 
                          }
                          else
                          {
                          ?>
                           <tr>
                              <td>0.00</td>
                              <td>0.00</td>
                              <td>0.00</td>
                              <td>0.00</td>
                              <td>0.00</td>
                              <td>0.00</td>
                              
                          </tr>
                          <tr>
                              <td colspan="6" style="color: green; font-weight: bold; font-size: 17px; text-align: center;">No Dues</td>

                          </tr>


                          <?php  
                          }
                          ?>
                         
                        </tbody>
                      </table>
                 
                   
                  
                </div>
            </div>
           

          
            <div class="panel">
				<div class="panel-body text-center">
                    <a href="<?php echo base_url('WaterApplyNewConnectionCitizen/water_connection_view/'.$water_conn_id);?>" class="btn btn-primary">BACK</a>
                </div>
            </div>
       
           
          

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_home/footer');

 ?>
