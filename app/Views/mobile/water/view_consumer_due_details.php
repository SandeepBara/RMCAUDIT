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
          User Charge Details
         </div>

         <table class="table table-responsive table-bordered">
           <tr>
             <td>Month</td>
             <td>Amount</td>
             
           </tr>
           <?php
            if($due_details)
            {
             

              foreach($due_details as $val)
              {

                //echo $val['generation_date'];

               $month=date("F",strtotime($val['generation_date']));

           ?>
           <tr>
             <td><?php echo $month;?></td>
             <td><?php echo $val['amount'];?></td>
             
           </tr>
           <?php  
              } 
            }
           ?>
         </table>
  
    </div>
    <div class="row-fluid">
      <div class="col-md-12">
        <a type="submit" id="submit" class="btn btn-success" href="<?php echo base_url("WaterPaymentMobile/pay_payment/".md5($consumer_dtls['id']));?>">Proceed to Pay</a>
      </div>
    </div>

    </div>
  </div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

