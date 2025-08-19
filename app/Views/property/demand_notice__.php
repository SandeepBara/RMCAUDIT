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
                    <?php
                        $ulb_exp = explode(' ',trim($ulb['ulb_name']));
                        $ulb_short_nm=$ulb_exp[0];
                     ?>
                        <div class="" style="font-size:14px; border: 4px; border-style: solid; padding: 10px;">
                            <table width="100%" >
                                 <tr>
                                    <td width="10%" style="text-align: left;">
                                        <img style="height: 80px;width: 80px;" src='<?php echo base_url('public/assets/').session()->get("ulb_dtl")["logo_path"];?>'>
                                    </td>
                                    <td width="90%" style="text-align:center; margin-right:40px;">
                                        <p><strong><span style="font-size:30px;">&#2325;&#2366;&#2352;&#2381;&#2351;&#2366;&#2354;&#2351; &#2352;&#2366;&#2305;&#2330;&#2368; &#2344;&#2327;&#2352; &#2344;&#2367;&#2327;&#2350;&#44; &#2352;&#2366;&#2305;&#2330;&#2368;</span><br><span>( &#2352;&#2366;&#2352;&#2360;&#2381;&#2357; &#2358;&#2366;&#2326;&#2366; )</span></u></strong></p>
                                        <br/>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" colspan="2"><p>&#2333;&#2366;&#2352;&#2326;&#2339;&#2381;&#2337; &#2344;&#2327;&#2352;&#2346;&#2366;&#2354;&#2367;&#2325;&#2366; &#2309;&#2343;&#2367;&#2344;&#2367;&#2351;&#2350; &#50;&#48;&#49;&#49; &#2325;&#2368; &#2343;&#2366;&#2352;&#2366; &#49;&#56;&#49; &#2325;&#2375; &#2309;&#2343;&#2368;&#2344; &#2325;&#2352; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#2361;&#2375;&#2340;&#2369; &#2337;&#2367;&#2350;&#2366;&#2306;&#2337; &#2344;&#2379;&#2335;&#2367;&#2360; &#2325;&#2366; &#2346;&#2381;&#2352;&#2375;&#2359;&#2339;&#2404;</p></td>
                                 </tr>

                             </table>
                             <table width="100%" stype="font-size:20px;">
                                <tr>
                                    <td width="70%">&#2346;&#2340;&#2381;&#2352;&#2366;&#2306;&#2325; : <strong class="border">NOTICE/<?=$notice["notice_no"];?></strong></td>
                                    <td width="30%">&#2344;&#2379;&#2335;&#2367;&#2360; &#2342;&#2367;&#2344;&#2366;&#2306;&#2325; : <strong class="border"><?=date('d-m-Y', strtotime($notice["notice_date"]));?></strong></td>
                                </tr>
                                <!-- <tr>
                                    <td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="30%">&#2346;&#2381;&#2352;&#2367;&#2306;&#2335; &#2342;&#2367;&#2344;&#2366;&#2306;&#2325; &nbsp;&nbsp; : <strong class="border"><?=date('d-m-Y');?></strong></td>
                                </tr> -->
                             </table>
                             <table style="width:100%;" align="left">
                                <tr>
                                    <td>&#2346;&#2381;&#2352;&#2375;&#2359;&#2367;&#2340;</td>
                                </tr>
                                 <tr>
                                    <td>&#2358;&#2381;&#2352;&#2368;&#47;&#2358;&#2381;&#2352;&#2368;&#2350;&#2340;&#2368;&#47;&#2350;&#2375;&#2360;&#2352;&#2381;&#2360; : <strong class="border"><?=$property["owner_name"]?></strong><br />
                                    &#2346;&#2367;&#2340;&#2366;&#47;&#2346;&#2340;&#2367; &#2325;&#2366; &#2344;&#2366;&#2350;  : <strong class="border"><?=$property["guardian_name"]?></strong><br />
                                    &#2361;&#2379;&#2354;&#2367;&#2337;&#2306;&#2327; &#2344;&#2306;&#2406; : <strong class="border"><?=!empty($property["new_holding_no"])?$property["new_holding_no"]:$property["holding_no"]?></strong>  &nbsp;&nbsp;&nbsp;       &#2357;&#2366;&#2352;&#2381;&#2337; &#2344;&#2306;&#2406;:- <strong class="border"><?=!empty($property["new_ward_no"])?$property["new_ward_no"]:$property["ward_no"]?></strong><br />
                                    &#2346;&#2340;&#2366; : <strong class="border"><?=$property["prop_address"]?></strong></td> 
                                </tr>
                             </table>
                            
                             <table style="width:100%;">
                                 <tr>
                                     <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                     &#2310;&#2346;&#2325;&#2375; &#2349;&#2357;&#2344; (&#2361;&#2379;&#2354;&#2381;&#2337;&#2367;&#2306;&#2327; &#2344;&#2306;&#2406; - <strong class="border"><?=isset($property["new_holding_no"])?$property["new_holding_no"]:$property["holding_no"]?></strong>) &#2325;&#2366; &#2343;&#2371;&#2340;&#2367;&#2325;&#2366;&#2352; &#45;  &#2348;&#2325;&#2366;&#2351;&#2366;&#2404;  &#2310;&#2346;&#2325;&#2375; &#2342;&#2381;&#2357;&#2366;&#2352;&#2366; <strong><?=date("d-m-Y", strtotime($notice["due_date"]));?></strong> &#2340;&#2325; &#2325;&#2368; &#2309;&#2357;&#2343;&#2367; &#2325;&#2366; &#2335;&#2376;&#2325;&#2381;&#2360; &#2332;&#2350;&#2366; &#2344;&#2361;&#2368;&#2306; &#2325;&#2367;&#2351;&#2366; &#2327;&#2351;&#2366; &#2361;&#2376;&#2404;  &#2309;&#2340;&#2319;&#2357; &#2333;&#2366;&#2352;&#2326;&#2306;&#2337; &#2344;&#2327;&#2352;&#2346;&#2366;&#2354;&#2367;&#2325;&#2366; &#2325;&#2352; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#40;&#2360;&#2350;&#2351;&#44;&#2346;&#2381;&#2352;&#2325;&#2381;&#2352;&#2367;&#2351;&#2366; &#2340;&#2341;&#2366; &#2357;&#2360;&#2370;&#2354;&#2368; &#41; &#2357;&#2367;&#2344;&#2367;&#2351;&#2350;&#44;&#45; &#50;&#48;&#49;&#55; &#2325;&#2375; &#2344;&#2367;&#2351;&#2350; &#51;&#46; &#49; &#46; &#50; &#2340;&#2361;&#2340; &#2310;&#2346;&#2325;&#2379; &#2313;&#2325;&#2381;&#2340; &#2309;&#2357;&#2343;&#2368; &#2325;&#2366; &#2361;&#2379;&#2354;&#2381;&#2337;&#2367;&#2306;&#2327; &#2335;&#2376;&#2325;&#2381;&#2360; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#2361;&#2375;&#2340;&#2369; &#2350;&#2366;&#2306;&#2327; &#2346;&#2340;&#2381;&#2352; &#2344;&#2367;&#2350;&#2381;&#2344; &#2327;&#2339;&#2344;&#2366; &#2325;&#2375; &#2309;&#2344;&#2369;&#2360;&#2366;&#2352; &#2342;&#2368; &#2332;&#2366; &#2352;&#2361;&#2368; &#2361;&#2376;&#2404; 
                                     </td>
                                </tr>
                                <tr>
                                     <td align="center">
                                        <table border="1px" cellspacing="0" width="60%" align="center">
                                            <tr align="right">
                                                <td><strong class="border">Till - <?=$notice["from_qtr"]?>/<?=$notice["from_fyear"]?></strong></td>
                                                <td><strong class="border"><?=$notice["upto_qtr"]?>/<?=$notice["upto_fyear"]?></strong></td>
                                                <td><strong>Total Amount</strong></td>
                                            </tr>
                                            <tr align="right">
                                                <td>&nbsp;&nbsp;</td>
                                                <td>Demand Amount</td>
                                                <td><strong><?=$notice["demand_amount"]?></strong></td>
                                            </tr>
                                            <tr align="right">
                                                <td>&nbsp;&nbsp;</td>
                                                <td>Penalty</td>
                                                <td><strong><?=$notice["penalty"]?></strong></td>
                                            </tr>
                                            <tr align="right">
                                                <td colspan="2"><strong>Total Amount</strong></td>
                                                <td><strong><?=round($notice["demand_amount"]+$notice["penalty"], 2);?></strong></td>
                                            </tr>
                                        </table>
                                        <br />
                                     </td>
                                </tr>
                                <tr>
                                     <td>
                                     &#2309;&#2340;&#2319;&#2357; &#2333;&#2366;&#2352;&#2326;&#2339;&#2381;&#2337; &#2344;&#2327;&#2352;&#2346;&#2366;&#2354;&#2367;&#2325;&#2366; &#2325;&#2352; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#40; &#2360;&#2350;&#2351; &#44; &#2346;&#2381;&#2352;&#2325;&#2381;&#2352;&#2367;&#2351;&#2366; &#2340;&#2341;&#2366; &#2357;&#2360;&#2370;&#2354;&#2368; &#41; &#2357;&#2367;&#2344;&#2367;&#2351;&#2350;&#44;&#45;&#50;&#48;&#49;&#55; &#2325;&#2375; &#2357;&#2367;&#2361;&#2367;&#2340; &#2346;&#2381;&#2352;&#2366;&#2357;&#2343;&#2366;&#2344; &#2325;&#2375; &#2309;&#2344;&#2369;&#2360;&#2366;&#2352; &#2310;&#2346;&#2325;&#2379; &#2313;&#2325;&#2381;&#2340; &#2309;&#2357;&#2343;&#2368; &#2325;&#2366; &#2361;&#2379;&#2354;&#2381;&#2337;&#2367;&#2306;&#2327; &#2335;&#2376;&#2325;&#2381;&#2360; &#2325;&#2366; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#2325;&#2352;&#2344;&#2366; &#2361;&#2376;&#2404; 
                                     </td>
                                </tr>
                                <tr>
                                    
                                     <td><br/>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;  &#2311;&#2360; &#2352;&#2366;&#2358;&#2367; &#2325;&#2366; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#2344;&#2379;&#2335;&#2367;&#2360; &#2346;&#2381;&#2352;&#2366;&#2346;&#2381;&#2340; &#2361;&#2379;&#2344;&#2375; &#2325;&#2375; &#48;&#49;&#40;&#2319;&#2325;&#41; सप्ताह  &#2325;&#2375; &#2309;&#2306;&#2342;&#2352; &#2325;&#2352;&#2344;&#2366; &#2360;&#2369;&#2344;&#2367;&#2358;&#2381;&#2330;&#2367;&#2340; &#2325;&#2352;&#2375;&#2306;&#2327;&#2375;&#2404; &#2309;&#2344;&#2381;&#2351;&#2341;&#2366; &#2313;&#2325;&#2381;&#2340; &#2344;&#2367;&#2351;&#2350;&#2366;&#2357;&#2354;&#2368; &#2325;&#2368; &#2325;&#2306;&#2337;&#2367;&#2325;&#2366; &#51;&#46;&#49;&#46;&#52; &#2325;&#2375; &#2357;&#2367;&#2361;&#2367;&#2340; &#2346;&#2381;&#2352;&#2366;&#2357;&#2343;&#2366;&#2344; &#2325;&#2375; &#2309;&#2344;&#2369;&#2360;&#2366;&#2352; &#2344;&#2367;&#2350;&#2381;&#2344; &#2346;&#2381;&#2352;&#2325;&#2366;&#2352; &#2360;&#2375; &#2313;&#2346;&#2352;&#2379;&#2325;&#2381;&#2340; &#2325;&#2375; &#2309;&#2340;&#2367;&#2352;&#2367;&#2325;&#2381;&#2340; &#2325;&#2375; &#2309;&#2340;&#2367;&#2352;&#2367;&#2325;&#2381;&#2340; &#2342;&#2339;&#2381;&#2337; &#2325;&#2368; &#2352;&#2366;&#2358;&#2367; &#2344;&#2367;&#2350;&#2381;&#2344; &#2346;&#2381;&#2352;&#2325;&#2366;&#2352; &#2360;&#2375; &#2309;&#2343;&#2367;&#2352;&#2379;&#2346;&#2367;&#2340; &#2325;&#2368; &#2332;&#2366;&#2351;&#2375;&#2327;&#2368;&#2307;&#45;</td>
                                     
                                 </tr>
                             </table>
                             <table border="1px" cellspacing="0" width="80%" align="center" style="display:none">
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2325;&#2381;&#2352;&#2350;&#2366;&#2306;&#2325;</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2357;&#2367;&#2354;&#2350;&#2381;&#2348;&#2367;&#2340; &#2309;&#2357;&#2343;&#2368;</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2342;&#2339;&#2381;&#2337; &#2325;&#2368; &#2352;&#2366;&#2358;&#2367;</span></td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">01.</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2344;&#2367;&#2352;&#2381;&#2343;&#2366;&#2352;&#2367;&#2340; &#2309;&#2357;&#2343;&#2368; &#2360;&#2375; &#2319;&#2325; &#2360;&#2346;&#2381;&#2340;&#2366;&#2361; &#2325;&#2368; &#2309;&#2357;&#2343;&#2368; &#2340;&#2325;</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2349;&#2369;&#2327;&#2340;&#2375;&#2351; &#2352;&#2366;&#2358;&#2367; &#2325;&#2366; &#49; &#2346;&#2381;&#2352;&#2340;&#2367;&#2358;&#2340; </span></td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">02.</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2344;&#2367;&#2352;&#2381;&#2343;&#2366;&#2352;&#2367;&#2340; &#2309;&#2357;&#2343;&#2368; &#2360;&#2375; &#2342;&#2379; &#2360;&#2346;&#2381;&#2340;&#2366;&#2361; &#2325;&#2368; &#2309;&#2357;&#2343;&#2368; &#2340;&#2325;</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2349;&#2369;&#2327;&#2340;&#2375;&#2351; &#2352;&#2366;&#2358;&#2367; &#2325;&#2366; &#50; &#2346;&#2381;&#2352;&#2340;&#2367;&#2358;&#2340; </span></td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">03.</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2344;&#2367;&#2352;&#2381;&#2343;&#2366;&#2352;&#2367;&#2340; &#2309;&#2357;&#2343;&#2368; &#2360;&#2375; &#2319;&#2325; &#2350;&#2366;&#2361; &#2325;&#2368; &#2309;&#2357;&#2343;&#2368; &#2340;&#2325;</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2349;&#2369;&#2327;&#2340;&#2375;&#2351; &#2352;&#2366;&#2358;&#2367; &#2325;&#2366; &#51; &#2346;&#2381;&#2352;&#2340;&#2367;&#2358;&#2340; </span></td>
                                 </tr>
                                 <tr>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">04.</span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2344;&#2367;&#2352;&#2381;&#2343;&#2366;&#2352;&#2367;&#2340; &#2309;&#2357;&#2343;&#2368; &#2360;&#2375; &#2342;&#2379; &#2350;&#2366;&#2361; &#2325;&#2368; &#2309;&#2357;&#2343;&#2368; &#2340;&#2325; </span></td>
                                     <td style="padding-bottom:5px;font-size:12px;"><span style="font-weight:bold;">&#2349;&#2369;&#2327;&#2340;&#2375;&#2351; &#2352;&#2366;&#2358;&#2367; &#2325;&#2366; &#53; &#2346;&#2381;&#2352;&#2340;&#2367;&#2358;&#2340; </span></td>
                                 </tr>
                             </table>
                            <br/>
                             <table width="100%">
                                <tr>
                                    <td>
                                    &#2309;&#2340;&#2319;&#2357; &#2310;&#2346; &#2325;&#2352; &#2325;&#2366; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#2360;&#2360;&#2350;&#2351; &#2325;&#2352;&#2344;&#2366; &#2360;&#2369;&#2344;&#2367;&#2358;&#2381;&#2330;&#2367;&#2340; &#2325;&#2352;&#2375;&#2306;&#2404; <br />
                                    <strong>&#42;&#2342;&#2339;&#2381;&#2337; &#2352;&#2366;&#2358;&#2367; &#2325;&#2366; &#2327;&#2339;&#2344;&#2366; &#2349;&#2369;&#2327;&#2340;&#2366;&#2344; &#2325;&#2375; &#2360;&#2350;&#2351; &#2344;&#2367;&#2352;&#2381;&#2343;&#2366;&#2352;&#2367;&#2340; &#2325;&#2367;&#2351;&#2366; &#2332;&#2366;&#2319;&#2327;&#2366;&#2404; </strong></td>
                                </tr>
                                <tr>
                                    <td align="center"><br/>&#2360;&#2326;&#2381;&#2340; &#2340;&#2366;&#2325;&#2368;&#2342; &#2360;&#2350;&#2333;&#2366; &#2332;&#2366;&#2319;&#2404; </td>
                                </tr>
                                
                             </table>
                             <table width="100%">
                                <tr>
                                    <td width="60%"><br/><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="40%" align="center"><p style="position: relative;">
                                    <img src="<?=base_url();?>/public/assets/img/notice_signature.png" style="position: absolute;bottom: 24px;max-height: 70px;" alt="rmc">
                                    &#2313;&#2346;&#2344;&#2327;&#2352; &#2310;&#2351;&#2369;&#2325;&#2381;&#2340;<br />
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
