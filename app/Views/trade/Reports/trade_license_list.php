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
            <li class="active">Trade Licence List </li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Search Trade Licence </h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" id="myForm" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label" for="ward_mstr_id"><b>Ward No.</b><span
                                        class="text-danger"></span> </label>
                            </div>
                            <div class="col-md-4">
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

                            <div class="col-md-2">
                                <label class="control-label" for="applicaton_type"><b>Application Type</b><span
                                        class="text-danger"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control " name="applicaton_type" id="applicaton_type">
                                    <option value="">ALL</option>
                                    <?php
                                    if (isset($application_list)) {
                                        foreach ($application_list as $item):
                                            ?>
                                            <option value="<?= $item['id']; ?>"
                                                <?= ($applicaton_type ?? 0) == $item['id'] ? "selected" : ""; ?>>
                                                <?= $item['application_type']; ?></option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>
                            

                        </div>

                        <div class="row" style="margin-top:1rem;">
                        <div class="col-md-2">
                                <label class="control-label" for="nature_of_bussiness"><b>Nature Of Business</b><span
                                        class="text-danger"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control " name="nature_of_bussiness" id="nature_of_bussiness">
                                    <option value="">ALL</option>
                                    <?php
                                    if (isset($trade_items)) {
                                        foreach ($trade_items as $busines_code):
                                            ?>
                                            <option value="<?= $busines_code['id']; ?>"
                                                <?= ($nature_of_bussiness ?? 0) == $busines_code['id'] ? "selected" : ""; ?>>
                                                <?= $busines_code['trade_item']; ?></option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- OR BUSINESS CODE -->
                            <div class="col-md-2">
                                <label class="control-label" for="nature_of_bussiness">OR <b>Business Code</b><span
                                        class="text-danger"></span>
                                </label>
                            </div>
                            <div class="col-md-4">
                            <input type="text" id="business_code" name="business_code" class="form-control"
                                    placeholder="Business Code"
                                    value="<?= (isset($business_code)) ? $business_code : ""; ?>"
                                    >
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
                <h5 class="panel-title">Result</h5>
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
                                        <th>Licence No.</th>
                                        <th>Owner Name</th>
                                        <th>Firm Name</th>
                                        <th>Application Type</th>
                                        <th>Mobile No</th>
                                        <th>Apply Date</th>
                                        <th>Area IN Sq. Ft.</th>
                                        <th>Valid Upto</th>
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
                                                <td><?= $item["license_no"]; ?></td>
                                                <td><?= $item["owner_name"]; ?></td>
                                                <td><?= $item["firm_name"]; ?></td>
                                                <td><?= $item["application_type"]; ?></td>
                                                <td><?= $item["mobile"]; ?></td>
                                                <td><?= $item["apply_date"]; ?></td>
                                                <td><?= $item["area_in_sqft"]; ?></td>
                                                <td><?= $item["valid_upto"]; ?></td>
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const natureOfBusinessSelect = document.getElementById('nature_of_bussiness');
        const businessCodeInput = document.getElementById('business_code');

        // Event listener for select dropdown
        natureOfBusinessSelect.addEventListener('change', function() {
            if (this.value !== "") {
                // Clear the input field when an option is selected
                businessCodeInput.value = "";
            }
        });

        // Event listener for input field
        businessCodeInput.addEventListener('input', function() {
            if (this.value !== "") {
                // Reset the select field when input is typed
                natureOfBusinessSelect.value = "";
            }
        });
    });
</script>

<script>
    $(document).ready(function () {

        // TOGGLE FOR NATURE OF BUSINESS
        var nature_of_bussiness_2 = $('#nature_of_bussiness_2').val();

        if(nature_of_bussiness_2 != ''){

        }

        $('#tradeList').DataTable({
            responsive: false,
            dom: 'Bfrtip',
            "bLengthChange": false, //thought this line could hide the LengthMenu
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

                        var gerUrl = '<?= base_url(); ?>/Trade_report/trade_license_list?export=true&';
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