<?=$this->include("layout_mobi/header");?>
<style type="text/css">
  .error{

    color: red;
  }
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel">
  
       <div class="row-fluid">
         <div class="col-md-12">
           
           <div class="col-md-3">Consumer No.</div>
           <div class="col-md-3"><?php echo $consumer_dtls['consumer_no'];?></div>

           <div class="col-md-3">Application No.</div>
           <div class="col-md-3"><?php echo $consumer_dtls['application_no'];?></div>


         </div>
       </div>

       <div class="row-fluid">
         <div class="col-md-12">
           
           <div class="col-md-3">Pipeline Type</div>
           <div class="col-md-3"><?php echo $consumer_dtls['pipeline_type'];?></div>

           <div class="col-md-3">Property Type</div>
           <div class="col-md-3"><?php echo $consumer_dtls['property_type'];?></div>


         </div>
       </div>

       <div class="row-fluid">
         <div class="col-md-12">
           
           <div class="col-md-3">Connection Type</div>
           <div class="col-md-3"><?php echo $consumer_dtls['connection_type'];?></div>

           <div class="col-md-3">Connection Through</div>
           <div class="col-md-3"><?php echo $consumer_dtls['connection_through'];?></div>


         </div>
       </div>

       <div class="row-fluid">
         <div class="col-md-12">
           
           <div class="col-md-3">Category</div>
           <div class="col-md-3"><?php echo $consumer_dtls['category'];?></div>

           <div class="col-md-3">Area in Sqft</div>
           <div class="col-md-3"><?php echo $consumer_dtls['area_sqft'];?></div>


         </div>
       </div>

       <div class="row-fluid">
         <div class="col-md-12">
           
           <div class="col-md-3">Owner Name</div>
           <div class="col-md-3"><?php echo $consumer_dtls['applicant_name'];?></div>

           <div class="col-md-3">Mobile No.</div>
           <div class="col-md-3"><?php echo $consumer_dtls['mobile_no'];?></div>


         </div>
       </div>


      
      </div>

 <div style="clear: both;"></div>

       <div class="panel panel-bordered">
         <div class="panel-heading">
          Proceed Payment 
         </div>
<?php
  if($due_from!="")
  {
?>


<form method="post" action="<?php echo base_url("WaterUserChargePayment/pay_user_charge"); ?>" id="myform">
         <table class="table table-responsive table-bordered">
            <input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_dtls['id'];?>">
             <input type="hidden" name="due_from" id="due_from" value="<?php echo $due_from; ?>">
             <input type="hidden" name="ward_mstr_id" id="ward_mstr_id" value="<?php echo $consumer_dtls['ward_mstr_id']; ?>">


            <tr>
                <td>Select Month</td>
                <td>
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
                </td>
            </tr>
            <tr>
                <td>Amount</td>
                <td><input type="text" name="amount" id="amount" class="form-control" readonly="readonly"></td>
            </tr>
           <tr>
                <td>Payment Mode</td>
                <td>
                  <select name="payment_mode" id="payment_mode" class="form-control" onchange="show_hide_cheque_details(this.value)">
                    <option value="">Select</option>
                    <option value="CASH">CASH</option>
                    <option value="CHEQUE">CHEQUE</option>
                    <option value="DD">DD</option>

                  </select>
                </td>
            </tr>

            

          <tr id="chq_dtls1" style="display: none;">
              <td>Cheque/DD No.</td>
              <td><input type="text" name="cheque_no" id="cheque_no" class="form-control"  onkeypress="return isAlphaNum(event);" placeholder="Enter Cheque No."></td>

          </tr>
          <tr id="chq_dtls2" style="display: none;">
              <td>Cheque/DD Date</td>
              <td><input type="date" name="cheque_date" id="cheque_date" class="form-control"></td>
          </tr>
          <tr id="chq_dtls3" style="display: none;">
              <td>Bank Name</td>
              <td><input type="text" name="bank_name" id="bank_name" class="form-control"  onkeypress="return isAlpha(event);" placeholder="Enter Bank Name"></td>
          </tr>
          <tr id="chq_dtls4" style="display: none;">
              <td>Branch Name</td>
              <td><input type="text" name="branch_name" id="branch_name" class="form-control" onkeypress="return isAlpha(event);" placeholder="Enter Branch Name"></td>
          </tr>



          <tr>
                <td>Penalty</td>
                <td><input type="text" name="penalty" id="penalty" class="form-control" value="<?php echo $penalty;?>" readonly></td>
          </tr>
          <tr>
                <td>Rebate</td>
                <td><input type="text" name="rebate" id="rebate" class="form-control" value="<?php echo $rebate;?>" readonly></td>
          </tr>
            

          <tr>
              
              <td><input type="submit" name="pay" id="pay" value="Pay" class="btn btn-success">
              </td>

          </tr>
         </table>
  
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
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

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
           // alert(result);
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
</script>