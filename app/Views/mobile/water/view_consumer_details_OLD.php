<?=$this->include("layout_mobi/header");?>
<style type="text/css">
    .error{

        color: red;
    }
</style>
<!--CONTENT CONTAINER-->
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<div id="content-container">
    <!--Page content-->
    <div id="page-content">    

        <div><?php if(isset($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); }?></div>   
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Owner Basic Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-2">
                        <b>Consumer No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['consumer_no']?$consumer_dtls['consumer_no']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Application No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['application_no']?$consumer_dtls['application_no']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Pipeline Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['pipeline_type']?$consumer_dtls['pipeline_type']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Property Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['property_type']?$consumer_dtls['property_type']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Connection Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['connection_type']?$consumer_dtls['connection_type']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Connection Through :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['connection_through']?$consumer_dtls['connection_through']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Category :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['category']?$consumer_dtls['category']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Area in Sqft :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['area_sqft']?$consumer_dtls['area_sqft']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Owner Name :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['applicant_name']?$consumer_dtls['applicant_name']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Mobile No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['mobile_no']?$consumer_dtls['mobile_no']:"N/A"; ?>
                    </div>
                </div>
            </div>      
        </div>

        <?php
        if($initial_meter=="")
        {
        ?>
            
            <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Initial Meter Reading</h3>
            </div>
            <div class="panel-body">

 <form method="post" action="<?php echo base_url('WaterInitialMeterReading/insert');?>" id="myform">

                <div class="row">
                    <div class="col-md-12">
                       <!-- <p style="text-align: center; color: green; font-size: 15px; font-weight: bold;">Please take initial meter reading!</p>-->

                       <input type="hidden" name="consumer_id" value="<?php echo $consumer_dtls['id']; ?>">
                        <div class="col-md-3">
                            <b>Enter Initial Meter Reading: </b>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="initial_reading" id="initial_reading" class="form-control" required="required" onkeypress="return isNum(event);">
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" name="save" id="save" value="Save" class="btn btn-success">
                    </div>
                </div>

</form>

            </div>
        </div>

        <?php
        }
        else if($last_demand_month!=$prev_month)
        {
          

        ?>


        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Generate Demand</h3>
            </div>
            <div class="panel-body">
                <form method="post" action="<?php echo base_url('WaterGenerateDemand/tax_generation');?>" id="myform">

                    <div class="row-fluid">
                        <div class="col-md-12">

                            <input type="hidden" name="consumer_id" id="consumer_id" value="<?php echo $consumer_dtls['id'];?>">

                            <input type="hidden" name="ward_mstr_id" id="ward_mstr_id" value="<?php echo $consumer_dtls['ward_mstr_id']; ?>">

                            <input type="hidden" name="property_type_id" id="property_type_id" value="<?php echo $consumer_dtls['property_type_id']; ?>">

                            <input type="hidden" name="area_sqft" id="area_sqft" value="<?php echo $consumer_dtls['area_sqft']; ?>">



                            <div class="col-md-3">Enter Final Meter Reading</div>
                            <div class="col-md-3"><input type="text" name="final_meter_reading" id="final_meter_reading" value="" class="form-control"></div>

                           <!-- <div class="col-md-3">Is Your Meter OK ?</div>
                            <div class="col-md-3">
                                <select name="is_meter_ok" id="is_meter_ok" class="form-control" onchange="show_hide_meter_date(this.value)">

                                    <option value="">Select</option>
                                    <option value="YES">YES</option>
                                    <option value="NO">NO</option>

                                </select>
                            </div>
                        -->

                        </div>

                    </div>

                 <!--   <div id="show_meter_date" style="display: none;">

                        <div class="row-fluid">
                            <div class="col-md-12">

                                <div class="col-md-3">Meter Destroy Date</div>
                                <div class="col-md-3"><input type="date" name="meter_destroy_date" id="meter_destroy_date" value="" class="form-control" onchange="validate_date(this.value)"></div>


                            </div>


                        </div>

                        <input type="hidden" name="curr_date" id="curr_date" value="<?php echo date('Y-m-d');?>">

                    </div>

                    -->
                    
                    <div class="row-fluid">
                        <div class="col-md-12">
                            <input type="submit" name="generate" id="generate" value="Generate" class="btn btn-success">
                        </div>
                    </div>
                </form>
			</div>

            <?php
        }
        else
        {

            

            ?>

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Proceed Payment </h3>
                    </div>

                    <?php
                    
                    if($due_details)
                    {
                    ?>

            <form method="post" action="<?php echo base_url("WaterUserChargePayment/pay_user_charge"); ?>" id="payment_form">

                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    <select name="payment_mode" id="payment_mode" class="form-control"  onchange="show_hide_cheque_details(this.value)">
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
                                <td><input type="date" name="cheque_date" id="cheque_date" class="form-control" ></td>
                            </tr>
                            <tr id="chq_dtls3" style="display: none;">
                                <td>Bank Name</td>
                                <td><input type="text" name="bank_name" id="bank_name" class="form-control"  onkeypress="return isAlpha(event);" placeholder="Enter Bank Name"></td>
                            </tr>
                            <tr id="chq_dtls4" style="display: none;">
                                <td>Branch Name</td>
                                <td><input type="text" name="branch_name" id="branch_name" class="form-control" onkeypress="return isAlpha(event);"  placeholder="Enter Branch Name"></td>
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
                        final_meter_reading: {
                            required: true,

                        },
                      
                    }


                });

            });
        </script>

        <script type="text/javascript">

            function show_hide_meter_date(argument)
            {
                var is_meter_ok=argument;
                if(is_meter_ok=='NO')
                {
                    $("#show_meter_date").show();

                }
                else
                {
                    $("#show_meter_date").hide();
                }
            }

            function validate_date(arg)
            {

                var meter_destroy_date=arg;
                var curr_date=$("#curr_date").val();
                //alert(meter_destroy_date);
                //alert(curr_date);


                if(meter_destroy_date>curr_date)
                {
                    alert('Meter Destroy Date can not be greater than Current Date');
                    $("#meter_destroy_date").val("");

                }
            }
        </script>

        <script>

            $(document).ready(function () 
                              {

                $('#payment_form').validate({ // initialize the plugin


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