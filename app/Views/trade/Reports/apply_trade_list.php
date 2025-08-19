<?= $this->include('layout_vertical/header'); ?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
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
            <li class="active">Trade Apply List </li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Search Apply Trade Licence </h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" id="myForm" method="get">
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-1">
                                <label class="control-label" for="from_date"><b>From Date</b><span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    placeholder="From Date"
                                    value="<?= (isset($from_date)) ? $from_date : date('Y-m-d'); ?>"
                                    max="<?= date('Y-m-d'); ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="control-label" for="upto_date"><b>Upto Date</b> <span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="upto_date" name="upto_date" class="form-control"
                                    placeholder="Upto Date"
                                    value="<?= (isset($upto_date)) ? $upto_date : date('Y-m-d'); ?>"
                                    max="<?= date('Y-m-d'); ?>">
                            </div>

                            <div class="col-md-1">
                                <label class="control-label" for="ward_mstr_id"><b>Ward No.</b><span
                                        class="text-danger"></span> </label>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="ward_mstr_id" id="ward_mstr_id">
                                    <option value="">ALL</option>
                                    <?php
                                    if (isset($ward)) {
                                        foreach ($ward as $val):
                                            ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if (isset($ward_mstr_id) && $ward_mstr_id == $val['id']) {
                                                   echo "selected";
                                               } ?>><?php echo $val['ward_no']; ?>
                                            </option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <!-- FILTER 2 -->

                        <div class="row" style="margin-top: 1rem;">

                            <div class="col-md-1">
                                <label class="control-label" for="category"><b>Category</b><span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="category" id="category">
                                    <option value="">ALL</option>
                                    <?php
                                    if (isset($category_list)) {
                                        foreach ($category_list as $val): ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if (isset($category) && $category == $val['id']) {
                                                   echo "selected";
                                               } ?>>
                                                <?php echo $val['category_type']; ?>
                                            </option>
                                        <?php endforeach;
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label" for="application_type"><b>Applicaton Type</b> <span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="application_type" id="application_type">
                                    <option value="">ALL</option>
                                    <?php
                                    if (isset($application_list)) {
                                        foreach ($application_list as $val):
                                            ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if (isset($application_type) && $application_type == $val['id']) {
                                                   echo "selected";
                                               } ?>>
                                                <?php echo $val['application_type']; ?>
                                            </option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <div class="row" style="margin-top: 1rem;">
                            <div class="col-md-5 text-right">
                                <input type="submit" name="search" id="btn_search" class="btn btn-primary"
                                    value="SEARCH" />
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result <span><?= $results['count'] ?></span></h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tradeList" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Application</th>
                                        <th>Owner Name</th>
                                        <th>Firm Name</th>
                                        <th>Application Type</th>
                                        <th>Mobile No</th>
                                        <th>Apply Date</th>
                                        <th>Holding No</th>
                                        <th>Apply From</th>
                                        <th>Employee Name</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if (isset($results) && count($results) > 0) {

                                        foreach ($results['result'] as $item):
                                            // print_var($item);
                                            ?>

                                            <tr>
                                                <td><?= ++$count; ?></td>
                                                <td><?= $item["ward_no"]; ?></td>
                                                <td><?= $item["application_no"]; ?></td>
                                                <td><?= $item["firm_owner_name"]; ?></td>
                                                <td><?= $item["firm_name"]; ?></td>
                                                <td><?= $item["application_type"]; ?></td>
                                                <td><?= $item["mobile"]; ?></td>
                                                <td><?= $item["apply_date"]; ?></td>
                                                <td><?= $item["holding_no"]; ?></td>
                                                <td><?= $item["apply_from"]; ?></td>
                                                <td><?= $item["employee_name"] ?? ""; ?></td>

                                                <td>
                                                    <a href="<?php echo base_url('trade_da/view_application_details/' . md5($item['id'])); ?>"
                                                        type="button" class="btn btn-primary" style="color:white;">View</a>
                                                </td>

                                            </tr>
                                            <?php
                                        endforeach;
                                    } else {

                                        ?>

                                        <tr>
                                            <td colspan="8">Data Not Available!!</td>
                                        </tr>

                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?= pagination(isset($pager) ? $pager : 0); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">

</script>

<script>
    $(document).ready(function () {
        $('#tradeList').DataTable({
            responsive: false,
            dom: 'Bfrtip',
            "bLengthChange": false,
            "bInfo": false,
            "bLengthChange": false,
            "bPaginate": false,
            lengthMenu: false,
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function (e, dt, node, config) {

                        var gerUrl = '<?= base_url(); ?>/Trade_report/trade_apply_list?export=true&';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function (i, field) {
                            gerUrl += (field.name + '=' + field.value) + "&";
                        });
                        window.open(gerUrl).opener = null;
                    }
                },
                {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Report",
                    download: 'open',
                    footer: { text: '' },
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                }
            ]
        });
    });
</script>