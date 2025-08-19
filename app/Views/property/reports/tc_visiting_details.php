<?= $this->include('layout_vertical/popup_header'); ?>

<style>
    #footer {
        display: none;
    }
</style>

<link href="<?= base_url(); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.min.js"></script>

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">TC - <?php echo $tc_name[0]['emp_name'] ?? ''; ?> Visiting Report</li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"> <?= $heading ?? "TC"; ?></h3>
            </div>
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-responsive" id="demo_dt_basic">
                    <thead>
                        <?php if ($moduleId === '2'): ?>
                            <?php if ($remarksId === '7'): ?>
                                <tr>
                                    <td>Sl</td>
                                    <td>Holding No</td>
                                    <td>New Ward No</td>
                                    <td>Address</td>
                                    <td>Owner Name</td>
                                    <td>Mobile No</td>
                                   
                                    <td>View</td>
                                </tr>
                            <?php elseif (in_array($remarksId, [8, 9, 10, 11, 12])): ?>
                                <tr>
                                    <td>Sl</td>
                                    <td>Holding No</td>
                                    <td>New Ward No</td>
                                    <td>Address</td>
                                    <td>Owner Name</td>
                                    <td>Mobile No</td>
                                    <!-- <td>Payment Status</td> -->
                                    <td>Action</td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No data available to show</td>
                                </tr>
                            <?php endif; ?>
                        <?php elseif ($moduleId === '1'): ?>
                            <tr>
                                <td>SL</td>
                                <td>SAF NO</td>
                                <td>New Ward No</td>
                                <td>Owner Name</td>
                                <td>Address</td>
                                <td>Mobile No</td>
                                <td>Payment Status</td>
                                <td>View</td>
                            </tr>
                        <?php elseif ($moduleId === '3'): ?>
                            <tr>
                                <td>SL</td>
                                <td>Ward No</td>
                                <td>Consumer No</td>
                                <td>Owner Name</td>
                                <td>Mobile No</td>
                                <td>View</td>
                            </tr>
                        <?php elseif ($moduleId === '4'): ?>
                            <tr>
                                <td>SL</td>
                                <td>Application No</td>
                                <td>Ward No</td>
                                <td>Owner Name</td>
                                <td>Mobile No</td>
                                <td>Firm Address</td>
                                <td>License No</td>
                                <td>View</td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No data available to show</td>
                            </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php if ($moduleId === '2'): ?>
                            <?php if (!empty($posts)): ?>
                                <?php foreach ($posts as $index => $post): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $post['new_holding_no'] ?></td>
                                        <td><?= $post['ward_no'] ?></td>
                                        <td><?= $post['prop_address'] ?></td>
                                        <td><?= $post['owner_name'] ?></td>
                                        <td><?= $post['mobile_no'] ?></td>
                                        <?php if ($remarksId === '7'): ?>
                                           
                                            <td><a class="btn btn-primary" href="<?= base_url() . '/propDtl/full/' . $post['id'] ?>"
                                                    target="_blank">View</a></td>
                                        <?php elseif (in_array($remarksId, [8, 9, 10, 11, 12])): ?>
                                            <td><a class="btn btn-primary" href="<?= base_url() . '/propDtl/full/' . $post['id'] ?>"
                                                    target="_blank">View</a></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No records found</td>
                                </tr>
                            <?php endif; ?>
                        <?php elseif ($moduleId === '1' || $moduleId === '3' || $moduleId === '4'): ?>
                            <?php if (!empty($posts)): ?>
                                <?php foreach ($posts as $index => $post): ?>
                                    <tr>
                                        <?php if ($moduleId === '1'): ?>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $post['saf_no'] ?></td>
                                            <td><?= $post['ward_no'] ?></td>
                                            <td><?= $post['owner_name'] ?></td>
                                            <td><?= $post['prop_address'] ?></td>
                                            <td><?= $post['mobile_no'] ?></td>
                                            <td><?= $post['payment_status'] ?></td>
                                            <td><a href="<?= base_url() . '/safdtl/full/' . $post['saf_dtl_id'] ?>" target="_blank"
                                                    class="btn btn-primary">View</a></td>
                                        <?php elseif ($moduleId === '3'): ?>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $post['ward_no'] ?></td>
                                            <td><?= $post['consumer_no'] ?></td>
                                            <td><?= $post['owner_name'] ?></td>
                                            <td><?= $post['mobile_no'] ?></td>
                                            <td><a href="<?= base_url() . '/WaterViewConsumerDetails/index/' . md5($post['consumer_id']) ?>"
                                                    target="_blank" class="btn btn-primary">View</a></td>
                                        <?php elseif ($moduleId === '4'): ?>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $post['application_no'] ?></td>
                                            <td><?= $post['ward_no'] ?></td>
                                            <td><?= $post['owner_name'] ?></td>
                                            <td><?= $post['mobile_no'] ?></td>
                                            <td><?= $post['address'] ?></td>
                                            <td><?= $post['license_no'] ?></td>
                                            <td><a href="<?= base_url() . '/view_application_details' . '/' . $post['application_id'] ?>"
                                                    class="btn btn-primary" target="_blank">view</a></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No records found</td>
                                </tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No data available to show</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>


            </div>
        </div>



    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<script src="<?= base_url(); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script>
    var columns = [0, 1, 2, 3, 4, 5];
    const table = document.getElementById('demo_dt_basic');

    const rows = table.getElementsByTagName('tr');
    const totalColumns = rows[0].getElementsByTagName('td').length;
    var columnsArray = [];

    // Loop through each column
    for (let col = 0; col < totalColumns; col++) {
        columnsArray.push(col);
    }
    // console.log(columnsArray);

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
                footer: { text: '' },
                exportOptions: { columns: columnsArray }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: columnsArray }
            }]
    });
</script>
