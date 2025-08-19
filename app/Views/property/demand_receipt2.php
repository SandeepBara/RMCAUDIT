<!DOCTYPE html>
<html lang="en-US">
<?php
    $sesion = session();
    $ulb_dtl = $sesion->get("ulb_dtl");
    //print_var($ulb_dtl);
    //die();
    $ulb_exp = explode(' ', trim($ulb_dtl['ulb_name']));
    $ulb_short_nm = $ulb_exp[0];
?>
<head>
    <title>Self Assessment Memo </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="<?= base_url(); ?>/public/assets/img/favicon.ico">
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        @media print {
            #content-container {
                padding-top: 0px;
            }

            /* .row {
                background-image: url(<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_dtl["ulb_mstr_id"]; ?>.png) !important;
                background-repeat: no-repeat !important;
                background-position: center !important;
                -webkit-print-color-adjust: exact;
               
               
            } */

            .no-print {
                display: none;
            }
            @page {size: landscape}
        }
        #data_table_view, #data_table_view th, #data_table_view td {
                border: 1px solid black !important;
                border-collapse: collapse !important;
}
#data_table_view td{
    padding : 10px;
}
#data_table_view_first_row td{
    font-weight: 600;
}
.logo_img{
    position: absolute;
    top: 30%;
    left: 35%;
    z-index: -1;
    opacity: 0.5;
  
}
    </style>
    <style type="text/css" media="print">
        @media print {
            /* For Remove Header URL */
            @page {
                margin-top: 0;
                margin-bottom: 0;
                size: landscape;
                size: A4;
            }
            body {
                padding-top: 30px;
                padding-bottom: 5px;
                background: #FFFFFF;
                /* border : 3px dotted */
            }

            * {
                -webkit-print-color-adjust: exact !important;
                /* Chrome, Safari */
                color-adjust: exact !important;
                /*Firefox*/
            }
            .textsm{
                font-size: 10px;
            }
            .logo_img{
                position: absolute;
                top: 35%;
                left: 30%;
                z-index: -1;
                opacity: 0.3;
            
            }
        }
        td {
            font-size: 16px;
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
    <div id="content-container text-center" style="margin-top:-30px">
        <!--Page content-->
        <div id="page-content">
            <div class="panel panel-bordered panel-dark col-md-8 col-md-offset-2">
                <div class="panel-body" id="print_watermark" style="width: 350mm; height: auto; margin: auto;  background: transparent; ">
               <img src="<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_dtl["ulb_mstr_id"]; ?>.png" alt="" class="logo_img">
                    <div class="row" style="border: 3px;  padding: 20px;">
                        <table width="100%">
                            <tr>
                                <td id="ulb_name_st" style="width: 100%; text-align: center; font-weight: bold; font-size: 30px;opacity:0.5">
                                    <?= $ulb_dtl['ulb_name'] . ', ' . $ulb_short_nm; ?>
                                </td>
                            </tr>
                        </table>
                        <br /><br />
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 100%; text-align: center;">
                                    <span style="border: 3px; border-style: solid; padding: 10px 50px; font-weight: 600; font-size: 20px;">
                                        Property Tax Demand
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <br />
                        <table style="width: 100%; font-weight: 300; font-size:25px">
                            <tr>
                                <td style="width: 60%;">
                                   <span >Holding No.</span> : <span style="font-weight:600;"><?=$holding_no;?></span>
                                </td>
                                <td style="width: 40%;">
                                    <span >Date : </span><span style="font-weight:600;"><?=date ("d-m-Y");?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%; ">
                                    <span>New Holding No. : </span><span style="font-weight:600;"><?=($new_holding_no=="")?"N/A":$new_holding_no;?></span>
                                </td>
                                <td style="width: 40%; ">
                                    <span >Ward No. : </span><span style="font-weight:600;"><?=$ward_no;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%; ">
                                    Department / Section: Revenue Section
                                </td>
                                <td style="width: 40%; ">
                                    <span >New Ward No. : </span><span style="font-weight:600;"><?=($new_ward_no=="")?"N/A":$new_ward_no;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%;">
                                    Account Description: Holding Tax & Others
                                </td>
                                <td style="width: 40%;">
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-weight: 300;margin-top:15px;font-size:25px">
                            <tr>
                                <td style="width: 60%;">
                                    <span >Owner name : </span><span style="font-weight:600;"><?=$owner_name;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%;">                                  
                                    <span >Address : </span><span style="font-weight:600;"><?=$prop_address;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%;">                                  
                                    <span >Mobile No : </span><span style="font-weight:600;"><?=$mobile_no?></span> 
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%;">
                                <span >Email :-</span><span style="font-weight:600;"><?=$email?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%;">
                                <i class="text-sm">[To Update please call free 18008904115 / please visit this link <strong><?=base_url("/grievance_new/login")?> </strong> for grievance ]</i>
                                </td>
                            </tr>
                        </table>
                        <table id="data_table_view" style="width: 100%; font-weight: 300;margin-top:15px;">
                            <tr id="data_table_view_first_row">
                                <td colspan="2">Demand</td>
                                <td rowspan="2">
                                    Quarterly Tax
                                </td>
                                <td rowspan="2">
                                    Rainwater Harvesting Tax
                                </td>
                                <td rowspan="2">
                                    Total Quarterly Tax
                                </td>
                                <td rowspan="2">
                                    Total Qtr
                                </td>
                                <td rowspan="2">
                                    Total Dues
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    Demand From
                                </td>
                                <td >
                                    Demand Upto
                                </td>
                            </tr>
                            <?php 
                                if($arrear){
                                    ?>
                                    <tr>
                                        <td >
                                            <?=$arrear["demand_from_qtr"]." / ".$arrear["demand_from_fy"];?>
                                        </td>
                                        <td >
                                            <?=$arrear["demand_upto_qtr"]." / ".$arrear["demand_upto_fy"];?>
                                        </td>
                                        <td align="right">
                                            <?=$arrear["qtr_tax"]/$arrear["total_qtr"];?>
                                        </td>
                                        <td align="right">
                                            <?=$arrear["additional_amount"]/$arrear["total_qtr"];?>
                                        </td>
                                        <td align="right">
                                            <?=$arrear["balance"]/$arrear["total_qtr"];?>
                                        </td>
                                        <td align="right">
                                            <?=$arrear["total_qtr"];?>
                                        </td>
                                        <td align="right">
                                            <?=round($arrear["balance"]*1.00,2);?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                if($current){
                                    ?>
                                    <tr>

                                        <td >
                                            <?=$current["demand_from_qtr"]." / ".$current["demand_from_fy"];?>
                                        </td>
                                        <td >
                                            <?=$current["demand_upto_qtr"]." / ".$current["demand_upto_fy"];?>
                                        </td>
                                        <td align="right">
                                            <?=$current["qtr_tax"]/$current["total_qtr"];?>
                                        </td>
                                        <td align="right" >
                                            <?=$current["additional_amount"]/$current["total_qtr"];?>
                                        </td>
                                        <td align="right" >
                                            <?=$current["balance"]/$current["total_qtr"];?>
                                        </td>
                                        <td align="right">
                                            <?=$current["total_qtr"];?>
                                        </td>
                                        <td align="right">
                                            <?=round($current["balance"]*1.00,2);?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            
                            ?>
                            <tr>
                                <td colspan="4" align="right"> <i class="text-sm" style="color:red">* Total Quarterly Tax x Total Quarter = Total Dues</i></td>
                                <td colspan="2" align="right">Total Demand</td>
                                <td align="right"><?=round($t_balance*1.00,2);?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">1% Interest</td>
                                <td align="right"><?=round($payment_dtl["OnePercentPnalty"]*1.00,2);?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">Notice Penalty as per Rule <span class="textsm">झारखंड नगरपालिका कर भुगतान ( समय,प्रक्रिया तथा वसूली ) विनिमय,- 2017 के नियम 3.1.4</span></td>
                                <td align="right"><?=round($payment_dtl["noticePenalty"]*1.00,2);?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">2% Addition Penalty </td>
                                <td align="right"><?=round($payment_dtl["noticePenaltyTwoPer"]*1.00,2);?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">Less Special Rebate</td>
                                <td align="right"><?=$payment_dtl["RebateBifurcation"]["SpecialRebateAmount"]>0 ? round($payment_dtl["RebateBifurcation"]["SpecialRebateAmount"]*1.00,2) : 0.00;?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">Less JSK/Online Rebate</td>
                                <td align="right"><?=($payment_dtl["RebateBifurcation"]["onlineRebate"] + $payment_dtl["RebateBifurcation"]["jskRebate"]) >0 ? round(($payment_dtl["RebateBifurcation"]["onlineRebate"] + $payment_dtl["RebateBifurcation"]["jskRebate"])*1.00,2) : 0.00;?></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">Less First Qtr Rebate</td>
                                <td align="right"><?=$payment_dtl["RebateBifurcation"]["firstQtrRebate"]>0 ? round($payment_dtl["RebateBifurcation"]["firstQtrRebate"]*1.00,2) : 0.00;?></td>
                            </tr>                            
                            <tr>
                                <td colspan="4" align="right"> </td>
                                <td colspan="2" align="right">Less Total Advance</td>
                                <td align="right"><?=round($payment_dtl["AdvanceAmount"]*1.00,2);?></td>
                            </tr>
                            
                            <tr>
                                <td colspan="4" align="right"></td>
                                <td colspan="2" align="right">Grand Total Demand</td>
                                <td align="right"><?=round(($payment_dtl["PayableAmount"])*1.00);?></td>
                            </tr>
                            <tr>
                                <td colspan="2" >Total Demand <br>(in words)</td>
                                <td colspan="5" ><?=ucwords(getIndianCurrency(round($payment_dtl["PayableAmount"])));?> Only.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-1 noprint" align="center" style="margin-top: 20px;">
        <button class="btn btn-mint btn-icon" onclick="printDiv('print_watermark')"><i class="demo-pli-printer icon-lg"></i> PRINT</button>
    </div>
</body>

</html>