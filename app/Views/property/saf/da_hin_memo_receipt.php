<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Self Assessment Memo </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
<?php $session = session(); $ulb_name_hindi = $session->get("ulb_dtl")["ulb_name_hindi"];//$_SESSION);?>
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
                <a href="<?=base_url('citizenPaymentReceipt/da_eng_memo_receipt/');?><?="/".md5($ulb["id"])."/".md5($memo["id"])."/ENG"?>" class="btn btn-default">English Version</a>
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
							<td width="35%">
								<img style="height:60px;width:60px;float:right;" src='<?php echo base_url('public/assets/').session()->get("ulb_dtl")["logo_path"];?>'>
							</td>
							<td width="2%"></td>
							<td width="63%" style="font-size:28px; font-weight: bold;">
								<?=$ulb_name_hindi;?>
							</td>
						</tr>
					</table>
					<center>
						<table>
							<tr >
								<td><b style="border-bottom:1px solid black;">झारखण्ड नगरपालिका अधिनियम -2011  की धरा 152 (3) के अंतर्गत स्वनिर्धारित किये गए संपत्ति कर की सूचना |</b></td>
							</tr>
						</table>
					</center>
					<table style="width:100%; border:1">
						<tr>
							<td><br></td>
						</tr>
						<tr>
							<td align="right">मेमो सं० : <span style="font-weight:bold;" class="bol"><?=$memo['memo_no'];?></span></td>
						</tr>
						<tr>
							<td align="right">दिनांक : <span style="font-weight:bold;" class="bol"><?=date('d-m-Y', strtotime($memo['created_on']));?></span></td>
						</tr>
						<?php
						if($prop_tax['qtr']=='1')
						{
							$qtr_nm='प्रथम';
						}
						else if($prop_tax['qtr']=='2')
						{
							$qtr_nm='दूसरा';
						}
						else if($prop_tax['qtr']=='3')
						{
							$qtr_nm='तीसरा';
						}
						else if($prop_tax['qtr']=='4')
						{
							$qtr_nm='चौथी';
						}
						?>
						<tr>
							<td align="right">प्रभावी: <span style="font-weight:bold;" class="bol"><?=$qtr_nm.' त्रिमास '.$fy['fy'];?></span></td>
						</tr>			
					</table>
					<table style="width:100%;">
						<tr>
							<td>श्री /श्रीमती /सुश्री <span style="font-weight:bold;" class="bol">
							<?php
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
							<td>पता : <span style="font-weight:bold;" class="bol"> <?=$saf['prop_address'];?> 
								</span></td>
						</tr>                                 
					</table>
                    <?php
						$mutationTitle = "";
						if ($saf['assessment_type']=="Mutation") {
							$mutationTitle = "पुराना गृह सं० - <b>".$saf['old_holding_no']."</b>, ";
						}

					?>
					<table style="width:100%;">
						<tr>
							<td>&nbsp;&nbsp;एतद् द्वारा आपको सूचित किया जाता है कि आपके <?=$mutationTitle;?> नया गृह सं० - <b> <?=$memo['holding_no'];?> </b> पुराना वार्ड  सं० - <b> <?=$saf['ward_no'];?> </b>, </b> नया वार्ड सं० - <b> <?=$saf['new_ward_no'];?> </b> हुआ है , आपके स्व० निर्धारण घोषणा पत्र के आधार पर वार्षिक किराया मूल्य <b> <?=round($prop_tax['arv'], 2);?> /- रु० </b> निर्धारित किया गया है |</td>
						</tr>
						<tr>
							<td>इसके अनुसार प्रति तिमाही कर निम्न  प्रकार होगा |</td>
						</tr>
					</table>
					<table style="width:100%;" border="1px" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="3" style="width:50%;padding-bottom:5px;font-size:12px;"><b><center>स्व-निर्धारित कर की सूचना</center></b> </td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;"><span style="font-weight:bold;" class="bol">क्रम स० </span></td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;"><span style="font-weight:bold;" class="bol">ब्यौरे</span></td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><span style="font-weight:bold;" class="bol">राशि  (in Rs.)</span></td>
						</tr>

						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">1.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">गृह कर</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['holding_tax'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">2.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">जल कर</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['water_tax'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">3.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">शौचालय कर</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['latrine_tax'];?></td>
						</tr>
						
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">4.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">वर्षा जल संचयन जुर्माना</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['additional_tax'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">5.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">शिक्षा उपकर</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['education_cess'];?></td>
						</tr>
						<tr>
							<td class="bg" style="width:20%;padding-bottom:5px;font-size:12px;padding-left:7px;">6.</td>
							<td class="bg" style="width:50%;padding-bottom:5px;font-size:12px;padding-left:7px;">स्वास्थ्य उपकर</td>
							<td class="bg" style="width:30%;padding-bottom:5px;font-size:12px;padding-left:7px;"><?=$prop_tax['health_cess'];?></td>
						</tr>

						<tr>
							<td colspan="2" style="width:70%;padding-bottom:5px;font-size:12px;padding-left:7px;"><b>कुल राशि (प्रति तिमाही  )</b> </td>
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
							<span style="font-weight:bold;">आवेदक द्वारा हस्ताक्षर किए जाने के लिए</span>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
                            <td><span style="font-weight:bold;">नोट:-</span></td>
						</tr>
						<tr>
							<td>
								<ol>
                                    <li>कर निर्धारण की सूची, <?=$ulb_name_hindi;?> के वेबसाइट <span style="color:red;"> udhd.jharkhand.gov.in OR Call us at 1800 8904115 or 0651-3500700 </span> पर प्रदर्शित है |</li>
                                    <li>नियमावली कंडिका 11.4 के अलोक में वर्षा जल संरक्षण कि व्यवस्था नहीं होने के कारण अतिरिक्त गृह कर लगाया जो संपत्ति कर का 50% होगा |<br/>हिदायत दी जाती है कि , वर्षा जल संरक्षण संरचना लगा कर निगम को सूचित करे तथा अतिरिक्त गृह कर से राहत पाये| </li>
                                    <li>प्रत्येक वित्तीय वर्ष में संपत्ति कर का भुगतान त्रैमासिक देय होगा |</li>
                                    <li>यदि किसी वर्ष के लिए संपूर्ण घृति कर का भुगतान वित्तीय वर्ष के 30 जून के पूर्व कर दिया जाता है , तो करदाता को 5% कि रियायत दी जाएगी |</li>
                                    <li>किसी देय घृति को निद्रिष्टि समयावधि (प्रत्येक  तिमाही ) के अंदर या उसके पूर्व नहीं चुकाया जाता है , तो  1% प्रतिमाह कि दर से साधारण ब्याज देय होगा |</li>
                                    <li>यह कर निर्धारण आपके स्व -निर्धारण एवं की गयी घोषणा के आधार पर कि जा रही है,इस स्व -निर्धारण -सह-घोषणा पत्र कि स्थानीय जांच तथा समय निगम करा सकती है एवं तथ्य गलत पाये जाने पर नियमावली कंडिका  13.2 के अनुसार निर्धारित शास्ति (Fine) एवं अंतर राशि देय होगा |</li>
                                    <li><?=$ulb_name_hindi;?>  द्वारा संग्रहित इस संपत्ति कर इन इमारतों / ढांचों को कोई कानूनी हैसियत प्रदान नहीं करता है  और / या न ही अपने मालिकों / दखलकार को कानूनी अधिकार प्रदान करता है |</li>
                                    <li>अगर आपने नए होल्डिंग न०  का आखरी अंक 5/6/7/8 है तो यह विशिष्ट संरचनाओं कि श्रेणी के अंतर्गत माना जायेगा |</li>
								</ol>
							</td>
						</tr>
						<tr>
							<td><span style="font-weight:bold;font-size:14px;">नोट :- यह एक कंप्यूटर जनित रसीद है। इस रसीद के लिए भौतिक हस्ताक्षर की आवश्यकता नहीं है।</span></td>
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
