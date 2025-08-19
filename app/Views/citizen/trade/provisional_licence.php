<!DOCTYPE html>
<html>

<head>

    <link href="<?= base_url(); ?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?= base_url(); ?>/public/assets/plugins/pace/pace.min.js"></script>
    <style>
        #print_watermark {
            background-color: #FFFFFF;
            /* background-image:url(<?= base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important; */
            background-image: url(<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_mstr_name['ulb_mstr_id']; ?>.png) !important;
            background-repeat: no-repeat;
            background-position: center;

        }

        li {
            font-size: 12px !important;
            font-family: Arial, Helvetica, sans-serif;
        }

        #printable * {
            color: #B9290A;
            font-size: 12px !important;
        }

        #list tr :nth-child(1) {
            width: 0.5rem;
            margin-right: 1pex;
        }
    </style>
    <style>
        @media print {
            #content-container {
                padding-top: 0px;
            }

            #print_watermark {
                background-image: url(<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_mstr_name['ulb_mstr_id']; ?>.png) !important;
                background-repeat: no-repeat !important;
                background-position: center !important;
                -webkit-print-color-adjust: exact;
            }

            #printable * {
                color: #B9290A !important;
                /* font-size: 12px !important; */
                font-family: Arial, Helvetica, sans-serif !important;
            }

        }
    </style>
</head>

<body>
    <div id='printable'>

        <div id="page-content" style="border: 2px dotted ; margin:2%;padding:1%; ">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel">

                        <!-- ======= Cta Section ======= -->
                        <div class="panel panel-dark">

                            <div class="panel-body" id="print_watermark">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10" style="text-align: center;">
                                    <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png'); ?>'>
                                </div>
                                <table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;">

                                    <tbody>
                                        <tr>
                                            <td height="71" colspan="4" align="center">

                                                <div style="width: 90%; padding: 8px; height: auto; font-family: Arial, Helvetica, sans-serif; font-size: 18px;color:#B9290A ">
                                                    <strong style="text-transform: uppercase;"><?= $ulb_mstr_name["ulb_name"]; ?></strong> <br />
                                                    <strong>Provisional Municipal Trade License</strong><br />
                                                    <label style="font-size:14px;color:#B9290A;">(This Certificate relates to Section 155 (i) and 455 (i) Under Jharkhand Municipal Act of 2011)<br /></label>
                                                </div>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"> Application No : <span style="font-weight:bold;"><?= $basic_details['application_no']; ?></span></td>
                                            <td>Provisional License No : <span style="font-weight:bold;"><?= $basic_details['provisional_license_no']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                <div>Apply Date : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($basic_details['apply_date'])); ?></span> </div>

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <br>
                                <table width="100%" border="0">
                                    <tbody>
                                        <tr>
                                            <td>Mr / Mrs / Miss . : &nbsp;
                                                <span style="font-weight:bold;"><?= $basic_details['owner_name']; ?></span>
                                            </td>
                                            <td rowspan="4" align="left">
                                                <img style="margin-right:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/' . $ss); ?>'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td cols=2>
                                                in the : <span style="font-weight:bold;"><?= $ulb_mstr_name['ulb_name']; ?> </span> Municipal Area
                                            </td>

                                        </tr>

                                        <tr>
                                            <td cols=2>
                                                Firm / organization name : <span style="font-weight:bold;"><?= $basic_details['firm_name']; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td cols=2>Ward No. : <span style="font-weight:bold;"><?= $ward['ward_no']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td cols=2>Business Address : <span style="font-weight:bold;">
                                                    <?= strtoupper($basic_details['address']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td cols=2>For defined Fee : <span style="font-weight:bold;"><?= $tranProvDtl['paid_amount']; ?></span></td>
                                        </tr>
                                        <tr>
                                            <td cols=2>Having receipt no : <span style="font-weight:bold;">
                                                    <?= $tranProvDtl['transaction_no']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td cols=2>Establishment Date : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($basic_details['establishment_date'])); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td cols=2>Valid Upto : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($valid_upto)); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td cols=2>Subject to the following terms, license is granted.</td>
                                        </tr>


                                    </tbody>
                                </table>
                                <br>
                                <table id='list' width="99%" border="0" style="font-family: Arial, Helvetica, sans-serif; ">
                                    <tr>
                                        <td cosl=2>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td>1.</td>
                                        <td>Business will run according to licence issued.</td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Prior Permission from local body is necessary if business is changed.</td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Information to local body is necessary for extension of area.</td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>Prior information to local body regarding winding of business is necessary.</td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>Application for renewal of license is necessary one month before expiry of license.</td>
                                    </tr>
                                    <tr>
                                        <td>6.</td>
                                        <td>In the case of delay penalty will be levied according to section 459 of Jharkhand Municipal Act 2011.</td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>Illegal Parking in front of firm in non-permissible.</td>
                                    </tr>
                                    <tr>
                                        <td>8.</td>
                                        <td>Sufficient number of containers for disposing-garbage and refuse shall be made available within.</td>
                                    </tr>
                                    <tr>
                                        <td>9.</td>
                                        <td>The premises and the licensee will co-operate with the ULB for disposal of such waste.</td>
                                    </tr>
                                    <tr>
                                        <td>10.</td>
                                        <td>SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit.</td>
                                    </tr>
                                    <tr>
                                        <td>11.</td>
                                        <td>This provisional license is valid for 20 days from the date of apply . In case of no-objection from
                                            <strong><?= $ulb_mstr_name['ulb_name']; ?></strong>
                                            ,The license shall be deemed approved.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12.</td>
                                        <td>The final license can be downloaded from<span style="font-size:12px;color: #980601"> www.modernulb.com</span></td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="left" style="font-size:12px;color: #980601">
                                            <p></p>For More Details Please Visit : <?=receipt_url()?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td>Note: This is a computer generated Licence. This Licence does not require a physical signature.</td>
                                    </tr>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="print">
        <div class="col-sm-12 noprint text-right mar-top" style="text-align: center;">
            <button class="btn btn-mint btn-icon" onclick="printDiv('printable')" style="height:40px;width:60px;">PRINT</button>
        </div>
    </div>
</body>
<script type="text/javascript">
    function printDiv(divName) 
    {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        //document.getElementById('print').remove();
        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>

</html>