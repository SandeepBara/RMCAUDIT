<style>
.error {
    color: red;
}
</style>

<?php

		 echo $this->include('layout_home/header');
	 
?>


<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    
    <!--Page content-->
    <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
					<div class="panel-control">
					
						<span class="btn btn-info"><a href="<?php echo base_url('WaterApplyNewConnectionCitizen/water_connection_view/'.md5($consumer_details['id']));?>"  style="color: white;">Back</a></span>
					</div>
                    <h3 class="panel-title" style="color: white;">Water Application Status</h3>
                </div>    
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<div class="col-md-6">
								<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?php echo $application_no;?></span>
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
                        <label class="col-md-2 bolder">Ward No.</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Holding No. </label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['holding_no']; ?> 
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Type of Connection</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_type']; ?>
                        </div>
                        <label class="col-md-2 bolder">Connection Through </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['connection_through']; ?> 
                       </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Property Type</label>
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
					<h3 class="panel-title"> Water Applicant Details</h3>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered" style="font-size:12px;">
						<thead class="bg-trans-dark text-dark">
							<th>Applicant Name</th>
							<th>Guardian Name</th>
							<th>Email Id</th>
							<th>Mobile No.</th>
							<th>State</th>
							<th>District</th>
							<th>City</th>
						</thead>
						<?php
						if($owner_details)
						{
						  foreach($owner_details as $val)
						  {
						?>
						<tr>
							<td><?php echo $val['applicant_name'];?></td>
							<td><?php echo $val['father_name'];?></td>
							<td><?php echo $val['email_id'];?></td>
							<td><?php echo $val['mobile_no'];?></td>
							<td><?php echo $val['state'];?></td>
							<td><?php echo $val['district'];?></td>
							<td><?php echo $val['city'];?></td>
						</tr>
						<?php
						  }
						}
						?>
					</table>
				</div>
            </div>
			<?php
      //print_r($conn_fee_charge);

			if($conn_fee_charge)
			{
			?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Connection Fee Details </h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size:12px;">
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
							?>
                        </tbody>
                    </table>
                </div>
            </div>

      <?php
     



      ?>
			<form method="post" action="<?php echo base_url('WaterPaymentCitizen/proceed_payment');?>" id="myform">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Proceed Payment </h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-size:14px;">
							<tr>
								<td>Water Connection Fee:</td>
								<td><?php echo $conn_fee_charge['amount'];?></td>
								<input type="hidden" name="conn_fee" id="conn_fee" value="<?php echo $conn_fee_charge['amount'];?>">
								<td>Penalty:</td>
								<td><?php echo $penalty;?></td>
								<input type="hidden" name="penalty" id="penalty" value="<?php echo $penalty;?>">
								<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $water_conn_id; ?>">
							</tr>
							<tr>
								<td>Rebate:</td>
								<td><?php echo $rebate;?></td>
								<input type="hidden" name="rebate" id="rebate" value="<?php echo $rebate;?>">
								<td>Total Payable Amount:</td>
								<td><?php echo $total_amount;?></td>
								<input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total_amount;?>">
							</tr>
							<tr>
								<td>Transaction Mode: <span style="color:red;">*</span></td>
								<td>
									<select name="payment_mode" id="payment_mode" class="form-control" onchange="show_cheque_details(this.value)">
										<option value="">Select</option>
										<option value="CASH">CASH</option>
										<option value="CHEQUE">CHEQUE</option>
										<option value="DD">DD</option>
									</select>
								</td>
							</tr>
							<span>
							<tr  id="cheque_details" style="display: none;">
								<td>Cheque No: <span style="color:red;">*</span></td>
								<td>
									<input type="text" name="cheque_no" id="cheque_no" class="form-control">
								</td>
								<td>Cheque Date: <span style="color:red;">*</span></td>
								<td><input type="date" name="cheque_date" id="cheque_date" class="form-control" onchange="validate_chqdt(this.value)"></td>
							</tr>
							<tr id="cheque_details2" style="display: none;">
								<td>Bank Name: <span style="color:red;">*</span></td>
								<td>
									<input type="text" name="bank_name" id="bank_name" class="form-control" onkeypress="return isAlpha(event);">
								</td>
								<td>Branch Name: <span style="color:red;">*</span></td>
								<td><input type="text" name="branch_name" id="branch_name" class="form-control" onkeypress="return isAlpha(event);"></td>
							</tr>
							</span>
							<input type="hidden" name="payment_for" id="payment_for" value="<?php echo $conn_fee_charge['charge_for'];?>">
							<input type="hidden" name="curr_date" id="curr_date" value="<?php echo $curr_date;?>">
							<tr style="text-align:center;">
								<td colspan="4" class="text-center">
									<!--<input type="submit" name="pay" id="pay" value="Pay" class="btn btn-success">-->
								</td>
							</tr>
						</table>
					</div>
				</div>
           </form>

           <?php
           
            }
            else
            {
           ?>

			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Water Connection Fee Details </h3>
                </div>
				<div class="row">
					<div class="col-md-12">
						<p style="color: green; text-align: center; font-weight: bold; font-size:14px;">No Dues</p>
					</div>
				</div>
			</div>

  <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Payment Details </h3>
                </div>
                <div class="table-responsive">
            
               <table class="table table-bordered" style="font-size:12px;">
                <tr>
                    <th>S. No.</th>
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
                        <td><a href="<?php echo base_url('WaterPaymentCitizen/view_transaction_receipt/'.$water_conn_id.'/'.md5($val['id']));?>" style="color: #1919bd;">View</a></td>
                  </tr>
                        
                   <?php
                      endforeach;
                    endif;

                   ?>
                 </tr>
               </table>
             </div>
          
</div>

    
           <?php
            }
           ?>
             
          

    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_home/footer');
  
 ?>


<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>

<script>


    
    $(document).ready(function () 
    {

      jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Letters only please"); 


    $('#myform').validate({ // initialize the plugin
       

        rules: {
          remarks:"required",

            bank_name: {
                required: true,
                lettersonly: true,

                
            },
            branch_name: {
                required: true,
               lettersonly: true,
            }
            ,
             cheque_no: {
                required: true,
               digits: true,
            }
            ,
            
            cheque_date:"required",
            
            payment_mode:"required",

        }


    });

});
</script>

<script type="text/javascript">
  

  function validate_chqdt(str)
  {
      var chq_dt=str;
      var curr_date=$("#curr_date").val();

      //  year = d.getFullYear();

     // alert(chq_dt);

      if(chq_dt<curr_date)
      {
          alert("Cheque Date should be greater than Current Date");
          $("#cheque_date").val("");

      }

  }
  function show_cheque_details(arg) {
    
    var payment_mode=arg;
    if(payment_mode=='CHEQUE')
    {
        $("#cheque_details").show();
        $("#cheque_details2").show();

    }
    else
    {
       $("#cheque_details").hide();
       $("#cheque_details2").hide();
    }
  }
</script>