<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->

<!-- <?= print_var(($denialDetails)) ?> -->
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
            <li class="active"><?= $status ?></li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title"><?= $status ?></h5>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="table-responsive">
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ward.</th>
                                            <th>Firm Name</th>emp_name
                                            <th>Firm Owner Name </th>
                                            <th>Tax Collector Name </th>
                                            <th>Denial Apply Date </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($denialDetails)) :
                                            if (empty($denialDetails)) :
                                        ?>
                                                <tr>
                                                    <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                                <?php else :
                                                $i = 0;
                                                foreach ($denialDetails as $value) :
                                                ?>
                                                    <tr>
                                                        <td><?= ++$i; ?></td>
                                                        <td><?= $value["ward"]; ?></td>
                                                        <td><?= $value["firm_name"]; ?></td>
                                                        <td><?= $value["applicant_name"] ? $value["applicant_name"] : "N/A"; ?></td>
                                                        <td><?= $value["full_emp_name"] ? $value["full_emp_name"]: "N/A"; ?></td>
                                                        <td><?= $value["created_on"] ? date('d-m-Y H:i:s A',strtotime($value["created_on"])): "N/A"; ?></td>
                                                        <td>
                                                            <a class="btn btn-primary" href="<?php echo base_url('TradeDenialApplyReports/viewDetails/' . md5($value['id'])); ?>" role="button">View</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif;  ?>
                                        <?php endif;  ?>
                                    </tbody>
                                </table>
                                <hr>
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
<script src="<?= base_url(); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
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
                        columns: [0, 1, 2, 3,4]
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
                        columns: [0, 1, 2, 3,4]
                    }
                }
            ]
        });
    });
</script>