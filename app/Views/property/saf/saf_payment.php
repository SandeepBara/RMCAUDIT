<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!-- <div id="page-title">
             <h5 class="page-header text-overflow">Self Assessment Form</h5>
        </div> --><!--End page title-->
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF Payment</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form action="<?php echo base_url('Home/citizen_confirm_payment');?>" method="post" role="form" class="php-email-form">
            <div class="panel panel-bordered panel-dark">
                
                <input type="hidden" class="form-control" id="custm_id" name="custm_id" value="<?php echo $id; ?>">
                
     <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
  <table class="table table-bordered">
  <thead class="thead-light">
  <tr>
                        <th colspan="10" style="background-color:#843139;color:white;">Owner Basic Details</th>
                        </tr>
  </thead>
                                    <tbody>
                                    <?php if($basic_details): ?>
                                    <tr>
                                        <td>Ward No.</td>
                                        <td><strong>:</strong></td>
                                        <td><?php echo $basic_details['ward_no']; ?>
                                        </td>
                                    <tr>
                                        <td>Holding No.</td>
                                        <td><strong>:</strong></td>
                                        <td><?php echo $basic_details['holding_no']; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($owner_details): ?>
                                    <tr>
                                        <td>Owner Name</td>
                                        <td><strong>:</strong></td>
                                        <td><?php echo $owner_details['owner_name']; ?>
                                        </td>
                                    <tr>
                                        <td>R/W Guardian</td>
                                        <td><strong>:</strong></td>
                                        <td><?php echo $owner_details['relation_type']; ?>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Guardian's Name</td>
                                        <td><strong>:</strong></td>
                                        <td><?php echo $owner_details['guardian_name']; ?>
                                        </td>
                                    <tr>
                                        <td>Mobile No</td>
                                        <td><strong>:</strong></td>
                                        <td><?php echo $owner_details['mobile_no']; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    </table>
                                    </div>
                        </div>
                    </div>
                </div>
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  
    <tr>
      <th scope="col">Sl No.</th>
      <th scope="col">ARV</th>
      <th scope="col">Effect From</th>
      <th scope="col">Holding Tax</th>
      <th scope="col">Water Tax</th>
      <th scope="col">Conservancy/Latrine Tax</th>
      <th scope="col">Education Cess</th>
      <th scope="col">Health Cess</th>
      <th scope="col">Quarterly Tax</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if($tax_list):
  $i=1; $qtr_tax=0; ?>
  <?php foreach($tax_list as $tax_list): 
  $qtr_tax=$tax_list['hold_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
  ?>
    <tr>
      <td><?php echo $i++; ?></td>
      <td><?php echo $tax_list['arv']; ?></td>
      <td><?php echo $tax_list['']; ?></td>
      <td><?php echo $tax_list['hold_tax']; ?></td>
      <td><?php echo $tax_list['water_tax']; ?></td>
      <td><?php echo $tax_list['latrine_tax']; ?></td>
      <td><?php echo $tax_list['education_cess']; ?></td>
      <td><?php echo $tax_list['health_cess']; ?></td>
      <td><?php echo $qtr_tax; ?></td>
      <td>Current</td>
    </tr>
    <input type="hidden" class="form-control" id="qtrl_tax" name="qtrl_tax" value="<?php echo $qtr_tax; ?>">
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
    </table>
  
  
    
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <thead class="thead-light">
  <tr>
                        <th colspan="10" style="background-color:#843139;color:white;">Pay Property Tax</th>
                        </tr>
  
    
  </thead>
  <tbody>
  <?php if($demand_detail):
  $i=1; $total_due = 0;
    
  ?>
  <?php /*foreach($demand_detail as $tot_demand):
  $i==1? $first_qtr = $tot_demand['qtr']:'';
  $i==1? $first_fy = $tot_demand['fy_id']:'';
  $i==1? $fir_fy = $tot_demand['fy']:'';
  $demand =$tot_demand['amount']+$tot_demand['holding_tax']+$tot_demand['water_tax']+$tot_demand['education_cess']+$tot_demand['health_cess']+$tot_demand['lighting_tax']+$tot_demand['latrine_tax']+$tot_demand['harvest_tax'];
  $total_demand = $demand;
  $total_due = $total_due + $total_demand;
  $z=0;
  $z=$z+$i;
  $rebate=0;
  $i++;
  $dates = date("m");
  if($dates==04 || $dates==05 || $dates==08){
      $rebate = ($total_demand/100)*5;
  }
  endforeach;*/ ?>
        <tr>
                                        <td>Due Upto Year</td>
                                        <input type="hidden" class="form-control" id="totl_dmnd" name="totl_dmnd" value="<?php echo $total_demand; ?>">
                                        <input type="hidden" class="form-control" id="totl_due" name="totl_due" value="<?php echo $total_due; ?>">
                                        <td><input type="hidden" class="form-control" id="due_upto_year" name="due_upto_year" value="<?php echo $tot_demand['fy_id']; ?>">
                                        <input type="text" class="form-control" id="due_upto_years" name="due_upto_years" value="<?php echo $tot_demand['fy']; ?>" readonly>
                                        </td>
                                        <td>Due Upto Quarter
                                        <input type="hidden" class="form-control" id="from_fy_year" name="from_fy_year" value="<?php echo $first_fy; ?>">
                                        <input type="hidden" class="form-control" id="tl_qtr" name="tl_qtr" value="<?php echo $z; ?>">
                                        <input type="hidden" class="form-control" id="from_fy_qtr" name="from_fy_qtr" value="<?php echo $first_qtr; ?>">
                                        </td>
                                        
                                        <td>
                                                    <select class="form-control" id="date_upto_qtr" name="date_upto_qtr" onchange="calculate()">
    <option value="" >Choose...</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
  </select>
                                                
                                        </td>
                                        </tr>
                                        
                                    <tr style="height: 63px;">
                                        <td>Total Demand</td>
                                        
                                        <td><input type="text" class="form-control" id="total_demand" name="total_demand" value="<?php echo $total_due; ?>" readonly>
                                        </td>
                                        <td>Rebate Amount</td>
                                        
                                        <td><input type="text" class="form-control" id="total_rebate" name="total_rebate" value="<?php echo $rebate; ?>" onkeyup="calculatePay();"  onkeypress="return isNumber(event);" readonly>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Total Payable</td>
                                        
                                        <td colspan="3"><input type="text" class="form-control" id="total_payable" name="total_payable" value="0.00" style="width:292px;" readonly>
                                        </td>
                                        
                                    </tr>   
                                    <tr>
                                        <td>Payment Gateway</td>
                                        
                                        <td colspan="3"><select class="form-control" id="payment_mode" name="payment_mode" style="width:292px;">
    <option value="" >Choose...</option>
    <?php if($tran_mode):?>
    <?php foreach($tran_mode as $tran_mode): ?>
    <option value="<?php echo $tran_mode['id']; ?>"><?php echo $tran_mode['transaction_mode']; ?></option>
    <?php endforeach; ?>
    <?php endif; ?>
  </select>
                                        </td>
                                        
                                        
                                        
                                    </tr>
                                    <tr >
                                        <td colspan="4" id="terms">
                    <label style="font-weight: bold; color: #d70707">Before poceeding to for online payment please check the terms conditions</label><br>
                    <input type="checkbox" id="checkbox" value="agreed" onchange="validate()" name="agreement">
                    &nbsp;&nbsp;<label for="checkbox"><b>I agree to <a href="" id="terms_page" target="_blank"><span style="color: #fb2c0b; text-decoration: none">Terms &amp; Conditions</span></a></b></label>
                </td>
                                        
                                        
                                    </tr>
                                    <?php endif; ?>
                                    <tr style="height: 63px;color:red;">
                                        <td colspan="4">
                                        <input type="submit" disabled class="button button5" value="Proceed To Pay Online" id="proceed_To_Pay" name="proceed_To_Pay">
                                            <a href="<?php echo base_url('Home/citizen_due_details/'.$id);?>" type="button" class="button button1">View Demand Details</a>
            <a href="<?php echo base_url('Home/citizen_property_details/'.$id);?>" type="button" class="button button2">View Property Details</a>
                                        </td>
                                        
                                        
                                    </tr>
    
    </tbody>
    </table>
  
            </div><br><br>
            
            
                            </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
