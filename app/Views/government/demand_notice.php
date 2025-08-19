<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Notice </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="icon" href="<?=base_url();?>/public/assets/img/favicon.ico">
<style>
.border{
    padding: 0 5px;
    border-bottom: 1px solid;
    line-height: 1.8em;
    /* font-size: 14px; */
}
@media print {
    #content-container {padding-top: 0px;}
    .row {
        background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["id"]??"";?>.png) !important;
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
    background-image:url(<?=base_url(); ?>/public/assets/img/logo/<?=$ulb["id"]??"";?>.png) !important;
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
<div id="content-container text-center" style="font-size:small">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark col-md-8 col-md-offset-2">


            <div class="panel-body" id="print_watermark" style="width: 250mm; height: auto; margin: auto;  background: #FFFFFF; ">
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
                        .border{
                            padding: 0 5px;
                            border-bottom: 1px solid;
                            line-height: 1.4em;
                            font-size: 12px;
                        }
                        body  {
                        padding-top:15px;
                        padding-bottom: 5px ; background:#FFFFFF;
                        font-size: 8px;
                        }
                        /* Enable Background Graphics(ULB Logo) */
                        *{
                        -webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
                        color-adjust: exact !important;                 /*Firefox*/
                        
                    }
                }
            </style>
                <div class="row" style="border: 1px; border-style: solid; padding: 1px;">
                    <?php
                        $ulb_exp = explode(' ',trim($ulb['ulb_name']));
                        $ulb_short_nm=$ulb_exp[0];
                     ?>
                        <div class="" style="font-size:11.5px; border: 4px; border-style: solid; padding: 10px;">
                            <table width="100%" >
                                 <tr>
                                    <td width="10%" style="text-align: left;">
                                        <img style="height: 80px;width: 80px;" src='<?php echo base_url('public/assets/').session()->get("ulb_dtl")["logo_path"];?>'>
                                    </td>
                                    <td width="90%" style="text-align:center; margin-right:40px;">
                                        <p>
                                            <strong>
                                                <span style="font-size:30px;">
                                                    कार्यालय राँची नगर निगम, राँची
                                                    </span>
                                                    <br>
                                                    <u>
                                                        <span>
                                                            ( राजस्व शाखा )
                                                        </span>
                                                    </u>
                                            </strong>
                                        </p>
                                        <!-- <br/> -->
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" colspan="2">
                                        <p>
                                            झारखंड नगरपालिका अधिनियम 2011 की धारा 181 के अधीन सेवा शुल्क (Service charge) भुगतान हेतु डिमांड नोटिस - I का प्रेषण ।
                                        </p>
                                    </td>
                                 </tr>

                             </table>
                             <table width="100%" stype="font-size:15px;">
                                <tr>
                                    <td width="70%">
                                        नोटिस संख्या : 
                                        <strong class="border">NOTICE/<?=$notice["notice_no"];?></strong>
                                    </td>
                                    <td width="30%">
                                        नोटिस तिथि : 
                                        <strong class="border"><?=date('d-m-Y', strtotime($notice["notice_date"]));?></strong>
                                    </td>
                                </tr>
                             </table>
                             <table style="width:100%;" align="left">
                                <tr>
                                    <td>
                                        <br>
                                        प्रेषित 
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        श्री/श्रीमती/मेसर्स : 
                                        <strong class="border"><?=$property["officer_name"]?></strong><br />
                                        कॉलोनी/भवन का नाम : 
                                        <strong class="border"><?=$property["building_colony_name"]?></strong>
                                        &nbsp;&nbsp;&nbsp;   &nbsp;&nbsp;&nbsp;  
                                        कार्यालय का नाम : 
                                        <strong class="border"><?=$property["office_name"]?></strong>
                                        <br />
                                        Acknowledgement No 
                                        <strong class="border"><?=$property["application_no"];?></strong>  
                                        <br>
                                        वार्ड नं० :- 
                                        <strong class="border"><?=!empty($property["ward_no"])?$property["ward_no"]:""?></strong>

                                        &nbsp;&nbsp;&nbsp;  
                                        नई वार्ड नं० :- 
                                        <strong class="border"><?=!empty($property["new_ward_no"])?$property["new_ward_no"]:""?></strong>
                                        <br />
                                        पता : 
                                        <strong class="border"><?=$property["building_colony_address"]?></strong>
                                        <br/>
                                        मोबाइल नंबर :-
                                        <strong class="border"><?=$property["mobile_no"]?></strong>
                                        &nbsp;&nbsp;&nbsp;
                                        ईमेल :-
                                        <strong class="border"><?=$property["email"]?></strong>
                                    </td> 
                                </tr>
                             </table>
                            
                             <table style="width:100%;">
                                 <tr>
                                     <td>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        आपके भवन (Acknowledgement No - 
                                        <strong class="border"><?=$property["application_no"];?></strong>
                                        ) का सेवा शुल्क बकाया आपके द्वारा 
                                        <strong class="border"><?= date("d M Y",strtotime(getQtrFistDate($notice["from_fyear"],$notice["from_qtr"])));?></strong> 
                                        से 
                                        <strong class="border"><?=date("d M Y",strtotime(getQtrLastDate($notice["upto_fyear"],$notice["upto_qtr"]))) ;?></strong> 
                                        अवधि तक का सेवा शुल्क जमा नहीं किया गया है। जिसके फल स्वरुप झारखंड नगरपालिका कर भुगतान ( समय,प्रक्रिया तथा वसूली ) विनिमय,- 2017 के नियम 3.1.2 तहत आपको उक्त अवधि का सेवा शुल्क का भुगतान नोटिस प्राप्ति के 30 दिन के अंतर्गत अनिवार्य रूप से करना है। इस मांग पत्र में उल्लेखित राशि की गणना निम्न रूप से की गई है। 
                                        <!-- <br> -->
                                        <!-- <br> -->
                                     
                                     </td>
                                </tr>
                                <tr>
                                     <td align="center">                                       
                                        <table border="1px" cellspacing="0" width="95%" align="center">
                                            <tr align="left">
                                                <td><strong>SL. NO.</strong></td>
                                                <td><strong>Particular ( विवरण )</strong></td>
                                                <td><strong></strong></td>
                                            </tr>
                                            <tr align="left">
                                                <td>1</td>
                                                <td>Service charge to be paid for the period form <strong class="border"><?=date("d M Y",strtotime(getQtrFistDate($notice["from_fyear"],$notice["from_qtr"]))) ?></strong> upto <strong class="border"><?=date("d M Y",strtotime(getQtrLastDate($notice["upto_fyear"],$notice["upto_qtr"]))) ?></strong></td>
                                                <td align="right"><strong><?=$notice["demand_amount"]?></strong></td>
                                            </tr>
                                            <tr align="left">
                                                <td>2</td>
                                                <td> * Interest amount @ 1% per month under section 182(3) of Jharkhand Municipal Act 2011 section 182(3) 
                                                एवं झारखण्ड नगरपालिका संपत्ति कर (निर्धारण, संग्रहण और वसूली) नियमावली 2013 यथा संशोधित के नियम 12.2 के अनुसार।
                                                </td>
                                                <td align="right"><strong><?=$notice["penalty"]?></strong></td>
                                            </tr>
                                            <tr align="left">
                                                <td>3</td>
                                                <td>
                                                    ** Penalty amount under झारखंड नगरपालिका कर भुगतान ( समय,प्रक्रिया तथा वसूली ) विनिमय,- 2017 के नियम 3.1.4
                                                </td>
                                                <td align="right"><strong><?=$notice["noticePenalty"]?></strong></td>
                                            </tr>
                                            <tr align="left">
                                                <td colspan="2"><strong>कुल भुगतेय राशि</strong></td>
                                                <td align="right"><strong><?=round($notice["demand_amount"]+$notice["penalty"]+$notice["noticePenalty"], 2);?></strong></td>
                                            </tr>
                                        </table>
                                        <!-- <br /> -->
                                     </td>
                                </tr>
                                <tr>
                                     <td>
                                        अतएव झारखण्ड नगरपालिका कर भुगतान ( समय , प्रक्रिया तथा वसूली ) विनियम,-2017 के विहित प्रावधान के अनुसार आपको उक्त अवधि का सेवा शुल्क का भुगतान अनिवार्य रूप से करना है। 
                                    </td>
                                </tr>
                                <tr>
                                    
                                     <td>
                                        <!-- <br/> -->
                                            &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;  
                                            इस राशि का भुगतान नोटिस प्राप्त होने के 01(एक) माह के अंदर करना सुनिश्चित करेंगे। अन्यथा उक्त विनिमय 2017 की कंडिका 3.1.4 के विहित प्रावधान के अनुसार दण्ड की राशि निम्न प्रकार से अधिरोपित की जायेगी :-
                                        </td>
                                     
                                 </tr>
                             </table>
                             <!-- <br> -->
                             <table border="1px" cellspacing="0" width="95%" align="center">
                                 <tr>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            क्रमांक
                                        </span>
                                    </td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            विलम्बित अवधी
                                        </span>
                                    </td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            दण्ड की राशि
                                        </span>
                                    </td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;"><span style="font-weight:bold;">01.</span></td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            निर्धारित अवधी से एक सप्ताह की अवधी तक
                                        </span>
                                    </td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            भुगतेय राशि का 1 प्रतिशत
                                        </span>
                                    </td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;"><span style="font-weight:bold;">02.</span></td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            निर्धारित अवधी से दो सप्ताह की अवधी तक
                                        </span>
                                    </td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            भुगतेय राशि का 2 प्रतिशत
                                        </span>
                                    </td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;"><span style="font-weight:bold;">03.</span></td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            निर्धारित अवधी से एक माह की अवधी तक
                                        </span>
                                    </td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            भुगतेय राशि का 3 प्रतिशत
                                        </span>
                                    </td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;"><span style="font-weight:bold;">04.</span></td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            निर्धारित अवधी से दो माह की अवधी तक
                                        </span>
                                    </td>
                                     <td style="padding-bottom:5px;">
                                        <span style="font-weight:bold;">
                                            भुगतेय राशि का 5 प्रतिशत
                                        </span>
                                    </td>
                                 </tr>
                             </table>
                            <!-- <br/> -->
                             <table width="100%">
                                <tr>
                                    <td>
                                        उसके पश्चात प्रत्येक माह के लिए 2 प्रतिशत अतिरिक्त दण्ड की राशि भुगतेय होगी।
                                        <br/>
                                        अतएव आप कर का भुगतान ससमय करना सुनिश्चित करें। 
                                        <br />
                                    <strong>
                                        * भुगतान के समय अद्यतन ब्याज की गणना की जाएगी
                                        <br>
                                        ** दण्ड राशि का गणना भुगतान के समय निर्धारित किया जाएगा।
                                        <br>
                                    </strong>
                                </td>
                                </tr>
                                <tr>
                                    <td align="center" class="text-sm">( this value of the total payble amount is subject to the date of notice generation. It may vary every month )</td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        इसे सख्त ताकीद समझा जाए।
                                        <br>
                                        ** This is a computer-generated demand notice with facsimile signature and dose not require a physical signature and stamp **      
                                        <br>
                                        ** Without Prejudice **                                   
                                    </td>
                                </tr>
                                
                             </table>
                             <table width="100%">
                                <tr>
                                    <td width="60%"><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="40%" align="center">
                                        <p style="position: relative;"><br><br>
                                            <?php
                                                $sign = "dmcsign.png";
                                                if($memo["created_on"]<'2024-09-28'){
                                                    $sign = "dmcsign.png";
                                                }
                                                if($memo["created_on"]<'2024-02-15'){
                                                    $sign = "rajnishkumar_sign.png";
                                                }
                                            ?>
                                            <!-- <img src="<?=base_url();?>/public/assets/img/<?=$sign?>" style="position: absolute;bottom: 24px;max-height: 90px;" alt="rmc"> -->
                                            <img src="<?=$signature_path;?>" style="position: absolute;bottom: 24px;max-height: 90px;" alt="rmc">
                                            <?=$degignation?>
                                            <br />
                                            राँची नगर निगम, राँची
                                        </p>
                                    </td>
                                </tr>
                             </table>
                        </div>

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
