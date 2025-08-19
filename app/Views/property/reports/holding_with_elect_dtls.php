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
                    <input type="button" id="excel_export_ajax" class="btn btn-primary btn-sm" value="EXCEL EXPORT" />
                        
                </div>
                <h5 class="panel-title">Holding With Electricity Detail Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="reportTable" class="table table-striped table-bordered text-sm">
                            <thead>
								<tr>
                                    <th>SAF NO.</th>
                                    <th>Ward No.</th>
                                    <th>Holding No.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Mobile No.</th>
                                    <th>Elect. Consumer No.</th>
                                    <th>Elect. Account No.</th>
                                    <th>Elect. Bind Book No.</th>
                                    <th>Holding Type</th>
                                    <th>Buildup Area</th>
                                    <th>Property Type</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if (isset($posts['result'], $posts['result'])) {
                                    foreach ($posts['result'] as $result)
                                        {
                            ?>
								<tr>
                                    <td><?=$result["saf_no"];?></td>
                                    <td><?=$result["ward_no"];?></td>
                                    <td><?=$result["holding_no"];?></td>
                                    <td><?=$result["owner_name"];?></td>
                                    <td><?=$result["prop_address"];?></td>
                                    <td><?=$result["owner_mobile"];?></td>
                                    <td><?=$result["elect_consumer_no"];?></td>
                                    <td><?=$result["elect_acc_no"];?></td>
                                    <td><?=$result["elect_bind_book_no"];?></td>
                                    <td><?=$result["holding_type"];?></td>
                                    <td><?=$result["builtup_area"];?></td>
                                    <td><?=$result["property_type"];?></td>
                                </tr>
                            <?php 
                                        }
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
                var filename = data;
                window.open('<?=base_url();?>/'+filename).opener = null;
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
