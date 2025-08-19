<?= $this->include('layout_mobi/header');?>


<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
    <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <div class="panel-control">
                        <button type="button" class="btn btn-info btn_wait_load" onclick="history.back()">Back</button>
                    </div>
                    <h3 class="panel-title">Self Assessment Form</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Application No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <?=($saf_no!="")?$saf_no:"N/A";?>
                        </div>

                        <label class="col-md-3">Assessment Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <?php
                        
                        echo $assessment_type;
                        if($assessment_type=='Mutation')
                        {
                            echo '  ('.$transfer_mode.')';
                        }
                        ?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-3">Apply Date</label>
                        <div class="col-md-3 text-bold pad-btm">
                        <?=($apply_date!="")?$apply_date:"N/A";?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-3">Does the property being assessed has any previous Holding Number? </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($has_previous_holding_no))?($has_previous_holding_no=='t')?"Yes":"No":"N/A";?>
                        </div>
                        <?php
                        if($has_previous_holding_no=='t')
                        {
                            ?>
                            
                            <label class="col-md-3">Previous Holding No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=($holding_no);?>
                            </div>
                            
                            <?php
                        }
                        ?>
                        
                    </div>
                    <hr />
                    <div id="has_prev_holding_dtl_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($is_owner_changed))?($is_owner_changed==1)?"YES":"NO":"N/A";?>
                            </div>
                            <div id="is_owner_changed_tran_property_hide_show" class="<?=(isset($is_owner_changed))?($is_owner_changed==0)?"hidden":"":"";?>">
                                <label class="col-md-3">Mode of transfer of property from previous Holding Owner</label>
                                <div class="col-md-3 text-bold pad-btm">
                                        <?=(isset($transfer_mode))?$transfer_mode:"N/A";?>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="row">
                        <label class="col-md-3">Ward No</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($ward_no))?$ward_no:"N/A";?>
                        </div>
                        <label class="col-md-3">Ownership Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($ownership_type))?$ownership_type:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Property Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($property_type))?$property_type:"N/A";?>
                        </div>
                    </div>
                    <div class="<?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id!=3))?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Appartment Name</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($appartment_name))?$appartment_name:"N/A";?>
                            </div>
                            <label class="col-md-3">Registry Date</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($flat_registry_date))?$flat_registry_date:"N/A";?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Road Type</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($road_type))?$road_type:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Zone</label>
                        <div class="col-md-3 text-bold">
                            <?=(isset($zone_mstr_id))?($zone_mstr_id==1)?"Zone 1":"Zone 2":"N/A";?>
                        </div>
                    </div>
                </div>
            </div>
        <!-------Owner Details-------->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Applicant Image</th>
                                            <!-- <th>Applicant Document</th> -->
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                <?php
                                if (isset($saf_owner_detail))  {
                                    foreach ($saf_owner_detail as $owner_detail) {  
                                ?>
                                        <tr>
                                            <td>
                                                <?=$owner_detail['owner_name'];?>
                                            </td>
                                            <td>
                                            <?php 
                                            if ($owner_detail['applicant_img_dtl']) {
                                                $path = $owner_detail['applicant_img_dtl']['doc_path'];
                                            ?>
                                                    <img src="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" class="img-lg" onclick="imageLoadToModel('<?=$path;?>')" />
                                            <?php 
                                            } else {
                                                echo "<span class='text-warning text-bold'>Document is not uploaded.</span>";
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Document Details</h3>
                </div>
                <div class="panel-body">
                    <table  class="table table-bordered text-sm">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>#</th>
                                <th>Document Name</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- ONLY FOR SAF_FORM -->
                        <?php
                            $i=0;
                            foreach($doc_list as $doc) {
                                if($doc['other_doc']=='saf_form'){
                                ?>
                                <tr >
                                    <td><?=++$i;?></td>
                                    <td style="font-weight:600"><?=$doc["doc_name"];?></td>
                                    <td>
                                        <?php
                                        $extention = strtolower(explode('.', $doc["doc_path"])[1]);
                                        if ($extention=="pdf") { ?>
                                                <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class="img-lg" onclick="print_the_web_page('<?=base_url();?>/writable/uploads/<?=$doc["doc_path"];?>')" />
                                        <?php } else { ?>
                                                <img src="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" class="img-lg" onclick="print_the_web_page('<?=base_url();?>/writable/uploads/<?=$doc["doc_path"];?>')" />
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                                }
                            }
                            ?>
                            <!-- FOR ALL DOCUMENT EXCEPT SAF_FORM -->
                            <?php
                            $i=1;
                            foreach($doc_list as $doc) {
                                if($doc['other_doc']=='saf_form' || $doc['other_doc']=='applicant_image'){
                                    continue;
                                 }
                                ?>
                                <tr >
                                    <td><?=++$i;?></td>
                                    <td><div style="font-weight:600"><?=$doc["doc_name"];?></div> <div><?= isset($doc['owner_name'])?'('.$doc['owner_name'].')':'' ?></div></td>
                                    <td>
                                        <?php
                                        $extention = strtolower(explode('.', $doc["doc_path"])[1]);
                                        if ($extention=="pdf") { ?>
                                            <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class="img-lg" onclick="print_the_web_page('<?=base_url();?>/writable/uploads/<?=$doc["doc_path"];?>')" />
                                        <?php } else { ?>
                                            <img src="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" class="img-lg" onclick="imageLoadToModel('<?=$doc["doc_path"];?>')" />
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->

<!--saf property image Bootstrap Modal-->
<div id="saf_geotag_image-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body" id="saf_geotag_image_body">
            </div>
        </div>
    </div>
</div>
<!--End saf property image Bootstrap Modal-->
<?= $this->include('layout_mobi/footer');?>
<script type="text/javascript">
var imageLoadToModel = function(image_path) {
    $("#saf_geotag_image-modal").modal('show');
    var appendData = "<div class='row'>";
    appendData += '<div class="col-md-12">';
    appendData += '<img src="<?=base_url();?>/getImageLink.php?path='+image_path+'" style="height: 400px; width: 100%;" loading="lazy" />'
    appendData += '</div>';
    appendData += "</div>";
    $("#saf_geotag_image_body").html(appendData);
}
function print_the_web_page(pdfUrl) {
    AndroidInterface.printTheWebPage(pdfUrl);
}
</script>