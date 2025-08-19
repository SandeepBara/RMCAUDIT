<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->

<style>
    .row {
        line-height: 25px;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin-top: -760px;
        margin-left: 238px;
        padding: 0;
        border: 1px solid #888;
        width: 80%;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s;
        text-align: initial;
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }

        to {
            top: 0;
            opacity: 1
        }
    }

    @keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }

        to {
            top: 0;
            opacity: 1
        }
    }

    /* The Close Button */
    .close {
        color: black;
        float: right;
        font-size: 16px;
        margin-top: 5px !important;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-header {
        padding: 2px 16px;
        color: white;
    }

    .modal-body {
        padding: 2px 16px;
    }

    .modal-footer {
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
    }

    /* print  */
    @media print {
        #print_watermark {
            background-image: url(<?= base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            -webkit-print-color-adjust: exact;
        }
    }

    #print_watermark {
        background-color: #FFFFFF;
        background-image: url(<?= base_url(); ?>/public/assets/img/rmcWATERlogo.png) !important;
        background-repeat: no-repeat;
        background-position: center;

    }

    #botum-button {
        background-color: #ecf0f5 !important;
    }
</style>

<link href="<?= base_url(); ?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->
        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Trade</a></li>
            <li class="active">Trade Application Details</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <form method="post" class="form-horizontal" action="">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Trade Application Status</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center;">
                            <span style="font-weight: bold; font-size: 17px; color: #bb4b0a;"> Your Application No. is <span style="color: #179a07;"><?php echo $basic_details['application_no']; ?></span>. Application Status: <?php echo $application_status; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                        <!-- <a href="<?php echo base_url('trade_da/track_application_no') ?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</a> -->
                    </div>
                    <h3 class="panel-title">Basic Details</h3>
                </div>

                <div class="panel-body">
                    <span style="color: red">
                        <?php
                        if (isset($validation)) {
                        ?>
                            <?= $validation->listErrors(); ?>
                        <?php
                        }
                        ?>
                    </span>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Ward No. :</b>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $ward['ward_no']; ?>
                        </div>

                        <input type="hidden" name="apply_licence_id" value="<?= md5($basic_details['id']); ?>" />
                        <div class="col-sm-3">
                            <b>Holding No. :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $holding['holding_no'] ? $holding['holding_no'] : "N/A"; ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Application No. :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['application_no'] ? $basic_details['application_no'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Application Type :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['application_type'] ? $basic_details['application_type'] : "N/A"; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Licence For :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $holding['licence_for_years'] ? $holding['licence_for_years'] . "  Years" : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Firm Type :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['firm_type'] ? $basic_details['firm_type'] : "N/A"; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Ownership Type :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['ownership_type'] ? $basic_details['ownership_type'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Firm Name :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['firm_name'] ? $basic_details['firm_name'] : "N/A"; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Nature Of Business :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $nature_business['trade_item'] ? $nature_business['trade_item'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Cateogry Type :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $category_type['category_type'] ? $category_type['category_type'] : "N/A"; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>K No :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['k_no'] ? $basic_details['k_no'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Area :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['area_in_sqft'] ? $basic_details['area_in_sqft'] : "N/A"; ?>
                            <input type="hidden" id="area_in_sqft" value="<?= $basic_details['area_in_sqft'] ? $basic_details['area_in_sqft'] : ""; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Account No :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['account_no'] ? $basic_details['account_no'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Firm Establishment Date :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $holding['establishment_date'] ? date('d-m-Y', strtotime($holding['establishment_date'])) : "N/A"; ?>
                            <input type="hidden" id="firm_date" value="<?= $holding['establishment_date'] ? date('d-m-Y', strtotime($holding['establishment_date'])) : "N/A"; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Address :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $holding['address'] ? $holding['address'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Landmark :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $holding['landmark'] ? $holding['landmark'] : "N/A"; ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-------Owner Details-------->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="saf_receive_table" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="thead-light" style="background-color: #e6e6e4;">
                                <tr>
                                    <th scope="col">Owner Name</th>
                                    <th scope="col">Guardian Name</th>
                                    <th scope="col">Mobile No</th>
                                    <th scope="col">Email Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($owner_list)) :
                                    if (empty($owner_list)) :
                                ?>
                                        <tr>
                                            <td style="text-align:center;"> Data Not Available...</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php
                                        $i = 1;
                                        foreach ($owner_list as $value) :

                                            $j = $i++;
                                        ?>
                                            <tr>
                                                <td><?= $value['owner_name'] ? $value['owner_name'] : "N/A"; ?></td>
                                                <td><?= $value['guardian_name'] ? $value['guardian_name'] : "N/A"; ?></td>
                                                <td><?= $value['mobile'] ? $value['mobile'] : "N/A"; ?></td>
                                                <td><?= $value['emailid'] ? $value['emailid'] : "N/A"; ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--------prop doc------------>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Document Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="thead-light" style="background-color: #e6e6e4;">
                                <tr>
                                    <th style="width:160px;">Document Name</th>
                                    <th style="width:160px;">Document Image</th>
                                    <th style="width:160px;">Status</th>
                                </tr>
                            </thead>

                            <body>
                                <?php
                                $cnt = 0;
                                $verifystatus = 0;
                                $rejectedstatus = 0;

                                foreach ($doc_exists as  $value) {
                                ?>
                                    <tr>
                                        <td><?= $value['doc_for']; ?></td>
                                        <td>
                                            <a href="<?= base_url(); ?>/writable/uploads/<?= $value['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="height: 40px;"></a>
                                        </td>
                                        <td>
                                            <?php
                                            if ($value['verify_status'] == "0") {
                                            ?>
                                                <span class='text-warning'>Pending</span>
                                            <?php
                                            } else if ($value['verify_status'] == 1) {
                                            ?>
                                                <span class='text-success'>Verified</span>
                                            <?php
                                            } else if ($value['verify_status'] == 2) {
                                            ?>
                                                <span class='text-danger'>Rejected</span>
                                            <?php
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                <?php
                                }
                                ?>
                            </body>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment Detail</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Processing Fee :</th>
                                        <th>Transaction Date :</th>
                                        <th>Payment Through :</th>
                                        <th>Payment For :</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($payment_dtls)) {
                                    ?>
                                        <tr>
                                            <td colspan="5" class="text-danger text-center">! No Data</td>
                                        </tr>
                                        <?php
                                    } else {
                                        foreach ($payment_dtls as $val) {
                                        ?>
                                            <tr>
                                                <td><?= $val['paid_amount'] ? $val['paid_amount'] : "N/A"; ?></td>
                                                <td><?= $val['transaction_date'] ? $val['transaction_date'] : "N/A"; ?></td>
                                                <td><?= $val['payment_mode'] ? $val['payment_mode'] : "N/A"; ?></td>
                                                <td><?= $val['transaction_type'] ? $val['transaction_type'] : "N/A"; ?></td>
                                                <td>
                                                    <a target="popup" onclick="window.open('<?php echo base_url('tradeapplylicence/viewTransactionReceipt/' . md5($val['id'])); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('tradeapplylicence/viewTransactionReceipt/' . $linkId); ?>" type="button" class="btn btn-primary" style="color:white;">View</a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <!-----level remarks------->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Remarks From Level</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs" style="font-size:13px;font-weight: bold;">
                        <li class="active" style="background-color:#97c78ebd;"><a data-toggle="tab" href="#Dealing_Officer">Dealing Officer </a></li>
                        <li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#tax_daroga">Tax Daroga</a></li>
                        <li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#Section_Head">Section Head</a></li>
                        <li style="background-color:#97c78ebd;"><a data-toggle="tab" href="#executive_officer">Executive Officer</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="Dealing_Officer" class="tab-pane fade in active">
                            <h3></h3>
                            <?php
                            if (isset($dealingLevel)) :
                                foreach ($dealingLevel as $value) :
                            ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Received Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;;">Forwarded Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ? $value['forward_date'] . ' ' . $value['forward_time'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Remarks</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <div id="tax_daroga" class="tab-pane fade">
                            <h3></h3>
                            <?php
                            if (isset($taxDarogaLevel)) :
                                foreach ($taxDarogaLevel as $value) :
                            ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Received Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;;">Forwarded Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ? $value['forward_date'] . ' ' . $value['forward_time'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                            <br />
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Remarks</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <div id="Section_Head" class="tab-pane fade">
                            <h3></h3>
                            <?php
                            if (isset($sectionHeadLevel)) :
                                foreach ($sectionHeadLevel as $value) :
                            ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Received Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;;">Forwarded Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ? $value['forward_date'] . ' ' . $value['forward_time'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                            <br />
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Remarks</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <div id="executive_officer" class="tab-pane fade">
                            <h3></h3>
                            <?php
                            if (isset($executiveLevel)) :
                                foreach ($executiveLevel as $value) :
                            ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-body">
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Received Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;;">Forwarded Date</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ? $value['forward_date'] . ' ' . $value['forward_time'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                            <br />
                                            <div class="col-sm-12">
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">Remarks</b>
                                                </div>
                                                <div class="col-sm-3">
                                                    <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                                    <br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-----end level remarks------->
            <!-----link------->

            <div class="panel  panel-dark" id="botum-button">
                <div class="panel-body">
                    <div class="row">
                        <?php
                        if ($basic_details['application_type_id'] != 4) {
                            if ($licencee['payment_status'] == 0) {
                        ?>
                                <!-- <button class="btn btn-primary btn-sm" type="button" onclick="chequeDetailsData()">View</button> -->
                                <button data-target="#demo-lg-modalss" data-toggle="modal" class="btn btn-warning" type="button">View</button>

                            <?php
                            }
                            if ($basic_details['pending_status'] == 5) {
                            ?>
                                <a href="<?php echo base_url('Trade_DA/municipalLicence/' . $linkId); ?>" target="popup" type="button" class="btn btn-primary" style="color:white;" onclick="window.open('<?php echo base_url('Trade_DA/municipalLicence/' . $linkId); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;">
                                    View Trade Licence
                                </a>
                            <?php
                            } else {
                            ?>
                                <a target="popup" onclick="window.open('<?php echo base_url('tradeapplylicence/provisionalCertificate/' . $linkId); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('tradeapplylicence/provisionalCertificate/' . $linkId); ?>" type="button" class="btn btn-primary" style="color:white;">View Provisional Certificate</a>
                        <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
            <!-----end link------->
        </form>
        <!-- check bounce payment -->
        <div class="panel  panel-dark" id="model" style="display: '';">
            <div class="panel-body">
                <div class="row">
                    <form action="<?php echo base_url(); ?>/Trade_report/check_bounce_payment/<?= md5($licence_dtl['id']) ?>" method='post'>
                        <?php
                        //print_var($application_type);
                        if ($application_type["id"] <> 4) {
                        ?>
                            <div class="panel panel-bordered panel-dark">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Licence Required for the Year</h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    # Renewal
                                    if ($application_type["id"] == 2) {
                                    ?>
                                        <div class="row">
                                            <label class="col-md-2">License Expire</label>
                                            <div class="col-md-3 pad-btm"> <b> <?= $licence_dtl['valid_upto']; ?> </b> </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="row">
                                        <label class="col-md-2">License For<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <?php

                                            if ($application_type["id"] == 3) {
                                            ?>
                                                <select id="licence_for" name="licence_for" class="form-control" onclick="show_charge()">
                                                    <option value="1">1 Year</option>
                                                </select>
                                            <?php
                                            } else {
                                            ?>
                                                <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()">
                                                    <option value="">--Select--</option>
                                                    <option value="1">1 Year</option>
                                                    <option value="2">2 Year</option>
                                                    <option value="3">3 Year</option>
                                                    <option value="4">4 Year</option>
                                                    <option value="5">5 Year</option>
                                                    <option value="6">6 Year</option>
                                                    <option value="7">7 Year</option>
                                                    <option value="8">8 Year</option>
                                                    <option value="9">9 Year</option>
                                                    <option value="10">10 Year</option>
                                                </select>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <label class="col-md-2">Charge Applied<span class="text-danger">*</span></label>

                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="charge" disabled="disabled" class="form-control" value="<?php echo $rate ?? 0; ?>" onkeypress="return isNum(event);" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-2">Penalty<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="penalty" disabled="disabled" class="form-control" value="<?php echo $penalty ?? 0; ?>" onkeypress="return isNum(event);" />
                                        </div>

                                        <label class="col-md-2">Denial Amount<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="denialAmnt" disabled="disabled" class="form-control" value="0" onkeypress="return isNum(event);" required />
                                        </div>
                                    </div>


                                    <div class="row">

                                        <label class="col-md-2">Total Charge<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="total_charge" disabled="disabled" class="form-control" value="<?php echo $total_charge ?? 0; ?>" onkeypress="return isNum(event);" min="299" required />
                                        </div>


                                        <label class="col-md-2">Payment Mode<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()">
                                                <option value="">Choose...</option>
                                                <option value="CASH">CASH</option>
                                                <option value="CHEQUE">CHEQUE</option>
                                                <option value="DEMAND DRAFT">DEMAND DRAFT</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" id="chqno" style="display: none;">
                                        <label class="col-md-2">Cheque/DD Date<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="date" class="form-control" id="chq_date" name="chq_date" value="<?= date("Y-m-d") ?>" placeholder="Enter Cheque/DD Date">
                                        </div>
                                        <label class="col-md-2">Cheque/DD No.<span class="text-danger">*</span></label>

                                        <div class="col-md-3 pad-btm">
                                            <input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No.">
                                        </div>
                                    </div>
                                    <div class="row" id="chqbank" style="display: none;">
                                        <label class="col-md-2">Bank Name<span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder=" Enter Bank Name">
                                        </div>
                                        <label class="col-md-2">Branch Name<span class="text-danger">*</span></label>

                                        <div class="col-md-3 pad-btm">
                                            <input type="text" class="form-control" id="branch_name" name="branch_name" value="" placeholder=" Enter Branch Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="panel panel-bordered panel-dark">
                            <div class="col-md-10" id="dd"></div>
                            <div class="panel-body demo-nifty-btn text-center">
                                <?php
                                $onclick = '';
                                if ($application_type['id'] != 4) // Surrender
                                {
                                    $onclick = 'onclick="return confirmsubmit()"';
                                }
                                ?>
                                <input type="hidden" name="apply_from" value="JSK" />
                                <button type="submit" id="btn_review" name="btn_review" <?= $onclick; ?> class="btn btn-primary">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end check bounce payment -->

    </div>

    <!--===================================================-->
    <!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->

<!-- ///////modal start -->
<!-- The Modal -->
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Image preview</h4>
            </div>
            <div class="modal-body">
                <img src="" id="imagepreview" style="width: 400px; height: 264px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- //////modal end -->
<div id="demo-lg-modalss" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
            $('#imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
        });

    });
</script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>
<script>
    $(document).ready(function() {
        $("#formname").validate({
            rules: {
                rejectedremarks: {
                    required: true
                }

            },
            messages: {
                rejectedremarks: {
                    required: "Please Enter Remarks"
                }
            }
        });
    });
    /*
    function app_img_remarks_details(il)
    {
     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_img_verify_status =$('#applicant_img_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
        //alert(app_img_verify);
     if(app_img_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_img_verify_status==1){
                   $("#applicant_img_verify_status"+il).val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }+

            $("#app_img_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(app_img_verify=="1")
        {
            if(applicant_img_verify_status==0){
                    $("#applicant_img_verify_status"+il).val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#app_img_remarks"+il).hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
                
            }
        }
    }
    function app_doc_remarks_details(il)
    {

     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_doc_verify_status =$('#applicant_doc_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
       //alert(app_img_verify);
     if(app_doc_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_doc_verify_status==1){
                   $("#applicant_doc_verify_status"+il).val(0);
                   var str=count_change_app-1;
                   $("#count_change_app").val(str);
                }
            }

            $("#app_doc_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
            $("#btn_update_ward").css("display","block");
        }
        else if(app_doc_verify=="1")
        {
            if(applicant_doc_verify_status==0){
                    $("#applicant_doc_verify_status"+il).val(1);
                     var str=count_change_app+1;
                    $("#count_change_app").val(str);
                     $("#app_doc_remarks"+il).hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
                $("#btn_update_ward").css("display","none");
            }
        }
    }
    $(document).ready(function(){
        $(".app_img_remarks").hide();
        $(".app_doc_remarks").hide();
        $("#bu_remarks").hide();
        $("#tan_remarks").hide();
        $("#pvt_remarks").hide();
        $("#noc_remarks").hide();
        $("#Par_remarks").hide();
        $("#sap_remarks").hide();
        $("#sol_remarks").hide();
        $("#ele_remarks").hide();
        $("#app_remarks").hide();

        $("#bu_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var bu_verify = $("#bu_verify").val();
            var bu_verify_status = $("#bu_verify_status").val();
            if(bu_verify=="2")
            {
                if(count_change_app>0){
                    if(bu_verify_status==1){
                    $("#bu_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#bu_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(bu_verify=="1")
            {
                if(bu_verify_status==0){
                    $("#bu_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#bu_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        $("#noc_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var noc_verify = $("#noc_verify").val();
            var noc_verify_status = $("#noc_verify_status").val();
            if(noc_verify=="2")
            {
                if(count_change_app>0){
                    if(noc_verify_status==1){
                    $("#noc_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#noc_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(noc_verify=="1")
            {
                if(noc_verify_status==0){
                    $("#noc_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#noc_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        
        $("#pvt_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var pvt_verify = $("#pvt_verify").val();
            var pvt_verify_status = $("#pvt_verify_status").val();
            if(pvt_verify=="2")
            {
                if(count_change_app>0){
                    if(pvt_verify_status==1){
                    $("#pvt_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#pvt_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(pvt_verify=="1")
            {
                if(pvt_verify_status==0){
                    $("#pvt_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#pvt_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        
        $("#tan_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var tan_verify = $("#tan_verify").val();
            var tan_verify_status = $("#tan_verify_status").val();
            if(tan_verify=="2")
            {
                if(count_change_app>0){
                    if(tan_verify_status==1){
                    $("#tan_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#tan_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(tan_verify=="1")
            {
                if(tan_verify_status==0){
                    $("#tan_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#tan_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        
        $("#Par_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var Par_verify = $("#Par_verify").val();
            var Par_verify_status = $("#Par_verify_status").val();
            if(Par_verify=="2")
            {
                if(count_change_app>0){
                    if(Par_verify_status==1){
                    $("#Par_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#Par_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(Par_verify=="1")
            {
                if(Par_verify_status==0){
                    $("#Par_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#Par_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        $("#sap_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var sap_verify = $("#sap_verify").val();
            var sap_verify_status = $("#sap_verify_status").val();
            if(sap_verify=="2")
            {
                if(count_change_app>0){
                    if(sap_verify_status==1){
                    $("#sap_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#sap_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(sap_verify=="1")
            {
                if(sap_verify_status==0){
                    $("#sap_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#sap_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        $("#sol_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var sol_verify = $("#sol_verify").val();
            var sol_verify_status = $("#sol_verify_status").val();
            if(sol_verify=="2")
            {
                if(count_change_app>0){
                    if(sol_verify_status==1){
                    $("#sol_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#sol_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(sol_verify=="1")
            {
                if(sol_verify_status==0){
                    $("#sol_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#sol_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        $("#ele_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var ele_verify = $("#ele_verify").val();
            var ele_verify_status = $("#ele_verify_status").val();
            if(ele_verify=="2")
            {
                if(count_change_app>0){
                    if(ele_verify_status==1){
                    $("#ele_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#ele_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(ele_verify=="1")
            {
                if(ele_verify_status==0){
                    $("#ele_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#ele_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });
        
        $("#app_verify").on('change',function(){
            var count_change_app = parseInt($("#count_change_app").val());
            var count_app = parseInt($("#count_app").val());
            var app_verify = $("#app_verify").val();
            var app_verify_status = $("#app_verify_status").val();
            if(app_verify=="2")
            {
                if(count_change_app>0){
                    if(app_verify_status==1){
                    $("#app_verify_status").val(0);
                        var str=count_change_app-1;
                        $("#count_change_app").val(str);
                    }
                }
                $("#app_remarks").show();
                $("#btn_approve_submit").css("display","none");
                $("#btn_app_submit").css("display","block");
                $("#btn_update_ward").css("display","block");
            }
            else if(app_verify=="1")
            {
                if(app_verify_status==0){
                    $("#app_verify_status").val(1);
                    var str=count_change_app+1;
                    $("#count_change_app").val(str);
                    $("#app_remarks").hide();
                }
                if(str==count_app)
                {
                    $("#btn_approve_submit").css("display","block");
                    $("#btn_app_submit").css("display","none");
                    $("#btn_update_ward").css("display","none");
                }
            }
        });

        $("#btn_app_submit").click(function(){
            var proceed = true;

            $('#saf_receive_table').find('.app_img_verify').each(function(){
                $(this).css('border-color','');
                var ID = this.id.split('app_img_verify')[1];
                if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
                if($(this).val()=='2'){
                    if ($("#app_img_remarks"+ID).val()=="") {
                        $("#app_img_remarks"+ID).css('border-color','red'); 	proceed = false;
                    }

                }
            });
            $('#saf_receive_table').find('.app_doc_verify').each(function(){
                $(this).css('border-color','');
                var IDD = this.id.split('app_doc_verify')[1];
                if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
                if($(this).val()=='2'){
                    if ($("#app_doc_remarks"+IDD).val()=="") {
                        $("#app_doc_remarks"+IDD).css('border-color','red'); 	proceed = false;
                    }
                }
            });

            var remarks = $("#remarks").val();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }

            var pr_verify = $("#pr_verify").val();
            if(pr_verify=="")
            {
                $('#pr_verify').css('border-color','red');
                proceed = false;
            }
            if(pr_verify=="2")
            {
                var pr_remarks = $("#pr_remarks").val();
                if(pr_remarks=="")
                {
                    $('#pr_remarks').css('border-color','red');
                    proceed = false;
                }
            }
            var rc_verify = $("#rc_verify").val();
            if(rc_verify=="")
            {
                $('#rc_verify').css('border-color','red');
                proceed = false;
            }
            if(rc_verify=="2")
            {
                var rc_remarks = $("#rc_remarks").val();
                if(rc_remarks=="")
                {
                    $('#rc_remarks').css('border-color','red');
                    proceed = false;
                }
            }

            var ad_verify = $("#ad_verify").val();
            if(ad_verify=="")
            {
                $('#ad_verify').css('border-color','red');
                proceed = false;
            }
            if(ad_verify=="2")
            {
                var ad_remarks = $("#ad_remarks").val();
                if(ad_remarks=="")
                {
                    $('#ad_remarks').css('border-color','red');
                    proceed = false;
                }
            }

            var fa_verify = $("#fa_verify").val();
            if(fa_verify=="")
            {
                $('#fa_verify').css('border-color','red');
                proceed = false;
            }
            if(fa_verify=="2")
            {
                var fa_remarks = $("#fa_remarks").val();
                if(fa_remarks=="")
                {
                    $('#fa_remarks').css('border-color','red');
                    proceed = false;
                }
            }

            return proceed;
        });
        $("#btn_approve_submit").click(function(){
            var proceed = true;

            var remarks = $("#remarks").val();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }
            $('#saf_receive_table').find('.app_img_verify').each(function(){
                $(this).css('border-color','');
                if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

            });
            $('#saf_receive_table').find('.app_doc_verify').each(function(){
                $(this).css('border-color','');
                if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

            });

            var pr_verify = $("#pr_verify").val();
            if(pr_verify=="")
            {
                $('#pr_verify').css('border-color','red');
                proceed = false;
            }

            var rc_verify = $("#rc_verify").val();
            if(rc_verify=="")
            {
                $('#rc_verify').css('border-color','red');
                proceed = false;
            }

            var ad_verify = $("#ad_verify").val();
            if(ad_verify=="")
            {
                $('#ad_verify').css('border-color','red');
                proceed = false;
            }        

            var fa_verify = $("#fa_verify").val();
            if(fa_verify=="")
            {
                $('#fa_verify').css('border-color','red');
                proceed = false;
            }       

            return proceed;
        });
    });
    */
</script>

<script src="<?= base_url(); ?>/public/assets/js/bootstrap.min.js"></script>
<script>
    // Get the modal
    var modalpayment = document.getElementById("myModal");
    // Get the button that opens the modal
    var btnpayment = document.getElementById("customer_view_detail");
    // When the user clicks the button, open the modal 
    btnpayment.onclick = function() {
        modalpayment.style.display = "block";
    }
    // When the user clicks on clse_mdel function, close the modal
    function clse_mdel() {
        modalpayment.style.display = "none";

    }
</script>

<!-- provitional model -->
<script>
    // Get the modal
    var modalprov = document.getElementById("provtnalmodal");

    // Get the button that opens the modal
    var btnprov = document.getElementById("provtnal");

    // When the user clicks the button, open the modal 
    btnprov.onclick = function() {
        modalprov.style.display = "block";
    }

    // When the user clicks on clse_mdel_prov function, close the modal

    function clse_mdel_prov() {
        modalprov.style.display = "none";

    }
    var modalpayment = document.getElementById("myModal"); //payment model
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modalprov || event.target == modalpayment) {
            modalprov.style.display = "none";
            modalpayment.style.display = "none";
        }
    }
</script>


<script>
    function printPageArea(printarea_prov) {
        var printContent = document.getElementById(printarea_prov);
        var WinPrint = window.open('', '', 'width=900,height=650');
        WinPrint.document.write(printContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>
<script>
    function printPageArea(print_payment_receipt) {
        var printContent = document.getElementById(print_payment_receipt);
        var WinPrint = window.open('', '', 'width=900,height=650');
        WinPrint.document.write(printContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>


<script>
    function chequeDetailsData() {
        document.getElementById('model').style.display = '';
    }

    function show_charge() {
        var timefor = $("#licence_for").val();
        var str = $("#area_in_sqft").val();
        var edate = $("#firm_date").val();
        var noticedate = $("#noticedate").val();
        if (<?= $application_type['id'] ?> == 1) {
            if (edate > noticedate && noticedate != "") {
                $(".hideNotice").css("display", "none");
                $("#denialAmnt").val(0);
                alert("Notice date should not be smaller then Firm establishment date");
                $("#applyWith option:selected").prop("selected", false);
                $("#noticeNo").val("");
                $("#noticedate").val("");
                $("#owner_business_premises").val("");
            }
        }
        if (str != "" && timefor != "") {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("tradeapplylicence/getcharge"); ?>',
                dataType: "json",
                data: {
                    "areasqft": str,
                    "applytypeid": <?= $application_type["id"] ?>,
                    "estdate": edate,
                    "licensefor": timefor,
                    "tobacco_status": 0,
                    apply_licence_id: <?= $licence_dtl['id'] ?>
                },

                success: function(data) {
                    console.log(data);
                    // alert(data);
                    if (data.response == true) {
                        var cal = data.rate * timefor;
                        $("#charge").val(data.rate);
                        $("#penalty").val(data.penalty);
                        $("#total_charge").val(data.total_charge);
                        var ttlamnt = parseInt(data.total_charge) + parseInt($("#denialAmnt").val());
                        $("#total_charge").val(ttlamnt);
                    } else {

                        $("#charge").val(0);
                        $("#penalty").val(0);
                        $("#total_charge").val(0);
                        $("#denialAmnt").val(0);

                    }
                }

            });
        }

        <?php
        if ($application_type["id"] == 2) {
        ?>
            var for_year = $('#licence_for').val();
            var valid_from = $('#firm_date').val();
            //alert(for_year);alert(valid_from); 
            $('#btn_review').display = 'none';
            $('#btn_review').hide();
            jQuery.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/re_day_diff"); ?>' + '/' + valid_from + '/' + for_year + '/' + 'ajax',
                dataType: "json",

                success: function(data) {
                    console.log(data);
                    if (parseInt(data.diff_day) < 0) {
                        $("#licence_for option:selected").prop("selected", false);
                        $("#charge").val('');
                        $("#penalty").val('');
                        $("#total_charge").val('');
                    }

                    $('#btn_review').show();

                }
            });
        <?php
        }
        ?>

    }

    function myFunction() {
        var mode = document.getElementById("payment_mode").value;
        if (mode == 'CASH') {
            $('#chqno').hide();
            $('#chqbank').hide();
        } else {
            $('#chqno').show();
            $('#chqbank').show();
        }
    }
</script>
<?= $this->include('layout_vertical/footer'); ?>