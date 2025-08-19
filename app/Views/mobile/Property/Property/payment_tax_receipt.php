<?=$this->include("layout_mobi/header");?>

<div id="content-container">
	<div id="page-content">
		<div class="panel panel-bordered panel-mint">
			<div class="panel-heading">
				<div class="panel-control">
					<a href="<?=base_url()."/Mobi/full/".md5($tran_mode_dtl["prop_dtl_id"])?>" class="btn btn-dark btn_wait_load">Back</a>
				</div>
				<h3 class="panel-title">HOLDING PAYMENT RECEIPT</h3>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<div class="panel">

						<div id="blutooth_printable_area">
							<?php $ulb_name = $ulb_mstr_name["ulb_name"]; ?>
								<center><b><?=$ulb_name;?></b></center><br />
								<center>Payment Receipt</center><br />
								<center>------------------------------------------------</center><br />
								Date &nbsp;&nbsp;   :&nbsp;&nbsp; <?=$tran_mode_dtl["tran_date"];?><br />
								POS ID &nbsp;&nbsp;   :&nbsp;&nbsp; XXXXXXXXXXXXXXXXXXX<br />
								Transaction No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$tran_mode_dtl["tran_no"];?><br />
								Holding No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$citizenName["holding_no"] ?? null; ?> <br />
								New Holding No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$citizenName["new_holding_no"] ?? null; ?> <br />
								Ward No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$citizenName["ward_no"] ?? null;?> <br />
								Citizen Name &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$citizenName["owner_name"];?> <br />
								Address &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$citizenName["prop_address"];?> <br />

								
								
								
							
									
								<center>------------------------------------------------</center><br />
								
								Paid Upto &nbsp;&nbsp;  :&nbsp;&nbsp; <?=$tran_mode_dtl['from_qtr'];?>/<?=$tran_mode_dtl['from_fyear'];?> - <?=$tran_mode_dtl['upto_qtr'];?>/<?=$tran_mode_dtl['upto_fyear'];?><br />
								Demand Amount &nbsp;&nbsp;   :&nbsp;&nbsp; <?=$tran_mode_dtl["demand_amt"];?><br />
								Penalty Amount &nbsp;&nbsp;   :&nbsp;&nbsp; <?=$tran_mode_dtl["penalty_amt"];?><br />
								Rebate Amount &nbsp;&nbsp;   :&nbsp;&nbsp; <?=$tran_mode_dtl["discount_amt"];?><br />
								Amount Paid &nbsp;&nbsp;   :&nbsp;&nbsp; <?=$tran_mode_dtl["payable_amt"];?><br />
								Payment Mode &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$tran_mode_dtl["tran_mode"];?><br />
								<?php if(in_array($tran_mode_dtl['tran_mode'], ["CHEQUE", "DD"])){ ?>
									Cheque No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$chqDD_details["cheque_no"];?><br />
									Bank Name &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$chqDD_details["bank_name"];?><br />
									Branch Name &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$chqDD_details["branch_name"];?><br />
									Cheque Date &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$chqDD_details["cheque_date"];?><br />
								<?php } ?>
								<center>------------------------------------------------</center><br />  

								TC Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $emp_dtls["emp_name"]; ?><br />
								Mobile No. &nbsp;&nbsp;   :&nbsp;&nbsp; <?php echo $emp_dtls["personal_phone_no"]; ?><br />
								For Details Please visit : udhd.jhakhand.gov.in OR Call us at 1800 8904115 or 0651-3500700<br>
								Please keep this Bill For Future Reference

								<?php
									$txt = "";
									//$txt .= "<bc>".$ulb_mstr_name['ulb_name']."</bc><br />";
									//$txt .= "<nc>Citizen Copy</nc><br />";
									$txt .= "<nc>Payment Receipt</nc><br />";
									$txt .= "<n>-----------------------------------------</n><br />";
									$txt .= "<n>Date           :  ".$tran_mode_dtl["tran_date"]."</n><br />";
									$txt .= "<n>POS ID         :  XXXXXXXXXXX</n><br />";
									$txt .= "<n>Transaction No.:  ".$tran_mode_dtl["tran_no"]."</n><br />";
									$txt .= "<n>Holding No.    :  ".($citizenName["holding_no"] ?? null)."</n><br />";
									$txt .= "<n>New Holding No.    :  ".($citizenName["new_holding_no"] ?? null)."</n><br />";
									$txt .= "<n>Ward No.       :  ".($citizenName["ward_no"] ?? null)."</n><br />";
									$txt .= "<n>Citizen Name   :  ".$citizenName["owner_name"]."</n><br />";
									$txt .= "<n>Address        :  ".$citizenName["prop_address"]."</n><br />";
									$txt .= "<n>-----------------------------------------</n><br />";
									
									$txt .= "<n>Paid  Upto     :  ".$tran_mode_dtl['from_qtr']."/".$tran_mode_dtl['from_fyear']." - ".$tran_mode_dtl['upto_qtr']."/".$tran_mode_dtl['upto_fyear']."</n><br />";
									$txt .= "<n>Demand Amount  :  ".$tran_mode_dtl["demand_amt"]."</n><br />";
									$txt .= "<n>Penalty Amount :  ".$tran_mode_dtl["penalty_amt"]."</n><br />";
									$txt .= "<n>Rebate Amount  :  ".$tran_mode_dtl["discount_amt"]."</n><br />";
									$txt .= "<n>Amount Paid    :  ".$tran_mode_dtl["payable_amt"]."</n><br />";
									$txt .= "<n>Payment Mode   :  ".$tran_mode_dtl["tran_mode"]."</n><br />";
									if(in_array($tran_mode_dtl['tran_mode'], ["CHEQUE", "DD"])) {
										$txt .= "<n>Cheque No.       :  ".$chqDD_details["cheque_no"]."</n><br />";
										$txt .= "<n>Bank Name		 :  ".$chqDD_details["bank_name"]."</n><br />";
										$txt .= "<n>Branch Name    	 :  ".$chqDD_details["branch_name"]."</n><br />";
										$txt .= "<n>Cheque Date  	 :  ".$chqDD_details["cheque_date"]."</n><br />";
									} 
									$txt .= "<n>------------------------------------------</n><br />";
									$txt .= "<n>TC Name        :   ".$emp_dtls["emp_name"]." </n><br />";
									$txt .= "<n>Mobile No.     :   ".$emp_dtls["personal_phone_no"]."</n><br />";
									$txt .= "<n>For Details Please visit : udhd.jharkhand.gov.in OR Call us at 1800 8904115 or 0651-3500700</n><br />";
									$txt .= "<n>Please keep this Bill For Future Reference</n><br />";
									$txt .= "<n></n><br />";
									$txt .= "<n></n><br />";
									$txt .= "<n></n><br />";
									$txt .= "<n></n><br />";
									$txt .= "<n></n><br />";

									for ($i=1;$i<=1;$i++) {
										$txt1=NULL;
										$txt1=$txt;
										if($i==1){
											$copyCT= '<bc>'.$ulb_name.'</bc><br />'.'<nc>Citizen Copy</nc>';
											$copyTC= '<bc>'.$ulb_name.'</bc><br />'.'<nc>TC Copy</nc>';
											$copyPT= '<bc>'.$ulb_name.'</bc><br />'.'<nc>Partner Copy</nc>';
										}
										$txt1=$copyCT. '<br />'.$txt1. '<br />'.$copyTC. '<br />'.$txt1. '<br />'.$copyPT. '<br />'.$txt1;								
									}
				
								?>     

									<input type="hidden" id="bt_printer" value="<?=$txt1;?>" />
					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?=$this->include("layout_mobi/footer");?>
<script type="text/javascript">
function bt_printer(){
	var url = document.getElementById("bt_printer").value;
	AndroidInterface.btPrinter(url);
}
bt_printer();
</script>