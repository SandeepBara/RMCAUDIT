<?= $this->include('layout_vertical/header'); ?>

<style>
    .error{
        color:red;
    }
</style>
<div id="content-container">
    <div id="page-head">

        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
        </div>
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Trade</a></li>
            <li class="active">Trade Application Details</li>
        </ol>
    </div>
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
                        <div class="col-sm-3">
                            <b>New Ward No. :</b>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $newWard['ward_no']??""; ?>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="apply_licence_id" value="<?= md5($basic_details['id']); ?>" />
                        <div class="col-sm-3">
                            <b>Holding No. :</b>
                        </div>
                        <div class="col-sm-3">
                            <?php // $holding['holding_no'] ? $holding['holding_no'] : "N/A"; ?>

                            <!-- <u><i> <a class="bg-light text-info  border-none " onclick="openPropertyDetails123(<?= $basic_details['prop_dtl_id'] ?>)"><?= $holding['holding_no'] ?></a></i></u> -->
                            <u><i> <a class="bg-light text-info  border-none "target="_blank" href="<?=isset($PropSafLink)?$PropSafLink:"#";?>"><?= $holding['holding_no'] ?></a></i></u>
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
                            <?= isset($basic_details['application_type']) ? $basic_details['application_type'] : "N/A"; ?>
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
                            <?= isset($basic_details['firm_type']) ? $basic_details['firm_type'] : "N/A"; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <b>Ownership Type :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= isset($basic_details['ownership_type']) ? $basic_details['ownership_type'] : "N/A"; ?>
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
                            <?= isset($nature_business) ? $nature_business[0]['trade_item'] : "N/A"; ?>
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
                            <?php
                                if($application_type['id']==1)
                                {
                                    ?>
                                    <input type="hidden" id="firm_date" value="<?= $holding['establishment_date'] ? date('d-m-Y', strtotime($holding['establishment_date'])) : date('d-m-Y'); ?>">                                    
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="hidden" id="firm_date" value="<?= $licencee['valid_from'] ? date('d-m-Y', strtotime($licencee['valid_from'])) : date('d-m-Y'); ?>">
                                        <?php
                                }
                                ?> 
                                <input type="hidden" id="notice_date" value="<?= isset($notice_date) ? date('d-m-Y', strtotime($notice_date)) : ""; ?>">
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
                            <b>Pin Code:</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['pin_code'] ? $basic_details['pin_code'] : "N/A"; ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Landmark :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $holding['landmark'] ? $holding['landmark'] : "N/A"; ?>

                        </div>
                        <div class="col-sm-3">
                            <b>Applied Date :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['created_on'] ? date('d-M-Y',strtotime($basic_details['created_on'])) : "N/A"; ?>

                        </div>
                        <div class="col-sm-3">
                            <b>Valid Upto :</b>
                        </div>
                        <div class="col-sm-3">
                            <?= $basic_details['valid_upto'] ? date('d-M-Y',strtotime($basic_details['valid_upto'])) : "N/A"; ?>

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
                                            <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $value['document_path']; ?>" target="_blank"> <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="height: 40px;"></a>
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
            <!-- <div class="panel panel-bordered panel-dark">
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
            </div> -->

            <!-----end level remarks------->
            <!-----link------->

            <?=levelRemarkTree($linkId)?>

            <div class="panel  panel-dark" id="botum-button">
                <div class="panel-body">
                    <div class="row text-center">
                        <?php
                        if ($basic_details['application_type_id'] != 4) 
                        {
                            if ($licencee['payment_status'] == 0 && in_array(session()->get('emp_details')['user_type_mstr_id']??null,[4,5,8,1]) ) 
                            {
                                ?>
                                
                                <button data-target="#demo-lg-modalss" data-toggle="modal" class="btn btn-warning" type="button" onclick="denial_carcge()">Pay Now</button>

                                <?php
                            }elseif($licencee['is_fild_verification_charge']=="t" && $licencee['exrta_charge']>0){
                                ?>
                                <button data-target="#payExtraCharge" data-toggle="modal" class="btn btn-warning" type="button" >Pay Area Difference Amount</button>
                                <?php
                            }

                            if ($basic_details['pending_status'] == 5) {
                            ?>
                                <a href="<?php echo base_url('Trade_DA/municipalLicence/' . $linkId); ?>" target="popup" type="button" class="btn btn-primary" style="color:white;" onclick="window.open('<?php echo base_url('Trade_DA/municipalLicence/' . $linkId); ?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;">
                                    View Trade Licence
                                </a>
                            <?php
                            } elseif(in_array($licencee['payment_status'], [1,2])) {
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
        
    </div>
</div>
<div id="demo-lg-modalss" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
            </div>
            <div class="modal-body">
                <!-- check bounce payment -->
                <div class="panel  panel-dark" id="model" style="display: '';">
                    <div class="panel-body">
                        <div class="row">
                            <form id = 'payment' name = 'payment' action="<?php echo base_url(); ?>/Trade_report/check_bounce_payment/<?= md5($licence_dtl['id']) ?>" method='post'>
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
                                                    <div class="col-md-3 pad-btm"> <b> <?= $licence_dtl['valid_from']; ?> </b> </div>
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
                                                        <select id="licence_for" name="licence_for" class="form-control" onclick="show_charge()" required>
                                                            <option value="1">1 Year</option>
                                                        </select>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()" required>
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
                                                    <input type="text" id="charge" disabled="disabled" class="form-control" value="<?php echo $rate ?? 0; ?>" onkeypress="return isNum(event);" required/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-2">Penalty<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="penalty" disabled="disabled" class="form-control" value="<?php echo $penalty ?? 0; ?>" onkeypress="return isNum(event);" required/>
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
                                                    <select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()"required>
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
                                                    <input type="date" class="form-control" id="chq_date" name="chq_date" value="<?= date("Y-m-d") ?>" placeholder="Enter Cheque/DD Date" >
                                                </div>
                                                <label class="col-md-2">Cheque/DD No.<span class="text-danger">*</span></label>

                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No." >
                                                </div>
                                            </div>
                                            <div class="row" id="chqbank" style="display: none;">
                                                <label class="col-md-2">Bank Name<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder=" Enter Bank Name" >
                                                </div>
                                                <label class="col-md-2">Branch Name<span class="text-danger">*</span></label>

                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" placeholder=" Enter Branch Name" >
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
            </div>
        </div>
    </div>
</div>


<div id="payExtraCharge" class="modal fade" tabindex="-1">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form id = 'extraPaymentForm' name = 'extraPaymentForm' action="<?php echo base_url(); ?>/Trade_report/extraChargePayment/<?= md5($licence_dtl['id']) ?>" method='post'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Area Difference Charge</h4>
                </div>
                <div class="modal-body">
                    <div class="panel  panel-dark" id="model" style="display: '';">
                        <div class="panel-body">
                            <div class="row">
                                
                                    <input type="hidden" name="apply_from" value="JSK" />
                                    <input type="hidden" name="extraCharge" value="extraCharge" />
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-body demo-nifty-btn text-center" >                                        
                                            <div id="extraChargeModalBody">

                                            </div>
                                            <div class="row">
                                                <label class="col-md-2">Payment Mode<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <select class="form-control" name="payment_mode" id='paymentMode2' required>
                                                        <option value="">Choose...</option>
                                                        <option value="CASH">CASH</option>
                                                        <option value="CHEQUE">CHEQUE</option>
                                                        <option value="DEMAND DRAFT">DEMAND DRAFT</option>
        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row bankDtlDiv" style="display: none;">
                                                <label class="col-md-2"><span class="modeDiv"></span> Date<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="date" class="form-control bankDtlRequired"  name="chq_date" id="chq_date2" value="<?= date("Y-m-d") ?>" placeholder="Enter Cheque/DD Date" >
                                                </div>
                                                <label class="col-md-2"><span class="modeDiv"></span> No.<span class="text-danger">*</span></label>
        
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control bankDtlRequired"  name="chq_no" id="chq_no2" value="" placeholder="Enter Cheque/DD No." >
                                                </div>
                                            </div>
                                            <div class="row bankDtlDiv"  style="display: none;">
                                                <label class="col-md-2">Bank Name<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control bankDtlRequired" name="bank_name" id="bank_name2" value="" placeholder=" Enter Bank Name" >
                                                </div>
                                                <label class="col-md-2">Branch Name<span class="text-danger">*</span></label>
        
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control bankDtlRequired" name="branch_name" id="branch_name2" value="" placeholder=" Enter Branch Name" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="payExtraAmount" name="payExtraAmount" class="btn btn-primary">Pay</button>
                </div>            
            </form>
        </div>
    </div>
</div>


<script>

    $(document).ready(function(){
        $('#payExtraCharge').on('show.bs.modal', function (e) {            
            $.ajax({
                url:"<?=base_url("TradeCitizen/extraCharge/".md5($licence_dtl['id']))?>",
                type:"post",
                dataType:"json",
                beforeSend:function(){
                    $("#loadingDiv").show();
                    $("#payExtraCharge .modal-footer").hide();
                    $("#extraChargeModalBody").html("Loading......");
                },
                success:function(response){
                    $("#loadingDiv").hide();
                    data = response?.html;
                    $("#extraChargeModalBody").html(data);
                    if(response?.status){
                        $("#payExtraCharge .modal-footer").show();
                    }
                }
            })
        });

        $("#paymentMode2").on("change",function(){
            let paymentMod = $(this).val().toUpperCase().trim();                
            $(".bankDtlDiv").hide();
            $(".bankDtlRequired").attr("required",false);
            if(paymentMod==""){
                return false;
            }
            if(!['CASH','UPI','CARD'].includes(paymentMod)){
                $(".bankDtlDiv").show();
                $(".modeDiv").html(paymentMod);
                $(".bankDtlRequired").attr("required",true);
            }
        });

        $("#extraPaymentForm").validate({
            rules:{
                paymentMode2:{
                    required:true,
                }
            },
            submitHandler: function (form) {
                // This runs only if validation passes
                if (confirm("Are you sure you want to Pay ?")) {
                    $("#payExtraCharge .modal-footer").hide();
                    form.submit(); // manually submit the form
                }
            }

        });

    });

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
    
</script>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<script>
    function form_validate()
    { 
        $("#payment").validate({
            rules:{                         
                chq_date:{
                    required:true
                },                
                chq_no:{
                    required:true
                },                
                bank_name:{
                    required:true
                },                
                branch_name:{
                    required:true
                },                
                
                applyWith:{
                    required:true
                },                  
               
                licence_for:{
                    required:true
                }, 
                charge:{
                    required:true
                }, 
                total_charge:{
                    required:true
                }, 
                payment_mode:{
                    required:true
                },

            },
            messages:{  
                applyWith:{
                    required:"Please Select Apply With",
                },
                               
                chq_date:{
                    required:"Please Select date"  
                },                
                chq_no:{
                    required:"Please Enter Cheque/DD No."  
                },                
                bank_name:{
                    required:"Please Enter Bank Name"  
                },                
                branch_name:{
                    required:"Please Enter Branch Name"  
                },                
                licence_for:{
                    required:"Please Enter Licence For"  
                }, 

                payment_mode:{
                    required:"Please Enter Payment Mode"  
                },
                               
            }
        });
    } 

    function isAlpha(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }

    function isNumDot(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46){
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }else{
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }

    

    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isDateFormatYYYMMDD(value){
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(value) ) {
            return false;
        }else{
            return true;
        }
    }

    function confirmsubmit()
    {   
        var p = document.getElementById('payment_mode').value;
        var brname = document.getElementById('branch_name').value;
        var bname = document.getElementById('bank_name').value;
        var chqno= document.getElementById('chq_no').value;
        var chqdate = document.getElementById('chq_date').value;
        var totalcharge = document.getElementById('total_charge').value;
        var deAmnt = document.getElementById('denialAmnt').value;
        var pen = document.getElementById('penalty').value;
        var char = document.getElementById('charge').value;
        var lfor = document.getElementById('licence_for').value;        
        $('#btn_review').hide();
        if((p=='CHEQUE'|| p=='DEMAND DRAFT') && (brname==''||bname==''||chqno==''||totalcharge==''))
        {
            $('#btn_review').show();
            alert('Enter All Filed');
            return false;
        }
        if((p==''|| totalcharge=='' ||deAmnt==''||pen==''||char==''||lfor==''))
        {
            $('#btn_review').show();
            alert('Enter All Filed');
            return false;
        }
        return true;
        
        var val =form_validate();
        //alert($("#payment").valid());
        //alert(val);
        if($("#payment").valid())
        {
            var amt = $('#total_charge').val();
            var del=confirm("Are you sure you want to confirm Payment of Rs "+amt+"?");
            return del;
        }
        else
        {
            return false;
        }
        
    }
    $(document).ready(function(){
        $('#btn_review').click('on',function(){
            //alert();
        });
    })
    let denial_amount = 0;
    function denial_carcge() 
    {    
        debugger;   
        var notice_date=$('#notice_date').val(); 
        console.log(notice_date);  
        if (notice_date != "") 
        {
            $('#btn_review').hide();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/dinial_charge"); ?>',
                dataType: "json",
                data: {
                    "notice_date": notice_date,                   
                    
                },

                success: function(data) 
                {
                    if (data.response == true) 
                    {
                        console.log('inside true')
                        //var cal = data.rate * timefor;
                        $("#denialAmnt").val(data.amount); 
                        denial_amount =    data.amount;                  
                        $('#btn_review').show();
                    } 
                    
                }

            });
        }

    }

    function show_charge() 
    {        
        var timefor = $("#licence_for").val();
        var str = $("#area_in_sqft").val();
        var edate = $("#firm_date").val();
        var noticedate = $("#noticedate").val();
        var temp = <?=$application_type["id"];?>;
        // alert($("#noticedate").val());
        if (<?= $application_type['id'] ?> == 1) 
        {
            if (edate > noticedate && noticedate != "") 
            {
                $(".hideNotice").css("display", "none");
                $("#denialAmnt").val(0);
                alert("Notice date should not be smaller then Firm establishment date");
                $("#applyWith option:selected").prop("selected", false);
                $("#noticeNo").val("");
                $("#noticedate").val("");
                $("#owner_business_premises").val("");
            }
        }
        if (str != "" && timefor != "") 
		{
            $('#btn_review').hide();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/getcharge"); ?>',
                dataType: "json",
                data: {
                    "areasqft": str,
                    "applytypeid": <?= $application_type["id"] ?>,
                    "estdate": edate,
                    "licensefor": timefor,
                    "tobacco_status": 0,
                    "nature_of_business":<?= ("'".$licencee['nature_of_bussiness']."'"); ?>,
                    apply_licence_id: <?= $licencee['id'] ?>
                },

                success: function(data) {
                    console.log(data);
                    // alert(parseInt($("#denialAmnt").val()));
                    if (data.response == true) {
                        var cal = data.rate * timefor;
                        $("#charge").val(data.rate);
                        $("#penalty").val(data.penalty);
                        $("#total_charge").val(data.total_charge);
                        var ttlamnt = parseInt(data.total_charge) + parseInt(denial_amount);
                        $("#denialAmnt").val(denial_amount + data.arear_amount);
                        $("#total_charge").val(ttlamnt);
                        $('#btn_review').show();
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
        if ($application_type["id"] == 21) {
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

    function openPropertyDetails(prop_dtl_id) {


        var prop_dtl = '<?= $basic_details['prop_dtl_id'] ?>';
        // alert(prop_dtl_id)
        window.open('<?= base_url()?>/propDtl/full/'+prop_dtl)
    }
</script>
<?= $this->include('layout_vertical/footer'); ?>