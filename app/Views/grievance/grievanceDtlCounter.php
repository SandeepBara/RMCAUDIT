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
            <li><a href="#">Grievance</a></li>
            <li><a href="#" class="active">Grievance Detail</a></li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">            
            <div class="panel-heading">
                <h3 class="panel-title"> Grievance Details </h3>
            </div>
            <div class="panel-body"> 
                <?php
                    if($from!="inbox"){
                        ?>
                            <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                                <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;"> Your applied application no. is 
                                    <span style="color: #ff6a00"><?=$app["token_no"];?></span>. 
                                    You can use this application no. for future reference.
                                </span>
                                <br>
                                <br>
                                <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                                    Current Status : <span style="color:#009900"><?=$app["app_status"];?></span>
                                </div>
                            </div>
                        <?php
                    }
                ?>
                <div class="row">
                    <label class="col-md-1" for="name">Citizen Name <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["name"]; ?>
                    </div>

                    <label class="col-md-1" for="mobile">Mobile No <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["mobile_no"]; ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-1" for="grievance_for">Grievance For <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["grievance_for"]; ?>
                    </div>

                    <label class="col-md-1" for="holding_no"><?=$app["app_type"];?> No <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["app_no"]; ?>
                    </div>

                    <label class="col-md-1" for="guardian_name">Ward No <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["ward_no"]; ?>
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-md-1" for="guardian_name">Owner Name <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["owner_name"]; ?>
                    </div>  
                </div>
                <div class="row">
                    <label class="col-md-1" for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["guardian_name"]; ?>
                    </div> 

                    <label class="col-md-1">Address <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["address"]; ?>
                    </div>
                </div>

                <!-- Queries Input -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="queries">Queries <span class="text-danger">*</span></label>
                        <textarea id="queries" name="queries" class="form-control" placeholder="Enter your queries here..." rows="4" readonly><?= ($app["queries"] ?? ""); ?></textarea>
                    </div>
                </div>

                <!-- Attachment View -->
                <div class="col-md-3 attachment-container">
                    <label class="control-label" for="attachment">Attachment:</label>
                    <?php
                        $path = $app['doc_path'];
                        $extention = strtolower(explode('.', $path)[1]);
                        if ($extention == "pdf") {
                            ?>
                                    <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                        <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                    </a>
                                <?php
                        } else {
                        ?>
                            <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                            </a>
                        <?php
                        }
                    ?>
                </div>
            </div>
        </div>

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
                    if($from=="inbox"){
                        if (($permission["can_forward"]??'f')=='t' && ($permission["forward_role_id"]??null) && $fullDocUpload && $fullDocVerify) {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Forward')"> Forward</button>
                            <?php
                        }
                        if (($permission["is_finiser"]??'f')=='t' && $fullDocUpload && $fullDocVerify) {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Approved')"> Close Grievance</button>
                            <?php
                        }
                        if (($permission["can_backward"]??'f')=='t' && ($permission["backword_role_id"]??null)) {
                            ?>
                                <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Backward')"> Backward</button>
                            <?php
                        }
    
                    }
                    elseif($from=="btcList" && $request_dtl["is_parked"]=='t'){
                        if (($permission["can_forward"]??'f')=='t') {
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
            
            <!-- ==================== -->
            <div id="forward_backward_model" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #25476a;">
                            <button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="color: white;"><span id='action_title'> </span> <?=$request_dtl['request_no'];?></h4>
                        </div>
                        <form id ="post_next_form" action="<?=base_url('grievance_new/postNextLevel/'.md5($app["id"])); ?>" method="post">
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
    
    function setModel(str=''){
        $("#action_title").html(str);        
        $("#action_type").val(str);
        $("#action_btn").val(str);
        
    }

    $("document").ready(function(){        
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
        

    });
</script>

<?php echo $this->include('layout_vertical/footer');?>