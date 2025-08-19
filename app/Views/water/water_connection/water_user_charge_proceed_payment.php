
<?php
session_start();

     echo $this->include('layout_vertical/header');

 

?>
<style type="text/css">
    .error{
        color: red;
    }
</style>
<!--<style type="text/css">
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
  .bolder{font-weight: bold;}
  
</style>-->
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
                    <h3 class="panel-title"> Water Connection Details View</h3>
                </div>
                <div class="panel-body">   

                    <div class="row">
                        <label class="col-md-2 bolder">Consumer No.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['consumer_no']; ?>
                        </div>

                        <label class="col-md-2 bolder">Category <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['category']; ?> 
                        </div>

                      

                    </div>

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
                
				
                </div>
            </div>
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Property Details</h3>
                </div>
                <div class="panel-body">                     
                    <div class="row">
                        <label class="col-md-2 bolder">Ward No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?>
                        </div>
                        <?php
							if($consumer_details['prop_dtl_id']!="")
							{
                        ?>
                        <label class="col-md-2 bolder">Holding No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['holding_no']; ?> 
						</div>
                        <?php   
							}
							else
							{
						?>
                        <label class="col-md-2 bolder">SAF No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['saf_no']; ?> 
                        </div>
                        <?php
							}
                        ?>
                    </div>
                    <div class="row">
                        <label class="col-md-2 bolder">Area in Sqft.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['area_sqft']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Area in Sqmt.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['area_sqmt']; ?> 
                        </div>
                    </div>
                   <div class="row">
                        <label class="col-md-2 bolder">Address<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $consumer_details['address']; ?> 
                        </div>
                        <label class="col-md-2 bolder">Landmark <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                           <?php echo $consumer_details['landmark']; ?> 
                        </div>
                    </div>
					<div class="row">
						<label class="col-md-2 bolder">Pin<span class="text-danger">*</span></label>
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
								{
									foreach($consumer_owner_details as $val)
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
                        <h3 class="panel-title"> Proceed Payment</h3>
                    </div>
                    <div class="panel-body">
<?php 

    if($due_from!="")
    {
 ?>

                <form method="post" action="<?php echo base_url("WaterUserChargePayment/pay_user_charge"); ?>" id="myform">


                    <input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_details['id']; ?>">

                    <input type="hidden" name="ward_mstr_id" id="ward_mstr_id" value="<?php echo $consumer_details['ward_mstr_id']; ?>">

                   <input type="hidden" name="due_from" id="due_from" value="<?php echo $due_from; ?>">

                   <div class="row">
                    <div class="col-md-12">
                   <div class="col-md-3">Upto Month</div>

                   <div class="col-md-3">
                       <select name="month" id="month" class="form-control" onchange="get_amount(this.value)">
                           <option value="">Select</option>
                             <?php
                      if($due_details)
                      {
                        foreach($due_details as $val)
                        {

                    ?>

                    <option value="<?php echo $val['generation_date'];?>"><?php echo date("F",strtotime($val['generation_date']));?></option>

                    <?php   
                        } 
                      }
                    ?>
                       </select>
                   </div>

                   <div class="col-md-3">Amount</div>
                   <div class="col-md-3"><input type="text" name="amount" id="amount" class="form-control" readonly="readonly"></div>
                   



                   </div>

                   </div>

                   <div class="row">
                       <div class="col-md-12">
                         

                            <div class="col-md-3">Penalty</div>
                            <div class="col-md-3">
                                <input type="text" name="penalty" id="penalty" class="form-control" readonly="readonly" value="<?php echo $penalty; ?>">
                            </div>
                            <div class="col-md-3">Rebate</div>
                            <div class="col-md-3">
                                <input type="text" name="rebate" id="rebate" class="form-control" readonly="readonly" value="<?php echo $rebate;?>">
                            </div>
                            
                            
                       </div>

                   </div>

                   <div class="row">
                       <div class="col-md-12">
                           <div class="col-md-3">Payment Mode</div>
                           <div class="col-md-3">
                                <select name="payment_mode" id="payment_mode" class="form-control" onchange="show_hide_cheque_details(this.value)">
                                <option value="">Select</option>
                                <option value="CASH">CASH</option>
                                <option value="CHEQUE">CHEQUE</option>
                                <option value="DD">DD</option>

                                </select>
                            </div>

                       </div>

                   </div>

                   <div class="row" id="chq_dtls1" style="display: none;">
                       <div class="col-md-12">
                           <div class="col-md-3">Cheque/DD No.</div>
                           <div class="col-md-3">
                               <input type="text" name="cheque_no" id="cheque_no" class="form-control" onkeypress="return isNum(event);">
                           </div>

                           <div class="col-md-3">Cheque/DD Date</div>
                           <div class="col-md-3">
                               <input type="date" name="cheque_date" id="cheque_date" class="form-control" >
                           </div>

                       </div>

                   </div>

                     <div class="row" id="chq_dtls2" style="display: none;">

                       <div class="col-md-12">

                           <div class="col-md-3">Bank Name</div>
                           <div class="col-md-3">
                               <input type="text" name="bank_name" id="bank_name" class="form-control" onkeypress="return isAlpha(event);">
                           </div>

                           <div class="col-md-3">Branch Name</div>
                           <div class="col-md-3">
                               <input type="text" name="branch_name" id="branch_name" class="form-control" onkeypress="return isAlpha(event);">
                           </div>

                       </div>

                   </div>

                   
                   <div class="row">
                       <div class="col-md-12">
                           <input type="submit" name="pay" id="pay" value="Pay" class="btn btn-success">
                       </div>

                   </div>



               </form>

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

      
            <div class="panel">
				<div class="panel-body text-center">


                   
                    
                   

                    <?php 
                    
                    if($user_type!=5)
                    {
                    ?>

                   

                    <div class="col-md-3">

                     <a href="<?php echo base_url('WaterViewConsumerDueDetails/transactionDetails/'.md5($consumer_details['id']));?>" class="btn btn-info">View Transaction</a>

                    </div>

                    <?php
                    }
                    ?>
                    <div class="col-md-3">

                        <a href="<?php echo base_url('WaterApplyNewConnection/water_connection_view/'.md5($consumer_details['apply_connection_id']));?>" class="btn btn-warning">View Application</a>

                    </div>
                   
                
               </div>
            </div>  
    


    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?php 

		echo $this->include('layout_vertical/footer');

 ?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

    $('#myform').validate({ // initialize the plugin
       

        rules: {
            month: {
                required: true,
               
            },
            amount: {
                required: true,
              
            },
            payment_mode: {
                required: true,
              
            },
            cheque_no: {
                required: true,
              
            },
            cheque_date: {
                required: true,
              
            },
            bank_name: {
                required: true,
              
            },
            branch_name: {
                required: true,
                
            },
            
        }


    });

});
</script>

<script type="text/javascript">

  function get_amount(argument) {

    var generation_date=argument;
    //alert(generation_date);
    var consumer_id=$("#consumer_id").val();


    $.ajax({
          url:"<?php echo base_url("WaterPaymentMobile/get_amount");?>",    
          type: "post",    //request type,
          dataType: 'json',
          data: {generation_date: generation_date,consumer_id:consumer_id},
          success:function(result){
           // alert(result.amount);
           // console.log(result);
              $("#amount").val(result.amount);

          }
      });


  }
  function show_hide_cheque_details(argument)
  {
      var payment_mode=argument;
      //alert(payment_mode);

      if(payment_mode!='CASH')
      {
          //alert('hiiiiii');

          $("#chq_dtls1").show();
          $("#chq_dtls2").show();
          $("#chq_dtls3").show();
          $("#chq_dtls4").show();
          
      }
      else
      {
         // alert('hhhhhhhhh');
          $("#chq_dtls1").hide();
          $("#chq_dtls2").hide();
          $("#chq_dtls3").hide();
          $("#chq_dtls4").hide();
          
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