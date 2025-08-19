<?=$this->include("layout_mobi/header");?>
<SCRIPT type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</SCRIPT>

<br>
<br>
    <div class="panel" style="border: #39a9b0 solid 2px;" onload="noBack();" 
	onpageshow="if (event.persisted) noBack();" onunload="">
        <div class="panel-heading" style="background: #39a9b0; border-radius: 0px; color: #FFFFFF"><strong>Payment Receipt </strong></div>
        <div><strong style="float:right"><i id="refresh" class="fa fa-refresh" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp; </strong></div>

        <div class="row-fluid">
			<div class="panel-control">
				<a class = "pull-right btn btn-info btn_wait_load" href="<?=base_url()?>/mobitradeapplylicence/trade_licence_view/<?=md5($apply_id['id'])?>"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</a>
			</div>
        <div class="span4">
            <div class="panel">
                <div id="blutooth_printable_area">
                       <?php 
 						$ulb_name = $ulb_mstr_name["ulb_name"]??null;
						?>
						 <center><b><?=$ulb_name;?></b></center><br />
						
						<center>
						<b>Provisional Municipal Trade License</b>
						</center><br />
						<center>
						(This Certificate relates to Section 155 (i) and 455 (i) Under Jharkhand Municipal Act of 2011)
						</center>
						<center>------------------------------------------------</center><br />
						&nbsp;&nbsp;Application No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?=$basic_details['application_no'];?><br />
						&nbsp;&nbsp;Provisional License No : &nbsp;&nbsp;&nbsp;<?=$basic_details['provisional_license_no'];?><br />
						&nbsp;&nbsp;Apply Date  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?=date('d-m-Y', strtotime($basic_details['apply_date']));?><br />
						&nbsp;&nbsp;Mr/Mrs. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;&nbsp;<?=$basic_details['owner_name'];?><br>
						&nbsp;&nbsp;in the &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;<?=$ulb['ulb_name'];?>  Municipal Area <br />
						&nbsp;&nbsp;Firm / organization <br />
						&nbsp;&nbsp;name  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  :&nbsp;&nbsp;<?=$basic_details['firm_name'];?> <br />
						&nbsp;&nbsp;Ward No  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$basic_details['ward_no'];?> <br />
						&nbsp;&nbsp;Business Address   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;  <?=strtoupper($basic_details['address']);?> <br />
						&nbsp;&nbsp;For defined Fee    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$tranProvDtl['paid_amount'];?> <br />
						&nbsp;&nbsp;Having receipt no    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=$tranProvDtl['transaction_no'];?> <br />
						&nbsp;&nbsp;Establishment Date  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=date('d-m-Y', strtotime($basic_details['establishment_date']));?> <br />
						&nbsp;&nbsp;Valid Upto  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp;<?=date('d-m-Y', strtotime($valid_upto));?> <br />
						&nbsp;&nbsp;Subject to the following terms, license is granted.<br/><br/>
 
               
                        
                <center>---------------------------------------------------------------</center><br />
				&nbsp;&nbsp;1.Business will run according to licence issued.<br />
                &nbsp;&nbsp;2.Prior Permission from local body is necessary if business is changed<br />
				&nbsp;&nbsp;3.Information to local body is necessary for extension of area <br />
				&nbsp;&nbsp;4.Prior information to local body regarding winding of business is necessary.<br />
                &nbsp;&nbsp;5.Application for renewal of license is necessary one month before expiry of license.<br />
				&nbsp;&nbsp;6.In the case of delay penalty will be levied according to section 459 of Jharkhand Municipal Act 2011. <br />
				&nbsp;&nbsp;7.Illegal Parking in front of firm in non-permissible. <br />
 			    &nbsp;&nbsp; 8.Sufficient number of containers for disposing-garbage and refuse shall be made available within. <br />
 			    &nbsp;&nbsp;9.The premises and the licensee will co-operate with the ULB for disposal of such waste. <br />
 			    &nbsp;&nbsp;10.SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit. <br />
 			    &nbsp;&nbsp;11.This provisional license is valid for 20 days from the date of apply . In case of no-objection from Ranchi Municipal Corporation ,The license shall be deemed approved. <br />
 			    &nbsp;&nbsp;12.The final license can be downloaded from www.ranchimunicipal.net. <br /><br />

                <center>------------------------------------------------</center><br />                

				&nbsp;&nbsp;For More Details Please Visit : www.ranchimunicipal.net <br />
 			    &nbsp;&nbsp; Or Call us at 18008904115 or 0651-3500700 <br /><br />
 			    &nbsp;&nbsp;Note: This is a computer generated Licence. This Licence does not require a physical signature. <br />

				
                
            
                
                   <?php
						$txt = "";
							$txt .= "<bc>".$ulb_name."</bc><br />";
							//$txt .= "<nc>Citizen Copy</nc><br />";
							$txt .= "<nc>Provisional Municipal Trade License</nc><br />";
							$txt .= "<nc>(This Certificate relates to Section 155 (i) and 455 (i) Under Jharkhand Municipal Act of 2011)</nc>";
							$txt .= "<n>-----------------------------------------</n><br />";
							$txt .= "<n>Application No           :  ".$basic_details['application_no']."</n><br />";
							$txt .= "<n>Provisional License No   :  ".$basic_details['provisional_license_no']."</n><br />";
							$txt .= "<n>Apply Date  			 :  ".date('d-m-Y', strtotime($basic_details['apply_date']))."</n><br />";
							$txt .= "<n>Mr/Mrs.                  :  ".$basic_details['owner_name']."</n><br />";
							$txt .= "<n>in the       			 :  ".$ulb['ulb_name']."</n><br />";
							$txt .= "<n>Firm / organization name :  ".$basic_details['firm_name']."</n><br />";
							$txt .= "<n>Ward No       			 :  ".$basic_details['ward_no']."</n><br />";
							$txt .= "<n>Business Address       	 :  ".strtoupper($basic_details['address'])."</n><br />";
							$txt .= "<n>For defined Fee       	 :  ".$tranProvDtl['paid_amount']."</n><br />";
							$txt .= "<n>Having receipt no        :  ".$tranProvDtl['transaction_no']."</n><br />";
							$txt .= "<n>Establishment Date       :  ".date('d-m-Y', strtotime($basic_details['establishment_date']))."</n><br />";
							$txt .= "<n>Valid Upto               :  ".date('d-m-Y', strtotime($valid_upto))."</n><br />";
							$txt .= "<n>Subject to the following terms, license is granted.</n><br />";
 
							$txt .= "<n>-----------------------------------------</n><br />";
							$txt .= "<n>1.Business will run according to licence issued</n><br />";
							$txt .= "<n>2.Prior Permission from local body is necessary if business is changed</n><br />";
							$txt .= "<n>3.Information to local body is necessary for extension of area </n><br />";
							$txt .= "<n>4.Prior information to local body regarding winding of business is necessary.</n><br />";
							$txt .= "<n>5.Application for renewal of license is necessary one month before expiry of license.</n><br />";
							$txt .= "<n>6.In the case of delay penalty will be levied according to section 459 of Jharkhand Municipal Act 2011.</n><br />";
							$txt .= "<n>7.Illegal Parking in front of firm in non-permissible. </n><br />";
							$txt .= "<n>8.Sufficient number of containers for disposing-garbage and refuse shall be made available within.</n><br />";
							$txt .= "<n>9.The premises and the licensee will co-operate with the ULB for disposal of such waste.</n><br />";
							$txt .= "<n>10.SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit.</n><br />";
							$txt .= "<n>11.This provisional license is valid for 20 days from the date of apply . In case of no-objection from Ranchi Municipal Corporation ,The license shall be deemed approved.</n><br />";
							$txt .= "<n>12.The final license can be downloaded from www.ranchimunicipal.net.</n><br />";

							$txt .= "<n>-----------------------------------------</n><br />";

							$txt .= "<n>For More Details Please Visit : www.ranchimunicipal.net</n><br />";
							$txt .= "<n>Or Call us at 18008904115 or 0651-3500700 </n><br />";
							$txt .= "<n>Note: This is a computer generated Licence. This Licence does not require a physical signature.</n><br />";

			for($i=1;$i<=1;$i++){
				$txt1=NULL;
				$txt1=$txt;
				if($i==1){
					$copyCT= '<bc>'.$ulb_name.'</bc><br />'.'<nc>Citizen Copy</nc>';
					$copyTC= '<bc>'.$ulb_name.'</bc><br />'.'<nc>TC Copy</nc>';
				}
				$txt1=$copyCT. '<br />'.$txt1. '<br />'.$copyTC. '<br />'.$txt1;
				//$txt1.= PHP_EOL. PHP_EOL. PHP_EOL. PHP_EOL;
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
<script>
	$('#refresh').click(function() {
    location.reload();
});
</script>