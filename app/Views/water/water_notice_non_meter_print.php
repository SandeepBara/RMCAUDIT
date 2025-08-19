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
            font-size: 14px;
        }
        @media print {
            #content-container {padding-top: 0px;}
            .row {
                /*background-image:url(*/<?php //=base_url(); ?>/*/public/assets/img/logo/*/<?php //=$ulb["id"];?>/*.png) !important;*/
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
            /*background-image:url(*/<?php //=base_url(); ?>/*/public/assets/img/logo/*/<?php //=$ulb["id"];?>/*.png) !important;*/
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
                            line-height: 1.8em;
                            font-size: 14px;
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
                <div class="row" style="border: 1px; border-style: solid; padding: 1px;">
                    <?/*php
                    $ulb_exp = explode(' ',trim($ulb['ulb_name']));
                    $ulb_short_nm=$ulb_exp[0];
                    */?>
                    <div class="" style="font-size:14px; border: 4px; border-style: solid; padding: 10px;">
                        <table width="100%" >
                            <tr>
                                <td width="10%" style="text-align: left;">
                                    <img style="height: 80px;width: 80px;" src='<?php echo base_url('public/assets/').session()->get("ulb_dtl")["logo_path"] ;?>'>
                                </td>
                                <td width="90%" style="text-align:center; margin-right:40px;">
                                    <p><strong><span style="font-size:30px;">RANCHI MUNCIPAL CORPORATION</span>
                                            <br><span>NOTICE</span>
                                            <br><span>( जलापूर्ति शाखा  )</span></u></strong></p>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" colspan="2"><p>झारखण्ड नगरपालिका अधिनियम - 2011 की धारा 592
                                    </p></td>

                            </tr>

                        </table>
                        <table width="100%" style="font-size:20px;">
                            <tr>
                                <td width="70%" style="padding-bottom: 20px !important;">&#2346;&#2340;&#2381;&#2352;&#2366;&#2306;&#2325; : <strong class="border"><?=$notice_no?? 'NA'?></strong></td>
                                <td width="30%"> &#2342;&#2367;&#2344;&#2366;&#2306;&#2325; : <strong class="border"><?=date('d-m-Y', strtotime($notice_date)) ;?></strong></td>
                            </tr>

                        </table>
                        <table style="width:100%;" align="left">
                            <tr >
                                <td style="padding-bottom: 30px !important;">&#2346;&#2381;&#2352;&#2375;&#2359;&#2367;&#2340; :- &nbsp; &nbsp; &nbsp; &nbsp; ..................</td>
                            </tr>
                            <tr>
                                <td>&#2358;&#2381;&#2352;&#2368;&#47;&#2358;&#2381;&#2352;&#2368;&#2350;&#2340;&#2368;&#47;&#2350;&#2375;&#2360;&#2352;&#2381;&#2360; : <strong class="border"><?=$consumer['owner_name'] ?? 'NA' ?></strong><br />
                                    &#2346;&#2367;&#2340;&#2366;&#47;&#2346;&#2340;&#2367; &#2325;&#2366; &#2344;&#2366;&#2350;  : <strong class="border"><?=$consumer['father_name'] ?? 'NA' ?></strong><br />
                                    &#2361;&#2379;&#2354;&#2367;&#2337;&#2306;&#2327; &#2344;&#2306;&#2406; : <strong class="border"><?=$consumer['holding_no'] ?? 'NA' ?></strong><br />
                                    वार्ड सं <strong class="border"><?=$consumer['ward_no'] ?></strong> <br/>
                                    &#2346;&#2340;&#2366; : <strong class="border"><?=$consumer['address'] ?? 'NA' ?></strong></td>
                            </tr>
                        </table>

                        <table style="width:100%;">
                            <tr>
                                <td style="padding-top: 20px; word-spacing: 2.5px; padding-bottom: 20px;">
                                    आपके भवन / अपार्टमेंट / संस्थान / प्रतिष्ठान में लगे जल संयोजन जिसका उपभोक्ता संख्या :- <strong><?=$consumer['consumer_no'] ?? 'NA' ?></strong> है | आपके द्वारा दिनांक <strong> <?=$consumer['demand_from'] ?? 'NA' ?> </strong> से <strong> <?=$consumer['demand_upto'] ?? 'NA' ?> </strong> तक जल संयोजन विपत्र भुगतान नहीं किया गया है |  नगर विकास एव आवास विभाग , झारखण्ड सरकार की अधिसूचना संख्या 1624 ,
                                    दिनाँक - 31-05-2006 के तहत आपको उक्त अवधि के जलकर विपत्र का भुगतान हेतु माँग पत्र निम्न प्रकार से दी जा रही है |
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table border="1px" cellspacing="0" width="80%" align="center">
                                        <tr align="center">
                                            <td>क० स० </td>
                                            <td>अवधि </td>
                                            <td>विपत्र की माँग</td>
                                            <td>टिप्पणी </td>

                                        </tr>
                                        <tr align="center">
                                            <td>1</td>
                                            <td><?=$consumer['demand_from'] ?? 'NA' ?> से <?=$consumer['demand_upto'] ?? 'NA' ?></td>
                                            <td><?= $demand_amount ?? 'NA' ?></td>
                                            <td></td>

                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td  align="right">Penalty <br> ( दण्ड ) </td>
                                            <td align="center"><?=$penalty_total ?? 'NA' ?></td>
                                            <td align="center"></td>
                                        </tr>
                                        <tr align="right">
                                            <td></td>
                                            <td  align="right">Total Payable amount <br>
                                                    कुल देय राशि</td>
                                            <td align="center"><strong><?=$total_amount ?? 'NA' ?></strong></td>
                                            <td align="center"><strong></strong></td>
                                        </tr>

                                    </table>
                                    <br />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    अतएव आपको निर्देशित किया जाता है की उपरोक्त राशि का भुगतान, 15  दिनों के अन्दर कर दे अन्यथा जल संयोजन को बिच्छेदित करते हुए झारखण्ड नगरपालिका अधिनियम, 2011 की धारा 187 सहपठित धारा 184 के दिहित पारवधानो के अंतर्गत विधि सम्मत अग्रेतर आवश्यक करवाई प्रारम्भ की जाएगी|
                                </td>
                            </tr>

                        </table>

                        <br/>
                        <table width="100%" align="center">

                            <tr align="center">
                                <td>इसे सख्त ताक़िद समझा जाए </td>
                            </tr>

                        </table>
                        <table width="100%">
                            <tr>
                                <td width="60%"><br/><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td width="40%" align="center"><p style="position: relative;">
                                       <!-- <img src="<?php echo base_url();?>/public/assets/img/watetsign.png" style="position: absolute;bottom: 24px;max-height: 70px;" alt="rmc"> -->
                                       <img src="<?=$signature_path;?>" style="position: absolute;bottom: 24px;max-height: 70px;" alt="rmc">
                                       उप प्रशासक<br />
                                        &#2352;&#2366;&#2305;&#2330;&#2368; &#2344;&#2327;&#2352; &#2344;&#2367;&#2327;&#2350;&#44; &#2352;&#2366;&#2305;&#2330;&#2368;
                                    </p></td>
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
