<?=$this->include("layout_mobi/header");?>
<SCRIPT type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</SCRIPT>

	<div id="content-container">
		<div id="page-content">
    <div class="panel" style="border: #39a9b0 solid 2px;" onload="noBack();" 
	onpageshow="if (event.persisted) noBack();" onunload="">
        <div class="panel-heading" style="background: #39a9b0; border-radius: 0px; color: #FFFFFF">
        	<strong>Payment Receipt </strong>
        	<a href="<?= base_url('/Mobi/mobileMenu/trade'); ?>" class="btn btn-primary pull-right">Back</a>
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
                   TRADE PAYMENT RECEIPT
                </center><br />
                <center>------------------------------------------------</center><br />
                Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=$transaction_details["transaction_date"];?><br />
				TC Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $emp_details["emp_name"]; ?><br />
                Mobile No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?php echo $emp_details["personal_phone_no"]; ?><br />
				Trade Application No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$applicant_details["application_no"]; ?> <br />
				Ward No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$ward_no;?> <br />
				Firm Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$applicant_details["firm_name"];?> <br />
                
                
                <!--Dues From &nbsp;&nbsp;   :&nbsp;&nbsp;FY :  <br />
                Dues Upto &nbsp;&nbsp;   :&nbsp;&nbsp;FY :  <br />-->
                <!--Total Amount &nbsp;&nbsp;   :&nbsp;&nbsp;<?php //echo $total_demand_amt;?> <br />-->
                        
                <center>------------------------------------------------</center><br />
				Transaction No. &nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["transaction_no"];?><br />
                Amount Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=$transaction_details["paid_amount"];?><br />
 			    Payment Mode &nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$transaction_details["payment_mode"];?><br />
				<?php if($transaction_details["payment_mode"]=="CHEQUE" || $transaction_details["payment_mode"]=="DD"){ ?>
					Cheque No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$cheque_details["cheque_no"];?><br />
					Bank Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$cheque_details["bank_name"];?><br />
					Branch Name &nbsp;&nbsp; &nbsp;&nbsp;  :&nbsp;&nbsp;<?=$cheque_details["branch_name"];?><br />
					Cheque Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$cheque_details["cheque_date"];?><br />
				<?php } ?>
                <center>------------------------------------------------</center><br />                
                
                   <?php
						$txt = "";
							//$txt .= "<bc>".$ulb_mstr_name['ulb_name']."</bc><br />";
							//$txt .= "<nc>Citizen Copy</nc><br />";
							$txt .= "<nc>TRADE PAYMENT RECEIPT</nc><br />";
							$txt .= "<n>-----------------------------------------</n><br />";
							$txt .= "<n>Date           			 :  ".$transaction_details["transaction_date"]."</n><br />";
							$txt .= "<n>Ward No.       			 :  ".$ward_no."</n><br />";
							$txt .= "<n>Transaction No.			 :  ".$transaction_details["transaction_no"]."</n><br />";
							$txt .= "<n>Trade Application No.    :  ".$applicant_details["application_no"]."</n><br />";
							$txt .= "<n>Firm Name    			 :  ".$applicant_details["firm_name"]."</n><br />";
							$txt .= "<n>-----------------------------------------</n><br />";
							$txt .= "<n>Amount Paid   			 :  ".$transaction_details["paid_amount"]."</n><br />";
							$txt .= "<n>Payment Mode  			 :  ".$transaction_details["payment_mode"]."</n><br />";
							if($transaction_details["payment_mode"]=="CHEQUE" || $transaction_details["payment_mode"]=="DD"){
								$txt .= "<n>DD/Cheque No.        :  ".$cheque_details["cheque_no"]."</n><br />";
								$txt .= "<n>Bank Name		 	 :  ".$cheque_details["bank_name"]."</n><br />";
								$txt .= "<n>Branch Name    	 	 :  ".$cheque_details["branch_name"]."</n><br />";
								$txt .= "<n>Cheque Date  	 	 :  ".$cheque_details["cheque_date"]."</n><br />";
							} 
							$txt .= "<n>------------------------------------------</n><br />";
							$txt .= "<n>TC Name        			 :   ".$emp_details["emp_name"]." </n><br />";
							$txt .= "<n>Mobile No.     			 :   ".$emp_details["personal_phone_no"]."</n><br />";
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