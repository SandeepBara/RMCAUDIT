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
                /* size: A4; */
            }
            body {
                padding-top: 30px;
                padding-bottom: 5px;
                background: #FFFFFF
            }

            * {
                -webkit-print-color-adjust: exact !important;
                /* Chrome, Safari */
                color-adjust: exact !important;
                /*Firefox*/
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
                        <table style="width: 100%; font-weight: 300;">
                            <tr>
                                <td style="width: 60%; font-size: 18px;">
                                   <span style="font-size:16px">Holding No.</span> : <span style="font-weight:600;font-size:16px"><?=$holding_no;?></span>
                                </td>
                                <td style="width: 40%; font-size: 16px;">
                                    <span style="font-size:16px">Date : </span><span style="font-weight:600;font-size:16px"><?=date ("d-m-Y");?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%; font-size: 16px;">
                                    <span style="font-size:16px">New Holding No. : </span><span style="font-weight:600;font-size:16px"><?=($new_holding_no=="")?"N/A":$new_holding_no;?></span>
                                </td>
                                <td style="width: 40%; font-size: 16px;">
                                    <span style="font-size:16px">Ward No. : </span><span style="font-weight:600;font-size:16px"><?=$ward_no;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%; font-size: 16px;">
                                    Department / Section: Revenue Section
                                </td>
                                <td style="width: 40%; font-size: 16px;">
                                    <span style="font-size:16px">New Ward No. : </span><span style="font-weight:600;font-size:16px"><?=($new_ward_no=="")?"N/A":$new_ward_no;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%; font-size: 16px;">
                                    Account Description: Holding Tax & Others
                                </td>
                                <td style="width: 40%; font-size: 16px;">
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-weight: 300;margin-top:15px;">
                            <tr>
                                <td style="width: 60%; font-size: 16px;">
                                    <span style="font-size:16px">Owner name : </span><span style="font-weight:600;font-size:16px"><?=$owner_name;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 60%; font-size: 16px;">
                                  
                                    <span style="font-size:16px">Address : </span><span style="font-weight:600;font-size:16px"><?=$prop_address;?></span>
                                </td>
                            </tr>
                        </table>
                        <table id="data_table_view" style="width: 100%; font-weight: 300;margin-top:15px;">
                            <tr id="data_table_view_first_row">
                                <td >
                                    Demand From
                                </td>
                                <td >
                                    Demand Upto
                                </td>
                                <td >
                                    Demand (In Rs.)
                                </td>
                                <td >
                                    RWH Penalty (In Rs.)
                                </td>
                                <td >
                                    Already Paid (In Rs.)
                                </td>
                                <td >
                                    Total (In Rs.)
                                </td>
                            </tr>
                            <tr >
                                <td >
                                    <?=$demand_from_qtr." / ".$demand_from_fy;?>
                                </td>
                                <td >
                                    <?=$demand_upto_qtr." / ".$demand_upto_fy;?>
                                </td>
                                <td >
                                    <?=$t_balance;?>
                                </td>
                                <td >
                                    <?=$additional_amount;?>
                                </td>
                                <td >
                                    <?=$payment_dtl["AdvanceAmount"];?>
                                </td>
                                <td >
                                    <?=$t_balance;?>
                                </td>
                            </tr>
                            <?php $total_payable_amount = round(($payment_dtl["OnePercentPnalty"]+$t_balance)-$payment_dtl["RebateAmount"]-$payment_dtl["AdvanceAmount"]); ?>
                            <tr>
                                <td colspan="2" style="font-size: 16px; text-align: right;font-weight:600">Total</td>
                                <td style="font-weight:600"><?=$t_balance;?></td>
                                <td style="font-weight:600"><?=$additional_amount;?></td>
                                <td style="font-weight:600"><?=$payment_dtl["AdvanceAmount"];?></td>
                                <td style="font-weight:600"><?=$t_balance;?></td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="2" >1% Interest</td>
                                <td colspan="4" >Rs. <?=$payment_dtl["OnePercentPnalty"];?></td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="2" >Rebate </td>
                                <td colspan="4" >Rs. <?=$payment_dtl["RebateAmount"];?></td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="2" >Total Payable</td>
                                <td colspan="4" >Rs. <?=($total_payable_amount>=0)?$total_payable_amount:0;?>.00</td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="2" >Total Demand (in words)</td>
                                <td colspan="4" ><?=ucwords(getIndianCurrency(($total_payable_amount>=0)?$total_payable_amount:0));?> Only.</td>
                            </tr>
                            <!-- <tr style="font-weight:600">
                                <td colspan="6" >Note: This demand valid upto 30th June 2022 only.</td>
                            </tr> -->
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