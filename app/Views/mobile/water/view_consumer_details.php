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
                <a href="<?=base_url().'/WaterSearchConsumerMobile/search_consumer_tc';?>" class="btn btn-info pull-right panel-control btn_please_wait" onclick="history.back();">
					<i class="fa fa-arrow-left" aria-hidden="true"></i>Back
				</a>
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
                        <?=$consumer_dtls['owner_name']?$consumer_dtls['owner_name']:"N/A"; ?>
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

        

       <!-- <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Consumer Due Details</h3>
            </div>
            <div class="panel-body">
                 <table class="table table-responsive" id="demo_dt_basic">
                    <tr>
                        <th>Demand Month</th>
                        <th>Amount</th>
                        <th>Penalty</th>
                        
                    </tr>

                    <?php
                    
                  //  foreach($due_details as $val)
                    {
                      // echo $val['demand_upto'];
                      // echo  $month=date('F',strtotime($val['demand_upto']));
                    ?>
                    <tr>
                        <td><?php echo $val['demand_month']??null;?></td>
                        <td><?php echo $val['current_amount']??null;?></td>
                        <td><?php echo $val['penalty']??null;?></td>
                        
                    </tr>

                    <?php    
                    }
                    ?>
                </table>

            </div>
        </div>-->

              <?php
              if($due_from!="")
              {


              ?>  
              <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Demand Details</h3>
                </div>
                <div class="panel-body">   

                     <div class="row">
                        <label class="col-md-2 bolder">Due From</label>
                        <div class="col-md-3 pad-btm">
                            <?php 

                                echo date('F',strtotime($due_from)).' / '.date('Y',strtotime($due_from))
                            ?>
                        </div>

                        <label class="col-md-2 bolder">Due Upto </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo date('F',strtotime($due_upto)).' / '.date('Y',strtotime($due_upto)) ?> 
                        </div>
                        
                      

                    </div>
                    

                    <div class="row">
                        <label class="col-md-2 bolder">Arrear Demand</label>
                        <div class="col-md-3 pad-btm"  style="color: red; font-weight: bold; font-size: 17px;">
                            <?php echo $arr_due_amt; ?>
                        </div>

                        <label class="col-md-2 bolder">Current Demand </label>
                        <div class="col-md-3 pad-btm"  style="color: red; font-weight: bold; font-size: 17px;">
                            <?php echo $curr_due_amt; ?> 
                        </div>

                        

                    </div>

                 
                </div>
            </div>
            <?php
                }
                else
                {
                   echo '<div class="panel panel-bordered" style="color:green; font-weight:bold; font-size:17px; text-align:center;">No Dues!!!</div>';
                }
            ?>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Consumer Connection Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                Connection Type
                            </div>
                            <div class="col-md-3">
                                <?php
                                    if(in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                                    {
                                        $connection_type = "Meter/Fixed";
                                        $meter_no=$connection_dtls['meter_no'];
                                    }
                                    elseif($connection_dtls['connection_type']==1)
                                    {
                                        $connection_type='Meter';
                                    }
                                    else if($connection_dtls['connection_type']==2)
                                    {
                                        $connection_type='Gallon';
                                    }   
                                    else
                                    {
                                        $connection_type='Fixed';
                                    }
                                ?>
                                <?php  echo $connection_type;?>
                            </div>
                            <div class="col-md-3">
                                Connection Date
                            </div>
                            <div class="col-md-3">
                                <?php  echo date('d-m-Y',strtotime($connection_dtls['connection_date']));?>
                            </div>

                            <?php 
                                if($connection_dtls['connection_type']!=3)
                                {
                                    ?>
                                    <div class="col-md-3">
                                    Last Meter Reading
                                    </div>
                                    <div class="col-md-3">
                                        <?php  echo isset($last_reading)?$last_reading:'N/A';?>
                                    </div>

                                    <?php
                                }
                            ?>
                        </div>

                    </div>

            

                    

                </div>
            </div>


       <form method="post">
        <div class="panel-body">
            <input type="hidden" id="connection_type" name="connection_type" value="<?=$consumer_dtls["meter_connection_type"]??"";?>" />
            <input type="hidden" id="last_reading" name="last_reading" value="<?=$consumer_dtls["initial_reading"]??0;?>" />
            <input type="hidden" id="area_sqft" name="area_sqft" value="<?=$consumer_dtls["area_sqft"]??0;?>" />
            <div class="row">
                <div class="col-xs-6 mar-top btn-block">
                    
                    <!-- <button type="submit" value="generate_demand" name="generate_demand" class="btn btn-warning ">Generate Demand</button> -->
                    <!-- <a href = "<?=base_url().'/WaterViewConsumerMobile/demand_generate/'.$consumer_dtls['id']?>" class="btn btn-warning ">Generate Demand</a> -->
                    <a href = "<?=base_url().'/WaterViewConsumerMobile/demand_generate/'.($consumer_dtls['id']).'/'.true ;?>" class="btn btn-warning btn-block">Generate Demand</a>
                </div>
                <?php 
                    if($due_from!="")
                    {

                    ?>
                        <!-- <button type="submit" value="proceed_to_pay" name="proceed_to_pay" class="btn btn-success">Proceed to Pay</button> -->
                        <div class="col-xs-6 mar-top btn-block">
                            <a href = "<?=base_url().'/WaterUserChargePaymentMobile/payment_details/'.md5($consumer_dtls['id'])?>" class="btn btn-success btn-block">Proceed to Pay</a>
                        </div>
                    <?php                                          
                    }
                    //print_var(isset($consumer_dtls['area_sqmt']));
                    if((isset($consumer_dtls['area_sqmt']) && $consumer_dtls['area_sqmt']==0) || empty($consumer_dtls['area_sqmt']))
                    {
                        ?>
                        <div class="col-xs-6 mar-top btn-block">
                            <a href = "<?=base_url().'/WaterViewConsumerMobile/update_consumer/'.md5($consumer_dtls['id'])?>" class ="btn btn-info btn-block">update consumer</a>
                        </div>
                        <?php
                    } 

                    
                    if(isset($last_transection_id) && !empty($last_transection_id))
                    {
                        ?>
                        <div class="col-xs-6 col-sm-6  mar-top btn-block ">
                            <a href = "<?=base_url().'/WaterUserChargePaymentMobile/payment_tc_receipt/'.md5($consumer_dtls['id']).'/'.md5($last_transection_id);?>" class ="btn btn-dark btn-block">Last Payment Receipt</a>
                        </div>
                        <?php
                    }   
                ?>
                <div class="col-xs-6 col-sm-6  mar-top  btn-block">
                    <a href = "<?=base_url().'/WaterViewConsumerMobile/consumer_demand_receipt/'.md5($consumer_dtls['id']);?>" class ="btn btn-warning btn-block">Total Demand Receipt</a>
                </div>
                      
            </div>
        </div>
        <!-- </div> -->

       </form>


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

        <!-- <script>

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
        </script> -->

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
    else if($error=flashToast('error'))
    {
        echo "modelInfo('".$error."');";
    }
  ?>

</script>
    