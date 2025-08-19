<?php 
<?= $this->include('layout_vertical/header');


?>


<style type="text/css">
<!--
.style1 {font-weight: bold}

-->
</style>

<div class="span11" style="width:96%; margin-top:20px;">
<div class="panel" style=" border-radius:0px;">
            <div class="panel-heading" style="margin-bottom: 10px; font-weight: bold">Payment Receipt  </div>
    <div id="printableArea">
    <style>
    body{ background:#FFFFFF}
    .hide_border{border-left:hidden; border-right:hidden; border-bottom:hidden; border-top:1px #333333 solid}
    </style>
    
    <style type="text/css">
.print_text {width:auto; height:30px; margin-right:4px;  float:left; font-family: Monotype Corsiva; color: #16335F; font-size: 22px;}
.print_text_amt {width:430px; height:20px; border-bottom:#000000 dotted 2px; float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color: #16335F; }
.print_text_amt1 {width:463px; height:20px; border-bottom:#000000 dotted 2px; float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color: #16335F;}
.print_text_amt2 {width:540px; height:20px; border-bottom:#000000 dotted 2px; float:left; ffont-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color: #16335F; }

.print_text_amt3 {width:569px; height:20px; border-bottom:#000000 dotted 2px; float:left; ffont-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color: #16335F; }


.style1 {	font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold
}
.money_box{ border:#333333 solid 1px; width:140px; padding:5px; margin:auto; font-weight:bold; font-size:14px; text-align:center}

 </style>


<style>

html, body {
    height: 100%;
    min-height: 100%;
    margin: 0;
    padding: 0;
}
</style>
   
  
        <div style="width:200mm; height: auto; margin: auto; background:url(../ulb_logo/<?=$ulb_master_id?>.png) 140px 200px; background-repeat:no-repeat;" class="abcd">


<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; ">
 <!-- <tr>
    <td height="10" colspan="3" valign="bottom" align="center"><div style="font-family: Microsoft Sans Serif;
	font-size: 30px;
	font-weight: bold; text-align:center; line-height:40px;"><?php echo $ulb_name;?></div></td>
    </tr>-->
  <tr>
    <td height="58" colspan="3" align="center"> 
    
    <div style="width:40%; padding:8px; height:auto; border:#000000 solid 2px; font-family:Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold"> WATER USER CHARGE </div>
    </td>
    </tr>
	<tr><td colspan="3" align="center"> <div style="width:40%;height:auto; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:bold">Payment Receipt</div></td></tr>
  <tr>
    <td width="12%" height="20"> Receipt No. </td>
    <td width="54%">
<strong style="font-size:15px"> <?=$mr_no?></strong></td>
    <td width="34%"><span style="float:left">Date : </span>  
    <div style=" width:auto; height:14px; line-height:20px; float:left; margin-left:2px; padding-bottom:3px; font-size:12px; font-weight:bold"> <?=$mr_date?></div></td>
    </tr>
   
  <tr>
    <td height="42" colspan="3"><table width="100%" border="0">
      <tr>
        <td width="66%" rowspan="4" valign="top">
		Department / Section :
		<br />
		Account Description : Water Fees (User Charge), Fees, Other Charges &amp; Deposits</td>
        <td width="34%" height="28"><div style="float:left">Ward No : </div> 
		<div style=" width:180px; height:14px; line-height:22px; float:left; margin-left:2px;  padding-bottom:3px; font-size:12px; font-weight:bold;"><?=$ward_no?></div></td>
        </tr>
      <tr>
        <td height="28"><div style="float:left">Holding No : </div> 
		<div style=" width:163px; height:14px; float:left; margin-left:2px; padding-bottom:3px; font-weight:700;"><?=$holding_no?></div></td>
        </tr>
      <tr>
        <td height="20">
            <div style="float:left">Consumer No : </div> 
		<div style=" width:163px; height:14px; float:left; margin-left:2px; padding-bottom:3px; font-weight: 700;"><?=$consumer_no?></div>        </td>
        </tr>
		<?php if($old_consumer_no!=''){?>
		<tr>
        <td height="20">
            <div style="float:left">Consumer No : </div> 
		<div style=" width:163px; height:14px; float:left; margin-left:2px; padding-bottom:3px; font-weight: 700;"><?=$old_consumer_no?></div>        </td>
      </tr> <?php } ?> 
    </table></td>
    </tr>
  <tr>
    <td height="" colspan="3" valign="top">
    
    <table width="754" height="91">
    <tr>
    <td width="202" height="22">Received From Shri / Smt.</td>
    <td width="168"><span style="font-size:12px; font-weight:bold ;"><?=$consumer_name?> </span></td>
    <td width="92">C/O</td>
    <td width="272"><span style="font-size:12px; font-weight:bold ;"><?php if($guardian_name!==''){echo $guardian_name;}else echo 'NA';?></span></td>
    </tr>
    <tr>
    <td height="20">Address : &nbsp;</td>
    <td><span style="font-size:12px; font-weight:bold"><?=$address?></span></td>
    <td>Mobile No</td>
    <td><span style="font-size:12px; font-weight:bold ;"><?php  if($mobile_no!='' and $mobile_no!='0'){ echo $mobile_no;}else echo 'NA';?></span></td>
    </tr>
     <tr>
    <td colspan="4"><div style="float:left;">A Sum of Rs. </div>
    <div style="width:100px; height:15px; line-height:20px; float:left; margin-left:5px;  padding-bottom:3px; font-size:12px; font-weight:bold;"><?php echo number_format($amount_paid,2)?>&nbsp;</div>		
		<div style="float:left;">(in words) </div>
		<div style="border-bottom:#333333 dotted 2px; width:510px; height:14px; float:left; margin-left:2px;  padding-bottom:3px; font-size:12px; font-weight:bold; line-height:18px;">
            &nbsp;<span id="amount_words">
            <?php echo $db->Inwords(abs($amount_paid)).' only.';?></span>&nbsp;</div></td>
    
    </tr>
    <tr>
    <td colspan="4"><div style="float:left;"> towards <strong style="font-size:12px;">Water Fees (User Charges), Fees, Other Charges & Deposits</strong> vide <strong><?=$payment_mode?></strong> </div></td>
    </tr> 
     <?php  if($payment_mode=='SBI Collect')  {?>
   <tr>
    <td colspan="4"><div style="float:left;" > Bank Transaction No </div>
		<div style="border-bottom:#333333 dotted 2px; width:630px; height:14px; float:left; margin-left:5px;  padding-bottom:3px; font-weight: 700;">
       	&nbsp;&nbsp;&nbsp;<?=$transctn_id?>
        </div>
        </td>
        </tr>  
    
    <?php } ?>
    <?php  if($payment_mode=='CHEQUE' || $payment_mode=='DEMAND DRAFT'){ ?> 
    <tr>
    <td colspan="4"><div style="float:left;" > Cheque No </div>
		<div style="border-bottom:#333333 dotted 2px; width:320px; height:14px; float:left; margin-left:5px;  padding-bottom:3px; font-weight: 700;">
       	&nbsp;&nbsp;&nbsp;<?=$check_dd_no?>
            </div> <div style="float:left;">Dated </div>
		<div style="border-bottom:#333333 dotted 2px; width:320px; height:14px; float:left; margin-left:5px;  padding-bottom:3px; font-weight: 700;">	&nbsp;&nbsp;&nbsp;<?=$check_dd_date?></div></td>
    </tr>
     <tr>
    <td colspan="4"><div style="float:left;">Drawn on </div>
		<div style="border-bottom:#333333 dotted 2px; width:695px; height:14px; float:left; margin-left:2px;  padding-bottom:3px;">		&nbsp;&nbsp;&nbsp;<?=$bank_name?></div></td>
     
    </tr>
     <tr>
    <td colspan="4"><div style="border-bottom:#333333 dotted 2px; width:625px; height:14px; float:left; margin-left:2px;  padding-bottom:3px; font-weight: 700;">	&nbsp;&nbsp;&nbsp;<?=$branch_name?></div>	
		
		<div style="float:left;">Place Of The Bank.</div></td></tr>
        <tr>
        <td colspan="4">N.B.Cheque/Draft/ Bankers Cheque are Subject to realisation.</td>
                </tr>
    <?php }
	  if($payment_mode=='CARD'){ ?> 
    <tr>
    <td colspan="2"><div style="float:left;" > Card No </div>
		<div style="border-bottom:#333333 dotted 2px; width:320px; height:14px; float:left; margin-left:5px;  padding-bottom:3px; font-weight: 700;">
       	&nbsp;&nbsp;&nbsp;XXXXXXXXXXXX<?=$card_no?>
            </div></td> <td colspan="2"> <div style="float:left;">Card Holders Name </div>
		<div style="border-bottom:#333333 dotted 2px; width:253px; height:14px; float:left; margin-left:5px;  padding-bottom:3px; font-weight: 700;">	&nbsp;&nbsp;&nbsp;<?=$cardholder_name?></div></td>
    </tr>
     <tr>
    <td colspan="2"><div style="float:left;">APPR Code </div>
		<div style="border-bottom:#333333 dotted 2px; width:306px; height:14px; float:left; margin-left:2px;  padding-bottom:3px;">&nbsp;&nbsp;&nbsp;<?=$appr_code?></div></td> <td colspan="2"> <div style="float:left;">Transaction Id </div>
		<div style="border-bottom:#333333 dotted 2px; width:285px; height:14px; float:left; margin-left:5px;  padding-bottom:3px; font-weight: 700;">	&nbsp;&nbsp;&nbsp;<?=$transctn_id?></div></td>
     
    </tr>
   
    <?php }?>
    </table>
    
    </td>
    </tr>
</table>
<div style="width:100%; margin: auto; line-height:35px; border-bottom:#000000 double 2px; margin-bottom:2px"><strong style="font-size:12px;">WATER USES CHARGE DETAILS</strong></div>

<table width="100%" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <tr style="font-size:12px;">
    <td width="6%" height="21" nowrap="nowrap" style="" ><strong style="font-size:12px;">Code of Amount</strong></td>
    <td width="42%" align="center" style="" ><strong style="font-size:12px;">Account Description</strong></td>
    <td width="9%" align="center"  style="width: 8%; "><strong style="font-size:12px;"><?php
    if($show_meter==0)
    {echo 'Period';}elseif($show_meter==1)
    {echo 'Units';}else
    {echo 'Period/Units';}?></strong></td>
   
	<?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?>  
    <td width="18%" align="center"  style="width: 8%;  font-weight:bold">Due Period</td>
    <?php } ?>
        
    <td width="25%" align="right" style="" ><strong style="font-size:12px;">Amount</strong></td>
  </tr>
  
  <tr>
    <td height="21"  style=" " ><strong style="font-size:12px;">1405012</strong></td>
    <td  style="" >Water Fees (User Charge)(<?=$strtcfy." - ".$endcfy?>)</td>
    <td nowrap="nowrap"   style="width: 21%;  " align="center">
       <strong style="font-size:13px;">
       <?php
	if($show_meter==0 || $row['connection_type']==0)
		{
			if($due_from==0 || $due_upto==0)
				echo 'N/A';
			else
				echo  $due_from." - ".$due_upto;
	  	}
		elseif($show_meter==1)
		{
	    echo  $prev_meter_read." - ".$curr_metr_reading;
		}
		elseif($show_meter==2)
		{
		 echo  $due_from." - ".$due_upto.'<hr style="margin:0px; background:#333; height:1px; border:none">&nbsp;&nbsp;'.$prev_meter_read." - ".$curr_metr_reading;
	  	}
	
	  ?>
       </strong>   </td>
       
       	<?php
   if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?>
    <td nowrap="nowrap" style="width: 21%;  text-align:center" valign="top"><strong>
	<?php if($show_meter==0 || $show_meter==2){echo $due_months.' Month(s)';}?></strong><?php if($show_meter==2){?><hr style="margin:0px; background:#333;  height:1px; border:none"><?php } ?>
    </td>
    <?php }?>
    
    <td  align="right" style=""><strong style="font-size:13px;"><?php 
	if($show_meter==0){echo number_format($current_amount_fxd,2);}else if($show_meter==1){echo number_format($current_amount_mtr,2);}
	else if($show_meter==2){ echo  $current_amount_fxd.'<hr style="margin:0px; background:#333;  height:1px; border:none">'.$current_amount_mtr; }else{echo '0.00';} ?></strong></td>
  </tr>

  <tr>
    <td height="21" style="" ><strong style="font-size:12px;">1801003</strong></td>
    <td style="" >Water Supply Deposits (Deposit Fortified)<span style="font-size:15px;"> </span></td>
    <td	<?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>  style="width: 21%; ">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
      </td>
    <td  align="right" style="">
    </td>
  </tr>
  <tr>
    <td height="21" style="" ><strong style="font-size:12px;">1404007</strong></td>
    <td style="" >Disconnection Fees<span style="font-size:15px;"> </span></td>
    <td <?php
    if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?> style="width: 21%; ">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
    <td  align="right" style="">
      
    </td>
  </tr>
  
  <tr>
    <td height="21" style="" ><strong style="font-size:12px;">3402001</strong></td>
    <td style="">Water Connection Deposit(Refundable)</td>
    <td <?php
    if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>  style="width: 21%; ">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       
      </td>
    <td  align="right" style="">
      
    </td>
  </tr>
    <tr >
    <td height="21" style="" ><strong>1407004</strong></td>
    <td style="" >Road Damage Recovery</span></td>
    <td <?php
    if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>  style="width: 21%; ">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       
      </td>
    <td  align="right" style="">
      
    </td>
  </tr>
 
  <tr >
    <td height="21"  style="" ><strong >1718003</strong></td>
    <td align="left" style="" >Interest on Water Tax Receivable </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  
  
   <!--added more rows -->
  <tr>
    <td height="21"  style="" ><strong >2341673</strong></td>
    <td align="left" style="" >House Connection Fees For Water/Restoration Charges/ Trench-Cutting </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >3497197</strong></td>
    <td align="left" style="" >Other Fees </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >34637197</strong></td>
    <td align="left" style="" >Meter Rent For Water Supply </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >36748912</strong></td>
    <td align="left" style="" >Water Connection Application Form(Sale Form & Application) </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >3310297</strong></td>
    <td align="left" style="" >Plumber Registration Fees </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >4576123</strong></td>
    <td align="left" style="" >Tender Money Deposit </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >5673491</strong></td>
    <td align="left" style="" >Supply Of Water By Tanker </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >2398761</strong></td>
    <td align="left" style="" >Sale Of Tender Form </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >5678934</strong></td>
    <td align="left" style="" >Ferrule Clearance Charge </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
 <tr>
    <td height="21"  style="" ><strong >6712356</strong></td>
    <td align="left" style="" >Service Administrative Charges </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="21"  style="" ><strong >3490879</strong></td>
    <td align="left" style="" >Water Supply Deposits(Lapsed Deposit) </span></td>
    <td <?php
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {?> colspan="2"<?php }?>    style="width: 21%; ">&nbsp;</td>
    <td  align="right" style="">&nbsp;</td>
  </tr>
  <!-- End  -->
  
  <?php
 
     if(($show_meter==0 || $show_meter==2) && $is_meter_working=='')
    {$sty='style="border:#000000 solid 0px;"';$cols='colspan="4"';}else{$sty='""';$cols='colspan="3"';}?>
    
 


  <tr <?=$sty?>>
    <td height="21" align="right" nowrap="nowrap"   style="width: 21%;  "  <?=$cols?>><strong style="font-size:13px;">Total&nbsp;Billing Amount&nbsp;&nbsp;</strong></td>
    <td align="right" nowrap="nowrap" style=" " >
       <strong style="font-size:13px;"> 
       <?php  $total_billing_amount=round($pay_q_row['total_billing_amount']); 
	   			echo number_format($total_billing_amount,2); ?></strong>
      </td>
  </tr>
  
  <!--AMOUNT ADJUSTED -->
  <?php if($amount_adjusted>0){ ?>
  <tr <?=$sty?>>
    <td height="21" align="right" nowrap="nowrap"   style="width: 21%;  "  <?=$cols?>><strong style="font-size:13px;">Amount To Be Adjusted&nbsp;&nbsp;</strong></td>
    <td align="right" nowrap="nowrap" style=" " >
       <strong style="font-size:13px;"> 
       <?php  echo number_format($amount_adjusted,2); ?></strong>
      </td>
  </tr>
  <?php } ?>
  <!--END -->
  
   <?php if($pay_q_row['advance_adjusted']>0){?> <tr <?=$sty?>>
   <td height="21" align="right" nowrap="nowrap"   style="width: 21%;  "  <?=$cols?>><strong style="font-size:13px;">Advance Adjusted&nbsp;&nbsp;&nbsp;</strong></td>
    <td  align="right" style=" "> <strong style="font-size:13px;"><?php echo number_format($pay_q_row['advance_adjusted'],2);?></strong>
    </td>
  </tr><?php } ?>
  <tr <?=$sty?>>
    <td height="21"  align="right" style="width: 21%;  "  <?=$cols?>><strong style="font-size:13px;">Amount Received&nbsp;&nbsp;&nbsp;</strong></td>
    <td  align="right" style=" "> <strong style="font-size:13px;"><?php  echo $total_paid=number_format($amount_paid,2);?></strong> </td>
  </tr>
	<?php 
	
	if($pay_q_row['total_due']>=0){
	?>
	    
    <tr <?=$sty?>>
	
    <td height="21"  align="right" style="width: 21%; "  <?=$cols?>><strong style="font-size:13px;">Total Due&nbsp;&nbsp;&nbsp;</strong></td>
    <td  align="right"  style="width: 21%; "> <strong style="font-size:13px;"><?php  echo number_format($pay_q_row['total_due'],2);?></strong></td> </tr><?php }  if($pay_q_row['advance_amount']>0){
	?>
	    
    <tr <?=$sty?>>
	
    <td height="21"  align="right" style="width: 21%; "  <?=$cols?>><strong style="font-size:13px;">Advance Amount &nbsp;&nbsp;</strong></td>
    <td  align="right"  style="width: 21%; "> <strong style="font-size:13px;"><?php  echo number_format($pay_q_row['advance_amount'],2);?></strong></td> </tr><?php }  ?>
	 <tr >
	   
	     </tr>
</table>
<br />
<table width="100%"  border="0">
           <tr>
            <!-- <td width="26%" height="17" align="right"> <strong>Signature Of Clerk</strong></td>-->
             <td width="57%">
 </td>
             <td width="27%" align="right"><strong>Signature of Tax Collector</strong> </td>
           </tr>
           <tr>
             <td height="21">&nbsp;</td>
             <td align="right">&nbsp;</td>
           </tr>
         </table>


<table width="100%"  align="center" border="0">
	 
<tr>

<td width="61%" align="left" valign="top" >

  <?php 
$rsulb=$CoreSystem->getRows($_SESSION["db_system"],"ulb_master","id=".$_SESSION['ulb_master_id']);
$rscom=$CoreSystem->getRows($_SESSION["db_system"],"system_patner_master","id=".$rsulb['patner_name']);

?> 
For Details Please Visit : ranchimunicipal.com
               <br />
			  
Or Call us at 18008904115 or 0651-3500700</td>

<td width="17%" rowspan="2" align="right" valign="top" nowrap="nowrap" style="vertical-align:middle; text-align:left">&nbsp;</td>
<td width="22%" rowspan="2" align="right" valign="top" style="vertical-align:middle; text-align:center">
In Association with<br>
Sri Publication & Stationers Pvt. Ltd.<br>
Ashok Nagar,<br>
Ranchi - 834002</td>
</tr>
<tr>
  <td align="left" valign="top" ><strong>Print Date : <?=date('d-m-Y')?></strong></td>
</tr>
<tr>
  <td colspan="3" align="left" valign="top" ><strong>NOTE : This is a Computer generated receipt.This receipt does not require physical signature.</strong></td>
  </tr>
</table>
<br />


</div>


<div style="page-break-after:always"></div>
<?php if($penalty>0){?>
<div style="width:210mm; height:auto; margin:40px auto; border:#666666 solid 1px;">

  <table width="90%" border="0" align="center">
   <tr>
    <td height="10" colspan="3" align="center"><span style="font-family: Microsoft Sans Serif;
	font-size: 20px;
	font-weight: bold; text-align:center;"><?php echo $ulb_name;?></span></td>
    </tr>
  <tr>
    <td width="100%" height="34">
    
    <table width="93%" border="0">
      <tr>
       
        <td width="35%" align="right" colspan="4" nowrap="nowrap">
        	<span style="float:left; text-align:left">
        <span class="style1">MR No.: <?=$mr_no?> </span>
            <p></p>
		
         <span style="line-height:40px; font-weight:bold"> MR Date :  <?=$mr_date?></span> 
         <p></p>
</span>
		  <p></p>
		  <span style="float:right">
                   <span class="style1"> Consumer No :  <?=$consumer_no?>
		 </span>
         <p></p>
         <span class="style1"> Ward :  <?=$ward_no?>
		 </span>
         <p></p>
         <span class="style1"> Holding No :  <?=$holding_no?>&nbsp;&nbsp;&nbsp;
		 </span></span>		  </td>
      </tr>
      <tr>
        <td colspan="4" >
<div class="money_box">Money Receipt </div></td>
      </tr>
    
      <tr>
        <td height="34" colspan="4"><div class="print_text">Received with thanks from : </div>
            <div class="print_text_amt">
          <?=$consumer_name?>
            </div>            </td>
      </tr>
      <tr>
        <td height="32" colspan="4">
        <div class="print_text">An Amount In Words : </div>
            <div class="print_text_amt1">
			<?php echo 'Rs '.$db->Inwords(abs($penalty)). ' Only';?> </div>            </td>
      </tr>
      
      
      <tr>
        <td height="41" colspan="4">
        <div class="print_text">Against the :</div>
            <div class="print_text_amt2">
           Cheque Bounce Charge            </div>            </td>
      </tr>
      <tr>
        <td height="32" colspan="4"><div class="print_text">Payment :</div>
            <div class="print_text_amt3">
           <?=$payment_mode?></div></td>
      </tr>
      <tr>
        <td height="37" colspan="3">
        <table width="40%" border="2" cellpadding="2" cellspacing="1" style=" border-bottom:solid">
            <tr>
              <td width="28%" height="31" align="center" bgcolor="#FFFFFF"><span class="style1">Rs.</span></td>
              <td width="72%" bgcolor="#FFFFFF"  style="font-size:18px; font-weight:bold;"><?=$penalty?></td>
            </tr>
        </table></td>
        <td width="11%" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td width="35%">&nbsp;</td>
        <td width="1%">&nbsp;</td>
        <td colspan="2" align="right">Authorised Signature </td>
        </tr>
    </table></td>
    </tr>
</table>
<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="3%"><img src="<?php echo IMAGE_PATH?>cutter.png" /></td>
    <td width="93%"><div style="width:100%; height:3px; border-bottom:dashed 2px #000000;"></div></td>
    <td width="4%"><img src="<?php echo IMAGE_PATH?>cutter1.png" /></td>
  </tr>
</table>-->
</div>
<?php }?>
</div>


<a href="#confirmation"  data-toggle="modal" style="opacity:0; "></a>
						<div id="confirmation" class="modal hide fade in" role="dialog" ria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header btn-danger" style="height:30px; ">
                        	<a class="close" data-dismiss="modal" style="float:right;cursor:pointer;"><strong>X</strong></a>
                            <h2 style="color:#FFF; font-size:16px; text-align:left">Alert</h2>
                        </div>
                        <div>
							   <fieldset>
								<div class="modal-body">
								 <ul class="nav nav-list">
								 <p><strong>Please Don't Use Browser Back Button or Don't Reload the Page...</strong></p>
									</ul> 
								 </div>
								</fieldset>
						</div>
						<div class="modal-footer">
						<a class="close btn" data-dismiss="modal" style="float:right;">Close</a>
						
						</div>
                     	</div>
						



<div align="center" style="margin-bottom:40px; margin-top:30px;"><button onClick="printDiv('printableArea')" style="width:100px" class="btn btn-inverse">Print</button>
</div>




</div>
    <script>
$(document).ready(function() { 
var text_app= "<?=$_SERVER['HTTP_HOST']?>/watercharge/paymentreceipt_qrcode.php?pmid=<?=$_GET['pmid']?>";
//alert (text_app);
var qrcode = new QRCode("qrcode", {
    text: text_app,
    width: 150,
    height: 150,
    colorDark : "#000000",
    colorLight : "#ffffff",
    correctLevel : QRCode.CorrectLevel.H
});
});
</script>
