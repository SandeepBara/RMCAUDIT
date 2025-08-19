<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->

<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Ward Wise Demand</h5>
            </div>
            <div class="panel-body">
                <form method="GET" action="<?=base_url();?>/prop_report/remainingDemandPropertyDtl?page=1">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label text-bold">Ward No.</label>
                                <div class="has-success">
                                    <select id='ward_mstr_id' name="ward_mstr_id" class="form-control">
                                        <option value=''>ALL</option>
                                    <?php
                                    if (isset($wardList)) {
                                        foreach ($wardList as $list) {
                                    ?>
                                        <option value='<?=$list['id'];?>' <?=(isset($ward_mstr_id) && $ward_mstr_id==$list['id'])?"SELECTED='SELECTED'":"";?>><?=$list['ward_no'];?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div class="has-success">
                                    <input type="submit" id="btn_search" class="btn btn-primary btn-block btn-sm" value="SEARCH" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">                            
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div class="has-success ">
                                    <input type="button" id="excel_export_ajax" class="btn btn-primary btn-block btn-sm" value="EXCEL EXPORT" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if (isset($result)) { ?>
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No.</th>
										<th>Unique House No.</th>
                                        <th>Owner Name</th>
                                        <th>Mobile no.</th>
                                        <th>Address</th>
                                        <th>From FY</th>
                                        <th>From QTR</th>
                                        <th>Upto FY</th>
                                        <th>Upto QTR</th>
                                        <th>Total Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = $offset;
                                foreach ($result AS $key=>$list) {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$list['ward_no']?></td>
                                        <td><?=$list['holding_no']?></td>
                                        <td><?=$list['new_holding_no']?></td>
                                        <td><?=$list['owner_name']?></td>
                                        <td><?=$list['mobile_no']?></td>
                                        <td><?=$list['prop_address']?></td>
                                        <td><?=$list['from_fyear']?></td>
                                        <td><?=$list['from_qtr']?></td>
                                        <td><?=$list['upto_fyear']?></td>
                                        <td><?=$list['upto_qtr']?></td>
                                        <td><?=number_format($list['demand_amt'], 2)?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>

                            <?=pagination($pager??0);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
$("#btn_search").click(function() {
    $("#btn_search").val("LOADING ...");
});

$("#excel_export_ajax").click(function() {
    try{
        $.ajax({
            type:"POST",
            url: "<?=base_url();?>/prop_report/remainingDemandPropertyDtlAjax",
            dataType: "json",
            data: {
                "ward_mstr_id":$("#ward_mstr_id").val(),
            },
            beforeSend: function() {
                modelInfo("Please Wait, Report is generating...");
                $("#excel_export_ajax").val("Please Wait...");
                $("#loadingDiv").show();
            },
            success:function(data){
                //var filename = data.generatecsvreports;
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
