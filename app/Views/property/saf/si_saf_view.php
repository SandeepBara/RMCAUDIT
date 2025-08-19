<?= $this->include('layout_vertical/header');?>

<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">SI SAF</a></li>
        <li class="active">SI SAF View</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Self Assessment Form
                    <a href="<?=base_url();?>/safdtl/full/<?=$saf_dtl_id;?>" class="btn btn-default pull-right" style="color: black;">View SAF Full Details</a>
                </h3>
                
            </div>
            <div class="panel-body">
                <div class="row">
                    <label class="col-md-3">Does the property being assessed has any previous Holding Number? </label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?=(isset($has_previous_holding_no))?($has_previous_holding_no=='t')?"Yes":"No":"N/A";?>
                    </div>
                    <?php if($has_previous_holding_no=='t') { ?>
                        <label class="col-md-3">Previous Holding No.</label>
                        <div class="col-md-3 text-bold pad-btm">
                           <a style="color:blue;text-decoration:underline;" href="<?=base_url("propDtl/full/".$previous_holding_id);?>" target="_blank"><?=($holding_no);?></a> 
                        </div>
                    <?php } ?>
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
                <?= $this->include('common/basic_details_saf');?>
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
                                        <th>Guardian Name</th>
                                        <th>Relation</th>
                                        <th>Mobile No</th>
                                        <th>Aadhar No.</th>
                                        <th>PAN No.</th>
                                        <th>Email ID</th>
                                        <th>Applicant Image</th>
                                        <th>Applicant Document</th>
                                    </tr>
                                </thead>
                                <tbody id="owner_dtl_append">
                            <?php
                            if (isset($saf_owner_detail)) {
                                foreach ($saf_owner_detail as $owner_detail) {    
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
                                        <?php
                                        if ($owner_detail['applicant_img_dtl'])
                                        {
                                            $path = $owner_detail['applicant_img_dtl']['doc_path'];
                                            ?>
                                            <a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
                                                <img src="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" class="img-lg" />
                                            </a>
                                        <?php
                                        } else {
                                            echo "<span class='text-danger text-bold'>Document is not uploaded.</span>";
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php
                                        if ($owner_detail['applicant_doc_dtl'])
                                        {
                                            $path = $owner_detail['applicant_doc_dtl']['doc_path'];
                                            $extention = strtolower(explode('.', $path)[1]);
                                            if($extention=="pdf")
                                            {
                                                ?>
                                                <a href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" target="_blank" > 
                                                    <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" class="img-lg" />
                                                </a>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
                                                    <img src='<?=base_url();?>/getImageLink.php?path=<?=$path;?>' class='img-lg' />
                                                </a>
                                                <?php
                                            }
                                            if ($owner_detail['applicant_doc_dtl']['verify_status']==0)
                                            {
                                                echo "<br /><span class='text-danger text-bold'>Not Verified</span>";
                                            }
                                            else if ($owner_detail['applicant_doc_dtl']['verify_status']==1)
                                            {
                                                echo "<br /><span class='text-success text-bold'>Verified.</span>";
                                                echo "<br /><span class='text-success text-bold'>Verify date = ".date('Y-m-d', strtotime($owner_detail['applicant_doc_dtl']['verified_on']))."</span>";
                                            }
                                        }
                                        else
                                        {
                                            echo "<span class='text-danger text-bold'>Document is not uploaded.</span>";
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
        <!------- end Owner Details-------->
        
        <!-------Tax Details-------->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Tax Details</h3>
            </div>
            <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>Sl No.</th>
                                    <th>ARV</th>
                                    <th>Effect From</th>
                                    <th>Holding Tax</th>
                                    <th>Water Tax</th>
                                    <th>Conservancy/Latrine Tax</th>
                                    <th>Education Cess</th>
                                    <th>Health Cess</th>
                                    <th>RWH Penalty</th>
                                    <th>Quarterly Tax</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($saf_tax_list):
                                $i=1; $qtr_tax=0; $lenght= sizeOf($saf_tax_list);?>
                            <?php foreach($saf_tax_list as $tax_list): 
                                $qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'] + $tax_list['additional_tax'];
                            ?>
                                <tr>
                                    <td><?=$i++;?></td>
                                    <td><?=round($tax_list['arv'], 2);?></td>
                                    <td><?=$tax_list['qtr'];?> / <?=$tax_list['fy'];?></td>
                                    <td><?=round($tax_list['holding_tax'], 2);?></td>
                                    <td><?=round($tax_list['water_tax'], 2);?></td>
                                    <td><?=round($tax_list['latrine_tax'], 2);?></td>
                                    <td><?=round($tax_list['education_cess'], 2);?></td>
                                    <td><?=round($tax_list['health_cess'], 2);?></td>
                                    <td><?=round($tax_list['additional_tax'], 2);?></td>
                                    <td><?=round($qtr_tax, 2); ?></td>
                                <?php if($i>$lenght){ ?>
                                    <td class="text-danger">Current</td>
                                <?php } else { ?>
                                    <td>Old</td>
                                <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" style="text-align:center;color:red;">Data Are Not Available!!</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <!-------end Tax Details-------->

        <!--------prop doc------------>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Document</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-trans-dark text-dark">
                            <tr>
                                <th>#</th>
                                <th>Document Name</th>
                                <th>Document Image</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($document_list)) {
                            $i=0;
                            foreach($document_list as $doc) {
                                ?>
                                <tr>
                                <td><?=++$i;?></td>
                                    <td><?=$doc['doc_name'];?></td>
                                    <td>
                                        <?php
                                        $path = $doc['doc_path'];
                                        $extention = strtolower(explode('.', $path)[1]);
                                        if($extention=='pdf') {
                                            ?>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$doc['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td><span class="text text-success">Verified</span></td>
                                </tr>
                                <?php
                            }
                        }
                        if(!empty($prop_pr_mode_document))
                        {
                            ?>
                            <tr>
                                <td><?=$prop_pr_mode_document['doc_name'];?></td>
                                <?php
                                                $exp_pr_doc=explode('.',$prop_pr_mode_document['doc_path']);
                                                $exp_pr_doc_ext=$exp_pr_doc[1];
                                                ?>
                                                <td>
                                                    <?php
                                                    if($exp_pr_doc_ext=='pdf')
                                                    {
                                                    ?>
                                                    <a href="<?=base_url();?>/writable/uploads/<?=$prop_pr_mode_document['doc_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <a href="<?=base_url();?>/writable/uploads/<?=$prop_pr_mode_document['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prop_pr_mode_document['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                    <?php
                                                    }
                                                    ?>

                                </td>
                            </tr>
                            <?php
                        }

                        if(!empty($latest_document_list)) {
                            $i=0;
                            foreach($latest_document_list as $doc) {
                                ?>
                                <tr>
                                <td><?=++$i;?></td>
                                    <td><?=$doc['doc_name'];?></td>
                                    <td>
                                        <?php
                                        $path = $doc['doc_path'];
                                        $extention = strtolower(explode('.', $path)[1]);
                                        if($extention=='pdf') {
                                            ?>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/getImageLink.php?path=<?=$doc['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php  if($doc['verify_status'] == 0){ ?>
                                            <form method="POST">
                                                <input type="hidden" name="saf_doc_dtl_id" value="<?=$doc["id"];?>">
                                                <button type="submit" name="btn_verify" value="Verify" class="btn btn-success btn-rounded btn-labeled">
                                                    <i class="btn-label fa fa-check"></i>
                                                    <span> Verify </span>
                                                </button>

                                                <button type="submit" name="btn_reject" value="Reject" class="btn btn-danger btn-rounded btn-labeled">
                                                    <i class="btn-label fa fa-close"></i>
                                                    <span> Reject </span>
                                                </button>
                                            </form>
                                        <?php }
                                            if($doc['verify_status'] == 1){
                                        ?>
                                            <span class="text text-success">Verified</span>
                                        <?php } 
                                            if($doc['verify_status'] == 2){
                                                ?>
                                                <span class="text text-danger">Rejected</span>
                                                <?php }
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
        <!-------- end prop doc------------>
        <!-------- prop field verification details------------>

        <!-------- end prop field verification details------------>
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
        <!--------user wise level remarks------------>
        <?php  if (isset($level)) { ?>
        <div class="panel panel-bordered panel-dark">
            <div data-toggle="collapse" data-target="#demo" role="type">
                <div class="panel-heading">
                    <h3 class="panel-title">Level Remarks
                    </h3>
                </div>
            </div>

            <div class="panel-body" id="demo">
                <div class="nano has-scrollbar" style="height: 50vh">
                    <div class="nano-content" tabindex="0" style="right: -17px;">
                        <div class="panel-body chat-body media-block">
                            <?php
                            $i = 0;
                            foreach ($level as $row) {
                                ++$i;
                            ?>
                                <div class="chat-<?= ($i % 2 == 0) ? "user" : "user"; ?>">
                                    <div class="media-left">
                                        <img src="<?= base_url("public/assets/img/") ?>/<?= $row["user_type"]; ?>.png" class="img-circle img-sm" alt="<?= $row["user_type"]; ?>" title="<?= $row["user_type"]; ?>" loading="lazy" />
                                        <br /> <?=$row["emp_name"]?>
                                    </div>
                                    <div class="media-body">
                                        <div>
                                            <p><?= $row["remarks"]; ?><small>
                                                    <?= date("g:iA", strtotime($row["forward_time"])); ?> <?= date("d M, Y", strtotime($row["forward_date"])); ?></small></p>
                                        </div>
                                    </div>
                                </div>
                            <?php

                            }
                            ?>
                        </div>
                    </div>
                    <div class="nano-pane">
                        <div class="nano-slider" style="height: 61px; transform: translate(0px, 0px);"></div>
                    </div>
                </div>

            </div>
        </div>
        <?php } ?> 
        <!-------- end user wise level remarks------------>                              
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered panel-dark">
                    <form method="post" class="form-horizontal" id="level_form">
                        <div class="panel-body" style="padding-bottom: 0px;">
                            <div class="form-group">
                                <label class="col-md-2" >Remarks</label>
                                <div class="col-md-10">
                                    <textarea type="text" placeholder="Please Enter Remarks" id="remarks" name="remarks" class="form-control" required></textarea>
                                </div>
                            </div>
                            <?php
                            if(isset($form['verification_status']))
                            {
                                if($form['verification_status']=="0")
                                {
                                    ?>
                                        <div class="form-group">
                                            <label class="col-md-2" >&nbsp;</label>
                                            <div class="col-md-10">

                                                <button class="btn btn-danger" id="btn_backward_submit" name="btn_backward_submit" type="submit">Backward</button>
                                                <button class="btn btn-danger hidden" id="btn_backward_submit2" name="btn_backward_submit2" type="button">Backward </button>

                                                <button class="btn btn-success" id="btn_verify_submit" name="btn_verify_submit" type="submit">Verify & Forward</button>
                                                <button class="btn btn-success hidden" id="btn_verify_submit2" name="btn_verify_submit2" type="button">Forwarding</button>

                                                <button class="btn btn-danger" id="btn_back_to_citizen_submit" name="btn_back_to_citizen_submit" type="submit">Back To Citizen</button>
                                                <button class="btn btn-danger hidden" id="btn_back_to_citizen_submit2" name="btn_back_to_citizen_submit2" type="button">Back To Citizen </button>

                                                <button class="btn btn-danger" id="btn_reject_submit" name="btn_reject_submit" type="submit">Reject</button>
                                                <button class="btn btn-danger hidden" id="btn_reject_submit2" name="btn_reject_submit2" type="button">Please wait </button>

                                            </div>
                                        </div>
                                        <?php
                                }
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>
<script>
    $("#btn_verify_submit").click(function() {
        if ($("#remarks").val()!="") {
            $("#btn_verify_submit").addClass("hidden");
            $("#btn_verify_submit2").removeClass("hidden");
        }
    });

    $("#btn_backward_submit").click(function() {
        if ($("#remarks").val()!="") {
            $("#btn_backward_submit").addClass("hidden");
            $("#btn_backward_submit2").removeClass("hidden");
        }
    });


    $("#btn_back_to_citizen_submit").click(function() {
        if ($("#remarks").val()!="") {
            $("#btn_back_to_citizen_submit").addClass("hidden");
            $("#btn_back_to_citizen_submit2").removeClass("hidden");
        }
    });

    $("#btn_reject_submit").click(function() {
        if ($("#remarks").val()!="") {
            $("#btn_reject_submit").addClass("hidden");
            $("#btn_reject_submit2").removeClass("hidden");
        }
    });
    $("#level_form").submit(function(event){ 
        var form = document.getElementById('level_form');        
        var submitButtons = form.querySelectorAll('input[type="submit"], button[type="submit"]');        
        submitButtons.forEach(function(button) {
            button.style.display="none";
        });
    });
</script>

