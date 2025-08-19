<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Government DCB Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="reportTable" class="table table-striped table-bordered text-sm">
                            <thead>
								<tr>
                                    <th>Arrear Demand</th>
                                    <th>Current Demand</th>
                                    <th>Arrear Collection</th>
                                    <th>Current Collection</th>
                                    <th>Arrear Balance</th>
                                    <th>Current Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if (isset($result)) {
                            ?>
								<tr>
                                    <td><?=$result["arrear_demand"];?></td>
                                    <td><?=$result["current_demand"];?></td>
                                    <td><?=$result["arear_collection"];?></td>
                                    <td><?=$result["current_collection"];?></td>
                                    <td><?=$result["arearbalance"];?></td>
                                    <td><?=$result["curbalance"];?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
    $('#reportTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        "paging": false,
        "info": false,
        "searching":false,
        buttons: [
            {
                text: 'excel',
                extend: "excel",
                title: "Government DCB Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5 ] }
            }
        ]
    });
});
</script>
