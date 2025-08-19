<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Final Memo </title>
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
            <!--===================================================-->
<div id="content-container text-center">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark col-md-8 col-md-offset-2">


            <div class="panel-body" id="print_watermark" style="width: 250mm; height: auto; margin: auto;  background: #FFFFFF; ">
                <div class="col-sm-1 noprint text-right">
                    <!-- <button class="btn btn-mint btn-icon" onclick="print()" style="height:40px;width:60px;border:none;">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7,10a1,1,0,1,0,1,1A1,1,0,0,0,7,10ZM19,6H18V3a1,1,0,0,0-1-1H7A1,1,0,0,0,6,3V6H5A3,3,0,0,0,2,9v6a3,3,0,0,0,3,3H6v3a1,1,0,0,0,1,1H17a1,1,0,0,0,1-1V18h1a3,3,0,0,0,3-3V9A3,3,0,0,0,19,6ZM8,4h8V6H8Zm8,16H8V16h8Zm4-5a1,1,0,0,1-1,1H18V15a1,1,0,0,0-1-1H7a1,1,0,0,0-1,1v1H5a1,1,0,0,1-1-1V9A1,1,0,0,1,5,8H19a1,1,0,0,1,1,1Z" fill="#6563ff"/></svg>
                    </button> -->
                </div>
            <div class="col-md-12">
                &nbsp;&nbsp;&nbsp;&nbsp;
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
                                    <td width="40%" style="text-align: right;">
                                        <img style="height:60px;width:60px;" src='<?php echo base_url('public/assets/').(session()->get("ulb_dtl")["logo_path"]??$ulb["logo_path"]);?>'>
                                    </td>
                                    <td width="60%" style="font-size:14px; padding-left: 15px;">
                                        <label><strong><u><?=$ulb['ulb_name'].', '.$ulb_short_nm;?><br><span style="margin-left: 40px;">( Revenue branch  )</span></u></strong></label>

                                    </td>
                                 </tr>

                             </table>
                             <center>
                                 <table>
                                     <tr>
                                         <td><hr/><b>Notice of property tax prescribed under section 152 (B) cum read Jharkhand Municipal Property Tax (Assessment, Collection and Recovery) Rules of Jharkhand Municipal Act, 2011.</b><hr/></td>
                                     </tr>
                                 </table>
                             </center>
                             <table style="width:100%;" >
                                 <tr>
                                    <td>Mr /Mrs /Ms <br/><span style="font-weight:bold;" class="bol">
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
                                         </span>
                                    </td>
                                    <td align="right">
                                        Memo No. : <span style="font-weight:bold;" class="bol"><?=$memo['memo_no'];?></span><br>
                                        Date Of Approval: <span style="font-weight:bold;" class="bol"><?=$memo["created_on"] ? date('Y-m-d',strtotime($memo["created_on"])):"" ;?></span>
                                    </td>
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
                                    <td>Address: <span style="font-weight:bold;" class="bol"><?=strtoupper($saf['prop_address']).' '.strtoupper($saf['prop_city']);?>
                                         </span>
                                    </td>
                                    <td align="right">Effective: <span style="font-weight:bold;" class="bol"><?=$qtr_nm.' Quarter '.$fy['fy'];?></span></td>
                                 </tr>
                             </table>

                             <table style="width:100%;">
                                 <tr>
                                     <td>&nbsp;&nbsp;
                                     <div class="col-sm-4">
                                        <p >You are hereby informed that your new Holding No.- </p>
                                     </div>
                                     <div class="col-sm-6">
                                        <table cellpadding="0" cellspacing="0">
                                             <tr>
                                                 <?php


                                                 for($ivk=0; $ivk < strlen($memo['new_holding_no']); $ivk++)
                                                 {
                                                     ?>
                                                    <td style="width:20px;height:20px;border:2px solid #000;text-align:center;"><?=$memo['new_holding_no'][$ivk];?></td>
                                                    <?php
                                                 }
                                                 ?>
                                             </tr>
                                         </table>
                                        </div>


                                     </td>
                                 </tr>
                                 <tr>
                                     <td> The annual rent value of this holding is Rs.  <b> <?=round($saf_tax['arv'], 2);?> / - </b> after local check made by <?=$ulb['ulb_name'];?> in the light of the self assessment declaration letter made by you for assessment of tax for <b>Ward No. </b><?=$saf['new_ward_no'];?> (<b>Old Ward No.</b> <?=$saf['ward_no'];?>)
                                     <?php if ($saf['holding_no']!=$memo['new_holding_no']) { ?>
                                        (old house no. <?=$saf['holding_no'];?>, Ward no.<?=$saf['ward_no'];?>)
                                    <?php } ?>
                                    , <?=round($prop_tax['arv'], 2);?> / - is fixed at the place.
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>Based on the annual rent value determined by the corporation, the <?=$qtr_nm;?> quarter will be taxed in writing with effect from the year <?=$fy['fy'];?>.
                                     </td>
                                 </tr>
                             </table>
                             <table style="width:100%;" border="1px" cellpadding="0" cellspacing="0">
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Sl. No.</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Particulars</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Quarter/Financial Year</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Based on the Self Assessment</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">On the basis of ULB Calculation</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Difference Amount<br/>(4-3)</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">100% Penalty of Column 5</span></td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">1</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">2</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">3</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">4</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">5</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">6</span></td>
                                 </tr>
                                 <?php
                                 $saf_total=0;
                                 $prop_total=0;
                                 $diff_total=0;
                                 $i=1;
                                 if(isset($prop_tax_dtl) && !empty($prop_tax_dtl))
                                 foreach ($prop_tax_dtl as $value)
                                 {
                                    $diff_tax=($value['holding_tax']*4)-($value['holding_tx']*4);
                                    //$totaldiff_tax=($value['holding_tax']*4)+($value['holding_tx']*4);
                                        if($diff_tax<0) {
                                            $diff_tax_amt=0;
                                        } else {
                                            $diff_tax_amt=$diff_tax;
                                        }
                                        //$diff_percent=($diff_tax/$totaldiff_tax)*100;
                                        ?>
                                        <tr>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=$i++;?></span></td>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?php if ($value['fyy']=="2022-2023") { echo "Holding Tax @ 0.075% or 0.15% or 0.2%"; } else { echo "Holding Tax @ 2%";}?></span></td>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Quarter: <?=$value['qtr'];?>/ Year: <?=$value['fyy'];?></span></td>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=(isset($value['holding_tx']))?round($value['holding_tx']*4, 2):'0';?></span></td>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=(isset($value['holding_tax']))?round($value['holding_tax']*4, 2):'0';?></span></td>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=round($diff_tax_amt, 2);?></span></td>
                                            <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=round($diff_tax_amt, 2);?></span></td>
                                        </tr>
                                        <?php
                                        $saf_total=$saf_total+ ($value['holding_tx']*4);
                                        $prop_total=$prop_total+ ($value['holding_tax']*4);
                                        $diff_total=$diff_total+ $diff_tax_amt;
                                 }
                                 ?>

                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">Total amount </span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=round($saf_total, 2);?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=round($prop_total, 2);?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=round($diff_total, 2);?></span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;"><?=round($diff_total, 2);?></span></td>
                                 </tr>
                             </table>

                             <table>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img style="width:100px;height:100px;" src="<?php echo base_url('writable/uploads/qrCodeGenerator/'.$ss);?>" title="<?=$path;?>" />
                                    </td>
                                    <td style="text-align: center;">
                                    <?php if ($memo["verify_user_type_id"]==9) { 
										if($memo["emp_details_id"] == '1499'){
                                            $sign = "/girishprasad_sign.png";
										}else if($memo["emp_details_id"] == '1252'){
                                            $sign = "/nishant_tirky.png";
										}else if($memo["emp_details_id"] == '845'){
                                            $sign = "/mritunjay_kumar.jpg";
                                        }else if($memo["emp_details_id"] == '1615'){
                                            $sign = "/1615.png";
                                        }elseif ($memo["emp_details_id"] == '1688') {
                                            $sign = "/1688.png";
                                        }
                                        elseif(date('Y-m-d',strtotime($memo["created_on"]))>'2024-12-01'){
                                            $sign = "/1615.png";
                                        }else{
                                            $sign = "/robinkachhap.jpeg";
                                        }
                                        ?>
                                        <!-- <img  src="<?php echo base_url('writable/eo_sign/').$sign;?>" style="width:100px;height:100px;" /> -->
                                        <img  src="<?=$signature_path;?>" style="width:100px;height:100px;" />
                                        <br />
                                        <label style="font-size: 15px; font-weight: bold;">City&nbsp;Manager&nbsp;/Incharge</label>
                                        <label style="font-size: 15px; font-weight: bold;">(Revenue&nbsp;Manager)</label>
                                        <br/>
                                        <?=$ulb['ulb_name'].', '.$ulb_short_nm;?> 
                                    <?php 
                                } else { 
                                    $sign = "dmcsign.png";                                     
                                    // $degignation = "Additional";
                                    if($memo["created_on"]<'2024-09-28'){
                                        $sign="dmcsign_old.png";
                                        // $degignation = "Additional";
                                    }
                                    if($memo["created_on"]<'2024-02-15'){
                                        $sign="rajnishkumar_sign.png";
                                    }
                                    if($memo["created_on"]>='2025-01-16'){
                                        $sign = "gautam.png"; 
                                        // $degignation = "Deputy";
                                    }
                                    ?>
                                        <!-- <img  src="<?php echo base_url("writable/eo_sign/$sign");?>" style="width:100px;height:100px;" /> -->
                                        <img  src="<?=$signature_path;?>" style="width:100px;height:100px;" />

                                        <!-- <img  src="<?php echo base_url('writable/eo_sign/rajnishkumar_sign.png');?>" style="width:100px;height:100px;" /> -->
                                        <br />
                                        <label style="font-size: 15px; font-weight: bold;"><?=$degignation;//$memo["created_on"]<'2024-09-28' ?'Deputy':"Additional" ?>&nbsp;Municipal&nbsp;Commissioner</label>
                                        <br/>
                                        <?=$ulb['ulb_name'].', '.$ulb_short_nm;?> 
                                    <?php } ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><span style="font-weight:bold;">Note:-</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <ol>
                                            <li>If there is any objection against the tax prescribed by the corporation, under the prescribed provision of Section 167 (1) of the Jharkhand Municipal Act, 2011, within 30 (thirty) days of receipt of this assessment Memo can file an objection in the prescribed form. </li>
                                            <li>The list of tax assessment is displayed on the <?=$ulb['ulb_name'];?> website www.ranchimunicipal.com.</li>
                                            <li>As per clause 13.4 of the Jharkhand Municipal Property Tax (Assessment, Collection and Recovery) Rules 2013, the actual amount of differential tax and one hundred percent penalty on it is also payable.</li>
                                            <li>This property tax collected by the <?=$ulb['ulb_name'];?> does not confer any legal status on these buildings / structures and / or does not confer any legal rights to its owners / occupiers.</li>

                                        </ol>
                                    </td>
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
            <div class="col-md-12">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->

</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

    <div class="col-sm-1 noprint" align="center" style="margin-top: 40px;">
        <button class="btn btn-mint btn-icon" onclick="printDiv('print_watermark')"><i class="demo-pli-printer icon-lg"></i> PRINT</button>
    </div>
</body>
</html>
