<?=$this->include('layout_vertical/header');?>

<style type="text/css">
    .error
    {
        color:red ;
    }
</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="#" class="active">Consumer Request</a></li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">            
            <div class="panel-heading">
                <h3 class="panel-title"> Water Connection Details </h3>
            </div>
            <div class="panel-body"> 
                <?php
                    if($from!="inbox"){
                        ?>
                            <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                                <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;"> Your applied application no. is 
                                    <span style="color: #ff6a00"><?=$request_dtl['request_no'];?></span>. 
                                    You can use this application no. for future reference.
                                </span>
                                <br>
                                <br>
                                <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                                    Current Status : <span style="color:#009900"><?=$app_status;?></span>
                                </div>
                            </div>
                        <?php
                    }
                ?>
            

                <div class="row">
                    <label class="col-md-2 bolder">Consumer No.</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['consumer_no']??""; ?></b>
                    </div>
                    <label class="col-md-2 bolder">Ward No.</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['ward_no']??""; ?></b>
                    </div>                    
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Type of Connection <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['connection_type']??""; ?></b>
                    </div>
                    <label class="col-md-2 bolder">Category <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['category']??""; ?></b> 
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Property Type <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['property_type']??""; ?></b> 
                    </div>
                        <label class="col-md-2 bolder">Pipeline Type <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['pipeline_type']??""; ?></b> 
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Apply From <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['apply_from']??""; ?></b> 
                    </div>
                    <label class="col-md-2 bolder">Consumer Connection Date<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=date('d-m-Y',strtotime($consumer_details['created_on']))??"";?></b> 
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Holding No<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['holding_no']??""; ?></b> 
                    </div>
                    <label class="col-md-2 bolder">Address<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['address']??""; ?></b> 
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">            
            <div class="panel-heading">
                <h3 class="panel-title"> Request Details </h3>
            </div>
            <div class="panel-body">  
                <div class="row">
                    <label class="col-md-2 bolder">Request No.</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$request_dtl['request_no']??""; ?></b>
                    </div>
                    <label class="col-md-2 bolder">Request Type</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$request_dtl['request_type']??""; ?></b>
                    </div>                    
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Apply Date</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$request_dtl['apply_date']??""; ?></b>
                    </div>
                </div>                
            </div>
        </div>

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"><?=in_array($request_dtl['request_type_id'],[1]) ? "Previous ":"";?>Owner Details</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-responsive">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th class="bolder">Owner Name</th>
                            <th class="bolder">Guardian Name</th>
                            <th class="bolder">Mobile No.</th>
                            <th class="bolder">Email ID</th>
                            <th class="bolder">State</th>
                            <th class="bolder">District</th>
                            <th class="bolder">City</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($consumer_owner_details??false){
                            foreach($consumer_owner_details as $val){
                            ?>
                            <tr>
                                <td><?=isset($val['applicant_name'])? $val['applicant_name'] :'';?></td>
                                <td><?=isset($val['father_name'])? $val['father_name'] :'';?></td>
                                <td><?=isset($val['mobile_no'])? $val['mobile_no'] :'';?></td>
                                <td><?=isset($val['email_id'])? $val['email_id'] :'';?></td>
                                <td><?=isset($val['state'])? $val['state'] :'';?></td>
                                <td><?=isset($val['district'])? $val['district'] :'';?></td>
                                <td><?=isset($val['city'])? $val['city'] :'';?></td>
                            </tr>
                            <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>  
        <?php
            if(in_array($request_dtl['request_type_id'],[1])){
                ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">New Owner Details</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th class="bolder">Owner Name</th>
                                    <th class="bolder">Guardian Name</th>
                                    <th class="bolder">Mobile No.</th>
                                    <th class="bolder">Email ID</th>
                                    <th class="bolder">State</th>
                                    <th class="bolder">District</th>
                                    <th class="bolder">City</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($request_owner??false){
                                    foreach($request_owner as $val){
                                    ?>
                                    <tr>
                                        <td><?=isset($val['applicant_name'])? $val['applicant_name'] :'';?></td>
                                        <td><?=isset($val['father_name'])? $val['father_name'] :'';?></td>
                                        <td><?=isset($val['mobile_no'])? $val['mobile_no'] :'';?></td>
                                        <td><?=isset($val['email_id'])? $val['email_id'] :'';?></td>
                                        <td><?=isset($val['state'])? $val['state'] :'';?></td>
                                        <td><?=isset($val['district'])? $val['district'] :'';?></td>
                                        <td><?=isset($val['city'])? $val['city'] :'';?></td>
                                    </tr>
                                    <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div> 
                <?php
            }
        ?>

        <!-- Payments -->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Payment Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>Transaction No</th>
                                        <th>Payment Mode</th>
                                        <th>Date</th>
                                        <th>Payment For</th>
                                        <th>Amount</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($payment_detail)) {
                                            $i = 1;
                                            foreach ($payment_detail as $payment_detail) {
                                                ?>
                                                <tr class="<?= ($payment_detail["status"] == 3) ? 'text-danger' : null; ?>">
                                                    <td><?= $i++; ?></td>
                                                    <td class="text-bold"><?= $payment_detail['transaction_no']; ?></td>
                                                    <td><?= $payment_detail['payment_mode'] ?></td>
                                                    <td><?= $payment_detail['transaction_date']; ?></td>
                                                    <td><?= $payment_detail['transaction_type']; ?></td>
                                                    <td><?= $payment_detail['total_amount']; ?></td>

                                                    <td>
                                                        <a onClick="PopupCenter('<?= base_url('WaterConsumerRequest/paymentReceipt/' . md5($payment_detail['id'])); ?>', 'SAF Payment Receipt', 1024, 786)" id="customer_view_detail" class="btn btn-primary">View</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="9" class="text-danger text-bold text-center"> No Any Transaction ...</td>
                                            </tr>
                                        <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Payments -->
        <!--  Documents -->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"> Document List</h3>
            </div>
            <div class="panel-body" style="padding-bottom: 0px;">
                <div class="table-responsive">
                    <?php
                        if($from=="inbox" && ($permission["can_verify_doc"]??"f")=="t"){
                            ?>
                                <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Document Name</th>
                                            <th>Document</th>
                                            <th>Verify/Reject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    foreach($uploaded_doc_list as $doc)
                                    {
                                        //Checking if consumer document
                                        $owner_name=NULL; 
                                        if($doc["applicant_detail_id"]>0)
                                        {
                                            $owner=array();

                                            $applicant_detail_id=$doc['applicant_detail_id'];
                                            foreach($owner_details as $value)
                                            {
                                                
                                                if($value['id']==$applicant_detail_id)
                                                {
                                                    $owner=$value;
                                                }
                                            }
                                            $owner_name='<span class="text text-primary">('.$owner["applicant_name"].')</span>';
                                        }
                                        ?>
                                            <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$doc["document_name"];?> <?=$owner_name;?></td>
                                            <td>
                                                <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["document_path"];?>" target="_blank" title="<?=$doc["document_name"];?>">
                                                <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
                                                </a>
                                            </td>
                                            <td>
                                            <?php
                                            
                                            if($doc["verify_status"]==0) // Not Verified then verify
                                            {
                                                ?>
                                                <form method="POST" action="<?=base_url("WaterConsumerRequest/verifyRejectDoc/".md5($request_dtl["id"])."/".$from??"")?>">
                                                    <input type="hidden" name="applicant_doc_id" value="<?=$doc["id"];?>">
                                                        <button type="submit" name="btn_verify" value="Verify" class="btn btn-success btn-rounded btn-labeled">
                                                            <i class="btn-label fa fa-check"></i>
                                                            <span> Verify </span>
                                                        </button>

                                                        <a class="btn btn-danger btn-rounded btn-labeled" role="button" data-toggle="modal" data-target="#rejectModal<?=$doc["id"];?>">
                                                            <i class="btn-label fa fa-close"></i>
                                                            <span> Reject </span>
                                                        </a>
                                                </form>

                                                <div class="modal fade" id="rejectModal<?=$doc["id"];?>" style="display: none;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h4 class="modal-title"> Mention Reason For Document Rejection - <?=$doc["document_name"];?> <?=$owner_name;?> </h4>
                                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                                            </div>
                                                        
                                                            
                                                            <form method="POST" action="<?=base_url("WaterConsumerRequest/verifyRejectDoc/".md5($request_dtl["id"])."/".$from??"")?>">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="applicant_doc_id" value="<?=$doc["id"];?>">
                                                                    <textarea type="text" name="remarks" id="remarks_doc" class="form-control" placeholder="Mention Remarks Here" required=""></textarea>
                                                                </div>
                                                            
                                                            
                                                                <div class="modal-footer">
                                                                <input type="submit" name="btn_reject" value="Reject" class="btn btn-primary">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            if($doc["verify_status"]==1) // Approved
                                            {
                                                ?>
                                                <span class="text text-success text-bold">Approved</span>
                                                <?php
                                            }
                                            if($doc["verify_status"]==2) // Rejected
                                            {
                                                ?>
                                                <span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" data-original-title="<?=$doc["remarks"];?>">Rejected</span>
                                                <?php
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            <?php
                        }else{
                            ?>
                                <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Document Name</th>
                                            <th>Document</th>
                                            <th>Doc Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    foreach($uploaded_doc_list as $doc)
                                    {
                                        $owner_name=NULL; 
                                        if($doc["applicant_detail_id"]>0)
                                        {
                                            $owner=array();

                                            $applicant_detail_id=$doc['applicant_detail_id'];
                                            foreach($owner_details as $value)
                                            {
                                                
                                                if($value['id']==$applicant_detail_id)
                                                {
                                                    $owner=$value;
                                                }
                                            }                                            
                                            $owner_name='<span class="text text-primary">('.$owner["applicant_name"].')</span>';
                                        }
                                        ?>
                                            <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$doc["document_name"];?> <?=$owner_name;?></td>
                                            <td>
                                                <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["document_path"];?>" target="_blank" title="<?=$doc["document_name"];?>">
                                                <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
                                                </a>
                                            </td>
                                            <td>
                                            <?php
                                                $class = $doc["verify_status"]==0 ? "warning":($doc["verify_status"]==1 ? "success" : ($doc["verify_status"]==2 ? "danger" :""));
                                                $verify_status = $doc["verify_status"]==0 ? "Pending":($doc["verify_status"]==1 ? "Approved" : ($doc["verify_status"]==2 ? "Rejected" :""));
                                                ?>
                                                <span class="text text-<?=$class;?> text-bold" data-placement="top" data-toggle="tooltip" data-original-title="<?=$doc["remarks"];?>"><?=$verify_status;?></span>
                                                <?php   
                                            ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- end Documents -->

        <?php
            if (isset($level)) {
            ?>
                <div class="panel panel-bordered panel-dark">
                    <div data-toggle="collapse" data-target="#demo" role="type">
                        <div class="panel-heading">
                            <h3 class="panel-title">Level Remarks
                            </h3>
                        </div>
                    </div>

                    <div class="panel-body collapse" id="demo">
                        <div class="nano has-scrollbar" style="height: 60vh">
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
                                                        <p><?= !empty($row["remarks"])?$row["remarks"]:'NA'; ?><small>
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
            <?php
            }
        ?>

        <div class="panel-body">
            <div class="row">
                <div class="text text-center">
                    <?php
                    if (($request_dtl["doc_upload_status"] == 0 || $request_dtl["doc_verify_status"] == 0 )&& ($permission["can_upload_doc"]??'f')=='t') {
                        ?>
                            <a class="btn btn-primary btn-rounded" href="<?=base_url('WaterConsumerRequest/uploadDoc/'.($request_dtl["id"]));?>"> Upload Document</a>
                        <?php 
                    }
                    if ($request_dtl["payment_status"] == 0 && ($permission["can_take_payment"]??'f')=='t') {
                        ?>
                            <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#payment_model"> Proceed to payment</button>
                        <?php 
                    }
                    if($from=="inbox"){
                        if ($request_dtl["payment_status"] == 1 && ($permission["can_forward"]??'f')=='t' && $fullDocUpload && $fullDocVerify) {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Forward')"> Forward</button>
                            <?php
                        }
                        if ($request_dtl["payment_status"] == 1 && ($permission["can_backward"]??'f')=='t') {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Backward')"> Backward</button>
                            <?php
                        }
    
                        if ($request_dtl["payment_status"] == 1 && ($permission["can_btc"]??'f')=='t') {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Back To Citizen')"> Back To Citizen</button>
                            <?php
                        }
    
                        if ($request_dtl["payment_status"] == 1 && ($permission["can_reject"]??'f')=='t') {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Reject')"> Reject</button>
                            <?php
                        }
                    }
                    elseif($from=="btcList" && $request_dtl["is_parked"]=='t'){
                        if ($request_dtl["payment_status"] == 1 && ($permission["can_forward"]??'f')=='t') {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Forward')"> Send</button>
                            <?php
                        }
                    }


                    ?>
                </div>
            </div>
        </div>

        <div>
            <!-- models -->
            <div id="payment_model" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #25476a;">
                            <button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="color: white;">Payment <span class="text-danger">&#8377; <?=$request_dtl["due_demand_amount"];?> </span> </h4>
                        </div>
                        <form id ="payment_form" action="<?=  base_url('/WaterConsumerRequest/proceedToPayment/'.md5($request_dtl["id"])); ?>" method="post">
                            <div class="modal-body">
                                <input type="hidden" class="form-control" id="type" name="type" value="<?=$request_dtl["request_type"];?>">
                                <div class="row">
                                    <label class="col-md-4 text-bold">Select Payment Mode</label>
                                    <div class="col-md-6 has-success pad-btm">
                                        <select name="payment_mode" id="payment_mode" class="form-control" onchange="show_hide_cheque_details(this.value)" required>
                                            <option value="">Select</option>
                                            <option value="CASH">CASH</option>
                                            <option value="CHEQUE">CHEQUE</option>
                                            <option value="DD">DD</option>
                                            <option value="NEFT">NEFT</option>
                                            <option value="RTGS">RTGS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="chq_dtls1" style="display: none;">
                                    <div class="col-md-6 has-success pad-btm">
                                        <label class="col-md-12 text-bold">Cheque/DD No.</label>
                                        <input type="text" name="cheque_no" id="cheque_no" class="form-control" onkeypress="return isAlphaNum(event);" placeholder="Enter Cheque No.">
                                    </div>
                                    <div class="col-md-6 has-success pad-btm">
                                        <label class="col-md-12 text-bold" for="timeslot2">Cheque/DD Date</label>
                                        <input type="date" name="cheque_date" id="cheque_date" class="form-control">
                                    </div>
                                </div>
                                <div class="row" id="chq_dtls2" style="display: none;">
                                    <div class="col-md-6 has-success pad-btm">
                                        <label class="col-md-12 text-bold" for="timeslot3">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control"  onkeypress="return isAlpha(event);" placeholder="Enter Bank Name">
                                    </div>
                                    <div class="col-md-6 has-success pad-btm">
                                        <label class="col-md-12 text-bold" for="timeslot4">Branch Name</label>
                                        <input type="text" name="branch_name" id="branch_name" class="form-control" onkeypress="return isAlpha(event);" placeholder="Enter Branch Name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 has-success pad-btm">
                                        <label class="col-md-12 text-bold" for="timeslot3">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" onkeypress="return isAlphaNum(event);"></textarea>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-primary btn-labeled" style="text-align:center;" id="btn" name="btn" value="Pay"/>
                                    </div>                                    
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- ==================== -->
            <div id="forward_backward_model" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #25476a;">
                            <button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="color: white;"><span id='action_title'> </span> <?=$request_dtl['request_no'];?></h4>
                        </div>
                        <form id ="post_next_form" action="<?=base_url('WaterConsumerRequest/postNextLevel/'.md5($request_dtl["id"])); ?>" method="post">
                            <div class="modal-body">                               
                                <div class="row">
                                    <input type="hidden" class="form-control" id="action_type" name="action_type" value="<?=$request_dtl["request_type"];?>">
                                    <input type="hidden" class="form-control" id="views" name="views" value="<?=$from??"outBox";?>">
                                    <div class="col-md-2 has-success pad-btm">
                                        
                                    </div>
                                    <div class="col-md-8 has-success pad-btm">
                                        <label class="col-md-12 text-bold" for="timeslot3">Remarks</label>
                                        <textarea class="form-control" name="level_remarks" id="level_remarks" onkeypress="return isAlphaNum(event);"></textarea>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-primary btn-labeled" style="text-align:center;" id="action_btn" name="action_btn" value="Pay"/>
                                    </div>                                    
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- =================== -->
            
            <!-- end models -->
        </div>
           
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
    function show_hide_cheque_details(argument){
        var payment_mode=argument;
        if(payment_mode!='CASH' && argument!="")
        {
            $("#chq_dtls1").show();
            $("#chq_dtls2").show();
        }
        else
        {
            $("#chq_dtls1").hide();
            $("#chq_dtls2").hide();
        }

    }

    function setModel(str=''){
        $("#action_title").html(str);        
        $("#action_type").val(str);
        $("#action_btn").val(str);
        
    }

    $("document").ready(function(){
        $('#payment_form').validate({
            rules: {
                month: {
                    required: true,
                },
                amount: {
                    required: true,
                
                },
                payment_mode: {
                    required: true,
                
                },
                remarks: {
                    required: true,
                
                },
                cheque_no: {
                    required: true,
                
                },
                cheque_date: {
                    required: true,
                
                },
                bank_name: {
                    required: true,
                
                },
                branch_name: {
                    required: true,
                    
                }
            },

            submitHandler: function(form) {
                if(confirm("Are sure want to make payment?")){
                    $("#btn").hide();
                    return true;
                    $("#loadingDiv").show()
                }
                else
                {
                    return false;
                }
            }
        });
        $('#post_next_form').validate({
            rules: {                
                level_remarks: {
                    required: true,           
                },
            },

            submitHandler: function(form) {
                str = $("#action_type").val();
                if(confirm("Are sure want to make "+str.toLowerCase()+" ?")){
                    $("#loadingDiv").show()
                    $("#action_btn").hide();
                    return true;
                }
                else
                {
                    return false;
                }
            }
        });
        // $('#form').validate({ 
        //     debugger:true,
        //     rules: {
        //         "request_type":"required",
        //         "doc": {
        //             required: true,
        //             extension: "png|jpg|jpeg|pdf", // Specify allowed file extensions
        //             filesize_max: 2097152 // Specify maximum file size (2MB)
        //         },              

        //         "owner_name[]": {
        //             "required":true,
        //         },
        //         "mobile_no[]": {
        //             "required": true,
        //             "digits": true,
        //             "minlength": 10,
        //             "maxlength": 10,
        //         },
                
        //         // "guardian_name[]":{
        //         //     "required":true,
        //         // },
        //         // "email_id[]":{
        //         //     "required":true,
        //         // },
        //         messages: {
        //             "doc": {
        //                 extension: "Please select a file with a valid extension (png, jpg, jpeg, gif)",
        //                 filesize_max: "File size exceeds 2MB limit"
        //             }
        //         }
                
            
            
        //     }
        // });

        // Custom method to check file size
        // $.validator.addMethod('filesize_max', function(value, element, param) {
        //     var fileSize = element.files[0].size; // Size in bytes
        //     return fileSize <= param;
        // }, 'File size must be less than {0} bytes.');

        // // Custom method to check file extension
        // $.validator.addMethod("extension", function(value, element, param) {
        //     param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpg|jpeg|pdf";
        //     return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
        // }, "Please specify a valid file format[png,jpg,jpeg,pdf].");

    });
</script>

<?php echo $this->include('layout_vertical/footer');?>