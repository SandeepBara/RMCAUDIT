<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<?php
$session = session();
$emp_details = $session->get('emp_details');
$ulb = $session->get('ulb_dtl');
?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">EO SAF</a></li>
        <li class="active">EO SAF View</li>
        </ol>
    </div>
    <!--Page content-->
    <div id="page-content">
     

                    <div class="panel panel-bordered panel-dark">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Self Assessment Form
                                <a href="<?=base_url();?>/safdtl/full/<?=md5($saf_dtl_id);?>" class="btn btn-default pull-right text-danger" style="color: black;">View SAF Full Details</a>
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
                                                        <?php
                                                        if ($owner_detail['applicant_img_dtl'])
                                                        {
                                                            $path = $owner_detail['applicant_img_dtl']['doc_path'];
                                                            ?>
                                                            <a target="_blank" href="<?=base_url();?>/getImageLink.php?path=<?=$path;?>">
                                                                <img src="<?=base_url();?>/getImageLink.php?path=<?=$path;?>" class="img-lg" />
                                                            </a>
                                                            <?php
                                                        }
                                                        else
                                                        {
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
                                            $i=0;
                                            if($Verification)
                                            foreach($Verification as $row)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$row['verified_by'];?></td>
                                                    <td><?=date(DATE_RFC822, strtotime($row['created_on']));?></td>
                                                    <td><a href="<?=base_url();?>/TCVerification/index/<?=md5($row['id']);?>" class="btn btn-primary" target="_blank"> View </a></td>
                                                </tr>
                                                <?php
                                            }
                                            else
                                            {
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
                                                    <th>ARV</th>
                                                    <th>Quarterly Tax</th>
                                                    <th>Effect From</th>
                                                    <th>Memo Type</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $i=0;
                                            if($Memo)
                                            foreach($Memo as $row)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$row['memo_no'];?></td>
                                                    <td><?=date("Y-m-d", strtotime($row['created_on']));?></td>
                                                    <td><?=$row['arv'];?></td>
                                                    <td><?=$row['quarterly_tax'];?></td>
                                                    <td><?=$row['effect_quarter'];?>/<?=$row['fy'];?></td>
                                                    <td class="text-left"><?=$row['memo_type'];?></td>
                                                    <td><a href="#" class="btn btn-primary" onclick="window.open('<?=base_url();?>/citizenPaymentReceipt/da_eng_memo_receipt/<?=md5($ulb['ulb_mstr_id']);?>/<?=md5($row['id']);?>', 'newwindow', 'width=1000, height=1000'); return false;">View</a></td>
                                                </tr>
                                                <?php
                                            }
                                            else
                                            {
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
                    <?=propLevelRemarkTree($saf_dtl_id);?>
                    <?php
                    if($saf_pending_status==0)
                    {
                        ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a onclick="window.open('<?=base_url('TCVerification/index/'.md5(end($Verification)['id']));?>#footer', 'newwindow', 'width=1000, height=1000'); return false;" class="btn btn-default pull-right text-bold" style="color: black;">
                                        View Difference Calculation
                                    </a>
                                </h3>
                                
                            </div>
                            <div class="panel-body">
                                <form method="post">
                                    <div class="form-group" id="remarks_div">
                                        <label class="col-md-2" >Remarks</label>
                                        <div class="col-md-10">
                                            <textarea type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" required></textarea>
                                        </div>
                                    </div>

                                    
                                    <div class="form-group" id="button_div">
                                        <label class="col-md-2" >&nbsp;</label>
                                        <div class="col-md-10" style="margin: 0px 0px 0px 179px;">
                                            <button class="btn btn-success" id="btn_approved_submit" name="btn_approved_submit" type="submit">Approved</button>
                                            <button class="btn btn-success hidden" id="btn_approved_submit2" name="btn_approved_submit2" type="button">Please Wait</button>

                                            <button class="btn btn-warning" id="btn_backward_submit" name="btn_backward_submit" type="submit">Backward</button>
                                            <button class="btn btn-warning hidden" id="btn_backward_submit2" name="btn_backward_submit2" type="button">Please Wait</button>

                                            <button class="btn btn-danger" id="btn_back_to_citizen_submit" name="btn_back_to_citizen_submit" type="submit">Back to citizen</button>
                                            <button class="btn btn-danger hidden" id="btn_back_to_citizen_submit2" name="btn_back_to_citizen_submit2" type="button">Please Wait</button>

                                            <button class="btn btn-danger" id="btn_reject_submit" name="btn_reject_submit" type="submit">Reject</button>
                                            <button class="btn btn-danger hidden" id="btn_reject_submit2" name="btn_reject_submit2" type="button">Please Wait </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
    </div>
    <!-------prop doc------------->



<?= $this->include('layout_vertical/footer');?>
<script>
$("#btn_approved_submit").click(function() {
    if ($("#remarks").val()!="") {
        $("#btn_approved_submit").addClass("hidden");
        $("#btn_approved_submit2").removeClass("hidden");
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
function PopupCenter(url, title, w, h) {  
    // Fixes dual-screen position                         Most browsers      Firefox  
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  
              
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  
              
    var left = ((width / 2) - (w / 2)) + dualScreenLeft;  
    var top = ((height / 2) - (h / 2)) + dualScreenTop;  
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);  
  
    // Puts focus on the newWindow  
    if (window.focus) {  
        //newWindow.focus();  
    }  
} 
<?php
if(isset($_GET["memo_id"]) && $_GET["memo_id"]!=NULL){
    ?>
    
    PopupCenter('<?=base_url('citizenPaymentReceipt/da_eng_memo_receipt/'.md5($ulb_mstr_id).'/'.($_GET["memo_id"]));?>', 'Self Assessment Memo', 1024, 786);
    
    <?php
}
?>
</script>
