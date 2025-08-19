
<?=$this->include('layout_vertical/popup_header');?>
<link href="<?=base_url();?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/nifty.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/demo/nifty-demo-icons.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/bootstrap4-toggle/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/css/common.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>  
<link href="<?= base_url(''); ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#"><?=$type??"";?></a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"> Records</h3>
            </div>
            <div class="panel-body table-responsive">
                <table id="empTable" class="table table-striped table-bordered text-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ward No.</th>
                            <th>Application No.</th>
                            <th>Firm Name </th>
                            <th>Apply Date</th>
                            <th>License No.</th>
                            <th>License Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count =0;
                        foreach($result as $val){
                            ?>
                                <tr>
                                    <td><?=++$count;?></td>
                                    <td><?=$val["ward_no"]??"";?></td>
                                    <td><?=$val["application_no"]??"";?></td>
                                    <td><?=$val["firm_name"]??"";?></td>
                                    <td><?=$val["apply_date"]??"";?></td>
                                    <td><?=$val["license_no"]??"";?></td>
                                    <td><?=$val["license_date"]??"";?></td>
                                </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>      
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
    $('#empTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        buttons: [
            'pageLength',
            {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
            }, 
            {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
            }
        ]
    });
});
</script>
