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
                                    <p><strong><span style="font-size:30px;">कार्यालय : राँची नगर निगम </span><br><span>( जलापूर्ति शाखा  )</span></u></strong></p>
                                    <br/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" colspan="2"><p>कचहरी रोड , राँची , पिन न० - 834001 <br/>
                                    Email ID - support@ranchimuncipal.com
                                    </p>
                                </td>
                            </tr>

                        </table>
                        <table width="100%" stype="font-size:20px;">
                            <tr>
                                <td width="70%">&#2346;&#2340;&#2381;&#2352;&#2366;&#2306;&#2325; : <strong class="border">NOTICE/<?=$notice_no?? 'NA'?></strong></td>
                                <td width="30%"> &#2342;&#2367;&#2344;&#2366;&#2306;&#2325; : <strong class="border"><?=date('d-m-Y', strtotime($notice_date)) ;?></strong></td>
                            </tr>

                        </table>
                        <table style="width:100%; padding-top: 25px;" align="left">
                            <tr>
                                <td style="padding-bottom: 10px;">&#2346;&#2381;&#2352;&#2375;&#2359;&#2367;&#2340;,</td>

                            </tr>
                            <tr style="padding-top: 10px;">
                                <td style="padding-bottom: 10px !important;"> <strong> विषय :-  जल शुल्क हेतु माँग पत्र  (Demand ) का प्रेसण |</strong> <br/>

                                </td>
                            </tr>
                            <tr>
                                <td>श्री/श्रीमती/मेसर्स : <strong class="border"><?=$consumer['owner_name'] ?? 'NA' ?></strong></td>
                            </tr>
                            <tr >
                                <td style="padding-bottom: 15px;">महाशय,</td>
                            </tr>
                        </table>

                        <table style="width:100%;">
                            <tr>
                                <td style="padding-bottom: 15px; word-spacing: 1.5;">&nbsp
                                    आपके भवन / अपार्टमेंट / संस्थान / प्रतिस्ठान में लगे जल संयोजन संख्या ( <strong class="border"><?=$consumer['consumer_no'] ?? 'NA' ?></strong> ) वार्ड सं
                                        <strong class="border"><?= $consumer['ward_no'] ?></strong> सम्बंधित जल शुल्क का भुगतान विगत  <strong class="border"> <?= strtoupper($month = date('F', strtotime($from_fyear))) ?? 'NA'; ?> </strong> माह से नहीं की गयी है , इससे सम्बंधित माँग विवरणी निमन्वत है :-
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table border="1px" cellspacing="0" width="95%" align="center">
                                        <tr align="center">
                                            <td width="5%">s no. <br>1.</td>
                                            <td width="22%">Period for which demand is being raised <br>2.</td>
                                            <td>Total Month <br>3.</td>
                                            <td>Total water consumed in <br> KL <br>4.</td>
                                            <td width="18%">Rate per KL 5.</td>
                                            <td width="12%">Water Charge <br> = (3x4x5 ) 6.</td>
                                            <td width="10%">Interest <br> 1.5 % per month on column 6 <br>7.</td>
                                            <td>Total amount <br> due (6x7) 8.</td>
                                        </tr>
                                        <tr align="center">
                                            <td>1</td>
                                            <td><?=$consumer['demand_from'] ?? 'NA' ?> से <?=$consumer['demand_upto'] ?? 'NA' ?></td>
                                            <td>
                                                <?php
                                                $demand_from = new DateTime($consumer['demand_from']);
                                                $demand_upto = new DateTime($consumer['demand_upto']);
                                                $interval = $demand_from->diff($demand_upto);

                                                $total_months = $interval->format('%y') * 12 + $interval->format('%m');

                                                echo $total_months. ' महीने';
                                                ?>
                                            </td>
                                            <td><?=($reading<0)?0:$reading ?></td>
                                            <td>
                                                <span class="py-2 px-1">≤ 05 = RS - 0.00</span><br>
                                                <span class="py-2 px-1">&gt; 05 ≤ 50 = RS - 9.00</span><br>
                                                <span class="py-2 px-1">&gt; 50 ≤ 500 = RS - 10.80</span><br>
                                                <span class="py-2 px-1">&gt; 500 = RS - 13.50</span>
                                            </td>

                                            <td><?=$demand_amount ?></td>
                                            <td><?=$penalty_total ?></td>
                                            <td><?=($demand_amount +$penalty_total) ?></td>
                                        </tr>
                                        <tr align="right">
                                            <td></td>
                                            <td colspan="6" align="right">Grand Total</td>
                                            <td align="center"><strong><?=$total_amount ?? 'NA' ?></strong></td>
                                        </tr>

                                    </table>
                                    <br />
                                </td>
                            </tr>
                            <tr>
                                <td style="word-spacing: 1.5">
                                   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; आपको निर्देशित किया जाता है की उपरोक्त राशि का भुगतान, 15  दिनों के अन्दर कर दे अन्यथा जल संयोजन को बिच्छेदित करते हुए झारखण्ड नगरपालिका अधिनियम, 2011 की धारा 187 सहपठित धारा 184 के दिहित पारवधानो के अंतर्गत विधि सम्मत अग्रेतर आवश्यक करवाई प्रारम्भ की जाएगी |
                                </td>
                            </tr>

                        </table>

                        <br/>
                        <table width="100%">

                           <tr>
                              <strong>Note:-</strong>  इस राशि का भुगतान Cash / DD के माध्यम से निगम के प्राधिकृत संस्था <strong> M / S Shree Publications and Stationers Pvt. Ltd. </strong> के अधिकृत प्रितिनिधि / संस्था के स्थानीय कार्यालय / निगम कार्यालय में अवस्थित अकाउंट पर जमा किया जा सकता है |
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
