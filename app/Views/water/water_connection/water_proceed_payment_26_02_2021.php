<style>
.error {
  
    color: red;
}
</style>

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
						<?php
                        if($user_type!=5)
                        {
                          $link='WaterApplyNewConnection/water_connection_view/'.$water_conn_id;
                        }
                        else
                        {
                           $link='WaterSearchApplicantsMobile/search_applicants_tc';
                        }
						?>
						<span class="btn btn-info"><a href="<?php echo base_url($link);?>"  style="color: white;">Back</a></span>
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

			if($consumer_details['payment_status']==0 or isset($conn_fee_charge))
			{
			?>

      <?php
     

      if($user_type==5 or $user_type==1 or $user_type==8 or $user_type==4)
      {


      ?>
			<form method="post" action="<?php echo base_url('WaterPayment/proceed_payment');?>" id="myform">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Proceed Payment </h3>
					</div>
					<div class="table-responsive">
            <p style="color: green; font-size: 15px; text-align: center; font-weight: bold;">Note: 10% rebate on penalty in case whole penalty is paid</p>
            <br>
						<table class="table table-bordered" style="font-size:14px;">
							<tr>
								<td>Water Connection Fee:</td>
								<td><?php echo $conn_fee_charge['conn_fee'];?></td>
								<input type="hidden" name="conn_fee" id="conn_fee" value="<?php echo $conn_fee_charge['conn_fee'];?>">
								<td>Penalty:</td>
								<td><?php echo $conn_fee_charge['penalty']+$penalty;?></td>
								<input type="hidden" name="penalty" id="penalty" value="<?php echo $penalty;?>">
								<input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $water_conn_id; ?>">
               
							</tr>
							<tr style="color: #1b1b5d; font-weight: bold;font-size: 17px;">
								
								<input type="hidden" name="rebate" id="rebate" value="<?php echo $rebate;?>">
								<td >Total Due:</td>
								<td ><span id="total_due"><?php echo $total_amount;?></span></td>
								<input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total_amount;?>">
                <td>Rebate:</td>
                <td id="rebate_text">0</td>

							</tr>

              <tr>

                <td>Pay Full</td>
                <td>
                  <input type="radio" name="pay_full" id="pay_full" value="1" onclick="enable_installment(this.value)">Yes
                  <input type="radio" name="pay_full" id="pay_full" value="0" onclick="enable_installment(this.value)">No

                </td>

                <td id="total_payable_text" style="color: green; font-weight: bold; font-size: 17px;">Total Payable : </td>
                <td id="total_payable" style="color: green; font-weight: bold; font-size: 17px;">0</td>
              </tr>


              <tr id="installment_block" style="display: none;">
                
                <td>Penalty Installment</td>
                <td>
                  <select name="penalty_installment_upto_id" id="penalty_installment_upto_id" class="form-control" onchange="getTotalPayable(this.value)">
                    <option value="">SELECT</option>
                   <?php
                    if($penalty_installment)
                    {   
                        $installment_amount=0;
                        foreach($penalty_installment as $val)
                        {

                          $installment_amount=$installment_amount+$val['installment_amount'];

                   ?>
                   <option value="<?php echo $val['id'];?>"><?php echo $installment_amount;?></option>
                   <?php       

                        }
                    }
                   ?>
                    
                  </select>
                </td>
              </tr>

							<tr>
								<td>Payment Mode: <span style="color:red;">*</span></td>
								<td>
									<select name="payment_mode" id="payment_mode" class="form-control" onchange="show_cheque_details(this.value)">
										
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
              <input type="hidden" name="installment" id="installment" >
							<tr style="text-align:center;">
								<td colspan="4" class="text-center">
									<input type="submit" name="pay" id="pay" value="Pay" class="btn btn-success">
								</td>
							</tr>
						</table>
					</div>
				</div>
           </form>

           <?php
            }
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
                        <td><a href="<?php echo base_url('WaterPayment/view_transaction_receipt/'.$water_conn_id.'/'.md5($val['id']));?>" style="color: #1919bd;">View</a></td>
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
	if($user_type==4 || $user_type==5 || $user_type==7)
	{

		echo $this->include('layout_mobi/footer');
	}
	else
	{
		echo $this->include('layout_vertical/footer');
	}
  
 ?>


<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>

<script>

     function enable_installment(argument)
     {

          var is_checked=argument;
          //alert(is_checked);

          if(is_checked==1)
          {
              $("#installment_block").hide();
              
             // $("#total_payable").text(Math.round($("#total_due").text()));
              var apply_connection_id=$("#water_conn_id").val();
              
              $.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterPayment/penaltyRebate");?>',
                dataType: "json",
                data: {
                        "apply_connection_id":apply_connection_id
                },
                beforeSend: function() {
                  $("#loadingDiv").show();
                },
                
                success:function(data){

                  // console.log(data);
                  //alert(data.installment_rebate);
                  $("#loadingDiv").hide();

                  $("#rebate_text").text(data.installment_rebate);
                 // alert(($("#total_due").text());
                  $("#total_payable").text(Math.round(parseFloat($("#total_due").text())-parseFloat(data.installment_rebate)));
                  
                }
                         
              });


          }
          else
          {
              $("#rebate_text").text("0");
              $("#total_payable").text("0");
              $("#installment_block").show();
              $("#penalty_installment_upto_id").val('');
          }

     }

    
    $(document).ready(function () 
    {

        jQuery.validator.addMethod("lettersonly", function(value, element) {
          return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Letters only please"); 


    $('#myform').validate({ // initialize the plugin
        

        rules: {
          remarks:"required",
          penalty_installment_upto_id:"required",

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
  
  
  function getTotalPayable(argument) {
      
      if(!argument)
      {
          $("#rebate_text").val('');
          $("#total_payable").val('');
      }
      $("#pay").prop("disabled",true);
      var installment_upto_id=argument;
      var apply_connection_id=$("#water_conn_id").val();
      
      var conn_fee=$("#conn_fee").val();
      //alert(conn_fee);

      $.ajax({
            type:"POST",
            url: '<?php echo base_url("WaterPayment/getTotalPayable");?>',
            dataType: "json",
            data: {
                    "installment_upto_id":installment_upto_id,"apply_connection_id":apply_connection_id
            },
            beforeSend: function() {
              $("#loadingDiv").show();
            },
            
            success:function(data){

              //console.log(data);
              //alert(data);
              //alert(data.installment_rebate);
              
              $("#loadingDiv").hide();
              if(data.installment_rebate>0)
              {
                  $("#rebate_text").text(data.installment_rebate);
              }
              $("#total_payable").text(Math.round(parseFloat(conn_fee)+parseFloat(data.penalty)-parseFloat(data.installment_rebate)));    

              $("#pay").prop("disabled",false); 
            }
                     
          });


  }

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
  function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php 
    if($payment=flashToast('payment'))
    {
        echo "modelInfo('".$payment."');";
    }
  ?>
  
</script>

