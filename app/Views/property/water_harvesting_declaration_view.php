<?= $this->include('layout_vertical/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="<?=base_url("/WaterHarvesting/declarationList")?>">Water Harvesting Declaration List</a></li>
            <li class="active">Water Harvesting Declaration</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark"> <!-- Water Harvesting Declaration -->
            <div class="panel-heading">
                <h5 class="panel-title">Water Harvesting Declaration</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Date of Completion of Water Harvesting Structure</label>
                    <label class="col-md-9 pad-btm"><?=$water_hrvting_application_no;?></label>
                </div>
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Reference No.</label>
                    <label class="col-md-3 pad-btm"><?=$water_hrvting_application_no;?></label>
                    <label class="col-md-3 pad-btm text-bold">15 Digits Holding No./ SAF No.</label>
                    <label class="col-md-3 pad-btm"><?=$holding_saf_sam_no;?></label>
                </div>
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Name</label>
                    <label class="col-md-3 pad-btm"><?=$owner_name;?></label>
                    <label class="col-md-3 pad-btm text-bold">Guardian Name</label>
                    <label class="col-md-3 pad-btm"><?=$guardian_name;?></label>
                </div>
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Ward No.</label>
                    <label class="col-md-3 pad-btm"><?=$ward_no;?></label>
                    <label class="col-md-3 pad-btm text-bold">	Mobile No.</label>
                    <label class="col-md-3 pad-btm"><?=$mobile_no;?></label>
                </div>
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Name of Building and Address</label>
                    <label class="col-md-9 pad-btm"><?=$prop_address;?></label>
                </div>
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Date of Completion of Water Harvesting Structure</label>
                    <label class="col-md-9 pad-btm"><?=$water_harvesting_completion_date;?></label>
                </div>
                <?php if ($done_before_17_wh=="YES") { ?>
                <div class="row">
                    <label class="col-md-3 pad-btm text-bold">Application Date By RMC</label>
                    <label class="col-md-9 pad-btm"><?=$rmc_recommended_application_date;?></label>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark"> <!-- Uploaded Document -->
            <div class="panel-heading">
                <h5 class="panel-title">Uploaded Document</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if ($water_harvesting_form!="") { ?>
                                    <tr>
                                        <td>1</td>
                                        <td class="text-bold">Upload Water Harvesting Declaration Form</td>
                                        <td>
                                            <a href='<?= base_url(); ?>/getImageLink.php?path=<?= $water_harvesting_form; ?>' target="_blank" class="btn btn-primary">VIEW</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if ($water_harvesting_img!="") { ?>
                                    <tr>
                                        <td>2</td>
                                        <td class="text-bold">Upload Water Harvesting Image</td>
                                        <td>
                                            <a href='<?= base_url(); ?>/getImageLink.php?path=<?= $water_harvesting_img; ?>' target="_blank" class="btn btn-primary">VIEW</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (!is_null($rmc_recommended_file) && !empty($rmc_recommended_file) && $rmc_recommended_file!="") { ?>
                                    <tr>
                                        <td>1</td>
                                        <td class="text-bold">RMC Recommended File</td>
                                        <td>
                                            <a href='<?= base_url(); ?>/getImageLink.php?path=<?= $rmc_recommended_file; ?>' target="_blank" class="btn btn-primary">VIEW</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($status==2 && isset($level_remarks_result)) { ?> <!-- Rejected Panel -->
            <div class="panel panel-bordered panel-danger">
                <div class="panel-heading">
                    <h5 class="panel-title">Rejected</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3 pad-btm">Rejected by</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["emp_name"];?></label>
                        <label class="col-md-3 pad-btm">Rejected Date</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["created_on"];?></label>
                    </div>
                </div>
            </div>
        <?php } else if ($approval_status==1 && isset($level_remarks_result)) { ?> <!-- Approved Panel -->
            <div class="panel panel-bordered panel-success">
                <div class="panel-heading">
                    <h5 class="panel-title">Approved</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3 pad-btm">Apply Date</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["emp_name"];?></label>
                        <label class="col-md-3 pad-btm">Approved Date</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["created_on"];?></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3 pad-btm">Approved by</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["emp_name"];?></label>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="panel panel-bordered panel-warning"> <!-- Pending Panel -->
                <div class="panel-heading">
                    <h5 class="panel-title">Pending</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3 pad-btm">Tax Collector</label>
                        <?php 
                        $pending_at = "STC";
                        if ($level_remarks_result[count($level_remarks_result)-1]["receiver_user_type_mstr_id"]==7) {
                            $pending_at = "RMC TC";
                        }
                        ?>
                        <label class="col-md-3 pad-btm text-bold"><?=$pending_at;?></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3 pad-btm">Received Date</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["created_on"];?></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3 pad-btm">Pending days</label>
                        <label class="col-md-3 pad-btm text-bold"><?=$level_remarks_result[count($level_remarks_result)-1]["created_on"];?></label>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="panel panel-bordered panel-dark"> <!-- Remarks Panel -->
            <div class="panel-heading">
                <h5 class="panel-title">Remarks</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>Employee Details</th>
                                        <th>Remarks</th>
                                        <th>Datetime</th>
                                    </tr>
                                </thead>
                                <?php 
                                $remarksIsAvalabile = false;
                                if (isset($level_remarks_result)) { 
                                    foreach ($level_remarks_result as $key => $level_dtl) {
                                        if ($level_dtl["emp_name"]!="") {
                                            $remarksIsAvalabile = true;
                                ?>
                                    <tr>
                                        <td><?=$level_dtl["emp_name"];?></td>
                                        <td><?=$level_dtl["msg_body"];?></td>
                                        <td><?=$level_dtl["created_on"];?></td>
                                    </tr>
                                <?php 
                                        }
                                    }
                                } 
                                if ($remarksIsAvalabile==false) {
                                ?>
                                    <tr>
                                        <td colspan="3" class="text-danger text-danger">Remarks Not Found</td>
                                    </tr>
                                <?php 
                                }
                                ?>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>