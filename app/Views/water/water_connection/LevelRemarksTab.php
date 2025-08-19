<?php 
$da = [];
$je=$eo=$sh=$ae =[];

foreach($getremarks as $val)
{
    if($val['receiver_user_type_id']==12)
    $da[]=$val;
    if($val['receiver_user_type_id']==13)
    $je[]=$val;
    if($val['receiver_user_type_id']==14)
    $sh[]=$val;
    if($val['receiver_user_type_id']==15)
    $ae[]=$val;
    if($val['receiver_user_type_id']==16)
    $eo[]=$val;
}

?>
<div class="panel panel-bordered panel-dark">
    <div class="panel-heading">
        <h3 class="panel-title"> Level Remarks Of Application</h3>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs"  style="font-size:13px;font-weight: bold;">
        <?php 
            //foreach($getremarks as $val)
            {
                // Water Dealing Assistant
                //if($val['receiver_user_type_id']==12)
                {
                    ?>
                    <li class="active" style="background-color:#3a444e;"><a data-toggle="tab" href="#Dealing_Officer ">Dealing Officer </a></li>
                    <?php
                }

                // Water Junior Engineer
                //else if($val['receiver_user_type_id']==13)
                {
                    ?>
                    <li style="background-color:#3a444e;"><a data-toggle="tab" href="#Junior_Engineer">Junior Engineer</a></li>
                    <?php
                }

                // Water Section Head
                //else if($val['receiver_user_type_id']==14)
                {
                    ?>
                    <li style="background-color:#3a444e;"><a data-toggle="tab" href="#Section_Head">Section Head</a></li>
                    <?php
                }

                // Water Assistant Engineer
                //else if($val['receiver_user_type_id']==15)
                {
                    ?>
                    <li style="background-color:#3a444e;"><a data-toggle="tab" href="#Assistant_Engineer">Assistant Engineer</a></li>
                    <?php
                }

                // Water Executive Officer
                //else if($val['receiver_user_type_id']==16)
                {
                    ?>
                    <li style="background-color:#3a444e;"><a data-toggle="tab" href="#Executive_Engineer">Executive Engineer</a></li>
                    <?php
                }
                    
            }
            ?>
        </ul>

         <!--<div class="tab-content">
            
            <?php 
                foreach($getremarks as $val)
                {
                   
                    ?>
                    <div id="Dealing_Officer" class="tab-pane fade in active">
                        <?php
                        if($val['receiver_user_type_id']==12)
                        {
                            ?>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Received On : </b> </div>
                                <div class="col-sm-3"> <?=$val['created_on'];?> </div>
                                <div class="col-sm-3 text text-success"> <b> Forwaded On : </b> </div>
                                <div class="col-sm-3 text text-success"> <?=$val["forward_date"];?> <?=$val["forward_time"];?></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Remark : </b> </div>
                                <div class="col-sm-3"> <?=$val['remarks'];?> </div>
                                <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                            </div>
                            
                            <?php
                        }
                        ?>
                    </div>

                    <div id="Section_Head" class="tab-pane fade">
                        <?php
                        if($val['receiver_user_type_id']==14)
                        {
                            ?>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Received On : </b> </div>
                                <div class="col-sm-3"> <?=$val['created_on'];?> </div>
                                <div class="col-sm-3 text text-success"> <b> Forwaded On : </b> </div>
                                <div class="col-sm-3 text text-success"> <?=$val["forward_date"];?> <?=$val["forward_time"];?></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Remark : </b> </div>
                                <div class="col-sm-3"> <?=$val['remarks'];?> </div>
                                <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div id="Junior_Engineer" class="tab-pane fade">
                        <?php 
                        if($val['receiver_user_type_id']==13)
                        { 
                            ?>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Received On : </b> </div>
                                <div class="col-sm-3"> <?=$val['created_on'];?> </div>
                                <div class="col-sm-3 text text-success"> <b> Forwaded On : </b> </div>
                                <div class="col-sm-3 text text-success"> <?=$val["forward_date"];?> <?=$val["forward_time"];?></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Remark : </b> </div>
                                <div class="col-sm-3"> <?=$val['remarks'];?> </div>
                                <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div id="Assistant_Engineer" class="tab-pane fade">
                        <?php
                        if($val['receiver_user_type_id']==15)
                        {
                            ?>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Received On : </b> </div>
                                <div class="col-sm-3"> <?=$val['created_on'];?> </div>
                                <div class="col-sm-3 text text-success"> <b> Forwaded On : </b> </div>
                                <div class="col-sm-3 text text-success"> <?=$val["forward_date"];?> <?=$val["forward_time"];?></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Remark : </b> </div>
                                <div class="col-sm-3"> <?=$val['remarks'];?> </div>
                                <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?> </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div id="Executive_Engineer" class="tab-pane fade">
                        <?php
                        if($val['receiver_user_type_id']==16)
                        {
                            ?>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Received On : </b> </div>
                                <div class="col-sm-3"> <?=$val['created_on'];?> </div>
                                <div class="col-sm-3 text text-success"> <b> Forwaded On : </b> </div>
                                <div class="col-sm-3 text text-success"> <?=$val["forward_date"];?> <?=$val["forward_time"];?></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3"> <b> Remark : </b> </div>
                                <div class="col-sm-3"> <?=$val['remarks'];?> </div>
                                <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                # endforeach;
            ?>
        </div> -->

        <div class="tab-content">
            <div id="Dealing_Officer" class="tab-pane fade in active">
                <h3></h3>
                <?php
                if (isset($da)) :
                    foreach ($da as $value) :
                ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Received Date</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">Forwarded Date</b>
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ?  date('d-m-Y',strtotime($value['forward_date'])) . ' ' . date('H:i:s',strtotime($value['forward_time'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Remarks</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                    <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div id="Junior_Engineer" class="tab-pane fade">
                <h3></h3>
                <?php
                if (isset($je)) :
                    foreach ($je as $value) :
                ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Received Date</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">Forwarded Date</b>
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ?  date('d-m-Y',strtotime($value['forward_date'])) . ' ' . date('H:i:s',strtotime($value['forward_time'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Remarks</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                    <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div id="Section_Head" class="tab-pane fade">
                <h3></h3>
                <?php
                if (isset($sh)) :
                    foreach ($sh as $value) :
                ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Received Date</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">Forwarded Date</b>
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ?  date('d-m-Y',strtotime($value['forward_date'])) . ' ' . date('H:i:s',strtotime($value['forward_time'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Remarks</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                    <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div id="Assistant_Engineer" class="tab-pane fade">
                <h3></h3>
                <?php
                if (isset($ae)) :
                    foreach ($ae as $value) :
                ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Received Date</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">Forwarded Date</b>
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ?  date('d-m-Y',strtotime($value['forward_date'])) . ' ' . date('H:i:s',strtotime($value['forward_time'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Remarks</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                    <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div id="Executive_Engineer" class="tab-pane fade">
                <h3></h3>
                <?php
                if (isset($eo)) :
                    foreach ($eo as $value) :
                ?>
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Received Date</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['created_on'] != "" ? date('d-m-Y H:i:s', strtotime($value['created_on'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">Forwarded Date</b>
                                    </div>
                                    <div class="col-sm-3 text text-success">
                                        <b style="font-size: 15px;">: <?= $value['forward_date'] != "" ?  date('d-m-Y',strtotime($value['forward_date'])) . ' ' . date('H:i:s',strtotime($value['forward_time'])) : "N/A"; ?></b>
                                        <br />
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">Remarks</b>
                                    </div>
                                    <div class="col-sm-3">
                                        <b style="font-size: 15px;">: <?= $value['remarks'] != "" ? $value['remarks'] : "N/A"; ?></b>
                                        <br />
                                    </div>
                                    <div class="col-sm-3"> <b> Total Duration : </b> </div>
                                    <div class="col-sm-3"> <?=calculate_duration($val['created_on'], trim($val["forward_date"].' '.$val["forward_time"]));?></div>
                                </div>
                            </div>
                        </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>



    </div>
</div>