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
                <div class="panel-control">
                </div>
                <h5 class="panel-title">Ward Wise Rain Water Harvesting Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="reportTable" class="table table-striped table-bordered text-sm">
                            <thead>
								<tr>
                                    <th>Sl. No.</th>
                                    <th>Ward No.</th>
                                    <th>Total Household</th>
                                    <th>Total Household Should be Water Harvesting</th>
                                    <th>Rain Water harvesting <br/>
                                        done (area<3228) </th>
                                    <th>Rain Water harvesting <br/>
                                        done (area>=3228) </th>
                                    <th>Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if (isset($posts)) {
                                    $i=1;
                                    $total_ho = 0;
                                    $total_sho = 0;
                                    $total_rainw = 0;
                                    $remaining = 0;
                                    foreach ($posts as $result)
                                    {
                                        $total_ho = $total_ho + $result["total_household"];
                                        $total_sho = $total_sho + $result["should_harvesting"];
                                        $total_rainw = $total_rainw + $result["greaterorequal_area_3228"];
                                        $remaining = $remaining + $result["remaining"];
                            ?>
								<tr>
                                    <td><?=$i;?></td>
                                    <td><?=$result["ward_no"];?></td>
                                    <td><?=$result["total_household"];?></td>
                                    <td><?=$result["should_harvesting"];?></td>
                                    <td><?=$result["less_area_3228"];?></td>
                                    <td><?=$result["greaterorequal_area_3228"];?></td>
                                    <td><?=$result["remaining"];?></td>
                                </tr>
                            <?php   $i++;
                                    }
                                    ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" align="right"><strong>Total</strong></td>
                                                <td><strong><?=$total_ho;?></strong></td>
                                                <td><strong><?=$total_sho;?></strong></td>
                                                <td>&nbsp;</td>
                                                <td><strong><?=$total_rainw;?></strong></td>
                                                <td><strong><?=$remaining;?></strong></td>
                                            </tr>
                                        </tfoot>
                                    <?php
                                } ?>
                            
                            </tbody>
                        </table>
                        <?=isset($posts['count'])?pagination($posts['count']):null;?>
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
$("#excel_export_ajax").click(function() {
    try{
        $.ajax({
            type:"POST",
            url: "<?=base_url();?>/prop_report/HoldingWithElectricityDetail",
            dataType: "json",
            data: {
                "excel":1
            },
            beforeSend: function() {
                modelInfo("Please Wait, Report is generating...");
                $("#excel_export_ajax").val("Please Wait...");
                $("#loadingDiv").show();
            },
            success:function(data){
                console.log(data);
                var filename = data.generatecsvreports;
                window.open('<?=base_url();?>/writable/'+filename).opener = null;
                $("#loadingDiv").hide();
                $("#excel_export_ajax").val("EXCEL EXPORT");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                $("#loadingDiv").hide();
                $("#excel_export_ajax").val("EXCEL EXPORT");
            }
        });
    }catch (err) {
        alert(err.message);
    }
});
</script>
