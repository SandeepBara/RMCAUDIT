
<?= $this->include('layout_vertical/header');?>
<style type="text/css">
  .row{ font-weight: bold; color: #000000; }
  .label{ font-weight: bold; color: #000000; }
  .table td{font-weight: bold; color: #000000; font-size: 12px;}
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
            <li><a href="#">Apply New License </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->   
    <div id="page-content">
        <form id="formname" name="form" method="post" action="<?=base_url('');?>/TradeReApplyLicence/re_apply" >
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>
                 <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Licence Application Status</h3>
                </div>
                <div class="panel-body">
                  <div class="row">
                      <div class="col-md-12" style="text-align: center;">
                         <span style="font-weight: bold; font-size: 17px; color: #bb4b0a;"> Your Application No. is <span style="color: #179a07;"><?php echo $licencee['application_no'];?></span>. Application Status: Payment Is Not Clear</span>
                      </div>
                  </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply New License</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                    <div class="row">
                        <label class="col-md-3">Application Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 control-label text-semibold">
                            NEW LICENCE
                        </div>
                        <label class="col-md-3">Firm Type <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm"><?php echo $licencee['firm_type'];?>                                 
                    </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                          <?php echo $licencee['ownership_type'];?>
                        </div>
                    </div>
                </div>
            </div>
             <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Details</h3>
                </div>
                <div class="panel-body">
                  <div class="row">
                        <label class="col-md-3">Holding / SAF No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm"><?php echo $holding_no;?>
                       </div>

                        <label class="col-md-3">Ward No. <span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                        <?php echo $ward_no;?>                      

                       </div>
                    </div>   
                  <div class="row">

                    <label class="col-md-3">Firm Name<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                        <?php echo $licencee['firm_name'];?>
                       </div>

                  <label class="col-md-3">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>

                       <?php echo $licencee['area_in_sqft'];?>
                       <input type="hidden"  id="area_in_sqft" name="area_in_sqft" class="form-control" value="<?php echo $licencee['area_in_sqft'];?>">
                  </div>
                  <div class="row">

                    <label class="col-md-3">Firm Establishment Date<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <?php echo $licencee['establishment_date'];?>
                         <input type="hidden"  id="firm_date" name="firm_date" class="form-control" value="<?php echo $licencee['establishment_date'];?>">
                       </div>

                   <label class="col-md-3">Licence For<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                         <?php if(isset($licencee['licence_for_years'])){echo $licencee['licence_for_years'].' YEARS';}?>
                       </div>
                  </div>
                  <div class="row">
                  <label class="col-md-3">Address<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm">
                          <?php echo $licencee['address'];?>
                       </div>

                        <label class="col-md-3">Landmark</label>

                       <div class="col-md-3 pad-btm">
                         <?php echo $licencee['landmark'];?>
                       </div>

                 </div>

                  <div class="row">

                  <label class="col-md-3">Pin<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                           <?php echo $licencee['pin_code'];?>
                       </div>

                  </div>  
           </div>
         </div>

           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name <span class="text-danger">*</span></th>
                                            <th>Guardian Name</th>                                            
                                            <th>Mobile No <span class="text-danger">*</span></th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>District</th> 
                                        </tr>
                                    </thead>
                                    
                                      
                                <?php 
                                
                               if(isset($firm_owner)){
                                if(!empty($firm_owner)){
                                  foreach ($firm_owner as  $value) {                            
                                ?>
                                        <tr>
                                            <td><?=$value["owner_name"];?></td>        
                                            <td><?=$value["guardian_name"]?></td>
                                            <td><?=$value["mobile"]?></td>
                                            <td><?=$value["address"]?></td>
                                            <td><?=$value["city"]?></td>
                                            <td><?=$value["district"]?></td> 
                                            <td><?=$value["state"]?></td>
                                        </tr>
                                  <?php }
                                }
                              } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           </div>
           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Items of Trade</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Trade Code</th>
                                            <th>Trade Item</th>                                       
                                        </tr>
                                    </thead>
                                    
                                <?php 
                                
                               if(isset($trade_items)){
                                if(!empty($trade_items)){
                                  foreach ($trade_items as  $valueitem) {                            
                                ?>
                                        <tr>                                            
                                            <td><?=$valueitem["trade_code"]?></td>
                                            
                                            <td><?=$valueitem["trade_item"]?></td>
                                        </tr>
                                  <?php }
                                }
                              } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                  
             <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Licence Required for the Year</h3>
                </div>
                <div class="panel-body">              
                  <div class="row">                    
                   <label class="col-md-2">Licence For<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm">
                        <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()">
                           <option value="">--Select--</option>
                           <option value="1">1 Year</option>
                           <option value="2">2 Year</option>
                           <option value="3">3 Year</option>
                           <option value="4">4 Year</option>
                           <option value="5">5 Year</option>
                           <option value="6">6 Year</option>
                           <option value="7">7 Year</option>
                           <option value="8">8 Year</option>
                           <option value="9">9 Year</option>
                           <option value="10">10 Year</option>
                         </select>
                       </div>                       
                       <label class="col-md-2">Charge Applied<span class="text-danger">*</span></label>

                       <div class="col-md-3 pad-btm">
                          <input type="text"  id="charge"  disabled="disabled" class="form-control" value="<?php echo $rate; ?>" >

                       </div>
                  </div>  
                  <div class="row">                    

                   <label class="col-md-2">Penalty<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm">
                          <input type="text"  id="penalty" name="penalty" class="form-control" value="<?php echo $calpenalty;?>" readonly >
                          <input type="hidden"  id="id" name="id" class="form-control" value="<?php echo md5($licencee['id']);?>" >
                          <input type="hidden"  id="apply_id" name="apply_id" class="form-control" value="<?php echo $licencee['id'];?>" >
                          <input type="hidden"  id="licence_charge" name="licence_charge" value="<?php echo $rate; ?>" >
                       </div>
                       <label class="col-md-2">Cheque Bounce Charge<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm">
                          <input type="text"  id="cheque_bounce"  disabled="disabled" class="form-control" value="<?php echo $penalty?>">
                          <input type="hidden"  id="cheque_bounce_charge" name="cheque_bounce_charge" class="form-control" value="<?php echo $penalty?>">
                          <input type="hidden"  id="ward_mstr_id" name="ward_mstr_id" class="form-control" value="<?php echo $licencee['ward_mstr_id'];?>">

                       </div>
                  </div>   

                  <div class="row">
                    <label class="col-md-2">Total Charge<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm">
                          <input type="text"  id="total_charge"  disabled="disabled" class="form-control" value="<?php echo $total_charge?>">
                       </div>
                  <label class="col-md-2">Payment Mode<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm">
                          <select class="form-control" id="payment_mode" name="payment_mode">                                
                              <option value="CASH">CASH</option>
                          </select>
                       </div>
                  </div>
                  <div class="row">
                      <label >Your cheque is cancel due to "<b style="font-weight: bold; color: #d70707"><?=$chequeDetails['reason']; ?></b>" <br > cheque no <b style="font-weight: bold; color: #d70707">"<?=$bankDetails['cheque_no']; ?>" |</b> bank name <b style="font-weight: bold; color: #d70707">"<?=$bankDetails['bank_name']; ?>" |</b> branch name <b style="font-weight: bold; color: #d70707">"<?=$bankDetails['branch_name']; ?>" |</b> cheque issue date <b style="font-weight: bold; color: #d70707">"<?=date('d-m-Y',strtotime($bankDetails['cheque_date'])); ?>" |</b> and cheque cancellation charge "<b style="font-weight: bold; color: #d70707"><?=$chequeDetails['amount']; ?></b>" <br> You need to re-pay it by CASH</label><br>
                  </div>
               </div>
           </div>

            <div class="panel panel-bordered panel-dark" style="text-align: center;">             
              <button class="btn btn-primary" id="re_apply" name="re_apply" type="submit">Re_Apply</button>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#re_apply').click(function(){
      var licence_for = $('#licence_for').val();
      if(licence_for==""){
        $("#licence_for").css({"border-color":"red"});
          $("#licence_for").focus();
          return false;
      }
    });
    $("#licence_for").change(function(){$(this).css('border-color','');});
  });
  $("#charge").val('0');
  $("#total_charge").val('0');
  $("#penalty").val('0');
  function show_charge()
  {
      var timefor = $("#licence_for").val();
      var str =  $("#area_in_sqft").val();
      var edate =  $("#firm_date").val();
      var licence_for =  $("#licence_for").val();
      
      if(str!=""){
          $.ajax({
            type:"POST",
            url: '<?php echo base_url("TradeReApplyLicence/getcharge");?>',
            dataType: "json",
            data: {
                    "areasqft":str,"applytypeid":1
            },
            success:function(data){
              console.log(data);
             // alert(data);
              if (data.response==true) {
                var cal = data.rate * timefor;
                $("#charge").val(cal);
                $("#licence_charge").val(cal);
                var monthdiff=0;
                //alert(monthdiff);
                var calpenalty = 0;
                var totalchrg = cal;
                if(licence_for!=""){
                  monthdiff = getpenalty(edate);
                }
                var cheque_bounce_charge = $('#cheque_bounce_charge').val();
                if(monthdiff>0){
                   calpenalty = (monthdiff*20)+100;
                   totalchrg = parseInt(cal)+parseInt(calpenalty)+parseInt(cheque_bounce_charge);    
                   $("#total_charge").val(totalchrg);     
                   $("#penalty").val(calpenalty);
                }else{
                  if(cal>0){
                    totalchrg = parseInt(cal)+parseInt(cheque_bounce_charge);  
                    $("#total_charge").val(totalchrg);
                    $("#penalty").val(calpenalty);
                  }else{
                    $("#total_charge").val('0');
                    $("#penalty").val(calpenalty);
                  }
                }
              }
          }
               
        });
      }
    }
    function getpenalty(d1){
    d1 = new Date(d1);
    var d2 = new Date('<?=date("Y-m-d");?>');
    var months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth();
    months += d2.getMonth();
    month = months <= 0 ? 0 : months;
    return month;
    }
</script>>