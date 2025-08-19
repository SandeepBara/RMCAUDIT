<?php
  //session_start();
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
  echo $this->include('layout_mobi/header');
?>

<style type="text/css">
/*
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
  */
  .bolder{font-weight: bold;}
  
</style>
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
						<span class="btn btn-info"><a href="<?php echo base_url('WaterApplyNewConnectionMobi/water_connection_view/'.$water_conn_id);?>"  style="color: white;">Back</a></span>
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
                    <h3 class="panel-title"> Water Connection Fee Details </h3>
                </div>
                <div class="panel-body">
                      <table class="table table-bordered table-responsive table-striped">
                        <tbody>
                          <?php
                          if($conn_fee_charge)
                          {
                                ?>
                                <tr>
                                    <td>Connection Fee</td>
                                    <td style="text-align: right; "><?php echo $conn_fee_charge['conn_fee'];?></td>
                                </tr>
                                <tr>
                                    <td style="">Penalty</td>
                                    <td style="text-align: right; "><?php echo $conn_fee_charge['penalty'];?></td>
                                </tr>
                                <tr class="text text-success">
                                    <td style="">Total Amount</td>
                                    <td style="text-align: right; "><?php echo $conn_fee_charge['amount'];?></td>
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
                    <a href="<?php echo base_url('WaterApplyNewConnectionMobi/water_connection_view/'.$water_conn_id);?>" class="btn btn-primary">BACK</a>
                </div>
            </div>
       
           
          

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_mobi/footer');

 ?>
