
<?php
session_start();
    
 
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
            <li><a href="#">View Water Connection</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->

    <?php 
        print_r($consumer_details);
    ?>
    <div id="page-content">
            
            
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="col-sm-6">
                        <h3 class="panel-title"> Water Connection Details View</h3>
                    </div>

                      <div class="col-sm-6" style="text-align: right; ">
                        <a href="<?php echo base_url('WaterViewConsumerDetails/index/'.md5($consumer_details['id']));?>" class="btn btn-info">Back</a>
                    </div>
                </div>
                <div class="panel-body">   

                    <div class="row">
                        <label class="col-md-2 bolder">Consumer No./ Application No.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo isset($consumer_details['consumer_no'])?$consumer_details['consumer_no']:$consumer_details['application_no']; ?>
                        </div>

                        <label class="col-md-2 bolder">Ward No.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?>
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
                    
                    <div class="row">
                        <label class="col-md-2 bolder">Category </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
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
                        <h3 class="panel-title"> Transaction Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-2 bolder">Transaction No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['transaction_no']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Transaction Amount </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['paid_amount']; ?> 
                            </div>
                            
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Payment Mode </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['payment_mode']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Cheque No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['cheque_no']; ?> 
                            </div>
                        </div>
                         <div class="row">
                            <label class="col-md-2 bolder">Cheque Date </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['cheque_date']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Bank Name </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['bank_name']; ?> 
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Branch Name </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $transaction_details['branch_name']; ?> 
                            </div>
                           
                        </div>

                    </div>
                </div>

                <div class="clear"></div>

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Update Cheque Details</h3>
                    </div>
                    <form method="post" id="cheque_update_form" >
                        <div class="panel-body">
                            <div class="row">
                                <label class="col-md-2 bolder">Cheque No.</label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control" onkeypress="return isAlphaNum(event);" value="<?php echo $transaction_details['cheque_no']; ?>">
                                </div>
                                <label class="col-md-2 bolder">Cheque Date</label>
                                <div class="col-md-3 pad-btm">
                                    <input type="date" name="cheque_date" id="cheque_date" class="form-control"  value="<?php echo $transaction_details['cheque_date']; ?>">
                                </div>
                               
                            </div>
                            <input type="hidden" name="cheque_dtl_id" id="cheque_dtl_id" value="<?php echo $transaction_details['cheque_dtl_id']; ?>">
                            <div class="row">
                                <label class="col-md-2 bolder">Bank Name</label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text" name="bank_name" id="bank_name" class="form-control" onkeypress="return isAlpha(event);" value="<?php echo $transaction_details['bank_name']; ?>">
                                </div>
                                <label class="col-md-2 bolder">Branch Name</label>
                                <div class="col-md-3 pad-btm">
                                    <input type="text" name="branch_name" id="branch_name" class="form-control" onkeypress="return isAlpha(event);" value="<?php echo $transaction_details['branch_name']; ?>">
                                </div>
                               
                            </div>
                            
                            <div class="row">
                                <input type="submit" name="update_cheque" id="update_cheque" class="btn btn-success">
                            </div>
                            
                        </div>
                    </form>
                </div>
	


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php echo $this->include('layout_vertical/footer'); ?>
<script type="text/javascript">
    
$(document).ready(function () {

    

    
    jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please"); 



    $('#cheque_update_form').validate({ // initialize the plugin
        rules: {

            "cheque_no":"required",
            "cheque_date":"required",
            "bank_name":"required",
            "branch_name":"required",
            
            
        }
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