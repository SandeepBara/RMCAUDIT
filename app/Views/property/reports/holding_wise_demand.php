<?= $this->include('layout_vertical/header'); ?>
<style type="text/css">
    .error {
        color: red;
    }

    .buttons-page-length {
        display: none !important;
    }
</style>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->

        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li class="active">Demand Holding List</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Search Parameter</h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" id="myform" method="get">
                    <div class="row">

                        <!-- Ward -->
                        <div class="col-md-1">
                            <label class="control-label"><b>Ward No.</b></label>
                        </div>
                        <div class="col-md-3">
                            <select name="ward_mstr_id[]" id="ward_mstr_id" class="form-control select2"
                                multiple="multiple">

                                <?php foreach ($wardList as $val): ?>
                                    <option value="<?= $val['id']; ?>" <?= (isset($ward_mstr_id) && in_array($val['id'], $ward_mstr_id) ? 'selected' : '') ?>>
                                        <?= $val['ward_no']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Paid Status -->
                        <div class="col-md-1">
                            <label class="control-label"><b>Paid Status</b></label>
                        </div>
                        <div class="col-md-3">
                            <select name="paid_status" id="paid_status" class="form-control">
                                <option value="">All</option>
                                <option value="1" <?= (isset($paid_status) && $paid_status === '1') ? 'selected' : '' ?>>
                                    Yes</option>
                                <option value="0" <?= (isset($paid_status) && $paid_status === '0') ? 'selected' : '' ?>>No
                                </option>
                            </select>
                        </div>

                        <!-- Property Type -->
                        <div class="col-md-1">
                            <label class="control-label"><b>Property Type</b></label>
                        </div>
                        <div class="col-md-3">
                            <select name="property_type_mstr_id[]" id="property_type_mstr_id"
                                class="form-control select2" multiple="multiple">
                                <?php foreach ($propertyTypeList as $val): ?>
                                    <option value="<?= $val['id']; ?>" <?= (isset($property_type_mstr_id) && in_array($val["id"], $property_type_mstr_id) ? 'selected' : '') ?>>
                                        <?= $val['property_type']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <br>

                    <div class="row">
                        <!-- Area -->
                        <div class="col-md-1">
                            <label class="control-label" data-toggle="tooltip" data-placement="top"
                                title="Show properties where the plot area is less than or equal to the entered value">
                                <b>Area Of Plot</b>
                            </label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="area_of_plot" class="form-control" placeholder="Enter area"
                                value="<?= isset($area_of_plot) ? htmlspecialchars($area_of_plot) : '' ?>">
                        </div>

                        <!-- RWH Status -->
                        <div class="col-md-1">
                            <label class="control-label"><b>RWH Status</b></label>
                        </div>
                        <div class="col-md-3">
                            <select name="rwh_status" id="rwh_status" class="form-control">
                                <option value="">All</option>
                                <option value="1" <?= (isset($rwh_status) && $rwh_status === '1') ? 'selected' : '' ?>>Yes
                                </option>
                                <option value="0" <?= (isset($rwh_status) && $rwh_status === '0') ? 'selected' : '' ?>>No
                                </option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" name="btn_search" type="submit">Search</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">List</h5>
            </div>
            <div class="panel-body table-responsive">

                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>New Ward No.</th>
                            <th>15 Digits Holding No.</th>
                            <th>Property Type</th>
                            <th>Owner Name.</th>
                            <th>Area Of Plot</th>
                            <th>Address</th>
                            <th>Demand Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($results)) {
                            $count = $offset ?? 0;
                            foreach ($results as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= ++$count; ?></td>
                                    <td><?= $value["new_ward_no"]; ?></td>
                                    <td><?= $value["new_holding_no"]; ?></td>
                                    <td><?= $value["property_type"]; ?></td>
                                    <td><?= $value["owner_name"]; ?></td>
                                    <td><?= $value["area_of_plot"]; ?></td>
                                    <td><?= $value["prop_address"]; ?></td>
                                    <td><?= round($value["current_year_demand"], 2); ?></td>
                                </tr>
                                <?php
                            }
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

        <!--===================================================-->
        <!--End page content-->
    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->


    <?= $this->include('layout_vertical/footer'); ?>
    <!--DataTables [ OPTIONAL ]-->

    <script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>/public/assets/plugins/select2/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".select2").select2();
            $('#demo_dt_basic').DataTable({
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

                            var gerUrl = '<?= base_url(); ?>/prop_report/HoldingDemand?export=true&';
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