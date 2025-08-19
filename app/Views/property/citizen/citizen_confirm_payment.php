
<?= $this->include('layout_horizontal/header');?>

  <!-- ======= Hero Section ======= -->
  
	<section id="cta" class="cta" style="margin-top: 73px;">
      <img src="<?=base_url();?>/public/assets/img/gallery/thumbs/swatch-bharat2.jpg" alt="Los Angeles" width="100%" height="300px">
    </section><!-- End Cta Section -->

	<!-- ======= Cta Section ======= -->
   
    <section id="contact" class="contact" style="margin-top: -205px;">
      <div class="container">
        <div class="col-lg-12">
			<div class="panel panel-default">
	  <div class="panel-heading" style="text-align:left;"><h5><b>Payment Details 	
	  <a href="" style="float:right;color:#000;">Back</a></b></h5></div>
	  <div class="panel-body">
		
			
			<form action="<?php echo base_url('Home/citizen_confirm_payment');?>" method="post" role="form" class="php-email-form">
			<div class="col-md-12" style="font-size:14px;">
				<input type="hidden" class="form-control" id="custm_id" name="custm_id" value="<?php echo $id; ?>">
				
	
  <table class="table table-hover tbl-res" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
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
  <?php foreach($demand_detail as $tot_demand):
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
  
  ?>
  
  
  <?php endforeach; ?>
  <?php $dates = date("m");
	if($dates=='04' || $dates=='05' || $dates=='06'){
	  $rebate = ($total_demand/100)*5;
	}
	?>
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
										<input type="hidden" class="form-control" id="total_qrt" name="total_qrt" value="" readonly>
										<input type="hidden" class="form-control" id="total_qrt_pnlty" name="total_qrt_pnlty" value="" readonly>
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
										<button type="submit" disabled class="button button5" id="proceed_To_Pay" name="proceed_To_Pay">Proceed To Pay Online</button>
											<a href="<?php echo base_url('Home/citizen_due_details/'.$id);?>" type="button" class="button button1">View Demand Details</a>
			<a href="<?php echo base_url('Home/citizen_property_details/'.$id);?>" type="button" class="button button2">View Property Details</a>
										</td>
										
										
									</tr>
	
	</tbody>
	</table>
  
			</div><br><br>
			
			
							</form>
				
					</div><br><br>
					
					
			
	  </div>
	</div>
	</div>

      </div>
    </section><!-- End Contact Section -->
  
<?= $this->include('layout_horizontal/footer');?>
<script>

function validate() {
        var chk = document.getElementById("checkbox");
        var btn = document.getElementById("proceed_To_Pay");
        if (chk.checked == true)
            btn.disabled = false;
        else
            btn.disabled = true;
    }
	
function calculate(){
		
		var totl_dmnd = $("#totl_dmnd").val();
		var tl_qtr = $("#tl_qtr").val();
		var date_upto_qtr = $("#date_upto_qtr").val();
		var tlt_qtr = tl_qtr-(4-date_upto_qtr)
		var tol_mnth = parseFloat(tl_qtr)*3;
		var dif_qtr = 0;
		var tol_pent = 0;
		var i=1;
		for(i=1; i<tlt_qtr; i++)
		{
			dif_qtr = parseFloat(dif_qtr) + 3;
			var each_penlty = (parseFloat(totl_dmnd)/100)*(parseFloat(tol_mnth)-parseFloat(dif_qtr));
			tol_pent = tol_pent + each_penlty;
		}
        var date_upto_qtr = $("#date_upto_qtr").val();
        var qtrl_tax = $("#qtrl_tax").val();
		var total_payable = $("#total_payable").val();
		var total_demand = $("#total_demand").val();
		var totl_due = $("#totl_due").val();
		var total_rebate = $("#total_rebate").val();
		var total_pay_amount= (parseFloat(totl_due)-((parseFloat(qtrl_tax)*4)-(parseFloat(qtrl_tax)*parseFloat(date_upto_qtr)))+parseFloat(tol_pent));
		
		var pay_amt = parseFloat(total_pay_amount)-parseFloat(total_rebate);
		//pay_amount = (date_upto_qtr*qtrl_tax)+total_payable;
		if(date_upto_qtr==""){
            //$("#asset_quantity").prop("readonly", false);
			$("#total_payable").val("0");
			//$("#total_demand").val(pay_amount);
            
        }
        else{
            //$("#asset_quantity").prop("readonly", true);    
            $("#total_payable").val(pay_amt);
			$("#total_demand").val(total_pay_amount); 
			$("#total_qrt").val(tlt_qtr);
			$("#total_qrt_pnlty").val(tol_pent);
        }
    }
	
	
	
	
        $("#proceed_To_Pay").click(function(){
			proceed = true;
            var date_upto_qtr = $("#date_upto_qtr").val();
            var payment_mode = $("#payment_mode").val();
            if(date_upto_qtr=="")
			{
				alert("Please Select Upto Quater");
				$("#date_upto_qtr").css('border-color', 'red');
				return false;
			}

			if(payment_mode=="")
			{
				alert("Please Select Payment Mode");
				$("#payment_mode").css('border-color', 'red');
				return false;
			}
		return process;
		 });
		 $("#date_upto_qtr").change(function(){ $(this).css('border-color',''); });
		$("#payment_mode").change(function(){ $(this).css('border-color',''); });
		 /*$("#item_name_id").trigger("change");
		 $("#sub_item_name_id").trigger("change");
		 $("#model_no_id").trigger("change");
		 $("#serial_no").trigger("change");*/

</script>
 