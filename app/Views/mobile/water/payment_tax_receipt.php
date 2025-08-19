<?=$this->include("layout_mobi/header");?>

<div id="content-container">
	<div id="page-content">
    <div class="panel" style="border: #39a9b0 solid 2px;">        
		<div class="panel panel-bordered panel-mint">  
			<div class="panel-heading ">
				<strong class='panel-title'>Payment Receipt </strong>
				<a href="<?=base_url().(in_array(strtoupper($transaction_details['transaction_type']),[strtoupper('Demand Collection')])?'/WaterViewConsumerMobile/view/':'').($transaction_details['related_id']);?>" class="btn btn-dark pull-right">
					<i class="fa fa-arrow-left" aria-hidden="true"></i>Back
				</a>
			</div>
		</div>
        <div class="row-fluid">
        <div class="span4">
            <div class="panel">
                <div id="blutooth_printable_area">
					<?php 
						//$title='SOLID WASTE MANAGEMENT';
					$ulb_name = $ulb_mstr_name["ulb_name"];
					
					?>
						
					<center><b><?=$ulb_name;?></b></center><br />
						
					<center>
					WATER USER CHARGE PAYMENT RECEIPT
					</center><br />
					<center>------------------------------------------------</center><br />
					Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=date("d-m-Y",strtotime($transaction_details["transaction_date"]));?><br />
					TC Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $emp_dtls["emp_name"]; ?><br />
					TC Mobile No.&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?php echo $emp_dtls["personal_phone_no"]; ?><br />
					Consumer No.&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$consumer_details["consumer_no"]; ?> <br />
					Ward No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$consumer_details["ward_no"];?> <br />
					<?php
					$holding_no = "";
					$advance ="";
					$adjustment="";
					$meter = "";
					$fixed="";
					if(isset($consumer_details['holding_no']))
					{
						$holding_no="<n>Holding No     :  ".$consumer_details["holding_no"]."</n><br />";
						?>
						
						Holding No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?=$consumer_details["holding_no"];?> <br />
						<?php
					}
					elseif(isset($consumer_details['saf_no']))
					{
						$holding_no="<n>Saf No         :  ".$consumer_details["saf_no"]."</n><br />";
						?>
						
						Saf No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?=$consumer_details["saf_no"];?> <br />
						<?php
					}

					?>
					Citizen Name &nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$consumer_details["name"];?> <br />
					Guardian Name :&nbsp;&nbsp;<?=$consumer_details["father_name"];?> <br />
					Citizen Mobile No &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?=$consumer_details["mobile_no"];?> <br />
					Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  :&nbsp;&nbsp;<?=$consumer_details["address"];?> <br />
                
					<!--Dues From &nbsp;&nbsp;   :&nbsp;&nbsp;FY :  <br />
					Dues Upto &nbsp;&nbsp;   :&nbsp;&nbsp;FY :  <br />-->
					<!--Total Amount &nbsp;&nbsp;   :&nbsp;&nbsp;<?php //echo $total_demand_amt;?> <br />-->
                        
					<center>------------------------------------------------</center><br />
					Transaction No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["transaction_no"];?><br />
					<!-- Paid From &nbsp;&nbsp;   :&nbsp;&nbsp; <?=date('F',strtotime($transaction_details['from_month']));?><br />-->
				
                	Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?php echo (date('F',strtotime($transaction_details['from_month'])).' / '.date('Y',strtotime($transaction_details['from_month'])));

					if($transaction_details['upto_month']!=$transaction_details['from_month'])
					{

					
					?>

					to
					
					<?php echo date('F',strtotime($transaction_details['upto_month'])).' / '.date('Y',strtotime($transaction_details['upto_month']));


					}
					?><br>

					<!--Paid Upto &nbsp;&nbsp;  :&nbsp;&nbsp; <?=date('F',strtotime($transaction_details['upto_month']));?><br />-->



					Total Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; <?=$transaction_details["total_amount"];?><br />
					Penalty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; <?=$transaction_details["penalty"];?><br />
					Rebate &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=$transaction_details["rebate"];?><br />
					Paid Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  :&nbsp;&nbsp; <?=$transaction_details["paid_amount"];?><br />
					<?php
						if(isset($adjustment_amount) && !empty($adjustment_amount))
						{
							$adjustment="<n>Adjust Amount  :  ".$adjustment_amount['amount']."</n><br />";
							?>							
							Adjust Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  :&nbsp;&nbsp; <?=$adjustment_amount['amount']?></br>
							
							<?php
						}
						if(isset($advance_amount) && !empty($advance_amount))
						{
							$advance="<n>Advance Amount  :  ".$advance_amount['amount']."</n><br />";
							?>
							Advance Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  :&nbsp;&nbsp; <?=$advance_amount['amount']?></br>							
							<?php
						}
						if(isset($meter_reading) && !empty($meter_reading))
						{
							if(!empty($meter_reading['final_reading']))
							{	
								$meter ="<n>Meter Payment (".$meter_reading['initial_reading']." - ".$meter_reading['final_reading'].") :  ".$meter_reading['meter_payment']."</n><br />";
								?>
								Meter Payment (<?=$meter_reading['initial_reading']?> - <?=$meter_reading['final_reading']?>) : <?=$meter_reading['meter_payment']?></br>
								<?php
							}
							if(!empty($meter_reading['demand_upto']))
							{
								$fixed = "<n>Fixed Payment (".$meter_reading['demand_from']." - ".$meter_reading['demand_upto'].") :  ".$meter_reading['fixed_payment']."</n><br />";
								?>
								Fixed Payment (<?=$meter_reading['demand_from']?> - <?=$meter_reading['demand_upto']?>) : <?=$meter_reading['fixed_payment']?></br>
								
								<?php
							}														
						}
					?>
					Due Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=$transaction_details["due_amount"];?><br />
					
					Payment Mode &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["payment_mode"];?><br />
					<?php if($transaction_details["payment_mode"]=='CHEQUE' || $transaction_details["payment_mode"]=='DD'){ ?>
						Cheque No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  :&nbsp;&nbsp;<?=$transaction_details["cheque_no"];?><br />
						Bank Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["bank_name"];?><br />
						Branch Name &nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["branch_name"];?><br />
						Cheque Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=date('d-m-Y',strtotime($transaction_details["cheque_date"]));?><br />
					<?php } ?>
					<center>------------------------------------------------</center><br />                

                
                
                   <?php

							$txt = "";
							//$txt .= "<bc>".$ulb_mstr_name['ulb_name']."</bc><br />";
							//$txt .= "<nc>Citizen Copy</nc><br />";
							$txt .= "<nc>Water User Charge Payment Receipt</nc><br />";
							$txt .= "<n>-----------------------------------------</n><br />";
							$txt .= "<n>Date           :  ".date('d-m-Y',strtotime($transaction_details["transaction_date"]))."</n><br />";
							$txt .= "<n>Ward No.       :  ".$consumer_details["ward_no"]."</n><br />";	
							$txt .= "$holding_no";							
							$txt .= "<n>Consumer No.   :  ".$consumer_details["consumer_no"]."</n><br />";
							$txt .= "<n>Citizen Name   :  ".$consumer_details["name"]."</n><br />";
							$txt .= "<n>Guardian Name  :  ".$consumer_details["father_name"]."</n><br />";
							$txt .= "<n>Citizen Mobile No:  ".$consumer_details["mobile_no"]."</n><br />";
							$txt .= "<n>Address  :  ".$consumer_details["address"]."</n><br />";
							$txt .= "<n>-----------------------------------------</n><br />";
							$txt .= "<n>Transaction No.:  ".$transaction_details["transaction_no"]."</n><br />";
							$txt .= "<n>Period         :  ".(date('F',strtotime($transaction_details['from_month'])).'/'.date('Y',strtotime($transaction_details['from_month']))). (($transaction_details['upto_month']!=$transaction_details['from_month'])?(' to '.date('F',strtotime($transaction_details['upto_month'])).'/'.date('Y',strtotime($transaction_details['upto_month']))):'')."</n><br />";
							$txt .= "<n>Total Amount   :  ".$transaction_details["total_amount"]."</n><br />";
							$txt .= "<n>Penalty        :  ".$transaction_details["penalty"]."</n><br />";
							$txt .= "<n>Rebate         :  ".$transaction_details["rebate"]."</n><br />";
							$txt .= "<n>Amount Paid    :  ".$transaction_details["paid_amount"]."</n><br />";
							$txt .= "$adjustment";
							$txt .= "$advance";
							$txt .= "$meter";
							$txt .= "$fixed";
							$txt .= "<n>Due Amount    :  ".$transaction_details["due_amount"]."</n><br />";
							 
							//$txt .= "<n>Period     :  ".date('F',strtotime($transaction_details['from_month']))." - ".date('F',strtotime($transaction_details['upto_month']))."</n><br />";
							

							// $txt .= "</n>Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;". date('F',strtotime($transaction_details['from_month']))."</n><br />";

							
						if($transaction_details['upto_month']!=$transaction_details['from_month'])
						{
								
							?>

							<!-- to 		 -->

							<?php							
							
							//$txt.="</n>". date('F',strtotime($transaction_details['upto_month'])).' / '.date('Y',strtotime($transaction_details['upto_month']))."</n><br />";


						}
				




							$txt .= "<n>Payment Mode   :  ".$transaction_details["payment_mode"]."</n><br />";
							if($transaction_details["payment_mode"]=='CHEQUE' || $transaction_details["payment_mode"]=='DD'){
							$txt .= "<n>Cheque No.     :  ".$transaction_details["cheque_no"]."</n><br />";
							$txt .= "<n>Bank Name	   :  ".$transaction_details["bank_name"]."</n><br />";
							$txt .= "<n>Branch Name    :  ".$transaction_details["branch_name"]."</n><br />";
							$txt .= "<n>Cheque Date    :  ".date('d-m-Y',strtotime($transaction_details["cheque_date"]))."</n><br />";
							} 
							$txt .= "<n>------------------------------------------</n><br />";
							$txt .= "<n>TC Name        :   ".$emp_dtls["emp_name"]." </n><br />";
							$txt .= "<n>Mobile No.     :   ".$emp_dtls["personal_phone_no"]."</n><br />";
							$txt .= "<n>Please keep this Bill For Future Reference</n><br />";
							$txt .= "<n>Toll Free No. 18008904665</n><br />";
							$txt .= "<n></n><br />";
							$txt .= "<n></n><br />";
							$txt .= "<n></n><br />";
							$txt .= "<n></n><br />";
							$txt .= "<n></n><br />";


           
            
							for($i=1;$i<=1;$i++){
								$txt1=NULL;
								$txt1=$txt;
								if($i==1){
									$copyCT= '<bc>'.$ulb_name.'</bc><br />'.'<nc>Citizen Copy</nc>';
									$copyTC= '<bc>'.$ulb_name.'</bc><br />'.'<nc>TC Copy</nc>';
									$copyPT= '<bc>'.$ulb_name.'</bc><br />'.'<nc>ULB Copy</nc>';
									
								
								}
								$txt1=$copyCT. '<br />'.$txt1. '<br />'.$copyTC. '<br />'.$txt1. '<br />'.$copyPT. '<br />'.$txt1;
								//$txt1.= PHP_EOL. PHP_EOL. PHP_EOL. PHP_EOL;
					
							}
					//print_var($txt1);
		   
		   			?>     

			<input type="hidden" id="bt_printer" value="<?=$txt1;?>" />
			   
				</div>
			</div>
		</div>
		</div>
	</div>

	</div>
</div>
<script type="text/javascript">
function bt_printer(){
	var url = document.getElementById("bt_printer").value;
	AndroidInterface.btPrinter(url);
}
bt_printer();
</script>
<?=$this->include("layout_mobi/footer");?>