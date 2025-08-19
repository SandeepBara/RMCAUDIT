<!DOCTYPE html>
<html>

<head>

    <link href="<?= base_url(); ?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>/public/assets/plugins/pace/pace.min.css" rel="stylesheet">
    <script src="<?= base_url(); ?>/public/assets/plugins/pace/pace.min.js"></script>

    <style>
        @media print {
            @page{
                margin: 3mm;
            }
            #content-container {
                padding-top: 0px;
                padding: auto;
            }

            #page-content {
                padding-top: 0px;
                padding: auto 1rem;
            }

            #print_watermark {
                background-image: url(<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_mstr_id; ?>.png) !important;
                background-repeat: no-repeat !important;
                background-position: center !important;
                -webkit-print-color-adjust: exact;
            }
        }

        #print_watermark {
            background-color: #FFFFFF;
            background-image: url(<?= base_url(); ?>/public/assets/img/logo/<?= $ulb_mstr_id; ?>.png) !important;
            background-repeat: no-repeat;
            background-position: center;

        }

        #page-content {
            padding-top: 0px;
            padding: auto 1rem;
        }

        #num_table {
            font-size: 1rem !important;
            margin-left: 1rem;
        }

        #num_table td {

            padding: 0.5 1rem;
        }
    </style>
</head>

<body>
    <!--DataTables [ OPTIONAL ]-->
    <!--CONTENT CONTAINER-->
    <!--===================================================-->
    <div id="content-container">
        <div id="page-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel">

                        <!-- ======= Cta Section ======= -->
                        <div class="panel panel-dark">

                            <div class="panel-body" id="print_watermark">
                                <div class="panel-body" style="border: solid 2px black;outline-style: dotted;">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-10" style="text-align: center;">
                                        <!-- <img style="height:80px;width:80px;" src='<?php echo base_url('public/assets/img/logo1.png'); ?>'> -->
                                    </div>

                                    <div class="col-sm-12" style="text-align: center; text-transform: uppercase; font-size: 33px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; line-height: 60px;">
                                        <!-- <?= $ulb["ulb_name"]; ?> -->
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="col-sm-8">

                                        </div>
                                        <div class="">
                                        </div>
                                    </div>
                                    <table width="99%" border="0" align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color:#B9290A;">

                                        <tbody>
                                            <tr>
                                                <td height="71" colspan="4" align="center">
                                                    <label style="font-size:14px;color:#B9290A;"><strong>Municipal Trade Licence Approval Certificate</strong></label><br />
                                                    <label style="font-size:14px;color:#B9290A;"><strong><?= strtoupper($ulb['ulb_name']); ?></strong></label><br />
                                                    <label style="font-size:14px;color:#B9290A;"><strong>Municipal License</strong></label><br />
                                                    <label style="font-size:14px;color:#B9290A;">(This certificate relates to Section 455(i) Jharkhand Municipal Act 2011)</label><br />
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Municipal Trade Licence Number : <span style="font-weight:bold;"><?= $basic_details['license_no']; ?></span></td>
                                                <td rowspan="4">
                                                    <img style="margin-right:0px;width:100px;height:100px;" src='<?php echo base_url('writable/uploads/qrCodeGenerator/' . $ss); ?>'>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        Issue date of Municipal Trade Licence Number : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($basic_details['license_date'])); ?></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Validity of Municipal Trade Licence Number : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($basic_details['valid_upto'])); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Occupancy certificate no : </td>
                                            </tr>

                                            <tr>
                                                <td>Owner/ Entity Name : <span style="font-weight:bold;"><?= $basic_details['firm_name']; ?></span></td>
                                                <td>Ward No. : <span style="font-weight:bold;"><?= $ward['ward_no']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Owner Name of Entity : <span style="font-weight:bold;"> <?= $basic_details['owner_name']; ?> </span></td>
                                                <td>Holding No. : <span style="font-weight:bold;"><?= $basic_details['holding_no'] ?? "N/A"; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td width="700px">Nature of Entity : <span style="font-weight:bold;"><?= $basic_details['brife_desp_firm']; ?> </span></td>
                                                <td>Street Address : <span style="font-weight:bold;"><?= ($basic_details['address'].($basic_details['landmark']??"").($basic_details['pin_code']??"")); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Ownership of business premises : <span style="font-weight:bold;"> <?= $basic_details['premises_owner_name']; ?> </span></td>
                                                <td>Application No. : <span style="font-weight:bold;"><?= $basic_details['application_no']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Business code : <span style="font-weight:bold;">(<?= $basic_details['nature_of_bussiness']; ?> )</span></td>
                                                <td>Date & time of Application : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($basic_details['apply_date'])); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>Date of establishment : <span style="font-weight:bold;"><?= date('d-m-Y', strtotime($basic_details['establishment_date'])); ?></span></td>
                                                <td>Mobile No. : <span style="font-weight:bold;"> <?= $basic_details['mobile']; ?> </span></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <br>
                                    <table width="100%" border="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem !important;color:#B9290A;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    This is to declare that <b><?= $basic_details['firm_name']; ?></b>
                                                    having application number <b><?= $basic_details['application_no']; ?></b>
                                                    has been successfully registered with us with satisfactory compliance of registration criteria and to certify that trade licence number
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b><?= $basic_details['license_no']; ?></b>
                                                    has been allocated to <b><?= $basic_details['firm_name']; ?></b> for conducting business which is (
                                                    <?php
                                                    if (isset($item_details)) :
                                                        if (empty($item_details)) :
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else :
                                                            $tradecodeArray = [];
                                                            foreach ($item_details as $value) {
                                                                $tradecodeArray[] = $value['trade_code'];
                                                            }
                                                            echo implode(', ', $tradecodeArray);
                                                        endif;
                                                    endif;  ?>
                                                    )
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    as per business code mentioned in Jharkhand Municipal Act 2011 in
                                                    the regime of this local body.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    The validity of this subject to meeting the terms and conditions as specified in U/S 455 of Jharkahnd

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Municipal Act 2011 and other applicable sections in the act along with following terms and conditions:-
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table><br>
                                    <div class="col-sm-12" style="font-family: Arial, Helvetica, sans-serif;color:#B9290A; font-size: 1rem !important;">

                                        <table style="color:#B9290A;" id='num_table'>
                                            <tr>
                                                <td>1</td>
                                                <td>Business will run according to licence issued.</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Prior Permission from local body is necessary if business is changed.</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Information to local body is necessary for extension of area.</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Prior Information to local body regarding winding of business is necessary.</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Application for renewal of licence is necessary one month before expiry of licence.</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>In case of delay penalty will be levied according to rule 259 of Jharkhand Municipal Act 2011.</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Illegal Parking in front of firm in non-permissible.</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Sufficient number of containers for disposing-garbage and refuse shall be made available within the premises and the licence will co-operate with the ULB for disposal of such waste.</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>SWM Rules, 2016 and Plastic Waste Management Rules 2016 shall be adhered to in words as well as spirit.</td>
                                            </tr>
                                        </table>
                                    </div><br>

                                    <div style="width: 99%; margin: auto; line-height: 35px; color:#B9290A; position: relative;">
                                        <div style="position: absolute; right: 0; top: -131px; text-align: center;">
                                            <img src="<?= $signature_path; ?>" style="width: 100px; height: 100px; display: block; margin-bottom: 5px;">
                                            <span>Signature :</span> 
                                        </div>
                                        <strong style="font-size: 14px;">Note: This is a computer generated Licence. This Licence does not require a physical signature.</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-xl-12 noprint text-center" style="text-align: center;margin-top:0.5rem;">
    <button class="btn btn-mint btn-icon btn-primary" id="btnPrint" onclick="printData()" style="color: #20bee2; background-color: #869a9f;">print</button>
    </div>
</body>
<script>
    function printData() {
        document.getElementById("btnPrint").style.display="none";
        var divToPrint = document.getElementById("content-container");
        divToPrint.style.color = 'color:#B9290A;';
        window.print(divToPrint);
        window.close();
        document.getElementById("btnPrint").style.display="inline-block";
    }
</script>

</html>