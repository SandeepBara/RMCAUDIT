<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

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
            <li><a href="#">Report</a></li>
            <li class="active">Water Physical Status</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Physical Status</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" method="post">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label class="control-label" for="fromDate"><b>From Date</b> <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="date" id="fromDate" name="fromDate" class="form-control" placeholder="From Date" value="<?= (isset($fromDate)) ? $fromDate : date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label" for="uptoDate"><b>To Date</b><span class="text-danger">*</span> </label>
                                            <div class="input-group">
                                                <input type="date" id="uptoDate" name="uptoDate" class="form-control" placeholder="To Date" value="<?= (isset($uptoDate)) ? $uptoDate : date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label" for="Ward_id"><b>Ward No</b><span class="text-danger">*</span> </label>
                                            <select id="Ward_id" name="Ward_id" class="form-control">
                                                <option value="">ALL</option>
                                                <?php foreach ($wardList as $value): ?>
                                                    <option value="<?= $value['id'] ?>" <?= (isset($Ward_id)) ? $Ward_id == $value["id"] ? "SELECTED" : "" : ""; ?>><?= $value['ward_no']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th rowspan ="3">Ward No</th>
                                            <th rowspan ="2">Assessed HH</th>
                                            <th colspan ="6">Total No of water connection</th>
                                            <th colspan ="2">Type Of water connection</th>
                                            <th colspan ="3">Bill served status</th>
                                        </tr>
                                        <tr>
                                            <th >Total Legacy water connection</th>
                                            <th >Total Approved Online water connection</th>
                                            <th colspan ="2">Water Connection converted through JUIDCO - WCA</th>
                                            <th>Total Disconnected</th>
                                            <th >Total Water Connection</th>
                                            <th >Metered Water Connection</th>
                                            <th >Non Metered Water Connection</th>
                                            <th >For Metered Water Connection</th>
                                            <th >For Non Metered Water Connection</th>
                                            <th >Total No. Of connection in which Bill Served</th>
                                        </tr>
                                        <tr>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>(4 A) New Connection</th>
                                            <th>(4 B) Replaced Connection</th>
                                            <th>5</th>
                                            <th>(6=2+3+4A-5)</th>
                                            <th>7</th>
                                            <th>8</th>
                                            <th>9</th>
                                            <th>10</th>
                                            <th>11=9+10</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!isset($result)):
                                        ?>
                                            <tr>
                                                <td colspan="11" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                            <?php else:
                                            $i = 0;

                                            foreach ($result as $value):
                                            ?>
                                                <tr>
                                                    <td><?= $value['ward_no']?? "N/A"; ?></td>
                                                    <td><?= $value['total_hh']?? "N/A"; ?></td>
                                                    <td><?= $value['legacy_consumer']?? "N/A"; ?></td>
                                                    <td><?= $value['total_approved']??"N/A"; ?></td>
                                                    <td><?= $value['judco_consumer']??"N/A"; ?></td>
                                                    <td><?= 0; ?></td>
                                                    <td><?= $value['deactivated_consumer']??"N/A"; ?></td>
                                                    <td><?= ($value['legacy_consumer'] + $value['total_approved'] +  $value['judco_consumer']); ?></td>
                                                    <td><?= $value['meter_connection']?? "N/A"; ?></td>
                                                    <td><?= $value['non_meter_connection']?? "N/A"; ?></td>

                                                    <td><?= $value['meter_connection_demand']?? "N/A"; ?></td>
                                                    <td><?= $value['non_meter_connection_demand']?? "N/A"; ?></td>
                                                    <td><?= ($value['meter_connection_demand'] + $value['non_meter_connection_demand']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif;  ?>
                                    </tbody>
                                    <?php if(isset($total)): ?>
                                        <tfoot class="text-bold">
                                        <?php 
                                        foreach($total as $value):
                                        ?>
                                            <tr>
                                            <td>Total</td>
                                            <td><?= $value['total_hh']?? "N/A"; ?></td>
                                            <td><?= $value['legacy_consumer']?? "N/A"; ?></td>
                                            <td><?= $value['total_approved']??"N/A"; ?></td>
                                            <td><?= $value['judco_consumer']??"N/A"; ?></td>
                                            <td><?= 0; ?></td>
                                            <td><?= $value['deactivated_consumer']??"N/A"; ?></td>
                                            <td><?= ($value['legacy_consumer'] + $value['total_approved'] +  $value['judco_consumer']); ?></td>
                                            <td><?= $value['meter_connection']?? "N/A"; ?></td>
                                            <td><?= $value['non_meter_connection']?? "N/A"; ?></td>

                                            <td><?= $value['meter_connection_demand']?? "N/A"; ?></td>
                                            <td><?= $value['non_meter_connection_demand']?? "N/A"; ?></td>
                                            <td><?= ($value['meter_connection_demand'] + $value['non_meter_connection_demand']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tfoot>
                                    <?php endif ;?>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->
</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Report",
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12]
                    }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Report",
                    download: 'open',
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12]
                    }
                }
            ]
        });
    });
    $('#btn_search').click(function() {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if (from_date == "") {
            $("#from_date").css({
                "border-color": "red"
            });
            $("#from_date").focus();
            return false;
        }
        if (to_date == "") {
            $("#to_date").css({
                "border-color": "red"
            });
            $("#to_date").focus();
            return false;
        }
        if (to_date < from_date) {
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({
                "border-color": "red"
            });
            $("#to_date").focus();
            return false;
        }
    });
    $("#from_date").change(function() {
        $(this).css('border-color', '');
    });
    $("#to_date").change(function() {
        $(this).css('border-color', '');
    });
</script>