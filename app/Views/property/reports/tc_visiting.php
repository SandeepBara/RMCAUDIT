<?= $this->include('layout_vertical/header'); ?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">TC VISITING REPORT</h5>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <form method="post" id="myForm">
                        <div class="row">
                            <label class="col-md-2 text-bold">From Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?php if (isset($from_date) && !empty($from_date)) {
                                    echo $from_date;
                                } else {
                                    echo "";
                                } ?>" />
                            </div>
                            <label class="col-md-2 text-bold">Upto Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?php if (isset($upto_date) && !empty($upto_date)) {
                                    echo $upto_date;
                                } else {
                                    echo "";
                                } ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 text-bold">Collector Name</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='collector_id' name='collector_id' class="form-control select2">
                                    <option value=''>ALL</option>
                                    <?php
                                    if (isset($empDtlList)) {
                                        foreach ($empDtlList as $list) {
                                            ?>
                                            <option value='<?= $list['id']; ?>' <?php if (isset($collector_id) && $list['id'] == $collector_id) {
                                                  echo "selected='selected'";
                                              } ?>>
                                                <?= $list['emp_name'] . " " . $list['middle_name'] . " " . $list['last_name'] . " (" . $list['user_type'] . ")"; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_id' name='ward_id' class="form-control select2">
                                    <option value=''>ALL</option>
                                    <?php
                                    if (isset($wardList)) {
                                        foreach ($wardList as $list) {
                                            ?>
                                            <option value='<?= $list['id']; ?>' <?php if (isset($ward_id) && $list['id'] == $ward_id) {
                                                  echo "selected='selected'";
                                              } ?>><?= $list['ward_no']; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php $collector_id; ?>
        <div class="panel panel-dark">
            <div class="panel-heading">
                <div class="panel-control">
                    <input type="button" id="excel_export_ajax" class="btn btn-primary btn-sm" value="EXCEL EXPORT"
                        onclick="exportReportToExcel(this);" />

                </div>
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="reportTable" class="table table-striped table-bordered text-sm table-responsive"
                            style="display: block">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th colspan='6' style="background-color:#3a444e; color:#fff; text-align:center;">
                                        Property</th>
                                    <th></th>
                                    <th colspan='5' style="background-color:#3a444e; color:#fff; text-align:center;">Saf
                                    </th>
                                    <th></th>
                                    <th colspan='7' style="background-color:#3a444e; color:#fff; text-align:center;">
                                        Water</th>
                                    <th></th>
                                    <th colspan='5' style="background-color:#3a444e; color:#fff; text-align:center;">
                                        Trade</th>
                                </tr>
                                <tr>
                                    <th>Name Tax Collector</th>
                                    <th>Payment Received</th>
                                    <th>Already Paid</th>
                                    <th>Not agreed to pay</th>
                                    <th>Pay later</th>
                                    <th>Door Locked</th>
                                    <th>SAF Not Done</th>
                                    <th></th>
                                    <th>SAF Apply</th>
                                    <th>Payment Received</th>
                                    <th>Land Loard Not Available</th>
                                    <th>Updation required</th>
                                    <th>Geo tag Done</th>
                                    <th></th>
                                    <th>Water Bill Generate</th>
                                    <th>Water Bill Collection</th>
                                    <th>Water Not Paid</th>
                                    <th>Citizen Pay Later</th>
                                    <th>Water Charge not paid as no water supply</th>
                                    <th>Apply For Water New Connection</th>
                                    <th>Total Amount</th>
                                    <th></th>
                                    <th>New Trade Apply</th>
                                    <th>Renewal Apply</th>
                                    <th>Deniel Apply</th>
                                    <th>Trade Collection</th>
                                    <th>Apply Later</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($posts)) {
                                    // print_r($post);
                                    foreach ($posts as $result) {
                                        // print_r($result);
                                        ?>
                                        <tr>
                                            <td><?= $result['full_name']??$result["emp_name"]; ?></td>
                                            <td>
                                                <a href="#"
                                                    <?= $result["payment_received"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',2,7)"'):"" ?>><?= $result["payment_received"]; ?>
                                                </a>
                                            </td>
                                            <td><a href="#" <?= $result["already_paid"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',2,8)"'):"" ?>><?= $result["already_paid"]; ?></a></td>
                                            <td><a href="#" <?= $result["not_agary_to_pay"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',2,9)"'):"" ?>><?= $result["not_agary_to_pay"]; ?></a></td>
                                            <td><a href="#"  <?= $result["pay_leter"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',2,10)"'):"" ?>><?= $result["pay_leter"]; ?></a></td>
                                            <td><a href="#"  <?= $result["door_locked"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',2,11)"'):"" ?>><?= $result["door_locked"]; ?></a></td>
                                            <td><a href="#"  <?= $result["saf_not_done"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',2,12)"'):"" ?>><?= $result["saf_not_done"]; ?></a></td>
                                            <td></td>
                                            <td><a href="#" <?= $result["saf_apply"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',1,1)"'):"" ?>><?= $result["saf_apply"]; ?></a></td>
                                            <td><a href="#" <?= $result["spayment_received"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',1,2)"'):"" ?>><?= $result["spayment_received"]; ?></a></td>
                                            <td><a href="#" <?= $result["land_lord_not_avaliable"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',1,3)"'):"" ?>><?= $result["land_lord_not_avaliable"]; ?></a></td>
                                            <td><a href="#" <?= $result["updation_required"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',1,4)"'):"" ?>><?= $result["updation_required"]; ?></a></td>
                                            <td><a href="#" <?= $result["geotag_done"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',1,5)"'):"" ?>><?= $result["geotag_done"]; ?></a></td>
                                            <td></td>
                                            <td><a href="#" <?= $result["water_bill_generate"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',3,14)"'):"" ?>><?= $result["water_bill_generate"]; ?></a></td>
                                            <td><a href="#" <?= $result["bill_collection"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',3,15)"'):"" ?>><?= $result["bill_collection"]; ?></a></td>
                                            <td><a href="#" <?= $result["not_paid"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',3,16)"'):"" ?>><?= $result["not_paid"]; ?></a></td>
                                            <td><a href="#" <?= $result["pay_later"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',3,17)"'):"" ?>><?= $result["pay_later"]; ?></a></td>
                                            <td><a href="#" <?= $result["not_paid_as_no_water_supply"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',3,18)"'):"" ?>><?= $result["not_paid_as_no_water_supply"]; ?></a> </td>
                                            <td><a href="#" <?= $result["new_connction_apply"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',3,19)"'):"" ?>><?= $result["new_connction_apply"]; ?></a></td>
                                            <td>0</td>
                                            <td></td>
                                            <td><a href="#" <?= $result["new_trade_apply"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',4,21)"'):"" ?>><?= $result["new_trade_apply"]; ?></a></td>
                                            <td><a href="#" <?= $result["renewal_apply"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',4,22)"'):"" ?>><?= $result["renewal_apply"]; ?></a></td>
                                            <td><a href="#" <?= $result["deniel_apply"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',4,23)"'):"" ?>><?= $result["deniel_apply"]; ?></a></td>
                                            <td><a href="#" <?= $result["trade_collection"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',4,25)"'):"" ?>><?= $result["trade_collection"]; ?></a></td>
                                            <td><a href="#" <?= $result["apply_later"]>0 ? ('onclick="openNewWindow('.$result['employeeid'].',4,26)"'):"" ?>><?= $result["apply_later"]; ?></a></td>
                                        </tr>
                                        <?php
                                    }
                                } ?>

                            </tbody>
                        </table>
                        <?= isset($posts['count']) ? pagination($posts['count']) : null; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<!-- <script src="https://unpkg.com/table-to-excel@1.0.1/dist/tableToExcel.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script type="text/javascript">
    // function exportReportToExcel() {
    //     let table = document.getElementById("reportTable");
    //     TableToExcel.convert(table[0], {
    //         name: `file.xlsx`,
    //         sheet: {
    //             name: 'Sheet 1'
    //         }
    //     });
    // }

    function exportReportToExcel() {
        let table = document.getElementById("reportTable");

        if (!table) {
            console.error("Table element not found!");
            return;
        }

        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.table_to_sheet(table);

        XLSX.utils.book_append_sheet(wb, ws, "Sheet 1");
        XLSX.writeFile(wb, "Report.xlsx");
    }

    function openNewWindow(empDtlId, moduleId, remarksId) {
        var getUrl = "<?= base_url('/prop_report/tcVisitingDetails/'); ?>?parent=true&empDtlId=" + empDtlId + "&moduleId=" + moduleId + "&remarksId=" + remarksId + "&";
        var formData = $("#myForm").serializeArray();
        $.each(formData, function (i, field) {
            getUrl += (field.name + '=' + field.value) + "&";
        });
        window.open(getUrl).opener = null;
    }
</script>