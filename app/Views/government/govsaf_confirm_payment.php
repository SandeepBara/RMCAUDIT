
<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
</style>
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Pay Property Tax</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>

<!-- ======= Cta Section ======= -->

			<div id="page-content">
				<div class="panel panel-bordered panel-dark">
							
					<div class="panel-heading">
						<div class="panel-control">
							<button onclick="goBack()" class="btn btn-info">Go Back</button>
						</div>
						<h3 class="panel-title">Basic Details</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-3">
								<b>Ward No. :</b>
							</div>
							<div class="col-sm-3">
								<?=$basic_details['ward_no']?$basic_details['ward_no']:"N/A"; ?>
							</div>
							
							<div class="col-sm-3">
								<b>Application No. :</b>
							</div>
							<div class="col-sm-3">
								<?=$basic_details['application_no']?$basic_details['application_no']:"N/A"; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b>Property Type :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['property_type']?$basic_details['property_type']:"N/A"; ?>
							</div>
							
							<div class="col-md-3">
								<b>Ownership Type :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['ownership_type']?$basic_details['ownership_type']:"N/A"; ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<b>Rainwater Harvesting Provision :</b>
							</div>
							<div class="col-md-3">
								<?php if($basic_details['is_water_harvesting']=='t'){ ?>
								YES
								<?php } else if($basic_details['is_water_harvesting']=='f'){ ?>
								No
								<?php }else{ ?>
								N/A
								<?php } ?>
							</div>
							<div class="col-md-3">
								<b>Assessment Type :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['assessment_type']?$basic_details['assessment_type']:"N/A"; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b>Office Name :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['office_name']?$basic_details['office_name']:"N/A"; ?>
							</div>
							
							<div class="col-md-3">
								<b>Building Colony Name :</b>
							</div>
							<div class="col-md-3">
								<?=$basic_details['building_colony_name']?$basic_details['building_colony_name']:"N/A"; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<b>Address :</b>
							</div>
							<div class="col-md-9">
								<?=$basic_details['address']?$basic_details['address']:"N/A"; ?>
							</div>
						</div>
					</div>
				</div>
					
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Tax Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
									<th scope="col">Sl No.</th>
									<th scope="col">ARV</th>
									<th scope="col">Effect From</th>
									<th scope="col">Holding Tax</th>
									<th scope="col">Water Tax</th>
									<th scope="col">Conservancy/Latrine Tax</th>
									<th scope="col">Education Cess</th>
									<th scope="col">Health Cess</th>
									<th scope="col">RWH Penalty</th>
									<th scope="col">Quarterly Tax</th>
									<th scope="col">Status</th>
							</thead>
							<tbody>
								<?php if($tax_list):
							  $i=1; $qtr_tax=0; $lenght= sizeOf($tax_list); ?>
							  <?php foreach($tax_list as $tax_list): 
							  $qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] +$tax_list['additional_tax'];
							  ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $tax_list['arv']; ?></td>
									<td><?php echo $tax_list['qtr'];?> / <?php echo $tax_list['fy']; ?></td>
									<td><?php echo $tax_list['holding_tax']; ?></td>
									<td><?php echo $tax_list['water_tax']; ?></td>
									<td><?php echo $tax_list['latrine_tax']; ?></td>
									<td><?php echo $tax_list['education_cess']; ?></td>
									<td><?php echo $tax_list['health_cess']; ?></td>
									<td><?php echo $tax_list['additional_tax']; ?></td>
									<td><?php echo $qtr_tax; ?></td>
									<?php if($i>$lenght){ ?>
										<td style="color:red;">Current</td>
									<?php } else { ?>
										<td>Old</td>
									<?php } ?>
								</tr>
								<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="11" style="text-align:center;"> <b>Data not available!!</b></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
					
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Payment Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
									<th scope="col"> Sl No.</th>
									<th scope="col"> Transaction No</th>
									<th scope="col"> Date</th>
									<th scope="col"> Payment From </th>
									<th scope="col"> Payment Upto </th>
									<th scope="col"> Amount</th>
									<th scope="col"> View</th>
							</thead>
							<tbody>
								<?php if(isset($payment_detail)):
								$i=1;
								?>
								<?php foreach($payment_detail as $payment_detail): 
								?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $payment_detail['tran_no']; ?></td>
									<td><?php echo $payment_detail['tran_date']; ?></td>
									<td><?php echo $payment_detail['from_qtr']." / ".$payment_detail['from_fy']; ?></td>
									<td><?php echo $payment_detail['upto_qtr']." / ".$payment_detail['from_upto']; ?></td>
									<td><?php echo $payment_detail['payable_amt']; ?></td>
									<td><a href="<?php echo base_url('govsafDetailPayment/govsaf_payment_receipt/'.md5($payment_detail['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
								</tr>
								<?php endforeach; ?>
								<?php else: ?>
								<tr>
									<td colspan="10" style="text-align:center;color:red;"> Data Not Available...</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
					
				<?php if(isset($payment_detail) && empty($payment_detail)){?>
					<form action="<?php echo base_url('govsafDetailPayment/govsaf_confirm_payment/'.$id);?>" method="post">
						<input type="hidden" class="form-control" id="custm_id" name="custm_id" value="<?php echo $basic_details["id"]; ?>">
						<input type="hidden" class="form-control" id="ward_mstr_id" name="ward_mstr_id" value="<?php echo $basic_details["ward_mstr_id"]; ?>">
						
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Pay Property Tax</h3>
							</div>
							<div id="loadingDivs" style="display: none; background: url(http://192.168.0.113/RMCDMC/public/assets/img/loaders/transparent-background-loading.gif) no-repeat center center; position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 9999999;"></div>
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<tbody>
									<?php if($demand_detail):
									$i=1; $total_due = 0;
									?>
									<?php foreach($demand_detail as $tot_demand):
									$i==1? $first_qtr = $tot_demand['qtr']:'';
									$i==1? $first_fy = $tot_demand['fy_mstr_id']:'';
									$i==1? $fir_fy = $tot_demand['fy']:'';
									$total_fyid =$tot_demand['fy_mstr_id'];
									$total_demand =$tot_demand['balance'];								/*$tot_demand['holding_tax']+$tot_demand['water_tax']+$tot_demand['education_cess']+$tot_demand['health_cess']+$tot_demand['lighting_tax']+$tot_demand['latrine_tax']+$tot_demand['additional_tax'];
									$total_demand = $demand;*/
									$total_due = $total_due + $total_demand;
									$total_quarter=$i;
									$rebate=0;?>
									<input type="hidden" class="form-control" id="totl_fy<?=$i;?>" name="totl_fy[]" value="<?php echo $total_fyid; ?>">
									<input type="hidden" class="form-control" id="totl_dmnd<?=$i;?>" name="totl_dmnd[]" value="<?php echo $total_demand; ?>">
									
									<?php $i++;
									?>
									<?php endforeach; ?>
									<?php $month = date("m");?>
									
									<tr>
										<td>Due Upto Year</td>
										<input type="hidden" class="form-control" id="crnFy_id" name="crnFy_id" value="<?php echo $fy_id['id']; ?>">
										<input type="hidden" class="form-control" id="crnt_dm" name="crnt_dm" value="<?php echo $month; ?>">
										<input type="hidden" class="form-control" id="totl_due" name="totl_due" value="<?php echo $total_due; ?>">
										<td>
											<div class="form-group">
												<select id="due_upto_year" name="due_upto_year" class="form-control m-t-xxs">
													<option value="">Select Financial Year</option>
													<?php if($fydemand): ?>
													<?php foreach($fydemand as $post): ?>
													<option value="<?php echo $post['fy_id']; ?>"><?php echo $post['fy']; ?></option>
													<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
										</td>
										<td>Due Upto Quarter<span class="text-danger">*</span>
										<input type="hidden" class="form-control" id="from_fy_year" name="from_fy_year" value="<?php echo $first_fy; ?>">
										<input type="hidden" class="form-control" id="ful_qtr" name="ful_qtr" value="<?php echo $total_quarter; ?>">
										<input type="hidden" class="form-control" id="tl_qtr" name="tl_qtr" value="">
										<input type="hidden" class="form-control" id="from_fy_qtr" name="from_fy_qtr" value="<?php echo $first_qtr; ?>">
										<input type="hidden" class="form-control" id="lastfy_qtr" name="lastfy_qtr" value="">
										</td>
										<td>
											<select class="form-control" id="date_upto_qtr" name="date_upto_qtr" onchange="calculate()">
												<option value="" >Choose...</option>
											</select>
										</td>
									</tr>
									<tr style="height: 63px;">
										<td>Total Demand</td>
										<td><input type="text" class="form-control" id="total_demand" name="total_demand" value="<?php echo $total_due; ?>" readonly>
										</td>
										<td>Rebate Amount</td>
										<td><input type="text" class="form-control" id="total_rebate" name="total_rebate" value="0.00" onkeyup="calculatePay();"  onkeypress="return isNumber(event);" readonly>
										<input type="hidden" class="form-control" id="total_qrt" name="total_qrt" value="" readonly>
										<input type="hidden" class="form-control" id="total_qrt_pnlty" name="total_qrt_pnlty" value="" readonly>
										</td>
									</tr>
									<?php endif?>
									<tr>
										
										<td>Advance</td>
										<td colspan="1"><input type="text" class="form-control" id="advance" name="advance" value="0.00" readonly>
										</td>
										
										<td>Late Assessment Fine(Rule 14.1)</td>
										<td colspan="1"><input type="text" class="form-control" id="late_fine" name="late_fine" value="<?=isset($latefine)?$latefine:"0.00"; ?>" readonly>
										</td>
									</tr>
									
									<tr>
										<td>Difference Penalty</td>
										<td colspan="1"><input type="text" class="form-control" id="difference_Penalty" name="difference_Penalty" value="<?=(isset($difference_Penalty['penalty_amt']))?$difference_Penalty['penalty_amt']:"0.00";?>" readonly>
										</td>
										<td>1% Interest</td>
										<td colspan="1"><input type="text" class="form-control" id="total_penalty" name="total_penalty" value="0.00" readonly>
										</td>
										
									</tr>
									<tr>
										
										<td>Total Amount</td>
										<td colspan="1"><input type="text" class="form-control" id="total_payabl" name="total_payabl" value="0.00" readonly>
										</td>
										<td>Total Payable Amount</td>
										<td colspan="1"><input type="text" class="form-control" id="total_payabl_amnt" name="total_payabl_amnt" value="0.00" >
										</td>
										
									</tr>
									<tr>
										<td>Payment Mode <span class="text-danger">*</span></td>
										<td colspan="1">
											<select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()">
												<option value="" >Choose...</option>
												<?php if($tran_mode):?>
												<?php foreach($tran_mode as $tran_mode): ?>
												<option value="<?php echo $tran_mode['id']; ?>"><?php echo $tran_mode['transaction_mode']; ?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</td>
										<td colspan="2"></td>
									</tr>
									<tr id="chq" hidden>
										<td>Cheque/DD Date <span class="text-danger">*</span></td>
										<td colspan="1"><input type="text" class="form-control mask_date" id="chq_date" name="chq_date" value="<?=(isset($data['chq_date']))?$data['chq_date']:"";?>" placeholder="Enter Cheque/DD Date">
										</td>
										<td>Cheque/DD No. <span class="text-danger">*</span></td>
										<td colspan="1"><input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No." onkeypress="return isNumberKey(event);">
										</td>
									</tr>
									<tr id="bank" hidden>
										<td>Bank Name <span class="text-danger">*</span></td>
										<td colspan="1"><input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder=" Enter Bank Name">
										</td>
										<td>Branch Name <span class="text-danger">*</span></td>
										<td colspan="1"><input type="text" class="form-control" id="branch_name" name="branch_name" value="" placeholder=" Enter Branch Name">
										</td>
									</tr>
									
									<?php if($bank_reCancel){ ?>
									<tr id="bank_recancelation">
										<td colspan="4" id="terms">
											<label >Your cheque is cancel due to "<b style="font-weight: bold; color: #d70707"><?=$bank_reCancel['reason']; ?></b>" <br > cheque no <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['cheque_no']; ?>" |</b> bank name <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['bank_name']; ?>" |</b> branch name <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['branch_name']; ?>" |</b> cheque issue date <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['cheque_date']; ?>" |</b> and cheque cancellation charge "<b style="font-weight: bold; color: #d70707"><?=$bank_reCancel['amount']; ?></b>" <br> You need to re-pay it by CASH</label><br>
												<input type="checkbox" id="checkbox" value="agreed" onchange="validate()" name="agreement">
												&nbsp;&nbsp;<label for="checkbox"><b>You need to check before proceed to pay </b></label>
										
										</td>
										
										<input type="hidden" class="form-control" id="chq_cancel_charge" name="chq_cancel_charge" value="<?=(isset($bank_reCancel['amount']))?$bank_reCancel['amount']:"0.00";?>" >
										
									</tr>
									<?php } ?>
									
								</tbody>
							</table><br><br>
						</div>
						
						<div class="panel">
							<div class="panel-body text-center">
							<?php if(isset($payment_detail) && !empty($payment_detail)): ?>
								<b class="text-success"> Payment Already Done </b>
							<?php else: ?>
								<button type="submit" class="btn btn-purple btn-labeled" id="proceed_To_Pay" name="proceed_To_Pay">Proceed To Pay</button>
							<?php endif?>
							</div>
						</div>
						
					</form>
				<?php } ?>
			</div>
						
		
			
  
<?= $this->include('layout_vertical/footer');?>
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
		
		var pd_mod = $("#payment_mode").val();
		var due_upto_year = $("#due_upto_year").val();
		var crnFy_id = $("#crnFy_id").val();
		var tl_qtr = $("#tl_qtr").val();
		var ful_qtr = $("#ful_qtr").val();
		var crnt_dm = $("#crnt_dm").val();
		var late_fine = $("#late_fine").val();
		var info_hidng_penalty = $("#info_hidng_penalty").val();
		var advance = $("#advance").val();
		var date_upto_qtr = $("#date_upto_qtr").val();
		var lastfy_qtr = $("#lastfy_qtr").val();
		var total_payabl = $("#total_payabl").val();
		var total_demand = $("#total_demand").val();
		var totl_due = $("#totl_due").val();
		var total_rebate = $("#total_rebate").val();
		var tlt_qtr = tl_qtr-(lastfy_qtr-date_upto_qtr);
		var crnt_dmr;
		var tol_mnths;
		var tol_mnth;
		var dif_qtr = 0;
		var tol_pent = 0;
		var i;
		var rebate;
		var rebate_demand = 0;
		
		if(crnt_dm=='01' || crnt_dm=='02' || crnt_dm=='03')
		{
			crnt_dm = parseFloat(crnt_dm)+9;
			crnt_dmr=(12-crnt_dm);
			tol_mnths = (ful_qtr*3)-crnt_dmr;
		}else{
			crnt_dmr=(12-crnt_dm)+3;
			tol_mnths = (ful_qtr*3)-crnt_dmr;
		}
		tol_mnth = tol_mnths;
		
		if(crnt_dm=='04' || crnt_dm=='05' || crnt_dm=='06'){
			if(date_upto_qtr==4){
				if(crnFy_id == due_upto_year){
					var loop_start_val = tlt_qtr-4;
					loop_start_val = loop_start_val + 1;
					for(i=loop_start_val; i<=tlt_qtr; i++){
						var ID = i;
						var totl_dmnd = $("#totl_dmnd"+ID).val();
						var rebate_demand = parseFloat(rebate_demand) + parseFloat(totl_dmnd);
					}
					rebate = (rebate_demand/100)*5;
				}
				else{ rebate = 0; }
			}
			else{ rebate = 0; }
		}else{ rebate = 0; }
		var total_dem= 0;
		//alert(crnt_dm);
		for(i=1; i<=tlt_qtr; i++){
			ID = i;
			totl_dmnd = $("#totl_dmnd"+ID).val();
			dif_qtr = dif_qtr + 3;
			total_dem= total_dem + parseFloat(totl_dmnd);
			var totl_fy = $("#totl_fy"+ID).val();
			//alert(totl_fy);
			if (totl_fy>=49) {
				if (tol_mnth>=3) {
					var each_penlty = (parseFloat(totl_dmnd)/100)*(parseFloat(tol_mnth)-parseFloat(dif_qtr));
					if(each_penlty>0){
						tol_pent = tol_pent + each_penlty;
					}else{
						tol_pent = tol_pent;
					}
				}else { tol_pent = tol_pent; }	
			}else { tol_pent = tol_pent; }			
				
		}
		
		//alert(totl_dmnd);
		var payd_amt = (parseFloat(total_dem)+parseFloat(late_fine)+parseFloat(tol_pent))-parseFloat(rebate);
		
		var payabl_amnt = Math.round(payd_amt);
		//pay_amount = (date_upto_qtr*qtrl_tax)+total_payable;
		
		if(date_upto_qtr==""){
			$("#total_payabl").val("0");
		}
        else{
            //$("#asset_quantity").prop("readonly", true);    
            $("#total_payabl").val(payd_amt.toFixed(2));
			$("#total_payabl_amnt").val(payabl_amnt.toFixed(2));
			$("#total_penalty").val(tol_pent.toFixed(2));
			$("#total_demand").val(total_dem.toFixed(2)); 
			$("#total_qrt").val(tlt_qtr.toFixed(2));
			$("#total_qrt_pnlty").val(tol_pent.toFixed(2));
			$("#total_rebate").val(rebate.toFixed(2));
			
        }
    }
	
	
	
	
        $("#proceed_To_Pay").click(function(){
			proceed = true;
            var date_upto_qtr = $("#date_upto_qtr").val();
            var payment_mode = $("#payment_mode").val();
			var jsk_id = $("#jsk_type").val();
            var chq_date = $("#chq_date").val();
            var chq_no = $("#chq_no").val();
			var bank_name = $("#bank_name").val();
            var branch_name = $("#branch_name").val();
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
			
			if(payment_mode==4)
			{
				if(jsk_id==8)
				{
					alert("JSK Are Not Allowed To Pay Online");
					$("#payment_mode").css('border-color', 'red');
					return false;
				}
			}
			if(payment_mode==2 || payment_mode==3){
				if(chq_date=="")
				{
					alert("Please mention here cheque date");
					$("#chq_date").css('border-color', 'red');
					return false;
				}
				if(chq_no=="")
				{
					alert("Please mention here 6 digit cheque Number");
					$("#chq_no").css('border-color', 'red');
					return false;
				}
				if(bank_name=="")
				{
					alert("Please mention bank name");
					$("#bank_name").css('border-color', 'red');
					return false;
				}
				if(branch_name=="")
				{
					alert("Please mention branch name");
					$("#branch_name").css('border-color', 'red');
					return false;
				}
			}
			confirm("Are you sure to make payment");
			$("#proceed_To_Pay").hide();
		return process;
		 });
		 $("#date_upto_qtr").change(function(){ $(this).css('border-color',''); });
		 $("#payment_mode").change(function(){ $(this).css('border-color',''); });
		 $("#chq_date").change(function(){ $(this).css('border-color',''); });
		 $("#chq_no").change(function(){ $(this).css('border-color',''); });
		 $("#bank_name").change(function(){ $(this).css('border-color',''); });
		 $("#branch_name").change(function(){ $(this).css('border-color',''); });
		 


</script>
 
 
 <script>
function myFunction() {
  var mode = document.getElementById("payment_mode").value;
	if (mode == 2) {
		$('#chq').show(); 
		$('#bank').show();
	} else if(mode == 3) {
		$('#chq').show(); 
		$('#bank').show();
	} else {
		$('#chq').hide(); 
		$('#bank').hide();
	}
	
	calculate()
}
</script>

<script type="text/javascript">

    $('#chq_date').datepicker({
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
	
	
	
	
	
	
	
$('#due_upto_year').change(function(){
	
	var custm_id = $("#custm_id").val();
	var due_upto_year = $("#due_upto_year").val();
	if(due_upto_year!=""){
		//alert(due_upto_year);
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('govsafDetailPayment/ajax_gatequarter'); ?>",
			dataType:"json",
			data:
			{ 
				due_upto_year:due_upto_year,custm_id:custm_id
			},
			beforeSend: function() {
				$("#loadingDivs").show();
			},
			success: function(data){
				$("#loadingDivs").hide();
				console.log(data);
				if(data.response==true){
					$("#date_upto_qtr").html(data.data);
					$("#tl_qtr").val(data.val);
					$("#lastfy_qtr").val(data.last);
				}else{
					$("#date_upto_qtr").html("<option value=''>Select Quarter</option>");
				}
			}
		});
	}

});
	
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php if($result = flashToast('govsaf_confirm_payment')) { ?>
	modelInfo('<?=$result;?>');
<?php }?>	

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


</script>

<script>
function goBack() {
  window.history.back();
}
</script>