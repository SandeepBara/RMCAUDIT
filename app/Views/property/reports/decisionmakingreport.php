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
                <h5 class="panel-title">Decision Making Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="reportTable" class="table table-striped table-bordered text-sm">
                                <thead>
									<tr>
                                        <th>ward no</th>
                                        <th>total legacy data 3</th>
                                        <th>total new assessment 4.1</th>
                                        <th>total_re_assessment 4.2</th>
                                        <th>total mutation 4.3</th>
                                        <th>total saf 5</th>
                                        <th>total to be reassessed 6</th>
                                        <th>total holding 7</th>
                                        <th>non assessed percentage 8</th>
                                        <th>fully digitized saf 9</th>
                                        <th>total sam 10</th>
                                        <th>sam percentage 11</th>
                                        <th>tota geo_tagging 12</th>
                                        <th>geo tagging percentage 13</th>
                                        <th>total pure commercial 14.1</th>
                                        <th>total mix saf 14.2</th>
                                        <th>total pure government 14.3</th>
                                        <th>total pure residencial 14.4</th>
                                        <th>total btc</th>
                                        <th>total fam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    if (isset($report)) { 
                                        foreach ($report As $key=> $values) {
                                ?>
									<tr>
                                        <td><?=$values["ward_no"];?></td>
                                        <td><?=$values["total_ulb_legacy_data_3"];?></td>
                                        <td><?=$values["total_new_assessment_4_1"];?></td>
                                        <td><?=$values["total_re_assessment_4_2"];?></td>
                                        <td><?=$values["total_mutation_4_3"];?></td>
                                        <td><?=$values["total_saf_5"];?></td>
                                        <td><?=$values["total_to_be_reassessed_6"];?></td>
                                        <td><?=$values["total_holding_7"];?></td>
                                        <td><?=round($values["non_assessed_percentage_8"], 2);?></td>
                                        <td><?=$values["fully_digitized_saf_9"];?></td>
                                        <td><?=$values["total_sam_10"];?></td>
                                        <td><?=$values["sam_percentage_11"];?></td>
                                        <td><?=$values["tota_geo_tagging_12"];?></td>
                                        <td><?=$values["geo_tagging_percentage_13"];?></td>
                                        <td><?=$values["total_pure_commercial_14_1"];?></td>
                                        <td><?=$values["total_mix_saf_14_2"];?></td>
                                        <td><?=$values["total_pure_government_14_3"];?></td>
                                        <td><?=$values["total_pure_residencial_14_5"];?></td>
                                        <td><?=$values["total_btc"];?></td>
                                        <td><?=$values["total_fam"];?></td>
                                    </tr>
                                <?php }} ?>
                                </tbody>
                            </table>
                        </div>
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
                title: "Decision Making Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19 ] }
            }
        ]
    });
});
</script>
