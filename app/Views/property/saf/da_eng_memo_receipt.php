<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Self Assessment Memo </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
<style>
* {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
@media print {
	#content-container {padding-top: 0px;}
	.row {
        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["id"];?>.png) !important;
		background-repeat:no-repeat !important;
		background-position:center !important;
        -webkit-print-color-adjust: exact;
    }
	.no-print {
        display: none;
    }
}
.row{
	background-color:#FFFFFF;
	background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["id"];?>.png) !important;
	background-repeat:no-repeat;
	background-position:center;

}
</style>
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

</script>
</head>
<body>
<!--CONTENT CONTAINER-->
<div id="content-container text-center">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark col-md-8 col-md-offset-2">


			<div class="panel-body" id="print_watermark" style="width: 250mm; height: auto; margin: auto;  background: #FFFFFF; ">
			<div class="panel-control no-print">
                <a href="<?=base_url('citizenPaymentReceipt/da_eng_memo_receipt/');?><?="/".md5($ulb["id"])."/".md5($memo["id"])."/HIN"?>" class="btn btn-default">Hindi Version</a>
				<div class="col-sm-1 noprint text-right">
					<!-- <button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;border:none;">
						<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7,10a1,1,0,1,0,1,1A1,1,0,0,0,7,10ZM19,6H18V3a1,1,0,0,0-1-1H7A1,1,0,0,0,6,3V6H5A3,3,0,0,0,2,9v6a3,3,0,0,0,3,3H6v3a1,1,0,0,0,1,1H17a1,1,0,0,0,1-1V18h1a3,3,0,0,0,3-3V9A3,3,0,0,0,19,6ZM8,4h8V6H8Zm8,16H8V16h8Zm4-5a1,1,0,0,1-1,1H18V15a1,1,0,0,0-1-1H7a1,1,0,0,0-1,1v1H5a1,1,0,0,1-1-1V9A1,1,0,0,1,5,8H19a1,1,0,0,1,1,1Z" fill="#6563ff"/></svg>
					</button> -->
				</div>
            </div>
			<style type="text/css" media="print">
				@media print
				{
					/* For Remove Header URL */
						@page {
						margin-top: 0;
						margin-bottom: 0;
						size: portrait;
						size: A4;
						}
						body  {
						padding-top:30px;
						padding-bottom: 5px ; background:#FFFFFF
						}
						/* Enable Background Graphics(ULB Logo) */
						*{
						-webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
						color-adjust: exact !important;                 /*Firefox*/
					}
				}
			</style>
				<div class="row" style="border: 3px; border-style: dotted; padding: 20px;">
					<?php
						$ulb_exp = explode(' ',trim($ulb['ulb_name']));
						$ulb_short_nm=$ulb_exp[0];
					?>
					<table width="100%">
						<tr>
							<td width="29%">
								<img style="height:60px;width:60px;float:right;" src='<?php echo base_url('public/assets/').session()->get("ulb_dtl")["logo_path"];?>'>
							</td>
							<td width="2%"></td>
							<td width="69%" style="font-size:24px; font-weight: bold;">
								<?=$ulb['ulb_name'].', '.$ulb_short_nm;?>
							</td>
						</tr>
					</table>
					<center>
						<table>
							<tr >
								<td><b style="border-bottom:1px solid black;">Notice of property tax customized under section 152(3) of Jharkhand Municipal Act-2011</b></td>
							</tr>
						</table>
					</center>
					<table style="width:100%; border:1">
						<tr>
							<td><br></td>
						</tr>
						<tr>
							<td align="right">Memo No. : <span style="font-weight:bold;" class="bol"><?=$memo['memo_no'];?></span></td>
						</tr>
						<tr>
							<td align="right">Date : <span style="font-weight:bold;" class="bol"><?=date('d-m-Y', strtotime($memo['created_on']));?></span></td>
						</tr>
						<?php
						if($prop_tax['qtr']=='1')
						{
							$qtr_nm='First';
						}
						else if($prop_tax['qtr']=='2')
						{
							$qtr_nm='Second';
						}
						else if($prop_tax['qtr']=='3')
						{
							$qtr_nm='Third';
						}
						else if($prop_tax['qtr']=='4')
						{
							$qtr_nm='Fourth';
						}
						?>
						<tr>
							<td align="right">Effective: <span style="font-weight:bold;" class="bol"><?=$qtr_nm.' Quarter '.$fy['fy'];?></span></td>
						</tr>
					</table>
					<table style="width:100%;">
						<tr>
							<td>Mr/Mrs/Ms: <span style="font-weight:bold;" class="bol">
							<?php
								//print_var($owner_list);
								if(isset($owner_list)):
									if(empty($owner_list)):
							?>
								<span class="text-danger">N/A</span>
							<?php else:
								$ownerArray = [];
								foreach($owner_list as $value) {
									$ownerArray[] = strtoupper($value['owner_name']).' '.strtoupper($value['relation_type']).' '.strtoupper($value['guardian_name']);
								}
								echo implode(', ', $ownerArray);
						endif;
							endif;  ?>
								</span></td>
						</tr>
						<tr>
							<td>Address: <span style="font-weight:bold;" class="bol"> <?=$saf['prop_address'];?>
								</span></td>
						</tr>
					</table>
					<?php
						$mutationTitle = "";
						if ($saf['assessment_type']=="Mutation") {
							$mutationTitle = "Old Holding Number - <b>".$saf['old_holding_no']."</b>, ";
						}

					?>
					<table style="width:100%;">
						<tr>
							<td>&nbsp;&nbsp;You are hereby informed that <?=$mutationTitle;?>your New Holding Number - <b> <?=$memo['holding_no'];?> </b> in Ward No - <b> <?=$saf['ward_no'];?> </b>, </b> New Ward No - <b> <?=$saf['new_ward_no'];?> </b> has been done,  on the basis of your self-assessment declaration form, the annual rental price has been fixed at Rs <b> <?=round($prop_tax['arv'], 2);?>/- </b> based on your self assessment declaration.</td>
						</tr>
						<tr>
							<td>Accordingly the tax per quarter will be as follows.</td>
						</tr>
					</table>
					<table style="width:100%;" border="1px" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="3" style="width:50%;padding-bottom:5px;font-size:12px;"><b><center>Self assessment tax notice</center></b> </td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;"><span style="font-weight:bold;" class="bol">SL. No.</span></td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;"><span style="font-weight:bold;" class="bol">Particulars</span></td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><span style="font-weight:bold;" class="bol">Amount (in Rs.)</span></td>
						</tr>

						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">1.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">House Tax</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['holding_tax'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">2.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">Water Tax</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['water_tax'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">3.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">Latrine Tax</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['latrine_tax'];?></td>
						</tr>

						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">4.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">RWH Penalty</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['additional_tax'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">5.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">Education Cess</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['education_cess'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">6.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">Health Cess</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['health_cess'];?></td>
						</tr>

						<tr>
							<td colspan="2" style="width:70%;padding-bottom:5px;font-size:12px;padding-left:7px;"><b>Total Amount (per quarter)</b> </td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=($prop_tax['holding_tax'] + $prop_tax['water_tax'] + $prop_tax['latrine_tax'] + $prop_tax['additional_tax'] + $prop_tax['education_cess'] + $prop_tax['health_cess']);?></td>
						</tr>
					</table>

					<table style="width:100%;">
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td align="right">
							<img style="margin-left:0px;width:100px;height:100px;float:left;" src="<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>" title="<?=$path;?>">
							<div style="width:180px;height: 30px;border:2px solid #000;"></div>
							<span style="font-weight:bold;">To be signed by the Applicant</span>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td><span style="font-weight:bold;">Note:-</span></td>
						</tr>
						<tr>
							<td>
								<ol>
									<li>The tax assessment list is displayed on the website of <?=$ulb['ulb_name'];?> : <?=receipt_url();?> </li>
									<li>In the light of manual 11.4, additional house tax will be levied which will be 50% of the property tax due to lack of arrangement of rainwater harvesting.<br/>It is advised to inform the corporation by installing rainwater conservation structure and get relief from additional house tax.</li>
									<li>Property tax will be paid quartely in each financial year.</li>
									<li>If the entire hourly tax for a year is paid before 30 June of the financial year, a rebate of 5% will be given to the taxpayer.</li>
									<li>Simple Interest will be payable at the rate of 1% per month if any payable are not not paid within or before the specified time period (every quarter).</li>
									<li>This tax assessment is being  done on the basis of your self-determination and declaration made, this self-assessment-cum-declaration can be conducted by the local corporation in due course of time and if the facts are found to be incorrect, the penalty prescribed in accordance with manual Condica 13.2 (Fine) and difference amount will be payable.</li>
									<li>The property is collected by <?=$ulb['ulb_name'];?> does not confer any legal status on these buildings and / or its owners / occupiers Confers any legal right to.</li>
									<li>If the last digit of your new holding number is 5/6/7/8, then it will be considered under the category of specific structures.</li>
								</ol>
							</td>
						</tr>
						<tr>
							<td><span style="font-weight:bold;font-size:14px;">NOTE: This is a computer generated receipt. This receipt does not require physical signature.</span></td>
						</tr>
					</table>
					<center>
						<div style="margin-top: 40px;">
							<div class="col-md-12">
								<img src="<?=base_url();?>/public/assets/img/swachh-bharat.PNG" style="height:30px; width:100px;" alt="rmc">
							</div>
						</div>
					</center>
				</div>

			</div>

        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<div class="col-sm-1 noprint" align="center" style="margin-top: 40px;">
	<button class="btn btn-mint btn-icon" onclick="printDiv('print_watermark')"><i class="demo-pli-printer icon-lg"></i> PRINT</button>
</div>
</body>
<?php //echo $this->include('layout_home/footer');?>
</html>
