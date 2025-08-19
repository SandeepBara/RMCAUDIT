<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">SAF Document Upload</a></li>
        <li class="active">SAF Document Upload</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <?php if(isset($err_msg)){ ?>
        <div class="row" >
            <div class="col-md-12">
                <center>
                    <h4 style="color:red;"><?=$err_msg;?></h4>
                </center>
            </div>
        </div>
        <?php }?>
        <!-------Owner Details-------->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Owner Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Upload Document</th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>Guardian Name</th>
                                <th>Mobile No.</th>
                                <th>Aadhar No.</th>
                                <th>PAN No.</th>
                                <th>Email ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($owner_list)) {
                                if (empty($owner_list)) {
                            ?>
                                    <tr>
                                        <td style="text-align:center;"> Data Not Available...</td>
                                    </tr>
                            <?php 
                                } else {
                            ?>
                                    <?php
                                    $i=1;
                                    foreach ($owner_list as $value) {
                                    $j=$i++;
                                    ?>
                                    <tr>
                                        <td>
                                        <?php
                                        if (empty($value['saf_owner_dtl_id'])) {
                                        ?>
                                            <button type="button" class="btn btn-sm btn-info" id="det_click<?=$j;?>" onclick="owner_details(<?=$j;?>);">Upload </button>
                                            <input type="hidden" id="owner_id<?=$j;?>" name="owner_id<?=$j;?>" value="<?=$value['id'];?>"/>
                                        <?php 
                                        } else {
                                        ?>
                                            <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                        <?php 
                                        }
                                        ?>
                                        </td>
                                        <td><?=$value['owner_name'];?><input type="hidden" id="owner_name<?=$j;?>" value="<?=$value['owner_name'];?>"/></td>
                                        <td><?=$value['relation_type'];?><input type="hidden" id="relation_type<?=$j;?>" value="<?=$value['relation_type'];?>"/></td>                            
                                        <td><?=$value['guardian_name'];?><input type="hidden" id="guardian_name<?=$j;?>" value="<?=$value['guardian_name'];?>"/></td>
                                        <td><?=$value['mobile_no'];?><input type="hidden" id="mobile_no<?=$j;?>" value="<?=$value['mobile_no'];?>"/></td>
                                        <td><?=$value['aadhar_no'];?><input type="hidden" id="aadhar_no<?=$j;?>" value="<?=$value['aadhar_no'];?>"/></td>
                                        <td><?=$value['pan_no'];?><input type="hidden" id="pan_no<?=$j;?>" value="<?=$value['pan_no'];?>"/></td>
                                        <td><?=$value['email'];?><input type="hidden" id="email<?=$j;?>" value="<?=$value['email'];?>"/></td>
                                    </tr>
                            <?php 
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <!-------Propery Type-------->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">SAF Form Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Upload Document</th>
                                <th>Upload File</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>
                                    <?php
                                    if(isset($owner_fr_document_exists)):
                                    if(empty($owner_fr_document_exists)):
                                    ?>
                                        <button type="button" class="btn btn-sm btn-info" id="saf_form_doc_click<?=$j;?>" onclick="saf_form_doc(1);"  >Upload </button>
                                    <?php else: ?>
                                        <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                    <?php endif;  ?>
                                    <?php endif;  ?>
                                    </td>
                                    <td> SAF Form </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-------Propery Type-------->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Document Details</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SL. No.</th>
                                <th>Upload Document</th>
                                <th>Upload File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <?php
                                    if(isset($owner_tr_document_exists)):
                                    if(empty($owner_tr_document_exists)):
                                    ?>
                                    <button type="button" class="btn btn-sm btn-info" id="trans_doc_click<?=$j;?>" onclick="trans_doc(1);"  >Upload </button>
                                    <?php else: ?>
                                    <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                    <?php endif;  ?>
                                    <?php endif;  ?>
                                    </td>

                                <td>
                                <?php
                                    if(isset($transfer_mode)):
                                            if(empty($transfer_mode)):
                                    ?>
                                            <span class="text-danger">N/A</span>
                                    <?php else:
                                            foreach ($transfer_mode as $value):
                                                    echo $value["doc_name"].'  ';
                                            endforeach;
                                        endif;  
                                    endif;  ?>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <?php
                                    if(isset($owner_pr_document_exists)):
                                    if(empty($owner_pr_document_exists)):
                                    ?>
                                    <button type="button" class="btn btn-sm btn-info" id="prop_doc_click<?=$j;?>" onclick="prop_doc(1);"  >Upload </button>
                                    <?php else: ?>
                                    <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                    <?php endif;  ?>
                                    <?php endif;  ?>
                                    </td>

                                <td>
                                <?php
                                    if(isset($property_type)):
                                            if(empty($property_type)):
                                    ?>
                                            <span class="text-danger">N/A</span>
                                    <?php else:
                                            foreach ($property_type as $value):
                                                    echo $value["doc_name"].'  ';
                                            endforeach;
                                        endif;  
                                    endif;  ?>
                                </td>
                            </tr>
                        <?php
                        if ($saf_dtl['no_electric_connection']=='t') {
                        ?>
                            <tr>
                                <td>3</td>
                                <td>
                                    <?php
                                    if($no_elect_connection_exists==false):
                                    ?>
                                    <button type="button" class="btn btn-sm btn-info" id="no_electric_connection_doc_click" onclick="no_electric_connection_doc();"  >Upload </button>
                                    <?php else: ?>
                                    <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                    <?php endif;  ?>
                                    </td>

                                <td>
                                    <?php
                                    if (!isset($no_electric_connection_doc_list)) {
                                    ?>
                                        <span class="text-danger">N/A</span>
                                    <?php 
                                    } else {
                                        foreach ($no_electric_connection_doc_list as $value) {
                                            echo $value["doc_name"];
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <?php
                        if ($saf_dtl['prop_type_mstr_id']==3) {
                        ?>
                            <tr>
                                <td>4</td>
                                <td>
                                    <?php
                                    if($flat_exists==false):
                                    ?>
                                    <button type="button" class="btn btn-sm btn-info" id="flat_doc_click" onclick="flat_doc();"  >Upload </button>
                                    <?php else: ?>
                                    <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                    <?php endif;  ?>
                                    </td>

                                <td>
                                    <?php
                                    if (!isset($flat_doc_list)) {
                                    ?>
                                        <span class="text-danger">N/A</span>
                                    <?php 
                                    } else {
                                        $z = 0;
                                        foreach ($flat_doc_list as $value) {
                                            
                                            if($z==0)
                                                echo $value["doc_name"];
                                            else
                                                echo ", ".$value["doc_name"];
                                            $z++;
                                        }
                                    }
                                    ?>
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
        <div class="panel">
            <div class="panel-body text-center">
                <a href="<?php echo base_url('safDemandPayment/saf_property_details/'.md5($saf_dtl['id']));?>" type="button" class="btn btn-primary btn-labeled">View Property Details</a>
                <a href="<?php echo base_url('safDemandPayment/saf_payment_details/'.md5($saf_dtl['id']));?>" type="button" class="btn btn-warning btn-labeled">View Payment Details</a>
                <a href="<?php echo base_url('safDemandPayment/saf_confirm_payment/'.md5($saf_dtl['id']));?>" type="button" class="btn btn-purple btn-labeled">Pay Property Tax Online</a>
            </div>
        </div>
    </div>  
    <!--End page content-->      
</div>
<!--END CONTENT CONTAINER-->
<!-- Owner Modal -->
<div id="owner_details_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Owner Document</h4>
            </div>
            <div class="modal-body">
            <form method="post" enctype="multipart/form-data" action="">
                <input type="hidden" name="saf_owner_dtl_id" id="saf_owner_dtl_id" value="">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" >
                        <tr>
                            <td><b>Name</b></td>
                            <td>:</td>
                            <td id="owner_det_name"></td>
                            <td><b>Relation</b></td>
                            <td>:</td>
                            <td id="relation_det_type"></td>
                            <td><b>Guardian Name</b></td>
                            <td>:</td>
                            <td id="guardian_det_name"></td>
                        </tr>
                        <tr>
                            <td><b>Mobile</b></td>
                            <td>:</td>
                            <td id="mobile_det_no"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Email Id</b></td>
                            <td>:</td>
                            <td id="email_det"></td>
                        </tr>
                        <tr>
                            <td>Applicant Image</td>
                            <td>:</td>
                            <td colspan="3"><img/></td>
                            <td colspan="4"><input type="file" name="applicant_image_path" id="applicant_image_path" class="form-control" accept=".png,.jpg,.jpeg"/></td>
                        </tr>
                        <tr>
                            <td>Document Type</td>
                            <td>:</td>
                            <td colspan="3">
                                <select id="owner_doc_mstr_id" name="owner_doc_mstr_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                        if(isset($other_doc)){
                                        foreach ($other_doc as $values){
                                    ?>
                                    <option value="<?=$values['id']?>" ><?=$values['doc_name']?>
                                    </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                            <td colspan="4"><input type="file" name="owner_doc_path" id="owner_doc_path" class="form-control" accept=".png,.jpg,.jpeg,.pdf" /></td>
                        </tr>
                        <tr>
                            <td colspan="9"><input type="submit" name="btn_owner_doc" value="Save" id="btn_owner_doc" class="btn btn-success"  />  </td>
                        </tr>

                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- SAF Form Document Modal -->
<div id="saf_form_doc_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SAF Form Upload</h4>
            </div>
            <div class="modal-body">
            <form method="post" enctype="multipart/form-data" action="">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" >
                        <tr>
                            <td>Document Type</td>
                            <td>:</td>
                            <td>
                                <select id="saf_form_doc_mstr_id" name="saf_form_doc_mstr_id" class="form-control">
                                    <option value="0">SAF Form</option>
                                </select>
                            </td>
                            <td><input type="file" name="saf_form_doc_path" id="saf_form_doc_path" class="form-control" accept=".png,.jpg,.jpeg,.pdf"/></td>
                        </tr>
                        <tr>
                            <td colspan="9"><input type="submit" name="btn_fr_doc" value="Save" id="btn_fr_doc" class="btn btn-info"  />  </td>
                        </tr>

                    </table>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Transfer Mode Document Modal -->
<div id="trans_doc_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" >
                            <tr>
                                <td>Property Type</td>
                                <td>:</td>
                                <td>
                                    <select id="trans_doc_mstr_id" name="trans_doc_mstr_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                            if(isset($transfer_mode)){
                                            foreach ($transfer_mode as $values){
                                        ?>
                                        <option value="<?=$values['id']?>" ><?=$values['doc_name']?>
                                        </option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td><input type="file" name="trans_doc_path" id="trans_doc_path" class="form-control" accept=".png,.jpg,.jpeg,.pdf"/></td>
                            </tr>
                            <tr>
                                <td colspan="9"><input type="submit" name="btn_tr_doc" value="Save" id="btn_tr_doc" class="btn btn-info"  />  </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Property Type Document Modal -->
<div id="prop_doc_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
            <form method="post" enctype="multipart/form-data" action="">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" >
                        <tr>
                            <td>Property Type</td>
                            <td>:</td>
                            <td>
                                <select id="prop_doc_mstr_id" name="prop_doc_mstr_id" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                        if(isset($property_type)){
                                        foreach ($property_type as $values){
                                    ?>
                                    <option value="<?=$values['id']?>" ><?=$values['doc_name']?>
                                    </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                            <td><input type="file" name="prop_doc_path" id="prop_doc_path" class="form-control" accept=".png,.jpg,.jpeg,.pdf"/></td>
                        </tr>
                        <tr>
                            <td colspan="9"><input type="submit" name="btn_pr_doc" value="Save" id="btn_pr_doc" class="btn btn-info"  />  </td>
                        </tr>

                    </table>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Transfer Mode Document Modal -->
<div id="no_electric_connection_doc_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" >
                            <tr>
                                <td>Document Type</td>
                                <td>:</td>
                                <td>
                                    <input type="text" class="form-control" value="<?php if(isset($no_electric_connection_doc_list)){echo $no_electric_connection_doc_list[0]["doc_name"];}?>" readonly />
                                    <input type="hidden" id="no_electric_connection_doc_id" name="no_electric_connection_doc_id" value="<?php if(isset($no_electric_connection_doc_list)){echo $no_electric_connection_doc_list[0]["id"];}?>" />
                                </td>
                                <td><input type="file" name="no_electric_connection_doc_path" id="no_electric_connection_doc_path" class="form-control" accept=".png,.jpg,.jpeg,.pdf"/></td>
                            </tr>
                            <tr>
                                <td colspan="9"><input type="submit" name="btn_no_electric_connection_upload" value="Save" id="btn_no_electric_connection_upload" class="btn btn-info"  />  </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Transfer Mode Document Modal -->
<div id="flat_doc_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" >
                            <tr>
                                <td>Document Type</td>
                                <td>:</td>
                                <td>
                                    <input type="text" class="form-control" value="<?php if(isset($flat_doc_list)){echo $flat_doc_list[0]["doc_name"];}?>" readonly />
                                    <input type="hidden" id="flat_doc_id" name="flat_doc_id" value="<?php if(isset($flat_doc_list)){echo $flat_doc_list[0]["id"];}?>" />
                                </td>
                                <td><input type="file" name="flat_doc_path" id="flat_doc_path" class="form-control" accept=".png,.jpg,.jpeg,.pdf"/></td>
                            </tr>
                            <tr>
                                <td colspan="9"><input type="submit" name="btn_flat_upload" value="Save" id="btn_flat_upload" class="btn btn-info"  />  </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>
<script>
function owner_details(il)
{
    var owner_id =$('#owner_id'+il).val();
    var owner_name =$('#owner_name'+il).val();
    var guardian_name =$('#guardian_name'+il).val();
    var relation_type =$('#relation_type'+il).val();
    var mobile_no =$('#mobile_no'+il).val();
    var aadhar_no =$('#aadhar_no'+il).val();
    var pan_no =$('#pan_no'+il).val();
    var email =$('#email'+il).val();
	$('#saf_owner_dtl_id').val(owner_id);
    $('#owner_det_name').html(owner_name);
    $('#guardian_det_name').html(guardian_name);
    $('#relation_det_type').html(relation_type);
    $('#mobile_det_no').html(mobile_no);
    $('#aadhar_det_no').html(aadhar_no);
    $('#pan_det_no').html(pan_no);
    $('#email_det').html(email);
	$("#owner_details_Modal").modal('show');
}

function saf_form_doc(il)
{
	$("#saf_form_doc_Modal").modal('show');
}
function trans_doc(il)
{
	$("#trans_doc_Modal").modal('show');
}
function prop_doc(il)
{	
    $("#prop_doc_Modal").modal('show');
}

function no_electric_connection_doc()
{	
    $("#no_electric_connection_doc_Modal").modal('show');
}

function flat_doc()
{	
    $("#flat_doc_Modal").modal('show');
}

</script>
<script type="text/javascript">
$(document).ready( function () {
    $("#applicant_image_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['jpg','jpeg','png']) == -1) {
            $("#applicant_image_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) {
            $("#applicant_image_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });
    $("#owner_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf','jpg','jpeg','png']) == -1) {
            $("#owner_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) { 
            $("#owner_doc_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });
    $("#trans_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf','jpg','jpeg','png']) == -1) {
            $("#trans_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) { 
            $("#trans_doc_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });
    $("#prop_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf','jpg','jpeg','png']) == -1) {
            $("#prop_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) { 
            $("#prop_doc_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });
    $("#saf_form_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf','jpg','jpeg','png']) == -1) {
            $("#saf_form_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) { 
            $("#saf_form_doc_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });
    $("#no_electric_connection_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf','jpg','jpeg','png']) == -1) {
            $("#no_electric_connection_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) { 
            $("#no_electric_connection_doc_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });

    $("#flat_doc_path").change(function() {
        var input = this;
        var ext = $(this).val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['pdf','jpg','jpeg','png']) == -1) {
            $("#flat_doc_path").val("");
            alert('invalid document type');
        }
        if (input.files[0].size > 5242880) {
            $("#flat_doc_path").val("");
            alert("Try to upload file less than 5MB"); 
        }
        keyDownNormal(input);
    });

    $("#btn_owner_doc").click(function() {
        var process = true;
        var applicant_image_path = $("#applicant_image_path").val();

        if (applicant_image_path == '') {
            $("#applicant_image_path").css({"border-color":"red"});
            $("#applicant_image_path").focus();
            process = false;
          }
        var owner_doc_mstr_id = $("#owner_doc_mstr_id").val();
        if (owner_doc_mstr_id == '') {
            $("#owner_doc_mstr_id").css({"border-color":"red"});
            $("#owner_doc_mstr_id").focus();
            process = false;
          }
         var owner_doc_path = $("#owner_doc_path").val();
        if (owner_doc_path == '') {
            $("#owner_doc_path").css({"border-color":"red"});
            $("#owner_doc_path").focus();
            process = false;
          }
        return process;
    });
    $("#btn_fr_doc").click(function() {
        var process = true;

        var saf_form_doc_path = $("#saf_form_doc_path").val();
        if (saf_form_doc_path == '') {
            $("#saf_form_doc_path").css({"border-color":"red"});
            $("#saf_form_doc_path").focus();
            process = false;
          }

        return process;
    });
    $("#btn_tr_doc").click(function() {
        var process = true;
        var trans_doc_mstr_id = $("#trans_doc_mstr_id").val();

        if (trans_doc_mstr_id == '') {
            $("#trans_doc_mstr_id").css({"border-color":"red"});
            $("#trans_doc_mstr_id").focus();
            process = false;
          }
        var trans_doc_path = $("#trans_doc_path").val();
        if (trans_doc_path == '') {
            $("#trans_doc_path").css({"border-color":"red"});
            $("#trans_doc_path").focus();
            process = false;
          }

        return process;
    });
    $("#btn_pr_doc").click(function() {
        var process = true;
        var prop_doc_mstr_id = $("#prop_doc_mstr_id").val();

        if (prop_doc_mstr_id == '') {
            $("#prop_doc_mstr_id").css({"border-color":"red"});
            $("#prop_doc_mstr_id").focus();
            process = false;
          }
        var prop_doc_path = $("#prop_doc_path").val();
        if (prop_doc_path == '') {
            $("#prop_doc_path").css({"border-color":"red"});
            $("#prop_doc_path").focus();
            process = false;
          }

        return process;
    });
    $("#btn_no_electric_connection_upload").click(function() {
        var process = true;
        var trans_doc_mstr_id = $("#no_electric_connection_doc_id").val();

        if (trans_doc_mstr_id == '') {
            $("#no_electric_connection_doc_id").css({"border-color":"red"});
            $("#no_electric_connection_doc_id").focus();
            process = false;
        }
        var no_electric_connection_doc_path = $("#no_electric_connection_doc_path").val();
        if (no_electric_connection_doc_path == '') {
            $("#no_electric_connection_doc_path").css({"border-color":"red"});
            $("#no_electric_connection_doc_path").focus();
            process = false;
        }
        return process;
    });
    $("#btn_flat_upload").click(function() {
        var process = true;
        var trans_doc_mstr_id = $("#flat_doc_id").val();

        if (trans_doc_mstr_id == '') {
            $("#flat_doc_id").css({"border-color":"red"});
            $("#flat_doc_id").focus();
            process = false;
        }
        var flat_doc_path = $("#flat_doc_path").val();
        if (flat_doc_path == '') {
            $("#flat_doc_path").css({"border-color":"red"});
            $("#flat_doc_path").focus();
            process = false;
        }
        return process;
    });
    $("#applicant_image_path").change(function(){$(this).css('border-color','');});
    $("#owner_doc_mstr_id").change(function(){$(this).css('border-color','');});
    $("#owner_doc_path").change(function(){$(this).css('border-color','');});
    $("#trans_doc_mstr_id").change(function(){$(this).css('border-color','');});
    $("#trans_doc_path").change(function(){$(this).css('border-color','');});
    $("#prop_doc_mstr_id").change(function(){$(this).css('border-color','');});
    $("#prop_doc_path").change(function(){$(this).css('border-color','');});

});
</script>