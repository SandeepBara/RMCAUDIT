<?=$this->include("layout_mobi/header");?>

<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>

<br>
<br>
    <div class="panel" style="border: #39a9b0 solid 2px;" onload="noBack();" 
			onpageshow="if (event.persisted) noBack();" onunload="">
		
        <div class="panel-heading" style="background: #39a9b0; border-radius: 0px; color: #FFFFFF">
			
			<strong>Payment Receipt </strong>
		</div>
		
        <div class="row-fluid">
			<div class="panel-control">
				<a class = "pull-right btn btn-info" href="<?=base_url()?>/mobitradeapplylicence/trade_licence_view/<?=md5($transaction_details['related_id'])?>"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</a>
			</div>
        <div class="span4">
            <div class="panel">
                <div id="blutooth_printable_area">
                       <?php 
							//$title='SOLID WASTE MANAGEMENT';
						$ulb_name = $ulb_mstr_name["ulb_name"];
						
						?>
						<center><b><?=$ulb_name;?></b></center><br />
						
                <center>
                   TRADE PAYMENT RECEIPT
                </center><br />
                <center>------------------------------------------------</center><br />
                Provisional Licence No &nbsp;&nbsp;&nbsp;: <?=$applicant_details["provisional_license_no"];?><br />
				Licence Validity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?=$applicant_details["valid_upto"];?><br />
				Application type:&nbsp;&nbsp;&nbsp; <?=$applicant_details["valid_upto"];?><br />

				Transaction No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["transaction_no"];?><br />
				Transaction Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=$transaction_details["transaction_date"];?><br />
				Application No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$applicant_details["application_no"]; ?> <br />

				Ward No.   :&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$ward_no;?> <br />
				Firm Name   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$applicant_details["firm_name"];?> <br />
				Licence No   :&nbsp;&nbsp;&nbsp; <?=$applicant_details["license_no"];?><br />
				Area (Sq. Ft.)  :&nbsp;&nbsp;&nbsp; <?=$applicant_details["area_in_sqft"];?><br />
				Mobile No   :&nbsp;&nbsp;&nbsp; <?=$applicant_details["mobile"];?><br />
				Address   :&nbsp;&nbsp;&nbsp; <?=$applicant_details["address"];?><br />

				<center>------------------------------------------------</center><br />
				Current Amount     &nbsp;&nbsp;   :&nbsp;&nbsp;<?=($transaction_details["paid_amount"]-$transaction_details["penalty"]);?><br />
				Fine Amount     &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["penalty"];?><br />
				Total Amount     &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["paid_amount"];?><br />
				Payment Mode     &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["payment_mode"];?><br />

				<?php if($transaction_details["payment_mode"]=="CHEQUE" || $transaction_details["payment_mode"]=="DD"){ ?>
					Cheque No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$cheque_details["cheque_no"];?><br />
					Bank Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$cheque_details["bank_name"];?><br />
					Branch Name &nbsp;&nbsp; &nbsp;&nbsp;  :&nbsp;&nbsp;<?=$cheque_details["branch_name"];?><br />
					Cheque Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$cheque_details["cheque_date"];?><br />
				<?php } ?>

				<center>------------------------------------------------</center><br />

				TC Name :&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $emp_details["emp_name"]??null; ?><br />
                Mobile No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?php echo $emp_details["personal_phone_no"]??null; ?><br />
				
                
            
                
                   <?php
							$txt = "";
							$txt .= "<nc>TRADE PAYMENT RECEIPT</nc><br />";
							$txt .= "<nc>-----------------------------------------</nc><br />";
							$txt .= "<n>Provisional Licence No :  ".$applicant_details["provisional_license_no"]."</n><br />";
							$txt .= "<n>Licence Validity       :  ".$applicant_details["valid_upto"]."</n><br />";
							$txt .= "<n>Application type       :  ".$applicant_details["application_type"]."</n><br />";
							$txt .= "<n>Transaction No.	       :  ".$transaction_details["transaction_no"]."</n><br />";
							$txt .= "<n>Transaction Date       :  ".$transaction_details["transaction_date"]."</n><br />";
							$txt .= "<n>Application No.        :  ".$applicant_details["application_no"]."</n><br />";
							$txt .= "<n>Ward No.               :  ".$ward_no."</n><br />";
							$txt .= "<n>Firm Name              :  ".$applicant_details["firm_name"]."</n><br />";
							$txt .= "<n>Licence No             :  ".$applicant_details["license_no"]."</n><br />";
							$txt .= "<n>Area (Sq. Ft.)         :  ".$applicant_details["area_in_sqft"]."</n><br />";
							$txt .= "<n>Mobile No              :  ".$applicant_details["mobile"]."</n><br />";
							$txt .= "<n>Address                :  ".$applicant_details["address"]."</n><br />";
							

							
							
							$txt .= "<nc>-----------------------------------------</nc><br />";
							$txt .= "<n>Current Amount   :  ".($transaction_details["paid_amount"]-$transaction_details["penalty"])."</n><br />";
							$txt .= "<n>Fine Amount      :  ".$transaction_details["penalty"]."</n><br />";
							$txt .= "<n>Total Amount   	 :  ".$transaction_details["paid_amount"]."</n><br />";
							$txt .= "<n>Payment Mode  	 :  ".$transaction_details["payment_mode"]."</n><br />";
							if($transaction_details["payment_mode"]=="CHEQUE" || $transaction_details["payment_mode"]=="DD"){
								$txt .= "<n>DD/Cheque No.        :  ".$cheque_details["cheque_no"]."</n><br />";
								$txt .= "<n>Bank Name		 	 :  ".$cheque_details["bank_name"]."</n><br />";
								$txt .= "<n>Branch Name    	 	 :  ".$cheque_details["branch_name"]."</n><br />";
								$txt .= "<n>Cheque Date  	 	 :  ".$cheque_details["cheque_date"]."</n><br />";
							} 
							$txt .= "<nc>------------------------------------------</nc><br />";
							$txt .= "<n>TC Name       :   ".$emp_details["emp_name"]." </n><br />";
							$txt .= "<n>Mobile No.    :   ".$emp_details["personal_phone_no"]."</n><br />";
							$txt .= "<n>For Details Please visit : udhd.jhakhand.gov.in OR Call us at 1800 8904115 or 0651-3500700</n><br />";
							$txt .= "<n>Please keep this Bill For Future Reference</n><br />";
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
				//echo $txt1.= PHP_EOL. PHP_EOL. PHP_EOL. PHP_EOL;
				//print_var($txt1);
				
			}
		   
		   ?>     

			<input type="hidden" id="bt_printer" value="<?=$txt1;?>" />
			   
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