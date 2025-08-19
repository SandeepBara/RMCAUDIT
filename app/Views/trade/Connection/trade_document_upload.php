<?= $this->include('layout_vertical/header'); ?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?= base_url() ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">

<?php $session = session(); ?>
<?php $edit = '';
$display = ''; ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Trade</a></li>
            <li><a href="#">Trade Apply Licence Document Upload</a></li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="row">
            <div class="col-md-12">
                <b>
                    <h4 style="color:red;">
                        <?php
                        if (!empty($errors)) {
                            echo $errors;
                        }
                        ?>
                    </h4>
                </b>
            </div>
        </div>
        <!-------Owner Details-------->

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <div class="panel-control">
                </div>
                <h3 class="panel-title">Apply Licence <button onclick="openWin()" class="btn btn-md btn-dark pull-right">Edit Basic Details</button></h3>
            </div>
            <div class="panel-body">

                <div class="row">
                    <label class="col-md-2">Application Type <span class="text-danger">*</span></label>
                    <div class="col-md-3 control-label text-semibold">
                        <?= $application_type["application_type"] ?>
                    </div>
                    <label class="col-md-2">Firm Type <span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm"><?php echo $firmtype['firm_type']; ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm"><?php echo  $ownershiptype['ownership_type']; ?>
                    </div>

                    <label class="col-md-2 classother" style="display: none;">For Other Firm type<span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm classother" style="display: none;">
                        <input type="text" name="firmtype_other" id="firmtype_other" class="form-control" value="<?php echo isset($firmtype_other) ? $firmtype_other : ""; ?>" placeholder="Other Firm type" onkeypress="return isAlphaNum(event);">
                    </div>
                    <?php
                    if ($application_type["id"] <> 1) {
                    ?>
                        <label class="col-md-2">License No. </label>
                        <div class="col-md-3 control-label text-semibold">
                            <?= $licencedet["license_no"] ?>
                        </div>
                    <?php
                    }
                    if ($application_type["id"] = 1) {
                    ?>
                        <label class="col-md-2">Application No. </label>
                        <div class="col-md-3 control-label text-semibold">
                            <?= $licencedet["application_no"] ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Firm Details</h3>
            </div>

            <div class="panel-body">

                <div class="row">
                    <label class="col-md-2" id="saf_lebel">Holding No. <span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm" id="saf_div">
                        <?= ($licencedet['holding_no'] == "" ? 'N/A' : $licencedet['holding_no']); ?>
                    </div>
                    <label class="col-md-2">Ward No. <span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <?= $ward_no['ward_no'] ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-2">Firm Name<span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $licencedet["firm_name"]; ?>
                    </div>
                    <label class="col-md-2">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <?php if ($application_type["id"] == 3) { ?> <input type="text" name="area_in_sqft" id="area_in_sqft" class="form-control" onblur="show_charge()" value="<?php echo isset($area_in_sqft) ? $area_in_sqft : ""; ?>" onkeypress="return isNumDot(event);">
                        <?php } else {
                            echo $licencedet["area_in_sqft"]; ?>
                            <input type="hidden" name="area_in_sqft" id="area_in_sqft" class="form-control" value="<?php echo $licencedet["area_in_sqft"]; ?>">
                        <?php } ?>

                    </div>

                </div>

                <div class="row">
                    <label class="col-md-2">Firm Establishment Date<span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $licencedet["establishment_date"]; ?>
                        <input type="hidden" name="firm_date" id="firm_date" value="<?php echo isset($licencedet["establishment_date"]) ? $licencedet["establishment_date"] : NULL; ?>" onchange="show_charge(); checkDOB()" onkeypress="return isNum(event);">
                    </div>
                    <label class="col-md-2">Business Address<span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $licencedet["address"]; ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-2">Pin Code<span class="text-danger">*</span></label>
                    <div class="col-md-3 pad-btm">
                        <?php echo $licencedet["pin_code"]; ?>
                    </div>
                    <label class="col-md-2">New Ward No. <span class="text-danger">*</span></label>

                    <div class="col-md-3 pad-btm">
                        <?= $new_ward_no["ward_no"] != "" ? $new_ward_no["ward_no"] : "N/A" ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-2">Owner of Business Premises</label>
                    <div class="col-md-3 pad-btm">
                        <?= $licencedet["premises_owner_name"] != "" ? $licencedet["premises_owner_name"] : "N/A"; ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Owner Details </h3>

            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>Owner Name
                                <th>Guardian Name </th>
                                <th>Mobile No </th>
                                <th>Email Id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                                    <td><?= $value['owner_name'] == "" ? 'N/A' : $value['owner_name']; ?></td>
                                    <td><?= $value['guardian_name'] == "" ? 'N/A' : $value['guardian_name']; ?></td>
                                    <td><?= $value['mobile'] == "" ? 'N/A' : $value['mobile']; ?></td>
                                    <td><?= $value['emailid'] == "" ? 'N/A' : $value['emailid']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        if ($application_type["application_type"] == "NEW LICENSE") { ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                    </div>
                    <h3 class="panel-title">Document Upload (Owner) </h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Document Image</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($owner_list)) {
                                    if (empty($owner_list)) {
                                ?>
                                        <tr>
                                            <td style="text-align:center;"> Data Not Available...</td>
                                        </tr>
                                    <?php
                                    } else {
                                    ?>
                                        <?php
                                        $i = 0;
                                        foreach ($owner_list as $value) {
                                            $i++;
                                        ?>
                                            <tr>
                                                <td id="ownername<?= $i ?>" class="text-center"><?= $value['owner_name']; ?>&nbsp;(ID Proof&nbsp;)<span style="color: #f00">*</span></td>
                                                <td style="text-align:center">
                                                    <?php
                                                    if (empty($value['doc_upload_id'])) {
                                                        $display = "none";
                                                    ?>
                                                        <span class="text-danger">N/A</span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $value['document_path']; ?>" target="_blank">
                                                            <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;">
                                                        </a>

                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if (empty($value['doc_upload_id'])) {
                                                    ?>
                                                        <button class="btn btn-info" onclick="open_owner_doc_upload('<?= $i ?>');">Upload Document</button>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <?php
                                                        if ($value['verify_status'] == 0) {
                                                            $edit = 'y';
                                                        ?>
                                                            <span class="text-success"> <b>Uploaded Successfully !!</b></span>
                                                        <?php
                                                        }
                                                        if ($value['verify_status'] == 1) {
                                                        ?>
                                                            <span class="text-success"> <b>Document Verified !!</b></span>
                                                        <?php
                                                        }
                                                        if ($value['verify_status'] == 2) {
                                                        ?>
                                                            <span class="text-danger"> <b>Document Rejected !!</b></span>
                                                        <?php
                                                        }
                                                        ?>
                                                        <button class="btn btn-success" onclick="open_owner_doc_upload('<?= $i ?>');">Edit Document</button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php /*} else { ?>
                                                        <span class="text-danger">  <b>Uploaded Successfully !!</b></span>
                                                    <?php }*/ ?>
                                                </td>
                                            </tr>

                                            <!-- owner image view hear -->
                                            <tr>
                                                <td id="ownername_img<?= $i ?>" class="text-center"><?= $value['owner_name']; ?>&nbsp;(Owner Image&nbsp;)<span style="color: #f00"></span></td>
                                                <td style="text-align:center">
                                                    <?php
                                                    if (empty($value['img_doc_upload_id'])) {
                                                        $display = "";
                                                    ?>
                                                        <span class="text-danger">N/A</span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $value['img_document_path']; ?>" target="_blank">
                                                            <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;">
                                                        </a>

                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if (empty($value['img_doc_upload_id'])) {
                                                    ?>
                                                        <button class="btn btn-info" onclick="open_owner_doc_upload_img('<?= $i ?>');">Upload Document</button>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <?php
                                                        if ($value['img_verify_status'] == 0) {
                                                            $edit = 'y';
                                                        ?>
                                                            <span class="text-success"> <b>Uploaded Successfully !!</b></span>
                                                        <?php
                                                        }
                                                        if ($value['img_verify_status'] == 1) {
                                                        ?>
                                                            <span class="text-success"> <b>Document Verified !!</b></span>
                                                        <?php
                                                        }
                                                        if ($value['img_verify_status'] == 2) {
                                                        ?>
                                                            <span class="text-danger"> <b>Document Rejected !!</b></span>
                                                        <?php
                                                        }
                                                        ?>
                                                        <button class="btn btn-success" onclick="open_owner_doc_upload_img('<?= $i ?>');">Edit Document</button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php /*} else { ?>
                                                        <span class="text-danger">  <b>Uploaded Successfully !!</b></span>
                                                    <?php }*/ ?>
                                                </td>
                                            </tr>
                                            <!-- owner image view end hear -->
                                        <?php
                                        }
                                        ?>
                                    <?php
                                    }
                                    ?>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div>
                            <span style="color: #05ad48;font-size: revert;">Uploaded</span> <span style="color: #324654;"><?php echo $doc_details_owner_count['count']; ?></span> <span style="color: black;">out of </span> <span style="color: black;"><?php echo $owner_doc_list['count'] ?></span>
                            <span style="color: #f00"> ( Note :- Please upload all mandatory documents )</span>
                        </div>
                        <?php
                        $i = 0;
                        foreach ($owner_list as $value) {
                            $i++;
                        ?>
                            <br>
                            <!-- woner id proof model -->
                            <div id="<?= 'ownerdoc' . $i ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title" id="owner_names<?= $i ?>"></h4>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" action="">
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <input type="hidden" id="ownrid" name="ownrid" value="<?= $value['id'] ?>">
                                                    <table class="table table-bordered table-hover">
                                                        <tr>
                                                            <td>ID Proof&nbsp;<span style="color: #f00">*</span><input type="hidden" name="doc_for<?= $i ?>" id="doc_for<?= $i ?>"></td>
                                                            <td colspan="4">
                                                                <select id="idproof<?= $i ?>" name="idproof<?= $i ?>" class="form-control idproof">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    if ($idprooflist) {
                                                                        foreach ($idprooflist as $proofval) {
                                                                    ?>
                                                                            <option value="<?php echo $proofval['id']; ?>" <?php if ($proofval['id'] == $value['doc_document_id']) {
                                                                                                                                echo 'selected="selected"';
                                                                                                                            } ?>><?= $proofval["doc_name"] ?></option>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="file" name="doc_path_owner<?= $i ?>" id="doc_path_owner<?= $i ?>" class="form-control" accept=".pdf" onchange="ext(this,<?= $i ?>)" required />
                                                                <span class="text-danger" id="doc_path_owner<?= $i ?>sms"></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button name="btn_doc_path_owner" id="btn_doc_path_owner" class="btn btn-success" value="<?= $i ?>" type="submit">Upload</button>
                                                <a href="#" class="btn" data-dismiss="modal">Close</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- woner id proof model end -->
                            <!-- woner img model -->
                            <div id="<?= 'ownerdoc_img' . $i ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title" id="owner_names_img<?= $i ?>"></h4>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" action="">
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <input type="hidden" id="ownrid" name="ownrid" value="<?= $value['id'] ?>">
                                                    <table class="table table-bordered table-hover">
                                                        <tr>
                                                            <td>Consumer Photo&nbsp;<span style="color: #f00"></span>
                                                                <input type="hidden" name="doc_for<?= $i ?>" id="doc_for<?= $i ?>" value="<?= $value['owner_name'] ?>(Consumer Photo)">
                                                            </td>
                                                            <td colspan="4">
                                                                <select id="consumer_photo<?= $i ?>" name="consumer_photo<?= $i ?>" class="form-control idproof">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Consumer Photo</option>

                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="file" name="doc_path_owner_img<?= $i ?>" id="doc_path_owner_img<?= $i ?>" class="form-control" accept=".jpg" onchange="ext2(this,<?= $i ?>)" />
                                                                <span class="text-danger" id="doc_path_owner_img<?= $i ?>sms"></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button name="btn_doc_path_owner_img" id="btn_doc_path_owner_img" class="btn btn-success" value="<?= $i ?>" type="submit">Upload</button>
                                                <a href="#" class="btn" data-dismiss="modal">Close</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- woner img model end -->

                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Other Documents</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th style="text-align:left;">Document Name</th>
                                <th>Document Image</th>
                                <th>Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($doc_details, $doc_details)) {
                                $cnt = 0;
                                foreach ($doc_details as $docvalue) {
                                    $cnt++;
                            ?>
                                    <tr>
                                        <?php
                                        if ($application_type["id"] == 2 || $application_type["id"] == 3 || $application_type["id"] == 4) {
                                        ?>
                                            <td class="col-sm-4"><strong><?php echo $docvalue["doc_for"]; ?><?php $abc = str_replace(" ", "_", $docvalue['doc_for']) ?> &nbsp;</strong> ( pdf only)<span style="color: #f00">*</span></td>
                                        <?php
                                        } elseif ($docvalue["mandatory"] == 1 && $application_type["id"] == 1) {
                                        ?>
                                            <td class="col-sm-4"><strong><?php echo $docvalue["doc_for"]; ?><?php $abc = str_replace(" ", "_", $docvalue['doc_for']) ?> &nbsp;</strong> ( pdf only)<span style="color: #f00">*</span></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td class="col-sm-4"><strong><?php echo $docvalue["doc_for"]; ?><?php $abc = str_replace(" ", "_", $docvalue['doc_for']) ?> &nbsp;</strong> ( pdf only)</td>
                                        <?php
                                        }
                                        ?>

                                        <td style="text-align: left">
                                            <?php

                                            if (empty($docvalue['docexists']) || !isset($docvalue['docexists'])) {

                                            ?>
                                                <span class="text-danger">N/A</span>
                                                <?php
                                                if ($docvalue["mandatory"] == 1 && $application_type["id"] == 1) {
                                                    $display = "none";
                                                }
                                            } else {
                                                ?>
                                                <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $docvalue['docexists']['document_path'] ?? NULL; ?>" target="_blank"><img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
                                                <?php
                                                if ($docvalue['docexists']['verify_status'] == 0) {
                                                    $edit = 'y';
                                                ?>
                                                    <span class="text-success"> <b>Uploaded Successfully !!</b></span>
                                                <?php
                                                }
                                                if ($docvalue['docexists']['verify_status'] == 1) {
                                                ?>
                                                    <span class="text-success"> <b>Document Verified !!</b></span>
                                                <?php
                                                }
                                                if ($docvalue['docexists']['verify_status'] == 2) {
                                                ?>
                                                    <span class="text-danger"> <b>Document Rejected !!</b></span>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            if (isset($docvalue['docexists'], $docvalue['docexists'])) {
                                            ?>

                                                <button class="btn btn-success" onclick="modelfnc_doc('<?= $cnt ?>');">Edit Document</button>
                                            <?php
                                            } else {
                                            ?>
                                                <button class="btn btn-info" onclick="modelfnc_doc('<?= $cnt ?>');">Upload Document</button>
                                            <?php
                                            }
                                            ?>
                                            <div id="<?= 'doc_Modal_' . $cnt ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Upload Receipt</h4>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" action="">
                                                            <div class="modal-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hover">
                                                                        <tr>
                                                                            <?php if ($application_type["id"] == 2 || $application_type["id"] == 3 || $application_type["id"] == 4) : ?>
                                                                                <td class="col-sm-4"><strong><?php echo $docvalue["doc_for"]; ?><?php $abc = str_replace(" ", "_", $docvalue['doc_for']) ?> &nbsp;</strong> ( pdf only)<span style="color: #f00">*</span></td>
                                                                            <?php elseif ($docvalue["mandatory"] == 1 && $application_type["id"] == 1) : $other_doc = 'required'; ?>
                                                                                <td class="col-sm-4"><strong><?php echo $docvalue["doc_for"]; ?><?php $abc = str_replace(" ", "_", $docvalue['doc_for']) ?> &nbsp;</strong> ( pdf only)<span style="color: #f00">*</span></td>
                                                                            <?php else : $other_doc = ''; ?>
                                                                                <td class="col-sm-4"><strong><?php echo $docvalue["doc_for"]; ?><?php $abc = str_replace(" ", "_", $docvalue['doc_for']) ?> &nbsp;</strong> ( pdf only)</td>
                                                                            <?php endif; ?>
                                                                            <input type="hidden" name="doc_for<?= $cnt ?>" value="<?= $docvalue["doc_for"] ?>" id="doc_for<?= $cnt ?>">
                                                                            <td colspan="3">
                                                                                <select id="doc_mstr_id<?= $cnt ?>" name="doc_mstr_id<?= $cnt ?>" class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    <?php
                                                                                    if (isset($docvalue['docfor'])) :
                                                                                        foreach ($docvalue['docfor'] as $valuefor) :
                                                                                    ?>
                                                                                            <option value="<?= $valuefor['id'] ?>"><?= $valuefor['doc_name'] ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </td>

                                                                            <td>
                                                                                <input type="file" name="doc_path<?= $cnt ?>" id="doc_path<?= $cnt ?>" class="form-control" accept=".pdf" onchange="ext(this,<?= $i ?>)" <?= $other_doc ?> />
                                                                                <span class="text-danger" id="doc_path<?= $cnt ?>sms"></span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button name="btn_doc_path" id="btn_doc_path" class="btn btn-success" value="<?= $cnt ?>" type="submit">Upload</button>
                                                                <a href="#" class="btn" data-dismiss="modal">Close</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                    <div>
                        <span style="color: #05ad48;font-size: revert;">
                            Uploaded
                        </span>
                        <span style="color: #324654;">
                            <?= $doc_cnt['count']; ?>
                        </span>
                        <span style="color: black;">out of </span> <span style="color: black;"><?php echo $doc_count['doc_for'] ?></span>

                        <span style="color: #f00"> ( Note :- Please upload all mandatory documents )</span>
                    </div>
                    <br>
                </div>
            </div>
            <!--End page content-->
        </div>
        <!--END CONTENT CONTAINER-->
        <?php
        if ($application_type["id"] == 2 || $application_type["id"] == 3 || $application_type["id"] == 4) {
            $and_or = $doc_cnt['count'] == $doc_count['doc_for'];
        } elseif ($application_type["id"]  == 1) {
            $and_or = $doc_cnt_mndtry['count'] == $doc_count_mandatory['doc_for']  && $doc_details_owner_count['count'] == $owner_doc_list['count'];
        } else {
            $and_or = $doc_cnt_mndtry['count'] == $doc_count_mandatory['doc_for']  || $doc_details_owner_count['count'] == $owner_doc_list['count'];
        }
        if ($and_or  || $edit == 'y') {
        ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body">
                    <?php if (isset($cheque_details['status'], $cheque_details['status']) && $cheque_details['status'] == 3) : ?>
                        <div class="col-sm-4 col-sm-offset-4">
                        <?php else : ?>
                            <div class="col-sm-2 col-sm-offset-5">
                            <?php endif; ?>
                            <?php
                            if ((in_array(strtoupper($payment_mode), ["CASH", 'ONLINE'])) || ($trade_conn_dtl['payment_status'] == 1 && $trade_conn_dtl['application_type_id'] == 4)) {
                            ?>
                                <!-- <a class="btn btn-primary" href="<?php //echo base_url('TradeDocument/send_rmc/'.$apply_licence_id);
                                ?>" role="button" style="display:<?php //echo $display?>">Send To Level</a> -->

                                <button class="btn btn-primary" id="sendToLevelButton" role="button" style="display:<?= $display ?>" onclick="removeButton();">Send To Level</button>
                                <!---------------------------------->
                                <div class="load6" id="myloader" style="display:none;">
                                    <div class="loader"></div>
                                </div>

                                <?php
                            } else {
                                if (isset($cheque_details)) {
                                    if ($cheque_details['status'] == 1 && $trade_conn_dtl["payment_status"] == 1) {
                                ?>
                                        <!-- <a class="btn btn-primary" href="<?php //echo base_url('TradeDocument/send_rmc/'.$apply_licence_id); >" role="button" style="display:<?php //echo $display?>" >Send To RMC</a> -->

                                        <button class="btn btn-primary" id="sendToLevelButton" role="button" style="display:<?= $display ?>" onclick="removeButton();">Send To RMC</button>
                                        <!---------------------------------->
                                        <div class="load6" id="myloader" style="display:none;">
                                            <div class="loader"></div>
                                        </div>
                                    <?php
                                    } else if ($cheque_details['status'] == 3) {
                                    ?>
                                        <span style="color:red;">Given cheque number (<?= $cheque_details['cheque_no'] ?>) has been bounced</span>
                                    <?php
                                    } else {
                                    ?>
                                        <span style="color:red;">Given cheque number (<?= $cheque_details['cheque_no'] ?>) is not clear.</span>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <span style="color:red;">Its look like payment issue, payment status is not clear.</span>
                            <?php
                                }
                            }
                            ?>


                            </div>
                        </div>
                </div>
            <?php
        }
            ?>





            <?= $this->include('layout_vertical/footer'); ?>

            <script>
                function owner_details(il) {
                    var owner_id = $('#owner_id' + il).val();
                    var owner_name = $('#applicant_name' + il).val();
                    var mobile_no = $('#mobile_no' + il).val();
                    $('#owner_dtl_id').val(owner_id);
                    $('#owner_det_name').html(owner_name);
                    $('#mobile_det_no').html(mobile_no);
                    $("#owner_details_Modal").modal('show');
                }

                function last_payment_doc() {
                    $("#last_payment_doc_Modal").modal('show');
                }

                function tanent_doc() {
                    $("#tanent_doc_Modal").modal('show');
                }

                function noc_noc_affidavit_doc() {
                    $("#noc_noc_affidavit_doc_Modal").modal('show');
                }

                function partnership_doc() {
                    $("#partnership_doc_Modal").modal('show');
                }

                function pvtltd_doc() {
                    $("#pvtltd_doc_Modal").modal('show');
                }

                function sapat_patra_doc() {
                    $("#sapat_patra_doc_Modal").modal('show');
                }

                function solid_waste_doc() {
                    $("#solid_waste_doc_Modal").modal('show');
                }

                function electricity_bill_doc() {
                    $("#electricity_bill_doc_Modal").modal('show');
                }

                function application_form_doc() {
                    $("#application_form_doc_Modal").modal('show');
                }


                function modelfnc_doc(str) { //alert(str);
                    $("#doc_Modal_" + str).modal('show');
                }

                function open_owner_doc_upload(str) {
                    var ownrname = $("#ownername" + str).text();
                    var res = ownrname.split("(");
                    $("#owner_names" + str).text(res['0']);
                    $("#ownerdoc" + str).modal('show');
                }

                function open_owner_doc_upload_img(str) {
                    var ownrname = $("#ownername_img" + str).text();
                    var res = ownrname.split("(");
                    $("#owner_names_img" + str).text(res['0']);
                    $("#ownerdoc_img" + str).modal('show');
                }
            </script>

            <script>
                $(".idproof").change(function() {
                    var selectedText = $(this).find("option:selected").text();
                    var selectedid = $(this).attr('id');
                    var res = selectedid.split("f");
                    var owner_id = 'owner_names' + res['1'];
                    var owner_names = $("#" + owner_id).text();
                    var id_proof_name = owner_names + '(' + selectedText + ')';
                    $("#doc_for" + res['1']).val(id_proof_name);

                });
            </script>

            <script type="text/javascript">
                $(document).ready(function() {
                    $("#last_payment_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['pdf']) == -1) {
                            $("#last_payment_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#last_payment_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#agreement_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['pdf']) == -1) {
                            $("#agreement_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#agreement_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#connection_form_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['pdf']) == -1) {
                            $("#connection_form_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#connection_form_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#registration_certificate_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['pdf']) == -1) {
                            $("#registration_certificate_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#registration_certificate_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#firm_agreement_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['pdf']) == -1) {
                            $("#firm_agreement_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#firm_agreement_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#consumer_photo_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['jpg', 'jpeg', 'png']) == -1) {
                            $("#consumer_photo_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#consumer_photo_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#photo_id_proof_doc_path").change(function() {
                        var input = this;
                        var ext = $(this).val().split('.').pop().toLowerCase();
                        if ($.inArray(ext, ['pdf']) == -1) {
                            $("#photo_id_proof_doc_path").val("");
                            alert('invalid document type');
                        }
                        if (input.files[0].size > 30720) {
                            $("#photo_id_proof_doc_path").val("");
                            alert("Try to upload file less than 30MB");
                        }
                        keyDownNormal(input);
                    });
                    $("#btn_payment_receipt").click(function() {
                        var process = true;
                        var last_payment_doc_path = $("#last_payment_doc_path").val();
                        if (last_payment_doc_path == '') {
                            $("#last_payment_doc_path").css({
                                "border-color": "red"
                            });
                            $("#last_payment_doc_path").focus();
                            process = false;
                        }
                        return process;
                    });
                    $("#btn_agreement").click(function() {
                        var process = true;
                        var agreement_doc_path = $("#agreement_doc_path").val();
                        if (agreement_doc_path == '') {
                            $("#agreement_doc_path").css({
                                "border-color": "red"
                            });
                            $("#agreement_doc_path").focus();
                            process = false;
                        }
                        return process;
                    });
                    $("#btn_owner_doc").click(function() {
                        var process = true;
                        var consumer_photo_doc_path = $("#consumer_photo_doc_path").val();
                        if (consumer_photo_doc_path == '') {
                            $("#consumer_photo_doc_path").css({
                                "border-color": "red"
                            });
                            $("#consumer_photo_doc_path").focus();
                            process = false;
                        }
                        var owner_doc_mstr_id = $("#owner_doc_mstr_id").val();
                        if (owner_doc_mstr_id == '') {
                            $("#owner_doc_mstr_id").css({
                                "border-color": "red"
                            });
                            $("#owner_doc_mstr_id").focus();
                            process = false;
                        }

                        var photo_id_proof_doc_path = $("#photo_id_proof_doc_path").val();
                        if (photo_id_proof_doc_path == '') {
                            $("#photo_id_proof_doc_path").css({
                                "border-color": "red"
                            });
                            $("#photo_id_proof_doc_path").focus();
                            process = false;
                        }
                        return process;
                    });
                    $("#btn_connection_form").click(function() {
                        var process = true;
                        var connection_form_doc_path = $("#connection_form_doc_path").val();
                        if (connection_form_doc_path == '') {
                            $("#connection_form_doc_path").css({
                                "border-color": "red"
                            });
                            $("#connection_form_doc_path").focus();
                            process = false;
                        }
                        return process;
                    });
                    $("#btn_registration_certificate").click(function() {
                        var process = true;
                        var registration_certificate_doc_path = $("#registration_certificate_doc_path").val();
                        if (registration_certificate_doc_path == '') {
                            $("#registration_certificate_doc_path").css({
                                "border-color": "red"
                            });
                            $("#registration_certificate_doc_path").focus();
                            process = false;
                        }
                        return process;
                    });


                    $("#btn_firm_agreement").click(function() {
                        var process = true;
                        var firm_agreement_doc_path = $("#firm_agreement_doc_path").val();
                        if (firm_agreement_doc_path == '') {
                            $("#firm_agreement_doc_path").css({
                                "border-color": "red"
                            });
                            $("#firm_agreement_doc_path").focus();
                            process = false;
                        }
                        return process;
                    });
                    $("#last_payment_doc_path").change(function() {
                        $(this).css('border-color', '');
                    });
                    $("#connection_form_doc_path").change(function() {
                        $(this).css('border-color', '');
                    });
                    $("#photo_id_proof_doc_path").change(function() {
                        $(this).css('border-color', '');
                    });
                    $("#registration_certificate_doc_path").change(function() {
                        $(this).css('border-color', '');
                    });
                    $("#firm_agreement_doc_path").change(function() {
                        $(this).css('border-color', '');
                    });
                    $("#agreement_doc_path").change(function() {
                        $(this).css('border-color', '');
                    });

                });
            </script>

            <script>
                function ext(inputs, count) {
                    var input = inputs;
                    //var text = document.getElementById(count).innerHTML;
                    var ext = $(inputs).val().split('.').pop().toLowerCase();
                    if ($.inArray(ext, ['pdf']) == -1) {
                        document.getElementById(inputs.id + "sms").innerHTML = 'invalid document type';
                        $(inputs).val('');
                        return;
                    } else if (input.files[0].size > 2097152) {
                        document.getElementById(inputs.id + "sms").innerHTML = 'Try to upload file less than 2MB';
                        //alert("Try to upload file less than 2MB"); 
                        $(inputs).val('');
                        return;
                    } else {
                        document.getElementById(inputs.id + "sms").innerHTML = '';
                    }
                    //keyDownNormal(input);
                }

                function ext2(inputs, count) {
                    var input = inputs;
                    //var text = document.getElementById(count).innerHTML;
                    var ext = $(inputs).val().split('.').pop().toLowerCase();
                    if ($.inArray(ext, ['jpg', 'png']) == -1) {
                        document.getElementById(inputs.id + "sms").innerHTML = 'invalid document type! only jpg or png allowed!';
                        $(inputs).val('');
                        return;
                    } else if (input.files[0].size > 2097152) {
                        document.getElementById(inputs.id + "sms").innerHTML = 'Try to upload file less than 2MB';
                        //alert("Try to upload file less than 2MB"); 
                        $(inputs).val('');
                        return;
                    } else {
                        document.getElementById(inputs.id + "sms").innerHTML = '';
                    }
                    //keyDownNormal(input);
                }

                function openWin() {
                    window.open("<?php echo base_url('Trade_Apply_Licence/updateApplicationAtAnyStage/' . md5($owner_list[0]['apply_licence_id'])) ?>", "_blank", "width=800,height=800");
                }
            </script>


            <script>
                function removeButton() {
                    // return false;
                    var conf = confirm('Are you sure to perform this action.\n Application will be sent on level');
                    if (conf) {
                        $('#sendToLevelButton').hide();
                        $('#myloader').css('display', 'block');
                        location.replace("<?= base_url('TradeDocument/send_rmc/' . $apply_licence_id); ?>");

                    }


                }
            </script>