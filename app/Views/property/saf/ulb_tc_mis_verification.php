<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">SAF</a></li>
        <li class="active">SAF Document Verification</li>
        </ol>
    </div>
    <!--Page content-->
    <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Self Assessment Form
                        <a href="<?=base_url("safdtl/full/".$saf_dtl_id);?>" class="btn btn-default pull-right" style="color: black;">View Full SAF Details</a>
                    </h3>
                </div>
                <div class="panel-body">


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
                    <div id="has_prev_holding_dtl_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no=='f')?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($is_owner_changed))?($is_owner_changed=='t')?"YES":"NO":"N/A";?>
                            </div>
                            <div id="is_owner_changed_tran_property_hide_show" class="<?=(isset($is_owner_changed))?($is_owner_changed=='f')?"hidden":"":"";?>">
                                <label class="col-md-3">Mode of transfer of property from previous Holding Owner</label>
                                <div class="col-md-3 text-bold pad-btm">
                                        <?=(isset($transfer_mode))?$transfer_mode:"N/A";?>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </div>


                <?= $this->include('common/basic_details_saf');?>
                </div>



            </div>
        <!------- Panel Owner Details-------->
        <?php
        // print_var($has_previous_holding_no);
        // print_var($is_owner_changed);

        if ($has_previous_holding_no=='t' && $is_owner_changed=='t')
        {
            ?>
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Previous Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Relation</th>
                                            <th>Guardian Name</th>
                                            <th>Mobile No</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append" class="">
                                    <?php

                                    if(isset($prev_saf_owner_detail))
                                    {

                                        foreach($prev_saf_owner_detail as $owner_detail)
                                        {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?=$owner_detail['owner_name'];?>
                                                </td>
                                                <td>
                                                    <?=$owner_detail['relation_type'];?>
                                                </td>
                                                <td>
                                                    <?$owner_detail['guardian_name'];?>
                                                </td>

                                                <td>
                                                    <?=$owner_detail['mobile_no'];?>
                                                </td>
                                                <td>
                                                    <?=$owner_detail['email'];?>
                                                </td>
                                                <td>
                                                    <?=$owner_detail['pan_no'];?>
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
            <?php
        }
        ?>
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
                                            <th>Guardian Name</th>
                                            <th>Relation</th>
                                            <th>Mobile No</th>
                                            <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                            <th>DOB</th>
                                            <th>Gender</th>
                                            <th>Is_Specially_Abled</th>
                                            <th>Is_Armed_Force</th>
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                <?php

                                if (isset($saf_owner_detail))
                                {
                                    foreach ($saf_owner_detail as $owner_detail)
                                    {

                                        ?>
                                        <tr>
                                            <td>
                                                <?=$owner_detail['owner_name'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['guardian_name'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['relation_type'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['mobile_no'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['aadhar_no'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['pan_no'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['email'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['dob']==''?'N/A':$owner_detail['dob'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['gender']==''?'N/A':$owner_detail['gender'];?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['is_specially_abled']=='f'?'No':'Yes';?>
                                            </td>
                                            <td>
                                                <?=$owner_detail['is_armed_force']=='f'?'No':'Yes';?>
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
                    <h3 class="panel-title">Electricity Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row <?=(isset($prop_type_mstr_id))?(($prop_type_mstr_id!=2))?"hidden":"":"";?>">
                        <label class="col-md-10">
                            <div class="checkbox">
                                <?php
                                if($no_electric_connection=='t'){
                                    echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                }else{
                                    echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                                }
                                ?>
                                <label for="no_electric_connection"><span class="text-danger">Note:</span> In case, there is no Electric Connection. You have to upload Affidavit Form-I. (Please Tick)</label>
                            </div>
                        </label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Electricity K. No</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($elect_consumer_no);?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12 pad-btm text-center text-bold"><u>OR</u></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">ACC No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($elect_acc_no);?>
                        </div>
                        <label class="col-md-3">BIND/BOOK No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($elect_bind_book_no);?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Electricity Consumer Category</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=$elect_cons_category;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Building Plan/Water Connection Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Building Plan Approval No. </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($building_plan_approval_no);?>
                        </div>
                        <label class="col-md-3">Building Plan Approval Date </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($building_plan_approval_date);?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Water Consumer No. </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($water_conn_no);?>
                        </div>
                        <label class="col-md-3">Water Connection Date</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($water_conn_date);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Khata No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($khata_no);?>
                        </div>
                        <label class="col-md-3">Plot No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($plot_no);?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Village/Mauja Name</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($village_mauja_name);?>
                        </div>
                        <label class="col-md-3">Area of Plot (in Decimal)</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($area_of_plot);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Address</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Property Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?=($prop_address);?>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($prop_city);?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($prop_dist);?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=($prop_pin_code);?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-5">
                            <div class="checkbox">
                                <?php
                                if(isset($is_corr_add_differ)){
                                    echo '<i class="fa fa-check-square-o" style="font-size: 22px;"></i>';
                                }else{
                                    echo '<i class="fa fa-window-close-o" style="font-size: 22px;"></i>';
                                }
                                ?>
                                <label>If Corresponding Address Different from Property Address</label>

                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark <?=(!isset($is_corr_add_differ))?"hidden":"";?>">
                <div class="panel-heading">
                    <h3 class="panel-title">Correspondence Address</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-md-3">Correspondence Address</label>
                        <div class="col-md-7 text-bold pad-btm">
                            <?=(isset($corr_address))?$corr_address:"N/A";?>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-md-3">City</label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($corr_city))?$corr_city:"N/A";?>
                        </div>
                        <label class="col-md-3">District</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?=(isset($corr_dist))?$corr_dist:"N/A";?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">State</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?=(isset($corr_state))?$corr_state:"N/A";?>
                        </div>
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 text-bold text-bold pad-btm">
                            <?=(isset($corr_pin_code))?$corr_pin_code:"N/A";?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Field Verification </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Verified By</th>
                                            <th>Verification On</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 0;
                                    if (isset($verification))
                                        foreach ($verification as $row) {
                                    ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td><?= $row['verified_by']; ?></td>
                                            <td><?= date(DATE_RFC822, strtotime($row['created_on'])); ?></td>
                                            <td><a href="<?= base_url(); ?>/TCVerification/index/<?= md5($row['id']); ?>" class="btn btn-primary" target="_blank"> View </a></td>
                                        </tr>
                                    <?php
                                        }
                                    else {
                                    ?>
                                        <tr>
                                            <td colspan="4" style="text-align:center;color:red;">Data Are Not Available!!</td>
                                        </tr>
                                    <?php
                                    }

                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Memo Details</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Memo No</th>
                                            <th>Generated On</th>
                                            <th>Generated By</th>
                                            <th>ARV</th>
                                            <th>Quarterly Tax</th>
                                            <th>Effect From</th>
                                            <th>Memo Type</th>
                                            <th>Holding No</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 0;
                                    if (isset($memo))
                                        foreach ($memo as $row) {
                                    ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td><?= $row['memo_no']; ?></td>
                                            <td><?= date("Y-m-d", strtotime($row['created_on'])); ?></td>
                                            <td><?= $row['emp_name']; ?></td>
                                            <td><?= $row['arv']; ?></td>
                                            <td><?= $row['quarterly_tax']; ?></td>
                                            <td><?= $row['effect_quarter']; ?>/<?= $row['fy']; ?></td>
                                            <td class="text-left"><?= $row['memo_type']; ?></td>
                                            <td class="text-left"><?= $row['holding_no']; ?></td>
                                            <td><a onClick="PopupCenter('<?= base_url("citizenPaymentReceipt/da_eng_memo_receipt/" . md5($ulb_mstr_id) . "/" . md5($row["id"])); ?>', '<?= $row['memo_type']; ?>', 1024, 786)" href="#" class="btn btn-primary">View</a></td>
                                        </tr>
                                    <?php
                                        }
                                    else {
                                    ?>
                                        <tr>
                                            <td colspan="8" style="text-align:center;color:red;">Data Are Not Available!!</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php if (isset($doc_dtl_list)) { ?>
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
                                <th>Status</th>
                                <th>Remarks (If Any)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $docCount = 0; foreach ($doc_dtl_list AS $doc_dtl) { ?> 
                        <tr>
                            <td><?=++$docCount;?></td>
                            <td><div style="font-weight:600"><?=$doc_dtl["doc_name"];?></div> <div><?= ($doc_dtl['owner_name']!="")?'('.$doc_dtl['owner_name'].')':'' ?></div></td>
                            <td>
                                <?php
                                $extention = strtolower(explode('.', $doc_dtl["doc_path"])[1]);
                                if ($extention=="pdf")
                                {
                                    ?>
                                    <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc_dtl["doc_path"];?>" target="_blank" > 
                                        <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class="img-lg" />
                                    </a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$doc_dtl["doc_path"];?>">
                                        <img src="<?=base_url();?>/getImageLink.php?path=<?=$doc_dtl["doc_path"];?>" class="img-lg" />
                                    </a>
                                    <?php
                                }
                                ?>
                            </td>
                            <td>
                            <?php
                            if($doc_dtl['verify_status']==0)
                            {
                                echo "<span class='text-danger text-bold'>Not Verified</span>";
                            }
                            if($doc_dtl['verify_status']==1)
                            {
                                echo "<span class='text-success text-bold'>Verified</span>";
                                echo "<span class='text-primary text-bold'><br>On ".date('d-m-Y', strtotime($doc_dtl['verified_on']))."</span>";
                            }
                            if($doc_dtl['verify_status']==2)
                            {
                                echo "<span class='text-danger text-bold'>Rejected.</span>";
                                echo "<span class='text-primary text-bold'><br>On ".date('Y-m-d', strtotime($doc_dtl['verified_on']))."</span>";
                            }
                            ?>
                        </td>
                        <td><?=($doc_dtl["remarks"]=="")?"N/A":$doc_dtl["remarks"];?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>



            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">

                    <?php 
                    if ($level_result["verification_status"]==2) {
                    ?>
                        <div class="form-group">
                            <label class="col-md-2 text-bold">Remarks</label>
                            <div class="col-md-10">
                                <?=$level_result["remarks"]?><br /><br />
                            </div>
                        </div>

                    <?php
                    } else {
                    ?>
                    <form method="POST">
                        <input type="hidden" id="saf_dtl_id" name="saf_dtl_id" value="<?=$saf_dtl_id;?>" />
                        <div class="form-group">
                            <label class="col-md-2 text-bold">Remarks<span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea id="remarks1" name="remarks" class="form-control" placeholder="Please Enter Remark" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">&nbsp;&nbsp;&nbsp;</label>
                            <div class="col-md-10" style="padding: 20px 20px 20px 10px;">
                                <button class="btn btn-danger" id="btn_backward_submit" name="btn_backward_submit" type="submit">Backward</button>
                                <button class="btn btn-danger hidden" id="btn_backward_submit2" name="btn_backward_submit2" type="button">Backward</button>
                                
                                <button type="submit" class="btn btn-primary" id="btn_back_to_citizen" name="btn_back_to_citizen">Back To Citizen</button>
                                <button type="button" class="btn btn-primary hidden" id="btn_back_to_citizen2" name="btn_back_to_citizen2">Back To Citizen</button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    </form>
                </div>
            </div>
    <!-- End page content-->
</div>
<?=$this->include('layout_vertical/footer');?>

<script>

$("#btn_backward_submit").click(function() {
    if ($("#remarks").val()!="") {
        $("#btn_backward_submit").addClass("hidden");
        $("#btn_backward_submit2").removeClass("hidden");
    }
});


$("#btn_back_to_citizen").click(function() {
    if ($("#remarks").val()!="") {
        $("#btn_back_to_citizen").addClass("hidden");
        $("#btn_back_to_citizen2").removeClass("hidden");
    }
});

</script>