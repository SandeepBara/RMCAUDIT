<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->

<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">SAF/Property Individual Demand And Collecton</h5>
            </div>
            <div class="panel-body">
                <form method="POST" action="<?=base_url();?>/prop_report/safPropIndividualDemandAndCollecton?page=1">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label text-bold">Ward No.</label>
                                <div class="has-success pad-btm">
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
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label text-bold">Search Keyword <a class="btn-link text-semibold add-tooltip" data-toggle="tooltip" href="#" data-placement="bottom" data-original-title="Holding No., Unique House No., Saf No., Applicant Name, Mobile No."> (<i class="fa fa-info"></i>)</a></label>
                                <div class="has-success pad-btm">
                                    <input type="text" id="search_param" name="search_param" class="form-control" value="<?=(isset($search_param))?$search_param:"";?>" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">                            
                            <!-- <input type="button" id="excel_export" class="btn btn-primary btn-block btn-sm" value="EXCEL EXPORT" /> -->
                            <input type="button" id="excel_export_ajax" class="btn btn-primary btn-block btn-sm" value="EXCEL EXPORT" />
                            <input type="submit" id="btn_search" class="btn btn-primary btn-block btn-sm" value="SEARCH" />
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
                                        <th>Saf No.</th>
                                        <th>Applicant Name</th>
                                        <th>Mobile no.</th>
                                        <th>Address</th>
                                        <th>Assessment Type</th>
                                        <th>Usage Type</th>
                                        <th>Construction Type</th>
                                        <th class="text-center">Demand Before 20-21</th>
                                        <th class="text-center">Demand for 20-21</th>
                                        <th class="text-center">Demand for 21-22</th>
                                        <th class="text-center">Total Demand</th>
                                        <th class="text-center">Collection Before 20-21</th>
                                        <th class="text-center">Collection for 20-21</th>
                                        <th class="text-center">Collection for 21-22</th>
                                        <th class="text-center">Total Collection</th>
                                        <th>Penalty</th>
                                        <th>Rebate</th>
                                        <th>Advance</th>
                                        <th>Adjust</th>
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
                                        <td><?=$list['saf_no']?></td>
                                        <td><?=$list['owner_name']?></td>
                                        <td><?=$list['mobile_no']?></td>
                                        <td><?=$list['prop_address']?></td>
                                        <td><?=$list['assessment_type']?></td>
                                        <td><?=$list['usage_type']?></td>
                                        <td><?=$list['construction_type']?></td>
                                        <td><?=$list['super_arrear_demand']?></td>
                                        <td><?=$list['arrear_demand']?></td>
                                        <td><?=$list['current_demand']?></td>
                                        <td><?=number_format($list['total_demand'], 2)?></td>
                                        <td><?=$list['super_arrear_collection']?></td>
                                        <td><?=$list['arrear_collection']?></td>
                                        <td><?=$list['current_collection']?></td>
                                        <td><?=number_format($list['total_collection'], 2)?></td>
                                        <td><?=$list['penalty']?></td>
                                        <td><?=$list['rebate']?></td>
                                        <td><?=$list['advance']?></td>
                                        <td><?=$list['adjust']?></td>
                                        <td><?=number_format($list['total_due'], 2)?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>

                            <?=pagination($pager);?>
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
$("#excel_export").click(function() {
    window.open('<?=base_url();?>/prop_report/safPropIndividualDemandAndCollecton/excel').opener = null;
    this.focus();
});

$("#excel_export_ajax").click(function() {
    try{
        $.ajax({
            type:"POST",
            url: "<?=base_url();?>/prop_report/safPropIndividualDemandAndCollectonAjax",
            dataType: "json",
            data: {
                "ward_mstr_id":$("#ward_mstr_id").val(),
                "search_param":$("#search_param").val(),
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
