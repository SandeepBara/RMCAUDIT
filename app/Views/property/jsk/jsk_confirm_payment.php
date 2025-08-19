
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
				<li><a href="<?=base_url("propDtl/full/$id");?>">Property Details</a></li>
				<li class="active">Pay Property Tax</li>
				</ol>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End breadcrumb-->

			</div>

<!-- ======= Cta Section ======= -->

			<div id="page-content">
				
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Property Details</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-md-3">Hoding No </label>
						<div class="col-md-3 text-bold pad-btm">
						<?=$holding_no;?>
						</div>
						
						<label class="col-md-3">Ward No</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$ward_no;?>
						</div>
					</div>

					<div class="row">
						<label class="col-md-3">Assessment Type</label>
						<div class="col-md-3 text-bold pad-btm">
						<?php
						echo $assessment_type;
						if($assessment_type=='Mutation')
						{
							echo '  ('.$transfer_mode.')';
						}
						?>
						</div>
						

						<label class="col-md-3">Entry Type</label>
						<div class="col-md-3 text-bold pad-btm">
						<?=($entry_type);?>
						</div>
					</div>

				
					<div class="row">
						<label class="col-md-3">Order Date</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=date("d-m-Y", strtotime($created_on));?>
						</div>
						
						<label class="col-md-3">Old Holding (If Any)</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$holding_no;?>
						</div>
					</div>
					<div class="row">
						<label class="col-md-3">Property Type</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$property_type;?>
						</div>
						<label class="col-md-3">Ownership Type</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$ownership_type;?>
						</div>
					</div>
					<div class="<?=($prop_type_mstr_id==3)?null:"hidden";?>">
						<div class="row">
							<label class="col-md-3">Appartment Name</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$appartment_name;?>
							</div>
							<label class="col-md-3">Registry Date</label>
							<div class="col-md-3 text-bold pad-btm">
								<?=$flat_registry_date;?>
							</div>
						</div>
					</div>

					<div class="row">
						<label class="col-md-3">Road Type</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$road_type;?>
						</div>

						<label class="col-md-3">Holding Type</label>
						<div class="col-md-3 text-bold pad-btm">
							<?=$holding_type;?>
						</div>
					</div>
				
					<div class="row">
						<?php
						if($ulb["ulb_mstr_id"]==1)
						{
							?>
							<label class="col-md-3">Rain Water Harvesting</label>
							<div class="col-md-3 text-bold">
							<?=($is_water_harvesting=="t")?"Yes":"No";?>
							</div>
							<?php
						}
						?>
						<label class="col-md-3">Zone</label>
						<div class="col-md-3 text-bold">
							<?=($zone_mstr_id==1)?"Zone 1":"Zone 2";?>
						</div>
					</div>
				
				</div>
			</div>


			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Owner Details</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered text-sm">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>Owner Name</th>
											<th>Guardian Name</th>
											<th>Relation</th>
											<th>Mobile No</th>
											<th>Aadhar No.</th>
											<th>PAN No.</th>
											<th>Email</th>
										</tr>
									</thead>
									<tbody id="owner_dtl_append">
									<?php
									if(isset($prop_owner_detail))
									{
										foreach ($prop_owner_detail as $owner_detail)
										{
											?>
											<tr>
												<td><?=$owner_detail['owner_name'];?></td>
												<td><?=$owner_detail['guardian_name'];?></td>
												<td><?=$owner_detail['relation_type'];?></td>
												<td><?=$owner_detail['mobile_no'];?></td>
												<td><?=$owner_detail['aadhar_no'];?></td>
												<td><?=$owner_detail['pan_no'];?></td>
												<td><?=$owner_detail['email'];?></td>
											</tr>
											<?php
										}
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Tax Details</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered text-sm">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Sl No.</th>
									<th>ARV</th>
									<th>Effect From</th>
									<th>Holding Tax</th>
									<th>Water Tax</th>
									<th>Conservancy/Latrine Tax</th>
									<th>Education Cess</th>
									<th>Health Cess</th>
									<th>RWH Penalty</th>
									<th>Quarterly Tax</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							if($prop_tax_list)
							{
								$i=1; $qtr_tax=0;$lenght= sizeOf($prop_tax_list);
								foreach($prop_tax_list as $tax_list)
								{
									$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
									?>
									<tr>
										<td><?=$i++;?></td>
										<td><?=round($tax_list['arv'], 2);?></td>
										<td><?=$tax_list['qtr'];?> / <?=$tax_list['fy'];?></td>
										<td><?=round($tax_list['holding_tax'], 2);?></td>
										<td><?=round($tax_list['water_tax'], 2);?></td>
										<td><?=round($tax_list['latrine_tax'], 2);?></td>
										<td><?=round($tax_list['education_cess'], 2);?></td>
										<td><?=round($tax_list['health_cess'], 2);?></td>
										<td><?=round($tax_list['additional_tax'], 2);?></td>
										<td><?=round($qtr_tax, 2); ?></td>
										<td>
											<?php 
											if($i>$lenght)
											{
												?>
												<span class="text text-success text-bold">Current</span>
												<?php 
											}
											else
											{
												?>
												<span class="text text-danger text-bold">Old</span>
												<?php 
											}
											?>
										</td>
									</tr>
									<?php 
								}
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

				<form action="<?php echo base_url('jsk/jsk_confirm_payment/'.$id);?>" method="post">
				<input type="hidden" class="form-control" id="custm_id" name="custm_id" value="<?=$prop_dtl_id;?>">
				<input type="hidden" class="form-control" id="ward_mstr_id" name="ward_mstr_id" value="<?=$ward_mstr_id;?>">
				
					
				
					<div id="loadingDivs" style="display: none; background: url(<?php base_url();?>/public/assets/img/loaders/dotloader.gif) no-repeat center center; position: absolute; top: 150px; left: 0; height: 100%; width: 100%; z-index: 9999999;">
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Pay Property Tax</h3>
						</div>
						
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<tbody>
								  <?php if($demand_detail):
								  $i=1; $total_due = 0;
								  ?>
								  <?php foreach($demand_detail as $tot_demand):
								  $i==1? $first_qtr = $tot_demand['qtr']:'';
								  $i==1? $first_fy = $tot_demand['fy_id']:'';
								  $i==1? $fir_fy = $tot_demand['fy']:'';
								  $total_fyid =$tot_demand['fy_id'];
								  $total_demand =$tot_demand['balance'];
								  $total_due = $total_due + $total_demand;
								  $total_quarter=$i;
								  $rebate=0;?>
								  <input type="hidden" class="form-control" id="totl_fy<?=$i;?>" name="totl_fy[]" value="<?php echo $total_fyid; ?>">
								  <input type="hidden" class="form-control" id="totl_dmnd<?=$i;?>" name="totl_dmnd[]" value="<?php echo $total_demand; ?>">
								  <?php $i++;
								  ?>
								  <?php endforeach; ?>
								  <?php $month = date("m");
									
									
								  ?>
								<tr>
									<td>Due Upto Year</td>
									<input type="hidden" class="form-control" id="crnFy_id" name="crnFy_id" value="<?php echo $fy_id['id']; ?>">
									<input type="hidden" class="form-control" id="crnt_dm" name="crnt_dm" value="<?php echo $month; ?>">
									<input type="hidden" class="form-control" id="totl_due" name="totl_due" value="<?php echo $total_due; ?>">
									<td>
										<div class="form-group">
											<select id="due_upto_year" name="due_upto_year" class="form-control m-t-xxs">
												
												<?php if($fydemand): ?>
												<?php foreach($fydemand as $post): ?>
												<option value="<?php echo $post['fy_id']; ?>"><?php echo $post['fy']; ?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</td>
									<td>Due Upto Quarter <span class="text-danger">*</span>
									<input type="hidden" class="form-control" id="from_fy_year" name="from_fy_year" value="<?php echo $first_fy; ?>">
									<input type="hidden" class="form-control" id="tl_qtr" name="tl_qtr" value="">
									<input type="hidden" class="form-control" id="lastfy_qtr" name="lastfy_qtr" value="">
									<input type="hidden" class="form-control" id="ful_qtr" name="ful_qtr" value="<?php echo $total_quarter; ?>">
									<input type="hidden" class="form-control" id="from_fy_qtr" name="from_fy_qtr" value="<?php echo $first_qtr; ?>">
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
									<?php 
									/*$advnce_amnt = 0;
									if($advance):
									if($advance['arear_amt']>0){
									$advnce_amnt = $advance['arear_amt']-$advance['adjust_advnc_amnt'];
									}*/
									?>
									<td>Advance</td>
									<td colspan="1"><input type="text" class="form-control" id="advance" name="advance" value="<?=$advance?$advance:"0.00"; ?>" readonly>
									</td>
									<?php //else:?>
									<!--<td>Advance</td>
									<td colspan="1"><input type="text" class="form-control" id="advance" name="advance" value="" readonly>
									</td>-->
									<?php 
									//endif?>
									<td>1% Interest</td>
									<td colspan="1"><input type="text" class="form-control" id="total_penalty" name="total_penalty" value="0.00" readonly>
									</td>
								</tr>	
								<tr>
									<td>Difference Penalty</td>
									<td colspan="1"><input type="text" class="form-control" id="difference_Penalty" name="difference_Penalty" value="<?=(isset($difference_Penalty['penalty_amt']))?$difference_Penalty['penalty_amt']:"0.00";?>" readonly>
									</td>
									<input type="hidden" class="form-control" id="jsk_type" name="jsk_type" value="<?php echo $emp_type_id; ?>" readonly>
									<td>Rebate Amount of JSK</td>
									<td colspan="1"><input type="text" class="form-control" id="rbt_jsk_online" name="rbt_jsk_online" value="0.00" readonly>
									</td>
								</tr>
								<tr>
									<td>Total Amount</td>
									<td colspan="1"><input type="text" class="form-control" id="total_payabl" name="total_payabl" value="0.00" readonly>
									</td>
									<td>Total Payable Amount</td>
									<td colspan="1"><input type="text" class="form-control" id="total_payabl_amnt" name="total_payabl_amnt" value="0.00" readonly>
									</td>
								</tr>
								<tr>
									<td>Payment Mode <span class="text-danger">*</span></td>
									<td colspan="1">
										<?php if(isset($bank_reCancel) && $bank_reCancel){ ?>
											<select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()">
												<option value="1">CASH</option>
											</select>
										<?php }else{ ?>
											<select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()">
												<option value="" >Choose...</option>
												<?php if($tran_mode):?>
												<?php foreach($tran_mode as $tran_mode): ?>
												<?php if($tran_mode['id']!=4){ ?>
												<option value="<?php echo $tran_mode['id']; ?>"><?php echo $tran_mode['transaction_mode']; ?></option>
												<?php } ?>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										<?php } ?>
									</td>
									<td>Rest Advance Amount</td>
									<td colspan="1"><input type="text" class="form-control" id="rest_amnt" name="rest_amnt" value="0.00" readonly>
									</td>
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
								<?php if(isset($bank_reCancel) && $bank_reCancel){ ?>
								<tr id="bank_recancelation">
									<td colspan="4" id="terms">
										<label >Your cheque is cancel due to "<b style="font-weight: bold; color: #d70707"><?=$bank_reCancel['reason']; ?></b>" <br > cheque no <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['cheque_no']; ?>" |</b> bank name <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['bank_name']; ?>" |</b> branch name <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['branch_name']; ?>" |</b> cheque issue date <b style="font-weight: bold; color: #d70707">"<?=$bank_reCancel['cheque_date']; ?>" |</b> and cheque cancellation charge "<b style="font-weight: bold; color: #d70707"><?=$bank_reCancel['amount']; ?></b>" <br> You need to re-pay it by CASH</label><br>
											<input type="checkbox" id="checkbox" value="agreed" onchange="validate()" name="agreement">
											&nbsp;&nbsp;<label for="checkbox"><b>You need to check before proceed to pay </b></label>
									
									</td>
									
									<input type="hidden" class="form-control" id="chq_cancel_charge" name="chq_cancel_charge" value="<?=$bank_reCancel['amount'];?>" >
									
								</tr>
								<?php } else { ?>
									<input type="hidden" class="form-control" id="chq_cancel_charge" name="chq_cancel_charge" value="0.00">
								<?php } ?>
							</tbody>
						</table><br><br>
					</div>
					<div class="panel">
						<div class="panel-body text-center">
							<?php if($demand_detail){ ?>
								<?php if(isset($bank_reCancel) && $bank_reCancel){ ?>
									<button type="submit" disabled class="btn btn-primary" id="proceed_To_Pay" name="proceed_To_Pay">Proceed To Pay</button>
								<?php } else{ ?>
									<button type="submit" class="btn btn-primary" id="proceed_To_Pay" name="proceed_To_Pay">Proceed To Pay</button>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</form>
								
			</div>
		</div>
						
		
			
  
<?= $this->include('layout_vertical/footer');?>
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
</script>
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
		
		var chq_cancel_charge = $("#chq_cancel_charge").val();
		var difference_Penalty = $("#difference_Penalty").val();
		var due_upto_year = $("#due_upto_year").val();
		var crnFy_id = $("#crnFy_id").val();
		var pd_mod = $("#payment_mode").val();
		var jsk_id = $("#jsk_type").val();
		var tl_qtr = $("#tl_qtr").val();
		var ful_qtr = $("#ful_qtr").val();
		var crnt_dm = $("#crnt_dm").val();
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
		//alert(crnFy_id);
		if(crnt_dm=='04' || crnt_dm=='05' || crnt_dm=='06'){
			if(tlt_qtr >=4){
				if(crnFy_id == due_upto_year){
					if(date_upto_qtr==4){
						var loop_start_val = tlt_qtr-4;
						loop_start_val = loop_start_val + 1;
						for(i=loop_start_val; i<=tlt_qtr; i++){
							var ID = i;
							var totl_dmnd = $("#totl_dmnd"+ID).val();
							var rebate_demand = parseFloat(rebate_demand) + parseFloat(totl_dmnd);
						}
						rebate = (rebate_demand/100)*5;
					}else{ rebate = 0; }
				}else{ rebate = 0; }
			}
			else{ rebate = 0; }
		}else{ rebate = 0; }
		
		var total_dem= 0;
		
		for(i=1; i<=tlt_qtr; i++){
			ID = i;
			totl_dmnd = $("#totl_dmnd"+ID).val();
			dif_qtr = dif_qtr + 3;
			total_dem= total_dem + parseFloat(totl_dmnd);
			var totl_fy = $("#totl_fy"+ID).val();
			//alert(tol_mnth);
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
		
		//alert(tol_pent);
		var payd_amt = (parseFloat(total_dem)+parseFloat(tol_pent)+parseFloat(difference_Penalty)+parseFloat(chq_cancel_charge))-(parseFloat(rebate));
		//pay_amount = (date_upto_qtr*qtrl_tax)+total_payable;
		
		var pay_amt = 0;
		var pay_rb_onljsk=0;
		if(jsk_id!=4){
			pay_rb_onljsk = (total_dem/100)*2.5;
			pay_amt = payd_amt - pay_rb_onljsk;
		}else if(pd_mod==4){
			pay_rb_onljsk = (total_dem/100)*5;
			pay_amt = payd_amt - pay_rb_onljsk;
		}else{ pay_amt = payd_amt; }
		
		var paybl_amnt = Math.round(pay_amt-parseFloat(advance));
		if(paybl_amnt<0){
			payabl_amnt = 0;
			rest_amnt = (-paybl_amnt);
		}else{
			payabl_amnt = paybl_amnt;
		}
		
		if(date_upto_qtr==""){
			$("#total_payabl").val("0");
            
        }
        else{
            $("#total_payabl").val(pay_amt.toFixed(2));
			$("#total_payabl_amnt").val(payabl_amnt.toFixed(2));
			$("#total_penalty").val(tol_pent.toFixed(2));
			$("#total_demand").val(total_dem.toFixed(2)); 
			$("#total_qrt").val(tlt_qtr.toFixed(2));
			$("#total_qrt_pnlty").val(tol_pent.toFixed(2));
			$("#total_rebate").val(rebate.toFixed(2));
			$("#rbt_jsk_online").val(pay_rb_onljsk.toFixed(2));
			$("#rest_amnt").val(rest_amnt.toFixed(2));
        }
    }
	
	
	
	
        $("#proceed_To_Pay").click(function(){
			
			proceed = true;
            var date_upto_qtr = $("#date_upto_qtr").val();
            var payment_mode = $("#payment_mode").val();
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
			$("#loadingDivs").show();
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
	
	calculate();
}





$('#due_upto_year').change(function(){
	var custm_id = $("#custm_id").val();
	var due_upto_year = $("#due_upto_year").val();
	if(due_upto_year!=""){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/jsk/ajax_gatequarter'); ?>",
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
				//console.log(data);
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

$(document).ready(function(){
	var custm_id = $("#custm_id").val();
	var due_upto_year = $("#due_upto_year").val();
	if(due_upto_year!=""){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('/jsk/ajax_rungatequarter'); ?>",
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
				//console.log(data);
				if(data.response==true){
					$("#date_upto_qtr").html(data.data);
					$("#tl_qtr").val(data.val);
					$("#lastfy_qtr").val(data.last);
				}else{
					$("#date_upto_qtr").html("<option value=''>Select Quarter</option>");
				}
				
				calculate();
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
<?php if($result = flashToast('jsk_confirm_payment')) { ?>
	modelInfo('<?=$result;?>');
<?php }?>


function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


</script>