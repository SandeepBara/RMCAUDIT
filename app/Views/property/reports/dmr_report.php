<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Decision Making Report</h5>
            </div>
            <div class="panel-body">
                <form method="POST" action="<?=base_url();?>/prop_report/dmr">
                    <div class="row">
                        <label class="control-label text-bold col-md-1">Ward No.</label>
                        <div class="col-md-5">
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
                        <div class="col-md-2">                     
                            <input type="submit" id="btn_search" class="btn btn-primary btn-block btn-sm" value="SEARCH" />
                        </div>
                        <div class="col-md-2">                     
                            <input type="button" id="excel_export" class="btn btn-primary btn-block btn-sm" value="EXCEL EXPORT" />
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
                                        <th class="text-center">Ward No.</th>
                                        <th class="text-center">ULB Provided Legacy Data (3)</th>
										<th class="text-center">New Assessment (4.1)</th>
                                        <th class="text-center">Re Assessment (4.2)</th>
                                        <th class="text-center">Mutation (4.3)</th>
                                        <th class="text-center">Total SAF 5=4</th>
                                        <th class="text-center">To Be Reassessed From DB(6) </th>
                                        <th class="text-center">Total HH as per Records (7=5+6)</th>
                                        <th class="text-center">% of non reassessed (8=3/6) </th>
                                        <th class="text-center">Fully Digitized SAF From DB(9) </th>
                                        <th class="text-center">SAM (10)</th>
                                        <th class="text-center">SAM % 11=10/9% </th>
                                        <th class="text-center">Geo Tagging from DB (12)</th>
                                        <th class="text-center">Geo Tagging % (13=12/9)</th>
                                        <th class="text-center">pure No of Comm. HH (14.1)</th>
                                        <th class="text-center">Mixed (14.2)</th>
                                        <th class="text-center">Govt Building (14.3)</th>
                                        <th class="text-center">Vacant Land (14.4) </th>
                                        <th class="text-center">Pure No of Res. HH (14.5)</th>
                                        <th class="text-center">BTC</th>
                                        <th class="text-center">FAM Pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 0;
                                    $total_ulb_legacy_data_3 = 0;
                                    $total_new_assessment_4_1 = 0;
                                    $total_re_assessment_4_2 = 0;
                                    $total_mutation_4_3 = 0;
                                    $total_saf_5 = 0;
                                    $total_to_be_reassessed_6 = 0;
                                    $total_holding_7 = 0;
                                    $non_assessed_percentage_8 = 0;
                                    $fully_digitized_saf_9 = 0;
                                    $total_sam_10 = 0;
                                    $sam_percentage_11 = 0;
                                    $tota_geo_tagging_12 = 0;
                                    $geo_tagging_percentage_13 = 0;
                                    $total_pure_commercial_14_1 = 0;
                                    $total_mix_saf_14_2 = 0;
                                    $total_pure_government_14_3 = 0;
                                    $total_vacant_land_14_4 = 0;
                                    $total_pure_residencial_14_5 = 0;
                                    $total_btc = 0;
                                    $total_fam_pending = 0;

                                    foreach ($result AS $key=>$list) {
                                        $total_ulb_legacy_data_3 += $list['total_ulb_legacy_data_3'];
                                        $total_new_assessment_4_1 += $list['total_new_assessment_4_1'];
                                        $total_re_assessment_4_2 += $list['total_re_assessment_4_2'];
                                        $total_mutation_4_3 += $list['total_mutation_4_3'];
                                        $total_saf_5 += $list['total_saf_5'];
                                        $total_to_be_reassessed_6 += $list['total_to_be_reassessed_6'];
                                        $total_holding_7 += $list['total_holding_7'];
                                        $non_assessed_percentage_8 += $list['non_assessed_percentage_8'];
                                        $fully_digitized_saf_9 += $list['fully_digitized_saf_9'];
                                        $total_sam_10 += $list['total_sam_10'];
                                        $sam_percentage_11 += $list['sam_percentage_11'];
                                        $tota_geo_tagging_12 += $list['tota_geo_tagging_12'];
                                        $geo_tagging_percentage_13 += $list['geo_tagging_percentage_13'];
                                        $total_pure_commercial_14_1 += $list['total_pure_commercial_14_1'];
                                        $total_mix_saf_14_2 += $list['total_mix_saf_14_2'];
                                        $total_pure_government_14_3 += $list['total_pure_government_14_3'];
                                        $total_vacant_land_14_4 += $list['total_vacant_land_14_4'];
                                        $total_pure_residencial_14_5 += $list['total_pure_residencial_14_5'];
                                        $total_btc += $list['total_btc'];
                                        $total_fam_pending += $list['total_fam_pending'];
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$list['ward_no'];?></td>
                                        <td><?=$list['total_ulb_legacy_data_3'];?></td>
                                        <td><?=$list['total_new_assessment_4_1'];?></td>
                                        <td><?=$list['total_re_assessment_4_2'];?></td>
                                        <td><?=$list['total_mutation_4_3'];?></td>
                                        <td><?=$list['total_saf_5'];?></td>
                                        <td><?=$list['total_to_be_reassessed_6'];?></td>
                                        <td><?=$list['total_holding_7'];?></td>
                                        <td><?=$list['non_assessed_percentage_8'];?></td>
                                        <td><?=$list['fully_digitized_saf_9'];?></td>
                                        <td><?=$list['total_sam_10'];?></td>
                                        <td><?=$list['sam_percentage_11'];?></td>
                                        <td><?=$list['tota_geo_tagging_12'];?></td>
                                        <td><?=$list['geo_tagging_percentage_13'];?></td>
                                        <td><?=$list['total_pure_commercial_14_1'];?></td>
                                        <td><?=$list['total_mix_saf_14_2'];?></td>
                                        <td><?=$list['total_pure_government_14_3'];?></td>
                                        <td><?=$list['total_vacant_land_14_4'];?></td>
                                        <td><?=$list['total_pure_residencial_14_5'];?></td>
                                        <td><?=$list['total_btc'];?></td>
                                        <td><?=$list['total_fam_pending'];?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">Total</td>
                                        <td><?=$total_ulb_legacy_data_3;?></td>
                                        <td><?=$total_new_assessment_4_1;?></td>
                                        <td><?=$total_re_assessment_4_2;?></td>
                                        <td><?=$total_mutation_4_3;?></td>
                                        <td><?=$total_saf_5;?></td>
                                        <td><?=$total_to_be_reassessed_6;?></td>
                                        <td><?=$total_holding_7;?></td>
                                        <td><?=$non_assessed_percentage_8;?></td>
                                        <td><?=$fully_digitized_saf_9;?></td>
                                        <td><?=$total_sam_10;?></td>
                                        <td><?=$sam_percentage_11;?></td>
                                        <td><?=$tota_geo_tagging_12;?></td>
                                        <td><?=$geo_tagging_percentage_13;?></td>
                                        <td><?=$total_pure_commercial_14_1;?></td>
                                        <td><?=$total_mix_saf_14_2;?></td>
                                        <td><?=$total_pure_government_14_3;?></td>
                                        <td><?=$total_vacant_land_14_4;?></td>
                                        <td><?=$total_pure_residencial_14_5;?></td>
                                        <td><?=$total_btc;?></td>
                                        <td><?=$total_fam_pending;?></td>
                                    </tr>
                                </tfoot>
                            </table>
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
    var ward_mstr_id = $("#ward_mstr_id").val();
    if(ward_mstr_id=='') {
        ward_mstr_id="ALL";
    }
    window.open('<?=base_url();?>/prop_report/dmr_excel/'+ward_mstr_id);//.opener = null;
    this.focus();
});
</script>
